UPDATE showtecsystem.menu
	SET status='inativo'
	WHERE codigo_permissao = 'contas_pneushow';
	
UPDATE showtecsystem.cad_permissoes_funcionarios
	SET status='0'
	WHERE cod_permissao = 'contas_pneushow';
