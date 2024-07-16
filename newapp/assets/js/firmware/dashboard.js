const intervalReferences = [];
const localeText = AG_GRID_LOCALE_PT_BR;
let defaultOverlay = AG_GRID_DEFAULT_OVERLAYS_FIRMWARE;
var Bars = {};
let totalDados;
let totalFirmwares;
let totalSeriaisAtualizados;

$(document).ready(function () {
    setupEventHandlers();
    getMetricas();
    getChartData();
});

function setupEventHandlers() {
    const dropdown = $("#opcoes_atualizar");

    $("#dropdownMenuButtonAtualizar").click(() => {
        dropdown.toggle();
        $(this).blur();
    });

    $(document).click((event) => {
        if (!dropdown.is(event.target) && event.target.id !== "dropdownMenuButtonAtualizar") {
            dropdown.hide();
        }
    });

    $("#card-atualizacao-desabilitada").click(() => {
      buscarRegrasAtualizacaoDesabilitada()
      $(this).blur();
  }); 

    $("#card-dias-especifico").click(() => {
      buscarRegrasDia()
      $(this).blur();
  });

    $("#card-hora-especifica").click(() => {
      buscarRegrasHorario()
      $(this).blur();
  });


    $("#card-total-firmware").click(() => {
      buscarTotalFirmwares()
      $(this).blur();
  });

    $("#card-seriais-atualizados").click(() => {
      buscarSeriaisAtualizados()
      $(this).blur();
  });

  $('#modalSeriaisAtualizados').on('hide.bs.modal', function () {
    $("#search-input-date").val('').trigger('change');
    $("#search-input-serial").val('').trigger('change');
  });

  $("#card-versao-anterior").click(() => {
    buscarClientesDesatualizados()
    $(this).blur();
});

$("#card-seriais-desatualizados").click(() => {
  buscarSeriaisDesatualizados()
  $(this).blur();
});

    setupIntervalControls();
    setupChartExpander();
}

function setupIntervalControls() {
    $("#stopInterval").click(stopIntervals);

    $("#10seg").click(() => setRefreshInterval(10));
    $("#60seg").click(() => setRefreshInterval(60));
    $("#180seg").click(() => setRefreshInterval(180));
}

function setRefreshInterval(seconds) {
    stopIntervals();
    const interval = setInterval(reloadData, 1000 * seconds);
    intervalReferences.push(interval);
}

function stopIntervals() {
    intervalReferences.forEach(clearInterval);
    intervalReferences.length = 0;
}

function reloadData(){
  getMetricas();
  getChartData();
}

function setupChartExpander() {
    $(".chart-exp").click(async function () {
        let id = $(this).attr("id").split("-")[1];
        let tipoGrafico = (id >= 1 && id <= 3) ? "Cadastros" : "Atualizações";
        let periodo = $(`#chart-${id} span`).text()
        let name = `Gráfico ${tipoGrafico} - ${periodo}`;

        $("#chartModalLabel").html(name);
        setupDownloadButton(id, name);
        expandChart(id);
    });
}

async function setupDownloadButton(id, name) {
  $("#downloadChart").off("click").click(async function (event) {
      event.preventDefault();
      const chartId = `myDashBar${id}`;
      if (Bars[chartId]) {
        agCharts.AgCharts.download(Bars[chartId], {
          width: 800,
          height: 500,
          fileName: name,
        });
        $("#downloadChart").blur()
      } else {
        showAlert("warning", "Gráfico não encontrado. Por favor, aguarde a renderização completa do gráfico.");
        $("#downloadChart").blur()
      }
    });
}


async function expandChart(id) {
  ShowLoadingScreen();

  const idMappings = {
    1: "firmwareCadastrados30Dias",
    2: "firmwareCadastrados60Dias",
    3: "firmwareCadastrados90Dias",
    4: "atualizacao7Dias",
    5: "atualizacao15Dias",
    6: "atualizacao30Dias"
  };

  const chartId = "myDashBarModal";

  try {
    const dados = await getChartData();

    const key = idMappings[id];

    if (dados && key && dados[key] !== undefined && dados[key] > 0) {
      montarGraficoBarras(dados[key], chartId);
      $('#chartModal').modal('show');
    } else {
      showAlert("warning", "Valores não encontrados para a renderização do gráfico.");
    }
  } catch (error) {
    showAlert("error", "Não foi possível obter os dados do gráfico do dashboard. Tente novamente.");
  }
  HideLoadingScreen();
}

function getMetricas() {
  let route = Router + "/getMetricas";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: function (data) {
      if (data) {
        totalFirmwares = data.totalFirmwareCadastrado;
        totalSeriaisAtualizados = data.totalSeriaisAtualizados;
        $("#seriais-desatualizados").text(data.totalSeriaisDesatualizados ?? "--").toLocaleString("pt-BR");
        $("#versao-anterior").text(data.totalSeriaisVersaoAnterior ?? "--").toLocaleString("pt-BR");
        $("#atualizacao-desabilitada").text(data.totalAtualizacaoDesabilitada ?? "--").toLocaleString("pt-BR");
        $("#dia-especifico").text(data.totalRegraDia ?? "--").toLocaleString("pt-BR");
        $("#hora-especifica").text(data.totalRegraHora ?? "--").toLocaleString("pt-BR");
        $("#total-firmware").text(data.totalFirmwareCadastrado ?? "--").toLocaleString("pt-BR");
        $("#seriais-atualizados").text(data.totalSeriaisAtualizados ?? "--").toLocaleString("pt-BR");
        ajustarAltura();
    }
    },
    error: function () {
      $("#seriais-desatualizados").text("--");
      $("#versao-anterior").text("--");
      $("#seriais-atualizados").text("--");
      $("#total-firmware").text("--");
      $("#seriais-atualizados").text("--");
      $("#atualizacao-desabilitada").text("--");
      $("#dia-especifico").text("--");
      $("#hora-especifica").text("--");
      showAlert("error", "Não foi possível obter as métricas do dashboard. Recarregue a página.");
    },
  });
}

async function getChartData() {
  const route = Router + "/getDadosCharts";
  $("#loadingMessage").show();

  try {
    const data = await $.ajax({
      cache: false,
      url: route,
      type: "POST",
      dataType: "json"
    });
    if (data) {
      let maiorValor = 0;

      for (const key in data) {
        if (data.hasOwnProperty(key)) {
          const valor = parseInt(data[key]);
          if (valor > maiorValor) {
            maiorValor = valor;
          }
        }
      }

      totalDados = maiorValor;

      const idMappings = {
        firmwareCadastrados30Dias: "myDashBar1",
        firmwareCadastrados60Dias: "myDashBar2",
        firmwareCadastrados90Dias: "myDashBar3",
        atualizacao7Dias: "myDashBar4",
        atualizacao15Dias: "myDashBar5",
        atualizacao30Dias: "myDashBar6"
      };

      for (const [key, chartId] of Object.entries(idMappings)) {
        if (data.hasOwnProperty(key)) {
          montarGraficoBarras(parseInt(data[key]), chartId);
        } else {
          montarGraficoBarras(parseInt(0), chartId);
        }
      }
      return data;
    } else {
      showAlert("error","Dados não encontrados.");
    }
  } catch (error) {
    showAlert("error", "Não foi possível obter os dados do gráfico do dashboard. Tente novamente.");
  } finally {
    $("#loadingMessage").hide();
  }
}

function montarGraficoBarras(valor, chartId) {
  let idDash = chartId.split("myDashBar")[1];
  let categoryText = idDash >= 1 && idDash <= 3 ? "Cadastros" : "Atualizações";

  if (chartId === "myDashBarModal") {
    categoryText = 'Quantidade';
  }
  
  $(`#loadingMessage${idDash}`).hide();

  let data = [];

  if (valor !== 0) {
    data.push({ categoria: categoryText, valor: valor, visible: true });
  }

  let maxValor = valor !== 0 ? valor : 0;
  let maxDados = Math.ceil((maxValor + 10) / 10) * 10;


  const options = {
    container: document.getElementById(chartId),
    data: data,
    series: [{
      type: "bar",
      xKey: "categoria",
    },
    {
      type: "bar",
      xKey: "categoria",
      yKey: "valor",
      fill: "#3A87AD",
      stroke: "#3A87AD",
      tooltip: {
        renderer: function(params) {
          return {
            content:`${categoryText}: ${Math.round(params.datum[params.yKey]).toString()}`
          };
        }
      }
    },
    {
      type: "bar",
      xKey: "categoria",
    }],
    axes: [
      {
        type: "category",
      },
      {
        type: "number",
        min: 0, 
        max: maxDados
      },
    ],
    overlays: defaultOverlay,
    legend: {
      enabled: false,
    },
    padding: { top: 20, right: 30, bottom: 20, left: 30 },
    listeners: {
      seriesNodeClick: async function(params) {
        let dados;
        if (idDash == 1) {
          dados = await buscarCadastros30Dias();
          $("#modalFirmwaresCadastradosPeriodo").text("Firmwares Cadastrados - Superior a 30 Dias")
        } else if (idDash == 2) {
          dados = await buscarCadastros60Dias();
          $("#modalFirmwaresCadastradosPeriodo").text("Firmwares Cadastrados - Superior a 60 Dias")
        } else if (idDash == 3) {
          dados = await buscarCadastros90Dias();
          $("#modalFirmwaresCadastradosPeriodo").text("Firmwares Cadastrados - Superior a 90 Dias")
        } else if (idDash == 4) {
          dados = await buscarAtualizacoes07Dias();
          $("#modalFirmwaresAtualizadosPeriodo").text("Firmwares Atualizados - Últimos 07 Dias")
        } else if (idDash == 5) {
          dados = await buscarAtualizacoes15Dias();
          $("#modalFirmwaresAtualizadosPeriodo").text("Firmwares Atualizados - Últimos 15 Dias")
        } else if (idDash == 6) {
          dados = await buscarAtualizacoes30Dias();
          $("#modalFirmwaresAtualizadosPeriodo").text("Firmwares Atualizados - Últimos 30 Dias")
        }
             
      }
  }
  };

  if (window.Bars && window.Bars[chartId]) {
    window.Bars[chartId].destroy();
  }

  window.Bars = window.Bars || {};
  window.Bars[chartId] = agCharts.AgCharts.create(options);
}

function buscarCadastros30Dias(){
  ShowLoadingScreen()

  let route = Router + "/getFirmware30Dias";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: function (data) {
      if (data) {
        atualizarAgGridFirmware(data.rows)
        $("#modalFirmwaresCadastrados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

function buscarCadastros60Dias(){
  ShowLoadingScreen()

  let route = Router + "/getFirmware60Dias";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: function (data) {
      if (data) {
        atualizarAgGridFirmware(data.rows)
        $("#modalFirmwaresCadastrados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error","Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

function buscarCadastros90Dias(){
  ShowLoadingScreen()

  let route = Router + "/getFirmware90Dias";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: function (data) {
      if (data) {
        atualizarAgGridFirmware(data.rows)
        $("#modalFirmwaresCadastrados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}
function buscarAtualizacoes07Dias(){
  ShowLoadingScreen()

  let route = Router + "/getAtualizados7Dias";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: function (data) {
      if (data) {
        atualizarAgGridAtualizacoes(data.rows)
        $("#modalFirmwaresAtualizados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

function buscarAtualizacoes15Dias(){
  ShowLoadingScreen()

  let route = Router + "/getAtualizados15Dias";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: function (data) {
      if (data) {
        atualizarAgGridAtualizacoes(data.rows)
        $("#modalFirmwaresAtualizados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

function buscarAtualizacoes30Dias(){
  ShowLoadingScreen()

  let route = Router + "/getAtualizados30Dias";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: function (data) {
      if (data) {
        atualizarAgGridAtualizacoes(data.rows)
        $("#modalFirmwaresAtualizados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}


async function buscarRegrasAtualizacaoDesabilitada(){
  ShowLoadingScreen()

  let route = Router + "/getRegraAtualizacoesDesabilitadas";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: await function (data) {
      if (data) {
        atualizarAgGridAtualizacoesDesabilitadas(data.rows)
        $("#modalRegraAtualizacoesDesabilitadas").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

async function buscarClientesDesatualizados(){
  ShowLoadingScreen();

  let route = Router + "/getClientesDesatualizados";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: await function (data) {
      if (data && data.success) {
        const dadosTratados = data.rows.map(cliente => {
          const id = cliente.cliente.id;
          const nome = cliente.cliente.nome || " ";
          const documento = cliente.cliente.cnpj ? formatCNPJ(cliente.cliente.cnpj) : (cliente.cliente.cpf ? formatCPF(cliente.cliente.cpf) : " ");
          const quantidade = cliente.quantidade || " ";

          return { id, nome, documento, quantidade };
        });

        dadosTratados.sort((a, b) => a.nome.localeCompare(b.nome));

        atualizarAgGridClientesDesatualizados(dadosTratados);
        $("#modalClientesSemAtualizacao").modal('show');
      } else {
        showAlert("error", data.message || "Erro desconhecido");
      }
      HideLoadingScreen();
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen();
    },
  });
}

function formatCPF(cpf) {
  return cpf.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4");
}

function formatCNPJ(cnpj) {
  return cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
}

async function buscarRegrasDia(){
  ShowLoadingScreen()

  let route = Router + "/getRegraDia";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: await function (data) {
      if (data) {
        atualizarAgGridRegraDia(data.rows)
        $("#modalRegraDiasEspecificos").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

async function buscarRegrasHorario(){
  ShowLoadingScreen()

  let route = Router + "/getRegraHorario";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: await function (data) {
      if (data) {
        atualizarAgGridRegraHorario(data.rows)
        $("#modalRegraHorarioEspecifico").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

async function buscarTotalFirmwares() {
  ShowLoadingScreen()

  let route = Router + "/buscarFirmwareCadastrados";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    data:{
            endRow: totalFirmwares,
          },
    dataType: "json",
    success: await function (response) {
      if (response) {
        atualizarAgGridTotalFirmwares(response.rows)
        $("#modalTotalFirmwaresCadastrados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

async function buscarSeriaisAtualizados(){
  ShowLoadingScreen()

  let route = Router + "/getSeriaisAtualizados";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    data: {endRow: totalSeriaisAtualizados},
    dataType: "json",
    success: await function (data) {
      if (data) {
        atualizarAgGridSeriaisAtualizados(data.rows)
        $("#modalSeriaisAtualizados").modal('show')
    }
    HideLoadingScreen()
    },
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

async function buscarSeriaisDesatualizados(){
  ShowLoadingScreen()

  let route = Router + "/getSeriaisDesatualizados";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    success: await function (data) {
      if (data) {
        if(data.rows.length > 0){
          var dados = data.rows.map(row => {
            for (let key in row) {
                if (row[key] === "" || row[key] === null) {
                    row[key] = "-";
                }
            }
            return row;
          
      });
        atualizarAgGridSeriaisDesatualizados(dados)
        HideLoadingScreen()
        $("#modalSeriaisDesatualizados").modal('show')
    }
    }},
    error: function () {
      showAlert("error", "Não foi possível obter os dados. Contate o suporte técnico.");
      HideLoadingScreen()
    },
  });
}

var AgGridFirmwareAtualizacoes;
function atualizarAgGridAtualizacoes(dados) {
  stopAgGRIDAtualizacoes()
    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "id",
            width: 80,
            sortable: true, 
            suppressSizeToFit: true
          },
          {
            headerName: "ID Cliente",
            field: "idCliente",
            width: 80,
            sortable: true, 
            suppressSizeToFit: true
          },
          {
            headerName: "Nome do Cliente",
            field: "nomeCliente",
            flex: 1,
            sortable: true, 
            suppressSizeToFit: true
          },
          {
            headerName: "Serial",
            field: "serial",
            width: 150,
            sortable: true, 
            suppressSizeToFit: true
          },
          {
            headerName: "Data de Envio",
            field: "horaEnvio",
            width: 200,
            sortable: true,
            suppressSizeToFit: true,
          },
        ],
        defaultColDef: {
          editable: false,
          sortable: false,
          filter: false,
          resizable: true,
          suppressMenu: true,
          cellClass: 'cell-ellipsis'
      },
      sideBar: {
          toolPanels: [
              {
                  id: 'columns',
                  labelDefault: 'Colunas',
                  iconKey: 'columns',
                  toolPanel: 'agColumnsToolPanel',
                  toolPanelParams: {
                      suppressRowGroups: true,
                      suppressValues: true,
                      suppressPivots: true,
                      suppressPivotMode: true,
                      suppressColumnFilter: false,
                      suppressColumnSelectAll: false,
                      suppressColumnExpandAll: true,
                      width: 100
                  },
              },
          ],
          defaultToolPanel: false,
      },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };
    var gridDiv = document.querySelector('#tableFirmwareAtualizados');
    gridDiv.style.setProperty('height', '519px');
    AgGridFirmwareAtualizacoes = new agGrid.Grid(gridDiv, gridOptions);
    AgGridFirmwareAtualizacoes.gridOptions.api.setRowData(dados);

    preencherExportacoesModalAtualizacoes(gridOptions)
}
function stopAgGRIDAtualizacoes() {
  var gridDiv = document.querySelector('#tableFirmwareAtualizados');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }
  var wrapper = document.querySelector('.wrapperDadosFirmwareAtualizados');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableFirmwareAtualizados" class="ag-theme-alpine my-grid-firmware-atualizados"></div>';
  }
}

var AgGridFirmware;
function atualizarAgGridFirmware(dados) {
    
  stopAgGRIDFirmware()

    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "id",
            width: 80,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Versão",
            field: "versao",
            width: 150,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Descrição",
            field: "descricao",
            flex: 1,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Data Liberação",
            field: "dataLiberacao",
            width: 200,
            sortable: true,
            suppressSizeToFit: true,
            cellRenderer: function (params) {
              const value = params.value;
              if (value) {
                  let partes = value.split(" ")
                  let partesData = partes[0].split("-");
                  let ano = partesData[0]
                  let mes = partesData[1]
                  let dia = partesData[2]
                  let hora = partes[1]
                  let dataTratada = `${dia}/${mes}/${ano}  ${hora}` 
                  return dataTratada
              }
              return "-";
          }
          },
          {
            headerName: "Tecnologia",
            field: "nomeTipoHardware",
            width: 250,
            sortable: true,
            suppressSizeToFit: true
          }
        ],
        defaultColDef: {
          editable: false,
          sortable: false,
          filter: false,
          resizable: true,
          suppressMenu: true,
          cellClass: 'cell-ellipsis'
      },
      sideBar: {
          toolPanels: [
              {
                  id: 'columns',
                  labelDefault: 'Colunas',
                  iconKey: 'columns',
                  toolPanel: 'agColumnsToolPanel',
                  toolPanelParams: {
                      suppressRowGroups: true,
                      suppressValues: true,
                      suppressPivots: true,
                      suppressPivotMode: true,
                      suppressColumnFilter: false,
                      suppressColumnSelectAll: false,
                      suppressColumnExpandAll: true,
                      width: 100
                  },
              },
          ],
          defaultToolPanel: false,
      },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };

    var gridDiv = document.querySelector('#tableFirmware');
    gridDiv.style.setProperty('height', '519px');
    AgGridFirmware = new agGrid.Grid(gridDiv, gridOptions);
    AgGridFirmware.gridOptions.api.setRowData(dados);

    preencherExportacoesModalFirmware(gridOptions)
}

function stopAgGRIDFirmware() {
  var gridDiv = document.querySelector('#tableFirmware');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperDadosFirmware');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableFirmware" class="ag-theme-alpine my-grid-firmware"></div>';
  }
}

var AgGridAtualizacoesDesabilitadas;
function atualizarAgGridAtualizacoesDesabilitadas(dados) {
  stopAgGRIDAtualizacoesDesabilitadas()

    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "id",
            width: 80,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "ID Cliente",
            field: "idCliente",
            width: 100,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Nome do Cliente",
            field: "nomeCliente",
            flex: 1,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Descrição",
            field: "descricao",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Data de Cadastro",
            field: "dataCadastro",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          }
        ],
        defaultColDef: {
          editable: false,
          sortable: false,
          filter: false,
          resizable: true,
          suppressMenu: true,
          cellClass: 'cell-ellipsis'
      },
      sideBar: {
          toolPanels: [
              {
                  id: 'columns',
                  labelDefault: 'Colunas',
                  iconKey: 'columns',
                  toolPanel: 'agColumnsToolPanel',
                  toolPanelParams: {
                      suppressRowGroups: true,
                      suppressValues: true,
                      suppressPivots: true,
                      suppressPivotMode: true,
                      suppressColumnFilter: false,
                      suppressColumnSelectAll: false,
                      suppressColumnExpandAll: true,
                      width: 100
                  },
              },
          ],
          defaultToolPanel: false,
      },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };

    var gridDiv = document.querySelector('#tableAtualizacoesDesabilitadas');
    gridDiv.style.setProperty('height', '519px');
    AgGridAtualizacoesDesabilitadas = new agGrid.Grid(gridDiv, gridOptions);
    AgGridAtualizacoesDesabilitadas.gridOptions.api.setRowData(dados);

    preencherExportacoesModalAtualizacoesDesabilitadas(gridOptions)
}

function stopAgGRIDAtualizacoesDesabilitadas() {
  var gridDiv = document.querySelector('#tableAtualizacoesDesabilitadas');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperDadosAtualizacoesDesabilitadas');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableAtualizacoesDesabilitadas" class="ag-theme-alpine my-grid-atualizacoes-desabilitadas"></div>';
  }
}

var AgGridRegraDia;
function atualizarAgGridRegraDia(dados) {
  stopAgGRIDRegraDia()

    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "id",
            width: 80,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "ID Cliente",
            field: "idCliente",
            width: 100,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Nome do Cliente",
            field: "nomeCliente",
            flex: 1,
            sortable: true,
          },
          {
            headerName: "Descrição",
            field: "descricao",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Dias de Atualização",
            field: "diaEscolhido",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Data de Cadastro",
            field: "dataCadastro",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          }
        ],
        defaultColDef: {
          editable: false,
          sortable: false,
          filter: false,
          resizable: true,
          suppressMenu: true,
          cellClass: 'cell-ellipsis'
      },
      sideBar: {
          toolPanels: [
              {
                  id: 'columns',
                  labelDefault: 'Colunas',
                  iconKey: 'columns',
                  toolPanel: 'agColumnsToolPanel',
                  toolPanelParams: {
                      suppressRowGroups: true,
                      suppressValues: true,
                      suppressPivots: true,
                      suppressPivotMode: true,
                      suppressColumnFilter: false,
                      suppressColumnSelectAll: false,
                      suppressColumnExpandAll: true,
                      width: 100
                  },
              },
          ],
          defaultToolPanel: false,
      },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };

    var gridDiv = document.querySelector('#tableRegraDiasEspecificos');
    gridDiv.style.setProperty('height', '519px');
    AgGridRegraDia = new agGrid.Grid(gridDiv, gridOptions);
    AgGridRegraDia.gridOptions.api.setRowData(dados);

    preencherExportacoesModalDiasEspecificos(gridOptions)


}

function stopAgGRIDRegraDia() {
  var gridDiv = document.querySelector('#tableRegraDiasEspecificos');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperRegraDiasEspecificos');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableRegraDiasEspecificos" class="ag-theme-alpine my-grid-dias-especificos"></div>';
  }
}
var AgGridRegraHorario;
function atualizarAgGridRegraHorario(dados) {
  stopAgGRIDRegraHorario()

    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "id",
            width: 80,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "ID Cliente",
            field: "idCliente",
            width: 100,
            sortable: true,
          },
          {
            headerName: "Nome do Cliente",
            field: "nomeCliente",
            flex: 1,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Descrição",
            field: "descricao",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Horário Inicial",
            field: "horaInicio",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Horário Final",
            field: "horaFim",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Data de Cadastro",
            field: "dataCadastro",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
            cellClass: 'cell-ellipsis'
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };

    var gridDiv = document.querySelector('#tableRegraHorarioEspecifico');
    gridDiv.style.setProperty('height', '519px');
    AgGridRegraHorario = new agGrid.Grid(gridDiv, gridOptions);
    AgGridRegraHorario.gridOptions.api.setRowData(dados);

    preencherExportacoesModalHoraEspecificos(gridOptions)
}

function stopAgGRIDRegraHorario() {
  var gridDiv = document.querySelector('#tableRegraHorarioEspecifico');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperRegraHorarioEspecifico');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableRegraHorarioEspecifico" class="ag-theme-alpine my-grid-horario-especifico"></div>';
  }
}


var AgGridTotalFirmwares;
function atualizarAgGridTotalFirmwares(dados) {
  stopAgGRIDTotalFirmwares()

    const gridOptions = {
        columnDefs: [
          {
            headerName: 'ID Firmware',
            field: 'id',
            chartDataType: 'category',
            width: 100,
            suppressSizeToFit: true
        },
        {
            headerName: 'Versão',
            field: 'versao',
            width: 150,
            chartDataType: 'series'
        },
        {
            headerName: 'Descrição',
            field: 'descricao',
            chartDataType: 'series',
            width: 250,
            suppressSizeToFit: true
        },
        {
            headerName: 'Hardware',
            field: 'nomeHardware',
            width: 180,
            suppressSizeToFit: true,
            chartDataType: 'category'
        },
        {
            headerName: 'Modelo',
            field: 'nomeModelo',
            width: 180,
            chartDataType: 'series'
        },
        {
            headerName: 'Data Liberação',
            field: 'dataLiberacao',
            chartDataType: 'series',
            width: 180,
            suppressSizeToFit: true
        },
        {
          headerName: 'Status',
          field: 'status',
          width: 80,
          chartDataType: 'category',
          cellRenderer: function (options) {
              let data = options.data['status'];
              if (data == 'Ativo') {
                  return `
                  <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                  `;
              } else {
                  return `
                  <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                  `
              }
          }
        }
      ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
            cellClass: 'cell-ellipsis'
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };

    var gridDiv = document.querySelector('#tableTotalFirmwaresCadastrados');
    gridDiv.style.setProperty('height', '519px');
    AgGridTotalFirmwares = new agGrid.Grid(gridDiv, gridOptions);
    AgGridTotalFirmwares.gridOptions.api.setRowData(dados);

    preencherExportacoesTotalFirmwares(gridOptions)
    
}

function stopAgGRIDTotalFirmwares() {
  var gridDiv = document.querySelector('#tableTotalFirmwaresCadastrados');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperTotalFirmwaresCadastrados');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableTotalFirmwaresCadastrados" class="ag-theme-alpine my-grid-total-firmwares-cadastrados"></div>';
  }
}
var AgGridSeriaisAtualizados;
function atualizarAgGridSeriaisAtualizados(dados) {
  stopAgGRIDSeriaisAtualizados()

    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "id",
            width: 80,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "ID Cliente",
            field: "idCliente",
            width: 100,
            sortable: true,
          },
          {
            headerName: "Nome do Cliente",
            field: "nomeCliente",
            flex: 1,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Serial",
            field: "serial",
            width: 200,
            sortable: true,
            suppressSizeToFit: true,
            filter: 'agTextColumnFilter'
          },
          {
            headerName: "Horário Envio",
            field: "horaEnvio",
            width: 200,
            sortable: true,
            suppressSizeToFit: true,
            filter: 'agDateColumnFilter',  // Adicione o filtro de data
        filterParams: {
          comparator: function(filterLocalDateAtMidnight, cellValue) {
            if (cellValue == null) {
              return -1;
            }
            var dateParts = cellValue.split(' ')[0].split('/');
            var cellDate = new Date(Number(dateParts[2]), Number(dateParts[1]) - 1, Number(dateParts[0]));
            if (filterLocalDateAtMidnight.getTime() === cellDate.getTime()) {
              return 0;
            }
            if (cellDate < filterLocalDateAtMidnight) {
              return -1;
            }
            if (cellDate > filterLocalDateAtMidnight) {
              return 1;
            }
          }
        }
          }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
            cellClass: 'cell-ellipsis'
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
        onFilterChanged: function() {
          checkNoRowsOverlay();
        }
    };

    var gridDiv = document.querySelector('#tableSeriaisAtualizados');
    gridDiv.style.setProperty('height', '519px');
    AgGridSeriaisAtualizados = new agGrid.Grid(gridDiv, gridOptions);
    AgGridSeriaisAtualizados.gridOptions.api.setRowData(dados);

    var searchSerialInput = document.getElementById('search-input-serial');
    var searchDateInput = document.getElementById('search-input-date');
    if (searchSerialInput) {
      searchSerialInput.addEventListener('input', onFilterTextBoxChanged);
    }
    if (searchDateInput) {
      searchDateInput.addEventListener('change', onFilterTextBoxChanged);
    }
  

    preencherExportacoesSeriaisAtualizados(gridOptions)
}

function stopAgGRIDSeriaisAtualizados() {
  var gridDiv = document.querySelector('#tableSeriaisAtualizados');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperSeriaisAtualizados');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableSeriaisAtualizados" class="ag-theme-alpine my-grid-seriais-atualizados"></div>';
  }
}

function onFilterTextBoxChanged() {
  var serialValue = document.getElementById('search-input-serial').value.toLowerCase();
  var dateValue = document.getElementById('search-input-date').value;
  var api = AgGridSeriaisAtualizados.gridOptions.api;

  var filterModel = {};
  
  if (serialValue) {
    filterModel.serial = {
      filterType: 'text',
      type: 'contains',
      filter: serialValue,
    };
  }

  if (dateValue) {
    filterModel.horaEnvio = {
      dateFrom: dateValue,
      filterType: 'date',
      type: 'equals',
    };
  }

  api.setFilterModel(filterModel);
  api.onFilterChanged();
  checkNoRowsOverlay();
}

function formatDate(dateString) {
  var parts = dateString.split('-');
  return parts[2] + '/' + parts[1] + '/' + parts[0];
}


function checkNoRowsOverlay() {
  var api = AgGridSeriaisAtualizados.gridOptions.api;
  var allRows = api.getDisplayedRowCount();
  if (allRows === 0) {
    api.showNoRowsOverlay();
  } else {
    api.hideOverlay();
  }
}


var AgGridClientesDesatualizados;
function atualizarAgGridClientesDesatualizados(dados) {
  stopAgGRIDClientesDesatualizados()

    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "id",
            width: 80,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Nome do Cliente",
            field: "nome",
            flex: 1,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Documento",
            field: "documento",
            width: 200,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Qnt. Seriais",
            field: "quantidade",
            width: 150,
            sortable: true,
            suppressSizeToFit: true
          }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
            cellClass: 'cell-ellipsis'
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };

    var gridDiv = document.querySelector('#tableClientesDesatualizados');
    gridDiv.style.setProperty('height', '519px');
    AgGridClientesDesatualizados = new agGrid.Grid(gridDiv, gridOptions);
    AgGridClientesDesatualizados.gridOptions.api.setRowData(dados);

    preencherExportacoesClientesDesatualizados(gridOptions);

}

function stopAgGRIDClientesDesatualizados() {
  var gridDiv = document.querySelector('#tableClientesDesatualizados');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperClientesDesatualizados');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableClientesDesatualizados" class="ag-theme-alpine my-grid-clientes-desatualizados"></div>';
  }
}

var AgGridSeriaisDesatualizados;
function atualizarAgGridSeriaisDesatualizados(dados) {
  stopAgGRIDSeriaisDesatualizados()

    const gridOptions = {
        columnDefs: [
          {
            headerName: "ID",
            field: "idCliente",
            width: 80,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Nome do Cliente",
            field: "nomeCliente",
            flex: 1,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Serial",
            field: "serial",
            width: 150,
            sortable: true,
            suppressSizeToFit: true
          },
          {
            headerName: "Versão",
            field: "versaoAtualEquipamento",
            width: 150,
            sortable: true,
            suppressSizeToFit: true
          }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
            cellClass: 'cell-ellipsis'
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Colunas',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100
                    },
                },
            ],
            defaultToolPanel: false,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 25,
    };

    var gridDiv = document.querySelector('#tableSeriaisDesatualizados');
    gridDiv.style.setProperty('height', '519px');
    AgGridSeriaisDesatualizados = new agGrid.Grid(gridDiv, gridOptions);
    AgGridSeriaisDesatualizados.gridOptions.api.setRowData(dados);

    preencherExportacoesSeriaisDesatualizados(gridOptions);
    
}

function stopAgGRIDSeriaisDesatualizados() {
  var gridDiv = document.querySelector('#tableSeriaisDesatualizados');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperSeriaisDesatualizados');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableSeriaisDesatualizados" class="ag-theme-alpine my-grid-seriais-desatualizados"></div>';
  }
}

function ShowLoadingScreen() {
    $("#loading").show();
}

function HideLoadingScreen() {
    $("#loading").hide();
}