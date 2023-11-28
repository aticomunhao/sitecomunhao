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
if($Acao=="salvaOcor"){
    $Data = addslashes($_REQUEST['datains']);  // filter_input(INPUT_GET, 'datains');
    $Texto = addslashes($_REQUEST['textoocorrencia']);
    $Codigo = (int) filter_input(INPUT_GET, 'codigo');

    $Erro = 0;
    $CodigoNovo = 0;
    if($Codigo == 0){
        $UsuIns = $_SESSION['usuarioID'];
        $CodSetor = $_SESSION['CodSetorUsu'];
        $rs0 = mysqli_query($xVai, "SELECT CodOcor FROM cesb_ocorrencias WHERE YEAR(dataIns) = YEAR(NOW())");
        $row0 = mysqli_num_rows($rs0);
    
        $Num = str_pad(($row0+1), 4, "0", STR_PAD_LEFT);

        $Sql = mysqli_query($xVai, "INSERT INTO cesb_ocorrencias (usuIns, dataOcor, dataIns, CodSetor, NumOcor, Ocorrencia) 
        VALUES($UsuIns, str_to_date('$Data', '%d/%m/%Y'), NOW(), $CodSetor, CONCAT('$Num', '/', YEAR(NOW())), '$Texto')") or die ("Faltam Parâmetros" . mysqli_error($xVai));
        if(!$Sql){
            $Erro = 1;
        }else{
            $CodigoNovo = mysqli_insert_id($xVai); // obtem o número AUTO_INCREMENT da operação INSERT realizada
            //acertar ideogramas
            mysqli_query($xVai, "UPDATE cesb_ocorrideogr SET CoddaOcor = $CodigoNovo WHERE CodProv = ".$_SESSION['usuarioID']." And CoddaOcor = 0");
            mysqli_query($xVai, "UPDATE cesb_ocorrideogr SET CodProv = 0 WHERE CodProv = ".$_SESSION['usuarioID']);
        }
    }else{
        $Sql = mysqli_query($xVai, "UPDATE cesb_ocorrencias SET dataOcor = str_to_date('$Data', '%d/%m/%Y'), Ocorrencia = '$Texto' WHERE CodOcor = $Codigo");
        //acertar ideogramas
        mysqli_query($xVai, "UPDATE cesb_ocorrideogr SET CoddaOcor = $Codigo WHERE CodProv = ".$_SESSION['usuarioID']." And CoddaOcor = 0");
        mysqli_query($xVai, "UPDATE cesb_ocorrideogr SET CodProv = 0 WHERE CodProv = ".$_SESSION['usuarioID']);
    }

    $var = array("coderro"=>$Erro, "codigonovo"=>$CodigoNovo);
    $responseText = json_encode($var);
    echo $responseText;
}

if($Acao=="salvaIdeogr"){
    $CodOcor = (int) filter_input(INPUT_GET, 'codOcorr');
    $Src = filter_input(INPUT_GET, 'source');
    $Erro = 0;
    $CodProv = 0;
    if($CodOcor == 0){
        $CodProv = $_SESSION['usuarioID'];
        $Sql = mysqli_query($xVai, "INSERT INTO cesb_ocorrideogr (CoddaOcor, descIdeo, CodProv) VALUES($CodOcor, '$Src', $CodProv)"); // or die ("Faltam Parâmetros" . mysqli_error($xVai));
    }else{
        $Sql = mysqli_query($xVai, "INSERT INTO cesb_ocorrideogr (CoddaOcor, descIdeo) VALUES($CodOcor, '$Src')"); // or die ("Faltam Parâmetros" . mysqli_error($xVai));
        if(!$Sql){
            $Erro = 1;
        }
    }
    $var = array("coderro"=>$Erro, "codOcorr"=>$CodOcor, "testeUniq"=>$CodProv);
     $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="sairSemSalvar"){
    $Erro = 0;
    //limpa ideogramas salvos 
    $Sql = mysqli_query($xVai, "UPDATE cesb_ocorrideogr SET CodProv = 0 WHERE CodProv = ".$_SESSION['usuarioID']." And CoddaOcor = 0");
    if(!$Sql){
        $Erro = 1;
    }
    $Sql = mysqli_query($xVai, "DELETE FROM cesb_ocorrideogr WHERE CodProv = 0 And CoddaOcor = 0");
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="buscaOcorr"){
    $CodOcor = (int) filter_input(INPUT_GET, 'codigo');
    $Erro = 0;
    $Sql = mysqli_query($xVai, "SELECT date_format(dataOcor, '%d/%m/%Y') AS DataOcorrencia, Ocorrencia FROM cesb_ocorrencias WHERE CodOcor = $CodOcor");
    if(!$Sql){
        $Erro = 1;
        $var = array("coderro"=>$Erro);
    }else{
        $Tbl = mysqli_fetch_array($Sql);
        $Data = $Tbl["DataOcorrencia"];
        $Texto = $Tbl["Ocorrencia"];
        $var = array("coderro"=>$Erro, "data"=>$Data, "texto"=>$Texto);
    }
     $responseText = json_encode($var);
    echo $responseText;
}
if($Acao=="apagaIdeogr"){
    $Cod = (int) filter_input(INPUT_GET, 'codigo');
    $Erro = 0;

    $Sql = mysqli_query($xVai, "DELETE FROM cesb_ocorrideogr WHERE CodIdeo = $Cod");
    if(!$Sql){
        $Erro = 1;
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}
