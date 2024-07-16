ALTER TABLE `portal_compras`.`log_solicitacoes` 
CHANGE COLUMN `acao` `acao` ENUM('cadastrar', 'remover', 'editar', 'adicionar_cotacao', 'adicionar_produto_cotacao', 'selecionar_cotacao', 'aprovar', 'reprovar', 'comentar', 'adicionar_boleto', 'adicionar_pre_nota') NOT NULL DEFAULT 'cadastrar' ;
