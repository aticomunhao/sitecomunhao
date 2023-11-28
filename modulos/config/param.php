<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <style type="text/css">
            .etiq{
                text-align: right; color: #036; font-size: .9em; font-weight: bold; padding: 3px;
            }
        </style>

        <script type="text/javascript">
            function ajaxIni(){
                try{
                    ajax = new ActiveXObject("Microsoft.XMLHTTP");}
                    catch(e){
                        try{
                            ajax = new ActiveXObject("Msxml2.XMLHTTP");}
                        catch(ex) {
                            try{
                                ajax = new XMLHttpRequest();}
                            catch(exc){
                                alert("Esse browser não tem recursos para uso do Ajax");
                                ajax = null;
                            }
                        }
                    }
                }

//                $(document).ready(function(){
//                });

                function salvaParam(Valor, Param){
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/config/registr.php?acao=salvaParam&param="+Param+"&valor="+Valor, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) > 0){
                                        alert("Houve erro ao salvar");
                                    }
                                }
                            }
                        };
                        ajax.send(null);
                    } 
                }

                function MarcaAdm(obj){
                    if(obj.checked === true){
                        Valor = 1;
                    }else{
                        Valor = 0;
                    }
                    ajaxIni();
                    if(ajax){
                        ajax.open("POST", "modulos/config/registr.php?acao=salvaAdm&valor="+Valor+"&caixa="+obj.value, true);
                        ajax.onreadystatechange = function(){
                            if(ajax.readyState === 4 ){
                                if(ajax.responseText){
//alert(ajax.responseText);
                                    Resp = eval("(" + ajax.responseText + ")");
                                    if(parseInt(Resp.coderro) > 0){
                                        alert("Houve erro ao salvar");
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
            $rsSis = mysqli_query($xVai, "SELECT admVisu, admCad, admEdit, insAniver, editAniver, insEvento, editEvento, insTarefa, editTarefa, insRamais, editRamais, insTelef, editTelef, editPagina, insArq, insTroca, editTroca, insOcor, editOcor FROM cesb_paramsis WHERE idPar = 1");
            $ProcSis = mysqli_fetch_array($rsSis);
            $admVisu = $ProcSis["admVisu"]; // administrador visualiza usuários
            $admEdit = $ProcSis["admEdit"]; // administrador edita usuários
            $admCad = $ProcSis["admCad"];   // administrador cadastra usuários

            $insEvento = $ProcSis["insEvento"];   // inserção de eventos no calendário
            $rs1 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insEvento");
            $Proc1 = mysqli_fetch_array($rs1);
            $nomeInsEvento = $Proc1["adm_nome"];

            $editEvento = $ProcSis["editEvento"];   // edição de eventos no calendário
            $rs2 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editEvento");
            $Proc2 = mysqli_fetch_array($rs2);
            $nomeEditEvento = $Proc2["adm_nome"];

            $insTarefa = $ProcSis["insTarefa"];   // inserção de tarefas
            $rs3 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insTarefa");
            $Proc3 = mysqli_fetch_array($rs3);
            $nomeInsTarefa = $Proc3["adm_nome"];

            $editTarefa = $ProcSis["editTarefa"];   // edição de tarefas
            $rs4 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editTarefa");
            $Proc4 = mysqli_fetch_array($rs4);
            $nomeEditTarefa = $Proc4["adm_nome"];
            
            $insRamais = $ProcSis["insRamais"];   // edição de ramais internos
            $rs5 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insRamais");
            $Proc5 = mysqli_fetch_array($rs5);
            $nomeInsRamais = $Proc5["adm_nome"];

            $editRamais = $ProcSis["editRamais"];   // edição de ramais internos
            $rs6 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editRamais");
            $Proc6 = mysqli_fetch_array($rs6);
            $nomeEditRamais = $Proc6["adm_nome"];

            $insTelef = $ProcSis["insTelef"];   // edição de telefones
            $rs7 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insTelef");
            $Proc7 = mysqli_fetch_array($rs7);
            $nomeInsTelef = $Proc7["adm_nome"];

            $editTelef = $ProcSis["editTelef"];   // edição de telefones
            $rs8 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editTelef");
            $Proc8 = mysqli_fetch_array($rs8);
            $nomeEditTelef = $Proc8["adm_nome"];

            $editPagina = $ProcSis["editPagina"];   // edição das páginas das diretorias/assessorias
            $rs9 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editPagina");
            $Proc9 = mysqli_fetch_array($rs9);
            $nomeEditPagina = $Proc9["adm_nome"];

            $insArq = $ProcSis["insArq"];   // edição das páginas das diretorias/assessorias
            $rs10 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insArq");
            $Proc10 = mysqli_fetch_array($rs10);
            $nomeInsArq = $Proc10["adm_nome"];

            $insAniver = $ProcSis["insAniver"];   // edição das páginas das diretorias/assessorias
            $rs11 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insAniver");
            $Proc11 = mysqli_fetch_array($rs11);
            $nomeInsAniver = $Proc11["adm_nome"];

            $editAniver = $ProcSis["editAniver"];   // edição de telefones
            $rs12 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editAniver");
            $Proc12 = mysqli_fetch_array($rs12);
            $nomeEditAniver = $Proc12["adm_nome"];
            
            $insTroca = $ProcSis["insTroca"];   // edição de trocas
            $rs13 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insTroca");
            $Proc13 = mysqli_fetch_array($rs13);
            $nomeInsTroca = $Proc13["adm_nome"];

            $editTroca = $ProcSis["editTroca"];   // edição de trocas
            $rs14 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editTroca");
            $Proc14 = mysqli_fetch_array($rs14);
            $nomeEditTroca = $Proc14["adm_nome"];

            $insOcor = $ProcSis["insOcor"];   // registro de ocorrências
            $rs15 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $insOcor");
            $Proc15 = mysqli_fetch_array($rs15);
            $nomeInsOcor = $Proc15["adm_nome"];

            $editOcor = $ProcSis["editOcor"];
            $rs16 = mysqli_query($xVai, "SELECT adm_nome FROM cesb_usugrupos WHERE adm_fl = $editOcor");
            $Proc16 = mysqli_fetch_array($rs16);
            $nomeEditOcor = $Proc16["adm_nome"];


            $OpAdmInsEv = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmEditEv = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");

            $OpAdmInsTar = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmEditTar = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");

            $OpAdmInsRamais = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmEditRamais = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");

            $OpAdmInsTelef = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmEditTelef = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");

            $OpAdmEditPag = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmInsArq = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");

            $OpAdmInsAniver = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmEditAniver = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            
            $OpAdmInsTroca = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmEditTroca = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");

            $OpAdmInsOcor = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");
            $OpAdmEditOcor = mysqli_query($xVai, "SELECT id, adm_fl, adm_nome FROM cesb_usugrupos WHERE Ativo = 1 ORDER BY adm_fl");

        ?>

        <div style="margin: 0 auto; margin-top: 40px; padding: 20px; border: 2px solid blue; border-radius: 15px; width: 50%; min-height: 200px;">
            <div style="text-align: center;">
                <h4>Parâmetros do Sistema</h4>
            </div>


<!-- Aniversariantes  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Aniversariantes</b>:<br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR aniversariantes:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insAniver');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $insAniver; ?>"><?php echo $nomeInsAniver; ?></option>
                            <?php 
                            if($OpAdmInsAniver){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsAniver)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Nível mínimo para EDITAR aniversariantes:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editAniver');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editAniver; ?>"><?php echo $nomeEditAniver; ?></option>
                            <?php 
                            if($OpAdmEditAniver){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditAniver)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>


<!-- Calendário  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Calendário</b>:<br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR eventos:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insEvento');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                            <option value="<?php echo $insEvento; ?>"><?php echo $nomeInsEvento; ?></option>
                            <?php 
                            if($OpAdmInsEv){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsEv)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>Nível mínimo para EDITAR eventos:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editEvento');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editEvento; ?>"><?php echo $nomeEditEvento; ?></option>
                            <?php 
                            if($OpAdmEditEv){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditEv)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>


<!-- Páginas  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Páginas das Diretorias/Assessorias</b>:<br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR arquivos:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insArq');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $insArq; ?>"><?php echo $nomeInsArq; ?></option>
                            <?php 
                            if($OpAdmInsArq){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsArq)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Nível mínimo para EDITAR página:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editPagina');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editPagina; ?>"><?php echo $nomeEditPagina; ?></option>
                            <?php 
                            if($OpAdmEditPag){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditPag)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

<!-- Ramais  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Ramais</b>:<br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR ramais:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insRamais');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                            <option value="<?php echo $insRamais; ?>"><?php echo $nomeInsRamais; ?></option>
                            <?php 
                            if($OpAdmInsRamais){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsRamais)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>Nível mínimo para EDITAR ramais:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editRamais');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editRamais; ?>"><?php echo $nomeEditRamais; ?></option>
                            <?php 
                            if($OpAdmEditRamais){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditRamais)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>


<!-- Ocorrrências  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Registro de Ocorrêncas</b>: <label style="color: gray; font-size: .8em;">Cada usuário só pode ver as ocorrências que inseriu</label><br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR ocorrência:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insOcor');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $insOcor; ?>"><?php echo $nomeInsOcor; ?></option>
                            <?php 
                            if($OpAdmInsOcor){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsOcor)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Nível mínimo para EDITAR ocorrência:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editOcor');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editOcor; ?>"><?php echo $nomeEditOcor; ?></option>
                            <?php 
                            if($OpAdmEditOcor){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditOcor)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>


<!-- Tarefas  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Tarefas</b>: <label style="color: gray; font-size: .8em;">Cada nível insere tarefa para seu nível ou nível inferior</label><br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR tarefas:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insTarefa');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                            <option value="<?php echo $insTarefa; ?>"><?php echo $nomeInsTarefa; ?></option>
                            <?php 
                            if($OpAdmInsTar){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsTar)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>Nível mínimo para EDITAR tarefas:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editTarefa');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editTarefa; ?>"><?php echo $nomeEditTarefa; ?></option>
                            <?php 
                            if($OpAdmEditTar){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditTar)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>


<!-- Telefones  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Telefones</b>:<br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR telefones:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insTelef');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                            <option value="<?php echo $insTelef; ?>"><?php echo $nomeInsTelef; ?></option>
                            <?php 
                            if($OpAdmInsTelef){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsTelef)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>Nível mínimo para EDITAR telefones:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editTelef');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editTelef; ?>"><?php echo $nomeEditTelef; ?></option>
                            <?php 
                            if($OpAdmEditTelef){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditTelef)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

<!-- Trocas  -->
            <div style="margin: 5px; border: 1px solid; border-radius: 10px; padding: 15px;">
                - <b>Trocas</b>: <label style="color: gray; font-size: .8em;">É editável pelo setor que inseriu</label><br>
                <table style="margin: 0 auto;">
                    <tr>
                        <td>Nível mínimo para INSERIR trocas:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'insTroca');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                            <option value="<?php echo $insTroca; ?>"><?php echo $nomeInsTroca; ?></option>
                            <?php 
                            if($OpAdmInsTroca){
                                while ($Opcoes = mysqli_fetch_array($OpAdmInsTroca)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>Nível mínimo para EDITAR trocas:</td>
                        <td style="padding-left: 5px;">
                        <select onchange="salvaParam(value, 'editTroca');" style="font-size: 1rem; width: 200px;" title="Selecione um nível de usuário.">
                        <option value="<?php echo $editTroca; ?>"><?php echo $nomeEditTroca; ?></option>
                            <?php 
                            if($OpAdmEditTroca){
                                while ($Opcoes = mysqli_fetch_array($OpAdmEditTroca)){ ?>
                                    <option value="<?php echo $Opcoes['adm_fl']; ?>"><?php echo $Opcoes['adm_nome']; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
<br><br><br>

            <div style="border: 1px solid; border-radius: 10px; padding: 15px;">
                - Usuários:<br>
                <input type="checkbox" id="admVisu" onclick="MarcaAdm(this, 'admVisu');" value="admVisu" <?php if($admVisu == 1) {echo "checked";} ?>>
                <label for="admVisu" class="etiq">Administradores podem acessar lista de usuários</label>
                <br>
                <input type="checkbox" id="admEdit" onclick="MarcaAdm(this, 'admEdit');" value="admEdit" <?php if($admEdit == 1) {echo "checked";} ?>>
                <label for="admEdit" class="etiq">Administradores podem EDITAR usuários</label>
                <br>
                <input type="checkbox" id="admCad" onclick="MarcaAdm(this, 'admCad');" value="admCad" <?php if($admCad == 1) {echo "checked";} ?>>
                <label for="admCad" class="etiq">Administradores podem CADASTRAR usuários</label>
            </div>

        </div>
    </body>
</html>