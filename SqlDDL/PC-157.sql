ALTER TABLE `portal_compras`.`solicitacoes` 
ADD COLUMN `id_empresas` INT UNSIGNED NOT NULL AFTER `id_usuario_compras`,
ADD INDEX `idx_fk__solicitacoes__empresas` (`id_empresas` ASC);
;
ALTER TABLE `portal_compras`.`solicitacoes` 
ADD CONSTRAINT `fk__solicitacoes__empresas`
  FOREIGN KEY (`id_empresas`)
  REFERENCES `portal_compras`.`empresas` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `portal_compras`.`solicitacoes`
ADD UNIQUE INDEX `UNIQUE__codigo_pedido_erp__id_empresas__id_filiais` (
    `codigo_pedido_erp` ASC,
    `id_filiais` ASC,
    `id_empresas` ASC
);
;


ALTER TABLE `portal_compras`.`nota_fiscal`
ADD COLUMN `codigo_empresa` VARCHAR(20) NOT NULL AFTER `path_anexo`,
ADD COLUMN `codigo_filial` VARCHAR(20) NOT NULL AFTER `codigo_empresa`;

ALTER TABLE `portal_compras`.`nota_fiscal`
CHANGE COLUMN `data_vencimento` `data_vencimento` DATE NULL DEFAULT NULL;
