CREATE SCHEMA `televendas`;

USE `televendas`;

CREATE TABLE `anotacoes` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    descricao VARCHAR(240) NOT NULL,
    arquivo VARCHAR(120) DEFAULT NULL,
    id_crm VARCHAR(64) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `produtos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  `valor_unitario` float(10,2) NOT NULL,
  `valor_total` float(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `tipo` enum('hardware','software','licenca') NOT NULL,
  `plano_satelital` varchar(120) DEFAULT NULL,
  `desconto` float(10,2) DEFAULT NULL,
  `id_crm` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `configurometros` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(120) NOT NULL,
  `acessorios` varchar(120) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `quantidade_total` int(11) NOT NULL,
  `id_crm` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `afs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_crm` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `pedidos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `razao_status` varchar(120) NOT NULL,
  `valor_total` float(10,2) NOT NULL,
  `id_crm` varchar(64) DEFAULT NULL,
  `id_cad_clientes` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fk__pedidos__cad_clientes` (`id_cad_clientes`),
  CONSTRAINT `fk__pedidos__cad_clientes` FOREIGN KEY (`id_cad_clientes`) REFERENCES `showtecsystem`.`cad_clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE `composicoes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sistema` varchar(120) NOT NULL,
  `familia_produto` varchar(120) NOT NULL,
  `categoria` varchar(60) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `tipo_comunicacao` varchar(60) NOT NULL,
  `produto` varchar(120) NOT NULL,
  `tipo_veiculo` varchar(60) NOT NULL,
  `id_crm` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `cotacoes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  `status` varchar(120) NOT NULL,
  `valor_total` float(10,2) NOT NULL,
  `nome_cliente` varchar(240) NOT NULL,
  `email_cliente` varchar(120) NOT NULL,
  `razao_status` varchar(120) DEFAULT NULL,
  `id_crm` varchar(64) DEFAULT NULL,
  `id_pedidos` int(11) unsigned DEFAULT NULL,
  `id_afs` int(11) unsigned DEFAULT NULL,
  `id_configurometros` int(11) unsigned DEFAULT NULL,
  `id_composicoes` int(11) unsigned DEFAULT NULL,
  `id_produtos` int(11) unsigned DEFAULT NULL,
  `id_anotacoes` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fk__cotacoes__pedidos` (`id_pedidos`),
  KEY `idx_fk__cotacoes__af` (`id_afs`),
  KEY `idx_fk__cotacoes__configurometros` (`id_configurometros`),
  KEY `idx_fk__cotacoes__composicoes` (`id_composicoes`),
  KEY `idx_fk__cotacoes__produtos` (`id_produtos`),
  KEY `idx_fk__cotacoes__anotacoes` (`id_anotacoes`),
  CONSTRAINT `fk__cotacoes__pedidos` FOREIGN KEY (`id_pedidos`) REFERENCES `televendas`.`pedidos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__cotacoes__af` FOREIGN KEY (`id_afs`) REFERENCES `televendas`.`afs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__cotacoes__configurometros` FOREIGN KEY (`id_configurometros`) REFERENCES `televendas`.`configurometros` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__cotacoes__composicoes` FOREIGN KEY (`id_composicoes`) REFERENCES `televendas`.`composicoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__cotacoes__produtos` FOREIGN KEY (`id_produtos`) REFERENCES `televendas`.`produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__cotacoes__anotacoes` FOREIGN KEY (`id_anotacoes`) REFERENCES `televendas`.`anotacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE `oportunidades` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cenario_venda` varchar(120) NOT NULL,
  `chance_ganhar` varchar(120) NOT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` datetime DEFAULT NULL,
  `data_fechamento` datetime DEFAULT NULL,
  `data_estipulada_fechamento` datetime NOT NULL,
  `fase` varchar(120) NOT NULL,
  `status` varchar(120) NOT NULL,
  `quantidade_rastreadroes` int(11) NOT NULL,
  `razao_status` varchar(120) NOT NULL,
  `valor_total_licenca` float(10,2) NOT NULL,
  `valor_total_hardware` float(10,2) NOT NULL,
  `id_crm` varchar(64) DEFAULT NULL,
  `id_cotacoes` int(11) unsigned DEFAULT NULL,
  `id_cad_clientes` int(11) unsigned NOT NULL,
  `id_usuario` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fk__oportunidades__cotacoes` (`id_cotacoes`),
  KEY `idx_fk__oportunidades__cad_clientes` (`id_cad_clientes`),
  KEY `idx_fk__oportunidades__usuarios` (`id_usuario`),
  CONSTRAINT `fk__oportunidades__cotacoes` FOREIGN KEY (`id_cotacoes`) REFERENCES `televendas`.`cotacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__oportunidades__cad_clientes` FOREIGN KEY (`id_cad_clientes`) REFERENCES `showtecsystem`.`cad_clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__oportunidades__usuario` FOREIGN KEY (`id_usuario`) REFERENCES `showtecsystem`.`usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);