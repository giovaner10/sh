/* Insert de nova permissão para o módulo de vendas de software - Acesso ao menu Clientes */

INSERT INTO showtecsystem.cad_permissoes_funcionarios
(descricao, cod_permissao, status, modulo)
VALUES('Vendas de Software - Acesso Menu Clientes', 'vis_vendas_de_software_clientes', '1', 'Comercial e Televendas');