var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
  $(".btn-expandir").on("click", function (e) {
    e.preventDefault();
    expandirGrid();
  });

  $("#coluna").select2({
    language: "pt-BR",
    placeholder: "Selecione uma opção",
    minimumResultsForSearch: -1,
    width: "100%",
  });

  $("#coluna").on("change", function (e) {
    let val = $(this).val();
    let textoSelecionado = $(this).find("option:selected").text();

    if (val != "data") {
      $("#labelforBusca").html(
        textoSelecionado ? textoSelecionado : "Indefinido"
      );
      $(".nomeBusca").show();
      $(".dataBusca").hide();
    } else {
      $(".nomeBusca").hide();
      $(".dataBusca").show();
    }
    $("#valorBusca").val("");
    $("#dataInicial").val("");
    $("#dataFinal").val("");
  });

  $("#formBusca").submit(function (e) {
    e.preventDefault();
    showLoadingPesquisarButton();

    var searchOptions = {
      coluna: $("#coluna").val(),
      valorBusca: $("#valorBusca").val(),
      dataInicial: $("#dataInicial").val(),
      dataFinal: $("#dataFinal").val(),
    };

    if (
      searchOptions &&
      searchOptions.coluna &&
      (searchOptions.valorBusca ||
        (searchOptions.dataInicial && searchOptions.dataFinal))
    ) {
      if (
        new Date(searchOptions.dataInicial) > new Date(searchOptions.dataFinal)
      ) {
        resetPesquisarButton();
        showAlert("warning","Data Final não pode ser menor que a Data Inicial!")
        return;
      }

      atualizarAgGrid(searchOptions);
    } else {
      showAlert("warning","Digite o(s) parâmetro(s) obrigatório(s) para pesquisar.")
      resetPesquisarButton();
    }
  });

  $("#BtnLimparFiltro").click(function () {
    $("#valorBusca").val("");
    $("#dataInicial").val("");
    $("#dataFinal").val("");
    showLoadingLimparButton();
    atualizarAgGrid();
  });

  $(document).on("shown.bs.dropdown", ".dropdown-table", function () {
    var dropdownId = $(this).find(".dropdown-menu").attr("id");
    var buttonId = $(this).find(".btn-dropdown").attr("id");
    var tableId = "table";
    abrirDropdown(dropdownId, buttonId, tableId);
  });

  atualizarAgGrid();
});

// Utilitários
function exibirAlerta(icon, title, text) {
  Swal.fire({
    position: "top-start",
    icon: icon,
    title: title,
    text: text,
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
  });
}

function formatDateTime(date) {
  dates = date.split(" ");
  dateCalendar = dates[0].split("-");
  return (
    dateCalendar[2] +
    "/" +
    dateCalendar[1] +
    "/" +
    dateCalendar[0] +
    " " +
    dates[1]
  );
}

let menuAberto = false;
function expandirGrid() {
  menuAberto = !menuAberto;

  let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
  let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

  if (menuAberto) {
    $(".img-expandir").attr("src", buttonShow);
    $("#filtroBusca").hide();
    $(".menu-interno").hide();
    $("#conteudo").removeClass("col-md-9");
    $("#conteudo").addClass("col-md-12");
  } else {
    $(".img-expandir").attr("src", buttonHide);
    $("#filtroBusca").show();
    $(".menu-interno").show();
    $("#conteudo").removeClass("col-md-12");
    $("#conteudo").addClass("col-md-9");
  }
}

function abrirDropdown(dropdownId, buttonId, tableId) {
  var dropdown = $("#" + dropdownId);
  var posDropdown = dropdown.height() + 10;

  var posBordaTabela = $("#" + tableId + " .ag-body-viewport")
    .get(0)
    .getBoundingClientRect().bottom;
  var posBordaTabelaTop = $("#" + tableId + " .ag-body-viewport")
    .get(0)
    .getBoundingClientRect().top;
  var posButton = $("#" + buttonId)
    .get(0)
    .getBoundingClientRect().bottom;
  var posButtonTop = $("#" + buttonId)
    .get(0)
    .getBoundingClientRect().top;

  if (posDropdown > posBordaTabela - posButton) {
    if (posDropdown < posButtonTop - posBordaTabelaTop) {
      dropdown.css("top", `-${10}px`);
    } else {
      let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
      dropdown.css("top", `-${posDropdown - 60 - diferenca}px`);
    }
  }
}

// AGGRID
// AgGrid
var AgGrid;
function atualizarAgGrid(options) {
  stopAgGRID();

  function getServerSideDados() {
    return {
      getRows: (params) => {
        var route = Router + "/get_log_veiculos";

        $.ajax({
          cache: false,
          url: route,
          type: "POST",
          data: {
            startRow: params.request.startRow,
            endRow: params.request.endRow,
            coluna: options ? options.coluna : "",
            valorBusca: options ? options.valorBusca : "",
            dataInicial: options ? options.dataInicial : "",
            dataFinal: options ? options.dataFinal : "",
          },
          dataType: "json",
          async: true,
          beforeSend: function () {
            if (AgGrid) {
              AgGrid.gridOptions.api.showLoadingOverlay();
            }
          },
          success: function (data) {
            if (data && data.success) {
              console.log(data);
              AgGrid.gridOptions.api.hideOverlay();
              params.success({
                rowData: data.rows,
                rowCount: data.rows.length,
              });
            } else if (data && data.message && data.statusCode != 500) {
              showAlert("warning",data.message)
              params.failCallback();
              params.success({
                rowData: [],
                rowCount: 0,
              });
              AgGrid.gridOptions.api.showNoRowsOverlay();
            } else {
              showAlert("warning", "Erro na solicitação ao servidor.")
              params.failCallback();
              params.success({
                rowData: [],
                rowCount: 0,
              });
              AgGrid.gridOptions.api.showNoRowsOverlay();
            }
            if (options) {
              resetPesquisarButton();
            } else {
              resetLimparButton();
            }
          },
          error: function (error) {
            showAlert("warning", "Erro na solicitação ao servidor.")
            params.failCallback();
            params.success({
              rowData: [],
              rowCount: 0,
            });
            if (options) {
              resetPesquisarButton();
            } else {
              resetLimparButton();
            }
            AgGrid.gridOptions.api.showNoRowsOverlay();
          },
        });
      },
    };
  }

  var gridOptions = {
    columnDefs: [
      { headerName: "ID", field: "id" },
      { headerName: "Data", field: "data" },
      //{ headerName: "Antes", field: "antes" },
      //{ headerName: "Depois", field: "depois" },
      { headerName: "Motivo", field: "motivo" },
      { headerName: "Observação", field: "observacao" },
      { headerName: "Usuário", field: "usuario" },
      { headerName: "Veículo", field: "veiculo" },
      { headerName: "Placa Antes", field: "placa_antes" },
      { headerName: "Placa Depois", field: "placa_depois" },
      { headerName: "Serial Antes", field: "serial_antes" },
      { headerName: "Serial Depois", field: "serial_depois" },
    ],
    masterDetail: true,
    detailRowHeight: 160,
    defaultColDef: {
      editable: false,
      sortable: false,
      filter: false,
      resizable: true,
      suppressMenu: true,
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
    autoGroupColumnDef: {
      minWidth: 300,
      flex: 1,
    },
    pagination: true,
    paginationPageSize: parseInt(
      $("#select-quantidade-por-pagina-dados").val()
    ),
    cacheBlockSize: 100,
    localeText: localeText,
    domLayout: "normal",
    rowModelType: "serverSide",
    serverSideStoreType: "partial",
    overlayLoadingTemplate:
      '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
  };

  var gridDiv = document.querySelector("#table");
  gridDiv.style.setProperty("height", "530px");
  AgGrid = new agGrid.Grid(gridDiv, gridOptions);

  $("#select-quantidade-por-pagina-dados")
    .off()
    .change(function () {
      var selectedValue = $("#select-quantidade-por-pagina-dados").val();
      gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

  let datasource = getServerSideDados();
  gridOptions.api.setServerSideDatasource(datasource);
  preencherExportacoes(gridOptions);
}

// Carregamento
function stopAgGRID() {
  var gridDiv = document.querySelector("#table");
  if (gridDiv && gridDiv.api) {
    gridDiv.api.destroy();
  }
  var wrapper = document.querySelector(".wrapper");
  if (wrapper) {
    wrapper.innerHTML =
      '<div id="table" class="ag-theme-alpine my-grid"></div>';
  }
}

function ShowLoadingScreen() {
  $("#loading").show();
}

function HideLoadingScreen() {
  $("#loading").hide();
}

function showLoadingPesquisarButton() {
  $("#BtnPesquisar")
    .html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...')
    .attr("disabled", true);
  $("#BtnLimparFiltro").attr("disabled", true);
}

function resetPesquisarButton() {
  $("#BtnPesquisar")
    .html('<i class="fa fa-search"></i> Pesquisar')
    .attr("disabled", false);
  $("#BtnLimparFiltro").attr("disabled", false);
}

function showLoadingLimparButton() {
  $("#BtnLimparFiltro")
    .html('<i class="fa fa-spinner fa-spin"></i> Limpando...')
    .attr("disabled", true);
  $("#BtnPesquisar").attr("disabled", true);
}

function resetLimparButton() {
  $("#BtnLimparFiltro")
    .html('<i class="fa fa-leaf"></i> Limpar')
    .attr("disabled", false);
  $("#BtnPesquisar").attr("disabled", false);
}

// EXPORTAÇÃO DA TEBELA
function exportarArquivo(tipo, gridOptions, titulo) {
  var colunas = [
    "usuario",
    "acao",
    "serial",
    "data",
    "motivo",
    "observacao",
    "antes.veiculo",
    "depois.veiculo",
    "antes.placa",
    "depois.placa",
    "antes.serial",
    "depois.serial",
    "antes.CNPJ_",
    "depois.CNPJ_",
    "antes.imagem",
    "depois.imagem",
  ];

  switch (tipo) {
    case "csv":
      fileName = titulo + ".csv";
      gridOptions.api.exportDataAsCsv({
        fileName: fileName,
        columnKeys: colunas,
      });
      break;
    case "excel":
      fileName = titulo + ".xlsx";
      gridOptions.api.exportDataAsExcel({
        fileName: fileName,
        columnKeys: colunas,
      });
      break;
    case "pdf":
      let definicoesDocumento = getDocDefinition(
        printParams("A4"),
        gridOptions.api,
        gridOptions.columnApi,
        "",
        titulo,
        colunas
      );
      pdfMake.createPdf(definicoesDocumento).download(titulo + ".pdf");
      break;
  }
}

function printParams(pageSize) {
  return {
    PDF_HEADER_COLOR: "#ffffff",
    PDF_INNER_BORDER_COLOR: "#dde2eb",
    PDF_OUTER_BORDER_COLOR: "#babfc7",
    PDF_LOGO: BaseURL + "media/img/new_icons/omnilink.png",
    PDF_HEADER_LOGO: "omnilink",
    PDF_ODD_BKG_COLOR: "#fff",
    PDF_EVEN_BKG_COLOR: "#F3F3F3",
    PDF_PAGE_ORITENTATION: "landscape",
    PDF_WITH_FOOTER_PAGE_COUNT: true,
    PDF_HEADER_HEIGHT: 25,
    PDF_ROW_HEIGHT: 25,
    PDF_WITH_CELL_FORMATTING: true,
    PDF_WITH_COLUMNS_AS_LINKS: false,
    PDF_SELECTED_ROWS_ONLY: false,
    PDF_PAGE_SIZE: pageSize,
  };
}

$(document).ready(function () {
  var dropdown = $("#opcoes_exportacao");

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

function preencherExportacoes(gridOptions) {
  const formularioExportacoes = document.getElementById("opcoes_exportacao");
  const opcoes = ["csv", "excel", "pdf"];
  let buttonCSV = BaseURL + "/media/img/new_icons/csv.png";
  let buttonEXCEL = BaseURL + "media/img/new_icons/excel.png";
  let buttonPDF = BaseURL + "media/img/new_icons/pdf.png";
  formularioExportacoes.innerHTML = "";
  opcoes.forEach((opcao) => {
    let button = "";
    let texto = "";
    switch (opcao) {
      case "csv":
        button = buttonCSV;
        texto = "CSV";
        margin = "-5px";
        break;
      case "excel":
        button = buttonEXCEL;
        texto = "Excel";
        margin = "0px";
        break;
      case "pdf":
        button = buttonPDF;
        texto = "PDF";
        margin = "0px";
        break;
    }
    let div = document.createElement("div");
    div.classList.add("dropdown-item");
    div.classList.add("opcao_exportacao");
    div.setAttribute("data-tipo", opcao);
    div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px; color: #1C69AD !important; font-family: 'Mont SemiBold';" title="Exportar no formato ${texto}">${texto}</label>
    `;
    div.style.height = "30px";
    div.style.marginTop = margin;
    div.style.borderRadius = "1px";
    div.style.transition = "background-color 0.3s ease";
    div.addEventListener("mouseover", function () {
      div.style.backgroundColor = "#f0f0f0";
    });
    div.addEventListener("mouseout", function () {
      div.style.backgroundColor = "";
    });
    div.style.border = "1px solid #ccc";
    div.addEventListener("click", function (event) {
      event.preventDefault();
      exportarArquivo(
        opcao,
        gridOptions,
        "Relatório do Log de Cadastro de Veículos"
      );
    });
    formularioExportacoes.appendChild(div);
  });
}
