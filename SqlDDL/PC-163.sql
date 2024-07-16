ALTER TABLE `portal_compras`.`cotacoes`
ADD COLUMN `motivo_selecao_cotacao` VARCHAR(240) NULL DEFAULT NULL AFTER `tipo_especie`;