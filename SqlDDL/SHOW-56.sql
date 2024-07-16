/* *************************** departamentos *****************************/
CREATE TABLE `showtecsystem`.`departamentos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `status` ENUM('inativo', 'ativo') NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Gente & Gestão', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Financeiro', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('TI', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Engenharia', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Comercial e Televendas', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Marketing', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Operações', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Controle de Qualidade', 'ativo');
INSERT INTO `showtecsystem`.`departamentos` (`nome`, `status`) VALUES ('Governança Corporativa', 'ativo');
/* *************************** ************** *****************************/


/* *************************** arquivos *****************************/
ALTER TABLE `showtecsystem`.`arquivos` 
CHANGE COLUMN `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ;
;
ALTER TABLE `showtecsystem`.`arquivos` 
ADD COLUMN `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo' AFTER `link`;
/* *************************** arquivos *****************************/


/* *************************** cad_formularios_informacoes *****************************/
ALTER TABLE `showtecsystem`.`cad_formularios_informacoes` 
ADD COLUMN `id_departamentos` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `id`,
CHANGE COLUMN `id_arquivo` `id_arquivo` INT(11) NULL DEFAULT NULL AFTER `id_departamentos`,
CHANGE COLUMN `id_assunto` `id_assunto` INT(11) NULL DEFAULT NULL AFTER `ativo`;

ALTER TABLE `showtecsystem`.`cad_formularios_informacoes` 
CHANGE COLUMN `ativo` `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo' ;

/* FK cad_formularios_informacoes - departamentos */
ALTER TABLE `showtecsystem`.`cad_formularios_informacoes` 
ADD CONSTRAINT `fk__cad_formularios_informacoes__id_departamentos__departamentos`
  FOREIGN KEY (`id_departamentos`)
  REFERENCES `showtecsystem`.`departamentos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

/* index cad_formularios_informacoes - departamentos */
ALTER TABLE `showtecsystem`.`cad_formularios_informacoes` 
ADD INDEX `idx_fk__cad_formularios_informacoes__id_dep__departamentos` (`id_departamentos` ASC);
;

/* index cad_formularios_informacoes - arquivos */
ALTER TABLE `showtecsystem`.`cad_formularios_informacoes` 
CHANGE COLUMN `id_arquivo` `id_arquivos` INT(11) UNSIGNED NOT NULL ;
ALTER TABLE `showtecsystem`.`cad_formularios_informacoes` 
ADD INDEX `idx_fk__cad_formularios_informacoes__id_arquivos__arquivos` (`id_arquivos` ASC);
;

/* FK cad_formularios_informacoes - arquivos */
ALTER TABLE `showtecsystem`.`cad_formularios_informacoes` 
ADD CONSTRAINT `fk__cad_formularios_informacoes__id_arquivos__arquivos`
  FOREIGN KEY (`id_arquivos`)
  REFERENCES `showtecsystem`.`arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
/* *************************** ***************************** *****************************/


/* *************************** usuario *****************************/
/* Alter column and FK usuario - departamentos */
ALTER TABLE `showtecsystem`.`usuario` 
CHANGE COLUMN `departamento_id` `id_departamentos` INT(10) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `showtecsystem`.`usuario` 
ADD CONSTRAINT `fk__usuario__id_departamentos__departamentos`
  FOREIGN KEY (`id_departamentos`)
  REFERENCES `showtecsystem`.`departamentos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

/* Alter index FK usuario - departamentos */
ALTER TABLE `showtecsystem`.`usuario` 
ADD INDEX `idx_fk__usuario__id_departamentos__departamentos` (`id_departamentos` ASC);
;
/* *************************** ******* *****************************/


/* *************************** cad_atividades *****************************/
ALTER TABLE `showtecsystem`.`cad_atividades` 
ADD COLUMN `data_exclusao` DATETIME NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `showtecsystem`.`cad_atividades` 
CHANGE COLUMN `id_funcionario` `id_usuario` INT(11) UNSIGNED NOT NULL ;
ALTER TABLE `showtecsystem`.`cad_atividades` 
ADD INDEX `idx_fk__cad_atividades__id_usuario__usuario` (`id_usuario` ASC);
;

ALTER TABLE `showtecsystem`.`cad_atividades` 
ADD CONSTRAINT `fk__cad_atividades__id_usuario__usuario`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
/* *************************** ************** *****************************/

/* *************************** parcerias *****************************/
CREATE TABLE `showtecsystem`.`parcerias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_categoria` INT NOT NULL,
  `id_arquivo` INT NOT NULL,
  `descricao` VARCHAR(100) NOT NULL,
  `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

/* Parcerias - Categorias */
ALTER TABLE `showtecsystem`.`parcerias` 
CHANGE COLUMN `id_categoria` `id_parcerias_categorias` INT(11) UNSIGNED NOT NULL ,
ADD INDEX `idx_fk__parcerias__id_parcerias_categorias__parcerias_categorias` (`id_parcerias_categorias` ASC);
;
ALTER TABLE `showtecsystem`.`parcerias` 
ADD CONSTRAINT `fk__parcerias__id_parcerias_categorias__parcerias_categorias`
  FOREIGN KEY (`id_parcerias_categorias`)
  REFERENCES `showtecsystem`.`parcerias_categorias` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

/* Parcerias - Arquivos */
ALTER TABLE `showtecsystem`.`parcerias` 
CHANGE COLUMN `id_arquivo` `id_arquivos` INT(11) UNSIGNED NOT NULL ,
ADD INDEX `idx_fk__parcerias__id_arquivos__arquivos` (`id_arquivos` ASC);
;
ALTER TABLE `showtecsystem`.`parcerias` 
ADD CONSTRAINT `fk__parcerias__id_arquivos__arquivos`
  FOREIGN KEY (`id_arquivos`)
  REFERENCES `showtecsystem`.`arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
/* *************************** ************** *****************************/


/* *************************** parcerias_categorias *****************************/
CREATE TABLE `showtecsystem`.`parcerias_categorias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `status` ENUM('ativo', 'inativo') NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

ALTER TABLE `showtecsystem`.`parcerias_categorias` 
ADD COLUMN `ordem` INT UNSIGNED NOT NULL AFTER `status`,
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL;

INSERT INTO `showtecsystem`.`parcerias_categorias` (`nome`, `status`) VALUES ('Restaurantes', 'ativo');
INSERT INTO `showtecsystem`.`parcerias_categorias` (`nome`, `status`) VALUES ('Instituições de Ensino', 'ativo');
/* *************************** ********************* *****************************/


/* *************************** colaborador *****************************/
ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE COLUMN `dtNasc` `data_nascimento` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `end_num` `endereco_numero` VARCHAR(7) NULL DEFAULT NULL ,
CHANGE COLUMN `tel_cel` `celular` CHAR(11) NULL DEFAULT NULL ,
CHANGE COLUMN `emerg_contato` `emergencia_contato` VARCHAR(50) NULL DEFAULT NULL ;
CHANGE COLUMN `tel_resid_desuso_20210721` `telefone_residencial` VARCHAR(11) NULL DEFAULT NULL ,
CHANGE COLUMN `tel_emerg_desuso_20210721` `telefone_emergencia` VARCHAR(11) NULL DEFAULT NULL ;
CHANGE COLUMN `end_compl` `endereco_complemento` VARCHAR(15) NULL DEFAULT NULL ;
CHANGE COLUMN `rg_exp` `rg_expedicao` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `pis_exp` `pis_expedicao` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `cnh_exp` `cnh_expedicao` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `cnh_prim` `cnh_primeiro` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `email_partic` `email_particular` VARCHAR(150) NULL DEFAULT NULL ;
CHANGE COLUMN `cnh_org` `cnh_orgao` VARCHAR(2) NULL DEFAULT NULL ;
CHANGE COLUMN `email_corp` `email_corporativo` VARCHAR(150) NULL DEFAULT NULL ;
CHANGE COLUMN `cnh_val` `cnh_validade` DATE NULL DEFAULT NULL ;
CHANGE COLUMN `ctps_exp` `ctps_expedicao` DATE NULL DEFAULT NULL ;
CHANGE COLUMN `estado` `estado` CHAR(2) NULL DEFAULT NULL ;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
DROP COLUMN `celular_emergencia`,

ALTER TABLE `showtecsystem`.`cad_colaborador` 
DROP COLUMN `orgao_emissor_cnh_desuso_20210721`,

ALTER TABLE `showtecsystem`.`cad_colaborador` 
DROP COLUMN `id_endereco_tipo`;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `estado_civil` `id_estado_civil` INT(11) NOT NULL ;
UPDATE cad_colaborador SET id_estado_civil = NULL WHERE id_estado_civil = 0 OR id_estado_civil > 8;
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
DROP INDEX `estado_civil_id_estado_civil_idx` ,
ADD INDEX `idx_fk__cad_colaborador__id_estado_civil__estado_civil` (`id_estado_civil` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__id_estado_civil__estado_civil`
  FOREIGN KEY (`id_estado_civil`)
  REFERENCES `showtecsystem`.`estado_civil` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `escolaridade` `id_escolaridade` INT(11) UNSIGNED NULL DEFAULT NULL ;
UPDATE cad_colaborador SET id_escolaridade = NULL WHERE id_escolaridade = 0 OR id_escolaridade > 14;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD INDEX `idx_fk__cad_colaborador__id_escolaridade__escolaridade` (`id_escolaridade` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador` 
ADD CONSTRAINT `fk__cad_colaborador__id_escolaridade__escolaridade`
  FOREIGN KEY (`id_escolaridade`)
  REFERENCES `showtecsystem`.`escolaridade` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador` 
CHANGE COLUMN `rg_uf_id` `rg_id_estado` INT(11) UNSIGNED NULL DEFAULT NULL ;
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

/* *************************** ************ *****************************/


/* *************************** estado_civil *****************************/
CREATE TABLE `showtecsystem`.`estado_civil` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('Casado');
INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('Divorciado');
INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('Marital');
INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('Solteiro');
INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('Separado Judicialmente');
INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('União Estável');
INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('Viúvo');
INSERT INTO `showtecsystem`.`estado_civil` (`descricao`) VALUES ('Outros');
/* *************************** ************* *****************************/


/* *************************** escolaridade *****************************/
CREATE TABLE `showtecsystem`.`escolaridade` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Ensino Fundamental - 4ª Série Incompleto');
INSERT INTO `showtecsystem`.`escolaridade` (`id`, `descricao`) VALUES ('', 'Ensino Fundamental - 4ª Série Completo');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Ensino Fundamental - 5ª a 8ª Série');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Ensino Fundamental Completo');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Ensino Médio Incompleto');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Ensino Médio Completo');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Técnico Incompleto');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Técnico Completo');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Educação Superior Incompleto');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Educação Superior');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Pós Graduação');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Mestrado');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('Doutorado');
INSERT INTO `showtecsystem`.`escolaridade` (`descricao`) VALUES ('PHD');
/* *************************** ************ *****************************/


/* *************************** estado *****************************/
CREATE TABLE `showtecsystem`.`estado` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sigla` CHAR(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('AC');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('AL');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('AP');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('AM');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('BA');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('CE');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('DF');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('ES');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('GO');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('MA');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('MT');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('MS');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('MG');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('PA');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('PB');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('PR');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('PE');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('PI');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('RJ');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('RN');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('RS');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('RO');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('RR');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('SC');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('SP');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('SE');
INSERT INTO `showtecsystem`.`estado` (`sigla`) VALUES ('TO');
/* *************************** ****** *****************************/


/* *************************** cor_pele *****************************/
CREATE TABLE `showtecsystem`.`cor_pele` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Albino');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Amarelo');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Branco');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Indígena');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Mulato');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Negro');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Pardo');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Vermelho');
INSERT INTO `showtecsystem`.`cor_pele` (`descricao`) VALUES ('Outra');
/* *************************** *********** *****************************/


/* *************************** parentesco *****************************/
CREATE TABLE `showtecsystem`.`parentesco` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));

INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Companheiro(a)');
INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Cônjuge');
INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Enteado(a)');
INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Filho(a)');
INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Guarda Judicial');
INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Tutela/Curatela');
INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Ex Cônjuge - Pensão Alimentícia');
INSERT INTO `showtecsystem`.`parentesco` (`descricao`) VALUES ('Outros');
/* *************************** ********* *****************************/


/* *************************** cad_colaborador_dependentes *****************************/
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
CHANGE COLUMN `dep_nome` `nome` VARCHAR(200) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_cpf` `cpf` VARCHAR(20) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_sexo` `sexo` VARCHAR(20) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_dtNasc` `data_nascimento` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `dep_mae` `mae` VARCHAR(200) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_dtCasam` `data_casamento` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `dep_naturalidade` `naturalidade` VARCHAR(50) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_cns` `cns` VARCHAR(30) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_ppd` `ppd` TINYINT(1) NULL DEFAULT '0' ,
CHANGE COLUMN `dep_cartorio_desuso_20210721` `cartorio` VARCHAR(100) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_registro_desuso_20210721` `registro` VARCHAR(100) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_declar_vivo` `declar_vivo` VARCHAR(100) NULL DEFAULT NULL ,
CHANGE COLUMN `dep_irrf` `irrf` TINYINT(1) NULL DEFAULT '0' ;

ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
CHANGE COLUMN `dep_estado_civil` `id_estado_civil` INT(11) UNSIGNED NULL DEFAULT NULL ;
UPDATE cad_colaborador_dependentes SET id_estado_civil = NULL WHERE id_estado_civil = 0 OR id_estado_civil > 8;
;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD INDEX `idx_fk__cad_colaborador_dependentes__id_est_ci__estado_civil` (`id_estado_civil` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD CONSTRAINT `fk__cad_colaborador_dependentes__id_estado_civil__estado_civil`
  FOREIGN KEY (`id_estado_civil`)
  REFERENCES `showtecsystem`.`estado_civil` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
CHANGE COLUMN `dep_parentesco` `id_parentesco` INT(11) UNSIGNED NULL DEFAULT NULL ;
UPDATE cad_colaborador_dependentes SET id_parentesco = NULL WHERE id_parentesco = 0 OR id_parentesco > 8;
;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD INDEX `idx_fk__cad_colaborador_dependentes__id_parentesco__parentesco` (`id_parentesco` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD CONSTRAINT `fk__cad_colaborador_dependentes__id_parentesco__parentesco`
  FOREIGN KEY (`id_parentesco`)
  REFERENCES `showtecsystem`.`parentesco` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
CHANGE COLUMN `dep_raca` `id_cor_pele` INT(11) UNSIGNED NULL DEFAULT NULL ;
UPDATE cad_colaborador_dependentes SET id_cor_pele = NULL WHERE id_cor_pele = 0 OR id_cor_pele > 9
;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD INDEX `idx_fk__cad_colaborador_dependentes__id_cor_pele__cor_pele` (`id_cor_pele` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD CONSTRAINT `fk__cad_colaborador_dependentes__id_cor_pele__cor_pele`
  FOREIGN KEY (`id_cor_pele`)
  REFERENCES `showtecsystem`.`cor_pele` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD COLUMN `id_cad_colaborador` INT(11) UNSIGNED NULL AFTER `id`;
;
UPDATE cad_colaborador_dependentes SET id_cad_colaborador = NULL WHERE id_cad_colaborador = 0;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD INDEX `idx_fk__cad_colaborador_dependentes__id_colab__cad_colaborador` (`id_cad_colaborador` ASC);
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD CONSTRAINT `fk__cad_colaborador_dependentes__id_colab__cad_colaborador`
  FOREIGN KEY (`id_cad_colaborador`)
  REFERENCES `showtecsystem`.`cad_colaborador` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
CHANGE COLUMN `id_funcionario` `id_usuario` INT(11) UNSIGNED NULL DEFAULT NULL ,
ADD INDEX `idx_fk__cad_colaborador_dependentes__id_usuario__usuario` (`id_usuario` ASC);
;
ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD CONSTRAINT `fk__cad_colaborador_dependentes__id_usuario__usuario`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_colaborador_dependentes` 
ADD COLUMN `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo' AFTER `irrf`;
/* *************************** **************************** *****************************/

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


/* *************************** cad_docs_pendente_arquivos *****************************/
ALTER TABLE `showtecsystem`.`cad_docs_pendente_arquivos` 
ADD COLUMN `id_cad_docs_pendentes` INT(11) UNSIGNED NOT NULL AFTER `id`;
;
UPDATE cad_docs_pendente_arquivos SET id_cad_docs_pendente = NULL WHERE id_cad_docs_pendente = 0;
;
ALTER TABLE `showtecsystem`.`cad_docs_pendente_arquivos` 
ADD INDEX `idx_fk__cad_docs_pendente_arquivos__id_docs__cad_docs_pendentes` (`id_cad_docs_pendentes` ASC);
;
ALTER TABLE `showtecsystem`.`cad_docs_pendente_arquivos` 
ADD CONSTRAINT `fk__cad_docs_pendente_arquivos__id_docs__cad_docs_pendentes`
  FOREIGN KEY (`id_cad_docs_pendentes`)
  REFERENCES `showtecsystem`.`cad_docs_pendentes` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_docs_pendente_arquivos` 
CHANGE COLUMN `id_funcionario` `id_usuario` INT(11) UNSIGNED NULL DEFAULT NULL ,
ADD INDEX `idx_fk__cad_docs_pendentes_arquivos__id_usuario__usuario` (`id_usuario` ASC);
;
ALTER TABLE `showtecsystem`.`cad_docs_pendente_arquivos` 
ADD CONSTRAINT `fk__cad_docs_pendentes_arquivos__id_usuario__usuario`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `showtecsystem`.`cad_docs_pendente_arquivos` 
ADD COLUMN `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo' AFTER `data_envio`;
/* *************************** **************************** *****************************/


/* *************************** cad_comunicados *****************************/
ALTER TABLE `showtecsystem`.`cad_comunicados` 
CHANGE COLUMN `ativo` `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo' ;
/* *************************** *************** *****************************/