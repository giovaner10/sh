INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
SELECT 
  'auditoria_agendamentos',
  NULL,
  'vis_agendamento_auditoria',
  'sim',
  id,
  'textsms',
  999,
  'ativo',
  'Agendamento',
  'Scheduling'
FROM showtecsystem.menu
WHERE nome = 'menu_auditoria';


SET @auditoria_agendamentos_id = (select ID from showtecsystem.menu where nome = 'auditoria_agendamentos')
UPDATE showtecsystem.menu 
    SET id_pai = @auditoria_agendamentos_id, 
    nome = 'agendamentos_instalacao', 
    caminho = 'Auditoria/Agendamento/AgendamentoInstalacao', 
    lang_pt = 'Agendamento de Instalação', 
    lang_en = 'Installation Scheduling' 
WHERE caminho  = 'Auditoria/Agendamento'


