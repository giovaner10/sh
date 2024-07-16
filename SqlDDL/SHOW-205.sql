ALTER TABLE `showtecsystem`.`contratos` 
CHANGE COLUMN `tipo_proposta` `tipo_proposta` INT(11) NULL DEFAULT '0' COMMENT '0 = Contrato SHOW; 1 = Contrato SIMM2M; 2 = Contrato SHOW TELEMETRIA; 3 = Contrato NORIO; 4 = Contrato TORNOZELEIRA; 5 = Contrato Roteirização; 6- Contrato Iscas; 7- Contrato Licenciamento Software' ;
