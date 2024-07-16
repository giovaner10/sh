INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`descricao`, `cod_permissao`, `modulo`) 
VALUES ('Indicadores de anúncios/vendas', 'cad_indicadoresanunciosvendas', 'Cadastros');


UPDATE `showtecsystem`.`menu` SET `nome` = 'vendas_gestor', `caminho` = NULL, `filhos` = 'sim' WHERE (`nome` = 'anuncios_produtos');

INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `ordem`, `status`) 
VALUES ('anuncios', 'vendasgestor/anuncios', 'cad_anunciosprodutos', 'nao', null, '999', 'ativo');

INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `ordem`, `status`) 
VALUES ('indicadores', 'vendasgestor/indicadores', 'cad_indicadoresanunciosvendas', 'nao', null, '999', 'ativo');


-- Atuliza o id do pai dos itens de menu anuncios e indicadores
UPDATE showtecsystem.menu as menu, (SELECT id FROM showtecsystem.menu WHERE nome = 'vendas_gestor') as pai
SET menu.id_pai = pai.id
WHERE menu.nome IN ('anuncios', 'indicadores');

