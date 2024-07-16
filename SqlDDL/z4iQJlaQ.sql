ALTER TABLE `showtecsystem`.`termo_adesao`
ADD COLUMN `aditivo_de` INT(11) NULL DEFAULT NULL COMMENT 'Indica que um termo eh aditivo de outro, e indica o id do termo do qual ele eh aditivo.' AFTER `end_entrega_id`;

ALTER TABLE `systems`.`termo_adesao_sim`
ADD COLUMN `aditivo_de` INT(11) NULL DEFAULT NULL COMMENT 'Indica que um termo eh aditivo de outro, e indica o id do termo do qual ele eh aditivo.' AFTER `end_entrega_id`;
