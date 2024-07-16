$(document).ready(function() {
    $("#ocorrencia lacklist").select2({
        placeholder: "Selecione o tipo de ocorrência",
        allowClear: true,
        language: "pt-BR",
    })

    $("#ocorrenciaBlacklist").val('').trigger("change");

    $('#BtnAdicionarBlacklist').on('click', function () {
        abrirBlacklist();
        $('#idBlacklist').val('0');
    });

    $('#btnSalvarImportacaoBlacklist').on('click', function () {
        var seguradoraImportacao = $('#seguradoraImportacao').val();
        var usuarioImportacao = $('#usuarioImportacao').val();

        if (!seguradoraImportacao) {
            showAlert('warning', 'Selecione um cliente para importar as hot lists!')
            return;
        }

        if (!usuarioImportacao) {
            showAlert('warning', 'Não foi possível identificar o usuário realizando a ação! Tente novamente!')
            return;
        }

        if (!ListaImportacao || ListaImportacao.length == 0) {
            showAlert('warning', 'Adicione itens para poder importar')
            return;
        }
        salvarImportacaoBlacklist();
    });

    $('#BtnImportarBlacklist').on('click', function () {
        $.ajax({
            cache: false,
            url: Router + '/getUserId',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#usuarioImportacao').val(data.id);
            }
        });

        $('#seguradoraImportacao').val('').trigger('change');
        $('#arquivoItens').val('');

        importarBlacklist();
        limparImportacaoBlacklist();
    });

    var dropdown_import_blacklist = $('#opcoes_importacao_blacklist');
    
    $('#dropdownMenuButtonBlacklistImportacao').click(function() {
        dropdown_import_blacklist.toggle();
    });
    
    $(document).click(function(event) {
        if (!dropdown_import_blacklist.is(event.target) && event.target.id !== 'dropdownMenuButtonBlacklistImportacao') {
            dropdown_import_blacklist.hide();
        }
    });

    $('#BtnImportarBlacklistRemocao').on('click', function() {
        $.ajax({
            cache: false,
            url: Router + '/getUserId',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#usuarioImportacaoBlacklistRemocao').val(data.id);
            }
        });
 
        $('#seguradoraImportacaoBlacklistRemocao').val('').trigger('change');
        $('#arquivoItensBlacklistRemocao').val('');
       
        importarBlacklistRemocao();
        limparImportacaoBlacklistRemocao();
    });

    $('#btnSalvarImportacaoBlacklistRemocao').on('click', function() {
        var seguradoraImportacao = $('#seguradoraImportacaoBlacklistRemocao').val();
        var usuarioImportacao = $('#usuarioImportacaoBlacklistRemocao').val();
        if (!seguradoraImportacao) {
            showAlert('warning', 'Selecione um cliente para importar as hot lists!')
            return;
        }
        if (!usuarioImportacao) {
            showAlert('warning', 'Não foi possível identificar o usuário realizando a ação! Tente novamente!')
            return;
        }
        if(!ListaImportacao || ListaImportacao.length == 0){
            showAlert('warning', 'Adicione itens para poder remover' )
            return;
        }
        salvarImportacaoBlacklistRemocao();
    });

    $('#formBlacklist').on('submit', function  (e) {
        e.preventDefault();
        let cliente = GetClienteID();
        let id = $('#idBlacklist').val();

        if ((cliente == '0' || !cliente) && !id) {
            showAlert('warning', 'Cliente Obrigatório, por favor selecione.')
            return;
        }
        salvarBlacklist();
    });

    $("#info-icon").click(function (e) {
        $("#modalModeloItens").modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#limparTabelaItens").click(function (e) {
        limparImportacaoBlacklist()
    });

    $("#info-icon-blacklist-remocao").click(function (e) {
        $("#modalModeloItensRemocao").modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#limparTabelaItensBlacklistRemocao").click(function (e) {
        limparImportacaoBlacklistRemocao()
    });
    $("#associacaoBlacklist").on("shown.bs.modal", function () {
        var labelHotList = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Selecione a placa da hot list a ser desassociada." : "Selecione a placa da hot list a ser associada.";
        var placeholderText = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Selecione a placa da hot list a ser desassociada..." : "Selecione a placa da hot list a ser associada...";      
        var labelHotList = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Selecione a placa da hot list a ser desassociada." : "Selecione a placa da hot list a ser associada.";
        var labelCheckbox = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Desassociar todas as placas:" : "Associar todas as placas:";

        $("label[for='associarHotList']").text(labelCheckbox);
        $("#associarHotList").prop("checked", false);
        $("#inputPlacas").val("").prop("disabled", false);
        $("#labelPlacasBlacklistsEditar").text(labelHotList)
        $("#placasBlacklistsEditar")
          .select2({
            placeholder: placeholderText,
            allowClear: true,
            language: 'pt-BR'
          }).prop("disabled", false).val(null).trigger("change");
      });
    
      $("#associacaoBlacklist").on("hide.bs.modal", function () {
        $('#placasBlacklists').prop("disabled", false);
    });
    
    
      $("#associarHotList").change(function () {
        var placeholderText = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Selecione a placa da hot list a ser desassociada..." : "Selecione a placa da hot list a ser associada...";
        var labelHotList = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Selecione a placa da hot list a ser desassociada." : "Selecione a placa da hot list a ser associada.";
        var isChecked = $(this).is(":checked");

        $("#placasBlacklists").prop("disabled", isChecked);
        $("#inputPlacas").val("").prop("disabled", isChecked);
        $("#labelPlacasBlacklistsEditar").text(labelHotList)
        $('#placasHotlistsEditar').prop("disabled", isChecked);
        $("#placasBlacklists").val(null).trigger("change").prop("disabled", isChecked); 
        $("#placasBlacklistsEditar").val(null).trigger("change").prop("disabled", isChecked); 
        $("#placasBlacklists").select2({
        placeholder: placeholderText,
        allowClear: false,
      })
        if($("#titleAssociacaoBlacklist").text().includes("desassociação")){
            $("#clienteBusca").val(); //Pega o id_cliente
            $("#idAlertasEmailBlacklist").val(); // Pega id_email
            $("label[for='associarHotList']").text("Dessasociar todas as placas:");
              
            $('#BtnImportarBlacklist').on('click', function () {
                   associacaoLoteHotList()
            })
    
    }});
});


// REQUISIÇÕES

function getDadosBlackList(callback, options) {
    //var route;

    if (options) {
        if (options.placa || options.cliente) {
            showLoadingPesquisarButton();
        } else {
            showAlert('warning', 'Dados Insuficientes para fazer uma busca')
            resetPesquisarButton();
        }

    }

    if (typeof callback === "function") callback(null);
}


function salvarImportacaoBlacklistRemocao() {
    showLoadingSalvarImportacaoBlacklistRemocaoButton()

    let route = Router + '/removerImportacaoHotlist';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { ListaImportacao: JSON.stringify(ListaImportacao) },
        dataType: 'json',
        success: function (data) {
            $('#header-resposta-importacao').html('Relatório de Importação - Remover Hot List')
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

                ListaImportacao = [];
                limparImportacaoBlacklistRemocao();
                atualizarTableBlacklist();
                $('#importarBlacklistRemocao').modal('hide');
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
            resetSalvarImportacaoBlacklistRemocaoButton();
        },
        error: function (error) {
            $('#titulo-resposta').html('<i class="fa fa-times-circle-o" aria-hidden="true"></i> Falha!').addClass('title-danger').removeClass('title-success');
            $('#mensagem-resposta').text('Erro ao enviar solicitação.');
            $('#modal-resposta-importacao').modal({
                backdrop: 'static',
                keyboard: false
            });
            resetSalvarImportacaoBlacklistRemocaoButton();
        }
    });
}

function getBlackListByID(id) {
    let route = Router + '/buscarBlacklistByID';
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                preencherBlacklist(data.resultado);
                HideLoadingScreen();
                $("#blacklist").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                showAlert('error', 'Dados não encontrados')
                HideLoadingScreen();
            }
        },
        error: function (error) {
            showAlert('error', 'Dados não encontrados')
            HideLoadingScreen();
        }
    });
}


function deleteBlackList(id, status)  {
    let route = Router + '/deletarBlacklist';

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
                            showAlert('success', 'Item removido com sucesso')
                            atualizarTableBlacklist();
                            HideLoadingScreen();
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
        showAlert('warning', "Essa Hot List já foi removida!")
        HideLoadingScreen();
    }

}

function salvarBlacklist() {
    let id = $('#idBlacklist').val();
    showLoadingSalvarButton()

    let route = Router + '/atualizarBlacklist';

    if (id == '0') {
        route = Router + '/adicionarBlacklist';
    }

    let blacklist = $('#formBlacklist').serialize();

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: blacklist + '&clienteID=' + GetClienteID(),
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                showAlert('success', 'Hotlist salva com sucesso')
                atualizarTableBlacklist();
            } else if (data.status == 400) {
                if ('resultado' in data && 'mensagem' in data.resultado) {
                    showAlert('warning', data['resultado']['mensagem'])
                } else {
                    showAlert('error', 'Erro ao enviar requisição')
                }
            } else {
                showAlert('error', 'Erro ao enviar requisição')
            }
            resetSalvarButton()
        },
        error: function (error) {
            showAlert('error', 'Erro ao enviar requisição')
            resetSalvarButton()
        }
    });
}

function salvarImportacaoBlacklist() {
    let id = $('#idBlacklist').val();
    showLoadingSalvarImportacaoButton()

    let route = Router + '/adicionarImportacaoBlacklist';

    let blacklist = $('#formBlacklist').serialize();

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { ListaImportacao: JSON.stringify(ListaImportacao) },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200 || data.status == 400) {
                var resultado = data.resultado;
                var showFalhas = false;
                $('#header-resposta-importacao').html('Relatório de Importação - Adicionar Hot List')
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
                            if (index < 500) {
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
                ListaImportacao = [];
                limparImportacaoBlacklist();
                atualizarTableBlacklist();
                $('#importarBlacklist').modal('hide');
            } else {
                showAlert('error', 'Erro ao enviar requisição');
            }
            resetSalvarImportacaoButton()
        },
        error: function (error) {
            showAlert('error', 'Erro ao enviar requisição')
            resetSalvarImportacaoButton()
        }
    });
}

function atualizarTableBlacklist() {
    $("#blacklist").modal('hide')

    var searchOptions = null;

    
    searchOptions = {
        placa: $("#placaBusca").val(),
        cliente: $("#clienteBusca").val(),
    };
    

    if (searchOptions && (searchOptions.placa || searchOptions.cliente)) {
        getDadosBlackList(function (error) {
            if (!error) {
                atualizarAgGridBlackList(searchOptions);
            } else {
                atualizarAgGridBlackList();
            }
        }, searchOptions);
    } else {
        getDadosBlackList(function (error) {
            if (!error) {
                atualizarAgGridBlackList();
            } else {
                atualizarAgGridBlackList();
            }
        });
    }
}

function abrirBlacklist(id, status) {

    ShowLoadingScreen();
    limparBlacklist();

    if (id) {
        if (status && status == 'Ativo') {
            $("#titleBlacklist").html('Editar Hot List')
            $('#divCliente').hide();
            $('.divSeguradoraBlacklist').hide();
            $('#seguradoraBlacklist').attr('required', false)
            getBlackListByID(id);
        } else {
            showAlert('warning', 'Essa Hot List não pode ser editada, pois está inativa!');
            HideLoadingScreen();
        }
    } else {
        $.ajax({
            cache: false,
            url: Router + '/getUserId',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#usuarioBlacklist').val(data.id);
            },
            error: function () {
                showAlert('error', 'Não foi possível pegar os dados do usuário logado. Por favor, tente novamente!')
                return;
            }
        });
        $("#titleBlacklist").html('Cadastrar Hot List')
        $('#divCliente').show();
        $('.divSeguradoraBlacklist').show();
        $('#seguradoraBlacklist').attr('required', true)
        HideLoadingScreen();
        $("#blacklist").modal({
            backdrop: 'static',
            keyboard: false
        });
    }
}

function abrirAssociacaoBlacklist(id, id_cliente) {

    ShowLoadingScreen();
    $('#idAlertasEmailBlacklist').val(id);
    $('#idClienteHotList').val(id_cliente);
    $('#removeModal').val(0);
    $('#divPlacasBlacklists').show();
    $('#divPlacasBlacklistsEditar').hide();
    $('#titleAssociacaoBlacklist').text('Solicitar associação de Hot List');
    $('#labelPlacasBlacklists').text('Selecione as placas das hot lists a serem associadas:');
    $('label[for="associarColdList"]').text('Associar todas as placas:');

    var placeholderText = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Selecione a placa da hot list a ser desassociada..." : "Selecione a placa da hot list a ser associada...";

    let route = Router + '/buscar_placas_blacklist';
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            id_cliente: id_cliente
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                if (data.resultado.length > 0) {
                    $("#placasBlacklists").select2({
                        data: data.resultado,
                        placeholder: placeholderText,
                        allowClear: true,
                        language: "pt-BR",
                        width: 'resolve',
                        height: '32px',
                        multiple: true
                    })
                    $("#placasBlacklists").select2('val', ' ');

                    $("#associacaoBlacklist").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    showAlert('warning', 'O cliente não possui hot lists cadastradas!');
                }

            } else if (data.status == 400) {
                showAlert('warning', data['resultado']['mensagem'])
            } else if (data.status == 404) {
                showAlert('warning', 'O cliente não possui hot lists cadastradas!');
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

function abrirDesassociacaoBlacklist(id, id_cliente) {
    var placeholderText = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? "Selecione a placa da hot list a ser desassociada..." : "Selecione a placa da hot list a ser associada...";

    ShowLoadingScreen();
    $('#idAlertasEmailBlacklist').val(id);
    $('#idClienteHotList').val(id_cliente);
    $('#removeModal').val(1);
    $('#divPlacasBlacklists').hide();
    $('#divPlacasBlacklistsEditar').show();
    $('#titleAssociacaoBlacklist').text('Solicitar desassociação de Hot List');
    $('#labelPlacasBlacklists').text('Selecione as placas da hot list a serem desassociadas:');
    $('label[for="associarColdList"]').text('Desassociar todas as placas:');

    let route = Router + '/buscar_placas_blacklist_associadas';
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
                    $("#placasBlacklistsEditar").empty()
                    $("#placasBlacklistsEditar").select2({
                        data: data.resultado,
                        placeholder: placeholderText,
                        allowClear: true,
                        language: "pt-BR",
                        width: 'resolve',
                        height: '32px',
                        multiple: true
                    })
                    $("#placasBlacklistsEditar").select2('val', ' ')

                    $("#associacaoBlacklist").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    $("#placasBlacklistsEditar").empty()
                    $("#placasBlacklistsEditar").select2({
                        placeholder: placeholderText,
                        allowClear: true,
                        multiple: true
                    })
                    $("#placasBlacklistsEditar").select2('val', ' ')
                    $("#associacaoBlacklist").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }

            } else if (data.status == 400) {
                showAlert('warning', data['resultado']['mensagem'])
            } else if (data.status == 404) {
                $("#placasBlacklistsEditar").empty()
                $("#placasBlacklistsEditar").select2({
                    placeholder: placeholderText,
                    allowClear: true,
                    multiple: true
                })
                $("#placasBlacklistsEditar").select2('val', ' ')
                $("#associacaoBlacklist").modal({
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

function desassociarPlacaAbaBlacklist(idAlerta, idPlaca){
    let route = Router + '/removerAssociacaoBlacklist';
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
                    id_blacklist: idPlaca
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        showAlert('success', 'Desassociação realizada com sucesso!');
                        $("#associacaoBlacklist").modal('hide');

                    } else if (data.status == 400) {
                        showAlert('warning', 'O valor enviado não possui associação à esse alerta de e-mail!')
                    } else if (data.status == 404) {
                        showAlert('warning', 'O valor enviado não possui associação à esse alerta de e-mail!')
                    } else {
                        showAlert('error', 'Erro ao realizar a desassociação. Tente novamente!')
                    }
                    resetSalvarButtonAssociacaoBlacklist()
                    atualizarAgGridAbaBlacklist();
                },
                error: function (error) {
                    showAlert('error', 'Erro ao realizar a desassociação. Tente novamente!')
                    resetSalvarButtonAssociacaoBlacklist()
                }
            });
        }
    });
}

$("#btnSalvarAssociacaoBlacklist").click(function () {
    showLoadingSalvarButtonAssociacaoBlacklist();
    var associar = $("#titleAssociacaoBlacklist").text().includes(" associação ") ? true : false;
    var desassociar = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? true : false;
    var checked = $("#associarHotList").is(":checked");
    var idAlerta = $("#idAlertasEmailBlacklist").val();
    var idCliente = $("#idClienteHotList").val();

    console.log();


    if (associar && checked) { 
        
    let route = Router + "/adicionarAssociacaoAllBlacklist";
      $.ajax({
        cache: false,
        url: route,
        type: "POST",
        data: {
            idAlerta: idAlerta,
            idCliente: idCliente
        },
        dataType: "json",
        success: function (data) {
          if (data.status == 200) {
            showAlert('success', "Solicitação de associação realizada com sucesso!");
            $("#associacaoBlacklist").modal("hide");
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

          resetSalvarButtonAssociacaoBlacklist();
        },
        error: function (error) {
          showAlert('error', "Erro ao realizar a solicitação de associação.");
          resetSalvarButtonAssociacaoBlacklist();
        },
      });
      
    } else if (desassociar && checked) {
      let route = Router + "/desassociarAllBlacklist";
      $.ajax({
        cache: false,
        url: route,
        type: "POST",
        data: {
            idAlerta: idAlerta,
            idCliente: idCliente
        },
        dataType: "json",
        success: function (data) {
          if (data.status == 200) {
            showAlert('success', "Solicitação de desassociação realizada com sucesso!");
            $("#associacaoBlacklist").modal("hide");
            atualizarTableAlertasEmail();
          } else if (data.status == 400 || data.status == 404) {
            if ('mensagem' in data.resultado) {
                showAlert('warning', data.resultado["titulo"] + " " + data.resultado["mensagem"]);
            } else {
                showAlert('error', "Erro ao realizar a solicitação de desassociação. Tente novamente!");
            }
          } else {
            showAlert('error', "Erro ao realizar a desassociação. Tente novamente!");
          }
          resetSalvarButtonAssociacaoBlacklist();
        },
        error: function (error) {
          showAlert('error', "Erro ao realizar a desassociação. Tente novamente!");
          resetSalvarButtonAssociacaoBlacklist();
        },
      });
    } else {
      showLoadingSalvarButtonAssociacaoBlacklist();
      var id_alerta_email = $("#idAlertasEmailBlacklist").val();
      var ids_blacklist = $("#placasBlacklists").val();
      var desassociar = $("#titleAssociacaoBlacklist").text().includes("desassociação") ? true : false;

      if (!desassociar) {
        if (ids_blacklist.length == 0) {
            resetSalvarButtonAssociacaoWhitelist();
            showAlert('warning', 'Selecione pelo menos uma placa para associar!')
            resetSalvarButtonAssociacaoBlacklist();
            return;
        }
        let route = Router + "/adicionarAssociacaoLoteBlacklist";
        $.ajax({
          cache: false,
          url: route,
          type: "POST",
          data: {
            id_cliente: idCliente,
            id_alerta_email: id_alerta_email,
            ids_blacklist: ids_blacklist,
          },
          dataType: "json",
          success: function (data) {
            if (data.status == 201) {
                showAlert('success', "Solicitação de associação realizada com sucesso! Verifique o status na tela de Processamento em Lote.");

              $("#associacaoBlacklist").modal("hide");
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
            resetSalvarButtonAssociacaoBlacklist();
          },
          error: function (error) {
            showAlert('error', "Erro ao realizar a solicitação de associação.");
            resetSalvarButtonAssociacaoBlacklist();
          },
        });
      } else {
        var id_blacklist = $("#placasBlacklistsEditar").val();

        if (id_blacklist.length == 0) {
            resetSalvarButtonAssociacaoBlacklist();
            showAlert('warning', 'Selecione pelo menos uma placa para desassociar!')
            return;
        }

        let route = Router + "/desassociarLoteBlacklist";
        $.ajax({
          cache: false,
          url: route,
          type: "POST",
          data: {
            id_cliente: idCliente,
            id_alerta_email: id_alerta_email,
            id_blacklist: id_blacklist,
          },
          dataType: "json",
          success: function (data) {
            if (data.status == 201) {
                showAlert('success', "Solicitação de desassociação realizada com sucesso! Verifique o status na tela de Processamento em Lote.");
                $("#associacaoBlacklist").modal("hide");
                atualizarTableAlertasEmail();
            } else if (data.status == 400 || data.status == 404) {
                if ('titulo' in data.resultado && 'mensagem' in data.resultado) {
                    showAlert('warning', data.resultado.titulo + ' ' + data.resultado.mensagem);
                } else {
                    showAlert('error', 'Erro ao realizar a solicitação de desassociação. Tente novamente!');
                }
            } else {
              showAlert('error', "Erro ao realizar a solicitação de desassociação. Tente novamente!");
            }
            resetSalvarButtonAssociacaoBlacklist();
          },
          error: function (error) {
            showAlert('error', "Erro ao realizar a solicitação de desassociação. Tente novamente!");
            resetSalvarButtonAssociacaoBlacklist();
          },
        });
      }
    }
  });


// Utilitários
function preencherBlacklist(dados)  {
    $('#idBlacklist').val(dados['id']);
    $('#usuarioBlacklist').val(dados['id_usuario_importacao']);
    $('#placaBlacklist').val(dados['placa']);
    $('#chassiBlacklist').val(dados['chassi']);
    $('#ocorrenciaBlacklist').val(dados['tipo_ocorrencia']).trigger('change');
    $('#modeloBlacklist').val(dados['modelo']);
    $('#marcaBlacklist').val(dados['marca']);
    $('#corBlacklist').val(dados['cor']);
}

function limparBlacklist() {
    $('#idBlacklist').val('');
    $('#usuarioBlacklist').val('');
    $('#placaBlacklist').val('');
    $('#chassiBlacklist').val('');
    $('#ocorrenciaBlacklist').val('').trigger('change');
    $('#modeloBlacklist').val('');
    $('#marcaBlacklist').val('');
    $('#corBlacklist').val('');
    $("#seguradoraBlacklist").val('').trigger('change')
    $("#usuarioBlacklist").val('').trigger('change');
}

function GetClienteID() {
    let tipo = $('#tipo-busca').val()
    let cliente_id = 0;
    if (tipo == 0) {
        cliente_id = idClienteDoc
    } else if (tipo == 1) {
        cliente_id = $('#cliente').val()
    } else if (tipo == 2) {
        cliente_id = idClienteDoc
    }
    return cliente_id;
}

function importarItensExcel(event) {
    event.preventDefault();
    const fileInput = document.getElementById('arquivoItens');
    const file = fileInput.files[0];

    if (!file) {
        showAlert('warning', 'Por favor, selecione um arquivo.');
        return;
    }

    var seguradoraImportacao = 0;
    if ($("#seguradoraImportacao").val()) {
        seguradoraImportacao = $("#seguradoraImportacao").val();
    } else {
        showAlert('warning', 'Selecione o cliente antes de importar')
        return;
    }

    var usuarioImportacao = 0;
    if ($("#usuarioImportacao").val()) {
        usuarioImportacao = $("#usuarioImportacao").val();
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
                                id_cliente: seguradoraImportacao,
                                id_usuario_importacao: usuarioImportacao,
                        });

                } else {
                    valorIncompleto = true;
                }
            }else {
                breakLimit = true;
            }

        });

        if (valorIncompleto) {
            HideLoadingScreen()
            showAlert('warning', 'O arquivo possui algumas Hot Lists sem Placa. Verifique e tente novamente.');
            return;
        } else {
            HideLoadingScreen()
            if(breakLimit) {showAlert('warning', 'Desculpe, a planilha excede o limite de registros. Como resultado, apenas os primeiros 500 registros foram adicionados.')}
            preencherImportacaoBlacklist(dadosInserir);
        }
    };

    reader.readAsArrayBuffer(file);

}


// AGGRID
var AgGridBlackList;
function atualizarAgGridBlackList(options) {
    stopAgGRIDBlacklist();

    function getServerSideDadosBlacklist() {
        return {
            getRows: (params) => {
        
            var route = Router + '/buscarBlacklistServerSide';
        
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    startRow: params.request.startRow,
                    endRow: params.request.endRow,
                    placa: options ? options.placa : '',
                    cliente: options ? options.cliente : '',
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
                            if (chave === 'tipo_ocorrencia') {
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
                    AgGridBlackList.gridOptions.api.showNoRowsOverlay();
                } else {
                    showAlert('error', 'Erro na solicitação ao servidor');
                    params.failCallback();
                    params.success({
                        rowData: [],
                        rowCount: 0,
                    });
                    AgGridBlackList.gridOptions.api.showNoRowsOverlay();
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
                    AgGridBlackList.gridOptions.api.showNoRowsOverlay();
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

    function getContextMenuItemsHotlist(params) {
        if (params && params.node && 'data' in params.node && 'id' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    name: 'Editar',
                    action: () => {
                        abrirBlacklist(data.id, data.status)
                    },
                },
                {
                    name: 'Remover',
                    action: () => {
                        deleteBlackList(data.id, data.status)
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
                chartDataType: 'series'
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
                field: 'tipo_ocorrencia',
                width: 180,
                suppressSizeToFit: true,
                chartDataType: 'category'
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'nome',
                chartDataType: 'category'
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
                    let tableId = "tableBlacklist";
                    let dropdownId = "dropdown-menu-blacklist-" + data.id;
                    let buttonId = "dropdownMenuButtonBlacklist_" + data.id;
                
                    return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdown('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirBlacklist(${data.id}, '${data.status}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:deleteBlackList(${data.id}, '${data.status}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
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
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-blacklist').val()),
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        getContextMenuItems: getContextMenuItemsHotlist,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('id' in data) {
                    abrirBlacklist(data.id, data.status)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar a Hot List.')
                }
            }
        }
    };

    $('#select-quantidade-por-pagina-blacklist').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-blacklist').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableBlacklist');
    gridDiv.style.setProperty('height', '519px');

    AgGridBlackList = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDadosBlacklist();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesBlacklist(gridOptions);
}

function importarBlacklist() {
    $('#importarBlacklist').modal({
        backdrop: 'static',
        keyboard: false
    });
    ListaImportacao = [];
}

var ImportacaoBlacklist;
var ListaImportacao;
function preencherImportacaoBlacklist(dados) {
    stopAgGRIDImportacaoBlacklist();

    function getContextMenuItemsImportarHotlist(params) {
        if (params && params.node && 'data' in params.node && 'index' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    name: 'Remover',
                    action: () => {
                        deleteImportacaoBlacklist(data.index)
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
                    let tableId = "tableImportacaoBlacklist";
                    let dropdownId = "dropdown-menu-blacklist-importacoes-" + data.index;
                    let buttonId = "dropdownMenuButtonBlacklisImportacao_" + data.index;
                
                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdownModal('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:deleteImportacaoBlacklist(${data.index})" style="cursor: pointer; color: black;">Remover</a>
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
            resizable: false,
        },
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 5,
        rowSelection: 'single',
        localeText: localeText,
        getContextMenuItems: getContextMenuItemsImportarHotlist,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('index' in data) {
                    deleteImportacaoBlacklist(data.index)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar o registro.')
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableImportacaoBlacklist');
    ImportacaoBlacklist = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
    ListaImportacao = dados;
}

function importarItensExcelBlacklistRemocao(event) {
    event.preventDefault();
    const fileInput = document.getElementById('arquivoItensBlacklistRemocao');
    const file = fileInput.files[0];

    if (!file) {
        showAlert('warning', 'Por favor, selecione um arquivo.');
        return;
    }

    var seguradoraImportacao = 0;
    if ($("#seguradoraImportacaoBlacklistRemocao").val()) {
        seguradoraImportacao = $("#seguradoraImportacaoBlacklistRemocao").val();
    } else {
        showAlert('warning', 'Selecione o cliente antes de importar')
        return;
    }

    var usuarioImportacao = 0;
    if ($("#usuarioImportacaoBlacklistRemocao").val()) {
        usuarioImportacao = $("#usuarioImportacaoBlacklistRemocao").val();
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

                if (arrayProcessado.includes("placa") &&
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
            showAlert('warning', 'O arquivo possui algumas Hot Lists sem Placa ou Motivo. Verifique e tente novamente.');
            return;
        } else {
            HideLoadingScreen()
            if(breakLimit) {showAlert('warning', 'Desculpe, a planilha excede o limite de registros. Como resultado, apenas os primeiros 500 registros foram adicionados.')}
            preencherImportacaoBlacklistRemocao(dadosInserir);
        }
    };

    reader.readAsArrayBuffer(file);

}

function preencherImportacaoBlacklistRemocao(dados){
    stopAgGRIDImportacaoBlacklistRemocao();

    function getContextMenuItemsImportarHotlistRemocao(params) {
        if (params && params.node && 'data' in params.node && 'index' in params.node.data) {
            let data = params.node.data;
            var result = [
                {
                    name: 'Remover',
                    action: () => {
                        deleteImportacaoBlacklistRemocao(data.index)
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
                    let tableId = "tableImportacaoBlacklistRemocao";
                    let dropdownId = "dropdown-menu-blacklist-importacoes-remocao" + data.index;
                    let buttonId = "dropdownMenuButtonBlacklisImportacaoRemocao_" + data.index;
                
                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdownModal('${dropdownId}', '${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdownModal('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:deleteImportacaoBlacklistRemocao(${data.index})" style="cursor: pointer; color: black;">Remover</a>
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
        getContextMenuItems: getContextMenuItemsImportarHotlistRemocao,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('index' in data) {
                    deleteImportacaoBlacklistRemocao(data.index)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível identificar o registro.')
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableImportacaoBlacklistRemocao');
    ImportacaoBlacklist = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
    ListaImportacao = dados;
}

function deleteImportacaoBlacklist() {
    gridApi = ImportacaoBlacklist.gridOptions.api;
    var selectedRow = gridApi.getFocusedCell()
    if (!selectedRow) {
        showAlert('warning', 'Nenhum registro selecionado!');
        return;
    }

    ListaImportacao.splice(selectedRow.rowIndex, 1);
    preencherImportacaoBlacklist(ListaImportacao)
    showAlert('success', 'Removido com sucesso!');
}

function limparImportacaoBlacklist() {
    preencherImportacaoBlacklist();
    ListaImportacao = [];
}

function importarBlacklistRemocao() {
    $('#importarBlacklistRemocao').modal({
        backdrop: 'static',
        keyboard: false
    });
    ListaImportacao = [];
}

function limparImportacaoBlacklistRemocao() {
    preencherImportacaoBlacklistRemocao();
    ListaImportacao = [];
}

function deleteImportacaoBlacklistRemocao() {
    gridApi = ImportacaoBlacklist.gridOptions.api;
    var selectedRow = gridApi.getFocusedCell()
    if (!selectedRow) {
        showAlert('warning', 'Nenhum registro selecionado!');
      return;
    }
    ListaImportacao.splice(selectedRow.rowIndex, 1);
    preencherImportacaoBlacklistRemocao(ListaImportacao)
    showAlert('success', 'Removido com sucesso!');
}

function resetSalvarImportacaoBlacklistRemocaoButton() {
    $('#btnSalvarImportacaoBlacklistRemocao').html('Salvar').attr('disabled', false);
}

function stopAgGRIDImportacaoBlacklist() {
    var gridDiv = document.querySelector('#tableImportacaoBlacklist');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperImportacaoBlacklist');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoBlacklist" class="ag-theme-alpine my-grid"></div>';
    }
}

//Visibilidade

function showLoadingSalvarButton() {
    $('#btnSalvarBlacklist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButton() {
    $('#btnSalvarBlacklist').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarButtonAssociacaoBlacklist() {
    $('#btnSalvarAssociacaoBlacklist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonAssociacaoBlacklist() {
    $('#btnSalvarAssociacaoBlacklist').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarImportacaoButton() {
    $('#btnSalvarImportacaoBlacklist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarImportacaoButton() {
    $('#btnSalvarImportacaoBlacklist').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarImportacaoBlacklistRemocaoButton() {
    $('#btnSalvarImportacaoBlacklistRemocao').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function stopAgGRIDBlacklist() {
    var gridDiv = document.querySelector('#tableBlacklist');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperBlacklist');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableBlacklist" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDImportacaoBlacklist() {
    var gridDiv = document.querySelector('#tableImportacaoBlacklist');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperImportacaoBlacklist');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoBlacklist" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDImportacaoBlacklistRemocao() {
    var gridDiv = document.querySelector('#tableImportacaoBlacklistRemocao');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapperImportacaoBlacklistRemocao');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableImportacaoBlacklistRemocao" class="ag-theme-alpine my-grid-blacklist"></div>';
    }
}