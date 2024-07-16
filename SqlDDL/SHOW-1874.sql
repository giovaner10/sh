SELECT id INTO @menu_mapa FROM showtecsystem.menu WHERE nome = 'relatorios';

INSERT INTO showtecsystem.menu(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES('relatorio_acessos', 'relatorios/relatorio_acessos', 'vis_relatorio_acessos', 'nao', 57, 'shopping_cart', 999, 'ativo', 'Relatório de Acesso', 'Access Report');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('relatorio_acessos', 'vis_relatorio_acessos', 1, 'Relatórios');