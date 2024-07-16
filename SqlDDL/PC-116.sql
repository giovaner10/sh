INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Incluir Nota Fiscal - Portal Compras', 'cad_nota_fiscal_portal_compras', '1', 'Portal de Compras');


CREATE TABLE `portal_compras`.`especies` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`codigo` varchar(10) NOT NULL COMMENT 'Código (chave primaria) da especie no ERP',
	`descricao` varchar(120) NOT NULL,
	`status` enum('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
	`codigo_empresa` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
	`data_cadastro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`data_modificacao` datetime NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `idx__codigo_empresa` (`codigo_empresa`),
	UNIQUE KEY `UNIQUE__codigo__codigo_empresa` (`codigo`, `codigo_empresa`)
);