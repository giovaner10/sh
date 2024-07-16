CREATE TABLE `showtecsystem`.`processos_atendimento_agendamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_agendamento` varchar(100) DEFAULT NULL,
  `id_arquivo` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT = 'Tabela contendo os agendamentos dos processos de atendimento';

INSERT INTO showtecsystem.menu(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en) 
VALUES('processos_atendimento', NULL, 'processos_atendimento', 'sim', NULL, 'summarize', 999, 'ativo', 'Processos de Atendimento', 'Customer Service Processes');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Processos de Atendimento', 'processos_atendimento', 1, 'Processos de Atendimento');

SET @menu_processos_atendimento = (select id from showtecsystem.menu where nome = 'processos_atendimento');

INSERT INTO showtecsystem.menu(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES('agendamento_processos_atendimento', 'ProcessosAtendimento/agendamento', 'vis_agendamento_processos_atendimento', 'nao', @menu_processos_atendimento, 'summarize', 999, 'ativo', 'Agendamento', 'Scheduling');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Agendamento', 'vis_agendamento_processos_atendimento', 1, 'Processos de Atendimento');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Editar Agendamento', 'edi_agendamento_processos_atendimento', 1, 'Processos de Atendimento');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Excluir Agendamento', 'del_agendamento_processos_atendimento', 1, 'Processos de Atendimento');