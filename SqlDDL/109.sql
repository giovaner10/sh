ALTER TABLE `portal_compras`.`solicitacoes`
CHANGE COLUMN `filial` `filial` ENUM('matriz', 'santa_rita') NOT NULL DEFAULT 'matriz';

