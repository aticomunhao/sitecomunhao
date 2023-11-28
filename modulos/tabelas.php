<?php
//tabelas necessárias
require_once("dbclass.php");

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_usuarios");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_usuarios (id BIGINT NOT NULL AUTO_INCREMENT, 
usuario VARCHAR(50) NOT NULL UNIQUE COLLATE utf8mb4_general_ci, 
nome VARCHAR(50) NOT NULL COLLATE utf8mb4_general_ci, 
nomeCompl VARCHAR(100) NOT NULL COLLATE utf8mb4_general_ci, 
idPessoa BIGINT(20) NOT NULL DEFAULT 0, 
Sexo TINYINT(1) NOT NULL DEFAULT 1, 
senha VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, 
adm TINYINT(2) NOT NULL DEFAULT 0, 
CodSetor INT(11) NOT NULL DEFAULT 2, 
CodSubSetor INT(11) NOT NULL DEFAULT 1, 
diaAniv VARCHAR(2) COLLATE utf8mb4_general_ci, 
mesAniv VARCHAR(2) COLLATE utf8mb4_general_ci, 
usuIns INT(11) NOT NULL DEFAULT 0, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP, 
usuModif INT(11) NOT NULL DEFAULT 0, 
dataModif DATETIME DEFAULT '3000/12/31', 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
usuInat INT(11) NOT NULL DEFAULT 0, 
dataInat DATETIME DEFAULT '3000/12/31',
MotivoInat TINYINT(2) NOT NULL DEFAULT 0,
NumAcessos INT(11) NOT NULL DEFAULT 0, 
ultlog DATETIME DEFAULT CURRENT_TIMESTAMP, 
PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_usuarios checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT id FROM cesb_usuarios");
$row0 = mysqli_num_rows($rs0);
$Senha = MD5('123');
if($row0 == 0){
//   $Senha = password_hash('123456', PASSWORD_DEFAULT);
   $rs = mysqli_query($xVai, "INSERT INTO cesb_usuarios (usuario, nome, nomecompl, adm, senha, dataIns, Ativo, ultlog, NumAcessos, CodSetor, CodSubSetor, diaAniv, mesAniv) VALUES
   ('Ludinir',    'Ludinir',  'Ludinir Picelli',         7, '$Senha', NOW(), 1, NOW(), 0, 3, 1, '12', '10'),
   ('Fulano',     'Fulano',   'Fulano de Tal',           4, '$Senha', NOW(), 1, NOW(), 0, 2, 1, '05', '10'),
   ('Sicrano',    'Sicrano',  'Sicrano Bananildo',       2, '$Senha', NOW(), 1, NOW(), 0, 5, 1, '06', '10'),
   ('Beltrano',   'Beltrano', 'Beltrano da Silva Sauro', 2, '$Senha', NOW(), 1, NOW(), 0, 6, 1, '07', '10'),
   ('Moisés',     'Moisés',   'Moisés Patriarca',        7, '$Senha', NOW(), 1, NOW(), 0, 2, 1, '07', '10')
   ");
    echo "Adicionados 4 usuários <br>";   
}else{
   $rs = mysqli_query($xVai, "UPDATE cesb_usuarios SET senha = '$Senha' WHERE usuario = 'Fulano' or usuario = 'Sicrano' or usuario = 'Beltrano'");
}
//níveis de usuários
//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_usugrupos");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_usugrupos (id BIGINT NOT NULL AUTO_INCREMENT, 
adm_fl  TINYINT(2) NOT NULL DEFAULT 0, 
adm_nome VARCHAR(100) NOT NULL COLLATE utf8mb4_general_ci, 
datacria DATETIME DEFAULT CURRENT_TIMESTAMP, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
echo "Tabela cesb_usugrupos checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT id FROM cesb_usugrupos");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO cesb_usugrupos (id, adm_fl, adm_nome, datacria, Ativo) VALUES
   (1, 0, 'Público', NOW(), 0), 
   (2, 1, 'Convidado', NOW(), 0), 
   (3, 2, 'Registrado', NOW(), 1), 
   (4, 3, 'Gerente', NOW(), 1), 
   (5, 4, 'Administrador', NOW(), 1), 
   (6, 5, 'Checador', NOW(), 0),
   (7, 6, 'Revisor', NOW(), 1),
   (8, 7, 'Superusuário', NOW(), 1) 
   ");
   if($rs){
    echo "Criando grupos de usuários <br>";
   }
   echo "tabela cesb_usugrupos criada com sucesso. <br>";
}

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_anivers");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_anivers (id BIGINT NOT NULL AUTO_INCREMENT, 
nomeUsu VARCHAR(50) NOT NULL COLLATE utf8mb4_general_ci, 
nomeCompl VARCHAR(100) COLLATE utf8mb4_general_ci, 
diaAniv VARCHAR(2) COLLATE utf8mb4_general_ci, 
mesAniv VARCHAR(2) COLLATE utf8mb4_general_ci, 
UsuCod BIGINT(20) NOT NULL DEFAULT 0, 
usuIns INT(11) NOT NULL DEFAULT 0, 
usuModif INT(11) NOT NULL DEFAULT 0, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP, 
dataModif DATETIME DEFAULT CURRENT_TIMESTAMP, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
echo "Tabela cesb_anivers checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT id FROM cesb_anivers");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_anivers` (`id`, `nomeusu`, `nomecompl`, `mesAniv`, `diaAniv`, `usuIns`, `dataIns`, `usuModif`, `dataModif`, `Ativo`) VALUES
(1,	'Ludinir',	   'Ludinir Picelli',	                        08,	26,	1,	NOW(),	1,	NOW(),	1),
(2,	'Fulano',	   'Fulano de Tal',	                           09,	27,	1,	NOW(),	1,	NOW(),	1),
(3,	'Sicrano',	   'Sicrano de Tal',	                           09,	28,	1,	NOW(),	1,	NOW(),	1),
(4,	'Beltrano',	   'Beltrano de Tal',	                        09,	29,	1,	NOW(),	1,	NOW(),	1),
(5,	'João',	      'João das Couves',	                        09,	05,	1,	NOW(),	1,	NOW(),	1),
(6,	'José',	      'José do Ovo',	                              09,	06,	1,	NOW(),	1,	NOW(),	1),
(7,	'Bananéia',	   'Bananéia da Silva',	                        09,	06,	1,	NOW(),	1,	NOW(),	1),
(8,	'Viola',	      'Benvindo Viola',	                           09,	05,	1,	NOW(),	1,	NOW(),	1),
(9,	'Aparecido',   'Bispo Aparecido',	                        09,	18,	1,	NOW(),	1,	NOW(),	1),
(10,	'Alpina ',	   'Cafiaspirina da Cruz Alpina das Alturas',	09,	15,	1,	NOW(),	1,	NOW(),	1),
(11,	'Carabino',	   'Carabino Tiro Certo',	                     09,	03,	1,	NOW(),	1,	NOW(),	1),
(12,	'Chevrolet',   'Chevrolet da Silva Ford',	                  09,	14,	1,	NOW(),	1,	NOW(),	1),
(13,	'Mirela',	   'Mirela Tapioca',	                           09,	18,	1,	NOW(),	1,	NOW(),	1),
(14,	'Linhares',	   'Oceano Atlântico Linhares',	               09,	20,	1,	NOW(),	1,	NOW(),	1),
(15,	'Camisildo',   'Camisildo da Seleção',	                     09,	13,	1,	NOW(),	1,	NOW(),	1)");

   echo "tabela cesb_anivers criada com sucesso. <br>";
}else{
   $Mes = date("m");
   $Dia = date("d");
//   mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = $Mes, diaAniv = $Dia WHERE id < 3");
//   mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = $Mes, diaAniv = $Dia+1 WHERE id >= 3");
//   mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = $Mes, diaAniv = $Dia+2 WHERE id >= 10");
//   mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = $Mes+1, diaAniv = $Dia+3 WHERE id >= 12");
//   mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '01', diaAniv = '01' WHERE id = 1");
}


//$rs = mysqli_query($xVai, "UPDATE cesb_anivers SET datains = NOW(), datamodif = NOW()");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '01' WHERE diaAniv = 1 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '02' WHERE diaAniv = 2 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '03' WHERE diaAniv = 3 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '04' WHERE diaAniv = 4 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '05' WHERE diaAniv = 5 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '06' WHERE diaAniv = 6 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '07' WHERE diaAniv = 7 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '08' WHERE diaAniv = 8 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET diaAniv = '09' WHERE diaAniv = 9 ");

mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '01' WHERE mesAniv = 1 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '02' WHERE mesAniv = 2 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '03' WHERE mesAniv = 3 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '04' WHERE mesAniv = 4 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '05' WHERE mesAniv = 5 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '06' WHERE mesAniv = 6 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '07' WHERE mesAniv = 7 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '08' WHERE mesAniv = 8 ");
mysqli_query($xVai, "UPDATE cesb_anivers SET mesAniv = '09' WHERE mesAniv = 9 ");


//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_ramais_int");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_ramais_int (CodTel BIGINT NOT NULL AUTO_INCREMENT, 
nomeusu VARCHAR(50) NOT NULL, 
nomecompl VARCHAR(100) COLLATE utf8mb4_general_ci, 
CodSetor INT(11) NOT NULL DEFAULT 0,
setor VARCHAR(20) COLLATE utf8mb4_general_ci, 
ramal VARCHAR(20) COLLATE utf8mb4_general_ci, 
usuins VARCHAR(100) COLLATE utf8mb4_general_ci, 
usumodif VARCHAR(100) COLLATE utf8mb4_general_ci, 
datains DATETIME DEFAULT CURRENT_TIMESTAMP, 
datamodif DATETIME DEFAULT CURRENT_TIMESTAMP, 
CodUser INT(11) NOT NULL DEFAULT 0, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
PRIMARY KEY (`CodTel`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
echo "Tabela cesb_ramais_int checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT CodTel FROM cesb_ramais_int");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_ramais_int` (`CodTel`, `nomeusu`, `nomecompl`, `CodSetor`, `setor`, `ramal`, `usuins`, `datains`, `usumodif`, `datamodif`, `CodUser`, `Ativo`) VALUES
   (1,	'Ludinir',	'Ludinir Picelli',	0,	'ATI',	'2222333',	NULL,	NULL,	'Fulano da Silva Sauro',	'2023-09-11 16:28:02',	683,	1),
   (2,	'Fulano',	'Fulano da Silva Sauro',	0,	'DAC',	'1111',	NULL,	NULL,	'Fulano da Silva Sauro',	'2023-09-13 16:48:46',	0,	1),
   (3,	'João',	'João das Couves',	0,	'DAF',	'4445',	NULL,	NULL,	NULL,	NULL,	0,	1),
   (4,	'Camisildo',	'Camisildo da Seleção',	0,	'ATI',	'5555',	NULL,	NULL,	NULL,	NULL,	0,	1),
   (5,	'Linhares',	'Oceano Atlântico Linhares',	0,	'DIJ',	'R777777',	NULL,	NULL,	NULL,	NULL,	0,	1),
   (6,	'Alpina',	'Cafiaspirina da Cruz Alpina das Alturas',	0,	'DED',	'8888',	NULL,	NULL,	'Fulano da Silva Sauro',	'2023-09-13 15:18:05',	0,	1),
   (7,	'Aparecido',	'Bispo Aparecido',	0,	'FAEdd',	'6777999',	NULL,	NULL,	'Fulano da Silva Sauro',	'2023-09-11 21:41:38',	0,	1),
   (8,	'Mirela',	'Mirela Tapióca com çedilha',	0,	'ATI',	'2222',	NULL,	NULL,	'Fulano da Silva Sauro',	'2023-09-11 21:16:49',	0,	1)");  
   $rs = mysqli_query($xVai, "UPDATE cesb_ramais_int SET usuins = 'Sistema', datains = NOW(), datamodif = NOW()");

   mysqli_query($xVai, "ALTER TABLE cesb_ramais_int MODIFY usuins INT(11) NOT NULL DEFAULT 0 ");
   mysqli_query($xVai, "ALTER TABLE cesb_ramais_int MODIFY usumodif INT(11) NOT NULL DEFAULT 0 ");
   mysqli_query($xVai, "UPDATE cesb_ramais_int SET usuins = 1, usumodif = 1 ");

}


//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_ramais_ext");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_ramais_ext (CodTel BIGINT NOT NULL AUTO_INCREMENT, 
SiglaEmpresa VARCHAR(50) NOT NULL COLLATE utf8mb4_general_ci, 
NomeEmpresa VARCHAR(100) COLLATE utf8mb4_general_ci, 
ContatoNome VARCHAR(100) COLLATE utf8mb4_general_ci, 
CodSetor INT(11) NOT NULL DEFAULT 0,
Setor VARCHAR(20) COLLATE utf8mb4_general_ci, 
TelefoneFixo VARCHAR(20) COLLATE utf8mb4_general_ci, 
TelefoneCel VARCHAR(20) COLLATE utf8mb4_general_ci, 
usuins VARCHAR(100) COLLATE utf8mb4_general_ci, 
usumodif VARCHAR(100) COLLATE utf8mb4_general_ci, 
datains DATETIME DEFAULT CURRENT_TIMESTAMP, 
datamodif DATETIME DEFAULT CURRENT_TIMESTAMP, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
PRIMARY KEY (`CodTel`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$rs0 = mysqli_query($xVai, "SELECT CodTel FROM cesb_ramais_ext");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_ramais_ext` (`CodTel`, `SiglaEmpresa`, `NomeEmpresa`, `ContatoNome`, `Setor`, `TelefoneFixo`, `TelefoneCel`, `usuins`, `datains`, `usumodif`, `datamodif`, `Ativo`) VALUES
   (1,	'Bombeiros DF',	'Corpo de Bombeiros Militar do Distrito Federal ',	'Sicrano',	'Emergência',	'193',	'',	NULL,	'2023-09-11 16:56:43',	'Beltrano Assintomático',	'2023-09-12 17:29:21',	1),
   (2,	'SAMU ',	'Serviço de Atendimento Móvel de Urgência',	'',	'Emergência',	'192',	'',	NULL,	'2023-09-11 16:58:18',	'Fulano da Silva Sauro',	'2023-09-11 22:03:35',	1),
   (3,	'PRF',	'Polícia Rodoviária Federal ',	'',	'Patrulha',	'191',	'',	NULL,	'2023-09-11 17:00:23',	'Fulano da Silva Sauro',	'2023-09-13 15:27:25',	1),
   (4,	'PM-DF',	'Polícia Militar do Distrito Federal',	'Fulano',	'Emergência',	'190',	'',	NULL,	'2023-09-11 17:01:17',	'Beltrano Assintomático',	'2023-09-12 16:09:17',	1),
   (5,	'QGEx',	'Quartel General do Exército',	'Macumbaldo',	'Gabinete',	'(61) 3333-4444',	'(61) 99999-9999',	'Fulano da Silva Sauro',	'2023-09-11 22:07:40',	'Fulano da Silva Sauro',	'2023-09-13 15:40:15',	1)");
   mysqli_query($xVai, "ALTER TABLE cesb_ramais_ext MODIFY usuins INT(11) NOT NULL DEFAULT 0 ");
   mysqli_query($xVai, "ALTER TABLE cesb_ramais_ext MODIFY usumodif INT(11) NOT NULL DEFAULT 0 ");
   mysqli_query($xVai, "UPDATE cesb_ramais_ext SET usuins = 1, usumodif = 1 ");

   echo "Tabela cesb_ramais_ext checada. <br>";
}

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_escolhas");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_escolhas (CodEsc INT NOT NULL AUTO_INCREMENT, 
Esc1 VARCHAR(2) DEFAULT NULL COLLATE utf8mb4_general_ci, 
Esc2 VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_general_ci, 
LiberaProj TINYINT(1) NOT NULL DEFAULT 0, 
Sit VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_general_ci, 
MotInat VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_general_ci, 
Sex VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_general_ci, 
PRIMARY KEY (`CodEsc`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$rs0 = mysqli_query($xVai, "SELECT CodEsc FROM cesb_escolhas");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_escolhas` (CodEsc, Esc1, Esc2, LiberaProj, Sit, MotInat, Sex) VALUES
   (1,	'',	'',	1, '', '', ''),
   (2,	'01',	'Janeiro', 1, 'Funcionário', 'Aposentadoria', 'Masculino'),
   (3,	'02',	'Fevereiro', 0, 'Contratado', 'Desistência', 'Feminino'),
   (4,	'03',	'Março',	0, 'Voluntário', 'Falecimento', 'Indeterminado'),
   (5,	'04',	'Abril',	0, 'Excluído', 'Abandono', ''),
   (6,	'05',	'Maio',	0, '', 'Rescisão', ''),
   (7,	'06',	'Junho',	0, '', '', ''),
   (8,	'07',	'Julho',	0, '', '', ''),
   (9,	'08',	'Agosto',	0, '', '', ''),
   (10,	'09',	'Setembro',	0, '', '', ''),
   (11,	'10',	'Outubro',	0, '', '', ''),
   (12,	'11',	'Novembro',	0, '', '', ''),
   (13,	'12',	'Dezembro',	0, '', '', ''),
   (14,	'13',	'',	0, '', '', ''),
   (15,	'14',	'',	0, '', '', ''),
   (16,	'15',	'',	0, '', '', ''),
   (17,	'16',	'',	0, '', '', ''),
   (18,	'17',	'',	0, '', '', ''),
   (19,	'18',	'',	0, '', '', ''),
   (20,	'19',	'',	0, '', '', ''),
   (21,	'20',	'',	0, '', '', ''),
   (22,	'21',	'',	0, '', '', ''),
   (23,	'22',	'',	0, '', '', ''),
   (24,	'23',	'',	0, '', '', ''),
   (25,	'24',	'',	0, '', '', ''),
   (26,	'25',	'',	0, '', '', ''),
   (27,	'26',	'',	0, '', '', ''),
   (28,	'27',	'',	0, '', '', ''),
   (29,	'28',	'',	0, '', '', ''),
   (30,	'29',	'',	0, '', '', ''),
   (31,	'30',	'',	0, '', '', ''),
   (32,	'31',	'',	0, '', '', '');
   ");

   echo "Tabela cesb_escolhas checada. <br>";
}

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_setores");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_setores (CodSet INT NOT NULL AUTO_INCREMENT, 
SiglaSetor VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_general_ci, 
DescSetor VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_general_ci, 
mOrdem INT(3) NOT NULL DEFAULT 1, 
usuIns VARCHAR(100) COLLATE utf8mb4_general_ci, 
usuModif VARCHAR(100) COLLATE utf8mb4_general_ci, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP, 
dataModif DATETIME DEFAULT CURRENT_TIMESTAMP, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
textoPag longtext COLLATE utf8mb4_general_ci, 
PRIMARY KEY (`CodSet`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$rs0 = mysqli_query($xVai, "SELECT CodSet FROM cesb_setores");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_setores` (CodSet, SiglaSetor, DescSetor, mOrdem, usuins, usumodif, Ativo, textoPag) VALUES
   (1,	'', '', 1, 1, 1, 1, ''),
   (2,   'DG', 'Diretoria-Geral',                        1, 1, 1, 1, ''),
   (3,	'DAC', 'Diretoria de Arte e Cultura',           2, 1, 1, 1, ''),
   (4,	'DAE', 'Diretoria de Assistência Espiritual',   3, 1, 1, 1, ''),
   (5,	'DAF', 'Diretoria Administrativa e Financeira', 4, 1, 1, 1, ''),
   (6,	'DAO', 'Diretoria de Atendimento e Orientação',	5, 1, 1, 1, ''),
   (7,	'DED', 'Diretoria de Estudos Doutrinários',     6, 1, 1, 1, ''), 
   (8,	'DIJ', 'Diretoria de Infância e Juventude',     7, 1, 1, 1, ''), 
   (9,	'DED', 'Diretoria de Promoção Social',          8, 1, 1, 1, ''),
   (10,	'AAD', 'Assessoria de Assuntos Doutrinários',   9, 1, 1, 1, ''),
   (11,	'ACE', 'Assessoria de Comunicação e Eventos',   10, 1, 1, 1, ''),
   (12,	'ADI', 'Assessoria de Desenvolvimento Institucional', 11, 1, 1, 1, ''),
   (13,	'AJU', 'Assessoria Jurídica',                   12, 1, 1, 1, ''),
   (14,	'AME', 'Assessoria de Estudos e Aplicações de Medicina Espiritual', 13, 1, 1, 1, ''),
   (15,	'APE', 'Assessoria de Planejamento Estratégico',  14, 1, 1, 1, ''),
   (16,	'APV', 'Assessoria da Pomada do Vovô Pedro',    15, 1, 1, 1, ''),
   (17,	'ATI', 'Assessoria de Tecnologia da Informação', 16, 1, 1, 1, ''),
   (18,	'OUV', 'Ouvidoria',                             17, 1, 1, 1, '');
   ");

   $rs0 = mysqli_query($xVai, "SELECT textoPag FROM cesb_textopag WHERE CodSet = 1");
   $tbl = mysqli_fetch_array($rs0);
   $TextoDir = $tbl["textoPag"];

   $rs1 = mysqli_query($xVai, "SELECT textoPag FROM cesb_textopag WHERE CodSet = 2");
   $tbl1 = mysqli_fetch_array($rs1);
   $TextoAss = $tbl1["textoPag"];

   mysqli_query($xVai, "UPDATE cesb_setores SET textoPag = '$TextoDir' WHERE CodSet between 2 And 9");
   mysqli_query($xVai, "UPDATE cesb_setores SET textoPag = '$TextoAss' WHERE CodSet > 9");

   echo "Tabela cesb_setores checada. <br>";
}


//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_subsetores");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_subsetores (CodSubSet INT NOT NULL AUTO_INCREMENT, 
CoddoSetor INT(11) NOT NULL DEFAULT 0, 
SiglaSubSetor VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_general_ci, 
DescSubSetor VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_general_ci, 
smOrdem INT(3) NOT NULL DEFAULT 1, 
usuIns INT(11) NOT NULL DEFAULT 0, 
usuModif INT(11) NOT NULL DEFAULT 0, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP, 
dataModif DATETIME DEFAULT CURRENT_TIMESTAMP, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
textoPag longtext COLLATE utf8mb4_general_ci, 
PRIMARY KEY (`CodSubSet`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_subsetores checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT CodSubSet FROM cesb_subsetores");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_subsetores` (CodSubSet, CoddoSetor, SiglasubSetor, DescSubSetor, smOrdem, usuins, usumodif, Ativo, textoPag) VALUES
   (1, 0, '', '', 1, 1, 1, 1, ''),
   (2, 3, 'CODAC', 'Coordenação Administrativa DAC',        1, 1, 1, 1, ''),
   (3, 3, 'DIPRA', 'Divisão de Produção Artística',         2, 1, 1, 1, ''),
   (4, 3, 'DIDAN', 'Divisão de Dança',                      3, 1, 1, 1, ''),
   (5, 3, 'DITEA', 'Divisão de Teatro',	                  4, 1, 1, 1, ''),
   (6, 3, 'DIMUS', 'Divisão de Música',                     5, 1, 1, 1, ''),
   (7, 3, 'DICIN', 'Divisão de Cinema',                     6, 1, 1, 1, ''),
   (8, 3, 'DIPPI', 'Divisão de Poesia e Pintura',           7, 1, 1, 1, ''),
   (9, 4, 'DITAD', 'Divisão de Tratamento e Desobsessão',   1, 1, 1, 1, ''),
   (10, 4, 'DIEME', 'Divisão de Educação da Mediunidade',   2, 1, 1, 1, ''),
   (11, 4, 'DIPAH', 'Divisão de Passes de Harmonização',    3, 1, 1, 1, ''),
   (12, 4, 'DIDES', 'Divisão de Desobsessão',               4, 1, 1, 1, ''),
   (13, 4, 'DIAMO', 'Divisão de Apoio ao Médium Ostensivo em Eclosão da Mediunidade', 5, 1, 1, 1, ''),
   (14, 5, 'DIADM', 'Divisão Administrativa DAF',           1, 1, 1, 1, ''),
   (15, 5, 'DIFIN', 'Divisão Fianceira',                    2, 1, 1, 1, ''),
   (16, 5, 'LIVRARIA', 'Divisão de Livraria',               3, 1, 1, 1, ''),
   (17, 5, 'BAZAR', 'Divisão de Bazar',                     4, 1, 1, 1, ''),
   (18, 5, 'ALMOX', 'Divisão de Almoxariafado',             5, 1, 1, 1, ''),

   (19, 6, 'DIVAP', 'Divisão de Atendimento ao Público',    1, 1, 1, 1, ''),
   (20, 6, 'DIVAF', 'Divisão de Atendimento Fraterno',      2, 1, 1, 1, ''),
   (21, 6, 'DIVAT', 'Divisão de Atendimento Específico e Formação',         3, 1, 1, 1, ''),
   (22, 7, 'DIFTE', 'Divisão de Formação do Trabalhador Espírita',          1, 1, 1, 1, ''),
   (23, 7, 'DIVES', 'Divisão de Estudo Sistematizado da Doutrina Espírita', 2, 1, 1, 1, ''),
   (24, 7, 'DIPAD', 'Divisão do Programa de Adaptação à Doutrina Espírita', 3, 1, 1, 1, ''),
   (25, 7, 'DIMOC', 'Divisão da Mocidade Espírita da Comunhão',             4, 1, 1, 1, ''),
   (26, 7, 'DIPAP', 'Divisão de Pesquisa e Aperfeçoamento',  5, 1, 1, 1, ''),
   (27, 7, 'DIESP', 'Divisão de Especialização',             6, 1, 1, 1, ''),

   (28, 8, 'DIRME', 'Divisão de Recursos e Meios',           1, 1, 1, 1, ''),
   (29, 8, 'DEMAT', 'Divisão de Evangelização do Maternal',  2, 1, 1, 1, ''),
   (30, 8, 'DEINF', 'Divisão de Evangelização da Infância',  3, 1, 1, 1, ''),
   (31, 8, 'DEJUV', 'Divisão de Evangelização da Juventude', 4, 1, 1, 1, ''),
   (32, 8, 'DEFAM', 'Divisão de Evangelização da Família',   5, 1, 1, 1, ''),

   (33, 9, 'DIAFA', 'Divisão de Acompanhamento de Famílias', 1, 1, 1, 1, ''),
   (34, 9, 'DIOFI', 'Divisão de Oficinas',                   2, 1, 1, 1, ''),
   (35, 9, 'DIADA', 'Divisão de Arrecadação e Distribuição de Alimentos', 3, 1, 1, 1, ''),
   (36, 9, 'DIAFRA', 'Divisão Fraterna',                     4, 1, 1, 1, '');
   ");

}

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_arqsetor");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_arqsetor (CodArq INT NOT NULL AUTO_INCREMENT, 
CodSetor INT(11) NOT NULL DEFAULT 0, 
CodSubSetor INT(11) NOT NULL DEFAULT 0, 
descArq VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_general_ci, 
usuIns INT(11) NOT NULL DEFAULT 0, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP, 
usuApag INT(11) NOT NULL DEFAULT 0, 
dataApag DATETIME DEFAULT CURRENT_TIMESTAMP, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
PRIMARY KEY (`CodArq`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_arqsetor checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT CodArq FROM cesb_arqsetor");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_arqsetor` (CodArq, CodSetor, CodSubSetor, descArq, usuIns, dataIns, Ativo) VALUES 
   (1, 2, 1, '6522093d3f8b4-DG-Lua de mel da borboleta.pdf', 2, NOW(), 1),
   (2, 4, 1, '652206a1ced3d-DAE-Lua de mel da borboleta.pdf', 2, NOW(), 1),
   (3, 2, 1, '652205b90c80d-DG-AvisoAosNavegantes.pdf', 2, NOW(), 1),
   (4, 3, 1, '65220517b5ecd-DAC-Lua de mel da borboleta.pdf', 2, NOW(), 1);");
}
//$rs1 = mysqli_query($xVai, "SELECT nomeArq FROM cesb_arqsetor LIMIT 1");
//if(!$rs1){
//   mysqli_query($xVai, "ALTER TABLE cesb_arqsetor ADD nomeArq VARCHAR(200) COLLATE utf8mb4_general_ci");
//}

$rs1 = mysqli_query($xVai, "SELECT COUNT(COLUMN_NAME) AS resultado FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'cesb_arqsetor' AND COLUMN_NAME = 'nomeArq'");
$tbl1 = mysqli_fetch_array($rs1);
if($tbl1["resultado"] == 0){
   mysqli_query($xVai, "ALTER TABLE cesb_arqsetor ADD nomeArq VARCHAR(200) COLLATE utf8mb4_general_ci");
   echo "Acerto de colunas na tabela cesb_arqsetor realizada. <br>";
}

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_tarefas");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_tarefas (idTar BIGINT NOT NULL AUTO_INCREMENT, 
usuIns BIGINT(20) NOT NULL DEFAULT 0, 
usuExec BIGINT(20) NOT NULL DEFAULT 0, 
TitTarefa Text, 
TextoTarefa Text, 
Prio TINYINT(1) DEFAULT 2,
Sit TINYINT(1) DEFAULT 1, 
DataIns DATETIME DEFAULT CURRENT_TIMESTAMP, 
dataSit1 DATETIME DEFAULT '3000/12/31', 
dataSit2 DATETIME DEFAULT '3000/12/31', 
dataSit3 DATETIME DEFAULT '3000/12/31', 
dataSit4 DATETIME DEFAULT '3000/12/31',
UsuModifSit BIGINT(20) NOT NULL DEFAULT 0,  
UsuModif BIGINT(20) NOT NULL DEFAULT 0, 
DataModif DATETIME DEFAULT '3000/12/31', 
UsuCancel BIGINT(20) NOT NULL DEFAULT 0,  
DataCancel DATETIME DEFAULT '3000/12/31', 
Ativo TINYINT(1) DEFAULT 1, 
PRIMARY KEY (`idTar`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_tarefas checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT usuExec FROM cesb_tarefas");
$row0 = mysqli_num_rows($rs0);
if($row0 > 0){
    $Erro = 0;
}else{
    mysqli_query($xVai, "INSERT INTO cesb_tarefas (usuIns, usuExec, TitTarefa, Ativo) VALUES(1, 2, 'Conserto torradeira da cantina.', 1)");
    mysqli_query($xVai, "INSERT INTO cesb_tarefas (usuIns, usuExec, TitTarefa, Ativo) VALUES(1, 2, 'Acerto documentação do Setor.', 1)");
    mysqli_query($xVai, "INSERT INTO cesb_tarefas (usuIns, usuExec, TitTarefa, Ativo) VALUES(1, 3, 'Prepara palestra para sexta-feira.', 1)");
}

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_tarefas_msg");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_tarefas_msg (idMsg BIGINT NOT NULL AUTO_INCREMENT, 
idUser BIGINT(20) DEFAULT 0, 
IdTarefa BIGINT(20) DEFAULT 0, 
usuInsTar BIGINT(20) NOT NULL DEFAULT 0, 
usuExecTar BIGINT(20) NOT NULL DEFAULT 0, 
dataMsg DATETIME DEFAULT CURRENT_TIMESTAMP, 
textoMsg Text, 
insLido TINYINT(1) DEFAULT 0, 
execLido TINYINT(1) DEFAULT 0, 
Tarefa_Ativ TINYINT(1) DEFAULT 1, 
Tarefa_Lida TINYINT(1) DEFAULT 0, 
Elim TINYINT(1) DEFAULT 0,  
dataElim DATETIME DEFAULT '3000/12/31', 
PRIMARY KEY (`idMsg`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_tarefas_msg checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT idMsg FROM cesb_tarefas_msg");
$row0 = mysqli_num_rows($rs0);
if($row0 > 0){
    $Erro = 0;
}else{
//    mysqli_query($xVai, "INSERT INTO cesb_tarefas_msg (idMsg, idUser, idTarefa, dataMsg, textoMsg) VALUES(1, 2, 1, NOW(), 'OK, recebido. Grato.')");
}

//adicionar douas colunas para marcação de lido por parte do emissor e do receptor 
$rs1 = mysqli_query($xVai, "SELECT COUNT(COLUMN_NAME) AS resultado FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'cesb_tarefas_msg' AND COLUMN_NAME = 'InsLido'");
$tbl1 = mysqli_fetch_array($rs1);
if($tbl1["resultado"] == 0){
   mysqli_query($xVai, "ALTER TABLE cesb_tarefas_msg ADD InsLido TINYINT(1) NOT NULL DEFAULT 0");
   mysqli_query($xVai, "ALTER TABLE cesb_tarefas_msg ADD ExecLido TINYINT(1) NOT NULL DEFAULT 0");
   echo "Acerto de colunas na tabela cesb_tarefas_msg realizada. <br>";
}


//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_trocas");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_trocas (idTr BIGINT NOT NULL AUTO_INCREMENT, 
idUser BIGINT(20) DEFAULT 0, 
idSetor INT(4) DEFAULT 0, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP,
textoTroca longtext, 
trocaAtiva TINYINT(1) NOT NULL DEFAULT 1, 
PRIMARY KEY (`idTr`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_trocas checada. <br>";


$rs0 = mysqli_query($xVai, "SELECT idTr FROM cesb_trocas");
$row0 = mysqli_num_rows($rs0);
if($row0 > 0){
    $Erro = 0;
}else{
    mysqli_query($xVai, "INSERT INTO cesb_trocas (idTr, idUser, idSetor, dataIns, textoTroca, trocaAtiva) 
    VALUES(1, 1, 4, NOW(), 'Armário disponível para troca, descarte ou doação. Tratar com fulano pelo ramal interno 3333. Grato.', 1)");
}

//guarda o nome das imagens inseridas nos anúncios de trocas para serem apagadas depois
//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_arqitr");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_arqitr (idiTr BIGINT NOT NULL AUTO_INCREMENT, 
idTroca BIGINT(20) DEFAULT 0, 
idUser BIGINT(20) DEFAULT 0, 
idSetor INT(4) DEFAULT 0, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP,
nomeArq VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_general_ci, 
PRIMARY KEY (`idiTr`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_arqitr checada. <br>";


//Para eventos do calendário
//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_calendev");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_calendev (idEv BIGINT NOT NULL AUTO_INCREMENT, 
evNum BIGINT(20) DEFAULT 0, 
titulo VARCHAR(250) DEFAULT NULL COLLATE utf8mb4_general_ci, 
cor VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_general_ci, 
dataIni DATETIME DEFAULT CURRENT_TIMESTAMP,
localEv longtext COLLATE utf8mb4_general_ci, 
Ativo TINYINT(1) DEFAULT 1, 
Repet TINYINT(1) DEFAULT 0,
Fixo TINYINT(1) DEFAULT 0,
UsuIns BIGINT(20) DEFAULT 0, 
UsuModif BIGINT(20) DEFAULT 0,
UsuApag BIGINT(20) DEFAULT 0, 
DataIns DATETIME DEFAULT '3000/12/31', 
DataModif DATETIME DEFAULT '3000/12/31', 
DataApag DATETIME DEFAULT '3000/12/31', 
PRIMARY KEY (`idEv`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$rs0 = mysqli_query($xVai, "SELECT idEv FROM cesb_calendev");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_calendev` (`evNum`, `titulo`, `cor`, `dataIni`, `localEv`, `Ativo`, `Repet`, `Fixo`, `UsuIns`, `UsuModif`) VALUES
(1,	'Confraternização Universal',	'#F5DEB3',	'2023-01-01 00:00:00',	'Feliz Ano Novo', 1, 2, 1, 1, 1),
(2,	'Tiradentes',                 '#F5DEB3',	'2023-04-21 00:00:00',	'',               1, 2, 1, 1, 1),
(3,	'Dia do Trabalho',            '#F5DEB3',	'2023-05-01 00:00:00',	'',               1, 2, 1, 1, 1),
(4,	'Independência do Brasil',    '#F5DEB3',	'2023-09-07 00:00:00',	'',               1, 2, 1, 1, 1),
(5,	'Padroeira do Brasil',        '#F5DEB3',	'2023-10-12 00:00:00',	'Nossa Senhora Aparecida', 1, 2, 1, 1, 1),
(6,	'Finados',                    '#F5DEB3',	'2023-11-02 00:00:00',	'',               1, 2, 1, 1, 1),
(7,	'Proclamação da República',   '#F5DEB3',	'2023-11-15 00:00:00',	'',               1, 2, 1, 1, 1),
(8,	'Natal',	                     '#F5DEB3',	'2023-12-25 00:00:00',	'Feliz Natal',    1, 2, 1, 1, 1),
(9,	'Palestra Divaldo Franco',	'#00FFFF',	'2023-10-10 00:00:00',	'Teatro Ulisses Guimarães - 10 Horas\nBrasília - DF', 1, 0, 0, 1, 1),
(10,	'Seminário Encontro da Juventude	',	'#FF7F50',	'2023-10-19 00:00:00',	'Auditório da Rainha da Paz\r\nIgreja de Madeira\r\nEixo Monumental\r\n70000-000 Basília - DF', 1, 0, 0, 1, 1),
(10,	'Seminário Encontro da Juventude	',	'#FF7F50',	'2023-10-20 00:00:00',	'Auditório da Rainha da Paz\nIgreja de Madeira\nEixo Monumental\n70000-000 Basília - DF', 1, 0, 0, 1, 1),
(10,	'Seminário Encontro da Juventude	',	'#FF7F50',	'2023-10-21 00:00:00',	'Auditório da Rainha da Paz\nIgreja de Madeira\nEixo Monumental\n70000-000 Basília - DF', 1, 0, 0, 1, 1),
(11,	'Preleção Fulano de Tal',	'#FFFF00',	'2023-10-23 00:00:00',	'Auditório Central das 18 às 20 horas\nLegião da Boa Vontade\nBrasília - DF ', 1, 0, 0, 1, 1),
(11,	'Preleção Fulano de Tal',	'#FFFF00',	'2023-10-24 00:00:00',	'Auditório Central das 18 às 20 horas\nLegião da Boa Vontade\nBrasília - DF ', 1, 0, 0, 1, 1),
(11,	'Preleção Fulano de Tal',	'#FFFF00',	'2023-10-25 00:00:00',	'Auditório Central das 18 às 20 horas\nLegião da Boa Vontade\nBrasília - DF ', 1, 0, 0, 1, 1),
(11,	'Preleção Fulano de Tal',	'#FFFF00',	'2023-10-26 00:00:00',	'Auditório Central das 18 às 20 horas\nLegião da Boa Vontade\nBrasília - DF ', 1, 0, 0, 1, 1),
(12,	'Workshop Anual',	'#00FFFF',	'2023-10-26 00:00:00',	'Legião da Boa Vontade\nBrasília - DF ', 1, 2, 0, 1, 1), 

(13,	'Encontro Anual',	'#FFFF00',	'2023-11-08 00:00:00',	'Auditório FEB\nBrasília - DF ', 1, 0, 0, 1, 1), 
(13,	'Encontro Anual',	'#FFFF00',	'2023-11-09 00:00:00',	'Auditório FEB\nBrasília - DF ', 1, 0, 0, 1, 1), 
(13,	'Encontro Anual',	'#FFFF00',	'2023-11-10 00:00:00',	'Auditório FEB\nBrasília - DF ', 1, 0, 0, 1, 1), 

(14,	'7° Congresso Espírita de Uberlândia',	'#FFFF00',	'2024-01-26 00:00:00',	'Center Convention Uberlândia\nAvenida João Naves de Ávila, 1331 Tibery.\nUberlândia - MG ', 1, 0, 0, 1, 1), 
(14,	'7° Congresso Espírita de Uberlândia',	'#FFFF00',	'2024-01-27 00:00:00',	'Center Convention Uberlândia\nAvenida João Naves de Ávila, 1331 Tibery.\nUberlândia - MG ', 1, 0, 0, 1, 1), 
(14,	'7° Congresso Espírita de Uberlândia',	'#FFFF00',	'2024-01-28 00:00:00',	'Center Convention Uberlândia\nAvenida João Naves de Ávila, 1331 Tibery.\nUberlândia - MG ', 1, 0, 0, 1, 1), 

(15,	'40° Congresso Espírita de Goiás',	'#FFFF00',	'2024-02-10 00:00:00',	'Goiânia - GO', 1, 0, 0, 1, 1),
(15,	'40° Congresso Espírita de Goiás',	'#FFFF00',	'2024-02-11 00:00:00',	'Goiânia - GO', 1, 0, 0, 1, 1),
(15,	'40° Congresso Espírita de Goiás',	'#FFFF00',	'2024-02-12 00:00:00',	'Goiânia - GO', 1, 0, 0, 1, 1), 
(15,	'40° Congresso Espírita de Goiás',	'#FFFF00',	'2024-02-13 00:00:00',	'Goiânia - GO', 1, 0, 0, 1, 1),

(16,	'Paul McCartney',	'#00FFFF',	'2023-11-30 00:00:00',	'Estádio Mané Garrincha\nBrasília - DF ', 1, 0, 0, 1, 1); 
 
 ");
}
echo "Tabela cesb_calendev checada. <br>";

//Manutenção quinquenal
mysqli_query($xVai, "DELETE FROM cesb_calendev WHERE dataIni <= DATE_SUB(CURDATE(), INTERVAL 5 YEAR)");


//Parâmetros do sistema
//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_paramsis");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_paramsis (idPar INT NOT NULL AUTO_INCREMENT, 
admVisu TINYINT(1) DEFAULT 0, 
admCad TINYINT(1) DEFAULT 0, 
admEdit TINYINT(1) DEFAULT 0, 
insAniver TINYINT(1) DEFAULT 4, 
editAniver TINYINT(1) DEFAULT 4, 
insEvento TINYINT(1) DEFAULT 4, 
editEvento TINYINT(1) DEFAULT 4, 
insTarefa TINYINT(1) DEFAULT 4, 
editTarefa TINYINT(1) DEFAULT 4, 
insOcor TINYINT(1) DEFAULT 2, 
editOcor TINYINT(1) DEFAULT 2, 
insRamais TINYINT(1) DEFAULT 7, 
editRamais TINYINT(1) DEFAULT 7, 
insTelef TINYINT(1) DEFAULT 4, 
editTelef TINYINT(1) DEFAULT 4, 
insTroca TINYINT(1) DEFAULT 4, 
editTroca TINYINT(1) DEFAULT 4, 
editPagina TINYINT(1) DEFAULT 4, 
insArq TINYINT(1) DEFAULT 4, 
PRIMARY KEY (`idPar`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$rs0 = mysqli_query($xVai, "SELECT idPar FROM cesb_paramsis");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_paramsis` (`idPar`, `admVisu`, `admCad`, `admEdit`) VALUES (1, 0, 0, 0) ");
}
echo "Tabela cesb_paramsis checada. <br>";

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_trafego");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_trafego (CodTraf INT NOT NULL AUTO_INCREMENT, 
descArq VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_general_ci, 
nomeArq VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_general_ci, 
usuIns BIGINT(20) NOT NULL DEFAULT 0, 
dataIns DATETIME DEFAULT CURRENT_TIMESTAMP, 
usuDest BIGINT(20) NOT NULL DEFAULT 0, 
usuApag BIGINT(20) NOT NULL DEFAULT 0, 
dataApag DATETIME DEFAULT CURRENT_TIMESTAMP, 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
PRIMARY KEY (`CodTraf`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_trafego checada. <br>";


//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_carousel");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_carousel (CodCar INT NOT NULL AUTO_INCREMENT, 
descArq VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_general_ci, 
descArqAnt VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_general_ci, 
PRIMARY KEY (`CodCar`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_carousel checada. <br>";

$rs0 = mysqli_query($xVai, "SELECT CodCar FROM cesb_carousel");
$row0 = mysqli_num_rows($rs0);
if($row0 == 0){
   $rs = mysqli_query($xVai, "INSERT INTO `cesb_carousel` (`CodCar`, `descArq`, `descArqAnt`) VALUES
      (1,	'imgfundo0.jpg', ''),
      (2,	'imgfundo1.jpg', ''),
      (3,	'imgfundo2.jpg', ''),
      (4,	'imgfundo3.jpg', ''); ");
}


//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_ocorrencias");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_ocorrencias (CodOcor INT NOT NULL AUTO_INCREMENT, 
usuIns BIGINT(20) NOT NULL DEFAULT 0, 
dataIns DATETIME DEFAULT '3000/12/31', 
dataOcor DATETIME DEFAULT '3000/12/31', 
CodSetor INT(11) NOT NULL DEFAULT 0, 
usuModif BIGINT(20) NOT NULL DEFAULT 0, 
dataModif DATETIME DEFAULT '3000/12/31', 
Ativo TINYINT(1) NOT NULL DEFAULT 1, 
Ocorrencia longtext COLLATE utf8mb4_general_ci, 
NumOcor VARCHAR(100) COLLATE utf8mb4_general_ci, 
PRIMARY KEY (`CodOcor`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_ocorrencias checada. <br>";

//mysqli_query($xVai, "DROP TABLE IF EXISTS cesb_ocorrideogr");
mysqli_query($xVai, "CREATE TABLE IF NOT EXISTS cesb_ocorrideogr (CodIdeo INT NOT NULL AUTO_INCREMENT, 
CoddaOcor BIGINT(20) NOT NULL DEFAULT 0, 
descIdeo VARCHAR(100) COLLATE utf8mb4_general_ci, 
CodProv BIGINT(20) NOT NULL DEFAULT 0, 
PRIMARY KEY (`CodIdeo`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

echo "Tabela cesb_ocorrideogr checada. <br>";