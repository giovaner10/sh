const gridNotasFiscaisDiv = document.querySelector("#grid-notas-fiscais");

$(document).ready(function () {
	$('select#especie-nota').select2({
		placeholder: 'Selecione uma espécie',
		allowClear: true,
		width: '100%',
		language: 'pt-BR',
	});

	if (funcaoUsuario === 'area_fiscal' && permissaoIncluirNotaFiscal) {
		carregarEspecies();
	}

});

const statusNotaFiscal = {
	pre_nota: 'Pré Nota',
	classificado: 'Classificado',
	excluido: 'Excluído',
}

// Renderiza a coluna de ações
function renderizarColunaAcoesNotasFiscais(dados) {
	const { data } = dados;
	const botaoBaixarAnexo = /* HTML */`
		<button
			class="btn btn-light btn-sm action-button baixarNotaFiscal"
			type="button"
			title="Baixar Anexo"
			style="margin-top: -6px;"
			data-id=${data.id}
		>
			<img src="${BASE_URL}media/img/new_icons/relatorios/download.svg" height="25">
		</button>`;

	return /* HTML */`<div>${botaoBaixarAnexo}</div>`;
}

// Colunas da AgGrid de Produtos
function columnDefsGridNotasFiscais() {

	let defs = [
		{ field: "id", headerName: "Id", flex: 1, minWidth: 80, sort: 'desc' },
		{ field: "numero", headerName: "Número", flex: 1, minWidth: 150 },
		{ field: "serie", headerName: "Serie", flex: 1, minWidth: 150 },
		{ field: "especie", headerName: "Espécie", flex: 1, minWidth: 150 },
		{
			field: "status", headerName: "Status", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { status } }) => {
				return statusNotaFiscal?.[status] || 'Não Encontrado';
			},
			valueGetter: ({ data: { status } }) => {
				return statusNotaFiscal?.[status] || 'Não Encontrado';
			},
			cellStyle: ({ data: { status } }) => {
				switch (status) {
					case 'classificado': return { 'background-color': 'rgb(228, 255, 225)' };
					case 'excluido': return { 'background-color': 'rgb(224, 130, 130)' };
					case 'pre_nota': return { 'background-color': 'rgb(255, 247, 225)' };
					default: '';
				}
			},
		},
		{
			field: "valor", headerName: "Valor", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { valor } }) => {
				return formatarParaMoedaBRL(valor);
			},
			valueGetter: ({ data: { valor } }) => {
				return formatarParaMoedaBRL(valor);
			}
		},
		{
			field: "dataEmissao", headerName: "Data de Emissão", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { dataEmissao } }) => {
				return dataEmissao ? dayjs(dataEmissao).format('DD/MM/YYYY') : '';
			},
			valueGetter: ({ data: { dataEmissao } }) => {
				return dataEmissao ? dayjs(dataEmissao).format('DD/MM/YYYY') : '';
			}
		},
		{
			field: "dataVencimento", headerName: "Data de Vencimento", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { dataVencimento } }) => {
				return dataVencimento ? dayjs(dataVencimento).format('DD/MM/YYYY') : '';
			},
			valueGetter: ({ data: { dataVencimento } }) => {
				return dataVencimento ? dayjs(dataVencimento).format('DD/MM/YYYY') : '';
			}
		},
		{
			field: "acoes",
			headerName: lang.acoes,
			width: 85,
			flex: false,
			resizable: false,
			cellRenderer: renderizarColunaAcoesNotasFiscais,
			cellClass: "actions-button-cell",
			pinned: 'right',
			pdfExportOptions: { skipColumn: true },
		}
		
	];

	return defs;
}

const gridNotasFiscaisOptions = {
	localeText: AG_GRID_TRANSLATE_PT_BR,
	columnDefs: columnDefsGridNotasFiscais(),
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
	pagination: false
};

if (gridNotasFiscaisDiv) {
	new agGrid.Grid(gridNotasFiscaisDiv, gridNotasFiscaisOptions);
}

async function listarNotasFiscaisDaSolicitacao() {
	const url = `${SITE_URL}/PortalCompras/NotaFiscal/listarParaSolicitacao/${idSolicitacao}`;

		$('#div-mensagem-sem-nota-fiscal').css('display', 'none');
		$('#div-dados-nota-fiscal').css('display', 'block');
	
	return await axios.get(url)
		.then(response => {
			const data = response.data;

			if (data.status != '1') {
				return showAlert('error', data.mensagem);
			}

			const notasFiscais = data.notasFiscais;
			
			if (notasFiscais?.length > 0) {
				gridNotasFiscaisOptions.api.setRowData(notasFiscais);
				$('#div-mensagem-sem-nota-fiscal').css('display', 'none');
				$('#div-dados-nota-fiscal').css('display', 'block');
			}
			else {
				gridNotasFiscaisOptions.api.setRowData([]);
				$('#div-mensagem-sem-nota-fiscal').css('display', 'block');
				$('#div-dados-nota-fiscal').css('display', 'none');
			}
		})
		.catch(error => {
			gridNotasFiscaisOptions.api.setRowData([]);
			console.error(error);
			showAlert('error', 'Não foi possível carregar as notas fiscais. Tente novamente mais tarde.');
		});
}

$(document).on('click', '.incluirNotaFiscal', async function (e) {
	e.stopPropagation();

	const botao = $(this);
	const idSolicitacao = botao.attr('data-id');
	const numeroPedido = botao.attr('data-numero_pedido');

	resetarCamposModalNotaFiscal();

	$('#btn-incluir-nota-fiscal').attr('data-id_solicitacao', idSolicitacao);
	$('#btn-incluir-nota-fiscal').attr('data-numero_pedido', numeroPedido);
	$('#modal-nota-fiscal').modal('show');
});


$(document).on('change', '#anexo-nota', function (e) {
	let uploadField = $('#viewAnexo-nota');
	var uploadImput = document.getElementById("anexo-nota");

	if (uploadImput) {
		let extensoes_suportadas = [
			'application/pdf',
		];

		if (uploadImput.files[0].size > (1024 * 1024 * 5)) {  //limita o tamanho da imagem em 5mb
			showAlert('error', 'O arquivo selecionado é muito grande. O tamanho máximo permitido é de 5MB.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else if (extensoes_suportadas.indexOf(uploadImput.files[0].type) === -1) {
			showAlert('error', 'O tipo de arquivo selecionado não é suportado. Por favor, selecione um arquivo PDF.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else {
			uploadField.html(`<p style="" class="truncade link-upload-valid"><i class="fa fa-file-pdf-o"></i>  ${uploadImput.files[0].name}</p>`);
		}
	}
});

async function incluirNotaFiscal() {
	let botao = $('#btn-incluir-nota-fiscal');
	let formData = new FormData($('#form-nota-fiscal')[0]);
	const idSolicitacao = botao.attr('data-id_solicitacao');
	const codigoPedido = botao.attr('data-numero_pedido');

	const anexo = formData.get('anexo');
	if (!anexo?.size) return showAlert('error', 'Anexo é necessário.');

	formData.set('valor', formatarParaDecimal(formData.get('valor')));

	botao
		.html('<i class="fa fa-spinner fa-spin"></i> Enviando...')
		.attr('disabled', true);

	const url = `${SITE_URL}/PortalCompras/NotaFiscal/incluir/${idSolicitacao}/${codigoPedido}`;

	await axios.post(url, formData)
		.then(async (response) => {
			const data = response.data;
			if (data.status == '1') {
				const preNota = data.preNota;
				// chama a api televendas para salvar a nota fiscal
				const formDataApi = new FormData();
				formDataApi.append('codigoPedidoErp', codigoPedido);
				formDataApi.append('codigoEmpresa', preNota.codigo_empresa);
				formDataApi.append('codigoFilial', preNota.codigo_filial);
				formDataApi.append('status', 'pre_nota');
				formDataApi.append('numero', formData.get('numero'));
				formDataApi.append('serie', formData.get('serie'));
				formDataApi.append('especie', formData.get('especie'));
				formDataApi.append('valor', formData.get('valor'));
				formDataApi.append('dataHotaEmissao', formData.get('data_emissao'));
				formDataApi.append('dataVencimento', formData.get('data_vencimento'));
				formDataApi.append('anexo', formData.get('anexo'));

				const resposta = await axios.post(`${BASE_URL_API_TELEVENDAS}/erp/invoices/create-update`, formDataApi, {
					headers: {
						// "x-access-token": TOKEN_FIX_API_TELEVENDAS,
						'Authorization': 'Bearer ' + TOKEN_URL_API_TELEVENDAS,
						"Content-type": "multipart/form-data",
						'Accept': 'application/json',
						'Access-Control-Allow-Origin': '*',
					}
				});

				if (resposta.status != '201' && resposta.status != '200') {
					showAlert('error', resposta.data);
					return;
				}

				// Atualiza a tabela de solciitacoes e modifica a situacao
				const nodeRow = buscarNodeNaAgGrid(idSolicitacao);
				if (nodeRow) {
					const indexRow = nodeRow.rowIndex;
					const solicitacao = nodeRow.data;
					solicitacao.situacao = 'aguardando_fiscal';
					atualizarRegistroNaAgGrid(indexRow, solicitacao);
				}

				resetarCamposModalNotaFiscal();
				$('#modal-nota-fiscal').modal('hide');

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
			botao.html('Enviar').attr('disabled', false);
		});
}

function resetarCamposModalNotaFiscal() {
	$('#form-nota-fiscal')[0].reset();
	$('#viewAnexo-nota').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
	$('#anexo-nota').val('');

	$('#especie-nota').val(null).trigger('change');

	removerErrorFormulario('form-nota-fiscal');
}

$(document).on('click', '.baixarNotaFiscal', function (e) {
	e.preventDefault();

	const botao = $(this);
	const idNotaFiscal = botao.attr('data-id');

	botao.html('<i class="fa fa-spinner fa-spin"></i>')
		.attr('disabled', true);


	const uri = `/erp/invoices/${idNotaFiscal}`;
	return axiosTelevendas.get(uri)
		.then(response => {
			const { data } = response;
			if (!data.link) return showAlert('error', 'Não existe anexo para essa nota fiscal.');

			window.location.href = data.link;
		})
		.catch(error => {
			console.error(error);
			showAlert('error', 'Não foi possível baixar o anexo da nota fiscal. Tente novamente mais tarde.');
		})
		.finally(() => {
			botao.html(`<img src="${BASE_URL}media/img/new_icons/relatorios/download.svg" height="25">`)
				.attr('disabled', false);
		});
});



async function carregarEspecies() {
	const url = `${SITE_URL}/PortalCompras/Especies/listar`;
	return axios.get(url)
		.then((response) => {
			const data = response.data;
			povoarSelectEspecies(data);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		});
}

// Povoar select de especies
function povoarSelectEspecies(dados) {
	const selectEspecie = $('#especie-nota');
	selectEspecie.empty();
	if (dados.length > 0) {
		dados.forEach(especie => {
			selectEspecie.append(`<option value="${especie.codigo}">${especie.descricao}</option>`);
		});
	}

	selectEspecie.val(null).trigger('change');
}