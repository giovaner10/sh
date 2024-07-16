ALTER TABLE `showtecsystem`.`usuario_gestor` 
CHANGE COLUMN `dhcad_usuario` `dhcad_usuario` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE `showtecsystem`.`usuario_gestor` 
CHANGE COLUMN `token_usuario` `token_usuario` VARCHAR(60) NULL DEFAULT '' ;
