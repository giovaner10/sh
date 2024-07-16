$(document).ready(function () {
	carregarCentroCustos();
});

async function carregarCentroCustos() {
	let url = `${SITE_URL}/PortalCompras/Configuracoes/buscar`;
	return axios.get(url)
		.then((response) => {
			const data = response.data;
			const centrosCustos = data?.configuracao?.centrosDeCusto
			if (centrosCustos) {
				povoarSelectCentroCusto(centrosCustos);
			}
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		});
}

function povoarSelectCentroCusto(centrosCusto) {

	const selectCentroCusto = $('#centro_custo');
	selectCentroCusto.empty();
	if (centrosCusto.length > 0) {
		centrosCusto.forEach(item => {
			const { centroDeCusto } = item;
			const [ id, nome ] = centroDeCusto.split(' - ');
			if(id == "01" || id == "02"){
				return;
			}
			selectCentroCusto.append(`<option value="${id}">${centroDeCusto}</option>`);
		});
	}

	selectCentroCusto.val(null).trigger('change');
}