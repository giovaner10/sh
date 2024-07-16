UPDATE showtecsystem.cad_chips SET status = 1 WHERE (id_equipamento is not null) AND id_equipamento != 0 AND status = 0;

ALTER TABLE `showtecsystem`.`cad_equipamentos` 
ADD COLUMN `id_chip_2` INT(11) NULL DEFAULT NULL AFTER `id_chip`;
