$(document).ready(function() {
    $("#ocorrenciaWhitelist").val('').trigger("change");

    $("#ocorrenciaWhitelist").select2({
        placeholder: "Selecione o tipo de ocorrência",
        allowClear: true,
        language: "pt-BR",
    })

    $('#BtnAdicionarWhitelist').on('click', function () {
        abrirWhitelist();
        $('#idWhitelist').val('0');
    });

    $('#btnSalvarImportacaoWhitelist').on('click', function() {
        var seguradoraImportacao = $('#seguradoraImportacaoWhitelist').val();
        var usuarioImportacao = $('#usuarioImportacaoWhitelist').val();
        if (!seguradoraImportacao) {
            showAlert('warning', 'Selecione um cliente para importar as cold lists!')
            return;
        }
        if (!usuarioImportacao) {
            showAlert('warning', 'Não foi possível identificar o usuário realizando a ação! Tente novamente!')
            return;
        }
        if(!ListaImportacaoWhitelist || ListaImportacaoWhitelist.length == 0){
            showAlert('warning', 'Adicione itens para poder importar' )
            return;
        }
        salvarImportacaoWhitelist();
    });

    $('#BtnImportarWhitelist').on('click', function() {
        $.ajax({
            cache: false,
            url: Router + '/getUserId',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#usuarioImportacaoWhitelist').val(data.id);
            }
        });

        $('#seguradoraImportacaoWhitelist').val('').trigger('change');
        $('#arquivoItensWhitelist').val('');
        
        importarWhitelist();
        limparImportacaoWhitelist();
    });

    $('#btnSalvarImportacaoWhitelistRemocao').on('click', function() {
        var seguradoraImportacao = $('#seguradoraImportacaoWhitelistRemocao').val();
        var usuarioImportacao = $('#usuarioImportacaoWhitelistRemocao').val();
        if (!seguradoraImportacao) {
            showAlert('warning', 'Selecione um cliente para importar as cold lists!')
            return;
        }
        if (!usuarioImportacao) {
            showAlert('warning', 'Não foi possível identificar o usuário realizando a ação! Tente novamente!')
            return;
        }
        if(!ListaImportacaoWhitelist || ListaImportacaoWhitelist.length == 0){
            showAlert('warning', 'Adicione itens para poder remover' )
            return;
        }
        salvarImportacaoWhitelistRemocao();
    });

    $('#BtnImportarWhitelistRemocao').on('click', function() {
        $.ajax({
            cache: false,
            url: Router + '/getUserId',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#usuarioImportacaoWhitelistRemocao').val(data.id);
            }
        });

        $('#seguradoraImportacaoWhitelistRemocao').val('').trigger('change');
        $('#arquivoItensWhitelistRemocao').val('');
        
        importarWhitelistRemocao();
        limparImportacaoWhitelistRemocao();
    });

    $('#formWhitelist').on('submit', function (e) {
        e.preventDefault();
        let cliente = GetClienteID();
        let id = $('#idWhitelist').val();

        if ((cliente == '0' || !cliente) && !id) {
            showAlert('warning', 'Cliente Obrigatório, por favor selecione.')
            return;
        }
        salvarWhitelist();
    });

    $("#info-icon-whitelist").click(function (e) {
        $("#modalModeloItens").modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#limparTabelaItensWhitelist").click(function (e) {
        limparImportacaoWhitelist()
    });

    $("#info-icon-whitelist-remocao").click(function (e) {
        $("#modalModeloItensRemocao").modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#limparTabelaItensWhitelistRemocao").click(function (e) {
        limparImportacaoWhitelistRemocao()
    });

    $("#associacaoWhitelist").on("shown.bs.modal", function () {
        var labelColdList = $("#titleAssociacaoWhitelist").text().includes("desassociação") ? "Selecione as placas da cold list a serem desassociadas:" : "Selecione as placas ds cold list a serem associadas:";
        var placeholderText = $("#titleAssociacaoWhitelist").text().includes("desassociação") ? "Selecione as placas da cold list a serem desassociadas..." : "Selecione as placas da cold list a serem associadas...";
        
        $("#associarColdList").prop("checked", false);
        $("#inputPlacas").val("").prop("disabled", false);
        $("#labelPlacasWhitelistsEditar").text(labelColdList)
        $("#placasWhitelistsEditar")
            .select2({
                placeholder: placeholderText,
                allowClear: true,
                language: "pt-BR"
            })
            .prop("disabled", false)
            .val(null)
            .trigger("change");
    });

    $("#associacaoWhitelist").on("hide.bs.modal", function () {
        $('#placasWhitelists').prop("disabled", false);
    });

    
    $("#associarColdList").change(function () {
        var labelColdList = $("#titleAssociacaoWhitelist").text().includes("desassociação") ? "Selecione as placas da cold list a serem desassociadas:" : "Selecione as placas da cold list a serem associadas:";
        var placeholderText = $("#titleAssociacaoWhitelist").text().includes("desassociação") ? "Selecione a placa da cold list a ser desassociada..." : "Selecione as placas da cold list a serem associadas...";

        var isChecked = $(this).is(":checked");
        $("#placasWhitelistsEditar").prop("disabled", isChecked);
        $("#inputPlacas").val("").prop("disabled", isChecked);
        $("#labelPlacasWhitelistsEditar").text(labelColdList)
        $("#placasWhitelists").val(null).trigger("change").prop("disabled", isChecked); // Limpa o select2
        $("#placasWhitelists").select2({
            placeholder: placeholderText,
            allowClear: false, // Remove a opção de limpar o select 
        })
        if ($("#titleAssociacaoWhitelist").text().includes("desassociação")) {
            $("#clienteBusca").val(); //Pega o id_cliente
            $("#idAlertasEmailWhitelist").val(); // Pega id_email

            $('#BtnImportarWhitelist').on('click', function () {
                associacaoLoteHotList();
            })
        }
    });

});

// REQUISIÇÕES
function getWhiteListByID(id) {
    let route = Router + '/buscarWhitelistByID';
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            if (response.status == 200) {
                preencherWhitelist(response.resultado[0]);
                HideLoadingScreen();
                $("#whitelist").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                showAlert('error', 'Dados não encontrados diferente')
                HideLoadingScreen();
            }
        },
        error: function (xhr, status, error) {
            showAlert('error', 'Dados não encontrados')
            HideLoadingScreen();
        }
    });
}

async function deleteWhitelist(id, status) {
    let route = Router + '/deletarWhitelist';
    if (status && status == 'Ativo') {
        Swal.fire({
            title: "Atenção!",
            text: "Deseja realmente remover o item?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    beforeSend: function() {
                        ShowLoadingScreen();
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            showAlert('success', 'Item removido com sucesso');
                            HideLoadingScreen();
                            atualizarTableWhitelist();
                        } else if (data.status == 400) {
                            showAlert('warning', data['resultado']['mensagem'])
                            HideLoadingScreen();
                        } else {
                            showAlert('error', 'Erro ao remover o item')
                            HideLoadingScreen();
                        }
                    },
                    error: function (error) {
                        showAlert('error', 'Erro ao remover o item')
                        HideLoadingScreen();
                    }
                });
            }
        });
    } else {
        showAlert('warning', "Essa Cold List já foi removida!")
        HideLoadingScreen();
    }

}

function validarDadosWhitelist(callback, options) {
    var route;

    if (options) {
        if (options.placa || options.cliente || options.status) {
            showLoadingPesquisarButton();
        } else {
            showAlert('warning', 'Dados Insuficientes para fazer uma busca')
            resetPesquisarButton();
        }
    }

    if (typeof callback === "function") callback(null);
}

function salvarWhitelist() {
    let id = $('#idWhitelist').val();
    showLoadingSalvarWhitelistButton()

    let route = Router + '/atualizarWhitelist';

    if (id == '0') {
        route = Router + '/adicionarWhitelist';
    }
    let whitelist = $('#formWhitelist').serialize();
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: whitelist,
        dataType: 'json',
        success: function (response) {
            if (response.status == 200) {
                showAlert('success', 'Cold List salva com sucesso')
                atualizarTableWhitelist();
            } else if (response.status == 400) {
                if ('resultado' in response && 'mensagem' in response.resultado) {
                    showAlert('warning', response['resultado']['mensagem']);
                } else {
                    showAlert('error', 'Erro ao enviar requisição');
                }
            } else {
                showAlert('error', 'Erro ao enviar requisição');
            }
            resetSalvarWhitelistButton()
        },
        error: function (error) {
            showAlert('error', 'Erro ao enviar requisição')
            resetSalvarWhitelistButton()
        }
    });
}

function atualizarTableWhitelist() {
    $('#whitelist').modal('hide');

    var searchOptions = null;

    searchOptions = {
        placa: $("#placaBusca").val(),
        dataInicial: $("#dataInicial").val(),
        dataFinal: $("#dataFinal").val(),
        cliente: $("#clienteBusca").val(),
        status: $("#statusBusca").val(),
    };

    if (searchOptions && (searchOptions.placa || searchOptions.cliente || searchOptions.status)) {
        validarDadosWhitelist(function (error) {
            if (!error) {
                atualizarAgGridWhitelist(searchOptions);
            } else {
                atualizarAgGridWhitelist();
            }
        }, searchOptions);
    } else {
        validarDadosWhitelist(function (error) {
            if (!error) {
                atualizarAgGridWhitelist();
            } else {
                atualizarAgGridWhitelist();
            }
        });
    }
}

function salvarImportacaoWhitelist() {
    let id = $('#idWhitelist').val();
    showLoadingSalvarImportacaoWhitelistButton()

    let route = Router + '/adicionarImportacaoWhitelist';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { ListaImportacaoWhitelist: JSON.stringify(ListaImportacaoWhitelist) },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200 || data.status == 400) {
                var resultado = data.resultado;
                var showFalhas = false;
                $('#header-resposta-importacao').html('Relatório de Importação - Adicionar Cold List')
                $('#lista-falhas').html('');
                $('#quantidades').show();

                if ("mensagem" in resultado) {
                    $('#mensagem-resposta').text(resultado.mensagem.replace('atualizados', 'adicionadas').replace('atualização', 'importação').replace('atualizadas', 'adicionados'))
                    
                    if ('totalDeDados' in resultado) {
                        $('#qtd-sucesso').html(resultado.totalDeDados.quantidadeSucesso);
                        $('#qtd-falha').html(resultado.totalDeDados.quantidadeFalha);
                    } else {
                        $('#qtd-sucesso').html('Quantidade de sucesso: 0');
                        $('#qtd-falha').html('Quantidade de falhas: 0');
                    }

                    if ("lista" in resultado && resultado.lista.length > 0) {
                        showFalhas = true;
                        resultado.lista.forEach((element, index) => {
                            if (index < 100) {
                                let partes = element.objeto.split("placa:");
                                if (partes.length > 0) {
                                    let valorPlaca = partes[1].split(",")[0].trim();
                                    $('#lista-falhas').append(`<li>Placa: ${valorPlaca} - ${element['titulo']}.</li>`)
                                }
                            }
                        });
                    }

                    if (showFalhas) {
                        $('#div-falhas').show();
                    } else {
                        $('#div-falhas').hide();
                    }

                    if (data.status == 200) {
                        $('#titulo-resposta').html('<i class="fa fa-check-circle-o" aria-hidden="true"></i> Sucesso!').addClass('title-success').removeClass('title-danger');
                    } else {
                        $('#titulo-resposta').html('<i class="fa fa-times-circle-o" aria-hidden="true"></i> Falha!').addClass('title-danger').removeClass('title-success');
                    }

                    $('#modal-resposta-importacao').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    showAlert('warning', 'Não foi possível definir a resposta!')
                }
                ListaImportacaoWhitelist = [];
                limparImportacaoWhitelist();
                atualizarTableWhitelist();
                $('#importarWhitelist').modal('hide');
            } else {
                showAlert('error', 'Erro ao enviar requisição');
            }
            resetSalvarImportacaoWhitelistButton()
        },
        error: function (error) {
            showAlert('error', 'Erro ao enviar requisição')
            resetSalvarImportacaoWhitelistButton()
        }
    });
}

function salvarImportacaoWhitelistRemocao() {
    showLoadingSalvarImportacaoWhitelistRemocaoButton()

    let route = Router + '/removerImportacaoWhitelist';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { ListaImportacaoWhitelist: JSON.stringify(ListaImportacaoWhitelist) },
        dataType: 'json',
        success: function (data) {
            $('#header-resposta-importacao').html('Relatório de Importação - Remover Cold List')
            $('#lista-falhas').html('');
            $('#qtd-sucesso').html('Quantidade de sucesso: 0');
            $('#qtd-falha').html('Quantidade de falhas: 0');
            $('#div-falhas').hide();
            $('#quantidades').hide();

            if (data.status == 201) {
                $('#mensagem-resposta').text('Solicitação de remoção enviada com sucesso! Verifique o status na tela de Processamento em Lote.');

                $('#titulo-resposta').html('<i class="fa fa-check-circle-o" aria-hidden="true"></i> Sucesso!').addClass('title-success').removeClass('title-danger');

                $('#modal-resposta-importacao').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                
                ListaImportacaoWhitelist = [];
                limparImportacaoWhitelistRemocao();
                atualizarTableWhitelist();
                $('#importarWhitelistRemocao').modal('hide');
            } else if (data.status == 400) {
                $('#titulo-resposta').html('<i class="fa fa-times-circle-o" aria-hidden="true"></i> Erro!').addClass('title-danger').removeClass('title-success');
                $('#mensagem-resposta').text('Erro ao enviar solicitação. Entre em contato com o suporte!');
                $('#modal-resposta-importacao').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                $('#titulo-resposta').html('<i class="fa fa-times-circle-o" aria-hidden="true"></i> Falha!').addClass('title-danger').removeClass('title-success');
                $('#mensagem-resposta').text('Erro ao enviar solicitação. Tente novamente!');
                $('#modal-resposta-importacao').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
            resetSalvarImportacaoWhitelistRemocaoButton();
        },
        error: function (error) {
            $('#titulo-resposta').html('<i class="fa fa-times-circle-o" aria-hidden="true"></i> Falha!').addClass('title-danger').removeClass('title-success');
            $('#mensagem-resposta').text('Erro ao enviar solicitação.');
            $('#modal-resposta-importacao').modal({
                backdrop: 'static',
                keyboard: false
            });
            resetSalvarImportacaoWhitelistRemocaoButton();
        }
    });
}

function abrirWhitelist(id, status) {

    ShowLoadingScreen();
    limparWhitelist();

    if (id) {
        if (status && status == 'Ativo') {
            $("#titleWhitelist").html('Editar Cold List')
            $('#divCliente').hide();
            $('.divSeguradoraWhitelist').hide();
            $('#seguradoraWhitelist').attr('required', false);
            getWhiteListByID(id);
        } else {
            showAlert('warning', 'Essa Cold List não pode ser editada, pois está inativa!');
            HideLoadingScreen();
        }
        
    } else {
        
        $.ajax({
            cache: false,
            url: Router + '/getUserId',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#usuarioWhitelist').val(data.id);
            },
            error: function() {
                showAlert('error', 'Não foi possível pegar os dados do usuário logado. Por favor, tente novamente!')
                return;
            }
        });
        $("#titleWhitelist").html('Cadastrar Cold List');
        $('.divSeguradoraWhitelist').show();
        $('#seguradoraWhitelist').attr('required',true);
        HideLoadingScreen();
        $("#whitelist").modal({
            backdrop: 'static',
            keyboard: false
        });
    }
}

function abrirAssociacaoWhitelist(id, id_cliente){
    
    ShowLoadingScreen();
    $('#idAlertasEmailWhitelist').val(id);
    $('#idCliente').val(id_cliente);
    $('#removeModalWhitelist').val(0);
    $('#divPlacasWhitelists').show();
    $('#divPlacasWhitelistsEditar').hide();
    $('#titleAssociacaoWhitelist').text('Solicitar associação de Cold List');
    $('#labelPlacasWhitelists').text('Selecione as placas das cold lists a serem associadas:');
    $('label[for="associarColdList"]').text('Associar todas as placas:');

    let route = Router + '/buscar_placas_whitelist';
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            id_cliente: id_cliente
        },
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                if (data.resultado.length > 0) {
                    $("#placasWhitelists").empty().trigger('change');
                    $("#placasWhitelists").select2({
                        data: data.resultado,
                        placeholder: "Selecione as placas da cold list a serem associadas...",
                        allowClear: true,
                        language: "pt-BR",
                        width: 'resolve',
                        height: '32px',
                        multiple: true
                    })
                    
                    $("#placasWhitelists").select2('val',' ');
                    $("#associacaoWhitelist").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    showAlert('warning', 'O cliente não possui cold lists cadastradas!');
                }

            }else if(data.status == 404){
                showAlert('warning', "O cliente não possui cold lists cadastradas!")
            }else{
                showAlert('error', 'Erro ao listar Placas. Tente novamente!')
            }
            HideLoadingScreen()
        },
        error: function(error) {
            showAlert('error', 'Erro ao listar Placas. Tente novamente!')
            HideLoadingScreen()
        }
    });
    
}

function abrirDesassociacaoWhitelist(id, id_cliente) {

    ShowLoadingScreen();
    $('#idAlertasEmailWhitelist').val(id);
    $('#idCliente').val(id_cliente);
    $('#removeModalWhitelist').val(1);
    $('#divPlacasWhitelists').hide();
    $('#divPlacasWhitelistsEditar').show();
    $('#titleAssociacaoWhitelist').text('Solicitar desassociação de Cold List');
    $('#labelPlacasWhitelists').text('Selecione a placa da cold list a ser desassociada:');
    $('label[for="associarColdList"]').text('Desassociar todas as placas:');

    let route = Router + '/buscar_placas_whitelists_associadas';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            id: id,
            id_cliente: id_cliente
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                if (data.resultado.length > 0) {
                    $("#placasWhitelistsEditar").empty()
                    $("#placasWhitelistsEditar").select2({
                        data: data.resultado,
                        placeholder: "Selecione as placas da cold list a serem desassociadas...",
                        allowClear: true,
                        language: "pt-BR",
                        width: 'resolve',
                        height: '32px',
                        multiple: true
                    })
                    $("#placasWhitelistsEditar").select2('val', ' ')

                    $("#associacaoWhitelist").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    $("#placasWhitelistsEditar").empty()
                    $("#placasWhitelistsEditar").select2({
                        placeholder: "Selecione as placas da cold list a serem desassociadas...",
                        allowClear: true,
                        multiple: true
                    })
                    $("#placasWhitelistsEditar").select2('val', ' ')
                    $("#associacaoWhitelist").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }

            } else if (data.status == 400) {
                showAlert('warning', data['resultado']['mensagem'])
            } else if (data.status == 404) {
                $("#placasWhitelistsEditar").empty()
                $("#placasWhitelistsEditar").select2({
                    placeholder: "Selecione as placas da cold list a serem desassociadas...",
                    allowClear: true,
                    multiple: true
                })
                $("#placasWhitelistsEditar").select2('val', ' ')
                $("#associacaoWhitelist").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                showAlert('error', 'Erro ao listar Placas. Tente novamente!')
            }
            HideLoadingScreen()
        },
        error: function (error) {
            showAlert('error', 'Erro ao listar Placas. Tente novamente!')
            HideLoadingScreen()
        }
    });

}

function desassociarPlacaAbaWhitelist(idAlerta, idPlaca){
    let route = Router + '/removerAssociacaoWhitelist';
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente desassociar o item?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id_alerta_email: idAlerta,
                    id_whitelist: idPlaca
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', 'Desassociação realizada com sucesso!');

                    }else if(data.status == 400){
                        showAlert('warning', 'O valor enviado não possui associação à esse alerta de e-mail!');
                    }else if(data.status == 404){
                        showAlert('warning', 'O valor enviado não possui associação à esse alerta de e-mail!');
                    }else{
                        showAlert('error', 'Erro ao realizar a desassociação. Tente novamente!');
                    }
                    resetSalvarButtonAssociacaoWhitelist();
                    atualizarAgGridAbaWhitelist();
                },
                error: function(error) {
                    showAlert('error', 'Erro ao realizar a desassociação. Tente novamente!');
                    resetSalvarButtonAssociacaoWhitelist();
                }
            });
        }
    });
}

$('#btnSalvarAssociacaoWhitelist').click(function () {
    var associar = $("#titleAssociacaoWhitelist").text().includes(" associação ") ? true : false;
    var desassociar = $("#titleAssociacaoWhitelist").text().includes("desassociação") ? true : false;
    var checked = $("#associarColdList").is(":checked");
    var idAlerta = $("#idAlertasEmailWhitelist").val();
    var idCliente = $('#idCliente').val();
    if(associar && checked){
        showLoadingSalvarButtonAssociacaoWhitelist();
        let route = Router + '/adicionarAssociacaoAllWhitelist';
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    idAlerta: idAlerta,
                    idCliente: idCliente
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        showAlert('success', "Solicitação de associação realizada com sucesso!");
                        $("#associacaoWhitelist").modal('hide');
                        atualizarTableAlertasEmail();
                    } else if (data.status == 400 || data.status == 404) {
                        if ('mensagem' in data.resultado) {
                            showAlert('warning', data.resultado["titulo"] + " " + data.resultado["mensagem"]);
                        } else {
                            showAlert('error', "Erro ao realizar a solicitação de associação. Tente novamente!");
                        }
                    } else {
                        showAlert('error', "Erro ao realizar a solicitação de associação. Tente novamente!");
                    }
                    resetSalvarButtonAssociacaoWhitelist();                    
                },
                error: function (error) {
                    showAlert('error', "Erro ao realizar a solicitação de associação.");
                    resetSalvarButtonAssociacaoWhitelist()
                }
            });
    } else if (desassociar && checked){
        showLoadingSalvarButtonAssociacaoWhitelist();
        let route = Router + '/desassociarAllWhitelist';
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    idAlerta: idAlerta,
                    idCliente: idCliente
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        showAlert('success', 'Solicitação de desassociação realizada com sucesso!');
                        $("#associacaoWhitelist").modal('hide');
                        atualizarTableAlertasEmail();
                    } else if (data.status == 400 || data.status == 404) {
                        if ('mensagem' in data.resultado) {
                            showAlert('warning', data.resultado["titulo"] + " " + data.resultado["mensagem"]);
                        } else {
                            showAlert('error', "Erro ao realizar a solicitação de desassociação. Tente novamente!");
                        }
                    } else {
                        showAlert('error', 'Erro ao realizar a desassociação. Tente novamente!');
                    }
                    resetSalvarButtonAssociacaoWhitelist();
                },
                error: function (error) {
                    showAlert('error', 'Erro ao realizar a desassociação. Tente novamente!');
                    resetSalvarButtonAssociacaoWhitelist()
                }
            });
    } else {
        showLoadingSalvarButtonAssociacaoWhitelist()
        var id_alerta_email = $('#idAlertasEmailWhitelist').val();
        var ids_whitelist = $('#placasWhitelists').val();
        var id_whitelist = $('#placasWhitelistsEditar').val();
        var desassociar = $("#titleAssociacaoWhitelist").text().includes("desassociação") ? true : false;
    
        if (!desassociar) {
            if (ids_whitelist.length == 0) {
                resetSalvarButtonAssociacaoWhitelist();
                showAlert('warning', 'Selecione pelo menos uma placa para associar!')
                return;
            }
            let route = Router + '/adicionarAssociacaoLoteWhitelist';
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id_cliente: idCliente,
                    id_alerta_email: id_alerta_email,
                    ids_whitelist: ids_whitelist
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 201) {
                        showAlert('success', 'Solicitação de associação realizada com sucesso! Verifique o status na tela de Processamento em Lote.');
                        $("#associacaoWhitelist").modal('hide');
                        atualizarTableAlertasEmail();
                    } else if (data.status == 400 || data.status == 404) {
                        if ('titulo' in data.resultado && 'mensagem' in data.resultado) {
                            showAlert('warning', data.resultado.titulo + ' ' + data.resultado.mensagem);
                        } else {
                            showAlert('error', 'Erro ao realizar a solicitação de associação. Tente novamente!')
                        }
                    } else {
                        showAlert('error', 'Erro ao realizar a solicitação de associação. Tente novamente!')
                    }
                    resetSalvarButtonAssociacaoWhitelist();
                },
                error: function (error) {
                    showAlert('error', 'Erro ao realizar a solicitação de associação. Tente novamente!')
                    resetSalvarButtonAssociacaoWhitelist()
                }
            });
        } else {
            let route = Router + '/desassociarLoteWhitelist';
            if (id_whitelist.length == 0) {
                resetSalvarButtonAssociacaoWhitelist();
                showAlert('warning', 'Selecione pelo menos uma placa para desassociar!')
                return;
            }
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id_cliente: idCliente,
                    id_alerta_email: id_alerta_email,
                    id_whitelist: id_whitelist
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 201) {
                        showAlert('success', 'Solicitação de desassociação realizada com sucesso! Verifique o status na tela de Processamento em Lote.');
                        $("#associacaoWhitelist").modal('hide');
                        atualizarTableAlertasEmail();
                    } else if (data.status == 400 || data.status == 404) {
                        if ('titulo' in data.resultado && 'mensagem' in data.resultado) {
                            showAlert('warning', data.resultado.titulo + ' ' + data.resultado.mensagem);
                        } else {
                            showAlert('error', 'Erro ao realizar a solicitação de desassociação. Tente novamente!');
                        }
                    } else {
                        showAlert('error', 'Erro ao realizar a solicitação de desassociação. Tente novamente!');
                    }
                    resetSalvarButtonAssociacaoWhitelist();
                },
                error: function (error) {
                    showAlert('error', 'Erro ao realizar a solicitação de desassociação. Tente novamente!');
                    resetSalvarButtonAssociacaoWhitelist();
                }
            });
        }
    }
})

// Utilitários

function preencherWhitelist(dados) {
    $('#idWhitelist').val(dados['id']);
    $('#usuarioWhitelist').val(dados['idUsuarioImportacao']);
    $('#placaWhitelist').val(dados['placa']);
    $('#chassiWhitelist').val(dados['chassi']);
    $('#ocorrenciaWhitelist').val(dados['tipoOcorrencia']).trigger('change');
    $('#modeloWhitelist').val(dados['modelo']);
    $('#marcaWhitelist').val(dados['marca']);
    $('#corWhitelist').val(dados['cor']);
}

function limparWhitelist() {
    $('#idWhitelist').val('');
    $('#usuarioWhitelist').val('');
    $('#placaWhitelist').val('');
    $('#chassiWhitelist').val('');
    $('#ocorrenciaWhitelist').val('').trigger('change');
    $('#modeloWhitelist').val('');
    $('#marcaWhitelist').val('');
    $('#corWhitelist').val('');
    $("#seguradoraWhitelist").val('').trigger('change')
    $("#usuarioWhitelist").val('').trigger('change');
}

function importarItensExcelWhitelist(event) {
    event.preventDefault();
    const fileInput = document.getElementById('arquivoItensWhitelist');
    const file = fileInput.files[0];

    if (!file) {
        showAlert('warning', 'Por favor, selecione um arquivo.');
        return;
    }

    var seguradoraImportacao = 0;
    if ($("#seguradoraImportacaoWhitelist").val()) {
        seguradoraImportacao = $("#seguradoraImportacaoWhitelist").val();
    } else {
        showAlert('warning', 'Selecione o cliente antes de importar')
        return;
    }

    var usuarioImportacao = 0;
    if ($("#usuarioImportacaoWhitelist").val()) {
        usuarioImportacao = $("#usuarioImportacaoWhitelist").val();
    } else {
        showAlert('warning', 'Não foi possível definir o usuário logado. Tente novamente!')
        return;
    }

    const validExtensions = ['.xls', '.xlsx'];
    const fileExtension = '.' + file.name.split('.').pop();

    if (!validExtensions.includes(fileExtension)) {
        showAlert('warning', 'Por favor, selecione um arquivo Excel válido (.xls ou .xlsx).');
        return;
    }
    var seriaisErro = [];
    const reader = new FileReader();
    reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const letras = /^[a-zA-ZÀ-ÿ]+$/;

        var dadosInserir = [];

        const jsonData = XLSX.utils.sheet_to_json(worksheet, { raw: true });

        if (jsonData.length === 0) {
            showAlert('warning', 'Arquivo vazio.');
            return;
        }

        const serialPromises = [];
        const serialSet = new Set();

        var valorIncompleto = false;
        var breakLimit = false;

        ShowLoadingScreen()

        jsonData.forEach((resultado, index) => {
            if (index < 500) {
                colunas = Object.keys(resultado);

                const arrayProcessado = colunas.map(palavra => removeAcento(palavra.toLowerCase()));

                if (arrayProcessado.includes("placa")) {
                    dadosInserir.push(
                        {
                            index: index,
                            placa: resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'PLACA')],
                            chassi: resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'CHASSI')],
                            textoOcorrencia: resultado[Object.keys(resultado).find(key => (removeAcento(key.toUpperCase()) === 'TIPO DE OCORRENCIA') || (removeAcento(key.toUpperCase()) === 'TIPO OCORRENCIA'))],
                            modelo: Object.hasOwn(resultado, Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MODELO')) ? resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MODELO')] : '',
                            marca: Object.hasOwn(resultado, Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MARCA')) ? resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MARCA')] : '',
                            cor: Object.hasOwn(resultado, Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'COR')) ? resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'COR')] : '',
                            idCliente: seguradoraImportacao,
                            idUsuarioImportacao: usuarioImportacao,
                        });

                } else {
                    valorIncompleto = true;
                }
            } else {
                breakLimit = true;
            }

        });

        if (valorIncompleto) {
            HideLoadingScreen()
            showAlert('warning', 'O arquivo possui algumas Cold Lists sem Placa. Verifique e tente novamente.');
            return;
        } else {
            HideLoadingScreen()
            if(breakLimit) {showAlert('warning', 'Desculpe, a planilha excede o limite de registros. Como resultado, apenas os primeiros 500 registros foram adicionados.')}
            preencherImportacaoWhitelist(dadosInserir);
        }
    };

    reader.readAsArrayBuffer(file);

}

function importarItensExcelWhitelistRemocao(event) {
    event.preventDefault();
    const fileInput = document.getElementById('arquivoItensWhitelistRemocao');
    const file = fileInput.files[0];

    if (!file) {
        showAlert('warning', 'Por favor, selecione um arquivo.');
        return;
    }

    var seguradoraImportacao = 0;
    if ($("#seguradoraImportacaoWhitelistRemocao").val()) {
        seguradoraImportacao = $("#seguradoraImportacaoWhitelistRemocao").val();
    } else {
        showAlert('warning', 'Selecione o cliente antes de importar')
        return;
    }

    var usuarioImportacao = 0;
    if ($("#usuarioImportacaoWhitelistRemocao").val()) {
        usuarioImportacao = $("#usuarioImportacaoWhitelistRemocao").val();
    } else {
        showAlert('warning', 'Não foi possível definir o usuário logado. Tente novamente!')
        return;
    }

    const validExtensions = ['.xls', '.xlsx'];
    const fileExtension = '.' + file.name.split('.').pop();

    if (!validExtensions.includes(fileExtension)) {
        showAlert('warning', 'Por favor, selecione um arquivo Excel válido (.xls ou .xlsx).');
        return;
    }
    var seriaisErro = [];
    const reader = new FileReader();
    reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const letras = /^[a-zA-ZÀ-ÿ]+$/;

        var dadosInserir = [];

        const jsonData = XLSX.utils.sheet_to_json(worksheet, { raw: true });

        if (jsonData.length === 0) {
            showAlert('warning', 'Arquivo vazio.');
            return;
        }

        const serialPromises = [];
        const serialSet = new Set();

        var valorIncompleto = false;
        var breakLimit = false;

        ShowLoadingScreen()

        jsonData.forEach((resultado, index) => {
            if (index < 500) {
                colunas = Object.keys(resultado);

                const arrayProcessado = colunas.map(palavra => removeAcento(palavra.toLowerCase()));

                if (
                    arrayProcessado.includes("placa") && 
                    arrayProcessado.includes("motivo")
                ){
                    dadosInserir.push(
                    {
                        index: index,
                        placa: resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'PLACA')],
                        motivo: resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MOTIVO')],
                        chassi: resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'CHASSI')],
                        textoOcorrencia: resultado[Object.keys(resultado).find(key => (removeAcento(key.toUpperCase()) === 'TIPO DE OCORRENCIA') || (removeAcento(key.toUpperCase()) === 'TIPO OCORRENCIA'))],
                        modelo: Object.hasOwn(resultado, Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MODELO')) ? resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MODELO')] : '',
                        marca: Object.hasOwn(resultado, Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MARCA')) ? resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'MARCA')] : '',
                        cor: Object.hasOwn(resultado, Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'COR')) ? resultado[Object.keys(resultado).find(key => removeAcento(key.toUpperCase()) === 'COR')] : '',
                        idCliente: seguradoraImportacao,
                        idUsuarioImportacao: usuarioImportacao,
                        status: 0
                    });
                    
                }else{
                    valorIncompleto = true;
                }
            } else {
                breakLimit = true;
            }

        });

        if (valorIncompleto) {
            HideLoadingScreen()
            showAlert('warning', 'O arquivo possui alguma(s) Cold List sem Placa ou Motivo. Verifique e tente novamente.');
            return;
        } else {
            HideLoadingScreen()
            if(breakLimit) {showAlert('warning', 'Desculpe, a planilha excede o limite de registros. Como resultado, apenas os primeiros 500 registros foram adicionados.')}
            preencherImportacaoWhitelistRemocao(dadosInserir);
        }
    };

    reader.readAsArrayBuffer(file);

}

// AG-GRID
var AgGridWhitelist;
function atualizarAgGridWhitelist(options) {
    stopAgGRIDWhitelist();
    function getServerSideDadosWhitelist() {
        return {
            getRows: (params) => {
        
            var route = Router + '/buscarWhitelistServerSide';
        
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    startRow: params.request.startRow,
                    endRow: params.request.endRow,
                    placa: options ? options.placa : '',
                    idCliente: options ? options.cliente : '',
                    status: options ? options.status : ''
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
                            if (chave === 'tipoOcorrencia') {
                                if (dados[i][chave] == '0') {
                                    dados[i][chave] = 'Roubo';
                                } else if (dados[i][chave] == '1') {
                                    dados[i][chave] = 'Furto';
                                } else if (dados[i][chave] == '2') {
                                    dados[i][chave] = 'Apropriação Indébita';
                                } else {
                                    dados[i][chave] = '';
                                }
                            }
                        }
                    }
                    params.success({
                        rowData: dados,
                        rowCount: data.lastRow,
                    });
                } else if (data && data.message){
                    showAlert('warning', data.message);
                    params.failCallback();
                    params.success({
                        rowData: [],
                        rowCount: 0,
                    });
                    AgGridWhitelist.gridOptions.api.showNoRowsOverlay();
                } else {
                    showAlert('error', 'Erro na solicitação ao servidor');
                    params.failCallback();
                    params.success({
                        rowData: [],
                        rowCount: 0,
                    });
                    AgGridWhitelist.gridOptions.api.showNoRowsOverlay();
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
                    AgGridWhitelist.gridOptions.api.showNoRowsOverlay();
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

    function getContextMenuItemsWhitelist(params) {
        if (params && params.node && 'data' in params.node && 'id' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    name: 'Editar',
                    action: () => {
                        abrirWhitelist(data.id, data.status)
                    },
                },
                {
                    name: 'Remover',
                    action: () => {
                        deleteWhitelist(data.id, data.status)
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
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Chassi',
                field: 'chassi',
                chartDataType: 'series'
            },
            {
                headerName: 'Marca',
                field: 'marca',
                chartDataType: 'series',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
                chartDataType: 'series'
            },
            {
                headerName: 'Cor',
                field: 'cor',
                chartDataType: 'series',
                width: 120,
                suppressSizeToFit: true
            },
            {
                headerName: 'Tipo de Ocorrência',
                field: 'tipoOcorrencia',
                width: 180,
                suppressSizeToFit: true,
                chartDataType: 'category'
            },
            {
                headerName: 'Cliente',
                field: 'nome',
                chartDataType: 'category'
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Razão Social',
                field: 'razaoSocial',
                chartDataType: 'category'
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options)  {
                    let data = options.data;
                    let tableId = "tableWhitelist";
                    let dropdownId = "dropdown-menu-whitelist-" + data.id;
                    let buttonId = "dropdownMenuButtonWhitelist_" + data.id;

                    return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdown('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirWhitelist(${data.id}, '${data.status}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:deleteWhitelist(${data.id}, '${data.status}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
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
        paginationPageSize: $('#select-quantidade-por-pagina-whitelist').val(),
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        getContextMenuItems: getContextMenuItemsWhitelist,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('id' in data) {
                    abrirWhitelist(data.id, data.status)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar o Cold List.')
                }
            }
        }
    };

    $('#select-quantidade-por-pagina-whitelist').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-whitelist').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableWhitelist');
    gridDiv.style.setProperty('height', '519px');

    AgGridWhitelist = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDadosWhitelist();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesWhitelist(gridOptions)
}

function limparImportacaoWhitelist() {
    preencherImportacaoWhitelist();
    ListaImportacaoWhitelist = [];
}
function importarWhitelist() {
    $('#importarWhitelist').modal({
        backdrop: 'static',
        keyboard: false
    });
    ListaImportacaoWhitelist = [];
}

var ImportacaoWhitelist;
var ListaImportacaoWhitelist;

function preencherImportacaoWhitelist(dados) {
    stopAgGRIDImportacaoWhitelist();

    function getContextMenuItemsImportarWhitelist(params) {
        if (params && params.node && 'data' in params.node && 'index' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    name: 'Remover',
                    action: () => {
                        deleteImportacaoWhitelist(data.index)
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
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Chassi',
                field: 'chassi',
                chartDataType: 'series'
            },
            {
                headerName: 'Marca',
                field: 'marca',
                chartDataType: 'series',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
                chartDataType: 'series',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cor',
                field: 'cor',
                chartDataType: 'series',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Tipo de Ocorrência',
                field: 'textoOcorrencia',
                chartDataType: 'series',
                width: 180,
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options)  {
                    let data = options.data;
                    let tableId = "tableImportacaoWhitelist";
                    let dropdownId = "dropdown-menu-whitelist-importacoes-" + data.index
                    let buttonId = "dropdownMenuButtonBlacklisWhitelistImportacao_" + data.index

                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdownModal('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:deleteImportacaoWhitelist(${data.index})" style="cursor: pointer; color: black;">Remover</a>
                            </div>
                        </div>
                    </div>`;
                }
            },

        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 100,
            minHeight: 100,
            filter: true,
            resizable: true,
        },
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 5,
        rowSelection: 'single',
        localeText: localeText,
        getContextMenuItems: getContextMenuItemsImportarWhitelist,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('index' in data) {
                    deleteImportacaoWhitelist(data.index)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar o registro.')
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableImportacaoWhitelist');
    ImportacaoWhitelist = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
    ListaImportacaoWhitelist = dados;
}

function deleteImportacaoWhitelist() {
    gridApi = ImportacaoWhitelist.gridOptions.api;
    var selectedRow = gridApi.getFocusedCell()
    if (!selectedRow) {
        showAlert('warning', 'Nenhum registro selecionado!');
        return;
    }
    ListaImportacaoWhitelist.splice(selectedRow.rowIndex, 1);
    preencherImportacaoWhitelist(ListaImportacaoWhitelist)
    showAlert('success', 'Removido com sucesso!');
}

$(document).ready(function() {
    var dropdown = $('#opcoes_importacao_whitelist');

    $('#dropdownMenuButtonWhitelistImportacao').click(function() {
        dropdown.toggle();
    });

    $(document).click(function(event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonWhitelistImportacao') {
            dropdown.hide();
        }
    });

    $(document).on('contextmenu', function () {
        dropdown.hide();
    });

});

function limparImportacaoWhitelistRemocao() {
    preencherImportacaoWhitelistRemocao();
    ListaImportacaoWhitelist = [];
}

function importarWhitelistRemocao(){
    $('#importarWhitelistRemocao').modal({
        backdrop: 'static',
        keyboard: false
    });
    ListaImportacaoWhitelist = [];
}

function preencherImportacaoWhitelistRemocao(dados){
    stopAgGRIDImportacaoWhitelistRemocao();

    function getContextMenuItemsImportarWhitelistRemocao(params) {
        if (params && params.node && 'data' in params.node && 'index' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    name: 'Remover',
                    action: () => {
                        deleteImportacaoWhitelistRemocao(data.index)
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
                width: 100, 
                suppressSizeToFit: true
            },
            {
                headerName: 'Motivo',
                field: 'motivo',
                chartDataType: 'series',
                width: 300, 
                suppressSizeToFit: true
            },
            {
                headerName: 'Chassi',
                field: 'chassi',
                chartDataType: 'series',
                width: 100, 
                suppressSizeToFit: true
            },
            {
                headerName: 'Marca',
                field: 'marca',
                chartDataType: 'series',
                width: 100, 
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
                chartDataType: 'series',
                width: 100, 
                suppressSizeToFit: true
            },
            {
                headerName: 'Cor',
                field: 'cor',
                chartDataType: 'series',
                width: 100, 
                suppressSizeToFit: true
            },
            {
                headerName: 'Tipo de Ocorrência',
                field: 'textoOcorrencia',
                chartDataType: 'series',
                width: 180, 
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options)  {
                    let data = options.data;
                    let tableId = "tableImportacaoWhitelistRemocao";
                    let dropdownId = "dropdown-menu-whitelist-importacoes-remocao" + data.index
                    let buttonId = "dropdownMenuButtonBlacklisWhitelistImportacaoRemocao_" + data.index

                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdownModal('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:deleteImportacaoWhitelistRemocao(${data.index})" style="cursor: pointer; color: black;">Remover</a>
                            </div>
                        </div>
                    </div>`;
                }
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 100,  
            minHeight: 100,
            filter: true,
            resizable: true,
        },
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 5,
        rowSelection: 'single',
        localeText: localeText,
        getContextMenuItems: getContextMenuItemsImportarWhitelistRemocao,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('index' in data) {
                    deleteImportacaoWhitelistRemocao(data.index)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar o registro.')
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableImportacaoWhitelistRemocao');
    ImportacaoWhitelist = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
    ListaImportacaoWhitelist = dados;
}

function deleteImportacaoWhitelistRemocao() {
    gridApi = ImportacaoWhitelist.gridOptions.api;
    var selectedRow = gridApi.getFocusedCell()
    if (!selectedRow) {
        showAlert('warning', 'Nenhum registro selecionado!');
      return;
    }
    ListaImportacaoWhitelist.splice(selectedRow.rowIndex, 1);
    preencherImportacaoWhitelistRemocao(ListaImportacaoWhitelist)
    showAlert('success', 'Removido com sucesso!');
}

//Visibilidade

function showLoadingSalvarWhitelistButton() {
    $('#btnSalvarWhitelist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarWhitelistButton() {
    $('#btnSalvarWhitelist').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarImportacaoWhitelistButton() {
    $('#btnSalvarImportacaoWhitelist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarImportacaoWhitelistButton() {
    $('#btnSalvarImportacaoWhitelist').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarImportacaoWhitelistRemocaoButton() {
    $('#btnSalvarImportacaoWhitelistRemocao').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarImportacaoWhitelistRemocaoButton() {
    $('#btnSalvarImportacaoWhitelistRemocao').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarButtonAssociacaoWhitelist() {
    $('#btnSalvarAssociacaoWhitelist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonAssociacaoWhitelist() {
    $('#btnSalvarAssociacaoWhitelist').html('Salvar').attr('disabled', false);
}

function stopAgGRIDWhitelist() {
    var gridDiv = document.querySelector('#tableWhitelist');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperWhitelist');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableWhitelist" class="ag-theme-alpine my-grid" style="height: 100% !important"></div>';
    }
}

function stopAgGRIDImportacaoWhitelist() {
    var gridDiv = document.querySelector('#tableImportacaoWhitelist');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperImportacaoWhitelist');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoWhitelist" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDImportacaoWhitelistRemocao() {
    var gridDiv = document.querySelector('#tableImportacaoWhitelistRemocao');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperImportacaoWhitelistRemocao');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoWhitelistRemocao" class="ag-theme-alpine my-grid"></div>';
    }
}