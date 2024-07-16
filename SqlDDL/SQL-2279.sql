INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES( 
  'relatorio_instalacao',
  'Auditoria/Agendamento/relatorioAgendamento',
  'vis_relatorio_instalacao',
  'nao',
  NULL,
  'shopping_cart',
  999,
  'ativo',
  'Relatório de Instalação',
  'Installation Report'
)

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao,cod_permissao,status,modulo)
VALUES ('Relatório de Instalação', 'vis_relatorio_instalacao', '1', 'Auditoria')

UPDATE showtecsystem.menu SET caminho = NULL, filhos = 'sim' WHERE nome = 'relatorio_agendamento'

UPDATE showtecsystem.menu SET id_pai = (select ID from showtecsystem.menu where nome = 'relatorio_agendamento') WHERE nome = 'relatorio_instalacao'