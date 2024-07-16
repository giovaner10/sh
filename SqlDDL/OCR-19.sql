INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('vis_menu_ocr', 'vis_menu_ocr', 1, 'OCR')

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'OCR', NULL, 'vis_menu_ocr', 'sim', NULL, 'web', 999, 'ativo', 'OCR', 'OCR', '')

SET @OCR = (select id from showtecsystem.menu where nome = 'OCR');

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'Dados Gerenciamento', 'OCR/DadosGerenciamentoOCR', 'vis_menu_ocr', 'nao', @OCR, 'web', 999, 'ativo', 'Dados Gerenciamento', 'Dados Gerenciamento', '')

