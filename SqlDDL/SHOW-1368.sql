INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status)
SELECT 
  'cenarios_de_vendas',
  'ComerciaisTelevendas/ComissionamentoDeVendas/cenariosDeVendas',
  'vis_comercialetelevendas',
  'nao',
  id,
  'shopping_cart',
  999,
  'ativo'
FROM showtecsystem.menu
WHERE nome = 'comissionamento_vendas';
