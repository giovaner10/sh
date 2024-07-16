var localeText = AG_GRID_LOCALE_PT_BR;

document.addEventListener("DOMContentLoaded", async function () {
  var dropdown = $("#opcoes_exportacao");
  var result = [];
  var result = [];
  const gridOptions = {
    columnDefs: colunas_instaladores,
    rowData: [],
    pagination: false,
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

  let menuAberto = false;

  $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');

  async function carregarDados() {
    salTab();
    $("#titulo-card").html("Remessa de salário:");

    await $.getJSON(get_intaladores_pendentes, function (data) {
      data.forEach(conta => checkboxInstal[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, 'RelatorioRemessasSalario');

  }

  function updateData(newData = []) {
    gridOptions.api.setRowData(newData);
  }

  function overlay() {
    $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
  }
  function refresh() {
    updateData();
    overlay();
  }

  $("#Remessadesalário-tab").click(async function () {
    salTab()
    refresh();

    $("#titulo-card").html("Remessa de salário:");

    gridOptions.api.setColumnDefs(colunas_instaladores);

    await $.getJSON(get_intaladores_pendentes, function (data) {
      data.forEach(conta => checkboxInstal[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, 'RelatorioRemessasSalario');
  });

  $("#Remessasdefornecedores-tab").click(async function () {
    fornTab()
    refresh();

    $("#titulo-card").html("Remessa de fornecedores:");

    gridOptions.api.setColumnDefs(colunas_forn);

    await $.getJSON(get_fornecedores_pendentes, function (data) {
      data.forEach(conta => checkboxFornecedoresTed[conta.conta_id] = !(conta.banco == "001"))
      data.forEach(conta => checkboxFornecedores[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, 'RelatorioRemessasFornecedor');
  });

  $("#Remessadetítulos-tab").click(async function () {
    titTab()
    refresh();

    $("#titulo-card").html("Remessa de títulos:");

    gridOptions.api.setColumnDefs(colunas_titulos);

    await $.getJSON(get_remessas_titulo, function (data) {
      data.forEach(conta => checkboxTitulo[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, 'RelatorioRemessasTitulo');
  });

  $("#Remessadeguias-tab").click(async function () {
    guiaTab()
    refresh();

    $("#titulo-card").html("Remessa de guias:");

    gridOptions.api.setColumnDefs(colunas_guias);

    await $.getJSON(get_remessas_guia, function (data) {
      data.forEach(conta => checkboxGuia[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, 'RelatorioRemessasGuia');
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
    const inputValue = $(this).val().toString().trim().toLowerCase();

    const filteredResult = result.filter(
      (item) =>
        item.conta_id.toString().includes(inputValue) ||
        (item.fornecedor &&
          item.fornecedor.toString().toLowerCase().includes(inputValue)) ||
        (item.titular &&
          item.titular.toString().toLowerCase().includes(inputValue))
    );

    gridOptions.api.setRowData(filteredResult);
  });

  $("#BtnLimparFiltro").on("click", function () {
    $("#filtrar-atributos").val("");
    updateData(result);
  });

  await carregarDados();

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

  function salTab() {
    $("#salario-tab").css("display", "block");
    $("#three-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "none");
  }

  function fornTab() {
    $("#salario-tab").css("display", "none");
    $("#three-tab").css("display", "block");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "none");
  }

  function titTab() {
    $("#salario-tab").css("display", "none");
    $("#three-tab").css("display", "none");
    $("#titulo-tab").css("display", "block");
    $("#guia-tab").css("display", "none");
  }

  function guiaTab() {
    $("#salario-tab").css("display", "none");
    $("#three-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "block");
  }

  $(".atualizar-force").on("click", function () {
    gridOptions.api.refreshCells({ force: true });
  });
});
