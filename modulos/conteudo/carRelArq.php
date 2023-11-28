<?php 
    session_start(); 
    require_once(dirname(dirname(__FILE__))."/dbclass.php");
    $admIns = (int) filter_input(INPUT_GET, 'admins'); // vem de relArq.php
    $Dir = $_SESSION["PagDir"]; // página apresentada

    //Ver arquivos dos subsetores - o usuário logado tem que ter o CodSubSetor = 1 (não pertence a nenhum subsetor)
    $VerSubSet = 1;

    $Sql = "SELECT CodArq, descArq, CodSetor, CodSubSetor FROM cesb_arqsetor WHERE CodSetor = ".$_SESSION["CodSetorUsu"]." And CodSubSetor = ".$_SESSION["CodSubSetorUsu"]." And Ativo = 1 And CodSetor = $Dir ORDER BY dataIns DESC";
    if($VerSubSet == 1){ // ver os arquivos dos subsetores
        if($_SESSION["CodSubSetorUsu"] == 1){  // se o usuário logado não pertence a nenhum subsetor
     $Sql = "SELECT CodArq, descArq, CodSetor, CodSubSetor FROM cesb_arqsetor WHERE CodSetor = ".$_SESSION["CodSetorUsu"]." And CodSubSetor > 0 And Ativo = 1 And CodSetor = $Dir ORDER BY dataIns DESC";
        }
    }
//echo $Sql;
    $DescSetor = $_SESSION["DescSetor"]."-"; // para retirar do nome do arquivo ao listar
    $TamSetor = strlen($DescSetor);
    $rs = mysqli_query($xVai, $Sql);

    echo "<div style='text-align: center; padding: 10px; font-family: tahoma, arial, cursive, sans-serif;'>";
        echo "<table style='margin: 0 auto; width: 95%;'>";
            while ($Tbl = mysqli_fetch_array($rs)){
                $CodArq = $Tbl["CodArq"];
                $Setor = $Tbl["CodSetor"];
                $SubSetor = $Tbl["CodSubSetor"];
                $DescArq = $Tbl["descArq"];

                $StrUniq = substr($DescArq, 0, 14);
                $NomeArq1 = str_replace($StrUniq, "", $DescArq); // tira o nome do setor
                if(substr($NomeArq1, 0, $TamSetor) == $DescSetor){
                    $NomeArq = str_replace($DescSetor, "", $NomeArq1);
                }else{
                    $NomeArq = $NomeArq1;
                }
                if($CodArq != 0){
                    echo "<tr>";
                        echo "<td><div style='display: none;'>$CodArq</div></td>";
                        echo "<td><div id='descarq' onclick='mostraArq($CodArq)' class='listaArq arqMouseOver'>$NomeArq</div>";
                        if($Setor == $_SESSION["CodSetorUsu"] && $SubSetor == $_SESSION["CodSubSetorUsu"] && $_SESSION["AdmUsu"] >= $admIns){ // $VerSubSet = 1 pode ver mas não pode apagar
                            echo "<div><span data-bs-toggle='modal' data-bs-target='#deletaModal' onclick='guardaArq($CodArq)' title='Apagar este arquivo' style='padding-left: 3px; padding-right: 10px; color: #aaa; top: 0px; float: left; font-size: 16px; font-weight: bold; font-variant-position: super; cursor: pointer;'>&times;</span></div>";
                        }
                        echo "</td>";
                    echo "</tr>";
                }
            }
        echo "</table>";
    echo "</div>";