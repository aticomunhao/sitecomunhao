<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
    </head>
    <body>
        <?php
            require_once("../dbclass.php");
            //  o nome da imagem é modificado a cada mudança para contornar o cache de imagem
            $rs1 = mysqli_query($xVai, "SELECT CodCar, descArq FROM cesb_carousel WHERE CodCar = 1");
            $tbl1 = mysqli_fetch_array($rs1);
            $Slide1 = $tbl1["descArq"];

            $rs2 = mysqli_query($xVai, "SELECT CodCar, descArq FROM cesb_carousel WHERE CodCar = 2");
            $tbl2 = mysqli_fetch_array($rs2);
            $Slide2 = $tbl2["descArq"];

            $rs3 = mysqli_query($xVai, "SELECT CodCar, descArq FROM cesb_carousel WHERE CodCar = 3");
            $tbl3 = mysqli_fetch_array($rs3);
            $Slide3 = $tbl3["descArq"];

            $rs4 = mysqli_query($xVai, "SELECT CodCar, descArq FROM cesb_carousel WHERE CodCar = 4");
            $tbl4 = mysqli_fetch_array($rs4);
            $Slide4 = $tbl4["descArq"];
        ?>

        <div class="mostraslide">
            <img src="imagens/slides/<?php echo $Slide1; ?>" width=250px; height=125px; alt="slide1">
            <div>
                <input type="radio" name="trocaslide" id="trocaslide0" value="1" title="Substituir Slide" onclick="Subst(value);">
                <label for="trocaslide0" class="etiq">Substituir Slide 1</label>
            </div>
        </div>
        <div class="mostraslide">
            <img src="imagens/slides/<?php echo $Slide2; ?>" width=250px; height=125px; alt="slide2">
            <div>
                <input type="radio" name="trocaslide" id="trocaslide1" value="2" title="Substituir Slide" onclick="Subst(value);">
                <label for="trocaslide1" class="etiq">Substituir Slide 2</label>
            </div>
        </div>
        <div class="mostraslide">
            <img src="imagens/slides/<?php echo $Slide3; ?>" width=250px; height=125px; alt="slide3">
            <div>
                <input type="radio" name="trocaslide" id="trocaslide2" value="3" title="Substituir Slide" onclick="Subst(value);">
                <label for="trocaslide2" class="etiq">Substituir Slide 3</label>
            </div>
        </div>
        <div class="mostraslide">
            <img src="imagens/slides/<?php echo $Slide4; ?>" width=250px; height=125px; alt="slide4">
            <div>
                <input type="radio" name="trocaslide" id="trocaslide3" value="4" title="Substituir Slide" onclick="Subst(value);">
                <label for="trocaslide3" class="etiq">Substituir Slide 4</label>
            </div>
        </div>

    </body>
</html>