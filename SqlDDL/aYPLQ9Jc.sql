/*Cria tabela para guardar histórico de desvinculação de iscas*/
CREATE TABLE `showtecsystem`.`historico_desvinculacao_isca` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_isca` INT NOT NULL,
  `data_desvinculacao` DATETIME NOT NULL,
  PRIMARY KEY (`id`));
