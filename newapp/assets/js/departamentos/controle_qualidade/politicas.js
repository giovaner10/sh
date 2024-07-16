
var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGrid([])
    getDados();

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#BtnLimparFiltro').on('click', function () {
        var searchInput = document.querySelector('#busca');
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
    })

});

var AgGridCemUltSolic;

function atualizarAgGrid(dados) {
    stopAgGRID();

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Código',
                field: '0',
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Descrição',
                field: '1',
                chartDataType: 'category',
                flex: 1,
                minWidth: 300,
                suppressSizeToFit: true
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
                    let tableId = "tablePolitica";
                    let dropdownId = "dropdown-menu-ultima-configuracao-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButtonMenu_" + data.id + varAleatorioIdBotao;


                    let actionsButtonAbertura = `
                            <div class="dropdown" style="position: relative;">
                                <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        `;

                    let buttons = [
                        {
                            label: 'Baixar',
                            html: `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="${uploadUrl}/${data[2].arquivo}" download data-toggle="tooltip" title="<?= lang('baixar') ?>">Baixar</a>
                                </div>
                            `
                        },
                        {
                            label: 'Visualizar',
                            html: `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="${uploadUrl}/${data[2].arquivo}" target="_blank" data-toggle="tooltip" title="<?= lang('visualizar') ?>">Visualizar</a>
                                </div>
                            `
                        }
                    ];

                    if (temPermissao) {
                        buttons.push(
                            {
                                label: 'Editar',
                                html: `
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a onclick="formularioEditarPolitica(${data[0]})" data-toggle="tooltip" id="buttonEditarPolitica_${data[0]}">Editar</a>
                                    </div>
                                `
                            },
                            {
                                label: 'Remover',
                                html: `
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a onclick="modalExcluirPolitica(${data[0]})" data-toggle="tooltip">Remover</a>
                                    </div>
                                `
                            }
                        );
                    }

                    buttons.sort((a, b) => a.label.localeCompare(b.label));

                    let buttonsHtml = buttons.map(button => button.html).join('');

                    let actionsButtonFechamento = `
                            </div>
                        </div>
                    `;

                    return actionsButtonAbertura + buttonsHtml + actionsButtonFechamento;
                }
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
            toolPanels: [{
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
            },],
            defaultToolPanel: false,
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-politica').val()),
        localeText: localeText
    };


    var gridDiv = document.querySelector('#tablePolitica');

    gridDiv.style.setProperty('height', '519px');
    AgGridCemUltSolic = new agGrid.Grid(gridDiv, gridOptions);

    AgGridCemUltSolic.gridOptions.api.setRowData(dados);

    $('#select-quantidade-por-pagina-politica').change(function () {
        var selectedValue = $(this).val();
        AgGridCemUltSolic.gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    document.querySelector('#busca').addEventListener('input', function () {
        var searchInput = document.querySelector('#busca');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    preencherExportacoes(gridOptions);
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#tablePolitica');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperPolitica');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tablePolitica" class="ag-theme-alpine my-grid-politica"></div>';
    }
}

function getDados(options) {
    AgGridCemUltSolic.gridOptions.api.showLoadingOverlay();

    $.ajax({
        url: rota + '/' + 8,
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            if (data == '') {
                AgGridCemUltSolic.gridOptions.api.showNoRowsOverlay();
                showAlert("error", "Dados não encontrados para os parâmetros informados.");
                return;
            }

            let dados = data['data']
            for (let i = 0; i < dados.length; i++) {
                for (let chave in dados[i]) {
                    if (dados[i][chave] === null) {
                        dados[i][chave] = '';
                    }
                }
            }
            atualizarAgGrid(dados);
            AgGridCemUltSolic.gridOptions.api.hideOverlay();
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
            AgGridCemUltSolic.gridOptions.api.showNoRowsOverlay();
        },
    });
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

function exportarArquivo(tipo, gridOptions, menu = 'Politicas', titulo) {
    let colunas = ['0', '1'];

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
            let definicoesDocumento = getCustomDocDefinitionColumn0(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                titulo,
                colunas
            );
            pdfMake.createPdf(definicoesDocumento).download(menu + '.pdf');
            break;

    }
}

$(document).ready(function () {

    function setupDropdown(buttonId, dropdownId) {
        var dropdown = $(dropdownId);

        $(buttonId).click(function (event) {
            event.stopPropagation();

            if (dropdown.is(':visible')) {
                dropdown.hide();
            } else {
                $(".dropdown-menu").hide();
                dropdown.show();
            }
        });

        $(document).click(function (event) {
            if (!dropdown.is(event.target) && event.target.id !== buttonId.substring(1) && dropdown.has(event.target).length === 0) {
                dropdown.hide();
            }
        });
    }

    setupDropdown('#dropdownMenuButton', '#opcoes_exportacao')
})


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
            exportarArquivo(opcao, gridOptions, 'RelatorioPoliticas', 'Relatório de Políticas');
        });

        formularioExportacoes.appendChild(div);
    });
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}