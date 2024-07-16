CREATE SCHEMA `vendasgestor` ;

CREATE TABLE `vendasgestor`.`anuncios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(60) NOT NULL COMMENT 'titulo do anuncio',
  `descricao` VARCHAR(240) NOT NULL COMMENT 'breve descricao sobre o item anunciado',
  `caminho_midia` VARCHAR(120) NOT NULL COMMENT 'path onde esta salva a imagem/video do anuncio',
  `data_inicio` DATETIME NOT NULL COMMENT 'data e hora que irá iniciar o anuncio',
  `data_fim` DATETIME NOT NULL COMMENT 'data e hora que irá findar o anuncio',
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_cad_produtos` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__anuncios__cad_produtos` (`id_cad_produtos` ASC),
  CONSTRAINT `fk__anuncios__cad_produtos`
    FOREIGN KEY (`id_cad_produtos`)
    REFERENCES `showtecsystem`.`cad_produtos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) COMMENT = 'Anuncios de produtos gestor, anuncios voltados a divulgacao de produtos.';


INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`descricao`, `cod_permissao`, `modulo`) 
VALUES ('Anúncios de Produtos', 'cad_anunciosprodutos', 'Cadastros');

INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `icone`) 
VALUES ('anuncios_produtos', 'vendasgestor/anuncios', 'cad_anunciosprodutos', 'nao', 'ads_click');


