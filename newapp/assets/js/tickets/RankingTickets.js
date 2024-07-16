var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function() {
    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        let searchOptions = {
            cliente: $('#clienteBusca').val()
        }

        if (searchOptions && searchOptions.cliente) {
            atualizarAgGrid(searchOptions);
        } else {
            resetPesquisarButton();
            exibirAlerta("warning", "Falha!", "Preencha o campo para fazer a busca!");
        }

    });

    $('#BtnLimparFiltro').on('click', function (e) {
        showLoadingLimparButton();
        limparPesquisa();
        atualizarAgGrid();
    });

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = "table";
        abrirDropdown(dropdownId, buttonId, tableId);
      });

    atualizarAgGrid();
});

//Ultilitarios 
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
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
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
        timerProgressBar: true
    });
}

function limparPesquisa() {
    $('#formBusca').trigger("reset");
}

function formatDate(date) {
    dateCalendar = date.split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0];
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

function abrirDetalhes(id_cliente) {
    ShowLoadingScreen();
    $.ajax({
        url: Router + '/getTicketDetails/' + id_cliente,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data && data.status == 200) {
                var dados = data.resultado;
                $('#clienteTicket').val(dados.cliente);
                $('#usuarioTickets').val(dados.usuario);
                $('#qtdTickets').val(dados.qtdTickets);

                let mes = "";
                switch (dados.mes.toString()) {
                    case '1':
                        mes = "Janeiro"; 
                        break;
                    case '2':
                        mes = "Fevereiro"; 
                        break;
                    case '3':
                        mes = "Março"; 
                        break;
                    case '4':
                        mes = "Abril";
                        break;
                    case '5':
                        mes = "Maio";
                        break;
                    case '6':
                        mes = "Junho";
                        break;
                    case '7':
                        mes = "Julho";
                        break;
                    case '8':
                        mes = "Agosto";
                        break;
                    case '9':
                        mes = "Setembro";
                        break;
                    case '10':
                        mes = "Outubro";
                        break;
                    case '11':
                        mes = "Novembro";
                        break;
                    case '12':
                        mes = "Dezembro";
                        break;
                    default:
                        mes = "Indefinido";
                }

                $('#mesTickets').val(mes);
                $('#estadoCliente').val(dados.situacaoCliente)
                $('#detalhes').modal('show');
                
            } else if (data && data.status == 404) {
                exibirAlerta('warning', 'Falha!', 'Esse cliente não possui informações para serem visualizadas!');
            } else {
                exibirAlerta('error', 'Erro!', 'Não foi possível obter as informações do cliente!');
            }
        },
        error: function() {
            exibirAlerta('error', 'Erro!', 'Não foi possível obter as informações do cliente!');
        },
        complete: function() {
            HideLoadingScreen();
        }
    });
    
}

$('#detalhes').on('hide.bs.modal', function (e) {
    $('#clienteTicket').val('');
    $('#usuarioTickets').val('');
    $('#qtdTickets').val('');
    $('#mesTickets').val('');
    $('#estadoCliente').val('');
})

// AgGrid
var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {
                
                var route = Router + '/buscarDadosServerSide';
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        cliente: options ? options.cliente : ''
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
                                }
                            }
                            AgGrid.gridOptions.api.hideOverlay();
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message && data.statusCode != 500) {
                            exibirAlerta('warning', 'Falha!', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            exibirAlerta('error', 'Erro!', 'Erro na solicitação ao servidor.');
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
                        exibirAlerta('error', 'Erro!', 'Erro na solicitação ao servidor.');
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
                headerName: 'Posição',
                field: 'posicao',
                width: 85,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if(params.value && params.value == 1 || params.value == 2 || params.value == 3) {
                        return params.value + '  <i class="fa fa-trophy" style="color: yellow;" aria-hidden="true"></i>';
                    } else {
                        return params.value;
                    }
                }
            },
            {
                headerName: 'Situação',
                field: 'status',
                width: 90,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value == 'Adimplente') {
                        return '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a>';
                    } else {
                        return '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a>';
                    }
                }
            },
            {
                headerName: 'Nome do Cliente',
                field: 'cliente',
                minWidth: 400,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Quantidade',
                field: 'qtdTickets',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;

                    let tableId = "table";
                    let dropdownId = "dropdown-menu" + data.idCliente;
                    let buttonId = "dropdownMenuButton_" + data.idCliente;
                    return `
                        <div class="dropdown dropdown-table" style="position: relative;">
                            <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:abrirDetalhes('${data.idCliente}')" style="cursor: pointer; color: black;">Visualizar</a>
                                </div>
                            </div>
                        </div>`;
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
        cacheBlockSize: 100,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>'
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '520px');

    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions)
}


// Carregamento
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


// EXPORTAÇÃO DA TEBELA
function exportarArquivo(tipo, gridOptions, titulo) {
    var colunas = ["posicao", "situacao", "cliente", "qtdTickets"];

    switch (tipo) {
        case "csv":
            fileName = titulo + ".csv";
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: colunas,
            });
            break;
        case "excel":
            fileName = titulo + ".xlsx";
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: colunas,
            });
            break;
        case "pdf":
            let definicoesDocumento = getDocDefinition(
                printParams("A4"),
                gridOptions.api,
                gridOptions.columnApi,
                "",
                titulo
            );
            pdfMake.createPdf(definicoesDocumento).download(titulo + ".pdf");
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

$(document).ready(function () {
    var dropdown = $("#opcoes_exportacao");
    $("#dropdownMenuButton").click(function () {
        dropdown.toggle();
    });
    $(document).click(function (event) {
        if (
            !dropdown.is(event.target) &&
            event.target.id !== "dropdownMenuButton"
        ) {
            dropdown.hide();
        }
    });
});

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById("opcoes_exportacao");
    const opcoes = ["csv", "excel", "pdf"];
    let buttonCSV = BaseURL + "/media/img/new_icons/csv.png";
    let buttonEXCEL = BaseURL + "media/img/new_icons/excel.png";
    let buttonPDF = BaseURL + "media/img/new_icons/pdf.png";
    formularioExportacoes.innerHTML = "";
    opcoes.forEach((opcao) => {
        let button = "";
        let texto = "";
        switch (opcao) {
            case "csv":
                button = buttonCSV;
                texto = "CSV";
                margin = "-5px";
                break;
            case "excel":
                button = buttonEXCEL;
                texto = "Excel";
                margin = "0px";
                break;
            case "pdf":
                button = buttonPDF;
                texto = "PDF";
                margin = "0px";
                break;
        }
        let div = document.createElement("div");
        div.classList.add("dropdown-item");
        div.classList.add("opcao_exportacao");
        div.setAttribute("data-tipo", opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px; color: #1C69AD !important; font-family: 'Mont SemiBold';" title="Exportar no formato ${texto}">${texto}</label>
    `;
        div.style.height = "30px";
        div.style.marginTop = margin;
        div.style.borderRadius = "1px";
        div.style.transition = "background-color 0.3s ease";
        div.addEventListener("mouseover", function () {
            div.style.backgroundColor = "#f0f0f0";
        });
        div.addEventListener("mouseout", function () {
            div.style.backgroundColor = "";
        });
        div.style.border = "1px solid #ccc";
        div.addEventListener("click", function (event) {
            event.preventDefault();
            exportarArquivo(opcao, gridOptions, "Relatório Ranking de Tickets");
        });
        formularioExportacoes.appendChild(div);
    });
}