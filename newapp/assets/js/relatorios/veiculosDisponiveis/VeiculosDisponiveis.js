var localeText = AG_GRID_LOCALE_PT_BR;
$(document).ready(function () {
    atualizarAgGrid([]);

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#clienteBusca').select2({
        ajax: {
            url: Router + '/listAjaxSelectClient',
            dataType: 'json'
        }
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();
        showLoadingLimparButton();

        var searchOptions = {
            id_cliente: $("#clienteBusca").val(),
            di: $("#dataInicial").val(),
            df: $("#dataFinal").val(),
        };

        id_cliente = $("#clienteBusca").val();
        di = formatarData($("#dataInicial").val());
        df = formatarData($("#dataFinal").val());

        if (id_cliente == null || di == "" || df == "") {
            resetPesquisarButton();
            resetLimparButton();
            alert("Preencha todos os campos de busca!");
            return;
        } else {
            if (validacaoFiltros()) {
                getDados(searchOptions);
                return;
            } else {
                resetPesquisarButton();
                resetLimparButton();
                return;
            }
        }
    });

    $('#BtnLimparFiltro').on('click', function () {
        $('#formBusca').trigger('reset');
        $('#clienteBusca').val(null).trigger('change');
        atualizarAgGrid([]);
    })
})

function validacaoFiltros() {
    dataInicio = $("#dataInicial").val();
    dataFim = $("#dataFinal").val();

    if (!dataInicio && !dataFim) {
        alert("É necessário informar a Data Inicial e Data Final");
        return false;
    }

    if (dataInicio && !dataFim) {
        alert("É necessário informar a Data Final");
        return false;
    }

    if (!dataInicio && dataFim) {
        alert("É necessário informar Data Inicial");
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

    if (dataInicial > dataFinal) {
        alert("A data inicial não pode ser maior que a data final.");
        return false;
    }

    if (dataFinal > dataAtual) {
        alert("A data final não pode ser maior que a data atual.");
        return false;
    }

    if (dataInicial > dataAtual) {
        alert("A data inicial não pode ser maior que a data atual.");
        return false;
    }

    return true;
}

function formatarData(data) {
    let partesData = data.split('-');
    let dataFormatada = partesData[2] + '/' + partesData[1] + '/' + partesData[0];
    return dataFormatada;
}

function getDados(options) {
    var route = Router + '/calculoVeiculosDisponiveis';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: options,
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data == '') {
                alert("Dados não encontrados para os parâmetros informados.")
            }
            atualizarAgGrid(data['veiculos_disponiveis']);
            resetPesquisarButton();
            resetLimparButton();
        },
        error: function (error) {
            alert('Erro na solicitação ao servidor');
            resetPesquisarButton();
            resetLimparButton();
        },
    });
}

var AgGrid;
function atualizarAgGrid(dados) {
    stopAgGRID();

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Data',
                field: "data",
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }

            },
            {
                headerName: 'Última Configuração',
                field: "ultimaConfig",
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa',
                field: "placa",
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Serial',
                field: "serial",
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Cliente',
                field: "cliente",
                chartDataType: 'category',
                width: 250,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Contrato',
                field: "contrato",
                chartDataType: 'category',
                width: 250,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Diário',
                field: "valorDiario",
                valueFormatter: function(params){
                    return "R$ " + params.value;
                },
                width: 250,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Mensal',
                field: "valorMensal",
                valueFormatter: function(params){
                    return "R$ " + params.value;
                },
                width: 250,
                suppressSizeToFit: true,
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
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-veiculos').val()),
    };

    var gridDiv = document.querySelector('#tableVeiculos');
    gridDiv.style.setProperty('height', '519px');
    AgGridCemUltSolic = new agGrid.Grid(gridDiv, gridOptions);
    AgGridCemUltSolic.gridOptions.api.setRowData(dados);

    $('#select-quantidade-por-pagina-veiculos').change(function () {
        var selectedValue = $(this).val();
        AgGridCemUltSolic.gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

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