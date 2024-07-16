ALTER TABLE `showtecsystem`.`cad_permissoes` 
ADD COLUMN `id_modulo` INT(11) NOT NULL AFTER `data_cad`,
CHANGE COLUMN `categoria` `categoria` VARCHAR(45) NOT NULL;

ALTER TABLE `showtecsystem`.`cad_modulos` 
ADD COLUMN `data_cad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`;

INSERT INTO showtecsystem.cad_modulos (`nome`) VALUES ('ATENDIMENTO');
INSERT INTO showtecsystem.cad_modulos (`nome`) VALUES ('CADASTRO');
INSERT INTO showtecsystem.cad_modulos (`nome`) VALUES ('CONFIGURACAO');
INSERT INTO showtecsystem.cad_modulos (`nome`) VALUES ('RELATORIO');
INSERT INTO showtecsystem.cad_modulos (`nome`) VALUES ('COMANDO');
INSERT INTO showtecsystem.cad_modulos (`nome`) VALUES ('NOTIFICACAO');

UPDATE showtecsystem.cad_permissoes SET id_modulo = 1 WHERE categoria = 'ATENDIMENTO';
UPDATE showtecsystem.cad_permissoes SET id_modulo = 2 WHERE categoria = 'CADASTRO';
UPDATE showtecsystem.cad_permissoes SET id_modulo = 3 WHERE categoria = 'CONFIGURACAO';
UPDATE showtecsystem.cad_permissoes SET id_modulo = 4 WHERE categoria = 'RELATORIO';
UPDATE showtecsystem.cad_permissoes SET id_modulo = 5, categoria = 'COMANDO' WHERE descricao like '%comando->%';
UPDATE showtecsystem.cad_permissoes SET id_modulo = 6 WHERE categoria = 'NOTIFICACAO';

ALTER TABLE `showtecsystem`.`cad_planos` 
DROP COLUMN `permissoes`,
CHANGE COLUMN `observacoes` `descricao` VARCHAR(100) NULL DEFAULT NULL,
CHANGE COLUMN `createdAt` `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

CREATE TABLE `showtecsystem`.`cad_plano_modulo` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_plano` INT(11) NOT NULL,
  `id_modulo` INT(11) NOT NULL,
  `status` ENUM('0', '1') NOT NULL DEFAULT '1',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));

CREATE TABLE `showtecsystem`.`cad_produtos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(100) NULL,
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('0', '1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`));

INSERT INTO showtecsystem.cad_produtos (`nome`) VALUES ('OMNIFROTA');
INSERT INTO showtecsystem.cad_produtos (`nome`) VALUES ('OMNILINK');
INSERT INTO showtecsystem.cad_produtos (`nome`) VALUES ('OMNIWEB');
INSERT INTO showtecsystem.cad_produtos (`nome`) VALUES ('ROTOGRAMA');
INSERT INTO showtecsystem.cad_produtos (`nome`) VALUES ('TELEMETRIA');
INSERT INTO showtecsystem.cad_produtos (`nome`) VALUES ('OMNICARGA');
INSERT INTO showtecsystem.cad_produtos (`nome`) VALUES ('SHOW');

CREATE TABLE `showtecsystem`.`cad_produto_plano` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_produto` INT NOT NULL,
  `id_plano` INT NOT NULL,
  `status` ENUM('0', '1') NOT NULL DEFAULT '1',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));

ALTER TABLE `showtecsystem`.`cad_clientes` 
ADD COLUMN `id_produto` INT(10) NULL DEFAULT NULL AFTER `id_central_default`;
