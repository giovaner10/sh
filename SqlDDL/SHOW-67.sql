ALTER TABLE `showtecsystem`.`profissionais` 
DROP FOREIGN KEY `fk__profissionais__enderecos`,
DROP FOREIGN KEY `fk__profissionais__telefones_comercial`;
ALTER TABLE `showtecsystem`.`profissionais` 
CHANGE COLUMN `id_enderecos` `id_enderecos` INT UNSIGNED NOT NULL ,
CHANGE COLUMN `id_telefones_comercial` `id_telefones_comercial` INT UNSIGNED NOT NULL ;
ALTER TABLE `showtecsystem`.`profissionais` 
ADD CONSTRAINT `fk__profissionais__enderecos`
  FOREIGN KEY (`id_enderecos`)
  REFERENCES `showtecsystem`.`enderecos` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk__profissionais__telefones_comercial`
  FOREIGN KEY (`id_telefones_comercial`)
  REFERENCES `showtecsystem`.`telefones` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;



CREATE TABLE `showtecsystem`.`comentarios_consulta_perfil_profissional` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(240) NOT NULL,
  `tipo_consulta` ENUM('cpf', 'antecedentes', 'mandados', 'cnh', 'debitos', 'veiculo') NOT NULL,
  `id_perfis_profissionais_consultas` INT UNSIGNED NOT NULL,
  `id_usuario` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__comentarios_consulta_perfil_profissional__perfis_consult` (`id_perfis_profissionais_consultas` ASC),
  INDEX `idx_fk__comentarios_consulta_perfil_profissional__usuario` (`id_usuario` ASC),
  UNIQUE INDEX `idx_tipo_consulta__id_perfis_profissionais_consultas` USING BTREE (`tipo_consulta`, `id_perfis_profissionais_consultas`),
  CONSTRAINT `fk__comentarios_consulta_perfil_profissional__perfis_consult`
    FOREIGN KEY (`id_perfis_profissionais_consultas`)
    REFERENCES `showtecsystem`.`perfis_profissionais_consultas` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__comentarios_consulta_perfil_profissional__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION 
);



ALTER TABLE `showtecsystem`.`perfis_profissionais` 
ADD COLUMN `descricao_analise` VARCHAR(240) NULL DEFAULT NULL AFTER `status_analise`;

ALTER TABLE `showtecsystem`.`profissionais` 
CHANGE COLUMN `tipo_profissional` `tipo_profissional` ENUM('funcionario', 'agregado', 'autonomo') NOT NULL DEFAULT 'funcionario' ;


ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
ADD COLUMN `id_usuario` INT UNSIGNED NULL AFTER `id_perfis_profissionais_consultas`,
CHANGE COLUMN `acao` `acao` ENUM('consulta', 'cadastro', 'edicao', 'analise') NOT NULL DEFAULT 'cadastro' ,
ADD INDEX `idx_fk__log_perfis_profissionais__usuario` (`id_usuario` ASC);
;
ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
ADD CONSTRAINT `fk__log_perfis_profissionais__usuario`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
DROP FOREIGN KEY `fk__log_perfis_profissionais__cad_clientes`,
DROP FOREIGN KEY `fk__log_perfis_profissionais__usuario_gestor`;
ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
CHANGE COLUMN `id_usuario_gestor` `id_usuario_gestor` INT(10) NULL DEFAULT NULL ,
CHANGE COLUMN `id_cad_clientes` `id_cad_clientes` INT(10) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
ADD CONSTRAINT `fk__log_perfis_profissionais__cad_clientes`
  FOREIGN KEY (`id_cad_clientes`)
  REFERENCES `showtecsystem`.`cad_clientes` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk__log_perfis_profissionais__usuario_gestor`
  FOREIGN KEY (`id_usuario_gestor`)
  REFERENCES `showtecsystem`.`usuario_gestor` (`code`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
DROP FOREIGN KEY `fk__log_perfis_profissionais__perfis_profissionais_consultas`;
ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
ADD INDEX `idx_fk__log_perfis_profissionais__perfis_profissionais_consultas` (`id_perfis_profissionais_consultas` ASC),
DROP INDEX `idx_fk__log_perfis_profissionais__perfis_profissionais_consulta` ;
;
ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
ADD CONSTRAINT `fk__log_perfis_profissionais__perfis_profissionais_consultas`
  FOREIGN KEY (`id_perfis_profissionais_consultas`)
  REFERENCES `showtecsystem`.`perfis_profissionais_consultas` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


ALTER TABLE `showtecsystem`.`log_perfis_profissionais` 
ADD COLUMN `resultado_analise` ENUM('indicado', 'nao_indicado') NULL DEFAULT NULL AFTER `data_cadastro`,
ADD COLUMN `comentario_analise` VARCHAR(240) NULL DEFAULT NULL AFTER `resultado_analise`;
