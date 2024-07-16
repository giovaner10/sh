CREATE TABLE `portal_compras`.`nota_fiscal` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`codigo_pedido_erp` VARCHAR(20) NOT NULL,
	`status` ENUM('pre_nota', 'classificado', 'excluido') NOT NULL DEFAULT 'pre_nota',
	`numero` VARCHAR(9) NOT NULL,
	`serie` VARCHAR(3) NOT NULL,
	`especie` VARCHAR(60) NULL DEFAULT NULL,
	`valor` FLOAT(10, 2) NOT NULL,
	`datahora_emissao` DATETIME NOT NULL,
	`data_vencimento` DATE NOT NULL,
	`path_anexo` VARCHAR(120) NULL DEFAULT NULL,
	`datahora_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `idx_codigo_pedido_erp` (`codigo_pedido_erp` ASC)
);


ALTER TABLE `portal_compras`.`solicitacoes`
CHANGE COLUMN `situacao` `situacao` ENUM(
	'aguardando_produto_cotacao',
	'aguardando_cotacao',
	'aguardando_confirmacao_cotacao',
	'aguardando_aprovacao',
	'reprovado',
	'aguardando_pre_nota',
	'aguardando_fiscal',
	'finalizado'
) NOT NULL DEFAULT 'aguardando_cotacao';

