var localeText = AG_GRID_LOCALE_PT_BR;

// MODAL POSICAO
$(document).on("click", ".botao_posicao", function (e) {
  e.preventDefault();
  var serial = $(this).attr("data-serial");
  $("#modal_posicao").modal();

  var href = urlPosicao + "/" + serial;

  carregar_viewPosicao(serial, href);
});

$(document).on("click", ".botao_desvincular", function (e) {
  e.preventDefault();
  var serial = $(this).attr("data-serial");
  var placa = $(this).attr("data-placa");
  if (serial == "null" || !serial) {
    window.alert(
      "Nao foi possível desvincular, o veículo não possui serial cadastrado."
    );
  } else if (placa == "null" || !placa) {
    window.alert(
      "Nao foi possível desvincular, o veículo não possui placa cadastrado."
    );
  } else {
    $("#placa-desvincular").text(placa);
    $("#serial-desvincular").text(serial);
    $("#modalDesvincular").modal();
  }
});

async function desvincularVeiculo() {
  var placa = $("#placa-desvincular").text();
  var serial = $("#serial-desvincular").text();

  document.getElementById("loading").style.display = "block";
  try {
    await $.ajax({
      url: desvincularComando + "/" + serial + "/" + placa,
      type: "GET",
      success: function () {
        alert("Desvinculado com sucesso.");
      },
      error: function (xhr, status, error) {
        var errorMessage = "Erro ao desvincular, tente novamente.";
        if (xhr.status === 404) {
          errorMessage = "Página não encontrada.";
        } else if (xhr.status === 500) {
          errorMessage = "Erro interno do servidor.";
        }
        alert("Falha ao desvincular.");
      },
    });
    document.getElementById("loading").style.display = "none";
    $("#modalDesvincular").modal("hide");
  } catch (error) {
    document.getElementById("loading").style.display = "none";
  }
}

function carregar_viewPosicao(serial, href) {
  $("#posicaoveic").html("<p>Carregando...</p>");
  $.ajax({
    url: href,
    dataType: "html",
    success: function (html) {
      if (serial) {
        $("#posicaoveic").html(html);
        setTimeout(function () {
          carregar_viewPosicao(serial, href);
        }, 60000000);
      } else {
        html = "<p>Veiculo sem informação de posição</p>";
        $("#posicaoveic").html(html);
      }
    },
  });
}

// modal veiculo atualizar/editar
$(document).on("click", ".botao_adm", function (e) {
  e.preventDefault();
  href = $(this).attr("data-href");
  code = $(this).attr("data-code");

  $("#modalVeiculoCadastro .modal-body").html("");
  document.getElementById("loading").style.display = "block";
  $("#modalVeiculoCadastro .modal-content").load(href, function () {
    document.getElementById("loading").style.display = "none";
    $("#codeVeiculo").val(code);
    $("#modalVeiculoCadastro").modal("show");
  });
});

// carrega comandos
$(document).on("click", ".botao_comando", function (e) {
  e.preventDefault();
  var serial = $(this).attr("data-serial");
  var code = $(this).attr("data-code");
  if (!serial) {
    window.alert("Serial não encontrado para este veículo.");
  } else if (serial.startsWith("MDVR") || serial.startsWith("OM")) {
    alert("Comandos não disponíveis para esse serial.");
  } else {
    window.open(envioComando + "/" + serial + "/" + code, "_self");
  }
});

//Define a tabela de grupos do veiculo quando a pagina é carregada
$(document).ready(function () {
  const gridOptionsGrupos = {
    columnDefs: [
      {
        headerName: "Grupo",
        field: "grupo",
        chartDataType: "category",
        width: "150px",
        valueGetter: function (params) {
          return params.data.grupo || "";
        },
      },
      {
        headerName: "Cliente",
        field: "cliente",
        chartDataType: "category",
        width: "150px",
        valueGetter: function (params) {
          return params.data.cliente || "";
        },
      },
      {
        headerName: "Espelhamento",
        field: "espelhamento",
        flex: 1,
        chartDataType: "category",
        valueGetter: function (params) {
          return params.data.espelhamento || "";
        },
      },
    ],
    rowData: [],
    pagination: true,
    defaultColDef: {
      resizable: true,
    },
    paginationPageSize: 10,
    localeText: localeText,
  };
  var gridDivGrupos = document.querySelector("#myGrid");
  new agGrid.Grid(gridDivGrupos, gridOptionsGrupos);

  $(document).on("click", ".botao_grupos", async function (e) {
    e.preventDefault();
    var placa = $(this).attr("data-placa");
    document.getElementById("loading").style.display = "block";

    await $.ajax({
      url: gruposVeiculos,
      type: "POST",
      data: { placa: placa },
      success: function (data) {
        gridOptionsGrupos.api.setRowData(data ? JSON.parse(data) : []);
        preencherExportacoesGrupo(gridOptionsGrupos);
        document.getElementById("loading").style.display = "none";
        $("#myModal").modal("show");
      },
    });
  });
});
