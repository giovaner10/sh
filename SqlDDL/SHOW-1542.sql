INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('menu_auditoria', 'vis_menu_auditoria', 1, 'Auditoria'),
('agendamento_auditoria', 'vis_agendamento_auditoria', 1, 'Auditoria');

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'menu_auditoria', null , 'vis_menu_auditoria', 'sim', null, 'summarize', 186, 'ativo', 'Auditoria', 'Audit', null);

SET @menu_auditoria = LAST_INSERT_ID();


INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'agendamento_auditoria', 'Auditoria/Agendamento' , 'vis_agendamento_auditoria', 'nao', @menu_auditoria, 'summarize', 999, 'ativo', 'Agendamento', 'Scheduling', null);
