var localeText = AG_GRID_LOCALE_PT_BR;

document.addEventListener("DOMContentLoaded", async function () {
  console.log("carregado");
  var dropdown = $("#opcoes_exportacao");
  var result = [];
  var resultadoAuxiliar = [];
  const gridOptions = {
    columnDefs: [
      { headerName: "ID", field: "id" },
      { headerName: "Modelo", field: "modelo" },
      { headerName: "Serial", field: "serial" },
      { headerName: "Linha", field: "linha" },
      { headerName: "Operadora", field: "operadora" },
      { headerName: "Placa", field: "placa" },
      { headerName: "Estado", field: "estado" },
      { headerName: "Solicitante", field: "solicitante" },
      { headerName: "OS", field: "os" },
      { headerName: "Data", field: "data" },
      {
        headerName: "Destino",
        field: "destino",
        cellRenderer: function (params) {
          return params.data.destino;
        },
      },
      { headerName: "Tipo de Envio", field: "tipoDeEnvio" },
      {
        headerName: "Rastreio",
        field: "rastreio",
        cellRenderer: function (params) {
          return params.data.rastreio;
        },
      },
      { headerName: "Gerenciar", 
      field: "gerenciar", 
      pinned: 'right',                
      width: "100px",
      cellClass: "actions-button-cell",
      cellRenderer: function(options) {

          let data = options.data;
          let id = data.id;
          let i = options.rowIndex;
          if(i > 9){
            i = 9;
          }

          return `
          <div class="dropdown" style="position: absolute;">
              <button  class="btn btn-dropdown dropdown-toggle mydropheight" type="button" id="mydropheight" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                  <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
              </button>
              <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="btnsAdministrar${id}" style="position:absolute; left: -183px; top: calc(90% - ${i * 18}px);" aria-labelledby="{buttonId}">
                
                  <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                      ${data.botaoDataChegada}
                  </div>

                  <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                        ${data.botaoConfirmarInstalacao}
                  </div>

                  <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                       ${data.botaoVerOrdem}
                  </div>

                  <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                       ${data.botaoDevolucao}
                  </div>

              </div>
          </div>`;
      }},
    ],
    rowData: [],
    pagination: true,
    defaultColDef: {
      resizable: true,
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
            width: 100,
          },
        },
      ],
      defaultToolPanel: false,
    },
    paginationPageSize: $("#select-quantidade-por-contatos-corporativos").val(),
    localeText: localeText,
  };
  var gridDiv = document.querySelector("#tableContatos");
  new agGrid.Grid(gridDiv, gridOptions);
  preencherExportacoes(gridOptions);

  let menuAberto = false;


  async function carregarDados() {
    updateData()
    $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
    try {
      const response = await $.ajax({
        url: Router,
        type: "POST",
        error: function (xhr, status, error) {
          console.error("Erro na requisição Ajax:", error);
        },
      });

      return JSON.parse(response).aaData;
    } catch (error) {
      console.error("Erro ao carregar dados:", error);
      return [];
    }
  }

  async function buscarDados() {
    updateData()
    $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
    try {
      const response = await $.ajax({
        url: RouterGet + '/?lsearch=' + $('#filtrar-atributos').val().toString(),
        type: "POST",
        error: function (xhr, status, error) {
          console.error("Erro na requisição Ajax:", error);
        },
      });

      updateData(JSON.parse(response).aaData);
    } catch (error) {
      console.error("Erro ao carregar dados:", error);
      return [];
    }
  }

  function updateData(newData = []) {
    gridOptions.api.setRowData(newData);
  }

  $(".btn-expandir").on("click", function (e) {
    e.preventDefault();
    menuAberto = !menuAberto;

    if (menuAberto) {
      $(".img-expandir").attr(
        "src",
        `${BaseURL}/assets/images/icon-filter-show.svg`
      );
      $("#filtroBusca").hide();
      $(".acoes-logistica").hide();
      $(".menu-interno").hide();
      $("#conteudo").removeClass("col-md-9");
      $("#conteudo").addClass("col-md-12");
    } else {
      $(".img-expandir").attr(
        "src",
        `${BaseURL}/assets/images/icon-filter-hide.svg`
      );
      $("#filtroBusca").show();
      $(".menu-interno").show();
      $(".acoes-logistica").show();
      $("#conteudo").css("margin-left", "0px");
      $("#conteudo").removeClass("col-md-12");
      $("#conteudo").addClass("col-md-9");
    }
  });

  $(".menu-interno-link").click(function () {
    $(".menu-interno-link").removeClass("selected");
    $(this).addClass("selected");
  });

  $("#BtnPesquisar").on("click", function (e) {
    e.preventDefault()
    console.log('adicao de filtro')
       buscarDados()
  });

  $("#BtnLimparFiltro").on("click", async function () {
    $("#filtrar-atributos").val("");
    updateData(await carregarDados());
  });

  result = await carregarDados();
  updateData(result);

  $("#dropdownMenuButton").click(function () {
    dropdown.toggle();
  });

  $(document).click(function (event) {
    if (
      !dropdown.is(event.target) &&
      event.target.id !== "dropdownMenuButton"
    ) {
      dropdown.hide();
    }
  });
});
