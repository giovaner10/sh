INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'pedidos_gerados_nf_amarra_bi',
  'levantamentoPedidos/pedidosGeradosNFAmarraBI',
  'vis_levantamento_pedidos_nf_amarra_bi',
  'nao',
  id,
  'dashboard',
  999,
  'ativo',
  'Pedidos Gerados NF Amarra BI',
  'Generated Orders NF Amarra BI'
FROM showtecsystem.menu
WHERE nome = 'levantamento_pedidos';

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('Pedidos Gerados NF Amarra BI', 'vis_levantamento_pedidos_nf_amarra_bi', 1, 'Relatórios');