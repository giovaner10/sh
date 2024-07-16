INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, data_cad, modulo)
VALUES ("Autorização de Compras", "vis_autorizacao_compras",1,Now(),"Autorização de Compras");

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('autorizacao_de_compras', null, 'vis_autorizacao_compras', 'sim', null, 'portal_compras', 11, 'ativo', 'Autorização de Compras', 'Purchase Authorization', null);

SET @autorizacao_de_compras = (select ID from showtecsystem.menu  where nome = 'autorizacao_de_compras' limit 0,1);

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('autorizacoes_pendentes', 'AutorizacaoDeCompras/AutorizacaoDeCompras', 'vis_autorizacao_compras', 'nao', @autorizacao_de_compras, 'portal_compras', 999, 'ativo', 'Autorizações Pendentes', 'Pending Authorizations', null);

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('autorizadores_cadastrados', 'AutorizacaoDeCompras/AutorizacaoDeCompras/AutorizadoresCadastrados', 'vis_autorizacao_compras', 'nao', @autorizacao_de_compras, 'portal_compras', 999, 'ativo', 'Autorizadores Cadastrados', 'Registered Authorizers', null);