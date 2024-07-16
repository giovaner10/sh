ALTER TABLE `showtecsystem`.`cad_planos` 
ADD COLUMN `editavel` ENUM('0', '1') NOT NULL DEFAULT '0' AFTER `status`;

CREATE TABLE `showtecsystem`.`cad_cliente_plano` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cliente` INT(11) UNSIGNED NOT NULL,
  `id_plano` INT UNSIGNED NOT NULL,
  `data_cad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('0', '1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

ALTER TABLE `showtecsystem`.`cad_clientes` 
DROP COLUMN `id_plano`;


