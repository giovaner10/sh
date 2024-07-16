
$(document).on('click', '.excluir-contato', async function (e) {
	e.preventDefault();

	const botao = $(this);
	const id = botao.attr('data-id');

	const confirmouRemocao = await confirmarAcao({
		titulo: 'Deseja realmente excluir este contato?',
		texto: 'Esta ação não poderá ser desfeita!'
	});

	if (!confirmouRemocao) return;

	showAlert('warning', 'Removendo contato, aguarde...');

	const url = `${SITE_URL}/AtendimentoOmnilink/CanalVoz/excluirContato/${id}`;
	botao
		.html('Removendo...')
		.attr('disabled', true);

	axios
		.patch(url)
		.then((response) => {
			const data = response.data;
			if (data.status == '1') {
				const node = gridOptions2.api.getRowNode(id);
				removerRegistroNaAgGridContatos(node.rowIndex);
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

function removerRegistroNaAgGridContatos(idRow) {
	let displayModel = gridOptions2.api.getModel();
	let rowNode = displayModel.rowsToDisplay[idRow];
	gridOptions2.api.applyTransaction({ remove: [rowNode.data] });
}