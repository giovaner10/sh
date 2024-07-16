INSERT INTO showtecsystem.menu
(id, nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status)
VALUES(null, 'comissionamento_vendas', NULL, 'vis_comercialetelevendas', 'sim', 31, 'shopping_cart', 999, 'ativo');

INSERT INTO showtecsystem.menu
(id, nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status)
VALUES(null, 'campanhas', 'ComerciaisTelevendas/ComissionamentoDeVendas', 'vis_comercialetelevendas', 'nao', 195, 'shopping_cart', 999, 'ativo');