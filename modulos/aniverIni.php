<?php
    date_default_timezone_set('America/Sao_Paulo');
    $date = date('Y-m-d H:i:s');
    $mdate = date('m');
    $ddate = date('d');
    $dias = 5;
    require_once("dbclass.php");
 
    function pegaAniver($param, $mdate, $ddate, $xVai) {
        $rs0 = mysqli_query($xVai, "SELECT nomeUsu, nomeCompl, diaAniv, mesAniv FROM cesb_anivers WHERE mesAniv = $mdate And diaAniv $param $ddate ORDER BY mesAniv, diaAniv");
        $row0 = mysqli_num_rows($rs0);
        return $rs0;
    }

    echo "<div style='text-align: center; font-weight: 800;'>";
    //echo "<hr>";
    $Tem = 0;
    echo "<table>";
    //aniversariantes de hoje
        echo "<tr>";
            echo "<td style='color: red; text-align: left;'>";
            $aniver = pegaAniver('=', $mdate, $ddate, $xVai);
            if($aniver){
                $Tem = 1;
                foreach ($aniver as $row) {
                    echo $row['diaAniv']."/". $row['mesAniv']." - <b>" . $row['nomeCompl'] . "</b>" . "<br>";
                }
            }else{
                echo "Nenhum aniversariante hoje";
            }
            echo "</td>";
    
    //aniversariantes do $dias seguintes:
            echo "<tr>";
            echo "<td style='color: blue; text-align: left;'>";
            $aniver = pegaAniver('>', $mdate, $ddate, $xVai);
            if($aniver){
                foreach ($aniver as $row) {
                    echo $row['diaAniv']."/". $row['mesAniv']." - <b>" . $row['nomeCompl'] . "</b>" . "<br>";
                }
            }else{
                if($Tem == 0){
                    echo "Nenhum aniversariante neste mês";
                }
            }
            echo "</td>";
        echo "</tr>";
    echo "</table>";
    echo "</div>";
    $_SESSION['ConecAtiv'] = 0;
    mysqli_close($xVai);