<?php
session_start();
if(!isset($_SESSION["usuarioID"])){
   header("Location: /cesb/index.php");
}
require_once("../dbclass.php");
$Acao = filter_input(INPUT_GET, "acao");

   if($Acao == "ressetasession"){
      $Arq = filter_input(INPUT_GET, "arquivo"); // fechou o modal sem salvar
      if(file_exists("imagens/".$Arq)){
         $var = unlink("imagens/".$Arq) or die ("SemPerm");
      }
      $_SESSION['geremsg'] = 0;
      $_SESSION['arquivo'] = "";
      $responseText = json_encode($_SESSION['geremsg']);
      echo $responseText;
   }
   if($Acao == "guardaslide"){
      $Slide = filter_input(INPUT_GET, "slide"); // fechou o modal sem salvar
      $_SESSION['gerenum'] = $Slide;
      $responseText = json_encode($_SESSION['gerenum']);
      echo $responseText;
   }
   if($Acao == "acertaslide"){ // vem de gereSlide.php
      $Valor = (int) filter_input(INPUT_GET, "valor");
      $Slide = filter_input(INPUT_GET, "numslide");
      $Arq = filter_input(INPUT_GET, "arquivo");
      $Erro = 0;
      if($Valor == 0){ // apagar
         if(file_exists("imagens/".$Arq)){
            $var = unlink("imagens/".$Arq) or die ("SemPerm");
         }
      }
      if($Valor == 1){ //substituir
         $NovoArquivo = "imgfundo".uniqid().".jpg"; //mudar o nome da imagem sempre para contornar o cache

         mysqli_query($xVai, "UPDATE cesb_carousel SET descArqAnt = descArq, descArq = '$NovoArquivo' WHERE CodCar = $Slide");
         if(copy("imagens/".$Arq, dirname(dirname(dirname(__FILE__)))."/imagens/slides/".$NovoArquivo)){
            unlink("imagens/".$Arq) or die ("SemPerm");

            //Apagar a imagem velha
            $rs0 = mysqli_query($xVai, "SELECT descArqAnt FROM cesb_carousel WHERE CodCar = $Slide");
            $row0 = mysqli_num_rows($rs0);
            if($row0 > 0){
               $tbl0 = mysqli_fetch_array($rs0);
               $ArqAnt = $tbl0["descArqAnt"];
               if(file_exists(dirname(dirname(dirname(__FILE__)))."/imagens/slides/".$ArqAnt)){
                  $var = unlink(dirname(dirname(dirname(__FILE__)))."/imagens/slides/".$ArqAnt) or die ("SemPerm");
               }
            }
         }else{
            $Erro = 1;
         }
      }
      $var = array("coderro"=>$Erro);
      $responseText = json_encode($var);
      echo $responseText;
   }
   mysqli_close($xVai); // fecha a conexão