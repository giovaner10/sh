let solicitacoesDagrid = [];

const gridDiv = document.getElementById("grid-registros-chamadas");
let larguraTela = window.innerWidth;

function monitorarLarguraTela() {
  larguraTela = window.innerWidth;
}

window.addEventListener('resize', monitorarLarguraTela);
monitorarLarguraTela();

// Função para formatar a duração da chamada
const formatahhmmss = (s) =>{
  const duas_casas = (numero) => {
    if (numero <= 9){
      numero = "0"+numero;
    }
    return numero;
  }
  hora = duas_casas(Math.round(s/3600));
  minuto = duas_casas(Math.round((s%3600)/60));
  segundo = duas_casas((s%3600)%60);
  formatado = hora+":"+minuto+":"+segundo;
  
  return formatado;
}

const retornaOHash = (pathFile) => {
  const indexOfCom = pathFile.indexOf('.com');
  const hash = pathFile.substring(indexOfCom + 5);
  return hash;
}

const gerarLinkParaDownload = (url) => {
  if(!url) {
    return;
  };
  const hash = retornaOHash(url).replace('/', '_');
  const apiUrl = `${SITE_URL}/AtendimentoOmnilink/RegistrosDeChamadas/download/${hash}`;

  axios
  .get(apiUrl)
  .then((response) => {
    const data = response.data;
    if (data) {
      showAlert('success', 'Seu download será iniciado em uma nova guia.');
    }
    setTimeout(() => {
      window.open(data, '_blank');
    }, 3000);
    
  })
  .catch((error) => {
    console.error(error);
    showAlert('error', lang.erro_inesperado);
  });
}

const ICONE_DE_CARREGAMENTO = '<div class="fa fa-spinner fa-spin" style="width: 1rem; height: 1rem; color:#1C69AD !important; margin-left: 8px;" role="status"><span class="sr-only"></span></div>';

$(document).on("click", "#download_ligacao", function() {
  const btnDownload = $(this);
  const download = btnDownload.data('download');
  if (!download) {
    return;
  }

  // Desabilita o evento de clique no botão de download
  btnDownload.css("pointer-events", "none");

  btnDownload.prepend(ICONE_DE_CARREGAMENTO);

  gerarLinkParaDownload(download);

  setTimeout(() => {
    btnDownload.find('.fa-spinner').remove();

    // Habilita o evento de clique no botão de download
    btnDownload.css("pointer-events", "auto");
  }, 4500);

});

// Renderiza a coluna de ações
function renderizarColunaAcoes(dados) {
	const { data } = dados;
  const naoTemArquivo = data.download === null
  const estiloHabilitado = 'cursor: pointer; text-decoration: none !important; width: 100%; display: block;'
  const estiloDesabilitado = 'cursor: pointer; opacity: 0.5; pointer-events: none; text-decoration: none !important; width: 100%; display: block;'
	
	let botaoFazerDownload = '';
	let botaoRediscagem = '';

  if (permissaoVisualizarRegistroChamadas) {
    botaoFazerDownload = /* HTML */`
			<a class="dropdown-item dropdown-item-acoes" id="download_ligacao" data-download=${data.download} style="${naoTemArquivo ? estiloDesabilitado : estiloHabilitado}" 
				data-id="${data.id}">
				Fazer Download
			</a>`;
	}

	if (permissaoVisualizarRegistroChamadas) {
		botaoRediscagem = /* HTML */`
			<a class="dropdown-item dropdown-item-acoes" data-rediscagem=${data.recebidaPor} id="rediscagem_ligacao" style="cursor: pointer; text-decoration: none !important; width: 100%; display: block;" 
				data-id="${data.id}">
				Fazer Rediscagem
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
        ${botaoFazerDownload}
        ${botaoRediscagem}
      </div>
    </div>`;
}


$(document).on('click', '#rediscagem_ligacao', function (e) {
  e.preventDefault();
  const telefone = $(this).data('rediscagem');
  const nome = $(this).data('nome');
  const abrirModal = $('[data-target="#modalDiscador"]');

  abrirModal.click();

  ativarGuia('chamada-em-curso')

  // Tirar o +55 do telefone
  const telefoneSem55 = telefone.replace('+55', '');

  if(!nome && !telefone) {
    return;
  }

  if(nome) {
    nomeContato.text(nome);
  }
  if(telefone) {
    numeroDestino.value = telefoneSem55;
  }
  btnLigar.click();

  tabDiscador.addClass('active');
  liDiscador.addClass('active');
  tabContatos.removeClass('active');
  liContatos.removeClass('active');
});

// Colunas da AgGrid
const columnDefs = () => {

  let defs = [
    { field: "sid", headerName: "Sid", flex: 1, minWidth: 100, sort: 'desc' },
    { field: "protocolo", headerName: "Protocolo", flex: 1, minWidth: 200 },
    { field: "efetuadaPor", headerName: "Agente", flex: 1, minWidth: 200 },
    { field: "recebidaPor", headerName: "Chamada Recebida Por", flex: 1, minWidth: 200 },
    {
      field: "dataHoraInicio", headerName: "Data/Hora Início", flex: 1, minWidth: 180,
      cellRenderer: ({ data: { dataHoraInicio } }) => {
        return dataHoraInicio ? dayjs(dataHoraInicio).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      },
      valueGetter: ({ data: { dataHoraInicio } }) => {
        return dataHoraInicio ? dayjs(dataHoraInicio).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      }
    },
    {
      field: "dataHoraFim", headerName: "Data/Hora Fim", flex: 1, minWidth: 180,
      cellRenderer: ({ data: { dataHoraFim } }) => {
        return dataHoraFim ? dayjs(dataHoraFim).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      },
      valueGetter: ({ data: { dataHoraFim } }) => {
        return dataHoraFim ? dayjs(dataHoraFim).format('DD/MM/YYYY HH:mm:ss') : '--/--/---- --:--:--';
      }
    },
    { field: "duracao", headerName: "Duração", flex: 1, minWidth: 100, sort: 'desc',
      cellRenderer: ({ data: { duracao } }) => {
        return duracao ? formatahhmmss(duracao) : '--:--:--';
      },
      valueGetter: ({ data: { duracao } }) => {
        return duracao ? formatahhmmss(duracao) : '--:--:--';
      }
    },
  ];

  if (permissaoVisualizarRegistroChamadas) {
		defs.push({
			field: "acoes",
			headerName: lang.acoes,
			width: 85,
			cellRenderer: renderizarColunaAcoes,
			cellClass: "actions-button-cell",
			pinned: larguraTela <= 390 ? '' : 'right',
			sortable: false,
			sort: false,
			resizable: false,
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

new agGrid.Grid(gridDiv, gridOptions);
gridOptions.api.showLoadingOverlay();

const carregarGridRegistrosLigacoes = () => {
  const url = `${SITE_URL}/AtendimentoOmnilink/RegistrosDeChamadas/listar`;

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

function limparSelecteds(){
  $('#dataInicio').val(null).trigger('change');
  $('#dataFim').val(null).trigger('change');
  
  hideError(['dataInicio', 'dataFim']);
  gridOptions.api.setRowData(solicitacoesDagrid);
}

$(document).on('click', '#btnFormFiltroRegistroChamadas', async function (e) {
  e.preventDefault();

  let dataInicio = $('#dataInicio').val();
  let dataFim = $('#dataFim').val();

  hideError(['dataInicio', 'dataFim']);

  if(!dataInicio && !dataFim) {
    showAlert("error","Preencha os campos do filtro" )
  }

  if(dataFim && !dataInicio) return showError('dataInicio','Por favor informe a data inicial');
  if(dataInicio && !dataFim) return showError('dataFim', 'Por favor informe a data final');

  dataFim = dayjs(dataFim + '23:59:59');
  dataInicio = dayjs(dataInicio + '00:00:00');

  if(dataFim.isBefore(dataInicio)) return showError('dataFim','Data final não pode ser menor que a data inicial');


  let dadosFiltrados = [];
  if (solicitacoesDagrid.length) {
    for (const data of solicitacoesDagrid) {
      let { dataHoraInicio } = data;
      
      dataHoraInicio = dayjs(dataHoraInicio);
      
      if(dataHoraInicio.isBefore(dataInicio) || dataHoraInicio.isAfter(dataFim)) continue;

      dadosFiltrados.push(data);
    }
  }

  gridOptions.api.setRowData([])
  gridOptions.api.setRowData(dadosFiltrados);
});
