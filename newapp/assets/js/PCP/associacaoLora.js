var localeText = AG_GRID_LOCALE_PT_BR;
let idAssociacaoEditar;

$(document).ready(function () {
  agGridAssociacaoLoRa();
  getClientesSelect2();

$('#status').select2({
      placeholder: "Selecione uma opção",
      allowClear: false
});

  $(".btn-expandir").on("click", function (e) {
    e.preventDefault();
    expandirGrid();
  });

  $("#formBusca").on("submit", function (e) {
    e.preventDefault();
    filtrarDados();
  });

  $("#modalEditarAssociarLora, #modalAssociarLora").on(
    "hide.bs.modal",
    function () {
      getClientesSelect2();
      $('#formAssociarLora')[0].reset();
      $('#formEdicaoAssociarLora')[0].reset();
    }
  );

  $("#BtnLimpar").on("click", function (e) {
    e.preventDefault();
    const camposFiltro = [
        "#serialEquipamento",
        "#idAntenaLora",
        "#nomeCliente",
        "#status"
    ].some((selector) => $(selector).val() !== "" && $(selector).val() !== null);

    if (camposFiltro) {
        limparCamposFormulario();
        agGridAssociacaoLoRa();
    }
});

  $("#cadastrarAssociacao").on("click", function (e) {
    e.preventDefault();
    getClientesSelect2();
    $("#modalAssociarLora").modal("show");
  });

  $("#btnSalvarAssociacao").on("hide.bs.modal", function () {
    $("#idEquipamento").val("");
    $("#idLoRa").val("");
    $("#idCliente").val(null).trigger('change');
  });

  $("#formEdicaoAssociarLora").on("submit", function (e) {
    e.preventDefault();
    if ($("#idEquipamentoEditar").val() == "" || $("#idLoRaEditar").val() == "" || $("#idClienteEditar").val() == null) {
        showAlert("warning", "Os Campos ID Equipamento, ID LoRa e Cliente são obrigatórios.");
        return;
    }

    let dados = {
        id: idAssociacaoEditar,
        serial: $("#idEquipamentoEditar").val(),
        idCliente: $("#idClienteEditar").val(),
        idLora: $("#idLoRaEditar").val(),
        status: 1
    };
    EditarAsociacaoLoRa(dados);
});

  $("#formAssociarLora").on("submit", function (e) {
    e.preventDefault();
    if ($("#idEquipamento").val() == "" || $("#idLoRa").val() == ""  || $("#idCliente").val() == null) {
      showAlert("warning","Os Campos ID Equipamento, ID LoRa e Cliente são obrigatórios.");
      return;
    }

    let dados = {
      serial: $("#idEquipamento").val(),
      idCliente: $("#idCliente").val(),
      idLora: $("#idLoRa").val()
    };
    associarLoRa(dados);
  });


});

function filtrarDados() {
  let idEquipamentoCampo = $("#serialEquipamento").val();
  let loraCampo = $("#idAntenaLora").val();
  let idClienteCampo = $("#nomeCliente").val();
  let statusCampo = $("#status").val();

  if (![idEquipamentoCampo, loraCampo, idClienteCampo, statusCampo].some(field => field !== "" && field !== null)) {
      showAlert("warning", "É necessário preencher ao menos um dos filtros para realizar a busca.");
      $("#BtnPesquisar").blur();
      return;
  }

  const options = {
      idCliente: idClienteCampo || "",
      idLora: loraCampo || "",
      serial: idEquipamentoCampo || "",
      status: statusCampo || ""
  };

  agGridAssociacaoLoRa(options);
}
  

function verificarDatas(dataInicial, dataFinal) {
  const dataInicio = new Date(dataInicial);
  const dataFim = new Date(dataFinal);
  const dataAtual = new Date();

  if (!dataInicial || !dataFinal) {
    showAlert("warning", "Ambas as datas devem ser fornecidas.");
    $("#BtnPesquisar").blur();
    return false;
  }

  if (dataInicio > dataFim) {
    showAlert("warning", "A data inicial não pode ser maior que a data final.");
    $("#BtnPesquisar").blur();
    return false;
  }

  if (dataFim < dataInicio) {
    showAlert("warning", "A data final não pode ser menor que a data inicial.");
    $("#BtnPesquisar").blur();
    return false;
  }

  if (dataFim > dataAtual) {
    showAlert("warning", "A data final não pode ser maior que a data atual.");
    $("#BtnPesquisar").blur();
    return false;
  }

  return true;
}

function limparCamposFormulario() {
  $("#serialEquipamento").val("");
  $("#idAntenaLora").val("");
  $("#status").val("").trigger('change'); 
  $('#nomeCliente').val(null).trigger('change');
}

function associarLoRa(dados) {
  disabledButtons();
  loadingEnviarAssociacao();

  $("#idEquipamento").prop('disabled', true);
  $("#idLoRa").prop('disabled', true);
  $("#idCliente").prop('disabled', true);

  var route = Router + "/associarLora";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    data: dados,
    dataType: "json",
    success: function (response) {
      if (response.status == 200) {
        showAlert("success",`Associação do Equipamento ${dados.serial} realizada com sucesso!`);
        $("#modalAssociarLora").modal("hide");
        agGridAssociacaoLoRa();
      } else {
        let mensagemErro = response.resultado ? response.resultado.mensagem : "Erro na realização da associação, contate o suporte técnico.";
        showAlert("error", mensagemErro);
      }
      resetSalvarAssociacao();
      enableButtons();
      $("#idEquipamento").prop('disabled', false);
      $("#idLoRa").prop('disabled', false);
      $("#idCliente").prop('disabled', false);
    },
    error: function (xhr, status, error) {
      console.error("Erro na solicitação ao servidor:", status, error);
      showAlert("error", "Erro na solicitação ao servidor.");
      resetSalvarAssociacao();
      enableButtons();
      $("#idEquipamento").prop('disabled', false);
      $("#idLoRa").prop('disabled', false);
      $("#idCliente").prop('disabled', false);
    },
  });
}

function EditarAsociacaoLoRa(dados) {
  disabledButtons();
  loadingEnviarAssociacao();
  $("#idEquipamentoEditar").prop('disabled', true); 
  $("#idLoRaEditar").prop('disabled', true); 
  $("#idClienteEditar").prop('disabled', true);

  var route = Router + "/editarAssociacaoLoRa";

  $.ajax({
    cache: false,
    url: route,
    type: "POST",
    data: dados,
    dataType: "json",
    success: function (response) {
      if (response.status == 200) {
        showAlert("success",`Edição da associação do Equipamento ${dados.serial} realizada com sucesso!`);
        $("#modalEditarAssociarLora").modal("hide");
        agGridAssociacaoLoRa();
      } else {
        let mensagemErro = response.resultado.mensagem;
        showAlert("error", mensagemErro);
      }
      resetSalvarAssociacao();
      enableButtons();
      $("#idEquipamentoEditar").prop('disabled', false); 
      $("#idLoRaEditar").prop('disabled', false); 
      $("#idClienteEditar").prop('disabled', false);
    },
    error: function (xhr, status, error) {
      showAlert("error", "Erro na solicitação ao servidor.");
      resetSalvarAssociacao();
      enableButtons();
      $("#idEquipamentoEditar").prop('disabled', false); 
      $("#idLoRaEditar").prop('disabled', false); 
      $("#idClienteEditar").prop('disabled', false);
    },
  });
}

var agGridHistorico;
function agGridAssociacaoLoRa(options) {
  stopAgGRIDAssociacaoLoRa();
  disabledButtons();
  showLoadingPesquisarButton();

  function geServerSideDados() {
    return {
      getRows: (params) => {
        var route = Router + "/buscarAssociacoesLoRa";
        $.ajax({
          cache: false,
          url: route,
          type: "POST",
          data: {
            startRow: params.request.startRow,
            endRow: params.request.endRow,
            idCliente: options ? options.idCliente : "",
            idLora: options ? options.idLora : "",
            serial: options ? options.serial : "",
            status: options ? options.status : "",
          },
          dataType: "json",
          success: function (response) {
            if (response.success && response.rows && response.rows.length > 0) {
              var dados = response.rows.map((row) => {
                for (let key in row) {
                  if (row[key] === "" || row[key] === null) {
                    row[key] = "-";
                  }
                }
                return row;
              });
              params.successCallback(dados, response.lastRow);
            } else {
              gridOptions.api.showNoRowsOverlay();
              params.successCallback([], 0);
            }
            enableButtons();
            resetPesquisarButton();
          },
          error: function (error) {
            showAlert("error", "Erro na solicitação ao servidor.");
            gridOptions.api.showNoRowsOverlay();
            params.successCallback([], 0);
            enableButtons();
            resetPesquisarButton();
          },
        });
      },
    };
  }

  const gridOptions = {
    columnDefs: [
      {
        headerName: "ID",
        field: "id",
        chartDataType: "category",
        width: 80,
        suppressSizeToFit: true,
      },
      {
        headerName: "Cliente",
        field: "nomeCliente",
        flex:1,
        minWidth: 350,
        chartDataType: "series",
      },
      {
        headerName: "Equipamento",
        field: "serial",
        chartDataType: "series",
        width: 200,
      },
      {
        headerName: "ID LoRa",
        field: "idLora",
        chartDataType: "series",
        width: 200,
      },
      {
        headerName: "Data de Cadastro",
        field: "dataCadastro",
        chartDataType: "series",
        width: 200,
        suppressSizeToFit: true,
        cellRenderer: function (options) {
          return formatDateTime(options.value);
        },
      },
      {
        headerName: "Status",
        field: "status",
        chartDataType: "series",
        width: 100,
        suppressSizeToFit: true,
        cellRenderer: function (options) {
          let data = options.data["status"];
          if (data == "Ativo") {
            return `
                <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                `;
          } else {
            return `
                <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                `;
          }
        },
      },
      {
        headerName: "Ação",
        pinned: "right",
        width: 80,
        cellClass: "actions-button-cell",
        sortable: false,
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
            let tableId = "tableAssociacaoLora";
            let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
            let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;
    
            let acao = data.status === "Ativo" ? "Inativar" : "Ativar";
            let status = data.status === "Ativo" ? 0 : 1;
            let dropdownItems = "";
    
            if (data.status === "Ativo") {
                dropdownItems += `
                    <div class="dropdown-item dropdown-item-acoes acoes-lora" style="cursor: pointer;">
                        <a href="javascript:preencherModalVisualizarAssociacao(${encodeURIComponent(
                          JSON.stringify(data)
                        )})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                    </div>
                    <div class="dropdown-item dropdown-item-acoes acoes-lora" style="cursor: pointer;">
                        <a href="javascript:editarAssociacao('${data.id}','${data.idCliente}','${data.idLora}', '${data.serial}')" style="cursor: pointer; color: black;">Editar</a>
                    </div>
                    <div class="dropdown-item dropdown-item-acoes acoes-lora" style="cursor: pointer;">
                        <a href="javascript:alterarStatusAssociacao('${data.id}', '${status}')" style="cursor: pointer; color: black;">${acao}</a>
                    </div>`;
    
                return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-detalhes-tecnologia" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            ${dropdownItems}
                        </div>
                    </div>`;
            } else {
                return `
                    <div>
                        <button class="btn btn-dropdown" type="button" style="margin-top:-6px; width:35px; opacity: 0.5; cursor: default;">
                            <img src="${BaseURL + "media/img/new_icons/icon_acoes.svg"}" alt="Ações">
                        </button>
                    </div>`;
            }
        },
    }    
    ],
    overlayNoRowsTemplate:
      '<span style="padding: 10px; border: 2px solid #444; background: lightgray;">Dados não encontrados!</span>',
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
    enableRangeSelection: true,
    enableCharts: true,
    pagination: true,
    domLayout: "normal",
    cacheBlockSize: 50,
    paginationPageSize: parseInt(
      $("#select-quantidade-por-pagina-dados").val()
    ),
    localeText: localeText,
    rowModelType: "serverSide",
    serverSideStoreType: "partial",
    overlayNoRowsTemplate: localeText.noRowsToShow,
    onRowDoubleClicked: function (params) {
      let data = "data" in params ? params.data : null;
      if (data && "id" in data) {
        preencherModalVisualizarAssociacao(encodeURIComponent(
            JSON.stringify(data)));
      }
    },
  };

  $("#select-quantidade-por-pagina-dados").change(function () {
    var selectedValue = $("#select-quantidade-por-pagina-dados").val();
    gridOptions.api.paginationSetPageSize(Number(selectedValue));
  });

  var gridDiv = document.querySelector("#tableAssociacaoLora");
  gridDiv.style.setProperty("height", "519px");

  agGridHistorico = new agGrid.Grid(gridDiv, gridOptions);
  let datasource = geServerSideDados();
  gridOptions.api.setServerSideDatasource(datasource);

  preencherExportacoesAssociacaoLoRa(gridOptions, "Associação LoRa");
}

function stopAgGRIDAssociacaoLoRa() {
  var gridDiv = document.querySelector("#tableAssociacaoLora");
  if (gridDiv && gridDiv.api) {
    gridDiv.api.destroy();
  }
  var wrapper = document.querySelector(".wrapperAssociacaoLora");
  if (wrapper) {
    wrapper.innerHTML =
      '<div id="tableAssociacaoLora" class="ag-theme-alpine my-grid-associacao-lora"></div>';
  }
}

function preencherModalVisualizarAssociacao(data) {
  if (typeof data === "string") {
    data = JSON.parse(decodeURIComponent(data));
  }

  document.getElementById("idEquipamentoAssociado").value = data.serial || "-";
  document.getElementById("clienteAssociado").value = data.nomeCliente || "-";
  document.getElementById("dataAssociacao").value = formatDateTime(data.dataCadastro) || "-";
  document.getElementById("statusAssociacao").value = data.status || "-";

  $("#modalVisualizarAssociacao").modal("show");
}

async function editarAssociacao(id, idCliente, idLora, serial) {
  try {
      ShowLoadingScreen();

      idAssociacaoEditar = id;
      $("#titleEditarAssociacao").html(`Editar Associação - ${serial.length > 20 ? serial.substring(0, 20) + "..." : serial}`);
      $("#idEquipamentoEditar").val(serial);
      $("#idLoRaEditar").val(idLora);

      await getClientesSelect3(idCliente);

      $('#modalEditarAssociarLora').modal('show');
  } catch (error) {
      showAlert("error", "Erro ao selecionar o cliente: " + error.message);
  } finally {
      HideLoadingScreen();
  }
}

async function getClientesSelect3(id) {
  ShowLoadingScreen();
  await $.ajax({
      url: Router + '/clientesSelect2',
      type: "POST",
      dataType: "json",
      data: {
          id: id
      },
      success: function (data) {
          $("#idClienteEditar").empty();

          $("#idClienteEditar").append('<option value="">Selecione um cliente</option>');

          data.resultado.clientesDTO.forEach(function (item) {
              var option = $('<option selected>');
              option.val(item.id);
              option.text(`${item.nome} (${item.cnpj ? item.cnpj : (item.cpf || '')})`);
              $("#idClienteEditar").append(option);
          });
          HideLoadingScreen();
      },
      error: function (xhr, status, error) {
          showAlert("error", "Erro na solicitação ao servidor")
          HideLoadingScreen();
      }
  });
}


function getClientesSelect2(id) {
  $("#idClienteEditar, #idCliente, #nomeCliente").select2({
      placeholder: "Digite o nome do cliente",
      allowClear: true,
      language: {
          inputTooShort: function () {
              return "Digite pelo menos 4 caracteres";
          },
          noResults: function () {
              return "Cliente não encontrado";
          },
          searching: function () {
              return "Buscando...";
          }
      },
      width: 'resolve',
      height: '32px',
      minimumInputLength: 4,
      ajax: {
          cache: false,
          url: Router + '/clientesSelect2',
          type: "POST",
          delay: 1000,
          data: function (params) {
              var query = {
                  itemInicio: 1,
                  itemFim: 200,
              };

              if (id) {
                  query.id = id;
              }

              if (params.term && params.term.length >= 4) {
                  query.searchTerm = params.term;
              }

              return query;
          },
          dataType: "json",
          beforeSend: function() {
              $("#idClienteEditar, #idCliente, #nomeCliente").each(function() {
                  $(this).empty().append('<option></option>').trigger('change');
              });
          },
          processResults: function (data) {
              if (data.status == 200) {
                  return {
                      results: data.resultado.clientesDTO.map(item => {
                          return {
                              id: item.id,
                              text: `${item.nome} (${(item.cnpj !== null && item.cnpj !== '') ? item.cnpj : (item.cpf === null || item.cpf === '') ? '' : item.cpf})`
                          };
                      }),
                      pagination: {
                          more: false
                      }
                  };
              } else {
                  return {
                      results: []
                  };
              }
          }
      }
  });
}
  

function alterarStatusAssociacao(id, status) {
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente inativar a associação ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar"
      }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            disabledButtons();
            showLoadingPesquisarButton();
            let route = Router + '/alterarStatusAssociacao'
            $.ajax({
                url: route,
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                dataType: 'json',
                success: function (response) {
                    if (response['status'] == 200) {
                    showAlert("success", response['resultado']['mensagem'])
                    } else if (response['status'] == 400) {
                        showAlert("error", response['resultado']['mensagem'])
                    } else if (response['status'] == 404) {
                        showAlert("error", response['resultado']['mensagem'])
                    } else {
                        showAlert("error", "Erro ao atualizar o Status, contate o suporte técnico.")
                    }
                    enableButtons();
                    resetPesquisarButton();
                    agGridAssociacaoLoRa()
                    HideLoadingScreen()
                },
                error: function (error) {
                    showAlert("error", "Erro na solicitação ao servidor")
                    enableButtons();
                    resetPesquisarButton();
                    HideLoadingScreen()
                },
        
            });
        }            
      });
}

function ShowLoadingScreen() {
  $("#loading").show();
}

function HideLoadingScreen() {
  $("#loading").hide();
}

function showLoadingPesquisarButton() {
  $("#BtnPesquisar")
    .html('<i class="fa fa-spinner fa-spin"></i> Carregando...')
    .attr("disabled", true);
}

function loadingEnviarAssociacao() {
  $("#btnSalvarAssociacao, #btnSalvarEdicao").html('<i class="fa fa-spinner fa-spin"></i> Enviando...').attr("disabled", true);
}

function resetSalvarAssociacao() {
  $("#btnSalvarAssociacao, #btnSalvarEdicao").html("Salvar").attr("disabled", false);
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

let menuAberto = false;
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
function formatDateTime(data) {
  let [datePart, timePart] = data.split(" ");
  let [year, month, day] = datePart.split("-");
  let formattedDate = `${day}/${month}/${year}`;
  let formattedDateTime = `${formattedDate} ${timePart}`;
  return formattedDateTime;
}

function abrirDropdownModal(dropdownId, buttonId, tableId) {
  var dropdown = $('#' + dropdownId);

    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }

    $(".dropdown-menu").hide();

    dropdown.show();
    var posDropdown = dropdown.height() + 4;

    var dropdownItems = $('#' + dropdownId + ' .dropdown-item-acoes');
    var alturaDrop = 0;
    for (var i = 0; i <= dropdownItems.length; i++) {
        alturaDrop += dropdownItems.height();
    }

    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)) {
            dropdown.css('top', `-${alturaDrop - 60}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(alturaDrop - 50) - (diferenca + diferenca * 0.5)}px`);
        }
    }

    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });
}
