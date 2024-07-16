CREATE TABLE `showtecsystem`.`processos_atendimento_key_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_key_account` varchar(100) DEFAULT NULL,
  `id_arquivo` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT = 'Tabela contendo os documentos de key account dos processos de atendimento';

SET @menu_processos_atendimento = (select id from showtecsystem.menu where nome = 'processos_atendimento');

INSERT INTO showtecsystem.menu(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES('key_account_processos_atendimento', 'ProcessosAtendimento/KeyAccount', 'vis_key_account_processos_atendimento', 'nao', @menu_processos_atendimento, 'summarize', 999, 'ativo', 'Key Account', 'Key Account');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Key Account', 'vis_key_account_processos_atendimento', 1, 'Processos de Atendimento');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Editar Key Account', 'edi_key_account_processos_atendimento', 1, 'Processos de Atendimento');

INSERT INTO showtecsystem.cad_permissoes_funcionarios(descricao, cod_permissao, status, modulo) 
VALUES ('Excluir Key Account', 'del_key_account_processos_atendimento', 1, 'Processos de Atendimento');