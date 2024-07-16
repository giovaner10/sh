-- Altera tamanho da coluna descrição
ALTER TABLE `showtecsystem`.`cad_permissoes_funcionarios` CHANGE COLUMN `descricao` `descricao` VARCHAR(100) NOT NULL ;
-- Insere permissão
INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (`descricao`, `cod_permissao`, `status`, `modulo`) VALUES ('Alterar Informações Itens de Contrato (SAC Omnilink)', 'out_alterarInfoItensContratoSacOmnilink', '1', 'Suporte');
