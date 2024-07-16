var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    $(".btn-expandir").on("click", function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#contratoBusca').mask('00000000000');

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

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            cliente: $("#clienteBusca").val(),
            vendedor: $("#vendedorBusca").val(),
            contrato: $("#contratoBusca").val()
        }

        if (searchOptions && (searchOptions.cliente || searchOptions.vendedor || searchOptions.contrato)) {
            atualizarAgGrid(searchOptions);
        } else {
            exibirAlerta('warning', 'Falha!', 'Digite pelo menos um parâmetro para pesquisar.');
            resetPesquisarButton();
        }
    });

    $('#BtnLimparFiltro').click(function() {
        $('#formBusca').trigger("reset");
        showLoadingLimparButton();
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

// Utilitários
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

// AJAX
function removerContrato(id) {
    if (confirm('Você realmente deseja remover esse contrato?')) {
        let route = Router + '/removeContrato/' + id;
        $.ajax({
            cache: false,
            url: route,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                ShowLoadingScreen();
            },
            success: function (data) {
                if (data.success) {
                    exibirAlerta('success', 'Sucesso!', 'Remoção realizada com sucesso.');
                    var options = {
                        cliente: $("#clienteBusca").val(),
                        vendedor: $("#vendedorBusca").val(),
                        contrato: $("#contratoBusca").val()
                    }
                    atualizarAgGrid(options);
                } else if ('mensagem' in data) {
                    exibirAlerta('error', 'Erro!', data.mensagem);
                } else {
                    exibirAlerta('error', 'Erro!', 'Erro na solicitação ao servidor.');
                }
                HideLoadingScreen();
            },
            error: function (error) {
                exibirAlerta('error', 'Erro!', 'Erro na solicitação ao servidor.');
                HideLoadingScreen();
            }
        });
    }
}

// AgGrid
var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + '/listarContratosMonitorados';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        groupKeys: params.request.groupKeys,
                        rowGroupCols: params.request.rowGroupCols,
                        cliente: options ? options.cliente : '',
                        vendedor: options ? options.vendedor : '',
                        contrato: options ? options.contrato : ''
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        if (AgGrid && ("groupKeys" in  params.request && params.request.groupKeys.length == 0)) {
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
                headerName: 'ID Contrato',
                field: 'id',
                width: 120,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Cliente',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.data.id != "") {
                        return options.data.cliente;
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                rowGroup: true,
                hide: true,
                suppressSizeToFit: true,
                suppressColumnsToolPanel: true
            },
            {
                headerName: 'Vendedor',
                field: 'vendedor',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Início do Contrato',
                field: 'inicio_contrato',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Fim do Contrato',
                field: 'fim_contrato',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Vigência (Meses)',
                field: 'vigencia',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cronograma (Dias)',
                field: 'cronograma',
                width: 155,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    let dias = params.value;
                    if (dias === '') {
                        return '';
                    } else if (dias <= 0) {
                        return `<span class='label label-small label-warning'>${-dias} Restante(s)</span>`
                    } else {
                        return `<span class='label label-small label-danger' style='background-color: #CD201F'>${dias} Passado(s)</span>`
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
                            <div class="dropdown dropdown-table" style="position: relative;">
                                <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:removerContrato('${data.id}')" style="cursor: pointer; color: black;">Remover</a>
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
        autoGroupColumnDef: {
            minWidth: 300,
            flex: 1
        },
        paginateChildRows: true,
        isServerSideGroupOpenByDefault: function(params) {if (params.rowNode.id == '0' || params.rowNode.id == '1') { return true; }},
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

// Carregamentos

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
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Filtrando...').attr('disabled', true);
    $('#BtnLimparFiltro').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
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
    var colunas = ["id", "cliente", "vendedor", "inicio_contrato", "fim_contrato", "vigencia", "cronograma"];
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
                titulo,
                colunas
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
            exportarArquivo(opcao, gridOptions, "Relatório de Monitoramento de Contratos");
        });
        formularioExportacoes.appendChild(div);
    });
}