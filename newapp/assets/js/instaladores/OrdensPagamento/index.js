var localeText = AG_GRID_LOCALE_PT_BR;
var unificar_conta = false;
var id_conta;
var dataArray = {};
var rastreamentoArray = {};
var autorizacaosArray = {};
var valorArray = {};

$(document).ready(function () {

    $('.btn-expandir').on('click', function (e) {
        e.preventDefault();
        expandirGrid();
    });

    // Exportação
    var dropdown = $("#opcoes_exportacao");
      
    $("#dropdownMenuButton").click(function () {
        dropdown.toggle();
    });
    
    $(document).click(function (event) {
        if (
        !dropdown.is(event.target) &&
        event.target.id !== "dropdownMenuButton"
        ) {
        dropdown.hide();
        }
    });

    var dropdownContas = $("#opcoes_exportacao_contas");
      
    $("#dropdownMenuButtonContas").click(function () {
        dropdownContas.toggle();
    });
    
    $(document).click(function (event) {
        if (
        !dropdownContas.is(event.target) &&
        event.target.id !== "dropdownMenuButtonContas"
        ) {
        dropdownContas.hide();
        }
    });

    // Menus
    $('#menu-os-pagar').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-contas').removeClass("selected");
            $('.card-os-pagar').show();
            $('.card-contas').hide();
            getDados();
        }
    });

    $('#btnPagar').on('click', function () {

        let rowsToExport = 0;

        var rowModelType = AgGrid.gridOptions.api.getModel().getType();

        if (rowModelType === 'clientSide') {
            // Client-side Row Model
            AgGrid.gridOptions.api.forEachNodeAfterFilterAndSort(node => {
                if (!node.isSelected()) {
                    return;
                }
                if (node.group) return;
                rowsToExport++;
            });
        }

        if (rowsToExport == 0) {
            exibirAlerta("warning", "Falha!", "Selecione os itens da tabela antes de tentar pagar!");
            return;
        } else {
            $('#Modal-contas').modal('show');
        }

    });

    $('#menu-contas').on('click', function () {
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            $('#menu-os-pagar').removeClass("selected");
            $('.card-os-pagar').hide();
            $('.card-contas').show();
            getDadosContas();
        }
    });

    //inputs
    $(document).on('input', '.dataInput', function (event) {
        dataArray[$(this).attr("data-id")] = $(this).val() ? $(this).val() : "";
    })

    $(document).on('input', '.rastreamentoInput', function (event) {
        rastreamentoArray[$(this).attr("data-id")] = $(this).val() ? $(this).val() : "";
    })

    $(document).on('input', '.numberInput', function() {
        $(this).mask("00000000000");
        autorizacaosArray[$(this).attr("data-id")] = $(this).val() ? parseInt($(this).val()) : "";
    });

    $(document).on('input', '.realInput', function() {
        $(this).tooltip({ container: 'body' })
        $(this).tooltip('show');
        $(this).mask("000.000.000.000,00", { reverse: true });
        if ($(this).val()) {
            var valor = parseFloat($(this).val().replace('.', '').replace(',', '.'))
            var limite = parseFloat($(this).attr('data-value'));
            if (valor > limite)
                $(this).attr('style', 'color: red !important');
            else
                $(this).attr('style', 'color: green !important');

            valorArray[$(this).attr("data-id")] = valor;
        } else {
            valorArray[$(this).attr("data-id")] = "";
        }
        
    });

    $('#auth').click(function () {
        var senha = $('#pwdInst').val();
        var url_auth = Router + "/verifica_senha";
        if (senha != '') {
            $.ajax({
                url : url_auth,
                type : 'POST',
                data : {senha: senha},
                dataType : 'JSON',
                beforeSend: function() {
                    $('#auth').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Autorizando');
                },
                success : function(data){
                    if (data.status == 'OK') {
                        $('#modal-auth').modal('hide');
                        pagar(true);
                    } else {
                        exibirAlerta('warning', 'Falha!', 'Senha inválida. (Acesso negado!)');
                    }
                }, 
                error: function() {
                    exibirAlerta('error', 'Erro!', 'Erro ao validar a senha! Tente novamente.')
                },
                complete: function() {
                    $('#auth').attr('disabled', false).html('Autorizar');
                }
            })
        } else {
            exibirAlerta('warning', 'Falha!', 'Senha inválida. (Acesso negado!)')
        }
    });

    $('#close').click(function () {
        $('#pagar').prop("disabled", false);
        $('#loading').addClass('none');
        $('#modal-auth').modal('hide');
    });

    $('#Modal-contas').on('show.bs.modal', function() {
        AgGridContasInstalador.gridOptions.api.deselectAll();
    });

    atualizarAgGrid();
    atualizarAgGridContas();
    atualizarAgGridContasInstalador();
});

// Utilitários
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

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0];
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
        $('#conteudo').removeClass('col-md-12');
        $('#conteudo').addClass('col-md-9');
    }
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
            dropdown.css('top', `-${10}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(posDropdown - 60) - (diferenca) }px`);
        }
    }
}

// Requisições
function getDados() {
    if (AgGrid) {
        $.ajax({
            url: Router + '/getOsAgGrid?id=' + idInstalador,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                dataArray = {};
                rastreamentoArray = {};
                autorizacaosArray = {};
                valorArray = {};
                AgGrid.gridOptions.api.setRowData([]);
                AgGrid.gridOptions.api.showLoadingOverlay();
            },
            success: function(data) {
                if (data) {
                    if ("dados" in data && data.dados.length > 0) {
                        for (let i = 0; i < data.dados.length; i++) {
                            for (let chave in data.dados[i]) {
                                // Verifica se o valor é null e substitui por uma string vazia
                                if (data.dados[i][chave] === null) {
                                    data.dados[i][chave] = '';
                                }

                                if (chave == 'data') {
                                    dataArray[data.dados[i]['identificador']] = data.dados[i][chave];
                                }

                                if (chave == 'rastreamento') {
                                    rastreamentoArray[data.dados[i]['identificador']] = data.dados[i][chave];
                                }

                                if (chave == 'autorizacao') {
                                    autorizacaosArray[data.dados[i]['identificador']] = data.dados[i][chave];
                                }

                                if (chave == 'value') {
                                    valorArray[data.dados[i]['identificador']] = data.dados[i][chave];
                                }
                            }
                        }
                        AgGrid.gridOptions.api.setRowData(data.dados);
                    } else {
                        AgGrid.gridOptions.api.showNoRowsOverlay();
                    }
                } else {
                    exibirAlerta('error', 'Erro!', 'Desculpe, não foi possível obter a listagem. Tente novamente!');
                    AgGrid.gridOptions.api.setRowData([]);
                }
            },
            error: function() {
                exibirAlerta('error', 'Erro!', 'Desculpe, não foi possível obter a listagem. Tente novamente!');
                AgGrid.gridOptions.api.setRowData([]);
            }
        })
    }
}

function getDadosContas() {
    if (AgGridContas) {
        $.ajax({
            url: Router + '/pagosAgGrid?id=' + idInstalador,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                AgGridContas.gridOptions.api.setRowData([]);
                AgGridContas.gridOptions.api.showLoadingOverlay();
            },
            success: function(data) {
                if (data) {
                    if ("dados" in data && data.dados.length > 0) { 
                        for (let i = 0; i < data.dados.length; i++) {
                            for (let chave in data.dados[i]) {
                                // Verifica se o valor é null e substitui por uma string vazia
                                if (data.dados[i][chave] === null) {
                                    data.dados[i][chave] = '';
                                }
                            }
                        }
                        AgGridContas.gridOptions.api.setRowData(data.dados);
                    } else {
                        AgGridContas.gridOptions.api.showNoRowsOverlay();
                    }
                } else {
                    exibirAlerta('error', 'Erro!', 'Desculpe, não foi possível obter a listagem. Tente novamente!');
                    AgGridContas.gridOptions.api.setRowData([]);
                }
            },
            error: function() {
                exibirAlerta('error', 'Erro!', 'Desculpe, não foi possível obter a listagem. Tente novamente!');
                AgGridContas.gridOptions.api.setRowData([]);
            }
        })
    }
}

function getDadosContasInstalador() {
    if (AgGridContasInstalador) {
        $.ajax({
            url: SiteURL + '/contas/get_contas_instalador/' + idInstalador + '/0',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                AgGridContasInstalador.gridOptions.api.setRowData([]);
                AgGridContasInstalador.gridOptions.api.showLoadingOverlay();
            },
            success: function(data) {
                if (data && data.length > 0) {
                    for (let i = 0; i < data.length; i++) {
                        for (let chave in data[i]) {
                            // Verifica se o valor é null e substitui por uma string vazia
                            if (data[i][chave] === null) {
                                data[i][chave] = '';
                            }
                        }
                    }
                    AgGridContasInstalador.gridOptions.api.setRowData(data);
                } else {
                    AgGridContasInstalador.gridOptions.api.showNoRowsOverlay();
                }
            },
            error: function() {
                exibirAlerta('error', 'Erro!', 'Desculpe, não foi possível obter as contas do instalador. Recarregue a página e tente novamente!');
                AgGridContasInstalador.gridOptions.api.setRowData([]);
            }
        })
    }
}

//Pagamentos (sensivel) REVISAR
function nova_conta(){
    AgGridContasInstalador.gridOptions.api.deselectAll();
    unificar_conta=false;
    pagar(false);
}

function selecionar_conta(){
    if(unificar_conta==false){
        exibirAlerta("warning", "Falha!", "Selecione uma conta para unificar!");
    }
    else{
        pagar(false);
    }
}

function pagar(auth) {
    $('#loading').removeClass('none');
    var contas = null;
    var fornecedor   = null;
    var titular      = null;
    var cpfTitular   = null;
    var cnpjTitular  = null;
    var conta        = null;
    var bank         = null;
    var agencia      = null;
    var weekly       = null;
    var valo         = null;
    var lim          = null;
    var dataS        = new Array();
    var valor        = new Array();
    var autorizacao  = new Array();
    var rastreamento = new Array();
    var responsavel  = new Array();
    var id           = new Array();
    var servico      = new Array();
    var cliente      = new Array();
    var placa        = new Array();
    var serial       = new Array();
    var user         = new Array();
    var cadastro     = new Array();
    var dataFormatada= null;
    var aux          = false;

    // Client-side Row Model
    AgGrid.gridOptions.api.forEachNodeAfterFilterAndSort(node => {
        if (!node.isSelected()) {
            return;
        }

        if (auth == false) {
            valo = parseFloat( valorArray[node.data.identificador] );
            lim = parseFloat( node.data.valorMensal );
            if (valo > lim) {
                $('#Modal-contas').modal('hide');
                $('#modal-auth').modal('show');

                aux = true;
                return false;
            }
        }

        dataS.push(dataArray[node.data.identificador]);
        rastreamento.push(rastreamentoArray[node.data.identificador]);
        autorizacao.push(autorizacaosArray[node.data.identificador]);
        responsavel.push(node.data.solicitante);
        valor.push( parseFloat( valorArray[node.data.identificador] ) );
        id.push(node.data.id);
        servico.push(node.data.servico);
        cliente.push(node.data.cliente);
        placa.push(node.data.placa);
        serial.push(node.data.serial);
        user.push(node.data.usuario);
        cadastro.push(node.data.cadastro);
        conta = node.data.conta;
        bank = node.data.bank;
        agencia = node.data.agencia;
        fornecedor = node.data.fornecedor;
        titular = node.data.titular;
        cpfTitular = node.data.cpfTitular;
        cnpjTitular = node.data.cnpjTitular;
        weekly = node.data.weekly;
        var data = new Date();
        dataFormatada = ("0" + data.getDate()).substr(-2) + "/"
            + ("0" + (data.getMonth() + 1)).substr(-2) + "/" + data.getFullYear();

    });

    if (aux == true) { return false; }

    ShowLoadingScreen();

    var sum = 0;
    var campoVazio = false;
    $.each(valor, function (i, v) {
        if (v || v === 0) {
            sum += v;
        } else {
            campoVazio = true;
        }
    });

    $.each(dataS, function (i, dt) {
        if (!dt) {
            campoVazio = true;
        } 
    });

    if (campoVazio) {
        $('#pagar').prop("disabled", false);
        HideLoadingScreen();
        exibirAlerta('warning', 'Falha!', "Verifique se os campos obrigatórios estão todos preenchidos");
        return;
    }

    if (dataS != "" && valor != ""){
        var data_post=  {
            'fornecedor': titular,
            'descricao': 'ORDEM DE PAGAMENTO REF A '+servico.length+' SERVIÇO(S) DO TÉC. '+fornecedor+' BANCO: '+bank+' AG.: '+agencia+' C.: '+conta+' CPF: '+cpfTitular+' CNPJ: '+cnpjTitular,
            'info_bancaria': 'BANCO: '+bank+' AG.: '+agencia+' C.: '+conta+' CPF: '+cpfTitular+' CNPJ: '+cnpjTitular,
            'valor' : sum,
            'data_vencimento' : dataFormatada,
            'categoria': 'INSTALADOR',
            'seriais': serial,
            'cliente': cliente,
            'placa': placa,
            'id_os': id,
            'valorServ': valor,
            'dataServ': dataS,
            'servicoOs': servico,
            'userOs': user,
            'id_instalador': idInstalador,
            'instalador': fornecedor,
            'status_aprovacao': '0',
            'weekly': weekly
        };
        if(unificar_conta){
            data_post['id_conta']=unificar_conta;
            unificar_conta = false;
        }
        $.ajax({
            url : '../contas/addContaOs',
            type : 'POST',
            data : data_post,
            success : function(data){
                let id_contas = data;
                if (id_contas) {
                    contas = id_contas;

                    $.each(valor, function (i) {
                        $.ajax({
                            url : 'update_serv',
                            type : 'POST',
                            data : { 'id' : id[i] }
                        });
                        $.ajax({
                            url : 'cadServiceOp',
                            type : 'POST',
                            data : {
                                'id_instalador' : idInstalador,
                                'id_os' : id[i],
                                'servico' : servico[i],
                                'cliente' : (cliente[i].length > 45) ? cliente[i].substring(0, 45) : cliente[i],
                                'placa' : placa[i],
                                'serial': serial[i],
                                'user' : user[i],
                                'solicitante' : (responsavel[i].length > 45) ? responsavel[i].substring(0, 45) : responsavel[i],
                                'data' : dataS[i],
                                'cod_rastreamento' : rastreamento[i],
                                'cod_autorizacao' : autorizacao[i],
                                'valor' : valor[i],
                                'id_contas': contas
                            },success: function () {
                                $('#pagar').prop("disabled", false);
                                HideLoadingScreen();
                                exibirAlerta('success', 'Successo!', 'Operação realizada com sucesso!');
                                $('#Modal-contas').modal('hide');
                                getDados();
                                getDadosContasInstalador();
                            }, error: function () {
                                $('#pagar').prop("disabled", false);
                                HideLoadingScreen();
                                exibirAlerta('warning', 'Erro!', "Não foi possível realizar operação!");
                            }
                        });
                    });
                } else {
                    $('#pagar').prop("disabled", false);
                    HideLoadingScreen();
                    exibirAlerta('warning', 'Erro!', "Não foi possível concluir a operação!");
                }
            }, error: function () {
                $('#pagar').prop("disabled", false);
                HideLoadingScreen();
                exibirAlerta('warning', 'Erro!', "Não foi possível concluir operação!");
            }
        })
    }else{
        $('#pagar').prop("disabled", false);
        HideLoadingScreen();
        exibirAlerta('warning', 'Falha!', "Verifique se os campos obrigatórios estão todos preenchidos");
    }
}

// AgGrid
var AgGrid;
function atualizarAgGrid() {
    stopAgGRID();
    
    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Selecionar',
                pinned: 'right',
                width: 120,
                cellClass: 'centralizado',
                headerCheckboxSelection: true,
                checkboxSelection: true,
                suppressMenu: true,
                sortable: false,
                filter: false,
                resizable: false
            },
            {
                headerName: 'ID',
                field: 'id',
                suppressSizeToFit: true,
                width: 100,
            },
            {
                headerName: 'Serviço',
                field: 'servico',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                minWidth: 250,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa',
                field: 'placa',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Serial',
                field: 'serial',
                suppressSizeToFit: true,
                width: 180,
            },
            {
                headerName: 'Usuário',
                field: 'usuario',
                suppressSizeToFit: true,
                width: 200,
            },
            {
                headerName: 'Solicitante',
                field: 'solicitante',
                width: 200,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Data *',
                field: 'data',
                width: 180,
                suppressSizeToFit: true,
                valueFormatter: (params) => {
                    if (!params.value) {
                        return "";
                    }
                    let data = new Date(params.value);
                    const month = data.getMonth();
                    const day = data.getDate();
                    return `${day < 10 ? "0" + day : day}/${month < 10 ? "0" + month : month}/${data.getFullYear()}`;
                },
                cellRenderer: function(params) {
                    let valor;

                    if (params.data.identificador in dataArray) {
                        valor = dataArray[params.data.identificador] ? dataArray[params.data.identificador] : "";
                    } else {
                        valor = params.value;
                    }

                    if (valor) {
                        return `<input autocomplete="off" type="date" id="date${params.data.identificador}" value="${valor}" data-id="${params.data.identificador}" class="dataInput formatInput form-control" style="margin-top: 2px;"/>`
                    } else {
                        return `<input autocomplete="off" type="date" id="date${params.data.identificador}" value="" data-id="${params.data.identificador}" class="dataInput formatInput form-control" style="margin-top: 2px;"/>`;
                    }
                },
                // editable: true,
                // cellEditor: 'agDateCellEditor',
            },
            {
                headerName: 'Rastreamento',
                field: 'rastreamento',
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    let valor;
                    
                    if (params.data.identificador in rastreamentoArray) {
                        valor = rastreamentoArray[params.data.identificador] ? rastreamentoArray[params.data.identificador] : "";
                    } else {
                        valor = params.value;
                    }

                    return `<input autocomplete="off" type="text" maxlength="45" id="rastr${params.data.identificador}" value="${valor}" data-id="${params.data.identificador}" class="rastreamentoInput form-control" style="margin-top: 2px;"/>`;
                },
            },
            {
                headerName: 'Autorização',
                field: 'autorizacao',
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    let valor;
                    
                    if (params.data.identificador in autorizacaosArray) {
                        valor = autorizacaosArray[params.data.identificador] ? autorizacaosArray[params.data.identificador] : "";
                    } else {
                        valor = params.value;
                    }

                    return `<input autocomplete="off" type="text" id="autoriz${params.data.identificador}" value="${valor}" data-id="${params.data.identificador}" class="numberInput form-control" style="margin-top: 2px;"/>`;
                },
            },
            {
                headerName: 'Valor *',
                field: 'value',
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    let valor;
                    
                    if (params.data.identificador in valorArray) {
                        valor = valorArray[params.data.identificador] ? valorArray[params.data.identificador].toLocaleString('pt-br', {minimumFractionDigits: 2}) : "";
                    } else {
                        valor = params.value;
                    }

                    let valorMax = params.data.valorMensal;
                    let color = '#555';
                    if (valorMax && valor) {
                        if (valor > valorMax) {
                            color = "red";
                        } else {
                            color = "green";
                        }
                    }
                    return `<input autocomplete="off" data-toggle="tooltip" data-placement="top" title="Limite desejado: R$ ${valorMax.toLocaleString('pt-br', {minimumFractionDigits: 2})}" type="text" id="valor${params.data.identificador}" data-value="${valorMax}" value="${valor}" data-id="${params.data.identificador}" class="realInput form-control" style="margin-top: 2px; color: ${color};"/>`;
                },
            }
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
        rowSelection: "multiple",
        suppressRowClickSelection: true,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhuma OS cadastrada para esse instalador!</span>'
    };

    var gridDiv = document.querySelector('#table');
    gridDiv.style.setProperty('height', '530px');

    AgGrid = new agGrid.Grid(gridDiv, gridOptions);
    $('#select-quantidade-por-pagina-dados').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-dados').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    $('#search-input-os-pagar').off().on('input', function() {
        var searchInput = document.querySelector('#search-input-os-pagar');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    gridOptions.api.setRowData([]);
    getDados();
    preencherExportacoes(gridOptions)
}

// AgGrid
var AgGridContas;
function atualizarAgGridContas() {
    stopAgGRIDContas();
    
    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Selecionar',
                pinned: 'right',
                width: 120,
                cellClass: 'centralizado',
                headerCheckboxSelection: true,
                checkboxSelection: true,
                suppressMenu: true,
                sortable: false,
                filter: false,
                resizable: false
            },
            {
                headerName: 'OS',
                field: 'os',
                suppressSizeToFit: true,
                width: 100,
            },
            {
                headerName: 'Serviço',
                field: 'servico',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Cliente',
                field: 'cliente',
                minWidth: 250,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Placa',
                field: 'placa',
                width: 150,
                suppressSizeToFit: true
            },
            {
                headerName: 'Serial',
                field: 'serial',
                suppressSizeToFit: true,
                width: 180,
            },
            {
                headerName: 'Usuário',
                field: 'usuario',
                suppressSizeToFit: true,
                width: 200,
            },
            {
                headerName: 'Solicitante',
                field: 'solicitante',
                width: 200,
                suppressSizeToFit: true,
            },
            {
                headerName: 'Data',
                field: 'data',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    if (params.value) {
                        return formatDateTime(params.value);
                    } else {
                        return "";
                    }
                }
            },
            {
                headerName: 'Rastreamento',
                field: 'rastreamento',
                suppressSizeToFit: true
            },
            {
                headerName: 'Autorização',
                field: 'autorizacao',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor',
                field: 'valor',
                suppressSizeToFit: true
            }
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
        rowSelection: "multiple",
        suppressRowClickSelection: true,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro enviado para o Contas!</span>'
    };

    var gridDiv = document.querySelector('#tableContas');
    gridDiv.style.setProperty('height', '530px');

    AgGridContas = new agGrid.Grid(gridDiv, gridOptions);
    $('#select-quantidade-por-pagina-contas').off().change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-contas').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    $('#search-input-contas').off().on('input', function() {
        var searchInput = document.querySelector('#search-input-contas');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    gridOptions.api.setRowData([]);

    preencherExportacoesContas(gridOptions)
}


// AgGrid
var AgGridContasInstalador;
function atualizarAgGridContasInstalador() {
    stopAgGRIDContasInstalador();
    
    var gridOptions = {
        columnDefs: [
            {
                headerName: 'Selecionar',
                pinned: 'right',
                width: 120,
                cellClass: 'centralizado',
                headerCheckboxSelection: false,
                checkboxSelection: true,
                suppressMenu: true,
                sortable: false,
                filter: false,
                resizable: false
            },
            {
                headerName: 'ID',
                field: 'id',
                suppressSizeToFit: true,
                width: 100,
            },
            {
                headerName: 'Descrição',
                field: 'descricao',
                minWidth: 400,
                flex: 1,
                suppressSizeToFit: true
            },
            {
                headerName: 'Vencimento',
                field: 'data_vencimento',
                width: 130,
                suppressSizeToFit: true,
                cellRenderer: function(params) {
                    if (params.value) {
                        return formatDateTime(params.value);
                    } else {
                        return "";
                    }
                }
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
        onRowSelected: function (event) {
            if (event.node.isSelected()) {
                setTimeout(function () {
                    unificar_conta = event.node.data.id;
                    id_conta = event.node.data.id;
                }, 50);
            } else {
                unificar_conta = false;
                id_conta = false;
            }
        },
        paginationPageSize: 5,
        localeText: localeText,
        domLayout: 'normal',
        rowSelection: "single",
        suppressRowClickSelection: true,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro enviado para o ContasInstalador!</span>'
    };

    var gridDiv = document.querySelector('#tableContasInstalador');
    gridDiv.style.setProperty('height', '310px');

    AgGridContasInstalador = new agGrid.Grid(gridDiv, gridOptions);

    gridOptions.api.setRowData([]);

    getDadosContasInstalador();
}

// Carregamento

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
    $('#BtnLimparFiltro').html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
    $('#BtnPesquisar').attr('disabled', false);
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

function stopAgGRIDContas() {
    var gridDiv = document.querySelector('#tableContas');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperContas');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableContas" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDContasInstalador() {
    var gridDiv = document.querySelector('#tableContasInstalador');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperContasInstalador');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableContasInstalador" class="ag-theme-alpine my-grid"></div>';
    }
}