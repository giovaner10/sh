listarFiliais();

function listarFiliais() {
	const url = `${SITE_URL}/PortalCompras/Filiais/listar`;

	axios
		.get(url)
		.then((response) => {
			const data = response.data;
			popularSelectFiliais(data);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		});
}

function popularSelectFiliais(filiais) {
	const selectFilial = $('#filial');
	selectFilial.empty();
	selectFilial.append('<option value="" selected disabled>Selecione uma filial</option>');

	if (Object.keys(filiais).length > 0) {
		Object.entries(filiais).forEach(([key, value]) => {
			selectFilial.append(`<option value="${value.id}">${value.codigo} - ${value.nome}</option>`);
		});
	}

	selectFilial.removeAttr('readonly');
}