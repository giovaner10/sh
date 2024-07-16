var localeText = AG_GRID_LOCALE_PT_BR;
var AgGridResumoRelatorioVeiculos;
var nomeCliente = '';
var dataInicialDoc = '';
var dataFinalDoc = '';
$(document).ready(function() {
    atualizarAgGridResumoRelatorioVeiculos();
    $('#clientes').select2({
        ajax: {
            url: RouterController + '/listAjaxSelectClient',
            dataType: 'json'
        },
        placeholder: "Digite o nome do cliente",
        allowClear: true,
        language: "pt-BR",
    });
    
});

function atualizarAgGridResumoRelatorioVeiculos(dados) {
    stopAgGRIDResumoRelatorioVeiculos();
    const gridOptions = {
        columnDefs: [
            {
                headerName: '#',
                field: 'count',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
            },
            {
                headerName: 'Placa',
                field: 'placa',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options){
                    if (options.data.status == '1') {
                        return `<span class="badge badge-success">${options.value}</span>`;
                    }else{
                        return `<span class="badge badge-warning">${options.value}</span>`;
                    }
                }
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Contrato',
                field: 'idContrato',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Quantidade de Dias',
                field: 'qtdDias',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let data = options.data;
                    let tabelaNome = "AgGridResumoRelatorioVeiculos";

                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdown(this, ${data.count}, 'resumo', ${tabelaNome})" class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonResumo_${data.count}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButton_${data.count}" id="dropdown-menu-resumo-${data.count}">
                            <a class="dropdown-item-acoes" id="btnAbrirModal" onclick="javascript:abrirModal('${data.placa}', '${data.dataInicial}', '${data.dataFinal}')" style="cursor: pointer">Visualizar</a>
                        </div>
                    </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
                        width: 100,
                    },
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableResumoVeiculos');
    AgGridResumoRelatorioVeiculos = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
    preencherExportacoes(gridOptions);

    gridOptions.quickFilterText = '';
    document.querySelector('#search-input').addEventListener('input', function() {
        var searchInput = document.querySelector('#search-input');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    document.querySelector('#select-quantidade-por-pagina').addEventListener('change', function() {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });
}
jQuery.browser = {};
    (function () {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        let route = RouterController + '/loadRelResumoDisp';
        var dados = $(this).serialize();
        let dataInicial = new Date($('#dp1').val());
        let dataFinal = new Date($('#dp2').val());
        let dadosObj = {};

        if (dataInicial > dataFinal) {
            alert('Data inicial não pode ser maior que a data final');
            return;
        }

        $.each(dados.split('&'), function (index, value) {
            let pair = value.split('=');
            dadosObj[pair[0]] = decodeURIComponent(pair[1] || '');
    
        });

        dadosObj['di'] = dadosObj['di'] ? formataDataInserir(dadosObj['di']) : '';
        dadosObj['df'] = dadosObj['df'] ? formataDataInserir(dadosObj['df']) : '';
        dados = $.param(dadosObj);


        $.ajax({
            ache: false,
            url: route,
            type: 'GET',
            data: dados,
            dataType: 'json',
            beforeSend: function() {
                AgGridResumoRelatorioVeiculos.gridOptions.api.showLoadingOverlay();
                showLoadingButtonFiltro('BtnPesquisar');
            },
            success: function (data) {
                if (data.status == 'OK') {
                    AgGridResumoRelatorioVeiculos.gridOptions.api.setRowData(data.table);
                    $('#total_veiculos').text('Quantidade de Veículos Disponíveis: ' + data.qtd_veiculos);
                    $('#valor_total').text('Valor Total: ' + 'R$ '+ data.valor_total);
                    $('#total_veiculos').css('display', 'block');
                    $('#valor_total').css('display', 'block');
                    nomeCliente = $('#clientes option:selected').text();
                    dataInicialDoc = data.dataInicial;
                    dataFinalDoc = data.dataFinal;
                }else {
                    atualizarAgGridResumoRelatorioVeiculos();
                }
            },
            error: function (data) {
                alert('Erro ao buscar dados. Tente novamente.');
                atualizarAgGridResumoRelatorioVeiculos();
            },
            complete: function() {
                resetLoadingButtonFiltro('BtnPesquisar');
            }
        });
    });

    function stopAgGRIDResumoRelatorioVeiculos() {
        var gridDiv = document.querySelector('#tableResumoVeiculos');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }
    
        var wrapper = document.querySelector('.wrapperResumoVeiculos');
        if (wrapper) {
            wrapper.innerHTML = '<div id="tableResumoVeiculos" class="ag-theme-alpine my-grid"></div>';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('opcoes_exportacao');
    
        document.getElementById('dropdownMenuButton').addEventListener('click', function() {
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });
    
        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButton') {
                dropdown.style.display = 'none';
            }
        });
    });

    function preencherExportacoes(gridOptions) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao');
        const opcoes = ['csv', 'excel', 'pdf'];
    
        let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
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
    
            div.addEventListener('mouseover', function() {
                div.style.backgroundColor = '#f0f0f0'; 
            });
    
            div.addEventListener('mouseout', function() {
                div.style.backgroundColor = '';
            });
    
            div.style.border = '1px solid #ccc';
    
            div.addEventListener('click', function(event) {
                event.preventDefault();
                exportarArquivo(opcao, gridOptions);
            });
    
            formularioExportacoes.appendChild(div);
        });
    }

    function exportarArquivo(tipo, gridOptions) {
        switch (tipo) {
            case 'csv':
                fileName = 'RelatorioResumoVeiculosDisponiveis.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName,
                    columnKeys: ['count', 'placa', 'nomeCliente', 'idContrato', 'qtdDias', 'valorTotal'],
                    prependContent: [
                        [
                            {
                                data: {
                                    type: 'String',
                                    value: `${$('#total_veiculos').text()} | ${$('#valor_total').text()} | Cliente: ${nomeCliente} | Período: ${dataInicialDoc} à ${dataFinalDoc}`
                                },
                                mergeAcross: 5,
                            },
                        ],
                    ]
                });
                break;
            case 'excel':
                fileName = 'RelatorioResumoVeiculosDisponiveis.xlsx';
                gridOptions.api.exportDataAsExcel({
                    fileName: fileName,
                    columnKeys: ['count', 'placa', 'nomeCliente', 'idContrato', 'qtdDias', 'valorTotal'],
                    prependContent: [
                        [
                            {
                                data: {
                                    type: 'String',
                                    value: `${$('#total_veiculos').text()} | ${$('#valor_total').text()} | Cliente: ${nomeCliente} | Período: ${dataInicialDoc} à ${dataFinalDoc}`
                                },
                                mergeAcross: 5,
                            },
                        ],
                    ]
                });
                break;
            case 'pdf':
                let definicoesDocumento = getDocDefinition(
                    printParams('A4'),
                    gridOptions.api,
                    gridOptions.columnApi,
                    '',
                    `Relatório de Resumo dos Veículos Disponíveis\n ${$('#total_veiculos').text()} | ${$('#valor_total').text()}\n Cliente: ${nomeCliente}\n Período: ${dataInicialDoc} à ${dataFinalDoc}`
                );
    
                pdfMake.createPdf(definicoesDocumento).download('RelatorioResumoVeiculosDisponiveis.pdf');
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
            PDF_FONT_SIZE: 10,
        };
    }
    
    function showLoadingButtonFiltro(idButton) {
        $('#' + idButton).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Filtrando...').attr('disabled', true);
    }

    function resetLoadingButtonFiltro(idButton) {
        $('#' + idButton).html('<i class="fa fa-search" aria-hidden="true"></i> Filtrar').attr('disabled', false);
    }

    function formataDataInserir(value) {
        value = value.replaceAll('-', '/');
        value = value.split('/');
        value = value[2] + '/' + value[1] + '/' + value[0];
    
        return value;
    }

let menuAberto = false;
function expandirGrid(){
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

$('.btn-expandir').on('click', function(e) {
    e.preventDefault();
    expandirGrid();
});

function abrirDropdown(botaoP, id, nomeItem, tabelaNome) {
    var dropdown = $('#dropdown-menu-' +nomeItem+ '-' + id);
    var dropdownDOM = $('#dropdown-menu-' +nomeItem+ '-' + id)[0]
    var botao = $(botaoP);
    var tamBotao = botao.outerWidth();

    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }

    $(".dropdown-menu-acoes").hide();

    dropdown.show();

    var posicao = botaoP.getBoundingClientRect();
    var posDrop = dropdownDOM.getBoundingClientRect();
    var gridApi = tabelaNome.gridOptions.api;
    var posBordaTabelaBottom = gridApi.gridBodyCtrl.eGridBody.getBoundingClientRect().bottom;
    var posDropdownBottom = dropdownDOM.getBoundingClientRect().bottom;
    var alturaDrop = dropdownDOM.offsetHeight;
    var tamAjuste = 0;
    var propTopDropdown = window.getComputedStyle(dropdownDOM).getPropertyValue('top');
    var distancia = 0;
    
    if ((posDropdownBottom + 5) > posBordaTabelaBottom) {
        tamAjuste = alturaDrop - 40
        dropdown.css('top', tamAjuste * -1);

        var posBordaTabelaTop = gridApi.gridBodyCtrl.eGridBody.getBoundingClientRect().top;
        var posDropdownTop = dropdownDOM.getBoundingClientRect().top;
        var propLeftDrop = window.getComputedStyle(dropdownDOM).getPropertyValue('left');

        if (posDropdownTop < posBordaTabelaTop) {
            dropdown.css('top', 0);
            distancia = posicao.left + tamBotao - (posDrop.left + posDrop.width);
            dropdown.css('left', (parseInt(propLeftDrop) + distancia) - 32);
        }

    }

    if (propTopDropdown == '0px') {
        var propLeftDropAtual = window.getComputedStyle(dropdownDOM).getPropertyValue('left');
        distancia = posicao.left + tamBotao - (posDrop.left + posDrop.width);
        dropdown.css('left', (parseInt(propLeftDropAtual) + distancia) - 32);

    }
}

function abrirModal(placa, dataInicial, dataFinal){
    $('#diasPlacas').text('');
    $('.dropdown-menu-acoes').hide();
    $.ajax({
        url: RouterController + '/placaDiasVeiculosDisponiveis',
        data: {
            di: dataInicial,
            df: dataFinal,
            placa: placa
        },
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            ShowLoadingScreen();
        },
        success: function (callback) {
            if (callback.status && callback.result.length) {
                $('#diasPlacas').text(callback.result);
                $('#modalDatasPlacas').modal('show');
            }else{
                alert('Não possuem dias para a placa selecionada.');
            }
        },
        error: function(callback){
            alert('Erro ao buscar o(s) dia(s). Tente novamente');
        },
        complete: function() {
            HideLoadingScreen();
        }
    });
}

function ShowLoadingScreen(){
    $('#loading').show()
}

function HideLoadingScreen(){
    $('#loading').hide()
}

$(document).on('click', function (event) {
    if (!$(event.target).closest('.dropdown').length) {
        $(".dropdown-menu-acoes").hide();
    }
});

$('#BtnLimpar').click(function() {
    $('#clientes').val('').trigger('change');
    $('#dp1').val('');
    $('#dp2').val('');
    $('#total_veiculos').text('');
    $('#valor_total').text('');
    atualizarAgGridResumoRelatorioVeiculos();
});