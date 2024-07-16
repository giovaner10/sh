INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Visualizar Solicitações - Portal Compras', 'vis_solicitacoes_portal_compras', '1', 'Portal de Compras');

CREATE TABLE `portal_compras`.`solicitacoes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `situacao` ENUM('pendente', 'aprovado', 'reprovado') NOT NULL DEFAULT 'pendente',
  `centro_custo` JSON NOT NULL,
  `produtos` JSON NOT NULL,
  `valor_total` FLOAT(10,2) NOT NULL,
  `departamento` ENUM('show', 'norio', 'omnilink', 'ceabs') NOT NULL,
  `capex` ENUM('sim', 'nao') NOT NULL DEFAULT 'nao',
  `rateio` ENUM('sim', 'nao') NOT NULL DEFAULT 'nao',
  `anexo_rateio` VARCHAR(120) NULL DEFAULT NULL,
  `motivo_compra` VARCHAR(240) NOT NULL,
  `tipo_requisicao` ENUM('recorrente', 'nao_recorrente', 'contrato') NOT NULL DEFAULT 'nao_recorrente',
  `filial` VARCHAR(60) NULL DEFAULT NULL,
  `anexo_solicitacao` VARCHAR(120) NULL DEFAULT NULL,
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` DATETIME NULL DEFAULT NULL,
  `id_usuario` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__solicitacoes__usuario` (`id_usuario` ASC),
  INDEX `idx_fk__solicitacoes__fornecedores` (`id_fornecedores` ASC),
  UNIQUE INDEX `nome_status_UNIQUE` (`nome` ASC, `status` ASC),
  CONSTRAINT `fk__solicitacoes__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);