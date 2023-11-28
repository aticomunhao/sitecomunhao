<?php
session_start();
if(!isset($_SESSION["usuarioID"])){
    header("Location: /cesb/index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <title></title>
        <link rel="stylesheet" type="text/css" media="screen" href="class/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/relacmod.css" />
        <script src="comp/js/jquery.min.js"></script> <!-- versão 3.6.3 -->
        <script src="class/bootstrap/js/bootstrap.min.js"></script>
        <style TYPE="text/css">
            .etiq{
               text-align: right; color: #808080; font-size: 70%; font-weight: bold; padding-right: 1px; padding-bottom: 1px;
            }
            .etiqueta{
                text-align: center;
                vertical-align: middle;
                cursor: move;
                font-weight: bold;
                width: 200px;
                height: 140px;
                border: 1px solid;
                margin: 0 auto;
                display: inline-block; 
                padding: 2px;
                font-size: .8rem;
                border-radius: 7px;
            }
            tr td{
/*                 border: 1px solid; */
               border: 0;
            }
            .etiqLat{
                font-size: 1.5rem;
                font-weight: bold;
                margin: 0;
                text-align: center;
                overflow-y: auto;
                min-width: 150px;
                border: 1px outset; /* outset, borda em alto relevo  - inset, borda em baixo relevo */
                border-radius: 10px;
            }
            .etiqInt{
                border: 2px solid;
            }
            .etiqAtiva{
                background-color: yellow;
            }
            .etiqInat{
                background-color: #F5F5F5;
            }
            .etiqInativa{
                border: 3px solid blue;
                background-color: #C6E2FF;
            }
            .relacmodal{
               display: none; /* oculto default */
                position: fixed;
                z-index: 200;
                left: 0;
                top: 0;
                width: 100%; /* largura total */
                height: 100%; /* altura total */
                overflow: auto; /* autoriza scroll se necessário */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }
            /* caixa do Modal Content */
            .modalTarefa-content{
                background: linear-gradient(180deg, white, #86c1eb);
                margin: 10% auto; /* 10% do topo e centrado */
                padding: 20px;
                border: 1px solid #888;
                border-radius: 15px;
                width: 50%; /* acertar de acordo com a tela */
            }
            .modalMsg-content{
                background: linear-gradient(180deg, white, #86c1eb);
                margin: 7% auto; /* 10% do topo e centrado */
                padding: 20px;
                border: 1px solid #888;
                border-radius: 15px;
                width: 50%; /* acertar de acordo com a tela */
            }
            /* Botão fechar */
            .close{
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                text-align: right;
            }
            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
            .blink{
                animation: blink 1.4s infinite;
            }
            @keyframes blink {
                0% {
                    opacity: 1;
                }
                100% {
                    opacity: 0;
                    color: blue;
                }
            }
        </style>
        <script type="text/javascript">
//            let urlA = window.location.href;
//            const Caminho = urlA.replace("/tarefas", "/");
            $(document).ready(function(){
                if(parseInt(document.getElementById("UsuAdm").value) < parseInt(document.getElementById("admIns").value)){ // nível administrativo
                    document.getElementById("botinserir").style.visibility = "hidden"; // botão de inserir
                }
                //Fecha caixa ao clicar na página
                modalMsg = document.getElementById('relacmodalMsg'); //span[0]
                spanMsg = document.getElementsByClassName("close")[0];
                window.onclick = function(event){
                    if(event.target === modalMsg){
                        modalMsg.style.display = "none";
                    }
                };
            });
            function ajaxIni(){
                try{
                ajax = new ActiveXObject("Microsoft.XMLHTTP");}
                catch(e){
                try{
                   ajax = new ActiveXObject("Msxml2.XMLHTTP");}
                   catch(ex) {
                   try{
                       ajax = new XMLHttpRequest();}
                       catch(exc) {
                          alert("Esse browser não tem recursos para uso do Ajax");
                          ajax = null;
                       }
                   }
                }
            }
            function PegaCod(Cod, Ativo){
                document.getElementById("guardaid").value = Cod; //Pega e guarda o código do elemento dragado
                document.getElementById("guardaAtiv").value = Ativo;
            }

            function allowDrop(ev) {
                ev.preventDefault();
            }

            function drag(ev) {
                ev.dataTransfer.setData("text", ev.target.id);
            }

            function drop(ev, col) {
                if(parseInt(col) > 0){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=mudaStatus&numero="+document.getElementById("guardaid").value+"&novoStatus="+col+"&usumodif="+document.getElementById("usu_Logado_id").value+"&guardaativ="+document.getElementById("guardaAtiv").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 1){
                                        alert("Houve um erro no servidor.")
                                    }else{
                                        $('#container3').load('modulos/conteudo/tarefas.php');
                                        ev.preventDefault();
                                        var data = ev.dataTransfer.getData("text");
                                        ev.target.appendChild(document.getElementById(data));
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }
            }
            function carregaModal(Cod){
                document.getElementById("guardaidEdit").value = Cod;
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=buscaTarefa&numero="+Cod, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");  //Lê o array que vem
                                document.getElementById("idExecSelect").value = Resp.usuExec;
                                document.getElementById("textoEvid").value = Resp.TitTarefa;
                                document.getElementById("textoExt").value = Resp.TextoTarefa;
                                document.getElementById("selecprio").value = Resp.priorid;
                                document.getElementById("selectStatus").value = Resp.sit;
                                if(parseInt(document.getElementById("usu_Logado_id").value) === parseInt(Resp.usuIns)){ // se for o usuário que inseriu a tarefa
                                    document.getElementById("selectStatus").disabled = false;
                                }
                                document.getElementById("labelnomeIns").innerHTML = "Inserida por: "+Resp.NomeUsuIns;
                                document.getElementById("relacmodalTarefa").style.display = "block";
                                document.getElementById("textoEvid").focus();
                            }
                        }
                    };
                    ajax.send(null);
                }
            }

            function abreModal(){ // inserir nova tarefa
                document.getElementById("idExecSelect").value = "";
                document.getElementById("textoEvid").value = "";
                document.getElementById("textoExt").value = "";
                document.getElementById("labelnomeIns").innerHTML = "";
                document.getElementById("selecprio").value = 2;                
                document.getElementById("guardaid").value = 0;
                document.getElementById("mudou").value = "1"; // vai inserir novo
                document.getElementById("botapagar").disabled = true;
                document.getElementById("selectStatus").disabled = true;
                document.getElementById("titulomodal").innerHTML = "Inserção de Tarefa";
                document.getElementById("relacmodalTarefa").style.display = "block";
            }
            function salvaModal(){
                if(document.getElementById("idExecSelect").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Escolha o <u>NOME</u> do destinatário da tarefa";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("textoEvid").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>TÍTULO</u> da tarefa";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(parseInt(document.getElementById("mudou").value) === 1){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=salvaTarefa&numero="+document.getElementById("guardaidEdit").value
                        +"&usuLogado="+document.getElementById("usu_Logado_id").value
                        +"&idExecSelect="+document.getElementById("idExecSelect").value
                        +"&selectStatus="+document.getElementById("selectStatus").value
                        +"&priorid="+document.getElementById("selecprio").value
                        +"&textoEvid="+encodeURIComponent(document.getElementById("textoEvid").value)
                        +"&textoExt="+encodeURIComponent(document.getElementById("textoExt").value), true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 0){
                                        document.getElementById("mudou").value = "0";
                                        document.getElementById("relacmodalTarefa").style.display = "none";
                                        $('#container3').load('modulos/conteudo/tarefas.php');
                                    }else{
                                        alert("Houve um erro no servidor.");
                                        document.getElementById("relacmodalTarefa").style.display = "none";
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }else{
                    document.getElementById("mudou").value = "0";
                    document.getElementById("relacmodalTarefa").style.display = "none";
                }
            }
            function fechaModalTarefa(){
                document.getElementById("relacmodalTarefa").style.display = "none";
            }
            function modif(){ // assinala se houve qualquer modificação nos campos do modal durante a edição para evitar salvar desnecessariamente
                document.getElementById("mudou").value = "1";
            }

            function deletaModal(){
                let Conf = confirm("Confirma apagar esse lançamento?");
                if(Conf){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=deletaTarefa&numero="+document.getElementById("guardaidEdit").value+"&usuLogado="+document.getElementById("usu_Logado_id").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//    alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 1){
                                        alert("Houve um erro no servidor.")
                                        document.getElementById("relacmodalTarefa").style.display = "none";
                                    }else{
                                        document.getElementById("mudou").value = "0";
                                        document.getElementById("relacmodalTarefa").style.display = "none";
                                        $('#container3').load('modulos/conteudo/tarefas.php');
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }
            }
            function carregaMsg(Cod){
                document.getElementById("guardaidEdit").value = Cod;
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=buscaMsg&numero="+Cod, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");  //Lê o array que vem
                                document.getElementById("titTarefa").innerHTML = Resp.TitTarefa;
                                $("#faixacentral").load("modulos/conteudo/jTarefa.php?numtarefa="+Cod+"&usulogadoid="+document.getElementById('usu_Logado_id').value+"&usulogadonome="+encodeURIComponent(document.getElementById('nome_Logado').value));
                                document.getElementById("relacmodalMsg").style.display = "block";
                                document.getElementById("novamensagem").focus();
                            }
                        }
                    };
                    ajax.send(null);
                }
            }
            function fechaModalMsg(){ // marca mensagem como lidas
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=marcalidas&numtarefa="+document.getElementById("guardaidEdit").value+"&nomeusuario="+document.getElementById("nome_Logado").value, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//     alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");  //Lê o array que vem
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor ao fechar as mensagens. Informe à ATI.")
                                }
                                $('#container3').load('modulos/conteudo/tarefas.php'); // para parar de piscar a ícone de tem mensagem
                            }
                        }
                    };
                    ajax.send(null);
                }
                document.getElementById("relacmodalMsg").style.display = "none";
            }
        </script>
    </head>
    <body>
    <?php
        require_once(dirname(dirname(__FILE__))."/dbclass.php");

        $Adm = $_SESSION["AdmUsu"];
        $UsuLogadoId = $_SESSION["usuarioID"];
        $UsuLogadoNome = $_SESSION["UsuLogadoNome"];

        $admIns = parAdm("insTarefa");   // nível para inserir
        $admEdit = parAdm("editTarefa"); // nível para editar

        //Para relacionar usuários - adm <= $Adm só paga tarefa para nível adm menor ou igual
        if($_SESSION['Conectado'] == "MySql"){
            $OpcoesUsers = mysqli_query($xVai, "SELECT id, nome FROM cesb_usuarios WHERE adm <= $Adm And idPessoa = 0 ORDER BY nome");
        }else{ // login feito no PostGres
            $OpcoesUsers = mysqli_query($xVai, "SELECT id, nome FROM cesb_usuarios WHERE adm <= $Adm And idPessoa > 0 ORDER BY nome");
        }
        //marca que foi visualizado nesta data - dataSit1
        mysqli_query($xVai, "UPDATE cesb_tarefas SET dataSit1 = NOW() WHERE usuExec = ".$_SESSION["usuarioID"]." And dataSit1 = '3000/12/31' And Ativo = 1");
    ?>
        <input type="hidden" id="guardaid" value="0" />
        <input type="hidden" id="guardaidEdit" value="0" />
        <input type="hidden" id="usu_Logado_id" value="<?php echo $_SESSION["usuarioID"]; ?>" />
        <input type="hidden" id="usu_Logado" value="<?php echo $_SESSION["UsuLogado"]; ?>" />
        <input type="hidden" id="nome_Logado" value="<?php echo $_SESSION["UsuLogadoNome"]; ?>" />
        <input type="hidden" id="UsuAdm" value="<?php echo $_SESSION["AdmUsu"]; ?>" />
        <input type="hidden" id="admIns" value="<?php echo $admIns; ?>" /> <!-- nível mínimo para inserir tarefas -->
        <input type="hidden" id="admEdit" value="<?php echo $admEdit; ?>" /> <!-- nível mínimo para inserir tarefas -->
        <input type="hidden" id="mudou" value="0" /> <!-- valor 1 quando houver mudança em qualquer campo do modal -->
        <input type="hidden" id="guardaAtiv" value="1" /> <!-- Guarda se a tarefa foi finalizada-->
        <input type="button" class="resetbot" id="botinserir" value="Inserir" onclick="abreModal();">

        <div style='margin: 20px; border: 3px solid green; border-radius: 10px;'>
            <table style="margin: 0 auto; border: 0; width: 80%;" >
            <caption>Arraste o quadro amarelo para a direita &#8594;</caption>
                <?php
                if($Adm == 7){ // Superusuários
                    $resultT = mysqli_query($xVai, "SELECT nome, idTar as chaveTar, cesb_tarefas.usuIns, usuExec, TitTarefa, TextoTarefa, Sit, cesb_tarefas.Ativo, date_format(cesb_tarefas.DataIns, '%d/%m/%Y') AS DataInsert, date_format(cesb_tarefas.dataSit1, '%d/%m/%Y %H:%i') AS DataVista, Prio FROM cesb_tarefas INNER JOIN cesb_usuarios ON cesb_tarefas.usuIns = cesb_usuarios.id WHERE cesb_tarefas.Ativo > 0 ORDER BY cesb_tarefas.Ativo, cesb_tarefas.Prio, cesb_tarefas.DataIns DESC, nome");
                }else{
                    $resultT = mysqli_query($xVai, "SELECT nome, idTar as chaveTar, cesb_tarefas.usuIns, usuExec, TitTarefa, TextoTarefa, Sit, cesb_tarefas.Ativo, date_format(cesb_tarefas.DataIns, '%d/%m/%Y') AS DataInsert, date_format(cesb_tarefas.dataSit1, '%d/%m/%Y %H:%i') AS DataVista, Prio FROM cesb_tarefas INNER JOIN cesb_usuarios ON cesb_tarefas.usuIns = cesb_usuarios.id WHERE cesb_tarefas.Ativo > 0 And usuExec = $UsuLogadoId OR cesb_tarefas.Ativo > 0 And cesb_tarefas.usuIns = $UsuLogadoId ORDER BY cesb_tarefas.Ativo, cesb_tarefas.Prio, cesb_tarefas.DataIns DESC, nome");
                }
                //Cabeçalho
                echo "<tr>";
                echo "<td></td>";
                echo "<td style='text-align: center; font-weight: 600;'>Tarefa<br>Designada</td>";
                echo "<td></td>";
                echo "<td style='text-align: center; font-weight: 600;'>Tarefa<br>Recebida</td>";
                echo "<td></td>";
                echo "<td style='text-align: center; font-weight: 600;'>Tarefa<br>Em Andamento</td>";
                echo "<td></td>";
                echo "<td style='text-align: center; font-weight: 600;'>Tarefa<br>Terminada</td>";
                echo "</tr>";
                $row = mysqli_num_rows($resultT);

                if($row > 0){
                    While ($tbl = mysqli_fetch_array($resultT)){
                        $idTar = $tbl["chaveTar"];
                        $usuIns = $tbl["usuIns"];
                        $usuExec = $tbl["usuExec"];
                        $Status = $tbl["Sit"];
                        $Titulo = $tbl["TitTarefa"];
                        $Texto = $tbl["TextoTarefa"];
                        $Ativo = $tbl["Ativo"]; // 0 = Apagado   1 = Ativo   2 = arquivado
                        $DataInsert = $tbl["DataInsert"];
                        $DataVisu = $tbl["DataVista"];
                        if($DataVisu == "31/12/3000 00:00"){
                            $DataVisu = "";
                        }
                        $Priorid = $tbl["Prio"];

                        $rs1 = mysqli_query($xVai, "SELECT nomeCompl, nome FROM cesb_usuarios WHERE id = $usuIns");
                        $Proc1 = mysqli_fetch_array($rs1);
                        $NomeIns = $Proc1["nome"];
                        $rs2 = mysqli_query($xVai, "SELECT nomeCompl, nome FROM cesb_usuarios WHERE id = $usuExec");
                        $Proc2 = mysqli_fetch_array($rs2);
                        $NomeExec = $Proc2["nome"];

                        $rs3 = mysqli_query($xVai, "SELECT idMsg FROM cesb_tarefas_msg WHERE IdTarefa = $idTar");
                        $TemMsg = mysqli_num_rows($rs3); // ver se tem mensagem para essa tarefa


                        $row4 = 0;
                        $row6 = 0;
                        $rs3 = mysqli_query($xVai, "SELECT usuIns FROM cesb_tarefas WHERE idTar = $idTar And usuIns = ".$_SESSION["usuarioID"]);
                        $row3 = mysqli_num_rows($rs3); // ver se foi o usu logado que inseriu a tarefa
                        if($row3 > 0){ // foi o usuário logado que inseriu
                            $rs4 = mysqli_query($xVai, "SELECT insLido FROM cesb_tarefas_msg WHERE IdTarefa = $idTar And insLido = 0"); // procura mensagens não lidas como usuIns para essa tarefa
                            $row4 = mysqli_num_rows($rs4); // quantid mensagens não lidas como usuIns
                        }
                        $rs5 = mysqli_query($xVai, "SELECT usuExec FROM cesb_tarefas WHERE idTar = $idTar And usuExec = ".$_SESSION["usuarioID"]);
                        $row5 = mysqli_num_rows($rs5); // ver se foi o usu logado que recebeu a tarefa
                        if($row5 > 0){ // foi o usuário logado que recebeu
                            $rs6 = mysqli_query($xVai, "SELECT execLido FROM cesb_tarefas_msg WHERE IdTarefa = $idTar And execLido = 0"); // procura mensagens não lidas como usuIns para essa tarefa
                            $row6 = mysqli_num_rows($rs6); // quantid mensagens não lidas como usuExec
                        }
//echo $_SESSION["usuarioID"]."- ".$row4."<br>";
//echo $_SESSION["usuarioID"]."- ".$row6."<br>";


                        echo "<tr>";  //Primeira coluna à esquerda - data e nomes
                        echo "<td style='vertical-align: top;'><div style='padding-bottom: 8px; padding-top: 2px; color: #808080;'><sup>Em $DataInsert para:</sup></div>";
                            echo "<div class='etiqLat'>" . $NomeExec;
                            echo "<div style='position: relative; top: -10px; font-size: .5em; text-align: center;'> <sub>Visto em: " . $DataVisu . "</sub></div>";
                        echo "</div>";
                        echo "<div><sub>Pedido de: " . $NomeIns . "</sub></div>";
                        echo "</td>";

                        echo "<td>";
                        if($Status == 1 && $Ativo != 2){
                            echo "<div class='etiqueta etiqAtiva' draggable='true' droppable='true' id='posicaotit' ondrag='PegaCod($idTar, $Ativo);' ondrop='drop(event)' ondragover='allowDrop(event)' title='Arraste o quadro amarelo para a direita'>$Titulo</div>";
                        }elseif($Status == 1 && $Ativo == 2){
                            echo "<div class='etiqueta etiqInativa' draggable='false' droppable='false'>$Titulo</div>";
                        }else{
                            echo "<div class='etiqueta etiqInat' draggable='false' droppable='true' ondrop='drop(event, 1)' ondragover='allowDrop(event)' </div>";
                        }
                        echo "</td>";

                        echo "<td title='Arraste o quadro amarelo para a direita'>&#10144;</td>";

                        echo "<td>";
                        if($Status == 2 && $Ativo != 2){
                            echo "<div class='etiqueta etiqAtiva' draggable='true' droppable='true' id='posicaotit' ondrag='PegaCod($idTar, $Ativo);' ondrop='drop(event)' ondragover='allowDrop(event)' title='Arraste o quadro amarelo para a direita'>$Titulo</div>";
                        }elseif($Status == 2 && $Ativo == 2){
                            echo "<div class='etiqueta etiqInativa' draggable='false' droppable='false'>$Titulo</div>";
                        }else{
                            echo "<div class='etiqueta etiqInat' draggable='false' droppable='true' ondrop='drop(event, 2)' ondragover='allowDrop(event)'   </div>";
                        }
                        echo "</td>";

                        echo "<td title='Arraste o quadro amarelo para a direita'>&#10144;</td>";

                        echo "<td>";
                        if($Status == 3 && $Ativo != 2){
                            echo "<div class='etiqueta etiqAtiva' draggable='true' droppable='true' id='posicaotit' ondrag='PegaCod($idTar, $Ativo);' ondrop='drop(event)' ondragover='allowDrop(event)' title='Arraste o quadro amarelo para a direita'>$Titulo</div>";
                        }elseif($Status == 3 && $Ativo == 2){
                            echo "<div class='etiqueta etiqInativa' draggable='false' droppable='false'>$Titulo</div>";
                        }else{
                            echo "<div class='etiqueta etiqInat' draggable='false' droppable='true' ondrop='drop(event, 3)' ondragover='allowDrop(event)'   </div>";
                        }
                        echo "</td>";

                        echo "<td title='Arraste o quadro amarelo para a direita'>&#10144;</td>";

                        echo "<td>";
                        if($Status == 4 && $Ativo != 2){
                            echo "<div class='etiqueta etiqAtiva' draggable='true' droppable='true' id='posicaotit' ondrag='PegaCod($idTar, $Ativo);' ondrop='drop(dr)' ondragover='allowDrop(event)' title='Arraste o quadro amarelo para a direita'>$Titulo</div>";
                            
                        }elseif($Status == 4 && $Ativo == 2){
                            echo "<div class='etiqueta etiqInativa' draggable='false' droppable='false'>$Titulo</div>";
                        }else{
                            echo "<div class='etiqueta etiqInat' draggable='false' droppable='true' ondrop='drop(event, 4)' ondragover='allowDrop(event)' </div>";
                        }

                        if($Ativo != 2){ // Mostrar prioridade da tarefa
                            if($Priorid == 0){
                                echo "<p class='blink' style='font-family: Trebuchet MS, Verdana, sans-serif; letter-spacing: 5px; color: red; font-size: 1.5em; font-weigth: bold; margin: 0; padding: 0;'><br><br>URGENTE</p>";
                            }
                            if($Priorid == 1){
                                echo "<p style='font-family: Trebuchet MS, Verdana, sans-serif; letter-spacing: 5px; color: red; font-size: 1.2em; font-weigth: bold; margin: 0; padding: 0;'><br><br>IMPORTANTE</p>";
                            }
                        }

                        echo "</td>";

                        echo "<td>";  

                        if($Adm >= $admEdit && $usuExec != $UsuLogadoId){ // usuário logado não pode ser o executante
                            echo "<div title='Editar' style='cursor: pointer;' onclick='carregaModal($idTar);'>&#9997;</div>";
                        }

                        echo "<div title='Mensagens' style='cursor: pointer;' onclick='carregaMsg($idTar);'>";
                            if($row4 > 0 || $row6 > 0){
                                echo "<p class='blink'>&#9993;</p>";
                            }else{
                                echo "<p>&#9993;</p>";
                            }
                            echo "</div>";
                        echo "</td>";
                        
                        echo "</tr>";
                    }
                }else{
                    echo "<tr>";
                        echo "<td colspan='8' style='text-align: center; font-weight: 800; color: blue; border: 1px solid;'>Nenhuma Tarefa Designada para $UsuLogadoNome</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <?php
        mysqli_close($xVai); // fecha a conexão
        ?>

        <!-- div modal para edição e inserção de tarefa -->
        <div id="relacmodalTarefa" class="relacmodal">  <!-- ("close")[0] -->
            <div class="modalTarefa-content">
                <span class="close" onclick="fechaModalTarefa();">&times;</span>
                <h3 id="titulomodal" style="text-align: center; color: #666;">Edição Inserção de Tarefas</h3>

                <table style="margin: 0 auto;">
                    <tr>
                        <td id="etiqIdExec" class="etiq">Tarefa para:</td>
                        <td colspan='4' >
                            <select id="idExecSelect" style="font-size: 1rem; width: 200px;" title="Selecione um usuário.">
                            <option value= ""></option>
                            <?php 
                            if($OpcoesUsers){
                                while ($Opcoes = mysqli_fetch_array($OpcoesUsers)){ ?>
                                    <option value="<?php echo $Opcoes['id']; ?>"><?php echo $Opcoes['nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                            <label id="labelnomeIns" class="etiq" style="padding-left: 30px;"></label>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td id="etiqTextoEvid" class="etiq">Tarefa:</td>
                        <td colspan='4' rowspan='3' style="min-width: 500px;"><textarea id="textoEvid" rows='3' placeholder="Descrição sucinta" onchange="modif();" style="font-size:95%; width: 60%;"></textarea>
                    </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td id="etiqTextoEvid" class="etiq">Prioridade:</td>
                        <td>  <!-- &nabla; -->
                            <select id="selecprio" title="Prioridade da tarefa" onchange="modif();">
                                <option value='0'>Urgente</option>
                                <option value='1'>Importante</option>
                                <option value='2'>Ordinário</option>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr> 
                    <tr>
                        <td></td>
                        <td></td>
                    </tr> 
                    <tr>
                        <td id="etiqTextoExt" class="etiq">Memória:</td>
                        <td colspan='4' rowspan='6'><textarea id="textoExt" rows='6' placeholder="Memoire" onchange="modif();" style="font-size: 95%; width: 98%;"></textarea></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td id="etiqSelecStatus" class="etiq">Mudar Status:</td>
                        <td>
                            <select id="selectStatus" onchange="modif();" title="Seleciona o novo status para essa tarefa">
                                <option value="1">Designada</option>
                                <option value="2">Recebida</option>
                                <option value="3">Andamento</option>
                                <option value="4">Terminada</option>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: center;"><div id="mensagem" style="color: red; font-weight: bold;"></div></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="etiq" style="text-align: left;"><input type="button" class="resetbotred" id="botapagar" value="Apagar" onclick="deletaModal();"></td>
                        <td colspan='4' style="text-align: right; padding-right: 50px;"><input type="button" class="resetbotazul" id="salvar" value="Salvar" onclick="salvaModal();"></td>
                        <td></td>
                    </tr>
                </table>
           </div>
        </div> <!-- Fim Modal Tarefa-->

        <!-- div modal para leitura e inserção de mensagens -->
        <div id="relacmodalMsg" class="relacmodal">  <!-- ("close")[0] -->
            <div class="modalMsg-content">
                <span class="close" onclick="fechaModalMsg();">&times;</span>
                <h3 id="titulomodalMsg" style="text-align: center; color: #666;">Mensagens</h3>
                <div style="border: 1px solid; border-radius: 10px; margin: 5px; padding: 5px;">
                    <table>
                        <tr>
                            <td class="etiq">Tarefa: </td>
                            <td><div id='titTarefa'></div></td>
                        </tr>
<!--                        <tr>
                            <td class="etiq">Descrição:</td>
                            <td><div id='textoTarefa'></div></td>
                        </tr>
-->
                    </table>
                </div>
                <div id="faixacentral" style='border: 1px solid; border-radius: 10px;'></div> <!-- aqui entra jTarefa.php -->
            </div>
        </div>  <!-- Fim Modal Mensagens-->
    </body>
</html>