INSERT INTO showtecsystem.menu
(id, nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES(344, 'pos-venda', '', 'vis_vismenuposvenda', 'sim', NULL, 'payment', 999, 'ativo', 'Pós-vendas', 'After-sales', NULL);

INSERT INTO showtecsystem.menu
(id, nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES(345, 'analista-suporte', 'PosVenda/Gestao/analista_suporte', 'vis_vismenuposvenda', 'nao', 344, 'payment', 999, 'ativo', 'Analistas de Suporte', 'Support Analyst', NULL);

INSERT INTO showtecsystem.menu
(id, nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES(346, 'dashboard', 'PosVenda/Gestao', 'vis_vismenuposvenda', 'nao', 344, 'payment', 999, 'ativo', 'Dashboard', 'Dashboard', NULL);

INSERT INTO showtecsystem.menu
(id, nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES(347, 'clientes-ativos', 'PosVenda/Gestao/clientes_ativos', 'vis_vismenuposvenda', 'nao', 344, 'payment', 999, 'ativo', 'Clientes Ativos', 'Active Clients', NULL);