var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGridAcessos();

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#BtnLimparFiltro').on('click', function(){
        $('#formBusca').trigger('reset');
        $('#tipoData').trigger('change');
        atualizarAgGridAcessos();
    })

    $('#tipoData').on('change', function () {
        let tipo = $(this).val();

        if (tipo == 'dateRange') {
            $('#dateContainer1').show();
            $('#dateContainer2').show();
            $('#mesContainer').hide();
            $('#anoContainer').hide();
            $('#periodoContainer').hide();

        } else if (tipo == 'mes') {
            $('#dateContainer1').hide();
            $('#dateContainer2').hide();
            $('#mesContainer').show();
            $('#anoContainer').hide();
            $('#periodoContainer').hide();

        } else if (tipo == 'ano') {
            $('#dateContainer1').hide();
            $('#dateContainer2').hide();
            $('#mesContainer').hide();
            $('#anoContainer').show();
            $('#periodoContainer').hide();

        } else if (tipo == 'periodo') {
            $('#dateContainer1').hide();
            $('#dateContainer2').hide();
            $('#mesContainer').hide();
            $('#anoContainer').hide();
            $('#periodoContainer').show();

        }
    });

    $('#formBusca').off().on('submit', function (e) {
        e.preventDefault();

        const anoAtual = new Date().getFullYear();

        var tipoData = $('#tipoData').val();
        var searchOptions = {
            dataInicial: null,
            dataFinal: null,
            mes: null,
            ano: null,
            periodo: null
        };

        let errorMessage = "";

        if (tipoData === 'dateRange' && !validacaoFiltros()) {
            errorMessage = "Insira uma data inicial e final válida.";
        } else if (tipoData === 'mes' && !$('#mesInput').val()) {
            errorMessage = "Insira um mês válido.";
        } else if (tipoData === 'ano' && !$('#anoInput').val() || ($('#anoInput').val() > anoAtual)) {
            errorMessage = "Insira um ano válido.";
        } else if (tipoData === 'periodo' && !$('#periodoInput').val()) {
            errorMessage = "Insira um período válido.";
        }

        if (errorMessage != '') {
            $('#BtnPesquisar').blur();
            alert(errorMessage);
            return;

        } else{
            switch (tipoData) {
                case 'dateRange':
                    searchOptions.dataInicial = $('#dataInicial').val();
                    searchOptions.dataFinal = $('#dataFinal').val();
                    break;
                case 'mes':
                    searchOptions.mes = $('#mesInput').val();
                    break;
                case 'ano':
                    searchOptions.ano = $('#anoInput').val();
                    break;
                case 'periodo':
                    searchOptions.periodo = $('#periodoInput').val();
                    break;
            }

            atualizarAgGridAcessos(searchOptions);
        }
    });

})

var AgGridAcessos;
function atualizarAgGridAcessos(searchOptions) {
    stopAgGRIDAcessos();
    disabledButtons();
    showLoadingPesquisarButton();

    if (searchOptions) {
        function getServerSideDados(callback) {
            var route = Router + '/listarMapaCalorByUserOrData';
            $.ajax({
                url: route,
                type: 'POST',
                data: searchOptions,
                dataType: 'json',
                success: function (data) {
                    if (data.status === 200) {
                        var dados = data.results;
                        for (let i = 0; i < dados.length; i++) {
                            for (let chave in dados[i]) {
                                if (dados[i][chave] === null) {
                                    dados[i][chave] = '';
                                }
                            }
                        }
                        callback(dados);
                    } else {
                        callback([]);
                    }
                    enableButtons();
                    resetPesquisarButton();
                },
                error: function () {
                    alert('Erro ao buscar acessos. Tente novamente');
                    enableButtons();
                    resetPesquisarButton();
                }
            })
        }

    } else {
        function getServerSideDados(callback) {
            var route = Router + '/listarRelatorioAcesso';
            $.ajax({
                cache: false,
                url: route,
                type: 'GET',
                dataType: 'json',
                async: true,
                beforeSend: function () {
                    gridOptions.api.showLoadingOverlay();
                },
                success: function (data) {
                    if (data.status === 200) {
                        var dados = data.results;
                        for (let i = 0; i < dados.length; i++) {
                            for (let chave in dados[i]) {
                                if (dados[i][chave] === null) {
                                    dados[i][chave] = '';
                                }
                            }
                        }
                        callback(dados);
                    } else {
                        callback([]);
                    }
                    enableButtons();
                    resetPesquisarButton();
                },
                error: function (error) {
                    console.error('Erro na solicitação ao servidor:', error);
                    callback([]);
                    enableButtons();
                    resetPesquisarButton();
                },
                complete: function () {
                    gridOptions.api.hideOverlay();
                    enableButtons();
                    resetPesquisarButton();
                }
            });
        }
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'URL',
                field: 'urlAcessada',
                chartDataType: 'category',
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Qtd. de Acessos',
                field: 'qtdAcessos',
                chartDataType: 'category',
                width: 180,
                suppressSizeToFit: true,
                sortable: true,
                sort: 'desc'
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-relatorio-acesso').val()),
        cacheBlockSize: 50,
        localeText: localeText,
    };

    $('#select-quantidade-por-pagina-relatorio-acesso').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-relatorio-acesso').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    document.querySelector('#search-input').addEventListener('input', function () {
        var searchInput = document.querySelector('#search-input');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    var gridDiv = document.querySelector('#tableRelatorioAcesso');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    getServerSideDados(function (datasource) {
        gridOptions.api.setRowData(datasource);
        preencherExportacoes(gridOptions);
    });
}

function stopAgGRIDAcessos() {
    var gridDiv = document.querySelector('#tableRelatorioAcesso');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperRelatorioAcesso');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableRelatorioAcesso" class="ag-theme-alpine my-grid-relatorio-acesso"></div>';
    }
}

function validacaoFiltros() {
    dataInicio = $("#dataInicial").val();
    dataFim = $("#dataFinal").val();

    if (!dataInicio && !dataFim) {
        return false;
    }

    if (dataInicio && !dataFim) {
        return false;
    }

    if (!dataInicio && dataFim) {
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

    if (dataInicial > dataFinal || dataFinal > dataAtual || dataInicial > dataAtual) {
        return false;
    }

    return true;
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

function disabledButtons() {
    $('.btn').attr('disabled', true);
}
function enableButtons() {
    $('.btn').attr('disabled', false);
}