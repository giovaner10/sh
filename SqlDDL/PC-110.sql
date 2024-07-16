ALTER TABLE `showtecsystem`.`usuario` 
ADD COLUMN `funcao_portal` ENUM('solicitante', 'aprovador', 'area_compras', 'area_fiscal', 'area_financeira') NULL DEFAULT NULL AFTER `id_chefia_imediata`;


INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Atribuir funçâo - Portal Compras', 'cad_funcao_portal_compras', '1', 'Portal de Compras');