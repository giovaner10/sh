INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'relatorio_agendamento',
  'Auditoria/Agendamento/relatorioAgendamento',
  'vis_agendamento_auditoria',
  'nao',
  id,
  'shopping_cart',
  999,
  'ativo',
  'Relatório de Agendamento',
  'Scheduling Report'
FROM showtecsystem.menu
WHERE nome = 'menu_auditoria';