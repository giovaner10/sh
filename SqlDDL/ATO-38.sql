CREATE TABLE `atendimento_omnilink`.`contatos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cliente` INT UNSIGNED NULL,
	`id_usuario` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NULL DEFAULT NULL,
  `empresa` VARCHAR(50) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `datahora_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datahora_modificacao` DATETIME NULL DEFAULT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  INDEX `idx_nome__status` (`nome` ASC, `status` ASC),
  INDEX `idx_fk__contatos__cliente` (`id_cliente` ASC),
  INDEX `idx_fk__contatos__usuario` (`id_usuario` ASC),
  CONSTRAINT `fk__contatos__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Editar Contatos - Atendimento Omnilink', 'edi_contatos_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Remover Contatos - Atendimento Omnilink', 'rem_contatos_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Visualizar Contatos - Atendimento Omnilink', 'vis_contatos_atendimento_omnilink', '1', 'Atendimento Omnilink');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Cadastrar Contatos - Atendimento Omnilink', 'cad_contatos_atendimento_omnilink', '1', 'Atendimento Omnilink');
