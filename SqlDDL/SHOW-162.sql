
/* *************************** cad_docs_pendentes *****************************/
ALTER TABLE `showtecsystem`.`cad_docs_pendentes` 
CHANGE COLUMN `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ;

ALTER TABLE `showtecsystem`.`cad_docs_pendentes` 
CHANGE COLUMN `id_funcionario` `id_usuario` INT(11) UNSIGNED NULL DEFAULT NULL ,
ADD INDEX `idx_fk__cad_docs_pendentes__id_usuario__usuario` (`id_usuario` ASC);
;
ALTER TABLE `showtecsystem`.`cad_docs_pendentes` 
ADD CONSTRAINT `fk__cad_docs_pendentes__id_usuario__usuario`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_docs_pendentes` 
CHANGE COLUMN `status` `status_documentos` ENUM('pendente', 'documentos enviados') NULL DEFAULT 'pendente' COMMENT '1 = pendente 0 = entregue' ;

ALTER TABLE `showtecsystem`.`cad_docs_pendentes` 
ADD COLUMN `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo' AFTER `data_entrega`;

ALTER TABLE `showtecsystem`.`cad_docs_pendentes` 
CHANGE COLUMN `residencia` `residencia` ENUM('sim', 'nao') NULL DEFAULT 'nao' ,
CHANGE COLUMN `cpf` `cpf` ENUM('sim', 'nao') NULL DEFAULT 'nao' ,
CHANGE COLUMN `rg` `rg` ENUM('sim', 'nao') NULL DEFAULT 'nao' ,
CHANGE COLUMN `banco` `banco` ENUM('sim', 'nao') NULL DEFAULT 'nao' ,
CHANGE COLUMN `recebido` `recebido` ENUM('sim', 'nao') NULL DEFAULT 'nao' ;
/* *************************** ****************** *****************************/


/* *************************** colaborador *****************************/
ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `id_estado_civil` `id_estado_civil` INT(11) UNSIGNED NOT NULL ;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `rg_uf` `rg_id_estado` INT(11) UNSIGNED NULL DEFAULT NULL ;
;
UPDATE cad_colaborador SET rg_id_estado = NULL WHERE rg_id_estado = 0 OR rg_id_estado > 27;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__rg_id_estado__estado`
  FOREIGN KEY (`rg_id_estado`)
  REFERENCES `showtecsystem`.`estado` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD INDEX `idx_fk__cad_colaborador__rg_id_estado__estado` (`rg_id_estado` ASC);
;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
DROP COLUMN `orgao_emissor_cnh`,

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `cnh_uf` `cnh_id_estado` INT(11) UNSIGNED NULL DEFAULT NULL ;
UPDATE cad_colaborador SET cnh_id_estado = NULL WHERE cnh_id_estado = 0 OR cnh_id_estado > 27
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD INDEX `idx_fk__cad_colaborador__cnh_id_estado__estado` (`cnh_id_estado` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__cnh_id_estado__estado`
  FOREIGN KEY (`cnh_id_estado`)
  REFERENCES `showtecsystem`.`estado` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `ctpf_serie` `ctps_serie` varchar(5) DEFAULT NULL ;
;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `ctpf_uf` `ctps_id_estado` INT(11) UNSIGNED NULL DEFAULT NULL ;
UPDATE cad_colaborador SET ctps_id_estado = NULL WHERE ctps_id_estado = 0 OR ctps_id_estado > 27
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD INDEX `idx_fk__cad_colaborador__ctps_id_estado__estado` (`ctps_id_estado` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__ctps_id_estado__estado`
  FOREIGN KEY (`ctps_id_estado`)
  REFERENCES `showtecsystem`.`estado` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
DROP COLUMN `end_tipo`;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `tel_resid` `telefone_residencial` VARCHAR(11) NULL DEFAULT NULL,
CHANGE COLUMN `tel_emerg` `telefone_emergencia` VARCHAR(11) NULL DEFAULT NULL ;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `raca` `id_cor_pele` INT(11) UNSIGNED NULL DEFAULT NULL ;
UPDATE cad_colaborador SET id_cor_pele = NULL WHERE id_cor_pele = 0 OR id_cor_pele > 9
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD INDEX `idx_fk__cad_colaborador__id_cor_pele__cor_pele` (`id_cor_pele` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__id_cor_pele__cor_pele`
  FOREIGN KEY (`id_cor_pele`)
  REFERENCES `showtecsystem`.`cor_pele` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__id_escolaridade__escolaridade`
  FOREIGN KEY (`id_escolaridade`)
  REFERENCES `showtecsystem`.`escolaridade` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__id_estado_civil__estado_civil`
  FOREIGN KEY (`id_estado_civil`)
  REFERENCES `showtecsystem`.`estado_civil` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
/* *************************** ************ *****************************/
