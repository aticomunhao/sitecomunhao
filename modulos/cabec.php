<!DOCTYPE html>
<html lang="pt-br">
    <title></title>
    <head>
    </head>
    <body>
        <div id="container1Esq" style="width: 10%;">
            <img src="imagens/LogoComunhao.png" height="40px;">
        </div>
        <div id="container1Cen" class="fontSiteFamily" style="width: 47%; text-align: center;">
            <?php
            date_default_timezone_set('America/Sao_Paulo');
//                $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);  //FULL no primeiro parâmetro dá o dia da semana também
//                echo $formatter->format(time());  // não funcionou na comunhão
            $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
            $data = date('Y-m-d');
            $diasemana_numero = date('w', strtotime($data));     
            ?>
            <div class="fontTrebuchet_Spacing" style="width: 99%; position: absolute; top: 2px; font-size: 1.3rem; letter-spacing: 2px; word-spacing: 2px;">Comunhão Espírita de Brasília</div>
            <div class="" style="width: 99%; position: absolute; font-size: .9rem; bottom: 0px; font-weight: bold;"><?php echo $diasemana[$diasemana_numero].", ".date('d/m/Y'); ?></div>
        </div>
        <div class="form-control" id="container1Dir" style="width: 40%; border: 1px solid blue; border-radius: 20px;  background-image: url('imagens/ComunBannerLongo2.jpg'); background-repeat: no-repeat; background-position-x: center; background-position-y: center;">
        </div>
    </body>
</html>