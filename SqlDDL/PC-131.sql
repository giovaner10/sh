ALTER TABLE `portal_compras`.`solicitacoes` 
ADD COLUMN `id_usuario_compras` INT UNSIGNED NULL DEFAULT NULL,
ADD INDEX `idx_fk__solicitacoes__usuario_compras` (`id_usuario_compras` ASC),
ADD CONSTRAINT `fk__solicitacoes__usuario_compras`
  FOREIGN KEY (`id_usuario_compras`)
  REFERENCES `showtecsystem`.`usuario` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
