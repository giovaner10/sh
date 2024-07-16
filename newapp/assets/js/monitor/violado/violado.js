var localeText = AG_GRID_LOCALE_PT_BR;

document.addEventListener("DOMContentLoaded", async function () {
  var result = [];
  var resultadoAuxiliar = [];
  const gridOptions = {
    columnDefs: [
      {
        headerName: "Nome",
        field: "nome",
        chartDataType: "category",
        flex: 1,
      },
      {
        headerName: "Ações",
        width: 120,
        maxWidth: 120,
        pinned: "right",
        cellRenderer: function (options) {
          return '<button onclick="show()" id="res">Visualizar</button>';
        },
      },
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

  $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');

  async function carregarDados() {
    try {
      const response = await $.ajax({
        url: Router + "/equipamentoVioladoCampos",
        type: "POST",
        error: function (xhr, status, error) {
          console.error("Erro na requisição Ajax:", error);
        },
      });

      return JSON.parse(response);
    } catch (error) {
      console.error("Erro ao carregar dados:", error);
      return [];
    }
  }

  function updateData(newData) {
    gridOptions.api.setRowData(newData);
  }
  // .ag-header-cell

  $("#select-quantidade-por-contatos-corporativos").change(function () {
    gridOptions.api.paginationSetPageSize($(this).val());
  });

  $(".btn-expandir").on("click", function (e) {
    e.preventDefault();
    menuAberto = !menuAberto;

    if (menuAberto) {
      $(".img-expandir").attr(
        "src",
        `${BaseURL}/assets/images/icon-filter-show.svg`
      );
      $("#filtroBusca").hide();
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
      $("#conteudo").css("margin-left", "0px");
      $("#conteudo").removeClass("col-md-12");
      $("#conteudo").addClass("col-md-9");
    }
  });

  $(".menu-interno-link").click(function () {
    $(".menu-interno-link").removeClass("selected");
    $(this).addClass("selected");
  });

  $("#filtrar-atributos").on("keyup", function () {
    const inputValue = $(this).val().toString().toLowerCase();
    console.log("caiu aqui");

    const filteredResult = result.filter((item) =>
      item.nome.toString().toLowerCase().includes(inputValue)
    );

    updateData(filteredResult);
  });

  $("#BtnLimparFiltro").on("click", function () {
    $("#filtrar-atributos").val("");
    updateData(resultadoAuxiliar);
  });

  result = await carregarDados();
  resultadoAuxiliar = result;
  updateData(result);
});

//// modal para listagem dos veiculos

const columnDefsModal = [
  {
    headerName: "Placa",
    field: "placa",
  },
  {
    headerName: "Voltagem",
    field: "voltagem",
  },
  {
    headerName: "Cliente",
    field: "cliente",
  },
  {
    headerName: "Email",
    field: "email",
  },
  {
    headerName: "Telefone",
    field: "telefone",
  },
  {
    headerName: "Data/Hora",
    field: "data/hora",
  },
];

const gridOptionsModal = {
  rowData: [],
  columnDefs: columnDefsModal,
};

var grid = document.getElementById("myGrid");

new agGrid.Grid(grid, gridOptionsModal);

grid.style.setProperty("height", "527px");

function show() {
  $(".ag-overlay-no-rows-center").html("Nenhum equipamento encontrado");    

  // funcao para extracao removida, apenas atualizando a tela, para atualizacoes futuras os dados devem ser inseridos aqui, exemplo: const mockData = [ {placa: "XYZ5678",voltagem: "110V", cliente: "Maria Oliveira",email: "maria@example.com",telefone: "(00) 9876-5432", "data/hora": "2024-04-11 11:30:00" }];

  preencherExportacoes(gridOptionsModal);

  document.getElementById("modals").style.display = "block";
}

const closeButton = document.getElementById("closes");

closeButton.addEventListener("click", () => {
  document.getElementById("modals").style.display = "none";
});
