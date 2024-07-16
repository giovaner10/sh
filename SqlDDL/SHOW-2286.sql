INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao,cod_permissao,status,modulo)
VALUES ('agendamentos_manutencao', 'vis_agendamento_manutencao', '1', 'Auditoria')

UPDATE showtecsystem.menu SET codigo_permissao = 'vis_agendamento_manutencao' WHERE nome = 'agendamentos_manutencao'

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao,cod_permissao,status,modulo)
VALUES ('agendamentos_instalacao', 'vis_agendamento_instalacao', '1', 'Auditoria')

UPDATE showtecsystem.menu SET codigo_permissao = 'vis_agendamento_instalacao' WHERE nome = 'agendamentos_instalacao'