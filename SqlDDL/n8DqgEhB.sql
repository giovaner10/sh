CREATE TABLE `showtecsystem`.`cad_permissoes_funcionarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `descricao` VARCHAR(45) NOT NULL,
    `cod_permissao` VARCHAR(45) NOT NULL,
    `status` ENUM('0', '1') NOT NULL DEFAULT '1',
    `data_cad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `cod_permissao_UNIQUE` (`cod_permissao` ASC));

CREATE TABLE `showtecsystem`.`cad_cargo` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `descricao` VARCHAR(45) NOT NULL,
    `status` ENUM('0', '1') NOT NULL DEFAULT '1',
    `data_cad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `descricao_UNIQUE` (`descricao` ASC));


ALTER TABLE `showtecsystem`.`usuario`
    ADD COLUMN `cargo` INT(10) NULL AFTER `data_retorno_ferias`;
    ADD COLUMN `departamento` VARCHAR(100) NULL DEFAULT NULL AFTER `cargo`,
    ADD COLUMN `chefia_imediata` VARCHAR(100) NULL DEFAULT NULL AFTER `departamento`,
    ADD COLUMN `diretoria` VARCHAR(45) NULL DEFAULT NULL AFTER `chefia_imediata`,
    ADD COLUMN `unidade` VARCHAR(45) NULL DEFAULT NULL AFTER `diretoria`;


ALTER TABLE `showtecsystem`.`usuario`
    ADD UNIQUE INDEX `login_UNIQUE` (`login` ASC);


CREATE TABLE `showtecsystem`.`cad_modulos_funcionarios` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(120) NOT NULL,
    `status` ENUM('0', '1') NOT NULL DEFAULT '1',
    `data_cad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`));


CREATE TABLE `showtecsystem`.`cad_modulo_permissao_funcionario` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_modulo` INT(11) UNSIGNED NOT NULL,
    `id_permissao` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`));


CREATE TABLE `showtecsystem`.`cad_cargo_modulo_funcionario` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_modulo` INT(11) UNSIGNED NOT NULL,
    `id_cargo` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`));
