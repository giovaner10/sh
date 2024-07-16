CREATE TABLE `showtecsystem`.`contatos_clientes_crm` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `documento_cliente` VARCHAR(14) NOT NULL,
  `nome` VARCHAR(80) NOT NULL,
  `funcao` VARCHAR(80) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER `funcao`,
  PRIMARY KEY (`id`),
  INDEX `idx__documento_cliente` (`documento_cliente` ASC))
COMMENT = 'Tabela contendo os contatos adicionais dos clientes Omnilink';


CREATE TABLE `showtecsystem`.`telefones_contatos_clientes_crm` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_contatos_clientes_crm` INT UNSIGNED NOT NULL,
  `telefone` VARCHAR(11) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER `funcao`,
  PRIMARY KEY (`id`),
  INDEX `fk__telefones_contatos_clientes_crm__contatos_clientes_crm_idx` (`id_contatos_clientes_crm` ASC),
  CONSTRAINT `fk__telefones_contatos_clientes_crm__contatos_clientes_crm`
    FOREIGN KEY (`id_contatos_clientes_crm`)
    REFERENCES `showtecsystem`.`contatos_clientes_crm` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
COMMENT = 'Tabela contendo os telefones dos contatos adicionais dos clientes Omnilink';


CREATE TABLE `showtecsystem`.`emails_contatos_clientes_crm` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_contatos_clientes_crm` INT UNSIGNED NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER `funcao`,
  PRIMARY KEY (`id`),
  INDEX `fk__emails_contatos_clientes_crm__contatos_clientes_crm_idx` (`id_contatos_clientes_crm` ASC),
  CONSTRAINT `fk__emails_contatos_clientes_crm__contatos_clientes_crm`
    FOREIGN KEY (`id_contatos_clientes_crm`)
    REFERENCES `showtecsystem`.`contatos_clientes_crm` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
COMMENT = 'Tabela contendo os emails dos contatos adicionais dos clientes Omnilink';

