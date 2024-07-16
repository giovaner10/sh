INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('monitoramento_uso_tecnologias', null, 'vis_menu_uso_tecnologias', 'sim', 'score', 11, 'ativo', 'Monitoramento de Uso das Tecnologias', 'Technology Use Monitoring', null);

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('dashboard_monitoramento_tecnologias', 'MonitoramentoUsoTecnologias/Dashboard', 'vis_menu_uso_tecnologias', 'nao', LAST_INSERT_ID(), 'dashboard', 999, 'Dashboard', 'Dashboard', null);

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, data_cad, modulo)
VALUES ("Monitoramento de Uso das Tecnologias", "vis_menu_uso_tecnologias",1,Now(),"Dashboards");