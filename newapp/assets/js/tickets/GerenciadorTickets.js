var localeText = AG_GRID_LOCALE_PT_BR;
var referencia = false;
$(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
var DadosAgGrid = [];
const gridFilaDeLigacoes = document.getElementById('grid-fila-ligacoes');
let sidDestino = '';
const divAtender = $("#botao-atender");
const divEncerrar = $("#div-encerrar");
const botaoEncerrar = $("#disconnect-call");

async function conexaoComOWebSocket(url) {
    try {
    const socket = io(url);

    socket.on('connect', () => {
        // Lógica para conexão
        carregarGridListagemDeLigacoes();
    });

    socket.on('queueUpdate', (callsInQueue) => {
    // Atualizar o estado do frontend com as novas informações da fila
        gridOptionsListarLigacoes.api.setRowData(callsInQueue);
    });
} catch (error) {
        console.error("Ocorreu um erro ao realizar uma requisição à API Televendas", error)
    }
}

// Função de renderização de célula personalizada para o ag-Grid
function renderizarColunaAtenderLigacao(dados) {
	const { data } = dados;

	return /* HTML */`
        <button 
            data-conectar-ligacao="${data.callSid}" 
            class="btn-conectar-ligacao" 
            style="
                cursor: pointer;
                width: 100%;
                display: flex;
                border-radius: 5px;
                padding: 0px;
                margin: 2px 0px 0px 0px;
                height: 31px !important;
                background-color: #007bff;
                text-align: center;
                align-items: center;
                justify-content: center;
                border: none;
                box-shadow: 2px 2px 3px black;
                color: white;
            "
            data-id="${data.callSid}"
        >
            Iniciar Atendimento
        </button>
    `;
}

function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;

    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

const columnDefsFilaLigacacoes = () => {

    let defs = [
        { field: "callSid", headerName: "Sid", flex: 1 },
        { field: "position", headerName: "Posição na fila", flex: 1, sort: 'asc' },
        { field: "waitTime", headerName: "Tempo em espera", flex: 1,
            cellRenderer: ({ data: { waitTime } }) => {
                return waitTime ? formatTime(waitTime) : '--:--:--';
            },
            valueGetter: ({ data: { waitTime } }) => {
                return waitTime ? formatTime(waitTime) : '--:--:--';
            }

        },
        {
            field: "Atendimento",
            headerName: "Atendimento",
            width: 200,
            cellRenderer: renderizarColunaAtenderLigacao,
            cellClass: "actions-button-cell",
            pinned: larguraTela <= 390 ? '' : 'right',
            sortable: false,
            sort: false,
            resizable: false,
        }
    ];


    return defs;
}

// Atualize a definição da coluna para usar a função de renderização personalizada
const gridOptionsListarLigacoes = {
        localeText: AG_GRID_TRANSLATE_PT_BR,
        columnDefs: columnDefsFilaLigacacoes(),
        rowData: [],
        animateRows: true,
        sortingOrder: ['desc', 'asc'],
        accentedSort: true,
        defaultColDef: {
        editable: false,
        resizable: false,
        sortable: false,
        },
        getRowId: (params) => params.data.callSid,
        overlayLoadingTemplate: mensagemCarregamentoAgGrid,
        overlayNoRowsTemplate: mensagemAgGridSemDados,
        pagination: true,
};

new agGrid.Grid(gridFilaDeLigacoes, gridOptionsListarLigacoes);

function carregarGridListagemDeLigacoes() {
    const url = `${SITE_URL}/webdesk/listarLigacoesEmFila`;

    gridOptionsListarLigacoes.api.showLoadingOverlay();
    axios
        .get(url)
        .then((response) => {
        const data = response.data;
        gridOptionsListarLigacoes.api.setRowData(data);
        })
        .catch((error) => {
        console.error(error);
        gridOptionsListarLigacoes.api.setRowData([]);
                showAlert('warning', lang.erro_inesperado);
        })
        .finally(() => {
            gridOptionsListarLigacoes.api.hideOverlay();
        });
}

//Conecta a ligação e redireciona para a página de criação de tickets
$(document).on('click', '.btn-conectar-ligacao', function (e) {
    const botao = $(this);
    const callSid = botao.attr('data-conectar-ligacao');
    const id = botao.attr('data-id');
    const btnConectarLigacao = $('.btn-conectar-ligacao');

    if (!callSid) {
        showAlert('error', 'Não foi possível obter o número de origem para efetuar a chamada.');
        return;
    }

    sidDestino = callSid;

    conectarChamada(btnConectarLigacao, id);

    setTimeout(() => {
        $('#novoTicket').trigger('click');
    }, 1500);


});

function removerRegistroNaAgGridListarLigações(idRow) {
	let displayModel = gridOptionsListarLigacoes.api.getModel();
	let rowNode = displayModel.rowsToDisplay[idRow];
	gridOptionsListarLigacoes.api.applyTransaction({ remove: [rowNode.data] });
}

$(document).ready(function() {
    atualizarAgGridShow();

    $('.btn-expandir').on('click', function(e) {
        e.preventDefault();
        expandirGrid();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        let prestadoraBusca = ($('#menu-show-norio').hasClass('selected') == true || $('#menu-show-norio').hasClass('active') == true) ? 1 : 0;
        var options = {
            cliente: $("#clienteBusca option:selected").text()?.trim().toLowerCase(),
            //categoria
            departamento: $("#departamentoBusca").val(),
            tag: $("select#tagBuscar").val(),
            status: $("#statusBusca").val(),
            tipoEmpresa: prestadoraBusca,
            dataInicio: formatarData($('#dataInicial').val()),
            dataFim: formatarData($('#dataFinal').val())
        };

        if (options.cliente || options.departamento || options.tag 
            || options.status || options.prestadora || options.dataInicio 
            || options.dataFim) {
            showLoadingPesquisarButton();
            showLoadingLimparButton();
            if(validacaoFiltros()){
                if(prestadoraBusca == 1){
                    atualizarAgGridShow(options);
                }else{
                    atualizarAgGridOmnilink(options);
                }
            }else{
                resetPesquisarButton();
                resetLimparButton();
                return;
            }
        } else {
            exibirAlerta('warning', 'Falha!', 'Digite pelo menos um campo para fazer a pesquisa.');
            resetPesquisarButton();
            resetLimparButton();
        }

        $('.menu-interno-link').attr('disabled', true);

    });

    $('.novoTicket').on('click', function () {
        $('#id_usuario').val('').trigger('change');
        $('#placa').val('').trigger('change');
        $('#assunto').val('');
        $('#departamento').val('').trigger('change');
        $('#prioridade').val('').trigger('change');
        $('#input_id_cliente').val('');
        $('#input_usuario').val('');
        $('#input_nome_usuario').val('');
        $('#descricao').val('');
        $('#arquivo').val('');
    });

    $("#ContactForm").submit(function (e) {
        e.preventDefault();
        showLoadingSalvarButton()

        var formdata = new FormData($("#ContactForm")[0]);
        $('.placa-alert').css('display', 'block');
        var prestadora = $("#prestadoraBusca").val()

        if (prestadora) {
            referencia = 'OMNILINK'
        } else {
            referencia = 'NORIO'
        }

        formdata.append('referencia', referencia);

        $.ajax({
            cache: false,
            url: SITE_URL + "/webdesk/new_ticket",
            type: 'POST',
            dataType: 'json',
            data: formdata,
            contentType: false,
            processData: false,
            success: function (callback) {
                if (callback.success) {
                    $("#ContactForm").trigger('reset');
                    $('#placa').trigger("chosen:updated");
                    var options = {
                        status: $("#statusBusca").val(),
                        prestadora: $("#prestadoraBusca").val(),
                        cliente: ''
                    };

                    if (!prestadora) {
                        $('.menu-interno-link').attr('disabled', true);
                        getDados(function (error, dados) {
                            if (!error) {
                                if (dados) {
                                    atualizarAgGridShow(dados);
                                } else {
                                    atualizarAgGridShow();
                                }
                            } else {
                                atualizarAgGridShow();
                            }
                            $('.menu-interno-link').attr('disabled', false);
                        }, options)

                    } else {
                        $('.menu-interno-link').attr('disabled', true);
                        getDados(function (error, dados) {
                            if (!error) {
                                if (dados) {
                                    atualizarAgGridOmnilink(dados);
                                } else {
                                    atualizarAgGridOmnilink();
                                }
                            } else {
                                atualizarAgGridOmnilink();
                            }
                            $('.menu-interno-link').attr('disabled', false);
                        }, options)

                    }

                    alert('Ticket criado com sucesso!')

                } else {
                    alert(callback.mensagem);
                }

                resetSalvarButton()

                $('#novo_ticket').modal('hide');

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Não é possível salvar o ticket no momento. Tente novamente mais tarde!');
                resetSalvarButton()

                $('#novo_ticket').modal('hide');
            }
        });

        return false;
    });

    $('#clienteBusca').select2({
        ajax: {
            url: Router + '/buscarClientes',
            dataType: 'json'
        },
        placeholder: "Digite o nome do cliente",
        allowClear: true,
        language: "pt-BR",

    });

    $('#statusBusca').select2({
        language: 'pt-BR',

    })

    $("#clienteBusca").select2('val', ' ')
    
    $('#menu-show-norio').off().on('click', function() {
        limparForm();
        $(this).addClass("selected");
        $('#menu-omnilink').removeClass( "selected" );
        let prestadoraBusca = 1;
        var options = {
            cliente: $("#clienteBusca option:selected").text()?.trim().toLowerCase(),
            //categoria
            departamento: $("#departamentoBusca").val(),
            tag: $("select#tagBuscar").val(),
            status: $("#statusBusca").val(),
            tipoEmpresa: prestadoraBusca,
            dataInicio: formatarData($('#dataInicial').val()),
            dataFim: formatarData($('#dataFinal').val())
        };
        $('.menu-interno-link').attr('disabled', true);
        atualizarAgGridShow(options);
    });

    $('#menu-omnilink').off().on('click', function() {
        limparForm();
        $(this).addClass("selected");
        $('#menu-show').removeClass( "selected" );
        let prestadoraBusca = 0;
        var options = {
            cliente: $("#clienteBusca option:selected").text()?.trim().toLowerCase(),
            //categoria
            departamento: $("#departamentoBusca").val(),
            tag: $("select#tagBuscar").val(),
            status: $("#statusBusca").val(),
            tipoEmpresa: prestadoraBusca,
            dataInicio: formatarData($('#dataInicial').val()),
            dataFim: formatarData($('#dataFinal').val())
        };
        $('.menu-interno-link').attr('disabled', true);
        atualizarAgGridOmnilink(options);
    });

    $('#BtnLimpar').on('click', function (e) {
        e.preventDefault();
        limparForm();
    });

    function limparForm(){
        $("#statusBusca").val('').trigger('change');
        $("#clienteBusca").val('').trigger('change');
        $("#departamentoBusca").val('').trigger('change');
        $("#tagBuscar").val('').trigger('change');
        $("#dataInicial").val('');
        $("#dataFinal").val('');
        atualizarAgGridOmnilink();
        atualizarAgGridShow();
    }

    //busca o usuário
    $('.select_usuario').select2({
        ajax: {
            url: SITE_URL + '/usuarios_gestor/get_ajax_usuarios_gestores',
            dataType: 'json'
        },
        placeholder: "Selecione o Usuario",
        allowClear: true,
        language: 'pt-BR',

    });

    $('#prioridade').select2({
        language: 'pt-BR',

    })

    $('#departamento').select2({
        language: 'pt-BR',

    })

    $('#departamentoBusca').select2({
        language: 'pt-BR',
        width: '100%',
    })

    $('#tagBuscar').select2({
        language: 'pt-BR',
        width: '100%',
    })


    //preenche as opcoes do select de placas
    $(document).on('change', '.select_usuario', function () {
        var id_usuario = $(this).val();
        var str = "";
        if (id_usuario) {
            document.getElementById('placa').readonly = true;
            $.ajax({
                url: SITE_URL + '/veiculos/lista_placas_usuario/' + id_usuario,
                datatype: 'json',
                success: function (data) {
                    var res = JSON.parse(data);
                    if (res.status == 'OK') {
                        $("#input_id_cliente")[0].value = res.results[0].id_cliente;
                        $("#input_usuario")[0].value = res.results[0].usuario;
                        $("#input_nome_usuario")[0].value = res.results[0].nome_usuario;
                        str += '<option value="" disabled selected>Selecione uma Placa</option>';
                        $(res.results).each(function (index, value) {
                            str += '<option value=' + value.placa + '>' + value.placa + '</option>';
                        })
                        $('#placa').html(str);
                        $('#placa').select2({
                            language: 'pt-BR',

                        })
                    }
                    document.getElementById('placa').readonly = false;
                },
                error: function (error) {
                    document.getElementById('placa').readonly = false;
                },
            });
        }

    });
});

function validacaoFiltros() {
    dataInicio = $("#dataInicial").val();
    dataFim = $("#dataFinal").val();

    if (!dataInicio && !dataFim) {
        alert("É necessário informar a Data Inicial e Data Final");
        return false;
    }

    if (dataInicio && !dataFim) {
        alert("É necessário informar a Data Final");
        return false;
    }

    if (!dataInicio && dataFim) {
        alert("É necessário informar Data Inicial");
        return false;
    }

    if (!validarDatas(dataInicio, dataFim)) {
        return false;
    }

    return true;
}

function validarDatas(dataInicialStr, dataFinalStr) {
    const dataInicial = new Date(dataInicialStr);
    const dataFinal = new Date(dataFinalStr);
    const dataAtual = new Date();

    dataAtual.setHours(0, 0, 0, 0);

    const umDiaEmMilissegundos = 24 * 60 * 60 * 1000;

    const diferencaEmDias = Math.round(Math.abs((dataFinal - dataInicial) / umDiaEmMilissegundos));

    if (diferencaEmDias > 30) {
        alert("O período de busca não pode exceder 30 dias.");
        return false;
    }

    if (dataInicial > dataFinal) {
        alert("A data inicial não pode ser maior que a data final.");
        return false;
    }

    if (dataFinal > dataAtual) {
        alert("A data final não pode ser maior que a data atual.");
        return false;
    }

    if (dataInicial > dataAtual) {
        alert("A data inicial não pode ser maior que a data atual.");
        return false;
    }

    return true;
}

function formatarData(data) {
    if (data.trim() === '') {
        return ''; // Retorna uma string vazia se o campo estiver vazio
    } else {
        let partesData = data.split('-');
        let dataFormatada = partesData[2] + '/' + partesData[1] + '/' + partesData[0];
        return dataFormatada;
    }
}

let menuAberto2 = false;
function expandirGrid(){
    menuAberto2 = !menuAberto2;

    let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
    let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

    if (menuAberto2) {
        $('.img-expandir').attr("src", buttonShow);
        $('#filtroBusca').hide();
        $('.menu-interno').hide();
        $('.conteudo').removeClass('col-md-9');
        $('.conteudo').addClass('col-md-12');
    } else {
        $('.img-expandir').attr("src", buttonHide);
        $('#filtroBusca').show();
        $('.menu-interno').show();
        $('.conteudo').css('margin-left', '0px');
        $('.conteudo').removeClass('col-md-12');
        $('.conteudo').addClass('col-md-9');
    }
}

function atualizarAgGridShow(options) {
    
    stopAgGRIDShow();
    showLoadingPesquisarButton();
    showLoadingLimparButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                let route = Router + '/buscarTicketsNovo';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        cliente: options ? formatarNomeCliente(options.cliente) : '',
                        departamento: options ? options.departamento : '',
                        tag: options ? options.tag : '',
                        tipoEmpresa: 1,
                        status: options ? options.status : '',
                        dataInicio: options ? options.dataInicio : '',
                        dataFim: options ? options.dataFim : ''
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
                            alert("Dados não encontrados para os parâmetros não informados.");
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        } else {
                            alert('Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        }
                        resetLimparButton();
                        resetPesquisarButton();
                        carregarGridRegistrosLigacoes();
                    },
                    error: function (error) {
                        alert('Erro na solicitação ao servidor');
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
                headerName: 'Id',
                field: 'id',
                chartDataType: 'category',
                width: 100
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                width: 240, 
                suppressSizeToFit: true,
            },
            {
                headerName: 'Situação',
                field: 'situacao',
                chartDataType: 'category',
                width: 120, 
                cellRenderer: function(options) {
                    if (options.value == "Inadimplente") {
                        return '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" style="color: fa0202;" aria-hidden="true"></i></a>'
                    } else if (options.value == "Adimplente") {
                        return '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> '
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'E-mail',
                field: 'nomeUsuario',
                width: 240, 
                suppressSizeToFit: true,
                chartDataType: 'category'
            },
            {
                headerName: 'Usuário',
                field: 'usuario',
                chartDataType: 'category'
            },
            {
                headerName: 'Categoria',
                field: 'departamento',
                chartDataType: 'category'
            },
            {
                headerName: 'Assunto',
                field: 'assunto',
                chartDataType: 'category'
            },
            {
                headerName: 'Última interação',
                field: 'ultimaInteracao',
                chartDataType: 'category',
                width: 180,
                cellRenderer: function (options){
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Prestadora',
                field: 'empresa',
                chartDataType: 'category'
            },
            {
                headerName: 'Status',
                field: 'mensagemStatus',
                chartDataType: 'category',
                width: 220,
                suppressSizeToFit: true,
                cellRenderer: function(options) {
                    return options.value;
                }
            },
            {
                headerName: 'Data de Abertura',
                field: 'dataAbertura',
                chartDataType: 'category',
                width: 180,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Data de Fechamento',
                field: 'dataFechamento',
                chartDataType: 'category',
                width: 180,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Tempo Gasto (Horas)',
                field: 'tempoGasto',
                chartDataType: 'category',
                width: 180,
                valueFormatter: function(params) {
                    if(params.value != ''){
                        var valorInteiro = parseInt(params.value, 10);
                    }
            
                    if (valorInteiro <= 0) {
                        return '';
                    } else {
                        return valorInteiro;
                    }
                }
            },            
            {
                headerName: 'Ações',
                width: 80,
                maxWidth: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
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
                    let tableId = "tableShow";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;
    
                    return `
                    <div class="dropdown" style="position: relative;">
                        <button class="btn btn-dropdown" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:fixed;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="${SITE_URL}/webdesk/ticket/${data.id}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" target="_blank">Visualizar</a>
                            </div>
                        </div>
                    </div>`;
                }
            },
        ],
        defaultColDef: {
            editable: false,
            wrapText: true,
            autoHeight: true,
            resizable: true
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'normal',
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-show-norio').val()),
        localeText: localeText,
        cacheBlockSize: 50,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayNoRowsTemplate: localeText.noRowsToShow
    };
    
    var gridDiv = document.querySelector('#tableShow');
    gridDiv.style.setProperty('height', '519px');
    
    document.querySelector('#select-quantidade-por-pagina-show-norio').addEventListener('change', function() {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina-show-norio').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);
    
    preencherExportacoesShow(gridOptions);
    $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
}

function atualizarAgGridOmnilink(options) {
    stopAgGRIDOmnilink();
    showLoadingPesquisarButton();
    showLoadingLimparButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                let route = Router + '/buscarTicketsNovo';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        cliente: options ? formatarNomeCliente(options.cliente) : '',
                        departamento: options ? options.departamento : '',
                        tag: options ? options.tag : '',
                        tipoEmpresa: 0,
                        status: options ? options.status : '',
                        dataInicio: options ? options.dataInicio : '',
                        dataFim: options ? options.dataFim : ''
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
                            alert("Dados não encontrados para os parâmetros não informados.");
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        } else {
                            alert('Erro na solicitação ao servidor');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            gridOptions.api.showNoRowsOverlay();
                        }
                        resetLimparButton();
                        resetPesquisarButton();
                        carregarGridRegistrosLigacoes();
                    },
                    error: function (error) {
                        alert('Erro na solicitação ao servidor');
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
                headerName: 'Id',
                field: 'id',
                chartDataType: 'category',
                width: 100
            },
            {
                headerName: 'Prioridade',
                field: 'prioridade',
                width: 150, 
                suppressSizeToFit: true,
            },
             {
                headerName: 'Canal',
                field: 'canal',
                width: 100, 
                suppressSizeToFit: true,
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                width: 240,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Situação',
                field: 'situacao',
                chartDataType: 'category',
                width: 120, 
                cellRenderer: function(options) {
                    if (options.value == "Inadimplente") {
                        return '<a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" style="color: fa0202;" aria-hidden="true"></i></a>'
                    } else if (options.value == "Adimplente") {
                        return '<a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> '
                    } else {
                        return options.value;
                    }
                }
            },
            {
                headerName: 'E-mail',
                field: 'nomeUsuario',
                width: 240,
                suppressSizeToFit: true,
                chartDataType: 'category'
            },
            {
                headerName: 'Agente',
                field: 'usuario',
                chartDataType: 'category'
            },
            {
                headerName: 'Fila',
                field: 'departamento',
                chartDataType: 'category'
            },
            {
                headerName: 'Assunto',
                field: 'assunto',
                chartDataType: 'category'
            },
            {
                headerName: 'Última interação',
                field: 'ultimaInteracao',
                chartDataType: 'category',
                width: 180,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Prestadora',
                field: 'empresa',
                chartDataType: 'category'
            },
            {
                headerName: 'Status',
                field: 'mensagemStatus',
                width: 220,
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value;
                }
            },
            {
                headerName: 'Data de Abertura',
                field: 'dataAbertura',
                chartDataType: 'category',
                width: 180,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Data de Fechamento',
                field: 'dataFechamento',
                chartDataType: 'category',
                width: 180,
                cellRenderer: function (options) {
                    return formatDateTime(options.value);
                }
            },
            {
                headerName: 'Tempo Gasto (Horas)',
                field: 'tempoGasto',
                chartDataType: 'category',
                width: 180,
                valueFormatter: function(params) {
                    if(params.value != ''){
                        var valorInteiro = parseInt(params.value, 10);
                    }
            
                    if (valorInteiro <= 0) {
                        return '';
                    } else {
                        return valorInteiro;
                    }
                }
            },            
            {
                headerName: 'Ações',
                width: 80,
                maxWidth: 80,
                pinned: 'right',
                cellClass: "actions-button-cell",
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
                    let tableId = "tableShow";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown" style="position: relative;">
                        <button class="btn btn-dropdown" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="${dropdownId}" style="position:fixed;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="${SITE_URL}/webdesk/ticket/${data.id}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" target="_blank">Visualizar</a>
                            </div>
                        </div>
                    </div>`;
                }
            },
        ],
        defaultColDef: {
            editable: false,
            wrapText: true,
            autoHeight: true,
            resizable: true
        },
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'normal',
        pagination: true,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-omnilink').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial',
        overlayNoRowsTemplate: localeText.noMatches
    };

    var gridDiv = document.querySelector('#tableOmnilink');
    gridDiv.style.setProperty('height', '519px');

    document.querySelector('#select-quantidade-por-pagina-omnilink').addEventListener('change', function () {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina-omnilink').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDados();
    gridOptions.api.setServerSideDatasource(datasource);

    preencherExportacoesOmni(gridOptions);
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

function stopAgGRIDOmnilink() {
    var gridDiv = document.querySelector('#tableOmnilink');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperOmnilink');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableOmnilink" class="ag-theme-alpine my-grid-omnilink"></div>';
    }
}

function stopAgGRIDShow() {
    var gridDiv = document.querySelector('#tableShow');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperShow');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableShow" class="ag-theme-alpine my-grid-show"></div>';
    }
}

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function showLoadingLimparButton() {
    $('#BtnLimpar').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimpar').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

function showLoadingSalvarButton() {
    $('#btnSalvarBlacklist').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButton() {
    $('#btnSalvarBlacklist').html('Salvar').attr('disabled', false);
}

function exibirAlerta(icon, title, text) {
    Swal.fire({
      position: 'top-start',
      icon: icon,
      title: title,
      text: text,
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
    });
}

function formatarNomeCliente(texto) {
    let posicaoSeparador = texto.indexOf('- ');

    if (posicaoSeparador !== -1) {
        return texto.substring(posicaoSeparador + 2);
    } else {
        return texto;
    }
}

// ANTIGO
$(function () {
    var max_ant = 0;
    $(".maxlength").keyup(function (event) {

        var target = $("#content-countdown");
        var max = target.attr('title');
        var len = $(this).val().length;
        var remain = len;

        if (len > max && len > max_ant) {
            var tpl = [
                '<div class="alert alert-info">',
                '<button type="button" class="close" data-dismiss="alert">&times;</button>',
                'Para uma melhor comunicação favor tente utilizar o limite de 500 caracteres!',
                '</div>'
            ].join('');

            $(".msg_caracter").html(tpl);
            $(".msg_caracter").show();
            max_ant = len;

        } else if (len <= max && len < max_ant) {
            $(".msg_caracter").hide();
            max_ant = len;
        }

        target.html(remain);

    });
});

$(document).on("shown.bs.dropdown", ".dropdown-table", function () {
    const dropdownId = $(this).find(".dropdown-menu").attr("id");
    const buttonId = $(this).find(".btn-dropdown").attr("id");
    abrirDropdown(dropdownId, buttonId, "tableShow");
});

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $("#" + dropdownId);

    var posDropdown = dropdown.height() + 4;

    var dropdownItems = $("#" + dropdownId + " .dropdown-item-acoes");
    var alturaDrop = 0;
    for (var i = 0; i <= dropdownItems.length; i++) {
        alturaDrop += dropdownItems.height();
    }

    if (alturaDrop >= 235) {
        $(".dropdown-menu-acoes").css("overflow-y", "scroll");
    }

    var posBordaTabela = $("#" + tableId + " .ag-body-viewport")
        .get(0)
        .getBoundingClientRect().bottom;
    var posBordaTabelaTop = $("#" + tableId + " .ag-header")
        .get(0)
        .getBoundingClientRect().bottom;

    var posButton = $("#" + buttonId)
        .get(0)
        .getBoundingClientRect().bottom;
    var posButtonTop = $("#" + buttonId)
        .get(0)
        .getBoundingClientRect().top;

    if (posDropdown > posBordaTabela - posButton) {
        if (posDropdown < posButtonTop - posBordaTabelaTop) {
            dropdown.css("top", `-${alturaDrop - 50}px`);
        } else {
            let diferenca =
                posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css("top", `-${alturaDrop / 2 - diferenca}px`);
        }
    }
}