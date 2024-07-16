-- CRIA TABELA
CREATE TABLE `showtecsystem`.`ocorrencia_x_user_sac` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `incidentid` VARCHAR(36) NOT NULL,
  `id_usuario_cadastro` INT(10) UNSIGNED NOT NULL COMMENT 'Id do usuário do shownet que fez o cadastro da ocorrência no SAC',
  `id_usuario_update` INT(10) UNSIGNED NULL COMMENT 'Id do usuário do shownet que fez o update do SAC do shownet',
  `clause` ENUM('insert', 'update') NOT NULL,
  `data_cadastro` DATETIME NULL,
  `data_update` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `index_fk_id_usuario_cadastro` (`id_usuario_cadastro` ASC),
  INDEX `index_fk_id_usuario_update` (`id_usuario_update` ASC),
  CONSTRAINT `id_usuario_cadastro`
    FOREIGN KEY (`id_usuario_cadastro`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `id_usuario_update`
    FOREIGN KEY (`id_usuario_update`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- Seta campo id_usuario_cadastro como nullable
ALTER TABLE `showtecsystem`.`ocorrencia_x_user_sac` 
DROP FOREIGN KEY `id_usuario_cadastro`;
ALTER TABLE `showtecsystem`.`ocorrencia_x_user_sac` 
CHANGE COLUMN `id_usuario_cadastro` `id_usuario_cadastro` INT(10) UNSIGNED NULL COMMENT 'Id do usuário do shownet que fez o cadastro da ocorrência no SAC' ;
ALTER TABLE `showtecsystem`.`ocorrencia_x_user_sac` 
ADD CONSTRAINT `id_usuario_cadastro`
  FOREIGN KEY (`id_usuario_cadastro`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
-- Correção nome index 

ALTER TABLE `showtecsystem`.`ocorrencia_x_user_sac` 
DROP FOREIGN KEY `id_usuario_cadastro`,
DROP FOREIGN KEY `id_usuario_update`;
ALTER TABLE `showtecsystem`.`ocorrencia_x_user_sac` 
DROP INDEX `index_fk_id_usuario_cadastro` ,
ADD INDEX `idx_fk__usuario__ocorrencia_x_user_sac__id_usuario_cadastro` (`id_usuario_cadastro` ASC),
DROP INDEX `index_fk_id_usuario_update` ,
ADD INDEX `idx_fk__usuario__ocorrencia_x_user_sac__id_usuario_update` (`id_usuario_update` ASC);
;
ALTER TABLE `showtecsystem`.`ocorrencia_x_user_sac` 
ADD CONSTRAINT `fk__usuario__ocorrencia_x_user_sac__id_usuario_cadastro`
  FOREIGN KEY (`id_usuario_cadastro`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk__usuario__ocorrencia_x_user_sac__id_usuario_update`
  FOREIGN KEY (`id_usuario_update`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

