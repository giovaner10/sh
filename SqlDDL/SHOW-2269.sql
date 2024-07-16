INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'levantamento_pedidos',
  'levantamentoPedidos',
  'vis_levantamento_pedidos',
  'nao',
  id,
  'dashboard',
  999,
  'ativo',
  'Levantamento de Pedidos',
  'Order placement'
FROM showtecsystem.menu
WHERE codigo_permissao = 'vis_relatorios'