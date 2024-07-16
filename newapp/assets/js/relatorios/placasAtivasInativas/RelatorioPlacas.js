var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGrid();

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

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#BtnLimparFiltro').on('click', function () {
        $('#clienteBusca').val(null).trigger('change');
        atualizarAgGrid();
    });

    $('#formBusca').on('submit', function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            clienteBusca: $("#clienteBusca").val()
        };

        clienteBusca = $("#clienteBusca").val();

        if (!clienteBusca) {
            resetPesquisarButton();
            showAlert('warning', "Preencha o campo de busca!")
        } else {
            atualizarAgGrid(searchOptions);
        }


    });
})

var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();
    showLoadingLimparButton();
    showLoadingPesquisarButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/relPlacas';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idCliente: options ? options.clienteBusca : '',
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
                            showAlert('error', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
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
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                chartDataType: 'category',
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Nº de Placas Ativas',
                field: 'qtdAtivos',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Nº de Placas Inativas',
                field: 'qtdInativos',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-placas').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-placas').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-placas').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tablePlacas');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoes(gridOptions);
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#tablePlacas');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperPlacas');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tablePlacas" class="ag-theme-alpine my-grid-placas"></div>';
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

// Geral

function exportarArquivo(tipo, gridOptions, menu = '', titulo) {
    let colunas = ['id', 'cliente', 'qtdAtivos', 'qtdInativos'];

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

// OMNISAFE
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
            exportarArquivo(opcao, gridOptions, 'RelatorioPlacasAtivasInativas', 'Relatório de Placas Ativas e Inativas');
        });

        formularioExportacoes.appendChild(div);
    });
}
