ALTER TABLE `showtecsystem`.`menu` 
DROP INDEX `idx_ordem` ,
DROP INDEX `idx_id` ;
;

ALTER TABLE `showtecsystem`.`menu` 
CHANGE COLUMN `ordem` `ordem` INT(11) NOT NULL DEFAULT 999 ;


/* Alterações icones */
UPDATE `showtecsystem`.`menu` SET `icone` = 'view_module' WHERE (`id` = '6');

/* Alterações caminhos */
UPDATE `showtecsystem`.`menu` SET `caminho` = 'Homes' WHERE (`id` = '1');

/* Alterações permissões */
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_genteegestaoinformacaogeral' WHERE (`id` = '8');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_empresa' WHERE (`id` = '2');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'dashboard_iscas' WHERE (`id` = '103');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'dashboard_iscas' WHERE (`id` = '104');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'dashboard_iscas' WHERE (`id` = '105');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_iscas' WHERE (`id` = '102');

/* Alterações ordem */
UPDATE `showtecsystem`.`menu` SET `ordem` = '1' WHERE (`id` = '1');
UPDATE `showtecsystem`.`menu` SET `ordem` = '2' WHERE (`id` = '2');
UPDATE `showtecsystem`.`menu` SET `ordem` = '3' WHERE (`id` = '3');
UPDATE `showtecsystem`.`menu` SET `ordem` = '4' WHERE (`id` = '4');
UPDATE `showtecsystem`.`menu` SET `ordem` = '5' WHERE (`id` = '5');
UPDATE `showtecsystem`.`menu` SET `ordem` = '10' WHERE (`id` = '6');
UPDATE `showtecsystem`.`menu` SET `ordem` = '11' WHERE (`id` = '7');
UPDATE `showtecsystem`.`menu` SET `ordem` = '12' WHERE (`id` = '8');
UPDATE `showtecsystem`.`menu` SET `ordem` = '13' WHERE (`id` = '9');
UPDATE `showtecsystem`.`menu` SET `ordem` = '14' WHERE (`id` = '10');
UPDATE `showtecsystem`.`menu` SET `ordem` = '20' WHERE (`id` = '13');
UPDATE `showtecsystem`.`menu` SET `ordem` = '21' WHERE (`id` = '14');
UPDATE `showtecsystem`.`menu` SET `ordem` = '22' WHERE (`id` = '15');
UPDATE `showtecsystem`.`menu` SET `ordem` = '23' WHERE (`id` = '16');
UPDATE `showtecsystem`.`menu` SET `ordem` = '24' WHERE (`id` = '17');
UPDATE `showtecsystem`.`menu` SET `ordem` = '25' WHERE (`id` = '18');
UPDATE `showtecsystem`.`menu` SET `ordem` = '26' WHERE (`id` = '19');
UPDATE `showtecsystem`.`menu` SET `ordem` = '27' WHERE (`id` = '20');
UPDATE `showtecsystem`.`menu` SET `ordem` = '28' WHERE (`id` = '21');
UPDATE `showtecsystem`.`menu` SET `ordem` = '29' WHERE (`id` = '22');
UPDATE `showtecsystem`.`menu` SET `ordem` = '30' WHERE (`id` = '23');
UPDATE `showtecsystem`.`menu` SET `ordem` = '31' WHERE (`id` = '24');
UPDATE `showtecsystem`.`menu` SET `ordem` = '32' WHERE (`id` = '25');
UPDATE `showtecsystem`.`menu` SET `ordem` = '33' WHERE (`id` = '26');
UPDATE `showtecsystem`.`menu` SET `ordem` = '34' WHERE (`id` = '27');
UPDATE `showtecsystem`.`menu` SET `ordem` = '35' WHERE (`id` = '28');
UPDATE `showtecsystem`.`menu` SET `ordem` = '40' WHERE (`id` = '29');
UPDATE `showtecsystem`.`menu` SET `ordem` = '45' WHERE (`id` = '30');
UPDATE `showtecsystem`.`menu` SET `ordem` = '50' WHERE (`id` = '31');
UPDATE `showtecsystem`.`menu` SET `ordem` = '55' WHERE (`id` = '32');
UPDATE `showtecsystem`.`menu` SET `ordem` = '60' WHERE (`id` = '33');
UPDATE `showtecsystem`.`menu` SET `ordem` = '65' WHERE (`id` = '34');
UPDATE `showtecsystem`.`menu` SET `ordem` = '70' WHERE (`id` = '35');
UPDATE `showtecsystem`.`menu` SET `ordem` = '75' WHERE (`id` = '36');
UPDATE `showtecsystem`.`menu` SET `ordem` = '76' WHERE (`id` = '37');
UPDATE `showtecsystem`.`menu` SET `ordem` = '77' WHERE (`id` = '38');
UPDATE `showtecsystem`.`menu` SET `ordem` = '78' WHERE (`id` = '39');
UPDATE `showtecsystem`.`menu` SET `ordem` = '79' WHERE (`id` = '40');
UPDATE `showtecsystem`.`menu` SET `ordem` = '80' WHERE (`id` = '41');
UPDATE `showtecsystem`.`menu` SET `ordem` = '81' WHERE (`id` = '42');
UPDATE `showtecsystem`.`menu` SET `ordem` = '82' WHERE (`id` = '43');
UPDATE `showtecsystem`.`menu` SET `ordem` = '83' WHERE (`id` = '44');
UPDATE `showtecsystem`.`menu` SET `ordem` = '84' WHERE (`id` = '45');
UPDATE `showtecsystem`.`menu` SET `ordem` = '85' WHERE (`id` = '46');
UPDATE `showtecsystem`.`menu` SET `ordem` = '86' WHERE (`id` = '47');
UPDATE `showtecsystem`.`menu` SET `ordem` = '87' WHERE (`id` = '48');
UPDATE `showtecsystem`.`menu` SET `ordem` = '88' WHERE (`id` = '49');
UPDATE `showtecsystem`.`menu` SET `ordem` = '89' WHERE (`id` = '50');
UPDATE `showtecsystem`.`menu` SET `ordem` = '90' WHERE (`id` = '51');
UPDATE `showtecsystem`.`menu` SET `ordem` = '91' WHERE (`id` = '52');
UPDATE `showtecsystem`.`menu` SET `ordem` = '92' WHERE (`id` = '53');
UPDATE `showtecsystem`.`menu` SET `ordem` = '93' WHERE (`id` = '54');
UPDATE `showtecsystem`.`menu` SET `ordem` = '94' WHERE (`id` = '55');
UPDATE `showtecsystem`.`menu` SET `ordem` = '95' WHERE (`id` = '56');
UPDATE `showtecsystem`.`menu` SET `ordem` = '100' WHERE (`id` = '57');
UPDATE `showtecsystem`.`menu` SET `ordem` = '101' WHERE (`id` = '58');
UPDATE `showtecsystem`.`menu` SET `ordem` = '102' WHERE (`id` = '59');
UPDATE `showtecsystem`.`menu` SET `ordem` = '103' WHERE (`id` = '60');
UPDATE `showtecsystem`.`menu` SET `ordem` = '104' WHERE (`id` = '61');
UPDATE `showtecsystem`.`menu` SET `ordem` = '105' WHERE (`id` = '62');
UPDATE `showtecsystem`.`menu` SET `ordem` = '106' WHERE (`id` = '63');
UPDATE `showtecsystem`.`menu` SET `ordem` = '107' WHERE (`id` = '64');
UPDATE `showtecsystem`.`menu` SET `ordem` = '108' WHERE (`id` = '65');
UPDATE `showtecsystem`.`menu` SET `ordem` = '109' WHERE (`id` = '66');
UPDATE `showtecsystem`.`menu` SET `ordem` = '110' WHERE (`id` = '67');
UPDATE `showtecsystem`.`menu` SET `ordem` = '111' WHERE (`id` = '68');
UPDATE `showtecsystem`.`menu` SET `ordem` = '112' WHERE (`id` = '69');
UPDATE `showtecsystem`.`menu` SET `ordem` = '113' WHERE (`id` = '70');
UPDATE `showtecsystem`.`menu` SET `ordem` = '114' WHERE (`id` = '71');
UPDATE `showtecsystem`.`menu` SET `ordem` = '115' WHERE (`id` = '72');
UPDATE `showtecsystem`.`menu` SET `ordem` = '116' WHERE (`id` = '73');
UPDATE `showtecsystem`.`menu` SET `ordem` = '117' WHERE (`id` = '74');
UPDATE `showtecsystem`.`menu` SET `ordem` = '118' WHERE (`id` = '75');
UPDATE `showtecsystem`.`menu` SET `ordem` = '119' WHERE (`id` = '76');
UPDATE `showtecsystem`.`menu` SET `ordem` = '120' WHERE (`id` = '77');
UPDATE `showtecsystem`.`menu` SET `ordem` = '121' WHERE (`id` = '78');
UPDATE `showtecsystem`.`menu` SET `ordem` = '122' WHERE (`id` = '79');
UPDATE `showtecsystem`.`menu` SET `ordem` = '123' WHERE (`id` = '80');
UPDATE `showtecsystem`.`menu` SET `ordem` = '124' WHERE (`id` = '81');
UPDATE `showtecsystem`.`menu` SET `ordem` = '125' WHERE (`id` = '82');
UPDATE `showtecsystem`.`menu` SET `ordem` = '126' WHERE (`id` = '83');
UPDATE `showtecsystem`.`menu` SET `ordem` = '127' WHERE (`id` = '84');
UPDATE `showtecsystem`.`menu` SET `ordem` = '128' WHERE (`id` = '85');
UPDATE `showtecsystem`.`menu` SET `ordem` = '129' WHERE (`id` = '86');
UPDATE `showtecsystem`.`menu` SET `ordem` = '130' WHERE (`id` = '87');
UPDATE `showtecsystem`.`menu` SET `ordem` = '131' WHERE (`id` = '88');
UPDATE `showtecsystem`.`menu` SET `ordem` = '132' WHERE (`id` = '89');
UPDATE `showtecsystem`.`menu` SET `ordem` = '133' WHERE (`id` = '90');
UPDATE `showtecsystem`.`menu` SET `ordem` = '134' WHERE (`id` = '91');
UPDATE `showtecsystem`.`menu` SET `ordem` = '135' WHERE (`id` = '92');
UPDATE `showtecsystem`.`menu` SET `ordem` = '136' WHERE (`id` = '93');
UPDATE `showtecsystem`.`menu` SET `ordem` = '140' WHERE (`id` = '94');
UPDATE `showtecsystem`.`menu` SET `ordem` = '141' WHERE (`id` = '95');
UPDATE `showtecsystem`.`menu` SET `ordem` = '142' WHERE (`id` = '96');
UPDATE `showtecsystem`.`menu` SET `ordem` = '143' WHERE (`id` = '97');
UPDATE `showtecsystem`.`menu` SET `ordem` = '144' WHERE (`id` = '98');
UPDATE `showtecsystem`.`menu` SET `ordem` = '145' WHERE (`id` = '99');
UPDATE `showtecsystem`.`menu` SET `ordem` = '146' WHERE (`id` = '100');
UPDATE `showtecsystem`.`menu` SET `ordem` = '147' WHERE (`id` = '101');
UPDATE `showtecsystem`.`menu` SET `ordem` = '150' WHERE (`id` = '102');
UPDATE `showtecsystem`.`menu` SET `ordem` = '151' WHERE (`id` = '103');
UPDATE `showtecsystem`.`menu` SET `ordem` = '152' WHERE (`id` = '104');
UPDATE `showtecsystem`.`menu` SET `ordem` = '153' WHERE (`id` = '105');
UPDATE `showtecsystem`.`menu` SET `ordem` = '154' WHERE (`id` = '106');
UPDATE `showtecsystem`.`menu` SET `ordem` = '155' WHERE (`id` = '107');
UPDATE `showtecsystem`.`menu` SET `ordem` = '156' WHERE (`id` = '108');
UPDATE `showtecsystem`.`menu` SET `ordem` = '157' WHERE (`id` = '109');
UPDATE `showtecsystem`.`menu` SET `ordem` = '158' WHERE (`id` = '110');
UPDATE `showtecsystem`.`menu` SET `ordem` = '159' WHERE (`id` = '111');
UPDATE `showtecsystem`.`menu` SET `ordem` = '160' WHERE (`id` = '112');
UPDATE `showtecsystem`.`menu` SET `ordem` = '161' WHERE (`id` = '113');
UPDATE `showtecsystem`.`menu` SET `ordem` = '162' WHERE (`id` = '114');
UPDATE `showtecsystem`.`menu` SET `ordem` = '165' WHERE (`id` = '115');
UPDATE `showtecsystem`.`menu` SET `ordem` = '166' WHERE (`id` = '116');
UPDATE `showtecsystem`.`menu` SET `ordem` = '167' WHERE (`id` = '117');
UPDATE `showtecsystem`.`menu` SET `ordem` = '170' WHERE (`id` = '118');
UPDATE `showtecsystem`.`menu` SET `ordem` = '171' WHERE (`id` = '119');
UPDATE `showtecsystem`.`menu` SET `ordem` = '172' WHERE (`id` = '120');
UPDATE `showtecsystem`.`menu` SET `ordem` = '173' WHERE (`id` = '121');
UPDATE `showtecsystem`.`menu` SET `ordem` = '174' WHERE (`id` = '122');
UPDATE `showtecsystem`.`menu` SET `ordem` = '175' WHERE (`id` = '123');
UPDATE `showtecsystem`.`menu` SET `ordem` = '176' WHERE (`id` = '124');
UPDATE `showtecsystem`.`menu` SET `ordem` = '177' WHERE (`id` = '125');
UPDATE `showtecsystem`.`menu` SET `ordem` = '180' WHERE (`id` = '126');
UPDATE `showtecsystem`.`menu` SET `ordem` = '181' WHERE (`id` = '127');
UPDATE `showtecsystem`.`menu` SET `ordem` = '182' WHERE (`id` = '128');
UPDATE `showtecsystem`.`menu` SET `ordem` = '185' WHERE (`id` = '129');
UPDATE `showtecsystem`.`menu` SET `ordem` = '186' WHERE (`id` = '130');
UPDATE `showtecsystem`.`menu` SET `ordem` = '187' WHERE (`id` = '131');
UPDATE `showtecsystem`.`menu` SET `ordem` = '188' WHERE (`id` = '132');
UPDATE `showtecsystem`.`menu` SET `ordem` = '189' WHERE (`id` = '133');
UPDATE `showtecsystem`.`menu` SET `ordem` = '190' WHERE (`id` = '134');
UPDATE `showtecsystem`.`menu` SET `ordem` = '191' WHERE (`id` = '135');
UPDATE `showtecsystem`.`menu` SET `ordem` = '192' WHERE (`id` = '136');
UPDATE `showtecsystem`.`menu` SET `ordem` = '193' WHERE (`id` = '137');

INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('listagem_de_dashboards', 'Dashboards', 'cad_cadastrodashboard', 'nao', '129', 'dashboard', '1', 'ativo');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`descricao`, `cod_permissao`, `status`, `data_cad`, `modulo`) VALUES ('Indicadores SAC', 'vis_indicadoressac', '1', '2022-08-19 11:35:12', 'Dashboards');
UPDATE `showtecsystem`.`dashboards` SET `permissao` = 'vis_indicadoressac' WHERE (`id` = '8');

UPDATE dashboards
INNER JOIN menu ON (menu.codigo_permissao = dashboards.permissao)
SET dashboards.id_menu = menu.id
WHERE dashboards.id <> ''

DELETE FROM dashboards WHERE id_menu = 0;

ALTER TABLE `showtecsystem`.`dashboards` 
ADD COLUMN `id_menu` INT(11) UNSIGNED NOT NULL AFTER `ativo`,
ADD INDEX `idx_fk__dashboards__id_menu__menu` (`id_menu` ASC);
;
ALTER TABLE `showtecsystem`.`dashboards` 
ADD CONSTRAINT `fk__dashboards__id_menu__menu`
  FOREIGN KEY (`id_menu`)
  REFERENCES `showtecsystem`.`menu` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_dashboards' WHERE (`id` = '129');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_suporte' WHERE (`id` = '118');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'clientes_visualiza' WHERE (`id` = '83');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_chiplinhas' WHERE (`id` = '79');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_relatoriosfinanceiro' WHERE (`id` = '66');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_assinaturaseptc' WHERE (`id` = '58');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_relatorios' WHERE (`id` = '57');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'cad_chips' WHERE (`id` = '55');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_cadastros' WHERE (`id` = '36');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_departamentos' WHERE (`id` = '6');
UPDATE `showtecsystem`.`menu` SET `codigo_permissao` = 'vis_comercialetelevendas' WHERE (`id` = '31');
