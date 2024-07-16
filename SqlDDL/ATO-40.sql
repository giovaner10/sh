INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Acesso - Atendimento Omnilink', 'atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Visualizar Filas - Atendimento Omnilink', 'vis_filas_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Cadastrar Filas - Atendimento Omnilink', 'cad_filas_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Editar Filas - Atendimento Omnilink', 'edi_filas_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Remover Filas - Atendimento Omnilink', 'rem_filas_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Vincular Agentes à Fila - Atendimento Omnilink', 'cad_vincular_agentes_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `icone`, `id_pai`, `ordem`, `status`, `lang_pt`, `lang_en`) 
VALUES ('atendimento_omnilink', 'AtendimentoOmnilink/Filas', 'atendimento_omnilink', 'sim', 'atendimento_omnilink', '118', '999', 'ativo', 'atendimento_omnilink', 'atendimento_omnilink');


CREATE TABLE `atendimento_omnilink`.`filas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) NOT NULL,
  `descricao` VARCHAR(240) NULL DEFAULT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `usuarios` JSON NULL DEFAULT NULL COMMENT 'Sao os agentes que irao atender as ligacoes',
  `numeros` JSON NULL DEFAULT NULL COMMENT 'Sao os numeros designados para receber as chamadas',
  `datahora_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datahora_modificacao` DATETIME NULL DEFAULT NULL,
  `id_usuario` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_nome__status` (`nome` ASC, `status` ASC),
  INDEX `idx_fk__filas__usuario` (`id_usuario` ASC),
  CONSTRAINT `fk__filas__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `atendimento_omnilink`.`filas` 
ADD COLUMN `dia_inicial` VARCHAR(45) NOT NULL AFTER `descricao`,
ADD COLUMN `dia_final` VARCHAR(45) NOT NULL AFTER `dia_inicial`,
ADD COLUMN `horario_inicial` VARCHAR(45) NOT NULL AFTER `dia_final`,
ADD COLUMN `horario_final` VARCHAR(45) NOT NULL AFTER `horario_inicial`;
