INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('meu_omnilink', 'Marketings/MeuOmnilink', 'vis_marketingmeuomnilink', 'nao', '32', 'favorite', '999', 'ativo');

CREATE TABLE `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NOT NULL,
  `imagem_nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem_diretorio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo_url` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordem` int(11) NOT NULL,
  `status` enum('ativo','inativo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ativo',
  `exibe_na_home` enum('sim','nao') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nao',
  `id_usuario` INT(11) NOT NULL,
  `data_hora_cadastro` DATETIME NOT NULL,
  `data_hora_alteracao` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT * FROM showtecsystem.arquivos order by id desc;CREATE TABLE `ultimas_noticias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem_nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem_diretorio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo_url` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL,
  `status` enum('ativo','inativo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ativo',
  `exibe_na_home` enum('sim','nao') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nao',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

