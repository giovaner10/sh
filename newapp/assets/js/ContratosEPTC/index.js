var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGrid();

    $(".btn-expandir").on("click", function (e) {
        e.preventDefault();
        expandirGrid();
    });

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

    $('#coluna').select2({
        language: "pt-BR",
        placeholder: "Selecione uma opção",
        minimumResultsForSearch: -1,
        width: '100%'
    });

    $('#coluna').on("change", function (e) {
        let val = $(this).val();
        let textoSelecionado = $(this).find("option:selected").text();

        if (val == 'prefixo') {
            $('#valorBusca').mask('00000000000');
        } else {
            $('#valorBusca').unmask();
        }

        $('#labelforBusca').html(textoSelecionado ? textoSelecionado: 'Indefinido');

        $('#valorBusca').val('');
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            coluna: $("#coluna").val(),
            valor: $("#valorBusca").val()
        }

        if (searchOptions && searchOptions.coluna && searchOptions.valor) {
            atualizarAgGrid(searchOptions);
        } else {
            exibirAlerta('warning', 'Falha!', 'Preencha o campo para pesquisar.');
            resetPesquisarButton();
        }
    });

    $("#formDigitalizar").on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        
        let id_contrato = $('#id_contrato').val();

        $.ajax({
            url: SiteURL + '/contratos/digitalizacao_contrato/' + id_contrato,
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 
            beforeSend:function(){
                showLoadingSalvarButton();
            },
            success: function(data){
                try {
                    var data = JSON.parse(data);
                    if (data.status) {
                        $('#descricao').val('');
                        $('#arquivo').val('');
                        exibirAlerta('success', 'Sucesso!', "Arquivo enviado com suceso!");
                        $.ajax({
                            url: Router + '/get_arquivos_digi_contrato_eptc/' + id_contrato,
                            type: 'GET',
                            dataType: 'json',
                            beforeSend: function() {
                                if (AgGridAnexos) {
                                    AgGridAnexos.gridOptions.api.showLoadingOverlay();
                                }
                            },
                            success: function(data){
                                if (data.success) {
                                    if (data.dados) {
                                        atualizarAgGridAnexos(data.dados);
                                    } else {
                                        atualizarAgGridAnexos([]);
                                    }
                                } else {
                                    exibirAlerta('error', 'Erro!', 'Não foi possível atualizar a lista de arquivos.');
                                }
                            },
                            error: function () {
                                exibirAlerta('error', 'Erro!', 'Não foi possível atualizar a lista de arquivos.');
                            },
                            complete: function () {
                                AgGridAnexos.gridOptions.api.hideOverlay();
                                HideLoadingScreen();
                            }
                        });
                    } else {
                        exibirAlerta('error', 'Erro!', "Não foi possível enviar o arquivo!");
                    }
                } catch (e) {
                    alert('Erro ao tentar exibir resposta! Entre em contato com o suporte técnico!' + e);
                }
            },
            error: function () {
                exibirAlerta('error', 'Erro!', "Não foi possível enviar o arquivo!");
            },
            complete: function(){
                resetSalvarButton();
            }
        });
    });

    $('#BtnLimparFiltro').click(function() {
        $('#valorBusca').val('');
        showLoadingLimparButton();
        atualizarAgGrid();
    });

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = $(this).attr("data-tableId");
        abrirDropdown(dropdownId, buttonId, tableId);
    });
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

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
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
    var altDropdown = dropdown.height() + 10;
    dropdown.css('bottom', `auto`).css('top', '100%');
 
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posDropdown = $('#' + dropdownId).get(0).getBoundingClientRect().bottom;
    var posDropdownTop = $('#' + dropdownId).get(0).getBoundingClientRect().top;

    if (altDropdown > (posBordaTabela - posDropdownTop)) {
        if (altDropdown < (posDropdownTop - posBordaTabelaTop)){
            dropdown.css('top', `auto`).css('bottom', '80%');
        } else {
            let diferenca = altDropdown - (posDropdownTop - posBordaTabelaTop);
            dropdown.css('top', `-${(altDropdown - 60) - (diferenca) }px`);
        }
    }
}

function digitalizarContrato(prefixo) {
    ShowLoadingScreen();

    $('#descricao').val('');
    $('#arquivo').val('');
    $('#id_contrato').val(prefixo);

    $.ajax({
        url: Router + '/get_arquivos_digi_contrato_eptc/' + prefixo,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            if (AgGridAnexos) {
                AgGridAnexos.gridOptions.api.showLoadingOverlay();
            }
        },
        success: function(data){
            if (data.success) {
                if (data.dados) {
                    atualizarAgGridAnexos(data.dados);
                } else {
                    atualizarAgGridAnexos([]);
                }

                $('#myModal_digitalizar').modal('show');
            } else {
                exibirAlerta('error', 'Erro!', 'Não foi possível listar os arquivos.');
            }
        },
        error: function () {
            exibirAlerta('error', 'Erro!', 'Não foi possível listar os arquivos.');
        },
        complete: function () {
            AgGridAnexos.gridOptions.api.hideOverlay();
            HideLoadingScreen();
        }
    });
    
}

// AgGrid
var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {
                var route = Router + '/get_contratos_paginated';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        coluna: options ? options.coluna : '',
                        valor: options ? options.valor : '',
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
                headerName: 'Prefixo',
                field: 'prefixo',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa',
                field: 'placa',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Permissionário',
                field: 'permissionario',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Marca',
                field: 'marca',
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
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
                                        <a href="${SiteURL + '/contratos_eptc/imprimir_contrato/' + data.id}" target="_blank" style="cursor: pointer; color: black;">Imprimir</a>
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:digitalizarContrato('${data.prefixo}')" style="cursor: pointer; color: black;">Digitalizar</a>
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
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        cacheBlockSize: 100,
        localeText: localeText,
        domLayout: 'normal',
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
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

var AgGridAnexos;
function atualizarAgGridAnexos(dados) {
    stopAgGRIDAnexos();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 100,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                flex: 1,
                minWidth: 300,
                suppressSizeToFit: true,
            },
            { 
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                suppressMenu: true,
                sortable: false,
                filter: false,
                resizable: false,
                cellRenderer: function (options) {
                    let data = options.data;

                    let tableId = "tableAnexos";
                    let dropdownId = "dropdown-menu" + data.id;
                    let buttonId = "dropdownMenuButton_" + data.id;
                    if (data.id) {
                        return `
                            <div class="dropdown dropdown-table" data-tableId="${tableId}">
                                <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a  href="${SiteURL + '/contratos/visualizar_contrato/' + data.file}" target="_blank" style="cursor: pointer; color: black;">Visualizar</a>
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
            sortable: true,
            filter: true,
            resizable: true,
            suppressMenu: false,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
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
                    },
                },
            ],
            defaultToolPanel: false,
        },
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: 5,
        localeText: localeText,
    }

    var gridDiv = document.querySelector('#tableAnexos');
    gridDiv.style.setProperty('height', '310px');
    AgGridAnexos = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
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

function stopAgGRIDAnexos() {
    var gridDiv = document.querySelector('#tableAnexos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperAnexos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableAnexos" class="ag-theme-alpine my-grid"></div>';
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

function showLoadingSalvarButton() {
    $('#sendAnexo').html('<i class="fa fa-spinner fa-spin"></i> Enviando...').attr('disabled', true);
}

function resetSalvarButton() {
    $('#sendAnexo').html('Enviar').attr('disabled', false);
}

// EXPORTAÇÃO DA TEBELA
function exportarArquivo(tipo, gridOptions, titulo) {
    var colunas = ["id", "prefixo", "placa", "permissionario", "marca", "modelo"];
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
            exportarArquivo(opcao, gridOptions, "Relatório de Contratos EPTC");
        });
        formularioExportacoes.appendChild(div);
    });
}