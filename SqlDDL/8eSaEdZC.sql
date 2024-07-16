ALTER TABLE `showtecsystem`.`cad_faturas`
CHANGE COLUMN `atividade` `atividade` ENUM('0', '1', '2', '3') NULL DEFAULT '0' COMMENT '0 - Outros;     1- Atividade de Monitoramento;     2- Servicos Tecnicos;     3- Aluguel de outras maquinas e equipamentos' ;
