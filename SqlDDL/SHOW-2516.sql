INSERT INTO showtecsystem.cad_permissoes_funcionarios
(descricao, cod_permissao, status, modulo) 
VALUES('Painel de Ativação', 'vis_painel_ativacao', 1, 'Painel de Ativação');

INSERT INTO showtecsystem.menu
(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('Painel de Ativação', 'PainelAtivacao', 'vis_painel_ativacao', 'nao', NULL, 'summarize', 1, 'ativo', 'Painel de Ativação', 'Activation Panel', NULL);