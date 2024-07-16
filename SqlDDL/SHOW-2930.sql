-- SUBMENU LoRa
INSERT INTO showtecsystem.menu
(nome, codigo_permissao, filhos, id_pai, ordem, status, lang_pt, lang_en)
VALUES
('LoRa', 'vis_lora', 'sim', (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'pcp'), 999, 'ativo', 'LoRa', 'LoRa');


-- Update para 'pcp_lora'
UPDATE showtecsystem.menu 
SET id_pai = (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'LoRa')
WHERE nome = 'pcp_lora';

-- Update para 'pcp_comandos_lora'
UPDATE showtecsystem.menu 
SET id_pai = (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'LoRa')
WHERE nome = 'pcp_comandos_lora';


-- Submenu Iscas

INSERT INTO showtecsystem.menu
(nome, codigo_permissao, filhos, id_pai, ordem, status, lang_pt, lang_en)
VALUES
('submenu_iscas', 'vis_suporte', 'sim', (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'pcp'), 999, 'ativo', 'Iscas', 'Baits');


-- Update para 'pcp_lora'
UPDATE showtecsystem.menu 
SET id_pai = (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'submenu_iscas')
WHERE nome = 'pcp_iscas';

-- Update para 'pcp_comandos_lora'
UPDATE showtecsystem.menu 
SET id_pai = (SELECT id FROM (SELECT * FROM showtecsystem.menu) AS temp WHERE nome = 'submenu_iscas')
WHERE nome = 'pcp_iscas_cliente';