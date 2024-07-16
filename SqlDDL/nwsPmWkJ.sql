ALTER TABLE `showtecsystem`.`contratos` 
CHANGE COLUMN `consumo_fatura` `consumo_fatura` CHAR(1) NULL DEFAULT '0' COMMENT '0-faturamento mensal, 1-faturamento  por dias de uso, 2-faturamento apartir do cadastro no contrato, 3-faturamento apartir da instalacao do equipamento' ;
