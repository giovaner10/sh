CREATE TABLE `showtecsystem`.`quotes_televendas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) NOT NULL,
  `id_cotacao` varchar(60) DEFAULT NULL,
  `data` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__quotes__id_usuario` (`id_usuario`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `showtecsystem`.`quotes_televendas` ADD CONSTRAINT `fk__quotes__id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `showtecsystem`.`usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION