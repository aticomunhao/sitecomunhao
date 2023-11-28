<?php
session_start(); 
if(!isset($_SESSION["usuarioID"])){
    header("Location: /cesb/index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link rel="stylesheet" type="text/css" media="screen" href="class/dataTable/datatables.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/relacmod.css" />
        <script src="class/tinymce5/tinymce.min.js"></script>
        <script src="comp/js/jquery.min.js"></script> <!-- versão 3.6.3 -->
        <script src="comp/js/jquery.mask.js"></script>
        <script src="class/dataTable/datatables.min.js"></script>

        <style type="text/css">
            #container5{
                width: 25%;
                min-height: 300px;
                margin: 10px;
                margin-top: 12px;
                border: 2px solid; 
                border-radius: 10px; 
                padding: 10px;
            }
            #container6{
                width: 70%;
                min-height: 300px;
                margin: 10px;
                margin-top: 12px;
                border: 2px solid; 
                border-radius: 10px; 
                padding: 10px;
            }
            .cContainer{ /* encapsula uma frase no topo de uma div em reArq.php e pagArq.php */
                position: absolute; 
                left: 20px;
                margin-top: -20px; 
                border: 1px solid blue; 
                border-radius: 10px; 
                padding-left: 10px; 
                padding-right: 10px; 
            }
            .bContainer{ /* botão upload */
                position: absolute; 
                right: 30px;
                margin-top: -20px; 
                border: 1px solid blue;
                background-color: blue;
                color: white;
                cursor: pointer;
                border-radius: 10px; 
                padding-left: 10px; 
                padding-right: 10px; 
            }
            .ideogr{
                display: inline;
                margin: 5px;
            }
            tr td{
                border: 0px solid;
            }
            
        </style>
        <script type="text/javascript">
            new DataTable('#idTabela', {
                columnDefs: [
                {
                    targets: [2],
                    orderData: [1, 0]
                },
                {
                    targets: [5],
                    "orderable": false
                },
                {
                    targets: [6],
                    "orderable": false
                }
                ],
                lengthMenu: [
                    [50, 100, 200, 500],
                    [50, 100, 200, 500]
                ],
                language: {
                    info: 'Mostrando Página _PAGE_ of _PAGES_',
                    infoEmpty: 'Nenhum registro encontrado',
                    infoFiltered: '(filtrado de _MAX_ registros)',
                    lengthMenu: 'Mostrando _MENU_ registros por página',
                    zeroRecords: 'Nada foi encontrado'
                }
            });
            table = new DataTable('#idTabela');
            table.on('click', 'tbody tr', function () {
                data = table.row(this).data();
                $id = data[1];
                document.getElementById("guardacod").value = $id;                
                if($id !== 0){
                    if(parseInt(document.getElementById("UsuAdm").value) >= parseInt(document.getElementById("admEditOcor").value)){
                        carregaModal($id);
                    }
                }
            });

            $(document).ready(function(){
                if(parseInt(document.getElementById("UsuAdm").value) < parseInt(document.getElementById("admInsOcor").value)){
                    document.getElementById("botinserir").style.visibility = "hidden"; // botão de inserir
                } 
            });

            function regOcor(){
                $("#container5").load("modulos/ocorrencias/relIdeogr.php");
                $("#container6").load("modulos/ocorrencias/insOcor.php");
                document.getElementById("relacmodalOcor").style.display = "block";
            }

            function fechaModal(){
                if(parseInt(document.getElementById("mudou").value) === 1){
                    a = confirm("Há modificações que não foram salvas. As informações serão perdidas. Continua?");
                    if(a){
                        ajaxIni();
                        if(ajax){
                            ajax.open("POST", "modulos/ocorrencias/salvaOcor.php?acao=sairSemSalvar", true);
                            ajax.onreadystatechange = function(){
                                if(ajax.readyState === 4 ){
                                    if(ajax.responseText){
//alert(ajax.responseText);
                                        Resp = eval("(" + ajax.responseText + ")");
                                        if(parseInt(Resp.coderro) === 1){
                                            alert("Houve um erro no servidor.")
                                        }else{
                                            document.getElementById("mudou").value = "0";
                                            document.getElementById("guardacod").value = "0";
                                            document.getElementById("relacmodalOcor").style.display = "none";
                                        }
                                    }
                                }
                            };
                            ajax.send(null);
                        }
                    }
                }else if(parseInt(document.getElementById("mudou").value) === 2){
                    $('#container3').load('modulos/ocorrencias/pagOcor.php');
                }else{
                    document.getElementById("mudou").value = "0";
                    document.getElementById("relacmodalOcor").style.display = "none";
                }
            }

            function salvaModal(){
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/ocorrencias/salvaOcor.php?acao=salvaOcor&datains="+encodeURIComponent(document.getElementById("dataocor").value)+"&textoocorrencia="+encodeURIComponent(document.getElementById("textoocorrencia").value)+"&codigo="+document.getElementById("guardacod").value, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor.")
                                }else{
                                    document.getElementById("mudou").value = "0";
                                    if(parseInt(Resp.codigonovo) !== 0){
                                        document.getElementById("guardacod").value = Resp.codigonovo;
                                    }
                                    document.getElementById("relacmodalOcor").style.display = "none";
                                    $('#container3').load('modulos/ocorrencias/pagOcor.php');
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }

            function modif(){ // assinala se houve qualquer modificação nos campos do modal
                document.getElementById("mudou").value = "1";
            }

            function carregaModal(Cod){
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/ocorrencias/salvaOcor.php?acao=buscaOcorr&codigo="+Cod, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor.")
                                }else{
                                    document.getElementById("guardacod").value = Cod;
                                    $("#container5").load("modulos/ocorrencias/relIdeogr.php");
                                    $("#container6").load("modulos/ocorrencias/insOcor.php");
                                    a= confirm("Confirma editar esta ocorrência?");
                                    if(a){
                                        $("#mostraideogr").load("modulos/ocorrencias/carIdeogr.php?codocor="+Cod);
                                        document.getElementById("relacmodalOcor").style.display = "block";
                                        document.getElementById("dataocor").value = Resp.data;
                                        document.getElementById("textoocorrencia").value = Resp.texto;
                                    }
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }
            function apagaArq(Cod){
                a = confirm("Confirma apagar esta imagem?");
                if(!a){
                    return false;
                }
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/ocorrencias/salvaOcor.php?acao=apagaIdeogr&codigo="+Cod, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor.")
                                }else{
                                    document.getElementById("mudou").value = "2";
                                    $("#mostraideogr").load("modulos/ocorrencias/carIdeogr.php?codocor="+document.getElementById("guardacod").value);
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
        <div style="margin: 6px; padding: 10px; min-height: 500px; border: 2px solid blue; border-radius: 15px; text-align: center;">
            <h3>Registro de Ocorrências</h3>
            <div class="box" style="position: absolute; left: 30px; top: 25px;">
                <input type="button" id="botinserir" class="resetbot" value="Registrar Ocorrência" onclick="regOcor();">
            </div>
            <hr>

            <?php
            date_default_timezone_set('America/Sao_Paulo');
            require_once(dirname(dirname(__FILE__))."/dbclass.php");
            $admIns = parAdm("insOcor");   // nível para inserir 
            $admEdit = parAdm("editOcor"); // nível para editar
            $hoje = date('d/m/Y');

            //Conta quantos registros já fez
            $rsQuant = mysqli_query($xVai, "SELECT CodOcor FROM cesb_ocorrencias WHERE Ativo = 1 And usuIns = ".$_SESSION["usuarioID"]." ORDER BY dataIns");
            $Quant = mysqli_num_rows($rsQuant);
    
            if($_SESSION["AdmUsu"] > 6){
                $rs0 = mysqli_query($xVai, "SELECT CodOcor, date_format(dataOcor, '%d/%m/%Y') AS DataOcorrencia, date_format(dataIns, '%d/%m/%Y %H:%i') AS DataInsert, Ocorrencia, usuIns, NumOcor FROM cesb_ocorrencias WHERE Ativo = 1 ORDER BY dataIns DESC LIMIT 50");
            }else{    
                $rs0 = mysqli_query($xVai, "SELECT CodOcor, date_format(dataOcor, '%d/%m/%Y') AS DataOcorrencia, date_format(dataIns, '%d/%m/%Y %H:%i') AS DataInsert, Ocorrencia, usuIns, NumOcor FROM cesb_ocorrencias WHERE usuIns = ".$_SESSION["usuarioID"]." And Ativo = 1 ORDER BY dataIns DESC");
            }
            $row0 = mysqli_num_rows($rs0);
            if($row0 > 0){
                ?>
                <table id="idTabela" class="display" style="width:85%">
                <thead>
                    <tr>
                        <th style="display: none;">-</th>
                        <th style="display: none;"></th>
                        <th style="text-align: center;">Nº Ocorrência</th>
                        <th style="text-align: center;">Data Ins</th>
                        <th style="text-align: center;">Data Ocor</th>
                        <th style="text-align: center;">Ideogramas</th>
                        <th style="text-align: center;">Ocorrência</th>
                        <th style="text-align: center;">Usuário</th>
<!--                        <th style="text-align: center;">Modif</th> -->
                    </tr>
                </thead>
                <tbody>

                <?php 

                while ($Tbl0 = mysqli_fetch_array($rs0)){
                    $CodOcor = $Tbl0["CodOcor"];
                    $NumOcor = $Tbl0["NumOcor"];
                    $DataIns = $Tbl0["DataInsert"];
                    $CodUsu = $Tbl0["usuIns"];
                    
                    if($DataIns == "31/12/3000 00:00"){
                        $DataIns = "";
                    }
                    $DataOcor = $Tbl0["DataOcorrencia"];
                    if(is_null($Tbl0["Ocorrencia"])){
                        $Ocor = "";    
                    }else{
                        $Ocor = nl2br($Tbl0["Ocorrencia"]);                  
                    }
                ?>
                    <tr>
                        <td style="display: none;"></td>
                        <td style="display: none;"><?php echo $CodOcor; ?></td>
                        <td><?php echo $NumOcor; ?></td>
                        <td><?php echo $DataIns; ?></td>
                        <td><?php echo $DataOcor; ?></td>
                        <td><?php  
                    
                        $rsIdeo = mysqli_query($xVai, "SELECT descIdeo FROM cesb_ocorrideogr WHERE CoddaOcor = $CodOcor ORDER BY CodIdeo");
                        $rowIdeo = mysqli_num_rows($rsIdeo);
                        if($rowIdeo > 0){
                            while ($TblIdeo = mysqli_fetch_array($rsIdeo)){
                                $Ideogr = $TblIdeo["descIdeo"];
                                echo "<div style='display: inline; padding: 2px;'><img src='$Ideogr' width='40px' height='40px;'></div>";
                            }
                        }
                        ?>
                        </td>
                        <td style="text-align: left;"><?php echo $Ocor; ?></td>
                        <td style="text-align: center;"><?php echo $CodUsu; ?></td>
<!--                        <td style="text-align: center;">
                            <div title='Editar' style='cursor: pointer;' onclick='carregaModal($CodOcor);'>&#9997;</div>
                        </td>
-->
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            }else{
                echo "Nenhuma ocorrência registrada por ".$_SESSION["UsuLogadoNome"];
            }
            ?>
        </div>
        <input type="text" id="UsuAdm" value="<?php echo $_SESSION["AdmUsu"] ?>" />
        <input type="text" id="admInsOcor" value="<?php echo $admIns ?>" />
        <input type="text" id="admEditOcor" value="<?php echo $admEdit ?>" />
        <input type="hidden" id="admIns" value="<?php echo $admIns; ?>" /> <!-- nível mínimo para inserir  -->
        <input type="hidden" id="admEdit" value="<?php echo $admEdit; ?>" /> <!-- nível mínimo para editar -->
        <input type="hidden" id="guardacod" value="0" /> <!-- id recém inserido -->
        <input type="hidden" id="mudou" value="0" />
        
        <!-- div modal para registrar ocorrêencia  -->
        <div id="relacmodalOcor" class="relacmodal">
            <div class="modal-content-InsOcor">
                <span class="close" onclick="fechaModal();">&times;</span>
                <h3 id="titulomodal" style="text-align: center; color: #666;">Registrar Ocorrência</h3>
                <div style="border: 2px solid blue; border-radius: 10px;">
                
                    <div id="container5"></div>
                    <div id="container6"></div>

                    <div style="text-align: center;">
                        <button class="resetbotazul" onclick="salvaModal();">Salvar</button>
                    </div>
                </div>
           </div>
        </div> <!-- Fim Modal-->

    </body>
</html>