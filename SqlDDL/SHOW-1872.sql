SET @resultados_operacoes_bi = (select ID from showtecsystem.menu where lang_pt like 'Resultado de Operações' Limit 1);
SET @atividade_servico_bi = (select ID from showtecsystem.menu where nome = 'atividade_servico_bi' Limit 1);
SET @segmentacao_clientes_bi = (select ID from showtecsystem.menu where nome = 'segmentacao_clientes_bi' Limit 1);
SET @painel_indisponibilidade_bi = (select ID from showtecsystem.menu where nome = 'painel_indisponibilidade_bi' Limit 1);

UPDATE showtecsystem.menu SET status = 'inativo' WHERE id = @resultados_operacoes_bi;
UPDATE showtecsystem.menu SET lang_pt = 'Backlog - Atividade de Serviço', lang_en = 'Backlog - Service Activity' WHERE id = @atividade_servico_bi;
UPDATE showtecsystem.menu SET lang_pt = 'Segmentação Clientes', lang_en = 'Customer Segmentation' WHERE id = @segmentacao_clientes_bi;
UPDATE showtecsystem.menu SET status = 'inativo' WHERE id = @painel_indisponibilidade_bi;

UPDATE showtecsystem.menu SET link_bi = 'https://app.powerbi.com/view?r=eyJrIjoiMTk4NzdmOGEtNTk1OC00ZjViLWExMDUtNDBlN2ViZTQyMTg0IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9' WHERE (nome = 'telemetria_can_bi');

