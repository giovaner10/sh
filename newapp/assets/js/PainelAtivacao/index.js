var localeText = AG_GRID_LOCALE_PT_BR;
let intervalReferences = [];
var serialBuscaRel = '';
var naBuscaRel = '';
var dataInicioBuscaRel = '';
var dataFimBuscaRel = '';

$(document).ready(function () {
    var dropdown = $('#opcoes_atualizar');

    $('#dropdownMenuButtonAtualizar').click(function () {
        dropdown.toggle();
    });

    $(document).click(function (event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonAtualizar') {
            dropdown.hide();
        }
    });

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#stopInterval').on('click', function () {
        stopIntervals();
    })
    
    $('#10seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => atualizarAgGrid(), 1000 * 10);
        intervalReferences.push(myInterval);
    });

    $('#60seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => atualizarAgGrid(), 1000 * 60);
        intervalReferences.push(myInterval);
    });

    $('#180seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => atualizarAgGrid(), 1000 * 180);
        intervalReferences.push(myInterval);
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();

        let searchOptions = {
            serial: $('#serial').val(),
            numNA: $('#numNA').val(),
            dataInicio: formatarData($('#dataInicial').val()),
            dataFim: formatarData($('#dataFinal').val()),
        }

        if (searchOptions.serial || searchOptions.numNA || searchOptions.dataInicio || searchOptions.dataFim) {
            showLoadingPesquisarButton();
            if(validacaoFiltros()){
                serialBuscaRel = $('#serial').val();
                naBuscaRel = $('#numNA').val();
                dataInicioBuscaRel = formatarData($('#dataInicial').val());
                dataFimBuscaRel = formatarData($('#dataFinal').val());
                atualizarAgGrid(searchOptions);
            }else{
                resetPesquisarButton();
                return;
            }
        } else {
            showAlert('warning', 'Digite pelo menos um campo para fazer a pesquisa.');
        }

    });

    $('#BtnLimparFiltro').on('click', function (e) {
        showLoadingLimparButton();
        serialBuscaRel = '';
        naBuscaRel = '';
        dataInicioBuscaRel = '';
        dataFimBuscaRel = '';
        limparPesquisa();
        atualizarAgGrid();
    });

    $('#ativacao .nav-link').on('click', function (e) {
        let tabId = $(this).attr('id');
        if (tabId == 'tab-processo-ativacao') {
            $('#div-processo-ativacao').show();
            $('#div-formulario-ativacao').hide();
            $('#div-inventario-ativacao').hide();
        } else if (tabId == 'tab-formulario-ativacao') {
            $('#div-formulario-ativacao').show();
            $('#div-processo-ativacao').hide();
            $('#div-inventario-ativacao').hide();
        } else if (tabId == 'tab-inventario-ativacao') {
            $('#div-inventario-ativacao').show();
            $('#div-processo-ativacao').hide();
            $('#div-formulario-ativacao').hide();
        }
    });

    atualizarAgGrid();
    atualizarAgGridProcessoAtivacao();
})

function validacaoFiltros() {
    dataInicio = $("#dataInicial").val();
    dataFim = $("#dataFinal").val();

    if (!dataInicio && !dataFim) {
        showAlert("warning", "É necessário informar a Data Inicial e Data Final");
        return false;
    }

    if (dataInicio && !dataFim) {
        showAlert("warning", "É necessário informar a Data Final");
        return false;
    }

    if (!dataInicio && dataFim) {
        showAlert("warning", "É necessário informar Data Inicial");
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

    const umDiaEmMilissegundos = 24 * 60 * 60 * 1000;

    const diferencaEmDias = Math.round(Math.abs((dataFinal - dataInicial) / umDiaEmMilissegundos));

    if (dataInicial > dataFinal) {
        showAlert("warning", "A data inicial não pode ser maior que a data final.");
        return false;
    }

    if (dataFinal > dataAtual) {
        showAlert("warning", "A data final não pode ser maior que a data atual.");
        return false;
    }

    if (dataInicial > dataAtual) {
        showAlert("warning", "A data inicial não pode ser maior que a data atual.");
        return false;
    }

    return true;
}

function formatarData(data) {
    let partesData = data.split('-');
    let dataFormatada = partesData[2] + '/' + partesData[1] + '/' + partesData[0];
    return dataFormatada;
}

function abrirAtivacao(codigo) {
    limparModal();
    $('#titleProcessoAtivacao').html('Visualizar Ativação');
    if (codigo) {
        ShowLoadingScreen();
        $('#tab-processo-ativacao').trigger("click");
        $('#titleProcessoAtivacao').html('Visualizar Ativação - ' + codigo);

        var processCompleted = false;
        var formCompleted = false;
        var inventarioCompleted = false;
        var ItensInventarioCompleted = false;

        function checkCompleted() {
            if (processCompleted && formCompleted && inventarioCompleted && ItensInventarioCompleted) {
                HideLoadingScreen();
                $('#ativacao').modal('show');
            }
        }

        getProcessoAtivacao(codigo, function (success, data, mensagem) {
            if (success) {
                atualizarAgGridProcessoAtivacao(data);
            } else {
                atualizarAgGridProcessoAtivacao();
                $('#alert-processo-ativacao').show();
                $('#alert-msg-processo-ativacao').html(mensagem);
            }
            processCompleted = true;
            checkCompleted();
        });

        getFormularioAtivacao(codigo, function (success, data, mensagem) {
            if (success) {
                preencherFormularioAtivacao(data);
            } else {
                $('#alert-formulario-ativacao').show();
                $('#alert-msg-formulario-ativacao').html(mensagem);
            }
            formCompleted = true;
            checkCompleted();
        });

        getInventarioAtivacao(codigo, function (success, data, mensagem) {
            if (success) {
                preencherInventarioAtivacao(data);
            } else {
                $('#alert-inventario-ativacao').show();
                $('#alert-msg-inventario-ativacao').html(mensagem);
            }
            inventarioCompleted = true;
            checkCompleted();
        });

        getItensInventarioAtivacao(codigo, function (success, data, mensagem) {
            if (success) {
                atualizarAgGridItensInventario(data);
            } else {
                atualizarAgGridItensInventario();
                $('#alert-itens-inventario-ativacao').show();
                $('#alert-msg-itens-inventario-ativacao').html(mensagem);
            }
            ItensInventarioCompleted = true;
            checkCompleted();
        });
    } else {
        showAlert('warning', 'Esse registro não possui código. Portanto, não é possível visualizá-lo.');
    }
}

function getProcessoAtivacao(codigo, callback) {
    $.ajax({
        cache: false,
        url: Router + '/buscarProcessoAtivacao',
        type: 'POST',
        data: {
            codigo: codigo
        },
        dataType: 'json',
        beforeSend: function () {
            AgGridProcessoAtivacao.gridOptions.api.showLoadingOverlay();
        },
        success: function (data) {
            if (data.status == 200) {
                if (typeof callback == 'function') callback(true, data.resultado, false);
            } else if (data.status == 404) {
                if (typeof callback == 'function') callback(false, [], false);
            } else if (data.status == 400) {
                if ('mensagem' in data.resultado && data.resultado.mensagem) {
                    if (typeof callback == 'function') callback(false, [], data.resultado.mensagem);
                } else {
                    if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar os Processos de Ativação.');
                }
            } else {
                if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar os Processos de Ativação.');
            }
        },
        error: function () {
            if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar os Processos de Ativação.')
        }
    })
}

function getFormularioAtivacao(codigo, callback) {
    $.ajax({
        cache: false,
        url: Router + '/buscarFormularioAtivacao',
        type: 'POST',
        data: {
            codigo: codigo
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                if (typeof callback == 'function') callback(true, data.resultado, false);
            } else if (data.status == 404) {
                if (typeof callback == 'function') callback(false, [], 'Formulário de Ativação não encontrado.');
            } else if (data.status == 400) {
                if ('mensagem' in data.resultado && data.resultado.mensagem) {
                    if (typeof callback == 'function') callback(false, [], data.resultado.mensagem);
                } else {
                    if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar o Formulário de Ativação.');
                }
            } else {
                if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar o Formulário de Ativação.');
            }
        },
        error: function () {
            if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar o Formulário de Ativação.')
        }
    })
}

function getInventarioAtivacao(codigo, callback) {
    $.ajax({
        cache: false,
        url: Router + '/buscarInventarioAtivacao',
        type: 'POST',
        data: {
            codigo: codigo
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                if (typeof callback == 'function') callback(true, data.resultado, false);
            } else if (data.status == 404) {
                if (typeof callback == 'function') callback(false, [], 'Inventário de Ativação não encontrado.');
            } else if (data.status == 400) {
                if ('mensagem' in data.resultado && data.resultado.mensagem) {
                    if (typeof callback == 'function') callback(false, [], data.resultado.mensagem);
                } else {
                    if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar o Inventário de Ativação.');
                }
            } else {
                if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar o Inventário de Ativação.');
            }
        },
        error: function () {
            if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar o Inventário de Ativação.')
        }
    })
}

function getItensInventarioAtivacao(codigo, callback) {
    $.ajax({
        cache: false,
        url: Router + '/buscarItensInventarioAtivacao',
        type: 'POST',
        data: {
            codigo: codigo
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                if (typeof callback == 'function') callback(true, data.resultado, false);
            } else if (data.status == 404) {
                if (typeof callback == 'function') callback(false, [], 'Itens de Inventário de Ativação não encontrado.');
            } else if (data.status == 400) {
                if ('mensagem' in data.resultado && data.resultado.mensagem) {
                    if (typeof callback == 'function') callback(false, [], data.resultado.mensagem);
                } else {
                    if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar os Itens de Inventário de Ativação.');
                }
            } else {
                if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar os Itens de Inventário de Ativação.');
            }
        },
        error: function () {
            if (typeof callback == 'function') callback(false, [], 'Não foi possível buscar os Itens de Inventário de Ativação.')
        }
    })
}

function limparModal() {
    $('.alert').hide();
    $('#origemForm').val('');
    $('#naCRMForm').val('');
    $('#tipoServ').val('');
    $('#serieAntForm').val('');
    $('#idSataNt').val('');
    $('#iccid1Ant').val('');
    $('#iccid2Ant').val('');
    $('#renavan').val('');
    $('#placa').val('');
    $('#celTecnico').val('');
    $('#tipoVeiculo').val('');
    $('#corVeiculo').val('');
    $('#modeloVeiculo').val('');
    $('#codCRM').val('');
    $('#servCRM').val('');
    $('#hodometro').val('');
    $('#origemInvent').val('');
    $('#antenaSat').val('');
    $('#nserie').val('');
}

function preencherFormularioAtivacao(data) {
    $('#origemForm').val(data.origem ? data.origem : '');
    $('#naCRMForm').val(data.naCRM ? data.naCRM : '');
    $('#tipoServ').val(data.tipoServ ? data.tipoServ : '');
    $('#serieAntForm').val(data.serieAntForm ? data.serieAntForm : '');
    $('#idSataNt').val(data.idSataNt ? data.idSataNt : '');
    $('#iccid1Ant').val(data.iccid1Ant ? data.iccid1Ant : '');
    $('#iccid2Ant').val(data.iccid2Ant ? data.iccid2Ant : '');
    $('#renavan').val(data.renavan ? data.renavan : '');
    $('#placa').val(data.placa ? data.placa : '');
    $('#celTecnico').val(data.celTecnico ? data.celTecnico : '');
    $('#tipoVeiculo').val(data.tipoVeiculo ? data.tipoVeiculo : '');
    $('#corVeiculo').val(data.corVeiculo ? data.corVeiculo : '');
    $('#modeloVeiculo').val(data.modeloVeiculo ? data.modeloVeiculo : '');
    $('#codCRM').val(data.codCRM ? data.codCRM : '');
    $('#servCRM').val(data.servCRM ? data.servCRM : '');
    $('#hodometro').val(data.hodometro ? data.hodometro : '');
}

function preencherInventarioAtivacao(data) {
    $('#origemInvent').val(data.origem ? data.origem : '');
    $('#antenaSat').val(data.antenaSat ? data.antenaSat : '');
    $('#nserie').val(data.nserie ? data.nserie : '');
}

//Ultilitarios 
let menuAberto = false;
function expandirGrid() {
    menuAberto = !menuAberto;

    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

    if (menuAberto) {
        $('.img-expandir').attr("src", buttonShow);
        $('#filtroBusca').hide();
        $('.menu-interno').hide();
        $('#conteudo').removeClass('col-md-9');
        $('#conteudo').addClass('col-md-12');
    } else {
        $('.img-expandir').attr("src", buttonHide);
        $('#filtroBusca').show();
        $('.menu-interno').show();
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

function exibirAlerta(icon, title, text) {
    Swal.fire({
        position: 'top-start',
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true
    });
}

function limparPesquisa() {
    $('#formBusca').trigger("reset");
}

function abrirDropdown(dropdownId, buttonId, tableId) {
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
    $(document).on('contextmenu', function () {
        dropdown.hide();
    });
}

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
}

function formatDate(date) {
    dateCalendar = date.split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0];
}


// AgGrid
var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {
                let dataAtual = new Date();
                
                var route = Router + '/buscarDadosServerSide';
                if (options) {
                    serialBuscaRel = options.serial;
                    naBuscaRel = options.numNA;
                    dataInicioBuscaRel = options.dataInicio;
                    dataFimBuscaRel = options.dataFim;
                } else {
                    serialBuscaRel = '';
                    naBuscaRel = '';
                    dataInicioBuscaRel = '';
                    dataFimBuscaRel = '';
                }
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        serial: options ? options.serial : '',
                        numNA: options ? options.numNA : '',
                        dataInicio: options ? options.dataInicio : dataAtual.toLocaleDateString('pt-BR'),
                        dataFim: options ? options.dataFim : dataAtual.toLocaleDateString('pt-BR')
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        if (AgGrid) {
                            AgGrid.gridOptions.api.showLoadingOverlay();
                        }
                    },
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    // Verifica se o valor é null e substitui por uma string vazia
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                }
                            }
                            AgGrid.gridOptions.api.hideOverlay();
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message && data.statusCode != 500) {
                            showAlert('warning', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor.');
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
                        showAlert('error', 'Erro na solicitação ao servidor.');
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

    function getContextMenuItems(params) {
        if (params && params.node && 'data' in params.node && 'codigo' in params.node.data) {
            var result = [
                {
                    // custom item
                    name: 'Visualizar',
                    action: () => {
                        abrirAtivacao(params.node.data.codigo)
                    },
                }
            ];
        } else {
            var result = [];
        }

        return result;
    }

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Status',
                field: 'status',
                width: 250,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Concluído com sucesso') {
                        return `<span class="badge badge-success">${options.value}</span>`;

                    } else if (
                        options.value == 'Pendente inicia ativação' ||
                        options.value == 'Pendente finaliza ativação' ||
                        options.value == 'Aguardando teste de comunicação' ||
                        options.value == 'Pendente inventário'
                    ) {
                        return `<span class="badge badge-warning">${options.value}</span>`;

                    } else if (options.value == 'Inventário recebido') {
                        return `<span class="badge badge-info">${options.value}</span>`;

                    } else if (options.value == 'Rejeitada' || options.value == 'Ativação cancelada') {
                        return `<span class="badge badge-danger">${options.value}</span>`;

                    } else {
                        return `<span class="badge badge-secondary">${options.value}</span>`;
                    }
                }
            },
            {
                headerName: 'Código',
                field: 'codigo',
                width: 120,
                suppressSizeToFit: true,
                hide: true
            },
            {
                headerName: 'Chave de Comunicação',
                field: 'chaveComuni',
                width: 180,
                suppressSizeToFit: true,
                hide: true
            },
            {
                headerName: 'Serial',
                field: 'serial',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Origem',
                field: 'origem',
                suppressSizeToFit: true
            },
            {
                headerName: 'NA',
                field: 'naCRM',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cod. Cliente',
                field: 'codCliente',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Loja',
                field: 'loja',
                width: 140,
                suppressSizeToFit: true,
                hide: true
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Ocorrência',
                field: 'ocorCRM',
                width: 160,
                suppressSizeToFit: true,
                hide: true
            },
            {
                headerName: 'Data Registro',
                field: 'dataRegistro',
                width: 130,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value) {
                        return formatDate(options.value);
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: 'Hora Registro',
                field: 'horaRegistro',
                width: 130,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;

                    let tableId = "table";
                    let dropdownId = "dropdown-menu" + data.codigo;
                    let buttonId = "dropdownMenuButton_" + data.codigo;
                    if (data.codigo) {
                        return `
                            <div class="dropdown" style="position: relative;">
                                <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:abrirAtivacao('${data.codigo}')" style="cursor: pointer; color: black;">Visualizar</a>
                                    </div>
                                </div>
                            </div>`;
                    } else {
                        return '';
                    }
                },
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true
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
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        cacheBlockSize: 100,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            if (data) {
                if ('codigo' in data) {
                    abrirAtivacao(data.codigo)
                }
            }
        }
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '530px');

    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions)
}

var AgGridProcessoAtivacao;
function atualizarAgGridProcessoAtivacao(dados) {
    stopAgGRIDProcessoAtivacao();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Status',
                field: 'status',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Descrição',
                field: 'descProces',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data',
                field: 'data',
                width: 110,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value) {
                        return formatDate(options.value);
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: 'Hora',
                field: 'hora',
                width: 100,
                suppressSizeToFit: true
            },

            {
                headerName: 'Evento',
                field: 'evento',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Observação',
                field: 'obsProcesso',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Mensagem Enviada',
                field: 'msgEnviada',
                width: 250,
                suppressSizeToFit: true
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true
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
        pagination: true,
        paginationPageSize: 10,
        cacheBlockSize: 100,
        localeText: localeText,
        domLayout: 'normal',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
    };

    var gridDiv = document.querySelector('#tableProcessoAtivacao');
    gridDiv.style.setProperty('height', '500px');

    AgGridProcessoAtivacao = new agGrid.Grid(gridDiv, gridOptions);

    gridOptions.api.setRowData(dados);

}

var AgGridItensInventario;
function atualizarAgGridItensInventario(dados) {
    stopAgGRIDItensInventario();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Inventariado',
                field: 'inventariado',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Descrição do Item',
                field: 'descItem',
                flex: 1,
                minWidth: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor',
                field: 'vlrInventar',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Descrição do Valor',
                field: 'descValor',
                flex: 1,
                minWidth: 200,
                suppressSizeToFit: true
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true
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
        pagination: true,
        paginationPageSize: 6,
        cacheBlockSize: 100,
        localeText: localeText,
        domLayout: 'normal',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
    };

    var gridDiv = document.querySelector('#tableItensInventarioAtivacao');
    gridDiv.style.setProperty('height', '370px');

    AgGridItensInventario = new agGrid.Grid(gridDiv, gridOptions);

    gridOptions.api.setRowData(dados);

}

function stopIntervals(){
    intervalReferences.forEach(interval => clearInterval(interval));
    intervalReferences = [];
}


// Carregamento
function stopAgGRID() {
    var gridDiv = document.querySelector('#table');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapper');
    if (wrapper) {
        wrapper.innerHTML = '<div id="table" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDProcessoAtivacao() {
    var gridDiv = document.querySelector('#tableProcessoAtivacao');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperProcessoAtivacao');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableProcessoAtivacao" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDItensInventario() {
    var gridDiv = document.querySelector('#tableItensInventarioAtivacao');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperItensInventarioAtivacao');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensInventarioAtivacao" class="ag-theme-alpine my-grid"></div>';
    }
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...').attr('disabled', true);
    $('#BtnLimparFiltro').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
    $('#BtnLimparFiltro').attr('disabled', false);
}

function showLoadingLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-spinner fa-spin"></i> Limpando...').attr('disabled', true);
    $('#BtnPesquisar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
    $('#BtnPesquisar').attr('disabled', false);
}

function showLoadingSalvarButton() {
    $('#btnSalvar').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButton() {
    $('#btnSalvar').html('Salvar').attr('disabled', false);
}

// EXPORTAÇÃO DA TEBELA
function exportarArquivo(tipo, gridOptions, titulo) {
    var colunas = ["status", "codigo", "chaveComuni", "serial", "origem", "naCRM", "codCliente", "loja", "cliente", "ocorCRM", "dataRegistro", "horaRegistro"];

    switch (tipo) {
        case "csv":
            tipoArquivo = 'csv';
            $.ajax({
                cache: false,
                url: Router + '/baixarRelatorio',
                type: 'POST',
                data: getExportForm(tipoArquivo),
                beforeSend: function () {
                    ShowLoadingScreen();
                },
                xhrFields: {
                    responseType: 'blob' // Define o tipo de resposta como blob (para download de arquivo)
                },
                success: function(response, textStatus, xhr) {
                    if (xhr.status === 200) {
                        if (response && response.size > 0) {
                            if (!xhr.getResponseHeader('Content-Type').includes('application/json')) {
                                var blob = new Blob([response], { type: xhr.getResponseHeader('Content-Type') });
                                var blobUrl = URL.createObjectURL(blob);
                                var link = document.createElement('a');
                                link.href = blobUrl;
                                link.download = titulo + '.' + tipoArquivo;
                                link.click();
                            } else {
                                showAlert('error', 'Erro ao baixar o relatório');
                            }
                        } else {
                            showAlert('warning', 'Não há dados para serem exportados!');
                        }
                    } else {
                        showAlert('error', 'Erro ao baixar o relatório:', xhr.status);
                    }
                    HideLoadingScreen();
                },
                error: function(xhr, textStatus, errorThrown) {
                    showAlert('error', 'Erro ao baixar o relatório:', textStatus);
                    HideLoadingScreen();
                }
            });
            break;
        case "excel":
            tipoArquivo = 'xlsx';
            $.ajax({
                cache: false,
                url: Router + '/baixarRelatorio',
                type: 'POST',
                data: getExportForm(tipoArquivo),
                beforeSend: function () {
                    ShowLoadingScreen();
                },
                xhrFields: {
                    responseType: 'blob' // Define o tipo de resposta como blob (para download de arquivo)
                },
                success: function(response, textStatus, xhr) {
                    if (xhr.status === 200) {
                        if (response && response.size > 0) {
                            if (!xhr.getResponseHeader('Content-Type').includes('application/json')) {
                                var blob = new Blob([response], { type: xhr.getResponseHeader('Content-Type') });
                                var blobUrl = URL.createObjectURL(blob);
                                var link = document.createElement('a');
                                link.href = blobUrl;
                                link.download = titulo + '.' + tipoArquivo;
                                link.click();
                            } else {
                                showAlert('error', 'Erro ao baixar o relatório');
                            }
                        } else {
                            showAlert('warning', 'Não há dados para serem exportados!');
                        }
                    } else {
                        showAlert('error', 'Erro ao baixar o relatório:', xhr.status);
                    }
                    HideLoadingScreen();
                },
                error: function(xhr, textStatus, errorThrown) {
                    showAlert('error', 'Erro ao baixar o relatório:', textStatus);
                    HideLoadingScreen();
                }
            });
            break;
        case "pdf":
            tipoArquivo = 'pdf';
            $.ajax({
                cache: false,
                url: Router + '/baixarRelatorio',
                type: 'POST',
                data: getExportForm(tipoArquivo),
                beforeSend: function () {
                    ShowLoadingScreen();
                },
                xhrFields: {
                    responseType: 'blob' // Define o tipo de resposta como blob (para download de arquivo)
                },
                success: function(response, textStatus, xhr) {
                    if (xhr.status === 200) {
                        if (response && response.size > 0) {
                            if (!xhr.getResponseHeader('Content-Type').includes('application/json')) {
                                var blob = new Blob([response], { type: xhr.getResponseHeader('Content-Type') });
                                var blobUrl = URL.createObjectURL(blob);
                                var link = document.createElement('a');
                                link.href = blobUrl;
                                link.download = titulo + '.' + tipoArquivo;
                                link.click();
                            } else {
                                showAlert('error', 'Erro ao baixar o relatório');
                            }
                        } else {
                            showAlert('warning', 'Não há dados para serem exportados!');
                        }
                    } else {
                        showAlert('error', 'Erro ao baixar o relatório:', xhr.status);
                    }
                    HideLoadingScreen();
                },
                error: function(xhr, textStatus, errorThrown) {
                    showAlert('error', 'Erro ao baixar o relatório:', textStatus);
                    HideLoadingScreen();
                }
            });
            break;
    }
}

function getExportForm(tipo) {
    return {
        dataInicio: dataInicioBuscaRel,
        dataFim: dataFimBuscaRel,
        serial: serialBuscaRel,
        na: naBuscaRel,
        tipoArquivo: tipo
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
            exportarArquivo(opcao, gridOptions, "Relatório do Painel de Ativação");
        });
        formularioExportacoes.appendChild(div);
    });
}