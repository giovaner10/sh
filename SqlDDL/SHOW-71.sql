ALTER TABLE `showtecsystem`.`cad_iscas` 
CHANGE COLUMN `id_cliente` `id_cliente` INT(10) UNSIGNED NULL ,
CHANGE COLUMN `id_contrato` `id_contrato` INT(11) UNSIGNED NULL ;

UPDATE showtecsystem.cad_iscas SET id_cliente = null where id_cliente = 0;
UPDATE showtecsystem.cad_iscas SET id_contrato = null where id_contrato = 0;