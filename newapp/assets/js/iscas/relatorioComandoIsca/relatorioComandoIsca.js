var localeText = AG_GRID_LOCALE_PT_BR;
$(document).ready(function(){
  $('.btn-expandir').on('click', function (e) {
    e.preventDefault();
    expandirGrid();
});

  var searchOptions = {
    dataInicio: formatDateTime ($("#dataInicial").val()),
    dataFim: formatDateTime($("#dataFim").val()),
    horaInicio: $("#horaInicial").val(),
    horaFim: $("#horaFinal").val(),
    serial: $("#buscaSerial").val(),
    status: $("#status").val(),
  };

  atualizarAgGridHistoricoComandos(searchOptions);

  
  
  $('#formBusca').on('submit', function (e) {
    e.preventDefault();

    var searchOptions = {
      dataInicio: formatDateTime ($("#dataInicial").val()),
      dataFim: formatDateTime($("#dataFim").val()),
      horaInicio: $("#horaInicial").val(),
      horaFim: $("#horaFinal").val(),
      serial: $("#buscaSerial").val(),
      status: $("#status").val(),
    };
    if (validacaoFiltros(searchOptions)) {
      atualizarAgGridHistoricoComandos(searchOptions);
    }
    $("#gerarRelatorio").blur();
});

$("#btnLimpar").click(function (e) {
    e.preventDefault();
    if ($("#dataInicial").val() != "" || $("#dataFim").val()!= "" || $("#horaInicial").val() != "00:00:00" || $("#horaFinal").val() !="23:59:59" || $("#buscaSerial").val()!= "" || $("#status").val() != null) {
        limparCampos();
        atualizarAgGridHistoricoComandos(searchOptions);
    }
});

$("#horaInicial").val('00:00:00');
$("#horaFinal").val('23:59:59');


});

//AG Grid
var AgGridHistoricoComandos;
function atualizarAgGridHistoricoComandos(options) {
    stopAgGRIDHistoricoComandos();
    desabilitarBotoes();
    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/relatorioComandos';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        itemInicio: params.request.startRow,
                        itemFim: params.request.endRow,
                        dataInicio: options ? options.dataInicio : '',
                        dataFim: options ? options.dataFim : '',
                        horaInicio: options ? options.horaInicio : '00:00:00',
                        horaFim: options ? options.horaFim : '23:59:59',
                        serial: options ? options.serial : '',
                        status: options ? options.status : ''
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '-';
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            alert(data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert("error",'Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        }
                        habilitarBotoes();
                    },
                    error: function (error) {
                       showAlert("error",'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        gridOptions.api.showNoRowsOverlay();
                        habilitarBotoes();
                    },
                    
                });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Equipamento',
                field: 'equipamento',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Comando',
                field: 'comando',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                  return formatDateTime(options.value);
              }
            },
            {
                headerName: 'Data de Envio',
                field: 'dataEnvio',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                  return formatDateTime(options.value);
              }
            },
            {
                headerName: 'Data Confirmação',
                field: 'dataConfirmacao',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status',
                field: 'descricaoStatus',
                width: 180,
                suppressSizeToFit: true
            },
            {
                headerName: 'ID Usuário',
                field: 'idUsuario',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Usuário',
                field: 'usuario',
                width: 150,
                suppressSizeToFit: true
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true,
            overlayNoRowsTemplate: 'Não há dados para serem carregados.',

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
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-comandos').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-comandos').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-comandos').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableComandos');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes (gridOptions);
}

function stopAgGRIDHistoricoComandos() {
  var gridDiv = document.querySelector('#tableComandos');
  if (gridDiv && gridDiv.api) {
      gridDiv.api.destroy();
  }

  var wrapper = document.querySelector('.wrapperComandos');
  if (wrapper) {
      wrapper.innerHTML = '<div id="tableComandos" class="ag-theme-alpine my-grid-comandos"></div>';
  }
}

let menuAberto = false;
 
function expandirGrid() {
    menuAberto = !menuAberto;
    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;
 
    if (menuAberto) {
        $(".img-expandir").attr("src", buttonShow);
        $(".col-md-3").fadeOut(250, function () {
            $("#conteudo").removeClass("col-md-9").addClass("col-md-12");
        });
    } else {
        $(".img-expandir").attr("src", buttonHide);
        $("#conteudo").removeClass("col-md-12").addClass("col-md-9");
        setTimeout(() => {
            $(".col-md-3").fadeIn(250);
        }, 510);
    }
}

// Visualização de Loading

function desabilitarBotoes() {
    $('.btn').prop('disabled', true);
    $('.btn-success').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function habilitarBotoes() {
    $('.btn').prop('disabled', false);
    $('.btn-success').html('<i class="fa fa-file-alt"></i> Gerar Relatório').attr('disabled', false);
}



// Utilitários:

function formatDateTime(date) {
  if (!date || typeof date !== 'string') {
      return "";
  }

  const parts = date.split(' ');
  const dateParts = parts[0] ? parts[0].split('-') : null;
  if (!dateParts || dateParts.length !== 3) {
      return "";
  }

  const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
  const timePart = parts.length > 1 ? ` ${parts[1]}` : "";

  return formattedDate + timePart;
}

function validacaoFiltros({ dataInicio, horaInicio, dataFim, horaFim, serial, status }) {
  if (!dataInicio && !dataFim && !serial && !status) {
    showAlert("warning","É necessário informar ao menos um parâmetro para gerar o Relatório.");
    return false;
  }

  if (dataInicio || dataFim) {

    if (!dataInicio || !horaInicio || !dataFim || !horaFim) {
      showAlert("warning","É necessário preencher todos os campos de data e hora.");
      return false;
    }


    if (!validarDatas(dataInicio,dataFim )) {
      return false;
    }
  }

  return true;
}

function validarDatas(dataInicialStr, dataFinalStr) {
  const dataHoraInicial = converterParaDataISO(dataInicialStr);
  const dataHoraFinal = converterParaDataISO(dataFinalStr);
  const agora = new Date();

  if (dataHoraInicial > dataHoraFinal) {
    showAlert("warning",'A Data Inicial não pode ser maior que a Data Final.');
    return false;
  }

  if (dataHoraFinal > agora) {
    showAlert("warning",'A Data Final não pode ser maior que a Data Atual.');
    return false;
  }

  const diferencaTempo = dataHoraFinal - dataHoraInicial;
  const diferencaDias = diferencaTempo / (1000 * 3600 * 24);
  
  if (diferencaDias > 31) {
    showAlert("warning",'O período não pode ser superior a 31 dias.');
    return false;
  }

  return true;
}

function converterParaDataISO(dataStr) {
  const partes = dataStr.split('/');
  return new Date(partes[2], partes[1] - 1, partes[0]);
}

function limparCampos() {
    $("#dataInicial").val('');
    $("#dataFim").val('');
    $("#horaInicial").val('00:00:00');
    $("#horaFinal").val('23:59:59');
    $("#buscaSerial").val('');
    $("#status").val('');
}



