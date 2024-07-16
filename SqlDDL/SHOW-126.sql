ALTER TABLE `showtecsystem`.`cad_clientes` 
ADD COLUMN `acesso_omnisearch` ENUM('bloqueado', 'liberado') NULL DEFAULT 'bloqueado' AFTER `logotipo`,
ADD COLUMN `valor_cadastro_omnisearch` FLOAT(10,2) NULL DEFAULT '0.00' AFTER `acesso_omnisearch`,
ADD COLUMN `valor_consulta_omnisearch` FLOAT(10,2) NULL DEFAULT '0.00' AFTER `valor_cadastro_omnisearch`,
COMMENT 'libera ou bloqueia o acesso do cliente a area das consultas de perfis de profissionais (omniserach)';


