ALTER TABLE `portal_compras`.`solicitacoes`
CHANGE COLUMN `situacao` `situacao` ENUM(
    'aguardando_produto_cotacao', 'aguardando_cotacao', 'aguardando_confirmacao_cotacao', 'aguardando_aprovacao', 'aprovado', 'reprovado'
) NOT NULL DEFAULT 'aguardando_cotacao';