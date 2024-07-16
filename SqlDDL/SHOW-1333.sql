INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('vendas_comissionadas', 'vis_vendas_comissionadas', 1, 'Comissionamento')

SET @menu_comissionamento_vendas = (select ID from showtecsystem.menu  where nome = 'comissionamento_vendas' limit 0,1);

SELECT  @menu_comissionamento_vendas

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES( 'vendas_comissionadas', 'ComerciaisTelevendas/ComissionamentoDeVendas/VendasComissionadas', 'vis_vendas_comissionadas', 'nao', @menu_comissionamento_vendas, 'shopping_cart', 999, 'ativo', 'Vendas Comissionadas', 'Commissioned Sales');