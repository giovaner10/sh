ALTER TABLE `portal_compras`.`configuracoes` 
ADD COLUMN `aprovadores` JSON NULL DEFAULT NULL AFTER `centros_de_custo`;
