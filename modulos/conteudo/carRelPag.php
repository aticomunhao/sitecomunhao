<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <style type="text/css">
        </style>
    </head>
    <body>
        <?php
            require_once(dirname(dirname(__FILE__))."/dbclass.php");
            $rs0 = mysqli_query($xVai, "SELECT textoPag FROM cesb_setores WHERE CodSet = ".$_SESSION["PagDir"]);
            $row0 = mysqli_num_rows($rs0);
            if($row0 > 0){ // não é de setor, estão procura o subsetor
                $Proc0 = mysqli_fetch_array($rs0);
                $TextoPag = html_entity_decode($Proc0["textoPag"]);
            }else{
                $TextoPag = "";
            }
        ?>
        <div id="contentPag" style="margin: 30px;"> <?php echo $TextoPag; ?> </div>
    </body>
</html>