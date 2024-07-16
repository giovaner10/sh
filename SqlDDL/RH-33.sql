-- Lembrar de verificar o ID do menu que vai ser atualizado em produção
UPDATE showtecsystem.menu
SET nome='RH', caminho='rh', filhos='sim', lang_pt='RH', lang_en='HR'
WHERE id=40;

-- Executar depois do Update e trocar pelo id_pai correto(Do registro atualizado anteriormente)
INSERT INTO showtecsystem.menu
(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('tipos_de_documento', 'documentos/TiposDocumento', 'usuarios_visualiza', 'nao', 40, 'badge', 10, 'ativo', 'Tipos de Documento', 'Document Types', NULL),
('funcionarios', 'usuarios', 'usuarios_visualiza', 'nao', 40, 'badge', 10, 'ativo', 'Funcionários', 'Employees', NULL);