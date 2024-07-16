-- Lembrar de verificar o ID do menu que vai ser atualizado em produção
UPDATE showtecsystem.menu
SET nome='relatorio_instalacao_consolidado', lang_pt='Relatório de Instalação Consolidado', lang_en='Consolidated Installation Report'
WHERE nome='relatorio_instalacao';

UPDATE showtecsystem.menu
SET nome='relatorio_manutencao_consolidado', lang_pt='Relatório de Manutenção Consolidado', lang_en='Consolidated Maintenance Report'
WHERE nome='relatorio_manutencao';

-- Antes de executar, verificar o id_pai correto!
INSERT INTO showtecsystem.menu
(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('relatorio_instalacao_detalhado', 'Auditoria/Agendamento/relatorioAgendamentoDetalhado', 'vis_relatorio_instalacao', 'nao', 250, 'badge', 10, 'ativo', 'Relatório de Instalação Detalhado', 'Consolidated Installation Report', NULL);