ALTER TABLE `portal_compras`.`solicitacoes`
ADD COLUMN `acao_aprovadores` JSON NULL DEFAULT NULL AFTER `anexo_solicitacao`;