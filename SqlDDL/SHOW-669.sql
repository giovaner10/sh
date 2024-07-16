CREATE TABLE `showtecsystem`.`release_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_note` varchar(100) DEFAULT NULL,
  `id_arquivo` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT = 'Tabela contendo os relese notes das ultimas alterações em shownet';



INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) 
VALUES ('release_notes', 'Empresas/ReleaseNotes', 'vis_empresa', 'nao', '2', 'engineering', 'ativo');