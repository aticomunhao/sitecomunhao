<?php
session_start();
if(!isset($_SESSION["usuarioID"])){
    header("Location: ../index.php");
}
function cleanString($text) {
    $utf8 = array(
      '/[áàâãä]/u'    =>   'a',
      '/[ÁÀÂÃÄ]/u'    =>   'A',
      '/[ÍÌÎÏ]/u'     =>   'I',
      '/[íìîï]/u'     =>   'i',
      '/[éèêë]/u'     =>   'e',
      '/[ÉÈÊË]/u'     =>   'E',
      '/[óòôõºö]/u'   =>   'o',
      '/[ÓÒÔÕÖ]/u'    =>   'O',
      '/[úùûü]/u'     =>   'u',
      '/[ÚÙÛÜ]/u'     =>   'U',
      '/ç/'           =>   'c',
      '/Ç/'           =>   'C',
      '/ª/'           =>   'a',
      '/ñ/'           =>   'n',
      '/Ñ/'           =>   'N',
      '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
      '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
      '/[“”«»„]/u'    =>   ' ', // Double quote
      '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
 }
 
    $arquivo = $_FILES['arquivo'];  //recebe o arquivo do formulário

    //Verificar as extensões outra vez - já foi feito no custom.js - O js não está entendendo o MIME type pptx e ppsx - está sendo filtrado só aqui
    if($arquivo['type'] == 'application/pdf' || $arquivo['type'] == 'application/msword' || $arquivo['type'] == 'application/vnd.openxmlformats' || $arquivo['type'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $arquivo['type'] == 'text/plain' || $arquivo['type'] == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' || $arquivo['type'] == 'application/vnd.openxmlformats-officedocument.presentationml.slideshow'){
        // MIME docx = application/vnd.openxmlformats-officedocument.wordprocessingml.document
        // MIME pptx = application/vnd.openxmlformats-officedocument.presentationml.presentation 

        require_once(dirname(dirname(__FILE__))."/dbclass.php");
        $DescSetor = "Sistema";
        // Seleciona o setor do usuário
        $rs0 = mysqli_query($xVai, "SELECT SiglaSetor FROM cesb_usuarios INNER JOIN cesb_setores ON cesb_usuarios.CodSetor = cesb_setores.CodSet WHERE cesb_usuarios.id = ".$_SESSION["usuarioID"]." And CodSubSetor = 1 ");
        $row0 = mysqli_num_rows($rs0);
        if($row0 > 0){ // não é de setor, estão procura o subsetor
            $Proc0 = mysqli_fetch_array($rs0);
            $DescSetor = $Proc0["SiglaSetor"];
        }else{   // Seleciona o subsetor do usuário
            $rs0 = mysqli_query($xVai, "SELECT SiglaSubSetor FROM cesb_usuarios INNER JOIN cesb_subsetores ON cesb_usuarios.CodSubSetor = cesb_subsetores.CodSubSet WHERE cesb_usuarios.id = ".$_SESSION["usuarioID"]." And CodSubSetor > 1 ");
            $Proc0 = mysqli_fetch_array($rs0);
            $DescSetor = $Proc0["SiglaSubSetor"];               
        }

        $NomeArq = $_FILES['arquivo']['name']; // salvar no bd - pode ser usado para indexação
        $DescArq = uniqid()."-".$DescSetor."-".$_FILES['arquivo']['name'];
        $DescArq = cleanString($DescArq);
        $DescArq = str_replace("ã", "a", $DescArq);

        $Caminho = "arquivos/".$DescArq;
        if(move_uploaded_file($arquivo['tmp_name'], $Caminho)){  // realiza o uploud
            $rs = mysqli_query($xVai, "INSERT INTO cesb_arqsetor (CodSetor, CodSubSetor, descArq, nomeArq, usuIns) 
            VALUES (".$_SESSION["CodSetorUsu"].", ".$_SESSION["CodSubSetorUsu"].", '$DescArq', '$NomeArq', ".$_SESSION["usuarioID"].")"); // Salva no bd

            $_SESSION['msgarq'] = "Arquivo carregado com sucesso";
        }else{
            $_SESSION['msgarq'] = "O arquivo NÃO foi carregado";
        }
    }else{
        $_SESSION["msgarq"] = "Tipo de arquivo não permitido - Updload suspenso.";
    }