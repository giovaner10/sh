const divGridAgenda = document.querySelector("#grid-agenda");
const btnAddContatoLista = $('#btn-add-contato-listar');
const tabContatosListar = $("#tab-contatos");

// Função de renderização de célula personalizada para o ag-Grid
function renderizarContato(params) {
  const data = params.data;

  let botaoEditarContato = '';
  let botaoRemoverContato = '';

  if (permissaoEditarContato) {
    botaoEditarContato = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes editar-contato" style="cursor: pointer; text-decoration: none !important; width: 100%; display: block;" 
        data-id="${data.id}">
        ${lang.editar}
      </a>`;
  }

  if (permissaoRemoverContato) {
    botaoRemoverContato = /* HTML */`
      <a class="dropdown-item dropdown-item-acoes excluir-contato" style="cursor: pointer; color: red !important; text-decoration: none !important; width: 100%; display: block;"
        data-id="${data.id}">
        Excluir
      </a>`;
  }

  return /* HTML */`
    <div class="d-flex w-100 justify-content-between" id="container-contato">

    <div id="container-info-botoes">

      <div id="container-infos-contato">
        <p id="nome-contato">${data.nome}</p>
        <p id="telefone-contato">${data.telefone}</p>
      </div>

      <div id="container-botoes">

        <button class="btn btn-light btn-sm" id="botao-rediscagem" data-telefone="${data.telefone}" data-nome="${data.nome}">
          <img width="20" src="${BASE_URL + 'media/img/new_icons/omnicom/icon-iniciar-chamada.svg'}">
        </button>

        <div class="dropdown dropdown_${data.id}">
          <button
            class="btn btn-light btn-sm dropdown-toggle-contatos ligar-contato"
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

          <div class="dropdown-menu dropdown-menu-right shadow" id="dropdown-menu-contatos" aria-labelledby="dropdownMenuButton_${data.id}">
            ${botaoEditarContato}
            ${botaoRemoverContato}
          </div>

        </div>

      </div>

      </div>

    </div>
  `;
}

// Atualize a definição da coluna para usar a função de renderização personalizada
const gridOptions2 = {
  localeText: AG_GRID_TRANSLATE_PT_BR,
  columnDefs: [
    {
        field: "nome",
        cellRenderer: renderizarContato,
        maxWidth: 265,
        flex: 1,
        suppressMenu: true,
        filter: true,
        sortable: true,
        sort: 'asc', 
    },
    {
        field: "telefone",
        hide: true,
        suppressMenu: true,
        filter: true,
    }
  ],
  rowData: [],
  rowHeight: 55,
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


new agGrid.Grid(divGridAgenda, gridOptions2);

// Carregar a grade de contatos
permissaoListarContatos && carregarGridContatos();

function carregarGridContatos() {
  const url = `${SITE_URL}/AtendimentoOmnilink/CanalVoz/listarContatos`;

  gridOptions2.api.showLoadingOverlay();
  axios
    .get(url)
    .then((response) => {
      const data = response.data;
      gridOptions2.api.setRowData(data);
    })
    .catch((error) => {
      console.error(error);
      gridOptions2.api.setRowData([]);
			showAlert('warning', lang.erro_inesperado);
    })
    .finally(() => {
      gridOptions2.api.hideOverlay();
    });
}

function pesquisarNaAggridContatos(elemento) {
	const valor = $(elemento).val();
	gridOptions2.api.setQuickFilter(valor);
}

btnAddContatoLista.click(() => {
  $('#botao-salvar-contato').removeAttr('data-id');
  $('#botao-salvar-contato').attr('data-acao', 'cadastrar');
  $('#titulo-modal-contato').text('Criar Contato');
  ativarGuia('guia-criar-editar-contato');

  ativarNavLinkContatos();
});
