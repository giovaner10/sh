CREATE TABLE `showtecsystem`.`proposta_comercial` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NOT NULL,
  `id_arquivos` INT(10) UNSIGNED NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  INDEX `idx_fk__proposta_comercial__id_arquivos__arquivo` (`id_arquivos` ASC),
  CONSTRAINT `fk__proposta_comercial__id_arquivos__arquivo`
    FOREIGN KEY (`id_arquivos`)
    REFERENCES `showtecsystem`.`arquivos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
