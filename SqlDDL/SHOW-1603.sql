INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'insumos',
  'Insumos',
  'logistica_shownet',
  'nao',
  id,
  'shopping_cart',
  999,
  'ativo',
  'Insumos',
  'Supplies'
FROM showtecsystem.menu
WHERE nome = 'Logistica';
