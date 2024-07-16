

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'pedidos_gerados_nf',
  'levantamentoPedidos/pedidosGeradosComNFGerada',
  'vis_levantamento_pedidos_nf',
  'nao',
  id,
  'dashboard',
  999,
  'ativo',
  'Pedidos Gerados com NF',
  'Generated Orders with NF'
FROM showtecsystem.menu
WHERE nome = 'levantamento_pedidos';

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('levantamento_pedidos_nf', 'vis_levantamento_pedidos_nf', 1, 'Relatórios');