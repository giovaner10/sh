
$(document).ready(function () {

    $('#BtnAdicionarAlertasEmail').on('click', function () {
        abrirAlertasEmail();
        $('#idAlertasEmail').val('0');
    });

    $('#btnSalvarAlertasEmail').on('click', function () {

        // verificar se seguradora foi selecionado
        if (!$('#seguradoraAlertasEmail').val()) {
            showAlert('warning', 'Você precisa selecionar um cliente!')
            return;
        }

        // verificar se existe e-mails salvos
        if ($('#tags').find('li').length < 1) {
            showAlert('warning', 'Você precisa adicionar pelo menos um e-mail!')
            return;
        }

        salvarAlertasEmail();
    });

    //Inclusão da label para orientação do usuário.
    $('<span/>', {
        text: 'Após digitar o e-mail aperte "Enter" para incluí-lo à lista.',
        css: { padding: '0px 0px 0px 0px', fontSize: 'small', color: '#7F7F7F', fontFamily: 'Century Gothic, sans-serif', display: 'flex', marginTop: '0px' }
    }).insertAfter('label[for="input-tag"]');

    // Obter os elementos de tags e input do DOM
    const tags = $('#tags');
    const input = $('#input-tag');
    existingEmails = new Set();

    // Adicionar um ouvinte de eventos para keydown no elemento de input
    input.on('keydown', function (event) {

        // Verificar se a tecla pressionada é 'Enter'
        if (event.key === 'Enter') {

            // Impedir a ação padrão do evento de pressionar a tecla
            // (enviar o formulário)
            event.preventDefault();

            // Criar um novo elemento de item de lista para a tag
            const tag = $('<li></li>');

            // Obter o valor aparado do elemento de input
            const tagContent = input.val().trim();

            // Se o valor aparado não for uma string vazia
            if (tagContent !== '') {
                if (validaEmail(tagContent)) {
                    if (!emailExists(tagContent)) {
                        existingEmails.add(tagContent);

                        tag.text(tagContent);

                        // Adicionar um botão de exclusão à tag
                        tag.append('<button class="delete-button"><i class="fa fa-close"></i></button>');

                        // Anexar a tag à lista de tags
                        tags.append(tag);

                        // Limpar o valor do elemento de input
                        input.val('');
                    } else {
                        showAlert('warning', 'O e-mail já foi adicionado!')
                    }
                } else {
                    showAlert('warning', 'O e-mail não é valido!')
                }
            }
        }
    });

    // Adicionar um ouvinte de eventos para clique na lista de tags
    tags.on('click', '.delete-button', function (event) {
        // Remover o elemento pai (a tag)
        $(this).parent().remove();
    });

    function validaEmail(email) {
        const expression = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return expression.test(email);
    }

    function emailExists(email) {
        return existingEmails.has(email);
    }

    $('.tab-alertas').on('click', function (e) {
        e.preventDefault(); // Prevenir a ação padrão do link

        // Remover a classe 'active' de todas as abas e ocultar todos os conteúdos
        $('.tab-alertas').removeClass('active');
        $('.conteudo-tab').hide();

        // Adicionar a classe 'active' à aba clicada
        $(this).addClass('active');

        // Mostrar o conteúdo correspondente à aba clicada

        if ($(this).attr('id') === 'tab-hotlist') {
            $('#modalDivAlerta').removeClass('modal-md').addClass('modal-lg');
            $('#conteudoHotList').show();
            $('#btnSalvarAlertasEmail').hide();
            atualizarAgGridAbaBlacklist();
        } 
        else if ($(this).attr('id') === 'tab-coldlist') {
            $('#modalDivAlerta').removeClass('modal-md').addClass('modal-lg');
            $('#conteudoColdList').show();
            $('#btnSalvarAlertasEmail').hide();
            atualizarAgGridAbaWhitelist();
        } else {
            $('#modalDivAlerta').removeClass('modal-lg').addClass('modal-md');
            $('#btnSalvarAlertasEmail').show();
            $('#conteudoDadosAlerta').show();
        }
    });

    // Garantir que a aba "Dados do Alerta" seja ativada ao abrir o modal
    $('#alertasEmail').on('show.bs.modal', function () {
        $('.nav-tabs .nav-item:first').addClass('active');
        $('#tab-dadosAlerta').addClass('active');
        $('#conteudoDadosAlerta').show();
        $('#conteudoHotList').hide();
        $('#conteudoColdList').hide();
        $('#modalDivAlerta').removeClass('modal-lg').addClass('modal-md');
        $('#btnSalvarAlertasEmail').show();
    });

    $('#alertasEmail').on('hide.bs.modal', function () {
        $('#tab-dadosAlerta').removeClass('active');
        $('#tab-hotlist').removeClass('active');
        $('.nav-tabs .nav-item').removeClass('active');
        $('#btnSalvarAlertasEmail').show();
    });
});

// REQUISIÇÕES

function getDadosAlertasEmail(callback, options) {

    if (options) {
        if (options.cliente || options.email) {
            showLoadingPesquisarButton();
            $("#loadingMessageAlertasEmail").hide();
        } else {
            showAlert('warning', 'Dados Insuficientes')
            resetPesquisarButton();
        }
    }

    if (typeof callback === "function") callback(null);
}

function salvarAlertasEmail() {
    let id = $('#idAlertasEmail').val();
    showLoadingSalvarButtonAlertasEmail();

    let route = Router + '/atualizarAlertasEmail';

    if (id == '0') {
        route = Router + '/adicionarAlertasEmail';
    }

    let id_cliente = $('#seguradoraAlertasEmail').val();
    let integra_css = $('#intregraCSSAlertasEmail').is(':checked') ? 1 : 0;
    let notifica_email = $('#notificaEmailAlertasEmail').is(':checked') ? 1 : 0;
    let notifica_tela_alerta = $('#notificaTelaAlertaAlertasEmail').is(':checked') ? 1 : 0;
    let emails = [];

    $('#tags').find('li').each(function () {
        const existingEmail = $(this).text().trim();
        emails.push(existingEmail);
    });

    let data = {
        id: id,
        id_cliente: id_cliente,
        integra_css: integra_css,
        notifica_email: notifica_email,
        notifica_tela_alerta: notifica_tela_alerta,
        emails: emails
    }

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                showAlert('success', 'Alerta salvo com sucesso!')
                $("#alertasEmail").modal('hide')
                atualizarTableAlertasEmail();
            } else if (data.status == 400 || data.status == 404) {
                if ('mensagem' in data.resultado) {
                    showAlert('warning', data['resultado']['mensagem'])
                } else {
                    showAlert('error', 'Erro ao enviar requisição');
                }
            } else {
                showAlert('error', 'Erro ao enviar requisição');
            }
            HideLoadingScreen();
            resetSalvarButtonAlertasEmail()
        },
        error: function (error) {
            showAlert('error', 'Erro ao enviar requisição');
            resetSalvarButtonAlertasEmail()
        }
    });
}

function atualizarTableAlertasEmail() {

    var searchOptions = null;

    searchOptions = {
        placa: $("#placaBusca").val(),
        cliente: $("#clienteBusca").val(),
    };

    if (searchOptions && (searchOptions.placa || searchOptions.cliente)) {
        getDadosAlertasEmail(function (error) {
            if (!error) {
                atualizarAgGridAlertasEmail(searchOptions);
            } else {
                atualizarAgGridAlertasEmail();
            }
        }, searchOptions)
    } else {
        getDadosAlertasEmail(function (error) {
            if (!error) {
                atualizarAgGridAlertasEmail();
            } else {
                atualizarAgGridAlertasEmail();
            }
        })
    }
}

function getAlertasEmailByID(id) {
    let route = Router + '/buscarAlertasEmailByID';
    return new Promise((resolve, reject) => {
        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    // Resolve a Promise com os dados se o status for 200
                    resolve(data.resultado);
                } else {
                    // Rejeita a Promise com uma mensagem de erro se o status não for 200
                    reject('Dados não encontrados');
                }
                HideLoadingScreen();
            },
            error: function (error) {
                // Rejeita a Promise com o erro
                reject(error.statusText || 'Erro ao realizar a requisição');
                HideLoadingScreen();
            }
        });
    });
}

function deleteAlertasEmail(id) {
    let route = Router + '/deletarAlertasEmail';

    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente remover o alerta?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar"
    }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        showAlert('success', 'Alerta removido com sucesso')
                        atualizarTableAlertasEmail();
                    } else if (data.status == 400) {
                        showAlert('warning', 'Não foi possível remover o alerta.')
                    } else {
                        showAlert('error', 'Erro ao remover o alerta')
                    }
                    HideLoadingScreen();
                },
                error: function (error) {
                    showAlert('error', 'Erro ao remover o alerta')
                    HideLoadingScreen();
                }
            });
        }
    });
}

async function abrirAlertasEmail(id, nomeCliente) {
    ShowLoadingScreen();
    limparAlertasEmail();

    if (id) {
        try {
            const resultado = await getAlertasEmailByID(id);
            preencherAlertasEmail(resultado, id, nomeCliente);
            $("#titleAlertasEmail").html('Editar Alerta');
            $('#tab-hotlist').show();
            $('#tab-coldlist').show();
            $('#btnAssociarHotlist').off('click').on('click', function () {
                abrirAssociacaoBlacklist(id, resultado.id_cliente);
            });
            $('#btnAssociarColdlist').off('click').on('click', function () {
                abrirAssociacaoWhitelist(id, resultado.id_cliente);
            });
        } catch (error) {
            showAlert('error', 'Erro ao abrir alerta!');
        }
    } else {
        HideLoadingScreen();
        $("#alertasEmail").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#titleAlertasEmail").html('Cadastrar Alerta');
        $('#seguradoraAlertasEmail').removeAttr('readonly');
        $('#tab-hotlist').hide();
        $('#tab-coldlist').hide();
    }
}


// Utilitários
function preencherAlertasEmail(dados, id, nomeCliente) {
    $('#idAlertasEmail').val(id);
    $('#intregraCSSAlertasEmail').prop("checked", (parseInt(dados['integra_css']) ? true : false));
    $('#notificaEmailAlertasEmail').prop("checked", (parseInt(dados['notifica_email']) ? true : false));
    $('#notificaTelaAlertaAlertasEmail').prop("checked", (parseInt(dados['notifica_tela_alerta']) ? true : false));

    var valorVerificar = parseInt(dados['id_cliente']);

    var seguradoraAlertasEmail = $('#seguradoraAlertasEmail');

    if (seguradoraAlertasEmail.find("option[value='" + valorVerificar + "']").length > 0) {
        seguradoraAlertasEmail.val(valorVerificar).trigger('change');
        $('#seguradoraAlertasEmail').attr('readonly', 'readonly');
        $("#alertasEmail").modal({
            backdrop: 'static',
            keyboard: false
        });
    } else {
        seguradoraAlertasEmail.append(`<option selected value="${valorVerificar}">${nomeCliente ? nomeCliente : 'Indefinido'}</option>`).trigger('change');
        $('#seguradoraAlertasEmail').attr('readonly', 'readonly');
        $("#alertasEmail").modal({
            backdrop: 'static',
            keyboard: false
        });;
    }
    var emails = dados['emails'].split(',');
    if (emails) {
        emails.forEach(function (email) {
            const tag = $('<li></li>');
            const tagContent = email.trim();
            tag.text(tagContent);
            tag.append('<button class="delete-button"><i class="fa fa-close"></i></button>');
            $('#tags').append(tag);
        });
    }

}

function limparAlertasEmail() {
    existingEmails.clear();
    $('#idAlertasEmail').val('0');
    $('#intregraCSSAlertasEmail').prop("checked", false);
    $('#notificaEmailAlertasEmail').prop("checked", false);
    $('#notificaTelaAlertaAlertasEmail').prop("checked", false);
    $('#tags').html('');
    $('#input-tag').val('');
    $("#seguradoraAlertasEmail").val('').trigger('change');
}

var AgGridAbaBlacklist;
function atualizarAgGridAbaBlacklist(options) {
    stopAgGRIDAbaBlacklist();
    var idAlerta = $('#idAlertasEmail').val();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarPlacasBlacklistByAlerta';
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idAlerta: idAlerta
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            showAlert('warning', data.message);
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
                        if (options) resetPesquisarButton();

                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        if (options) resetPesquisarButton();
                    },
                });

                $("#loadingMessage").hide();
                if (!options) resetPesquisarButton();
            },
        };
    }

    function getContextMenuItemsAbaBlacklist(params) {
        if (params && params.node && 'data' in params.node && 'id' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    // custom item
                    name: 'Desassociar',
                    action: () => {
                        desassociarPlacaAbaBlacklist(idAlerta, data.id)
                    },
                }
            ];
        } else {
            var result = [];
        }

        return result;
    }


    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Placa',
                field: 'placa',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Marca',
                field: 'marca',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Chassi',
                field: 'chassi',
                chartDataType: 'category',
                width: 245,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ações',
                width: 80,
                maxWidth: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "tableAbaBlacklist";
                    let dropdownId = "dropdown-menu-alertas-email-abablacklist" + data.id;
                    let buttonId = "dropdownMenuButtonAlertasEmail-abablacklist_" + data.id;

                    return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdown('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:desassociarPlacaAbaBlacklist(${idAlerta}, ${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Desassociar</a>
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
            resizable: false,
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
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: 6,
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        getContextMenuItems: getContextMenuItemsAbaBlacklist,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('id' in data && idAlerta) {
                    desassociarPlacaAbaBlacklist(idAlerta, data.id)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar a Hot List.')
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableAbaBlacklist');
    gridDiv.style.setProperty('height', '350px');

    new agGrid.Grid(gridDiv, gridOptions);
    //gridOptions.api.setRowData(dados);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
}

var AgGridAbaWhitelist;
function atualizarAgGridAbaWhitelist(options) {
    stopAgGRIDAbaWhitelist();
    var idAlerta = $('#idAlertasEmail').val();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarPlacasWhitelistByAlerta';
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idAlerta: idAlerta
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            showAlert('warning', data.message);
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
                        if (options) resetPesquisarButton();

                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        if (options) resetPesquisarButton();
                    },
                });

                $("#loadingMessage").hide();
                if (!options) resetPesquisarButton();
            },
        };
    }

    function getContextMenuItemsAbaWhitelist(params) {
        if (params && params.node && 'data' in params.node && 'id' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    // custom item
                    name: 'Desassociar',
                    action: () => {
                        desassociarPlacaAbaWhitelist(idAlerta, data.id)
                    },
                }
            ];
        } else {
            var result = [];
        }

        return result;
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Placa',
                field: 'placa',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Marca',
                field: 'marca',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Chassi',
                field: 'chassi',
                chartDataType: 'category',
                width: 245,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ações',
                width: 80,
                maxWidth: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "tableAbaWhitelist";
                    let dropdownId = "dropdown-menu-alertas-email-abawhitelist" + data.id;
                    let buttonId = "dropdownMenuButtonAlertasEmail-abawhitelist_" + data.id;

                    return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdown('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:desassociarPlacaAbaWhitelist(${idAlerta}, ${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Desassociar</a>
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
            resizable: false,
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
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: 6,
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        getContextMenuItems: getContextMenuItemsAbaWhitelist,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('id' in data && idAlerta) {
                    desassociarPlacaAbaWhitelist(idAlerta, data.id)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar a Cold List.')
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableAbaWhitelist');
    gridDiv.style.setProperty('height', '350px');

    new agGrid.Grid(gridDiv, gridOptions);
    //gridOptions.api.setRowData(dados);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
}

// AGGRID
var AgGridAlertasEmail;
function atualizarAgGridAlertasEmail(options) {
    stopAgGRIDAlertasEmail();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarAlertasEmailServerSide';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        cliente: options ? options.cliente : '',
                        email: options ? options.email : ''
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    // Verifica se o valor é null e substitui por uma string vazia
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }

                                    // Convert numeric values to 'Sim' or 'Não'
                                    if (chave === 'integraCss' || chave === 'notificaEmail' || chave === 'notificaTelaAlerta') {
                                        dados[i][chave] = dados[i][chave] == 1 ? 'Sim' : 'Não';
                                    }
                                }

                            }
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                        } else if (data && data.message) {
                            showAlert('warning', data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridAlertasEmail.gridOptions.api.showNoRowsOverlay();
                            AgGridAlertasEmail.gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridAlertasEmail.gridOptions.api.showNoRowsOverlay();
                            AgGridAlertasEmail.gridOptions.api.showNoRowsOverlay();
                        }
                        if (options) {
                            resetPesquisarButton();
                        } else {
                            resetLimparButton();
                        }

                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        AgGridAlertasEmail.gridOptions.api.showNoRowsOverlay();
                        if (options) {
                            resetPesquisarButton();
                        } else {
                            resetLimparButton();
                        }
                    },
                });
            },
        };
    }

    function getContextMenuItemsAlertasEmail(params) {
        if (params && params.node && 'data' in params.node && 'id' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    name: 'Editar',
                    action: () => {
                        abrirAlertasEmail(data.id, data.nomeCliente);
                    },
                },
                {
                    name: 'Associar Hot List',
                    action: () => {
                        abrirAssociacaoBlacklist(data.id, data.idCliente);
                    },
                },
                {
                    name: 'Desassociar Hot List',
                    action: () => {
                        abrirDesassociacaoBlacklist(data.id, data.idCliente);
                    },
                },
                {
                    name: 'Associar Cold List',
                    action: () => {
                        abrirAssociacaoWhitelist(data.id, data.idCliente);
                    },
                },
                {
                    name: 'Desassociar Cold List',
                    action: () => {
                        abrirDesassociacaoWhitelist(data.id, data.idCliente);
                    },
                },
                {
                    name: 'Remover',
                    action: () => {
                        deleteAlertasEmail(data.id);
                    },
                }
            ];
        } else {
            var result = [];
        }

        return result;
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'E-mails',
                field: 'emails',
                chartDataType: 'category',
                width: 300,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                chartDataType: 'category',
                width: 210,
                suppressSizeToFit: true
            },
            {
                headerName: 'Integra CSS (CEABS)',
                field: 'integraCss',
                chartDataType: 'category',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['integraCss'];
                    if (data == 'Sim') {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `
                    }
                }
            },
            {
                headerName: 'Notifica E-mail',
                field: 'notificaEmail',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['notificaEmail'];
                    if (data == 'Sim') {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `
                    }
                }
            },
            {
                headerName: 'Notifica Tela Alerta',
                field: 'notificaTelaAlerta',
                chartDataType: 'category',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['notificaTelaAlerta'];
                    if (data == 'Sim') {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="green"/></svg>
                        `;
                    } else {
                        return `
                        <svg width="18" height="18" style="display: block; margin: auto; position: relative; top: 50%; transform: translateY(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                        `
                    }
                }
            },
            {
                headerName: 'Qtd. Cold list',
                field: 'whiteListAssociadas',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Qtd. Hot list',
                field: 'blackListAssociadas',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 80,
                maxWidth: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "tableAlertasEmail";
                    let dropdownId = "dropdown-menu-alertas-email-" + data.id;
                    let buttonId = "dropdownMenuButtonAlertasEmail_" + data.id;

                    return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdown('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirAlertasEmail(${data.id}, '${data.nomeCliente}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirAssociacaoBlacklist(${data.id}, ${data.idCliente})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Associar Hot List</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirDesassociacaoBlacklist(${data.id}, ${data.idCliente})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Desassociar Hot List</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirAssociacaoWhitelist(${data.id}, ${data.idCliente})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Associar Cold List</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirDesassociacaoWhitelist(${data.id}, ${data.idCliente})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Desassociar Cold List</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:deleteAlertasEmail(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
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
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        domLayout: 'normal',
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-alertas-email').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        getContextMenuItems: getContextMenuItemsAlertasEmail,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('id' in data) {
                    abrirAlertasEmail(data.id, data.nomeCliente)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar o Alerta.')
                }
            }
        }
    };

    $('#select-quantidade-por-pagina-alertas-email').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-alertas-email').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableAlertasEmail');
    gridDiv.style.setProperty('height', '519px');

    AgGridAlertasEmail = new agGrid.Grid(gridDiv, gridOptions);
    //gridOptions.api.setRowData(dados);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesAlertasEmail(gridOptions);
}


// Visibilidade
function showLoadingSalvarButtonAlertasEmail() {
    $('#btnSalvarAlertasEmail').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonAlertasEmail() {
    $('#btnSalvarAlertasEmail').html('Salvar').attr('disabled', false);
}

function showLoadingButtonRemoverAlertaEmail() {
    $('#btnSalvarAssociacaoBlacklist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}


function stopAgGRIDAlertasEmail() {
    var gridDiv = document.querySelector('#tableAlertasEmail');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperAlertasEmail');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableAlertasEmail" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDAbaBlacklist() {
    var gridDiv = document.querySelector('#tableAbaBlacklist');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperAbaBlacklist');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableAbaBlacklist" class="ag-theme-alpine my-grid-abaBlacklist"></div>';
    }
}

function stopAgGRIDAbaWhitelist() {
    var gridDiv = document.querySelector('#tableAbaWhitelist');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperAbaWhitelist');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableAbaWhitelist" class="ag-theme-alpine my-grid-abaWhitelist"></div>';
    }
}