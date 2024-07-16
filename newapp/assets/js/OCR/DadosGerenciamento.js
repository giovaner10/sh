
var idClienteDoc;
var cliente = '';
var email = '';
var existingEmails;
var showMap = false;
var localeText = AG_GRID_LOCALE_PT_BR;
var marcadores = [];
var marcadoresPartidaChegada = [];

$(document).ready(function () {

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            placa: $("#placaBusca").val(),
            dataInicial: $("#dataInicial").val(),
            dataFinal: $("#dataFinal").val(),
            tipoMatch: $("#tipoEventoBusca").val()
        };

        var dataInicial = new Date($("#dataInicial").val());
        var dataFinal = new Date($("#dataFinal").val());

        var eventosPlacasVisivel = $('#menu-eventos-placas').hasClass("selected");

        if (eventosPlacasVisivel) {
            if (dataInicial > dataFinal) {
                resetPesquisarButton();
                showAlert('warning', "Data Final não pode ser menor que a Data Inicial!")
                return;
            }
            $('#emptyMessageEventosPlacas').hide();
            validarDadosEventosPlacas(function(error) {
                if (!error) {
                    carregarMapaEventos(-15.39,-55.73,4);
                    atualizarAgGridEventosPlacas(searchOptions);
                } else { 
                    carregarMapaEventos(-15.39,-55.73,4);
                    atualizarAgGridEventosPlacas()
                    resetPesquisarButton()
                }
            }, searchOptions)
        } else {
            if (dataInicial > dataFinal) {
                resetPesquisarButton();
                showAlert('warning', "Data Final não pode ser menor que a Data Inicial")
                return;
            }
            $('#emptyMessage').hide();
            validarDados(function (error) {
                if (!error) {
                    if ($('#menu-gerenciamento').hasClass('selected')) {
                        $('.card-dados-mapa').show()
                        showMap = true;
                        carregarMapa(-15.39, -55.73, 4);
                        atualizarAgGrid(searchOptions);
                    }
                } else {
                    carregarMapa(-15.39, -55.73, 4);
                    atualizarAgGrid();
                }
            }, searchOptions);
        }
    });

    $('#menu-eventos-placas').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-gerenciamento').removeClass("selected");
            $('#menu-blacklist').removeClass("selected");
            $('#menu-whitelist').removeClass("selected");
            $('#menu-alertas-email').removeClass("selected");
            $('.card-dados-gerenciamento').hide()
            $('.card-dados-mapa').hide()
            $('.card-blacklist').hide()
            $('.card-alertas-email').hide()
            $('.card-eventos-placas').show()
            $('.card-whitelist').hide()
            $('.buscaCliente').hide()
            $('.buscaTipoMatch').show()
            $('.buscaData').show()
            $('.buscaAlertasEmail').hide()
            $('.card-dados-mapa-eventos').show();
            $.ajax({
                url: Router + '/addSession/menu_ocr/EventosPlacas',
                type: 'GET'
            });
            validarDadosEventosPlacas(function(error) {
                if (!error) {
                    carregarMapaEventos(-15.39,-55.73,4);
                    atualizarAgGridEventosPlacas();
                } else { 
                    carregarMapaEventos(-15.39,-55.73,4);
                    atualizarAgGridEventosPlacas()
                    resetPesquisarButton()
                }
            })
        }
    });

    $('#menu-gerenciamento').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-blacklist').removeClass("selected");
            $('#menu-alertas-email').removeClass("selected");
            $('#menu-eventos-placas').removeClass("selected");
            $('#menu-whitelist').removeClass("selected");
            $('.card-dados-gerenciamento').show()
            if (showMap) $('.card-dados-mapa').show()
            $('.card-dados-mapa-eventos').hide()
            $('.card-eventos-placas').hide()
            $('.card-blacklist').hide()
            $('.card-alertas-email').hide()
            $('.card-whitelist').hide()
            $('.buscaCliente').hide()
            $('.buscaTipoMatch').show()
            $('.buscaAlertasEmail').hide()
            $('.buscaData').show()
            $.ajax({
                url: Router + '/addSession/menu_ocr/DadosGerenciamentoOCR',
                type: 'GET'
            });
            validarDados(function (error) {
                if (!error) {
                    if ($('#menu-gerenciamento').hasClass('selected')) {
                        $('.card-dados-mapa').show()
                        carregarMapa(-15.39, -55.73, 4);
                        atualizarAgGrid();
                        showMap = true;
                    }
                } else {
                    carregarMapa(-15.39, -55.73, 4);
                    atualizarAgGrid();
                }
            });
        }
    });

    $('.menu-interno-link').click(function () {
        limparPesquisa();
    })

    if ($('#menu-eventos-placas').hasClass("selected")) {
        $('.card-dados-mapa-eventos').show();
        validarDadosEventosPlacas(function(error) {
            if (!error) {
                carregarMapaEventos(-15.39,-55.73,4);
                atualizarAgGridEventosPlacas();
            } else { 
                carregarMapaEventos(-15.39,-55.73,4);
                atualizarAgGridEventosPlacas()
                resetPesquisarButton()
            }
        })
    }

    if ($('#menu-gerenciamento').hasClass('selected')) {
        validarDados(function (error) {
            if (!error) {
                if ($('#menu-gerenciamento').hasClass('selected')) {
                    $('.card-dados-mapa').show()
                    carregarMapa(-15.39, -55.73, 4);
                    atualizarAgGrid();
                    showMap = true;
                }
            } else {
                carregarMapa(-15.39, -55.73, 4);
                atualizarAgGrid();
            }
        });
    }
    
    $('#BtnLimparFiltro').click(function() {
        showLoadingLimparButton();

        if ($("#menu-eventos-placas").hasClass('selected')) {
            $('#placaBusca').val('');
            $('#dataInicial').val('');
            $('#dataFinal').val('');
            $('#tipoEventoBusca').val(null).trigger('change');
            validarDadosEventosPlacas(function(error) {
                if (!error) {
                    carregarMapaEventos(-15.39,-55.73,4);
                    atualizarAgGridEventosPlacas();
                } else { 
                    carregarMapaEventos(-15.39,-55.73,4);
                    atualizarAgGridEventosPlacas()
                    resetPesquisarButton()
                }
            })
        }else if ($("#menu-gerenciamento").hasClass('selected')) {
            $('#placaBusca').val('');
            $('#dataInicial').val('');
            $('#dataFinal').val('');
            $('#tipoEventoBusca').val(null).trigger('change');
            atualizarAgGrid();
        } 
    });

    $("#tipoEventoBusca").select2({
        placeholder: "Selecione o tipo",
        allowClear: true,
        language: "pt-BR",
        minimumResultsForSearch: -1
    })

    $('#tipoEventoBusca').val(null).trigger('change');

    $('#eventosPlacas').on('hide.bs.modal', function(){
        $('#ocorrencia').val('');
        $('#status').val('');
        $('#motivo').val('');
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
    $('#tipoEventoBusca').val(null).trigger('change');
}

//Requisições

function abrirDadosDetalhes(id) {
    ShowLoadingScreen();
    let route = Router + '/buscarDadosGerenciamentoByID';
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                $('#infoModal').modal('show');
                limparMarcasNoMapaPartidaChegada(marcadoresPartidaChegada);
                carregarMapaPartidaChegada(-15.39, -55.73, 4);
                marcadoresPartidaChegada = marcarNoMapaPartidaChegada(data.resultado);
                preencherDadosDetalhes(data.resultado);
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

function validarDados(callback, options) {
    $("#loadingMessage").show();

    if (options) {
        if (options.tipoMatch || options.placa || (options.dataInicial && options.dataFinal)) {
            if (options.dataInicial && !options.dataFinal) {
                showAlert('warning', 'Informe uma Data Final para fazer a busca!')
                $("#loadingMessage").hide();
                resetPesquisarButton();
                return;
            } else if (options.dataFinal && !options.dataInicial) {
                showAlert('warning', 'Informe uma Data Inicial para fazer a busca!')
                $("#loadingMessage").hide();
                resetPesquisarButton();
                return;
            } else {
                showLoadingPesquisarButton();
                $("#loadingMessage").hide();
                if (typeof callback === "function") callback(null);
            }
        } else {
            if (options.dataInicial && !options.dataFinal) {
                showAlert('warning', 'Informe uma Data Final para fazer a busca!')
                $("#loadingMessage").hide();
                resetPesquisarButton();
                return;
            } else if (options.dataFinal && !options.dataInicial) {
                showAlert('warning', 'Informe uma Data Inicial para fazer a busca!')
                $("#loadingMessage").hide();
                resetPesquisarButton();
                return;
            } else {
                showAlert('warning', 'Dados insuficientes para fazer uma busca!')
                $("#loadingMessage").hide();
                resetPesquisarButton();
                return;
            }

        }
    } else {
        $("#loadingMessage").hide();
        if (typeof callback === "function") callback(null);
    }
}

function validarDadosEventosPlacas(callback, options) {
    var route;

    if (options) {
        if (options.placa || (options.dataInicial && options.dataFinal) || options.tipoMatch) {
            if (options.dataInicial && !options.dataFinal) {
                showAlert('warning', 'Informe uma Data Final para fazer a busca!')
                resetPesquisarButton();
                return;
            } else if (options.dataFinal && !options.dataInicial) {
                showAlert('warning', 'Informe uma Data Inicial para fazer a busca!')
                resetPesquisarButton();
                return;
            } else {
                showLoadingPesquisarButton();
                if (typeof callback === "function") callback(null);
            }

        } else {
            showAlert('warning', 'Dados Insuficientes para fazer uma busca!')
            resetPesquisarButton();
            return;
        }
    } else {
        if (typeof callback === "function") callback(null);
    }
}

function abrirEventosPlacas(id) {
    ShowLoadingScreen();
    let route = Router + '/buscaEventosPlacasByID';
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                $('#eventosPlacas').modal('show');
                carregarMapaEventoIndividual(-15.39, -55.73, 4);
                marcarNoMapaEventoIndividual(data);
                preencherEventoPlaca(data.resultado);
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

    $('.formEvento').off('submit').on('submit', function(event){
        event.preventDefault();
        showLoadingSalvarTratamentoButton();

        var status = $('#status').val();
        var motivo = $('#motivo').val().replace(' - ', '-');
        var ocorrencia = $('#ocorrencia').val().replace(' - ', '-');

        $('#ocorrencia').val(ocorrencia);
        $('#motivo').val(motivo);

        if (ocorrencia) {
            motivo = ocorrencia + ' - ' + motivo;
        }

        var dados = {
            id: id,
            status: status,
            motivo: motivo
        }

        $.ajax({
            url: Router + '/atualizarStatusEvento',
            type: 'POST',
            data: dados,
            dataType: 'json',
            success: function (response) {
                if (status == '2') {
                    $('#detalheStatusEvento').html(`<span class="badge badge-warning">Tratado</span>`);
                } else if (status == '3') {
                    $('#detalheStatusEvento').html(`<span class="badge badge-primary status-primary">Em Tratativa</span>`);
                } else if (status == '4') {
                    $('#detalheStatusEvento').html(`<span class="badge badge-secondary status-secondary">Finalizado Evento Real</span>`);
                } else if (status == '5') {
                    $('#detalheStatusEvento').html(`<span class="badge badge-dark status-dark">Finalizado Evento Falso</span>`);
                }
                showAlert('success', "Status atualizado com sucesso!")
                resetSalvarTratamentoButton();
            },
            error: function (error) {
                showAlert('error', 'Erro ao atualizar evento.')
                resetSalvarTratamentoButton();
                HideLoadingScreen();
            }
        });
    })
}

$(window).scroll(function() {
    // Fecha todos os Select2 quando ocorrer um scroll
    $('.select2-container--open').each(function () {
        var $select2 = $(this).prev('select');
        $select2.select2('close');
    });
});

//Preencher
function preencherDadosDetalhes(dados) {
    HideLoadingScreen();
    $('#detalheSerial').html(dados['serial']);
    $('#detalhePlacaLida').html(dados['placa_lida']);
    $('#detalheTipoAlarme').html(dados['alarm_type']);
    $('#detalheMarca').html(dados['marca']);
    $('#detalheModelo').html(dados['modelo']);
    $('#detalheDataInicial').html(formatDateTime(dados['file_s_time']));
    $('#detalheDataFinal').html(formatDateTime(dados['file_e_time']));
    $('#detalheLatInicial').html(dados['latitude']);
    $('#detalheLonInicial').html(dados['longitude']);
    $('#detalheLatFinal').html(dados['latitude_fim']);
    $('#detalheLonFinal').html(dados['longitude_fim']);
    $('#detalheRefInicial').html(dados['enredeco'] ? dados['enredeco'] : 'Referência não encontrada');
    $('#detalheRefFinal').html(dados['enredeco'] ? dados['enredeco'] : 'Referência não encontrada');
    if (dados['tipo_match'] == '1') {
        $('#detalheTipo').html('<span class="badge badge-danger">Hot List</span>');
    } else if (dados['tipo_match'] == '2') {
        $('#detalheTipo').html('<span class="badge badge-info">Cold List</span>');
    } else {
        $('#detalheTipo').html(`<span class="badge badge-default">Indefinido</span>`);
    }

    if (dados['file'] != null) {

        let urlImg = "data:image/png;base64," + dados['file'];
        if (dados['status_foto'] == 1) {
            $('#div-img').show();
            $('#div-img-mensagem').hide();
            $('#img').attr('src', urlImg);
        } else {
            $('#div-img').hide();
            $('#div-img-mensagem').show();
        }
    }
    else {
        $('#div-img').hide();
        $('#div-img-mensagem').show();
    }

    if (dados['file_plate'] != null) {

        let urlImg = "data:image/png;base64," + dados['file_plate'];
        if (dados['status_foto'] == 1) {
            $('#div-img-plate').show();
            $('#div-img-mensagem-plate').hide();
            $('#img-plate').attr('src', urlImg);
        } else {
            $('#div-img-plate').hide();
            $('#div-img-mensagem-plate').show();
        }
    }
    else {
        $('#div-img-plate').hide();
        $('#div-img-mensagem-plate').show();
    }

    $('#map').attr('href', `https://www.google.es/maps/dir/'${dados['latitude']},${dados['longitude']}'/'${dados['latitude_fim']},${dados['longitude_fim']}'`)

}

function preencherEventoPlaca(dados) {
    HideLoadingScreen();
    $('#detalheSerialEvento').html(dados['serial']);
    $('#detalhePlacaLidaEvento').html(dados['placa_lida']);
    $('#detalheMarcaEvento').html(dados['marca']);
    $('#detalheModeloEvento').html(dados['modelo']);
    $('#detalheClienteEvento').html(dados['cliente']);
    $('#detalheDataInicialEvento').html(formatDateTime(dados['file_s_time']));
    $('#detalheDataFinalEvento').html(formatDateTime(dados['file_e_time']));
    $('#detalheLatEvento').html(dados['latitude']);
    $('#detalheLonEvento').html(dados['longitude']);
    $('#detalheRefEvento').html(dados['endereco'] ? dados['endereco'] : "Referência não encontrada");
    if (dados['status'] == '0') {
        $('#detalheStatusEvento').html(`<span class="badge badge-info">Inserido</span>`);
    } else if (dados['status'] == '1') {
        $('#detalheStatusEvento').html(`<span class="badge badge-success">Visualizado</span>`);
    } else if (dados['status'] == '2') {
        $('#detalheStatusEvento').html(`<span class="badge badge-warning">Tratado</span>`);
    } else if (dados['status'] == '3') {
        $('#detalheStatusEvento').html(`<span class="badge badge-primary status-primary">Em Tratativa</span>`);
    } else if (dados['status'] == '4') {
        $('#detalheStatusEvento').html(`<span class="badge badge-secondary status-secondary">Finalizado Evento Real</span>`);
    } else if (dados['status'] == '5') {
        $('#detalheStatusEvento').html(`<span class="badge badge-dark status-dark">Finalizado Evento Falso</span>`);
    }

    if (dados['tipo_match'] == '1') {
        $('#detalheTipoEvento').html('<span class="badge badge-danger">Hot List</span>');
    } else if (dados['tipo_match'] == '2') {
        $('#detalheTipoEvento').html('<span class="badge badge-info">Cold List</span>');
    } else {
        $('#detalheTipoEvento').html(`<span class="badge badge-default">Indefinido</span>`);
    }

    if (dados['file'] != null) {

        let urlImg = "data:image/png;base64," + dados['file'];
        $('#div-img-evento').show();
        $('#div-img-mensagem-evento').hide();
        $('#img-evento').attr('src', urlImg);
    }
    else {
        $('#div-img-evento').hide();
        $('#div-img-mensagem-evento').show();
    }

    if (dados['file_plate'] != null) {

        let urlImg = "data:image/png;base64," + dados['file_plate'];
        $('#div-img-evento-placa').show();
        $('#div-img-mensagem-evento-placa').hide();
        $('#img-evento-placa').attr('src', urlImg);
    }
    else {
        $('#div-img-evento-placa').hide();
        $('#div-img-mensagem-evento-placa').show();
    }

    if (dados['motivo'] != null) {
        let motivoCompleto = dados['motivo'].split(' - ');
        let ocorrencia = '';
        let motivo = '';

        if (motivoCompleto.length > 1) {
            ocorrencia = motivoCompleto[0];
            motivo = motivoCompleto[1];
        } else {
            motivo = motivoCompleto[0];
        }

        $('#ocorrencia').val(ocorrencia);
        $('#status').val(dados['status'] ? dados['status'] : '');
        $('#motivo').val(motivo);

    }

    if (dados['tipo_match'] && dados['tipo_match'] == 2) {
        $('#ocorrencia').removeAttr("required");
        $('#labelForOcorrencia').html('Número da ocorrência: ');
    } else {
        $('#ocorrencia').attr('required', true);
        $('#labelForOcorrencia').html('Número da ocorrência: <span class="text-danger">*</span>');
    }


    // $('#mapaPartida').attr('src', `https://maps.google.com/maps/embed?q=${dados['latitude']},${dados['longitude']}&z=8&amp`)
    //$('#mapaEvento').attr('src', `https://maps.google.com.br/maps?q=${dados['latitude']},${dados['longitude']}&output=embed&dg=oo&z=7`)
}


var AgGrid;
function atualizarAgGrid(options) {
    stopAgGRID();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/buscarDadosServerSide';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        placa: options ? options.placa : '',
                        dataInicial: options ? options.dataInicial : '',
                        dataFinal: options ? options.dataFinal : '',
                        tipo: options ? options.tipoMatch : ''
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
                                    if (chave === 'best_plate') {
                                        if (dados[i][chave] == '1') {
                                            dados[i][chave] = 'Melhor Placa'
                                        } else {
                                            dados[i][chave] = 'Candidato'
                                        }
                                    }
                                    if (chave === 'tipo_match') {
                                        if (dados[i][chave] == '1') {
                                            dados[i][chave] = 'Hot List';
                                        } else if (dados[i][chave] == '2') {
                                            dados[i][chave] = 'Cold List';
                                        } else {
                                            dados[i][chave] = 'Indefinido';
                                        }
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
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            showAlert('error', 'Erro na solicitação ao servidor');
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
                        showAlert('error', 'Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        AgGrid.gridOptions.api.showNoRowsOverlay();
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

    function getContextMenuItems(params) {
        if (params && params.node && 'data' in params.node && 'id_evento' in params.node.data) {
            var result = [
                {
                    // custom item
                    name: 'Visualizar',
                    action: () => {
                        abrirDadosDetalhes(params.node.data.id_evento)
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
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'series',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa Lida',
                field: 'placa_lida',
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Tipo',
                field: 'tipo_match',
                chartDataType: 'category',
                width: 120,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Hot List') {
                        return `<span class="badge badge-danger">Hot List</span>`;
                    } else if (options.value == 'Cold List') {
                        return `<span class="badge badge-info">Cold List</span>`;
                    } else if (options.value == 'Indefinido') {
                        return `<span class="badge badge-default">Indefinido</span>`;
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Marca',
                field: 'marca',
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Melhor Placa',
                field: 'best_plate',
                chartDataType: 'series',
                width: 140,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Melhor Placa') {
                        return `<span class="badge badge-warning">Melhor Placa</span>`;
                    } else {
                        return `<span class="badge badge-info">Candidato</span>`;
                    }
                }
            },
            {
                headerName: 'Data Inicial',
                field: 'file_s_time',
                chartDataType: 'series',
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Data Final',
                field: 'file_e_time',
                chartDataType: 'series',
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            { 
                headerName: 'Endereço',
                field: 'enredeco',
                width: 380,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data;
                    let inlineStyle = `
                        <style>
                            .linkEnderecoEvento {
                                text-decoration: none;
                                color: #337ab7;
                            }
                            .linkEnderecoEvento:hover {
                                color: #275d8b;
                            }
                        </style>`;
                    let url = `https://www.google.com.br/maps/dir//${data.latitude},${data.longitude}/@${data.latitude},${data.longitude},17z?entry=ttu`;
                    return `${inlineStyle} <a class="linkEnderecoEvento" href="${url}" target="_blank">${data.enredeco}</a>`;
                },
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options)  {
                    let data = options.data;
                    let tableId = "table";
                    let dropdownId = "dropdown-menu-" + data.id_evento;
                    let buttonId = "dropdownMenuButton_" + data.id_evento;

                    return `
                    <div class="dropdown" style="position: relative;">
                            <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdown('${dropdownId}','${buttonId}', '${tableId}');" ondblclick="event.preventDefault(); event.stopPropagation();" class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id_evento}" nome="${data.placa_lida}" id="${data.id_evento}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                    <a href="javascript:abrirDadosDetalhes(${data.id_evento})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                                </div>
                            </div>
                    </div>`;
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
        getContextMenuItems: getContextMenuItems,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('id_evento' in data) {
                    abrirDadosDetalhes(data.id_evento)
                } else {
                    showAlert('warning', 'Desculpe, não foi possível encontrar o identificador do evento.')
                }
            }
        }
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '519px');
    
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    gridOptions.api.addEventListener('paginationChanged', function(event) {
        $('#loadingMessage').show();

        let paginaAtual = Number(event.api.paginationGetCurrentPage());
        let tamanhoPagina = Number(event.api.paginationGetPageSize());

        const filteredData = [];
        event.api.forEachNode((n) => {
            filteredData.push(n.data);
        });

        const startIndex = paginaAtual * tamanhoPagina;
        const endIndex = startIndex + tamanhoPagina;
        const pageData = filteredData.slice(startIndex, endIndex);
        
        var dados = [];
        pageData.forEach((data) => {
            if (data) {
                dados.push(data)
            }
        })
        limparMarcasNoMapa(marcadores)
        
        marcadores = marcarNoMapa(dados)
        $('#loadingMessage').hide();
    
    });

    
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoes(gridOptions)
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


var AgGridEventosPlacas;
function atualizarAgGridEventosPlacas(options) {
    stopAgGRIDEventosPlacas();
    function getServerSideDados() {
        return {
            getRows: (params) => {
        
            var route = Router + '/buscarDadosEventosPlacasServerSide';
        
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    startRow: params.request.startRow,
                    endRow: params.request.endRow,
                    rowGroupCols: params.request.rowGroupCols,
                    groupKeys: params.request.groupKeys,
                    placa: options ? options.placa : '',
                    dataInicial: options ? options.dataInicial : '',
                    dataFinal: options ? options.dataFinal : '',
                    tipoMatch: options ? options.tipoMatch : 0
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
                                if (params.request.rowGroupCols.length > params.request.groupKeys.length) {
                                    dados[i][chave] = 'Inexistente';
                                } else {
                                    dados[i][chave] = '';
                                }
                                
                            }
                            if (chave === 'status') {
                                if (dados[i][chave] == '0') {
                                    dados[i][chave] = 'Inserido';
                                } else if (dados[i][chave] == '1') {
                                    dados[i][chave] = 'Visualizado';
                                } else if (dados[i][chave] == '2') {
                                    dados[i][chave] = 'Tratado';
                                } else if (dados[i][chave] == '3') {
                                    dados[i][chave] = 'Em Tratativa';
                                } else if (dados[i][chave] == '4') {
                                    dados[i][chave] = 'Finalizado Evento Real';
                                } else if (dados[i][chave] == '5') {
                                    dados[i][chave] = 'Finalizado Evento Falso';
                                } else {
                                    dados[i][chave] = '';
                                }
                            }
                            if (chave === 'bestPlate') {
                                if (dados[i][chave] == '1') {
                                    dados[i][chave] = 'Melhor Placa';
                                } else {
                                    dados[i][chave] = 'Candidato';
                                }
                            }
                            if (chave === 'tipoMatch') {
                                if (dados[i][chave] == '1') {
                                    dados[i][chave] = 'Hot List';
                                } else if (dados[i][chave] == '2') {
                                    dados[i][chave] = 'Cold List';
                                } else {
                                    dados[i][chave] = 'Indefinido';
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
                    AgGridEventosPlacas.gridOptions.api.showNoRowsOverlay();
                } else {
                    showAlert('error', 'Erro na solicitação ao servidor');
                    params.failCallback();
                    params.success({
                        rowData: [],
                        rowCount: 0,
                    });
                    AgGridEventosPlacas.gridOptions.api.showNoRowsOverlay();
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
                    AgGridEventosPlacas.gridOptions.api.showNoRowsOverlay();
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

    function getContextMenuItemsEventos(params) {
        if (params && params.node && 'data' in params.node && 'id' in params.node.data) {
            var result = [
                {
                    // custom item
                    name: 'Visualizar',
                    action: () => {
                        abrirEventosPlacas(params.node.data.id)
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
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                width: 185,
                enableRowGroup: true,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Inserido') {
                        return `<span class="badge badge-info">Inserido</span>`;
                    } else if (options.value == 'Visualizado') {
                        return `<span class="badge badge-success">Visualizado</span>`;
                    } else if (options.value == 'Tratado') {
                        return `<span class="badge badge-warning">Tratado</span>`;
                    } else if (options.value == 'Em Tratativa') {
                        return `<span class="badge badge-primary status-primary">Em Tratativa</span>`;
                    }else if (options.value == 'Finalizado Evento Real') {
                        return `<span class="badge badge-secondary status-secondary">Finalizado Evento Real</span>`;
                    }else if (options.value == 'Finalizado Evento Falso') {
                        return `<span class="badge badge-dark status-dark">Finalizado Evento Falso</span>`;
                    }
                }
            },
            {
                headerName: 'Tipo do evento',
                field: 'tipoMatch',
                chartDataType: 'category',
                enableRowGroup: true,
                width: 150,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value == 'Hot List') {
                        return `<span class="badge badge-danger">Hot List</span>`;
                    } else if (options.value == 'Cold List') {
                        return `<span class="badge badge-info">Cold List</span>`;
                    } else if (options.value == 'Indefinido') {
                        return `<span class="badge badge-default">Indefinido</span>`;
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Placa Lida',
                field: 'placaLida',
                enableRowGroup: true,
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Marca',
                field: 'marca',
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true,
                enableRowGroup: true, 
                cellRenderer: function (options) {
                    if (options.value == 'Inexistente') {
                        return `<p style="color: #aaa;">[Sem marca]</p> `
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Modelo',
                field: 'modelo',
                enableRowGroup: true,
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true, 
                cellRenderer: function (options) {
                    if (options.value == 'Inexistente') {
                        return `<p style="color: #aaa;">[Sem modelo]</p> `
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'Melhor Placa',
                field: 'bestPlate',
                enableRowGroup: true,
                chartDataType: 'series',
                width: 140,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    if (options.value) {
                        if (options.value == 'Melhor Placa') {
                            return `<span class="badge badge-warning">Melhor Placa</span>`;
                        } else {
                            return `<span class="badge badge-info">Candidato</span>`;
                        }
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: 'Início do evento',
                field: 'fileStartTime',
                enableRowGroup: true,
                chartDataType: 'category',
                cellRenderer: function (options) {
                    if (options.value) {
                        return formatDateTime(options.value);
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: 'Fim do evento',
                field: 'fileEndTime',
                enableRowGroup: true,
                chartDataType: 'category',
                cellRenderer: function (options) {
                    if (options.value) {
                        if (options.value == 'Inexistente') {
                            return `<p style="color: #aaa;">[Sem Data Fim]</p> `
                        } else {
                            return formatDateTime(options.value);
                        }
                    } else {
                        return '';
                    }
                }
            },
            {
                headerName: 'Latitude',
                field: 'latitude',
                enableRowGroup: true,
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Longitude',
                field: 'longitude',
                enableRowGroup: true,
                chartDataType: 'category',
                width: 140,
                suppressSizeToFit: true
            },
            {
                headerName: 'Serial',
                field: 'serial',
                chartDataType: 'category',
                enableRowGroup: true,
                width: 180,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'idCliente',
                enableRowGroup: true,
                chartDataType: 'category',
                width: 300,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.data.nome;
                }
            },
            { 
                headerName: 'Endereço',
                field: 'endereco',
                width: 380,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data;
                    let inlineStyle = `
                        <style>
                            #linkEnderecoEvento {
                                text-decoration: none;
                                color: #337ab7;
                            }
                            #linkEnderecoEvento:hover {
                                color: #275d8b;
                            }
                        </style>`;
                    let url = `https://www.google.com.br/maps/dir//${data.latitude},${data.longitude}/@${data.latitude},${data.longitude},17z?entry=ttu`;
                    if (data.latitude && data.longitude) { 
                        return `${inlineStyle} <a id="linkEnderecoEvento" href="${url}" target="_blank">${data.endereco}</a>`;
                    } else {
                        return '';
                    }
                }
            },
            { 
                headerName: 'Ações',
                width: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
                cellRenderer: function (options) {
                    let data = options.data;

                    let tableId = "tableEventosPlacas";
                    let dropdownId = "dropdown-menu-eventos" + data.id;
                    let buttonId = "dropdownMenuButtonEventos_" + data.id;
                    if (data.id) {
                        return `
                            <div class="dropdown" style="position: relative;">
                                <button onclick="javascript:abrirDropdown('${dropdownId}','${buttonId}', '${tableId}')" oncontextmenu="event.preventDefault(); event.stopPropagation(); abrirDropdown('${dropdownId}','${buttonId}', '${tableId}');" class="btn btn-dropdown dropdown-toggle" ondblclick="event.preventDefault(); event.stopPropagation();" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:abrirEventosPlacas(${data.id})" style="cursor: pointer; color: black;">Visualizar</a>
                                    </div>
                                </div>
                            </div>`;
                    } else {
                        return '';
                    }
                },
            },
            
        ],
        autoGroupColumnDef: {
            minWidth: '250'
        },
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
                        suppressRowGroups: false,
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
        onGridReady: function(params) {
            var maxGroupColumns = 3; // Defina o número máximo de colunas permitidas para agrupamento
    
            // Verificar se o número máximo de colunas agrupadas foi atingido quando uma coluna é agrupada
            params.api.addEventListener('columnRowGroupChanged', function() {
                var currentGroupedColumns = params.columnApi.getAllColumns().filter(col => col.rowGroupActive);
                if (currentGroupedColumns.length > maxGroupColumns) {
                    // Se excedeu o número máximo de colunas agrupadas, desfazer a última operação de agrupamento
                    var lastChangedColumnId = currentGroupedColumns[currentGroupedColumns.length - 1];
                    showAlert('warning', 'Quantidade máxima de colunas agrupadas atingida! A última coluna adicionada será removida.')
                    params.columnApi.removeRowGroupColumn(lastChangedColumnId);
                }
            });
        },
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        paginateChildRows: true,
        rowGroupPanelShow: 'always',
        domLayout: 'normal',
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-eventos-placas').val()),
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        getContextMenuItems: getContextMenuItemsEventos,
        onRowDoubleClicked: function (params) {
            let data = 'data' in params ? params.data : null;
            
            if (data) {
                if ('id' in data) {
                    abrirEventosPlacas(data.id)
                }
            }
        }
    };

    var gridDiv = document.querySelector('#tableEventosPlacas');
    AgGridEventosPlacas = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);

    $('#select-quantidade-por-pagina-eventos-placas').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-eventos-placas').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    var gridDiv = document.querySelector('#tableEventosPlacas');
    gridDiv.style.setProperty('height', '519px');

    function handleDataChange(event) {
        $('#loadingMessageEventosPlacas').show();
    
        let paginaAtual = Number(event.api.paginationGetCurrentPage());
        let tamanhoPagina = Number(event.api.paginationGetPageSize());
    
        const filteredData = [];
        event.api.forEachNode((node) => {
            // Verifica se o a linha está visivel
            if (node.displayed) {
                filteredData.push(node.data);
            }
        });
        const startIndex = paginaAtual * tamanhoPagina;
        const endIndex = startIndex + tamanhoPagina;
    
        const pageData = filteredData.slice(startIndex, endIndex);
    
        var dados = [];
    
        pageData.forEach((data) => {
            if (data) {
                dados.push(data)
            }
        })
        
        marcarNoMapaEventos(dados);
        
        $('#loadingMessageEventosPlacas').hide();
    }

    gridOptions.api.addEventListener('paginationChanged', handleDataChange);
    
    gridOptions.api.addEventListener('rowGroupOpened', handleDataChange);
    
    preencherExportacoesEventosPlacas(gridOptions);
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

function showLoadingSalvarTratamentoButton() {
    $('#botaoTratamento').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarTratamentoButton() {
    $('#botaoTratamento').html('Salvar').attr('disabled', false);
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

function stopAgGRIDEventosPlacas() {
    var gridDiv = document.querySelector('#tableEventosPlacas');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperEventosPlacas');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableEventosPlacas" class="ag-theme-alpine my-grid"></div>';
    }
}


//Utilitarios

function checkStatus(imageUrl) {
    var http = jQuery.ajax(
        {
            type: "HEAD",
            url: imageUrl,
            async: false
        })
    return http.status;
}

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
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

function removeAcento(palavra) {
    return palavra.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
}