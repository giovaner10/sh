ALTER TABLE `portal_compras`.`produtos`
DROP INDEX `nome_UNIQUE`,
ADD UNIQUE INDEX `nome_status_UNIQUE` (`nome` ASC, `status` ASC);
;

ALTER TABLE `portal_compras`.`produtos`
CHANGE COLUMN `codigo_ncm` `ncm` VARCHAR(120) NOT NULL;
