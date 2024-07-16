INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Portal de Compras', 'portal_compras', '1', 'Portal de Compras');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Visualizar Produtos - Portal Compras', 'vis_produtos_portal_compras', '1', 'Portal de Compras');

INSERT INTO `showtecsystem`.`menu`
(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('portal_compras', 'PortalCompras/Solicitacoes', 'portal_compras', 'nao', NULL, 'portal_compras', 11, 'ativo', 'portal_compras', 'portal_compras', NULL);


create schema portal_compras;


CREATE TABLE `portal_compras`.`produtos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) NOT NULL,
  `codigo_ncm` CHAR(8) NOT NULL,
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` DATETIME NULL DEFAULT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`));

