INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('processos_atendimento_ativacao_bi', 'vis_processos_atendimento_ativacao_bi', 1, 'Processos de Atendimento')

SET @processos_atendimento = (select ID from showtecsystem.menu where lang_pt like 'Processos de Atendimento' Limit 1);
 
INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'processos_atendimento_ativacao_bi', 'ProcessosAtendimento/ativacao', 'vis_processos_atendimento_ativacao_bi', 'nao', @processos_atendimento, 'dashboard', 999, 'ativo', 'Ativação', 'Activation', '')



CREATE TABLE showtecsystem.processos_ativacao (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `processo` varchar(100) DEFAULT NULL, 
  `id_arquivo` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1 COMMENT='Tabela contendo os processos em shownet';