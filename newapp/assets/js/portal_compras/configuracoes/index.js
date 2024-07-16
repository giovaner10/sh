const gridDiv = document.querySelector("#grid-centro-custo");

$(document).ready(async function (e) {
	$('select#diretor').select2({
		placeholder: 'Selecione um diretor',
		allowClear: true,
		width: '100%',
		language: 'pt-BR',
	});

	$('select#aprovadores-projetos-ceo').select2({
		placeholder: 'Selecione um CEO',
		allowClear: true,
		width: '100%',
		language: 'pt-BR',
	});

	$('select#aprovadores-projetos-cfo').select2({
		placeholder: 'Selecione um CFO',
		allowClear: true,
		width: '100%',
		language: 'pt-BR',
	});
	
	$('#overlay').css('display', 'block');

	await carregarDiretores();
	await carregarDadosConfiguracao();

	$('#overlay').css('display', 'none');

	$('select.centro-custo').select2({
		width: '100%',
		placeholder: 'Buscar centro de custo',
		allowClear: true,
		minimumInputLength: 3,
		maximumInputLength: 20,
		minimumResultsForSearch: 10,
		language: 'pt-BR',
		ajax: {
			url: `${SITE_URL}/PortalCompras/CentrosCusto/buscarPeloCodigoOuDescricao`,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				let results = Object.values(data).map(centroCusto => {
					return {
						id: centroCusto.codigo,
						text: `${centroCusto.codigo} - ${centroCusto.descricao}`,
					}
				});
				return {
					results: results
				}
			}
		}
	})
});

// Carregar diretores
async function carregarDiretores() {
	let url = `${SITE_URL}/usuarios/listarUsuariosPortal`;
	return axios.get(url)
		.then((response) => {
			const data = response.data;
			povoarSelectDiretores(data);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		});
}

// Povoar select de diretores
function povoarSelectDiretores(diretores) {
	const selectDiretor = $('.diretor');
	selectDiretor.empty();

	if (diretores.length > 0) {
		diretores.forEach(diretor => {
			selectDiretor.append(`<option value="${diretor.id}">${diretor.nome}</option>`);
		});
	}

	selectDiretor.val(null).trigger('change');
}


// Renderiza a coluna de ações
function renderizarColunaAcoes(dados) {
	const { data } = dados;

	const botaoEditar = /* HTML */ ` <button
    class="btn btn-light btn-sm action-button"
    type="button"
    title="Editar"
    style="margin-top: -6px;"
    onclick="editarCentroCusto('${data.id}')"
  >
    <img
      src="${BASE_URL}assets/css/icon/png/512/edit.png"
      height="21"
      title="Editar"
    />
  </button>`;

	const botaoRemover = /* HTML */`
		<button 
			class="btn btn-light btn-sm action-button" 
			type="button" 
			title="Remover" 
			style="margin-top: -6px;"
			onclick="removerCentroCusto('${data.id}')"
		>
			<img src="${BASE_URL}media/img/new_icons/icon-delete.png" height="21" alt="Remover">
		</button>`;

	return /* HTML */`
		<div>
			${botaoEditar}
			${botaoRemover}
		</div>`;
}


// Colunas da AgGrid
function columnDefsGrid() {

	let defs = [
		{ field: "centroDeCusto", headerName: "Centro de Custo", flex: 1, minWidth: 300 },
		{ field: "diretor", headerName: "Diretor", flex: 1, minWidth: 300 },
		{
			field: "acoes",
			headerName: lang.acoes,
			width: 110,
			flex: false,
			resizable: false,
			cellRenderer: renderizarColunaAcoes,
			cellClass: "actions-button-cell",
			pinned: 'right',
			pdfExportOptions: { skipColumn: true },
		}
	];

	return defs;
}

const gridOptions = {
	localeText: AG_GRID_TRANSLATE_PT_BR,
	columnDefs: columnDefsGrid(),
	rowData: [],
	animateRows: true,
	defaultColDef: {
		flex: true,
		editable: false,
		resizable: true,
		sortable: false,
		suppressMenu: true,
	},
	getRowId: (params) => params.data.id,
	overlayLoadingTemplate: mensagemCarregamentoAgGrid,
	overlayNoRowsTemplate: mensagemAgGridSemDados,
	pagination: true,
	paginationPageSize: 10,
};

new agGrid.Grid(gridDiv, gridOptions);

// Carregar dados da configuracao
async function carregarDadosConfiguracao() {
	let url = `${SITE_URL}/PortalCompras/Configuracoes/buscar`;

	return axios
		.post(url)
		.then((response) => {
			const data = response.data;

			if (data.status == 1) {
				const alcadaDeAprovacao = data.configuracao?.alcadaDeAprovacao;
	
				$('#diretor_min').val(alcadaDeAprovacao.diretor_min);
				$('#diretor_max').val(alcadaDeAprovacao.diretor_max);
				$('#cfo_min').val(alcadaDeAprovacao.cfo_min);
				$('#cfo_max').val(alcadaDeAprovacao.cfo_max);
				$('#ceo_min').val(alcadaDeAprovacao.ceo_min);

				const centrosDeCusto = data.configuracao?.centrosDeCusto;
				gridOptions.api.setRowData(centrosDeCusto || []);
				
				const aprovadores = data.configuracao?.aprovadores;
				$('#aprovadores-projetos-ceo').val(aprovadores.ceo || "").trigger('change');
				$('#aprovadores-projetos-cfo').val(aprovadores.cfo || "").trigger('change');
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
			$('#diretor_max').attr('readonly', false);
			$('#cfo_max').prop('readonly', false);
		});
}

$(document).on('keyup', '#diretor_max', function (e) {
	let valor = $(this).val();
	const valorDecimal = formatarParaDecimal(valor);
	const valorCFOMin = formatarParaMoeda(valorDecimal + 0.01);
	$("#cfo_min").val(valorCFOMin);
});

$(document).on('keyup', '#cfo_max', function (e) {
	let valor = $(this).val();
	const valorDecimal = formatarParaDecimal(valor);
	const valorCEOMin = formatarParaMoeda(valorDecimal + 0.01);
	$("#ceo_min").val(valorCEOMin);
});

function removerCentroCusto(id) {
	const centroCusto = gridOptions.api.getRowNode(id).data;
	gridOptions.api.updateRowData({ remove: [centroCusto] });
}

function editarCentroCusto(id) {
	const dados = gridOptions.api.getRowNode(id).data;
	const idDiretor = dados.diretor.split(' - ')[0];
	const idCentroCusto = dados.centroDeCusto.split(' - ')[0];

	$('#centro-custo').select2('trigger', 'select', {
		data: {
			id: idCentroCusto,
			text: dados.centroDeCusto,
		},
	});

	$('#centro-custo').attr('disabled', true).trigger('change');

	$('#diretor')
		.attr('disabled', false)
		.val(idDiretor)
		.trigger('change');

	$('#btn-adicionar-centro-custo').attr('disabled', false);
}

$(document).on('click', '#btn-adicionar-centro-custo', function () {
	let centroCusto = $('#centro-custo');
	const idCentroCusto = centroCusto.val();
	const nomeCentroCusto = centroCusto.find("option:selected").text();

	let diretor = $('#diretor');
	const idDiretor = diretor.val();
	const nomeDiretor = diretor.find("option:selected").text();
	
	if (!idCentroCusto) {
		showAlert('error', 'Selecione um centro de custo para adicionar.');
		return;
	}
	
	if (!idDiretor) {
		showAlert('error', 'Selecione um diretor para o centro de custo.');
		return;
	}

	const dadosCentroCusto = {
		id: idCentroCusto,
		centroDeCusto: nomeCentroCusto,
		diretor: `${idDiretor} - ${nomeDiretor}`,
	};

	let linhas = gridOptions.api.getModel().rowsToDisplay;
	let centroCustoJaAdicionado = linhas.find((linha) => linha.data.id == idCentroCusto);

	if (centroCustoJaAdicionado) {
		gridOptions.api.updateRowData({ update: [dadosCentroCusto] });
	}
	else {
		// Obter todos os dados da tabela
		let dadosTabela = [];
		gridOptions.api.forEachNode((node) => {
			dadosTabela.push(node.data);
		});
	
		// Adicionar o novo centro de custo ao topo
		dadosTabela.unshift(dadosCentroCusto);
	
		// Atualizar os dados da tabela
		gridOptions.api.setRowData(dadosTabela);
	}


	centroCusto.val(null).removeAttr('disabled').trigger('change');
	diretor.val(null).attr('disabled', true).trigger('change');
	$('#btn-adicionar-centro-custo').attr('disabled', true);
});


$(document).on('change', '#centro-custo', function () {
	const centroCusto = $(this).val();
	if (centroCusto) {
		$('#diretor').attr('disabled', false);
		$('#btn-adicionar-centro-custo').attr('disabled', false);
	}
});

// Salvar configuracao
async function salvarConfiguracao() {
	const botao = $('#btn-editar-configuracao');
	let formData = new FormData($("#form-configuracao")[0]);

	const diretorMin = formatarParaDecimal(formData.get('diretor_min'));
	const diretorMax = formatarParaDecimal(formData.get('diretor_max'));
	const cfoMin = formatarParaDecimal(formData.get('cfo_min'));
	const cfoMax = formatarParaDecimal(formData.get('cfo_max'));
	const ceoMin = formatarParaDecimal(formData.get('ceo_min'));
	const ceoInput = $('#aprovadores-projetos-ceo').val();
	const cfoInput = $('#aprovadores-projetos-cfo').val();

	if (diretorMax <= diretorMin) {
		showAlert('error', "Valor máximo do Diretor inferior ou igual ao seu valor mínimo");
		return false;
	}

	if (cfoMax <= cfoMin) {
		showAlert('error', "Valor máximo do CFO inferior ou igual ao seu valor mínimo");
		return false;
	}

	if (cfoMin <= diretorMax) {
		showAlert('error', "Valor mínimo do CFO inferior ou igual ao valor máximo do Diretor");
		return false;
	}

	if (ceoMin <= cfoMax) {
		showAlert('error', "Valor mínimo do CEO inferior ou igual ao valor máximo do CFO");
		return false;
	}

	if (!ceoInput && !cfoInput) {
		showAlert('error', "Selecione o CEO e o CFO");
		return false;
	}

	if(!ceoInput){
		showAlert('error', "Selecione o CEO");
		return false;
	}

	if(!cfoInput){
		showAlert('error', "Selecione o CFO");
		return false;
	}
	
	//Pega os dados da grid
	const dadosGrid = gridOptions.api.getModel().rowsToDisplay.map((row) => {
		return {
			id: row.data.id,
			centroDeCusto: row.data.centroDeCusto,
			diretor: row.data.diretor,
		};
	});

	if (!dadosGrid.length) {
		showAlert('error', 'Adicione o responsável para ao menos um centro de custo.');
		return false;
	}

	formData.set('diretor_min', diretorMin);
	formData.set('diretor_max', diretorMax);
	formData.set('cfo_min', cfoMin);
	formData.set('cfo_max', cfoMax);
	formData.set('ceo_min', ceoMin);
	formData.set('centros_custo', JSON.stringify(dadosGrid));
	formData.set('ceo_aprovador', ceoInput);
	formData.set('cfo_aprovador', cfoInput);

	let url = `${SITE_URL}/PortalCompras/Configuracoes/editar`;

	botao
		.prop('disabled', true)
		.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;
			showAlert(data.status == 1 ? 'success' : 'error', data.mensagem);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		})
		.finally(() => {
			botao.prop('disabled', false).html('Salvar');
		});
}