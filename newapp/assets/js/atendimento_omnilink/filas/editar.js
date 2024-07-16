$(document).on('click', '.editar-fila', function (e) {
	e.preventDefault();

	resetarCamposModalFila();
	
	const botao = $(this);
	const id = botao.attr('data-id');

	botao
		.attr('disabled', true)
		.html('Carregando...');

	const data = buscarNodeNaAgGrid(id).data;
	// Preenche os campos do modal
	$('#nome').val(data.nome);
	$('#descricao').val(data.descricao);
	$('#dia_inicial').val(data.diaInicial);
	$('#dia_final').val(data.diaFinal);
	$('#horario_inicial').val(data.horarioInicial);
	$('#horario_final').val(data.horarioFinal);


	// Configura o modal para editar
	$('#btn-salvar-fila').attr('data-id', id);

	$('#titulo-modal-fila').text('Editar Fila');
	$('#btn-salvar-fila').attr('data-acao', 'editar');

	$('#modal-cadastrar-editar-fila').modal('show');

	botao
		.attr('disabled', false)
		.html('Editar');
});
