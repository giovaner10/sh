var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGridChips();

    $('#ano').select2({
        allowClear: true,
        placeholder: "Selecione o ano"
    });

    $('#operadora').select2({
        allowClear: true,
        placeholder: "Selecione a operadora"
    });

    $('#empresa').select2({
        allowClear: true,
        placeholder: "Selecione uma opção"
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();
        showLoadingLimparButton();

        let searchOptions = {
            ano: $('#ano').val(),
            empresa: $('#empresa').val(),
            operadora: $('#operadora').val()
        }

        var ano = $('#ano').val();
        var operadora = $('#operadora').val();
        var empresa = $('#empresa').val();

        if (!ano || !operadora || !empresa) {
            resetLimparButton();
            resetPesquisarButton();
            showAlert('warning', 'Selecione todos os campos de busca.');
        } else {
            atualizarAgGridChips(searchOptions);
        }
    })

    $('#BtnLimparFiltro').on('click', function () {
        $('#ano').val(null).trigger('change');
        $('#operadora').val(null).trigger('change');
        $('#empresa').val(null).trigger('change');
        atualizarAgGridChips();
    })
})

var AgGridChips;
function atualizarAgGridChips(options) {
    stopAgGRIDChips();
    showLoadingPesquisarButton();
    showLoadingLimparButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/listarChipsRelatorio';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        ano: options ? options.ano : '',
                        empresa: options ? options.empresa : '',
                        operadora: options ? options.operadora : ''
                    },
                    dataType: 'json',
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
                            showAlert('error', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        }
                        resetLimparButton();
                        resetPesquisarButton();
                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        gridOptions.api.showNoRowsOverlay();
                        resetLimparButton();
                        resetPesquisarButton();
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
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'CCID',
                field: 'ccid',
                chartDataType: 'category',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Linha',
                field: 'linha',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Operadora',
                field: 'operadora',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Última Atualização',
                field: 'ultimaAtualizacao',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Vinc. Auto',
                field: 'ccidAuto',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Prestadora',
                field: 'prestadora',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
                width: 150,
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
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-chips').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-chips').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-chips').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableChips');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);

    if (options) {
        let datasource = getServerSideDados(options);
        gridOptions.api.setServerSideDatasource(datasource);
    } else {
        let emptyDatasource = {
            getRows: (params) => {
                params.success({ rowData: [], rowCount: 0 });
                gridOptions.api.showNoRowsOverlay();
                resetLimparButton();
                resetPesquisarButton();
            }
        };
        gridOptions.api.setServerSideDatasource(emptyDatasource);
    }

    preencherExportacoes(gridOptions);
}

function stopAgGRIDChips() {
    var gridDiv = document.querySelector('#tableChips');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperChips');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableChips" class="ag-theme-alpine my-grid-chips"></div>';
    }
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

function showLoadingLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
}