INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'levantamento_pedidos',
  NULL,
  'vis_levantamento_pedidos',
  'sim',
  id,
  'dashboard',
  999,
  'ativo',
  'Levantamento de Pedidos',
  'Order placement'
FROM showtecsystem.menu
WHERE nome = 'relatorios' AND codigo_permissao = 'vis_relatorios';

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'pedidos_gerados',
  'levantamentoPedidos/pedidosGerados',
  'vis_levantamento_pedidos',
  'nao',
  id,
  'dashboard',
  999,
  'ativo',
  'Pedidos Gerados',
  'Generated Orders'
FROM showtecsystem.menu
WHERE nome = 'levantamento_pedidos';

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('levantamento_pedidos', 'vis_levantamento_pedidos', 1, 'Relatórios');