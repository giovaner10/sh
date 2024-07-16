var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGrid();

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });


    $("#clienteBusca").select2({
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
            url: Router + '/clientesSelect2',
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) {
                var query = {
                    itemInicio: 1,
                    itemFim: 100
                };
                if (params.term && params.term.length >= 4) {
                    query.searchTerm = params.term;
                }
                return query;
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
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
            }
        }
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();
        showLoadingLimparButton();

        var searchOptions = {
            dataInicial: $("#dataInicialBusca").val(),
            dataFinal: $("#dataFinalBusca").val(),
            cliente: $("#clienteBusca").val()
        };

        dataInicial = $("#dataInicialBusca").val();
        dataFinal = $("#dataFinalBusca").val();
        cliente = $("#clienteBusca").val();

        if (!dataInicial || !dataFinal || !cliente) {
            if (!cliente) {
                showAlert('warning', "Preencha o Cliente!");
                resetPesquisarButton();
                resetLimparButton();
                return;
            }
            if (!dataInicial) {
                showAlert('warning', "Preencha a Data Inicial!");
                resetPesquisarButton();
                resetLimparButton();
                return;
            }
            if (!dataFinal) {
                showAlert('warning', "Preencha a Data Final!");
                resetPesquisarButton();
                resetLimparButton();
                return;
            }

        } else {
            if (validarDatas(dataInicial, dataFinal)) {
                atualizarAgGrid(searchOptions);
            } else {
                resetPesquisarButton();
                resetLimparButton();
            }
        }
    });

    $('#BtnLimparFiltro').on('click', function () {
        $('#formBusca').trigger('reset');
        $("#clienteBusca").val(null).trigger('change')
        atualizarAgGrid();
    })

})

function validarDatas(dataInicialStr, dataFinalStr) {
    const dataInicial = new Date(dataInicialStr);
    const dataFinal = new Date(dataFinalStr);
    const dataAtual = new Date();

    dataAtual.setHours(0, 0, 0, 0);

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

var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();
    showLoadingLimparButton();
    showLoadingPesquisarButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/listaVeiculosDiaAtualizacao';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        dataInicial: options ? options.dataInicial : '',
                        dataFinal: options ? options.dataFinal : '',
                        clienteId: options ? options.cliente : ''
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        if (data && data.status == 200) {
                            var dados = data.results;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                }
                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.results.length,
                            });
                        } else if (data.status == 404) {
                            showAlert('warning', "Dados não encontrados para os parâmetros informados.");
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert('warning', 'Dados não encontrados para os parâmetros informados.');
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

                $("#loadingMessage").hide();
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Contrato',
                field: 'idContrato',
                chartDataType: 'category',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa',
                field: 'placa',
                chartDataType: 'category',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Qtd. de dias Atualizado',
                field: 'contagemDias',
                chartDataType: 'category',
                width: 300,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Qtd. de dias Desatualizado',
                field: 'contagemDiasSemAtt',
                chartDataType: 'category',
                width: 300,
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor',
                field: 'valorPeriodo',
                chartDataType: 'category',
                flex: 1,
                suppressSizeToFit: true,
            },
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-veiculos').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-veiculos').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-veiculos').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableVeiculos');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);

    if (options) {
        let datasource = getServerSideDados();
        gridOptions.api.setServerSideDatasource(datasource);

    } else {
        gridOptions.api.setServerSideDatasource({
            getRows: (params) => {
                params.failCallback();
                params.success({
                    rowData: [],
                    rowCount: 0
                });
                gridOptions.api.showNoRowsOverlay();
                resetLimparButton();
                resetPesquisarButton();
            }
        });
    }

    preencherExportacoes(gridOptions);
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#tableVeiculos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperVeiculos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableVeiculos" class="ag-theme-alpine my-grid-veiculos"></div>';
    }
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
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
}

function showLoadingLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

//EXPORTAÇÕES

function exportarArquivo(tipo, gridOptions, menu = 'RelatorioAtualizacaoVeiculos', titulo) {
    let colunas = ['idContrato', 'placa', 'contagemDias', 'contagemDiasSemAtt', 'valorPeriodo'];

    switch (tipo) {
        case 'csv':
            fileName = menu + '.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: colunas
            });
            break;
        case 'excel':
            fileName = menu + '.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: colunas
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                titulo
            );
            pdfMake.createPdf(definicoesDocumento).download(menu + '.pdf');
            break;

    }
}

function printParams(pageSize) {
    return {
        PDF_HEADER_COLOR: "#ffffff",
        PDF_INNER_BORDER_COLOR: "#dde2eb",
        PDF_OUTER_BORDER_COLOR: "#babfc7",
        PDF_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
        PDF_HEADER_LOGO: 'omnilink',
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
    }
}

// Dados Gerenciamento 
$(document).ready(function () {
    var dropdown = $('#opcoes_exportacao');

    $('#dropdownMenuButton').click(function () {
        dropdown.toggle();
    });

    $(document).click(function (event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButton') {
            dropdown.hide();
        }
    });
});

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

    formularioExportacoes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcao_exportacao');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function () {
            div.style.backgroundColor = '#f0f0f0';
        });

        div.addEventListener('mouseout', function () {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function (event) {
            event.preventDefault();
            exportarArquivo(opcao, gridOptions, 'RelatorioAtualizacaoVeiculos', 'Relatório Atualização de Veículos');
        });

        formularioExportacoes.appendChild(div);
    });
}