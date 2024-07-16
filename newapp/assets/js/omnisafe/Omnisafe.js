var localeText = AG_GRID_LOCALE_PT_BR;

var envioComando;
var historicoComando;

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formAddPower').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            return false;
        }
    })
})

$(document).ready(function () {
    atualizarAgGridOmnisafe();
    getClientesSelect2();
    // buscarSeguradoras();

    $('#seriais').on('change', function () {
        if ($('#seriais').prop('checked')) {
            $('#serialComando').append(`<option value="0" disabled>Todos os seriais selecionados</option>`);
            $('#serialComando').prop('disabled', true).val(0).trigger('change');
        } else {
            $('#serialComando option[value="0"]').remove();
            $('#serialComando').prop('disabled', false).val(null).trigger('change');
        }
    })

    $('#qtdCameras').select2({})
    $('#tipoEquipamento').select2({})
    $('#idPerfilComando').select2({})
    $('#serialComando').select2({})
    $('#statusEnvioBusca').select2({
        placeholder: "Selecione o status",
        allowClear: true,
        language: "pt-BR",
        width: "100%",
    })
    $('#statusRecebimentoBusca').select2({
        placeholder: "Selecione o status",
        allowClear: true,
        language: "pt-BR",
        width: "100%",
    })

    $('#tipoEquipamento').on('change', function () {
        let valor = $(this).val();
        $('#qtdCameras').empty();
        $('#qtdCameras').append(`<option value="" selected disabled>Selecione uma opção</option>`)

        if (valor == 1) {
            $('#qtdCameras').append(`<option value="1">2 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="2">3 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="3">4 Câmeras</option>`)
        } else if (valor == 2) {
            $('#qtdCameras').append(`<option value="4">2 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="5">3 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="6">4 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="7">5 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="8">6 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="9">7 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="10">8 Câmeras</option>`)
        } else if (valor == 3) {
            $('#qtdCameras').append(`<option value="11">2 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="12">3 Câmeras</option>`)
            $('#qtdCameras').append(`<option value="13">4 Câmeras</option>`)
        }
    })

    $('#menu-omnisafe').addClass("selected");
    $('#menu-power').removeClass("selected");
    $('#menu-ultima-configuracao').removeClass("selected");
    $('#menu-historico-comandos').removeClass("selected");
    $('.card-omnisafe').show();
    $('.card-power').hide();
    $('.card-historico-comandos').hide();
    $('.card-ultima-configuracao').hide();
    $('.statusEnvio').hide();
    $('.statusRecebimento').hide();


    $('#week0Time0').mask('00:00-00:00');
    $('#week0Time1').mask('00:00-00:00');
    $('#week0Time2').mask('00:00-00:00');
    $('#week0Time3').mask('00:00-00:00');

    //SELECTS DO MODAL DE CADASTRO DE PERFIL
    $('#idCliente').on('change', function (e) {
        let clienteSelecionado = $(this).val();
        if ($("#serial").data('select2')) {
            $("#serial").select2('destroy');
        }
        $("#serial").empty();
        $("#serial").append('<option value="" disabled selected>Buscando seriais...</option>');
        $("#serial").select2({
            placeholder: "Buscando seriais...",
            language: "pt-BR",
            width: 'resolve',
            minimumResultsForSearch: Infinity
        });

        if (clienteSelecionado) {
            let route = Router + '/buscarSerial';
            $.ajax({
                cache: false,
                url: route,
                data: {
                    idCliente: clienteSelecionado
                },
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200 && data.resultado.length > 0) {

                        if ($("#serial").data('select2')) {
                            $("#serial").select2('destroy');
                        }
                        $("#serial").empty();

                        const placeholderOption = new Option("Digite o Serial", "", false, false);
                        $("#serial").append(placeholderOption).trigger('change');

                        $("#serial").select2({
                            data: [{ id: "", text: "Digite o Serial", disabled: true }].concat(data.resultado.map(item => ({ id: item.equipamento, text: item.equipamento }))),
                            placeholder: "Digite o Serial",
                            allowClear: true,
                            language: "pt-BR",
                            width: 'resolve',
                            dropdownParent: $('#addPerfilOmnisafe')
                        })
                    } else {

                        if ($("#serial").data('select2')) {
                            $("#serial").select2('destroy');
                        }
                        $("#serial").empty();
                        $("#serial").append(`<option value="" disabled selected>Seriais não encontrados</option>`);
                        $("#serial").select2({
                            placeholder: "Seriais não encontrados",
                            allowClear: false,
                            language: "pt-BR",
                            width: 'resolve',
                            minimumResultsForSearch: Infinity
                        })
                    }
                },
                error: function (xhr, status, error) {
                    if ($("#serial").data('select2')) {
                        $("#serial").select2('destroy');
                    }
                    $("#serial").empty();
                    $("#serial").append(`<option value="" disabled selected>Erro na busca</option>`);
                    $("#serial").select2({
                        placeholder: "Erro na busca",
                        allowClear: false,
                        language: "pt-BR",
                        width: 'resolve',
                        minimumResultsForSearch: Infinity
                    })
                }
            });
        }
    });

    //SELECTS DO MODAL DE CAD. CONFIGURAÇÃO POWER
    $('#idUsuario').on('change', function (e) {
        $('#serialPower').empty();
        $('#serialPower').append(`<option value="" selected>Selecione o Serial</option>`);
        $("#idCfgPerfil").empty();
        $('#idCfgPerfil').append(`<option value="" selected>Selecione o Perfil</option>`);
        let clienteSelecionado = $(this).val();
        if (clienteSelecionado) {
            let route = Router + '/buscarSerial';
            $.ajax({
                cache: false,
                url: route,
                data: {
                    idCliente: clienteSelecionado
                },
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        $("#serialPower").select2({
                            data: data.resultado.map(item => ({ id: item.equipamento, text: item.equipamento })),
                            placeholder: "Digite o Serial",
                            allowClear: true,
                            language: "pt-BR",
                            width: 'resolve',
                            height: '32px',
                        });
                    } else {
                        $('#serialPower').empty();
                        $('#serialPower').append(`<option value="" selected>Seriais não encontrados</option>`);
                    }
                }
            });

            let routePerfil = Router + '/buscarPerfilByParametros';
            $.ajax({
                cache: false,
                url: routePerfil,
                type: 'POST',
                data: {
                    idCliente: clienteSelecionado
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        $("#idCfgPerfil").select2({
                            data: data.resultado.map(item => ({ id: item.id, text: item.nomePerfil })),
                            placeholder: "Digite o perfil",
                            allowClear: true,
                            language: "pt-BR",
                            width: 'resolve',
                            height: '32px',
                        });
                        $("#idCfgPerfil").select2('val', ' ')

                    } else if (data.status == 400) {
                        $("#idCfgPerfil").empty();
                        $('#serialPower').append(`<option value="" selected>Seriais não encontrados</option>`);
                    } else {
                        $("#idCfgPerfil").empty();
                        $('#serialPower').append(`<option value="" selected>Seriais não encontrados</option>`);
                    }
                },
                error: function (error) {
                    showAlert('error', 'Erro ao listar Perfis');
                }
            });
        }
    });

    //SELECTS DO MODAL DE ENVIO DE COMANDOS
    $('#idClienteComando').on('change', function (e) {
        $('#serialComando').empty();
        $('#serialComando').append(`<option value="" selected>Selecione o Serial</option>`);
        $("#idPerfilComando").empty();
        $('#idPerfilComando').append(`<option value="" selected>Selecione o Perfil</option>`);
        let clienteSelecionado = $(this).val();
        if (clienteSelecionado) {
            let route = Router + '/buscarSerial';
            $.ajax({
                cache: false,
                url: route,
                data: {
                    idCliente: clienteSelecionado
                },
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        $("#serialComando").select2({
                            data: data.resultado.map(item => ({ id: item.equipamento, text: item.equipamento })),
                            placeholder: "Digite o Serial",
                            allowClear: true,
                            language: "pt-BR",
                            width: 'resolve',
                            height: '32px',
                        });
                    } else {
                        $('#serialComando').empty();
                        $('#serialComando').append(`<option value="" selected>Seriais não encontrados</option>`);
                    }
                }
            });

            let routePerfil = Router + '/buscarPerfilByParametros';
            $.ajax({
                cache: false,
                url: routePerfil,
                type: 'POST',
                data: {
                    idCliente: clienteSelecionado
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        $("#idPerfilComando").select2({
                            data: data.resultado.map(item => ({ id: item.id, text: item.nomePerfil })),
                            placeholder: "Digite o perfil",
                            allowClear: true,
                            language: "pt-BR",
                            width: 'resolve',
                            height: '32px',
                        });
                        $("#idPerfilComando").select2('val', ' ')

                    } else if (data.status == 400) {
                        $("#idPerfilComando").empty();
                        $('#serialComando').append(`<option value="" selected>Seriais não encontrados</option>`);
                    } else {
                        $("#idPerfilComando").empty();
                        $('#serialComando').append(`<option value="" selected>Seriais não encontrados</option>`);
                    }
                },
                error: function (error) {
                    showAlert('error', 'Erro ao listar Perfis');
                }
            });
        }
    });

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#btnAdicionarPerfil').on('click', function () {
        $('#addPerfilOmnisafe').modal('show');
    });

    $('#btnAdicionarConfigPower').on('click', function () {
        valoresPadrao();
        $('#addConfigPower').modal('show');
        atualizarAgGridItensSchedule([], false);
    });

    $('#btnAdicionarHistoricoComando').on('click', function () {
        $('#addComando').modal('show');
        // atualizarAgGridItensSchedule([], false);
    });

    $('#btnAddSchedule').on('click', function () {
        if (validarCamposSchedule()) {
            adicionarDadosTabelaItensSchedule();
        }
    })

    $('#btnSalvarPerfil').on('click', function () {
        if (validarCamposPerfil()) {
            if ($('#titleOmnisafe').html() == 'Cadastrar Perfil') {
                cadastrarPerfil();
            } else if ($('#titleOmnisafe').html() == 'Editar Perfil') {
                cadastrarPerfil(true);
            }
        }
    });

    $('#addPerfilOmnisafe').on('hide.bs.modal', function () {
        limparCamposCadPerfil();
        $('#titleOmnisafe').html('Cadastrar Perfil');
    });

    $('#addConfigPower').on('hide.bs.modal', function () {
        limparCamposAddPower();
        $('#titlePowerConfig').html("Cadastrar Configuração Power");
    });

    $('#addComando').on('hide.bs.modal', function () {
        limparCamposComandos();
    });

    $('#modalComando').on('hide.bs.modal', function () {
        $('.numConfig').hide();
    })

    $('#formAddPower').on('submit', function (event) {
        if ($('#titlePowerConfig').html() == "Cadastrar Configuração Power") {
            event.preventDefault();
            var form = $(this).serializeArray();

            const formFormatado = {
                configPower: form.reduce((obj, item) => {
                    if (!item.name.startsWith("week")) {
                        obj[item.name] = item.value;
                    }
                    return obj;
                }, {})
            };

            let gridSchedule = AgGridItensSchedule.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
                return {
                    week0Time0: dado.data.week0Time0,
                    week0Time1: dado.data.week0Time1,
                    week0Time2: dado.data.week0Time2,
                    week0Time3: dado.data.week0Time3
                };
            });
            formFormatado.configPower.idUsuario = idUsuarioLogado;

            if (gridSchedule.length > 0) {
                formFormatado.powerSchedules = gridSchedule;
                cadastrarConfigPower(formFormatado);
            } else {
                showAlert('warning', 'Adicione pelo menos um item na tabela');
            }
        }
        else {
            event.preventDefault();
            var form = $(this).serializeArray();

            const formFormatado = {
                configPower: form.reduce((obj, item) => {
                    if (!item.name.startsWith("week")) {
                        obj[item.name] = item.value;
                    }
                    return obj;
                }, {}),
            };

            formFormatado.configPower.serial = $('#serialPower').val() != null ? $('#serialPower').val() : "";
            formFormatado.configPower.idCfgPerfil = $('#idCfgPerfil').val();
            formFormatado.configPower.idUsuario = idUsuarioLogado;

            let gridSchedule = AgGridItensSchedule.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
                return {
                    idSchedule: dado.data.id == null ? "" : dado.data.id,
                    week0Time0: dado.data.week0Time0,
                    week0Time1: dado.data.week0Time1,
                    week0Time2: dado.data.week0Time2,
                    week0Time3: dado.data.week0Time3,
                    status: dado.data.status
                };
            });

            gridSchedule = gridSchedule.filter(function (dado) {
                return dado.status !== "Inativo";
            });

            //MONTA JSON DE EDIÇÃO
            SchedulesEdit = {};
            SchedulesEdit.idPower = $('#idPower').val();
            powerSchedulesEditFilter = gridSchedule.filter(function (dado) {
                if (dado.idSchedule === "") {
                    return false;
                } else {
                    return true;
                }
            });
            SchedulesEdit.powerSchedulesEdit = powerSchedulesEditFilter;

            //MONTA JSON DE CADASTRO
            powerScheduleCad = {};
            powerScheduleCad.idPower = $('#idPower').val();
            powerSchedulesCadFilter = gridSchedule.filter(function (dado) {
                if (dado.idSchedule === "") {
                    delete dado.idSchedule;
                    return true;
                } else {
                    return false;
                }
            });
            powerScheduleCad.powerSchedules = powerSchedulesCadFilter;
            salvarEdicaoPowerAndSchedules(formFormatado["configPower"], SchedulesEdit, powerScheduleCad);
        }
    })

    $('#formAddComando').on('submit', function (event) {
        showLoadingSalvarButtonComando();
        event.preventDefault();
        let idCliente = $('#idClienteComando').val();
        let idPerfil = $('#idPerfilComando').val();
        let serial = $('#serialComando').val();
        let qtdCameras = $('#qtdCameras').val();

        let form = {};
        form.idCliente = idCliente;
        form.idPerfil = idPerfil;
        form.serial = serial;
        form.numConfig = qtdCameras;

        cadastrarComando(form);
    })

    $('#btnLimparSchedule').on('click', function () {
        atualizarAgGridItensSchedule();
    })

    $('#menu-omnisafe').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-power').removeClass("selected");
            $('#menu-ultima-configuracao').removeClass("selected");
            $('#menu-historico-comandos').removeClass("selected");
            $('.card-omnisafe').show();
            $('.card-power').hide();
            $('.card-envio-comandos').hide();
            $('.card-historico-comandos').hide();
            $('.card-ultima-configuracao').hide();
            $('.nomePerfil').show();
            $('.statusEnvio').hide();
            $('.statusRecebimento').hide();
            atualizarAgGridOmnisafe();
        }
    });

    $('#menu-power').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-omnisafe').removeClass("selected");
            $('#menu-ultima-configuracao').removeClass("selected");
            $('#menu-historico-comandos').removeClass("selected");
            $('.card-power').show();
            $('.card-omnisafe').hide();
            $('.card-historico-comandos').hide();
            $('.card-ultima-configuracao').hide();
            $('.nomePerfil').hide();
            $('.statusEnvio').hide();
            $('.statusRecebimento').hide();
            atualizarAgGridConfigPower();
        }
    });

    $('#menu-historico-comandos').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-omnisafe').removeClass("selected");
            $('#menu-power').removeClass("selected");
            $('#menu-ultima-configuracao').removeClass("selected");
            $('.card-historico-comandos').show();
            $('.card-ultima-configuracao').hide();
            $('.card-omnisafe').hide();
            $('.card-power').hide();
            $('.nomePerfil').hide();
            $('.statusEnvio').show();
            $('.statusRecebimento').show();
            atualizarAgGridHistoricoComandos();
        }
    });

    $('#menu-ultima-configuracao').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-omnisafe').removeClass("selected");
            $('#menu-power').removeClass("selected");
            $('#menu-historico-comandos').removeClass("selected");
            $('.card-ultima-configuracao').show();
            $('.card-omnisafe').hide();
            $('.card-power').hide();
            $('.card-historico-comandos').hide();
            $('.nomePerfil').hide();
            $('.statusEnvio').show();
            $('.statusRecebimento').show();
            atualizarAgGridUltimaConfiguracao();
        }
    });

    $('.menu-interno-link').click(function () {
        limparPesquisa();
    })

    $('#BtnLimparFiltro').click(function () {
        serialBusca = $("#serialBusca").val(),
            perfilBusca = $("#perfilBusca").val(),
            clienteBusca = $("#clienteBusca").val()
        statusEnvioBusca = $("#statusEnvioBusca").val()
        statusRecebimentoBusca = $("#statusRecebimentoBusca").val()

        if (serialBusca || perfilBusca || clienteBusca || statusEnvioBusca || statusRecebimentoBusca) {
            if ($("#menu-omnisafe").hasClass('selected')) {
                atualizarAgGridOmnisafe();

            } else if ($("#menu-power").hasClass('selected')) {
                atualizarAgGridConfigPower();

            } else if ($("#menu-historico-comandos").hasClass('selected')) {
                atualizarAgGridHistoricoComandos();

            } else if ($("#menu-ultima-configuracao").hasClass('selected')) {
                atualizarAgGridUltimaConfiguracao();

            }
        }
        limparPesquisa();

    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            serial: $("#serialBusca").val(),
            nomePerfil: $("#perfilBusca").val(),
            statusEnvioBusca: $("#statusEnvioBusca").val(),
            statusRecebimentoBusca: $("#statusRecebimentoBusca").val(),
            idCliente: $("#clienteBusca").val()
        };

        serialBusca = $("#serialBusca").val();
        perfilBusca = $("#perfilBusca").val();
        statusEnvioBusca = $("#statusEnvioBusca").val();
        statusRecebimentoBusca = $("#statusRecebimentoBusca").val();
        clienteBusca = $("#clienteBusca").val();


        var omnisafeVisivel = $('#menu-omnisafe').hasClass("selected");
        var powerVisivel = $('#menu-power').hasClass("selected");
        var historicoVisivel = $('#menu-historico-comandos').hasClass("selected");
        var ultimaConfigVisivel = $('#menu-ultima-configuracao').hasClass("selected");

        if (omnisafeVisivel) {
            if (!serialBusca && !perfilBusca && !clienteBusca) {
                resetPesquisarButton();
                showAlert('warning', 'Preencha algum dos campos de busca!')
            } else {
                atualizarAgGridOmnisafe(searchOptions);
            }

        } else if (powerVisivel) {
            if (!serialBusca && !clienteBusca) {
                resetPesquisarButton();
                showAlert('warning', 'Preencha algum dos campos de busca!')
                return;
            } else {
                atualizarAgGridConfigPower(searchOptions);
            }
        } else if (historicoVisivel) {
            if (!serialBusca && !clienteBusca && !statusEnvioBusca && !statusRecebimentoBusca && !perfilBusca) {
                resetPesquisarButton();
                showAlert('warning', 'Preencha algum dos campos de busca!')
                return;
            } else if (serialBusca != '' && !serialBusca.startsWith('MDVR')) {
                showAlert('warning', 'O serial deve começar com as siglas "MDVR"')
                $('#serialBusca').val('');
                $('#serialBusca').focus();
                resetPesquisarButton();
                return;
            } else {
                atualizarAgGridHistoricoComandos(searchOptions);
            }
        } else if (ultimaConfigVisivel) {
            if (!serialBusca && !clienteBusca && !statusEnvioBusca && !statusRecebimentoBusca && !perfilBusca) {
                resetPesquisarButton();
                showAlert('warning', 'Preencha algum dos campos de busca!')
                return;
            } else if (serialBusca != '' && !serialBusca.startsWith('MDVR')) {
                showAlert('warning', 'O serial deve começar com as siglas "MDVR"')
                $('#serialBusca').val('');
                $('#serialBusca').focus();
                resetPesquisarButton();
                return;
            } else {
                atualizarAgGridUltimaConfiguracao(searchOptions);
            }
        }
    });

})

function preencherModalPerfil(dados) {
    $('#titleOmnisafe').html('Editar Perfil');
    $('#nomePerfil').val(dados['nomePerfil']);
    $('#idPerfil').val(dados['id']);
    $('#idCliente').attr('disabled', true);
    $('#idCliente').append(`<option value="${dados['idCliente']}" selected>${dados['nomeCliente']}</option>`);

    // $('#serial').append(`<option value="" disabled selected>Buscando seriais...</option>`)

    var idCliente = dados['idCliente'];
    let route = Router + '/buscarSerial';
    $.ajax({
        cache: false,
        url: route,
        data: {
            idCliente: idCliente
        },
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data.status == 200 && data.resultado.length > 0) {

                if ($("#serial").data('select2')) {
                    $("#serial").select2('destroy');
                }
                $("#serial").empty();

                const placeholderOption = new Option("Digite o Serial", "", false, false);
                $("#serial").append(placeholderOption).trigger('change');

                $("#serial").select2({
                    data: [{ id: "", text: "Digite o Serial", disabled: true }].concat(data.resultado.map(item => ({ id: item.equipamento, text: item.equipamento }))),
                    placeholder: "Digite o Serial",
                    allowClear: true,
                    language: "pt-BR",
                    width: 'resolve',
                    dropdownParent: $('#addPerfilOmnisafe')
                })
                if (dados['serial'] != null) {
                    $('#serial').append(`<option value="${dados['serial']}" selected>${dados['serial']}</option>`);
                }
            } else {

                if ($("#serial").data('select2')) {
                    $("#serial").select2('destroy');
                }
                $("#serial").empty();
                $("#serial").append(`<option value="" disabled selected>Seriais não encontrados</option>`);
                $("#serial").select2({
                    placeholder: "Seriais não encontrados",
                    allowClear: false,
                    language: "pt-BR",
                    width: 'resolve',
                    minimumResultsForSearch: Infinity
                })
            }
        }
    })

}

function buscarPerfilById(id) {
    let route = Router + '/buscarPerfilById';

    return new Promise((resolve, reject) => {
        $.ajax({
            url: route,
            type: 'POST',
            data: {
                idPerfil: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    resolve(response.resultado);
                } else {
                    reject('Dados não encontrados');
                }
            },
            error: function (error) {
                reject(error.statusText || 'Erro ao realizar a requisição');
                HideLoadingScreen();
            }
        });
    })
}

function cadastrarPerfil(edit) {
    showLoadingSalvarButtonPerfil();
    let route = Router + '/cadastrarPerfil'

    dados = {
        idCliente: $('#idCliente').val(),
        nomePerfil: $('#nomePerfil').val(),
        serial: $('#serial').val(),
    }

    if (edit) {
        route = Router + '/editarPerfil';
        dados.idPerfil = $('#idPerfil').val();
    }

    $.ajax({
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        success: function (response) {
            if (response['status'] == 200) {
                showAlert('success', response['resultado']['mensagem'])
            } else if (response['status'] == 400) {
                showAlert('error', response['resultado']['mensagem'])
            } else {
                showAlert('error', 'Erro interno do servidor. Entre em contato com o Suporte Técnico')
            }
            $('#addPerfilOmnisafe').modal('hide');
            resetSalvarButtonPerfil();
            atualizarTabatualizarTableOmnisafe();
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor')
            resetSalvarButtonPerfil();
            atualizarTableOmnisafe();
        },
    });
}

async function editarPerfil(id) {
    ShowLoadingScreen();
    const resultado = await buscarPerfilById(id);
    $('#addPerfilOmnisafe').modal('show');
    preencherModalPerfil(resultado);
    HideLoadingScreen();
}

function deletePerfil(id) {
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente remover este perfil ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar",
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            let route = Router + '/deletarPerfil'
            $.ajax({
                url: route,
                data: {
                    id: id
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['status'] == 200) {
                        showAlert('success', 'Perfil removido com sucesso!');
                    } else if (response['status'] == 400) {
                        showAlert('error', response['resultado']['mensagem'])
                    } else {
                        showAlert('error', 'Erro interno do servidor. Entre em contato com o Suporte Técnico')
                    }
                    HideLoadingScreen();
                    atualizarTableOmnisafe();
                },
                error: function (error) {
                    showAlert('error', 'Erro na solicitação ao servidor');
                    HideLoadingScreen();
                }
            })
        }
    })
}

var AgGridOmnisafe;
function atualizarAgGridOmnisafe(options) {
    stopAgGRIDOmnisafe();
    showLoadingLimparButton();
    showLoadingPesquisarButton();
    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/listarPerfis';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idCliente: options ? options.idCliente : '',
                        serial: options ? options.serial : '',
                        nomePerfil: options ? options.nomePerfil : ''
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
                            showAlert('error', data.message);
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
                headerName: 'Perfil',
                field: 'nomePerfil',
                chartDataType: 'category',
                width: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'category',
                flex: 1,
                minWidth: 250,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
                width: 200,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Data de Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
                width: 200,
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
                    let tableId = "tableOmnisafe";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:editarPerfil(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                        </div>
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:deletePerfil(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-omnisafe').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-omnisafe').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-omnisafe').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableOmnisafe');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    //gridOptions.api.setRowData(dados);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesPerfis(gridOptions);
}

function cadastrarConfigPower(dados) {
    showLoadingSalvarButtonPowerSchedule();
    let route = Router + '/cadastrarConfigPower'

    $.ajax({
        url: route,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(dados),
        success: function (response) {
            response = JSON.parse(response);
            if (response['status'] == 200) {
                showAlert('success', response['resultado']['mensagem'])
                $('#addConfigPower').modal('hide');
                atualizarTablePower();
            } else if (response['status'] == 400) {
                if (Array.isArray(response['resultado']['errors'])) {
                    let alertMsg = "Erros: " + response['resultado']['errors'].length + "\n";
                    response['resultado']['errors'].forEach(element => {
                        alertMsg += `${element}\n`;
                    });
                    showAlert('error', alertMsg);
                } else {
                    showAlert('error', response['resultado']['mensagem'])
                }
            }
            else {
                if (Array.isArray(response['resultado']['errors'])) {
                    let alertMsg = "Erros: " + response['resultado']['errors'].length + "\n";
                    response['resultado']['errors'].forEach(element => {
                        alertMsg += `${element}\n`;
                    });
                    showAlert('error', alertMsg);
                }
                else {
                    showAlert('error', response['resultado']['mensagem']);
                }
            }
            resetSalvarButtonPowerSchedule();
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor')
            resetSalvarButtonPowerSchedule();
            atualizarTablePower();
        },
    });
}

async function preencherConfigPower(dados) {
    let resultado = await buscarPerfilById(dados['idCfgPerfil']);
    $('#idUsuario').append(`<option value="${resultado['idCliente']}" selected>${resultado['nomeCliente']}</option>`).trigger('change');
    // $('#idUsuario').val(resultado['idCliente']).trigger('change');
    $('#idUsuario').attr('disabled', true);
    $('#idCfgPerfil').attr('disabled', true);
    $('#switchValue').val(dados['switchValue']);
    $('#delay').val(dados['delay']);
    $('#reserveDelay').val(dados['reserveDelay']);
    $('#hibernationReport').val(dados['hibernationReport']);
    $('#screenOffTime').val(dados['screenOffTime']);
    $('#powerOnTime').val(dados['powerOnTime']);
    $('#powerOffTime').val(dados['powerOffTime']);
    $('#accPowerOffRecEnable').val(dados['accPowerOffRecEnable']);
    $('#accOffRecTime').val(dados['accOffRecTime']);
    $('#timeRebootEn').val(dados['timeRebootEn']);
    $('#rebootTime').val(dados['rebootTime']);
    $('#lowPowerOff').val(dados['lowPowerOff']);
    $('#littlePowerEnable').val(dados['littlePowerEnable']);
    $('#titlePowerConfig').html("Editar Configuração Power");

    setTimeout(function () {
        $('#serialPower').val(dados['serial']).trigger('change');
        $("#idCfgPerfil").val(dados['idCfgPerfil']).trigger('change');
        $('#addConfigPower').modal('show');
        HideLoadingScreen();
    }, 4000);
    buscarPowerSchedules($('#idPower').val());
}

function salvarEdicaoPowerAndSchedules(power, schedulesEdit, schedulesCad) {
    showLoadingSalvarButtonPowerSchedule();
    let routePower = Router + '/editarConfigPower'
    $.ajax({
        url: routePower,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(power),
        dataType: 'json',
        success: async function (response) {
            if (response.status == 200) {
                if (schedulesEdit.powerSchedulesEdit.length > 0) {
                    await editarPowerSchedulesLista(schedulesEdit);
                }
                if (schedulesCad.powerSchedules.length > 0) {
                    await cadastrarPowerSchedulesLista(schedulesCad);
                }
                showAlert('success', "Edição realizada com sucesso.");
                $('#addConfigPower').modal('hide');
                atualizarTablePower();
            } else {
                if (Array.isArray(response['resultado']['errors'])) {
                    let alertMsg = "Erros: " + response['resultado']['errors'].length + "\n";
                    response['resultado']['errors'].forEach(element => {
                        alertMsg += `${element}\n`;
                    });
                    showAlert('error', alertMsg);
                } else {
                    showAlert('error', response['resultado']['mensagem']);
                }
            }
            resetSalvarButtonPowerSchedule();
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
            resetSalvarButtonPowerSchedule();
        }
    });
}

function editarPowerSchedulesLista(schedulesEdit) {
    let routeSchedulesEdit = Router + '/editarPowerSchedulesLista'
    return $.ajax({
        url: routeSchedulesEdit,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(schedulesEdit),
        dataType: 'json',
        success: function (response) {
            if (response.status == 200) {

            } else {
                showAlert('error', response['resultado']['mensagem'])
            }
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
        }
    });
}

function cadastrarPowerSchedulesLista(schedulesCad) {
    let routeSchedulesCad = Router + '/cadastrarPowerSchedulesLista'
    return $.ajax({
        url: routeSchedulesCad,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(schedulesCad),
        dataType: 'json',
        success: function (response) {
            if (response.status == 200) {

            } else {
                showAlert('error', response['resultado']['mensagem'])
            }
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
        }
    });
}

async function editarConfigPower(id) {
    $('#idPower').val(id);
    ShowLoadingScreen();
    const resultado = await buscarConfigPowerById(id);
    preencherConfigPower(resultado);
}

function buscarConfigPowerById(id) {
    let route = Router + '/buscarConfigPowerById';

    return new Promise((resolve, reject) => {
        $.ajax({
            url: route,
            type: 'POST',
            data: {
                idPower: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    resolve(response.resultado);
                } else {
                    reject('Dados não encontrados');
                }
            },
            error: function (error) {
                reject(error.statusText || 'Erro ao realizar a requisição');
                HideLoadingScreen();
            }
        });
    })
}

function buscarPowerSchedules(id) {
    let route = Router + '/buscarPowerSchedules';
    $.ajax({
        url: route,
        type: 'POST',
        data: {
            idPowerConfig: id
        },
        dataType: 'json',
        success: function (response) {
            if (response.status == 200) {
                atualizarAgGridItensSchedule(response['resultado'], true);
            } else {
                showAlert('error', response['resultado']['mensagem'])
            }
            HideLoadingScreen();
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
        }
    });
}


function deleteConfigPower(id) {
    if (confirm("Deseja realmente remover esta configuração?")) {
        ShowLoadingScreen();
        let route = Router + '/deletarConfigPower'
        $.ajax({
            url: route,
            data: {
                id: id
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response['status'] == 200) {
                    showAlert('success', 'Perfil removido com sucesso!');
                } else if (response['status'] == 400) {
                    showAlert('error', response['resultado']['mensagem']);
                } else {
                    showAlert('error', response['resultado']['mensagem'])
                }
                HideLoadingScreen();
                atualizarTablePower();
            },
            error: function (error) {
                showAlert('error', 'Erro na solicitação ao servidor');
                HideLoadingScreen();
                atualizarTablePower();
            }
        })

    }
}

function alterarStatusSchedule(id, status) {
    let statusFormatado = status == 'Ativar' ? '1' : '0';
    if (confirm("Deseja realmente alterar o status?")) {
        ShowLoadingScreen();
        let route = Router + '/alterarStatusConfigPowerSchedule'
        $.ajax({
            url: route,
            data: {
                id: id,
                status: statusFormatado
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response['status'] == 200) {
                    showAlert('success', 'Status alterado com sucesso!');
                } else if (response['status'] == 400) {
                    showAlert('error', response['resultado']['mensagem']);
                } else {
                    showAlert('error', response['resultado']['mensagem']);
                }
                buscarPowerSchedules($('#idPower').val());
            },
            error: function (error) {
                showAlert('error', 'Erro na solicitação ao servidor');
                HideLoadingScreen();
            }
        })
    }
}

var AgGridConfigPower;
function atualizarAgGridConfigPower(options) {
    stopAgGRIDConfigPower();
    showLoadingLimparButton();
    showLoadingPesquisarButton();
    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarConfigPower';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idCliente: options ? options.idCliente : '',
                        serial: options ? options.serial : ''
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
                            showAlert('error', data.message);
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
                        resetPesquisarButton();
                        resetLimparButton();
                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        resetLimparButton();
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
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'category',
                width: 280,
                suppressSizeToFit: true
            },
            {
                headerName: 'Switch Value',
                field: 'switchValue',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Delay',
                field: 'delay',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Reserve Delay',
                field: 'reserveDelay',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Hibernation Report',
                field: 'hibernationReport',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Screen Off Time',
                field: 'screenOffTime',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Power On Time',
                field: 'powerOnTime',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Power Off Time',
                field: 'powerOffTime',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Acc PowerOff Rec Enable',
                field: 'accPowerOffRecEnable',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Acc Off Rec Time',
                field: 'accOffRecTime',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Reboot Time',
                field: 'rebootTime',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Low Power Off',
                field: 'lowPowerOff',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Little Power Enable',
                field: 'littlePowerEnable',
                chartDataType: 'category',
                width: 150,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['status'];
                    if (data == 'Ativo') {
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
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options) {
                    let data = options.data;
                    let tableId = "tablePower";
                    let dropdownId = "dropdown-menu-power-" + data.id;
                    let buttonId = "dropdownMenuButtonMenu_" + data.id;
                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:editarConfigPower(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                        </div>
                        <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                            <a href="javascript:deleteConfigPower(${data.id})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-power').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-power').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-power').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tablePower');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoesPowerConfig(gridOptions);
}

var AgGridItensSchedule;
function atualizarAgGridItensSchedule(dados, isEdit) {
    stopAgGRIDItensSchedule();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Time 0',
                field: 'week0Time0',
                cellEditor: CustomTimeCellEditor,
                suppressSizeToFit: true,
                width: 150,
            },
            {
                headerName: 'Time 1',
                field: 'week0Time1',
                cellEditor: CustomTimeCellEditor,
                suppressSizeToFit: true,
                width: 150,
            },
            {
                headerName: 'Time 2',
                field: 'week0Time2',
                cellEditor: CustomTimeCellEditor,
                suppressSizeToFit: true,
                width: 150,
            },
            {
                headerName: 'Time 3',
                field: 'week0Time3',
                cellEditor: CustomTimeCellEditor,
                suppressSizeToFit: true,
                width: 150,
            },
            {
                headerName: 'Status',
                field: 'status',
                suppressSizeToFit: true,
                editable: false,
                width: 100,
            },
            {
                headerName: 'id',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'idPower',
                field: 'id_cfg_omnisafe_power',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                editable: false,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options) {
                    var firstRandom = Math.random() * 10;
                    var secRandom = Math.random() * 10;
                    var thirdRandom = Math.random() * 10;

                    let idLinha = options.node.rowIndex;
                    var varAleatorioIdBotao =
                        (firstRandom * secRandom).toFixed(0) +
                        "-" +
                        (thirdRandom * firstRandom).toFixed(0) +
                        "-" +
                        (secRandom * thirdRandom).toFixed(0);

                    let data = options.data;
                    let tableId = "tableItensSchedule";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;
                    statusTexto = data.status == 'Ativo' ? 'Inativar' : 'Ativar';


                    let statusButtonHtml = isEdit ? `
                    <div class="dropdown-item dropdown-item-acoes acoes-status" style="cursor: pointer;">
                        <a href="javascript:alterarStatusSchedule(${data.id}, '${statusTexto}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">${statusTexto}</a>
                    </div>` : '';

                    let deleteButtonHtml = isEdit ? '' : `
                    <div class="dropdown-item dropdown-item-acoes acoes-delete" style="cursor: pointer;">
                        <a href="javascript:deleteSchedule(this, ${idLinha})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
                    </div>`;


                    return `
                    <div class="dropdown" style="position: relative;">
                        <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            ${statusButtonHtml}
                            ${deleteButtonHtml}
                        </div>
                    </div>`;
                }
            }
        ],
        defaultColDef: {
            editable: function (params) {
                return params.data.status !== "Inativo";
            },
            sortable: true,
            minWidth: 80,
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableItensSchedule');
    AgGridItensSchedule = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
}

function adicionarDadosTabelaItensSchedule() {
    let time0 = $('#week0Time0').val();
    let time1 = $('#week0Time1').val();
    let time2 = $('#week0Time2').val();
    let time3 = $('#week0Time3').val();

    let dados = {
        week0Time0: time0,
        week0Time1: time1,
        week0Time2: time2,
        week0Time3: time3,
        status: 'Ativo'
    }
    AgGridItensSchedule.gridOptions.api.applyTransaction({ add: [dados] });
    limparCamposSchedule();
}

function deleteSchedule(botao, idRowIndex) {
    let id = idRowIndex;

    AgGridItensSchedule.gridOptions.api.applyTransaction({ remove: [AgGridItensSchedule.gridOptions.api.getDisplayedRowAtIndex(id).data] });
}

var AgGridUltimaConfiguracao;
function atualizarAgGridUltimaConfiguracao(options) {
    stopAgGRIDUltimaConfiguracao();
    showLoadingLimparButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarUltimaConfiguracao';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idCliente: options ? options.idCliente : '',
                        serial: options ? options.serial : '',
                        statusEnvioBusca: options ? options.statusEnvioBusca : '',
                        statusRecebimentoBusca: options ? options.statusRecebimentoBusca : ''
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
                            showAlert('error', data.message);
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
                        resetLimparButton();
                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        if (options) resetPesquisarButton();
                        resetLimparButton();
                    },
                });

                $("#loadingMessage").hide();
                if (!options) resetPesquisarButton();
            },
        };
    }

    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Configuração',
                field: 'numConfig',
                width: 250,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let numConfig = options.value;
                    return nomeConfig(numConfig);
                }
            },
            {
                headerName: 'Serial',
                field: 'serial',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa',
                field: 'placa',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Perfil',
                field: 'NomePerfil',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data de Envio',
                field: 'dataEnvio',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status do Envio',
                field: 'statusEnvio',
                width: 150,
                suppressSizeToFit: true,
                valueFormatter: function (params) {
                    let status = params.value;
                    return statusEnvio(status);
                }
            },
            {
                headerName: 'Data de Recebimento',
                field: 'dataRecebimento',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status de Recebimento',
                field: 'statusRecebimento',
                width: 150,
                suppressSizeToFit: true,
                valueFormatter: function (params) {
                    let status = params.value;
                    return statusRecebimento(status);
                }
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Data de Atualização',
                field: 'dataUpdate',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['status'];
                    if (data == 'Ativo') {
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
                    let tableId = "tableUltimaConfiguracao";
                    let dropdownId = "dropdown-menu-ultima-configuracao-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButtonMenu_" + data.id + varAleatorioIdBotao;

                    let escapedXml = escapeXml(data.xmlEnviado);
                    data.xmlEnviado = '';
                    let dataString = JSON.stringify(data);

                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirModalComando('${encodeURIComponent(dataString)}', 'Historico')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                            </div>
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:reenviarComando('${data.idCliente}', '${data.idPerfil}', '${data.serial}', '${data.numConfig}')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Reenviar Comando</a>
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-ultima-configuracao').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-ultima-configuracao').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-ultima-configuracao').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableUltimaConfiguracao');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoesUltimaConfiguracao(gridOptions);
}

function reenviarComando(idCliente, idPerfil, serial, numConfig) {
    let form = {};
    form.idCliente = idCliente;
    form.idPerfil = idPerfil;
    form.serial = serial;
    form.numConfig = numConfig

    cadastrarComando(form);
}

function cadastrarComando(form) {
    ShowLoadingScreen();
    let route;

    if ($('#seriais').prop('checked')) {
        route = Router + '/cadastrarComandoPorCliente';
    } else {
        route = Router + '/cadastrarComandoPredefinido';
    }

    $.ajax({
        url: route,
        type: 'POST',
        data: form,
        dataType: 'json',
        success: function (response) {
            if (response['status'] == 200) {
                showAlert('success', 'Comando enviado com sucesso.');
                $('#addComando').modal('hide');
                atualizarTableComando();
            } else if (response['status'] == 400) {
                const errorMessage = response.resultado.errors ? response.resultado.errors[0] : response.resultado.mensagem;
                showAlert('error', errorMessage);
            } else if (response['status'] == 404) {
                showAlert('error', response['resultado']['mensagem'])
            } else {
                showAlert('error', "Erro interno do servidor. Entre em contato com o Suporte Técnico");
            }
            HideLoadingScreen();
            resetSalvarButtonComando();
        },
        error: function (error) {
            showAlert('error', 'Erro na solicitação ao servidor');
            resetSalvarButtonComando();
            HideLoadingScreen();
            atualizarTableComando();
        },
    });
}

var AgGridHistoricoComandos;
function atualizarAgGridHistoricoComandos(options) {
    stopAgGRIDHistoricoComandos();
    showLoadingLimparButton();
    showLoadingPesquisarButton();
    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarHistoricoComandos';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idCliente: options ? options.idCliente : '',
                        serial: options ? options.serial : '',
                        statusEnvioBusca: options ? options.statusEnvioBusca : '',
                        statusRecebimentoBusca: options ? options.statusRecebimentoBusca : ''
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
                            showAlert('error', data.message);
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
                        resetLimparButton();
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
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Serial',
                field: 'serial',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa',
                field: 'placa',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Enviado por',
                field: 'nomeUsuario',
                width: 200,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Perfil',
                field: 'NomePerfil',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Data de Envio',
                field: 'dataEnvio',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status do Envio',
                field: 'statusEnvio',
                width: 150,
                suppressSizeToFit: true,
                valueFormatter: function (params) {
                    let status = params.value;
                    return statusEnvio(status);
                }
            },
            {
                headerName: 'Data de Recebimento',
                field: 'dataRecebimento',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status de Recebimento',
                field: 'statusRecebimento',
                width: 150,
                suppressSizeToFit: true,
                valueFormatter: function (params) {
                    let status = params.value;
                    return statusRecebimento(status);
                }
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Data de Atualização',
                field: 'dataUpdate',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Status',
                field: 'status',
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data['status'];
                    if (data == 'Ativo') {
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
                    let tableId = "tableHistoricoComandos";
                    let dropdownId = "dropdown-menu-comandos-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButtonMenu_" + data.id + varAleatorioIdBotao;

                    let escapedXml = escapeXml(data.xmlEnviado);
                    data.xmlEnviado = '';
                    let dataString = JSON.stringify(data);

                    return `
                    <div class="dropdown" style="position: relative;">
                    <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                        <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                    </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:abrirModalComando('${encodeURIComponent(dataString)}', 'Envio')" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-historico-comandos').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    $('#select-quantidade-por-pagina-historico-comandos').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-historico-comandos').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableHistoricoComandos');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoesHistoricoComandos(gridOptions);
}

function abrirModalComando(dataString, historico) {
    let data = JSON.parse(decodeURIComponent(dataString));
    var stringConfig = nomeConfig(data.numConfig);
    var stringStatusRecebimento = statusRecebimento(data.statusRecebimento)
    var stringStatusEnvio = statusRecebimento(data.statusEnvio)

    if (historico == 'Historico') {
        $('#configuracaoComando').val(stringConfig)
        $('.numConfig').show();
    }

    $('#titleComando').text("Comando Enviado #" + data.id)
    $('#serialComandoModal').val(data.serial)
    $('#placaComando').val(data.placa)
    $('#clienteComando').val(data.nomeCliente)
    $('#perfilComando').val(data.nomePerfil ? data.nomePerfil : data.NomePerfil)
    $('#dataEnvioComando').val(formatDateTime(data.dataEnvio))
    $('#statusEnvioComando').val(stringStatusEnvio)
    $('#dataRecebimentoComando').val(formatDateTime(data.dataRecebimento))
    $('#statusRecebimentoComando').val(stringStatusRecebimento)
    $('#dataCadastroComando').val(formatDateTime(data.dataCadastro))
    $('#dataAtualizacaoComando').val(formatDateTime(data.dataUpdate))
    $('#statusComando').val(data.status)

    $('#modalComando').modal('show')
}

function escapeXml(unsafe) {
    return unsafe.replace(/[<>&'"]/g, function (c) {
        switch (c) {
            case '<': return '&lt;';
            case '>': return '&gt;';
            case '&': return '&amp;';
            case '"': return '&quot;';
            case "'": return '&apos;';
            default: return c;
        }
    });
}

function abrirModalComXml(xmlData) {
    try {
        // Decodificar os dados XML
        var decodedXmlData = xmlData.replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&amp;/g, '&')
            .replace(/&quot;/g, '"')
            .replace(/&apos;/g, "'");
        $('#xmlModal').data('xml', decodedXmlData);
        $('#xmlModal').modal('show');
    } catch (e) {
        console.error("Erro ao decodificar os dados XML:", e);
    }
}

$('#xmlModal').on('show.bs.modal', function () {
    var xmlData = $(this).data('xml');
    if (xmlData) {
        $('#xmlVisualizado').empty();
        visualizarXml(xmlData);

        $(this).modal('handleUpdate');
    }
});

function visualizarXml(xmlData) {
    $('#xmlVisualizado').empty();

    var xmlDOM = $.parseXML(xmlData);
    var $xml = $(xmlDOM);

    function processNode(node) {
        var $node = $(node);
        var contents = $.trim($node.contents().not($node.children()).text());
        if (contents && !/^\s*$/.test(contents)) {
            return document.createTextNode(contents);
        } else {
            return null;
        }
    }

    function buildList($xmlElements) {
        var $ul = $('<ul>');
        $xmlElements.each(function () {
            var $li = $('<li class="tag">');
            var $this = $(this);

            $li.append('&lt;' + this.tagName + '&gt;');

            var textNode = processNode(this);
            if (textNode) {
                $li.append(textNode);
            }

            if ($this.children().length > 0) {
                $li.append(buildList($this.children()));
            }

            $li.append('&lt;/' + this.tagName + '&gt;');

            $ul.append($li);
        });
        return $ul;
    }

    var $rootList = buildList($xml.children());
    $('#xmlVisualizado').append($rootList);

}

$('#xmlVisualizado').on('click', 'li.tag', function (e) {
    e.stopPropagation();
    $(this).children('ul').toggle();
});

function exportModalInfoToTxtFile() {
    const text = $("#xmlVisualizado").text();
    let textFormatado = formatXml(text);
    const fileName = "Comando.txt";

    const textBlob = new Blob([textFormatado], { type: 'text/plain' });

    const downloadLink = document.createElement("a");
    downloadLink.download = fileName;
    downloadLink.href = window.URL.createObjectURL(textBlob);
    downloadLink.style.display = "none";

    document.body.appendChild(downloadLink);
    downloadLink.click();

    document.body.removeChild(downloadLink);
}

function copyModalInfoToClipboard() {
    const text = $("#xmlVisualizado").text();
    copyToClipboard(text);
}

async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showAlert("success", "Informações copiadas para a área de transferência!");
    } catch (err) {
        showAlert('error', "Falha ao copiar texto: ");
    }
}

function formatXml(xml) {
    var formatted = '';
    var reg = /(>)(<)(\/*)/g;
    xml = xml.replace(reg, '$1\r\n$2$3');
    var pad = 0;
    jQuery.each(xml.split('\r\n'), function (index, node) {
        var indent = 0;
        if (node.match(/.+<\/\w[^>]*>$/)) {
            indent = 0;
        } else if (node.match(/^<\/\w/)) {
            if (pad != 0) {
                pad -= 1;
            }
        } else if (node.match(/^<\w[^>]*[^\/]>.*$/)) {
            indent = 1;
        } else {
            indent = 0;
        }

        var padding = '';
        for (var i = 0; i < pad; i++) {
            padding += '  ';
        }

        formatted += padding + node + '\r\n';
        pad += indent;
    });

    return formatted;
}


function validarCamposPerfil() {
    let cliente = $('#idCliente');
    let perfil = $('#nomePerfil');
    let isValid = true;
    let mensagemErro = '';

    if (cliente.val() === '' || cliente.val() === null) {
        cliente.addClass('campo-invalido');
        isValid = false;
        if (mensagemErro === '') { // Se ainda não houver mensagem de erro, define uma
            mensagemErro = 'Selecione um Cliente';
            cliente.focus(); // Define o foco para o campo cliente
        }
    } else {
        cliente.removeClass('campo-invalido');
    }
    if (perfil.val().trim() === '') {
        perfil.addClass('campo-invalido');
        isValid = false;
        if (mensagemErro === '') { // Se ainda não houver mensagem de erro, define uma
            mensagemErro = 'Informe um nome de Perfil';
            perfil.focus(); // Define o foco para o campo perfil
        }

    } else {
        perfil.removeClass('campo-invalido');
    }

    if (mensagemErro !== '') {
        showAlert('error', mensagemErro);
    }

    return isValid;
}

function validarIntervaloHoras(horario) {
    const regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]-([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
    if (!regex.test(horario)) {
        return false; // Formato inválido
    }

    const partes = horario.split('-');
    const horaInicial = partes[0];
    const horaFinal = partes[1];

    const [horaIni, minutoIni] = horaInicial.split(':').map(Number);
    const [horaFim, minutoFim] = horaFinal.split(':').map(Number);

    // Converte tudo para minutos para facilitar a comparação
    const totalMinutosIni = horaIni * 60 + minutoIni;
    const totalMinutosFim = horaFim * 60 + minutoFim;

    return totalMinutosFim > totalMinutosIni;
}

function validarCamposSchedule() {
    let campos = [$('#week0Time0'), $('#week0Time1'), $('#week0Time2'), $('#week0Time3')];
    let campoInvalido = null;

    for (let campo of campos) {
        // Remove a classe de invalido para revalidar
        campo.removeClass('campo-invalido');

        if (!validarIntervaloHoras(campo.val())) {
            campo.addClass('campo-invalido');
            if (!campoInvalido) {
                campoInvalido = campo; // Armazena o primeiro campo inválido encontrado
            }
        }
    }

    if (campoInvalido) {
        campoInvalido.focus(); // Move o foco para o primeiro campo inválido encontrado
        alert("Por favor, insira o tempo no formato 00:00-23:59, onde a hora inicial é menor que a hora final.");
        return false;
    }

    return true; // Todos os campos são válidos
}

function limparCamposCadPerfil() {
    $('#idCliente').val(null).trigger('change');
    $('#nomePerfil').val('');
    $('#idCliente').attr('disabled', false);
    buscarSeguradoras(true);
}

function limparCamposAddPower() {
    $('#idUsuario').val(null).trigger('change');
    $('#serialPower').empty();
    $('#serialPower').append(`<option value="" selected>Selecione o Serial</option>`);
    $('#idCfgPerfil').empty();
    $('#idCfgPerfil').append(`<option value="" selected>Selecione o Perfil</option>`);
    $('#switchValue').val('');
    $('#delay').val('');
    $('#reserveDelay').val('');
    $('#hibernationReport').val('');
    $('#screenOffTime').val('');
    $('#powerOnTime').val('');
    $('#powerOffTime').val('');
    $('#accPowerOffRecEnable').val('');
    $('#accOffRecTime').val('');
    $('#timeRebootEn').val('');
    $('#rebootTime').val('');
    $('#lowPowerOff').val('');
    $('#littlePowerEnable').val('');
    $('#idUsuario').attr('disabled', false);
    $('#idCfgPerfil').attr('disabled', false);
    limparCamposSchedule();
}

function limparCamposSchedule() {
    $('#week0Time0').val('');
    $('#week0Time1').val('');
    $('#week0Time2').val('');
    $('#week0Time3').val('');
}

function valoresPadrao() {
    $('#switchValue').val('1');
    $('#delay').val('1440');
    $('#reserveDelay').val('1440');
    $('#hibernationReport').val('1');
    $('#screenOffTime').val('3');
    $('#powerOnTime').val('0');
    $('#powerOffTime').val('86219');
    $('#accPowerOffRecEnable').val('122');
    $('#accOffRecTime').val('1440');
    $('#timeRebootEn').val('0');
    $('#rebootTime').val('0');
    $('#lowPowerOff').val('90');
    $('#littlePowerEnable').val('0');
    $('#week0Time0').val('00:00-23:59');
    $('#week0Time1').val('00:00-23:59');
    $('#week0Time2').val('00:00-23:59');
    $('#week0Time3').val('00:00-23:59');
}

function limparCamposComandos() {
    $('#idClienteComando').val(null).trigger('change');
    $('#serialComando').empty();
    $('#serialComando').append(`<option value="" selected>Selecione o Serial</option>`);
    $('#idPerfilComando').empty();
    $('#idPerfilComando').append(`<option value="" selected>Selecione o Perfil</option>`);
    $('#placa').val('');
    $('#seriais').prop('checked', false);
    $('#qtdCameras').val(null).trigger('change');
    $('#tipoEquipamento').val(null).trigger('change');

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

//MANTER FILTRO NO CAMPO DE BUSCA
function atualizarTableOmnisafe() {

    var searchOptions = null;

    searchOptions = {
        serial: $("#serialBusca").val(),
        nomePerfil: $("#perfilBusca").val(),
        idCliente: $("#clienteBusca").val()
    };

    if (searchOptions && (searchOptions.serial || searchOptions.nomePerfil || searchOptions.idCliente)) {
        getDadosOmnisafe(function (error) {
            if (!error) {
                atualizarAgGridOmnisafe(searchOptions);
            } else {
                atualizarAgGridOmnisafe();
            }
        }, searchOptions)
    } else {
        getDadosOmnisafe(function (error) {
            if (!error) {
                atualizarAgGridOmnisafe();
            } else {
                atualizarAgGridOmnisafe();
            }
        })
    }
}

function atualizarTablePower() {

    var searchOptions = null;

    searchOptions = {
        serial: $("#serialBusca").val(),
        idCliente: $("#clienteBusca").val()
    };

    if (searchOptions && (searchOptions.serial || searchOptions.idCliente)) {
        getDadosOmnisafe(function (error) {
            if (!error) {
                atualizarAgGridConfigPower(searchOptions);
            } else {
                atualizarAgGridConfigPower();
            }
        }, searchOptions)
    } else {
        getDadosOmnisafe(function (error) {
            if (!error) {
                atualizarAgGridConfigPower();
            } else {
                atualizarAgGridConfigPower();
            }
        })
    }
}

function atualizarTableComando() {

    var searchOptions = null;

    searchOptions = {
        serial: $("#serialBusca").val(),
        statusEnvioBusca: $("#statusEnvioBusca").val(),
        statusRecebimentoBusca: $("#statusRecebimentoBusca").val(),
        idCliente: $("#clienteBusca").val()
    };

    if (searchOptions && (searchOptions.serial || searchOptions.idCliente || searchOptions.statusEnvioBusca || searchOptions.statusRecebimentoBusca)) {
        getDadosOmnisafe(function (error) {
            if (!error) {
                atualizarAgGridHistoricoComandos(searchOptions);
            } else {
                atualizarAgGridHistoricoComandos();
            }
        }, searchOptions)
    } else {
        getDadosOmnisafe(function (error) {
            if (!error) {
                atualizarAgGridHistoricoComandos();
            } else {
                atualizarAgGridHistoricoComandos();
            }
        })
    }
}

function getDadosOmnisafe(callback, options) {

    if (options) {
        if (options.serial || options.nomePerfil || options.idCliente || options.statusEnvioBusca || options.statusRecebimentoBusca) {
            showLoadingPesquisarButton();
            $("#loadingMessage").hide();
        } else {
            showAlert('error', 'Dados Insuficientes')
            resetPesquisarButton();
        }
    }

    if (typeof callback === "function") callback(null);
}

//PREENCHE SELECT'S DE CLIENTE
function buscarSeguradoras(hide) {

    let route = Router + '/clientesSelect2';
    $.ajax({
        cache: false,
        url: route,
        type: 'GET',
        dataType: 'json',
        success: function (data) {

            if (data.status == 200) {
                data.resultado.forEach(item => {
                    $('#clienteBusca, #idCliente, #idUsuario, #idClienteComando').append(`<option value="${item.id}">${item.nome} (${(item.cnpj !== null && item.cnpj !== '') ? item.cnpj : (item.cpf === null || item.cpf === '') ? '' : item.cpf})</option>`);
                });

                if (!hide) {
                    $("#clienteBusca").select2({
                        placeholder: "Digite o nome do Cliente",
                        allowClear: true,
                        language: "pt-BR",
                        width: 'resolve',
                        height: '32px',
                    })
                    $("#clienteBusca").select2('val', ' ')
                }

                $("#idCliente").select2({
                    placeholder: "Digite o nome do Cliente",
                    allowClear: true,
                    language: "pt-BR",
                    width: 'resolve',
                    height: '32px',
                })
                $("#idCliente").select2('val', ' ')

                $("#idUsuario").select2({
                    placeholder: "Digite o nome do Cliente",
                    allowClear: true,
                    language: "pt-BR",
                    width: 'resolve',
                    height: '32px',
                })
                $("#idUsuario").select2('val', ' ')

                $("#idClienteComando").select2({
                    placeholder: "Digite o nome do Cliente",
                    allowClear: true,
                    language: "pt-BR",
                    width: 'resolve',
                    height: '32px',
                })
                $("#idClienteComando").select2('val', ' ')
                limparSelectSerial();
            } else if (data.status == 400) {
                showAlert('error', data['resultado']['mensagem'])
            } else {
                showAlert('error', 'Erro ao listar Clientes')
            }
        },
        error: function (error) {
            showAlert('error', 'Erro ao listar Clientes')
        }
    });
}

function getClientesSelect2() {
    $("#clienteBusca, #idCliente, #idUsuario, #idClienteComando").select2({
        placeholder: "Digite o nome do cliente",
        allowClear: true,
        language: "pt-BR",
        width: 'resolve',
        height: '32px',
        minimumInputLength: 4,
        ajax: {
            url: Router + '/clientesSelect2',
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) {
                var query = {
                    itemInicio: 1,
                    itemFim: 100
                };
                if (params.term && params.term.length >= 4) {
                    query.searchTerm = params.term;
                }
                return query;
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.resultado.clientesDTO.map(item => {
                        return {
                            id: item.id,
                            text: `${item.nome} (${(item.cnpj !== null && item.cnpj !== '') ? item.cnpj : (item.cpf === null || item.cpf === '') ? '' : item.cpf})`
                        };
                    }),
                    pagination: {
                        more: false
                    }
                };
            }
        }
    });
}

//POSIÇÃO DO BOTÃO DE AÇÕES
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

function stopAgGRIDOmnisafe() {
    var gridDiv = document.querySelector('#tableOmnisafe');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperOmnisafe');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableOmnisafe" class="ag-theme-alpine my-grid-omnisafe"></div>';
    }
}

function stopAgGRIDConfigPower() {
    var gridDiv = document.querySelector('#tablePower');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperPower');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tablePower" class="ag-theme-alpine my-grid-power"></div>';
    }
}

function stopAgGRIDItensSchedule() {
    var gridDiv = document.querySelector('#tableItensSchedule');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensSchedule');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensSchedule" class="ag-theme-alpine my-grid-schedule"></div>';
    }
}

function stopAgGRIDUltimaConfiguracao() {
    var gridDiv = document.querySelector('#tableUltimaConfiguracao');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperUltimaConfiguracao');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableUltimaConfiguracao" class="ag-theme-alpine my-grid-UltimaConfiguracao"></div>';
    }
}

function stopAgGRIDHistoricoComandos() {
    var gridDiv = document.querySelector('#tableHistoricoComandos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperHistoricoComandos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableHistoricoComandos" class="ag-theme-alpine my-grid-historico-comandos"></div>';
    }
}

//VISIBILIDADE
function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function limparPesquisa() {
    document.getElementById('formBusca').reset();
    $('#clienteBusca').val(null).trigger('change');
    $('#statusRecebimentoBusca').val(null).trigger('change');
    $('#statusEnvioBusca').val(null).trigger('change');
}

function showLoadingLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

function showLoadingSalvarButtonPerfil() {
    $('#btnSalvarPerfil').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonPerfil() {
    $('#btnSalvarPerfil').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarButtonComando() {
    $('#btnSalvarComando').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonComando() {
    $('#btnSalvarComando').html('Salvar').attr('disabled', false);
}

function showLoadingSalvarButtonPowerSchedule() {
    $('#btnSalvarAddPowerSchedule').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonPowerSchedule() {
    $('#btnSalvarAddPowerSchedule').html('Salvar').attr('disabled', false);
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Filtrar').attr('disabled', false);
}

function limparSelectSerial() {
    $("#serial").empty();
    $("#serial").append('<option value="" disabled selected>Selecione o serial</option>');
    $("#serial").select2({
        placeholder: "Selecione o serial",
        language: "pt-BR",
        width: 'resolve',
        minimumResultsForSearch: Infinity
    });
}

function CustomTimeCellEditor() { }

CustomTimeCellEditor.prototype.init = function (params) {
    this.params = params;
    this.eInput = document.createElement('input');
    this.eInput.setAttribute('type', 'text');
    this.eInput.classList.add('ag-input-field-input');
    this.eInput.value = params.value || '00:00-00:00'; // Define o valor padrão se vazio

    this.eInput.addEventListener('input', this.applyMask.bind(this));
    this.eInput.addEventListener('blur', this.validateAndFormat.bind(this));
    this.eInput.addEventListener('keydown', this.onKeyDown.bind(this));

    this.eInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            // Impede ação padrão do Enter para evitar comportamento indesejado
            event.preventDefault();

            // Força o AG Grid a parar a edição e salvar o valor atual
            params.api.stopEditing();
        }
    });
};

CustomTimeCellEditor.prototype.applyMask = function (event) {
    // Aplica uma máscara para entrada no formato HH:MM-HH:MM enquanto o usuário digita
    let inputValue = event.target.value;
    let numbers = inputValue.replace(/\D/g, '');

    // Certifica-se de que a entrada tenha no máximo 8 números (HHMMHHMM)
    numbers = numbers.slice(0, 8);

    let formattedValue = '';
    if (numbers.length > 4) {
        formattedValue = `${numbers.slice(0, 2)}:${numbers.slice(2, 4)}-${numbers.slice(4, 6)}`;
        if (numbers.length > 6) {
            formattedValue += `:${numbers.slice(6, 8)}`;
        }
    } else if (numbers.length > 2) {
        formattedValue = `${numbers.slice(0, 2)}:${numbers.slice(2, 4)}`;
    } else if (numbers.length > 0) {
        formattedValue = numbers;
    }

    event.target.value = formattedValue;
};

CustomTimeCellEditor.prototype.validateAndFormat = function () {
    if (this.eInput.value.trim() === '') {
        this.eInput.value = '00:00-00:00'; // Preenche com o valor padrão se vazio
    } else {
        // Valida o formato ao sair do campo
        this.validateTimeFormat(this.eInput.value);
    }
};

CustomTimeCellEditor.prototype.onKeyDown = function (event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Impede a ação padrão do Enter para não sair do campo prematuramente
        this.validateAndFormat();
    }
};

CustomTimeCellEditor.prototype.validateTimeFormat = function (value) {
    const regex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]-([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
    if (!regex.test(value)) {
        alert("Por favor, insira o tempo no formato 00:00-23:59.");
        this.eInput.value = this.params.value || '00:00-00:00'; // Reverte para o valor original ou padrão
    }
};

// Outros métodos permanecem os mesmos...

CustomTimeCellEditor.prototype.getGui = function () {
    return this.eInput;
};

CustomTimeCellEditor.prototype.afterGuiAttached = function () {
    this.eInput.focus();
    this.eInput.select();
};

CustomTimeCellEditor.prototype.getValue = function () {
    const valor = this.eInput.value;
    const mascaraRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]-([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
    const horas = valor.split("-");

    // Verifica se o valor está no formato correto
    if (!mascaraRegex.test(valor)) {
        alert("Por favor, insira o tempo no formato 00:00-23:59.");
        // Retorna o valor anterior se o novo valor não estiver no formato correto
        return this.params.value;
    }

    // Extrai horas iniciais e finais
    const horaInicial = horas[0];
    const horaFinal = horas[1];

    // Converte as horas para minutos para facilitar a comparação
    const minutosInicial = parseInt(horaInicial.split(":")[0]) * 60 + parseInt(horaInicial.split(":")[1]);
    const minutosFinal = parseInt(horaFinal.split(":")[0]) * 60 + parseInt(horaFinal.split(":")[1]);

    // Verifica se a hora inicial é maior ou igual à hora final
    if (minutosInicial >= minutosFinal) {
        alert("A hora inicial deve ser menor que a hora final.");
        // Retorna o valor anterior
        return this.params.value;
    }

    return valor; // Retorna o novo valor se estiver tudo certo
};

CustomTimeCellEditor.prototype.destroy = function () { };

function nomeConfig(numConfig) {
    switch (numConfig) {
        case 1:
            return 'Omnisafe Plus 4CH 2 Câmeras';
        case 2:
            return 'Omnisafe Plus 4CH 3 Câmeras';
        case 3:
            return 'Omnisafe Plus 4CH 4 Câmeras';
        case 4:
            return 'Omnisafe Plus 8CH 2 Câmeras';
        case 5:
            return 'Omnisafe Plus 8CH 3 Câmeras';
        case 6:
            return 'Omnisafe Plus 8CH 4 Câmeras';
        case 7:
            return 'Omnisafe Plus 8CH 5 Câmeras';
        case 8:
            return 'Omnisafe Plus 8CH 6 Câmeras';
        case 9:
            return 'Omnisafe Plus 8CH 7 Câmeras';
        case 10:
            return 'Omnisafe Plus 8CH 8 Câmeras';
        case 11:
            return 'Omnisafe Dashcam 2 Câmeras';
        case 12:
            return 'Omnisafe Dashcam 3 Câmeras';
        case 13:
            return 'Omnisafe Dashcam 4 Câmeras';
    }
}

function statusEnvio(status) {
    switch (status) {
        case 0:
            return "Não Enviado";
        case 1:
            return "Envio em Processo";
        case 2:
            return "Enviado";
        case 3:
            return "Falha ao Enviar";
        case 4:
            return "Serial Inválido para Envio";
    }
}

function statusRecebimento(status) {
    switch (status) {
        case 0:
            return "Não Recebido";
        case 1:
            return "Verificando Recebimento";
        case 2:
            return "Recebido";
    }
}