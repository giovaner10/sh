
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) 
VALUES ('apresentacoes_comerciais', 'ashownet/apresentacoes_comerciais', 'vis_comercialetelevendasapresentacoes', 'nao', '31', 'shopping_cart', '52', 'ativo');


INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) 
VALUES ('listar_apresentacoes_comerciais', 'cadastros/listar_apresentacoes_comerciais', 'vis_comercialetelevendasapresentacoes', 'nao', '36', 'post_add', 'ativo');


INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`cod_permissao` , `descricao`, `modulo`)
VALUES ('cad_apresentacoes_comerciais','Apresentações Comerciais', 'Televendas');


INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`cod_permissao` , `descricao`, `modulo`)
VALUES ('listar_apresentacoes_comerciais','Listar Apresentações Comerciais', 'Cadastros');