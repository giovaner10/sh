CREATE TABLE `showtecsystem`.`cad_modulo_permissao` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_modulo` INT(11) UNSIGNED NOT NULL,
  `id_permissao` INT(11) UNSIGNED NOT NULL,
  `status` ENUM('0', '1') NOT NULL DEFAULT '1',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`));

INSERT INTO showtecsystem.cad_modulo_permissao (id_modulo, id_permissao)
SELECT id_modulo, id
FROM showtecsystem.cad_permissoes;

ALTER TABLE `showtecsystem`.`cad_permissoes` 
DROP COLUMN `id_modulo`,
DROP COLUMN `categoria`,
CHANGE COLUMN `data_cad` `data_cad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
DROP INDEX `categoria` ;
;

