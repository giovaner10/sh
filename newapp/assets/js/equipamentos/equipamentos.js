const localeText = AG_GRID_LOCALE_PT_BR;
let defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;
var setTime;
let validFileData;
let idEditar;

//MAPA
var mapaDadosPartidaChegada = "";
let mapaDadosPartidaChegadaObserver;
var markers = L.layerGroup();
var marcadores = [];
var marcadoresPartidaChegada = [];

$(document).ready(function () {
  disabledButtons();
  showLoadingPesquisarButton();
  atualizarAgGridEquipamentos();
  inicializarSelects();

  $(window).on('resize', function() {
    $('#filtroEquipamentos, #linha1, #linha2, #linhaEdit, #linha2Edit').select2('close');
  });

  $("#filtroEquipamentos").select2({
    allowClear: false,
    });

  $(".btn-expandir").on("click", function (e) {
    e.preventDefault();
    expandirGrid();
  });

  $("#select-quantidade-por-pagina-dados").on("change", function () {
    atualizarAgGridEquipamentos();
  });

  $("#filtroEquipamentos").on("change", function (e) {
    e.preventDefault();
    $("#searchTableEquipamentos").attr("disabled", false);
    $("#searchTableEquipamentos").val(null);

    let fil = $("#filtroEquipamentos").val();
    let placeholderText = $("#filtroEquipamentos option:selected").text();

    if (fil == "data_cadastro") {
      $("#searchTableEquipamentos").attr("type", "date");
      $("#searchTableEquipamentos").attr("placeholder", `${placeholderText}`);
    } else if (fil == "serial" || fil == "modelo") {
      $("#searchTableEquipamentos").attr("type", "text");
      $("#searchTableEquipamentos").attr("placeholder", `${placeholderText}`);
    } else {
      $("#searchTableEquipamentos").attr("type", "number");
      $("#searchTableEquipamentos").attr("placeholder", `${placeholderText}`);
    }
  });

  $("#formBusca").on("submit", function (e) {
    e.preventDefault();
    if (
      $("#filtroEquipamentos").val() == null ||
      $("#filtroEquipamentos").val() == ""
    ) {
      $("#BtnPesquisar").blur();
      showAlert("warning", "É necessário selecionar o tipo de busca");
      return;
    }

    if (
      $("#searchTableEquipamentos").val() == null ||
      $("#searchTableEquipamentos").val() == ""
    ) {
      $("#BtnPesquisar").blur();
      showAlert("warning", "É necessário preencher o campo Descrição.");
      return;
    }

    $("#BtnPesquisar").blur();
    disabledButtons();
    showLoadingPesquisarButton();
    atualizarAgGridEquipamentos();
  });

  $("#BtnLimpar").on("click", function (e) {
    e.preventDefault();
    if (
      ($("#searchTableEquipamentos").val() != null &&
        $("#searchTableEquipamentos").val() != "") ||
      ($("#filtroEquipamentos").val() != null &&
        $("#filtroEquipamentos").val() != "")
    ) {
      $("#formBusca").trigger("reset");
      $("#filtroEquipamentos").val("todos").trigger('change');
      $("#searchTableEquipamentos").val(null).trigger('change');
      $("#searchTableEquipamentos").attr("Selecionar Filtro");
      $("#searchTableEquipamentos").attr("type", "text");
      $("#searchTableEquipamentos").attr("placeholder","Selecione o tipo de filtro...");
      $("#searchTableEquipamentos").attr("disabled", true);

      atualizarAgGridEquipamentos();
    }
    $("#BtnLimpar").blur();
  });

  $("#modal_posicao").on("hidden.bs.modal", function () {
    $("#body_posicao").html("");
    clearTimeout(setTime);
  });

  $(".visualiza_posicao").on("click", function (e) {
    let url = $(e.currentTarget).attr("data-url");

    if (url) {
      $("#body_posicao").html("<p>Carregando...</p>");
      carregaViewPosicao(url);
    } else {
      $("#modal_posicao").modal("show");
      $("#body_posicao").html(
        '<p>Erro: "URL não identificada". Entre em contato com o adminstrador do sistema!</p>'
      );
    }
  });

  $("#formcadastrar").on("submit", function (e) {
    e.preventDefault();
    $("#btnSalvarCadastro").blur();
    validarCampos();
    cadastrarEquipamento();
  });

  $("#btnLimparCadastro").on("click", function (e) {
    e.preventDefault();
    limparForm("#formcadastrar");
  });

  $("#modal_adicionar_equipamento").on("hide.bs.modal", function () {
    limparForm("#formcadastrar");
  });

  $("#modal_adicionar_equipamento_lote").on("hide.bs.modal", function () {
    $("#fileEquipamentos").val("");
    validFileData = null;
  });

  $("#btnDashboard").on("click", function (e) {
    this.blur();
  });

  $("#info-icon").click(function (e) {
    $("#modalModeloItens").modal("show");
  });

  $("#fileEquipamentos").on("change", function (e) {
    let file = e.target.files[0];
    if (file) {
      validarArquivo(file);
    }
  });

  $("#btnSalvarCadastroLote").on("click", function (e) {
    e.preventDefault();
    let file = document.getElementById("fileEquipamentos").files[0];

    if (!file) {
      showAlert(
        "error",
        "Por favor, carregue um arquivo válido antes de enviar."
      );
      return;
    }

    showSalvarButton();
    disabledButtons();
    enviarDadosLote(validFileData);
  });

  $("#btnEditarEqp").on('click', function(e){
    e.preventDefault();
    if($("#linha1Edit, #linha2Edit").val() === null || $("#linha1Edit, #linha2Edit").val() === "" ){
      showAlert("warning", "Verifique os campos Linha 1 e Linha 2, eles são obrigatório.")
      return;
    }
    enviarEditarEquipamento()
  })

  $("#editar_equipamento").on("hide.bs.modal", function () {
    $("#linha1Edit, #linha2Edit").val(null).trigger('change');
    inicializarSelects();  
  });
});

function inicializarSelects() {
  $("#linha1, #linha2, #linha1Edit, #linha2Edit").select2({
    placeholder: "Digite a linha...",
    allowClear: true,
    minimumInputLength: 3,
    width: "100%",
    language: {
      inputTooShort: function () {
        return "Digite pelo menos 3 caracteres";
      },
      noResults: function () {
        return "Dados não encontrado";
      },
      searching: function () {
        return "Buscando...";
      },
    },
    ajax: {
      url: RouterLinhas,
      dataType: "json",
      delay: 1000,
      data: function (params) {
        return {
          term: params.term,
          _type: "query",
          q: params.term,
        };
      },
      processResults: function (data) {
        if (data.results && data.results.length) {
          return {
            results: data.results.map((item) => {
              return {
                id: item.id,
                text: item.text,
              };
            }),
          };
        } else {
          return {
            results: [],
          };
        }
      },
    },
  });
}

function carregaViewPosicao(url) {
  ShowLoadingScreen();
  $("#modal_posicao").modal("hide");
  $.get(url, function (callback) {
    $("#body_posicao").html(callback);
    let dados = {
      latitude: $("#body_posicao").find("#latitude").text(),
      longitude: $("#body_posicao").find("#longitude").text(),
    };

    if (dados.latitude != "" && dados.longitude != "") {
      limparMarcasNoMapaPartidaChegada(marcadoresPartidaChegada);
      carregarMapaPartidaChegada(-15.39, -55.73, 4);
      marcadoresPartidaChegada = marcarNoMapaPartidaChegada(dados);
    }

    $("#modal_posicao").modal("show");
    HideLoadingScreen();
    setTime = setTimeout(function () {
      carregaViewPosicao(url);
    }, 60000);
  }).fail(function () {
    HideLoadingScreen();
    showAlert(
      "error",
      "Erro na obteção dos dados. Entre em contato com o administrador do sistema."
    );
  });
}

function loadEquipamentos(params) {
  let route = Router + "/loadEquipamentos";
  let draw =
    Math.floor((params ? params.startRow : 0) / (params ? params.endRow : 25)) +
    1;

  AgGridEquipamentos.gridOptions.api.showLoadingOverlay();
  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    dataType: "json",
    data: {
      draw: draw,
      start: params ? params.startRow : 0,
      length: params ? params.endRow - params.startRow : 25,
      filtroEquipamentos: $("#filtroEquipamentos").val(),
      searchTableEquipamentos: $("#searchTableEquipamentos").val(),
    },
    success: function (response) {
      if (params) {
        if (response && response.data) {
          if (response.recordsTotal === 0) {
            params.successCallback([], 0);
            AgGridEquipamentos.gridOptions.api.hideOverlay();
            AgGridEquipamentos.gridOptions.api.showNoRowsOverlay();
            enableButtons();
            resetPesquisarButton();
            showAlert("error", "Dados não encontrados.");
            return;
          }
          // Validação e substituição de valores nulos ou vazios
          response.data = response.data.map((item) => {
            for (let key in item) {
              if (item[key] === null || item[key] === "") {
                item[key] = " ";
              }
            }
            return item;
          });

          params.successCallback(response.data, response.recordsTotal);
          AgGridEquipamentos.gridOptions.api.hideOverlay();
        } else {
          AgGridEquipamentos.gridOptions.api.showNoRowsOverlay();
          params.successCallback([], 0);
        }
      } else {
        atualizarAgGridEquipamentos(response);
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
      if (params) {
        AgGridEquipamentos.gridOptions.api.showNoRowsOverlay();
        params.successCallback([], 0);
      }
    },
  });
}

var allLoadedData = [];
var AgGridEquipamentos;
function atualizarAgGridEquipamentos() {
  stopAgGRIDEquipamentos();

  const gridOptions = {
    columnDefs: [
      {
        headerName: "ID",
        field: "id",
        chartDataType: "series",
        width: 100,
        suppressSizeToFit: true,
      },
      {
        headerName: "Serial",
        field: "serial",
        width: 150,
        chartDataType: "series",
      },
      {
        headerName: "Modelo",
        field: "modelo",
        chartDataType: "series",
        width: 250,
        suppressSizeToFit: true,
      },
      {
        headerName: "Data Cadastro",
        field: "data_cadastro",
        width: 180,
        suppressSizeToFit: true,
        chartDataType: "series",
      },
      {
        headerName: "Linha 1",
        field: "linha1",
        width: 120,
        chartDataType: "series",
      },
      {
        headerName: "CCID 1",
        field: "ccid1",
        chartDataType: "series",
        width: 120,
        suppressSizeToFit: true,
      },
      {
        headerName: "Linha 2",
        field: "linha2",
        width: 120,
        chartDataType: "series",
      },
      {
        headerName: "CCID 2",
        field: "ccid2",
        chartDataType: "series",
        width: 120,
      },
      {
        headerName: "Info",
        field: "info",
        chartDataType: "series",
        width: 180,
      },
      {
        headerName: "Status",
        field: "status",
        chartDataType: "series",
        width: 180,
      },
      {
        headerName: "Ações",
        width: 80,
        pinned: "right",
        cellClass: "actions-button-cell",
        cellRenderer: function (options) {
            var firstRandom = Math.random() * 10;
            var secRandom = Math.random() * 10;
            var thirdRandom = Math.random() * 10;
    
            var varAleatorioIdBotao =
                (firstRandom * secRandom).toFixed(0) +
                "-" +
                (thirdRandom * firstRandom).toFixed(0) +
                "-" +
                (secRandom * thirdRandom).toFixed(0);
    
            let data = options.data;
    
            if (!data) {
                return "";
            }
    
            let tableId = "tableEquipamentos";
            let dropdownId =
                "dropdown-menu-dados" + data.id + varAleatorioIdBotao;
            let buttonId =
                "dropdownMenuButtonDados_" + data.id + varAleatorioIdBotao;
    
            let desvincularOption = '';
    
            if (data.linha1 && data.linha1.trim() !== '' || data.linha2 && data.linha2.trim() !== '') {
                desvincularOption = `
                    <div class="dropdown-item dropdown-item-acoes acoes-item" style="cursor: pointer;">
                        <a onClick='javascript:desvincularEquipamento(${JSON.stringify(data)})' style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Desvincular</a>
                    </div>
                `;
            }
    
            return `
                <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" data-equipamento='${JSON.stringify(data)}' data-id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-regras" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        ${desvincularOption}
                        <div class="dropdown-item dropdown-item-acoes acoes-item" style="cursor: pointer;">
                            <a onClick='javascript:editarEquipamento(${JSON.stringify(data)})' style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                        </div>
                        <div class="dropdown-item dropdown-item-acoes visualiza_posicao acoes-item" style="cursor: pointer;">
                            <a onclick="javascript:carregaViewPosicao('${data.posicao}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                        </div>
                    </div>
                </div>`;
        }    
      },
    ],
    defaultColDef: {
      editable: false,
      sortable: false,
      filter: false,
      resizable: true,
      suppressMenu: true,
      cellClass: "cell-ellipsis",
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
    popupParent: document.body,
    domLayout: "normal",
    pagination: true,
    localeText: localeText,
    cacheBlockSize: 50,
    paginationPageSize: $("#select-quantidade-por-pagina-dados").val(),
    rowModelType: "infinite",
    datasource: {
      getRows: function (params) {
        loadEquipamentos(params);
      },
    },
    onRowDoubleClicked: function (params) {
      let data = "data" in params ? params.data : null;
      if (data) {
        if ("id" in data) {
          carregaViewPosicao(data.posicao);
        }
      }
    },
  };

  var gridDiv = document.querySelector("#tableEquipamentos");
  gridDiv.style.setProperty("height", "519px");
  AgGridEquipamentos = new agGrid.Grid(gridDiv, gridOptions);

  preencherExportacoesEquipamentos(gridOptions);
}

function stopAgGRIDEquipamentos() {
  var gridDiv = document.querySelector("#tableEquipamentos");
  if (gridDiv && gridDiv.api) {
    gridDiv.api.destroy();
  }

  var wrapper = document.querySelector(".wrapperEquipamentos");
  if (wrapper) {
    wrapper.innerHTML =
      '<div id="tableEquipamentos" class="ag-theme-alpine my-grid-equipamentos"></div>';
  }
}

function stripHtmlTags(str) {
  if (str === null || str === "") {
    return "";
  } else {
    str = str.toString();
    return str.replace(/<[^>]*>/g, "");
  }
}

function validarCampos() {
  let serial = $("#serial").val();
  let marca = $("#marca").val();
  let modelo = $("#modelo").val();

  if (!serial) {
    showAlert("warning", "O campo Serial deve ser preenchido.");
    return;
  }
  if (!marca) {
    showAlert("warning", "O campo Marca deve ser preenchido.");
    return;
  }
  if (!modelo) {
    showAlert("warning", "O campo Modelo deve ser preenchido.");
    return;
  }
}

function cadastrarEquipamento() {
  disabledButtons();
  showSalvarButton();

  let form = new FormData();
  form.append("equipamento[serial]", $("#serial").val());
  form.append("equipamento[marca]", $("#marca").val());
  form.append("equipamento[modelo]", $("#modelo").val());
  form.append("linha1", $("#linha1").val());
  form.append("linha2", $("#linha2").val());

  $.ajax({
    url: Router + "/adicionar",
    type: "POST",
    processData: false,
    contentType: false,
    data: form,
    dataType: "json",
    success: function (response) {

      if (response.success) {
        disabledButtons();
        resetSalvarButton();
        $("#modal_adicionar_equipamento").modal("hide");
        atualizarAgGridEquipamentos();
        showAlert("success", response.message);
      } else {
        disabledButtons();
        resetSalvarButton();
        showAlert("error", response.message);
      }
    },
    error: function () {
      disabledButtons();
      resetSalvarButton();
      showAlert("error", "Erro ao cadastrar equipamento.");
    },
  });
}

function enviarEditarEquipamento() {
  Swal.fire({
    title: "Atenção!",
    text: "Deseja realmente editar o equipamento ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#007BFF",
    cancelButtonColor: "#d33",
    confirmButtonText: "Continuar",
    cancelButtonText: "Cancelar",
    allowOutsideClick: false,
    allowEscapeKey: false,
  }).then((result) => {
    if (result.isConfirmed) {
      disabledButtons();
      showSalvarButton();

      let form = new FormData();
      form.append("id_equipamento", idEditar);
      form.append("linha1", $("#linha1Edit").val());
      form.append("linha2", $("#linha2Edit").val());

      $.ajax({
        url: Router + "/editando",
        type: "POST",
        processData: false,
        contentType: false,
        data: form,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            enableButtons();
            resetSalvarButton();
            $("#editar_equipamento").modal("hide");
            atualizarAgGridEquipamentos();
            showAlert("success", response.message);
          } else {
            enableButtons();
            resetSalvarButton();
            showAlert("error", response.message);
          }
        },
        error: function () {
          enableButtons();
          resetSalvarButton();
          showAlert("error", "Erro ao editar equipamento.");
        },
      });
    }
  });
}


function limparForm(form) {
  $(form).trigger("reset");
  $("#linha1, #linha2").val(null).trigger("change");
}

function editarEquipamento(elem) {
  idEditar = elem.id
  $("#editar_equipamento").modal("show");

  let editarHtml = $.parseHTML(elem.editar);

  let id_equipamento = $(editarHtml).attr("data-id");
  let id_linha_1 = $(editarHtml).attr("data-id_linha_1");
  let linha_1 = $(editarHtml).attr("data-linha_1");
  let id_linha_2 = $(editarHtml).attr("data-id_linha_2");
  let linha_2 = $(editarHtml).attr("data-linha_2");

  $("#id_equipamento").val(id_equipamento);

  if (id_linha_1 && linha_1) {
    var $option1 = $("<option selected='selected'></option>")
      .val(id_linha_1)
      .text(linha_1);
    $("#linha1Edit").append($option1).trigger("change");
  }

  if (id_linha_2 && linha_2) {
    var $option2 = $("<option selected='selected'></option>")
      .val(id_linha_2)
      .text(linha_2);
    $("#linha2Edit").append($option2).trigger("change");
  }

  inicializarSelects();
}

function desvincularEquipamento(elem) {
  var idEditar = elem.id;
  Swal.fire({
    title: "Atenção!",
    text: "Deseja realmente desvincular o equipamento?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#007BFF",
    cancelButtonColor: "#d33",
    confirmButtonText: "Continuar",
    cancelButtonText: "Cancelar",
    allowOutsideClick: false,
    allowEscapeKey: false,
  }).then((result) => {
    if (result.isConfirmed) {
      ShowLoadingScreen();
      disabledButtons();
      var formData = new FormData();
      formData.append('id_equipamento', idEditar);
      formData.append('linha1', '');
      formData.append('linha2', '');

      $.ajax({
        url: Router + "/editando",
        type: "POST",
        processData: false,
        contentType: false,
        data: formData,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            HideLoadingScreen();
            enableButtons();
            $("#editar_equipamento").modal("hide");
            atualizarAgGridEquipamentos();
            showAlert("success", response.message);
          } else {
            HideLoadingScreen();
            enableButtons();
            showAlert("error", response.message);
          }
        },
        error: function () {
          HideLoadingScreen();
          enableButtons();
          showAlert("error", "Erro ao desvincular equipamento.");
        },
      });
    }
  });
}




function expandirGrid() {
  menuAberto = !menuAberto;

  let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
  let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

  if (menuAberto) {
    $(".img-expandir").attr("src", buttonShow);
    $("#filtroBusca").hide();
    $("#informacoes").hide();
    $("#conteudo").removeClass("col-md-9");
    $("#conteudo").addClass("col-md-12");
  } else {
    $(".img-expandir").attr("src", buttonHide);
    $("#filtroBusca").show();
    $("#informacoes").show();
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
    .html('<i class="fa fa-search"></i> Pesquisar')
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

function showSalvarButton() {
  $("#btnSalvarCadastro")
    .html('<i class="fa fa-spinner fa-spin"></i> Salvando...')
    .attr("disabled", true);
  $("#btnSalvarCadastroLote")
    .html('<i class="fa fa-spinner fa-spin"></i> Salvando...')
    .attr("disabled", true);
  $("#btnEditarEqp")
    .html('<i class="fa fa-spinner fa-spin"></i> Salvando...')
    .attr("disabled", true);
}

function resetSalvarButton() {
  $("#btnSalvarCadastro").html("Salvar").attr("disabled", false);
  $("#btnSalvarCadastroLote").html("Salvar").attr("disabled", false);
  $("#btnEditarEqp").html("Salvar").attr("disabled", false);
}

function validarArquivo(file) {
  validFileData = null;

  const allowedExtensions = /(\.xlsx|\.xls)$/i;
  if (!allowedExtensions.exec(file.name)) {
    $("#fileEquipamentos").val("");
    showAlert("error", "Apenas arquivos .xlsx e .xls são permitidos.");
    return false;
  }

  let reader = new FileReader();
  reader.onload = function (e) {
    let data = new Uint8Array(e.target.result);
    let workbook = XLSX.read(data, { type: "array" });
    let sheetName = workbook.SheetNames[0];
    let worksheet = workbook.Sheets[sheetName];
    let jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

    if (jsonData.length === 0) {
      validFileData = null;
      $("#fileEquipamentos").val("");
      showAlert("error", "O arquivo está vazio.");
      return false;
    }

    let headers = jsonData[0];
    if (
      headers.length < 3 ||
      headers[0] !== "Serial" ||
      headers[1] !== "Marca" ||
      headers[2] !== "Modelo" ||
      (headers.length > 3 && headers[3] !== "Linha1") ||
      (headers.length > 4 && headers[4] !== "Linha2")
    ) {
      validFileData = null;
      $("#fileEquipamentos").val("");
      showAlert(
        "error",
        "O arquivo deve conter as colunas: Serial, Marca, Modelo, Linha1 (opcional), Linha2 (opcional)"
      );
      return false;
    }

    if (jsonData.length < 2) {
      validFileData = null;
      $("#fileEquipamentos").val("");
      showAlert("error", "Não há dados no arquivo enviado.");
      return false;
    }

    let seenSerials = {};
    let duplicates = [];

    for (let i = 1; i < jsonData.length; i++) {
      let row = jsonData[i];
      if (seenSerials[row[0]]) {
        duplicates.push(row[0]);
        jsonData.splice(i, 1);
        i--;
      } else {
        seenSerials[row[0]] = true;
      }
    }

    if (duplicates.length > 0) {
      showAlert(
        "warning",
        "Dados duplicados encontrados e removidos: " + duplicates.join(", ")
      );
    }

    validFileData = jsonData;
  };
  reader.readAsArrayBuffer(file);
}

function enviarDadosLote(file) {
  let equipamentos = file.slice(1).map((row) => ({
    Serial: row[0],
    Marca: row[1],
    Modelo: row[2],
    Linha1: row[3],
    Linha2: row[4],
  }));

  let formData = new FormData();
  formData.append('equipamentos', JSON.stringify(equipamentos));

  $.ajax({
    url: Router + "/adicionarLote",
    type: "POST",
    processData: false,
    contentType: false,
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.status) {
        enableButtons();
        resetSalvarButton();
        $("#modal_adicionar_equipamento_lote").modal("hide");
        atualizarAgGridEquipamentos();
        showAlert("success", response.msg);
      } else {
        enableButtons();
        resetSalvarButton();
        let falhas = response.falhas
        if(falhas.length != ""){
          showAlert("error", response.falhas);
        }else{
          showAlert("error", response.msg);
        }
      }
    },
    error: function () {
      enableButtons();
      resetSalvarButton();
      showAlert("error", "Erro ao cadastrar equipamentos.");
    },
  });
}

//MAPA
function carregarMapaPartidaChegada(lat = 0, log = 0, zoom = 2) {
  resetarMapaPartidaChegada();
  //carregar mapa na mesma posicao do evento
  mapaDadosPartidaChegada = L.map("mapaDadosPartidaChegada", {
    maxZoom: 15,
    minZoom: 3,
    zoomControl: false,
    worldCopyJump: true,
    dragging: false,
  });
  let osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  });
  mapaDadosPartidaChegada.setView([lat, log], zoom);
  mapaDadosPartidaChegada.dragging.enable();

  osm.addTo(mapaDadosPartidaChegada);

  // Função para chamar invalidateSize
  function ajustarTamanhoMapa() {
    mapaDadosPartidaChegada.invalidateSize();
  }

  // Adicionar observador de mutação com jQuery
  const target = document.getElementById("mapaDadosPartidaChegada");
  mapaDadosPartidaChegadaObserver = new MutationObserver(ajustarTamanhoMapa);

  const config = { attributes: true, childList: true, subtree: true };

  mapaDadosPartidaChegadaObserver.observe(target, config);

  // Certificar-se de chamar a função inicialmente
  ajustarTamanhoMapa();
}

//funcao responsavel por resetar o mapa
function resetarMapaPartidaChegada() {
  if (mapaDadosPartidaChegada != "") {
    mapaDadosPartidaChegada.off();
    mapaDadosPartidaChegada.remove();
    mapaDadosPartidaChegadaObserver.disconnect();
  }
}

function limparMarcasNoMapaPartidaChegada(marcadores) {
  if (marcadores != null) {
    marcadores.forEach((marker) => {
      mapaDadosPartidaChegada.removeLayer(marker);
    });
  }
}

//marca os pins no mapa
function marcarNoMapaPartidaChegada(dados) {
  let popup;
  let marker;

  if (dados != null) {
    // todos os eventos selecionados
    if (dados.length != 0) {
      popup = L.popup({ closeButton: false, autoClose: true }).setContent(
        htmlPopUpPartidaChegada(dados)
      );

      var dotStyles = `
                  background-color: #FFFFFF; /* Cor de fundo branca */
                  width: 1rem;
                  height: 1rem;
                  display: block;
                  position: absolute;
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                  border-radius: 50%; /* Bordas arredondadas para formar uma bolinha */
              `;

      var markerHtmlStyles = `
              background-color: #0064FF;
              width: 3rem;
              height: 3rem;
              display: block;
              left: -1.5rem;
              top: -1.5rem;
              position: relative;
              border-radius: 3rem 3rem 0;
              transform: rotate(45deg);
              border: 1px solid #FFFFFF;
          `;

      var icon = L.divIcon({
        className: "my-custom-pin",
        iconAnchor: [0, 24],
        labelAnchor: [-6, 0],
        popupAnchor: [0, -36],
        html: `
                      <span style="${markerHtmlStyles}">
                      <span style="${dotStyles}">
                      </span>
                  `,
      });

      marker = L.marker([dados.latitude, dados.longitude], {
        draggable: false,
        id: -1,
        icon: icon,
      }).addTo(mapaDadosPartidaChegada);

      marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });

      marcadores.push(marker);
    }
  }

  return marcadores;
}

function htmlPopUpPartidaChegada(data) {
  return `
  <div class="card-text-map">
      <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
          <li class="list-group-item"> 
              <span class="item-popup-title">${lang.serial}:</span>      
              <span class="float-right" style="color:#909090">${
                data.serial ? data.serial : ""
              }</span>
          </li>
          <li class="list-group-item"> 
              <span class="item-popup-title">Coordenadas:</span> <br/>   
              <div style="margin-top: 5px;">
                  <span class="badge badge-info" style="padding-top: 3px;">${
                    data.latitude
                  }</span>
                  <span class="badge badge-info" style="padding-top: 3px;">${
                    data.longitude
                  }</span> 
              </div>
          </li>
          <li class="list-group-item"> 
              <span class="item-popup-title">Ref.:</span>      
              <span class="" style="color:#909090">${
                data.endereco ? data.endereco : "Referência não encontrada"
              }</span>
          </li>
      </ul>
  </div>
  `;
}
