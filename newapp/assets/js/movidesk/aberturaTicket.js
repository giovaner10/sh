var servicosPai = [];
var servicosFilho = [];
var servicoNivel3 = [];

$(document).ready(function () {
    $(window).resize(function () {
        checkScreenWidth();
    });

    // Verifica a largura da tela ao carregar a página
    checkScreenWidth();

    // Inicializa o select2 nos elementos do modal quando ele é mostrado
    $('#modalMovidesk').on('shown.bs.modal', function () {
        $('#servicoMovidesk').select2({});
        $('#subservicoMovidesk').select2({});
        $('#subservicoMovidesk').prop('disabled', true)
        $('#produtoMovidesk').select2({});
        $('#prestadoraMovidesk').select2({});
        $('#categoriaMovidesk').select2({});
        $('#urgenciaMovidesk').select2({});
        $('#servicoNivel3').select2({});

        getServicosPai();
    });

    //RESTAURA PADRÕES AO MINIMIZAR MODAL
    $('#modalMovidesk').on('hide.bs.modal', function () {
        limparCampos();
        ocultaInputNivel3();
        ocultaProdutos();
        $('.cc').css(
            'width', '95%',
        );
        
        servicosPai = [];
        servicosFilho = [];
        servicoNivel3 = [];
    })

    //AO SELECIONAR SERVIÇO, BUSCA OS SUBSERVIÇOS, DEFINE A URGÊNCIA E A CATEGORIA PADRÃO
    $('#servicoMovidesk').on('change', function () {
        $('#subservicoMovidesk').empty().append('<option value="" disabled selected>Carregando subserviços...</option>');
        $('#subservicoMovidesk').prop('disabled', true)

        //ESCONDE SERVIÇO NIVEL 3 E PRODUTOS
        ocultaInputNivel3();
        ocultaProdutos();
        $('.cc').css(
            'width', '95%',
        );

        getServicosFilho($('#servicoMovidesk').val());

        let servicoSelecionado = $('#servicoMovidesk').find(':selected').text();
        servicosPai.resultado?.forEach(function (servico) {
            if (servicoSelecionado == servico.name && servico.defaultUrgency != null) {
                $('#urgenciaMovidesk').val(servico.defaultUrgency).trigger('change');
            }

            if (servicoSelecionado == servico.name && servico.categories.length > 0) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Selecione a categoria</option>`);
                servico.categories.forEach(function (categoria) {
                    $('#categoriaMovidesk').append(`<option value="${categoria}">${categoria}</option>`);
                });
                servico.defaultCategory != null ? $('#categoriaMovidesk').val(servico.defaultCategory).trigger('change') : $('#categoriaMovidesk').val(null).trigger('change');
                return;
            }

            if (servicoSelecionado == servico.name && servico.categories.length < 1 && servico.defaultCategory != null) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Selecione a categoria</option>`);
                $('#categoriaMovidesk').append(`<option value="${servico.defaultCategory}">${servico.defaultCategory}</option>`);
                $('#categoriaMovidesk').val(servico.defaultCategory).trigger('change');
            }

            if (servicoSelecionado == servico.name && servico.categories.length < 1 && servico.defaultCategory == null) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Não há categorias</option>`);
            }
        })
    })

    //AO SELECIONAR SUBSERVIÇO, BUSCA OS SUBSERVIÇOS NIVEL 3, DEFINE A URGÊNCIA E A CATEGORIA PADRÃO
    $('#subservicoMovidesk').on('change', function () {
        let servicoSelecionado = $('#subservicoMovidesk').find(':selected').text();
        let servicoPai = $('#servicoMovidesk').find(':selected').text();

        //MOSTRA PRODUTOS DE ACORDO COM A CONDIÇÃO
        if (servicoPai == 'Fábrica de Software (Gestor)' && servicoSelecionado != 'Caderno de Negócios - Inovação') {
            $('.produto').show();
            $('.anexo').css(
                'width', '95%',
            );
            $('#produtoMovidesk').attr('required', 'required');
            $('.cc').css(
                'width', '45%',
            );
        } else {
            $('.produto').hide();
            $('.anexo').css(
                'width', '45%',
            );
            $('#produtoMovidesk').val(null).trigger('change');
            $('#produtoMovidesk').removeAttr('required');
            $('.cc').css(
                'width', '45%',
            );
        }

        getServicosFilho($('#subservicoMovidesk').val(), true)
        servicosFilho.resultado?.forEach(function (servicoFilho) {
            if (servicoSelecionado == servicoFilho.name && servicoFilho.defaultUrgency != null) {
                $('#urgenciaMovidesk').val(servicoFilho.defaultUrgency).trigger('change');
            }

            if (servicoSelecionado == servicoFilho.name && servicoFilho.categories.length > 0) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Selecione a categoria</option>`);
                servicoFilho.categories.forEach(function (categoria) {
                    $('#categoriaMovidesk').append(`<option value="${categoria}">${categoria}</option>`);
                });
                servicoFilho.defaultCategory != null ? $('#categoriaMovidesk').val(servicoFilho.defaultCategory).trigger('change') : $('#categoriaMovidesk').val(null).trigger('change');
                return;
            }

            if (servicoSelecionado == servicoFilho.name && servicoFilho.categories.length < 1 && servicoFilho.defaultCategory != null) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Selecione a categoria</option>`);
                $('#categoriaMovidesk').append(`<option value="${servicoFilho.defaultCategory}">${servicoFilho.defaultCategory}</option>`);
                $('#categoriaMovidesk').val(servicoFilho.defaultCategory).trigger('change');
            }

            if (servicoSelecionado == servicoFilho.name && servicoFilho.categories.length < 1 && servicoFilho.defaultCategory == null) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Não há categorias</option>`);
            }
        })
    })


    $('#servicoNivel3').on('change', function () {
        let servicoSelecionado = $('#servicoNivel3').find(':selected').text();

        servicoNivel3.resultado?.forEach(function (servicoFilhoNivel3) {
            if (servicoSelecionado == servicoFilhoNivel3.name && servicoFilhoNivel3.defaultUrgency != null) {
                $('#urgenciaMovidesk').val(servicoFilhoNivel3.defaultUrgency).trigger('change');
            }

            if (servicoSelecionado == servicoFilhoNivel3.name && servicoFilhoNivel3.categories.length > 0) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Selecione a categoria</option>`);
                servicoFilhoNivel3.categories.forEach(function (categoria) {
                    $('#categoriaMovidesk').append(`<option value="${categoria}">${categoria}</option>`);
                });
                servicoFilhoNivel3.defaultCategory != null ? $('#categoriaMovidesk').val(servicoFilhoNivel3.defaultCategory).trigger('change') : $('#categoriaMovidesk').val(null).trigger('change');
                return;
            }

            if (servicoSelecionado == servicoFilhoNivel3.name && servicoFilhoNivel3.categories.length < 1 && servicoFilhoNivel3.defaultCategory != null) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Selecione a categoria</option>`);
                $('#categoriaMovidesk').append(`<option value="${servicoFilhoNivel3.defaultCategory}">${servicoFilhoNivel3.defaultCategory}</option>`);
                $('#categoriaMovidesk').val(servicoFilhoNivel3.defaultCategory).trigger('change');
            }

            if (servicoSelecionado == servicoFilhoNivel3.name && servicoFilhoNivel3.categories.length < 1 && servicoFilhoNivel3.defaultCategory == null) {
                $('#categoriaMovidesk').empty();
                $('#categoriaMovidesk').append(`<option value="" disabled selected>Não há categorias</option>`);
            }
        })
    })

    $('#ticketForm').on('submit', function (e) {
        showLoadingButton();
        e.preventDefault();

        let body = {
            "email": emailUser,
            "assunto": $('#assuntoMovidesk').val(),
            "categoria": $('#categoriaMovidesk').val(),
            "urgencia": $('#urgenciaMovidesk').val(),
            "servico": $('#servicoMovidesk').find(':selected').text(),
            "cc": $('#ccMovidesk').val(),
            "servicoId": $('#servicoMovidesk').val(),
            "servicoNivel1": $('#servicoMovidesk').find(':selected').text(),
            "prestadora": $('#prestadoraMovidesk').val(),
            "clienteId": $('#clienteIdMovidesk').val(),
            "descricao": $('#mensagemMovidesk').val()
        }

        body.servicoNivel2 = $('#subservicoMovidesk').val() != null ? $('#subservicoMovidesk').find(':selected').text() : '';
        body.servicoNivel3 = $('#servicoNivel3').val() != null ? $('#servicoNivel3').find(':selected').text() : '';

        let route = RouterMovidesk + '/cadastrarTicket';
        $.ajax({
            url: route,
            type: 'POST',
            data: body,
            dataType: 'json',
            success: async function (response) {
                if (response.status == 200) {
                    if ($('#anexoMovidesk').val()) {
                        var formData = new FormData();
                        formData.append('protocolo', extrairNumerosProtocolo(response.resultado.mensagem));
                        formData.append('arquivo', document.getElementById('anexoMovidesk').files[0]);

                        await enviarAnexo(formData)
                            .then(retorno => {
                                if (retorno === "Arquivo anexado com sucesso.") {
                                    showAlert('success', response.resultado.mensagem);
                                    $('#modalMovidesk').modal('hide');

                                } else {
                                    showAlert('error', 'Erro ao anexar arquivo. Contate o suporte técnico.');
                                    $('#modalMovidesk').modal('hide');
                                }
                            })
                            .catch(error => {
                                showAlert('error', error);
                            });

                    } else {
                        showAlert('success', response.resultado.mensagem);
                        $('#modalMovidesk').modal('hide');
                    }

                } else if (response.status == 400) {
                    showAlert('error', 'Dados inconsistentes, verifique-os e tente novamente.');
                }
                else if (response.status == 404) {
                    showAlert('error', 'Recurso não encontrado, tente novamente mais tarde.');
                }
                else if (response.status == 500) {
                    showAlert('error', 'Erro ao cadastrar ticket. Contate o suporte técnico.');
                }
                resetButton();
            },
            error: function (error) {
                showAlert('error', 'Erro na solicitação ao servidor');
                resetButton();
            }
        });

    });

})

function enviarAnexo(form) {
    let route = RouterMovidesk + '/enviarAnexo';
    return new Promise((resolve, reject) => {
        return $.ajax({
            url: route,
            type: 'POST',
            data: form,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    resolve("Arquivo anexado com sucesso.");
                }
                else {
                    reject("Erro ao anexar arquivo.");
                }
            },
            error: function (error) {
                showAlert('error', 'Erro na solicitação ao servidor');
                resetButton();
            }
        });
    })
}

function extrairNumerosProtocolo(texto) {
    const numeros = texto.match(/\d+/g);
    if (numeros) {
        return numeros.join('');
    }
    return '';
}

function getServicosPai() {
    $('#servicoMovidesk').prop('disabled', true).append('<option value="">Carregando serviços...</option>');
    disableButton();
    let route = RouterMovidesk + '/getServicosPai';
    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            if (response.status == 200) {
                servicosPai = response;
                $('#servicoMovidesk').empty().append('<option value="" disabled selected>Selecione o serviço</option>');

                response.resultado?.forEach(function (servico) {
                    $('#servicoMovidesk').append('<option value="' + servico.id + '">' + servico.name + '</option>');
                });

                $('#servicoMovidesk').prop('disabled', false);
                $(document).trigger('servicosPaiLoaded');
            } else {
                $('#servicoMovidesk').empty().append('<option value="" disabled selected>Serviços não encontrados.</option>');
                showAlert('error', 'Serviços não encontrados.');
            }
            enableButon();
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
            enableButon();
        }
    });
}

function getServicosFilho(id, nivel3) {
    $('.labelServicoNivel3').html($('#subservicoMovidesk').find(':selected').text() + ': ' + '<span class="text-danger">*</span>')
    $('#servicoNivel3').empty().append('<option value="" disabled selected>Carregando opções...</option>')

    let route = RouterMovidesk + '/getServicosFilho';
    $.ajax({
        url: route,
        type: 'POST',
        data: {
            idPai: id
        },
        dataType: 'json',
        success: function (response) {
            if (nivel3 && response.resultado.length > 0) {
                servicoNivel3 = response;

                $('#servicoNivel3').prop('disabled', true);
                $('.servicoNivel3').show();
                $('#servicoNivel3').attr('required', 'required');


                $('.labelServicoNivel3').html($('#subservicoMovidesk').find(':selected').text() + ': ' + '<span class="text-danger">*</span>')
                $('.anexo').css(
                    'width', '95%',
                );
                $('.cc').css(
                    'width', '45%',
                );

                $('#servicoNivel3').empty().append('<option value="" disabled selected>Selecione uma opção</option>');

                response.resultado?.forEach(function (servico) {
                    $('#servicoNivel3').append('<option value="' + servico.id + '">' + servico.name + '</option>');
                });

                $('#servicoNivel3').prop('disabled', false);
            } else {
                if (response.status == 200 && response.resultado.length > 0) {
                    servicosFilho = response;
                    $('.labelSubservico').html('Subserviço' + ': ' + '<span class="text-danger">*</span>')
                    $('#subservicoMovidesk').empty().append('<option value="" disabled selected>Selecione o subserviço</option>');

                    response.resultado?.forEach(function (servico) {
                        $('#subservicoMovidesk').append('<option value="' + servico.id + '">' + servico.name + '</option>');
                    });

                    $('#subservicoMovidesk').prop('disabled', false);
                    $('#servicoNivel3').removeAttr('required');

                } else if (!nivel3) {
                    $('#subservicoMovidesk').prop('disabled', true);
                    $('#subservicoMovidesk').empty().append('<option value="" disabled selected>Não há subserviços</option>');
                    $('.labelSubservico').text('Subserviço:');
                    $('#subservicoMovidesk').removeAttr('required');
                    $('#servicoNivel3').removeAttr('required');
                }

            }
            $(document).trigger('servicosFilhoLoaded');
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
        }
    });
}

function limparCampos() {
    $('#clienteIdMovidesk').val('');
    $('#servicoMovidesk').empty().append('<option value="" disabled selected>Carregando serviços...</option>');
    $('#subservicoMovidesk').empty().append('<option value="" disabled selected>Selecione o subserviço</option>');
    $('#subservicoMovidesk').attr('required', 'required');
    $('.labelSubservico').html('Subserviço: ' + '<span class="text-danger">*</span>');
    $('#prestadoraMovidesk').val(null).trigger('change');
    $('#categoriaMovidesk').empty().append('<option value="" disabled selected>Selecione a categoria</option>');
    $('#urgenciaMovidesk').val(null).trigger('change');
    $('#ccMovidesk').val(null).trigger('change');
    $('#anexoMovidesk').val('');
    $('#assuntoMovidesk').val('');
    $('#mensagemMovidesk').val('');
}

function ocultaInputNivel3() {
    $('#servicoNivel3').empty();
    $('#servicoNivel3').removeAttr('required');
    $('.servicoNivel3').hide();
    $('.anexo').css(
        'width', '95%',
    );
}

function ocultaProdutos() {
    $('#produtoMovidesk').val(null).trigger('change');
    $('#produtoMovidesk').removeAttr('required');
    $('.produto').hide();
    $('.anexo').css(
        'width', '95%',
    );
}

function checkScreenWidth() {
    if ($(window).width() <= 640) {
        $('#modalMovidesk').modal('hide');
    }
}

function disableButton() {
    $('#btnTicket').attr('disabled', true);
}

function enableButon() {
    $('#btnTicket').attr('disabled', false);
}

function showLoadingButton() {
    $('#btnTicket').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetButton() {
    $('#btnTicket').html('Abrir Ticket').attr('disabled', false);
}