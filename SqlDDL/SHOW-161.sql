ALTER TABLE `showtecsystem`.`cad_comunicados` 
CHANGE COLUMN `ativo` `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo' ;