listarEmpresas();

function listarEmpresas() {
	const url = `${SITE_URL}/PortalCompras/Empresas/listar`;

	axios
		.get(url)
		.then((response) => {
			const data = response.data;
			popularSelectEmpresas(data);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		});
}

function popularSelectEmpresas(filiais) {
	const selectFilial = $('#empresa');
	selectFilial.empty();
	selectFilial.append('<option value="" selected disabled>Selecione uma empresa</option>');

	if (Object.keys(filiais).length > 0) {
		Object.entries(filiais).forEach(([key, value]) => {
			selectFilial.append(`<option value="${value.id}" selected>${value.nome}</option>`);
		});
	}
}