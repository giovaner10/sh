CREATE TABLE `showtecsystem`.`agentes_status` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `nome_formatado` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(100) NULL,
  `em_conversa` ENUM('sim', 'nao') NULL DEFAULT 'nao' ,
  `status_pai` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
COMMENT = 'Status de agentes da plataforma Infobip';

INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Available', 'Disponível', 'Os agentes estão conectados e disponíveis para conversas adicionais.', 'sim', 'Available');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Available (mobile)', 'Disponível (móvel)', 'Os agentes estão conectados a um aplicativo móvel e disponíveis para conversas adicionais.', 'sim', 'Available');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Idle', 'Ocioso', 'Os agentes serão definidos automaticamente com esse status quando não tiverem nenhuma conversa atribuída.', 'nao', 'Available');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Idle (mobile)', 'Ocioso (móvel)', 'Os agentes estão conectados a um aplicativo móvel e serão automaticamente definidos com esse status quando não tiverem nenhuma conversa atribuída.', 'nao', 'Available');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Under capacity', 'Abaixo da capacidade', 'Os agentes serão definidos automaticamente com esse status quando tiverem conversas atribuídas, mas eles podem lidar com mais trabalho.', 'sim', 'Available');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Under capacity (mobile)', 'Abaixo da capacidade (móvel)', 'Os agentes estão conectados a um aplicativo móvel e serão automaticamente configurados com esse status quando tiverem conversas atribuídas, mas eles podem lidar com mais trabalho.', 'sim', 'Available');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Busy', 'Ocupado', 'Os agentes não receberão novas conversas, mas podem aceitar conversas transferidas.', 'sim', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Full capacity', 'Capacidade total', 'Os agentes serão automaticamente definidos com este status quando sua carga de trabalho for igual à sua capacidade.', 'sim', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Full capacity (mobile)', 'Capacidade total (móvel)', 'Os agentes estão conectados a um aplicativo móvel e serão definidos automaticamente com esse status quando sua carga de trabalho for igual à sua capacidade.', 'sim', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('In a call', 'Em uma chamada', 'Os agentes serão automaticamente definidos com este status quando estiverem em uma chamada.', 'sim', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Ringing', 'Recebendo chamada', 'Os agentes serão automaticamente definidos com esse status quando estiverem recebendo uma chamada.', 'sim', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa Particular', 'Pausa Particular', 'Utilizado para o colaborador usar em saídas menores a 5 minutos.', 'nao', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pré Pausa', 'Pré Pausa', 'Sinalização usada antes da pausa oficial', 'nao', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Retenção', 'Retenção', 'Utilizado para os agentes de retenção para contato ativo.', 'nao', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Wrapping', 'Encerrando chamada', 'Os agentes serão definidos automaticamente com esse status quando estiverem encerrando a chamada.', 'sim', 'Busy');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa 20 minutos', 'Pausa 20 minutos', 'Os agentes estão logados no sistema, mas não estão disponíveis para tomar conversas. Usado principalmente para intervalos.', 'nao', 'Away');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Almoço', 'Almoço', 'Pausa para refeição.', 'nao', 'Away');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa 10 minutos', 'Pausa 10 minutos', 'Pausa de 10 minutos usada para escala 6x1.', 'nao', 'Away');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa 15 minutos', 'Pausa 15 minutos', 'Pausa de 15 minutos usada para escala guarabira.', 'nao', 'Away');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Treinamento', 'Treinamento', 'Pausa referente a treinamento.', 'nao', 'Away');
INSERT INTO `showtecsystem`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Offline', 'Offline', 'Agents are signed out.', 'nao', 'Offline');

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`descricao`, `cod_permissao`, `status`, `data_cad`, `modulo`) VALUES ('Painel Infobip', 'vis_painelinfobip', '1', '2021-07-13 12:01:26', 'Suporte');