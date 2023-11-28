<?php
session_start();
require_once(dirname(dirname(__FILE__))."/dbclass.php");
if(isset($_REQUEST["acao"])){
    $Acao = $_REQUEST["acao"];
}

if($Acao == "selectarquivo"){  //vem de relArq.php
    $CodArq = filter_input(INPUT_GET, 'codigo');
    $Erro = 0;
    $rs = mysqli_query($xVai, "SELECT descArq FROM cesb_arqsetor WHERE CodArq = $CodArq");
    if(!$rs){
        $Erro = 1;
     }
    $Tbl = mysqli_fetch_array($rs);
    $DescArq = $Tbl["descArq"];
     //verifica se o arquivo existe no diretório
    if(!file_exists("arquivos/".$DescArq)){
        $Erro = 2;
    }
     $var = array("coderro"=>$Erro, "arquivo"=>$DescArq);
     $responseText = json_encode($var);
     echo $responseText;
}
if($Acao == "apagaarquivo"){  //vem de relArq.php
    $CodArq = filter_input(INPUT_GET, 'codigo');
    $Erro = 0;
    $rs = mysqli_query($xVai, "SELECT descArq FROM cesb_arqsetor WHERE CodArq = $CodArq");
    if(!$rs){
        $Erro = 1;
     }
    $Tbl = mysqli_fetch_array($rs);
    $DescArq = $Tbl["descArq"];
     //deleta o arquivo
    if(file_exists("arquivos/".$DescArq)){
        unlink("arquivos/".$DescArq);
     }else{
        $Erro = 2;
     }
    //modifica a condição na tabela
    $rs = mysqli_query($xVai, "UPDATE cesb_arqsetor SET Ativo = 0, usuApag = ".$_SESSION["usuarioID"].", dataApag = NOW() WHERE CodArq = $CodArq");

    //Apaga registros com mais de 5 anos de inativado
    mysqli_query($xVai, "DELETE FROM cesb_arqsetor WHERE Ativo = 0 And dataApag <= DATE_SUB(CURDATE(),INTERVAL 5 YEAR)");

     $var = array("coderro"=>$Erro, "arquivo"=>$DescArq);
     $responseText = json_encode($var);
     echo $responseText;
}
if($Acao == "salvaTexto"){  //vem de relPag.php
    $CodSetor = filter_input(INPUT_GET, 'setorid');
    $Texto = htmlentities(addslashes($_REQUEST["textopagina"]));

    $Erro = 0;
    $rs = mysqli_query($xVai, "UPDATE cesb_setores SET textoPag = '$Texto' WHERE CodSet = $CodSetor");
    if(!$rs){
        $Erro = 1;
    }
     $var = array("coderro"=>$Erro, "texto"=>$Texto);
     $responseText = json_encode($var);
     echo $responseText;
}
if($Acao == "buscaTextoPag"){  //vem de relPag.php
    $CodSetor = filter_input(INPUT_GET, 'setorid');
    $Erro = 0;
    $rs = mysqli_query($xVai, "SELECT textoPag FROM cesb_setores WHERE CodSet = $CodSetor");
    if(!$rs){
        $Erro = 1;
    }else{
        $Tbl = mysqli_fetch_array($rs);
        $Texto = $Tbl["textoPag"];
    }
     $var = array("coderro"=>$Erro, "texto"=>$Texto);
     $responseText = json_encode($var);
     echo $responseText;
}
