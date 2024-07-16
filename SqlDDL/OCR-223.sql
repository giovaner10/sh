
SET @OCR = (select id from showtecsystem.menu where nome = 'OCR');

UPDATE showtecsystem.menu
SET lang_pt='Gerenciamento de dados', lang_en='Data Management'
WHERE caminho='OCR/DadosGerenciamentoOCR'


INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'CadastrosOCR', 'OCR/DadosGerenciamentoOCR/Cadastros', 'vis_menu_ocr', 'nao', @OCR, 'web', 999, 'ativo', 'Cadastros', 'Registration', '')
