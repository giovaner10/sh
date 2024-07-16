var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    var dropdown = $('#dropdownOS');

    $('#dropdownMenuButtonOS').click(function() {
        if (dropdown.css('display') === 'none') {
            dropdown.css('display', 'flex');
            $('#opcoes_exportacao').hide();
        } else {
            dropdown.css('display', 'none');
        }
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonOS') {
            dropdown.hide();
        }
    });

    $('#idContrato').mask('00000000000');

    $("#coluna").select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $('#coluna').change(function() {
        let val = $(this).val();
        $('#idContrato').val('');
        $('#nomeCliente').val('');

        if (val == "contratos.id") {
            $('.buscaCliente').hide();
            $('.buscaContrato').show();
        } else {
            $('.buscaCliente').show();
            $('.buscaContrato').hide();
        }
    })
    
    atualizarAgGrid();

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            coluna: $("#coluna").val(),
            id: $('#idContrato').val(),
            nome: $('#nomeCliente').val(),
        };

        if (searchOptions.coluna && (searchOptions.id || searchOptions.nome)) {
            atualizarAgGrid(searchOptions);
        } else {
            resetPesquisarButton();
            showAlert('warning', 'Preencha o campo de busca!')
        }

    });

    $('#BtnLimparFiltro').on('click', function (){
        showLoadingLimparButton();
        $('#formBusca').trigger('reset');
        $('#coluna').val('nome').trigger('change');
        atualizarAgGrid();
    });

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = $(this).attr("data-tableId");
        abrirDropdown(dropdownId, buttonId, tableId);
    });

});


// Utilitarios
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

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
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


// AgGrid
var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + '/get_contratos_instalacao';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        coluna: options ? options.coluna : '',
                        id: options ? options.id : '',
                        nome: options ? options.nome : ''
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        if (AgGrid) {
                            AgGrid.gridOptions.api.showLoadingOverlay();
                        }
                    },
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    // Verifica se o valor é null e substitui por uma string vazia
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }

                                    if (chave === 'status') {
                                        switch (dados[i][chave]) {
                                            case "0":
                                                dados[i][chave] = 'Cadastrado';
                                                break;
                                            case "1":
                                                dados[i][chave] = 'OS';
                                                break;
                                            case "2":
                                                dados[i][chave] = 'Ativo';
                                                break;
                                            case "3":
                                                dados[i][chave] = 'Cancelado';
                                                break;
                                            case "4":
                                                dados[i][chave] = 'Teste';
                                                break;
                                            case "5":
                                                dados[i][chave] = 'Bloqueado';
                                                break;
                                            case "6":
                                                dados[i][chave] = 'Encerrado';
                                                break;
                                            case "7":
                                                dados[i][chave] = 'Em processo de Retirada';
                                                break;
                                            default:
                                                dados[i][chave] = 'Indefinido';
                                                break;
                                        }
                                    }
                                }
                            }
                            AgGrid.gridOptions.api.hideOverlay();
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message && data.statusCode != 500) {
                            showAlert('warning', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor.');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        }
                        if (options) {
                            resetPesquisarButton();
                        } else {
                            resetLimparButton();
                        }
                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor.');
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
                        AgGrid.gridOptions.api.showNoRowsOverlay();
                    },
                });
            },
        };
    }

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Contrato',
                field: 'id',
                width: 120,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Cliente',
                field: 'nome',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Veículos',
                field: 'quantidade_veiculos',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 190,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data Cadastro',
                field: 'data_cadastro',
                suppressSizeToFit: true,
                width: 180,
                cellRenderer: function (params) {
                    if (params.value) {
                        return formatDateTime(params.value);
                    } else {
                        return params.value;
                    }
                }
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "table";
                    let dropdownId = "dropdown-menu" + data.id;
                    let buttonId = "dropdownMenuButton_" + data.id;
                    if (data.id) {
                        return `
                            <div class="dropdown dropdown-table" data-tableId="${tableId}" style="position: relative;">
                                <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="${SiteURL + '/servico/gerar_ordem_servico/1/' + data.id + '/' + data.id_cliente + '/' + data.quantidade_veiculos}" target="_blank" style="cursor: pointer; color: black;">Gerar OS</a>
                                    </div>
                                </div>
                            </div>`;
                    } else {
                        return '';
                    }
                },
            },
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
            defaultToolPanel: false,
        },
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>'
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '530px');
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions)
}

//Carregamento
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
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
    $('#BtnPesquisar').attr('disabled', false);
}