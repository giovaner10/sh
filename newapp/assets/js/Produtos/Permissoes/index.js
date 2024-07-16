var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function() {

    criarAgGrid();
    criarAgGridProdutos();
    criarAgGridPermissoesProduto();
    criarAgGridPermissoesProdutoEdit();
    limparPesquisa();
    preencherSelectLicencas();

    $('#permissao_modulo').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $('#permissao_tecnologia').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $('#permissao_modulo_edit').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $('#permissao_tecnologia_edit').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $("#coluna").select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});

    $("#colunaProdutos").select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "100%",
	});


    $("#moduloPermissao").select2({
		allowClear: true,
		language: "pt-BR",
		width: "100%",
        placeholder: lang.selecione_modulo
	});

    $("#tecnologiaPermissao").select2({
		allowClear: true,
		language: "pt-BR",
		width: "100%",
        placeholder: lang.selecione_tecnologia
	});

    $('#filter_modulo').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "130px",
	});

    $('#filter_tecnologia').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "130px",
	});

    $('#filter_modulo_edit').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "130px",
	});

    $('#filter_tecnologia_edit').select2({
		allowClear: false,
        minimumResultsForSearch: -1,
		language: "pt-BR",
		width: "130px",
	});

    $('#coluna').change(function() {
        let val = $(this).val();
        $('#descricaoPermissao').val('');
        $('#codigoPermissao').val('');
        $('#moduloPermissao').val('').trigger('change');
        $('#tecnologiaPermissao').val('').trigger('change');

        if (val == "codigo") {
            $('.buscaDescricao').hide();
            $('.buscaCodigo').show();
            $('.buscaModulo').hide();
            $('.buscaTecnologia').hide();
        } else if (val == 'modulo') {
            $('.buscaDescricao').hide();
            $('.buscaCodigo').hide();
            $('.buscaModulo').show();
            $('.buscaTecnologia').hide();
        } else if (val == 'tecnologia') {
            $('.buscaDescricao').hide();
            $('.buscaCodigo').hide();
            $('.buscaModulo').hide();
            $('.buscaTecnologia').show();
        } else {
            $('.buscaDescricao').show();
            $('.buscaCodigo').hide();
            $('.buscaModulo').hide();
            $('.buscaTecnologia').hide();
        }
    });

    $('#colunaProdutos').change(function() {
        let val = $(this).val();
        $('#nomeProdutos').val('');
        $('#descricaoProdutos').val('');
        $('#codigoProdutos').val('');

        if (val == "descricao") {
            $('.buscaNomeProdutos').hide();
            $('.buscaDescricaoProdutos').show();
            $('.buscaCodigoProdutos').hide();
        } else if (val == 'codigo') {
            $('.buscaNomeProdutos').hide();
            $('.buscaDescricaoProdutos').hide();
            $('.buscaCodigoProdutos').show();
        } else {
            $('.buscaNomeProdutos').show();
            $('.buscaDescricaoProdutos').hide();
            $('.buscaCodigoProdutos').hide();
        }
    });

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('.menu-interno-link').click(function () {
        limparPesquisa();
    });

    $('#menu-permissoes').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-produtos').removeClass("selected");
            $('#card-permissoes').show();
            $('#card-produtos').hide();
            $('#filtroBusca').show();
            $('#filtroBuscaProdutos').hide();
            getPermissoesAgGrid();
        }
    });

    $('#menu-produtos').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-permissoes').removeClass("selected");
            $('#card-produtos').show();
            $('#card-permissoes').hide();
            $('#filtroBusca').hide();
            $('#filtroBuscaProdutos').show();
            getProdutosAgGrid();
        }
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        $('#search-input').val('').trigger('input');
        let searchOptions = {
            'descricao': $('#descricaoPermissao').val(),
            'codigo': $('#codigoPermissao').val(),
            'modulo': $('#moduloPermissao').val() ?? '',
            'tecnologia': $('#tecnologiaPermissao').val() ?? ''
        }

        if (searchOptions.descricao || searchOptions.codigo || searchOptions.modulo || searchOptions.tecnologia) {
            showLoadingPesquisarButton('Permissoes');
            getPermissoesAgGrid(() => {
                resetPesquisarButton('Permissoes');
            }, searchOptions);
        } else {
            showAlert('warning', 'Informe algum parâmetro para realizar a busca!')
        }
    });

    $('#BtnLimparPermissoes').on('click', function (e) {
        showLoadingLimparButton('Permissoes');
        limparPesquisa();
        getPermissoesAgGrid(() => {
            resetLimparButton('Permissoes');
        });
    });

    $('#formBuscaProdutos').submit(function (e) {
        e.preventDefault();
        $('#search-input').val('').trigger('input');
        let searchOptions = {
            'nome': $('#nomeProdutos').val(),
            'descricao': $('#descricaoProdutos').val(),
            'codProduto': $('#codigoProdutos').val(),
        }

        if (searchOptions.nome || searchOptions.descricao || searchOptions.codProduto) {
            showLoadingPesquisarButton('Produtos');
            getProdutosAgGrid(() => {
                resetPesquisarButton('Produtos');
            }, searchOptions);
        } else {
            showAlert('warning', 'Informe algum parâmetro para realizar a busca!')
        }
    });

    $('#BtnLimparProdutos').on('click', function (e) {
        showLoadingLimparButton('Produtos');
        limparPesquisa();
        getProdutosAgGrid(() => {
            resetLimparButton('Produtos');
        });
    });

    $('#btnAddPermissoes').click(function () {
        $("#formPermissao input:text").val('');
        $("#formPermissao select").val('').trigger('change');
        $('#modalCadPermissao').modal('show');
    });

    $("#btnAddProdutos").click(function() {
        $('#filter_modulo').val('Todos').trigger('change');
        $('#filter_tecnologia').val('Todos').trigger('change');
        $('.select_licencas').val(null).trigger('change');
        $('#search-input-permissoes-produto').val('').trigger('input');

        
        $("#formProduto input:text").val('');
        $("#formProduto input:checkbox").prop('checked', false);

        let searchOptions = {
            status: 1
        };

        ShowLoadingScreen();
        getPermissoesProdutosAgGrid(function() {
            HideLoadingScreen();
            $('#modalCadProduto').modal('show');
        }, searchOptions);        
    });

    $('#filter_modulo').on('change', function() {
        let val = $(this).val();
        let valTec = $('#filter_tecnologia').val();
        let filterModel;

        if (valTec != 'Todos') {
            if (val != 'Todos') {
                filterModel = {
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    },
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valTec
                    }
                }
            } else {
                filterModel = {
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valTec
                    }
                }
            }
        } else {
            if (val != 'Todos') {
                filterModel = {
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    }
                }
            } else {
                filterModel = null;
            }
        }

        AgGridPermissoesProduto.gridOptions.api.setFilterModel(filterModel);
        AgGridPermissoesProduto.gridOptions.api.onFilterChanged();
    })

    $('#filter_tecnologia').on('change', function() {
        let val = $(this).val();
        let valMod = $('#filter_modulo').val();
        let filterModel;

        if (valMod != 'Todos') {
            if (val != 'Todos') {
                filterModel = {
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    },
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valMod
                    }
                }
            } else {
                filterModel = {
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valMod
                    }
                }
            }
        } else {
            if (val != 'Todos') {
                filterModel = {
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    }
                }
            } else {
                filterModel = null;
            }
        }

        AgGridPermissoesProduto.gridOptions.api.setFilterModel(filterModel);
        AgGridPermissoesProduto.gridOptions.api.onFilterChanged();
    })

    $('#filter_modulo_edit').on('change', function() {
        let val = $(this).val();
        let valTec = $('#filter_tecnologia_edit').val();
        let filterModel;

        if (valTec != 'Todos') {
            if (val != 'Todos') {
                filterModel = {
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    },
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valTec
                    }
                }
            } else {
                filterModel = {
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valTec
                    }
                }
            }
        } else {
            if (val != 'Todos') {
                filterModel = {
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    }
                }
            } else {
                filterModel = null;
            }
        }

        AgGridPermissoesProdutoEdit.gridOptions.api.setFilterModel(filterModel);
        AgGridPermissoesProdutoEdit.gridOptions.api.onFilterChanged();
    })

    $('#filter_tecnologia_edit').on('change', function() {
        let val = $(this).val();
        let valMod = $('#filter_modulo_edit').val();
        let filterModel;

        if (valMod != 'Todos') {
            if (val != 'Todos') {
                filterModel = {
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    },
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valMod
                    }
                }
            } else {
                filterModel = {
                    modulo: {
                        filterType: 'text',
                        type: 'contains',
                        filter: valMod
                    }
                }
            }
        } else {
            if (val != 'Todos') {
                filterModel = {
                    tecnologia: {
                        filterType: 'text',
                        type: 'contains',
                        filter: val
                    }
                }
            } else {
                filterModel = null;
            }
        }

        AgGridPermissoesProdutoEdit.gridOptions.api.setFilterModel(filterModel);
        AgGridPermissoesProdutoEdit.gridOptions.api.onFilterChanged();
    })

    $('#formPermissao').submit(function() {
        event.preventDefault();

        // Desabilita button e inicia spinner
        $('#submit_permissao').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

        let dados = [];
        data = $(this).serializeArray();
        data.forEach(permissao => {
            dados[permissao.name] = permissao.value;
        });

        $.ajax({
            url: Router + "/cadastrarPermissao",
            type: "POST",
            dataType: "json",
            data: {
                descricao: dados['permissao_nome'],
                modulo: dados['permissao_modulo'],
                tecnologia: dados['permissao_tecnologia'],
            },
            success: callback => {
                if (callback && callback.status == 200) {
                    showAlert('success', 'Cadastrado com sucesso!');
                    atualizarAgGridPermissoes();
                    $('#modalCadPermissao').modal('hide');

                    tabela_permissoes_produto.clear();
                    tabela_permissoes_produto_edit.clear();
                    tabela_permissoes_produto.ajax.reload();
                } else if (callback && "resultado" in callback && (callback.status == 400 || callback.status == 404)) {
                    if ("errors" in callback.resultado && callback.resultado.errors.length > 0) {
                        showAlert('warning', callback.resultado.errors[0]);
                    } else if ("mensagem" in callback.resultado) {
                        showAlert('warning', callback.resultado.mensagem);
                    } else {
                        showAlert('warning', 'Não foi possível cadastrar a permissão. Verifique os campos e tente novamente.');
                    }
                } else {
                    showAlert('error', 'Não foi possível cadastrar a permissão. Tente novamente!');
                }

                $('#submit_permissao').attr('disabled', false).html('Salvar');
            },
            error: function() {
                showAlert('error', 'Não foi possível cadastrar a permissão. Tente novamente!');
                $('#submit_permissao').attr('disabled', false).html('Salvar');
            }
        })

    });

    $(document).on('click', '.editarPermissao', function() {
        id_permissao = $(this).data('id');

        if (id_permissao && AgGrid.gridOptions.api.getRowNode(id_permissao)) {
            dados = AgGrid.gridOptions.api.getRowNode(id_permissao).data;

            $('#id_permissao_edit').val(dados["id"] ?? '');
            $('#permissao_nome_edit').val(dados["descricao"] ?? '');
            $('#permissao_modulo_edit').val(dados["modulo"] ?? '').trigger('change');
            $('#permissao_tecnologia_edit').val(dados["tecnologia"] ?? '').trigger('change');
            $('#permissao_codigo_edit').val(dados["codPermissao"] ?? '');

            $('#modalEditPermissao').modal('show');
        } else {
            showAlert('warning', 'Não foi possível obter os dados da permissão. Tente novamente!');
            return;
        }
    });

    $('#formPermissaoEdit').submit(function() {
        event.preventDefault();

        let dados = [];
        data = $(this).serializeArray();
        data.forEach(permissao => {
            dados[permissao.name] = permissao.value;
        });

        $('#submit_permissao_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

        $.ajax({
            url: Router + "/editarPermissao",
            type: "POST",
            dataType: "json",
            data: {
                id: dados['id_permissao_edit'],
                descricao: dados['permissao_nome_edit'],
                modulo: dados['permissao_modulo_edit'],
                tecnologia: dados['permissao_tecnologia_edit'],
                cod_permissao: dados['permissao_codigo_edit'],
            },
            success: callback => {
                if (callback && callback.status == 200) {
                    showAlert('success', 'Atualizado com sucesso!');
                    atualizarAgGridPermissoes();
                    $('#modalEditPermissao').modal('hide');

                    tabela_permissoes_produto.clear();
                    tabela_permissoes_produto_edit.clear();
                    tabela_permissoes_produto.ajax.reload();
                } else if (callback && "resultado" in callback && (callback.status == 400 || callback.status == 404)) {
                    if ("errors" in callback.resultado && callback.resultado.errors.length > 0) {
                        showAlert('warning', callback.resultado.errors[0]);
                    } else if ("mensagem" in callback.resultado) {
                        showAlert('warning', callback.resultado.mensagem);
                    } else {
                        showAlert('error', 'Não foi possível editar a permissão. Tente novamente mais tarde!'); 
                    }
                } else {
                    showAlert('error', 'Não foi possível editar a permissão. Tente novamente mais tarde!');
                }

                $('#submit_permissao_edit').attr('disabled', false).html('Salvar');
            },
            error: function() {
                showAlert('error', 'Não foi possível editar a permissão. Tente novamente mais tarde!');
                $('#submit_permissao_edit').attr('disabled', false).html('Salvar');
            }
        })

    });

    $('#formProduto').submit(function(event) {
        event.preventDefault();

        // Desabilita button e inicia spinner
        $('#submit_produto').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

        // let permissoes_produto = new Array();
        // tabela_permissoes_produto.$('.produto_permissoes:checked').each(function() {
        //     permissoes_produto.push(this.value);
        // });

        let permissoes_produto = new Array();
        AgGridPermissoesProduto.gridOptions.api.forEachNode(node => {
            if (!node.isSelected()) {
                return;
            }
            permissoes_produto.push(node.data.id);
        });

        console.log(permissoes_produto);

        let data = {
            produto_nome: $('#produto_nome').val()?.toUpperCase(),
            produto_descricao: $('#produto_descricao').val(),
            id_licenca: $('#id_licenca').val(),
            produto_permissoes: permissoes_produto.join(',')
        };

        $.ajax({
            url: Router + "/add_produto",
            type: "POST",
            dataType: "json",
            data: data,
            success: callback => {
                if (callback.status == true) {
                    showAlert('success', 'Cadastrado com sucesso!');
                    atualizarAgGridProdutos();
                    $('#modalCadProduto').modal('hide');
                } else {
                    showAlert('warning', callback.msg);
                }

                $('#submit_produto').attr('disabled', false).html('Salvar');
            },
            error: function() {
                showAlert('error', 'Não foi possível cadastrar o produto no momento. Tente novamente mais tarde!');
                $('#submit_produto').attr('disabled', false).html('Salvar');
            }
        })

    });

    $(document).on('click', '.editarProduto', function() {
        id_row = $(this).data('id');

        if (id_row && AgGridProdutos.gridOptions.api.getRowNode(id_row)) {
            dados = AgGridProdutos.gridOptions.api.getRowNode(id_row).data;

            $('#id_produto_edit').val(dados['id'] ?? '');
            $('#produto_nome_edit').val(dados['nome'] ?? '');
            $('#produto_descricao_edit').val(dados['descricao'] ?? '');
            $('#id_licenca_edit').val(dados['idLicenca'] ?? '').trigger('change');

            $('#filter_modulo_edit').val('Todos').trigger('change');
            $('#filter_tecnologia_edit').val('Todos').trigger('change');
            $('#search-input-permissoes-produto-edit').val('').trigger('input');

            let searchOptions = {
                status: 1
            };

            ShowLoadingScreen();
            getPermissoesProdutosEditAgGrid(function() {
                $.ajax({
                    url: Router + "/get_permissoes_produto",
                    type: "GET",
                    dataType: "json",
                    data: {
                        id_produto: dados['id']
                    },
                    beforeSend: function () {
                        ShowLoadingScreen();
                    },
                    success: callback => {
                        if (callback.status == true) {
                            $.each(callback.permissoes, function(key, permissao) {
                                if (permissao.id_permissao) {
                                    let rowNode = AgGridPermissoesProdutoEdit.gridOptions.api.getRowNode(permissao.id_permissao);

                                    if (rowNode) {
                                        rowNode.setSelected(true);
                                    }
                                }
                            });
                        } else {
                            showAlert('warning', callback.msg);
                        }
        
                        $('#modalEditProduto').modal('show');
                        HideLoadingScreen();
                    },
                    error: function() {
                        showAlert('error', "Não foi possível abrir a edição de produto. Tente novamente!");
                        HideLoadingScreen();
                    }
                });
            }, searchOptions);

        } else {
            showAlert('warning', 'Não foi possível obter os dados do produto. Tente novamente!');
            return;
        }

    });

    $('#formProdutoEdit').submit(function(event) {
        event.preventDefault();
        Swal.fire({
            title: "Atenção!",
            text: `Editar as permissões deste produto alterará as permissões de todos os clientes que o possuam. Continuar operação?`,
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#submit_produto_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

                dados = $(this).serialize();

                let permissoes_produto_edit = new Array();
                AgGridPermissoesProdutoEdit.gridOptions.api.forEachNode(node => {
                    if (!node.isSelected()) {
                        return;
                    }
                    permissoes_produto_edit.push(node.data.id);
                });

                let data = {
                    id_produto_edit: $('#id_produto_edit').val(),
                    produto_nome_edit: $('#produto_nome_edit').val()?.toUpperCase(),
                    produto_descricao_edit: $('#produto_descricao_edit').val(),
                    id_licenca_edit: $('#id_licenca_edit').val(),
                    produto_permissoes_edit: permissoes_produto_edit.join(',')
                };

                $.ajax({
                    url: Router + "/edit_produtos",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: callback => {
                        if (callback.status == true) {
                            atualizarAgGridProdutos();
                            $('#modalEditProduto').modal('hide');
                            showAlert('success', callback.mensagem);
                        } else {
                            showAlert('warning', callback.mensagem);
                        }
                        $('#submit_produto_edit').attr('disabled', false).html('Salvar');
                    },
                    error: function() {
                        showAlert('error', lang.erro_edicao_produto);
                        $('#submit_produto_edit').attr('disabled', false).html('Salvar');
                    }
                })
            } else {
                return false;
            }
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
    $('#coluna').val('descricao').trigger('change');
    $('#descricaoPermissao').val('');
    $('#codigoPermissao').val('');
    $('#moduloPermissao').val('').trigger('change');
    $('#tecnologiaPermissao').val('').trigger('change');
    $('#colunaProdutos').val('nome').trigger('change');
    $('#nomeProdutos').val('');
    $('#descricaoProdutos').val('');
    $('#codigoProdutos').val('');
    $('#search-input').val('').trigger('input');
    $('#search-input-produtos').val('').trigger('input');
}

function atualizarAgGridProdutos() {
    let searchOptions = {
        'nome': $('#nomeProdutos').val(),
        'descricao': $('#descricaoProdutos').val(),
        'codProduto': $('#codigoProdutos').val(),
    }

    getProdutosAgGrid(null, searchOptions);
}

function atualizarAgGridPermissoes() {
    let searchOptions = {
        'descricao': $('#descricaoPermissao').val(),
        'codigo': $('#codigoPermissao').val(),
        'modulo': $('#moduloPermissao').val() ?? '',
        'tecnologia': $('#tecnologiaPermissao').val() ?? ''
    }

    getPermissoesAgGrid(null, searchOptions);
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

// AJAX
async function preencherSelectLicencas() {
    const select = $('.select_licencas');

    //instancia o select2
    select.select2({
        placeholder: lang.selecione_licenca,
        allowClear: true,
        width: '100%'
    });

    // busca os dados de licencas para preencher o select
    return await fetch(SiteURL + '/vendasgestor/listar_licencas_produtos')
        .then(response => response.json())
        .then(dados => {
            let options = `<option value="" selected disabled>${lang.selecione_licenca}</option>`;
            for (const dado of dados) {
                options += `<option value="${dado.id_licenca}">${dado.nome}</option>`;
            }

            select.html(options)
                .removeAttr('disabled')
                .trigger("change");
        })
        .catch(error => {});
}

function getPermissoesAgGrid(callback, searchOptions) {
    let router = Router + '/buscarPermissoes';
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
                var dados = data.resultado.cadPermissoes;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null) {
                            dados[i][chave] = '';
                        }
                        if (chave === 'status') {
                            if (dados[i][chave] == '1') {
                                dados[i][chave] = 'Ativo';
                            } else {
                                dados[i][chave] = 'Inativo';
                            }
                        }
                    }
                }
                AgGrid.gridOptions.api.setRowData(dados);
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

function getPermissoesProdutosAgGrid(callback, searchOptions) {
    let router = Router + '/buscarPermissoes';
    $.ajax({
        url: router,
        type: 'POST',
        dataType: 'json',
        data: searchOptions,
        beforeSend: function() {
            AgGridPermissoesProduto.gridOptions.api.setRowData([]);
            AgGridPermissoesProduto.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data && data.status == 200) {
                var dados = data.resultado.cadPermissoes;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null) {
                            dados[i][chave] = '';
                        }
                        if (chave === 'status') {
                            if (dados[i][chave] == '1') {
                                dados[i][chave] = 'Ativo';
                            } else {
                                dados[i][chave] = 'Inativo';
                            }
                        }
                    }
                }
                AgGridPermissoesProduto.gridOptions.api.setRowData(dados);
                AgGridPermissoesProduto.gridOptions.columnApi.applyColumnState({
                    defaultState: { sort: null },
                });
            } else if (data && data.status == 400 || data.status == 404) {
                if ("resultado" in data && "mensagem" in data.resultado) {
                    showAlert('warning', data.resultado.mensagem);
                } else {
                    showAlert('error', 'Não foi possível listar as permissões do produto!');
                }
                AgGridPermissoesProduto.gridOptions.api.setRowData([]);
            } else {
                showAlert('error', 'Não foi possível listar as permissões do produto!');
                AgGridPermissoesProduto.gridOptions.api.setRowData([]);
            }
            if( typeof callback == 'function') callback();
        },
        error: function () {
            showAlert('error', 'Não foi possível listar as permissões do produto!');
            AgGridPermissoesProduto.gridOptions.api.setRowData([]);
            if( typeof callback == 'function') callback();
        }
    });
}

function getPermissoesProdutosEditAgGrid(callback, searchOptions) {
    let router = Router + '/buscarPermissoes';
    $.ajax({
        url: router,
        type: 'POST',
        dataType: 'json',
        data: searchOptions,
        beforeSend: function() {
            AgGridPermissoesProdutoEdit.gridOptions.api.setRowData([]);
            AgGridPermissoesProdutoEdit.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data && data.status == 200) {
                var dados = data.resultado.cadPermissoes;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null) {
                            dados[i][chave] = '';
                        }
                        if (chave === 'status') {
                            if (dados[i][chave] == '1') {
                                dados[i][chave] = 'Ativo';
                            } else {
                                dados[i][chave] = 'Inativo';
                            }
                        }
                    }
                }
                AgGridPermissoesProdutoEdit.gridOptions.api.setRowData(dados);
                AgGridPermissoesProdutoEdit.gridOptions.columnApi.applyColumnState({
                    defaultState: { sort: null },
                });
            } else if (data && data.status == 400 || data.status == 404) {
                if ("resultado" in data && "mensagem" in data.resultado) {
                    showAlert('warning', data.resultado.mensagem);
                } else {
                    showAlert('error', 'Não foi possível listar as permissões do produto!');
                }
                AgGridPermissoesProdutoEdit.gridOptions.api.setRowData([]);
            } else {
                showAlert('error', 'Não foi possível listar as permissões do produto!');
                AgGridPermissoesProdutoEdit.gridOptions.api.setRowData([]);
            }
            if( typeof callback == 'function') callback();
        },
        error: function () {
            showAlert('error', 'Não foi possível listar as permissões do produto!');
            AgGridPermissoesProdutoEdit.gridOptions.api.setRowData([]);
            if( typeof callback == 'function') callback();
        }
    });
}

function getProdutosAgGrid(callback, searchOptions) {
    let router = Router + '/buscarProdutos';
    $.ajax({
        url: router,
        type: 'POST',
        dataType: 'json',
        data: searchOptions,
        beforeSend: function() {
            AgGridProdutos.gridOptions.api.setRowData([]);
            AgGridProdutos.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data && data.status == 200) {
                var dados = data.resultado.cadProdutos;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null) {
                            dados[i][chave] = '';
                        }
                        if (chave === 'status') {
                            if (dados[i][chave] == '1') {
                                dados[i][chave] = 'Ativo';
                            } else {
                                dados[i][chave] = 'Inativo';
                            }
                        }
                    }
                }
                AgGridProdutos.gridOptions.api.setRowData(dados);
            } else if (data && data.status == 400 || data.status == 404) {
                if ("resultado" in data && "mensagem" in data.resultado) {
                    showAlert('warning', data.resultado.mensagem);
                } else {
                    showAlert('error', 'Não foi possível listar os produtos!');
                }
                AgGridProdutos.gridOptions.api.setRowData([]);
            } else {
                showAlert('error', 'Não foi possível listar os produtos!');
                AgGridProdutos.gridOptions.api.setRowData([]);
            }
            if( typeof callback == 'function') callback();
        },
        error: function () {
            showAlert('error', 'Não foi possível listar os produtos!');
            AgGridProdutos.gridOptions.api.setRowData([]);
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

function atualizarStatusProduto(id, statusLabel) {
    if (id && statusLabel) {
        let status = (statusLabel == 'ativar' ? 1 : 0);
        let router = Router + '/alterarStatusProduto';

        Swal.fire({
            title: "Atenção!",
            text: `Deseja realmente ${statusLabel} o produto?`,
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
                            atualizarAgGridProdutos();
                            showAlert('success', (statusLabel == 'ativar' ? 'Produto ativado com sucesso.' : 'Produto inativado com sucesso.'));
                        } else if (data && data.status == 400 || data.status == 404) {
                            if ("resultado" in data && "mensagem" in data.resultado) {
                                showAlert('warning', data.resultado.mensagem);
                            } else {
                                showAlert('error', 'Não foi possível alterar o status do produto!');
                            }
                        } else {
                            showAlert('error', 'Não foi possível alterar o status do produto!');
                        }
                        HideLoadingScreen();
                    },
                    error: function () {
                        showAlert('error', 'Não foi possível alterar o status do produto!');
                        HideLoadingScreen();
                    }
                });
            }
        });
    } else {
        showAlert('warning', 'É preciso informar o id e o status do produto para alterá-lo.');
    }
}

// Tabelas
var tabela_permissoes_produto_edit = $('#tabela_permissoes_produto_edit').DataTable({
    order: [1, 'asc'],
    searching: true,
    paging: false,
    info: false,
    "language": {
        "decimal": "",
        "emptyTable": "Nenhum registro encontrado",
        "info": "Registro _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "0 Registros",
        "infoFiltered": "(Filtrados de _MAX_ registros)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Exibir: _MENU_",
        "loadingRecords": "Carregando...",
        "processing": "Processando...",
        "search": "Pesquisar: ",
        "zeroRecords": "Nenhum registro encontrado",
        "paginate": {
            "first": "Anterior",
            "last": "Próxima",
            "next": "Próxima",
            "previous": "Anterior"
        }
    }
});

var search_estoque_edit = document.createRange().createContextualFragment(`
    <label style="margin:5px">
        Módulo
        <select id="filter_modulo_edit" class="filter_produtos">
            <option value="Todos">Todos</option> 
            <option value="Atendimento">Atendimento</option> 
            <option value="Cadastro">Cadastro</option> 
            <option value="Comando">Comando</option> 
            <option value="Configuração">Configuração</option> 
            <option value="Monitorados">Monitorados</option> 
            <option value="Relatório">Relatório</option>
            <option value="Notificação">Notificação</option> 
        </select>
    </label>
    <label style="margin:5px">
        Tecnologia
        <select id="filter_tecnologia_edit" class="filter_produtos">
            <option value="Todos">Todos</option> 
            <option value="Omnilink">Omnilink</option> 
            <option value="Omniweb">Omniweb</option> 
            <option value="Omnifrota">Omnifrota</option> 
        </select>
    </label>`);

$('#tabela_permissoes_produto_edit_filter').prepend(search_estoque_edit);
$('#tabela_permissoes_produto_edit_filter').css('left', '60%');
$('#tabela_permissoes_produto_edit_filter').css('width', '100%');

$("#filter_modulo_edit").change(function() {
    filter_modulo = $('#filter_modulo_edit').val();

    if (filter_modulo == 'Todos') {
        tabela_permissoes_produto_edit.columns(2).search('', true, false).draw();
    } else {
        tabela_permissoes_produto_edit.columns(2).search(filter_modulo).draw();
    }
});

$("#filter_tecnologia_edit").change(function() {
    filter_tecnologia = $('#filter_tecnologia_edit').val();

    if (filter_tecnologia == 'Todos') {
        tabela_permissoes_produto_edit.columns(3).search('', true, false).draw();
    } else {
        tabela_permissoes_produto_edit.columns(3).search(filter_tecnologia).draw();
    }
});


var tabela_permissoes_produto = $('#tabela_permissoes_produto').DataTable({
    ajax: {
        url: Router + "/ajax_permissoes/1",
        success: function(callback) {
            tabela_permissoes_produto_edit.clear();
            tabela_permissoes_produto_edit.rows.add(callback.data).draw();
            tabela_permissoes_produto.clear();
            tabela_permissoes_produto.rows.add(callback.data).draw();
        }
    },
    order: [1, 'asc'],
    searching: true,
    paging: false,
    info: false,
    "language": {
        "decimal": "",
        "emptyTable": "Nenhum registro encontrado",
        "info": "Registro _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "0 Registros",
        "infoFiltered": "(Filtrados de _MAX_ registros)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Exibir: _MENU_",
        "loadingRecords": "Carregando...",
        "processing": "Processando...",
        "search": "Pesquisar: ",
        "zeroRecords": "Nenhum registro encontrado",
        "paginate": {
            "first": "Anterior",
            "last": "Próxima",
            "next": "Próxima",
            "previous": "Anterior"
        }
    }
});

var search_estoque = document.createRange().createContextualFragment(`  
    <label style="margin:5px">
        Módulo
        <select id="filter_modulo" class="filter_produtos">
            <option value="Todos">Todos</option> 
            <option value="Atendimento">Atendimento</option> 
            <option value="Cadastro">Cadastro</option> 
            <option value="Comando">Comando</option> 
            <option value="Configuração">Configuração</option> 
            <option value="Monitorados">Monitorados</option> 
            <option value="Relatório">Relatório</option>
            <option value="Notificação">Notificação</option> 
        </select>
    </label>
    <label style="margin:5px">
        Tecnologia
        <select id="filter_tecnologia" class="filter_produtos">
            <option value="Todos">Todos</option> 
            <option value="Omnilink">Omnilink</option> 
            <option value="Omniweb">Omniweb</option> 
            <option value="Omnifrota">Omnifrota</option> 
        </select>
    </label>`);

$('#tabela_permissoes_produto_filter').prepend(search_estoque);
$('#tabela_permissoes_produto_filter').css('left', '60%');
$('#tabela_permissoes_produto_filter').css('width', '100%');

$("#filter_modulo").change(function() {
    filter_modulo = $('#filter_modulo').val();

    if (filter_modulo == 'Todos') {
        tabela_permissoes_produto.columns(2).search('', true, false).draw();
    } else {
        tabela_permissoes_produto.columns(2).search(filter_modulo).draw();
    }

});

$("#filter_tecnologia").change(function() {
    filter_tecnologia = $('#filter_tecnologia').val();

    if (filter_tecnologia == 'Todos') {
        tabela_permissoes_produto.columns(3).search('', true, false).draw();
    } else {
        tabela_permissoes_produto.columns(3).search(filter_tecnologia).draw();
    }
});

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
                headerName: 'Tecnologia',
                field: 'tecnologia',
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
                                    <a class="editarPermissao" data-id="${data.id}" style="cursor: pointer; color: black;">Editar</a>
                                </div>
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
        getRowId: (params) => params.data.id,
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        localeText: localeText,
        domLayout: 'normal',
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

    $('#search-input').off().on('input', function() {
        var searchInput = $(this).val();
        gridOptions.api.setQuickFilter(searchInput);
    });

    getPermissoesAgGrid();
    
    preencherExportacoes(gridOptions, "Relatório Permissões (Gestor)", "Permissoes", 'opcoes_exportacao');
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

var AgGridPermissoesProduto;
function criarAgGridPermissoesProduto() {
    stopAgGRIDPermissoesProduto();

    var gridOptions = {
        columnDefs: [
            {
                pinned: 'left',
                width: 90,
                cellClass: 'centralizado',
                headerCheckboxSelection: true,
                checkboxSelection: true,
                suppressMenu: true,
                sortable: true,
                filter: false,
                comparator: function (valueA, valueB, nodeA, nodeB, isInverted) {
                    return (nodeA.isSelected() === true ? 1 : 0) - (nodeB.isSelected() === true ? 1 : 0);
                },
                resizable: false
            },
            {
                headerName: 'ID',
                field: 'id',
                width: 100,
                suppressSizeToFit: true,
                hide: true,
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
                filter: "agTextColumnFilter",
                suppressSizeToFit: true
            },
            {
                headerName: 'Tecnologia',
                field: 'tecnologia',
                width: 190,
                filter: "agTextColumnFilter",
                suppressSizeToFit: true
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
        paginationPageSize: 6,
        localeText: localeText,
        domLayout: 'normal',
        rowSelection: "multiple",
        suppressRowClickSelection: true,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
    };

    var gridDiv = document.querySelector('#tablePermissoesProduto');
    gridDiv.style.setProperty('height', '350px');
    AgGridPermissoesProduto = new agGrid.Grid(gridDiv, gridOptions);

    $('#search-input-permissoes-produto').off().on('input', function() {
        var searchInput = $(this).val();
        gridOptions.api.setQuickFilter(searchInput);
    });
}

function stopAgGRIDPermissoesProduto() {
    var gridDiv = document.querySelector('#tablePermissoesProduto');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperPermissoesProduto');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tablePermissoesProduto" class="ag-theme-alpine my-grid"></div>';
    }
}

var AgGridPermissoesProdutoEdit;
function criarAgGridPermissoesProdutoEdit() {
    stopAgGRIDPermissoesProdutoEdit();

    var gridOptions = {
        columnDefs: [
            {
                pinned: 'left',
                width: 90,
                cellClass: 'centralizado',
                headerCheckboxSelection: true,
                checkboxSelection: true,
                suppressMenu: true,
                sortable: true,
                filter: false,
                comparator: function (valueA, valueB, nodeA, nodeB, isInverted) {
                    return (nodeA.isSelected() === true ? 1 : 0) - (nodeB.isSelected() === true ? 1 : 0);
                },
                resizable: false
            },
            {
                headerName: 'ID',
                field: 'id',
                width: 100,
                suppressSizeToFit: true,
                hide: true,
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
                filter: "agTextColumnFilter",
                suppressSizeToFit: true
            },
            {
                headerName: 'Tecnologia',
                field: 'tecnologia',
                width: 190,
                filter: "agTextColumnFilter",
                suppressSizeToFit: true
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
        paginationPageSize: 6,
        localeText: localeText,
        domLayout: 'normal',
        getRowId: (params) => params.data.id,
        rowSelection: "multiple",
        suppressRowClickSelection: true,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
    };

    var gridDiv = document.querySelector('#tablePermissoesProdutoEdit');
    gridDiv.style.setProperty('height', '350px');
    AgGridPermissoesProdutoEdit = new agGrid.Grid(gridDiv, gridOptions);

    $('#search-input-permissoes-produto-edit').off().on('input', function() {
        var searchInput = $(this).val();
        gridOptions.api.setQuickFilter(searchInput);
    });
}

function stopAgGRIDPermissoesProdutoEdit() {
    var gridDiv = document.querySelector('#tablePermissoesProdutoEdit');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperPermissoesProdutoEdit');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tablePermissoesProdutoEdit" class="ag-theme-alpine my-grid"></div>';
    }
}

var AgGridProdutos ;
function criarAgGridProdutos() {
    stopAgGRIDProdutos();

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
                headerName: 'Nome',
                field: 'nome',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                minWidth: 300,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Código do Produto',
                field: 'codigoProduto',
                width: 280,
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
                    let tableId = "tableProdutos";
                    let dropdownId = "dropdown-menu-produtos" + data.id;
                    let buttonId = "dropdownMenuButtonProdutos_" + data.id
                    let btnStatus;
                    if (data.id) {
                        if (data.status === "Ativo") {
                            btnStatus = `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a class="editarProduto" data-id="${data.id}" data-idProduto="${data.id}" style="cursor: pointer; color: black;">Editar</a>
                                </div>
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:atualizarStatusProduto('${data.id}', 'inativar')" style="cursor: pointer; color: black;">Inativar</a>
                                </div>
                            `;
                        } else {
                            btnStatus = `
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:atualizarStatusProduto('${data.id}', 'ativar')" style="cursor: pointer; color: black;">Ativar</a>
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
        getRowId: (params) => params.data.id,
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
        localeText: localeText,
        domLayout: 'normal',
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
    };

    var gridDiv = document.querySelector('#tableProdutos');
    gridDiv.style.setProperty('height', '530px');
    AgGridProdutos  = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-produtos').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-produtos').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    $('#search-input-produtos').off().on('input', function() {
        var searchInput = $(this).val();
        gridOptions.api.setQuickFilter(searchInput);
    });
    
    preencherExportacoes(gridOptions, "Relatório Produtos", "Produtos", 'opcoes_exportacao_produtos')
}

function stopAgGRIDProdutos () {
    var gridDiv = document.querySelector('#tableProdutos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperProdutos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableProdutos" class="ag-theme-alpine my-grid"></div>';
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