var localeText = AG_GRID_LOCALE_PT_BR;

$(document).ready(function () {
    atualizarAgGridAbaTecnologias();

    $('#clienteBusca').select2({
        ajax: {
            url: RouterCliente + '/ajaxListSelect',
            dataType: 'json',
            delay: 1000,
        },
        placeholder: "Selecione o cliente",
        allowClear: true,
        minimumInputLength: 3,
        language: 'pt-BR'
    });

    $('#idCliente').select2({
        ajax: {
            url: RouterCliente + '/ajaxListSelect',
            dataType: 'json',
            delay: 1000,
        },
        placeholder: "Selecione o cliente",
        allowClear: true,
        minimumInputLength: 3,
        language: 'pt-BR'
    });

    $('#menu-cadastro-clientes').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            atualizarAgGridAbaTecnologias();
        }
    });

    $('#BtnAdicionarCliente').on('click', function () {
        $('#addClientes').modal('show');

    })

    $('.menu-interno-link').click(function () {
        limparPesquisa();
    })

    $('#addClientes').on('hide.bs.modal', function () {
        limparCamposCadastro();
        $('#titleTecnologias').html("Cadastrar Cliente");
    })

    $('#formAddClientes').on('submit', function (event) {
        if ($('#titleTecnologias').html() == "Cadastrar Cliente") {
            event.preventDefault();
            let form = $(this).serializeArray();

            let formData = {};

            form.forEach(function (field) {
                formData[field.name] = field.value;
            });

            cadastrarCliente(formData);
        } else {
            event.preventDefault();
            let form = $(this).serializeArray();

            let formData = {};

            form.forEach(function (field) {
                formData[field.name] = field.value;
            });

            atualizarTecnologia(formData);
        }
    })

    $('#BtnLimparFiltro').click(function () {
        idTecnologia = $("#idTecnologia").val(),
            clienteBusca = $("#clienteBusca").val()

        if (idTecnologia || clienteBusca) {
            if ($("#menu-cadastro-clientes").hasClass('selected')) {
                atualizarAgGridAbaTecnologias();

            }
        }
        limparPesquisa();
    });

    $('#formBusca').submit(function (e) {
        e.preventDefault();
        showLoadingPesquisarButton();

        var searchOptions = {
            idTecnologia: $("#idTecnologia").val(),
            clienteBusca: $("#clienteBusca").val()
        };

        idTecnologia = $("#idTecnologia").val();
        clienteBusca = $("#clienteBusca").val();

        var tecnologiasVisivel = $('#menu-cadastro-clientes').hasClass("selected");

        if (tecnologiasVisivel) {
            if (!idTecnologia && !clienteBusca) {
                resetPesquisarButton();
                alert("Preencha algum dos campos de busca!");
            } else {
                atualizarAgGridAbaTecnologias(searchOptions);
            }

        }
    });
})

function cadastrarCliente(form) {
    showLoadingSalvarButtonClientes();
    let route = Router + '/cadastrarCliente'
    $.ajax({
        url: route,
        type: 'POST',
        data: form,
        dataType: 'json',
        success: function (response) {
            if (response.status == 201) {
                alert(response['resultado']['mensagem'])
                $('#addClientes').modal('hide');
                atualizarTableTecnologias();
            } else if (response.status == 500) {
                alert("Erro interno do servidor. Entre em contato com o suporte técnico");
            } else {
                alert(response['resultado']['mensagem'])
            }
            resetSalvarButtonClientes();
        },
        error: function (error) {
            alert('Erro na solicitação ao servidor');
            resetSalvarButtonClientes();
        }
    });
}

function atualizarTecnologia(form){
    showLoadingSalvarButtonClientes();
    let route = Router + '/atualizarTecnologia'
    $.ajax({
        url: route,
        type: 'POST',
        data: form,
        dataType: 'json',
        success: function (response) {
            if (response.status == 200) {
                alert(response['resultado']['mensagem']);
                $("#addClientes").modal('hide');
                atualizarTableTecnologias();
            } else if (response.status == 500) {
                alert("Erro interno do servidor. Entre em contato com o suporte técnico");
            } else {
                alert(response['resultado']['mensagem']);
            }
            resetSalvarButtonClientes();
        },
        error: function (error) {
            alert('Erro na solicitação ao servidor');
            resetSalvarButtonClientes();
        }
    });
}

function editarTecnologia(id) {
    $('#titleTecnologias').html("Editar Cliente");

    ShowLoadingScreen();
    let route = Router + '/buscarTecnologiaById'
    $.ajax({
        url: route,
        type: 'POST',
        data: { id: id },
        success: function (response) {
            response = JSON.parse(response);
            if (response.status == 200) {
                preencherModalEdição(response['resultado']);
            } else if (response.status == 500) {
                alert("Erro interno do servidor. Entre em contato com o suporte técnico")
            } else {
                alert(response['resultado']['mensagem']);
            }
            HideLoadingScreen();
        },
        error: function (error) {
            alert('Erro na solicitação ao servidor');
            HideLoadingScreen();
        }
    });
}

function deletarTecnogia(id, status){
    if (confirm("Deseja realmente alterar o Status?")){
        ShowLoadingScreen();
        let route = Router + '/deletarTecnologia'
        $.ajax({
            url: route,
            type: 'POST',
            data: { 
                id: id,
                status: status
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.status == 200) {
                    atualizarTableTecnologias();
                } else if (response.status == 500) {
                    alert("Erro interno do servidor. Entre em contato com o suporte técnico")
                } else {
                    alert(response['resultado']['mensagem']);
                }
                HideLoadingScreen();
            },
            error: function (error) {
                alert('Erro na solicitação ao servidor');
                HideLoadingScreen();
            }
        });
    }
}

function preencherModalEdição(dados) {
    $('#idCliente').empty();
    $('#idCliente').append(`<option value="${dados['idCadastroCliente']}">${dados['nomeCliente']}</option>`).trigger('change');
    $("#id").val(dados['id']);
    $("#idTecnologias").val(dados['idTecnologia']);
    $("#usuario").val(dados['usuario']);
    $("#senhaUsuario").val(dados['senha']);
    $("#tipoUrl").val(dados['tipoUrl']);
    $("#url").val(dados['url']);
    $("#status").val(dados['status'] == 'Ativo' ? '1' : '0');
    HideLoadingScreen();
    $("#addClientes").modal('show');

}

var AgGridAbaTecnologias;
function atualizarAgGridAbaTecnologias(options) {
    stopAgGRIDAbaTecnologias();
    showLoadingLimparButton();
    showLoadingPesquisarButton();

    function getServerSideDados() {
        return {
            getRows: (params) => {

                var route = Router + '/listarTecnologias';
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        idTecnologia: options ? options.idTecnologia : '',
                        idCliente: options ? options.clienteBusca : ''
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
                            alert(data.message);
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        } else {
                            alert('Erro na solicitação ao servidor');
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
                        alert('Erro na solicitação ao servidor');
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        resetPesquisarButton();
                        resetLimparButton();
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
                chartDataType: 'category',
                width: 100,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'idCadastroCliente',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Agente',
                field: 'nomeCliente',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Fila',
                field: 'idTecnologia',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Prioridade',
                field: 'usuario',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Iniciado',
                field: 'tipoUrl',
                chartDataType: 'category',
                width: 170,
                suppressSizeToFit: true
            },
            {
                headerName: 'Status',
                field: 'url',
                chartDataType: 'category',
                width: 300,
                suppressSizeToFit: true
            },
            {
                headerName: 'Ações',
                width: 300,
                maxWidth: 100,
                pinned: 'right',
                cellClass: "actions-button-cell",
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
                    let tableId = "tableTecnologias";
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;
                    let statusAlterar = data.status == 'Ativo' ? 0 : 1;
                    let statusButton = data.status == 'Ativo' ? 'Inativar' : 'Ativar';

                    return `
                        <div class="btn btn-dropdown" style="cursor: pointer;">
                            <a href="#" style="cursor: pointer; color: black; text-decoration: none !important; display: block;">Atender</a>
                        </div>
                    `;
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
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-tecnologias').val()),
        cacheBlockSize: 50,
        localeText: localeText,
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    var gridDiv = document.querySelector('#tableTecnologias');
    gridDiv.style.setProperty('height', '519px');

    new agGrid.Grid(gridDiv, gridOptions);
    //gridOptions.api.setRowData(dados);
    let datasource = getServerSideDados()
    gridOptions.api.setServerSideDatasource(datasource);
    preencherExportacoes(gridOptions);
}

function stopAgGRIDAbaTecnologias() {
    var gridDiv = document.querySelector('#tableTecnologias');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperTecnologias');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableTecnologias" class="ag-theme-alpine my-grid-tecnologias"></div>';
    }
}

function limparCamposCadastro() {
    $("#idTecnologias").val('');
    $("#idCliente").val(null).trigger('change');
    $("#usuario").val('');
    $("#senhaUsuario").val('');
    $("#tipoUrl").val('');
    $("#url").val('');
}

function atualizarTableTecnologias() {

    var searchOptions = null;

    searchOptions = {
        idTecnologia: $("#idTecnologia").val(),
        clienteBusca: $("#clienteBusca").val()
    };

    if (searchOptions && (searchOptions.idTecnologia || searchOptions.clienteBusca)) {
        getDadosTecnologias(function (error) {
            if (!error) {
                atualizarAgGridAbaTecnologias(searchOptions);
            } else {
                atualizarAgGridAbaTecnologias();
            }
        }, searchOptions)
    } else {
        getDadosTecnologias(function (error) {
            if (!error) {
                atualizarAgGridAbaTecnologias();
            } else {
                atualizarAgGridAbaTecnologias();
            }
        })
    }
}

function getDadosTecnologias(callback, options) {

    if (options) {
        if (options.idTecnologia || options.clienteBusca) {
            showLoadingPesquisarButton();
            $("#loadingMessage").hide();
        } else {
            alert('Dados Insuficientes')
            resetPesquisarButton();
        }
    }

    if (typeof callback === "function") callback(null);
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

function limparPesquisa() {
    document.getElementById('formBusca').reset();
    $('#clienteBusca').val(null).trigger('change');
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

$('.btn-expandir').on('click', function (e) {
    e.preventDefault();
    expandirGrid();
});

function showLoadingPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function resetPesquisarButton() {
    $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
}

function showLoadingLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Carregando...').attr('disabled', true);
}

function resetLimparButton() {
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

function showLoadingSalvarButtonClientes() {
    $('#btnSalvarCliente').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButtonClientes() {
    $('#btnSalvarCliente').html('Salvar').attr('disabled', false);
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}