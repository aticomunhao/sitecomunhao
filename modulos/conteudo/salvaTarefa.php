<?php
session_start(); 
if(!isset($_SESSION["usuarioID"])){
    header("Location: /cesb/index.php");
}
require_once(dirname(dirname(__FILE__))."/dbclass.php");
$Acao = "";
if(isset($_REQUEST["acao"])){
    $Acao = $_REQUEST["acao"]; 
}

if($Acao=="mudaStatus"){
    $Erro = 0;
    if(isset($_REQUEST["numero"])){
        $Num = (int) $_REQUEST["numero"];
        $Ativo = (int) $_REQUEST["guardaativ"]; // Se for 4 vai tornar inativo
    }else{
        $Num = 0;
        $Erro = 1;
    }
    if(isset($_REQUEST["novoStatus"])){
        $Sit = (int) $_REQUEST["novoStatus"];
    }else{
        $Sit = 1;
    }
    if(isset($_REQUEST["usumodif"])){
        $UsuModif = $_REQUEST["usumodif"];
    }else{
        $UsuModif = 0;
    }

    //procura a situação Sit no bd
    $rs0 = mysqli_query($xVai, "SELECT Sit FROM cesb_tarefas WHERE idTar = $Num");
    $tbl = mysqli_fetch_array($rs0);
    $SitOrig = $tbl["Sit"];

    $Erro = 0;
    if($Sit > $SitOrig){ // Não deixa voltar a tarefa
        if($Num > 0){
            $Sql = mysqli_query($xVai, "UPDATE cesb_tarefas SET Sit = $Sit, dataSit".$Sit." = NOW(), UsuModifSit = $UsuModif, Ativo = IF($Sit = 4, 2, $Ativo) WHERE idTar = $Num");
            if(!$Sql){
                $Erro = 1;
            }
        }
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="buscaTarefa"){
    $Erro = 0;
    if(isset($_REQUEST["numero"])){
        $Num = (int) $_REQUEST["numero"];
    }else{
        $Num = 0;
        $Erro = 1;
    }
    if($Num > 0){
        $Sql = mysqli_query($xVai, "SELECT id, nome, cesb_tarefas.usuIns as usuInsId, usuExec, TitTarefa, TextoTarefa, Sit, Prio FROM cesb_tarefas INNER JOIN cesb_usuarios ON cesb_tarefas.usuIns = cesb_usuarios.id WHERE idTar = $Num");
        if(!$Sql){
            $Erro = 1;
            $var = array("coderro"=>$Erro);
        }
        $row = mysqli_num_rows($Sql);
        $tbl = mysqli_fetch_array($Sql);
        $usuIns = $tbl["usuInsId"];
        $Sql1 = mysqli_query($xVai, "SELECT nome FROM cesb_usuarios WHERE id = $usuIns");
        $tbl1 = mysqli_fetch_array($Sql1);
        $NomeUsuIns = $tbl1["nome"];
        $var = array("coderro"=>$Erro, "usuExec"=>$tbl["usuExec"], "usuIns"=>$usuIns, "NomeUsuIns"=>$NomeUsuIns, "TitTarefa"=>$tbl["TitTarefa"], "TextoTarefa"=>$tbl["TextoTarefa"], "Usuario"=>$tbl["nome"], "sit"=>$tbl["Sit"], "priorid"=>$tbl["Prio"]);
    }
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="salvaTarefa"){
    if(isset($_REQUEST["numero"])){
        $idTarefa = (int) filter_input(INPUT_GET, 'numero');
    }else{
        $idTarefa = 0;
        $Erro = 1;
    }
    $usuLogado = (int) filter_input(INPUT_GET, 'usuLogado');
    $usuExec = (int) filter_input(INPUT_GET, 'idExecSelect');
    $textoEvid = filter_input(INPUT_GET, 'textoEvid'); 
    $textoExt = filter_input(INPUT_GET, 'textoExt');
    $Status = filter_input(INPUT_GET, 'selectStatus'); // adminstr pode mudar
    $Priorid = filter_input(INPUT_GET, 'priorid');
    $Erro = 0;
    $row = 0;

    if($idTarefa != 0){
        $Sql = mysqli_query($xVai, "UPDATE cesb_tarefas SET usuExec = $usuExec, TitTarefa = '$textoEvid', TextoTarefa = '$textoExt', Sit = $Status, Prio = $Priorid, Ativo = IF($Status = 4, 2, 1),  UsuModif = $usuLogado, DataModif = NOW() WHERE idTar = $idTarefa"); // or die ("Faltam Parâmetros 0" . mysqli_error($xVai));
        if(!$Sql){
            $Erro = 1;
        }
    }else{  //inserçao de nova tarefa
        $rs0 = mysqli_query($xVai, "SELECT usuExec FROM cesb_tarefas WHERE usuExec = $usuExec And TitTarefa = '$textoEvid'"); // or die ("Faltam Parâmetros 1" . mysqli_error($xVai));
        $row0 = mysqli_num_rows($rs0);
        if($row0 > 0){
            $Erro = 2; // tarefa já foi dada para o mesmo usuário
        }else{
            $Sql = mysqli_query($xVai, "INSERT INTO cesb_tarefas (usuIns, usuExec, TitTarefa, TextoTarefa, DataIns, dataSit1, Sit, Prio) VALUES($usuLogado, $usuExec, '$textoEvid', '$textoExt', NOW(), NOW(), 1, $Priorid)"); // or die ("Faltam Parâmetros" . mysqli_error($xVai));
            if(!$Sql){
                $Erro = 1;
            }
        }
    }
    $var = array("coderro"=>$Erro, "idtarefa"=>$idTarefa);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="deletaTarefa"){
    $Erro = 0;
    if(isset($_REQUEST["numero"])){
        $idTarefa = (int) filter_input(INPUT_GET, 'numero');
        $usuLogado = (int) filter_input(INPUT_GET, 'usuLogado');
    }else{
        $idTarefa = 0;
        $Erro = 1;
    }
    if($idTarefa > 0){
        $Sql = mysqli_query($xVai, "UPDATE cesb_tarefas SET Ativo = 0, DataCancel = NOW(), UsuCancel = $usuLogado WHERE idTar = $idTarefa");
        if(!$Sql){
            $Erro = 1;
        }
    }
    $var = array("coderro"=>$Erro, "idtarefa"=>$idTarefa);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="buscaMsg"){
    $Erro = 0;
    if(isset($_REQUEST["numero"])){
        $idTarefa = (int) filter_input(INPUT_GET, 'numero');
    }else{
        $idTarefa = 0;
        $Erro = 1;
    }
    $Sql0 = mysqli_query($xVai, "SELECT TitTarefa, TextoTarefa FROM cesb_tarefas WHERE idTar = $idTarefa");
    $tbl0 = mysqli_fetch_array($Sql0);

    $Sql1 = mysqli_query($xVai, "SELECT idUser, idTarefa, textoMsg, dataMsg FROM cesb_tarefas LEFT JOIN cesb_tarefas_msg ON cesb_tarefas.idTar = cesb_tarefas_msg.idTarefa WHERE idTarefa = $idTarefa");
    $row1 = mysqli_num_rows($Sql1);
    if($row1 > 0){
        $tbl1 = mysqli_fetch_array($Sql1);
        $var = array("coderro"=>$Erro, "TitTarefa"=>$tbl0["TitTarefa"], "TextoTarefa"=>$tbl0["TextoTarefa"], "dataMsg"=>$tbl1["dataMsg"], "textoMsg"=>$tbl1["textoMsg"]);
    }else{
        $Erro = 2; // nenhuma mensagem
        $var = array("coderro"=>$Erro, "TitTarefa"=>$tbl0["TitTarefa"], "TextoTarefa"=>$tbl0["TextoTarefa"]);
    }
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="salvaMensagem"){
    $Erro = 0;
    if(isset($_REQUEST["numtarefa"])){
        $idTarefa = (int) filter_input(INPUT_GET, 'numtarefa');
        $idLogado = (int) filter_input(INPUT_GET, 'numusuario');
        $NomeLogado = filter_input(INPUT_GET, 'nomeusuario'); // para salvar em arquivo
        $textoExt = filter_input(INPUT_GET, 'textoExt');
    }else{
        $idTarefa = 0;
        $Erro = 1;
    }
    if($idTarefa > 0){
        $rs0 = mysqli_query($xVai, "SELECT usuIns, usuExec FROM cesb_tarefas WHERE idTar = $idTarefa");
        $tbl0 = mysqli_fetch_array($rs0);
        $UsuIns = $tbl0["usuIns"];
        $UsuExec = $tbl0["usuExec"];
        if($UsuIns == $idLogado){
            $Campo = "insLido";
        }else{
            $Campo = "execLido";
        }


//        $Sql = mysqli_query($xVai, "INSERT INTO cesb_tarefas_msg (idUser, idTarefa, TextoMsg, DataMsg, Tarefa_Ativ, Tarefa_Lida, Leitura) VALUES($idLogado, $idTarefa, '$textoExt', NOW(), 1, 0, CONCAT(NOW(), ' - ', '$NomeLogado', ' - Inserção', '\n'))");
        $Sql = mysqli_query($xVai, "INSERT INTO cesb_tarefas_msg (idUser, idTarefa, usuInsTar, usuExecTar, TextoMsg, DataMsg, $Campo) 
        VALUES($idLogado, $idTarefa, $UsuIns, $UsuExec, '$textoExt', NOW(), 1)");
        if(!$Sql){
            $Erro = 1;
        }
    }
    $var = array("coderro"=>$Erro, "idtarefa"=>$idTarefa);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="marcalidas"){
    $Erro = 0;
    if(isset($_REQUEST["numtarefa"])){
        $idTarefa = (int) filter_input(INPUT_GET, 'numtarefa');
        $NomeLogado = filter_input(INPUT_GET, 'nomeusuario'); // para salvar em arquivo
    }else{
        $idTarefa = 0;
        $Erro = 1;
    }
    if($idTarefa > 0){
//        $Sql = mysqli_query($xVai, "UPDATE cesb_tarefas_msg SET Tarefa_Lida = 1, Leitura = CONCAT(Leitura, NOW(), ' - ', '$NomeLogado', '\n') WHERE IdTarefa = $idTarefa");
        $Sql = mysqli_query($xVai, "UPDATE cesb_tarefas_msg SET Tarefa_Lida = 1 WHERE IdTarefa = $idTarefa");
        // And idUser = $idLogado
        if(!$Sql){
            $Erro = 1;
        }
    }
    $var = array("coderro"=>$Erro, "idtarefa"=>$idTarefa);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="temTarefa"){
    $Erro = 0;
    $row0 = 0;
    $Sql0 = mysqli_query($xVai, "SELECT idTar, Sit FROM cesb_tarefas WHERE usuExec = '".$_SESSION["UsuLogado"]."' And Sit = 1");
    if(!$Sql0){
        $Erro = 1;
    }else{
        $row0 = mysqli_num_rows($Sql0);
    }
    if($row0 > 0){
        if($row0 == 1){
            $msg = "Tarefa expedida para ". $_SESSION["UsuLogadoNome"].".<br> Clique em <u style='cursor: pointer;'>Tarefas</u> para verificar.";
        }else{
            $msg = $row0." tarefas expedidas para ". $_SESSION["UsuLogadoNome"].".<br> Clique em <u style='cursor: pointer;'>Tarefas</u> para verificar.";
        }
    }else{
        $msg = "";
    }
    $var = array("coderro"=>$Erro, "tem"=>$row0, "msg"=>$msg);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="apagaMensagem"){
    $Cod = (int) filter_input(INPUT_GET, 'numMsg');
    $Erro = 0;
    $Sql = mysqli_query($xVai, "UPDATE cesb_tarefas_msg SET Elim = 1, dataElim = NOW() WHERE idMsg = $Cod");
    if(!$Sql){
        $Erro = 1;
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}