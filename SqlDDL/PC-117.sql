CREATE TABLE `portal_compras`.`comentarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `origem` ENUM('solicitante','aprovador','area_compras','area_financeira','area_fiscal') NOT NULL DEFAULT 'solicitante',
  `mensagem` VARCHAR(240) NOT NULL,
  `path_anexo` VARCHAR(120) NULL DEFAULT NULL,
  `datahora_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_solicitacoes` INT UNSIGNED NOT NULL,
  `id_usuario` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__comentarios__solicitacoes` (`id_solicitacoes` ASC),
  INDEX `idx_fk__comentarios__usuario` (`id_usuario` ASC),
  CONSTRAINT `fk__comentarios__solicitacoes`
    FOREIGN KEY (`id_solicitacoes`)
    REFERENCES `portal_compras`.`solicitacoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__comentarios__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);