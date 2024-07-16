UPDATE `showtecsystem`.`departamentos` SET `nome` = 'Tecnologia da Informação' WHERE (`id` = '3');


UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%rh%' OR ocupacao LIKE '%gente e gestao%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 1

UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%financeiro%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 2

UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%desenvolvedor%' OR ocupacao LIKE '%Analista de Sistemas%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 3

UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%engenheiro%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 4

UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%comercial%' OR ocupacao LIKE '%televendas%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 5

UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%marketing%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 6

UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%opera%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 7

UPDATE usuario 
JOIN (SELECT id AS id_sub FROM usuario
    WHERE ocupacao LIKE '%qualidade%') AS sub ON usuario.id = sub.id_sub
SET id_departamentos = 8


/* Novos menus */
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `id_pai`, `icone`, `status`) VALUES ('informacoes_gerais', 'Financeiros/InformacoesGerais', 'vis_financeiroinformacaogeral', '13', 'info', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'TecnologiasInformacoes/InformacoesGerais', 'vis_tecnologiadainformacaoinformacaogeral', '29', 'devices', '41', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'Engenharias/InformacoesGerais', 'vis_engenhariainformacaogeral', '30', 'engineering', '46', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'Marketings/InformacoesGerais', 'vis_marketinginformacaogeral', '32', 'tungsten', '56', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'GovernacasCorporativas/InformacoesGerais', 'vis_governancacorporativainformacaogeral', '35', 'corporate_fare', '71', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'Operacoes/InformacoesGerais', 'vis_operacoesinformacaogeral', '33', 'device_hub', '61', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'ControlesQualidades/InformacoesGerais', 'vis_controledequalidadeinformacaogeral', '34', 'hd', '66', 'ativo');


/* Ordens */
UPDATE `showtecsystem`.`menu` SET `icone` = 'paid' WHERE (`id` = '13');
UPDATE `showtecsystem`.`menu` SET `ordem` = '36' WHERE (`id` = '28');
UPDATE `showtecsystem`.`menu` SET `ordem` = '35' WHERE (`id` = '27');
UPDATE `showtecsystem`.`menu` SET `ordem` = '34' WHERE (`id` = '26');
UPDATE `showtecsystem`.`menu` SET `ordem` = '33' WHERE (`id` = '25');
UPDATE `showtecsystem`.`menu` SET `ordem` = '32' WHERE (`id` = '24');
UPDATE `showtecsystem`.`menu` SET `ordem` = '31' WHERE (`id` = '23');
UPDATE `showtecsystem`.`menu` SET `ordem` = '30' WHERE (`id` = '22');
UPDATE `showtecsystem`.`menu` SET `ordem` = '29' WHERE (`id` = '21');
UPDATE `showtecsystem`.`menu` SET `ordem` = '28' WHERE (`id` = '20');
UPDATE `showtecsystem`.`menu` SET `ordem` = '27' WHERE (`id` = '19');
UPDATE `showtecsystem`.`menu` SET `ordem` = '26' WHERE (`id` = '18');
UPDATE `showtecsystem`.`menu` SET `ordem` = '25' WHERE (`id` = '17');
UPDATE `showtecsystem`.`menu` SET `ordem` = '24' WHERE (`id` = '16');
UPDATE `showtecsystem`.`menu` SET `ordem` = '23' WHERE (`id` = '15');
UPDATE `showtecsystem`.`menu` SET `ordem` = '22' WHERE (`id` = '14');


UPDATE `showtecsystem`.`menu` SET `icone` = 'paid', `ordem` = '21', `status` = 'ativo' WHERE (`id` = '138');
UPDATE `showtecsystem`.`menu` SET `caminho` = 'GentesGestoes/InformacoesGerais' WHERE (`id` = '8');
UPDATE `showtecsystem`.`menu` SET `nome` = 'tecnologia_da_informacao', `codigo_permissao` = 'vis_tecnologiadainformacao' WHERE (`id` = '29');
