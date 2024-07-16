SELECT id INTO @menu_mapa FROM showtecsystem.menu WHERE nome = 'menu_auditoria';

INSERT INTO showtecsystem.menu(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES('mapa_calor', 'Auditoria/mapa_calor/index', 'vis_mapa_calor', 'nao', @menu_mapa, 'shopping_cart', 999, 'ativo', 'Mapa de Calor', 'Heat Map');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('mapa_calor', 'vis_mapa_calor', 1, 'Auditoria');
