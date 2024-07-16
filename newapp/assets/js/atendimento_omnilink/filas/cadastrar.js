$(document).on('click', '#btn-nova-fila', function (e) {
	e.preventDefault();

	resetarCamposModalFila();
	$('#modal-cadastrar-editar-fila').modal('show');
});