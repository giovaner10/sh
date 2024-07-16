var localeText = AG_GRID_LOCALE_PT_BR;
excelRows = [];

$(document).ready(function () {
    atualizarAgGrid();

    //// UPLOAD 
    $('#fileUpload').change(handleFileUpload);

    function handleFileUpload() {
        var fileUpload = document.getElementById("fileUpload");
        $('.multiChipsAlert').css('display', 'none');

        if (isValidExcelFile(fileUpload.value.toLowerCase())) {
            if (typeof (FileReader) !== "undefined") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var data = e.target.result;
                    if (reader.readAsBinaryString) {
                        processExcel(data);
                    } else {
                        processExcel(arrayBufferToString(data));
                    }
                };

                if (reader.readAsBinaryString) {
                    reader.readAsBinaryString(fileUpload.files[0]);
                } else {
                    reader.readAsArrayBuffer(fileUpload.files[0]);
                }
            }
        } else {
            showAlert("error", "Use um arquivo excel válido");
            resetInputFields();
        }
    }

    function isValidExcelFile(fileName) {
        var regex = /^(.)+(.xls|.xlsx)$/;
        return regex.test(fileName);
    }

    function processExcel(data) {
        var workbook = XLSX.read(data, { type: 'binary' });
        var firstSheet = workbook.SheetNames[0];
        excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

        if (hasRequiredColumns(excelRows[0])) {
            showAlert("success", "Excel válido")
        } else {
            showAlert("error", "Dados inconsistentes, Verifique que os nomes das colunas: ccid, numero, conta, operadora, mbMes, custoMes, não podem ter espaços em branco no início e fim de cada palavra.")
            resetInputFields();
        }
    }

    function hasRequiredColumns(row) {
        return row.ccid && row.numero && row.conta && row.operadora && row.mbMes && row.custoMes;
    }

    function resetInputFields() {
        $('#fileUpload').val(null);
        $('input[type="text"]').val('');
    }

    function arrayBufferToString(buffer) {
        var data = "";
        var bytes = new Uint8Array(buffer);
        for (var i = 0; i < bytes.byteLength; i++) {
            data += String.fromCharCode(bytes[i]);
        }
        return data;
    }


    ////DROPDOWN'S
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

    setupDropdown('#dropdownMenuButton', '#opcoes_exportacao');

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#addModalChipLote').on('hide.bs.modal', function () {
        resetInputFields();
    });

    $("#operadoraForm").select2({
        placeholder: "Selecione a operadora",
        allowClear: true,
        language: "pt-BR",
        width: "100%",
    });

    $('#addModalChip').on('hide.bs.modal', function () {
        $('#titleChip').text('Cadastrar Chip');
        $('#formAddChip').trigger('reset');
        $('#operadoraForm').val(null).trigger('change');
        $('.inputRadio').hide();
    })

    $('#addChips').on('click', function () {
        $('#addModalChip').modal('show');
    })

    $('#addChipsLote').on('click', function () {
        $('#addModalChipLote').modal('show');
    })

    $('#BtnLimpar').on('click', function () {
        $('#formBusca').trigger('reset');
        atualizarAgGrid();
    })

    $('#formAddChip').submit(function (e) {
        e.preventDefault();
        showLoadingSalvarButtonChip();

        if ($('#titleChip').text() == 'Editar Chip') {
            editarChip();
            return;
        }

        let dados = {
            ccid: $("#ccidForm").val(),
            numero: $("#linhaForm").val(),
            operadora: $("#operadoraForm").val(),
            conta: $("#contaForm").val()
        }

        $.ajax({
            cache: false,
            url: Router + '/adicionarChip',
            data: dados,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    showAlert('success', 'Chip cadastrado com sucesso.')
                    $('#addModalChip').modal('hide');
                    atualizarTable();
                } else if (data.status == 400) {
                    showAlert('warning', data.resultado)
                } else if (data.status == 404) {
                    showAlert('warning', data.resultado)
                } else {
                    showAlert('error', "Erro na obtenção dos dados, contate o suporte técnico.")
                }
                resetSalvarButtonChip();
            },
            error: function (xhr, status, error) {
                showAlert('error', 'Erro na solicitação ao servidor.')
                resetSalvarButtonChip();
            }
        });
    })

    $('#btnSalvarChipLote').on('click', function (e) {
        e.preventDefault();
        showLoadingSalvarButtonChipLote();

        if (excelRows.length === 0) {
            showAlert("error", "Nenhum dado para salvar.");
            resetSalvarButtonChipLote();
            return;
        }

        $.ajax({
            cache: false,
            url: Router + '/adicionarChipLote',
            data: JSON.stringify(excelRows),
            contentType: 'application/json',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    showAlert('success', data.resultado.erros.length === 0 ? data.resultado.mensagem : data.resultado.mensagem + JSON.stringify(data.resultado.erros[0]))
                    $('#addModalChipLote').modal('hide');
                } else if (data.status == 400){
                    showAlert('error', data.resultado.mensagem + JSON.stringify(data.resultado.erros[0]))
                } else if (data.status == 500){
                    showAlert('error', "Erro na obtenção dos dados, contate o suporte técnico.")
                }
                resetSalvarButtonChipLote();
            },
            error: function (xhr, status, error) {
                showAlert('error', 'Erro na solicitação ao servidor.')
                resetSalvarButtonChipLote();
            }
        });
    })

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();
        showLoadingLimparButton();

        var searchOptions = {
            ccid: $("#ccid").val(),
            linha: $("#linha").val(),
            operadora: $("#operadora").val(),
        };

        ccid = $("#ccid").val();
        linha = $("#linha").val();
        operadora = $("#operadora").val();

        if (!ccid && !linha && !operadora) {
            resetPesquisarButton();
            resetLimparButton();
            showAlert('warning', "Preencha algum dos campos de busca!");
        } else {
            atualizarAgGrid(searchOptions);
        }

    });

})

function atualizarTable() {

    var searchOptions = null;

    searchOptions = {
        ccid: $("#ccid").val(),
        linha: $("#linha").val(),
        operadora: $("#operadora").val(),
    };

    if (searchOptions && (searchOptions.ccid || searchOptions.linha || searchOptions.operadora)) {
        getDados(function (error) {
            if (!error) {
                atualizarAgGrid(searchOptions);
            } else {
                atualizarAgGrid();
            }
        }, searchOptions)
    } else {
        getDados(function (error) {
            if (!error) {
                atualizarAgGrid();
            } else {
                atualizarAgGrid();
            }
        })
    }
}

function getDados(callback, options) {

    if (options) {
        if (options.ccid || options.linha || options.operadora) {
            showLoadingPesquisarButton();
            showLoadingLimparButton();
            $("#loadingMessage").hide();
        } else {
            showAlert('warning', 'Dados Insuficientes');
            resetPesquisarButton();
            resetLimparButton();
        }
    }

    if (typeof callback === "function") callback(null);
}

function listarChip(id) {
    ShowLoadingScreen();
    $.ajax({
        cache: false,
        url: Router + '/listarChipPorId',
        data: {
            id: id
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                $('#id').val(data.resultado.id)
                $('#ccidForm').val(data.resultado.ccid)
                $('#linhaForm').val(data.resultado.numero)
                $('#contaForm').val(data.resultado.conta)
                $('#operadoraForm').val(data.resultado.operadora.toUpperCase()).trigger('change')

                switch (data.resultado.status) {
                    case 0:
                        $('#statusCadastrado').prop('checked', true);
                        break;
                    case 1:
                        $('#statusHabilitado').prop('checked', true);
                        break;
                    case 2:
                        $('#statusAtivo').prop('checked', true);
                        break;
                    case 3:
                        $('#statusCancelado').prop('checked', true);
                        break;
                    case 4:
                        $('#statusBloqueado').prop('checked', true);
                        break;
                    case 5:
                        $('#statusSuspenso').prop('checked', true);
                        break;
                }

                $('#titleChip').text('Editar Chip');
                $('#addModalChip').modal('show');
                $('.inputRadio').show();
            } else if (data.status == 400) {
                showAlert('warning', data.resultado)
            } else if (data.status == 404) {
                showAlert('warning', data.resultado)
            } else {
                showAlert('error', "Erro na obtenção dos dados, contate o suporte técnico.")
            }
            HideLoadingScreen();
        },
        error: function (xhr, status, error) {
            showAlert('error', 'Erro na solicitação ao servidor.')
            HideLoadingScreen();
        }
    });
}

function editarChip() {
    showLoadingSalvarButtonChip();

    $.ajax({
        cache: false,
        url: Router + '/editarChip',
        data: {
            id: $('#id').val(),
            ccid: $('#ccidForm').val(),
            numero: $('#linhaForm').val(),
            conta: $('#contaForm').val(),
            operadora: $('#operadoraForm').val(),
            status: getStatusValue(),
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                showAlert('success', 'Chip editado com sucesso.');
                $('#addModalChip').modal('hide');
                atualizarTable();
            } else if (data.status == 400) {
                showAlert('warning', data.resultado);
            } else if (data.status == 404) {
                showAlert('warning', data.resultado);
            } else {
                showAlert('error', "Erro na edição, contate o suporte técnico.");
            }
            resetSalvarButtonChip();
        },
        error: function (xhr, status, error) {
            showAlert('error', 'Erro na solicitação ao servidor.');
            resetSalvarButtonChip();
        }
    });
}

var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();
    showLoadingLimparButton();
    showLoadingPesquisarButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/listarChips';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        ccid: options ? options.ccid : '',
                        linha: options ? options.linha : '',
                        operadora: options ? options.operadora : ''
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
                            showAlert('error', data.message)
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor');
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
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
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
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'CCID',
                field: 'ccid',
                chartDataType: 'category',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Linha',
                field: 'linha',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Operadora',
                field: 'operadora',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Conta',
                field: 'conta',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Última Atualização',
                field: 'ultimaAtualizacao',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Vinc. Auto',
                field: 'ccidAuto',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Prestadora',
                field: 'prestadora',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'dataCadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
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
                    let tableId = "tableChips";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:listarChip('${data.id}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                        </div>
                    </div>
                </div>`;
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-chips').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-chips').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-chips').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableChips');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    //gridOptions.api.setRowData(dados);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoes(gridOptions);
}

function stopAgGRID() {
    var gridDiv = document.querySelector('#tableChips');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperChips');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableChips" class="ag-theme-alpine my-grid-chips"></div>';
    }
}

function getStatusValue() {
    let status;
    if ($('#statusCadastrado').is(':checked')) {
        status = $('#statusCadastrado').val();
    } else if ($('#statusHabilitado').is(':checked')) {
        status = $('#statusHabilitado').val();
    } else if ($('#statusAtivo').is(':checked')) {
        status = $('#statusAtivo').val();
    } else if ($('#statusCancelado').is(':checked')) {
        status = $('#statusCancelado').val();
    } else if ($('#statusBloqueado').is(':checked')) {
        status = $('#statusBloqueado').val();
    } else if ($('#statusSuspenso').is(':checked')) {
        status = $('#statusSuspenso').val();
    }
    return status;
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

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
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

function showLoadingSalvarButtonChip() {
    $('#btnSalvarChip').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonChip() {
    $('#btnSalvarChip').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarButtonChipLote() {
    $('#btnSalvarChipLote').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonChipLote() {
    $('#btnSalvarChipLote').html('Salvar').attr('disabled', false);
}


//// EXPORTAÇÕES

function exportarArquivo(tipo, gridOptions, menu, titulo) {
    let colunas = [];
    if (menu === 'RelatorioChips') {
        colunas = ['id', 'ccid', 'linha', 'operadora', 'conta', 'serial', 'ultimaAtualizacao', 'cliente', 'ccidAuto', 'prestadora', 'dataCadastro', 'status'];
    }

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
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                titulo
            );
            pdfMake.createPdf(definicoesDocumento).download(menu + '.pdf');
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
    }
}

// Dados Gerenciamento 

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
            exportarArquivo(opcao, gridOptions, 'RelatorioChips', 'Relatório de Chips');
        });

        formularioExportacoes.appendChild(div);
    });
}
