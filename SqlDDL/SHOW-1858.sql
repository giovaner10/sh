CREATE TABLE `showtecsystem`.`processos_atendimento_reclame_aqui` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_reclame_aqui` varchar(100) DEFAULT NULL,
  `id_arquivo` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT = 'Tabela contendo os documentos de reclame aqui dos processos de atendimento';

SET @menu_processos_atendimento = (select id from showtecsystem.menu where nome = 'processos_atendimento');

INSERT INTO showtecsystem.menu(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES('reclame_aqui_processos_atendimento', 'ProcessosAtendimento/ReclameAqui', 'vis_reclame_aqui_processos_atendimento', 'nao', @menu_processos_atendimento, 'summarize', 999, 'ativo', 'Reclame Aqui', 'Complain Here');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Reclame Aqui', 'vis_reclame_aqui_processos_atendimento', 1, 'Processos de Atendimento');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Editar Reclame Aqui', 'edi_reclame_aqui_processos_atendimento', 1, 'Processos de Atendimento');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Excluir Reclame Aqui', 'del_reclame_aqui_processos_atendimento', 1, 'Processos de Atendimento');