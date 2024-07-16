INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Visualizar Fornecedores - Portal Compras', 'vis_fornecedores_portal_compras', '1', 'Portal de Compras');


CREATE TABLE `portal_compras`.`fornecedores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cnpj` CHAR(14) NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `nome_fantasia` VARCHAR(120) NOT NULL,
  `razao_social` VARCHAR(120) NOT NULL,
  `inscricao_estadual` VARCHAR(14) NOT NULL,
  `inscricao_municipal` VARCHAR(11) NULL DEFAULT NULL,
  `representante` VARCHAR(60) NULL DEFAULT NULL,
  `informacoes_adicionais` VARCHAR(240) NULL DEFAULT NULL,
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` DATETIME NULL DEFAULT NULL,
  `id_enderecos` INT UNSIGNED NOT NULL,
  `id_telefones` INT UNSIGNED NOT NULL,
  `id_emails` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__fornecedores__telefones` (`id_telefones` ASC),
  INDEX `idx_fk__fornecedores__emails` (`id_emails` ASC),
  INDEX `idx_fk__fornecedores__enderecos` (`id_enderecos` ASC),
  UNIQUE INDEX `razao_social_status_UNIQUE` (`razao_social` ASC, `status` ASC),
  CONSTRAINT `fk__fornecedores__telefones`
    FOREIGN KEY (`id_telefones`)
    REFERENCES `showtecsystem`.`telefones` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__fornecedores__emails`
    FOREIGN KEY (`id_emails`)
    REFERENCES `showtecsystem`.`emails` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__fornecedores__enderecos`
    FOREIGN KEY (`id_enderecos`)
    REFERENCES `showtecsystem`.`enderecos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);