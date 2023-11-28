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
        <title></title>
        <link rel="stylesheet" type="text/css" media="screen" href="class/dataTable/datatables.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/relacmod.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/indlog.css" />
        <script src="class/dataTable/datatables.min.js"></script>
        <script src="class/superfish/js/jquery.js"></script> <!-- versão 1.12.1 veio com o superfish - tem que usar esta, a versão 3.6 não recarrega a página-->
        <style>
            .resetbot{
                border-radius: 5px;
            }
        </style>
        <script>
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
                    if(parseInt(document.getElementById("UsuAdm").value) >= parseInt(document.getElementById("admEdit").value)){ // nível administrativo para Editar
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
                if(parseInt(document.getElementById("UsuAdm").value) < parseInt(document.getElementById("admIns").value)){ // nível administrativo para inserir
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
                    ajax.open("POST", "modulos/salvaAniver.php?acao=buscaAniver&numero="+id, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");  //Lê o array que vem
                                document.getElementById("usuario").value = Resp.usuario;
                                document.getElementById("nomecompl").value = Resp.nomecompl;
                                document.getElementById("diaAniv").value = Resp.diaAniv;
                                document.getElementById("mesAniv").value = Resp.mesAniv;
                                document.getElementById("titulomodal").innerHTML = "Edição de Aniversariante";
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
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>NOME USUAL</u>";
                    $('#mensagem').fadeOut(2000);
//                    alert("Preencha o campo Nome Usual");
                    return false;
                }
                if(document.getElementById("nomecompl").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>NOME COMPLETO</u>";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("diaAniv").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>DIA</u> do Aniversário";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("mesAniv").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>MÊS</u> do Aniversário";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(parseInt(document.getElementById("mudou").value) === 1){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/salvaAniver.php?acao=salvaAniver&numero="+document.getElementById("guardaid_click").value
                        +"&usulogado="+document.getElementById("guarda_usulogado_id").value
                        +"&usuario="+document.getElementById("usuario").value
                        +"&nomecompl="+document.getElementById("nomecompl").value
                        +"&diaAniv="+document.getElementById("diaAniv").value
                        +"&mesAniv="+document.getElementById("mesAniv").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 2){
                                        $('#mensagem').fadeIn("slow");
                                        document.getElementById("mensagem").innerHTML = "Esse nome <u>JÁ EXISTE</u>.";
                                        $('#mensagem').fadeOut("slow");
                                    }else{
                                        document.getElementById("mudou").value = "0";
                                        document.getElementById("guardaid_click").value = 0;
                                        document.getElementById("relacmodal").style.display = "none";
                                        $('#container3').load('modulos/aniverRel.php');
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
            function InsAniver(){
                document.getElementById("usuario").value = "";
                document.getElementById("nomecompl").value = "";
                document.getElementById("diaAniv").value = "";
				document.getElementById("mesAniv").value = "";
                document.getElementById("titulomodal").innerHTML = "Inserção de Aniversariantes";
                document.getElementById("relacmodal").style.display = "block";
                document.getElementById("botapagar").disabled = true;
                document.getElementById("usuario").focus();
            }
            function deletaModal(){
                let Conf = confirm("Confirma apagar esse lançamento?");
                if(Conf){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/salvaAniver.php?acao=deletaAniver&numero="+document.getElementById("guardaid_click").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//    alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 1){
                                        alert("Houve um erro no servidor.")
                                    }else{
                                        document.getElementById("mudou").value = "0";
                                        document.getElementById("relacmodal").style.display = "none";
                                        $('#container3').load('modulos/aniverRel.php');
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }
            }
            function fechaModal(){
                document.getElementById("guardaid_click").value = 0;
                document.getElementById("relacmodal").style.display = "none";
            }
            function foco(id){
                document.getElementById(id).focus();
            }
            function modif(){ // assinala se houve qualquer modificação nos campos do modal durante a edição para evitar salvar desnecessariamente
                document.getElementById("mudou").value = "1";
            }
        </script>
    </head>
    <body>
        <?php
        require_once("dbclass.php");

        if(isset($_REQUEST["tipo"])){
            $Tipo = $_REQUEST["tipo"];
        }else{
            $Tipo = 1;
        }

        $admIns = parAdm("insAniver");   // nível para inserir 
        $admEdit = parAdm("editAniver"); // nível para editar

        // aqui mysql
        if($_SESSION['Conectado'] == "MySql"){
            $rs0 = mysqli_query($xVai, "SELECT id, nomeUsu, nomeCompl, diaAniv, mesAniv FROM cesb_anivers WHERE nomeUsu != '' And Ativo = 1 ORDER BY mesAniv, diaAniv");
            $row0 = mysqli_num_rows($rs0);
        }

        //Aqui postgres
        if($_SESSION['Conectado'] == "PostGres"){
            require_once("config/dbcclass.php");
            $Conec = conecPost();
            $rs0 = pg_query($Conec, "SELECT id, nome_completo, TO_CHAR(dt_nascimento, 'DD'), TO_CHAR(dt_nascimento, 'MM') FROM pessoas WHERE nome_completo != '' ORDER BY TO_CHAR(dt_nascimento, 'MM'), TO_CHAR(dt_nascimento, 'DD')");
            $row0 = pg_num_rows($rs0);
        }


        //Para carregar as opções de select dia e mês
        $OpcoesMes = mysqli_query($xVai, "SELECT Esc1 FROM cesb_escolhas WHERE CodEsc < 14 ORDER BY Esc1");
        $OpcoesDia = mysqli_query($xVai, "SELECT Esc1 FROM cesb_escolhas ORDER BY Esc1");
        ?>
        <input type="hidden" id="UsuAdm" value="<?php echo $_SESSION["AdmUsu"] ?>" />
        <input type="hidden" id="admIns" value="<?php echo $admIns; ?>" /> <!-- nível mínimo para inserir tarefas -->
        <input type="hidden" id="admEdit" value="<?php echo $admEdit; ?>" /> <!-- nível mínimo para inserir tarefas -->

        <input type="hidden" id="guardaid_click" value="0" />
        <input type="hidden" id="mudou" value="0" /> <!-- valor 1 quando houver mudança em qualquer campo do modal -->
        <input type="hidden" id="guarda_usulogado_id" value="<?php echo $_SESSION["usuarioID"]; ?>" />
        <div style="margin: 20px; border: 2px solid blue; border-radius: 15px; padding: 20px;">
            <div class="box" style="position: relative; float: left; width: 33%;">
                <input type="button" id="botinserir" class="resetbot" value="Inserir Novo" onclick="InsAniver();">
                <?php
                if($admIns == 7){
                    echo "<label class='fonteATI'>ATI</label>";
                }
                ?>
            </div>
            <div class="box" style="position: relative; float: left; width: 33%; text-align: center;">
                <h3>Aniversariantes</h3>
            </div>
            <table id="idTabela" class="display" style="width:85%">
                <thead>
                    <tr>
                        <th>Nome Usual</th>
                        <th style="display: none;"></th>
                        <th>Nome Completo</th>
                        <th style="text-align: center;">Dia Aniversário</th>
                        <th style="text-align: center;">Mes Aniversário</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if($_SESSION['Conectado'] == "MySql"){
                    while ($tbl = mysqli_fetch_array($rs0)){
                        $Cod = $tbl["id"];
                        $NomeCompl = $tbl["nomeCompl"];
                        $DiaAniv = $tbl["diaAniv"];
                        $MesAniv = $tbl["mesAniv"];                    
                    ?>
                    <tr>
                        <td><?php echo $tbl["nomeUsu"]; ?></td>
                        <td style="display: none;"><?php echo $Cod; ?></td>
                        <td><?php echo $NomeCompl; ?></td>
                        <td style="text-align: center;"><?php echo $DiaAniv; ?></td>
                        <td style="text-align: center;"><?php echo $MesAniv; ?></td>
                    </tr>
                    <?php
                    }
                }
                if($_SESSION['Conectado'] == "PostGres"){
                    while ($tbl = pg_fetch_row($rs0)) {
                        $Cod = $tbl[0];
                        $NomeCompl = $tbl[1];
                        $DiaAniv = $tbl[2];
                        $MesAniv = $tbl[3];                    
                    ?>
                    <tr>
                        <td></td>
                        <td style="display: none;"><?php echo $Cod; ?></td>
                        <td><?php echo $NomeCompl; ?></td>
                        <td style="text-align: center;"><?php echo $DiaAniv; ?></td>
                        <td style="text-align: center;"><?php echo $MesAniv; ?></td>
                    </tr>
                    <?php
                    }
                }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- div modal para edição  -->
        <div id="relacmodal" class="relacmodal">  <!-- ("close")[0] -->
            <div class="modal-content-Aniver">
                <span class="close" onclick="fechaModal();">&times;</span>
                <h3 id="titulomodal" style="text-align: center; color: #666;">Edição de Aniversariantes</h3>
                <table style="margin: 0 auto; width: 90%;">
                    <tr>
                        <td id="etiqNome" class="etiq">Nome Usual</td>
                        <td><input type="text" id="usuario" name="usuario" placeholder="Nome usual" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('nomecompl');return false;}"></td>
                    </tr>
                    <tr>
                        <td id="etiqNomeCompl" class="etiq">Nome Completo</td>
                        <td><input type="text" id="nomecompl" name="nomecompl" style="width: 100%;" placeholder="Nome completo" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('usuario');return false;}"></td>
                    </tr>
                    <tr>
                        <td id="etiqDia" class="etiq">Dia do Aniversário</td>
                        <td>
                            <select id="diaAniv" name="diaAniv" style="font-size: 1rem; width: 50px;" title="Selecione um dia." onchange="modif();">
                            <?php 
                            if($OpcoesDia){
                                while ($Opcoes = mysqli_fetch_array($OpcoesDia)){ ?>
                                    <option value="<?php echo $Opcoes['Esc1']; ?>"><?php echo $Opcoes['Esc1']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>                        
                        </td>
                    </tr>
                    <tr>
                        <td id="etiqMes" class="etiq">Mês do Aniversário</td>
                        <td>
                            <select id="mesAniv" name="mesAniv" style="font-size: 1rem; width: 50px;" title="Selecione um mês." onchange="modif();">
                            <?php 
                            if($OpcoesMes){
                                while ($Opcoes = mysqli_fetch_array($OpcoesMes)){ ?>
                                    <option value="<?php echo $Opcoes['Esc1']; ?>"><?php echo $Opcoes['Esc1']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>                    
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;"><div id="mensagem" style="color: red; font-weight: bold;"></div></td>
                    <tr>
                        <td class="etiq" style="color: red; text-align: left;"><input type="button" class="resetbotred" id="botapagar" value="Apagar" onclick="deletaModal();"></td>
                        <td style="text-align: right; padding-right: 30px;"><input type="button" class="resetbotazul" name="salvar" id="salvar" value="Salvar" onclick="salvaModal();"></td>
                    </tr>
                </table>
           </div>
        </div> <!-- Fim Modal-->
        <?php
            $_SESSION['ConecAtiv'] = 0;
            mysqli_close($xVai);
        ?>
    </body>
</html>