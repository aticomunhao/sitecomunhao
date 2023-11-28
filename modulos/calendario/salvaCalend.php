<?php
session_start(); // inicia uma sessão
if(!isset($_SESSION["usuarioID"])){
    header("Location: /cesb/index.php");
}
require_once("../dbclass.php");
if(isset($_REQUEST["acao"])){
    $Acao = $_REQUEST["acao"];
}
if($Acao =="busca"){
    $Sent = (int) filter_input(INPUT_GET, 'sentido');
    $time = (int) filter_input(INPUT_GET, 'monthTime');
    $Erro = 0;
    if($Sent == 1){
        $mesAno = prevMonth($time);
        $numMes = (int) prevNumMes($time);
        $data = strtotime('-1 month', $time);
    }
    if($Sent == 2){
        $mesAno = nextMonth($time);
        $numMes = (int) nextNumMes($time);
        $data = strtotime('+1 month', $time);
    }
    $Ingl = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $Port = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
    $Trad = str_replace($Ingl, $Port, $mesAno);
    $var = array("result"=>$mesAno, "numMes"=>$numMes, "monthTime"=>$data, "mesTrad"=> $Trad);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="buscadata"){
    $time = filter_input(INPUT_GET, 'dataDia');
    $date = new DateTime("@$time");
    $Dia = $date->format('d-m-Y');   //  $var = $date->format('U = Y-m-d H:i:s'); // U é o timestamp unix

    $admIns = parAdm("insEvento");   // nível para inserir 
    $admEdit = parAdm("editEvento"); // nível para editar

    $InsEv = 0;
    $EditEv = 0;
    if($_SESSION["AdmUsu"] >= $admIns){
        $InsEv = 1;
    }
    if($_SESSION["AdmUsu"] >= $admEdit){
        $EditEv = 1;
    }

    $var = array("diaClick"=>$Dia, "insEv"=>$InsEv, "editEv"=>$EditEv);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="salvaev"){
    $numEv = (int) filter_input(INPUT_GET, 'numEv'); // numEv = 0 novo lançamento

    if($numEv > 0){ // cancela o lanç anterior pq pode haver mudança de duração
        $rsApag = mysqli_query($xVai, "UPDATE cesb_calendev SET Ativo = 0 WHERE evNum = $numEv");
    }

    $DataI = addslashes($_REQUEST['dataini']);  //  filter_input(INPUT_GET, 'dataini');
    if(!isset($_REQUEST['datafim'])){
        $DataF = $DataI;
    }else{
        $DataF = addslashes($_REQUEST['datafim']);  // filter_input(INPUT_GET, 'datafim');
    }
    $Texto = filter_input(INPUT_GET, 'textoev');
    $Local = filter_input(INPUT_GET, 'localev');
    $Cor = filter_input(INPUT_GET, 'corevento');
    $Repet = (int) filter_input(INPUT_GET, 'repet');
    $Fixo = (int) filter_input(INPUT_GET, 'fixo');
    
    $Erro = 0;

    // inverter o formato da data para y/m/d - lançar direto no BD
    $diaIni = implode("-", array_reverse(explode("/", $DataI))); // date('d/m/Y', strtotime("+ 1 days", strtotime($DataI)));
    $diaFim = implode("-", array_reverse(explode("/", $DataF))); 

    // transforma a data em número para poder somar
    $diaIniUnix = strtotime($diaIni);
    $diaFimUnix = strtotime($diaFim);
    $diasDif = (($diaFimUnix - $diaIniUnix) / 86400); // verifica quanto dias dá

    //Calcula o número do próximo evento
    $rs0 = mysqli_query($xVai, "SELECT MAX(evNum) As UltEv FROM cesb_calendev");
    if($rs0){
        $tbl0 = mysqli_fetch_array($rs0);
        $UltEv = $tbl0["UltEv"];
        $ProxEv = ($UltEv + 1);
    }

    if($diasDif == 0){ // só um dia
        $Dia = date('Y/m/d', $diaIniUnix);
        $rs = mysqli_query($xVai, "INSERT INTO cesb_calendev (evNum, titulo, cor, dataIni, localEv, Repet, Fixo) VALUES ($ProxEv, '$Texto', '$Cor', '$Dia', '$Local', $Repet, $Fixo)");
    }else{
        $Dia = date('Y/m/d', $diaIniUnix);
        for($n = 0; $n <= $diasDif; $n++){
            $rs = mysqli_query($xVai, "INSERT INTO cesb_calendev (evNum, titulo, cor, dataIni, localEv, Repet, Fixo) VALUES ($ProxEv, '$Texto', '$Cor', '$Dia', '$Local', $Repet, $Fixo)");
            $Soma = strtotime($Dia . ' + 1 day'); // soma um dia no formato unix
            $Dia = date('Y/m/d', $Soma); // transforma para poder lançar no BD.
        }
    }
    $var = array("coderro"=>$Erro, "diaIni"=>$diaIni);
    $responseText = json_encode($var);
    echo $responseText;
}

if($Acao =="buscaevento"){
    $Cod = (int) filter_input(INPUT_GET, 'codigo');
    $evNum = (int) filter_input(INPUT_GET, 'evNum');
    $Erro = 0;

    $rs0 = mysqli_query($xVai, "SELECT evNum, titulo, cor, localEv, Repet, Fixo FROM cesb_calendev WHERE idEv = $Cod");
    if($rs0){
        $tbl0 = mysqli_fetch_array($rs0);
        $evNum = $tbl0["evNum"];
        $Tit = $tbl0["titulo"];
        $Cor = $tbl0["cor"];
        $Local = $tbl0["localEv"];
        $Repet = $tbl0["Repet"];
        $Fixo = $tbl0["Fixo"];
        //pegar a data de início do evento
        //ver se há mais de um dia
        $rs1 = mysqli_query($xVai, "SELECT date_format(MIN(dataIni), '%d/%m/%Y') as DataIni FROM cesb_calendev WHERE evNum = $evNum And Ativo = 1");
        if($rs1){
            $tbl1 = mysqli_fetch_array($rs1);
            $DataIni = $tbl1["DataIni"];
            $DataFim = $DataIni;
        }
        //ver se há mais de um dia
        $rs2 = mysqli_query($xVai, "SELECT date_format(MAX(dataIni), '%d/%m/%Y') as DataFim FROM cesb_calendev WHERE evNum = $evNum And Ativo = 1");
        if($rs2){
            $tbl2 = mysqli_fetch_array($rs2);
            $DataFim = $tbl2["DataFim"];
        }
    }else{
        $Erro = 1;
    }

    $admIns = parAdm("insEvento");   // nível para inserir
    $admEdit = parAdm("editEvento"); // nível para editar

    $InsEv = 0;
    $EditEv = 0;
    if($_SESSION["AdmUsu"] >= $admIns){
        $InsEv = 1;
    }
    if($_SESSION["AdmUsu"] >= $admEdit){
        $EditEv = 1;
    }

    $var = array("coderro"=>$Erro, "evNum"=>$evNum, "dataIni"=>$DataIni, "titulo"=>$Tit, "cor"=>$Cor, "localEv"=>$Local, "dataFim"=>$DataFim, "Repet"=>$Repet, "Fixo"=>$Fixo, "insEv"=>$InsEv, "editEv"=>$EditEv);
    $responseText = json_encode($var);
    echo $responseText;
}
if($Acao =="apagaev"){
    $numEv = (int) filter_input(INPUT_GET, 'numEv'); // numEv = 0 novo lançamento
    $Erro = 0;
    if($numEv > 0){ // cancela o lanç anterior pq pode haver mudança de duração
        $rsApag = mysqli_query($xVai, "UPDATE cesb_calendev SET Ativo = 0, UsuApag = ".$_SESSION["usuarioID"].", DataApag = NOW()  WHERE evNum = $numEv");
        if(!$rsApag){
            $Erro = 1;
        }
    }
    $var = array("coderro"=>$Erro);
    $responseText = json_encode($var);
    echo $responseText;
}

function prevMonth($time){
//    return date('Y-m-d', strtotime('-1 month', $time));
    return date('F Y', strtotime('-1 month', $time));
}

function nextMonth($time){
//    return date('Y-m-d', strtotime('+1 month', $time));
    return date('F Y', strtotime('+1 month', $time));
}

function prevNumMes($time){
    return date('m', strtotime('-1 month', $time));
}
function nextNumMes($time){
    return date('m', strtotime('+1 month', $time));
}

