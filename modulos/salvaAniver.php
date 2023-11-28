<?php
require_once("dbclass.php");


if(isset($_REQUEST["acao"])){
    $Acao = $_REQUEST["acao"];
}

if($Acao=="buscaAniver"){
    $Num = $_REQUEST["numero"];
 //   $Num = (int) filter_input(INPUT_GET, 'num'); // id
    $Erro = 0;
    $row = 0;
    $Sql = mysqli_query($xVai, "SELECT nomeUsu, nomeCompl, diaAniv, mesAniv FROM cesb_anivers WHERE id = $Num");
    if(!$Sql){
       $Erro = 1;
    }else{
        $row = mysqli_num_rows($Sql);
        $Proc = mysqli_fetch_array($Sql);
    }
    $var = array("coderro"=>$Erro, "usuario"=>$Proc["nomeUsu"], "nomecompl"=>$Proc["nomeCompl"], "diaAniv"=>$Proc["diaAniv"], "mesAniv"=>$Proc["mesAniv"]);
    $responseText = json_encode($var);
    echo $responseText;
 }

if($Acao=="salvaAniver"){
    $id = (int) filter_input(INPUT_GET, 'numero');
    $Nome = filter_input(INPUT_GET, 'usuario');  //$_REQUEST["nome"];
    $NomeCompl = filter_input(INPUT_GET, 'nomecompl');
    $DiaAniv = filter_input(INPUT_GET, 'diaAniv');
    $MesAniv = filter_input(INPUT_GET, 'mesAniv');
    $UsuLogado = $_REQUEST["usulogado"];
    $Erro = 0;
    $row = 0;
    if($id != 0){ // edição
        $Sql = mysqli_query($xVai, "UPDATE cesb_anivers SET nomeUsu = '$Nome', nomeCompl = '$NomeCompl', diaAniv = '$DiaAniv', mesAniv = '$MesAniv', usuModif = '$UsuLogado', dataModif = NOW() WHERE id = $id");
        if(!$Sql){
            $Erro = 1;
        }
    }else{ // inserção
        $rs = mysqli_query($xVai, "SELECT nomeUsu, nomeCompl, diaAniv, mesAniv FROM cesb_anivers WHERE nomeUsu = '$Nome' And nomeCompl = '$NomeCompl'");
        $row = mysqli_num_rows($rs);
        if($row > 0){
//            $Proc = mysqli_fetch_array($rs);
            $Erro = 2; // nome já existe
        }else{
            $Sql = mysqli_query($xVai, "INSERT INTO cesb_anivers (nomeUsu, nomeCompl, diaAniv, mesAniv, dataIns, usuIns) 
            VALUES('$Nome', '$NomeCompl', '$DiaAniv', '$MesAniv', NOW()), '$UsuLogado')");
            if(!$Sql){
                $Erro = 1;
            }
        }
    }
    $var = array("coderro"=>$Erro, "id"=>$id, "row"=>$row);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="deletaAniver"){
    $id = (int) filter_input(INPUT_GET, 'numero');
    $Erro = 0;
    $Sql = mysqli_query($xVai, "UPDATE cesb_anivers SET Ativo = 0 WHERE id = $id");
    if(!$Sql){
        $Erro = 1;
    }    
    $var = array("coderro"=>$Erro, "id"=>$id);
    $responseText = json_encode($var);
    echo $responseText;
}