-- CRIAÇÃO Permissão
INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo)
VALUES ('Associação de Rastreador LoRa', 'vis_associacao_lora', '1', 'Administrativo LoRa');

-- SUBMENU Associação LoRa
INSERT INTO showtecsystem.menu
(nome, caminho, codigo_permissao, filhos, id_pai, ordem, status, lang_pt, lang_en)
VALUES
('associacao_lora', 'Pcp/associacaoLora', 'vis_associacao_lora', 'nao', (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'LoRa'), 999, 'ativo', 'Associação LoRa', 'LoRa Association');