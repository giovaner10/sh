 -- Verificar o id_pai em produção
 
 INSERT INTO showtecsystem.menu
 (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
 VALUES('relatorio_manutencao_detalhado', 'Auditoria/Agendamento/relatorioManutencaoDetalhado', 'vis_relatorio_manutencao', 'nao', 250, 'badge', 10, 'ativo', 'Relatório de Manutenção Detalhado', 'Consolidated Maintenance Report', NULL);
 