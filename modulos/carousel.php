<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
    </head>
    <body>
        <?php
            require_once("dbclass.php");
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

        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="imagens/slides/<?php echo $Slide1; ?>" height=250px; alt="Animado" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="imagens/slides/<?php echo $Slide2; ?>"" height=250px; alt="" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="imagens/slides/<?php echo $Slide3; ?>"" height=250px; alt="" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="imagens/slides/<?php echo $Slide4; ?>"" height=250px; alt="" class="d-block w-100">
            </div>
        </div>

        <!-- Controles à direita e esquerda -->
        <button class="carousel-control-prev" type="button" data-bs-target="#CorouselPagIni" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#CorouselPagIni" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </body>
</html>