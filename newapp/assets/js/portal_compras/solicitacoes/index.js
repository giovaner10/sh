let solicitacoesDagrid = [];

$(document).ready(function () {
  $('form select').select2({
    width: '100%',
    language: 'pt-BR',
  });

  $('select#centro_custo').select2({
    placeholder: 'Selecione um centro de custo',
    allowClear: true,
    width: '100%',
    language: 'pt-BR',
  });


  $('select#situacao-portal-filtro').select2({
    placeholder: 'Selecione uma situação',
    allowClear: true,
    width: '100%',
    language: 'pt-BR',
  });

  $('select#diretor').select2({
    placeholder: 'Selecione um requerente',
    allowClear: true,
    width: '100%',
    language: 'pt-BR',
  });

  $('#viewAnexo-boleto').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);
	$('#viewAnexo-nota').html(`<p style="color: #dc3545;" class="truncade">Nenhum arquivo selecionado.</p>`);

  new agGrid.Grid(gridDiv, gridOptions);
  carregarGridSolicitacoes();

	carregarDiretores();
});

function limparSelecteds() {
  $('#situacao-portal-filtro').val(null).trigger('change');
  $('#centro_custo').val(null).trigger('change');
  $('#diretor').val(null).trigger('change');
  $('#dataInicio').val(null).trigger('change');
  $('#dataFim').val(null).trigger('change');

  hideError(['dataInicio', 'dataFim', 'diretor', 'situacao-portal-filtro', 'centro_custo']);
  gridOptions.api.setRowData(solicitacoesDagrid);
}

const gridDiv = document.querySelector("#grid-solicitacoes");

const aprovacoes = {
  aprovado: 'Aprovado',
  reprovado: 'Reprovado',
  aguardando: 'Aguardando'
}

const situacoes = {
  aguardando_produto_cotacao: 'Aguardando Produto e Cotação',
  aguardando_cotacao: 'Aguardando Cotação',
  aguardando_confirmacao_cotacao: 'Aguardando Selecionar a Cotação',
  aguardando_aprovacao: 'Aguardando Aprovação',
  aprovado: 'Aprovado',
  reprovado: 'Reprovado',
  aguardando_pre_nota: 'Aguardando Pré-Nota',
  aguardando_fiscal: 'Aguardando Fiscal',
  aguardando_boleto: 'Aguardando Boleto',
  finalizado: 'Finalizado',
}

// Renderiza a coluna de ações
function renderizarColunaAcoes(dados) {
  const { data } = dados;

  let botaoVisualizarSolicitacao = '';
  let botaoRemoverSolicitacao = '';
  let botaoEditarSolicitacao = '';
  let botaoAdicionarCotacao = '';
  let botaoAdicionarProdutoECotacao = '';
  let botaoSelecionarCotacao = '';
  let botaoIncluirNotaFiscal = ''
  let botaoIncluirBoleto = '';

  if (permissaoVisualizarSolicitacoes) {
    botaoVisualizarSolicitacao = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes visualizarSolicitacao item-menu-acoes-grid" data-id="${data.id}">
        Visualizar
      </a>`;
  }

  const acessoEditarSolicitacao = permissaoEditarSolicitacoes
    && ['aguardando_produto_cotacao', 'aguardando_cotacao'].includes(data.situacao) && funcaoUsuario === 'solicitante'
		&& data.idUsuario == idUsuario && !data.idUsuarioComprasAtuante;

  if (acessoEditarSolicitacao) {
    botaoEditarSolicitacao = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes editarSolicitacao item-menu-acoes-grid" data-id="${data.id}">
        Editar
      </a>`;
  }

  const acessoAdicionarCotacao = data.situacao === 'aguardando_cotacao' && funcaoUsuario === 'area_compras';
  if (acessoAdicionarCotacao) {
    botaoAdicionarCotacao = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes acaoSobreProdutoECotacao item-menu-acoes-grid" data-id="${data.id}" data-id_usuario_compras="${data.idUsuarioComprasAtuante || ''}" data-acao="adicionar_cotacao">
        Adicionar Cotação
      </a>`;
  }

  const acessoAdicionarProdutoECotacao = data.situacao === 'aguardando_produto_cotacao' && funcaoUsuario === 'area_compras';
  if (acessoAdicionarProdutoECotacao) {
    botaoAdicionarProdutoECotacao = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes acaoSobreProdutoECotacao item-menu-acoes-grid" data-id="${data.id}" data-id_usuario_compras="${data.idUsuarioComprasAtuante || ''}" data-acao="adicionar_produto_cotacao">
        Adicionar Produto e Cotação
      </a>`;
  }

  const acessoSelecionarCotacao = data.situacao === 'aguardando_confirmacao_cotacao' && funcaoUsuario === 'solicitante';
  if (acessoSelecionarCotacao) {
    botaoSelecionarCotacao = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes acaoSobreProdutoECotacao item-menu-acoes-grid" data-id="${data.id}" data-id_usuario_compras="${data.idUsuarioComprasAtuante || ''}" data-acao="selecionar_cotacao">
        Selecionar Cotação
      </a>`;
  }

  if (permissaoRemoverSolicitacoes) {
    botaoRemoverSolicitacao = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes removerSolicitacao item-menu-acoes-grid" data-id="${data.id}">
        Remover
      </a>`;
  }

  const acessoIncluirNotaFiscal = permissaoIncluirNotaFiscal
    && data.numeroPedido
    && data.situacao === 'aguardando_pre_nota'
    && funcaoUsuario === 'area_fiscal'
    && data.tipoEspecie === 'outro';

  if (acessoIncluirNotaFiscal) {
    botaoIncluirNotaFiscal = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes incluirNotaFiscal item-menu-acoes-grid" data-id="${data.id}" data-numero_pedido="${data.numeroPedido}" >
        Incluir Nota Fiscal
      </a>`;
  }

  const acessoIncluirBoleto = permissaoIncluirBoleto
    && data.numeroPedido
    && data.situacao === 'aguardando_boleto'
		&& ( (data.tipoSolicitacao === 'pedido' && funcaoUsuario === 'solicitante' && idUsuario == data.idUsuario) || (data.tipoSolicitacao === 'requisicao' && funcaoUsuario === 'area_compras') );

  if (acessoIncluirBoleto) {
    botaoIncluirBoleto = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes incluirBoleto item-menu-acoes-grid" data-id="${data.id}">
        Incluir Boleto
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
        ${botaoVisualizarSolicitacao}
        ${botaoEditarSolicitacao}
        ${botaoAdicionarCotacao}
        ${botaoAdicionarProdutoECotacao}
        ${botaoSelecionarCotacao}
        ${botaoRemoverSolicitacao}
        ${botaoIncluirNotaFiscal}
        ${botaoIncluirBoleto}
      </div>
    </div>`;
}

const classeResultadoSolicitacao = {
  aguardando: "solicitacaoAguardando",
  reprovado: "solicitacaoReprovado",
  aprovado: "solicitacaoAprovado",
}

// Colunas da AgGrid
function columnDefs() {

  let defs = [
    { field: "id", headerName: "ID", flex: 1, maxWidth: 80, sort: 'desc' },
    { field: "nomeUsuario", headerName: "Nome do Requerente", flex: 1, minWidth: 250 },
    {
      field: "fornecedor", headerName: "Fornecedor", flex: 1, minWidth: 200,
      cellRenderer: ({ data: { fornecedor } }) => {
        const documentoFornecedor = fornecedor && fornecedor.documento
          ? (fornecedor.documento.length === 11
            ? criarMascara(fornecedor.documento, '###.###.###-##')
            : criarMascara(fornecedor.documento, '##.###.###/####-##'))
          : '';
        return fornecedor ? `${documentoFornecedor} - ${fornecedor.nome}` : '-----';
      }, valueGetter: ({ data: { fornecedor } }) => {
        const documentoFornecedor = fornecedor && fornecedor.documento
          ? (fornecedor.documento.length === 11
            ? criarMascara(fornecedor.documento, '###.###.###-##')
            : criarMascara(fornecedor.documento, '##.###.###/####-##'))
          : '';
        return fornecedor ? `${documentoFornecedor} - ${fornecedor.nome}` : '-----';
      }
    },
		{
			field: "situacao", headerName: "Situação", flex: 1, minWidth: 200,
			cellRenderer: ({ data }) => {
				if (['aguardando_produto_cotacao', 'aguardando_cotacao'].includes(data.situacao) && data.idUsuarioComprasAtuante && funcaoUsuario === 'solicitante') {
					return 'Área de Compras Atuando';
				}

				if (data.situacao === 'aguardando_boleto') {
					if (data.tipoSolicitacao === 'pedido') return 'Aguardando Boleto (Solicitante)';
					else if (data.tipoSolicitacao === 'requisicao') return 'Aguardando Boleto (Área de Compras)';
					else return 'Aguardando Boleto';
				}

				return situacoes?.[data.situacao] || 'Não Encontrado';
			},
			valueGetter: ({ data }) => {
				if (['aguardando_produto_cotacao', 'aguardando_cotacao'].includes(data.situacao) && data.idUsuarioComprasAtuante && funcaoUsuario === 'solicitante') {
					return 'Área de Compras Atuando';
				}

				if (data.situacao === 'aguardando_boleto') {
					if (data.tipoSolicitacao === 'pedido') return 'Aguardando Boleto (Solicitante)';
					else if (data.tipoSolicitacao === 'requisicao') return 'Aguardando Boleto (Área de Compras)';
					else return 'Aguardando Boleto';
				}

				return situacoes?.[data.situacao] || 'Não Encontrado';
			},
			cellStyle: ({ data: { situacao } }) => {
				switch (situacao) {
					case 'aguardando_produto_cotacao': return { 'background-color': 'rgb(255, 247, 225)' };
					case 'aguardando_cotacao': return { 'background-color': 'rgb(255, 247, 225)' };
					case 'aguardando_confirmacao_cotacao': return { 'background-color': 'rgb(255, 247, 225)' };
					case 'aguardando_aprovacao': return { 'background-color': 'rgb(255, 247, 225)' };
					case 'aprovado': return { 'background-color': 'rgb(228, 255, 225)' };
					case 'reprovado': return { 'background-color': 'rgb(224, 130, 130)' };
					case 'aguardando_pre_nota': return { 'background-color': 'rgb(255, 247, 225)' };
					case 'aguardando_fiscal': return { 'background-color': 'rgb(255, 247, 225)' };
					case 'aguardando_boleto': return { 'background-color': 'rgb(255, 247, 225)' };
					case 'finalizado': return { 'background-color': 'rgb(228, 255, 225)' };
					default: '';
				}
			},
		},
		{
			field: "numeroPedido", headerName: "Código do Pedido ERP", flex: 1, minWidth: 180,
			cellRenderer: ({ data: { numeroPedido } }) => {
				return numeroPedido || '-----';
			},
			valueGetter: ({ data: { numeroPedido } }) => {
				return numeroPedido || '-----';
			}
		},
    { field: "centroCusto", headerName: "Centro de Custo", flex: 1, minWidth: 250 },
    { field: "quantidadeProdutos", headerName: "Quantidade de Produtos", flex: 1, minWidth: 150 },
    {
      field: "dataCadastro", headerName: "Data de Cadastro", flex: 1, minWidth: 200,
      cellRenderer: ({ data: { dataCadastro } }) => {
        return dataCadastro ? dayjs(dataCadastro).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      },
      valueGetter: ({ data: { dataCadastro } }) => {
        return dataCadastro ? dayjs(dataCadastro).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      }
    },
    { field: "aprovacoes", headerName: "Aprovações", flex: 1, minWidth:160, pdfExportOptions: { skipColumn: true },
      cellRenderer: ({ data: {aprovadores, id} }) => {
				let htmlDiretor = '';
				let htmlCFO = '';
				let htmlCEO = '';

				if (!aprovadores || Object.keys(aprovadores).length === 0){
					return /*html*/`<div style="text-align: center;">-----</div>`;
				}

				if (aprovadores.diretor) {
					const classeResultado = classeResultadoSolicitacao[aprovadores.diretor.resultado];
					htmlDiretor = /*html*/`<div class="timeline-steps">
						<div class="timeline-content" data-aprovador-nome="${aprovadores.diretor.nome}" data-aprovador-resultado="${aprovadores.diretor.resultado}">
							<span class="conexao-circulo" style="left:0px;"></span>
							<div class="inner-circle ${classeResultado}"></div>
							<span class="conexao-circulo"></span>
						</div>
					</div>`;
				}

				if (aprovadores.cfo) {
					const classeResultado = classeResultadoSolicitacao[aprovadores.cfo.resultado];
					htmlCFO = /*html*/`<div class="timeline-steps">
						<div class="timeline-content" data-aprovador-nome="${aprovadores.cfo.nome}" data-aprovador-resultado="${aprovadores.cfo.resultado}">
							<span class="conexao-circulo" style="left:0px;"></span>
							<div class="inner-circle ${classeResultado}"></div>
							<span class="conexao-circulo"></span>
						</div>
					</div>`;
				}

				if (aprovadores.ceo) {
					const classeResultado = classeResultadoSolicitacao[aprovadores.ceo.resultado];
					htmlCEO = /*html*/`<div class="timeline-steps">
						<div class="timeline-content" data-aprovador-nome="${aprovadores.ceo.nome}" data-aprovador-resultado="${aprovadores.ceo.resultado}">
							<span class="conexao-circulo" style="left:0px;"></span>
							<div class="inner-circle ${classeResultado}"></div>
							<span class="conexao-circulo"></span>
						</div>
					</div>`;
				}

				return /*html*/`<div onclick="abrirModalDetalhesAprovacoes(${id})" style="text-align: center; cursor:pointer">${htmlDiretor}${htmlCFO}${htmlCEO}</div>`;      
      }
    },
    {
      field: "acoes",
      headerName: lang.acoes,
      width: 85,
      flex: false,
      resizable: false,
      sortable: false,
      cellRenderer: renderizarColunaAcoes,
      cellClass: "actions-button-cell",
      pinned: 'right',
      pdfExportOptions: { skipColumn: true },
    }
  ];

	if (funcaoUsuario === 'area_compras') {
		defs.push({
			field: "atuante", headerName: "Atuante", flex: 1, minWidth: 200,
			cellRenderer: ({ data: { idUsuarioComprasAtuante } }) => {
				let atuante = 'Não Possui Atuante';
				if (idUsuarioComprasAtuante && idUsuarioComprasAtuante == idUsuario) atuante = 'Estou Atuando';
				else if (idUsuarioComprasAtuante && idUsuarioComprasAtuante != idUsuario) atuante = 'Outro Usuário Atuando';
				return atuante;
			},
			valueGetter: ({ data: { idUsuarioComprasAtuante } }) => {
				let atuante = 'Não Possui Atuante';
				if (idUsuarioComprasAtuante && idUsuarioComprasAtuante == idUsuario) atuante = 'Estou Atuando';
				else if (idUsuarioComprasAtuante && idUsuarioComprasAtuante != idUsuario) atuante = 'Outro Usuário Atuando';
				return atuante;
			},
			cellStyle: ({ data: { idUsuarioComprasAtuante } }) => {
				if (idUsuarioComprasAtuante) return { 'background-color': 'rgb(255, 247, 225)' };
				return '';
			},
		});

	}

  return defs;
}

const gridOptions = {
  localeText: AG_GRID_TRANSLATE_PT_BR,
  columnDefs: columnDefs(),
  rowData: [],
  animateRows: true,
  sortingOrder: ['desc', 'asc'],
  accentedSort: true,
  defaultColDef: {
    flex: 1,
    editable: false,
    resizable: true,
    sortable: true,
    suppressMenu: true,
  },
  getRowId: (params) => params.data.id,
  getRowStyle: (params) => {
    const id = params.data.id;
    if (idSolicitacao && id == idSolicitacao) {
      return { 'background-color': 'rgba(34, 68, 204, 0.15)' }
    }
  },
  postSortRows: params => {
    if (idSolicitacao) {
      let rowNodes = params.nodes;
      // here we put Ireland rows on top while preserving the sort order
      let nextInsertPos = 0;
      for (let i = 0; i < rowNodes.length; i++) {
        const id = rowNodes[i].data.id;
        if (id == idSolicitacao) {
          rowNodes.splice(nextInsertPos, 0, rowNodes.splice(i, 1)[0]);
          nextInsertPos++;
        }
      }
    }
  },
  sideBar: {
    toolPanels: [
      {
        id: "columns",
        labelDefault: "Colunas",
        iconKey: "columns",
        toolPanel: "agColumnsToolPanel",
        toolPanelParams: {
          suppressRowGroups: true,
          suppressValues: true,
          suppressPivots: true,
          suppressPivotMode: true,
          suppressColumnFilter: false,
          suppressColumnSelectAll: false,
          suppressColumnExpandAll: true,
        },
      },
    ],
    defaultToolPanel: false,
  },
  overlayLoadingTemplate: mensagemCarregamentoAgGrid,
  overlayNoRowsTemplate: mensagemAgGridSemDados,
  pagination: true,
  paginationPageSize: 10,
};

function carregarGridSolicitacoes() {
  const url = `${SITE_URL}/PortalCompras/Solicitacoes/listar/${tipoSolicitacao}`;

  gridOptions.api.showLoadingOverlay();
  axios
    .get(url)
    .then((response) => {
      const data = response.data;
      solicitacoesDagrid = data;
      gridOptions.api.setRowData(data);
    })
    .catch((error) => {
      console.error(error);
      gridOptions.api.setRowData([]);
      showAlert('error', lang.erro_inesperado);
    })
    .finally(() => {
      gridOptions.api.hideOverlay();
    });
}

$(document).on('click', '.visualizarSolicitacao', async function (e) {
  e.stopPropagation();

  const botao = $(this);
  const id = botao.attr('data-id');
  const url = `${SITE_URL}/PortalCompras/Solicitacoes/visualizar/${id}`;

  // Redireciona para a página de edição
  window.location.href = url;
});

$(document).on('click', '.editarSolicitacao', async function (e) {
  e.stopPropagation();

  const botao = $(this);
  const id = botao.attr('data-id');
  const url = `${SITE_URL}/PortalCompras/Solicitacoes/editar/${id}`;

  // Redireciona para a página de edição
  window.location.href = url;
});

$(document).on('click', '.acaoSobreProdutoECotacao', async function (e) {
  e.stopPropagation();

  const botao = $(this);
  const idSolicitacao = botao.attr('data-id');
  const acao = botao.attr('data-acao');
  const idUsuarioCompras = botao.attr('data-id_usuario_compras');

  const url = `${SITE_URL}/PortalCompras/Solicitacoes/acaoSobreProdutoECotacao/${idSolicitacao}/${acao}`;

	if (['adicionar_produto_cotacao', 'adicionar_cotacao'].includes(acao)) {
		if (idUsuarioCompras && idUsuarioCompras != idUsuario) {
			const confirmouVincularSolicitacao = await confirmarAcao({
				titulo: 'Atenção! Esta solicitação já esta sendo tratada por outro usuário, deseja vincular a solicitação a você?',
				texto: 'Esta ação não poderá ser desfeita!'
			});
	
			if (!confirmouVincularSolicitacao) return;
		}
	}

  // Redireciona para a página da acao
  window.location.href = url;
});

function abrirModalDetalhesAprovacoes(id){
  const minhaSolicitacao = solicitacoesDagrid.filter((solicitacao) => solicitacao.id == id)?.[0];
  const { aprovadores } = minhaSolicitacao;
	let htmlDiretor = "";
	let htmlCFO = "";	
	let htmlCEO = "";

  const existeReprovacao = Object.values(aprovadores).some(aprovador => aprovador.resultado === 'reprovado');

	for (const [key, aprovador] of Object.entries(aprovadores)) {
		const classeResultado = classeResultadoSolicitacao[aprovador.resultado];
		const html = /*HTML*/ `
		<ul class="steps-aprovacao" id="stepsList">
    	<li>
				<div class="aprovador-modal truncade" style="text-transform:uppercase; width: 150px;">${aprovador.nome}</div>
				<p class="resultado-modal" id="${`${classeResultado}-modal-aprovacao`}" style="opacity:1">
          ${(existeReprovacao && aprovador.resultado === 'aguardando') ? 'Interrompido' : aprovacoes[aprovador.resultado]}
        </p>
				<div class="data-modal-aprovacao" style="font-size:13px; opacity:0.3">
					${aprovador?.dataHora ? dayjs(aprovador?.dataHora).format("DD/MM/YYYY HH:mm:ss") : "--/--/---- --:--"}
				</div>
				<div class="timeline-steps">
					<div class="timeline-content" data-aprovador-nome="${aprovadores.diretor.nome}" data-aprovador-resultado="${aprovadores.diretor.resultado}" >
						<span class="conexao-circulo-modal" style="left:-60px;"></span>
						<div class="inner-circle-modal ${`${classeResultado}-modal`}"></div>
							<span class="conexao-circulo-modal"></span>
						</div>
					</div>
				</div>
			</li>
    </ul>`;

		if (key === "diretor") htmlDiretor = html;
		if (key === "cfo") htmlCFO = html;
		if (key === "ceo") htmlCEO = html;

	}
  
  $("#step-modal-aprovacao").html(htmlDiretor + htmlCFO + htmlCEO);
  $('#modal-detalhe-situacao').modal('show');
}

async function carregarDiretores() {
  let url = `${SITE_URL}/usuarios/listarUsuariosPortal/solicitante`;
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
  const selectDiretor = $('#diretor');
  selectDiretor.empty();

  if (diretores.length > 0) {
    diretores.forEach(diretor => {
      selectDiretor.append(`<option value="${diretor.id}">${diretor.nome}</option>`);
    });
  }

  selectDiretor.val(null).trigger('change');
}

$(document).on('click', '#btnFormFiltroPortal', async function (e) {
  e.preventDefault();


  let dataInicio = $('#dataInicio').val();
  let dataFim = $('#dataFim').val();
  let situacaoPortal = $('#situacao-portal-filtro').val();
  let centroCustoFiltro = $('#centro_custo option:selected').text();
  let requerente = $('#diretor option:selected').text();

  hideError(['dataInicio', 'dataFim']);

  if (!dataInicio && !dataFim && !situacaoPortal && !centroCustoFiltro && !requerente) {
    showAlert("error", "Preencha pelo menos um dos campos do filtro")
  }

  if (dataInicio && !dataFim) return showError('dataFim', 'Por favor informe a data final');
  if (dataFim && !dataInicio) return showError('dataInicio', 'Por favor informe a data inicial');

  dataFim = dayjs(dataFim + '23:59:59');
  dataInicio = dayjs(dataInicio + '00:00:00');

  if (dataFim.isBefore(dataInicio)) return showError('dataFim', 'Data final não pode ser menor que a data inicial');


  let dadosFiltrados = [];
  if (solicitacoesDagrid.length) {
    for (const data of solicitacoesDagrid) {
      let { dataCadastro, centroCusto, nomeUsuario, situacao } = data;

      dataCadastro = dayjs(dataCadastro);

      if (dataCadastro.isBefore(dataInicio) || dataCadastro.isAfter(dataFim)) continue;
      if (centroCustoFiltro && centroCustoFiltro != centroCusto) continue;
      if (situacaoPortal && situacaoPortal != situacao) continue;
      if (requerente && requerente != nomeUsuario) continue;

      dadosFiltrados.push(data);
    }
  }

  gridOptions.api.setRowData([])
  gridOptions.api.setRowData(dadosFiltrados);
});