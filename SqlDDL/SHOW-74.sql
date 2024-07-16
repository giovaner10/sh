CREATE TABLE `showtecsystem`.`auditoria_iscas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `serial` VARCHAR(45) NOT NULL,
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operacao` ENUM('cadastro', 'migração') NOT NULL,
  `id_cliente_antigo` INT UNSIGNED NULL,
  `id_cliente_atual` INT UNSIGNED NOT NULL,
  `id_contrato_antigo` INT UNSIGNED NULL,
  `id_contrato_atual` INT UNSIGNED NOT NULL,
  `id_usuario` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `idx_fk__cad_clientes__auditoria_iscas__cliente_antigo` (`id_cliente_antigo` ASC),
  INDEX `idx_fk__cad_clientes__auditoria_iscas__cliente_atual` (`id_cliente_atual` ASC),
  INDEX `idx_fk__contratos__auditoria_iscas__contrato_antigo` (`id_contrato_antigo` ASC),
  INDEX `idx_fk__contratos__auditoria_iscas__contrato_atual` (`id_contrato_atual` ASC),
  INDEX `idx_fk__usuario__auditoria_iscas` (`id_usuario` ASC),
  CONSTRAINT `fk__cad_clientes__auditoria_iscas__cliente_antigo`
    FOREIGN KEY (`id_cliente_antigo`)
    REFERENCES `showtecsystem`.`cad_clientes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__cad_clientes__auditoria_iscas__cliente_atual`
    FOREIGN KEY (`id_cliente_atual`)
    REFERENCES `showtecsystem`.`cad_clientes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__contratos__auditoria_iscas__contrato_antigo`
    FOREIGN KEY (`id_contrato_antigo`)
    REFERENCES `showtecsystem`.`contratos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__contratos__auditoria_iscas__contrato_atual`
    FOREIGN KEY (`id_contrato_atual`)
    REFERENCES `showtecsystem`.`contratos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__usuario__auditoria_iscas`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
