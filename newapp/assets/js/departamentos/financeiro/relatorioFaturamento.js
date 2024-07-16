var tabelaRelatorioFaturamento = false;

var dataInicial = "00/00/0000";
var dataFinal = "00/00/0000";
var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
  inicializarAgGrid([]);
  initSelect2();
  AgGridRelatorio.gridOptions.api.showNoRowsOverlay();

  $("#formRelatorioFaturamento").on("submit", function (e) {
    e.preventDefault();
    let dataInicial = $("#data_inicial").val();
    let dataFinal = $("#data_final").val();

    if (dataInicial == "" || dataFinal == "") {
      $("#BtnPesquisar").blur();
      showAlert("warning", "É necessário informar Data Inicial e Data Final.");
      return;
    }
    
    if ($("#idStatus").val().length == 0) {
      $("#BtnPesquisar").blur();
      showAlert("warning", "É necessário selecionar ao menos um status");
      return;
    }

    if (dataInicial != "" || dataFinal != "") {
      if (dataInicial && dataFinal) {
        let dataInicioObj = new Date(dataInicial);
        let dataFinalObj = new Date(dataFinal);
        let dataAtual = new Date();

        if (dataFinalObj > dataAtual) {
          showAlert(
            "warning",
            "Data final não pode ser maior que a data atual."
          );
          $("#BtnPesquisar").blur();
          return;
        }

        if (dataInicioObj > dataFinalObj) {
          showAlert(
            "warning",
            "Data inicial não pode ser maior que a data final."
          );
          $("#BtnPesquisar").blur();
          return;
        }
      } else {
        showAlert(
          "warning",
          "É necessário informar a data inicial e a data final."
        );
        $("#BtnPesquisar").blur();
        return;
      }
      loadDadosRelatorio();
    }
  });

  $(".btn-expandir").on("click", function (e) {
    e.preventDefault();
    expandirGrid();
  });

  $(window).on("resize", function () {
    $("#empresa").select2("close");
    $("#orgao").select2("close");
    $("#idStatus").select2("close");
  });

  $('#idStatus').on('select2:select select2:unselect', function() {
    updateSelect2Status();
  });
  updateSelect2Status();

  $("#BtnLimpar").on('click', function(e){
    e.preventDefault();
    let dataInicio = $("#data_inicial").val();
    let dataFim = $("#data_final").val();
    let empresa = $("#empresa").val();
    let orgao = $("#orgao").val();
    let status = $("#idStatus").val();

    if(dataInicio != '' || dataFim != '' || empresa != 'todas' || orgao != '' || status.length == 0 || status.length > 1 || status[0] != '0' ){
      $("#data_inicial").val("")
      $("#data_final").val("")
      $("#empresa").val("todas").trigger('change')
      $("#orgao").val("").trigger('change')
      $("#idStatus").val(['0']).trigger('change')

      inicializarAgGrid([]);
      initSelect2();
      AgGridRelatorio.gridOptions.api.showNoRowsOverlay();
    }

  })

});

function initSelect2(){
  $('#empresa').select2();
  $('#orgao').select2();
  $('#idStatus').select2({
    placeholder: "Selecione um status",
    allowClear: false
  });
}

function updateSelect2Status() {
  var $select2Container = $('#select2-idStatus-container');
  if ($select2Container.find('li').length === 0) {
    $select2Container.css('display', 'none');
    $('.select2-selection__placeholder').text('Selecione um status');
  } else {
    $select2Container.css('display', '');
  }
}

let menuAberto = false;
function expandirGrid() {
    menuAberto = !menuAberto;
 
    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;
 
    if (menuAberto) {
        $('.img-expandir').attr("src", buttonShow);
        $('#filtroBusca').hide();
        $('#conteudo').removeClass('col-md-9');
        $('#conteudo').addClass('col-md-12');
    } else {
        $('.img-expandir').attr("src", buttonHide);
        $('#filtroBusca').show();
        $('#conteudo').css('margin-left', '0px');
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

var AgGridRelatorio;
function inicializarAgGrid(dados) {
  stopAgGRIDRelatorio();

  const gridOptions = {
    columnDefs: [
      { 
        headerName: "ID", 
        field: "id", 
        width: 80, 
        suppressSizeToFit: true 
    },
      { 
        headerName: "Cliente", 
        field: "nome", 
        width: 250,
        suppressSizeToFit: true 

    },
      {
        headerName: "CNPJ/CPF",
        field: "documento",
        width: 150,
        suppressSizeToFit: true,
      },
      {
        headerName: "Natureza do cliente",
        field: "natureza_cliente",
        width: 150,
        suppressSizeToFit: true,
      },
      { 
        headerName: "Tipo do cliente", 
        field: "orgao", 
        width: 150,
        suppressSizeToFit: true 
    },
      {
        headerName: "Prestadora",
        field: "prestadora",
        width: 150,
        suppressSizeToFit: true,
      },
      { 
        headerName: "Emissão", 
        field: "emissao", 
        width: 150,
        suppressSizeToFit: true 

    },
      {
        headerName: "Vencimento",
        field: "vencimento",
        width: 150,
        suppressSizeToFit: true,
      },
      {
        headerName: "Qtd. Veículos",
        field: "veiculos",
        width: 120,
        suppressSizeToFit: true 
      },
      {
        headerName: "Nota Fiscal",
        field: "nf",
        width: 120,
        suppressSizeToFit: true 
      },
      {
        headerName: "valor",
        field: "valor",
        width: 150,
        suppressSizeToFit: true,
        cellRenderer: function (params) {
          if (params.value) {
            const formattedValue = formatCurrency(params.value);
            return formattedValue;
        }
        return '-';
        } 
      },
      {
        headerName: "Mês de Referência",
        field: "mes_ref",
        width: 120,
        suppressSizeToFit: true 
      },
      {
        headerName: "N° da Fatura",
        field: "fatura",
        width: 120,
        suppressSizeToFit: true 
      },
      {
        headerName: "Atividade",
        field: "atividade",
        width: 150,
        suppressSizeToFit: true 
      },
      {
        headerName: "COFINS",
        field: "cofins",
        width: 80,
        suppressSizeToFit: true 
      },
      {
        headerName: "CSLL",
        field: "csll",
        width: 80,
        suppressSizeToFit: true 
      },
      {
        headerName: "IRPJ",
        field: "irpj",
        width: 80,
        suppressSizeToFit: true 
      },
      {
        headerName: "ISS",
        field: "iss",
        width: 80,
        suppressSizeToFit: true 
      },
      {
        headerName: "PIS",
        field: "pis",
        width: 80,
        suppressSizeToFit: true 
      },
      {
        headerName: "Status",
        field: "status",
        width: 120,
        suppressSizeToFit: true 
      },
    ],
    defaultColDef: {
      editable: false,
      sortable: true,
      minWidth: 80,
      minHeight: 100,
      filter: true,
      resizable: true,
      suppressMenu: true,
    },
    sideBar: {
      toolPanels: [
        {
          id: "columns",
          labelDefault: "Columns",
          labelKey: "columns",
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
    },
    popupParent: document.body,
    domLayout: "normal",
    pagination: true,
    localeText: localeText,
    cacheBlockSize: 50,
    paginationPageSize: parseInt(
      $("#select-quantidade-por-pagina-dados").val()
    ),
  };

  var gridDiv = document.querySelector("#tabelaRelatorioFaturamento");
  gridDiv.style.setProperty("height", "519px");
  AgGridRelatorio = new agGrid.Grid(gridDiv, gridOptions);
  AgGridRelatorio.gridOptions.api.setRowData(dados);

  $("#select-quantidade-por-pagina-dados").change(function () {
    var selectedValue = $(this).val();
    AgGridRelatorio.gridOptions.api.paginationSetPageSize(
      Number(selectedValue)
    );
  });

  preencherExportacoesRelatorio(gridOptions);
}

function formatCurrency(value) {
  return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
  }).format(value);
}

function loadDadosRelatorio() {
  AgGridRelatorio.gridOptions.api.showLoadingOverlay();
  disabledButtons();
  showLoadingPesquisarButton();

  const route = Router + "/geraRelatioFaturamento";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    data: {
      data_inicial: $("#data_inicial").val(),
      data_final: $("#data_final").val(),
      empresa: $("#empresa").val(),
      status: $("#clienteBusca").val(),
      orgao: $("#orgao").val(),
      status: $("#idStatus").val()
    },
    success: function (response) {
      if (response.success && response.data) {
        if (response.data.length === 0) {
          AgGridRelatorio.gridOptions.api.showNoRowsOverlay();
          showAlert("error", "Dados não encontrados.");
          return;
        }

        const rowData = response.data.map((item) => ({
          id: item.id || "",
          nome: item.nome || "-",
          documento: item.cnpj || item.cpf || "-",
          natureza_cliente: item.cnpj ? "Jurídica" : "Física",
          orgao: item.orgao || "-",
          prestadora: item.informacoes
            ? item.informacoes === "TRACKER"
              ? "Show Tecnologia"
              : item.informacoes
            : "-",
          emissao: item.data_emissao
            ? item.data_emissao.split("-").reverse().join("/")
            : "-",
          vencimento: item.data_vencimento
            ? item.data_vencimento.split("-").reverse().join("/")
            : "-",
          veiculos: item.quantidade_veiculos || "-",
          nf: item.nota_fiscal || "-",
          valor: item.valor_total || "-",
          mes_ref: item.mes_referencia || "-",
          fatura: item.numero || "-",
          atividade: item.atividade ? formatarAtividade(item.atividade) : "-",
          cofins: item.cofins || "-",
          csll: item.csll || "-",
          irpj: item.irpj || "-",
          iss: item.iss || "-",
          pis: item.pis || "-",
          tipoCliente: item.cnpj ? "Jurídica" : "Física",
          status: formatarStatus(item.status) || "-",
        }));

        AgGridRelatorio.gridOptions.api.hideOverlay();
        inicializarAgGrid(rowData);
      } else {
        AgGridRelatorio.gridOptions.api.showNoRowsOverlay();
        inicializarAgGrid();
      }
      enableButtons();
      resetPesquisarButton();
    },
    error: function () {
      enableButtons();
      resetPesquisarButton();
      showAlert(
        "error",
        "Não foi possível obter os dados. Contate o suporte técnico."
      );
      inicializarAgGrid();
      AgGridRelatorio.gridOptions.api.showNoRowsOverlay();
    },
  });
}

function formatarStatus(status) {
  switch (status) {
    case "0":
      return "Aberta";
    case "1":
      return "Paga";
    case "4":
      return "A Cancelar";
    case "3":
      return "Cancelada";
    default:
      return status;
  }
}

function formatarAtividade(atividade) {
  switch (atividade) {
    case "0":
      return "Outros";
    case "1":
      return "Atividade de Monitoramento";
    case "2":
      return "Serviços Técnicos";
    case "3":
      return "Aluguel de Máquinas e Equipamento";
    case "4":
      return "Suporte técnico, manutenção e outros serviços em tecnologia da informação";
    case "5":
      return "Desenvolvimento e licenciamento de programas de computador customizáveis";
    case "6":
      return "Serviços combinados de escritório e apoio administrativo";
    default:
      return "Não identificada";
  }
}

function stopAgGRIDRelatorio() {
  var gridDiv = document.querySelector("#tabelaRelatorioFaturamento");
  if (gridDiv && gridDiv.api) {
    gridDiv.api.destroy();
  }

  var wrapper = document.querySelector(".wrapperRelatorioFaturamento");
  if (wrapper) {
    wrapper.innerHTML =
      '<div id="tabelaRelatorioFaturamento" class="ag-theme-alpine my-grid-faturas"></div>';
  }
}

function expandirGrid() {
  menuAberto = !menuAberto;

  let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
  let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

  if (menuAberto) {
    $(".img-expandir").attr("src", buttonShow);
    $("#filtroBusca").hide();
    $("#conteudo").removeClass("col-md-9");
    $("#conteudo").addClass("col-md-12");
  } else {
    $(".img-expandir").attr("src", buttonHide);
    $("#filtroBusca").show();
    $("#conteudo").css("margin-left", "0px");
    $("#conteudo").removeClass("col-md-12");
    $("#conteudo").addClass("col-md-9");
  }
}

function showLoadingPesquisarButton() {
  $("#BtnPesquisar")
    .html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
    .attr("disabled", true);
}

function resetPesquisarButton() {
  $("#BtnPesquisar")
    .html('<i class="fa fa-search"></i> Gerar')
    .attr("disabled", false);
}

function disabledButtons() {
  $(".btn").attr("disabled", true);
}
function enableButtons() {
  $(".btn").attr("disabled", false);
}

function ShowLoadingScreen() {
  $("#loading").show();
}

function HideLoadingScreen() {
  $("#loading").hide();
}