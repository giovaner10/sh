INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`descricao`, `cod_permissao`, `modulo`) 
VALUES ('Produtos (Gestor)', 'cad_produtosgestor', 'Cadastros');

ALTER TABLE `showtecsystem`.`cad_clientes` 
ADD COLUMN `ids_produtos` JSON NULL DEFAULT NULL AFTER `id_produto`;


-- copia o id do produto do cliente para o novo campo
UPDATE showtecsystem.cad_clientes SET ids_produtos = CONCAT("[", id_produto, "]");

