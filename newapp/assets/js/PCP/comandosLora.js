var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    agGridHistoricoComandos()

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $("#formBusca").on('submit', function (e) {
        e.preventDefault();
        filtrarDados();
    })

    $("#BtnLimpar").on('click', function (e) {
        e.preventDefault();
        const camposFiltro = ["#serialEquipamento", "#dataInicialBusca", "#dataFinalBusca"].some(selector => $(selector).val() !== '');
        
        if (camposFiltro) {
            limparCamposFormulario();
            agGridHistoricoComandos();
        }
    });

    $("#cadastrarComando").on('click', function(e){
        e.preventDefault();
        $('#idComando').select2({
            placeholder: "Selecione um comando para ser enviado..."
        });
        $("#envioComando").modal('show');
    })

    $("#envioComando").on("hide.bs.modal", function () {
        $("#idEquipamento").val('');
        $("#idComando").val('');
    });


    $("#formEnvioComando").on('submit', function (e) {
        e.preventDefault();
        if($("#idComando").val() != "0" && $("#idComando").val() != "1"){
            showAlert('warning', "É necessário selecionar um comando para ser enviado.");
            return;
        }
        let dados = {
            idTerminal: $("#idEquipamento").val(),
            tipoComando: $("#idComando").val(),
        }
        enviarComandoLoRa(dados);
    })
    
})


function filtrarDados() {
    let idTerminal = $("#serialEquipamento").val();
    let dataInicio = $("#dataInicialBusca").val();
    let dataFim = $("#dataFinalBusca").val();

    if ((idTerminal == '' && dataInicio == '' && dataFim == '') || (idTerminal == null && dataInicio == null && dataFim == null)) {
        showAlert('warning', "É necessário preencher os filtros para realizar a busca.");
        $("#BtnPesquisar").blur();
        return;
    }

    if (dataInicio != '' || dataFim !='') {
        if (!verificarDatas(dataInicio, dataFim)) {
            return;
        }
    }

    const options = {
        serial: idTerminal,
        dataInicial: dataInicio,
        dataFinal: dataFim
    };

    agGridHistoricoComandos(options);
}

function verificarDatas(dataInicial, dataFinal) {
    const dataInicio = new Date(dataInicial);
    const dataFim = new Date(dataFinal);
    const dataAtual = new Date();

    if (!dataInicial || !dataFinal) {
        showAlert('warning', "Ambas as datas devem ser fornecidas.");
        $("#BtnPesquisar").blur();
        return false;
    }

    if (dataInicio > dataFim) {
        showAlert('warning', "A data inicial não pode ser maior que a data final.");
        $("#BtnPesquisar").blur();
        return false;
    }

    if (dataFim < dataInicio) {
        showAlert('warning', "A data final não pode ser menor que a data inicial.");
        $("#BtnPesquisar").blur();
        return false;
    }

    if (dataFim > dataAtual) {
        showAlert('warning', "A data final não pode ser maior que a data atual.");
        $("#BtnPesquisar").blur();
        return false;
    }

    return true;
}

function limparCamposFormulario() {
    $("#serialEquipamento").val('');
    $("#dataInicialBusca").val('');
    $("#dataFinalBusca").val('');
}

function enviarComandoLoRa(dados) {
    disabledButtons();
    loadingEnviarComando();

    var route = Router + "/enviarComandoLoRa";

    $.ajax({
        cache: false,
        url: route,
        type: "POST",
        data: dados,
        dataType: "json",
        success: function(response) {
            if (response.status == 200) {
                showAlert('success', `Comando enviado ao terminal ${dados.idTerminal}`);
                $("#envioComando").modal('hide');
                agGridHistoricoComandos();
            } else {
                let mensagemErro = response.resultado.mensagem;
                showAlert('error', mensagemErro);
            }
            resetEnviarComando();
            enableButtons();
        },
        error: function(xhr, status, error) {
            console.error("Erro na solicitação ao servidor:", status, error);
            showAlert("error", "Erro na solicitação ao servidor.");
            resetEnviarComando();
            enableButtons();
        }
    });
}


var agGridHistorico
function agGridHistoricoComandos(options) {
    stopAgGRIDHistorico();
    disabledButtons();
    showLoadingPesquisarButton();

    function geServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + "/buscarHistoricoComandos";
                $.ajax({
                    cache: false,
                    url: route,
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        serial: options ? options.serial : "",
                        dataInicial: options ? options.dataInicial : "",
                        dataFinal: options ? options.dataFinal : "",
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success && response.rows && response.rows.length > 0) {
                            var dados = response.rows.map(row => {
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
                    }
                });
            },
        };
    }

    const gridOptions = {
      columnDefs: [
        {
          headerName: "ID",
          field: "cmdId",
          chartDataType: "category",
          width: 80,
          suppressSizeToFit: true,
        },
        {
          headerName: "Equipamento",
          field: "cmdEqp",
          width: 200,
          chartDataType: "series",
        },
        {
          headerName: "Comando",
          field: "cmdComando",
          chartDataType: "series",
          width: 200,
        },
        {
          headerName: "Data de Cadastro",
          field: "cmdCadastro",
          chartDataType: "series",
          width: 200,
          suppressSizeToFit: true,
          cellRenderer: function (options) {
            if (options.value != "-"){
                return formatDateTime(options.value);
            }
        }},
        {
          headerName: "Data de Envio",
          field: "cmdEnvio",
          chartDataType: "category",
          width: 200,
          suppressSizeToFit: true,
          cellRenderer: function (options) {
            if (options.value != "-"){
                return formatDateTime(options.value);
            }
          },
        },
        {
          headerName: "Status",
          field: "descricaoStatus",
          chartDataType: "series",
          flex: 1,
          minWidth: 350,
          suppressSizeToFit: true,
        },
        {
            headerName: 'Ação',
            pinned: 'right',
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
                let tableId = "tableHistoricoComandos";
                let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

        
                return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="abrirDropdownModal('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px;">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes acoes-lora" style="cursor: pointer;">
                                <a href="javascript:preencherModalHistorico(${encodeURIComponent(JSON.stringify(data))})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                            </div>
                        </div>
                    </div>`;
          },
        },
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
        if (data && "cmdEqp" in data) {
          preencherModalHistorico(data);
        }
      },
    };

    $('#select-quantidade-por-pagina-dados').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableHistoricoComandos');
    gridDiv.style.setProperty('height', '519px');

    agGridHistorico = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = geServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesHistoricoComandos(gridOptions, 'HistoricoComandos');
}

function stopAgGRIDHistorico() {
    var gridDiv = document.querySelector('#tableHistoricoComandos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperHistoricoComandos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableHistoricoComandos" class="ag-theme-alpine my-grid-hitorico-comandos"></div>';
    }
}

function preencherModalHistorico(data) {
    if (typeof data === 'string') {
        data = JSON.parse(decodeURIComponent(data));
    }

    document.getElementById('idComandoModal').value = data.cmdId || '-';
    document.getElementById('idEquipamentoModal').value = data.cmdEqp || '-';
    document.getElementById('comandoModal').value = data.cmdComando || '-';
    document.getElementById('dataCadastroModal').value = data.cmdCadastro != "-" ?  formatDateTime(data.cmdCadastro) : '-';
    document.getElementById('dataEnvioModal').value = data.cmdEnvio != "-" ? formatDateTime(data.cmdEnvio)  : '-';
    document.getElementById('statusComandoModal').value = `${data.status} - ${data.descricaoStatus}` || '-';
    document.getElementById('mensagemComandoModal').value = data.mensagem || '-';
    document.getElementById('observacaoComandoModal').value = data.observacao || '-';
    
    $('#modalHistorico').modal('show');
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function loadingEnviarComando() {
    $('#btnEnviarComando').html('<i class="fa fa-spinner fa-spin"></i> Enviando...').attr('disabled', true);
}

function resetEnviarComando() {
    $('#btnEnviarComando').html('Enviar').attr('disabled', false);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function disabledButtons() {
    $('.btn').attr('disabled', true);
}
function enableButtons() {
    $('.btn').attr('disabled', false);
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
function formatDateTime(data) {
    if(data != null && data != ''){
        let [datePart, timePart] = data.split(' ');
        let [year, month, day] = datePart.split('-');
        let formattedDate = `${day}/${month}/${year}`;
        let formattedDateTime = `${formattedDate} ${timePart}`;
        return formattedDateTime;
    } else {
        " "
    }
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

    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;

    if (posDropdown > (posBordaTabela - posButton)) {
        dropdown.css('top', '5%');
    }

    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });

    $(document).on('contextmenu', function () {
        dropdown.hide();
    });
}