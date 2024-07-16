var localeText = AG_GRID_LOCALE_PT_BR;

document.addEventListener("DOMContentLoaded", async function () {
  var dropdown = $("#opcoes_exportacao");
  var result = [];
  const gridOptions = {
    columnDefs: [
      { headerName: 'Base', field: 'base', valueGetter: function(params) { return params.data.base || "" } },
      { headerName: 'Botao de Pânico', field: 'botaoPanico', valueGetter: function(params) { return params.data.botaoPanico || "" } },
      { headerName: 'Data', field: 'data', type: "dateColumn", valueGetter: function(params) { return params.data.data || "" } },
      { headerName: 'Data do Sistema', field: 'dataSys', type: "dateColumn", valueGetter: function(params) { return params.data.dataSys || "" } },
      { headerName: 'Direção', field: 'direcao', valueGetter: function(params) { return params.data.direcao || "" } },
      { headerName: 'Endereço', field: 'localizacao.endereco', valueGetter: function(params) { return (params.data.localizacao && params.data.localizacao.endereco) || "" } },
      { headerName: 'Latitude', field: 'localizacao.latitude', valueGetter: function(params) { return (params.data.localizacao && params.data.localizacao.latitude) || "" } },
      { headerName: 'Longitude', field: 'localizacao.longitude', valueGetter: function(params) { return (params.data.localizacao && params.data.localizacao.longitude) || "" } },
      { headerName: 'Engate da carreta', field: 'engateCarreta', valueGetter: function(params) { return params.data.engateCarreta || "" } },
      { headerName: 'Estado', field: 'estado', valueGetter: function(params) { return params.data.estado || "" } },
      { headerName: 'TeleEvento', field: 'teleEvento', valueGetter: function(params) { return params.data.teleEvento || "" } },
      { headerName: 'Hodômetro', field: 'odometro', valueGetter: function(params) { return params.data.odometro || "" } },
      { headerName: 'Tipo de Comunicacao', field: 'tipoComunicacao', valueGetter: function(params) { return params.data.tipoComunicacao || "" } },
      { headerName: 'Ignição', field: 'ignicao', valueGetter: function(params) { return params.data.ignicao || "" } },
      { headerName: 'Intervalo de transmissão', field: 'intervaloTransmissao', valueGetter: function(params) { return params.data.intervaloTransmissao || "" } },
      { headerName: 'Lacre do baú', field: 'lacreBau', valueGetter: function(params) { return params.data.lacreBau || "" } },
      { headerName: 'Motorista', field: 'motorista', valueGetter: function(params) { return params.data.motorista || "" } },
      { headerName: 'Placa', field: 'placa', valueGetter: function(params) { return params.data.placa || "" } },
      { headerName: 'Prefixo do Veiculo', field: 'prefixoVeiculo', valueGetter: function(params) { return params.data.prefixoVeiculo || "" } },
      { headerName: 'RPM', field: 'rpm', valueGetter: function(params) { return params.data.rpm || "" } },
      { headerName: 'Status GPS', field: 'statusGps', valueGetter: function(params) { return params.data.statusGps || "" } },
      { headerName: 'Status de Posição', field: 'statusPosicao', valueGetter: function(params) { return params.data.statusPosicao || "" } },
      { headerName: 'Falha de trava do motorista', field: 'falhaTravaMotorista', valueGetter: function(params) { return params.data.falhaTravaMotorista || "" } },
      { headerName: 'Temperaturas', field: 'temperaturas', valueGetter: function(params) { return params.data.temperaturas || "" } },
      { headerName: 'Horímetro', field: 'horimetro', valueGetter: function(params) { return params.data.horimetro || "" } },
      { headerName: 'Umidade', field: 'umidade', valueGetter: function(params) { return params.data.umidade || "" } },
      { headerName: 'Velocidade', field: 'velocidade', valueGetter: function(params) { return params.data.velocidade || "" } },
      { headerName: 'Voltagem', field: 'voltagem', valueGetter: function(params) { return params.data.voltagem || "" } },
      { headerName: 'Serial', field: 'serial', valueGetter: function(params) { return params.data.serial || "" } },
      { headerName: 'Saída 1', field: 'OUT1', valueGetter: function(params) { return params.data.OUT1 || "" } },
      { headerName: 'Saída 2', field: 'OUT2', valueGetter: function(params) { return params.data.OUT2 || "" } }
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
        url: RouterUrl + '/' + placa + '/' + code + (porDia ? '/diaria' : '/semanal'),
        type: "POST",
        error: function (xhr, status, error) {
          console.error("Erro na requisição Ajax:", error);
        },
      });
      return JSON.parse(response);
    } catch (error) {
      return [];
    }
  }

  function updateData(newData) {
    gridOptions.api.setRowData(newData);
  }


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


  $("#filtrar-atributos").on("keyup", function () {
    const inputValue = $(this).val().trim().toLowerCase();

    const filteredResult = result.filter(
      (item) =>
        item.placa.toLowerCase().includes(inputValue) ||
        item.prefixoVeiculo.toLowerCase().includes(inputValue) ||
        item.base.toLowerCase().includes(inputValue)||
        item.serial.toLowerCase().includes(inputValue)||
        item.lacreBau.toLowerCase().includes(inputValue)

    );
    updateData(filteredResult);
  });


  $("#BtnLimparFiltro").on("click", function () {
    $("#filtrar-atributos").val("");
    updateData(result);
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

