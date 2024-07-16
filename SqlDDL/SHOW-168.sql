CREATE TABLE `showtecsystem`.`guia` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NOT NULL,
  `id_arquivos` INT(10) UNSIGNED NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  INDEX `idx_fk__guia__id_arquivos__arquivos` (`id_arquivos` ASC),
  CONSTRAINT `fk__guia__id_arquivos__arquivos`
    FOREIGN KEY (`id_arquivos`)
    REFERENCES `showtecsystem`.`arquivos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
