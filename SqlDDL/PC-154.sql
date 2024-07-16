ALTER TABLE `portal_compras`.`solicitacoes`
ADD COLUMN `tipo` ENUM('requisicao', 'pedido') NOT NULL DEFAULT 'requisicao' AFTER `id`;
