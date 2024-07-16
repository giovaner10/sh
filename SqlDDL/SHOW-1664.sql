
SET @menu_bi = (select id from showtecsystem.menu where nome = 'menu_bi');
SET @menu_agendamento_ativacao = (select id from showtecsystem.menu where nome = 'agendamento_ativacao_bi');
SET @menu_atendimento_suporte = (select id from showtecsystem.menu where nome = 'atendimento_suporte_bi');
SET @menu_retencao = (select id from showtecsystem.menu where nome = 'retencao_bi');
SET @menu_operacoes_bi = (select id from showtecsystem.menu where nome = 'operacoes_bi');

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'resultados_operacoes_bi', 'BI/BI', 'vis_resultado_operacoes_bi', 'nao', @menu_operacoes_bi, 'dashboard', 999, 'ativo', 'Resultado de Operações', 'Result of Operations', ''),
( 'telemetria_can_bi', 'BI/BI', 'vis_telemetriacan_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Telemetria CAN', 'Telemetry CAN', ''),
( 'segmentacao_clientes_bi', 'BI/BI', 'vis_segmentacao_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Atividade de Serviço', 'Service Activity', ''),
( 'painel_indisponibilidade_bi', 'BI/BI', 'vis_painel_indisponibilidade_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Atividade de Serviço', 'Service Activity', '');

update showtecsystem.menu set codigo_permissao = 'vis_resultado_operacoes_bi' where nome = 'resultados_operacoes_bi'
update showtecsystem.menu set codigo_permissao = 'vis_telemetriacan_bi' where nome = 'telemetria_can_bi'
update showtecsystem.menu set codigo_permissao = 'vis_segmentacao_bi' where nome = 'segmentacao_clientes_bi'
update showtecsystem.menu set codigo_permissao = 'vis_painel_indisponibilidade_bi' where nome = 'painel_indisponibilidade_bi'

UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiZDA2MzczM2EtZmE3ZS00MWIzLWJiZjUtYjUxMjUwNmMyM2EzIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'abertura_na_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiYmNhYjI4YzAtNDYwYi00NDM4LWFlNWUtOWZkZDg2MDBmZTU5IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'gestao_agendamento_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiNGEwMDIxNzgtNGNhYS00YzI5LWE3ZjgtZWFhODA3ZmY0OWE0IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'painel_atendimento_bi');	
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiODM1NjNlOGEtZTA0MS00YjZkLWEyY2EtNmQyZDIzNWI5MmQ4IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'produtividade_bi');

UPDATE showtecsystem.menu SET id_pai = @menu_atendimento_suporte, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiZDMyNzZiY2YtMjcxMC00MDc2LTk0MjMtZjUxYjkzMzYzMmZkIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'desempenho_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_atendimento_suporte, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMzI1OWM0ZjctYTkwOC00MjQxLWIyOWYtMjhmOWMzZmRiYjU3IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'painel_operacoes_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_atendimento_suporte, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMTU0YjFlYTktYmJkNS00NmE5LWEyN2UtZWJjOTBmMWM5MDRlIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'chatbot_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_atendimento_suporte, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMjAwMDQ0NWYtN2UwNC00MWQyLWIyMTItNTUxOGE5YjA5MGU3IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'chatbot_crm_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_atendimento_suporte, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMTg5NGIwZGMtMTc5Ny00ZjkxLWJkZDctMDI5MzhlNTFkN2RmIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'homologacao_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiOTg0OWYzYTctOGRhOS00NDIwLTlmZWMtNzI2ZTIxMjcxNDliIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'monitoria_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiZjYwNjEyZDQtMDQzYi00YWJjLTg3YWYtYmIzNWIzNzc5NzVkIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'treinamento_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMTk4NzdmOGEtNTk1OC00ZjViLWExMDUtNDBlN2ViZTQyMTg0IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'telemetria_can_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiZDg3NDc5ZGMtNDJkNy00ZTgxLWJmZGUtMDU2MzY0NzdhOTUxIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'segmentacao_clientes_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_agendamento_ativacao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiYjI3ZjYzN2EtZjQ1Ni00ZDE4LWEwZGEtNjM3MWIxZDY1NGE5IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'painel_indisponibilidade_bi');

UPDATE showtecsystem.menu SET id_pai = @menu_operacoes_bi, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiNTBkOGI1ZGQtZTg1OS00ZjgxLTkxNDMtZDQ0Njk1ZTJhMTY3IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'prestadores_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_operacoes_bi, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMThjM2QxNzEtMWM1NC00ZmM0LTg0NjEtMmY3N2M3YzA0ZmExIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'atividade_servico_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_operacoes_bi, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiYjZjMDdjZWUtNGM3OC00Y2M1LTgwNjQtMjk5YTEyNzE1NWUwIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'atendimentos_cidade_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_operacoes_bi, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMTJkZWRjMjgtNDJiOC00NjRlLWI3MGYtYmIxMWE0YWE3OTI3IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'resultados_operacoes_bi');

UPDATE showtecsystem.menu SET id_pai = @menu_retencao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMTg4YzA1YjQtYjVmNi00ZjhjLWExZjQtNTNhZTk5ZTQ2OGFjIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'analitico_clientes_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_retencao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMjVlNDlmMmMtZDY4OC00NjIzLWJkZjgtYTM2MTNlYWFiMzdjIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'gestao_retencao_bi');
UPDATE showtecsystem.menu SET id_pai = @menu_retencao, link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiNjVjM2I5NzMtNjlkNi00OWI0LTlmOGYtZGVjN2Q0NTlhZGUwIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' 
WHERE (nome = 'churn_bi');

-- Dashboard para BI

UPDATE showtecsystem.menu SET id_pai = @menu_bi WHERE (nome = 'dashboards');
