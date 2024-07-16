var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {

    $("#coluna").select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = $(this).attr("data-tableId");
        abrirDropdown(dropdownId, buttonId, tableId);
    });

    $('#coluna').change(function() {
        let val = $(this).val();
        let textoSelecionado = $(this).find("option:selected").text();
        
        $('#valor').val('');

        $('#labelForValor').html(textoSelecionado ? textoSelecionado: 'Indefinido');

        if (val == 'cidade') {
            $('#valor').attr('placeholder', 'Digite a cidade...');
        } else if (val == 'estado') {
            $('#valor').attr('placeholder', 'Digite a sigla do estado...');
        } else if (val == 'pais') {
            $('#valor').attr('placeholder', 'Digite a sigla do país...');
        } else if (val == 'nome') {
            $('#valor').attr('placeholder', 'Digite o nome...');
        } else if (val == 'email') {
            $('#valor').attr('placeholder', 'Digite o e-mail...');
        } else {
            $('#valor').attr('placeholder', 'Digite o valor...');
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
            valor: $('#valor').val(),
        };

        if (searchOptions.coluna && (searchOptions.valor)) {
            atualizarAgGrid(searchOptions);
        } else {
            resetPesquisarButton();
            showAlert('warning', 'Preencha o campo de busca!')
        }

    });

    $('#BtnLimparFiltro').on('click', function (){
        showLoadingLimparButton();
        $('#valor').val('');
        atualizarAgGrid();
    });

    $('#btnSalvarEmail').on('click', function(e) {
        e.preventDefault();
        let emailShow = $('#emailShow').val();
        let idRepresentante = $('#idRepresentante').val();

        if (validateEmail(emailShow)) {
            $.ajax({
                url: Router + '/editar_email_show',
                type: 'POST',
                data: {
                    emailShow: emailShow,
                    idRepresentante: idRepresentante
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#btnSalvarEmail').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
                },
                success: function(data) {
                    if (data.statusCode == 200) {
                        showAlert('success', data.message);
                        var searchOptions = {
                            coluna: $("#coluna").val(),
                            valor: $('#valor').val(),
                        };
                        atualizarAgGrid(searchOptions);
                        $('#modalEmailShow').modal('hide');
                    } else if (data.statusCode === 404) {
                        showAlert('warning', data.message);
                    } else {
                        showAlert('error', 'Não foi possível editar o E-mail Show do representante.');
                    }
                    $('#btnSalvarEmail').html('Salvar').attr('disabled', false);
                },
                error: function() {
                    showAlert('error', 'Não foi possível editar o E-mail Show do representante.');
                    $('#btnSalvarEmail').html('Salvar').attr('disabled', false);
                }
            });
        } else {
            showAlert('warning', 'Digite um e-mail válido!');
        }
        
    });

    $('#modalEmailShow').on('hidden.bs.modal', function() {
        $('#idRepresentante').val('');
        $('#emailShow').val('');
        $('#btnSalvarEmail').html('Salvar').attr('disabled', false);
    });

    $('#arquivo').change(function() {
        if (this.files.length > 0) {
            var fileSize = this.files[0].size; // Tamanho do arquivo em bytes
            var maxSize = 2 * 1024 * 1024; // 2MB em bytes

            if (fileSize > maxSize) {
                $('#fileSizeError').show();
                $(this).val(''); // Limpa o campo de arquivo selecionado
            } else {
                $('#fileSizeError').hide();
            }
        } else {
            $('#fileSizeError').hide();
        }
    });

    $('#btnDigitalizarDocumento').on('click', function() {
        abrirModalArquivos('000', 'Digitalizar Arquivos para Todos');
    });

    $('#btnLimparArquivo').on('click', function() {
        $('#formUploadAnexo')[0].reset();
    });

    $('#modalVisualizarAnexos').on('hidden.bs.modal', function() {
        $('#fileSizeError').hide();
        $('#formUploadAnexo')[0].reset();
    });

    $('#formUploadAnexo').submit(function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);

        var idRepresentante = $('#id_representante_anexo').val();
        
        // Envie a requisição AJAX
        $.ajax({
            url: Router + '/post_arquivo',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#sendAnexo').html('<i class="fa fa-spinner fa-spin"></i> Enviando...').attr('disabled', true);
            },
            success: function(response){
                try {
                    var response = JSON.parse(response);
                    if (response.success) {
                        $('#formUploadAnexo')[0].reset();
                        showAlert('success', response.message);
                        $.ajax({
                            url: Router + '/get_arquivos/' + idRepresentante,
                            type: 'GET',
                            dataType: 'json',
                            beforeSend: function() {
                                if (AgGridAnexos) {
                                    AgGridAnexos.gridOptions.api.showLoadingOverlay();
                                }
                            },
                            success: function(data) {
                                if (data.statusCode == "200" || data.statusCode == "404") {
                                    atualizarAgGridAnexos(data.dados);
                                } else {
                                    showAlert('error', 'Erro ao buscar os dados do representante.');
                                    AgGridAnexos.gridOptions.api.hideOverlay();
                                }
                            },
                            error: function() {
                                showAlert('error', 'Erro ao buscar os dados do representante.');
                                AgGridAnexos.gridOptions.api.hideOverlay();
                            }
                        });
                    } else {
                        showAlert('error', response.message);
                    }
                } catch (e) {
                    showAlert('error', 'Falha ao enviar arquivo!');
                }
            },
            error: function(){
                $('#sendAnexo').html('Enviar').attr('disabled', false);
            },
            complete: function () {
                $('#sendAnexo').html('Enviar').attr('disabled', false);
            }
        });
    });
});


// Utilitarios
function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
}

function formatarTelefone(telefone) {
    // Remover todos os caracteres não numéricos
    telefone = telefone.replace(/\D/g, '');
    
    // Aplicar a máscara: (XX) XXXX-XXXX ou (XX) XXXXX-XXXX (padrão brasileiro) ou (XXX) XXX-XXXX (padrão americano)
    if (telefone.length === 11) {
        // Formato: (XX) XXXXX-XXXX (padrão brasileiro)
        return telefone.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else if (telefone.length === 10) {
        // Formato: (XX) XXXX-XXXX (padrão brasileiro)
        return telefone.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    } else {
        // Retornar o número de telefone original se não corresponder a nenhum formato conhecido
        return telefone;
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
            dropdown.css('top', `-${posDropdown - 50}px`);
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

//Ajax
function abrirModalEditarEmail(idRepresentante) {
    $.ajax({
        url: Router + '/get_email_representante/' + idRepresentante,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            ShowLoadingScreen();
        },
        success: function(data) {
            if(data.statusCode == "200") {
                $('#idRepresentante').val(idRepresentante);
                $('#emailShow').val(data.email);
                $('#modalEmailShow').modal('show');
            } else if (data.statusCode == "404") {
                showAlert('warning', data.message);
            } else {
                showAlert('error', 'Erro ao buscar os dados do representante.');
            }
            HideLoadingScreen();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showAlert('error', 'Erro ao buscar os dados do representante.');
            HideLoadingScreen();
        }
    });
}

function abrirModalInfo(idRepresentante) {
    $.ajax({
        url: Router + '/get_informacoes_representante/' + idRepresentante,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            ShowLoadingScreen();
        },
        success: function(data) {
            if(data.statusCode == "200") {
                atualizarAgGridInfo(data.dados);
                $('#modalMaisInfo').modal('show');
            } else if (data.statusCode == "404") {
                showAlert('warning', data.message);
            } else {
                showAlert('error', 'Erro ao buscar os dados do representante.');
            }
            HideLoadingScreen();
        },
        error: function() {
            showAlert('error', 'Erro ao buscar os dados do representante.');
            HideLoadingScreen();
        }
    });
}

function abrirModalArquivos(idRepresentante, titulo) {
    $.ajax({
        url: Router + '/get_arquivos/' + idRepresentante,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            ShowLoadingScreen();
        },
        success: function(data) {
            if(data.statusCode == "200" || data.statusCode == "404") {
                atualizarAgGridAnexos(data.dados);
                $('#titulo-digitalizar-arquivos').html(titulo ? titulo : "Digitalizar Arquivos");
                $('#id_representante_anexo').val(idRepresentante);
                $('#modalVisualizarAnexos').modal('show');
            } else {
                showAlert('error', 'Erro ao buscar os dados do representante.');
            }
            HideLoadingScreen();
        },
        error: function() {
            showAlert('error', 'Erro ao buscar os dados do representante.');
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
                var route = Router + '/get_representantes_ajax';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        coluna: options ? options.coluna : '',
                        valor: options ? options.valor : ''
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

                                    if (chave === 'telefone' || chave === 'celular') {
                                        dados[i][chave] = formatarTelefone(dados[i][chave]);
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
                headerName: 'ID',
                field: 'id',
                width: 120,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    return `<span class="badge">${params.value}</span>`
                }
            },
            {
                headerName: 'Nome',
                field: 'nome_completo',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data Criação',
                field: 'data_criacao',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value) {
                        return formatDateTime(params.value);
                    } else {
                        return params.value;
                    }
                }
            },
            {
                headerName: 'Cidade',
                field: 'cidade',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Estado',
                field: 'estado',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Pais',
                field: 'pais',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Telefone',
                field: 'telefone',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Celular',
                field: 'celular',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'E-mail',
                field: 'email',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'E-mail Show',
                field: 'emailshow',
                width: 250,
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
                                        <a href="javascript:abrirModalEditarEmail(${data.id})" style="cursor: pointer; color: black;">Editar E-mail Show</a>
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:abrirModalInfo(${data.id})" style="cursor: pointer; color: black;">Mais informações</a>
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:abrirModalArquivos(${data.id}, 'Digitalizar Arquivos')" style="cursor: pointer; color: black;">Digitalizar arquivos</a>
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

var AgGridInfo;
function atualizarAgGridInfo(dados) {
    stopAgGRIDInfo();

    var gridOptions = {
        columnDefs: [
            { 
                headerName: 'Dados',
                field: "coluna",
                width: 140,
                resizable: false,
                cellStyle: params => {
                    return {fontWeight: 'bold', fontSize: '13px', backgroundColor: '#f8f8f8', borderBottom: '1px solid #babfc7', borderRight: '1px solid #babfc7'};
                }
            },
            {
                headerName: 'Descrição',
                field: 'valor',
                flex: 1,
                minWidth: 300,
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.data.coluna == "Telefone" || params.data.coluna == "Celular") {
                        return formatarTelefone(params.value);
                    } else {
                        return params.value;
                    }
                }
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            filter: false,
            resizable: true,
            suppressMenu: true,
        },
        suppressRowClickSelection: true,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
        domLayout: 'normal',
        localeText: localeText,
    }

    var gridDiv = document.querySelector('#tableInfo');
    gridDiv.style.setProperty('height', '500px');
    AgGridInfo = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
}


function stopAgGRIDInfo() {
    var gridDiv = document.querySelector('#tableInfo');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperInfo');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableInfo" class="ag-theme-alpine my-grid"></div>';
    }
}


var AgGridAnexos;
function atualizarAgGridAnexos(dados) {
    stopAgGRIDAnexos();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                flex: 1,
                minWidth: 200,
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
                    let dropdownId = "dropdown-menu-arquivos" + data.id;
                    let buttonId = "dropdownMenuButtonArquivos_" + data.id;
                    if (data.id) {
                        return `
                            <div class="dropdown dropdown-table" data-tableId="${tableId}" style="position: relative;">
                                <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="${Router + '/view_file/' + data.file}" target="_blank" style="cursor: pointer; color: black;">Visualizar</a>
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