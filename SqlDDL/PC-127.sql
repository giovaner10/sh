DROP TABLE IF EXISTS portal_compras.produtos;

CREATE TABLE `portal_compras`.`produtos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL COMMENT 'Código do produto no ERP',
  `descricao` varchar(120) NOT NULL,
  `codigo_empresa` varchar(5) NOT NULL,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
	INDEX `idx__codigo_empresa` (`codigo_empresa` ASC),
  UNIQUE INDEX `UNIQUE__codigo__codigo_empresa` (`codigo` ASC, `codigo_empresa` ASC)
);

DROP TABLE IF EXISTS portal_compras.fornecedores;

CREATE TABLE `portal_compras`.`fornecedores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) NOT NULL COMMENT 'Código (chave primaria) do fornecedor no ERP',
  `documento` VARCHAR(14) NOT NULL,
  `nome` VARCHAR(64) NOT NULL,
  `loja` VARCHAR(5) NOT NULL,
  `tipo` VARCHAR(5) NULL,
  `codigo_empresa` varchar(5) NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
	INDEX `idx__codigo_empresa` (`codigo_empresa` ASC)
	UNIQUE INDEX `UNIQUE__codigo__loja__codigo_empresa` (`codigo` ASC, `loja` ASC, `codigo_empresa` ASC)
);


CREATE TABLE `portal_compras`.`centro_custo` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) NOT NULL,
  `descricao` VARCHAR(120) NOT NULL,
  `codigo_empresa` varchar(5) NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
	INDEX `idx__codigo_empresa` (`codigo_empresa` ASC)
	UNIQUE INDEX `UNIQUE__codigo__codigo_empresa` (`codigo` ASC, `codigo_empresa` ASC)
);


CREATE TABLE `portal_compras`.`condicao_pagamento` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) NOT NULL,
  `descricao` VARCHAR(120) NOT NULL,
  `codigo_empresa` varchar(5) NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
	INDEX `idx__codigo_empresa` (`codigo_empresa` ASC)
	UNIQUE INDEX `UNIQUE__codigo__codigo_empresa` (`codigo` ASC, `codigo_empresa` ASC)
);


CREATE TABLE `portal_compras`.`empresas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) NOT NULL COMMENT 'Código (chave primaria) da empresa no ERP',
  `nome` VARCHAR(64) NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
	UNIQUE INDEX `UNIQUE__codigo` (`codigo` ASC)
);


CREATE TABLE `portal_compras`.`filiais` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) NOT NULL COMMENT 'Código (chave primaria) da filial no ERP',
  `documento` VARCHAR(14) NOT NULL,
  `nome` VARCHAR(64) NOT NULL,
  `codigo_empresa` varchar(5) NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
	INDEX `idx__codigo_empresa` (`codigo_empresa` ASC),
	UNIQUE INDEX `UNIQUE__codigo__codigo_empresa` (`codigo` ASC, `codigo_empresa` ASC)
);