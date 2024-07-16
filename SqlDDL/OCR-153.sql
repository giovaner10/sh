
SET @OCR = (select id from showtecsystem.menu where nome = 'OCR');

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'DashboardOCR', 'OCR/Dashboard', 'vis_menu_ocr', 'nao', @OCR, 'web', 999, 'ativo', 'Dashboard', 'Dashboard', '')
