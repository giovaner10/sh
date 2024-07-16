ALTER TABLE `showtecsystem`.`cad_permissoes` 
ADD COLUMN `tecnologia` ENUM('Omnilink', 'Omniweb', 'Omnifrota') NULL AFTER `data_cad`,
ADD COLUMN `modulo` ENUM('Atendimento', 'Cadastro', 'Comando', 'Configuração', 'Monitorados', 'Relatório', 'Notificação') NULL AFTER `tecnologia`;

UPDATE showtecsystem.cad_permissoes SET modulo = 'Notificação' where cod_permissao like 'ev_%';
UPDATE showtecsystem.cad_permissoes SET modulo = 'Notificação' where cod_permissao like 'notify_%';
UPDATE showtecsystem.cad_permissoes SET modulo = 'Relatório' WHERE descricao LIKE 'Relatório%';
UPDATE showtecsystem.cad_permissoes SET modulo = 'Cadastro' where descricao like 'Cadastro%';
UPDATE showtecsystem.cad_permissoes SET modulo = 'Comando' where cod_permissao like 'comando_%';
UPDATE showtecsystem.cad_permissoes SET modulo = 'Configuração' where cod_permissao like 'config%';

CREATE TABLE `showtecsystem`.`cad_produto_permissao` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_produto` INT UNSIGNED NOT NULL,
  `id_permissao` INT UNSIGNED NOT NULL,
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));
