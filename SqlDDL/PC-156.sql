ALTER TABLE `portal_compras`.`cotacoes` 
ADD COLUMN `tipo_especie` ENUM('sped', 'nfe', 'cte', 'nfs', 'nf', 'outro') NOT NULL DEFAULT 'outro' AFTER `datahora_cadastro`;
