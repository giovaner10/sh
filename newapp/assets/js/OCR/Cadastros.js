var idClienteDoc;
var cliente = '';
var email = '';
var statusBusca;
var statusProcessamento = '';
var dataInicial = '';
var dataFinal = '';
var existingEmails;
var localeText = AG_GRID_LOCALE_PT_BR;
var blacklistTo50 = true;
var withelistTo50 = true;

$(document).ready(function() {
    // CARREGAR SEGURADORAS
    buscarSeguradoras();

    $('#statusBusca').select2({
        allowClear: false,
        language: "pt-BR",
        minimumResultsForSearch: -1
    })

    //BOTÃO DE EXPANDIR
    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    // FILTRO
    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            placa: $("#placaBusca").val(),
            cliente: $("#clienteBusca").val(),
            email: $("#emailBusca").val(),
            status: $("#statusBusca").val(),
            statusProcessamento: $('#statusProcessamento').val(),
            dataInicial: $("#dataInicial").val(),
            dataFinal: $("#dataFinal").val()
        };

        email = $("#emailBusca").val();
        cliente = $("#clienteBusca").val();
        placa = $("#placaBusca").val();
        statusBusca = $("#statusBusca").val();
        statusProcessamento = $('#statusProcessamento').val();
        dataInicial = new Date($("#dataInicial").val());
        dataFinal = new Date($("#dataFinal").val());

        var blacklistVisivel = $('#menu-blacklist').hasClass("selected");
        var alertasEmailVisivel = $('#menu-alertas-email').hasClass("selected");
        var whitelistVisivel = $('#menu-whitelist').hasClass("selected");
        var processamentoVisivel = $('#menu-processamento').hasClass("selected");

        if (blacklistVisivel) {
            if (!cliente && !placa && !statusBusca) {
                resetPesquisarButton();
                showAlert('warning', "Preencha algum dos campos de busca!");
                return;
            }

            atualizarAgGridBlackList(searchOptions);
        } else if (whitelistVisivel) {
            validarDadosWhitelist(function (error) {
                if (!error) {
                    atualizarAgGridWhitelist(searchOptions);
                } else {
                    atualizarAgGridWhitelist()
                }
            }, searchOptions);
        } else if (alertasEmailVisivel) {
            if (!cliente && (!email)) {
                resetPesquisarButton();
                showAlert('warning', "Preencha algum dos campos de busca!");
                return;
            }

            if (email && !validateEmail(email)) {
                resetPesquisarButton();
                showAlert('warning', "E-mail não é valido para busca!");
                return;
            }

            $('#emptyMessageAlertasEmail').hide();
            atualizarAgGridAlertasEmail(searchOptions);
        } else if (processamentoVisivel) {

            if (searchOptions.dataInicial && !searchOptions.dataFinal) {
                showAlert('warning', 'Informe uma Data Final para fazer a busca!')
                resetPesquisarButton();
                return;
            } else if (searchOptions.dataFinal && !searchOptions.dataInicial) {
                showAlert('warning', 'Informe uma Data Inicial para fazer a busca!')
                resetPesquisarButton();
                return;
            } else if ((searchOptions.dataInicial && searchOptions.dataFinal) && (dataInicial > dataFinal)) {
                resetPesquisarButton();
                showAlert('warning', "Data Final não pode ser menor que a Data Inicial!")
                return;
            }

            if (searchOptions.statusProcessamento || (searchOptions.dataInicial && searchOptions.dataFinal)) {
                atualizarAgGridProcessamento(searchOptions);
            } else {
                showAlert('warning', 'Dados Insuficientes para fazer uma busca!')
                resetPesquisarButton();
                return;
            }
        }
    });

    //MENUS
    $('#menu-whitelist').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-gerenciamento').removeClass("selected");
            $('#menu-alertas-email').removeClass("selected");
            $('#menu-blacklist').removeClass("selected");
            $('#menu-eventos-placas').removeClass("selected");
            $('#menu-processamento').removeClass("selected");
            $('.card-dados-gerenciamento').hide()
            $('.card-dados-mapa').hide()
            $('.card-dados-mapa-eventos').hide()
            $('.card-blacklist').hide()
            $('.card-eventos-placas').hide()
            $('.card-alertas-email').hide()
            $('.card-whitelist').show()
            $('.card-processamento').hide()
            $('.buscaProcessamento').hide()
            $('.buscaData').hide()
            $('.buscaAlertasEmail').hide()
            $('.buscaStatus').show()
            $('.buscaCliente').show()
            $.ajax({
                url: Router + '/addSession/cadastro_ocr/Whitelist',
                type: 'GET'
            });
            validarDadosWhitelist(function (error) {
                if (!error) {
                    atualizarAgGridWhitelist();
                } else {
                    atualizarAgGridWhitelist()
                }
            })
            $('#clienteBusca').val(null).trigger('change');
        }
    });

    $('#menu-alertas-email').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-gerenciamento').removeClass("selected");
            $('#menu-blacklist').removeClass("selected");
            $('#menu-eventos-placas').removeClass("selected");
            $('#menu-whitelist').removeClass("selected");
            $('#menu-processamento').removeClass("selected");
            $('.card-dados-gerenciamento').hide()
            $('.card-dados-mapa').hide()
            $('.card-dados-mapa-eventos').hide()
            $('.card-blacklist').hide()
            $('.card-eventos-placas').hide()
            $('.card-whitelist').hide()
            $('.card-alertas-email').show()
            $('.card-processamento').hide()
            $('.buscaProcessamento').hide()
            $('.buscaCliente').hide()
            $('.buscaData').hide()
            $('.buscaStatus').hide()
            $('.buscaAlertasEmail').show()
            $.ajax({
                url: Router + '/addSession/cadastro_ocr/AlertasEmail',
                type: 'GET'
            });
            getDadosAlertasEmail(function (error) {
                if (!error) {
                    atualizarAgGridAlertasEmail();
                } else {
                    atualizarAgGridAlertasEmail();
                }
            })
            $('#clienteBusca').val(null).trigger('change');
        }
    });

    $('#menu-blacklist').on('click', function () {
        if (!$(this).hasClass("selected")) {

            $(this).addClass("selected");
            $('#menu-gerenciamento').removeClass("selected");
            $('#menu-alertas-email').removeClass("selected");
            $('#menu-eventos-placas').removeClass("selected");
            $('#menu-whitelist').removeClass("selected");
            $('#menu-processamento').removeClass("selected");
            $('.card-dados-gerenciamento').hide()
            $('.card-dados-mapa').hide()
            $('.card-dados-mapa-eventos').hide()
            $('.card-eventos-placas').hide()
            $('.card-blacklist').show()
            $('.card-alertas-email').hide()
            $('.card-whitelist').hide()
            $('.card-processamento').hide()
            $('.buscaProcessamento').hide()
            $('.buscaData').hide()
            $('.buscaAlertasEmail').hide()
            $('.buscaCliente').show()
            $('.buscaStatus').show()
            $.ajax({
                url: Router + '/addSession/cadastro_ocr/Blacklist',
                type: 'GET'
            });
            getDadosBlackList(function (error) {
                if (!error) {
                    atualizarAgGridBlackList();
                    
                } else {
                    atualizarAgGridBlackList()
                }
            })
            $('#clienteBusca').val(null).trigger('change');
        }
    });

    $('#menu-processamento').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-gerenciamento').removeClass("selected");
            $('#menu-alertas-email').removeClass("selected");
            $('#menu-blacklist').removeClass("selected");
            $('#menu-whitelist').removeClass("selected");
            $('#menu-eventos-placas').removeClass("selected");
            $('.card-dados-gerenciamento').hide()
            $('.card-dados-mapa').hide()
            $('.card-dados-mapa-eventos').hide()
            $('.card-blacklist').hide()
            $('.card-eventos-placas').hide()
            $('.card-alertas-email').hide()
            $('.card-whitelist').hide()
            $('.card-processamento').show()
            $('.buscaProcessamento').show()
            $('.buscaData').hide()
            $('.buscaAlertasEmail').hide()
            $('.buscaStatus').hide()
            $('.buscaCliente').hide()
            $('#clienteBusca').val(null).trigger('change');
            $.ajax({
                url: Router + '/addSession/cadastro_ocr/ProcessamentoLote',
                type: 'GET'
            });
            atualizarAgGridProcessamento();
        }
    });

    $('.menu-interno-link').click(function () {
        limparPesquisa();
    })

    if ($('#menu-blacklist').hasClass('selected')) {
        getDadosBlackList(function (error) {
            if (!error) {
                atualizarAgGridBlackList()
            
            } else {
                atualizarAgGridBlackList()
            }
        })
    }

    if ($('#menu-whitelist').hasClass('selected')) {
        validarDadosWhitelist(function (error) {
            if (!error) {
                atualizarAgGridWhitelist();
            } else {
                atualizarAgGridWhitelist()
            }
        })
    }

    if ($('#menu-alertas-email').hasClass('selected')) {
        getDadosAlertasEmail(function (error) {
            if (!error) {
                atualizarAgGridAlertasEmail();
            } else {
                atualizarAgGridAlertasEmail();
            }
        })
    }

    if ($('#menu-processamento').hasClass('selected')) {
        $('#statusProcessamento').val('').trigger('change');
        atualizarAgGridProcessamento();
    }

    $('#BtnLimparFiltro').click(function() {
        showLoadingLimparButton();
   
        if ($("#menu-alertas-email").hasClass('selected')) {
            $('#emailBusca').val('');
            $('#clienteBusca').val(null).trigger('change');
            $('#statusBusca').val('Ativo').trigger('change');
            atualizarAgGridAlertasEmail();
    
        } else if ($("#menu-blacklist").hasClass('selected')) {
            $('#clienteBusca').val(null).trigger('change');
            $('#statusBusca').val('Ativo').trigger('change');
            $('#placaBusca').val('');
            atualizarAgGridBlackList();
    
         } else if ($("#menu-whitelist").hasClass('selected')) {
            $('#clienteBusca').val(null).trigger('change');
            $('#statusBusca').val('Ativo').trigger('change');
            $('#placaBusca').val('');
            validarDadosWhitelist(function (error, data) {
                if (!error) {
                    if (data) {
                        atualizarAgGridWhitelist(data);
                    } else {
                        atualizarAgGridWhitelist();
                    }
                } else {
                    atualizarAgGridWhitelist();
                }
            })   
        } else if ($("#menu-processamento").hasClass('selected')) {
            $('#statusProcessamento').val('').trigger('change');
            $('#dataInicial').val('');
            $('#dataFinal').val('');
            atualizarAgGridProcessamento();
        }

    });

    // $('#clienteBusca').select2().on('select2:unselect', function (e) {
        
    //     if ($("#menu-alertas-email").hasClass('selected')) {
    //         atualizarAgGridAlertasEmail();
    
    //     } else if ($("#menu-blacklist").hasClass('selected')) {
    //         atualizarAgGridBlackList();
    
    //     } else if ($("#menu-whitelist").hasClass('selected')) {
    //         validarDadosWhitelist(function (error, data) {
    //             if (!error) {
    //                 if (data) {
    //                     atualizarAgGridWhitelist(data);
    //                 } else {
    //                     atualizarAgGridWhitelist()
    //                     resetPesquisarButton()
    //                 }
    //             } else {
    //                 atualizarAgGridWhitelist()
    //                 resetPesquisarButton()
    //             }
    //         }) 
    //     } 

    // });   

});

$(window).scroll(function() {
    // Fecha todos os Select2 quando ocorrer um scroll
    $('.select2-container--open').each(function () {
        var $select2 = $(this).prev('select');
        $select2.select2('close');
    });
});

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

function limparPesquisa() {
    $('#formBusca').trigger("reset");
    $('#statusBusca').val('Ativo').trigger('change');
    $('#clienteBusca').val(null).trigger('change');
    $('#statusProcessamento').val('').trigger('change');
}

function buscarSeguradoras() {

    $("#clienteBusca").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });

    $("#seguradoraBlacklist").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });

    $("#seguradoraWhitelist").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });

    $("#seguradoraAlertasEmail").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });

    $("#seguradoraImportacao").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });

    $("#seguradoraImportacaoWhitelist").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });

    $("#seguradoraImportacaoWhitelistRemocao").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });

    $("#seguradoraImportacaoBlacklistRemocao").select2({
        ajax: {
            url: Router + '/buscar_clientes_ocr',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        placeholder: "Digite o nome do Cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
    });
}


function abrirDropdown(id, tableId) {
    var dropdown = $('#dropdown-menu-' + id);
    
    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }

    $(".dropdown-menu").hide();

    dropdown.show();
    var posDropdown = dropdown.height() + 4;

    var posBordaTabela = $('#' + tableId.id + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posButton = $('#dropdownMenuButton_' + id).get(0).getBoundingClientRect().bottom;

    if (posDropdown > (posBordaTabela - posButton)) {
        dropdown.css('top', '5%');
    }

    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#dropdownMenuButton_' + id).is(event.target)) {
            dropdown.hide();
        }
    });
}


//Visibilidade

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

// Utilitários
function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
}

function baixarModeloItens() {

    let route = Router + '/downloadModeloItens';
    $.ajax({
        url: route,
        type: 'GET',
        dataType: 'json',
        error: function (e) {
            showAlert('error', 'Erro ao baixar modelo!');
        },
        success: function (data) {
            if (data.status === 200) {
                window.location.href = data.mensagem;
            } else {
                showAlert('error', 'Não foi possível baixar o modelo!');
            }
        },
    });
}

function baixarModeloItensRemocao() {

    let route = Router + '/downloadModeloItensRemocao';
    $.ajax({
        url: route,
        type: 'GET',
        dataType: 'json',
        error: function (e) {
            showAlert('error', 'Erro ao baixar modelo!');
        },
        success: function (data) {
            if (data.status === 200) {
                window.location.href = data.mensagem;
            } else {
                showAlert('error', 'Não foi possível baixar o modelo!');
            }
        },
    });
}

function removeAcento(palavra) {
    return palavra.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
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
    for (var i=0; i <= dropdownItems.length;i++) {
        alturaDrop += dropdownItems.height();
    }
 
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)){
            dropdown.css('top', `-${alturaDrop - 60}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(alturaDrop - 50) - (diferenca + diferenca * 0.5) }px`);
        }
    } 
    
    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });

    $(document).on('contextmenu', function () {
        dropdown.hide();
    });
}

function abrirDropdownModal(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);
    
    if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }

    $(".dropdown-menu").hide();

    dropdown.show();
    var posDropdown = dropdown.height() + 4;

    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;

    if (posDropdown > (posBordaTabela - posButton)) {
        dropdown.css('top', '5%');
    }

    $(document).on('click', function (event) {
        if (!dropdown.is(event.target) && !$('#' + buttonId).is(event.target)) {
            dropdown.hide();
        }
    });

    $(document).on('contextmenu', function () {
        dropdown.hide();
    });
}

$('#modalModeloItensRemocao').on('hidden.bs.modal', function () {
    $('body').addClass('modal-open');
});

$('#modalModeloItens').on('hidden.bs.modal', function () {
    $('body').addClass('modal-open');
});