<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style type="text/css">
        </style>
        <script>
            function enviaMsg(idTar, idUsu){
                if(document.getElementById("novamensagem").value === ""){
                    $('#jmensagem').fadeIn("slow");
                    document.getElementById("jmensagem").innerHTML = "Digite uma mensagem";
                    $('#jmensagem').fadeOut(2000);
                    return false;
                }
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=salvaMensagem&numtarefa="+idTar+"&numusuario="+idUsu+"&textoExt="+encodeURIComponent(document.getElementById('novamensagem').value), true);
                        ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText); 
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 0){
                                    $("#faixacentral").load("modulos/conteudo/jTarefa.php?numtarefa="+idTar+"&usulogadoid="+idUsu);
                                }else{
                                    alert("Houve um erro no servidor.");
                                    document.getElementById("relacmodalMsg").style.display = "none";
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }
            function apagaMsg(Cod, idTar, idUsu){
                a = confirm("Confirma apagar esta mensagem?");
                if(!a){
                    return false;
                }
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/conteudo/salvaTarefa.php?acao=apagaMensagem&numMsg="+Cod, true);
                        ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText); 
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 0){
                                    $("#faixacentral").load("modulos/conteudo/jTarefa.php?numtarefa="+idTar+"&usulogadoid="+idUsu);
                                }else{
                                    alert("Houve um erro no servidor.");
                                    document.getElementById("relacmodalMsg").style.display = "none";
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }
        </script>
    </head>
    <body>
        <?php
            require_once(dirname(dirname(__FILE__))."/dbclass.php");
            $UsuLogado = "";
            $IdTarefa = $_REQUEST["numtarefa"];
            $UsuLogadoId = $_REQUEST["usulogadoid"];
            // marcar como lidas as mensagens da tarefa
            mysqli_query($xVai, "UPDATE cesb_tarefas_msg SET insLido = 1 WHERE IdTarefa = $IdTarefa And usuInsTar = $UsuLogadoId");
            mysqli_query($xVai, "UPDATE cesb_tarefas_msg SET execLido = 1 WHERE IdTarefa = $IdTarefa And usuExecTar = $UsuLogadoId");
        ?>
        <table style="margin: 0 auto;">
            <tr>
                <td style="width: 20%;"></td>
                <td style="width: 70%; text-align: center; font-weight: bold;">- Mensagens -</td>
                <td style="width: 10%;"></td>
            </tr>
            <?php
            $rs = mysqli_query($xVai, "SELECT idMsg, nome, idTarefa, textoMsg, date_format(dataMsg, '%d/%m/%Y %H:%i') AS DataMensagem, cesb_tarefas_msg.idUser FROM cesb_usuarios INNER JOIN (cesb_tarefas INNER JOIN cesb_tarefas_msg ON cesb_tarefas.idTar = cesb_tarefas_msg.idTarefa) ON cesb_usuarios.id = cesb_tarefas_msg.idUser WHERE cesb_tarefas_msg.Elim = 0 And idTarefa = $IdTarefa");
            $row = mysqli_num_rows($rs);
            if($row > 0){
                While ($tbl = mysqli_fetch_array($rs)){
                    $Cod = $tbl["idMsg"];
                    $MsgUser = $tbl["idUser"]; // quem inseriu a mensagem
                    $Nome = $tbl["nome"];
                    $DataMsg = $tbl["DataMensagem"];
                    $Msg = nl2br($tbl["textoMsg"]);

                    echo "<tr>";
                    echo "<td style='font-size: .7rem; text-align: center;'>$DataMsg <br>  $Nome</td>";
                    echo "<td><div style='border: 1px outset; border-radius: 5px; padding: 4px;'>$Msg</div></td>";
                    if($MsgUser == $UsuLogadoId){ // quem escreveu a msg pode apagar
                        echo "<td style='text-align: center;'><div style='cursor: pointer;' onclick='apagaMsg($Cod, $IdTarefa, $UsuLogadoId);' title='Apagar mensagem'> &#128465; </div></td>"; // Wastebasket &#128465;
                    }else{
                        echo "<td></td>";
                    }
                    echo "</tr>";
                }
            }else{
                echo "<tr>";
                echo "<td></td>";
                echo "<td style='text-align: center;'>Nenhuma mensagem.</td>";
                echo "<td></td>";
                echo "</tr>";
            }
            ?>
            <tr>
                <td class="etiq">Mensagem:</td>
                <td><textarea id='novamensagem' placeholder='Mensagem' rows='2' cols='45'></textarea></td>
                <td style="text-align: center;"><input type="button" class="resetbot" style="color: blue; font-weight: bold; font-size: .7rem;" id="botenviar" value="Enviar" onclick="enviaMsg(<?php echo $IdTarefa; ?>, <?php echo $UsuLogadoId; ?>);" title="Enviar mensagem."></td>
            </tr>
            <tr>
                <td></td>
                <td><div id="jmensagem" style="color: red; font-weight: bold; text-align: center;"></div></td>
                <td></td>
            </tr>
        </table>
    </body>
</html>