ALTER TABLE `portal_compras`.`cotacoes`
ADD COLUMN `produtos` JSON NOT NULL AFTER `fornecedor`;

