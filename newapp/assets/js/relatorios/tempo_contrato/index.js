var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    carregarSelects();

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();
        let options = {
            baseCalculo: $('#baseCalculo').val(),
            dataInicial: $('#dataInicial').val(),
            dataFinal: $('#dataFinal').val(),
            cliente: $('#clienteBusca').val(),
            status: $('#statusBusca').val()
        };

        if (options.baseCalculo && options.dataInicial && options.dataFinal && options.cliente && options.status) {
            var dataInicial = new Date($("#dataInicial").val());
            var dataFinal = new Date($("#dataFinal").val());

            if (dataInicial > dataFinal) {
                resetPesquisarButton();
                exibirAlerta("warning", "Falha!", "Data Final não pode ser menor que a Data Inicial!");
            } else {
                getDados(options);
            }
        } else {
            resetPesquisarButton();
            exibirAlerta("warning", "Falha!", "Preencha todos os campos obrigatórios para realizar a busca!");
        }
    });

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = "table";
        abrirDropdown(dropdownId, buttonId, tableId);
    });

    $('#BtnLimparFiltro').click(function() {
        showLoadingLimparButton();
        limparFiltro();
        getDados();
    })

    atualizarAgGrid();

});

function limparFiltro() {
    $('#baseCalculo').val('').trigger('change');
    $('#clienteBusca').val('').trigger('change');
    $('#statusBusca').val('').trigger('change');
    $('#dataInicial').val('');
    $('#dataFinal').val('');
}

function carregarSelects() {
    $('#clienteBusca').select2({
        allowClear: true,
        placeholder: lang.selecione_cliente,
        language: "pt-BR",
        ajax: {
            url: Router + '/listAjaxSelectClient',
            dataType: 'json'
        }
    });
    
    $("#clienteBusca").val('').trigger('change');

    $('#baseCalculo').select2({
        allowClear: true,
        language: "pt-BR",
        placeholder: lang.selecione_base_calculo,
        minimumResultsForSearch: -1
    })

    $("#baseCalculo").val('').trigger('change');

    $('#statusBusca').select2({
        allowClear: true,
        language: "pt-BR",
        placeholder: "Selecione o status",
        minimumResultsForSearch: -1
    })

    $("#statusBusca").val('').trigger('change');
}

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);
    var posDropdown = dropdown.height() + 10;
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;
    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)){
            dropdown.css('top', `-${10}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(posDropdown - 60) - (diferenca) }px`);
        }
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
      timerProgressBar: true,
    });
}

function formatDateTime(date){
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0]+" "+dates[1];
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

function getDados(searchOptions) {
    if (searchOptions) {
        $.ajax({
            url: Router + '/buscarDadosTempoContrato',
            type: 'POST',
            data: searchOptions,
            dataType: 'json',
            beforeSend: function() {
                AgGrid.gridOptions.api.setRowData([]);
                AgGrid.gridOptions.api.showLoadingOverlay();
            },
            success: function(data) {
                if (data.status === 200) {
                    var dados = data.resultado;

                    for (let i = 0; i < dados.length; i++) {
                        for (let chave in dados[i]) {
                            if (dados[i][chave] === null) {
                                dados[i][chave] = '';
                            }

                            if (
                                chave === 'placaVinculacao' ||
                                chave === 'placaDesvinculacao' ||
                                chave === 'serialVinculacao' ||
                                chave === 'serialDesvinculacao'
                            ) {
                                if (Array.isArray(dados[i][chave])) {
                                    dados[i][chave] = dados[i][chave].filter(function (el) {
                                        return el != null;
                                    });
                                }
                            }
                        }

                    }
                    AgGrid.gridOptions.api.setRowData(dados);
                } else if (data.status === 400 || data.status === 404) {
                    if (data.resultado && 'mensagem' in data.resultado) {
                        exibirAlerta('warning', 'Falha!', data.resultado.mensagem);
                    } else {
                        exibirAlerta('error', 'Erro!', 'Não foi possível fazer a listagem.');
                    }
                    AgGrid.gridOptions.api.setRowData([]);
                } else {
                    exibirAlerta('error', 'Erro!', 'Não foi possível fazer a listagem.');
                    AgGrid.gridOptions.api.setRowData([]);
                }
                
            },
            error: function() {
                exibirAlerta('error', 'Erro!', 'Não foi possível fazer a listagem.');
                AgGrid.gridOptions.api.setRowData([]);
            },
            complete: function() {
                resetPesquisarButton();
            }
        });
    } else {
        AgGrid.gridOptions.api.setRowData([]);
        resetLimparButton();
    }
}

// AgGrid
var AgGrid;
function atualizarAgGrid(dados) {
    stopAgGRID();
    
    var gridOptions = {
        columnDefs: [
            {
                headerName: 'ID Cliente',
                field: 'idCliente',
                suppressSizeToFit: true,
                width: 100,
                hide: true
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'ID Equipamento',
                field: 'idEquipamento',
                suppressSizeToFit: true,
                width: 140,
                hide: true
            },
            {
                headerName: 'Placa',
                field: 'placaContrato',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'ID Contrato',
                field: 'idContrato',
                suppressSizeToFit: true,
                width: 110,
            },
            {
                headerName: 'ID Veículo',
                field: 'idVeiculo',
                suppressSizeToFit: true,
                width: 100,
                hide: true
            },
            {
                headerName: 'Prefixo Veículo',
                field: 'prefixoVeiculo',
                width: 150,
                suppressSizeToFit: true,
                hide: true
            },
            {
                headerName: 'Secretaria',
                field: 'secretaria',
                width: 150,
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'Centro Veículo',
                field: 'centroVeiculo',
                width: 150,
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'OS',
                field: 'os',
                width: 120,
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'Lote',
                field: 'lote',
                width: 100,
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'Tipo de Frota',
                field: 'tipoFrota',
                width: 120,
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo do Veículo',
                field: 'modeloVeiculo',
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo do Equipamento',
                field: 'modeloEquipamento',
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'Fabricante',
                field: 'fabricante',
                hide: true,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data Vinculação',
                field: 'dataVinculacao',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    if (params.value) {
                        return formatDateTime(params.value);
                    } else {
                        return params.value;
                    }
                }
            },
            {
                headerName: 'Data Desvinculação',
                field: 'dataDesvinculacao',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    if (params.value) {
                        return formatDateTime(params.value);
                    } else {
                        return params.value;
                    }
                }
            },
            {
                headerName: 'Placas Vinculação',
                field: 'placaVinculacao',
                suppressSizeToFit: true
            },
            {
                headerName: 'Placas Desvinculação',
                field: 'placaDesvinculacao',
                suppressSizeToFit: true
            },
            {
                headerName: 'Seriais Vinculação',
                field: 'serialVinculacao',
                suppressSizeToFit: true
            },
            {
                headerName: 'Seriais Desvinculação',
                field: 'serialDesvinculacao',
                suppressSizeToFit: true
            },
            {
                headerName: 'Contagem de Dias',
                field: 'contagemDias',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Diário',
                field: 'valorDiario',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Instalação',
                field: 'valorInstalacao',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Mensal',
                field: 'valorMensal',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Período',
                field: 'valorPeriodo',
                width: 150,
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
            defaultToolPanel: 'columns',
        },
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        localeText: localeText,
        domLayout: 'normal',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>'
    };
    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '530px');

    AgGrid = new agGrid.Grid(gridDiv, gridOptions);
    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    gridOptions.api.setRowData(dados);
    preencherExportacoes(gridOptions)
}


function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + '/media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + '/media/img/new_icons/pdf.png';

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
        div.classList.add('opcoes_exportacao');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivo(opcao, gridOptions, 'Relatório por Tempo de Contrato');
        });

        formularioExportacoes.appendChild(div);
    });
}

function exportarArquivo(tipo, gridOptions, nomeArquivo) {
    let columnKeys = gridOptions.columnApi;
    switch (tipo) {
        case "csv":
            fileName = nomeArquivo + ".csv";
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: columnKeys,
            });
            break;
        case "excel":
            fileName = nomeArquivo + ".xlsx";
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: columnKeys,
            });
            break;
        case "pdf":
            let definicoesDocumento = getDocDefinition(
                printParams("A4"),
                gridOptions.api,
                columnKeys,
                '',
                nomeArquivo
            );
            pdfMake
                .createPdf(definicoesDocumento)
                .download(nomeArquivo + '.pdf');
            break;
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

$(document).ready(function() {
    var dropdown = $('#opcoes_exportacao');

    $('#dropdownMenuButton').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButton') {
            dropdown.hide();
        }
    });
});

// Carregamento

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
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
    $('#BtnPesquisar').attr('disabled', false);
}

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