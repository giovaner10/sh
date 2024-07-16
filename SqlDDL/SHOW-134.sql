-- CRIAÇÃO DE SCHEMA
CREATE SCHEMA `integracao_crm_shownet` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;

-- CRIAÇÃO DE TABELA auditoria_sac
CREATE TABLE `integracao_crm_shownet`.`auditoria_sac` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario_shownet` int(10) unsigned NOT NULL,
  `query` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_api` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clause` enum('insert','update','delete') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `cpf_cnpj` varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_fk__usuario__id__id_usuario_shownet` (`id_usuario_shownet`),
  CONSTRAINT `id_usuario_shownet` FOREIGN KEY (`id_usuario_shownet`) REFERENCES `showtecsystem`.`usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRIAÇÃO DE TABELA auditoria_campos_sac
CREATE TABLE `integracao_crm_shownet`.`auditoria_campos_sac` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_auditoria` int(10) unsigned NOT NULL,
  `campo` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor_antigo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor_novo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_fk__auditoria_sac__id__id_auditoria` (`id_auditoria`),
  CONSTRAINT `id_auditoria` FOREIGN KEY (`id_auditoria`) REFERENCES `integracao_crm_shownet`.`auditoria_sac` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3330 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRIAÇão DE TABELA ocorrencia_x_user_sac
CREATE TABLE `integracao_crm_shownet`.`ocorrencia_x_user_sac` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `incidentid` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_usuario_cadastro` int(10) unsigned DEFAULT NULL COMMENT 'Id do usuário do shownet que fez o cadastro da ocorrência no SAC',
  `id_usuario_update` int(10) unsigned DEFAULT NULL COMMENT 'Id do usuário do shownet que fez o update do SAC do shownet',
  `clause` enum('insert','update') COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fk__usuario__ocorrencia_x_user_sac__id_usuario_cadastro` (`id_usuario_cadastro`),
  KEY `idx_fk__usuario__ocorrencia_x_user_sac__id_usuario_update` (`id_usuario_update`),
  CONSTRAINT `fk__usuario__ocorrencia_x_user_sac__id_usuario_cadastro` FOREIGN KEY (`id_usuario_cadastro`) REFERENCES `showtecsystem`.`usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__usuario__ocorrencia_x_user_sac__id_usuario_update` FOREIGN KEY (`id_usuario_update`) REFERENCES `showtecsystem`.`usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
