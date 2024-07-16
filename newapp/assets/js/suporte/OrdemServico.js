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

    $("#listarOS").select2({
		placeholder: "Selecione a OS",
		allowClear: true,
		language: "pt-BR",
		width: "100%",
	});
    
    atualizarAgGrid();

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();
        showLoadingLimparButton();

        var searchOptions = {
            listarOS: $("#listarOS").val(),
            placa: $("#placa").val(),
        };

        listarOS = $("#listarOS").val();
        placa = $("#placa").val();

        if (!listarOS && !placa) {
            resetPesquisarButton();
            resetLimparButton();
            showAlert('warning', 'Preencha o campo de busca!')
        } else {
            atualizarAgGrid(searchOptions);
        }

    });

    $('#BtnLimpar').on('click', function (){
        $('#formBusca').trigger('reset');
        $('#listarOS').val(null).trigger('change');
        atualizarAgGrid();
    })

})

var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();
    showLoadingPesquisarButton();
    showLoadingLimparButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/ajax_load_os_novo';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        listarOS: options ? options.listarOS : '3',
                        placa: options ? options.placa : '',
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
                            showAlert('error', 'Erro na solicitação ao servidor')
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor')
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
                        showAlert('error', 'Erro na solicitação ao servidor')
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
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
                headerName: 'OS',
                field: 'conta_id',
                chartDataType: 'category',
                width: 80,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Tipo',
                field: 'tipo',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Nome Cliente',
                field: 'nome_cliente',
                chartDataType: 'category',
                minWidth: 450,
                suppressSizeToFit: true,
            },
            {
                headerName: 'ID Contrato',
                field: 'id_contrato',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Placa',
                field: 'placa',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Data Cadastro',
                field: 'data_cadastro',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Usuário',
                field: 'nome_user',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
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
                    let tableId = "tableOrdem";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    if (data.status == 'Fechada') {
                        return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="${data.actions.view}" target="_blank" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                            </div>
                        </div>
                    </div>`;

                    } else {

                        return `
                        <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="${data.actions.edit}" target="_blank" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="${data.actions.print}" target="_blank" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Imprimir</a>
                            </div>
                        </div>
                    </div>`;
                    }
                }
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
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
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-ordem').val()),
    };

    $('#select-quantidade-por-pagina-ordem').change(function () {
        var selectedValue = $(this).val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableOrdem');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);

    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoes(gridOptions);
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#tableOrdem');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperOrdem');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableOrdem" class="ag-theme-alpine my-grid-ordem"></div>';
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

function showLoadingLimparButton() {
    $('#BtnLimpar').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimpar').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
}