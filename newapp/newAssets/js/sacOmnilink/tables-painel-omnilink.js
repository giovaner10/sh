
let tableAnotacoes = $('#tabelaAnotacoesOcorrencia').DataTable({
    responsive: true,
    ordering: false,
    paging: true,
    searching: true,
    info: true,
    language: lang.datatable,
    deferRender: true,
    lengthChange: false,
    columns: [
        { data: 'subject' },
        { data: 'notetext' },
        { data: 'criado_por' },
        { data: 'createdon' },
        { data: 'anexo' },
        { data: 'acoes' }
    ]
});

let tableAnotacoesModal = $('#tabelaAnotacoes').DataTable({
    responsive: true,
    ordering: false,
    paging: true,
    searching: true,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    deferRender: true,
    columns: [
        {
            data: 'subject'
        },
        {
            data: 'notetext'
        },
        {
            data: 'criado_por',
        },
        {
            data: 'anexo'
        }
    ]
});

/**
 * Incializar e carrega os dados da tabela de Ocorrências
 */
function listarOcorrencias() {

    if(instanciaTabelaOcorrencia.length < parseInt(abaSelecionada)) instanciaTabelaOcorrencia[parseInt(abaSelecionada) - 1] = false;

    if (!instanciaTabelaOcorrencia[parseInt(abaSelecionada) - 1] ) {
        listarTotaisOcorrencias();

        //limpa e destrói a tabela (caso exista) para não ser instanciada mais de uma vez.
        if($("#tableIncidents-" + abaSelecionada).DataTable()){
            $("#tableIncidents-" + abaSelecionada).DataTable().clear()
            $("#tableIncidents-" + abaSelecionada).DataTable().destroy()
        }

        jQuery.fn.dataTable.Api.register('responsive.redisplay()', function() {
            let responsive = this.context[0].responsive;

            if (responsive) responsive._redrawChildren();
        });

        // Inicialização do plugin do DataTables na tabela de Ocorrências
        $("#tableIncidents-" + abaSelecionada).DataTable({
            lengthChange: false,
            ordering: false,
            processing: true,
            responsive: true,
            serverSide: true,
            language: {
                ...lang.datatable,
                sProcessing: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder: 'Pressione ENTER para pesquisar...',
            },
            ajax: {
                url: `${URL_PAINEL_OMNILINK}/listar_ocorrencias/${buscarDadosClienteAbaAtual()?.Id}/${incidentStateCode}`,
                type: 'POST',
                dataSrc: function (receivedData) { //verifica se os dados foram carregados

                    if (receivedData) {
                        switch (receivedData.status) {

                            case 200:
                                return receivedData.data; //retorna o que deve ser trabalhado no datatable
                            case 0: //sem conexão ao CRM
                                alert('Base de dados está apresentando instabilidade, tente novamente em alguns minutos')
                                return [];
                            default:
                                alert('Ocorreu um problema ao buscar as ocorrências, tente novamente em instantes.')
                                return [];
                        }

                    } else {
                        alert('Ocorreu um problema ao buscar as ocorrências, tente novamente em instantes.')
                        return [];
                    }

                },
                error: function (xhr, error, thrown) {
                    alert("Ocorreu um erro ao buscar as ocorrências. A base de dados pode estar apresentando instabilidade, tente novamente mais tarde.")
                    $('#active-incidents-link span:last-child').html(0);
                    $('#resolved-incidents-link span:last-child').html(0);
                    $('#canceled-incidents-link span:last-child').html(0);
                }
            },
            initComplete: function () {
                var api = this.api();
                // Campo de pesquisa faz a busca apenas se pressionar a tecla 
                // ENTER
                $('#tableIncidents-' + abaSelecionada + '_filter input')
                    .off('.DT')
                    .on('keyup.DT', function (e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
            },
            columns: [
                { data: 'ticketnumber' },
                { data: 'subject' },
                { data: 'type' },
                { data: 'origin' },
                { data: 'technology' },
                { data: 'actualqueue' },
                {
                    data: (data) => {
                        return {
                            value: data.cause.value,
                            text: data.cause.text,
                        }
                    },
                    render: {
                        _: "value",
                        display: "text",
                    }
                },
                { data: 'servicetype' },
                {
                    className: 'wrap',
                    data: 'description'
                },
                {
                    data: 'created',
                    render: (data) => `${data['by']}, ${data['on']}`,
                },
                {
                    data: 'modified',
                    render: (data) => `${data['by']}, ${data['on']}`,
                },
                {
                    className: 'actions',
                    data: function (data) {
                        if (!data.changeIncidentStatusButtonStyle) {
                            data.changeIncidentStatusButtonStyle = "background-color: #06a9f6 !important; border-color: #06a9f6 !important;";
                        }

                        return montarAcoesTabelaOcorrencia(data);
                    }
                },
            ],
            dom: 'Blfrtip',
            buttons: [
                {
                    filename: function () {
                        let status;

                        switch (incidentStateCode) {
                            case 0:
                                status = 'Abertas';
                                break;
                            case 1:
                                status = 'Resolvidas';
                                break;
                            case 2:
                                status = 'Canceladas';
                                break;
                        }

                        return `Ocorrências (${status}) - ${dadosCliente.name} ${new Date().getTime()}`;
                    },
                    extend: 'excelHtml5',
                    autoFilter: true,
                    className: 'btn btn-success',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                        modifier: {
                            page: 'all',
                        },
                    },
                    
                    title: function() {
                        let status;

                        switch (incidentStateCode) {
                            case 0:
                                status = 'Abertas';
                                break;
                            case 1:
                                status = 'Resolvidas';
                                break;
                            case 2:
                                status = 'Canceladas';
                                break;
                        }

                        return `Ocorrências (${status}) - ${dadosCliente.name}`;
                    },
                },
                {
                    filename: function () {
                        let status;

                        switch (incidentStateCode) {
                            case 0:
                                status = 'Abertas';
                                break;
                            case 1:
                                status = 'Resolvidas';
                                break;
                            case 2:
                                status = 'Canceladas';
                                break;
                        }

                        return `Ocorrências (${status}) - ${dadosCliente.name} ${new Date().getTime()}`;
                    },
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    customize: function(doc, tes) {
                       
                        var titulo = `Ocorrências`;
                        pdfTemplateIsolated(doc, titulo);
                    }
                },
                {
                    filename: function () {
                        let status;

                        switch (incidentStateCode) {
                            case 0:
                                status = 'Abertas';
                                break;
                            case 1:
                                status = 'Resolvidas';
                                break;
                            case 2:
                                status = 'Canceladas';
                                break;
                        }

                        return `Ocorrências (${status}) - ${dadosCliente.name} ${new Date().getTime()}`;
                    },
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    filename: function () {
                        let status;

                        switch (incidentStateCode) {
                            case 0:
                                status = 'Abertas';
                                break;
                            case 1:
                                status = 'Resolvidas';
                                break;
                            case 2:
                                status = 'Canceladas';
                                break;
                        }

                        return `Ocorrências (${status}) - ${dadosCliente.name} ${new Date().getTime()}`;
                    },
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> IMPRIMIR',
                    customize: function ( win )
                    {
                        titulo = `Ocorrências`;
                        printTemplateOmni(win, titulo);
                    }
                }
            ],
        });

        $("#tableIncidents-" + abaSelecionada).DataTable().on('processing.dt', function () {
            // Centralizar na tela o Elemento que mostra o carregamento de
            // dados da tabela
            $("#tableIncidents-" + abaSelecionada)[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        });
        
        instanciaTabelaOcorrencia[abaSelecionada - 1] = true;
    }
}

/**
 * Consultar o total de Ocorrências do Cliente agrupadas por Status,
 * e renderizar os resultados na página.
 */
function listarTotaisOcorrencias() {
    $.ajax({
        url: `${URL_PAINEL_OMNILINK}/listar_totais_ocorrencias/${buscarDadosClienteAbaAtual()?.Id}`,
        success: function(data) {
            // Caso a requisição obtenha sucesso, renderizar a resposta
            // no conteúdo das abas
            $('#active-incidents-link-' + abaSelecionada + ' span:last-child').html(data.active);
            $('#resolved-incidents-link-' + abaSelecionada + ' span:last-child').html(data.resolved);
            $('#canceled-incidents-link-' + abaSelecionada + ' span:last-child').html(data.canceled);
        },
        error: function() {
            // Caso ocorra algum erro na requisição, renderizar erro
            $('#active-incidents-link-' + abaSelecionada + ' span:last-child').text('#');
            $('#resolved-incidents-link-' + abaSelecionada + ' span:last-child').text('#');
            $('#canceled-incidents-link-' + abaSelecionada + ' span:last-child').text('#');
        }
    });
}

/**
 * Atualizar a tabela de Ocorrências para exibir dados com base no Status
 * 
 * @param {int} statecode Código do Status da Ocorrência
 */
function atualizarTabelaOcorrencias(statecode, force = false) {
    // Verifica se o status escolhido para exibição já está sendo exibido
    if (statecode !== incidentStateCode || force) {
        incidentStateCode = statecode;

        // Atualiza a tabela de Ocorrências realizando uma requisição Ajax
        $("#tableIncidents-" + abaSelecionada).DataTable().ajax
            .url(`${URL_PAINEL_OMNILINK}/listar_ocorrencias/${buscarDadosClienteAbaAtual()?.Id}/${incidentStateCode}`)
            .load();
    }
}

/**
 * 
 * @param {object} ocorrencia Objeto de Ocorrência
 * @returns 
 */
function montarAcoesTabelaOcorrencia(ocorrencia) {
    let buttons = `
        <button class="btn btn-primary btn-body-datatable" data-toggle="modal" ticket=${ocorrencia.ticketnumber} data-id=${ocorrencia.id} onclick="modalInformacoesOcorrencia(this)" title="Informações">
            <i class="fa fa-eye" aria-hidden="true"></i>
        </button>
        <button class="btn btn-primary btn-body-datatable" data-toggle="modal" ticket=${ocorrencia.ticketnumber} data-id=${ocorrencia.id} onclick="modalAnotacoes(this, ${incidentStateCode})" title="Anotacões">
            <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
        </button>`;
    
    if(incidentStateCode == 0){
        buttons += `
            <button class="btn btn-primary btn-body-datatable" data-toggle="modal" data-id=${ocorrencia.id} onclick="modalEditarOcorrencia(this)" title="Editar">
                <i class='fa fa-edit' aria-hidden='true'></i>
            </button>
            <button class="btn btn-primary btn-body-datatable" data-id=${ocorrencia.id} style="${ocorrencia.changeIncidentStatusButtonStyle}" onclick='mudarStatusOcorrencia(this)' title='Alterar status'>
                <i class="fa fa-exclamation" aria-hidden="true"></i>
            </button>
            <button class="btn btn-primary btn-body-datatable resolve-incident-button" onclick="clickButtonEncerrarOcorrencia(this, '${ocorrencia.id}');" title="Resolver Ocorrência">
                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
            </button>
            <button class="btn btn-primary btn-body-datatable" id="encerrarOcorrencia" onclick="eventoCancelarOcorrencia(this, '${ocorrencia.id}');" title="Cancelar Ocorrência">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
            <button class="btn btn-primary btn-body-datatable" data-id=${ocorrencia.id} onclick="abrirModalNA(this)" title="Abrir uma NA com esta ocorrência.">
                <i class="fa fa-calendar-plus-o"></i>
            </button>`;
    }
            
    return `<div style="display: contents;" role='group'>${buttons}</div>`;
}

let tableAlteracaoDeContrato = $("#tableAlteracaoDeContrato").DataTable({
    responsive: true,
    ordering: false,
    paging: true,
    searching: true,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    rowId: function(callback) {
        return `row_alteracao_contrato_${callback.tz_manutencao_contratoid}`;
    },
    columns: [
        {
            "data": "tz_name",
            render: function(data){
                return data||'-';
            }
        },
        {
            "data": "tz_veiculo",
            render: function(data){
                return data||'-';
            }
        }, 
        {
            "data": "tz_item_contrato",
            render: function(data){
                return data||'-';
            }
        }, 
        {
            "data": "tz_modelo_tipo_ativacao",
            render: function(data){
                return data||'-';
            }
        }, 
        {
            "data": "statecode",
            render: function(data){
                return data||'-';
            }
        }, 
        {
            "data": "createdon",
            "render": function(data) {
                let dataAtivacao = new Date(data).toLocaleDateString();
                return data ? dataAtivacao : '-';
            }
        },
        {"data": "acoes"}
    ],
    initComplete: function() {
        adicionarFiltroColunas(
            'tableAlteracaoDeContrato', 
            $("#tableAlteracaoDeContrato").dataTable().api(),
            [0,1,2,3,4,5]
        );
        
    }, 

});
let tableServicosContratados = $("#tableServicosContratados").DataTable({
    responsive: true,
    ordering: false,
    paging: true,
    searching: true,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    rowId: function(callback) {
        return `row_servico_contratado_${callback.tz_produto_servico_contratadoid}`;
    },
    columns: [
        {
            "data": "tz_name",
            render: function(data){
                return data||'(Sem nome)';
            }
        }, //<th>Nome</th>
        {
            "data": "tz_codigo_item_contrato",
            render: function(data){
                return data || '(Sem nome)'
            }
        }, //<th>Item de Contrato de Venda</th>
        {
            "data": "tz_produto",
            render: function(data){
                return data || '-'
            }
        }, //<th>Serviço</th>
        {
            "data": "tz_quantidade",
            render: function(data){
                return data || '-'
            }
        }, //<th>Quantidade</th>
        {
            "data": "tz_valor_contratado",
            render: function(data){
                return data || '-'
            }
        }, //<th>Valor Contratado</th>
        {
            "data": "tz_classificacao_produto",
            render: function(data){
                return data || '-'
            }
        }, //<th>Classificação do produto</th>
        {
            "data": "tz_grupo_receita",
            render: function(data){
                return data || '-'
            }
        }, //<th>Grupo de Receita</th>
        {
            "data": "tz_data_inicio",
            "render": function(data) {
                let data_inicio = new Date(data).toLocaleDateString();
                return data ? data_inicio : '-';
            }
        }, //<th>Data de Início</th>
        {
            "data": "tz_data_termino",
            "render": function(data) {
                let data_termino = new Date(data).toLocaleDateString();
                return data ? data_termino : '-';
            }
        }, //<th>Data de Término</th>
        {
            "data": "tz_data_fim_carencia",
            "render": function(data) {
                let data_fim_carencia = new Date(data).toLocaleDateString();
                return data ? data_fim_carencia : '-';
            }
        }, //<th>Data Fim Carência</th>
        {
            "data": "acoes",
            render: function(data){
                return data || ''
            }
        } //<th>Ações</th>
    ],
    initComplete: function() {
        adicionarFiltroColunas(
            'tableServicosContratados', 
            $("#tableServicosContratados").dataTable().api(),
            [0,1,2,3,4,5,6,7,8,9]
        );
    }, 

});
let tableServicosContratadosBusca = $("#tableServicosContratadosBusca").DataTable({
    responsive: true,
    ordering: false,
    paging: true,
    searching: true,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    rowId: function(callback) {
        return `row_servico_contratado_${callback.tz_produto_servico_contratadoid}`;
    },
    columns: [
        {
            "data": "tz_name",
            render: function(data){
                return data||'(Sem nome)';
            }
        }, //<th>Nome</th>
        {
            "data": "tz_codigo_item_contrato",
            render: function(data){
                return data || '(Sem nome)'
            }
        }, //<th>Item de Contrato de Venda</th>
        {
            "data": "tz_produto",
            render: function(data){
                return data || '-'
            }
        }, //<th>Serviço</th>
        {
            "data": "tz_quantidade",
            render: function(data){
                return data || '-'
            }
        }, //<th>Quantidade</th>
        {
            "data": "tz_valor_contratado",
            render: function(data){
                return data || '-'
            }
        }, //<th>Valor Contratado</th>
        {
            "data": "tz_classificacao_produto",
            render: function(data){
                return data || '-'
            }
        }, //<th>Classificação do produto</th>
        {
            "data": "tz_grupo_receita",
            render: function(data){
                return data || '-'
            }
        }, //<th>Grupo de Receita</th>
        {
            "data": "tz_data_inicio",
            "render": function(data) {
                let data_inicio = new Date(data).toLocaleDateString();
                return data ? data_inicio : '-';
            }
        }, //<th>Data de Início</th>
        {
            "data": "tz_data_termino",
            "render": function(data) {
                let data_termino = new Date(data).toLocaleDateString();
                return data ? data_termino : '-';
            }
        }, //<th>Data de Término</th>
        {
            "data": "tz_data_fim_carencia",
            "render": function(data) {
                let data_fim_carencia = new Date(data).toLocaleDateString();
                return data ? data_fim_carencia : '-';
            }
        }, //<th>Data Fim Carência</th>
        {
            "data": "acoes",
            render: function(data){
                return data || ''
            }
        } //<th>Ações</th>
    ],
    initComplete: function() {
        adicionarFiltroColunas(
            'tableServicosContratados', 
            $("#tableServicosContratados").dataTable().api(),
            [0,1,2,3,4,5,6,7,8,9]
        );
        
    }, 

});
// Itens do contrato
let tableItensDoContrato = $("#tableItensDoContrato").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: true,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        {"data": "code"}, //Cod.
        {"data": "amount"}, //Total
        {"data": "qtd"}, //Qtd
        {
            "data": "startDate",
            "render": function(data) {
                let dataInicio = new Date(data).toLocaleDateString();
                return data ? dataInicio : '-';
            }
        }, //Início
        {
            "data": "endDate",
            "render": function(data) {
                let dataTermino = new Date(data).toLocaleDateString();
                return data ? dataTermino : '-';
            }
        }, //Fim
        {"data": "productClassificationName"}, //Classificação
        {"data": "revenueGroupName"}, //Receita
        {"data": "serviceName"}, //Serviço
        {
            data: {
                'id': 'id'
            },
            orderable: false,
            render: function(data) {
                return `
                        <button
                            class="btn btn-primary"
                            title="Atualizar Licença"
                            id="btnAtualizarLicencaContrato"
                            style="width: 45px; margin: 0 auto; display: block;"
                            onClick="javascript:atualizarLicencaContrato(this, '${data['id']}')">
                            <i class="fa fa-refresh" aria-hidden="true" style="font-size: 20px; display: block; margin: 0 auto;"></i>
                        </button>
                    `;
            }
        }
    ],
    initComplete: function() {
        adicionarFiltroColunas(
            'tableItensDoContrato', 
            $("#tableItensDoContrato").dataTable().api()
        );
    }, 
    drawCallback: function(settings){
        let api = this.api();
        let data = api.rows().data().toArray();
        let options = `<option></option>`;
        data.forEach(element => {
            options += `<option value="${element.incidentid}">${element.ticketnumber}</option>`
        });

        $("#alteracao_contrato_tz_incidentid").html(options).trigger('change');


    }

});

let tableAtividadesDeServico = $(".tableAtividadesDeServico").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: true,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    rowId: function(activity) {
        return activity.Id;
    },
    columnDefs: [
        { "width": "5%", "targets": [0] },
        { "width": "10%", "targets": [1,2,3,4,5,6,7,8] },
        { "width": "250px", "targets": 9 }
    ],
    columns: [
        {
            'data': 'Code',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'trackerSerialNumberInstall',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'provider',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'serviceName',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'serviceNameComplement',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'subject',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'scheduledstart',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'scheduledend',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'StatusCode', // Razão do Status
            'render': function(callback){
                let retorno = ''
                if(typeof callback == 'string'){
                    retorno = callback;
                }else{
                    retorno = legendaStatusCodeAtividadesDeServico[callback];
                }
                return retorno;
            }
        },
        {
            'data': 'numeroOs',
            'render': function(callback){
                return `<div class="ordem-servico" title="Visualizar OS" onclick="visualizarOS(this,'${callback}')" style="cursor: pointer; color: #337ab7">${callback}</div>`;
            }
        },
        {
            'data': 'acoes',
            'render': function(callback){
                return callback||''
            }
        },
    ],
    dom: 'Blfrtip', 
    buttons: [
        {
            filename: filenameGenerator("Relatório de Atividades de Serviço"),
            extend: 'excelHtml5',
            autoFilter: true,
            titleAttr: 'Exportar para o Excel',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                modifier: {
                    page: 'all',
                },
            },
            orientation: 'landscape',
            pageSize: 'LEGAL',
            className: 'btn btn-primary',
            text: '<i class="fa fa-file-excel-o"></i> EXCEL',
            messageTop: function () {
                return `Relatório de Atividades de Serviço`;
            },
        },
        {
            extend: 'pdfHtml5',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            },
            filename: filenameGenerator("Relatório de Atividades de Serviço"),
            orientation: 'landscape',
            pageSize: 'LEGAL',
            className: 'btn btn-primary',
            text: '<i class="fa fa-file-pdf-o"></i> PDF',
            customize: function(doc, tes) {
               
                var titulo = `Relatório de Atividades de Serviço`;
                pdfTemplateIsolated(doc, titulo);
            }
        },
        {
            filename: filenameGenerator("Relatório de Atividades de Serviço"),
            extend: 'csvHtml5',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            },
            orientation: 'landscape',
            pageSize: 'LEGAL',
            className: 'btn btn-primary',
            text: '<i class="fa fa-file-code-o"></i> CSV'
        },
        {
            filename: filenameGenerator("Relatório de Atividades de Serviço"),
            extend: 'print',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            },
            orientation: 'landscape',
            pageSize: 'LEGAL',
            className: 'btn btn-primary',
            text: '<i class="fa fa-print"></i> IMPRIMIR',
            customize: function ( win )
            {
                titulo = `Relatório de Atividades de Serviço`;
                printTemplateOmni(win, titulo);
            }
        }
    ],
});

let tabelaBuscaBaseInstalada = $('#tabelaBuscaBaseInstalada').DataTable({
    language: lang.datatable,
    lengthChange: false,
    ordering: false,
    responsive: true,
    searching: false,
    columns: [
        { 
            title: 'Nome',
            data: 'nome' 
        },
        { 
            title: 'Placa',
            data: 'placa' 
        },
        { 
            title: 'Serial',
            data: 'serial' 
        },
        { 
            title: 'Cliente',
            data: function(data) {
                if (data.nomepj || data.nomepf) {
                    return `<a onClick="exibirClienteBuscaBaseInstalada('${data.cnpj || data.cpf}')" style="cursor: pointer;">${data.nomepj ?? data.nomepf}</a>`;
                }

                return '-';
            } 
        },
        {
            title: 'Ações',
            data: 'id',
            render: function (data) {
                return `
                <button class="btn btn-primary" title="Editar Base Instalada" onclick="getInfoBaseInstalada(this, '${data}')">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </button>
                `;
            }
        }
    ]
});

function instanciarTabelaProvidencia(cliente){
    //limpa a tabela para uma possível nova busca
    if($("#table_providencias-" + abaSelecionada).DataTable() && !instanciaTabelaProvidencias[parseInt(abaSelecionada) - 1]){
        $("#table_providencias-" + abaSelecionada).DataTable().clear()
        $("#table_providencias-" + abaSelecionada).DataTable().destroy()
    }
    
    //adiciona o novo índice no array caso não haja
    if(instanciaTabelaProvidencias.length < parseInt(abaSelecionada)) instanciaTabelaProvidencias[parseInt(abaSelecionada) - 1] = false;

    if(!instanciaTabelaProvidencias[parseInt(abaSelecionada) - 1]){

        let tableProvidencias = $("#table_providencias-" + abaSelecionada).DataTable({
            processing: true, 
            serverSide: true,
            filter: true,
            ordermulti: false, 
            responsive: true,
            ordering: false,
            paging: true,
            info: true,
            lengthChange: false,
            orderCellsTop: true,
            deferRender: true,
            fixedHeader: true,
            ajax: {
                url: `${URL_PAINEL_OMNILINK}/ajax_get_providencias/${cliente}`, //chama a função do php
                type: "POST",
                dataSrc: function (receivedData) { //verifica se os dados foram carregados
                    if (receivedData) {
                        switch (receivedData.status) {

                            case 200:
                                if (receivedData.providencias.length > 0) $("#icon_alert_providencia").css('display', 'inline-block');

                                return receivedData.providencias; //retorna o que deve ser trabalhado no datatable
                            case 0: //sem conexão ao CRM
                                alert('Base de dados está apresentando instabilidade, tente novamente em alguns minutos')
                                return [];
                            default:
                                alert('Ocorreu um problema ao buscar as providências, tente novamente em instantes.')
                                return [];
                        }

                    } else {
                        alert('Ocorreu um problema ao buscar as providências, tente novamente em instantes.')
                        return [];
                    }

                },
                error: function (xhr, error, thrown) {
                    alert("Ocorreu um erro ao buscar as providencias, tente novamente mais tarde.")
                }
                },
                initComplete: function(){
                    var api = this.api();
                    //pesquisa apenas se pressionar enter
                    $('#table_providencias-' + abaSelecionada + '_filter input')
                    .off('.DT')
                    .on('keyup.DT', function (e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
                    adicionarFiltroColunas(
                        'table_providencias', 
                        $("#table_providencias-" + abaSelecionada).dataTable().api(),
                        [0,1]
                        );
                    },
                    createdRow: function(row, data, dataIndex){
                        $(row).attr('id', 'row_providencia_'+data.tz_providenciasid);
                        data.statecode = getSwitchStatusProvidencia(data.tz_providenciasid, data.statecode);
                        
                        tableProvidencias.row("#row_providencia_"+data.tz_providenciasid).data(data);
                    },

                columns: [
                    { data : 'tz_name'},
                    {   data: "createdon",
                        render: function(data) {
                            return data ? formatarDataISO(data) : '-';
                    }
                    },
                    {
                        data:'statecode',
                    },
                    {
                        data: 'acao',
                        className: 'tamanhoAcao'
                    },
                    {
                        data: 'tz_providenciasid'
                    }
            ],
            dom: 'Blfrtip',
            buttons: [
                {
                    filename: filenameGenerator("Relatório de Providências"),
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0,1]
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL'
                },
                {
                    filename: filenameGenerator("Relatório de Providências"),
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0,1]
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    customize: function ( doc , tes)
                    {                
                        titulo = `Relatório de Providências`;
                        pdfTemplateIsolated(doc, titulo)
                    }
                },
                {
                    filename: filenameGenerator("Relatório de Providências"),
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0,1]
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    filename: filenameGenerator("Relatório de Providências"),
                    extend: 'print',
                    exportOptions: {
                        columns: [0,1]
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> IMPRIMIR',
                    customize: function ( win )
                    {
                        titulo = `Relatório de Providências`;
                        printTemplateOmni(win, titulo);
                    }
                }
            ],
            columnDefs: [
                {
                    targets: [4],
                    visible: false,
                    searchable: false
                }
            ],
            oLanguage: {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sProcessing":  '<STRONG id="processando-alert-providencia">Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>',
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "sSearchPlaceholder": "Tecle ENTER para pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
            
        });
        
        $('#table_providencias-' + abaSelecionada).on('processing.dt', function (e, settings, processing) {
			//arrasta para a metade da tela para mostrar que está carregando
			if (processing){
                //pega a posição do "processando"
                height = document.getElementById('processando-alert-providencia');
                //arrasta para o local da tela
                if(height)
                    window.scrollTo({ top: height.getBoundingClientRect().x, behavior: 'smooth' });

            }
				
		});
        instanciaTabelaProvidencias[parseInt(abaSelecionada) - 1] = true
    }
}

function getSwitchStatusProvidencia(idProvidencia, statecode){
    if(statecode == 0){
        return `
            <label class="switch" title="Ativa">
                <input id="statusProvidencia_${idProvidencia}" type="checkbox" checked onclick="mudarStatusProvidencia('${idProvidencia}',1)">
                <span class="slider"></span>
            </label>
        `;
    }else{
        return `
            <label class="switch" title="Inativa">
                <input id="statusProvidencia_${idProvidencia}" type="checkbox" onclick="mudarStatusProvidencia('${idProvidencia}',0)">
                <span class="slider"></span>
            </label>
        `;
    }
}
/**
     * Percore todas as colunas e adiciona o input de filtro se a coluna for informada no arrayColumns
     */
 function adicionarFiltroColunas(idTabela, table, arrayColumns){
    $(`#${idTabela} thead tr th`).each(function(i){
        var th = $(this);
        var title = th.text();
        if(arrayColumns){
            if(arrayColumns.indexOf(i) >= 0){
                th.html(`
                    <span style="min-width: 80px; position: relative">
                        <span>${title}</span> <a class="btnFilterDatatable" onclick="exibirInputFilter(this, ${i}, '${idTabela}')"><i class="fa fa-filter" aria-hidden="true"></i></a>
                        <input type="text" class="form-control inputSearch" oninput="buscarColuna(this, ${i}, '${idTabela}')" placeholder="Buscar" title="Buscar ${title}" style="display:none" />
                    </span>
                `);
            }else{
                th.html(`${title}`);
            }
        }else{
            th.html(`
                    <span style="min-width: 80px; position: relative">
                        <span>${title}</span> <a class="btnFilterDatatable" onclick="exibirInputFilter(this, ${i}, '${idTabela}')"><i class="fa fa-filter" aria-hidden="true"></i></a>
                        <input type="text" class="form-control inputSearch" oninput="buscarColuna(this, ${i}, '${idTabela}')" placeholder="Buscar" title="Buscar ${title}"  style="display:none" />
                    </span>
                `);
        }
    });

}
/**
 * Exibe input de filtragem no datatable
 */
function exibirInputFilter(button, indiceColuna, idTabela){
    let btn = $(button);
    const tabela = $(`#${idTabela}`).dataTable().api();
    btn.parent().find("input").val('').toggle();
    tabela.column(indiceColuna).search('').draw();
}

/**
 * Busca na coluna informada o valor inserido no input
 */
function buscarColuna(input, indiceColuna, idTabela){
    let valor = $(input).val();
    const tabela = $(`#${idTabela}`).dataTable().api();
    if(tabela.column(indiceColuna).search() !== valor){
        tabela.column(indiceColuna).search(valor).draw();
    }
}

let tableOrdensServico = $("#tableOrdensServico").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: false,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        {
            data: 'tz_numero_os',
            render: function(element){
                return `<div class="ordem-servico" title="Visualizar OS" onclick="visualizarOS(this,'${element}')" style="cursor: pointer; color: #337ab7">${element}</div>`;
            }
        },
        {
            data: 'statecode',
            render: function(element){
                if(element == '0' || element == '1' || element == '2'){
                    let select;
                    if (element == '0'){
                        select = 'Ativa';
                    } else if (element == '1'){
                        select = 'Resolvida';
                    } else {
                        select = 'Cancelada';
                    }
                    return select;
                } else {
                    return element;
                }
            }
        },

        {
            data: 'statuscode',
            render: function(idRazaoStatus){
                switch(idRazaoStatus){
                    case 1:
                        return 'Em aberto';
                        
                    case 419400000: 
                        return 'Fechada';

                    case 419400001: 
                        return 'Não realizado';

                    default:
                        return '';
                }
            },
        },
        {
            data: 'tz_tipo_servico',
            render: function(tipoServico){
                switch(tipoServico){
                    case 2: 
                        return 'Manutenção';

                    case 3:
                        return 'Troca';

                    case 4:
                        return 'Retirada';

                    default:
                        return 'Instalação';
                }
            },
        },
        { data: 'tz_observacoes'}
    ]
});

let tableAtividadesDeServicoIC = $("#tableAtividadesDeServicoIC").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: true,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    rowId: function(activity) {
        return activity.Id;
    },
    columnDefs: [
        { "width": "5%", "targets": [0] },
        { "width": "10%", "targets": [1,2,3,4,5,6,7,8] },
        { "width": "250px", "targets": 9 }
    ],
    columns: [
        {
            'data': 'Code',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'provider',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'serviceName',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'serviceNameComplement',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'subject',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'scheduledstart',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'scheduledend',
            'render': function(callback){
                return callback||''
            }
        },
        {
            'data': 'StatusCode', // Razão do Status
            'render': function(callback){
                let retorno = ''
                if(typeof callback == 'string'){
                    retorno = callback;
                }else{
                    retorno = legendaStatusCodeAtividadesDeServico[callback];
                }
                return retorno;
            }
        },
        {
            'data': 'numeroOs',
            'render': function(callback){
                return `<div class="ordem-servico" title="Visualizar OS" onclick="visualizarOS(this,'${callback}')" style="cursor: pointer; color: #337ab7">${callback}</div>`;
            }
        },
        {
            'data': 'Id',
            'render': function(callback){
                return `<a class="btn btn-primary btn-body-datatable" style="margin: 0 10%; border-radius: 3px !important;" title="Visualizar Info da NA" onclick="abrirModalInformacoesNA(this, '${callback}')">
                            <i class="fa fa-info" aria-hidden="true" style="padding-top : 12%;"></i>
                        </a>`
            }
        },
    ],
});

let tableItensOS = $("#tabela-itens-os").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: false,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        {data: "tz_name"},
        {data: "tz_itemid"},
        {data: "tz_quantidade"},
        {data: "tz_valor_total"},
        {data: "tz_status_aprovacao"},       
        {data: "acoes"}       
    ]
});

let tableItensOSNA = $("#tabela-itens-os-na").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: false,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        {data: "tz_name"},
        {data: "tz_itemid"},
        {data: "tz_quantidade"},
        {data: "tz_valor_total"},
        {data: "tz_status_aprovacao"},       
        {data: "acoes"}       
    ]
});

let tableItensOSBusca = $("#tabela-itens-os-busca").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: false,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        {data: "tz_name"},
        {data: "tz_itemid"},
        {data: "tz_quantidade"},
        {data: "tz_valor_total"},
        {data: "tz_status_aprovacao"},       
        {data: "acoes"}       
    ]
});

function instanciarTabelaVendasAf(idCliente,tipoCliente){
    // Inicialização do plugin do DataTables na tabela de Ocorrências
    
    if( $.fn.DataTable.isDataTable( "#tabela-vendas-af-" + abaSelecionada)){
        $("#tabela-vendas-af-" + abaSelecionada).DataTable().clear()
        $("#tabela-vendas-af-" + abaSelecionada).DataTable().destroy()
    }
    
    dadosAf = {
        idCliente: idCliente,
        tipoCliente: tipoCliente
    }

    $("#tabela-vendas-af-" + abaSelecionada).DataTable({
        lengthChange: false,
        ordering: false,
        responsive: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            emptyTable:     "Nenhuma AF encontrada.",
            info:           "Mostrando _START_ de _END_ do total de _TOTAL_ registros",
            infoEmpty:      "Mostrando 0 de 0 do total de 0 resultados",        
            paginate: {
                first:      "Primeira",
                last:       "Última",
                next:       "Próxima",
                previous:   "Anterior"
            },
            zeroRecords:    "Nenhum resultado encontrado.",
            search:         "Buscar:"
        },
        ajax: {
            url: `${URL_PAINEL_OMNILINK}/buscarAfCliente`,
            type: 'POST',
            data: dadosAf,
            dataType: 'json',
            dataSrc: function (receivedData) { //verifica se os dados foram carregados
                if (receivedData) {
                    switch (receivedData.status) {

                        case 200:
                            if (receivedData.data != null){
                                return receivedData.data; //retorna o que deve ser trabalhado no datatable
                            }else{
                                return [];
                            }
                        case 0: //sem conexão ao CRM
                            alert('Base de dados está apresentando instabilidade, tente novamente em alguns minutos')
                            return [];
                        default:
                            alert('Ocorreu um problema ao buscar as AFs, tente novamente em instantes.')
                            return [];
                    }
                } else {
                    alert('Ocorreu um problema ao buscar as AFs, tente novamente em instantes.')
                    return [];
                }
            },
            complete: function(){
                $("#tabela-vendas-af-" + abaSelecionada).DataTable().columns.adjust().draw();
            }
        },
        columns: [
            { data: 'numeroAf' },
            { data: 'modalidade' },
            { data: 'tipoPagamento' },
            { data: 'criado' },
            { data: 'modificado' },
            { data: 'statusAf' },
            { data: 'acoes' },
        ],
    });
}
let tabelaComunicacao = $("#tabela-equipamento-comunicacao").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: false,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        { data: "nomeTecnologia" },
        { data: "nomeModelo" },
        { data: "status" }, 
        { data: "fone" },
        { data: "operadora" },
        { data: "data" }, 
        { data: {'idOperadora': 'idOperadora',  'linha': 'linha'},
            orderable: false,
            render: function (data){
                if(data['operadora'] == 'CLARO'){
                    return `
                            <button data-toggle="modal" data-target="#modalMovidesk"
								class="btn btn-primary"
                                style="height: 45px; width: 45px;"
								title="Abrir Ticket Movidesk" 
								onclick="ticketPersonalizado(this, 'Chip')">
								<i class="fa fa-ticket" aria-hidden="true"></i>
							</button>
                            `;
                }
                else{
                    return ` <button
                                class="btn btn-primary"
                                title="Resetar Linha"
                                id="btnResetarChip"
                                style="height: 45px; width: 45px;"
                                onClick="javascript:modalResetarChip(this, '${data['linha']}', '${data['idOperadora']}')">
                                <i class="fa fa-refresh" aria-hidden="true" style="font-size: 20px; display: block; margin: 0 auto;"></i>
                            </button>
                            <button data-toggle="modal" data-target="#modalMovidesk"
								class="btn btn-primary"
                                style="height: 45px; width: 45px;"
								title="Abrir Ticket Movidesk" 
								onclick="ticketPersonalizado(this, 'Chip')">
								<i class="fa fa-ticket" aria-hidden="true"></i>
							</button>
                            `;
                }
            }
            },
    ]
});

let tabelaIridium = $("#tabela-equipamento-iridium").DataTable({
    responsive: true,
    ordering: false,
    paging:false,
    searching: false,
    info: false,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        { data: "imei" },
        { data: "estado" },
        { data: "criadoEm" },
        { data: "atualizadoEm" },
        { data: "destino" },
        { data: "metodoDeEntrega" },
        { data: "geoData" },
        { data: "moack" },
        { 
            data: "imei",
            render: function (data, type, row, meta) {
                return `
                <button data-toggle="modal" data-target="#modalMovidesk"
                    class="btn btn-primary"
                    title="Abrir Ticket Movidesk" 
                    style="height: 45px; width: 45px;"
                    onclick="ticketPersonalizado(this, 'Iridium')">
                    <i class="fa fa-ticket" aria-hidden="true"></i>
                </button>
                `;
            }
        },
    ]
});

let tabelamhs = $("#tabela-equipamento-mhs").DataTable({
    responsive: true,
    ordering: false,
    paging:false,
    searching: false,
    info: false,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        { data: "imei" },
        { data: "estado" },
        { data: "criadoEm" },
        { data: "atualizadoEm" },
        { data: "destino" },
        { data: "metodoDeEntrega" },
        { data: "geoData" },
        { data: "moack" },
    ]
});


let tabelaInformacoesMhs = $("#tabela-informações-mhs").DataTable({
    responsive: true,
    ordering: false,
    paging:true,
    searching: false,
    info: true,
    language: lang.datatable,
    lengthChange: false,
    columns: [
        { data: "idEquipamento" },
        { data: "dataHoraSuspensao" },
        { data: "dataHoraCancelamento" },
        { data: "idAntenna" },
        { data: "operadora" },
        { data: "nomeOperadora"},
        { data: "dataUltimaComunicacao" },
        { data: "tipoUltimaComunicacao" },
        { data: "descricaoTipo" },
    ]
});

let tableHistoricoStatusAF = $('#tabelaHistoricoStatusAF').DataTable({
    responsive: true,
    ordering: false,
    paging: true,
    searching: true,
    info: true,
    language: lang.datatable,
    deferRender: true,
    lengthChange: false,
    columns: [
        { data: 'dataEvento' },
        { data: 'observacoes' }
    ]
});

