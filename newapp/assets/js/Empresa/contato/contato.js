var localeText = AG_GRID_LOCALE_PT_BR;

document.addEventListener("DOMContentLoaded", async function () {
  var dropdown = $("#opcoes_exportacao");
  var result = [];
  var resultadoAuxiliar = [];
  const gridOptions = {
    columnDefs: [
      {
        headerName: "Nome",
        field: "nome",
        chartDataType: "category",
        width: "300px"
      },
      {
        headerName: "Cargo",
        field: "cargo",
        chartDataType: "category",
        width: "300px"

      },
      {
        headerName: "Empresa",
        field: "empresa",
        chartDataType: "category",
        width: "350px"

      },
      {
        headerName: "E-mail",
        field: "email",
        chartDataType: "category",
        width: "900px"
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
        url: Router + "/listContatosController",
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

  $("#ShowTecnologia-tab").click(function () {
    resultadoAuxiliar = filtrarDados("show");
    $("#titulo-card").html("Show Tecnologia:");
    updateData(resultadoAuxiliar);
  });

  $("#Norio-tab").click(function () {
    resultadoAuxiliar = filtrarDados("norio");
    $("#titulo-card").html("Norio:");
    updateData(resultadoAuxiliar);
  });

  $("#Omnilink-tab").click(function () {
    resultadoAuxiliar = filtrarDados("omni");
    $("#titulo-card").html("Omnilink:");
    updateData(resultadoAuxiliar);
  });

  $("#CEABS-tab").click(function () {
    resultadoAuxiliar = filtrarDados("ceabs");
    $("#titulo-card").html("CEABS:");
    updateData(resultadoAuxiliar);
  });

  $("#Outros-tab").click(function () {
    resultadoAuxiliar = filtrarDados("outros", true);
    $("#titulo-card").html("Outros:");
    updateData(resultadoAuxiliar);
  });

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
    const inputValue = $(this).val().trim().toLowerCase();

    const filteredResult = resultadoAuxiliar.filter(
      (item) =>
        item.nome.toLowerCase().includes(inputValue) ||
        item.email.toLowerCase().includes(inputValue) ||
        item.cargo.toLowerCase().includes(inputValue)
    );

    updateData(filteredResult);
  });

  function filtrarDados(filtro, outros = false) {
    if (outros == true) {
      return result.filter(
        (contato) =>
          !contato.empresa.toLowerCase().includes("show") &&
          !contato.empresa.toLowerCase().includes("norio") &&
          !contato.empresa.toLowerCase().includes("omnilink")
      );
    } else {
      return result.filter((e) => e.empresa.toLowerCase().includes(filtro));
    }
  }

  $("#BtnLimparFiltro").on("click", function () {
    $("#filtrar-atributos").val("");
    updateData(resultadoAuxiliar);
  });

  result = await carregarDados();
  resultadoAuxiliar = filtrarDados("norio");
  updateData(resultadoAuxiliar);

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

