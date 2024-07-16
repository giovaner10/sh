CREATE TABLE `televendas`.`avaliacao_clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('pendente','finalizado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente' NOT NULL,
  `resultado` enum('sem_resultado','aprovado','reprovado') COLLATE utf8mb4_unicode_ci DEFAULT 'sem_resultado' NOT NULL,
  `id_cotacao_crm` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cotacoes` int(10) unsigned DEFAULT NULL,
  `id_perfis` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fk__analise_clientes__perfis` (`id_perfis`),
  KEY `idx_fk__analise_clientes__cotacoes` (`id_cotacoes`),
  CONSTRAINT `fk__analise_clientes__cotacoes` FOREIGN KEY (`id_cotacoes`) REFERENCES `cotacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk__analise_clientes__perfis` FOREIGN KEY (`id_perfis`) REFERENCES `omniscore`.`perfis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);