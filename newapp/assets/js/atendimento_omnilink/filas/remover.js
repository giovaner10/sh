
$(document).on('click', '.remover-fila', async function (e) {
	e.preventDefault();

	const botao = $(this);
	const id = botao.attr('data-id');

	const confirmouRemocao = await confirmarAcao({
		titulo: 'Deseja realmente remover esta fila?',
		texto: 'Esta ação não poderá ser desfeita!'
	});

	if (!confirmouRemocao) return;

	showAlert('warning', 'Removendo fila, aguarde...');

	const url = `${SITE_URL}/AtendimentoOmnilink/Filas/remover/${id}`;
	botao
		.html('Removendo...')
		.attr('disabled', true);

	axios
		.patch(url)
		.then((response) => {
			const data = response.data;
			if (data.status == '1') {
				const node = buscarNodeNaAgGrid(id);
				removerRegistroNaAgGrid(node.rowIndex);
				showAlert('success', data.mensagem);
			}
			else {
				showAlert('error', data.mensagem);
			}
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		})
		.finally(() => {
			botao
				.prop('disabled', false)
				.html('Remover');
		});

});
