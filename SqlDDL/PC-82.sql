ALTER TABLE `portal_compras`.`produtos`
ADD UNIQUE INDEX `nome_UNIQUE` (`nome` ASC);
;

INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Cadastrar Produtos - Portal Compras', 'cad_produtos_portal_compras', '1', 'Portal de Compras');