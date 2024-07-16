const gridCaminhoUsuario = document.getElementById('grid-caminho-usuario');
const protocoloDaligacao = 'CA71bca86ce5364effdd689de539c202ef';

 // Todas as funcionalidades da página de detalhes do chamado
$('#show-details').click(function() {
    $('#modal-caminho-usuario').modal('show');
});

$('#titulo-modal-produto').html(`Conversando com ${protocoloDaligacao}`);

// Função de renderização de célula personalizada para o ag-Grid
function renderizarCaminhoUsuario(params) {
  const { pathUser, type } = params.data;
  // To Do: Implementar a lógica para exibir o tempo de chamada
  const time = new Date().toLocaleTimeString();

  const caminhoDosIcones = {
    'usuario': 'user',
    'sistema': 'sitemap',
  };

  return /* HTML */`
    <div class="d-flex w-100 justify-content-between" id="container-caminho-usuario">

    <div style="display: flex; flex-direction: row; margin: 15px 0 0 20px" id="container-info-caminho-usuario">

      <div id="container-icon">
        <i class="fa fa-${caminhoDosIcones[type]}" aria-hidden="true"></i>
      </div>

      <div id="container-infos-caminho-usuario">
        <p id="nome-contato">&nbsp; - &nbsp; ${pathUser}</p>
      </div>

      <div id="container-time">
        <p id="telefone-contato">&nbsp; - &nbsp; ${time}</p>
      </div>

    </div>

    </div>
  `;
}

// Atualize a definição da coluna para usar a função de renderização personalizada
const gridOptionsCaminhoUsuario = {
  localeText: AG_GRID_TRANSLATE_PT_BR,
  columnDefs: [
    {
        field: "infoCall",
        cellRenderer: renderizarCaminhoUsuario,
        flex: 1,
        suppressMenu: true,
        filter: true,
        sortable: true,
        sort: 'asc', 
    }
  ],
  rowData: [],
  rowHeight: 40,
  animateRows: true,
  sortingOrder: ['desc', 'asc'],
  accentedSort: true,
  defaultColDef: {
    flex: 1,
    editable: false,
    resizable: false,
    sortable: false,
  },
  getRowId: (params) => params.data.id,
  overlayLoadingTemplate: mensagemCarregamentoAgGrid,
  overlayNoRowsTemplate: mensagemAgGridSemDados,
  pagination: false,
  headerHeight: 0
};

new agGrid.Grid(gridCaminhoUsuario, gridOptionsCaminhoUsuario);

// Carregar a grade de contatos
// permissaoListarContatos && carregarGridContatos();
carregarGridCaminhoUsuario();

function carregarGridCaminhoUsuario() {
  const url = `${SITE_URL}/webdesk/listarCaminhoDoUsuario/${protocoloDaligacao}`;

  gridOptionsCaminhoUsuario.api.showLoadingOverlay();
  axios
    .get(url)
    .then((response) => {
      const data = response.data;
      gridOptionsCaminhoUsuario.api.setRowData(data);
    })
    .catch((error) => {
      console.error(error);
      gridOptionsCaminhoUsuario.api.setRowData([]);
			showAlert('warning', lang.erro_inesperado);
    })
    .finally(() => {
      gridOptionsCaminhoUsuario.api.hideOverlay();
    });
}