SET @auditoria_agendamento = (select id from showtecsystem.menu where nome = 'auditoria_agendamentos');
select  @auditoria_agendamento;

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'agendamentos_manutencao', 'Auditoria/Agendamento/AgendamentoManutencao' , 'vis_agendamento_auditoria', 'nao', @auditoria_agendamento, 'summarize', 999, 'ativo', 'Agendamento de Manutenção', 'Maintenance Scheduling', null);