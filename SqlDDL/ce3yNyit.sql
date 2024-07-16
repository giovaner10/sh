CREATE TABLE `showtecsystem`.`monitoramento_dispositivo_isca_x_usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_isca` INT(11) UNSIGNED NOT NULL,
  `id_usuario` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_isca_idx` (`id_isca` ASC),
  INDEX `id_usuario_idx` (`id_usuario` ASC),
  CONSTRAINT `id_isca`
    FOREIGN KEY (`id_isca`)
    REFERENCES `showtecsystem`.`cad_iscas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `id_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
