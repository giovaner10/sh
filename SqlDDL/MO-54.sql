ALTER TABLE `meu_omnilink`.`ultimas_noticias` 
DROP COLUMN `exibe_na_home`,
DROP COLUMN `ordem`,
ADD COLUMN `id_usuario` INT NOT NULL AFTER `status`,
ADD COLUMN `data_hora_cadastro` DATETIME NOT NULL AFTER `id_usuario`,
ADD COLUMN `data_hora_alteracao` DATETIME NULL AFTER `data_hora_cadastro`;
