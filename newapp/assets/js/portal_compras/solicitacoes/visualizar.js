const gridProdutosDiv = document.querySelector("#grid-produtos");

const tipoRequisicao = {
	recorrente: 'Recorrente',
	nao_recorrente: 'Não Recorrente',
	contrato: 'Contrato',
}

const formasPagamentos = {
	boleto: 'Boleto',
	pix: 'Pix',
	ted: 'Ted',
	deposito: 'Deposito',
}

const filiais = {
	sata_rita: 'Santa Rita',
	matriz: 'Matriz',
}

// Colunas da AgGrid de Produtos
function columnDefsGridProdutos() {

	let defs = [
		{ field: "codigo", headerName: "Código", flex: 1, minWidth: 80, sort: 'desc' },
		{ field: "descricao", headerName: "Descricao", flex: 1, minWidth: 200 },
		{ field: "quantidade", headerName: "Quantidade", flex: 1, minWidth: 80 },
		{
			field: "valorUnitario", headerName: "Valor Unitário", flex: 1, minWidth: 150,
			cellRenderer: ({ data: { valorUnitario } }) => {
				return formatarParaMoedaBRL(valorUnitario);
			},
			valueGetter: ({ data: { valorUnitario } }) => {
				return formatarParaMoedaBRL(valorUnitario);
			}
		},
		{
			field: "valorTotal", headerName: "Valor Total", flex: 1, minWidth: 150,
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

const gridProdutosOptions = {
	localeText: AG_GRID_TRANSLATE_PT_BR,
	columnDefs: columnDefsGridProdutos(),
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
};

new agGrid.Grid(gridProdutosDiv, gridProdutosOptions);

buscarDadosParaVisualizacao();

async function buscarDadosParaVisualizacao() {
	$('#overlay').css('display', 'block');

	await Promise.all([
		buscarDadosSolicitacaoParaVisualizacao(),
		buscarComentariosSolicitacao(),
		buscarHistoricoDaSolicitacao(),
		listarNotasFiscaisDaSolicitacao(),
	]);

	$('#overlay').css('display', 'none');
}

// Carrega os dados da solicitação
function buscarDadosSolicitacaoParaVisualizacao() {
	// Busca os dados da solicitação
	const url = `${SITE_URL}/PortalCompras/Solicitacoes/buscar/${idSolicitacao}`;
	axios
		.get(url)
		.then((response) => {
			const data = response.data;
			setarDadosSolicitacaoParaVisualizacao(data);
		})
		.catch((error) => {
			console.error(error);
			showAlert('error', lang.erro_inesperado);
		})
}

function setarDadosSolicitacaoParaVisualizacao(data) {
  // Preenche os campos da solicitação
  const solicitacao = data.dados?.solicitacao;
  $('#total-solicitacao')
		.text(formatarParaMoedaBRL(solicitacao.valorTotal))
		.css('display', 'block');

	// Desabilita os botões de aprovacao se o usuário não for um aprovador da solicitação ou se já tiver dado o parecer
	const aprovadores = solicitacao.aprovadores;
	let aprovador = undefined;
	if (aprovadores?.['ceo']?.id == idUsuario) aprovador = aprovadores?.['ceo'];
	else if (aprovadores?.['cfo']?.id == idUsuario) aprovador = aprovadores?.['cfo'];
	else if (aprovadores?.['diretor']?.id == idUsuario) aprovador = aprovadores?.['diretor'];

	$('#div-mensagem-aprovacao-realizada').css('display', 'none');
	$('#div-btn-aprovacao').css('display', 'none');
	if (aprovador && solicitacao.situacao === 'aguardando_aprovacao') {
		if (aprovador.resultado !== 'aguardando') {
			// Desabilita os botões de ação
			$('.btn-modal-aprovacao')
				.attr('disabled', true)
				.removeAttr('data-acao');

			$('#div-mensagem-aprovacao-realizada').css('display', 'block');
		}
		else {
			$('#div-btn-aprovacao').css('display', 'block');
		}
	}
	
	$('#total-solicitacao').text();

	const { empresa, filial, centroCusto } = solicitacao;

	$('#empresa').text(empresa?.nome?.toUpperCase() || 'Não informado');
	$('#filial').text(filial?.nome || 'Não informado');
	$('#centro-custo').text(centroCusto ? `${centroCusto.codigo} - ${centroCusto.descricao}` : 'Não informado');

	$('#tipo-requisicao').text(solicitacao.tipoRequisicao ? tipoRequisicao?.[solicitacao.tipoRequisicao] : 'Não informado');
  $('#motivo-compra').text(solicitacao.motivoCompra || 'Não informado');
	$('#motivo-cotacao').text(solicitacao.motivoCotacao || 'Não informado');
	$('#tipo-solicitacao').text(solicitacao.tipo === 'pedido' ? 'Pedido' : 'Requisição');

  $('#capex').text(solicitacao.capex === 'sim' ? 'Sim' : 'Não');
  $('#rateio').text(solicitacao.reteio === 'sim' ? 'Sim' : 'Não');

	if (solicitacao.anexoRateio) $('#anexo-rateio').html(`<a href="${BASE_URL + solicitacao.anexoRateio}" target="_blank">Visualizar</a>`);
	else $('#anexo-rateio').text('Nenhum anexo enviado');

  if (solicitacao.anexoSolicitacao) $('#anexo-solicitacao').html(`<a href="${BASE_URL + solicitacao.anexoSolicitacao}" target="_blank">Visualizar</a>`);
	else $('#anexo-solicitacao').text('Nenhum anexo enviado');

  // Preenche os produtos
	$('#div-mensagem-sem-produtos').css('display', 'none');
	$('#div-dados-produtos').css('display', 'none');

  const produtos = data.dados?.produtos;
	if (produtos && produtos.length > 0) {
		$('#div-dados-produtos').css('display', 'block');
		produtos.forEach((produto) => {
			gridProdutosOptions.api.updateRowData({ add: [produto] });
		});
	}
	else {
		$('#div-mensagem-sem-produtos').css('display', 'block');
	}

  // Preenche as cotações
	$('#div-mensagem-sem-cotacao').css('display', 'none');
	$('#div-dados-cotacao').css('display', 'none');

  const cotacao = data.dados?.solicitacao?.cotacao;
	if (cotacao) {
		$('#div-dados-cotacao').css('display', 'block');
		const { fornecedor, valorTotal, formaPagamento, condicaoPagamento, tipoEspecie, motivoSelecaoCotacao } = cotacao;
		const documentoFornecedor = fornecedor.documento.length == 11
			? criarMascara(fornecedor.documento, '###.###.###-##')
			: criarMascara(fornecedor.documento, '##.###.###/####-##');
	
		$('#fornecedor').text(`${documentoFornecedor} - ${fornecedor.nome}`);
		$('#valor-total').text(formatarParaMoedaBRL(valorTotal));
		$('#forma-pagamento').text(formasPagamentos?.[formaPagamento]);
		let [codigoCondicao, ...descricaoCondicao] = condicaoPagamento?.split(' - ');
		$('#condicao-pagamento').text(descricaoCondicao.join(' - '));
		$('#tipo-especie').text(tipoEspecie?.toUpperCase());
		$('#motivo-selecao-cotacao').text(motivoSelecaoCotacao || 'Não informado');
	}
	else {
		$('#div-mensagem-sem-cotacao').css('display', 'block');
	}
}