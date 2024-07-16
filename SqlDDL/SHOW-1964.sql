INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES
('Comissionamento - Principal', 'vis_principal_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Campanhas', 'vis_campanhas_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Estorno Vendas', 'vis_estorno_vendas_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Regionais', 'vis_regionais_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Cargo', 'vis_cargo_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Vendedores', 'vis_vendedores_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Comissões Calculadas', 'vis_comissoes_calculadas_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Configuração Cálculo', 'vis_configuracao_calculo_comissionamento', 1, 'Comissionamento'),
('Comissionamento - Cénarios de Vendas', 'vis_cenarios_de_vendas_comissionamento', 1, 'Comissionamento');

UPDATE showtecsystem.cad_permissoes_funcionarios 
SET descricao  = 'Comissionamento - Vendas Comissionadas', cod_permissao = 'vis_vendas_comissionadas_comissionamento'
WHERE descricao  = 'vendas_comissionadas';

UPDATE showtecsystem.menu SET codigo_permissao = 'vis_campanhas_comissionamento' WHERE nome  = 'campanhas';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_estorno_vendas_comissionamento' WHERE nome  = 'estorno_vendas';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_regionais_comissionamento' WHERE nome  = 'regionais';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_cargo_comissionamento' WHERE nome  = 'cargo';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_vendedores_comissionamento' WHERE nome  = 'vendedores';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_comissoes_calculadas_comissionamento' WHERE nome  = 'comissoes_calculadas';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_configuracao_calculo_comissionamento' WHERE nome  = 'configuracao_calculo_comissao';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_cenarios_de_vendas_comissionamento' WHERE nome  = 'cenarios_de_vendas';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_vendas_comissionadas_comissionamento' WHERE nome  = 'vendas_comissionadas';
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_principal_comissionamento' WHERE nome  = 'comissionamento_vendas';

