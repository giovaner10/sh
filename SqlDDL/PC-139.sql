INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo)
VALUES('Incluir Boleto - Portal Compras', 'cad_boleto_portal_compras', '1', 'Portal de Compras');

ALTER TABLE `portal_compras`.`solicitacoes` 
CHANGE COLUMN `situacao` `situacao` ENUM('aguardando_produto_cotacao', 'aguardando_cotacao', 'aguardando_confirmacao_cotacao', 'aguardando_aprovacao', 'aprovado', 'reprovado', 'aguardando_pre_nota', 'aguardando_fiscal', 'aguardando_boleto', 'finalizado') NOT NULL DEFAULT 'aguardando_cotacao' ;

ALTER TABLE `portal_compras`.`solicitacoes` 
ADD COLUMN `anexo_boleto` VARCHAR(120) NULL DEFAULT NULL AFTER `codigo_pedido_erp`;
