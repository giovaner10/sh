ALTER TABLE `meu_omnilink`.`ultimas_noticias` 
CHANGE COLUMN `descricao` `descricao` TEXT NOT NULL ;

ALTER TABLE `meu_omnilink`.`ultimas_noticias` 
CHANGE COLUMN `titulo` `titulo` VARCHAR(100) NOT NULL ;

ALTER TABLE `meu_omnilink`.`ultimas_noticias` 
CHANGE COLUMN `imagem_nome` `imagem_nome` VARCHAR(100) NOT NULL ;

ALTER TABLE `meu_omnilink`.`banners` 
CHANGE COLUMN `titulo` `titulo` VARCHAR(100) NOT NULL ,
CHANGE COLUMN `imagem_nome` `imagem_nome` VARCHAR(100) NOT NULL ;
