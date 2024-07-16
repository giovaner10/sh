const templateSemDados = `<span class="ag-overlay-loading-center" style="border-radius: 12px; border:none; padding: 10px;">Nenhum registro para exibir.</span>`

$(document).ready(function () {
    $('#statusProcessamento').select2({
        allowClear: true,
        language: "pt-BR",
        minimumResultsForSearch: -1,
        placeholder: 'Selecione um status'
    })
});

// AG-GRID
var AgGridProcessamento;
function atualizarAgGridProcessamento(options) {
    stopAgGRIDProcessamento();
    function getServerSideDadosProcessamento() {
        return {
            getRows: (params) => {
        
            var route = Router + '/buscarProcessamentoServerSide';
        
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    startRow: params.request.startRow,
                    endRow: params.request.endRow,
                    statusProcessamento: options ? options.statusProcessamento : '',
                    dataInicial: options ? options.dataInicial : '',
                    dataFinal: options ? options.dataFinal : ''
                },
                dataType: 'json',
                async: true,
                success: function (data) {
                if (data && data.success) {
                    var dados = data.rows;
                    for (let i = 0; i < dados.length; i++) {
                        for (let chave in dados[i]) {
                            // Verifica se o valor é null e substitui por uma string vazia
                            if (dados[i][chave] === null) {
                                dados[i][chave] = '';
                            }
                            if (chave === 'tipo_list') {
                                if (dados[i][chave] == '0') {
                                    dados[i][chave] = 'Hot List';
                                } else if (dados[i][chave] == '1') {
                                    dados[i][chave] = 'Cold List';
                                } else {
                                    dados[i][chave] = 'Indefinido';
                                }
                            }
                            if (chave === 'acao') {
                                if (dados[i][chave] == '0') {
                                    dados[i][chave] = 'Excluir';
                                } else if (dados[i][chave] == '1') {
                                    dados[i][chave] = 'Associar';
                                } else if (dados[i][chave] == '2' || dados[i][chave] == '3') {
                                    dados[i][chave] = 'Desassociar';
                                } else {
                                    dados[i][chave] = '';
                                }
                            }
                            if (chave === 'status') {
                                if (dados[i][chave] == '0') {
                                    dados[i][chave] = 'Recebido';
                                } else if (dados[i][chave] == '1') {
                                    dados[i][chave] = 'Processado com Sucesso';
                                } else if (dados[i][chave] == '2') {
                                    dados[i][chave] = 'Falha';
                                } else if (dados[i][chave] == '3') {
                                    dados[i][chave] = 'Processado com Falhas';
                                } else {
                                    dados[i][chave] = '';
                                }
                            }
                        }
                    }
                    params.success({
                        rowData: dados,
                        rowCount: data.lastRow,
                    });
                } else if (data && data.status && data.status == '404'){
                    showAlert('warning', 'Nenhum registro encontrado para os parâmetros informados.')
                    AgGridProcessamento.gridOptions.api.showNoRowsOverlay();
                    params.success({
                        rowData: [],
                        rowCount: 0,
                    });
                } else if (data && data.message){
                    showAlert('warning', data.message);
                    AgGridProcessamento.gridOptions.api.showNoRowsOverlay();
                    params.failCallback();
                    params.success({
                        rowData: [],
                        rowCount: 0,
                    });
                } else {
                    showAlert('error', 'Erro na solicitação ao servidor');
                    params.failCallback();
                    AgGridProcessamento.gridOptions.api.showNoRowsOverlay();
                    params.success({
                        rowData: [],
                        rowCount: 0,
                    });
                }
                if (options) {
                    resetPesquisarButton();
                } else {
                    resetLimparButton();
                }
                },
                error: function (error) {
                    showAlert('error', 'Erro na solicitação ao servidor');
                    AgGridProcessamento.gridOptions.api.showNoRowsOverlay();
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
                },
            });
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID cliente',
                field: 'id_cliente',
                suppressSizeToFit: true,
                width: 130,
            },
            {
                headerName: 'Tipo Match',
                field: 'tipo_list',
                chartDataType: 'series',
                width: 110,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Hot List') {
                        return `<span class="badge badge-danger">Hot List</span>`;
                    } else if (options.value == 'Cold List') {
                        return `<span class="badge badge-info">Cold List</span>`;
                    } else if (options.value == 'Indefinido') {
                        return `<span class="badge badge-default">Indefinido</span>`;
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'ID Alerta',
                field: 'id_cadastro_alerta',
                width: 130,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ação',
                field: 'acao',
                width: 120,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Excluir') {
                        return `<span class="badge badge-danger status-dark">Excluir</span>`;
                    } else if (options.value == 'Associar') {
                        return `<span class="badge status-primary">Associar</span>`;
                    } else if (options.value == 'Desassociar') {
                        return `<span class="badge badge-warning status-secondary">Desassociar</span>`;
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Status',
                field: 'status',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Recebido') {
                        return `<span class="badge badge-info">Recebido</span>`;
                    } else if (options.value == 'Processado com Sucesso') {
                        return `<span class="badge badge-success">Processado com Sucesso</span>`;
                    } else if (options.value == 'Falha') {
                        return `<span class="badge badge-danger">Falha</span>`;
                    } else if (options.value == 'Processado com Falhas') {
                        return `<span class="badge badge-warning">Processado com Falhas</span>`;
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Data Cadastro',
                field: 'data_cadastro',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value) {
                        return formatDateTime(options.value);
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Data Execução',
                field: 'data_execucao',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value) {
                        return formatDateTime(options.value);
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Falhas',
                field: 'falhas',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Sucessos',
                field: 'sucessos',
                width: 120,
                suppressSizeToFit: true
            }
        ],
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
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: $('#select-quantidade-por-pagina-processamento').val(),
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-processamento').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-processamento').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableProcessamento');
    gridDiv.style.setProperty('height', '519px');

    AgGridProcessamento = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDadosProcessamento();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesProcessamento(gridOptions)
}

function stopAgGRIDProcessamento() {
    var gridDiv = document.querySelector('#tableProcessamento');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperProcessamento');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableProcessamento" class="ag-theme-alpine my-grid" style="height: 100% !important"></div>';
    }
}