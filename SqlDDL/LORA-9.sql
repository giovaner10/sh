INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo)
VALUES ('Comandos Rastreador LoRa', 'vis_comandos_lora', '1', 'Administrativo LoRa');

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES ('pcp_comandos_lora', 'Pcp/comandosLora', 'vis_comandos_lora', 'nao', 
(SELECT id FROM (SELECT id FROM showtecsystem.menu WHERE nome = 'pcp') AS temp), 'textsms', 999, 'ativo', 'Comandos LoRa', 'LoRa commands', null);