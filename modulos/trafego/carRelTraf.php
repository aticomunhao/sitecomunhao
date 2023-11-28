<?php 
    session_start(); 
    require_once(dirname(dirname(__FILE__))."/dbclass.php");
    $rs = mysqli_query($xVai, "SELECT CodTraf, descArq, usuIns FROM cesb_trafego WHERE Ativo = 1 ORDER BY dataIns DESC");
    echo "<div style='text-align: center; padding: 10px; font-family: tahoma, arial, cursive, sans-serif;'>";
        echo "<table style='margin: 0 auto; width: 95%;'>";
            while ($Tbl = mysqli_fetch_array($rs)){
                $CodArq = $Tbl["CodTraf"];
                $DescArq = $Tbl["descArq"];
                $StrUniq = substr($DescArq, 0, 14);
                $NomeArq = str_replace($StrUniq, "", $DescArq);
                if($CodArq != 0){
                    echo "<tr>";
                        echo "<td><div style='display: none;'>$CodArq</div></td>";
                        echo "<td><div id='descarq' onclick='mostraArq($CodArq)' class='listaArq arqMouseOver'>$NomeArq</div>";
                        echo "<div><span data-bs-toggle='modal' data-bs-target='#deletaModal' onclick='guardaArq($CodArq)' title='Apagar este arquivo' style='padding-left: 3px; padding-right: 10px; color: #aaa; top: 0px; float: left; font-size: 16px; font-weight: bold; font-variant-position: super; cursor: pointer;'>&times;</span></div>";
                        echo "</td>";
                    echo "</tr>";
                }
            }
        echo "</table>";
    echo "</div>";