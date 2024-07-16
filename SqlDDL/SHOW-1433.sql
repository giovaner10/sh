
ALTER TABLE showtecsystem.menu ADD COLUMN link_bi varchar(1000);

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'menu_bi', null , 'vis_menu_bi', 'sim', null, 'dashboard', 186, 'ativo', 'BI', 'BI', null);

SET @menu_bi = LAST_INSERT_ID();

SELECT @menu_bi;

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('menu_bi', 'vis_menu_bi', 0, 'BI'),
('agendamento_ativacao_bi', 'vis_agendamento_ativacao_bi', 0, 'BI'),
('atendimento_suporte_bi', 'vis_atendimento_suporte_bi', 0, 'BI'),
('retencao_bi', 'vis_retencao_bi', 0, 'BI'),

('prestadores_bi', 'vis_prestadores_bi', 0, 'BI'),
('abertura_na_bi', 'vis_abertura_na_bi', 0, 'BI'),
('atividade_servico_bi', 'vis_atividade_servico_bi', 0, 'BI'),
('gestao_agendamento_bi', 'vis_gestao_agendamento_bi', 0, 'BI'),

('desempenho_bi', 'vis_desempenho_bi', 0, 'BI'),
('painel_atendimento_bi', 'vis_painel_atendimento_bi', 0, 'BI'),
('painel_operacoes_bi', 'vis_painel_operacoes_bi', 0, 'BI'),
('produtividade_bi', 'vis_produtividade_bi', 0, 'BI'),
('analitico_clientes_bi', 'vis_analitico_clientes_bi', 0, 'BI'),

('gestao_retencao_bi', 'vis_gestao_retencao_bi', 0, 'BI'),
('churn_bi', 'vis_churn_bi', 0, 'BI'),
('chatbot_bi', 'vis_chatbot_bi', 0, 'BI'),
('chatbot_crm_bi', 'vis_chatbot_crm_bi', 0, 'BI'),
('homologacao_bi', 'vis_homologacao_bi', 0, 'BI'),
('monitoria_bi', 'vis_monitoria_bi', 0, 'BI'),

('treinamento_bi', 'vis_treinamento_bi', 0, 'BI'),
('atendimentos_cidade_bi', 'vis_atendimentos_cidade_bi', 0, 'BI');


INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'agendamento_ativacao_bi', null , 'vis_agendamento_ativacao_bi', 'sim', @menu_bi, 'dashboard', 186, 'ativo', 'Agendamento/Ativação', 'Scheduling/Activation', null);

SET @menu_agendamento_ativacao = LAST_INSERT_ID(); 
select  @menu_agendamento_ativacao;

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'atendimento_suporte_bi', null , 'vis_atendimento_suporte_bi', 'sim', @menu_bi, 'dashboard', 186, 'ativo', 'Atendimento e Suporte', 'Service and Support', null);

SET @menu_atendimento_suporte = LAST_INSERT_ID(); 
select  @menu_atendimento_suporte;

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'retencao_bi', null , 'vis_retencao_bi', 'sim', @menu_bi, 'dashboard', 186, 'ativo', 'Retenção', 'Retention', null);

SET @menu_retencao = LAST_INSERT_ID(); 
select  @menu_retencao;

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'prestadores_bi', 'BI/BI', 'vis_prestadores_bi', 'nao', @menu_bi, 'dashboard', 999, 'ativo', 'Prestadores', 'Providers', 'https://app.powerbi.com/groups/me/reports/5c737aa3-b7e6-4a25-bb71-3c701f83529d/ReportSection?ctid=ab89d8bc-1ef4-4e7d-b0ce-c43fbfdface3&experience=power-bi'),
( 'abertura_na_bi', 'BI/BI', 'vis_abertura_na_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Abertura de NA', 'Opening of NA', 'https://app.powerbi.com/view?r=eyJrIjoiZjExNDg5MDktNTVmOS00YWY4LWI2ZDktYTNmZjVjNWJhYWY4IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'atividade_servico_bi', 'BI/BI', 'vis_atividade_servico_bi', 'nao', @menu_bi, 'dashboard', 999, 'ativo', 'Atividade de Serviço', 'Service Activity', 'https://app.powerbi.com/view?r=eyJrIjoiYzViN2E1M2MtM2ExYi00YzAzLWE2MGYtNjJmMDg5ZjAxOTNjIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'gestao_agendamento_bi', 'BI/BI', 'vis_gestao_agendamento_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Gestão', 'Management', 'https://app.powerbi.com/view?r=eyJrIjoiMGQ2NmRlODgtZTk1Zi00OTA0LTgwYTUtNzNiZTMxMWFlMTZkIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),

( 'desempenho_bi', 'BI/BI', 'vis_desempenho_bi', 'nao', @menu_atendimento_suporte, 'dashboard', 999, 'ativo', 'Desempenho', 'Performance', 'https://app.powerbi.com/view?r=eyJrIjoiNGI3YzJkYzktNjA2NC00MjBlLTgyZmItMmFlNDNjZjc1YjlhIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'painel_atendimento_bi', 'BI/BI', 'vis_painel_atendimento_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Painel de Atendimento', 'Service Panel', 'https://app.powerbi.com/view?r=eyJrIjoiOGJjZmQ3NGYtMjc4NS00YTUxLTk4MzYtZTc4YWFkYWYwNjQ3IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'painel_operacoes_bi', 'BI/BI', 'vis_painel_operacoes_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Painel de Operações', 'Operations Panel', 'https://app.powerbi.com/view?r=eyJrIjoiYTI2OGMxOTYtN2I4YS00ODI5LWEwMTctNWM5Njk4ZGVhMjAyIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'produtividade_bi', 'BI/BI', 'vis_produtividade_bi', 'nao', @menu_agendamento_ativacao, 'dashboard', 999, 'ativo', 'Produtividade', 'Productivity', 'https://app.powerbi.com/view?r=eyJrIjoiYmMyN2Y4NTAtM2VjZC00ZTU5LTljN2UtOTAzODY5YjgwZjgzIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'analitico_clientes_bi', 'BI/BI', 'vis_analitico_clientes_bi', 'nao', @menu_retencao, 'dashboard', 999, 'ativo', 'Analítico Clientes', 'Customer Analytics', 'https://app.powerbi.com/view?r=eyJrIjoiZWYxNjU4YTctMzMyMy00YWI0LTg0OWUtOTAzNmYyMjY3NmU1IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),

( 'gestao_retencao_bi', 'BI/BI', 'vis_gestao_retencao_bi', 'nao', @menu_retencao, 'dashboard', 999, 'ativo', 'Gestão', 'Management', 'https://app.powerbi.com/view?r=eyJrIjoiZWIzZDZjZWEtNzIzNS00ZmYzLThlY2MtNmJkNzdhYjlhOWQ5IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'churn_bi', 'BI/BI', 'vis_churn_bi', 'nao', @menu_retencao, 'dashboard', 999, 'ativo', 'Churn', 'Churn', 'https://app.powerbi.com/view?r=eyJrIjoiYjc0NDJiOTEtMGJkZi00NDc2LWIxODUtOTE2ZjZkZTQ5MjdjIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'chatbot_bi', 'BI/BI', 'vis_chatbot_bi', 'nao', @menu_atendimento_suporte, 'dashboard', 999, 'ativo', 'Chatbot', 'Chatbot', 'https://app.powerbi.com/view?r=eyJrIjoiN2I0NmJiNGItZTFkNC00ODQ3LThlMGQtMGJlMzM2Y2Q5NTgxIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'chatbot_crm_bi', 'BI/BI', 'vis_chatbot_crm_bi', 'nao', @menu_atendimento_suporte, 'dashboard', 999, 'ativo', 'Chatbot CRM', 'Chatbot CRM', 'https://app.powerbi.com/view?r=eyJrIjoiNDQxZWM4YzYtOGQ3ZS00NzhjLTlmYjQtYzIxM2FhY2JiYTRiIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'homologacao_bi', 'BI/BI', 'vis_homologacao_bi', 'nao', @menu_atendimento_suporte, 'dashboard', 999, 'ativo', 'Homologação', 'Homologation', 'https://app.powerbi.com/view?r=eyJrIjoiMjIwOTIzYjgtNGVkMy00ZTU4LTk2NzItYWNkOGU0OThmN2U0IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'monitoria_bi', 'BI/BI', 'vis_monitoria_bi', 'nao', @menu_atendimento_suporte, 'dashboard', 999, 'ativo', 'Monitoria', 'Monitoring', 'https://app.powerbi.com/view?r=eyJrIjoiOWIxZDZjYWItNTZmMC00NDdhLWI3ZWItZjhhZDM2OGJkY2RmIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),

( 'treinamento_bi', 'BI/BI', 'vis_treinamento_bi', 'nao', @menu_atendimento_suporte, 'dashboard', 999, 'ativo', 'Treinamento', 'Training', 'https://app.powerbi.com/view?r=eyJrIjoiNGZjMjM4NjktNGJkYS00NGY2LWFlNTAtNWVhMmQ4MzZhZjIxIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9'),
( 'atendimentos_cidade_bi', 'BI/BI', 'vis_atendimentos_cidade_bi', 'nao', @menu_bi, 'dashboard', 999, 'ativo', 'Atendimentos por Cidade', 'Services by City', 'https://app.powerbi.com/view?r=eyJrIjoiMDExZTE3N2YtNDA1Ni00MjcwLTk2NDMtZDAxNmRiOGQ3N2Y2IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9');
