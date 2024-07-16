var localeText = AG_GRID_LOCALE_PT_BR;

document.addEventListener("DOMContentLoaded", async function () {
  var dropdown = $("#opcoes_exportacao");
  var result = [];
  const gridOptions = {
    columnDefs: remessasdeSalarios,
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
    paginationPageSize: 20,
    localeText: localeText,
  };
  var gridDiv = document.querySelector("#tableContatos");
  new agGrid.Grid(gridDiv, gridOptions);
  preencherExportacoes(gridOptions);

  let menuAberto = false;

  $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');

  async function carregarDados() {
    salTab()
    $("#titulo-card").html("Remessas de salários:");

    await $.getJSON(get_salarios_pendentes, function (data) {
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, "RelatorioRemessasSalarios");
  }

  function updateData(newData = []) {
    gridOptions.api.setRowData(newData);
    gridOptions.api.paginationSetPageSize(newData.length)
  }

  function overlay() {
    $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
  }
  function refresh() {
    overlay()
    updateData();
    overlay();
  }

  $("#RemessasdeSalários-tab").click(async function () {
    salTab()
    refresh();
    $("#titulo-card").html("Remessas de salários:");

    await gridOptions.api.setColumnDefs(remessasdeSalarios);

    await $.getJSON(get_salarios_pendentes, function (data) {
      data.forEach(conta => checboxSalarios[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, "RelatorioRemessasSalarios");
  });

  $("#Remessasdeinstaladores-tab").click(async function () {
    instaladoresRemessa()
    refresh();

    $("#titulo-card").html("Remessas de instaladores:");

    await gridOptions.api.setColumnDefs(remessainstaladores);

    await $.getJSON(get_intaladores_pendentes, async function (data) {
      
      data.forEach(conta => checkboxRemessaTed[conta.conta_id] = !(conta.banco == "001"))
      data.forEach(conta => checkboxRemessa[conta.conta_id] = false)

      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, "RelatorioRemessasInstaladores");
  });

  $("#Retornodeinstaladorescomerros-tab").click(async function () {
    instaladoresErros();
    refresh();

    $("#titulo-card").html("Instaladores com erros:");

    await gridOptions.api.setColumnDefs(remessainstaladoresComErros);

    await $.getJSON(get_remessas_erros, function (data) {
      data.forEach(conta => checkboxRemessaTedErros[conta.conta_id] = !(conta.banco == "001"))
      data.forEach(conta => checkboxRemessaErros[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, "RelatorioRemessasErros");
  });

  $("#Remessasdefornecedores-tab").click(async function () {
    fornecedoresAba()
    refresh()

    $("#titulo-card").html("Remessas de fornecedores:");

    gridOptions.api.setColumnDefs(remessasFornecedores);

    await $.getJSON(get_fornecedores_pendentes, function (data) {
      
      data.forEach(conta => fornecedoresAbaInputTed[conta.conta_id] = !(conta.banco == "001"))
      data.forEach(conta => fornecedoresAbaInput[conta.conta_id] = false)

      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, "RelatorioRemessasFornecedor");
  });

  $("#Remessasdetítulos-tab").click(async function () {
    tituloAba()
    refresh();

    $("#titulo-card").html("Remessas de títulos:");

    gridOptions.api.setColumnDefs(remessasTitulos);

    await $.getJSON(get_remessas_titulo, function (data) {
      data.forEach(conta => titulosAbaInput[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, "RelatorioRemessasTitulos");
  });

  $("#Remessasdeguias-tab").click(async function () {
    guiaAba()
    refresh();

    $("#titulo-card").html("Remessas de guias:");

    gridOptions.api.setColumnDefs(remessasGuias);

    await $.getJSON(get_remessas_guia, function (data) {
      data.forEach(conta => guiasAbaInput[conta.conta_id] = false)
      result = data;
    });
    updateData(result);
    preencherExportacoes(gridOptions, "RelatorioRemessasGuias");
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
    const constantesInput = [checboxSalarios, checkboxRemessa, checkboxRemessaErros, fornecedoresAbaInput, titulosAbaInput, guiasAbaInput];

    for (let obj of constantesInput) {
        for (let key in obj) {
            obj[key] = false;
        }
    }
    updateData(result);
    gridOptions.api.refreshCells({ force: true });
    $("#data_pagamento_tecnico1").val("");
    $("#data_pagamento_tecnico2").val("");
    $("#data_pagamento_fornecedor1").val("");
    $("#data_pagamento_titulo").val("");
    $("#data_pagamento_guia").val("");
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
    $("#first-tab").css("display", "none");
    $("#three-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#second-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "none");
  }

  function instaladoresRemessa() {
    $("#salario-tab").css("display", "none");
    $("#first-tab").css("display", "block");
    $("#three-tab").css("display", "none");
    $("#second-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "none");
  }

  function instaladoresErros() {
    $("#salario-tab").css("display", "none");
    $("#first-tab").css("display", "none");
    $("#second-tab").css("display", "block");
    $("#three-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "none");
  }

  function fornecedoresAba() {
    $("#salario-tab").css("display", "none");
    $("#first-tab").css("display", "none");
    $("#second-tab").css("display", "none");
    $("#three-tab").css("display", "block");
    $("#titulo-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "none");
  }

  function tituloAba() {
    $("#salario-tab").css("display", "none");
    $("#first-tab").css("display", "none");
    $("#second-tab").css("display", "none");
    $("#three-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#titulo-tab").css("display", "block");
    $("#guia-tab").css("display", "none");
  }

  function guiaAba() {
    $("#salario-tab").css("display", "none");
    $("#first-tab").css("display", "none");
    $("#second-tab").css("display", "none");
    $("#three-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#titulo-tab").css("display", "none");
    $("#guia-tab").css("display", "block");
  }



  $(".atualizar-force").on("click", function () {
    gridOptions.api.refreshCells({ force: true });
  });
});
