<?php
require_once("dbclass.php");
if(isset($_REQUEST["acao"])){
    $Acao = $_REQUEST["acao"];
    $Tipo = (int) $_REQUEST["tipo"];
}

if($Tipo == 1){
    if($Acao =="buscaRamal"){
        $Num = (int) filter_input(INPUT_GET, 'numero'); // id
        $Erro = 0;
        $row = 0;
        $Sql = mysqli_query($xVai, "SELECT nomeusu, nomecompl, setor, ramal FROM cesb_ramais_int WHERE CodTel = $Num");
        if(!$Sql){
           $Erro = 1;
        }else{
            $row = mysqli_num_rows($Sql);
            $Proc = mysqli_fetch_array($Sql);
        }
        $var = array("coderro"=>$Erro, "usuario"=>$Proc["nomeusu"], "nomecompl"=>$Proc["nomecompl"], "setor"=>$Proc["setor"], "ramal"=>$Proc["ramal"]);
        $responseText = json_encode($var);
        echo $responseText;
    }
    if($Acao =="salvaRamal"){
        $id = (int) filter_input(INPUT_GET, 'numero');
        $Nome = filter_input(INPUT_GET, 'usuario');  //$_REQUEST["nome"];
        $NomeCompl = filter_input(INPUT_GET, 'nomecompl');
        $Setor = filter_input(INPUT_GET, 'setor');
        $Ramal = filter_input(INPUT_GET, 'ramal');
        $UsuLogado = $_SESSION["usuarioID"]; //$_REQUEST["usulogado"];
        $Erro = 0;
        $row = 0;
        if($id !== 0){ // edição
            $Sql = mysqli_query($xVai, "UPDATE cesb_ramais_int SET nomeusu = '$Nome', nomecompl = '$NomeCompl', setor = '$Setor', ramal = '$Ramal', usumodif = $UsuLogado, datamodif = NOW() WHERE CodTel = $id");
            if(!$Sql){
                $Erro = 1;
            }
        }else{ // inserção
            $rs = mysqli_query($xVai, "SELECT nomeusu, nomecompl, setor, ramal FROM cesb_ramais_int WHERE nomeusu = '$Nome' And nomecompl = '$NomeCompl'");
            $row = mysqli_num_rows($rs);
            if($row > 0){
//                $Proc = mysqli_fetch_array($rs);
                $Erro = 2; // nome já existe
            }else{
                $Sql = mysqli_query($xVai, "INSERT INTO cesb_ramais_int (nomeusu, nomecompl, setor, ramal, datains, usuins) 
                VALUES('$Nome', '$NomeCompl', '$Setor', '$Ramal', NOW(), $UsuLogado)");
                if(!$Sql){
                    $Erro = 1;
                }
            }
        }
        $var = array("coderro"=>$Erro, "id"=>$id, "row"=>$row);
        $responseText = json_encode($var);
        echo $responseText;
    }
    if($Acao =="deletaRamal"){
        $id = (int) filter_input(INPUT_GET, 'numero');
        $Erro = 0;
        $Sql = mysqli_query($xVai, "UPDATE cesb_ramais_int SET Ativo = 0 WHERE CodTel = $id");
        if(!$Sql){
            $Erro = 1;
        }    
        $var = array("coderro"=>$Erro, "id"=>$id);
        $responseText = json_encode($var);
        echo $responseText;
    }
}

if($Tipo == 2){
    if($Acao=="buscaRamal"){
        $Num = (int) filter_input(INPUT_GET, 'numero'); // id
        $Erro = 0;
        $row = 0;
        $Sql = mysqli_query($xVai, "SELECT SiglaEmpresa, NomeEmpresa, Setor, ContatoNome, TelefoneFixo, TelefoneCel FROM cesb_ramais_ext WHERE CodTel = $Num");
        if(!$Sql){
           $Erro = 1;
        }else{
            $row = mysqli_num_rows($Sql);
            $Proc = mysqli_fetch_array($Sql);
        }
         $var = array("coderro"=>$Erro, "SiglaEmpresa"=>$Proc["SiglaEmpresa"], "NomeEmpresa"=>$Proc["NomeEmpresa"], "Setor"=>$Proc["Setor"], "ContatoNome"=>$Proc["ContatoNome"], "TelefoneFixo"=>$Proc["TelefoneFixo"], "TelefoneCel"=>$Proc["TelefoneCel"]);
        $responseText = json_encode($var);
        echo $responseText;
     }
    
    if($Acao=="salvaRamal"){
        $id = (int) filter_input(INPUT_GET, 'numero');
        $SiglaEmpresa = filter_input(INPUT_GET, 'SiglaEmpresa');  //$_REQUEST["nome"];
        $NomeEmpresa = filter_input(INPUT_GET, 'NomeEmpresa');
        $Setor = filter_input(INPUT_GET, 'Setor');
        $TelefoneFixo = filter_input(INPUT_GET, 'TelefoneFixo');
        $TelefoneCel = filter_input(INPUT_GET, 'TelefoneCel');
        if(strlen($TelefoneFixo) == 6){
            $TelefoneFixo = str_replace("(", "", $TelefoneFixo);
            $TelefoneFixo = str_replace(")", "", $TelefoneFixo);
            $TelefoneFixo = str_replace(" ", "", $TelefoneFixo);
        }
        $ContatoNome = filter_input(INPUT_GET, 'ContatoNome');
        $UsuLogado = $_SESSION["usuarioID"]; //$_REQUEST["usulogado"];
        $Erro = 0;
        $row = 0;
        if($id !== 0){ // edição
            $Sql = mysqli_query($xVai, "UPDATE cesb_ramais_ext SET SiglaEmpresa = '$SiglaEmpresa', NomeEmpresa = '$NomeEmpresa', Setor = '$Setor', TelefoneFixo = '$TelefoneFixo', TelefoneCel = '$TelefoneCel', ContatoNome = '$ContatoNome', usumodif = '$UsuLogado', datamodif = NOW() WHERE CodTel = $id");
            if(!$Sql){
                $Erro = 1;
            }
        }else{ // inserção
            $rs = mysqli_query($xVai, "SELECT SiglaEmpresa, NomeEmpresa, Setor, TelefoneFixo FROM cesb_ramais_ext WHERE SiglaEmpresa = '$SiglaEmpresa' And NomeEmpresa = '$NomeEmpresa'");
            $row = mysqli_num_rows($rs);
            if($row > 0){
    //            $Proc = mysqli_fetch_array($rs);
                $Erro = 2; // nome já existe
            }else{
                $Sql = mysqli_query($xVai, "INSERT INTO cesb_ramais_ext (SiglaEmpresa, NomeEmpresa, Setor, TelefoneFixo, TelefoneCel, ContatoNome, datains, usuins) 
                VALUES('$SiglaEmpresa', '$NomeEmpresa', '$Setor', '$TelefoneFixo', '$TelefoneCel', '$ContatoNome', NOW(), '$UsuLogado')");
                if(!$Sql){
                    $Erro = 1;
                }
            }
        }
        $var = array("coderro"=>$Erro, "id"=>$id, "row"=>$row);
        $responseText = json_encode($var);
        echo $responseText;
    }
    if($Acao=="deletaRamal"){
        $id = (int) filter_input(INPUT_GET, 'numero');
        $Erro = 0;
        $Sql = mysqli_query($xVai, "UPDATE cesb_ramais_ext SET Ativo = 0 WHERE CodTel = $id");
        if(!$Sql){
            $Erro = 1;
        }    
        $var = array("coderro"=>$Erro, "id"=>$id);
        $responseText = json_encode($var);
        echo $responseText;
    }

}