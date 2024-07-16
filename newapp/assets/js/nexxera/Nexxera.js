var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGridHistorico();

    $("#tipoDocumento").select2({
		placeholder: "Selecione o tipo",
		allowClear: true,
		language: "pt-BR",
		width: "100%",
	});

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#BtnLimpar').on('click', function (e){
        $('#formBusca').trigger('reset')
        atualizarAgGridHistorico();
    })

    $('#formBusca').on('submit', function (e){
        e.preventDefault();
        showLoadingPesquisarButton();
        showLoadingLimparButton();

        var searchOptions = {
            dataInicio: formatarData($("#dataInicial").val()),
            dataFim: formatarData($("#dataFinal").val()),
            tipoDocumento: $("#tipoDocumento").val()
        };

        dataInicio = $("#dataInicial").val();
        dataFim = $("#dataFinal").val();
        tipoDocumento = $("#tipoDocumento").val();

        if (!tipoDocumento && (!dataInicio && !dataFim)) {
            resetPesquisarButton();
            resetLimparButton();
            showAlert('warning', 'Preencha algum dos campos de busca!')
        } else {
            if(validacaoFiltros()){
                atualizarAgGridHistorico(searchOptions);
                return;
            } else {
                resetPesquisarButton();
                resetLimparButton();
                return;
            }
        }
    }) 
    
})

var AgGridHistorico;
function atualizarAgGridHistorico(options) {
    stopAgGRIDHistorico();
    showLoadingLimparButton();
    showLoadingPesquisarButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/historicoEnvios';
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        tipoDocumento: options ? options.tipoDocumento : '',
                        dataInicio: options ? options.dataInicio : '',
                        dataFim: options ? options.dataFim : ''
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            showAlert('warning', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert('error', "Erro na solicitação ao servidor");
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        }
                        resetPesquisarButton();
                        resetLimparButton();

                    },
                    error: function (error) {
                        showAlert('error', "Erro na solicitação ao servidor");
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        gridOptions.api.showNoRowsOverlay();
                        resetPesquisarButton();
                        resetLimparButton();
                    },
                });

                $("#loadingMessage").hide();
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Tipo Documento',
                field: 'tipoDocumentoCopy1',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value == 0) {
                        return "Bradesco";
                    } else if (params.value == 1) {
                        return "Santander";
                    } else if (params.value == 2) {
                        return "Banco do Brasil";
                    } else if (params.value == 3) {
                        return "Itaú";
                    }
                }
            },
            {
                headerName: 'Banco Integrado',
                field: 'bancoIntegrado',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value == 0) {
                        return "Layout Pagamentos";
                    } else if (params.value == 1) {
                        return "Layout PIX";
                    } else if (params.value == 2) {
                        return "Layout Folha";
                    } else if (params.value == 3) {
                        return "Layout Impostos";
                    } else if (params.value == 4) {
                        return "Layout Retorno";
                    }
                }
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                chartDataType: 'category',
                flex: 1,
                minWidth: 300,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-historico').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayNoRowsTemplate: localeText.noRowsToShow
    };

    $('#select-quantidade-por-pagina-historico').change(function () {
        var selectedValue = $(this).val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableHistorico');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    

    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions);
}

function stopAgGRIDHistorico() {
    var gridDiv = document.querySelector('#tableHistorico');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperHistorico');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableHistorico" class="ag-theme-alpine my-grid-historico"></div>';
    }
}

function validacaoFiltros() {
    dataInicio = $("#dataInicial").val();
    dataFim = $("#dataFinal").val();

    if (dataInicio && !dataFim) {
        showAlert('warning', 'É necessário informar a Data Final');
        return false;
    }

    if (!dataInicio && dataFim) {
        showAlert('warning', 'É necessário informar a Data Inicial');
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

    if (diferencaEmDias > 31) {
        showAlert('warning', 'O período de busca não pode exceder 31 dias.');
        return false;
    }

    if (dataInicial > dataFinal) {
        showAlert('warning', 'A data inicial não pode ser maior que a data final.');
        return false;
    }

    if (dataFinal > dataAtual) {
        showAlert('warning', 'A data final não pode ser maior que a data atual.');
        return false;
    }

    if (dataInicial > dataAtual) {
        showAlert('warning', 'A data inicial não pode ser maior que a data atual.');
        return false;
    }

    return true;
}

function formatarData(data) {
    if(data == ''){
        return '';
    }
    let partesData = data.split('-');
    let dataFormatada = partesData[2] + '/' + partesData[1] + '/' + partesData[0];
    return dataFormatada;
}

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
        $('#conteudo').css('margin-left', '0px');
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function showLoadingLimparButton() {
    $('#BtnLimpar').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimpar').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

function showLoadingSalvarButtonClientes() {
    $('#btnSalvarCliente').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonClientes() {
    $('#btnSalvarCliente').html('Salvar').attr('disabled', false);
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}