ALTER TABLE `portal_compras`.`solicitacoes` 
ADD COLUMN `motivo_cotacao` VARCHAR(240) NULL DEFAULT NULL AFTER `motivo_compra`;
