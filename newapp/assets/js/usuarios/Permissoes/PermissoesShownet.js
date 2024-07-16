var treePermissoes;
var localeText = AG_GRID_LOCALE_PT_BR;
var tablePermissoes, page, index, linhaTabela, tableCargos, tableModuloCargo, tableModuloCargoEdit;

$(document).ready(function() {
    treePermissoes = $("#permissoesCargo").treeMultiselect({});

    criarAgGrid();
    criarAgGridCargos();

    $("#modulo").select2({
		allowClear: true,
		language: "pt-BR",
		width: "100%",
        placeholder: lang.selecione_modulo
	});

    $("#prefixo").select2({
		allowClear: false,
		language: "pt-BR",
		width: "100%",
	});

    $("#coluna").select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $('#coluna').change(function() {
        let val = $(this).val();
        $('#nomePermissao').val('');
        $('#codPermissao').val('');

        if (val == "cod_permissao") {
            $('.buscaNome').hide();
            $('.buscaCodigo').show();
        } else {
            $('.buscaNome').show();
            $('.buscaCodigo').hide();
        }
    });

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('.menu-interno-link').click(function () {
        limparPesquisa();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        $('#search-input').val('').trigger('input');
        let searchOptions = {
            'nome': $('#nomePermissao').val(),
            'codPermissao': $('#codPermissao').val()
        }

        if (searchOptions.nome || searchOptions.codPermissao) {
            showLoadingPesquisarButton('Permissoes');
            getPermissoesAgGrid(() => {
                resetPesquisarButton('Permissoes');
            }, searchOptions);
        } else {
            showAlert('warning', 'Informe o parâmetro para realizar a busca!')
        }
    });

    $('#formBuscaCargo').submit(function (e) {
        e.preventDefault();
        $('#search-input-cargo').val('').trigger('input');
        let searchOptions = {
            'nome': $('#nomeCargo').val()
        }

        if (searchOptions.nome) {
            showLoadingPesquisarButton('Cargos');
            getCargosAgGrid(() => {
                resetPesquisarButton('Cargos');
            }, searchOptions);
        } else {
            showAlert('warning', 'Informe o parâmetro para realizar a busca!')
        }
    });

    $('#BtnLimparPermissoes').on('click', function (e) {
        showLoadingLimparButton('Permissoes');
        limparPesquisa();
        getPermissoesAgGrid(() => {
            resetLimparButton('Permissoes');
        });
    });

    $('#BtnLimparCargos').on('click', function (e) {
        showLoadingLimparButton('Cargos');
        limparPesquisa();
        getCargosAgGrid(() => {
            resetLimparButton('Cargos');
        });
    });

    $('#menu-permissoes').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-cargos').removeClass("selected");
            $('#card-permissao').show();
            $('#card-cargos').hide();
            $('#filtroBusca').show();
            $('#filtroBuscaCargo').hide();
            getPermissoesAgGrid();
        }
    });

    $('#menu-cargos').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-permissoes').removeClass("selected");
            $('#card-cargos').show();
            $('#card-permissao').hide();
            $('#filtroBuscaCargo').show();
            $('#filtroBusca').hide();
            getCargosAgGrid();
        }
    });

    $('#btnAddPermissoes').click(function () {
        resetModal();
        $('#modalCadPermissao').modal('show');
    });

    //Abre Modal Cadastro de Cargos
    $("#btnAddCargo").click(function() {
        clearErrors();
        resetModalCadCargo();

        //CARREGA AS PERMISSOES PARA SER ADICIONADAS AO CARGO
        loadPermissoes();
    });

    //CADASTRA UMA NOVA PERMISSAO
    $('#formPermissao').submit(function(e) {
        e.preventDefault();

        let botao = $('#btnNovaPermissao');
        let url = Router + "/cadastrarPermissao";
        var dataForm = $(this).serialize();

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: dataForm,
            beforeSend: function() {
                // Desabilita button e inicia spinner
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
            },
            success: function(callback) {
                if (callback.status == 200) {
                    $('#modalCadPermissao').modal('hide');
                    atualizarAgGridPermissoes();
                    showAlert('success', 'Permissão cadastrada com sucesso!');
                } else if (callback.status == 400 || callback.status == 404) {
                    if ("resultado" in callback && "mensagem" in callback.resultado) {
                        if (callback.resultado.mensagem.includes('Já existe')) {
                            showAlert('warning', 'Já existe permissão cadastrada com esses parâmetros!');
                        } else {
                            showAlert('warning', callback.resultado.mensagem);
                        }
                    } else {
                        showAlert('error', 'Não foi possível cadastrar a permissão! Tente novamente mais tarde.');
                    }
                } else {
                    showAlert('error', 'Não foi possível cadastrar a permissão! Tente novamente mais tarde.');
                }
            },
            error: function() {
                showAlert('error', 'Não foi possível cadastrar a permissão! Tente novamente mais tarde.');
                botao.attr('disabled', false).html(lang.salvar);
            },
            complete: function() {
                botao.attr('disabled', false).html(lang.salvar);
            }
        })

    });

    //FORMULARIO PARA CADASTRO DE CARGO
    $('#formCargo').submit(function(e) {
        e.preventDefault();

        var permisoes_cargo = $('#permissoesCargo').val();
        if (!permisoes_cargo.length) {
            showAlert('warning', lang.necessario_selecionar_permissoes);
        } else {
            var botao = $('#submit_cargo');
            var acao = botao.attr('data-acao');
            var dataForm = $(this).serialize();
            var url = Router + "/cadastrarCargo";

            if (acao == 'editar') {
                var id = botao.attr('data-id');
                url = Router + "/atualizarCargo/" + id;
            }

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: dataForm,
                beforeSend: function() {
                    // Desabilita button e inicia spinner
                    $('#submit_cargo').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
                },
                success: function(callback) {
                    if (callback.status == 200 || callback.status == 201) {
                        $('#modalCadCargo').modal('hide');
                        atualizarAgGridCargos();
                        if (acao == 'editar') {
                            showAlert('success', 'Cargo editado com sucesso!');
                        } else {
                            showAlert('success', 'Cargo cadastrado com sucesso!');
                        }
                    } else if (callback.status == 400 || callback.status == 404) {
                        if ("resultado" in callback && "mensagem" in callback.resultado) {
                            showAlert('warning', callback.resultado.mensagem);
                        } else {
                            if (acao == 'editar') {
                                showAlert('error', 'Não foi possível editar o cargo! Tente novamente mais tarde.');
                            } else {
                                showAlert('error', 'Não foi possível cadastrar o cargo! Tente novamente mais tarde.');
                            }
                        }
                    } else {
                        if (acao == 'editar') {
                            showAlert('error', 'Não foi possível editar o cargo! Tente novamente mais tarde.');
                        } else {
                            showAlert('error', 'Não foi possível cadastrar o cargo! Tente novamente mais tarde.');
                        }
                    }
                },
                error: function() {
                    if (acao == 'editar') {
                        showAlert('error', 'Não foi possível editar o cargo! Tente novamente mais tarde.');
                    } else {
                        showAlert('error', 'Não foi possível cadastrar o cargo! Tente novamente mais tarde.');
                    }
                },
                complete: function() {
                    $('#submit_cargo').attr('disabled', false).html(lang.salvar);
                    $('.novoCargo-alert').css('display', 'block');
                    //vai para o topo do modal para mostrar a mensagem ao usuario
                    $('#modalCadCargo').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            })
        }

    });

    $(document).on('change', '.checked_all_permissoes', function() {
        let checked = false;
        let form = $(this).data('form');

        if ($(this).prop('checked'))
            checked = true;

        $('form#formCargo input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', checked);
        });
    });


    $(document).on('change', '.checked_all_permissoes_edit', function() {
        let checked = false;
        let form = $(this).data('form');

        if ($(this).prop('checked'))
            checked = true;

        $('form#formEditCargo input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', checked);
        });
    });

    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = $(this).attr("data-tableId");
        abrirDropdown(dropdownId, buttonId, tableId);
    });
});

// Utilitarios
function limparPesquisa() {
    $('#coluna').val('nome').trigger('change');
    $('#nomePermissao').val('');
    $('#codPermissao').val('');
    $('#nomeCargo').val('');
    $('#search-input').val('').trigger('input');
    $('#search-input-cargo').val('').trigger('input');
}

function clearErrors() {
    $(".has-error").removeClass("has-error");
    $(".help-block").html("");
}

function atualizarAgGridCargos() {
    let searchOptions = {
        'nome': $('#nomeCargo').val()
    }

    getCargosAgGrid(null, searchOptions);
}

function atualizarAgGridPermissoes() {
    let searchOptions = {
        'nome': $('#nomePermissao').val(),
        'codPermissao': $('#codPermissao').val()
    }

    getPermissoesAgGrid(null, searchOptions);
}

function resetModalCadCargo() {
    $("#formCargo")[0].reset();
    $('#msgNovoCargo').html('');
    $('.novoCargo-alert').css('display', 'none');
    $('#tituloNovoCargo').text(lang.adicionar_cargo);
    $('#submit_cargo').attr('data-acao', 'novo');
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
            if (posDropdown > 60) {
                dropdown.css('top', `-${(posDropdown/2)}`);
            } else {
                dropdown.css('top', `-10%`);
            }
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(posDropdown - 60) - (diferenca) }px`);
        }
    }
}

//Abre Modal Edição de Cargos
function editarCargo(botaoId) {
    clearErrors();
    resetModalCadCargo();
    let botao = $('#' + botaoId);
    let id = botao.attr("data-id");
    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.carregando);
    $('#tituloNovoCargo').text(lang.editar_cargo + ' #' + id);
    $('#submit_cargo').attr('data-id', id);
    $('#submit_cargo').attr('data-acao', 'editar');
    $('#descricaoCargo').val(botao.attr('data-descricao'));
    botao.attr('disabled', false).html(lang.editar);

    //CARREGA AS PERMISSOES PARA SER ADICIONADAS AO CARGO
    loadPermissoes(id);
};

let menuAberto = false;
function expandirGrid() {
    menuAberto = !menuAberto;

    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

    if (menuAberto) {
        $('.img-expandir').attr("src", buttonShow);
        $('#conteudo-lateral').hide();
        $('#conteudo').removeClass('col-md-9');
        $('#conteudo').addClass('col-md-12');
    } else {
        $('.img-expandir').attr("src", buttonHide);
        $('#conteudo-lateral').show();
        $('#conteudo').css('margin-left', '0px');
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
}

function resetModal() {
    $('#formPermissao')[0].reset();
    $('#modulo').val('').trigger('change');
    $('#prefixo').val('out').trigger('change');
    $('.novaPermissao-alert').css('display', 'none');
    $('#msgNovaPermissao').html('');
}

// AJAX
function loadPermissoes(id_cargo = false) {
    $('#permissoesCargo').val('');
    var url = Router + '/listaTodasPermissoes';
    if (id_cargo) url = Router + '/listaOpcoesPermissoesCargo/' + id_cargo;
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        beforeSend: function() {
            ShowLoadingScreen();
        },
        success: function(callback) {
            if (callback.success) {
                $('#permissoesCargo').empty();
                $.each(callback.permissoes, function(i, option) {
                    $("#permissoesCargo").append(option);
                });

                treePermissoes[0].remove();

                treePermissoes = $("#permissoesCargo").treeMultiselect({
                    searchable: true,
                    startCollapsed: true
                });

                $('.auxiliary input.search').attr('placeholder', 'Digite para pesquisar...')

                $("#modalCadCargo").modal();
            } else {
                treePermissoes[0].remove();
            }
            HideLoadingScreen();
        },
        error: function() {
            showAlert('error', 'Não foi possível listar as permissões!')
            HideLoadingScreen();
        }
    })
}

function getPermissoesAgGrid(callback, searchOptions) {
    let router = Router + '/buscarPermissoesPesquisa';
    $.ajax({
        url: router,
        type: 'POST',
        dataType: 'json',
        data: searchOptions,
        beforeSend: function() {
            AgGrid.gridOptions.api.setRowData([]);
            AgGrid.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data && data.status == 200) {
                AgGrid.gridOptions.api.setRowData(data.resultado);
            } else if (data && data.status == 400 || data.status == 404) {
                if ("resultado" in data && "mensagem" in data.resultado) {
                    showAlert('warning', data.resultado.mensagem);
                } else {
                    showAlert('error', 'Não foi possível listar as permissões!');
                }
                AgGrid.gridOptions.api.setRowData([]);
            } else {
                showAlert('error', 'Não foi possível listar as permissões!');
                AgGrid.gridOptions.api.setRowData([]);
            }
            if( typeof callback == 'function') callback();
        },
        error: function () {
            showAlert('error', 'Não foi possível listar as permissões!');
            AgGrid.gridOptions.api.setRowData([]);
            if( typeof callback == 'function') callback();
        }
    });
}

function getCargosAgGrid(callback, searchOptions) {
    let router = Router + '/buscarCargosPesquisa';
    $.ajax({
        url: router,
        type: 'POST',
        dataType: 'json',
        data: searchOptions,
        beforeSend: function() {
            AgGridCargos.gridOptions.api.setRowData([]);
            AgGridCargos.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data && data.status == 200) {
                AgGridCargos.gridOptions.api.setRowData(data.resultado);
            } else if (data && data.status == 400 || data.status == 404) {
                if ("resultado" in data && "mensagem" in data.resultado) {
                    showAlert('warning', data.resultado.mensagem);
                } else {
                    showAlert('error', 'Não foi possível listar os cargos!');
                }
                AgGridCargos.gridOptions.api.setRowData([]);
            } else {
                showAlert('error', 'Não foi possível listar os cargos!');
                AgGridCargos.gridOptions.api.setRowData([]);
            }
            if( typeof callback == 'function') callback();
        },
        error: function () {
            showAlert('error', 'Não foi possível listar os cargos!');
            AgGridCargos.gridOptions.api.setRowData([]);
            if( typeof callback == 'function') callback();
        }
    });
}

function atualizarStatusPermissao(id, statusLabel) {
    if (id && statusLabel) {
        let status = (statusLabel == 'ativar' ? 1 : 0);
        let router = Router + '/alterarStatusPermissao';

        Swal.fire({
            title: "Atenção!",
            text: `Deseja realmente ${statusLabel} a permissão?`,
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: router,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id, 
                        status: status
                    },
                    beforeSend: function() {
                        ShowLoadingScreen();
                    },
                    success: function(data) {
                        if (data && data.status == 200) {
                            atualizarAgGridPermissoes();
                            showAlert('success', (statusLabel == 'ativar' ? 'Permissão ativada com sucesso.' : 'Permissão inativada com sucesso.'));
                        } else if (data && data.status == 400 || data.status == 404) {
                            if ("resultado" in data && "mensagem" in data.resultado) {
                                showAlert('warning', data.resultado.mensagem);
                            } else {
                                showAlert('error', 'Não foi possível alterar o status da permissão!');
                            }
                        } else {
                            showAlert('error', 'Não foi possível alterar o status da permissão!');
                        }
                        HideLoadingScreen();
                    },
                    error: function () {
                        showAlert('error', 'Não foi possível alterar o status da permissão!');
                        HideLoadingScreen();
                    }
                });
            }
        });
    } else {
        showAlert('warning', 'É preciso informar o id e o status da permissão para alterá-la.');
    }
}

function atualizarStatusCargo(id, statusLabel) {
    if (id && statusLabel) {
        let status = (statusLabel == 'ativar' ? 1 : 0);
        let router = Router + '/alterarStatusCargo';

        Swal.fire({
            title: "Atenção!",
            text: `Deseja realmente ${statusLabel} o cargo?`,
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: router,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id, 
                        status: status
                    },
                    beforeSend: function() {
                        ShowLoadingScreen();
                    },
                    success: function(data) {
                        if (data && data.status == 200) {
                            atualizarAgGridCargos();
                            showAlert('success', (statusLabel == 'ativar' ? 'Cargo ativado com sucesso.' : 'Cargo inativado com sucesso.'));
                        } else if (data && data.status == 400 || data.status == 404) {
                            if ("resultado" in data && "mensagem" in data.resultado) {
                                showAlert('warning', data.resultado.mensagem);
                            } else {
                                showAlert('error', 'Não foi possível alterar o status do cargo!');
                            }
                        } else {
                            showAlert('error', 'Não foi possível alterar o status do cargo!');
                        }
                        HideLoadingScreen();
                    },
                    error: function () {
                        showAlert('error', 'Não foi possível alterar o status do cargo!');
                        HideLoadingScreen();
                    }
                });
            }
        });
    } else {
        showAlert('warning', 'É preciso informar o id e o status do cargo para alterá-lo.');
    }
}

// AgGrid
var AgGrid;
function criarAgGrid() {
    stopAgGRID();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 100,
                suppressSizeToFit: true,
                sort: "desc"
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Código da Permissão',
                field: 'codPermissao',
                width: 280,
                suppressSizeToFit: true
            },
            {
                headerName: 'Módulo',
                field: 'modulo',
                width: 190,
                suppressSizeToFit: true
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 120,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    if (params.value === 'Ativo') {
                        return `<span class="label label-success">Ativo</span>`;
                    } else {
                        return `<span class="label label-default">Inativo</span>`;
                    }
                }
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                sortable: false,
                filter: false,
                resizable: false,
                suppressMenu: true,
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "table";
                    let dropdownId = "dropdown-menu" + data.id;
                    let buttonId = "dropdownMenuButton_" + data.id
                    let btnStatus;
                    if (data.id) {
                        if (data.status === "Ativo") {
                            btnStatus = `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:atualizarStatusPermissao('${data.id}', 'inativar')" style="cursor: pointer; color: black;">Inativar</a>
                                </div>
                            `;
                        } else {
                            btnStatus = `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:atualizarStatusPermissao('${data.id}', 'ativar')" style="cursor: pointer; color: black;">Ativar</a>
                                </div>
                            `;
                        }
                        return `
                            <div class="dropdown dropdown-table" data-tableId="${tableId}" style="position: relative;">
                                <button class="btn btn-dropdown dropdown-toggle" onclick=fecharDrop() type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    ${btnStatus}
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
            suppressMenu: false
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
        localeText: localeText,
        domLayout: 'normal',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>'
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '530px');
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    $('#search-input').off().on('input', function() {
        var searchInput = $(this).val();
        gridOptions.api.setQuickFilter(searchInput);
    });

    getPermissoesAgGrid();
    
    preencherExportacoes(gridOptions, "Relatório Permissões de Funcionários", "Permissoes", 'opcoes_exportacao');
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

var AgGridCargos ;
function criarAgGridCargos() {
    stopAgGRIDCargos();

    var gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 100,
                suppressSizeToFit: true,
                sort: "desc"
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 120,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    if (params.value === 'Ativo') {
                        return `<span class="label label-success">Ativo</span>`;
                    } else {
                        return `<span class="label label-default">Inativo</span>`;
                    }
                }
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                sortable: false,
                filter: false,
                resizable: false,
                suppressMenu: true,
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "tableCargos";
                    let dropdownId = "dropdown-menu-cargos" + data.id;
                    let buttonId = "dropdownMenuButtonCargos_" + data.id
                    let btnStatus;
                    if (data.id) {
                        if (data.status === "Ativo") {
                            btnStatus = `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:editarCargo('${'dropdownCargos' + data.id}')" id="${'dropdownCargos' + data.id}" data-id="${data.id}" data-descricao="${data.descricao}" style="cursor: pointer; color: black;">Editar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:atualizarStatusCargo('${data.id}', 'inativar')" style="cursor: pointer; color: black;">Inativar</a>
                                </div>
                            `;
                        } else {
                            btnStatus = `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:atualizarStatusCargo('${data.id}', 'ativar')" style="cursor: pointer; color: black;">Ativar</a>
                                </div>
                            `;
                        }
                        return `
                            <div class="dropdown dropdown-table" data-tableId="${tableId}" style="position: relative;">
                                <button class="btn btn-dropdown dropdown-toggle" onclick=fecharDrop() type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    ${btnStatus}
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
            suppressMenu: false
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
        localeText: localeText,
        domLayout: 'normal',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>'
    };

    var gridDiv = document.querySelector('#tableCargos');
    gridDiv.style.setProperty('height', '530px');
    AgGridCargos  = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-cargo').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-cargo').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    $('#search-input-cargo').off().on('input', function() {
        var searchInput = $(this).val();
        gridOptions.api.setQuickFilter(searchInput);
    });
    
    preencherExportacoes(gridOptions, "Relatório Cargos de Funcionários", "Cargos", 'opcoes_exportacao_cargo')
}

function stopAgGRIDCargos () {
    var gridDiv = document.querySelector('#tableCargos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperCargos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableCargos" class="ag-theme-alpine my-grid"></div>';
    }
}

// Visibiliade
function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function showLoadingPesquisarButton(menu) {
    $('#BtnPesquisar' + menu).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...').attr('disabled', true);
    $('#BtnLimpar' + menu).html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', true);
}

function resetPesquisarButton(menu) {
    $('#BtnPesquisar' + menu).html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
    $('#BtnLimpar' + menu).html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
}

function showLoadingLimparButton(menu) {
    $('#BtnLimpar' + menu).html('<i class="fa fa-spinner fa-spin"></i> Limpando...').attr('disabled', true);
    $('#BtnPesquisar' + menu).html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', true);
}

function resetLimparButton(menu) {
    $('#BtnLimpar' + menu).html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
    $('#BtnPesquisar' + menu).html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}