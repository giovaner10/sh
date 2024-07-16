ALTER TABLE `portal_compras`.`produtos`
ADD COLUMN `tipo` VARCHAR(5) NOT NULL AFTER `codigo_empresa`;
