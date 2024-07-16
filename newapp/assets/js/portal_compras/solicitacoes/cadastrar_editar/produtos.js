let produtosBuscados = [];

$(document).ready(function () {
  $('select#produto-solicitacao').select2({
    width: '100%',
    placeholder: 'Buscar produto',
    allowClear: true,
    minimumInputLength: 3,
    maximumInputLength: 20,
    minimumResultsForSearch: 20,
    language: 'pt-BR',
    ajax: {
      url: `${SITE_URL}/PortalCompras/Produtos/buscarPelaDescricao`,
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
				let results = [];
				data.forEach((produto) => {
					produtosBuscados[produto.codigo] = {
						codigo: produto.codigo,
						descricao: produto.descricao,
						tipo: produto.tipo,
					};

					const capexSelecionado = $('#capex').is(":checked");
					if (capexSelecionado) {
						if (produto.tipo === 'AT') {
							results.push({
								id: produto.codigo,
								text: `${produto.codigo} - ${produto.descricao}`,
							});
						}
					}
					else {
						results.push({
							id: produto.codigo,
							text: `${produto.codigo} - ${produto.descricao}`,
						});
					}
				});

        return {
          results: results,
        };
      },
    },
  });

	if (tipoSolicitacao && tipoSolicitacao === 'pedido' || ['adicionar_produto_cotacao', 'adicionar_cotacao', 'selecionar_cotacao'].includes(acao)) {
		// campos do produto
		$('#produto-nao-encontrado').prop('checked', false);
		$('#div-produto-nao-encontrado').html('');
		$('.campos-add-produtos').css('display', 'block');
	}
});

const gridProdutosDiv = document.querySelector('#grid-produtos');

// Função para resetar os campos do modal
function resetarCamposModalProduto() {
  $('#produto-solicitacao').val('').trigger('change');
  $('#quantidade').val('');
  $('#btn-adicionar-produto').attr('data-acao', 'cadastrar');
}

function renderizarColunaAcoesAdicionarProduto(dados) {
  const { data } = dados;
  const botaoRemoverProduto = /* HTML */ ` <button
    class="btn btn-light btn-sm action-button"
    type="button"
    title="Remover"
    style="margin-top: -6px;"
    onclick="removerProduto('${data.codigo}')"
  >
    <img
      src="${BASE_URL}media/img/new_icons/icon-delete.png"
      height="21"
      title="Remover"
    />
  </button>`;

  const botaoEditarProduto = /* HTML */ ` <button
    class="btn btn-light btn-sm action-button"
    type="button"
    title="Editar"
    style="margin-top: -6px;"
    onclick="abrirProdutoParaEdicao('${data.codigo}')"
  >
    <img
      src="${BASE_URL}assets/css/icon/png/512/edit.png"
      height="21"
      title="Editar"
    />
  </button>`;

  return /* HTML */ `<div>${botaoEditarProduto} ${botaoRemoverProduto}</div>`;
}

// Colunas da AgGrid de Produtos
function columnDefsGridProdutos() {
  let defs = [
    {
      field: 'codigo',
      headerName: 'Código',
      flex: 1,
      minWidth: 80,
      sort: 'desc',
    },
    { field: 'descricao', headerName: 'Descricao', flex: 1, minWidth: 200 },
    { field: 'quantidade', headerName: 'Quantidade', flex: 1, minWidth: 80 },
		{
			field: 'acoes',
			headerName: lang.acoes,
			width: 110,
			flex: false,
			resizable: false,
			cellRenderer: renderizarColunaAcoesAdicionarProduto,
			cellClass: 'actions-button-cell',
			pinned: 'right',
			pdfExportOptions: { skipColumn: true },
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

$(document).on('click', '#btn-novo-produto', function () {
  resetarCamposModalProduto();
  $('#modal-produto').modal('show');
});


$(document).on('change', '#produto-solicitacao', function () {
	$('#quantidade').attr('readonly', true);
	
  const codigoProduto = $(this).val();
  if (codigoProduto) $('#quantidade').removeAttr('readonly');
});

function adicionarProduto() {
  const codigoProduto = $('#produto-solicitacao').val();
  const produto = produtosBuscados?.[codigoProduto];
  const quantidade = $('#quantidade').val();
  const botao = $('#btn-adicionar-produto');
  const acaoProduto = botao.attr('data-acao');

  let produtoJaAdicionado = false;

  let linhasGridprodutos = gridProdutosOptions.api.getModel().rowsToDisplay;
  linhasGridprodutos.map(produto => {
    if(produto.data.codigo == codigoProduto) produtoJaAdicionado = true;
  });

  if (produtoJaAdicionado && acaoProduto === 'cadastrar') {
		showAlert('error', 'Produto já adicionado.');
		return;
	}
  
  const novoProduto = {
    codigo: produto.codigo,
    descricao: produto.descricao,
    quantidade: parseInt(quantidade),
  };
  
  if (acaoProduto === 'cadastrar') {
    gridProdutosOptions.api.updateRowData({ add: [novoProduto] });
  }
  else {
    const nodeProduto = gridProdutosOptions.api.getRowNode(codigoProduto);
    var displayModel = gridProdutosOptions.api.getModel();
    let rowNode = displayModel.rowsToDisplay[nodeProduto.rowIndex];
    rowNode.setData(novoProduto);
  }
  
  resetarCamposModalProduto();
  $('#modal-produto').modal('hide');
}

function resetarCamposModalProduto() {
  $('.input-adicionar-produto').val(null);
  $('select#produto-solicitacao').val(null).trigger('change');
  $('#quantidade').val('').attr('readonly', true);

	$('#titulo-modal-produto').text('Adicionar produto');
  $('#btn-adicionar-produto').attr('data-acao', 'cadastrar');
}

function removerProduto(codigoProduto) {
  const produto = gridProdutosOptions.api.getRowNode(codigoProduto).data;
  gridProdutosOptions.api.updateRowData({ remove: [produto] });
}

function limparCamposProdutos() {
  gridProdutosOptions.api.setRowData([]);

  // remove disabled dos produtos no select
  $('#produto-solicitacao option').removeAttr('disabled');
}

$(document).on('change', '#produto-nao-encontrado', function () {
  const checked = $(this).is(':checked');
  if (checked) $('.campos-add-produtos').css('display', 'none');
	else $('.campos-add-produtos').css('display', 'block');
	
	limparCamposProdutos();
});

function abrirProdutoParaEdicao(codigo) {
  const {data: produto} = gridProdutosOptions.api.getRowNode(codigo);
	$('#quantidade').removeAttr('readonly').val(produto.quantidade || '');
  $('#produto-solicitacao').select2('trigger', 'select', {
    data: {
      id: produto.codigo,
      text: `${produto.codigo} - ${produto.descricao}`,
    },
  });

	$('#titulo-modal-produto').text(`Editar produto #${produto.codigo}`);
  $('#btn-adicionar-produto').attr('data-acao', 'editar');
  $('#modal-produto').modal('show');
}


$(document).on('click', '#capex', async function (e) {
	const checked = $(this).prop('checked');

	if (checked) {
		let produtosTabela = gridProdutosOptions.api.getModel().rowsToDisplay;
		if (produtosTabela.length > 0) {
			produtosTabela.forEach((produto) => {
				if (produtosBuscados?.[produto.data.codigo]?.tipo !== 'AT') {
					gridProdutosOptions.api.updateRowData({ remove: [produto.data] });
				}
			});
		}
	}
});