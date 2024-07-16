INSERT INTO showtecsystem.menu
(nome, caminho, codigo_permissao, filhos, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('gente_gestao', '', 'usuarios_visualiza', 'sim', '', 999, 'ativo', 'Gente e Gestão', 'People Management', '');

-- Executar depois do insert e colocar o id_pai igual ao que foi inserido

INSERT INTO showtecsystem.menu
UPDATE showtecsystem.menu
SET id_pai=354
WHERE id=40;