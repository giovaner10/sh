INSERT INTO showtecsystem.menu
(nome, caminho, codigo_permissao, filhos, id_pai, ordem, status, lang_pt, lang_en)
VALUES
('formularios', 'ControlesQualidades/Formularios', 'vis_controledequalidadeinformacaogeral', 'nao', (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'controle_de_qualidade'), 999, 'ativo', 'Formulários', 'Forms');