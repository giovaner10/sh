INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('nexxera', null, 'vis_nexxera', 'sim', null, 'web', 999, 'ativo', 'Nexxera', 'Nexxera', null);


INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('nexxera_historico', 'Nexxera/Nexxera', 'vis_nexxera', 'nao', (SELECT ID FROM showtecsystem.menu WHERE nome = 'nexxera'), 'web', 999, 'ativo', 'Histórico de Envios', 'Shipping History', null);


INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Nexxera', 'vis_nexxera', 1, 'Financeiro');