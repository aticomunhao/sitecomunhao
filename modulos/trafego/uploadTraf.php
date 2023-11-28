<?php
session_start();

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

        $NomeArq = $_FILES['arquivo']['name']; // salvar no bd - pode ser usado para indexação
        $DescArq = uniqid()."-".$_FILES['arquivo']['name'];
        $DescArq = cleanString($DescArq);
        $DescArq = str_replace("ã", "a", $DescArq);

        $Caminho = "arquivos/".$DescArq;
        if(move_uploaded_file($arquivo['tmp_name'], $Caminho)){  // realiza o uploud
            $rs = mysqli_query($xVai, "INSERT INTO cesb_trafego (descArq, nomeArq, usuIns) VALUES ('$DescArq', '$NomeArq', ".$_SESSION["usuarioID"].")"); // Salva no bd
            $_SESSION['msg'] = "Arquivo carregado com sucesso";
        }else{
            $_SESSION['msg'] = "O arquivo NÃO foi carregado";
        }
    }else{
        $_SESSION["msg"] = "Tipo de arquivo não permitido - Updload suspenso.";
    }
