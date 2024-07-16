INSERT INTO showtecsystem.menu
	(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status)
values
	('pcp', null, 'vis_suporte', 'sim', null, 'textsms', 151, 'ativo')

INSERT INTO showtecsystem.menu
	(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status)
values
	('pcp_iscas', 'Pcp/iscas', 'vis_suporte', 'sim', 166, 'textsms', 999, 'ativo')