<?php
session_start(); // inicia uma sessão
if(!isset($_SESSION["usuarioID"])){
    header("Location: /cesb/index.php");
}
require_once("../dbclass.php");
if(isset($_REQUEST["acao"])){
    $Acao = $_REQUEST["acao"];
}

if($Acao =="log"){
    $Usu = filter_input(INPUT_GET, 'usuario'); 
    $Sen = filter_input(INPUT_GET, 'senha');
    $Erro = 0;
    $Erro_Msg = "";
    $Usuario = removeInj($Usu);
    $Senha = removeInj($Sen);
    $Senha = MD5($Senha);
    $_SESSION['Conectado'] = "";
    $id = 0; // vem do postgre
    $LogPostgre = 1; // 1 - logar com o PostgreSQL

    require_once("dbcclass.php");
//    $Conec = conecPost(); // habilitar a extensão: extension=pgsql no phpini
    $Conec = "Foo"; // força conex mysql

    if($Conec != "Foo"){
        $_SESSION['Conectado'] = "PostGres"; // loginno postgres
//        $_SESSION['ConecP'] = $Conec; 
        $result = pg_query($Conec, "SELECT id, cpf, nome_completo, TO_CHAR(dt_nascimento, 'DD/MM/YYYY'), TO_CHAR(dt_nascimento, 'DD'), TO_CHAR(dt_nascimento, 'MM') FROM pessoas WHERE cpf = '$Usuario' And senha = '$Senha' ");
        $Achou = pg_num_rows($result);
        if($Achou == 1){
            $Sql = pg_fetch_row($result);
            $id = $Sql[0];
            $NomeCompl = $Sql[2];
            $Nome = substr($NomeCompl, 0, 30); // para o campo nome em mysql - ver se tem no postgre
            $DNasc = $Sql[3];
            $DiaAniv = $Sql[4];
            $MesAniv = $Sql[5];

            $CodSetor = 1; // ver se tem no postgre
            $Adm = 2; // ver se tem no postgre
            $Sexo = 1; // ver se tem no postgre

            if($id > 0){ // está no bd postgre
                // inserir coluna idPessoa na tabela cesb_usuários para identificar o usuário que logar
                $rs0 = mysqli_query($xVai, "SELECT idPessoa FROM cesb_usuarios WHERE idPessoa = $id");
                $row0 = mysqli_num_rows($rs0);
                if($row0 == 0){ // não tem em cesb_usuarios 
                    // depois de efetivado parar de inserir senha nessa tabela
                    mysqli_query($xVai, "INSERT INTO cesb_usuarios (usuario, nomeCompl, idPessoa, senha, CodSetor, Sexo, adm, dataIns, usuIns, mesAniv, diaAniv, dataModif, dataInat) 
                    VALUES('$Usuario', '$NomeCompl', $id, '$Senha', $CodSetor, $Sexo, $Adm, NOW(), 0, '$MesAniv', '$DiaAniv', '3000-12-31', '3000-12-31')");
                    mysqli_query($xVai, "UPDATE cesb_usuarios SET nome = '$Nome' WHERE idPessoa = $id And nome = '' or IsNull(nome)");
                    $rs1 = mysqli_query($xVai, "SELECT id FROM cesb_usuarios WHERE idPessoa = $id");
                    $Proc1 = mysqli_fetch_array($rs1);
                    $idUsu = $Proc1["id"]; // id no bd mysql
                }else{ // já tem
                    $rs0 = mysqli_query($xVai, "UPDATE cesb_usuarios SET usuario = '$Usuario', senha = '$Senha' WHERE idPessoa = $id");
                    $rs1 = mysqli_query($xVai, "SELECT id FROM cesb_usuarios WHERE idPessoa = $id");
                    $Proc1 = mysqli_fetch_array($rs1);
                    $idUsu = $Proc1["id"]; // id no bd mysql
                }                    
            }
        }else{ // usuário não encontrado  no postgre
            $Erro = 6;
            $Erro_Msg = "Usuário ou senha não conferem.";
            $var = array("coderro"=>$Erro, "msg"=>$Erro_Msg);
            $responseText = json_encode($var);
            echo $responseText;
            return;
        }
    }else{ // sem contato com o postgre
        $_SESSION['Conectado'] = "MySql"; //login no mysql
        $rs = mysqli_query($xVai, "SELECT id FROM cesb_usuarios WHERE usuario = '$Usuario' And senha = '$Senha'") or die('Tabelas ausentes');
        $Achou = mysqli_num_rows($rs);
        if($Achou == 0){
            $Erro = 1;
            $Erro_Msg = "Usuário ou senha não conferem.";
            $var = array("coderro"=>$Erro, "msg"=>$Erro_Msg);
            if(!preg_match('/^[a-zA-Z0-9_]+$/', $Usu)){
                $Erro = 2;
                $Erro_Msg = "Caracter inválido no nome de usuário.";
                $var = array("coderro"=>$Erro, "msg"=>$Erro_Msg);
            }
            $responseText = json_encode($var);
            echo $responseText;
            return;
        }else{
            $Procid = mysqli_fetch_array($rs);
            $idUsu = $Procid["id"];
        }
    }

    if($Achou == 1){ // postgre ou mysql
        $Erro = 0;
        $Erro_Msg = "";
//        $rs = mysqli_query($xVai, "SELECT id, usuario, nome, nomecompl, adm, CodSetor, CodSubSetor, Ativo FROM cesb_usuarios WHERE usuario = '$Usuario' And senha = '$Senha'") or die('Tabelas ausentes');
        $rs = mysqli_query($xVai, "SELECT id, usuario, nome, nomecompl, adm, CodSetor, CodSubSetor, Ativo FROM cesb_usuarios WHERE id = $idUsu") or die('Tabelas ausentes');
        $Proc = mysqli_fetch_array($rs);
        $Adm = $Proc["adm"];
        if(is_null($Proc["adm"])){
            $Adm = 0;
        }
        $_SESSION['start_login'] = time();
        $_SESSION['Conect'] = $xVai;
        $_SESSION["UsuLogado"] = "";
        $_SESSION["usuarioID"] = (int) $Proc["id"];
        $_SESSION["UsuLogado"] = $Proc["usuario"];
        $_SESSION["UsuLogadoNome"] = $Proc["nome"];
        $_SESSION["NomeCompl"] = $Proc["nomecompl"];
        $_SESSION["AdmUsu"] = $Adm;  //$Proc["adm"];
        $_SESSION["msg"] = ""; //para troca de slides e tráfego de arquivos
        $_SESSION['msgarq'] = ""; //para upload arquivos diretorias/assessorias
        $_SESSION['geremsg'] = 0;
        $_SESSION['gerenum'] = 0;
        $_SESSION['arquivo'] = "";
        $_SESSION["CodSetorUsu"] = $Proc["CodSetor"]; // Guarda o setor de trabalho do usuário
        $_SESSION["CodSubSetorUsu"] = $Proc["CodSubSetor"];
        $DescSetor = "Sistema";
        $rs0 = mysqli_query($xVai, "SELECT SiglaSetor FROM cesb_usuarios INNER JOIN cesb_setores ON cesb_usuarios.CodSetor = cesb_setores.CodSet WHERE cesb_usuarios.id = ".$_SESSION["usuarioID"]." And CodSubSetor = 1 ");
        $row0 = mysqli_num_rows($rs0);
        if($row0 > 0){ // não é de setor, então procura o subsetor
            $Proc0 = mysqli_fetch_array($rs0);
            $DescSetor = $Proc0["SiglaSetor"];
            if($DescSetor == ""){
                $DescSetor = "n/d";
            }
        }else{   // Seleciona o subsetor do usuário
            $rs0 = mysqli_query($xVai, "SELECT SiglaSubSetor FROM cesb_usuarios INNER JOIN cesb_subsetores ON cesb_usuarios.CodSubSetor = cesb_subsetores.CodSubSet WHERE cesb_usuarios.id = ".$_SESSION["usuarioID"]." And CodSubSetor > 1 ");
            $Proc0 = mysqli_fetch_array($rs0);
            $DescSetor = $Proc0["SiglaSubSetor"];               
        }
        $_SESSION["DescSetor"] = $DescSetor;
        if($Sen == "123456789"){
            $Erro = 5; // inserir nova senha
        }
        mysqli_query($xVai, "UPDATE cesb_usuarios SET NumAcessos = (NumAcessos + 1), ultlog = NOW() WHERE id = ".$_SESSION['usuarioID']);  // or die ("Faltam Parâmetros" . mysqli_error($xVai));
        $var = array("coderro"=>$Erro, "msg"=>$Erro_Msg, "usuarioid"=>$Proc["id"], "usuarioNome"=>$Proc["nome"], "usuarioAdm"=>$Proc["adm"], "usuario"=>$_SESSION["UsuLogado"]); 

        
//buscar na tabela de usuários do Postgre
        //Parâmetros do sistema
        $rsSis = mysqli_query($xVai, "SELECT admVisu, admCad, admEdit FROM cesb_paramsis WHERE idPar = 1");
        $ProcSis = mysqli_fetch_array($rsSis);
        $_SESSION["AdmVisu"] = $ProcSis["admVisu"]; // administrador visualiza usuários
        $_SESSION["AdmCad"] = $ProcSis["admCad"];   // administrador cadastra usuários
        $_SESSION["AdmEdit"] = $ProcSis["admEdit"]; // administrador edita usuários
    }

    if($Achou > 1){
        $Erro = 4;
        $Erro_Msg = "En contramos um problema no cadastro. Infome à ATI.";
        $var = array("coderro"=>$Erro, "msg"=>$Erro_Msg);
    }

    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="buscaacesso"){
    $Erro = 0;
    $NumAcessos = 0;
    $msg = "";
    $Marca = 0;
    $rsAc = mysqli_query($xVai, "SELECT NumAcessos FROM cesb_usuarios WHERE id = ".$_SESSION['usuarioID']);
    if(!$rsAc){
        $Erro = 1;
    }else{
        $ProcAc = mysqli_fetch_array($rsAc);
        $NumAcessos = $ProcAc["NumAcessos"];
        $msg = "Este é seu acesso nº $NumAcessos";
        if($NumAcessos < 500){ // abaixo de 500
            if($NumAcessos % 100 === 0){ // a cada 100 acessos vai aparecer a caixa comemorativa
                mysqli_query($xVai, "UPDATE cesb_usuarios SET NumAcessos = (NumAcessos + 1) WHERE id = ".$_SESSION['usuarioID']); // soma 1 para evitar continuar a comemoração no mesmo login
                $Marca = 1;
            }
        }else{ //se for acima de 500
            if($NumAcessos % 500 === 0){
                mysqli_query($xVai, "UPDATE cesb_usuarios SET NumAcessos = (NumAcessos + 1) WHERE id = ".$_SESSION['usuarioID']); // soma 1 para evitar continuar a comemoração no mesmo login
                $Marca = 1;
            }
        }
    }    
    $var = array("coderro"=>$Erro, "marca"=>$Marca, "acessos"=>$NumAcessos, "msg"=>$msg);
    $responseText = json_encode($var);
    echo $responseText;
}

if($Acao =="buscausu"){
    $Usu = (int) filter_input(INPUT_GET, 'numero'); 
    $Erro = 0;
    $rs = mysqli_query($xVai, "SELECT id, usuario, nome, nomecompl, adm, CodSetor, CodSubSetor, Ativo, diaAniv, mesAniv, date_format(ultlog, '%d/%m/%Y') as ultlogF, NumAcessos 
    FROM cesb_usuarios 
    WHERE id = $Usu") or die('Tabelas ausentes: '.mysqli_error($xVai));
    $row = mysqli_num_rows($rs);
    if($row == 0){
        $Erro = 1;
        $var = array("coderro"=>$Erro);
    }else{
        $Proc = mysqli_fetch_array($rs);
        if(!is_null($Proc["ultlogF"])){
            $UltLog = $Proc["ultlogF"];
        }else{
            $UltLog = "31/12/3000";
        }
        $var = array("coderro"=>$Erro, "usuario"=>$Proc["usuario"], "usuarioNome"=>$Proc["nome"], "nomecompl"=>$Proc["nomecompl"], "setor"=>$Proc["CodSetor"], "usuarioAdm"=>$Proc["adm"], "ativo"=>$Proc["Ativo"], "ultlog"=>$Proc["ultlogF"], "acessos"=>$Proc["NumAcessos"], "diaAniv"=>$Proc["diaAniv"], "mesAniv"=>$Proc["mesAniv"]);
    }
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="resetsenha"){
    $Usu = (int) filter_input(INPUT_GET, 'numero'); 
    $Erro = 0;
    $Senha = MD5("123456789");
    $rs = mysqli_query($xVai, "UPDATE cesb_usuarios SET senha = '$Senha' WHERE id = $Usu");
    if(!$rs){
        $Erro = 1;
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="deletausu"){
    $Usu = (int) filter_input(INPUT_GET, 'numero'); 
    $Erro = 0;
    $rs = mysqli_query($xVai, "DELETE FROM cesb_usuarios WHERE id = $Usu") or die ("Deleção1: " . mysqli_error($xVai));
    if(!$rs){
        $Erro = 1;
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}

if($Acao =="salvaUsu"){
    $Usu = (int) filter_input(INPUT_GET, 'numero');
    $UsuLogado = (int) filter_input(INPUT_GET, 'usulogado');
    $UsuLogin = filter_input(INPUT_GET, 'usulogin');
    $UsuNome = filter_input(INPUT_GET, 'usuarioNome');
    $NomeCompl = filter_input(INPUT_GET, 'nomecompl');
    $Setor = (int) filter_input(INPUT_GET, 'setor');
    $Adm = (int) filter_input(INPUT_GET, 'flAdm');
    $DiaAniv = filter_input(INPUT_GET, 'diaAniv');
    $MesAniv = filter_input(INPUT_GET, 'mesAniv');
    $Erro = 0;
    if($Usu > 0){  // salvar
        $rs = mysqli_query($xVai, "UPDATE cesb_usuarios SET usuario = '$UsuLogin', nome = '$UsuNome', nomecompl = '$NomeCompl', CodSetor = $Setor, adm = $Adm, usuModif = $UsuLogado, dataModif = NOW() WHERE id = $Usu");
        if(!$rs){
            $Erro = 1;
        }else{ // inserir no arquivo de aniversariantes
            if($DiaAniv != "" && $MesAniv != ""){
                $rs1 = mysqli_query($xVai, "SELECT UsuCod FROM cesb_anivers WHERE UsuCod = $Usu");
                $row1 = mysqli_num_rows($rs1);
                if($row1 == 0){
                    mysqli_query($xVai, "INSERT INTO cesb_anivers (nomeUsu, nomeCompl, diaAniv, mesAniv, UsuCod, dataIns, usuIns) VALUES('$UsuNome', '$NomeCompl', '$DiaAniv', '$MesAniv', $Usu, NOW(), '$UsuLogado')");
                }
            }
        }
    }
    if($Usu == 0){ // cadastrar
        $Sen = MD5("123456789");
        $rs = mysqli_query($xVai, "INSERT INTO cesb_usuarios (usuario, nome, nomecompl, CodSetor, senha, adm, usuIns, dataIns) 
        VALUES ('$UsuLogin', '$UsuNome', '$NomeCompl', $Setor, '$Sen', $Adm, $UsuLogado, NOW()) ");
        if(!$rs){
            $Erro = 1;
        }else{
            if($DiaAniv != "" && $MesAniv != ""){
                $CodigoNovo= mysqli_insert_id($xVai); // obtem o número AUTO_INCREMENT da operação INSERT
                mysqli_query($xVai, "INSERT INTO cesb_anivers (nomeUsu, nomeCompl, diaAniv, mesAniv, UsuCod, dataIns, usuIns) VALUES('$UsuNome', '$NomeCompl', '$DiaAniv', '$MesAniv', $CodigoNovo, NOW(), '$UsuLogado')");
            }
        }
    }
    $var = array("coderro"=>$Erro, "usuario"=>$Usu);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="checaLogin"){
    $Valor = filter_input(INPUT_GET, 'valor');
    $Erro = 0;
    $row = 0;
    $NomeCompl = "";
    $rs = mysqli_query($xVai, "SELECT nomeCompl FROM cesb_usuarios WHERE usuario = '$Valor'");
    if(!$rs){
        $Erro = 1;
    }else{
        $row = mysqli_num_rows($rs);
        if($row > 0){
            $Proc= mysqli_fetch_array($rs);
            $NomeCompl = $Proc["nomeCompl"];
        }
    }
    $var = array("coderro"=>$Erro, "quantiUsu"=>$row, "nomecompl"=>$NomeCompl);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="confsenhaant"){
    $Usu = $_SESSION["usuarioID"];
    $Valor = filter_input(INPUT_GET, 'valor');
    $Valor = removeInj($Valor);
    $Sen = MD5($Valor);
    $Erro = 0;
    $row = 0;

    $rs = mysqli_query($xVai, "SELECT id FROM cesb_usuarios WHERE id = $Usu And senha = '$Sen'");
    if(!$rs){
        $Erro = 1;
    }else{
        $row = mysqli_num_rows($rs);
    }
    $var = array("coderro"=>$Erro, "row"=>$row);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="salvaAtiv"){
    $Usu = (int) filter_input(INPUT_GET, 'numero');
    $UsuLogado = (int) filter_input(INPUT_GET, 'usulogado');
    $Valor = (int) filter_input(INPUT_GET, 'valor');
    $Erro = 0;
    $rs = mysqli_query($xVai, "UPDATE cesb_usuarios SET Ativo = $Valor, usuModif = $UsuLogado, dataModif = NOW() WHERE id = $Usu");
    if(!$rs){
        $Erro = 1;
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="trocasenha"){
    $Usu = $_SESSION["usuarioID"];
    $Sen = filter_input(INPUT_GET, 'novasenha');
    $Repet = filter_input(INPUT_GET, 'repetsenha');
  
    $Erro = 0;
    if($Sen != $Repet){
        $Erro = 1;
    }
    if($Sen == "1234567890" || $Sen == "123456789" || $Sen == "12345678" || $Sen == "1234567" || $Sen == "123456" || $Sen == "0987654321" || $Sen == "987654321" || $Sen == "87654321" || $Sen == "7654321" || $Sen == "654321"){
        $Erro = 2;
    }
    if(strlen($Sen) < 6){
        $Erro = 5;
    }
    if($Sen == "" || is_null($Sen)){
        $Erro = 3;
    }
    if($Erro == 0){
        $Senha = removeInj($Sen);
        $Senha = MD5($Senha);
        $rs = mysqli_query($xVai, "UPDATE cesb_usuarios SET senha = '$Senha', dataModif = NOW() WHERE id = $Usu");
        if(!$rs){
            $Erro = 4;
        }
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="mudasenha"){
    $Usu = $_SESSION["usuarioID"];
    $SenAnt = filter_input(INPUT_GET, 'senhaant'); // já foi checada no preenchimento
    $Sen = filter_input(INPUT_GET, 'novasenha');
    $Repet = filter_input(INPUT_GET, 'repetsenha');
    $Erro = 0;
    $Busca = str_split($Sen, 1);
    $Seq = $Busca[0];

    if($Sen != $Repet){
        $Erro = 1;
    }
    if($Sen == "1234567890" || $Sen == "123456789" || $Sen == "12345678" || $Sen == "1234567" || $Sen == "123456" || $Sen == "0987654321" || $Sen == "987654321" || $Sen == "87654321" || $Sen == "7654321" || $Sen == "654321"){
        $Erro = 2;
    }
    if(strlen($Sen) < 6){
        $Erro = 5;
    }
    if($Sen == "" || is_null($Sen)){
        $Erro = 3;
    }
    if($Erro == 0){
        $Senha = removeInj($Sen);
        $Senha = MD5($Senha);
        $rs = mysqli_query($xVai, "UPDATE cesb_usuarios SET senha = '$Senha', usuModif = $Usu, dataModif = NOW() WHERE id = $Usu");
        if(!$rs){
            $Erro = 4;
        }
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="salvaAdm"){
    $Valor = (int) filter_input(INPUT_GET, 'valor');
    $Caixa = filter_input(INPUT_GET, 'caixa');
    $Erro = 0;
    $rs = mysqli_query($xVai, "UPDATE cesb_paramsis SET $Caixa = $Valor WHERE idPar = 1"); // nome da coluna é o nome da variável
    if(!$rs){
        $Erro = 1;
    }
    $_SESSION[$Caixa] = $Valor;

    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}

if($Acao =="salvaParam"){
    $Campo = filter_input(INPUT_GET, 'param');
    $Valor = (int) filter_input(INPUT_GET, 'valor');
    $Erro = 0;
    $rs = mysqli_query($xVai, "UPDATE cesb_paramsis SET $Campo = $Valor WHERE idPar = 1");
    if(!$rs){
        $Erro = 1;
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}



function removeInj($VemDePost){  // função para remover injeções SQL
    $VemDePost = addslashes($VemDePost);
    $VemDePost = htmlspecialchars($VemDePost);
    $VemDePost = strip_tags($VemDePost);
    $VemDePost = str_replace("SELECT","",$VemDePost);
    $VemDePost = str_replace("FROM","",$VemDePost);
    $VemDePost = str_replace("WHERE","",$VemDePost);
    $VemDePost = str_replace("INSERT","",$VemDePost);
    $VemDePost = str_replace("UPDATE","",$VemDePost);
    $VemDePost = str_replace("DELETE","",$VemDePost);
    $VemDePost = str_replace("DROP","",$VemDePost);
    $VemDePost = str_replace("DATABASE","",$VemDePost);
    return $VemDePost; 
 }