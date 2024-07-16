USE showtecsystem;
CREATE TABLE `arquivos_rep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) NOT NULL,
  `descricao` varchar(250) DEFAULT NULL,
  `pasta` varchar(250) NOT NULL,
  `ndoc` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6458 DEFAULT CHARSET=latin1;