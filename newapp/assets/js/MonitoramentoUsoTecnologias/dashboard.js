var Bars = {};
$(document).ready(function () {
  ShowLoadingScreen();
  disabledDownloadButton();
  disabledPesquisarButton();
  disabledLimparButton()
  buscarClientes()

  buscarDadosByPeriodo()
    .then((dados) => {
      atualizarGraficosAgGrid(dados);
    })
    .catch((erro) => {
      alert(erro);
    });

  //Ação Btn Filtro
  $("#formBusca").submit(function (e) {
    cliente = $("#clienteBusca").val();
    dataInicio = $("#dataInicial").val();
    dataFim = $("#dataFinal").val();

    e.preventDefault();
    disabledDownloadButton();
    disabledLimparButton();
    showLoadingPesquisarButton();


  // validacaoFiltros();

    if (!validacaoFiltros()) {
      resetPesquisarButton();
      resetDownloadButton();
      resetLimparButton();
      return;
    }
    
    if (cliente && (!dataInicio || !dataFim)) {
      buscarDadosByIdCliente().then((dados) => {
        atualizarGraficosAgGrid(dados);
      })
      .catch((erro) => {
        alert(erro);
      });
    } else if (cliente && dataInicio && dataFim) {
      buscarDadosClienteEPeriodo().then((dados) => {
        atualizarGraficosAgGrid(dados);
      })
      .catch((erro) => {
        alert(erro);
      });
    } else if (!cliente && dataInicio && dataFim) {
      buscarDadosByPeriodo().then((dados) => {
        atualizarGraficosAgGrid(dados);
      })
    .catch((erro) => {
      alert(erro);
     });
    }
  });

  // Ação Limpar Filtros:
  $("#btnLimparFiltro").click(function (e) {
    if ($("#clienteBusca").val() != null) {
      $("#dataInicial").val("");
      $("#dataFinal").val("");
      $("#clienteBusca").val("");
      $("#clienteBusca")
        .select2({
          placeholder: "Selecionar Cliente",
          allowClear: true,
        })
        .val("")
        .trigger("change");

      e.preventDefault();
      disabledPesquisarButton();
      disabledDownloadButton();
      disabledLimparButton();

      buscarDadosByPeriodo()
        .then((dados) => {
          atualizarGraficosAgGrid(dados);
        })
        .catch((erro) => {
          alert(erro);
        });
    }
  });

  //Ação Baixar Gráfico
  $("#downloadChart").click(function (event) {
    event.preventDefault();
    if (Bars.myChart) {
      agCharts.AgChart.download(Bars.myChart, {
        width: 800,
        height: 500,
        fileName: "Grafico",
      });
    } else {
      alert("Referência ao gráfico não encontrada");
    }
  });
});

function buscarClientes() {
  let route = Router + "/buscarClientes";
  $.ajax({
    cache: false,
    url: route,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        $("#clienteBusca").empty().append('<option value="" disabled>Selecionar Cliente</option>');

        response.resultado.forEach(function (cliente) {
          $("#clienteBusca").append('<option value="' + cliente.idCliente + '">' + cliente.nomeCliente + '</option>');
        });

        $("#clienteBusca").select2({
          placeholder: "Selecionar Cliente",
          allowClear: true
        }).val('').trigger('change');
      } else {
        alert("Erro ao listar Clientes: " + response.resultado);
      }
    },
    error: function (error) {
      alert("Erro ao listar Clientes");
    }
  });
}

function buscarDadosByPeriodo() {
  let dataInicio = $("#dataInicial").val();
  let dataFim = $("#dataFinal").val();

  dataInicio = dataInicio ? dataInicio.split("-").reverse().join("/") : null;
  dataFim = dataFim ? dataFim.split("-").reverse().join("/") : null;

  return new Promise((resolve, reject) => {
    ShowLoadingScreen();
    let route = Router + "/buscarDadosTecnologiaByPeriodo";

    if (!dataInicio) {
      dataInicio = formatarData(new Date(new Date().setDate(new Date().getDate() - 30)));
    }
    if (!dataFim) {
      dataFim = formatarData(new Date());
    }

    $.ajax({
      cache: false,
      url: route,
      type: "POST",
      data: {
        dataInicio: dataInicio,
        dataFim: dataFim,
      },
      dataType: "json",
      success: function (response) {
        if (response.status === 200 && Array.isArray(response.resultado)) {
          resolve(response.resultado);
        } else {
          reject("Dados não encontrados");
        }
      },
      error: function (error) {
        reject("Erro na obtenção dos dados");
      },
      complete: function () {
        HideLoadingScreen();
        resetDownloadButton();
        resetPesquisarButton();
      },
    });
  });
}

function buscarDadosByIdCliente() {
  let idCliente = $("#clienteBusca").val();
  
  return new Promise((resolve, reject) => {
    ShowLoadingScreen();
    let route = Router + "/buscarDadosTecnologiaByIdCliente";
    $.ajax({
      cache: false,
      url: route,
      type: "POST",
      data: {
        idCliente: idCliente
      },
      dataType: "json",
      success: function (response) {
        if (response.status === 200 && Array.isArray(response.resultado)) {
          resolve(response.resultado);
        } else {
          reject("Dados não encontrados");
        }
      },
      error: function (error) {
        reject("Erro na obtenção dos dados");
      },
      complete: function () {
        HideLoadingScreen();
        resetDownloadButton();
        resetPesquisarButton();
      },
    });
  });
}

function buscarDadosClienteEPeriodo() {
  let dataInicio = $("#dataInicial").val();
  let dataFim = $("#dataFinal").val();
  let idCliente = $("#clienteBusca").val();

  dataInicio = dataInicio ? dataInicio.split("-").reverse().join("/") : null;
  dataFim = dataFim ? dataFim.split("-").reverse().join("/") : null;
  
  return new Promise((resolve, reject) => {
    ShowLoadingScreen();
    let route = Router + "/buscarDadosTecnologiaClienteEPeriodo";

    $.ajax({
      cache: false,
      url: route,
      type: "POST",
      data: {
        dataInicio: dataInicio,
        dataFim: dataFim,
        idCliente: idCliente
      },
      dataType: "json",
      success: function (response) {
        if (response.status === 200 && Array.isArray(response.resultado)) {
          resolve(response.resultado);
        } else {
          reject("Dados não encontrados");
        }
      },
      error: function (error) {
        reject("Erro na obtenção dos dados");
      },
      complete: function () {
        HideLoadingScreen();
        resetDownloadButton();
        resetPesquisarButton();
      },
    });
  });
}

function atualizarGraficosAgGrid(response) {
  let dataInicio = $("#dataInicial").val();
  let dataFim = $("#dataFinal").val();
  dataInicio = dataInicio ? dataInicio.split("-").reverse().join("/") : null;
  dataFim = dataFim ? dataFim.split("-").reverse().join("/") : null;
  if (!dataInicio) {
    dataInicio = formatarData(
      new Date(new Date().setDate(new Date().getDate() - 30))
    );
  }
  if (!dataFim) {
    dataFim = formatarData(new Date());
  }

  HideLoadingScreen();

  if (!Array.isArray(response)) {
    console.error(
      "Os dados fornecidos para atualizarGraficosAgGrid não são um array:",
      response
    );
    return;
  }

  let totalPorTecnologia = response.reduce((acc, item) => {
    let { tecnologia, qntKbytes } = item;
    acc[tecnologia] = (acc[tecnologia] || 0) + qntKbytes;
    return acc;
  }, {});

  let tecnologiasComTotais = Object.entries(totalPorTecnologia)
    .map(([codigo, total]) => ({
      nome: nomeTecnologia(parseInt(codigo)),
      total,
    }))
    .sort((a, b) => b.total - a.total);

  let objetoData = { quarter: "Tecnologia" };
  tecnologiasComTotais.forEach((tecnologia) => {
    objetoData[tecnologia.nome] = tecnologia.total;
  });

  let series = tecnologiasComTotais.map((tecnologia) => ({
    type: "bar",
    direction: "horizontal",
    xKey: "quarter",
    yKey: tecnologia.nome,
    yName: tecnologia.nome,
  }));

  var clienteSelecionado = $("#clienteBusca").val();
  var nomeCliente = $("#clienteBusca option:selected").text();

  let subtitulo = {
    text: `Período: ${dataInicio} - ${dataFim}`,
    fontWeight: "lighter",
    fontFamily: "Mont",
    color: "#333",
  };

  if (clienteSelecionado) {
    subtitulo.text = `Período: ${dataInicio} - ${dataFim}  | Cliente: ${nomeCliente}`;
  }

  const options = {
    container: document.getElementById("myChart"),
    title: {
      text: "Dados consumidos em KBytes",
      fontWeight: "lighter",
      fontFamily: "Mont",
      color: "#333",
    },
    subtitle: subtitulo,
    data: [objetoData],
    series: series,
    overlays: {
      noData: {
        text: "Não há dados disponíveis.",
      },
    },
    legend: {
      enabled: true, // Ativa a legenda
      position: 'bottom',
    },
    autoSize: true,
  };

  if (Bars.myChart) {
    agCharts.AgCharts.update(Bars.myChart, options);
  } else {
    Bars.myChart = agCharts.AgCharts.create(options);
  }
}

//MANIPULAÇÃO DOS BOTÕES DE CARREGAMENTO

function showLoadingPesquisarButton() {
  $("#filtrar").html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr("disabled", true); 
}
function disabledPesquisarButton() {
$("#filtrar").attr("disabled", true);}
  
function resetPesquisarButton() {
  $("#filtrar").html('<i class="fa fa-search"></i> Pesquisar').attr("disabled", false);  $("#btnLimparFiltro").html('<i class="fa fa-leaf"></i> Limpar').attr("disabled", false);}

function showLoadingDownloadButton() {
  $("#downloadChart").html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr("disabled", true);}

function disabledDownloadButton() {
  $("#downloadChart").attr("disabled", true);}

function resetDownloadButton() {
  $("#downloadChart").html('<i class="fa fa-download"></i> Baixar').attr("disabled", false);}

function disabledLimparButton() {
  $("#btnLimparFiltro").attr("disabled", true);}

function resetLimparButton() {
  $("#btnLimparFiltro").attr("disabled", false);}

function ShowLoadingScreen() {$("#loadingMessage").show();}

function HideLoadingScreen() {$("#loadingMessage").hide();}

// UTILITÁRIOS

function formatarData(data) {
  const dia = data.getDate().toString().padStart(2, "0");
  const mes = (data.getMonth() + 1).toString().padStart(2, "0");
  const ano = data.getFullYear();
  return `${dia}/${mes}/${ano}`;
}

function formatarDataParaComparacao(dataStr) {
  const partes = dataStr.split('/');
  // Note que o mês é ajustado -1 porque os meses em Date começam do zero (0-11)
  const data = new Date(partes[2], partes[1] - 1, partes[0]);
  return data;
}

function validacaoFiltros() {
  cliente = $("#clienteBusca").val();
  dataInicio = $("#dataInicial").val();
  dataFim = $("#dataFinal").val();

  if (!cliente && !dataInicio && !dataFim) {
    alert("É necessário informar ao menos um dos parâmetros para filtrar");
    return false;
  }

  if (dataInicio && !dataFim) {
    alert("É necessário informar a Data Final");
    return false;
  }

  if (!dataInicio && dataFim) {
    alert("É necessário informar Data Inicial");
    return false;
  }

  if (!validarDatas(dataInicio, dataFim)) {
    return false; 
  }

  return true;
}


function validarDatas(dataInicialStr, dataFinalStr) {
  const dataInicial = new Date(dataInicialStr);
  const dataFinal = new Date(dataFinalStr);
  const dataAtual = new Date();

  dataAtual.setHours(0, 0, 0, 0);

  if (dataInicial > dataFinal) {
    alert("A data inicial não pode ser maior que a data final.");
    return false;
  }

  if (dataFinal > dataAtual) {
    alert("A data final não pode ser maior que a data atual.");
    return false;
  }

  if (dataInicial > dataAtual) {
    alert("A data inicial não pode ser maior que a data atual.");
    return false;
  }

  return true;
}

function nomeTecnologia(code) {
  switch (code) {
    case 1:
      return "Autotrac";
    case 2:
      return "Onix";
    case 3:
      return "Sascar";
    case 4:
      return "Omnilink";
    case 5:
      return "Cielo";
    case 6:
      return "Controlloc";
    case 7:
      return "Rodosis";
    case 8:
      return "Cronos";
    case 9:
      return "Assemil Sat";
    case 10:
      return "Sighra";
    case 11:
      return "Tecnocell";
    case 12:
      return "ADois";
    case 13:
      return "Telemachine";
    case 14:
      return "Rota System";
    case 15:
      return "Link";
    case 16:
      return "Enalta";
    case 17:
      return "Positron";
    case 18:
      return "RCF";
    case 19:
      return "egSYS";
    case 20:
      return "Maxtrac";
    case 21:
      return "Tracker";
    case 22:
      return "RastSat";
    case 23:
      return "Ravex";
    case 24:
      return "Sitrack";
    case 25:
      return "Iter";
    case 26:
      return "FocLog";
    case 27:
      return "Ceabs";
    case 28:
      return "SobControle";
    case 29:
      return "CDN";
    case 30:
      return "OmniLoc";
    case 31:
      return "CentroSat";
    case 32:
      return "Linker";
    case 33:
      return "Quatenus";
    case 34:
      return "Lógico Rast.";
    case 35:
      return "Sintravir";
    case 36:
      return "Brasil TrackSat";
    case 37:
      return "Trac Gold";
    case 38:
      return "JV System";
    case 39:
      return "GlobalSat";
    case 40:
      return "CarvisionSystem";
    case 41:
      return "CarLog";
    case 42:
      return "TeleMonitora.";
    case 43:
      return "G10";
    case 44:
      return "Vigitrack";
    case 45:
      return "Chronus";
    case 46:
      return "CargoTrack";
    case 47:
      return "Pointer";
    case 48:
      return "Xtreme";
    case 49:
      return "SafeTruck";
    case 50:
      return "AutoVision";
    case 51:
      return "CarrierWeb";
    case 52:
      return "Alarcom";
    case 53:
      return "Inviosiga";
    case 54:
      return "HPS Fleet";
    case 55:
      return "L&L";
    case 56:
      return "SatPlus";
    case 57:
      return "Smart Car";
    case 58:
      return "Coopertrac";
    case 59:
      return "DeltaSat";
    case 60:
      return "Vegasat";
    case 61:
      return "A52";
    case 62:
      return "OnixPonto";
    case 63:
      return "OmniWeb";
    case 64:
      return "Veltec";
    case 65:
      return "Multiportal";
    case 66:
      return "Traffilog";
    case 67:
      return "App A52";
    case 68:
      return "GolSat";
    case 69:
      return "Actiming";
    case 70:
      return "Btrac";
    case 71:
      return "Metadados";
    case 72:
      return "Weso";
    case 73:
      return "3S";
    case 74:
      return "GsLog";
    case 75:
      return "Delsoft";
    case 76:
      return "Mix Telematics";
    case 77:
      return "STC";
    case 78:
      return "Totaltrac";
    case 79:
      return "FullTrack";
    case 80:
      return "CEABS";
    default:
      return `Tecnologia não mapeada (código: ${code})`;
  }
}
