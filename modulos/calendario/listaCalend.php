<?php
require_once("../dbclass.php");
date_default_timezone_set('America/Sao_Paulo'); 
$monthTime = $_REQUEST["monthTime"];
//$startDate = $_REQUEST["dataInicial"];
$startDate = strtotime("last sunday", $monthTime);

$date = new DateTime("@$startDate");
$Dia = $date->format('Y-m-d');   //  $var = $date->format('U = Y-m-d H:i:s'); // U é o timestamp unix

$rs0 = mysqli_query($xVai, "SELECT idEv, evNum, date_format(dataIni, '%d/%m/%Y') as DataInicial, titulo, cor FROM cesb_calendev WHERE Ativo = 1 And dataIni >= (DATE_ADD(CURDATE(), INTERVAL 0 DAY)) ORDER BY dataIni, evNum");
$row0 = mysqli_num_rows($rs0);
if($row0 > 0){
    while ($tbl0 = mysqli_fetch_array($rs0)){
        $Cod = $tbl0["idEv"];
        $evNum = $tbl0["evNum"];
        $Data = $tbl0["DataInicial"];
        $Tit = $tbl0["titulo"];
        $Cor = $tbl0["cor"];
        echo "<div style='font-size: .8em; font-weight: bold;'>";
        echo $Data;
        echo "<span style='color: $Cor;'>&#8226;</span>";
        echo $Tit;
        echo "</div>";
        echo "<hr style='margin: 5px;'>";
    }
}else{
    echo "<div style='font-size: .8em; font-weight: bold; text-align: center;'>Nenhum evento encontrado</div>";
}