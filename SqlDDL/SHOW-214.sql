ALTER TABLE 'showtecsystem.instaladores' ADD 'rg' VARCHAR(11) NOT NULL DEFAULT '' AFTER 'sobrenome'
ALTER TABLE 'showtecsystem.instaladores' ADD 'pis' VARCHAR(15) NULL DEFAULT '' AFTER 'rg'
ALTER TABLE 'showtecsystem.instaladores' ADD 'estado_civil' VARCHAR(30) NOT NULL DEFAULT '' AFTER 'pis'
ALTER TABLE 'showtecsystem.instaladores' ADD 'data_nascimento' DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER estado_civil;
ALTER TABLE `showtecsystem`.`instaladores` CHANGE COLUMN `pis` `pis` VARCHAR(15) NULL DEFAULT NULL ;
ALTER TABLE `showtecsystem`.`instaladores` CHANGE COLUMN `data_nascimento` `data_nascimento` DATETIME NOT NULL ;
ALTER TABLE `showtecsystem`.`instaladores` CHANGE COLUMN `rg` `rg` VARCHAR(14) NOT NULL DEFAULT '' ;
