UPDATE showtecsystem.menu
	SET filhos='nao', caminho='levantamentoPedidos'
	WHERE nome='levantamento_pedidos';


UPDATE showtecsystem.menu
	SET status='inativo'
	WHERE nome='pedidos_gerados';

UPDATE showtecsystem.menu
	SET status='inativo'
	WHERE nome='pedidos_gerados_nf';

UPDATE showtecsystem.menu
	SET status='inativo'
	WHERE nome='pedidos_gerados_nf_amarra_bi';

UPDATE showtecsystem.menu
	SET status='inativo'
	WHERE nome='pedidos_gerados__nf_amarra_bi_expedicao';
