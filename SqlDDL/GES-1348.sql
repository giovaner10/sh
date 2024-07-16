ALTER TABLE `showtecsystem`.`perfis_profissionais` 
ADD INDEX `idx_status_analise` (`status_analise` ASC),
ADD INDEX `idx_situacao` USING BTREE (`situacao`);
;

ALTER TABLE `showtecsystem`.`perfis_profissionais` 
CHANGE COLUMN `ultima_analise` `ultima_analise` DATETIME NULL DEFAULT NULL ,
CHANGE COLUMN `validade` `validade` DATETIME NULL DEFAULT NULL ;

