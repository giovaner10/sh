INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('configurador_omnisafe', 'OCR/DadosGerenciamentoOCR/Omnisafe', 'vis_configurador_omnisafe', 'nao', 292, 'web', 999, 'ativo', 'Configurador Omnisafe', 'Omnisafe Configurator', null);

INSERT INTO showtecsystem.cad_permissoes_funcionarios
(descricao, cod_permissao, status, modulo)
VALUES('Configurador Omnisafe', 'vis_configurador_omnisafe', '1', 'Configurador Omnisafe');