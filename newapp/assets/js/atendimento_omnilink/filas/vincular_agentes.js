$(document).ready(function () {
	// Buscar todos os usuarios shownet e adiciona no select2 agentes-fila
	axios
		.get(`${SITE_URL}/usuarios/listar`)
		.then((response) => {
			let usuarios = [];

			const data = response.data;
			if (data.length > 0) {
				usuarios = data.map(usuario => {
					return {
						id: usuario.id,
						text: usuario.nome
					}
				});
			}

			$('#agentes-fila').select2({
				data: usuarios,
				width: '100%',
				placeholder: 'Selecione os agentes'
			});
		})
		.catch((error) => {
			console.error(error);
		});


	// Busca os numeros de telefone para o select2
	// Buscar todos os usuarios shownet e adiciona no select2 agentes-fila
	axios
		.get(`${SITE_URL}/AtendimentoOmnilink/Filas/listarNumeros`)
		.then((response) => {
			let numeros = [];

			const data = response.data;

			if(data?.status == -1) {
				showAlert('error', data.mensagem);
			}

			if (Array.isArray(data) ) {
				numeros = data?.map(numero => {
					return {
						id: numero,
						text: numero
					}
				});
			}

			$('#numeros-fila').select2({
				data: numeros,
				width: '100%',
				placeholder: 'Selecione os números'
			});
		})
		.catch((error) => {
			console.error(error);
		});
});

$(document).on('click', '.vincular-agente', function (e) {
	e.preventDefault();

	resetarCamposModalVincularAgentes();
	
	const botao = $(this);
	const idFila = botao.attr('data-id');

	botao
		.attr('disabled', true)
		.html('Carregando...');

	const url = `${SITE_URL}/AtendimentoOmnilink/Filas/buscar/${idFila}`;

	axios
		.get(url)
		.then((response) => {
			const data = response.data;
			
			if (data.status == 1) {
				const fila = data.fila;

				if(fila.usuarios?.length) {
					$('#agentes-fila').val(fila.usuarios).trigger('change');
				}

				if(fila.numeros?.length) {
					$('#numeros-fila').val(fila.numeros).trigger('change');
				}

				$('#btn-vincular-agentes').attr('data-id', idFila);
				$('#modal-vincular-agentes').modal('show');
			}
			else {
				showAlert('error', data.mensagem);
			}
		})
		.catch((error) => {
			console.error(error);
		})
		.finally(() => {
			botao
				.attr('disabled', false)
				.html('Vincular Agentes');
		});
});

function resetarCamposModalVincularAgentes() {
	// reseta os select2
	$('#form-vincular-agentes select').val(null).trigger('change');
	$('#btn-vincular-agentes')
		.removeAttr('data-id');

	// Remover as mensagens de erro
	removerErrorFormulario('form-vincular-agentes');
}

function salvarVinculoAgentes() {
	const botao = $('#btn-vincular-agentes');

	const idFila = botao.attr('data-id') || 0;
	const agentes = $('#agentes-fila').val();
	const numeros = $('#numeros-fila').val();

	const url = `${SITE_URL}/AtendimentoOmnilink/Filas/vincularAgentes`;

	const formData = new FormData();
	formData.append('idFila', idFila);
	formData.append('agentes', agentes);
	formData.append('numeros', numeros);

	botao
		.attr('disabled', true)
		.html('Salvando...');

	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;

			if (data.status == 1) {
				showAlert('success', data.mensagem);
				$('#modal-vincular-agentes').modal('hide');
			}
			else {
				showAlert('error', data.mensagem);
			}
		})
		.catch((error) => {
			console.error(error);
		})
		.finally(() => {
			botao
				.attr('disabled', false)
				.html('Salvar');
		});
}
