SET @menu_bi = (select id from showtecsystem.menu where nome = 'menu_bi');

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('command_center_bi', 'vis_command_center_bi', 1, 'BI'),
('gestao_command_center_bi', 'vis_gestao_command_center_bi', 1, 'BI')

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'command_center_bi', NULL, 'vis_command_center_bi', 'sim', @menu_bi, 'dashboard', 999, 'ativo', 'Command Center', 'Command Center', '')


SET @command_center_bi = (select id from showtecsystem.menu where nome = 'command_center_bi');


INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'gestao_command_center_bi', 'BI/BI', 'vis_gestao_command_center_bi', 'nao', @command_center_bi, 'dashboard', 999, 'ativo', 'Gestão', 'Management', 
'https://app.powerbi.com/view?r=eyJrIjoiZTBmODIwNjUtY2MyMC00Y2U3LWI0MzMtY2M3NDQwNWEwZDI1IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9')

