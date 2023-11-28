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
        <script src="class/superfish/js/jquery.js"></script><!-- versão 1.12.1 veio com o superfish - tem que usar esta, a versão 3.6 não recarrega a página-->
        <script src="class/dataTable/datatables.min.js"></script>
        <style>
            .modal-content-Usu{
                background: linear-gradient(180deg, white, #86c1eb);
                margin: 15% auto; /* 10% do topo e centrado */
                padding: 20px;
                border: 1px solid #888;
                border-radius: 15px;
                width: 60%; /* acertar de acordo com a tela */
            }
        </style>
        <script>
            new DataTable('#idTabelaUsu', {
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

            tableUsu = new DataTable('#idTabelaUsu');
            tableUsu.on('click', 'tbody tr', function () {
                let data = tableUsu.row(this).data();
                $id = data[1];//
                document.getElementById("guardaid_click").value = $id;
                if($id !== ""){
                    if(parseInt(document.getElementById("UsuAdm").value) < 7){  // superusuário
                        if(parseInt(document.getElementById("UsuAdm").value) > 3 && parseInt(document.getElementById("admEditUsu").value) === 1){ // adminisetrador 
                            if(document.getElementById("guardaSiglaSetor").value === data[4]){ // sigla do usuário = sigla do administrador logado
                                document.getElementById("setor").disabled = true; // congela a escolha do setor
                                carregaModal($id);
                            }else{
                                document.getElementById("textoMsg").innerHTML = "Não pertence ao setor.";
                                document.getElementById("relacmensagem").style.display = "block"; // está em modais.php
                                setTimeout(function(){
                                    document.getElementById("relacmensagem").style.display = "none";
                                }, 2000);
                            }
                        }
                    }else{
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
                document.getElementById("botapagar").style.visibility = "hidden"; // botão para apagar usuário
                document.getElementById("botinserir").style.visibility = "hidden"; // botão de inserir usuário
                if(parseInt(document.getElementById("UsuAdm").value) === 7){ // superusuário 
                    document.getElementById("botapagar").style.visibility = "visible";
                    document.getElementById("botinserir").style.visibility = "visible";
                }
                if(parseInt(document.getElementById("UsuAdm").value) === 4){ // administador
                    if(parseInt(document.getElementById("admCadUsu").value) === 1){ // administardor cadastra usuário do seu setor 
                        document.getElementById("botinserir").style.visibility = "visible";
                    }
                }

                modalEdit = document.getElementById('relacmodalUsu'); //span[0]
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
                    ajax.open("POST", "modulos/config/registr.php?acao=buscausu&numero="+id, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");  //Lê o array que vem
                                if(parseInt(Resp.coderro) === 0){
                                    document.getElementById("usulogin").value = Resp.usuario;
                                    document.getElementById("usuarioNome").value = Resp.usuarioNome;
                                    document.getElementById("nomecompl").value = Resp.nomecompl;
                                    document.getElementById("diaAniv").value = Resp.diaAniv;
                                    document.getElementById("mesAniv").value = Resp.mesAniv;
                                    document.getElementById("ultlog").value = Resp.ultlog;
                                    document.getElementById("acessos").value = Resp.acessos;
                                    document.getElementById("flAdm").value = Resp.usuarioAdm;
                                    document.getElementById("setor").value = Resp.setor;
                                    if(parseInt(Resp.ativo) === 1){
                                        document.getElementById("atividade1").checked = "true";
                                    }else{
                                        document.getElementById("atividade2").checked = "true";
                                    }
                                    document.getElementById("titulomodal").innerHTML = "Edição de Usuários";
                                    document.getElementById("botapagar").disabled = false;
                                    document.getElementById("ressetsenha").disabled = false;
                                    document.getElementById("mudou").value = "0";
                                    document.getElementById("relacmodalUsu").style.display = "block";
//                                    document.getElementById("usulogin").focus();
                                }else{
                                    alert("Houve um erro no servidor.")
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }

            function salvaModal(){
                if(document.getElementById("usulogin").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>Login</u>";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("usuarioNome").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>NOME USUAL</u>";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("nomecompl").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>NOME COMPLETO</u>";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("setor").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>Setor de Trabalho/Diretoria/Assessoria</u>";
                    $('#mensagem').fadeOut(3000);
                    return false;
                }
                if(document.getElementById("flAdm").value === ""){
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Preencha o campo <u>Nível Administrativo</u> do usuário";
                    $('#mensagem').fadeOut(3000);
                    return false;
                }
                if(parseInt(document.getElementById("mudou").value) === 1){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/config/registr.php?acao=salvaUsu&numero="+document.getElementById("guardaid_click").value
                        +"&usulogado="+document.getElementById("guarda_usulogado_id").value
                        +"&usulogin="+document.getElementById("usulogin").value
                        +"&usuarioNome="+document.getElementById("usuarioNome").value
                        +"&nomecompl="+document.getElementById("nomecompl").value
                        +"&setor="+document.getElementById("setor").value
                        +"&flAdm="+document.getElementById("flAdm").value
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
                                        document.getElementById("relacmodalUsu").style.display = "none";
                                        $('#container3').load('modulos/config/cadUsu.php');
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }else{
                    document.getElementById("mudou").value = "0";
                    document.getElementById("relacmodalUsu").style.display = "none";
                }
            }
            function salvaAtiv(Valor){
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/config/registr.php?acao=salvaAtiv&numero="+document.getElementById("guardaid_click").value+"&valor="+Valor+"&usulogado="+document.getElementById("guarda_usulogado_id").value, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor.")
                                }else{
                                    document.getElementById("mudou").value = "1";
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }                
            }
            function deletaModal(){
                let Conf = confirm("Não haverá possibilidade de recuperação.\nConfirma deletar os dados deste usuário?");
                if(Conf){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/config/registr.php?acao=deletausu&numero="+document.getElementById("guardaid_click").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//    alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 1){
                                        alert("Houve um erro no servidor.")
                                    }else{
                                        document.getElementById("mudou").value = "0";
                                        document.getElementById("relacmodalUsu").style.display = "none";
                                        $('#container3').load('modulos/config/cadUsu.php');
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }
            }
            function insUsu(){
                if(parseInt(document.getElementById("UsuAdm").value) < 7){ 
                    if(parseInt(document.getElementById("admCadUsu").value) === 0){ // administrador não cadastra
                        return false;
                    }
                }
                if(document.getElementById("guardaSiglaSetor").value === "n/d"){
                    document.getElementById("textoMsg").innerHTML = "Administrador sem setor definido.";
                    document.getElementById("relacmensagem").style.display = "block"; // está em modais.php
                    setTimeout(function(){
                        document.getElementById("relacmensagem").style.display = "none";
                    }, 2000);
                    return false;
                }
                document.getElementById("usulogin").value = "";
                document.getElementById("usuarioNome").value = "";
                document.getElementById("nomecompl").value = "";
                document.getElementById("setor").value = "";
                document.getElementById("flAdm").value = "";
                document.getElementById("diaAniv").value = "";
                document.getElementById("mesAniv").value = "";
                document.getElementById("acessos").value = "-";
                document.getElementById("ultlog").value = "-";
                document.getElementById("setor").value = document.getElementById("guardaCodSetor").value;
                if(parseInt(document.getElementById("UsuAdm").value) < 7){
                    document.getElementById("setor").disabled = true;
                    document.getElementById("flAdm").value = 2; // usuário registrado
                    document.getElementById("flAdm").disabled = true;
                }
                document.getElementById("atividade1").checked = "true";
                document.getElementById("titulomodal").innerHTML = "Inserção de Usuário";
                document.getElementById("botapagar").disabled = true;
                document.getElementById("ressetsenha").disabled = true;
                document.getElementById("relacmodalUsu").style.display = "block";
                document.getElementById("usulogin").focus();
            }
            function checaLogin(){
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/config/registr.php?acao=checaLogin&valor="+encodeURIComponent(document.getElementById("usulogin").value), true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.quantiUsu) > 0){
                                    $('#mensagem').fadeIn("slow");
                                    document.getElementById("mensagem").innerHTML = "Já existe um login como esse, alocado para <u>"+Resp.nomecompl+"</u>";
                                    $('#mensagem').fadeOut(3000);
                                    alert("Já existe um login como esse, alocado para "+Resp.nomecompl);
                                    document.getElementById("usulogin").value = "";
                                    document.getElementById("usulogin").focus();
                                    document.getElementById("mudou").value = "1";
                                }
                            }
                        }
                    };
                    ajax.send(null);
                } 
            }
            function fechaModal(){
                document.getElementById("guardaid_click").value = 0;
                document.getElementById("relacmodalUsu").style.display = "none";
            }
            function foco(id){
                document.getElementById(id).focus();
            }
            function modif(){ // assinala se houve qualquer modificação nos campos do modal durante a edição para evitar salvar desnecessariamente
                document.getElementById("mudou").value = "1";
            }
            function resetSenha(){
                let Conf = confirm("A senha deste usuário será modificada para 123456789. Prossegue?");
                if(Conf){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/config/registr.php?acao=resetsenha&numero="+document.getElementById("guardaid_click").value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//    alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) === 1){
                                        alert("Houve um erro no servidor.")
                                    }else{
                                        document.getElementById("textoMsg").innerHTML = "Senha modificada para 123456789";
                                        document.getElementById("relacmensagem").style.display = "block"; // está em modais.php
                                        setTimeout(function(){
                                            document.getElementById("relacmensagem").style.display = "none";
                                        }, 2000);
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    }
                }
            }
            
        </script>
    </head>
    <body>
        <?php
        require_once(dirname(dirname(__FILE__))."/dbclass.php");
        if(isset($_REQUEST["tipo"])){
            $Tipo = $_REQUEST["tipo"];
        }else{
            $Tipo = 1;
        }
        require_once("modais.php");
        $rs0 = mysqli_query($xVai, "SELECT id, usuario, nome, nomecompl, diaAniv, mesAniv, date_format(ultlog, '%d/%m/%Y') as ultlogF, NumAcessos, cesb_usuarios.Ativo, cesb_setores.SiglaSetor FROM cesb_usuarios INNER JOIN cesb_setores ON cesb_usuarios.CodSetor = cesb_setores.CodSet WHERE usuario != '' And adm != 7 ORDER BY nome, nomecompl");
        $row0 = mysqli_num_rows($rs0);
           //Para carrecar os select de dia e mês
        $OpcoesMes = mysqli_query($xVai, "SELECT Esc1 FROM cesb_escolhas WHERE CodEsc < 14 ORDER BY Esc1");
        $OpcoesDia = mysqli_query($xVai, "SELECT Esc1 FROM cesb_escolhas ORDER BY Esc1");
        if($_SESSION["AdmUsu"] == 7){
            $OpcoesAdm = mysqli_query($xVai, "SELECT adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 Or adm_fl = 7 ORDER BY adm_fl");
        }else{
            $OpcoesAdm = mysqli_query($xVai, "SELECT adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
        }
        $OpcoesSetor = mysqli_query($xVai, "SELECT CodSet, SiglaSetor FROM cesb_setores ORDER BY SiglaSetor");
        ?>
        <input type="hidden" id="UsuAdm" value="<?php echo $_SESSION["AdmUsu"] ?>" />
        <input type="hidden" id="guardaSiglaSetor" value="<?php echo addslashes($_SESSION["DescSetor"]) ?>" />
        <input type="hidden" id="guardaCodSetor" value="<?php echo addslashes($_SESSION["CodSetorUsu"]) ?>" />
        <input type="hidden" id="guardaid_click" value="0" />
        <input type="hidden" id="mudou" value="0" /> <!-- valor 1 quando houver mudança em qualquer campo do modal -->
        <input type="hidden" id="guarda_usulogado_id" value="<?php echo $_SESSION["usuarioID"]; ?>" />
        <input type="hidden" id="admCadUsu" value="<?php echo $_SESSION["AdmCad"]; ?>" />
        <input type="hidden" id="admEditUsu" value="<?php echo $_SESSION["AdmEdit"]; ?>" />
        
        <div style="margin: 20px; border: 2px solid blue; border-radius: 15px; padding: 20px;">
            <div class="box" style="position: relative; float: left; width: 33%;">
                <input type="button" id="botinserir" class="resetbot" value="Inserir Novo" onclick="insUsu();">
            </div>
            <div class="box" style="position: relative; float: left; width: 33%; text-align: center;">
                <h3>Usuários Cadastrados</h3>
            </div>
            <table id="idTabelaUsu" class="display" style="width:85%">
                <thead>
                    <tr>
                        <th>Login</th>
                        <th style="display: none;"></th>
                        <th>Nome Usual</th>
                        <th>Nome Completo</th>
                        <th style="text-align: center;">Setor</th>
                        <th style="text-align: center;">Último Login</th>
                        <th style="text-align: center;">Ativo</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($tbl = mysqli_fetch_array($rs0)){
                        $Cod = $tbl["id"];
                        $Ativ = $tbl["Ativo"];
                        if($Ativ == 1){
                            $DescAtiv = "Ativo";
                        }else{
                            $DescAtiv = "Inativo";
                        }
                    ?>
                    <tr>
                        <td><?php echo $tbl["usuario"]; ?></td>
                        <td style="display: none;"><?php echo $Cod; ?></td>
                        <td><?php echo $tbl["nome"]; ?></td>
                        <td><?php echo $tbl["nomecompl"]; ?></td>
                        <td style="text-align: center;"><?php echo $tbl["SiglaSetor"]; ?></td>
                        <td style="text-align: center;"><?php echo $tbl["ultlogF"]; ?></td>
                        <td style="text-align: center;"><?php echo $DescAtiv; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- div modal para edição  -->
        <div id="relacmodalUsu" class="relacmodal">  <!-- ("close")[0] -->
            <div class="modal-content-Usu">
                <span class="close" onclick="fechaModal();">&times;</span>
                <h3 id="titulomodal" style="text-align: center; color: #666;">Edição de Usuários</h3>
                <table style="margin: 0 auto; width: 90%">
                    <tr>
                        <td id="etiqNome" class="etiq">Login:</td>
                        <td><input type="text" id="usulogin" placeholder="Login" onchange="checaLogin();" onkeypress="if(event.keyCode===13){javascript:foco('usuarioNome');return false;}"></td>
                        <td></td>
                        <td></td>
                        <td colspan="2" style="text-align: right;">
                            <label class="etiq">Último Acesso: </label>
                            <input type="text" disabled id="ultlog" style="text-align: center; font-size: .8rem; width: 100px;">
                        </td>
                    </tr>
                    <tr>
                        <td id="etiqNome" class="etiq">Nome Usual</td>
                        <td><input type="text" id="usuarioNome" placeholder="Nome usual" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('nomecompl');return false;}"></td>
                        <td></td>
                        <td></td>
                        <td colspan="2" style="text-align: right;">
                            <label class="etiq">Nº acessos: </label>
                            <input type="text" disabled id="acessos" style="text-align: center; font-size: .8rem; width: 100px;">
                        </td>
                    </tr>
                    <tr>
                        <td id="etiqNomeCompl" class="etiq">Nome Completo</td>
                        <td style="width: 50%;"><input type="text" id="nomecompl" style="width: 100%;" placeholder="Nome completo" onchange="modif();" onkeypress="if(event.keyCode===13){javascript:foco('usulogin');return false;}"></td>
                        <td></td>
                        <td></td>
                        <td colspan="2" style="text-align: right;">
                            <label style="font-size: 12px;" title="Ativo ou inativo">Situação: </label>
                            <input type="radio" name="atividade" id="atividade1" value="1" title="Ativo no sistema" onclick="salvaAtiv(value);"><label for="atividade1" style="font-size: 12px;">Ativo</label>
                            <input type="radio" name="atividade" id="atividade2" value="0" title="Bloqueado" onclick="salvaAtiv(value);"><label for="atividade2" style="font-size: 12px;">Bloqueado</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="etiq">Setor de Trabalho</td>
                        <td>
                            <select id="setor" style="font-size: 1rem;" title="Selecione um local de trabalho." onchange="modif();">
                            <?php 
                            if($OpcoesSetor){
                                while ($Opcoes = mysqli_fetch_array($OpcoesSetor)){ ?>
                                    <option value="<?php echo $Opcoes['CodSet']; ?>"><?php echo $Opcoes['SiglaSetor']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;"><button id="ressetsenha" class="resetbot" style="font-size: .9rem;" onclick="resetSenha();">Ressetar Senha</button></td>
                    </tr>
                    <tr>
                        <td class="etiq">Nível Admnistrativo</td>
                        <td>
                        <select id="flAdm" style="font-size: 1rem;" title="Selecione o nível administrativo do usuário." onchange="modif();">
                            <?php 
                            if($OpcoesAdm){
                                while ($Opcoes = mysqli_fetch_array($OpcoesAdm)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                        </td>
                        <td></td>
                        <td></td>

                        <td colspan="2" style="text-align: right;">
                            <label class="etiq">Aniversário: -Dia: </label>
                            <select id="diaAniv" name="diaAniv" style="font-size: 1rem; width: 50px;" title="Selecione um dia." onchange="modif();"">
                            <?php 
                            if($OpcoesDia){
                                while ($Opcoes = mysqli_fetch_array($OpcoesDia)){ ?>
                                    <option value="<?php echo $Opcoes['Esc1']; ?>"><?php echo $Opcoes['Esc1']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                            <label class="etiq"> -Mês: </label>
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
                        <td colspan="6"><hr style="margin: 3px; padding: 2px;"></td>
                    </tr>  
                    <tr>
                        <td colspan="6" style="text-align: center;"><div id="mensagem" style="color: red; font-weight: bold;"></div></td>
                    </tr>   
                    <tr>
                        <td class="etiq" style="color: red; text-align: left;"><input type="button" class="resetbotred" id="botapagar" value="Deletar" onclick="deletaModal();"></td> 
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right; padding-right: 30px;"><input type="button" class="resetbot" name="salvar" id="salvar" value="Salvar" onclick="salvaModal();"></td>
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