-- CRIA TABELA DE AUDITORIA

CREATE TABLE `showtecsystem`.`auditoria_sac` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario_shownet` INT(10) UNSIGNED NOT NULL,
  `query` VARCHAR(255) NULL,
  `url_api` VARCHAR(255) NULL,
  `clause` ENUM('insert', 'update', 'delete') NULL,
  `data_cadastro` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `index_fk__usuario__id__id_usuario_shownet` (`id_usuario_shownet` ASC),
  CONSTRAINT `id_usuario_shownet`
    FOREIGN KEY (`id_usuario_shownet`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- CRIA TABELA DE CAMPOS
CREATE TABLE `showtecsystem`.`auditoria_campos_sac` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_auditoria` INT UNSIGNED NOT NULL,
  `campo` VARCHAR(45) NULL,
  `valor_antigo` VARCHAR(255) NULL,
  `valor_novo` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `index_fk__auditoria_sac__id__id_auditoria` (`id_auditoria` ASC),
  CONSTRAINT `id_auditoria`
    FOREIGN KEY (`id_auditoria`)
    REFERENCES `showtecsystem`.`auditoria_sac` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
