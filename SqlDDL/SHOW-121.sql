-- ADICIONA COLUNA CPF_CNPJ na tabela de auditoria do sac
ALTER TABLE `showtecsystem`.`auditoria_sac` 
ADD COLUMN `cpf_cnpj` VARCHAR(14) NULL DEFAULT NULL AFTER `data_cadastro`;
