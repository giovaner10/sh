INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Acessar Discador - Atendimento Omnilink', 'vis_discador_atendimento_omnilink', '1', 'Atendimento Omnilink');

CREATE SCHEMA atendimento_omnilink;

CREATE TABLE `atendimento_omnilink`.`historico_chamadas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid` VARCHAR(100) NOT NULL,
  `efetuada_por` VARCHAR(60) NOT NULL,
  `recebida_por` VARCHAR(15) NOT NULL,
  `status` ENUM('completada', 'com_falha', 'sem_resposta', 'ocupado', 'recusada') NOT NULL DEFAULT 'completada',
  `caminho_arquivo` VARCHAR(240) NULL DEFAULT NULL,
  `datahora_inicio` DATETIME NOT NULL,
  `datahora_fim` DATETIME NOT NULL,
  `duracao` INT(20) NOT NULL COMMENT 'duracao eh a difirenca da datahora_fim e datahora_inicio, em segundos',
  `datahora_processamento` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `protocolo` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Protocolo identificador da chamada',
  `id_usuario` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_sid` (`sid`),
  INDEX `idx_fk__historico_chamadas__usuario` (`id_usuario` ASC),
  CONSTRAINT `fk__historico_chamadas__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
COMMENT = 'Registra as ligacoes realizadas para cliente atraves do shownet';
;