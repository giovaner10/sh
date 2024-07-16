INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT  
  'relatorio_manutencao',
  'Auditoria/Agendamento/relatorioManutencao',
  'vis_relatorio_manutencao',
  'nao',
  id,
  'shopping_cart',
  999,
  'ativo',
  'Relatório de Manutenção',
  'Maintenance Report'
FROM showtecsystem.menu
WHERE nome = 'relatorio_agendamento';

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao,cod_permissao,status,modulo)
VALUES ('Relatório de Manutenção', 'vis_relatorio_manutencao', '1', 'Auditoria')
