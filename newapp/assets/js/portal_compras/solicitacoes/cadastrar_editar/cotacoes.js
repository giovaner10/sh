let cotacoesBuscadas = [];
let fornecedoresBuscados = [];

$(document).ready(function () {

	if (funcaoUsuario === 'solicitante' && tipoSolicitacao === 'requisicao') {
		$('#li-aba-cotacoes').css('display', 'none');
	}

	$('#viewAnexo-cotacao').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);

	$('select#cotacao').select2({
		width: '100%',
		placeholder: 'Buscar cotação pelo fornecedor',
		allowClear: true,
		minimumInputLength: 3,
		maximumInputLength: 20,
		minimumResultsForSearch: 20,
		language: 'pt-BR',
		ajax: {
			url: `${SITE_URL}/PortalCompras/Cotacoes/buscarPeloFornecedor`,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data.map(cotacao => {
						if (!cotacoesBuscadas.hasOwnProperty(cotacao.id)) {
							cotacoesBuscadas[cotacao.id] = cotacao;
						}

						return {
							id: cotacao.id,
							text: `${formatarParaMoedaBRL(cotacao.valorTotal)} - ${cotacao.fornecedor.nome}`,
						}
					})
				};
			}
		}
	});

	$('select#fornecedor').select2({
		width: '100%',
		placeholder: 'Buscar um fornecedor',
		allowClear: true,
		minimumInputLength: 3,
		maximumInputLength: 20,
		minimumResultsForSearch: 20,
		language: 'pt-BR',
		ajax: {
			url: `${SITE_URL}/PortalCompras/Fornecedores/buscarPeloDocumentoOuNome`,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {				
				const results = data.map(fornecedor => {
					if (!fornecedoresBuscados.hasOwnProperty(fornecedor.codigo)) {
						fornecedoresBuscados[fornecedor.id] = fornecedor;	
					}
				
					const documentoFornecedor = fornecedor.documento.length == 11
						? criarMascara(fornecedor.documento, '###.###.###-##')
						: criarMascara(fornecedor.documento, '##.###.###/####-##');

					return {
						id: fornecedor.id,
						text: `${documentoFornecedor} - ${fornecedor.nome}`,
					}
				});
				
				return {
					results: results,
				};
			}
		}
	});

});

const gridCotacoesDiv = document.querySelector("#grid-cotacoes");
carregarCondicoesPagamento()


// Renderiza a coluna de ações
function renderizarColunaAcoesCotacoes(dados) {
	const { data } = dados;
	let botaoEditarCotacao = '';
	let botaoRemoverCotacao = '';

	const botaoVisualizarCotacao = /* HTML */`
		<a class="dropdown-item dropdown-item-acoes visualizarCotacao item-menu-acoes-grid" data-id="${data.id}">
			Visualizar
		</a>`;

	if (acao !== 'selecionar_cotacao') {
		botaoRemoverCotacao = /* HTML */`
			<a class="dropdown-item dropdown-item-acoes removerCotacao item-menu-acoes-grid" data-id="${data.id}">
				Remover
			</a>`;
	}

	return /* HTML */`
    <div class="dropdown dropdown_${data.id}">
      <button
        class="btn btn-light btn-sm"
        type="button"
        id="dropdownMenuButton_${data.id}"
        row_id="${data.id}"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
        style="margin-top:-6px; width:35px"
      >
        <img
          src="${BASE_URL + 'media/img/new_icons/icon_acoes.svg'}"
          alt="${lang.acoes}"
        />
      </button>
      <div class="dropdown-menu dropdown-menu-right shadow"aria-labelledby="dropdownMenuButton_${data.id}">
        ${botaoVisualizarCotacao}
        ${botaoRemoverCotacao}
      </div>
    </div>`;
}


// Colunas da AgGrid de Cotacoes
function columnDefsGridCotacoes() {

	let defs = [
		{ field: "id", headerName: "Id", flex: 1, minWidth: 80, sort: 'desc' },
		{ field: "fornecedor", headerName: "Fornecedor", flex: 1, minWidth: 300 },
		{
			field: "valorTotal", headerName: "Valor Total", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { valorTotal } }) => {
				return formatarParaMoedaBRL(valorTotal);
			},
			valueGetter: ({ data: { valorTotal } }) => {
				return formatarParaMoedaBRL(valorTotal);
			}
		},
		{
			field: "formaPagamento", headerName: "Forma de Pagamento", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { formaPagamento } }) => {
				switch (formaPagamento) {
					case 'pix': return 'Pix';
					case 'boleto': return 'Boleto';
					case 'ted': return 'TED';
					case 'deposito': return 'Depósito'
					default: return '';
				}
			},
			valueGetter: ({ data: { formaPagamento } }) => {
				switch (formaPagamento) {
					case 'pix': return 'Pix';
					case 'boleto': return 'Boleto';
					case 'ted': return 'TED';
					case 'deposito': return 'Depósito'
					default: return '';
				}
			}
		},
		{
			field: "condicaoPagamento", headerName: "Condição de Pagamento", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { condicaoPagamento } }) => {
				let [codigoCondicao, ...descricaoCondicao] = condicaoPagamento?.split(' - ');
				return descricaoCondicao.join(' - ');
			},
			valueGetter: ({ data: { condicaoPagamento } }) => {
				let [codigoCondicao, ...descricaoCondicao] = condicaoPagamento?.split(' - ');
				return descricaoCondicao.join(' - ');
			}
		},
		{
			field: "acoes",
			headerName: lang.acoes,
			width: 85,
			flex: false,
			resizable: false,
			cellRenderer: renderizarColunaAcoesCotacoes,
			cellClass: "actions-button-cell",
			pinned: 'right',
			pdfExportOptions: { skipColumn: true },
		}
	];

	if (acao === 'selecionar_cotacao' || (cadastroPedido)) {
		defs.splice(0, 0, {
			headerName: '',
			field: 'checkboxField',
			cellRenderer: 'agGroupCellRenderer',
			checkboxSelection: true,
			editable: false,
			width: 50,
		})
	}
	
	return defs;
}

const gridCotacoesOptions = {
	localeText: AG_GRID_TRANSLATE_PT_BR,
	columnDefs: columnDefsGridCotacoes(),
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

new agGrid.Grid(gridCotacoesDiv, gridCotacoesOptions);


const gridProdutosDaCotacaoDiv = document.querySelector("#grid-produtos-cotacao");


// Colunas da AgGrid de Produtos
function columnDefsGridProdutosDaCotacao() {
	let defs = [
		{
			field: "codigo",
			headerName: "Código",
			flex: 1,
			minWidth: 80,
			sort: "desc",
		},
		{ field: "descricao", headerName: "Descricao", flex: 1, minWidth: 200 },
		{ field: "quantidade", headerName: "Quantidade", flex: 1, minWidth: 80 },
		{
			field: "valorUnitario",
			headerName: "Valor Unitário",
			flex: 1,
			minWidth: 150,
			editable: true,
			cellEditor: maskedCellEditor,
			cellEditorParams: {
				mascara: 'moeda',
			},
			cellStyle: params => {
				if (!params.value) return { 'background-color': 'rgb(224, 130, 130)' };
				return { 'background-color': 'rgb(228, 255, 225)' };
			},
			onCellValueChanged: function(params) {
				let data = params.data;
				const valorUnitario = formatarParaDecimal(data?.valorUnitario || 0);
				data.valorUnitario = valorUnitario
				data.valorTotal = valorUnitario * data.quantidade;
				gridProdutosDaCotacaoOptions.api.updateRowData({ update: [data] });
			},
			valueFormatter: ({ value }) => {
				return formatarParaMoedaBRL(value);
			},
		},
		{
			field: "valorTotal",
			headerName: "Valor Total",
			flex: 1,
			minWidth: 150,
			cellRenderer: ({ data: { valorTotal } }) => {
				return formatarParaMoedaBRL(valorTotal);
			},
			valueGetter: ({ data: { valorTotal } }) => {
				return formatarParaMoedaBRL(valorTotal);
			}
		}
	];

	return defs;
}

const gridProdutosDaCotacaoOptions = {
	localeText: AG_GRID_TRANSLATE_PT_BR,
	columnDefs: columnDefsGridProdutosDaCotacao(),
	rowData: [],
	animateRows: true,
	defaultColDef: {
		flex: true,
		editable: false,
		resizable: true,
		sortable: false,
		suppressMenu: true,
	},
	getRowId: (params) => params.data.codigo,
	overlayLoadingTemplate: mensagemCarregamentoAgGrid,
	overlayNoRowsTemplate: mensagemAgGridSemDados,
	pagination: true,
	paginationPageSize: 10,
	singleClickEdit: true,
	stopEditingWhenCellsLoseFocus: true,
};

new agGrid.Grid(gridProdutosDaCotacaoDiv, gridProdutosDaCotacaoOptions);


function carregarCondicoesPagamento() {
	const url = `${SITE_URL}/PortalCompras/CondicoesPagamento/listar`;

	axios
		.get(url)
		.then((response) => {
			const data = response.data;
			popularSelectCondicoesPagamento(data);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		});
}

function popularSelectCondicoesPagamento(condicoesPagamento) {
	const selectCondicaoPagamento = $('#condicao_pagamento');
	selectCondicaoPagamento.empty();
	selectCondicaoPagamento.append('<option value="" selected disabled>Selecione uma condição de pagamento</option>');

	if (Object.keys(condicoesPagamento).length > 0) {
		Object.entries(condicoesPagamento).forEach(([key, condicaoPagamento]) => {
			selectCondicaoPagamento.append(`<option value="${condicaoPagamento.codigo}">${condicaoPagamento.descricao}</option>`);
		});
	}

	selectCondicaoPagamento.removeAttr('readonly');
}

$(document).on('change', '#anexo_cotacao', function (e) {
	let uploadField = $('#viewAnexo-cotacao');
	var uploadImput = document.getElementById("anexo_cotacao");

	if (uploadImput) {
		let extensoes_suportadas = [
			'application/pdf', // pdf
			'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //excel
			'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // word
			'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', // powerpoint
			'message/rfc822' // email
		];

		if (uploadImput.files[0].size > (1024 * 1024 * 5)) {  //limita o tamanho da imagem em 5mb
			showAlert('error', 'O arquivo selecionado é muito grande. O tamanho máximo permitido é de 5MB.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else if (extensoes_suportadas.indexOf(uploadImput.files[0].type) === -1) {
			showAlert('error', 'O tipo de arquivo selecionado não é suportado. Por favor, selecione um arquivo XLS, XLSX ou PDF.');
			uploadImput.value = "";
			uploadField.html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
		}
		else {
			let icon = uploadImput.files[0].type == 'application/pdf' ? 'fa-file-pdf-o' : 'fa-file-excel-o';
			uploadField.html(`<p style="link-upload-valid" class="truncade"><i class="fa ${icon}"></i>  ${uploadImput.files[0].name}</p>`);
		}
	}
});

$(document).on('click', '#btn-nova-cotacao', function () {
	resetarCamposModalCotacao();
	$('#modal-cotacao').modal('show');
});

$(document).on('change', '#fornecedor', function () {
	const idFornecedor = $(this).val();

	$('.input-adicionar-cotacao').attr('readonly', true);
	if (idFornecedor) $('.input-adicionar-cotacao').removeAttr('readonly');
});


async function cadastarCotacao() {
	let botao = $('#btn-cadastrar-cotacao');
	let formData = new FormData($('#form-cotacoes')[0]);
	const { size } = formData.get('anexo_cotacao');

	if(size === 0) {
		showAlert('error', 'Anexo é necessário');
		return;
	}

	const condicaoPagamento = `${$('#condicao_pagamento').val()} - ${$('#condicao_pagamento option:selected').text()}`;
	formData.set('condicao_pagamento', condicaoPagamento);

	let valorTotalCotacao = 0; let produtoComValorZerado = false;
	const produtosCotacao = gridProdutosDaCotacaoOptions.api.getModel().rowsToDisplay.map(raw => {
		const { data } = raw;
		valorTotalCotacao += formatarParaDecimal(data.valorTotal);
		if (data.valorUnitario == 0) {
			produtoComValorZerado = true;
		}

		return {
			codigo: data.codigo,
			descricao: data.descricao,
			quantidade: data.quantidade,
			valorUnitario: data.valorUnitario,
			valorTotal: data.valorTotal,
		};
	});

	if (produtosCotacao.length === 0) {
		showAlert('error', 'Adicione ao menos um produto na cotação');
		return;
	}

	if (produtoComValorZerado) {
		showAlert('error', 'Produtos com valor unitário zerado não são permitidos');
		return;
	}

	formData.set('valor_total', valorTotalCotacao.toFixed(2));
	formData.set('produtos', JSON.stringify(produtosCotacao));
	
	botao
		.html('<i class="fa fa-spinner fa-spin"></i> Cadastrando...').attr('disabled', true);

	let url = `${SITE_URL}/PortalCompras/Cotacoes/cadastrar`;

	await axios.post(url, formData)
		.then((response) => {
			const data = response.data;
			if (data.status == '1') {
				const cotacao = data?.cotacao;

				const fornecedor = cotacao?.fornecedor;

				const documentoFornecedor = fornecedor.documento.length == 11 
				? criarMascara(fornecedor.documento, '###.###.###-##') 
				: criarMascara(fornecedor.documento, '##.###.###/####-##');

				const minhaCotacao = {
					id: cotacao.id,
					fornecedor: `${documentoFornecedor} - ${fornecedor.nome}`,
					valorTotal: cotacao.valorTotal,
					formaPagamento: cotacao.formaPagamento,
					condicaoPagamento: cotacao.condicaoPagamento,
				};
				
				gridCotacoesOptions.api.updateRowData({ add: [minhaCotacao] });

				resetarCamposModalCotacao();

				$('#modal-cotacao').modal('hide');
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
			botao.html('Cadastrar').attr('disabled', false);
		});
}

function atualizarValorTotalSolicitacao() {
	const valorTotalSolicitacao = gridCotacoesOptions.api.getModel().rowsToDisplay.reduce((acc, row) => {
		return acc + parseFloat(row.data.valorTotal);
	}, 0);

	$('#total-solicitacao').text(formatarParaMoedaBRL(valorTotalSolicitacao));
}

function resetarCamposModalCotacao() {
	$('#form-cotacoes').trigger('reset');
	$('.input-adicionar-cotacao').val(null).attr('readonly', true).removeAttr('disabled');
	$('#form-cotacoes select').val(null).removeAttr('disabled').trigger('change');
	$('#titulo-modal-cotacao').text('Cadastrar Cotação');
	
	replicarDadosParaTabelaProdutosDaCotacao();
	
	removerErrorFormulario('form-cotacoes');
	$('#viewAnexo-cotacao').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
	$('#visualizar-anexo_cotacao').hide();
	$('#btn-cadastrar-cotacao')
		.css('display', 'block');
}

/**
 * Replicar os dados da tabela de produtos para a tabela de produtos da cotação
*/
function replicarDadosParaTabelaProdutosDaCotacao() {
	gridProdutosDaCotacaoOptions.api.setRowData([]);
	const produtos = gridProdutosOptions.api.getModel().rowsToDisplay;
	if (produtos.length > 0) {
		produtos.forEach(produto => {
			produto.data.valorUnitario = 0;
			produto.data.valorTotal = 0;
			gridProdutosDaCotacaoOptions.api.updateRowData({ add: [produto.data] });
		});
	}
}

function limparCamposCotacoes() {
	$('#viewAnexo-cotacao').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
	$('#motivo_selecao_cotacao').val('');
	$('#motivo_cotacao').val('');
	gridCotacoesOptions.api.setRowData([]);
}

function listarCotacoes() {
	if (acao === 'selecionar_cotacao') {
	
		const url = `${SITE_URL}/PortalCompras/Solicitacoes/buscarCotacoes/${idSolicitacao}`;
		axios
			.get(url)
			.then((response) => {
				const data = response.data;
				popularGridCotacoes(data);
			})
			.catch((error) => {
				console.error(error);
				showAlert('error', lang.erro_inesperado);
			});
	}
}

function popularGridCotacoes(data) {
	const cotacoes = data.cotacoes;
	if (cotacoes.length > 0) {
		cotacoes.forEach((cotacao) => {
			const { id, valorTotal, fornecedor, formaPagamento, condicaoPagamento } = cotacao;
			const documentoFornecedor = fornecedor.documento?.length == 11
				? criarMascara(fornecedor?.documento, '###.###.###-##')
				: criarMascara(fornecedor?.documento, '##.###.###/####-##');
			
			let [codigoCondicao, ...descricaoCondicao] = condicaoPagamento?.split(' - ');

			const minhaCotacao = {
				id: id,
				fornecedor: `${documentoFornecedor} - ${fornecedor.razaoSocial}`,
				valorTotal: valorTotal,
				formaPagamento: formaPagamento,
				condicaoPagamento: descricaoCondicao.join(' - '),
			};

			gridCotacoesOptions.api.updateRowData({ add: [minhaCotacao] });
		});
	}
}

$(document).on('click', '#btn-salvar-cotacao-selecionada', function () {
	const botao = $(this);

	const dadosCotacoes = buscaDadosEValidaCotacoes();
	if (!dadosCotacoes) return;

	const { idCotacao } = dadosCotacoes;

	let url = `${SITE_URL}/PortalCompras/Solicitacoes/setarCotacao/${idSolicitacao}/${idCotacao}`;

	let formData = new FormData();
	const motivoSelecaoCotacao = $('#motivo_selecao_cotacao').val() || '';
	formData.set('motivo_selecao_cotacao', motivoSelecaoCotacao);

	botao
		.prop('disabled', true)
		.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;

			if (data.status == 1) {
				showAlert('success', 'Cotação selecionada com sucesso, redirecionando...');
				botao.prop('disabled', false).html('Salvar');

				// Redireciona para a página de solicitações
				setTimeout(() => {
					window.location.href = `${SITE_URL}/PortalCompras/Solicitacoes/index/requisicao/${idSolicitacao}`;
				}, 2000);
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
			botao.prop('disabled', false).html('Salvar');
			$('#motivo_selecao_cotacao').val('');
		});
});

$(document).on('click', '#btn-motivo-selecao-cotacao', function () {
	const motivoSelecaoCotacao = $('#motivo_selecao_cotacao').val();
	if (!motivoSelecaoCotacao) {
		showAlert('error', 'Informe o motivo da seleção da cotação');
		return;
	}

	if (motivoSelecaoCotacao.length < 5 || motivoSelecaoCotacao.length > 240) {
		showAlert('error', 'O motivo da seleção da cotação deve ter no mínimo 5 caracteres e no máximo 240 caracteres.');
		return;
	}

	$('#modal-motivo-selecao-cotacao').modal('hide');
});

$(document).on('click', '#btn-salvar-motivo-cotacao', function () {
	const motivoCotacao = $('#motivo_cotacao').val();
	if (!motivoCotacao) {
		showAlert('error', 'Informe um motivo.');
		return;
	}

	if (motivoCotacao.length < 5 || motivoCotacao.length > 240) {
		showAlert('error', 'O motivo da cotação deve ter no mínimo 5 caracteres e no máximo 240 caracteres.');
		return;
	}

	$('#modal-salvar-motivo-cotacao').modal('hide');
});

$(document).on('click', '#btn-salvar-cotacoes', function () {
	const botao = $(this);

	const dadosCotacoes = buscaDadosEValidaCotacoes();
	if (!dadosCotacoes) return;

	const { idsCotacoes } = dadosCotacoes;

	//Pega os dados da solicitacao
	const formData = new FormData();

	formData.set('ids_cotacoes', JSON.stringify(idsCotacoes));
	formData.set('motivo_cotacao', $('#motivo_cotacao').val());

	let url = `${SITE_URL}/PortalCompras/Solicitacoes/adicionarCotacoes/${idSolicitacao}`;

	botao
		.prop('disabled', true)
		.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;

			if (data.status == 1) {
				showAlert('success', 'Cotações adicionadas com sucesso, redirecionando...');

				// Redireciona para a página de solicitações
				setTimeout(() => {
					window.location.href = `${SITE_URL}/PortalCompras/Solicitacoes/index/requisicao/${idSolicitacao}`;
				}, 2000);
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
			botao.prop('disabled', false).html('Salvar');
			$('#motivo_cotacao').val('');
		});
});

$(document).on("click", "#btn-salvar-produtos-cotacoes", async function (e) {
	e.preventDefault();

	const botao = $(this);
	const formData = new FormData();

	// Pega os dados dos produtos
	const produtos = gridProdutosOptions.api.getModel().rowsToDisplay.map((row) => {
		return {
			codigo: row.data.codigo,
			descricao: row.data.descricao,
			quantidade: parseInt(row.data.quantidade) || 0,
			valorUnitario: parseFloat(row.data.valorUnitario) || 0,
			valorTotal: parseFloat(row.data.valorTotal) || 0,
		};
	});

	if (produtos.length === 0) {
		showAlert('error', 'Adicione ao menos um produto');
		return;
	}

	// Pegar os dados das cotacoes
	const dadosCotacoes = buscaDadosEValidaCotacoes();
	if (!dadosCotacoes) return;

	const { idsCotacoes } = dadosCotacoes;

	formData.set('ids_cotacoes', JSON.stringify(idsCotacoes));
	formData.set("produtos", JSON.stringify(produtos));
	formData.set('motivo_cotacao', $('#motivo_cotacao').val());

	botao
		.prop("disabled", true)
		.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
	
	let url = `${SITE_URL}/PortalCompras/Solicitacoes/adicionarProdutosECotacoes/${idSolicitacao}`;
	axios
		.post(url, formData)
		.then((response) => {
			const data = response.data;

			if (data.status == 1) {
				showAlert('success', 'Produtos e cotações adicionados com sucesso, redirecionando...');

				// Redireciona para a página de solicitações
				setTimeout(() => {
					window.location.href = `${SITE_URL}/PortalCompras/Solicitacoes/index/requisicao/${idSolicitacao}`;
				}, 2000);
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
			botao.prop('disabled', false).html('Salvar');
			$('#motivo_cotacao').val('');
		});
});

$(document).on('click', '.removerCotacao', async function (e) {
	e.stopPropagation();

	const botao = $(this);
	const id = botao.attr('data-id');
	const cotacao = gridCotacoesOptions.api.getRowNode(id).data;
	gridCotacoesOptions.api.updateRowData({ remove: [cotacao] });
});


async function buscarCotacao(id) {
	const url = `${SITE_URL}/PortalCompras/Cotacoes/buscar/${id}`;
	const dados = await axios
		.get(url)
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		});

	return dados.data;
}

$(document).on('click', '.visualizarCotacao', async function (e) {
	e.stopPropagation();

	const botao = $(this);
	const id = botao.attr('data-id');

	botao.html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
	const dados = await buscarCotacao(id);
	botao.html('Visualizar').attr('disabled', false);

	if (dados.status === '1') popularModalParaVisualizarCotacao(dados.cotacao);
	else showAlert('error', dados.mensagem);
});

function popularModalParaVisualizarCotacao(cotacao) {
	resetarCamposModalCotacao();

	const { fornecedor, formaPagamento, condicaoPagamento, pathAnexo, produtos, tipoEspecie } = cotacao;
	const documentoFornecedor = fornecedor.documento.length == 11
		? criarMascara(fornecedor.documento, '###.###.###-##')
		: criarMascara(fornecedor.documento, '##.###.###/####-##');

	$('#fornecedor').select2('trigger', 'select', {
		data: {
			id: fornecedor.id,
			text: `${documentoFornecedor} - ${fornecedor.nome}`,
		},
	});

	$('#forma_pagamento').val(formaPagamento);
	$('#tipo_especie').val(tipoEspecie);
	$('#condicao_pagamento').val(condicaoPagamento?.split(' - ')?.[0]);
	$('#form-cotacoes input').attr('disabled', true);
	$('#form-cotacoes select').attr('disabled', true).trigger('change');

	if (pathAnexo) {
		const nomeArquivo = pathAnexo.split('/').pop();
		let icon = pathAnexo.includes('.pdf') ? 'fa-file-pdf-o' : 'fa-file-image-o';
		$('#viewAnexo-cotacao')
			.html(`<p class="truncade link-upload-valid"><i class="fa ${icon}"></i>  ${nomeArquivo}</p>`)
			.attr('disabled', true);

		$('#visualizar-anexo_cotacao')
			.attr('href', BASE_URL + pathAnexo)
			.show();
	}

	gridProdutosDaCotacaoOptions.api.setRowData(produtos);

	$('#titulo-modal-cotacao').text(`Visualizar Cotação #${cotacao.id}`);
	$('#btn-cadastrar-cotacao').css('display', 'none');
	$('#modal-cotacao').modal('show');
}
