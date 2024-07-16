INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios`
(descricao, cod_permissao, status, modulo)
VALUES('Visualizar Clientes - Televendas', 'vis_clientestelevendas', '1', 'Televendas');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios`
(descricao, cod_permissao, status, modulo)
VALUES('Televendas', 'vis_televendas', '1', 'Televendas');

INSERT INTO `showtecsystem`.`menu`
(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('televendas', 'ComerciaisTelevendas/NewPedidos', 'vis_televendas', 'sim', NULL, 'score', 11, 'ativo', 'televendas', 'televendas', NULL);