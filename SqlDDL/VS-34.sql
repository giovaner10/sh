/* Insert de nova permissão para o módulo de vendas de software */

INSERT INTO showtecsystem.cad_permissoes_funcionarios
(descricao, cod_permissao, status, modulo)
VALUES('Vendas de Software', 'vis_vendas_de_software', '1', 'Comercial e Televendas');

/* Insert de novo menu para o módulo de vendas de software */

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'vendas_software',
  'VendasDeSoftware/VendasSoftware',
  'vis_vendas_de_software',
  'nao',
  id,
  'shopping_cart',
  999,
  'ativo',
  'Vendas de Software',
  'Software Sales'
FROM showtecsystem.menu
WHERE nome = 'comercial_e_televendas';