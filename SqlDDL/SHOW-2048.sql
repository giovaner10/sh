
INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'cadastros_logistica',
  NULL,
  'logistica_shownet',
  'sim',
  id,
  'textsms',
  999,
  'ativo',
  'Cadastros Logística',
  'Logistics Registers'
FROM showtecsystem.menu
WHERE nome = 'Logistica';

SET @cadastros_logistica_id = (select ID from showtecsystem.menu where nome = 'cadastros_logistica')
UPDATE showtecsystem.menu SET id_pai = @cadastros_logistica_id WHERE nome = 'empresas'
UPDATE showtecsystem.menu SET id_pai = @cadastros_logistica_id WHERE nome = 'setores'
UPDATE showtecsystem.menu SET id_pai = @cadastros_logistica_id WHERE nome = 'transportadores'
UPDATE showtecsystem.menu SET id_pai = @cadastros_logistica_id WHERE nome = 'insumos'
UPDATE showtecsystem.menu SET id_pai = @cadastros_logistica_id WHERE nome = 'equipamentosExpedicao'

UPDATE showtecsystem.menu SET nome = 'cadastros_empresas', lang_pt = 'Cadastro de Empresas', lang_en = 'Company Registration' WHERE nome = 'empresas'
UPDATE showtecsystem.menu SET nome = 'cadastros_setores', lang_pt = 'Cadastro de Setores', lang_en = 'Sector Registration' WHERE nome = 'setores'
UPDATE showtecsystem.menu SET nome = 'cadastros_transportadores', lang_pt = 'Cadastro de Transportadores', lang_en = 'Transporter Registration' WHERE nome = 'transportadores'
UPDATE showtecsystem.menu SET nome = 'cadastros_insumos', lang_pt = 'Cadastro de Insumos', lang_en = 'Input Registration' WHERE nome = 'insumos'
UPDATE showtecsystem.menu SET nome = 'cadastros_equipamentosExpedicao', lang_pt = 'Cadastro de Equipamentos de Expedição', lang_en = 'Shipping Equipment Registration' WHERE nome = 'equipamentosExpedicao'