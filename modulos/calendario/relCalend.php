<?php
	session_start();
    if(!isset($_SESSION['AdmUsu'])){
        header("Location: index.php");
     }
    require_once("../dbclass.php");

    date_default_timezone_set('America/Sao_Paulo'); 
    $monthTime = $_REQUEST["monthTime"];
    //$startDate = $_REQUEST["dataInicial"];
    $startDate = strtotime("last sunday", $monthTime);

//echo "<table style='margin: 0 auto; width: 100vh; height: calc(100vh - 50px);'>";
echo "<table style='margin: 0 auto; text-align: center;'>";
echo "<thead>
    <tr>
        <th style='width: 1%;'>Dom</th>
        <th>Seg</th>
        <th>Ter</th>
        <th>Qua</th>
        <th>Qui</th>
        <th>Sex</th>
        <th>Sab</th>
    </tr>
</thead>";

echo "<tbody>";
for($row = 0; $row < 6; $row++){
    echo "<tr>";
    for($column = 0; $column < 7; $column++){
        if(date("Y-m", $startDate) != date("Y-m", $monthTime)){
            echo "<td class='other-month' style='vertical-align: top; text-align: left; height: 100px; font-size: 1.2em; font-weight: bold;'>";
        }else{
            echo "<td style='vertical-align: top; text-align: left; height: 100px; font-size: 1.2em; font-weight: bold;'>";
        }

        $HojeUnix = strtotime(date('Y/m/d'));
        if($HojeUnix == $startDate){
            echo "<div style='cursor: pointer; background-color: #F5DEB3; text-align: center; border: 1px solid; width: 30px; border-radius: 15px;' onclick='pegaData($startDate);' title='Inserir evento'>";
            echo date("j", $startDate);
            echo "</div>";
        }else{
            echo "<div style='cursor: pointer;' onclick='pegaData($startDate);' title='Inserir evento'>";
            echo date("j", $startDate);
            echo "</div>";
        }

        //Coluna Repet = 0 -> sem repetição, =1 -> repetição mensal, =2 -> repetição anual

        $date = new DateTime("@$startDate");
        $Dia = $date->format('Y-m-d');   //  $var = $date->format('U = Y-m-d H:i:s'); // U é o timestamp unix
        $rs0 = mysqli_query($xVai, "SELECT idEv, evNum, titulo, cor, Fixo FROM cesb_calendev WHERE dataIni = '$Dia' And Ativo = 1 Or day(dataIni) = day('$Dia') And Repet = 1 And Ativo = 1 or day(dataIni) = day('$Dia') And month(dataIni) = month('$Dia') And Repet = 2 And Ativo = 1 ORDER BY evNum");
        $row0 = mysqli_num_rows($rs0);
        if($row0 > 0){
            while ($tbl0 = mysqli_fetch_array($rs0)){
                $Cod = $tbl0["idEv"];
                $evNum = $tbl0["evNum"];
                $Tit = substr($tbl0["titulo"], 0, 20);
                $Cor = $tbl0["cor"];
                $Fixo = $tbl0["Fixo"];
                if($Fixo == 0){ // não é evento fixo
                    echo "<div style='background-color: ".$Cor."; font-size: .6em; margin: 0; padding: 0px; cursor: pointer;' onclick='pegaEvento($Cod, $evNum);' title='Editar evento'>$Tit</div>";
                }else{
                    if($_SESSION["AdmUsu"] > 6){
                        echo "<div style='background-color: ".$Cor."; font-size: .6em; margin: 0; padding: 0px; cursor: pointer;' onclick='pegaEvento($Cod, $evNum);' title='Editar evento'>$Tit</div>";
                    }else{
                        echo "<div style='background-color: ".$Cor."; font-size: .6em; margin: 0; padding: 0px;'>$Tit</div>";
                    }
                }
                
            }
        }
        echo "</td>";
        $startDate = strtotime("+1 day", $startDate);
    }
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
