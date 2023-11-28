<?php
session_start();
if(!isset($_SESSION["usuarioID"])){
    header("Location: /cesb/index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" media="screen" href="class/dataTable/datatables.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/relacmod.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/indlog.css" />
        <script src="class/dataTable/datatables.min.js"></script>
        <script src="class/superfish/js/jquery.js"></script><!-- versão 1.12.1 veio com o superfish - tem que usar esta, a versão 3.6 não recarrega a página-->
        <script>
            // config DataTable
            new DataTable('#idTabela', {
                columnDefs: [
                {
                    targets: [0],
                    orderData: [0, 1]
                },
                {
                    targets: [1],
                    orderData: [1, 0]
                },
                {
                    targets: [4],
                    orderData: [4, 0]
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
                document.getElementById("guardaid_click").value = $id;
                if($id !== ""){
                    if(parseInt(document.getElementById("UsuAdm").value) >= parseInt(document.getElementById("admEdit").value)){ // nível adm
                        carregaModal($id);
                    }
                }
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

            $(document).ready(function(){
                if(parseInt(document.getElementById("UsuAdm").value) < parseInt(document.getElementById("admIns").value)){
                    document.getElementById("botapagar").style.visibility = "hidden"; // botão para apagar
                    document.getElementById("botinserir").style.visibility = "hidden"; // botão de inserir
                }
                modalEdit = document.getElementById('relacmodal'); //span[0]
                spanEdit = document.getElementsByClassName("close")[0];
                window.onclick = function(event){
                    if(event.target === modalEdit){
                        modalEdit.style.display = "none";
                    }
                };
            });

            function carregaModal(id){
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/salvaRamais.php?acao=buscaRamal&tipo=1&numero="+id, true); // tipo 1 = ramal interno
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");  //Lê o array que vem
                                document.getElementById("usuario").value = Resp.usuario;
                                document.getElementById("nomecompl").value = Resp.nomecompl;
                                document.getElementById("setor").value = Resp.setor;
                                document.getElementById("ramal").value = Resp.ramal;
                                document.getElementById("titulomodal").innerHTML = "Edição de Ramal Telefônico";
                                document.getElementById("botapagar").disabled = false;
                                document.getElementById("mudou").value = "0";
                                document.getElementById("relacmodal").style.display = "block";
                                document.getElementById("usuario").focus();
                            }
                        }
                    };
                    ajax.send(null);
                }
            }
            function salvaModal(){
                if(document.getElementById("usuario").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo Nome Usual";
                    $('#mensagem').fadeOut(3000);
                    return false;
                }
                if(document.getElementById("nomecompl").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo Nome Completo";
                    $('#mensagem').fadeOut(3000);
                    return false;
                }
                if(document.getElementById("setor").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo Setor";
                    $('#mensagem').fadeOut(3000);
                    return false;
                }
                if(document.getElementById("ramal").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo Ramal";
                    $('#mensagem').fadeOut(3000);
                    return false;
                }
                //Se houve alguma modificação
                if(parseInt(document.getElementById("mudou").value) === 1){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/salvaRamais.php?acao=salvaRamal&tipo=1&numero="+document.getElementById("guardaid_click").value
                        +"&usuario="+document.getElementById("usuario").value
                        +"&nomecompl="+document.getElementById("nomecompl").value
                        +"&setor="+document.getElementById("setor").value
                        +"&ramal="+document.getElementById("ramal").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 2){
                                        $('#mensagem').fadeIn("slow");
                                        document.getElementById("mensagem").innerHTML = "Esse nome já existe.";
                                        $('#mensagem').fadeOut("slow");
                                    }else{
                                        document.getElementById("mudou").value = "0";
                                        document.getElementById("relacmodal").style.display = "none";
                                        $('#container3').load('modulos/ramaisInt.php');
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }else{
                    document.getElementById("mudou").value = "0";
                    document.getElementById("relacmodal").style.display = "none";
                }
            }
            function fechaModal(){
                document.getElementById("guardaid_click").value = 0;
                document.getElementById("relacmodal").style.display = "none";
            }
            function InsRamais(){
                document.getElementById("usuario").value = "";
                document.getElementById("nomecompl").value = "";
                document.getElementById("setor").value = "";
                document.getElementById("ramal").value = "";
                document.getElementById("guardaid_click").value = 0;
                document.getElementById("botapagar").disabled = true;
                document.getElementById("titulomodal").innerHTML = "Inserção de Ramal Telefônico";
                document.getElementById("relacmodal").style.display = "block";
            }
            function deletaModal(){
                let Conf = confirm("Confirma apagar esse lançamento?");
                if(Conf){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/salvaRamais.php?acao=deletaRamal&tipo=1&numero="+document.getElementById("guardaid_click").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 1){
                                        alert("Houve um erro no servidor.")
                                    }else{
                                        document.getElementById("mudou").value = "0";
                                        document.getElementById("relacmodal").style.display = "none";
                                        $('#container3').load('modulos/ramaisInt.php');
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }
            }
            function foco(id){
                document.getElementById(id).focus();
            }
            function modif(){ // assinala se houve qualquer modificação nos campos do modal
                document.getElementById("mudou").value = "1";
            }
        </script>
    </head>
    <body>
        <?php
        require_once("dbclass.php");
        $Tipo = (int) filter_input(INPUT_GET, 'tipo');
        if($Tipo == 2){
            $idAdm = $_SESSION["AdmUsu"];
        }else{
            $idAdm = 0;
        }

        $admIns = parAdm("insRamais");   // nível para inserir 
        $admEdit = parAdm("editRamais"); // nível para editar

        $rs0 = mysqli_query($xVai, "SELECT CodTel, nomeusu, nomecompl, ramal, setor FROM cesb_ramais_int WHERE nomeusu != '' And Ativo = 1 ORDER BY nomeusu");
        $row0 = mysqli_num_rows($rs0);
        ?>
        <input type="hidden" id="tipo_acesso" value="<?php echo $Tipo; ?>" />
        <input type="hidden" id="UsuAdm" value="<?php echo $idAdm; ?>" />

        <input type="hidden" id="admIns" value="<?php echo $admIns; ?>" /> <!-- nível mínimo para inserir -->
        <input type="hidden" id="admEdit" value="<?php echo $admEdit; ?>" /> <!-- nível mínimo para editar -->

        <input type="hidden" id="guardaid_click" value="0" />
        <input type="hidden" id="mudou" value="0" /> <!-- valor 1 quando houver mudança em qualquer campo do modal -->

        <div style="margin: 20px; border: 2px solid green; border-radius: 15px; padding: 20px;">
            <div class="box" style="position: relative; float: left; width: 33%;">
                <input type="button" id="botinserir" class="resetbot" value="Inserir Novo" onclick="InsRamais();">
                <?php
                if($admIns == 7){
                    echo "<label class='fonteATI'>ATI</label>";
                }
                ?>
            </div>
            <div class="box" style="position: relative; float: left; width: 33%; text-align: center;">
                <h3>Ramais Telefônicos</h3>
            </div>

            <table id="idTabela" class="display" style="width:85%">
                <thead>
                    <tr>
                        <th>Nome Usual</th>
                        <th style="display: none;"></th>
                        <th>Nome</th>
                        <th style="text-align: center;">Ramal</th>
                        <th style="text-align: center;">Setor</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($tbl = mysqli_fetch_array($rs0)){
                        $Cod = $tbl["CodTel"];
                    ?>
                        <tr>
                            <td><?php echo $tbl["nomeusu"]; ?></td>
                            <td style="display: none;"><?php echo $Cod; ?></td>
                            <td><?php echo $tbl["nomecompl"]; ?></td>
                            <td style="text-align: center;"><?php echo $tbl["ramal"]; ?></td>
                            <td style="text-align: center;"><?php echo $tbl["setor"]; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- div modal para edição  -->
        <div id="relacmodal" class="relacmodal">  <!-- ("close")[0] -->
            <div class="modal-content-Ramais">
                <span class="close" onclick="fechaModal();">&times;</span>
                <h3 id="titulomodal" style="text-align: center; color: #666;">Edição de Ramal Telefônico</h3>
                <table style="margin: 0 auto;">
                    <tr>
                        <td id="etiqNome" class="etiq">Nome Usual</td>
                        <td><input type="text" id="usuario" name="usuario" style="width: 50%;" placeholder="Nome usual" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('nomecompl');return false;}"></td>
                    </tr>
                    <tr>
                        <td id="etiqNomeCompl" class="etiq">Nome Completo</td>
                        <td><input type="text" id="nomecompl" name="nomecompl" style="width: 99%;" placeholder="Nome completo" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('setor');return false;}"></td>
                    </tr>
                    <tr>
                        <td id="etiqSetor" class="etiq">Setor</td>
                        <td><input type="text" id="setor" name="setor" style="width: 50%;" placeholder="Setor" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('ramal');return false;}"></td>
                    </tr>
                    <tr>
                        <td id="etiqRamal" class="etiq">Ramal</td>
                        <td><input type="text" id="ramal" name="ramal" style="width: 50%;" placeholder="Ramal" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('usuario');return false;}"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;"><div id="mensagem" style="color: red; font-weight: bold;"></div></td>
                    <tr>
                        <td class="etiq" style="color: red; text-align: left;"><input type="button" class="resetbotred" id="botapagar" value="Apagar" onclick="deletaModal();"></td>
                        <td style="text-align: right; padding-right: 20px; width: 400px;"><input type="button" class="resetbotazul" name="salvar" id="salvar" value="Salvar" onclick="salvaModal();"></td>
                    </tr>
                </table>
           </div>
        </div>

        <?php
            $_SESSION['ConecAtiv'] = 0;
            mysqli_close($xVai);
        ?>
    </body>
</html>