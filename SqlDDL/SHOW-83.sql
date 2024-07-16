/* Remove a tabela do esquema antigo*/
DROP TABLE `showtecsystem`.`infobip_agentes_status`;

/* Cria novo esquema */
CREATE SCHEMA `infobip` DEFAULT CHARACTER SET utf8mb4 ;

/* Cria a antiga tabela no novo esquema */
CREATE TABLE `agentes_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome_formatado` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pai` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `em_conversa` enum('sim','nao') COLLATE utf8mb4_unicode_ci DEFAULT 'nao',
  `em_pausa` enum('sim','nao') COLLATE utf8mb4_unicode_ci DEFAULT 'nao',
  `icone_disponibilidade` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempo_pausa` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Status de agentes da plataforma Infobip'

INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Available', 'Disponível', 'Os agentes estão conectados e disponíveis para conversas adicionais.', 'sim', 'Available');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Available (mobile)', 'Disponível (móvel)', 'Os agentes estão conectados a um aplicativo móvel e disponíveis para conversas adicionais.', 'sim', 'Available');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Idle', 'Ocioso', 'Os agentes serão definidos automaticamente com esse status quando não tiverem nenhuma conversa atribuída.', 'nao', 'Available');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Idle (mobile)', 'Ocioso (móvel)', 'Os agentes estão conectados a um aplicativo móvel e serão automaticamente definidos com esse status quando não tiverem nenhuma conversa atribuída.', 'nao', 'Available');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Under capacity', 'Abaixo da capacidade', 'Os agentes serão definidos automaticamente com esse status quando tiverem conversas atribuídas, mas eles podem lidar com mais trabalho.', 'sim', 'Available');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Under capacity (mobile)', 'Abaixo da capacidade (móvel)', 'Os agentes estão conectados a um aplicativo móvel e serão automaticamente configurados com esse status quando tiverem conversas atribuídas, mas eles podem lidar com mais trabalho.', 'sim', 'Available');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Busy', 'Ocupado', 'Os agentes não receberão novas conversas, mas podem aceitar conversas transferidas.', 'sim', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Full capacity', 'Capacidade total', 'Os agentes serão automaticamente definidos com este status quando sua carga de trabalho for igual à sua capacidade.', 'sim', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Full capacity (mobile)', 'Capacidade total (móvel)', 'Os agentes estão conectados a um aplicativo móvel e serão definidos automaticamente com esse status quando sua carga de trabalho for igual à sua capacidade.', 'sim', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('In a call', 'Em uma chamada', 'Os agentes serão automaticamente definidos com este status quando estiverem em uma chamada.', 'sim', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Ringing', 'Recebendo chamada', 'Os agentes serão automaticamente definidos com esse status quando estiverem recebendo uma chamada.', 'sim', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa Particular', 'Pausa Particular', 'Utilizado para o colaborador usar em saídas menores a 5 minutos.', 'nao', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pré Pausa', 'Pré Pausa', 'Sinalização usada antes da pausa oficial', 'nao', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Retenção', 'Retenção', 'Utilizado para os agentes de retenção para contato ativo.', 'nao', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Wrapping', 'Encerrando chamada', 'Os agentes serão definidos automaticamente com esse status quando estiverem encerrando a chamada.', 'sim', 'Busy');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa 20 minutos', 'Pausa 20 minutos', 'Os agentes estão logados no sistema, mas não estão disponíveis para tomar conversas. Usado principalmente para intervalos.', 'nao', 'Away');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Almoço', 'Almoço', 'Pausa para refeição.', 'nao', 'Away');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa 10 minutos', 'Pausa 10 minutos', 'Pausa de 10 minutos usada para escala 6x1.', 'nao', 'Away');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Pausa 15 minutos', 'Pausa 15 minutos', 'Pausa de 15 minutos usada para escala guarabira.', 'nao', 'Away');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Treinamento', 'Treinamento', 'Pausa referente a treinamento.', 'nao', 'Away');
INSERT INTO `infobip`.`agentes_status` (`nome`, `nome_formatado`, `descricao`, `em_conversa`, `status_pai`) VALUES ('Offline', 'Offline', 'Agents are signed out.', 'nao', 'Offline');



UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'no_accounts' WHERE (`id` = '21');

UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '3');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '4');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '12');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '13');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '14');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '16');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '17');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '18');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '19');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '20');
UPDATE `infobip`.`agentes_status` SET `em_conversa` = 'nao' WHERE (`id` = '21');


UPDATE `infobip`.`agentes_status` SET `em_pausa` = 'sim' WHERE (`id` = '12');
UPDATE `infobip`.`agentes_status` SET `em_pausa` = 'sim' WHERE (`id` = '13');
UPDATE `infobip`.`agentes_status` SET `em_pausa` = 'sim' WHERE (`id` = '16');
UPDATE `infobip`.`agentes_status` SET `em_pausa` = 'sim' WHERE (`id` = '17');
UPDATE `infobip`.`agentes_status` SET `em_pausa` = 'sim' WHERE (`id` = '18');
UPDATE `infobip`.`agentes_status` SET `em_pausa` = 'sim' WHERE (`id` = '19');


UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'restaurant', `tempo_pausa` = '01:00:00' WHERE (`id` = '17');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'timer', `tempo_pausa` = '00:10:00' WHERE (`id` = '18');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'timer', `tempo_pausa` = '00:15:00' WHERE (`id` = '19');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'wc', `tempo_pausa` = '00:05:00' WHERE (`id` = '12');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'motion_photos_pause', `tempo_pausa` = '00:05:00' WHERE (`id` = '13');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'lunch_dining', `tempo_pausa` = '00:20:00' WHERE (`id` = '16');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'school' WHERE (`id` = '20');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'lock' WHERE (`id` = '7');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '1');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '2');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '5');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '6');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'support_agent' WHERE (`id` = '10');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'support_agent' WHERE (`id` = '11');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'support_agent' WHERE (`id` = '15');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '8');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '9');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '3');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'call' WHERE (`id` = '4');
UPDATE `infobip`.`agentes_status` SET `icone_disponibilidade` = 'lock' WHERE (`id` = '14');

CREATE TABLE `infobip`.`atendimentos_nao_atribuidos_status` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `icone` VARCHAR(45) NOT NULL,
  `tempo_inicial_minutos` INT NOT NULL,
  `tempo_final_minutos` INT NOT NULL,
  PRIMARY KEY (`id`));

INSERT INTO `infobip`.`atendimentos_nao_atribuidos_status` (`icone`, `tempo_inicial_minutos`, `tempo_final_minutos`) VALUES ('success', '0', '1');
INSERT INTO `infobip`.`atendimentos_nao_atribuidos_status` (`icone`, `tempo_inicial_minutos`, `tempo_final_minutos`) VALUES ('warning', '1', '2');
INSERT INTO `infobip`.`atendimentos_nao_atribuidos_status` (`icone`, `tempo_inicial_minutos`, `tempo_final_minutos`) VALUES ('danger', '2', '999999999');

CREATE TABLE `infobip`.`filas_grupos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`));

INSERT INTO `infobip`.`filas_grupos` (`nome`) VALUES ('Agendamento');
INSERT INTO `infobip`.`filas_grupos` (`nome`) VALUES ('Ativação');
INSERT INTO `infobip`.`filas_grupos` (`nome`) VALUES ('Suporte');
INSERT INTO `infobip`.`filas_grupos` (`nome`) VALUES ('Financeiro');
INSERT INTO `infobip`.`filas_grupos` (`nome`) VALUES ('Vendas');

CREATE TABLE `infobip`.`canais_tipos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `icone` VARCHAR(45) NOT NULL,
  `tempo_medio_atendimento` TIME NOT NULL,
  PRIMARY KEY (`id`));

INSERT INTO `infobip`.`canais_tipos` (`nome`, `icone`, `tempo_medio_atendimento`) VALUES ('Chat', 'question_answer', '00:10:00');
INSERT INTO `infobip`.`canais_tipos` (`nome`, `icone`, `tempo_medio_atendimento`) VALUES ('Whats', 'whatsapp', '00:10:00');
INSERT INTO `infobip`.`canais_tipos` (`nome`, `icone`, `tempo_medio_atendimento`) VALUES ('Voz', 'phone_in_talk', '00:10:00');
INSERT INTO `infobip`.`canais_tipos` (`nome`, `icone`, `tempo_medio_atendimento`) VALUES ('Redes Sociais', 'apps', '00:10:00');
INSERT INTO `infobip`.`canais_tipos` (`nome`, `icone`, `tempo_medio_atendimento`) VALUES ('Chat/Whats', 'question_answer', '00:10:00');

CREATE TABLE `infobip`.`filas_grupos_x_filas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_filas_grupos` INT UNSIGNED NOT NULL,
  `codigo_filas` VARCHAR(150) NOT NULL,
  `status` ENUM('ativo', 'inativo') NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`));

  ALTER TABLE `infobip`.`filas_grupos_x_filas` 
ADD INDEX `idx_fk__filas_grupos_x_filas__id_filas_grupos__filas_grupos` (`id_filas_grupos` ASC);
;
ALTER TABLE `infobip`.`filas_grupos_x_filas` 
ADD CONSTRAINT `fk__filas_grupos_x_filas__id_filas_grupos__filas_grupos`
  FOREIGN KEY (`id_filas_grupos`)
  REFERENCES `infobip`.`filas_grupos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;