ALTER TABLE `showtecsystem`.`usuario` 
ADD COLUMN `id_arquivos` INT(11) UNSIGNED NULL AFTER `unidade`;

ALTER TABLE `showtecsystem`.`usuario` 
ADD INDEX `idx_fk__usuario__id_arquivos__arquivos` (`id_arquivos` ASC);
;
ALTER TABLE `showtecsystem`.`usuario` 
ADD CONSTRAINT `fk__usuario__id_arquivos__arquivos`
  FOREIGN KEY (`id_arquivos`)
  REFERENCES `showtecsystem`.`arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
