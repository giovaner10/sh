CREATE TABLE `showtecsystem`.`cad_suprimentos` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `serial` VARCHAR(50) NOT NULL,
  `descricao` VARCHAR(200) NULL,
  `tipo` INT NOT NULL COMMENT '1 = Cinta, 2 = Powerbank, 3 = Carregador',
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));

CREATE TABLE `showtecsystem`.`contratos_suprimentos` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_contrato` INT(11) UNSIGNED NOT NULL,
  `id_cliente` INT(11) UNSIGNED NOT NULL,
  `id_suprimento` INT(11) NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vinculacao` ENUM('disponivel', 'em uso', 'recolhimento', 'perdida') NOT NULL DEFAULT 'disponivel',
  PRIMARY KEY (`id`));
