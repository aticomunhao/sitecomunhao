<?php 
    session_start(); 
    require_once(dirname(dirname(__FILE__))."/dbclass.php");

    $CodOcor = $_REQUEST['codocor'];
    if($CodOcor == 0){
        $rsIdeo = mysqli_query($xVai, "SELECT CodIdeo, descIdeo FROM cesb_ocorrideogr WHERE CodProv = ".$_SESSION['usuarioID']." And CoddaOcor = 0 ORDER BY CodIdeo");
    }else{
        $rsIdeo = mysqli_query($xVai, "SELECT CodIdeo, descIdeo FROM cesb_ocorrideogr WHERE CoddaOcor = $CodOcor OR CodProv = ".$_SESSION['usuarioID']." And CoddaOcor = 0 ORDER BY CodIdeo");
    }

    $rowIdeo = mysqli_num_rows($rsIdeo);
    if($rowIdeo > 0){
        while ($TblIdeo = mysqli_fetch_array($rsIdeo)){
            $Ideogr = $TblIdeo["descIdeo"];
            $CodIdeo = $TblIdeo["CodIdeo"];
            echo "<div style='display: inline; padding: 2px;' title='Clique para apagar' onclick='apagaArq($CodIdeo)'><img src='$Ideogr' width='60px' height='60px;'>  </div>";
        }
    }