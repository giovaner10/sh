
var localeText = AG_GRID_LOCALE_PT_BR;
var dadosTabelaProdutos = [];
var dadosTabelaPropostas = [];
var dadosTabelaItensPropostas = [];
var teveBusca = false;
var tipoBusca = 0;
var dadoBusca = '';
var teveBuscaProposta = false;
var paramsBuscaProposta = '';
var dadosTabelaAutorizacaoFaturamento = [];
var enterButton = false;
var teveBuscaCliente = false;
var dadoBuscaCliente = '';
var dadosTabelaClientes = [];
var dadosTabelaOportunidades = [];
var dadosTabelaItensOportunidades = [];
var transformaOportunidade = false;
var itensOportunidadeGerarProposta = [];
var itensComposicaoProduto = [];
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({trigger: 'manual', placement: 'bottom'});
    $('[data-toggle="tooltip"]').tooltip('hide');
    $('#spinnerCliente').hide();
    $('#selectTipoBuscaProduto').on('change', function (e, clickedIndex, isSelected, previousValue) {
        let tipoBusca = $(this).val();
        if (tipoBusca == '0') {
            $('.divId').show();
            $('.divNome').hide();
            $('#buscaNome').val('');
            $('#buscaId').attr('required',true);
            $('#buscaNome').attr('required',false);
        }else{
            $('.divNome').show();
            $('.divId').hide();
            $('#buscaId').val('');
            $('#buscaNome').attr('required',true);
            $('#buscaId').attr('required',false);
        }
    });

    $('#temComposicao').on('change', function() {
        if ($(this).val() == '1') {
            $('#produtosComposicao').show();
        } else {
            $('#produtosComposicao').hide();
        }
    });

    $('#selectTipoBuscaCliente').on('change', function (e, clickedIndex, isSelected, previousValue) {
        let tipoBusca = $(this).val();
        if (tipoBusca == '0') {
            $('.divIdCliente').show();
            $('.divNomeCliente').hide();
            $('.divDocumentoCliente').hide();
            $('#buscaNomeCliente').val('');
            $('#buscaDocumentoCliente').val('');
            $('#buscaIdCliente').attr('required',true);
            $('#buscaNomeCliente').attr('required',false);
            $('#buscaDocumentoCliente').attr('required',false);
        }else if (tipoBusca == '1'){
            $('.divNomeCliente').show();
            $('.divIdCliente').hide();
            $('.divDocumentoCliente').hide();
            $('#buscaIdCliente').val('');
            $('#buscaDocumentoCliente').val('');
            $('#buscaNomeCliente').attr('required',true);
            $('#buscaIdCliente').attr('required',false);
            $('#buscaDocumentoCliente').attr('required',false);
        }else{
            $('.divIdCliente').hide();
            $('.divNomeCliente').hide();
            $('.divDocumentoCliente').show();
            $('#buscaNomeCliente').val('');
            $('#buscaIdCliente').val('');
            $('#buscaNomeCliente').attr('required',false);
            $('#buscaIdCliente').attr('required',false);
            $('#buscaDocumentoCliente').attr('required',true);
        }
    });

    $('#menu-proposta').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu-produto').removeClass( "selected" );
            $('#menu-autorizacao-faturamento').removeClass( "selected" );
            $('#menu-cliente').removeClass( "selected" );
            $('#menu-kanban').removeClass( "selected" );
            $('#menu-oportunidade').removeClass( "selected" );
            $('#menu-kanban-autorizacao').removeClass( "selected" );
            $('.card-produtos').hide();
            $('.card-propostas').show();
            $('.filtroProdutos').hide();
            $('.filtroPropostas').show();
            $('.filtroOportunidades').hide();
            $('.filtroAutorizacaoFaturamento').hide();
            $('.card-autorizacao-faturamento').hide();
            $('.filtroClientes').hide();
            $('.card-clientes').hide();
            $('.card-oportunidades').hide();
            $('.card-kanban-propostas').hide();
            $('.filtroKanbanPropostas').hide();
            $('.card-kanban-autorizacao').hide();
            $('.filtroKanbanAutorizacaoFaturamento').hide();
            listarTop100Propostas();
        }
    });

    $('#menu-produto').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu-proposta').removeClass( "selected" );
            $('#menu-autorizacao-faturamento').removeClass( "selected" );
            $('#menu-cliente').removeClass( "selected" );
            $('#menu-kanban').removeClass( "selected" );
            $('#menu-oportunidade').removeClass( "selected" );
            $('#menu-kanban-autorizacao').removeClass( "selected" );
            $('.card-propostas').hide();
            $('.card-produtos').show();
            $('.filtroPropostas').hide();
            $('.filtroProdutos').show();
            $('.filtroOportunidades').hide();
            $('#selectTipoBuscaProduto').val('0').trigger('change');
            $('.filtroAutorizacaoFaturamento').hide();
            $('.card-autorizacao-faturamento').hide();
            $('.filtroClientes').hide();
            $('.card-clientes').hide();
            $('.card-kanban-propostas').hide();
            $('.card-oportunidades').hide();
            $('.filtroKanbanPropostas').hide();
            $('.card-kanban-autorizacao').hide();
            $('.filtroKanbanAutorizacaoFaturamento').hide();
            listarTop100Produtos();
        }
    });

    $('#menu-autorizacao-faturamento').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu-proposta').removeClass( "selected" );
            $('#menu-produto').removeClass( "selected" );
            $('#menu-cliente').removeClass( "selected" );
            $('#menu-kanban').removeClass( "selected" );
            $('#menu-oportunidade').removeClass( "selected" );
            $('#menu-kanban-autorizacao').removeClass( "selected" );
            $('.card-propostas').hide();
            $('.card-produtos').hide();
            $('.filtroPropostas').hide();
            $('.filtroProdutos').hide();
            $('.filtroOportunidades').hide();
            $('.filtroAutorizacaoFaturamento').show();
            $('.card-autorizacao-faturamento').show();
            $('.filtroClientes').hide();
            $('.card-clientes').hide();
            $('.card-oportunidades').hide();
            $('.card-kanban-propostas').hide();
            $('.filtroKanbanPropostas').hide();
            $('.card-kanban-autorizacao').hide();
            $('.filtroKanbanAutorizacaoFaturamento').hide();
            atualizarAgGridAutorizacaoFaturamento();
        }
    });

    $('#menu-cliente').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu-produto').removeClass( "selected" );
            $('#menu-proposta').removeClass( "selected" );
            $('#menu-autorizacao-faturamento').removeClass( "selected" );
            $('#menu-kanban').removeClass( "selected" );
            $('#menu-oportunidade').removeClass( "selected" );
            $('#menu-kanban-autorizacao').removeClass( "selected" );
            $('.card-produtos').hide();
            $('.card-propostas').hide();
            $('.card-autorizacao-faturamento').hide();
            $('.card-kanban-propostas').hide();
            $('.card-oportunidades').hide();
            $('.filtroKanbanPropostas').hide();
            $('.filtroProdutos').hide();
            $('.filtroPropostas').hide();
            $('.filtroAutorizacaoFaturamento').hide();
            $('.filtroOportunidades').hide();
            $('.card-clientes').show();
            $('.filtroClientes').show();
            $('.card-kanban-autorizacao').hide();
            $('.filtroKanbanAutorizacaoFaturamento').hide();
            atualizarAgGridClientes();
        }
    });

    $('#menu-kanban').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu-produto').removeClass( "selected" );
            $('#menu-proposta').removeClass( "selected" );
            $('#menu-autorizacao-faturamento').removeClass( "selected" );
            $('#menu-cliente').removeClass( "selected" );
            $('#menu-oportunidade').removeClass( "selected" );
            $('#menu-kanban-autorizacao').removeClass( "selected" );
            $('.card-produtos').hide();
            $('.card-propostas').hide();
            $('.card-autorizacao-faturamento').hide();
            $('.card-clientes').hide();
            $('.card-oportunidades').hide();
            $('.card-kanban-propostas').show();
            $('.filtroProdutos').hide();
            $('.filtroPropostas').hide();
            $('.filtroAutorizacaoFaturamento').hide();
            $('.filtroClientes').hide();
            $('.filtroOportunidades').hide();
            $('.filtroKanbanPropostas').show();
            $('.card-kanban-autorizacao').hide();
            $('.filtroKanbanAutorizacaoFaturamento').hide();

            atualizarAgGridItensPropostaInfo();
            carregarKanbanPropostas('/buscarPropostasTop100');
        }
    });

    $('#menu-oportunidade').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu-produto').removeClass( "selected" );
            $('#menu-proposta').removeClass( "selected" );
            $('#menu-autorizacao-faturamento').removeClass( "selected" );
            $('#menu-cliente').removeClass( "selected" );
            $('#menu-kanban').removeClass( "selected" );
            $('#menu-kanban-autorizacao').removeClass( "selected" );
            $('.card-produtos').hide();
            $('.card-propostas').hide();
            $('.card-autorizacao-faturamento').hide();
            $('.card-clientes').hide();
            $('.card-kanban-propostas').hide();
            $('.filtroProdutos').hide();
            $('.filtroPropostas').hide();
            $('.filtroAutorizacaoFaturamento').hide();
            $('.filtroClientes').hide();
            $('.filtroKanbanPropostas').hide();
            $('.filtroOportunidades').show();
            $('.card-oportunidades').show();
            $('.card-kanban-autorizacao').hide();
            $('.filtroKanbanAutorizacaoFaturamento').hide();
            atualizarAgGridOportunidades();
        }
    });

    $('#menu-kanban-autorizacao').on('click', function() {
        if(!$(this).hasClass( "selected" )){
            $(this).addClass( "selected" );
            $('#menu-produto').removeClass( "selected" );
            $('#menu-proposta').removeClass( "selected" );
            $('#menu-autorizacao-faturamento').removeClass( "selected" );
            $('#menu-cliente').removeClass( "selected" );
            $('#menu-kanban').removeClass( "selected" );
            $('.card-produtos').hide();
            $('.card-propostas').hide();
            $('.card-autorizacao-faturamento').hide();
            $('.card-clientes').hide();
            $('.card-kanban-propostas').hide();
            $('.filtroProdutos').hide();
            $('.filtroPropostas').hide();
            $('.filtroAutorizacaoFaturamento').hide();
            $('.filtroClientes').hide();
            $('.filtroKanbanPropostas').hide();
            $('.filtroOportunidades').hide();
            $('.card-oportunidades').hide();
            $('.card-kanban-autorizacao').show();
            $('.filtroKanbanAutorizacaoFaturamento').show();
            carregarKanbanAutorizacao('/buscarAutorizacoes'); //Substituir o parâmetro pela função que busca as autorizações
        }
    });

    $('#selectTipoBuscaCliente').on('change', function (e, clickedIndex, isSelected, previousValue) {
        let tipoBusca = $(this).val();
        if (tipoBusca == '0') {
            $('.divIdCliente').show();
            $('.divNomeCliente').hide();
            $('#buscaNomeCliente').val('');
            $('#buscaDocumentoCliente').val('');
            $('#buscaIdCliente').attr('required',true);
            $('#buscaNomeCliente').attr('required',false);
            $('#buscaDocumentoCliente').attr('required',false);
        }else if (tipoBusca == '1'){
            $('.divNomeCliente').show();
            $('.divIdCliente').hide();
            $('#buscaIdCliente').val('');
            $('#buscaDocumentoCliente').val('');
            $('#buscaNomeCliente').attr('required',true);
            $('#buscaIdCliente').attr('required',false);
            $('#buscaDocumentoCliente').attr('required',false); 
        }else if (tipoBusca == '2'){    
            $('.divIdCliente').hide();
            $('.divNomeCliente').hide();
            $('.divDocumentoCliente').show();
            $('#buscaNomeCliente').val('');
            $('#buscaIdCliente').val('');
            $('#buscaNomeCliente').attr('required',false);
            $('#buscaIdCliente').attr('required',false);
            $('#buscaDocumentoCliente').attr('required',true);  
        }
    });

    $('#BtnAdicionarProduto').on('click', function() {
        abrirModalProduto();
        $('#produtoComposicao').val('').trigger('change');
        $('#idProduto').val('');
        $('#statusProduto').val('');
        $('#produtosComposicao').hide();
    });

    $('#formProduto').on('submit', function(e) {
        e.preventDefault();
        salvarProduto();
    });

    $('#formProposta').on('submit', function(e) {
        e.preventDefault();
        let documento = $('#documentoAutorizador').val();
        let email = $('#emailAutorizador').val();
        let documentoFormatado = documento ?  documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
        let telefoneAutorizador = $('#telefoneAutorizador').val();
        let telefoneInserir = telefoneAutorizador ? telefoneAutorizador.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
        telefoneInserir = telefoneInserir.replaceAll(' ', '');

        if (documento){
            if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
                showAlert('warning', 'Documento inválido.');
                return;
            }
        } 
        
        if (email) {
            if(!validaEmail(email)) {
                showAlert('warning', 'E-mail inválido.');
                return;
            }
        }

        if (telefoneAutorizador){
            if (telefoneInserir.length != 11){
                showAlert('warning', 'Telefone inválido.');
                return;
            }
        }

        salvarProposta();
    });

    $('#formItensPropostaEdit').on('submit', function(e) {
        e.preventDefault();
        let btnAdicionarTabelaItens = $('#btnAdicionarItemTabelaEdit').css('display') == 'none' ? false : true;

        if (btnAdicionarTabelaItens){
            adicionarDadosTabelaItensPropostaAddEdit();
        }else{
            salvarItemProposta();
        }
    });

    $('#formOportunidade').on('submit', function(e) {
        e.preventDefault();
        let id = $('#idOportunidade').val();

        let documentoCliente = $('#documentoClienteOportunidade').val();
        let emailCliente = $('#emailClienteOportunidade').val();
        let documentoClienteFormatado = documentoCliente ? documentoCliente.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';

        if (documentoCliente){
            if (documentoClienteFormatado.length != 11 && documentoClienteFormatado.length != 14){
                showAlert('warning', 'Digite um CPF/CNPJ válido!');
                return;
            }
        }

        if (emailCliente) {
            if(!validaEmail(emailCliente)) {
                showAlert('warning', 'E-mail do cliente inválido.');
                return;
            }
        }

        let dados = AgGridItensOportunidade.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
            return{
                idProduto: dado.data.idProduto,
                quantidade : dado.data.quantidadeItemTabela,
                valorUnitario : dado.data.valorUnitarioItemTabela ? (formataInsercao((dado.data.valorUnitarioItemTabela).replace('R$', '').trim())) : '',
                percentualDesconto : dado.data.percentualDescontoItemTabela ? ((dado.data.percentualDescontoItemTabela).replace('%', '').trim()).replace(',', '.') : '',
                valorTotal : dado.data.valoTotalItemTabela ? (formataInsercao((dado.data.valoTotalItemTabela).replace('R$', '').trim())) : '',
                valorDesconto : dado.data.valorDescontoItemTabela ? (formataInsercao((dado.data.valorDescontoItemTabela).replace('R$', '').trim())) : '',
                observacao: dado.data.observacaoItemTabela ? dado.data.observacaoItemTabela : null
            };
        });

        if (!id){
            if (dados.length > 0){
                salvarOportunidade(dados);
            }else{
                showAlert('warning', 'Adicione, pelo menos, um item na tabela da aba "Itens da Oportunidade".');
            }
        }else{
            salvarOportunidade();
        }

    });

    $('#formItensOportunidadeEdit').on('submit', function(e) {
        e.preventDefault();
        let btnAdicionarTabelaItens = $('#btnAdicionarItemTabelaEditOportunidade').css('display') == 'none' ? false : true;

        if (btnAdicionarTabelaItens){
            adicionarDadosTabelaItensOportunidadeAddEdit();
            
        }else{
            salvarItemOportunidade();
        }
    });

    $('#BtnAdicionarProposta').on('click', function() {
        transformaOportunidade = false;
        abrirModalProposta();
        $('#idProposta').val('');
        $('#statusProposta').val('');
        $('#valorTotalProposta').val('');
        $('#quantidadeTotalProposta').val('');
        $('#afProposta').val('');
        $('#idEnderecoFatura').val('');
        $('#idEnderecoPagamento').val('');
        $('#btnSalvarProposta').html('Salvar e continuar');
    });

    $('#BtnAdicionarOportunidade').on('click', function() {
        abrirModalOportunidade();
        $('#idOportunidade').val('');
    });


    $('#btnAdicionarNovoItemEdit').on('click', function() {
        abrirModalItemEdit();
        $('#idItem').val('');
    });

    $('#btnAdicionarNovoItemOportunidadeEdit').on('click', function() {
        abrirModalItemEditOportunidade();
        $('#idItemOportunidade').val('');
    });

    $('#produtoItemProposta').select2({
        ajax: {
            url: RouterController + '/listarProdutoSelect2',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.resultado
                };
            }
        },
        placeholder: "Digite o nome do produto",
        allowClear: true,
        language: "pt-BR",
    });

    $('#produtoItemOportunidade').select2({
        ajax: {
            url: RouterController + '/listarProdutoSelect2',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.resultado
                };
            }
        },
        placeholder: "Digite o nome do produto",
        allowClear: true,
        language: "pt-BR",
    });

    $('#produtoComposicao').select2({
        ajax: {
            url: RouterController + '/listarProdutoSelect2',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.resultado
                };
            }
        },
        placeholder: "Digite o nome do produto a ser adicionado...",
        allowClear: true,
        language: "pt-BR",
    });

    $('#produtoItemComposicaoEdit').select2({
        ajax: {
            url: RouterController + '/listarProdutoSelect2',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.resultado
                };
            }
        },
        placeholder: "Digite o nome do produto a ser adicionado...",
        allowClear: true,
        language: "pt-BR",
    });

    $('#btnAdicionarComposicaoTabela').on('click', function() {
        if ($('#produtoComposicao').val()) {
            let selectedId = $('#produtoComposicao').val();
            let selectedText = $('#produtoComposicao').select2('data')[0].text;

            // Verificar se o ID já existe na tabela
            let existingNode = AgGridItensComposicao.gridOptions.api.getRowNode(selectedId);

            if (existingNode) {
                showAlert('warning', 'O produto já foi adicionado à composição.');
            } else {
                let dados = {
                    id: selectedId,
                    produto: selectedText
                }
                AgGridItensComposicao.gridOptions.api.applyTransaction({add: [dados]});
                $('#produtoComposicao').val('').trigger('change');
            }
        } else {
            showAlert('warning', 'Selecione um produto para adicionar');
        }
        
    });

    if ($('#menu-produto').length){
        atualizarAgGridProdutos();
        listarTop100Produtos();
        atualizarAgGridItensComposicao();
    }

    if ($('#menu-proposta').length){
        atualizarAgGridPropostas();
        atualizarAgGridItensProposta();
        atualizarAgGridItensPropostaEdit();
        atualizarAgGridItensPropostaAddEdit();
        listarTop100Propostas();
    }

    if ($('#menu-autorizacao-faturamento').length){
        atualizarAgGridAutorizacaoFaturamento();
    }

    if ($('#menu-kanban').length){
        atualizarAgGridItensPropostaInfo();
        carregarKanbanPropostas('/buscarPropostasTop100');
    }

    if ($('#menu-oportunidade').length){
        atualizarAgGridOportunidades();
        atualizarAgGridItensOportunidades();
        atualizarAgGridItensOportunidadeEdit();
        atualizarAgGridItensOportunidadeAddEdit();
    }

    if ($('#menu-cliente').length){
        atualizarAgGridClientes();
    }

    if ($('#menu-kanban-autorizacao').length){
        carregarKanbanAutorizacao('/buscarAutorizacoes'); //Substituir o parâmetro pela função que busca as autorizações
    }
    
    if(permissao){
        listarVendedores('idVendedor');
    }else {
        $.ajax({
            url: RouterController + '/buscarVendedor',
            dataType: 'json',
            success: function(response){
                if(response['status'] == 200){
                    $('#idVendedor').empty();
                    $('#idVendedor').append(`<option value="${response["resultado"]["id"]}" selected disabled>${response["resultado"]["nome"]}</option>`);
                }
                if(response['status'] == 404){
                    $('#vendedor').html(`<button class="btn btn-primary" id="BtnAdicionarProposta" type="button" style="margin-right: 10px;" title="Somente usuários com cadastro de vendedor podem acessar essa funcionalidade" disabled><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>`);
                    $("#modalProposta").modal('hide');
                }
            }
        });
    }

    listarVendedoresSelects(['buscaVendedorProposta', 'buscaVendedorAutorizacaoFaturamento', 'buscaVendedorKanbanPropostas', 'buscaVendedorOportunidade', 'idVendedorOportunidade', 'buscaVendedorKanbanAutorizacaoFaturamento']);
    var cartaoVazio = '<div class="kanban-card-sombra kanban-card-placeholder"></div>';
    function adicionarCartoesVazios(quantidade) {
        for (var i = 0; i < quantidade; i++) {
            $('.nao-integrado-column-gerenciamento .kanban-cards').append(cartaoVazio);
            $('.integrado-column-gerenciamento .kanban-cards').append(cartaoVazio);
            $('.faturado-column-gerenciamento .kanban-cards').append(cartaoVazio);
            $('.atualizado-column-gerenciamento .kanban-cards').append(cartaoVazio);
        }
    }

    function adicionarCartoesVaziosAutorizacao(quantidade) {
        for (var i = 0; i < quantidade; i++) {
            $('.aguardando-column-autorizacao .kanban-cards').append(cartaoVazio);
            $('.enviado-column-autorizacao .kanban-cards').append(cartaoVazio);
            $('.reenviado-column-autorizacao .kanban-cards').append(cartaoVazio);
            $('.recusado-column-autorizacao .kanban-cards').append(cartaoVazio);
            $('.autorizado-column-autorizacao .kanban-cards').append(cartaoVazio);
        }
    }

    function carregarKanbanPropostas(funcao){
        $.ajax({
            url: RouterController + funcao,
            dataType: "json",
            tyooe: "GET",
            beforeSend: function () {
                $('.kanban-cards-propostas').html('');
                $('.kanban-count-gerenciamento').html('<i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>');
                adicionarCartoesVazios(100);
            },
            success: function (data) {
                if (data.status == 200){
                    preencheColunasKanban(data);
                }else if (data.status == 404 && data.resultado.mensagem){
                    $('.kanban-count-gerenciamento').html('0');
                    $('.kanban-cards-propostas').html('');
                }else{
                    $('.kanban-count-gerenciamento').html('0');
                    $('.kanban-cards-propostas').html('');
                    if (data['resultado']){
                        validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da proposta');
                    }else{
                        showAlert('error', 'Não foi possível buscar os dados da proposta. Tente novamente.');
                    }
                }
            },
            error: function (data) {
                $('.kanban-count-gerenciamento').html('0');
                $('.kanban-cards-propostas').html('');
                showAlert('error', 'Não foi possível listar os dados da proposta. Tente novamente.');
                resetLoadingButtonLimpar('BtnLimparKanbanPropostas');
            },
            complete: function () {
                resetLoadingButtonLimpar('BtnLimparKanbanPropostas');
            }
        })
    }

    function carregarKanbanAutorizacao(funcao){
        $.ajax({
            url: RouterController + funcao,
            dataType: "json",
            type: "POST",
            beforeSend: function () {
                $('.kanban-cards-autorizacao').html('');
                $('.kanban-count-autorizacao').html('<i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>');
                adicionarCartoesVaziosAutorizacao(100);
            },
            success: function (data) {
                if (data.status == 200){
                    preencheColunasKanbanAutorizacao(data);
                }else if (data.status == 404 && data.resultado.mensagem){
                    $('.kanban-count-autorizacao').html('0');
                    $('.kanban-cards-autorizacao').html('');
                }else{
                    $('.kanban-count-autorizacao').html('0');
                    $('.kanban-cards-autorizacao').html('');
                    if (data['resultado']){
                        validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da autorização de faturamento');
                    }else{
                        showAlert('error', 'Não foi possível buscar os dados da autorização de faturamento. Tente novamente.');
                    }
                }
            },
            error: function (data) {
                $('.kanban-count-autorizacao').html('0');
                $('.kanban-cards-autorizacao').html('');
                showAlert('error', 'Não foi possível listar os dados da autorização de faturamento. Tente novamente.');
                resetLoadingButtonLimpar('BtnLimparKanbanAutorizacaoFaturamento');
            },
            complete: function () {
                resetLoadingButtonLimpar('BtnLimparKanbanAutorizacaoFaturamento');
            }
        })
    }

    function preencheColunasKanban(dados){
        var gerados = dados.resultado;
        var htmlNaoIntegrado = '';
        var htmlIntegrado = '';
        var htmlFaturado = '';
        var htmlAtualizado = '';
        var geradosCountNaoIntegrado = 0;
        var geradosCountIntegrado = 0;
        var geradosCountFaturado = 0;
        var geradosCountAtualizado = 0;
        var valorMaior = 0;
        
        $('.kanban-cards-propostas').html('');
        gerados.forEach(function (item) {
            if (item.statusIntegracao == 'NAO_INTEGRADO') {
                geradosCountNaoIntegrado += 1;
                htmlNaoIntegrado += criarCards(item);
            }else if (item.statusIntegracao == 'INTEGRADO') {
                geradosCountIntegrado += 1;
                htmlIntegrado += criarCards(item);
            }else if (item.statusIntegracao == 'FATURADO') {
                geradosCountFaturado += 1;
                htmlFaturado += criarCards(item);
            }else if (item.statusIntegracao == 'ATUALIZADO') {
                geradosCountAtualizado += 1;
                htmlAtualizado += criarCards(item);
            }
        })
        
        $('.nao-integrado-column-gerenciamento .kanban-count-gerenciamento').text(geradosCountNaoIntegrado);
        $('.nao-integrado-column-gerenciamento .kanban-cards').append(htmlNaoIntegrado);
        $('.integrado-column-gerenciamento .kanban-count-gerenciamento').text(geradosCountIntegrado);
        $('.integrado-column-gerenciamento .kanban-cards').append(htmlIntegrado);
        $('.faturado-column-gerenciamento .kanban-count-gerenciamento').text(geradosCountFaturado);
        $('.faturado-column-gerenciamento .kanban-cards').append(htmlFaturado);
        $('.atualizado-column-gerenciamento .kanban-count-gerenciamento').text(geradosCountAtualizado);
        $('.atualizado-column-gerenciamento .kanban-cards').append(htmlAtualizado);

        var alturaKanbanCardsNaoIntegrado = document.querySelector('.nao-integrado-column-gerenciamento .kanban-cards').offsetHeight;
        var alturaKanbanCardsIntegrado = document.querySelector('.integrado-column-gerenciamento .kanban-cards').offsetHeight;
        var alturaKanbanCardsFaturado = document.querySelector('.faturado-column-gerenciamento .kanban-cards').offsetHeight;
        var alturaKanbanCardsAtualizado = document.querySelector('.atualizado-column-gerenciamento .kanban-cards').offsetHeight;

        valorMaior = Math.max(alturaKanbanCardsNaoIntegrado, alturaKanbanCardsIntegrado, alturaKanbanCardsFaturado, alturaKanbanCardsAtualizado);
        $('.kanban-column').css('height', ((valorMaior ) + 70) + 'px');
    }

    function preencheColunasKanbanAutorizacao(dados){
        items = {}
        var gerados = dados.resultado;
        var htmlAguardando = '';
        var htmlEnviado = '';
        var htmlReenviado = '';
        var htmlRecusado = '';
        var htmlAutorizado = '';
        var geradosCountAguardando = 0;
        var geradosCountEnviado = 0;
        var geradosCountReenviado = 0;
        var geradosCountRecusado = 0;
        var geradosCountAutorizado = 0;
        var valorMaior = 0;
        
        $('.kanban-cards-autorizacao').html('');
        gerados.forEach(function (item) {
            if (item.statusAutorizacao == 'AGUARDANDO') {
                geradosCountAguardando += 1;
                htmlAguardando += criarCardsAutorizacao(item);
            }else if (item.statusAutorizacao == 'ENVIADO') {
                geradosCountEnviado += 1;
                htmlEnviado += criarCardsAutorizacao(item);
            }else if (item.statusAutorizacao == 'REENVIADO') {
                geradosCountReenviado += 1;
                htmlReenviado += criarCardsAutorizacao(item);
            }else if (item.statusAutorizacao == 'RECUSADO') {
                geradosCountRecusado += 1;
                htmlRecusado += criarCardsAutorizacao(item);
            }else if (item.statusAutorizacao == 'AUTORIZADO') {
                geradosCountAutorizado += 1;
                htmlAutorizado += criarCardsAutorizacao(item);
            }
        })
        
        $('.aguardando-column-autorizacao .kanban-count-autorizacao').text(geradosCountAguardando);
        $('.aguardando-column-autorizacao .kanban-cards').append(htmlAguardando);
        $('.enviado-column-autorizacao .kanban-count-autorizacao').text(geradosCountEnviado);
        $('.enviado-column-autorizacao .kanban-cards').append(htmlEnviado);
        $('.reenviado-column-autorizacao .kanban-count-autorizacao').text(geradosCountReenviado);
        $('.reenviado-column-autorizacao .kanban-cards').append(htmlReenviado);
        $('.recusado-column-autorizacao .kanban-count-autorizacao').text(geradosCountRecusado);
        $('.recusado-column-autorizacao .kanban-cards').append(htmlRecusado);
        $('.autorizado-column-autorizacao .kanban-count-autorizacao').text(geradosCountAutorizado);
        $('.autorizado-column-autorizacao .kanban-cards').append(htmlAutorizado);

        var alturaKanbanCardsAguardando = document.querySelector('.aguardando-column-autorizacao .kanban-cards').offsetHeight;
        var alturaKanbanCardsEnviado = document.querySelector('.enviado-column-autorizacao .kanban-cards').offsetHeight;
        var alturaKanbanCardsReenviado = document.querySelector('.reenviado-column-autorizacao .kanban-cards').offsetHeight;
        var alturaKanbanCardsRecusado = document.querySelector('.recusado-column-autorizacao .kanban-cards').offsetHeight;
        var alturaKanbanCardsAutorizado = document.querySelector('.autorizado-column-autorizacao .kanban-cards').offsetHeight;

        valorMaior = Math.max(alturaKanbanCardsAguardando, alturaKanbanCardsEnviado, alturaKanbanCardsReenviado, alturaKanbanCardsRecusado, alturaKanbanCardsAutorizado);
        $('.kanban-column').css('height', ((valorMaior ) + 70) + 'px');
    }

    function criarCards(item){
        var id = item.id;
        var af = item.af ? item.af : '-';
        var cliente = item.nomeCliente ? item.nomeCliente : '-';
        var vendedor = item.nomeVendedor ? item.nomeVendedor : '-';
        var valorTotal = item.valorTotal ? (item.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00';

        var html = '';
        html += '<div class="kanban-card" id="card-' + id + '>';
        html += '<div class="text-card">';
        html += '<b>ID:</b> <span class="text-card-valor">' + id + '</span>';
        html += '<br><b>AF:</b> <span class="text-card-valor">' + af + '</span>';
        html += '<br><b>Cliente:</b> <span class="text-card-valor">' + cliente + '</span>';
        html += '<br><b>Vendedor:</b> <span class="text-card-valor">' + vendedor + '</span>';
        html += '<br><b>Valor Total:</b> <span class="text-card-valor">' + valorTotal + '</span>';
        html += '<i id="informacoesProposta-'+id+'" class="fa fa-info-circle iconeExibirInfoProposta btnInfo" style="font-size: 20px; color: #06a9f6; cursor: pointer; margin-top: 10px;" onclick="buscarDadosProposta(this,\'' + item.id + '\')"></i><i id="iconLoaderInfoDadosProposta-' + id + '" class="fa fa-spinner fa-spin iconeExibirInfoProposta" style="display:none; font-size: 20px;margin-top: 10px;color: #06a9f6"></i>';
        html += '</div>';
        html += '</div>';

        return html;
    }


    function criarCardsAutorizacao(item){
        var id = item.id;
        var af = item.af ? item.af : '-';
        var cliente = item.nomeCliente ? item.nomeCliente : '-';
        var vendedor = item.nomeVendedor ? item.nomeVendedor : '-';
        var valorTotal = item.valorTotal ? (item.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00';
        items[id] = item;

        var html = '';
        html += '<div style="cursor: pointer;" onClick="exibirDetalhes(' + id + ')"  class="kanban-card" id="card-' + id + '>';
        html += '<div class="text-card">';
        html += '<b>ID:</b> <span class="text-card-valor">' + id + '</span>';
        html += '<br><b>AF:</b> <span class="text-card-valor">' + af + '</span>';
        html += '<br><b>Cliente:</b> <span class="text-card-valor">' + cliente + '</span>';
        html += '<br><b>Vendedor:</b> <span class="text-card-valor">' + vendedor + '</span>';
        html += '<br><b>Valor Total:</b> <span class="text-card-valor">' + valorTotal + '</span>';
        if (item.statusIntegracao == 'NAO_INTEGRADO'){
            html += '<i id="informacoesAutorizacao-'+id+'" class="fa fa-circle iconeExibirInfoAutorizacao btnInfo" title="Não Integrado" style="font-size: 20px; color: red;margin-top: 10px;"></i>';
        }else if (item.statusIntegracao == 'INTEGRADO'){
            html += '<i id="informacoesAutorizacao-'+id+'" class="fa fa-circle iconeExibirInfoAutorizacao btnInfo" title="Integrado" style="font-size: 20px; color: #ff7300;margin-top: 10px;"></i>';
        }else if (item.statusIntegracao == 'ATUALIZADO'){
            html += '<i id="informacoesAutorizacao-'+id+'" class="fa fa-circle iconeExibirInfoAutorizacao btnInfo" title="Atualizado" style="font-size: 20px; color: #06a9f6;margin-top: 10px;"></i>';
        }else if (item.statusIntegracao == 'FATURADO'){
            html += '<i id="informacoesAutorizacao-'+id+'" class="fa fa-circle iconeExibirInfoAutorizacao btnInfo" title="Faturado" style="font-size: 20px; color: green;margin-top: 10px;"></i>';
        }
        html += '</div>';
        html += '</div>';

        return html;
    }

    $('#modalInformacoesAutorizacaoKanban').on('hidden.bs.modal', function () {
        $('#spanIdAutorizacao').html('');
        $('#spanAfAutorizacao').html('');
        $('#spanNomeClienteAutorizacao').html('');
        $('#spanCnpjClienteAutorizacao').html('');
        $('#spanNomeVendedorAutorizacao').html('');
        $('#spanValorTotalAutorizacao').html('');
        $('#spanIdPropostaAutorizacao').html('');
        $('#spanStatusIntegracaoAutorizacao').html('');
        $('#spanNomeAutorizadorAutorizacao').html('');
        $('#spanDocumentoAutorizadorAutorizacao').html('');
        $('#spanEmailAutorizadorAutorizacao').html('');
        $('#spanTelefoneAutorizadorAutorizacao').html('');
        $('#spanStatusAutorizacao').html('');
        $('#spanDataAutorizacao').html('');
        $('#spanObservacaoAutorizacao1').html('');
});

    $('#formBuscaKanbanPropostas').submit(function(e) {
        e.preventDefault();
        let route = RouterController + '/buscarPropostaPorDocumentoVendedorData';
        let dados = $(this).serialize();
        let params = new URLSearchParams(dados);
        let documento = params.get('documento');
        let documentoFormatado = documento ?  documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
        let dataInicial = new Date($('#buscaDataInicialKanbanPropostas').val());
        let dataFinal = new Date($('#buscaDataFinalKanbanPropostas').val());
        let propostaObj = {};
    
        $.each(dados.split('&'), function (index, value) {
            let pair = value.split('=');
            propostaObj[pair[0]] = decodeURIComponent(pair[1] || '');
    
        });
    
        propostaObj['dataInicial'] = propostaObj['dataInicial'] ? formataDataInserir(propostaObj['dataInicial']) : '';
        propostaObj['dataFinal'] = propostaObj['dataFinal'] ? formataDataInserir(propostaObj['dataFinal']) : '';
        dados = $.param(propostaObj);
    
        if (documento){
            if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
                showAlert('warning', 'Documento inválido.');
                return;
            }
        }else if (dataInicial > dataFinal){
            showAlert('warning', 'Data inicial não pode ser maior que a data final.');
            return;
        }
    
        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: dados,
            dataType: 'json',
            beforeSend: function() {
                showLoadingButtonFiltro('BtnPesquisarKanbanPropostas');
                $('.kanban-cards-propostas').html('');
                $('.kanban-count-gerenciamento').html('<i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>');
                adicionarCartoesVazios(50);
            },
            success: function(data) {
                if (data.status == 200){
                    preencheColunasKanban(data);
                }else if (data.status == 404 && data.resultado.mensagem){
                    $('.kanban-count-gerenciamento').html('0');
                    $('.kanban-cards-propostas').html('');
                }else{
                    $('.kanban-count-gerenciamento').html('0');
                    $('.kanban-cards-propostas').html('');
                    if (data['resultado']){
                        validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da proposta');
                    }else{
                        showAlert('error', 'Não foi possível buscar os dados da proposta. Tente novamente.');
                    }
                }
            },
            error: function(data) {
                $('.kanban-count-gerenciamento').html('0');
                $('.kanban-cards-propostas').html('');
                showAlert('error', 'Não foi possível buscar os dados da proposta. Tente novamente.');
                resetLoadingButtonFiltro('BtnPesquisarKanbanPropostas');
            },
            complete: function() {
                resetLoadingButtonFiltro('BtnPesquisarKanbanPropostas');
            }
        })
    });

    $('#formBuscaOportunidade').submit(function(e) {
        e.preventDefault();
        let dados = $(this).serialize();
        let params = new URLSearchParams(dados);
        let documento = params.get('documento');
        let documentoFormatado = documento ?  documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
        let options = '';
        let dataInicial = new Date($('#buscaDataInicialOportunidade').val());
        let dataFinal = new Date($('#buscaDataFinalOportunidade').val());
        let oportunidadeObj = {};


        showLoadingButtonFiltro('BtnPesquisarOportunidade');
        if (documento){
            if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
                showAlert('warning', 'Documento inválido.');
                resetLoadingButtonFiltro('BtnPesquisarOportunidade');
                return;
            }
        }else if (dataInicial > dataFinal){
            showAlert('warning', 'Data inicial não pode ser maior que a data final.');
            resetLoadingButtonFiltro('BtnPesquisarOportunidade');
            return;
        }

        $.each(dados.split('&'), function (index, value) {
            let pair = value.split('=');
            oportunidadeObj[pair[0]] = decodeURIComponent(pair[1] || '');
        });

        oportunidadeObj['dataInicial'] = oportunidadeObj['dataInicial'] ? formataDataInserir(oportunidadeObj['dataInicial']) : '';
        oportunidadeObj['dataFinal'] = oportunidadeObj['dataFinal'] ? formataDataInserir(oportunidadeObj['dataFinal']) : '';
        dados = $.param(oportunidadeObj);

        options = {
            documentoCliente: documentoFormatado,
            idVendedor: oportunidadeObj['idVendedor'],
            dataInicial: oportunidadeObj['dataInicial'],
            dataFinal: oportunidadeObj['dataFinal']
        }
    
        atualizarAgGridOportunidades(options);
    });

    $('#formBuscaKanbanAutorizacaoFaturamento').submit(function(e) {
        e.preventDefault();
        let route = RouterController + '/buscarAutorizacaoPorParametros';
        let dados = $(this).serialize();
        let params = new URLSearchParams(dados);
        let documento = params.get('documento');
        let idProposta = params.get('idProposta');
        let idVendedor = params.get('idVendedor');
        let documentoFormatado = documento ?  documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';


        if (!idProposta && !idVendedor && !documento){
            showAlert('warning', 'Preencha, pelo menos, um campo para realizar a busca.');
            return;
        }
        else if (documento){
            if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
                showAlert('warning', 'Documento inválido.');
                return;
            }
        }

        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: dados,
            dataType: 'json',
            beforeSend: function() {
                showLoadingButtonFiltro('BtnPesquisarKanbanAutorizacaoFaturamento');
                $('.kanban-cards-autorizacao').html('');
                $('.kanban-count-autorizacao').html('<i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>');
                adicionarCartoesVaziosAutorizacao(50);
            },
            success: function(data) {
                if (data.status == 200){
                    preencheColunasKanbanAutorizacao(data);
                }else if (data.status == 404 && data.resultado.mensagem){
                    $('.kanban-count-autorizacao').html('0');
                    $('.kanban-cards-autorizacao').html('');
                }else{
                    $('.kanban-count-autorizacao').html('0');
                    $('.kanban-cards-autorizacao').html('');
                    if (data['resultado']){
                        validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da autorização de faturamento');
                    }else{
                        showAlert('error', 'Não foi possível buscar os dados da autorização de faturamento. Tente novamente.');
                    }
                }
            },
            error: function(data) {
                $('.kanban-count-autorizacao').html('0');
                $('.kanban-cards-autorizacao').html('');
                showAlert('error', 'Não foi possível buscar os dados da autorização de faturamento. Tente novamente.');
                resetLoadingButtonFiltro('BtnPesquisarKanbanAutorizacaoFaturamento');
            },
            complete: function() {
                resetLoadingButtonFiltro('BtnPesquisarKanbanAutorizacaoFaturamento');
            }
        })
    });

    $('#BtnLimparKanbanPropostas').click(function() {
        showLoadingButtonLimpar('BtnLimparKanbanPropostas');
        $('#buscaDocumentoKanbanPropostas').val('');
        $('#buscaVendedorKanbanPropostas').val('').trigger('change');
        $('#buscaDataInicialKanbanPropostas').val('');
        $('#buscaDataFinalKanbanPropostas').val('');
        carregarKanbanPropostas('/buscarPropostasTop100');
    });

    $('#BtnLimparKanbanAutorizacaoFaturamento').click(function() {
        showLoadingButtonLimpar('BtnLimparKanbanAutorizacaoFaturamento');
        $('#buscaIdKanbanAutorizacaoFaturamento').val('');
        $('#buscaVendedorKanbanAutorizacaoFaturamento').val('').trigger('change');
        $('#buscaDocumentoKanbanAutorizacaoFaturamento').val('');
        carregarKanbanAutorizacao('/buscarAutorizacoes'); //Substituir o parâmetro pela função que busca as autorizações
    });

    $('#btnAdicionaNovoProdutoComposicao').on('click', function() {
        let idProduto = $(this).data('idProduto');
        $('#idProdutoPrincipal').val(idProduto);
        $('#produtoItemComposicaoEdit').val('').trigger('change');
        $('#modalItemComposicao').modal('show');
    });

    $('#modalItemComposicao').on('hidden.bs.modal', function() {
        $('#idProdutoPrincipal').val('');
        $('#produtoItemComposicaoEdit').val('').trigger('change');
        $('body').addClass('modal-open');
    });

    $('#formItensComposicaoEdit').submit(function(event) {
        event.preventDefault();
        let dados = $(this).serialize();
        let idProdutoPrincipal = $('#idProdutoPrincipal').val();

        if (!idProdutoPrincipal) {
            showAlert('warning', 'Não foi possível identificar o id do produto editado. Tente novamente!');
            return;
        }
        
        $.ajax({
            cache: false,
            url: RouterController + '/cadastrarProdutoComposicao',
            type: 'POST',
            data: dados,
            dataType: 'json',
            beforeSend: function() {
                showLoadingSalvarButton('btnSalvarItensComposicaoEdit');
            },
            success: function(data) {
                if (data.status == 200){
                    showAlert('success', 'Produto adicionado à composição!');
                    getComposicaoByIdProduto(idProdutoPrincipal);
                    $('#modalItemComposicao').modal('hide');
                }else if ((data.status == 404 || data.status == 400) && data['resultado'] && 'mensagem' in data.resultado){
                    let mensagem = data.resultado.mensagem.includes('existe no banco') ? 'Esse produto já foi adicionado à composição. Verifique seu status na tabela!' : data.resultado.mensagem;
                    showAlert('warning', mensagem)
                }else{
                    showAlert('error', 'Não foi possível adicionar o produto à composição. Tente novamente.');
                }
            },
            error: function(data) {
                showAlert('error', 'Não foi possível adicionar o produto à composição. Tente novamente.');
                resetSalvarButton('btnSalvarItensComposicaoEdit');
            },
            complete: function() {
                resetSalvarButton('btnSalvarItensComposicaoEdit');
            }
        })
    });
});
 
//Requisições
function getProdutoByID(id, status){
    $('#idProduto').val(id);
    if (status == "Ativo"){
        $('#statusProduto').val(1);
    }else{
        $('#statusProduto').val(0);
    }

    let route = RouterController + '/buscarProdutoPorId';
    ShowLoadingScreen();

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {id: id, exibeIdCrm: 1},
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                preencherProduto(data['resultado'][0]);
                if (!data['resultado'][0].idCrm) {
                    showAlert('warning', 'É necessário incluir o ID CRM neste produto.');
                }
                $.ajax({
                    url: RouterController + '/buscarComposicaoPorId',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    beforeSend: function() {
                        AgGridItensComposicao.gridOptions.api.showLoadingOverlay();
                    },
                    success: function(data) {
                        if (data.status == 200) {
                            dadosTabela =  data.resultado.map(function (dado, index) {
                                return {
                                    id: dado.idProdutoComposicao,
                                    idProduto: dado.idProdutoPrincial,
                                    idComposicao: dado.id,
                                    produto: dado.produto.nome,
                                    status: dado?.status,
                                };
                            })
                            atualizarAgGridItensComposicao(dadosTabela, true);
                        }else if (data.status == 404 || data.status == 400) {
                            atualizarAgGridItensComposicao([], true);
                        } else {
                            showAlert('error', 'Não foi possível listar as composições do produto. Tente novamente.');
                        } 
                        HideLoadingScreen();
                    },
                    error: function(error) {
                        showAlert('error', 'Não foi possível listar as composições do produto. Tente novamente.');
                        HideLoadingScreen();
                    }
                });
            }else if (data.status == 404 && data.resultado.mensagem){
                showAlert('warning', 'Dados do produto não encontrados.');
                HideLoadingScreen();
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados do produto');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados do produto. Tente novamente.');
                }
                HideLoadingScreen();
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados do produto. Tente novamente.');
            HideLoadingScreen();
        },
    });
}

function getComposicaoByIdProduto(id) {
    $.ajax({
        url: RouterController + '/buscarComposicaoPorId',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        beforeSend: function() {
            AgGridItensComposicao.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabela =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.idProdutoComposicao,
                        idProduto: dado.idProdutoPrincial,
                        idComposicao: dado.id,
                        produto: dado.produto.nome,
                        status: dado?.status
                    };
                })
                atualizarAgGridItensComposicao(dadosTabela, true);
            }else if (data.status == 404 || data.status == 400) {
                atualizarAgGridItensComposicao([], true);
            } else {
                showAlert('error', 'Não foi possível listar as composições do produto. Tente novamente.');
                atualizarAgGridItensComposicao([], true);
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível listar as composições do produto. Tente novamente.');
            atualizarAgGridItensComposicao([], true);
        }
    });
}

function getPropostaByID(id, status, af, quantidadeTotal){
    $('#idProposta').val(id);
    if (status == "Ativo"){
        $('#statusProposta').val(1);
    }else{
        $('#statusProposta').val(0);
    }
    $('#afProposta').val(af);
    $('#quantidadeTotalProposta').val(quantidadeTotal);

    let route = RouterController + '/buscarPropostaPorId';
    ShowLoadingScreen();

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                preencherProposta(data['resultado'][0]);
            }else if (data.status == 404 && data.resultado.mensagem){
                showAlert('warning', 'Dados da proposta não encontrados.');
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da proposta');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados da proposta. Tente novamente.');
                }
                HideLoadingScreen();
            } 
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados da proposta. Tente novamente.');
            HideLoadingScreen();
        },
    });

    $.ajax({
        cache: false,
        url: RouterController + '/buscarItensPropostaPorIdProposta',
        type: 'POST',
        data: {idProposta: id},
        dataType: 'json',
        beforeSend: function() {
            AgGridItensPropostaEdit.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaItensPropostas =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        nomeProduto: dado.nomeProduto,
                        quantidade: dado.quantidade,
                        valorUnitario: dado.valorUnitario ? (dado.valorUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        percentualDesconto: dado.percentualDesconto ? dado.percentualDesconto + '%' : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        valorDesconto: dado.valorDesconto ? (dado.valorDesconto).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        observacao: dado.observacao ? dado.observacao : '-',
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                })
                atualizarAgGridItensPropostaEdit(dadosTabelaItensPropostas);
            }else{
                AgGridItensPropostaEdit.gridOptions.api.hideOverlay();
                AgGridItensPropostaEdit.gridOptions.api.setRowData([]);
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível listar os itens da proposta. Tente novamente.');
            AgGridItensPropostaEdit.gridOptions.api.hideOverlay();
            AgGridItensPropostaEdit.gridOptions.api.setRowData([]);
        },
    });
}

function getItemPropostaByID(id){
    let route = RouterController + '/buscarItemPropostaPorId';
    ShowLoadingScreen();

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                preencherItemProposta(data['resultado'][0]);
            }else if (data.status == 404 && data.resultado.mensagem){
                showAlert('warning', 'Dados do item da proposta não encontrados.');
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados do item da proposta');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados do item da proposta. Tente novamente.');
                }
                HideLoadingScreen();
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados do item da proposta. Tente novamente.');
            HideLoadingScreen();
        },
    });
}

function getOportunidadeByID(id){
    $('#idOportunidade').val(id);

    let route = RouterController + '/buscarOportunidadePorId';
    ShowLoadingScreen();

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                preencherOportunidade(data['resultado'][0]);
            }else if (data.status == 404 && data.resultado.mensagem){
                showAlert('warning', 'Dados da oportunidade não encontrados.');
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da oportunidade');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados da oportunidade. Tente novamente.');
                }
                HideLoadingScreen();
            } 
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados da oportunidade. Tente novamente.');
            HideLoadingScreen();
        },
    });

    $.ajax({
        cache: false,
        url: RouterController + '/buscarItensOportunidadePorIdOportunidade',
        type: 'POST',
        data: {idOportunidade: id},
        dataType: 'json',
        beforeSend: function() {
            AgGridItensOportunidadeEdit.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaItensOportunidades =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        nomeProduto: dado.nomeProduto,
                        quantidade: dado.quantidade,
                        valorUnitario: dado.valorUnitario ? (dado.valorUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        percentualDesconto: dado.percentualDesconto ? dado.percentualDesconto + '%' : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        valorDesconto: dado.valorDesconto ? (dado.valorDesconto).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        observacao: dado.observacao ? dado.observacao : '-',
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                })
                atualizarAgGridItensOportunidadeEdit(dadosTabelaItensOportunidades);
            }else{
                AgGridItensOportunidadeEdit.gridOptions.api.hideOverlay();
                AgGridItensOportunidadeEdit.gridOptions.api.setRowData([]);
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível listar os itens da oportunidade. Tente novamente.');
            AgGridItensOportunidadeEdit.gridOptions.api.hideOverlay();
            AgGridItensOportunidadeEdit.gridOptions.api.setRowData([]);
        },
    });
}

function getItemOportunidadeByID(id){
    let route = RouterController + '/buscarItemOportunidadePorId';
    ShowLoadingScreen();

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                preencherItemOportunidade(data['resultado']);
            }else if (data.status == 404 && data.resultado.mensagem){
                showAlert('warning', 'Dados do item da oportunidade não encontrados.');
                HideLoadingScreen();
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados do item da oportunidade');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados do item da oportunidade. Tente novamente.');
                }
                HideLoadingScreen();
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados do item da oportunidade. Tente novamente.');
            HideLoadingScreen();
        }
    });
}
function getClienteByID(id, status){
}

$('#formBusca').submit(function(e) {
    e.preventDefault();
    let route = RouterController + '/buscarProdutoPorIdOuNome';
    let dados = $(this).serialize() + '&exibeIdCrm=1';
    let params = new URLSearchParams(dados);
    let tipoBuscaParam = params.get('selectTipoBuscaProduto');
    let idParam = params.get('id');
    let nomeParam = params.get('nome');

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        beforeSend: function() {
            AgGridProdutos.gridOptions.api.showLoadingOverlay();
            showLoadingButtonFiltro('BtnPesquisar');
        },
        success: function(data) {
            if (data.status == 200) {
                teveBusca = true;
                tipoBusca = tipoBuscaParam;
                dadoBusca = idParam ? idParam : nomeParam;

                dadosTabela =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        idCrm: dado.idCrm,
                        nomeProduto: dado.nomeProduto,
                        precoUnitario: dado.precoUnitario ? (dado.precoUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        validaQuantidade: dado.validaQuantidade == 1 ? 'Sim' : 'Não',
                        quantidadeMinima: dado.quantidadeMinima ? dado.quantidadeMinima : '-',
                        quantidadeMaxima: dado.quantidadeMaxima ? dado.quantidadeMaxima : '-',
                        tipoProduto: dado.tipoProduto == 0 ? 'Software' : dado.tipoProduto == 1 ? 'Hardware' : 'Acessório',
                        temComposicao: dado.temComposicao == 1 ? 'Sim' : 'Não',
                        status: dado?.status,
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                })
                atualizarAgGridProdutos(dadosTabela);
            }else if (data.status == 404){
                atualizarAgGridProdutos();
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados do produto');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados do(s) produto(s). Tente novamente.');
                }
                atualizarAgGridProdutos();
            } 
            
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados do(s) produto(s). Tente novamente.');
            atualizarAgGridProdutos();
            resetLoadingButtonFiltro('BtnPesquisar');
        },
        complete: function() {
            resetLoadingButtonFiltro('BtnPesquisar');
        }
    });
});

$('#formBuscaProposta').submit(function(e) {
    e.preventDefault();
    let route = RouterController + '/buscarPropostaPorDocumentoVendedorData';
    let dados = $(this).serialize();
    let params = new URLSearchParams(dados);
    let idVendedor = params.get('idVendedor');
    let documento = params.get('documento');
    let documentoFormatado = documento ?  documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
    let dataInicial = new Date($('#buscaDataInicialProposta').val());
    let dataFinal = new Date($('#buscaDataFinalProposta').val());
    let propostaObj = {};

    $.each(dados.split('&'), function (index, value) {
        let pair = value.split('=');
        propostaObj[pair[0]] = decodeURIComponent(pair[1] || '');

    });

    propostaObj['dataInicial'] = propostaObj['dataInicial'] ? formataDataInserir(propostaObj['dataInicial']) : '';
    propostaObj['dataFinal'] = propostaObj['dataFinal'] ? formataDataInserir(propostaObj['dataFinal']) : '';
    dados = $.param(propostaObj);

    if (documento){
        if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
            showAlert('warning', 'Documento inválido.');
            return;
        }
    }else if (dataInicial > dataFinal){
        showAlert('warning', 'Data inicial não pode ser maior que a data final.');
        return;
    }

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        beforeSend: function() {
            AgGridPropostas.gridOptions.api.showLoadingOverlay();
            showLoadingButtonFiltro('BtnPesquisarProposta');
        },
        success: function(data) {
            if (data.status == 200) {
                teveBuscaProposta = true;
                paramsBuscaProposta = dados;

               dadosTabelaPropostas =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        idCliente: dado.idCliente,
                        nomeCliente: dado.nomeCliente,
                        idVendedor: dado.idVendedor,
                        nomeVendedor: dado.nomeVendedor,
                        af: dado.af ? dado.af : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        quantidadeTotal: dado.quantidadeTotal,
                        formaPagamento: dado.formaPagamento,
                        recorrencia: dado.recorrencia == 0 ? 'Não recorrente' : 'Recorrente',
                        statusIntegracao: dado.statusIntegracao == "ATUALIZADO" ? 'Atualizado' : dado.statusIntegracao == 'FATURADO' ? 'Faturado' : dado.statusIntegracao == 'INTEGRADO' ? 'Integrado' : dado.statusIntegracao == 'NAO_INTEGRADO' ? 'Não integrado' : '-',
                        enderecoFatura: dado.enderecoFatura == 0 ? 'Não tem endereço fatura' : 'Tem endereço fatura',
                        enderecoPagamento: dado.enderecoPagamento == 0 ? 'Não tem endereço pagamento' : 'Tem endereço pagamento',
                        status: dado?.status,
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                        dataVencimento: dado.dataVencimento ? formataDataInserir(dado.dataVencimento) : '-',
                        diaVencimento: dado.diaVencimento ? dado.diaVencimento : '-',
                    };
                })
                atualizarAgGridPropostas(dadosTabelaPropostas);
            }else if (data.status == 404){
                atualizarAgGridPropostas();
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da proposta');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados da(s) proposta(s). Tente novamente.');
                }
                atualizarAgGridPropostas();
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados da(s) proposta(s). Tente novamente.');
            atualizarAgGridPropostas();
            resetLoadingButtonFiltro('BtnPesquisarProposta');
        },
        complete: function() {
            resetLoadingButtonFiltro('BtnPesquisarProposta');
        }
    });
});

$('#formBuscaAutorizacaoFaturamento').submit(function(e) {
    e.preventDefault();
    let route = RouterController + '/buscarAutorizacaoPorParametros';
    let dados = $(this).serialize();
    let params = new URLSearchParams(dados);
    let documento = params.get('documento');
    let idProposta = params.get('idProposta');
    let idVendedor = params.get('idVendedor');
    let documentoFormatado = documento ?  documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';


    if (!idProposta && !idVendedor && !documento){
        showAlert('warning', 'Preencha, pelo menos, um campo para realizar a busca.');
        return;
    }
    else if (documento){
        if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
            showAlert('warning', 'Documento inválido.');
            return;
        }
    }

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        beforeSend: function() {
            AgGridAutorizacaoFaturamento.gridOptions.api.showLoadingOverlay();
            showLoadingButtonFiltro('BtnPesquisarAutorizacaoFaturamento');
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaAutorizacaoFaturamento =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        idProposta: dado.idProposta,
                        nomeCliente: dado.nomeCliente,
                        cnpjCliente: dado.cnpjCliente,
                        nomeVendedor: dado.nomeVendedor,
                        observacao: dado.observacao ? dado.observacao : '-',
                        dataAutorizacao: dado.dataAutorizacao ? formatDateTime(dado.dataAutorizacao) : '-',
                        nomeAutorizador: dado.nomeAutorizador ? dado.nomeAutorizador : '-',
                        documentoAutorizador: dado.documentoAutorizador ? dado.documentoAutorizador : '-',
                        emailAutorizador: dado.emailAutorizador ? dado.emailAutorizador : '-',
                        statusAutorizacao: dado.statusAutorizacao ? dado.statusAutorizacao : '-',
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                })
                atualizarAgGridAutorizacaoFaturamento(dadosTabelaAutorizacaoFaturamento);
            }else if (data.status == 404){
                atualizarAgGridAutorizacaoFaturamento([]);
            }
            else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da autorização de faturamento');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados da(s) autorização(ões) de faturamento. Tente novamente.');
                }
                atualizarAgGridAutorizacaoFaturamento([]);
            }
        }
        ,
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados da(s) autorização(ões) de faturamento. Tente novamente.');
            atualizarAgGridAutorizacaoFaturamento([]);
            resetLoadingButtonFiltro('BtnPesquisarAutorizacaoFaturamento');
        },
        complete: function() {
            resetLoadingButtonFiltro('BtnPesquisarAutorizacaoFaturamento');
        }
    });
});

$('#formBuscaCliente').submit(function(e) {
    e.preventDefault();
    let dados = $(this).serialize();
    let params = new URLSearchParams(dados);
    let documento = params.get('documento');
    let documentoFormatado = documento ?  documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
    let options = '';

    showLoadingButtonFiltro('BtnPesquisarCliente');
    if (documento){
        if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
            showAlert('warning', 'Documento inválido.');
            resetLoadingButtonFiltro('BtnPesquisarCliente');
            return;
        }
    }

    options = {
        id: params.get('id'),
        nome: params.get('nome'),
        documento: params.get('documento'),
    }

    teveBuscaCliente = true;
    dadoBuscaCliente = options;
    atualizarAgGridClientes(options);
});

async function salvarProduto(){
    let route;
    let id = $('#idProduto').val();
    let diasPOC =  parseInt($('#diasValidadePoc').val());
    let permitePOC = parseInt($('#permitePocProduto').val());
    let temComposicao = parseInt($('#temComposicao').val());
    let produtosComposicaoIds = new Array();

    if (permitePOC && diasPOC > 30) {
        showAlert('warning', 'A quantidade de Dias de Validade da POC não pode ser maior que 30.');
        return;
    }

    if(id){
        route = RouterController + '/editarProduto';
        let composicaoAtual = parseInt($('#btnSalvarProduto').data('temComposicao'));
        if (composicaoAtual == 1 && temComposicao == 0) {
            let confirm = false;
            await Swal.fire({
                title: 'Atenção!',
                text: "Você optou por desabilitar a composição do produto. Como resultado, todas as associações serão desativadas. Tem certeza de que deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {
                    confirm = true;
                } else {
                    confirm = false;
                }
            });

            if (!confirm) {
                return;
            }
        }
    } else {
        route = RouterController + '/cadastrarProduto';

        if (temComposicao) {
            AgGridItensComposicao.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
                produtosComposicaoIds.push(dado.data.id);
            });

            if (produtosComposicaoIds.length == 0){
                showAlert('warning', 'Adicione pelo menos um item à tabela "Composição" ou altere a opção para não ter composição.');
                return;
            }
        }
    }

    showLoadingSalvarButton('btnSalvarProduto');

    let produto = $('#formProduto').serialize();
    let produtoObj = {};

    $.each(produto.split('&'), function (index, value) {
        let pair = value.split('=');
        produtoObj[pair[0]] = decodeURIComponent(pair[1] || '');

    });

    produtoObj['precoUnitario'] = formataInsercao(produtoObj['precoUnitario']);
    if (temComposicao) produtoObj['produtosComposicaoIds'] = produtosComposicaoIds;
    produto = $.param(produtoObj);

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: produto,
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                showAlert('success', data['resultado']['mensagem'] ?? 'Produto salvo com sucesso.');
                $('#modalProduto').modal('hide');

                if (teveBusca) {
                    listarDadosBusca(tipoBusca, dadoBusca);
                }else{
                    listarTop100Produtos();
                }
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' salvar o produto');
                }else{
                    showAlert('error', 'Não foi possível salvar o produto. Tente novamente.');
                }
            }  
        },
        error: function(error) {
            showAlert('error', 'Não foi possível salvar o produto. Tente novamente.');
            resetSalvarButton('btnSalvarProduto');
        },
        complete: function() {
            resetSalvarButton('btnSalvarProduto');
        }
    });
}

function alterarStatusItemComposicao(status, id ,idProduto) {
    let route = RouterController + '/alterarStatusComposicao';
    let statusComposicao = status == 'Ativo' ? 0 : 1;
    let msg = statusComposicao == 0 ? 'inativar' : 'ativar';

    Swal.fire({
        title: 'Deseja realmente '+msg+' o produto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id: id,
                    status: statusComposicao
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', data['resultado']['mensagem'] ?? 'Status alterado com sucesso.');

                        getComposicaoByIdProduto(idProduto);
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' alterar status.');
                        }else{
                            showAlert('error', 'Não foi possível alterar status. Tente novamente.');
                        }
                    } 
                },
                error: function(error) {
                    showAlert('error', 'Não foi possível alterar status. Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function() {
                    HideLoadingScreen();
                }
            });
        }
    });
}

function salvarProposta(){
    let id = $('#idProposta').val();
    let idPropostaCadastrada = '';
    showLoadingSalvarButton('btnSalvarProposta');
    let tipoSalvoCadastro = true;
    
    let route = RouterController + '/cadastrarPropostaEndereco';

    if(id){
        route = RouterController + '/editarPropostaEndereco';
        tipoSalvoCadastro = false;
    }

    let proposta = $('#formProposta').serialize();
    let params = new URLSearchParams(proposta);
    let dataVencimento = params.get('dataVencimento');
    let dataVencimentoFormatada = dataVencimento ? formataDataInserir(dataVencimento) : '';
    let propostaObj = {};

    $.each(proposta.split('&'), function (index, value) {
        let pair = value.split('=');
        propostaObj[pair[0]] = decodeURIComponent(pair[1] || '');

    });

    propostaObj['dataVencimento'] = dataVencimentoFormatada;
    proposta = $.param(propostaObj);

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: proposta,
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                
                if (transformaOportunidade){
                    salvaItensOportunidadeProposta(data['resultado']['idSalvo'], itensOportunidadeGerarProposta)
                    .then((res) => {
                        if (res){
                            showAlert('success', 'Proposta salva com sucesso.');
                            $('#btnSalvarProposta').html('Salvar e continuar').attr('disabled', false);
                            $('#modalProposta').modal('hide');
                            atualizarAgGridOportunidades();
                        }else{
                            showAlert('error', 'Proposta salva, porém não foi possível salvar os itens da proposta.');
                            $('#btnSalvarProposta').html('Salvar e continuar').attr('disabled', false);
                            $('#modalProposta').modal('hide');
                            atualizarAgGridOportunidades();
                        }
                    })
                    .catch((err) => {
                        showAlert('error', 'Proposta salva, porém não foi possível salvar os itens da proposta.');
                        $('#btnSalvarProposta').html('Salvar e continuar').attr('disabled', false);
                        $('#modalProposta').modal('hide');
                        atualizarAgGridOportunidades();
                    });
                }else{
                    showAlert('success', data['resultado']['mensagem'] ?? 'Proposta salva com sucesso.');
                    if (tipoSalvoCadastro){
                        $('#tab-itensDaProposta').css('pointer-events', 'auto');
                        $('#tab-itensDaProposta').click();
                        idPropostaCadastrada = data['resultado']['idSalvo'];
                        $('#btnSalvarItensProposta').data('idProposta', idPropostaCadastrada);
                        $('#idProposta').val(idPropostaCadastrada);
                        getPropostaByID(idPropostaCadastrada, 'Ativo', '', '0');
                        if (teveBuscaProposta) {
                            listarDadosBuscaProposta(paramsBuscaProposta);
                        }else{
                            listarTop100Propostas();
                        }
                    }else{
                        if ($('#tab-itensDaProposta').css('display') == 'none'){
                            $('#modalProposta').modal('hide');
                            if (teveBuscaProposta) {
                                listarDadosBuscaProposta(paramsBuscaProposta);
                            }else{
                                listarTop100Propostas();
                            }
                        }else{
                            if (teveBuscaProposta) {
                                listarDadosBuscaProposta(paramsBuscaProposta);
                            }else{
                                listarTop100Propostas();
                            }
                        }
                    }
                }
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' salvar a proposta');
                }else{
                    showAlert('error', 'Não foi possível salvar a proposta. Tente novamente.');
                }
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível salvar a proposta. Tente novamente.');
            if (!transformaOportunidade){
                if ((tipoSalvoCadastro && idPropostaCadastrada) || id){
                    $('#btnSalvarProposta').html('Editar').attr('disabled', false);
                }else{
                    $('#btnSalvarProposta').html('Salvar e continuar').attr('disabled', false);
                }
            }
        },
        complete: function() {
            if (!transformaOportunidade){
                if ((tipoSalvoCadastro && idPropostaCadastrada) || id){
                    $('#btnSalvarProposta').html('Editar').attr('disabled', false);
                }else{
                    $('#btnSalvarProposta').html('Salvar e continuar').attr('disabled', false);
                }   
            }
        }
    });
}

function salvarItemProposta(){
    let id = $('#idItem').val();
    let idProposta = $('#btnSalvarItensPropostaEdit').data('idProposta');
    showLoadingSalvarButton('btnSalvarItensPropostaEdit');

    let route = RouterController + '/cadastrarItensProposta';

    if(id){
        route = RouterController + '/editarItemProposta';
    }

    let itemProposta = $('#formItensPropostaEdit').serialize();
    let itemPropostaObj = {};

    $.each(itemProposta.split('&'), function (index, value) {
        let pair = value.split('=');
        itemPropostaObj[pair[0]] = decodeURIComponent(pair[1] || '');

    });

    itemPropostaObj['valorUnitario'] = formataInsercao((itemPropostaObj['valorUnitario']).replace('R$','').trim());
    itemPropostaObj['valorTotal'] = formataInsercao((itemPropostaObj['valorTotal']).replace('R$','').trim());
    itemPropostaObj['valorDesconto'] = formataInsercao((itemPropostaObj['valorDesconto']).replace('R$','').trim());
    itemPropostaObj['percentualDesconto'] = (((itemPropostaObj['percentualDesconto']).replace('%','').trim()).replace(',', '.'));
    itemProposta = $.param(itemPropostaObj);
    var valorUnitario = itemPropostaObj['valorUnitario'] = formataInsercao((itemPropostaObj['valorUnitario']).replace('R$','').trim());

    if (id){
        if (valorUnitario <= 0){
            showAlert('warning', 'O valor unitário não pode ser menor ou igual a 0.');
            resetSalvarButton('btnSalvarItensPropostaEdit');
            return;
        }
        
    }

    if (itemPropostaObj['percentualDesconto'] && parseFloat(itemPropostaObj['percentualDesconto']) > 100) {
        showAlert('warning', 'O percentual de desconto não pode ser maior que 100%.');
        resetSalvarButton('btnSalvarItensPropostaEdit');
        return;
    }

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: itemProposta,
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                showAlert('success', data['resultado']['mensagem'] ?? 'Item da proposta salvo com sucesso.');
                $('#modalItemProposta').modal('hide');
                listarDadosItensPropostaEdit(idProposta);
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' salvar o item da proposta');
                }else{
                    showAlert('error', 'Não foi possível salvar o item da proposta. Tente novamente.');
                }
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível salvar o item da proposta. Tente novamente.');
            resetSalvarButton('btnSalvarItensPropostaEdit');
        },
        complete: function() {
            resetSalvarButton('btnSalvarItensPropostaEdit');
        }
    });
}

function salvarItemOportunidade(){
    let id = $('#idItemOportunidade').val();
    let idOportunidade = $('#btnSalvarItensOportunidadeEdit').data('idOportunidade');
    showLoadingSalvarButton('btnSalvarItensOportunidadeEdit');

    let route = RouterController + '/cadastrarItensOportunidade';

    if(id){
        route = RouterController + '/editarItemOportunidade';
    }

    let itemOportunidade = $('#formItensOportunidadeEdit').serialize();
    let itemOportunidadeObj = {};

    $.each(itemOportunidade.split('&'), function (index, value) {
        let pair = value.split('=');
        itemOportunidadeObj[pair[0]] = decodeURIComponent(pair[1] || '');

    });

    itemOportunidadeObj['valorUnitario'] = formataInsercao((itemOportunidadeObj['valorUnitario']).replace('R$','').trim());
    itemOportunidadeObj['valorTotal'] = formataInsercao((itemOportunidadeObj['valorTotal']).replace('R$','').trim());
    itemOportunidadeObj['valorDesconto'] = formataInsercao((itemOportunidadeObj['valorDesconto']).replace('R$','').trim());
    itemOportunidadeObj['percentualDesconto'] = (((itemOportunidadeObj['percentualDesconto']).replace('%','').trim()).replace(',', '.'));
    itemOportunidadeObj['idOportunidade'] = idOportunidade;
    itemOportunidade = $.param(itemOportunidadeObj);
    var valorUnitario = itemOportunidadeObj['valorUnitario'] = formataInsercao((itemOportunidadeObj['valorUnitario']).replace('R$','').trim());

    if (id){
        if (valorUnitario <= 0){
            showAlert('warning', 'O valor unitário não pode ser menor ou igual a 0.');
            resetSalvarButton('btnSalvarItensOportunidadeEdit');
            return;
        }
        
    }

    if (itemOportunidadeObj['percentualDesconto'] && (parseFloat(itemOportunidadeObj['percentualDesconto']) > 100)) {
        showAlert('warning', 'O percentual de desconto não pode ser maior que 100%.');
        resetSalvarButton('btnSalvarItensOportunidadeEdit');
        return;
    }

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: itemOportunidade,
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                showAlert('success', data['resultado']['mensagem'] ?? 'Item da oportunidade salvo com sucesso.');
                $('#modalItemOportunidade').modal('hide');
                listarDadosItensOportunidadeEdit(idOportunidade);
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' salvar o item da oportunidade');
                }else{
                    showAlert('error', 'Não foi possível salvar o item da oportunidade. Tente novamente.');
                }
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível salvar o item da oportunidade. Tente novamente.');
            resetSalvarButton('btnSalvarItensOportunidadeEdit');
        },
        complete: function() {
            resetSalvarButton('btnSalvarItensOportunidadeEdit');
        }
    });
}

function salvarCliente(){
    showLoadingSalvarButton('btnSalvarCliente');

    let route = RouterController + '/cadastrarCliente';

    let dados = $('#formCliente').serialize();
    let params = new URLSearchParams(dados);
    let dataNascimento = params.get('dataNascimentoCliente');
    let clienteObj = {};
    let identidade = params.get('identidadeCliente');
    let orgaoExp = params.get('orgaoExpedidor');
    let ufOrgaoExpedidor = params.get('ufOrgaoExpedidor');

    $.each(dados.split('&'), function (index, value) {
        let pair = value.split('=');
        clienteObj[pair[0]] = decodeURIComponent(pair[1] || '');
    });

    if (dataNascimento){
        clienteObj['dataNascimentoCliente'] = formataDataInserir(dataNascimento);
    }

    if (identidade){
        clienteObj['orgaoExpedidor'] = orgaoExp + '/' + ufOrgaoExpedidor;
    }

    dados = $.param(clienteObj);
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                showAlert('success', data['resultado']['mensagem'] ?? 'Cliente salvo com sucesso.');
                $('#modalCliente').modal('hide');
                atualizarAgGridClientes();
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' salvar o cliente');
                }else{
                    showAlert('error', 'Não foi possível salvar o cliente. Tente novamente.');
                }
            }  
        },
        error: function(error) {
            showAlert('error', 'Não foi possível salvar o cliente. Tente novamente.');
            resetSalvarButton('btnSalvarCliente');
        },
        complete: function() {
            resetSalvarButton('btnSalvarCliente');
        }
    });
}

function salvarOportunidade(itensOportunidade){
    let id = $('#idOportunidade').val();

    if ($('#tab-dadosOportunidade').attr('aria-expanded') == "true"){
        showLoadingSalvarButton('btnSalvarOportunidade');
    }else{
        showLoadingSalvarButton('btnSalvarOportunidadeItens');
    }
    
    let route = RouterController + '/cadastrarOportunidade';

    if(id){
        route = RouterController + '/editarOportunidade';
    }

    let oportunidade = $('#formOportunidade').serialize();
    let params = new URLSearchParams(oportunidade);
    let oportunidadeObj = {};

    $.each(oportunidade.split('&'), function (index, value) {
        let pair = value.split('=');
        oportunidadeObj[pair[0]] = decodeURIComponent(pair[1] || '');

    });

    oportunidadeObj['itensOportunidade'] = itensOportunidade;

    oportunidade = $.param(oportunidadeObj);

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: oportunidade,
        dataType: 'json',
        success: function(data) {
            if (data.status == 200 || data.status == 201) {
                showAlert('success', data['resultado']['mensagem'] ?? 'Oportunidade salva com sucesso.');
                atualizarAgGridOportunidades();
                $('#modalOportunidade').modal('hide');
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' salvar a oportunidade');
                }else{
                    showAlert('error', 'Não foi possível salvar a oportunidade. Tente novamente.');
                }
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível salvar a oportunidade. Tente novamente.');
            if ($('#tab-dadosOportunidade').attr('aria-expanded') == "true"){
                resetSalvarButton('btnSalvarOportunidade');
            }else{
                resetSalvarButton('btnSalvarOportunidadeItens');
            }
        },
        complete: function() {
            if ($('#tab-dadosOportunidade').attr('aria-expanded') == "true"){
                resetSalvarButton('btnSalvarOportunidade');
            }else{
                resetSalvarButton('btnSalvarOportunidadeItens');
            } 
        }
    });
}

var AgGridProdutos;
function atualizarAgGridProdutos(dados) {
    stopAgGRIDProdutos();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
            },
            {
                headerName: 'ID CRM',
                field: 'idCrm',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 160,
            },
            {
                headerName: 'Nome',
                field: 'nomeProduto',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Preço Unitário',
                field: 'precoUnitario',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valida Quantidade',
                field: 'validaQuantidade',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Quantidade Mínima',
                field: 'quantidadeMinima',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Quantidade Máxima',
                field: 'quantidadeMaxima',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Tipo Produto',
                field: 'tipoProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Tem Composição',
                field: 'temComposicao',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
            },
            {
                headerName: 'Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let data = options.data;
                    let tabelaNome = 'AgGridProdutos';
                    let botao = data.status == 'Ativo' ? 
                    `<button class="dropdown-item-acoes" onclick="javascript:alterarStatusProduto(${data.id}, '${data.status}')" style="cursor: pointer"> Inativar</button>` : 
                    `<button class="dropdown-item-acoes" onclick="javascript:alterarStatusProduto(${data.id}, '${data.status}')" style="cursor: pointer"> Ativar</button>`;
                    
                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdown(this, ${data.id}, 'produtos', ${tabelaNome})" class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonProdutos_${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonProdutos_${data.id}" id="dropdown-menu-produtos-${data.id}">
                            <a class="dropdown-item-acoes" id="btnEditarProduto" onclick="javascript:abrirModalProduto(${data.id}, '${data.status}')" style="cursor: pointer"> Editar</a>
                            ${botao}
                        </div>
                    </div>`;
                }      
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableProdutos');
    AgGridProdutos = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
    preencherExportacoes(gridOptions);
    gridOptions.quickFilterText = '';
    document.querySelector('#search-input-show-produtos').addEventListener('input', function() {
        var searchInput = document.querySelector('#search-input-show-produtos');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    document.querySelector('#select-quantidade-por-pagina-produtos').addEventListener('change', function() {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina-produtos').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });
}

var AgGridItensComposicao;
function atualizarAgGridItensComposicao(dados, editFlag) {
    let columnDefs = [];
    if (editFlag) {
        columnDefs = [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 120,
            },
            {
                headerName: 'Produto',
                field: 'produto',
                chartDataType: 'category',
                suppressSizeToFit: true,
                flex: 1,
                minWidth: 300
            },
            {
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 120,
                cellRenderer: function(params) {
                    if (params.value === 'Ativo') {
                        return `<span class="label label-success">Ativo</span>`;
                    } else {
                        return `<span class="label label-default">Inativo</span>`;
                    }
                }
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let idLinha = options.node.rowIndex;
                    let tabelaNome = "AgGridItensComposicao";
                    let id = options.data.idComposicao;
                    let status = options.data.status;
                    let idProduto = options.data.idProduto;
                    let actionButton;
                    if (status == 'Ativo') {
                        actionButton = `<a class="dropdown-item-acoes" id="btnMudarStatusTabela" onclick="javascript:alterarStatusItemComposicao('${status}', '${id}', '${idProduto}')" style="cursor: pointer">Inativar</a>`
                    } else {
                        actionButton = `<a class="dropdown-item-acoes" id="btnMudarStatusTabela" onclick="javascript:alterarStatusItemComposicao('${status}', '${id}', '${idProduto}')" style="cursor: pointer">Ativar</a>`
                    }
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${idLinha}, 'itens-cad', ${tabelaNome})"class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonItensComposicaoCad_${idLinha}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonItensComposicaoCad_${idLinha}" id="dropdown-menu-itens-cad-${idLinha}">
                                ${actionButton}
                            </div>
                        </div>`;
                }
            }, 
        ]
    } else {
        columnDefs = [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 120,
            },
            {
                headerName: 'Produto',
                field: 'produto',
                chartDataType: 'category',
                suppressSizeToFit: true,
                flex: 1,
                minWidth: 300
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let idLinha = options.node.rowIndex;
                    let tabelaNome = "AgGridItensComposicao";
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${idLinha}, 'itens-cad', ${tabelaNome})"class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonItensComposicaoCad_${idLinha}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonItensComposicaoCad_${idLinha}" id="dropdown-menu-itens-cad-${idLinha}">
                                <a class="dropdown-item-acoes" id="btnRemoverItemTabelaComposicao" onclick="javascript:removerItemTabelaComposicao(this, ${idLinha})" style="cursor: pointer">Remover</a>
                            </div>
                        </div>`;
                }
            }, 
        ]
    }
    stopAgGRIDComposicao();
    const gridOptions = {
        columnDefs: columnDefs,
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        getRowId: (params) => params.data.id,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 6,
        localeText: localeText
    };

    var gridDiv = document.querySelector('#tableItensComposicao');
    AgGridItensComposicao = new agGrid.Grid(gridDiv, gridOptions);
    AgGridItensComposicao.gridOptions.api.setRowData(dados);
}


function listarTop100Produtos(){
    teveBusca = false;
    tipoBusca = 0;
    dadoBusca = '';

    $.ajax({
        url: RouterController + '/buscarProdutosTop100',
        type: 'GET',
        data: { exibeIdCrm: 1 },
        dataType: 'json',
        beforeSend: function() {
            AgGridProdutos.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabela =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        idCrm: dado.idCrm,
                        nomeProduto: dado.nomeProduto,
                        precoUnitario: dado.precoUnitario ? (dado.precoUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        validaQuantidade: dado.validaQuantidade == 1 ? 'Sim' : 'Não',
                        quantidadeMinima: dado.quantidadeMinima ? dado.quantidadeMinima : '-',
                        quantidadeMaxima: dado.quantidadeMaxima ? dado.quantidadeMaxima : '-',
                        tipoProduto: dado.tipoProduto == 0 ? 'Software' : dado.tipoProduto == 1 ? 'Hardware' : 'Acessório',
                        temComposicao: dado.temComposicao == 1 ? 'Sim' : 'Não',
                        status: dado?.status,
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                })
                atualizarAgGridProdutos(dadosTabela);
            }else{
                atualizarAgGridProdutos();
            } 
        },
        error: function(error) {
            showAlert('error', 'Não foi possível listar produtos. Tente novamente.');
            atualizarAgGridProdutos();
        }
    });
}

function listarTop100Propostas(){
    teveBuscaProposta = false;
    paramsBuscaProposta = '';
   /*  teveBusca = false;
    tipoBusca = 0;
    dadoBusca = '';
     */
    $.ajax({
        url: RouterController + '/buscarPropostasTop100',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            AgGridPropostas.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaPropostas =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        idCliente: dado.idCliente,
                        nomeCliente: dado.nomeCliente,
                        idVendedor: dado.idVendedor,
                        nomeVendedor: dado.nomeVendedor,
                        af: dado.af ? dado.af : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        quantidadeTotal: dado.quantidadeTotal,
                        formaPagamento: dado.formaPagamento,
                        recorrencia: dado.recorrencia == 0 ? 'Não recorrente' : 'Recorrente',
                        statusIntegracao: dado.statusIntegracao == "ATUALIZADO" ? 'Atualizado' : dado.statusIntegracao == 'FATURADO' ? 'Faturado' : dado.statusIntegracao == 'INTEGRADO' ? 'Integrado' : dado.statusIntegracao == 'NAO_INTEGRADO' ? 'Não integrado' : '-',
                        enderecoFatura: dado.enderecoFatura == 0 ? 'Não tem endereço fatura' : 'Tem endereço fatura',
                        enderecoPagamento: dado.enderecoPagamento == 0 ? 'Não tem endereço pagamento' : 'Tem endereço pagamento',
                        status: dado?.status,
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                        dataVencimento: dado.dataVencimento ? formataDataInserir(dado.dataVencimento) : '-',
                        diaVencimento: dado.diaVencimento ? dado.diaVencimento : '-',
                    };
                })
                atualizarAgGridPropostas(dadosTabelaPropostas);
            }else{
                atualizarAgGridPropostas();
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível listar propostas. Tente novamente.');
            atualizarAgGridPropostas();
            resetLoadingButtonLimpar('BtnLimparProspota');
        },
        complete: function() {
            resetLoadingButtonLimpar('BtnLimparProspota');
        }
    });
}
var AgGridPropostas;
function atualizarAgGridPropostas(dados) {
    stopAgGRIDPropostas();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Vendedor',
                field: 'nomeVendedor',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'AF',
                field: 'af',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Quantidade Total',
                field: 'quantidadeTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Forma de Pagamento',
                field: 'formaPagamento',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Recorrência',
                field: 'recorrencia',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Status Integração',
                field: 'statusIntegracao',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Data do Vencimento da Proposta',
                field: 'dataVencimento',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Dia do Vencimento do Boleto',
                field: 'diaVencimento',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Endereço Fatura',
                field: 'enderecoFatura',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Endereço Pagamento',
                field: 'enderecoPagamento',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Status',
                field: 'status',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
            },
            {
                headerName: 'Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let data = options.data;
                    let statusIntegracao = data.statusIntegracao;
                    let tabelaNome = "AgGridPropostas";
                    let botao = data.status == 'Ativo' ? 
                    `<a class="dropdown-item-acoes" onclick="javascript:alterarStatusProposta(${data.id}, '${data.status}')" style="cursor: pointer"> Inativar</a>` : 
                    `<a class="dropdown-item-acoes" onclick="javascript:alterarStatusProposta(${data.id}, '${data.status}')" style="cursor: pointer"> Ativar</a>`;
                    
                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdown(this, ${data.id}, 'propostas', ${tabelaNome})" class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonPropostas_${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px" ${statusIntegracao == 'Faturado' ? 'disabled' : ''}>
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButton_${data.id}" id="dropdown-menu-propostas-${data.id}">
                            <a class="dropdown-item-acoes" id="btnEditarProposta" onclick="javascript:abrirModalProposta(${data.id}, '${data.status}', '${data.af}', '${data.quantidadeTotal}')" style="cursor: pointer">Editar</a>
                            ${botao}
                        </div>
                    </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
                        width: 100,
                    },
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'normal',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tablePropostas');
    AgGridPropostas = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
    preencherExportacoesPropostas(gridOptions);

    gridOptions.quickFilterText = '';
    document.querySelector('#search-input-show-propostas').addEventListener('input', function() {
        var searchInput = document.querySelector('#search-input-show-propostas');
        gridOptions.api.setQuickFilter(searchInput.value);
    });

    document.querySelector('#select-quantidade-por-pagina-propostas').addEventListener('change', function() {
        var selectedValue = document.querySelector('#select-quantidade-por-pagina-propostas').value;
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });
}

var AgGridItensProposta;
function atualizarAgGridItensProposta(dados) {
    stopAgGRIDItensProposta();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'idProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'Produto',
                field: 'produtoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Produto Principal',
                field: 'produtoComposicao',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value) {
                        return 'Não';
                    } else {
                        return 'Sim';
                    }
                }
            },
            {
                headerName: 'Quantidade',
                field: 'quantidadeItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Unitário',
                field: 'valorUnitarioItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Percentual Desconto',
                field: 'percentualDescontoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valoTotalItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Desconto',
                field: 'valorDescontoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Observação',
                field: 'observacaoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let idLinha = options.node.id;
                    let tabelaNome = "AgGridItensProposta";
                    let dataComposicao = options.data.composicao ? options.data.composicao.length : 0;
                    let prodComposicao = options.data.produtoComposicao ? true : false;
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${idLinha}, 'itens-cad', ${tabelaNome})" ${prodComposicao ? 'disabled' : ''} class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonItensProspostaCad_${idLinha}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonItensProspostaCad_${idLinha}" id="dropdown-menu-itens-cad-${idLinha}">
                                <a class="dropdown-item-acoes" id="btnRemoverItemTabela" onclick="javascript:removerItemTabela(this, ${idLinha}, ${dataComposicao})" style="cursor: pointer">Remover</a>
                            </div>
                        </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
        onRowDataUpdated: function(event) {
            let valorTotal = 0;
            var dadosDaTabela = event.api.getModel().rowsToDisplay.map(rowNode => {
                if (rowNode && 'data' in rowNode && rowNode.data.valoTotalItemTabela) {
                    let valorLimpo = parseFloat(rowNode.data.valoTotalItemTabela.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                    if (!isNaN(valorLimpo)) {
                        valorTotal += valorLimpo;
                    }
                }
            });
            $('#valorTotalPropostaItens').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        },
    };

    var gridDiv = document.querySelector('#tableItensPropostas');
    AgGridItensProposta = new agGrid.Grid(gridDiv, gridOptions);

    $('#tableItensPropostas .ag-paging-panel').prepend(`
        <span class="ag-paging-row-summary-panel" role="info">
            <span>Valor Total: </span>
            <span id="valorTotalPropostaItens" style="font-weight: bold;">R$ 0,00</span>      
        </span>
    `);

    gridOptions.api.setRowData(dados);

    if (dados) {
        let valorTotal = 0;
        dados.forEach((dado, i) => {
            if (dado.valoTotalItemTabela) {
                let valorLimpo = parseFloat(dado.valoTotalItemTabela.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                if (!isNaN(valorLimpo)) {
                    valorTotal += valorLimpo;
                }
            }
        })

        $('#valorTotalPropostaItens').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
}

var AgGridItensPropostaEdit;
function atualizarAgGridItensPropostaEdit(dados) {
    stopAgGRIDItensPropostaEdit();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'Produto',
                field: 'nomeProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Quantidade',
                field: 'quantidade',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Unitário',
                field: 'valorUnitario',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Percentual Desconto',
                field: 'percentualDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Desconto',
                field: 'valorDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Observação',
                field: 'observacao',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
            },
            {
                headerName: 'Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let data = options.data;
                    let tabelaNome = "AgGridItensPropostaEdit";
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${data.id}, 'lista-itens-edit', ${tabelaNome})" class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonListaItensEdit_${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonListaItensEdit_${data.id}" id="dropdown-menu-lista-itens-edit-${data.id}">
                                <a class="dropdown-item-acoes" id="btnEditarItemEdit" onclick="javascript:abrirModalItemEdit(${data.id})" style="cursor: pointer">Editar</a>
                                <a class="dropdown-item-acoes" id="btnInativarItemEdit" onclick="javascript:inativarItemEdit(${data.id})" style="cursor: pointer">Inativar</a>
                            </div>
                        </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
        onRowDataUpdated: function(event) {
            let valorTotal = 0;
            var dadosDaTabela = event.api.getModel().rowsToDisplay.map(rowNode => {
                if (rowNode && 'data' in rowNode && rowNode.data.valorTotal) {
                    let valorLimpo = parseFloat(rowNode.data.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                    if (!isNaN(valorLimpo)) {
                        valorTotal += valorLimpo;
                    }
                }
            });
            $('#valorTotalPropostaItensEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        },
    };

    var gridDiv = document.querySelector('#tableItensPropostasEdit');
    AgGridItensPropostaEdit = new agGrid.Grid(gridDiv, gridOptions);

    $('#tableItensPropostasEdit .ag-paging-panel').prepend(`
        <span class="ag-paging-row-summary-panel" role="info">
            <span>Valor Total: </span>
            <span id="valorTotalPropostaItensEdit" style="font-weight: bold;">R$ 0,00</span>      
        </span>
    `);

    gridOptions.api.setRowData(dados);

    if (dados) {
        let valorTotal = 0;
        dados.forEach((dado, i) => {
            if (dado.valorTotal) {
                let valorLimpo = parseFloat(dado.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());

                if (!isNaN(valorLimpo)) {
                    valorTotal += valorLimpo;
                }
            }
        })

        $('#valorTotalPropostaItensEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
}

var AgGridItensPropostaAddEdit;
function atualizarAgGridItensPropostaAddEdit(dados) {
    stopAgGRIDItensPropostaAddEdit();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'ID',
                field: 'idProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'Produto',
                field: 'nomeProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Produto Principal',
                field: 'produtoComposicao',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value) {
                        return 'Não';
                    } else {
                        return 'Sim';
                    }
                }
            },
            {
                headerName: 'Quantidade',
                field: 'quantidade',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Unitário',
                field: 'valorUnitario',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Percentual Desconto',
                field: 'percentualDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Desconto',
                field: 'valorDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Observação',
                field: 'observacao',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let idLinha = options.node.id;
                    let tabelaNome = "AgGridItensPropostaAddEdit";
                    let dataComposicao = options.data.composicao ? options.data.composicao.length : 0;
                    let prodComposicao = options.data.produtoComposicao ? true : false;
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${idLinha}, 'itens-add-edit', ${tabelaNome})" ${prodComposicao ? 'disabled' : ''} class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonItensAddEdit_${idLinha}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonItensAddEdit_${idLinha}" id="dropdown-menu-itens-add-edit-${idLinha}">
                                <a class="dropdown-item-acoes" id="btnRemoverItemAddEdit" onclick="javascript:removerItemAddEdit(this, ${idLinha}, ${dataComposicao})" style="cursor: pointer">Remover</a>
                            </div>
                        </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
        onRowDataUpdated: function(event) {
            let valorTotal = 0;
            var dadosDaTabela = event.api.getModel().rowsToDisplay.map(rowNode => {
                if (rowNode && 'data' in rowNode && rowNode.data.valorTotal) {
                    let valorLimpo = parseFloat(rowNode.data.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                    if (!isNaN(valorLimpo)) {
                        valorTotal += valorLimpo;
                    }
                }
            });
            $('#valorTotaPropostaItensAddEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        },
    };

    var gridDiv = document.querySelector('#tableItensPropostasAddEdit');
    AgGridItensPropostaAddEdit = new agGrid.Grid(gridDiv, gridOptions);

    $('#tableItensPropostasAddEdit .ag-paging-panel').prepend(`
        <span class="ag-paging-row-summary-panel" role="info">
            <span>Valor Total: </span>
            <span id="valorTotaPropostaItensAddEdit" style="font-weight: bold;">R$ 0,00</span>      
        </span>
    `);

    gridOptions.api.setRowData(dados);

    if (dados) {
        let valorTotal = 0;
        dados.forEach((dado, i) => {
            if (dado.valorTotal) {
                let valorLimpo = parseFloat(dado.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                if (!isNaN(valorLimpo)) {
                    valorTotal += valorLimpo;
                }
            }
        })

        $('#valorTotaPropostaItensAddEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
}

var AgGridAutorizacaoFaturamento;
function atualizarAgGridAutorizacaoFaturamento(dadosTable) {
    stopAgGRIDAutorizacaoFaturamento();

    function getServerSideDados() {
        return {
            getRows: (params) => {
                var route = RouterController + '/buscarTodasAutorizacoesPaginacao';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        numeroPagina: params.request.startRow,
                        tamanhoPagina: params.request.endRow,
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        if (AgGridAutorizacaoFaturamento){
                            AgGridAutorizacaoFaturamento.gridOptions.api.showLoadingOverlay();
                        }
                    },
                    success: function (data) {
                        if (data.success) {
                            var dados = data.resultado;

                            for (let i = 0; i < dados.length; i++) {
                                for (let chave in dados[i]) {
                                    if (dados[i][chave] === null) {
                                        dados[i][chave] = '';
                                    }
                                }

                            }
                            
                            params.success({
                                rowData: dados,
                                rowCount: data.totalLinhas
                            });
                            AgGridAutorizacaoFaturamento.gridOptions.api.hideOverlay();
                        }else if (data.message) {
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridAutorizacaoFaturamento.gridOptions.api.showNoRowsOverlay();
                        }else{
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridAutorizacaoFaturamento.gridOptions.api.showNoRowsOverlay();
                        }
                    },
                    error: function (error) {
                        showAlert('error', "Não foi possível listar os dados de autorização. Tente novamente.")
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        AgGridAutorizacaoFaturamento.gridOptions.api.showNoRowsOverlay();

                    },
                });
            },
        };
    }
    const gridOptions = {
        columnDefs: [

            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                hide: true,
            },
            {
                headerName: 'ID Proposta',
                field: 'idProposta',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 120
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Documento Cliente',
                field: 'cnpjCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Vendedor',
                field: 'nomeVendedor',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Observação',
                field: 'observacao',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Autorizador',
                field: 'nomeAutorizador',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Documento do Autorizador',
                field: 'documentoAutorizador',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'E-mail do Autorizador',
                field: 'emailAutorizador',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Telefone do Autorizador',
                field: 'telefoneAutorizador',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Status da Autorização',
                field: 'statusAutorizacao',
                chartDataType: 'category',
            },
            {
                headerName: 'Data de Autorização',
                field: 'dataAutorizacao',
                chartDataType: 'category',
                cellRenderer: function (options) {
                    if (!dadosTable){
                        return options.value ? formatDateTime(options.value) : '-';
                    }else{
                        return options.value
                    }
                } 
            },
            {
                headerName: 'Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
                cellRenderer: function (options) {
                    if (!dadosTable){
                        return options.value ? formatDateTime(options.value) : '-';
                    }else{
                        return options.value
                    }
                }
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                menuTabs: [],
                cellRenderer: function (options){
                    let data = options.data;
                    let tabelaNome = "AgGridAutorizacaoFaturamento";
                    let statusAutorizacao = data.statusAutorizacao;
                    return `
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonAutorizacoes_${data.id}" onclick="javascript:abrirDropdown(this, ${data.id}, 'autorizacoes', ${tabelaNome})" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px" ${statusAutorizacao == 'AUTORIZADO' ? 'disabled' : ''}>
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-acoes dropdown-autorizacao" aria-labelledby="dropdownMenuButton_${data.id}" id="dropdown-menu-autorizacoes-${data.id}">
                            <a class="dropdown-item-acoes" id="btnEnvioAutorizacao" ${statusAutorizacao == 'AUTORIZADO' ? '' : 'onclick="javascript:enviarAutorizacao(' + data.idProposta + ')"'} style="cursor: pointer">Enviar</a>
                            <a class="dropdown-item-acoes" id="btnVisualizarSugestaoProposta" onclick="javascript:visualizarSugestaoProposta(${data.idProposta})" style="cursor: pointer">Visualizar Sugestões</a>
                        </div>
                    </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: dadosTable ? false : true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
                        width: 100,
                    },
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-autorizacao-faturamento').val()),
        rowModelType: dadosTable ? 'clientSide' : 'serverSide',
        serverSideStoreType: 'partial'
    };

    var gridDiv = document.querySelector('#tableAutorizacaoFaturamento');
    AgGridAutorizacaoFaturamento = new agGrid.Grid(gridDiv, gridOptions);

    $('#select-quantidade-por-pagina-autorizacao-faturamento').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-autorizacao-faturamento').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    
    if (dadosTable) {
        gridOptions.api.setRowData(dadosTable);
    }else{
        let datasource = getServerSideDados();
        gridOptions.api.setServerSideDatasource(datasource);
    }
    preencherExportacoesAutorizacaoFaturamento(gridOptions);
}

var AgGridClientes;
function atualizarAgGridClientes(options) {
    stopAgGRIDClientes();

    function getServerSideDadosCliente() {
        return {
            getRows: (params) => {
                var route = RouterController + '/buscarClientesPorParametrosPaginado';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        numeroPagina: params.request.startRow,
                        tamanhoPagina: params.request.endRow,
                        documento: options ? options.documento : '',
                        nomeCliente: options ? options.nome : '',
                        idCliente: options ? options.id : '',
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        if (AgGridClientes){
                            AgGridClientes.gridOptions.api.showLoadingOverlay();
                        }
                    },
                    success: function (data) {
                        if (data.success) {
                            var dados = data.resultado;

                            dadosTabelaClientes = dados.map(function (dado, index) {
                                return {
                                    id: dado.id,
                                    nome: dado.nome ? dado.nome : '-',
                                    razaoSocial: dado.razaoSocial ? dado.razaoSocial : '-',
                                    documentoCliente: dado.cnpj ? formataCpfCnpjExibicao(dado.cnpj) : dado.cpf ? formataCpfCnpjExibicao(dado.cpf) : '-',
                                    identidade: dado.cnpj ? '-' : dado.identidade ? dado.identidade : '-',
                                    inscricaoEstadual: dado.inscricaoEstadual ? dado.inscricaoEstadual : '-',
                                    telefoneCliente: dado.fone ? formataTelefoneExibirTabela(dado.fone) : dado.cel ? formataTelefoneExibirTabela(dado.cel) : '-',
                                    emailCliente: dado.email ? dado.email : dado.email2 ? dado.email2 : dado.email3 ? dado.email3 : '-',
                                    dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                                    dataHoraAtualizacao: dado.dataHoraAtualizacao ? formatDateTime(dado.dataHoraAtualizacao) : '-',
                                };

                            });

                            params.success({
                                rowData: dadosTabelaClientes,
                                rowCount: data.totalLinhas
                            });
                            AgGridClientes.gridOptions.api.hideOverlay();
                        }else if (data.message) {
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridClientes.gridOptions.api.showNoRowsOverlay();
                        }else{
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridClientes.gridOptions.api.showNoRowsOverlay();
                        }
                    },
                    error: function (error) {
                        showAlert('error', "Não foi possível listar os dados do(s) cliente(s). Tente novamente.")
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        AgGridClientes.gridOptions.api.showNoRowsOverlay();
                        resetLoadingButtonFiltro('BtnPesquisarCliente')
                    },
                    complete: function () {
                        resetLoadingButtonFiltro('BtnPesquisarCliente')
                    }
                });
            },
        };
    }
    const gridOptions = {
        columnDefs: [

            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 120
            },
            {
                headerName: 'Nome',
                field: 'nome',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Razão Social',
                field: 'razaoSocial',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'CPF/CNPJ',
                field: 'documentoCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Identidade',
                field: 'identidade',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Inscrição Estadual',
                field: 'inscricaoEstadual',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Telefone',
                field: 'telefoneCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'E-mail',
                field: 'emailCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Data de Atualização',
                field: 'dataHoraAtualizacao',
                chartDataType: 'category',
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
                        width: 100,
                    },
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-clientes').val()),
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    var gridDiv = document.querySelector('#tableClientes');
    AgGridClientes = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDadosCliente();
    gridOptions.api.setServerSideDatasource(datasource);

    $('#select-quantidade-por-pagina-clientes').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-clientes').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    preencherExportacoesClientes(gridOptions);
}

var AgGridSugestaoProposta;
function atualizarAgGridSugestaoProposta(dados) {
    stopAgGRIDSugestaoProposta();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Autorizador',
                field: 'nomeAutorizador',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 300
            },
            {
                headerName: 'Sugestão',
                field: 'sugestaoPropost',
                chartDataType: 'category',
                suppressSizeToFit: true,
                tooltipField: 'sugestaoPropost',
                flex: 1,
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 200,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
                        width: 100,
                    },
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
    };

    var gridDiv = document.querySelector('#tableSugestaoProposta');
    AgGridSugestaoProposta = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);
}

var AgGridItensPropostaInfo;
function atualizarAgGridItensPropostaInfo(dados) {
    stopAgGRIDItensPropostaInfo();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
            },
            {
                headerName: 'Produto',
                field: 'nomeProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Quantidade',
                field: 'quantidadeProduto',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Unitário',
                field: 'valorUnitario',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Percentual Desconto',
                field: 'percentualDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Desconto',
                field: 'valorDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Observação',
                field: 'observacao',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
            },
            {
                headerName: 'Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
        onRowDataUpdated: function(event) {
            let valorTotal = 0;
            var dadosDaTabela = event.api.getModel().rowsToDisplay.map(rowNode => {
                if (rowNode && 'data' in rowNode && rowNode.data.valorTotal) {
                    let valorLimpo = parseFloat(rowNode.data.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                    if (!isNaN(valorLimpo)) {
                        valorTotal += valorLimpo;
                    }
                }
            });
            $('#valorTotalPropostasInfo').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        },
    };

    var gridDiv = document.querySelector('#tableItensPropostasInfo');
    AgGridItensPropostaInfo = new agGrid.Grid(gridDiv, gridOptions);

    $('#tableItensPropostasInfo .ag-paging-panel').prepend(`
        <span class="ag-paging-row-summary-panel" role="info">
            <span>Valor Total: </span>
            <span id="valorTotalPropostasInfo" style="font-weight: bold;">R$ 0,00</span>      
        </span>
    `);

    gridOptions.api.setRowData(dados);

    if (dados) {
        let valorTotal = 0;
        dados.forEach((dado, i) => {
            if (dado.valorTotal) {
                let valorLimpo = parseFloat(dado.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());

                if (!isNaN(valorLimpo)) {
                    valorTotal += valorLimpo;
                }
            }
        })

        $('#valorTotalPropostasInfo').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
}

function preencherProduto(dados){
    $('#btnSalvarProduto').data('id', dados['id']);
    $('#btnAdicionaNovoProdutoComposicao').data('idProduto', dados['id']);
    $('#btnSalvarProduto').data('temComposicao', dados['temComposicao'] ? dados['temComposicao'] : '0');
    $('#nomeProduto').val(dados['nomeProduto']);
    $('#idCrm').val(dados['idCrm']);
    $('#precoUnitario').val(dados['precoUnitario'].toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace('R$', '').trim());
    $('#validaQuantidade').val(dados['validaQuantidade']).trigger('change');
    $('#quantidadeMinima').val(dados['quantidadeMinima']);
    $('#quantidadeMaxima').val(dados['quantidadeMaxima']);
    $('#tipoProduto').val(dados['tipoProduto']);
    $('#temComposicao').val(dados['temComposicao'] ? dados['temComposicao'] : '0').trigger('change');
}

var AgGridOportunidades;
function atualizarAgGridOportunidades(options) {
    stopAgGRIDOportunidade();

    function getServerSideDadosOportunidades() {
        return {
            getRows: (params) => {
                var route = RouterController + '/buscarOportunidadesPorParametrosPaginado';

                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        itemInicio: params.request.startRow,
                        itemFim: params.request.endRow,
                        idVendedor: options ? options.idVendedor : '',
                        documentoCliente: options ? options.documentoCliente : '',
                        dataInicial: options ? options.dataInicial : '',
                        dataFinal: options ? options.dataFinal : '',
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        if (AgGridOportunidades){
                            AgGridOportunidades.gridOptions.api.showLoadingOverlay();
                        }
                    },
                    success: function (data) {
                        if (data.success) {
                            var dados = data.resultado;

                            dadosTabelaOportunidades = dados.map(function (dado, index) {
                                return {
                                    id: dado.id,
                                    nomeCliente: dado.nomeCliente ? dado.nomeCliente : '-',
                                    documentoCliente: dado.documentoCliente ? formataCpfCnpjExibicao(dado.documentoCliente) : '-',
                                    nomeVendedor: dado.nomeVendedor ? dado.nomeVendedor : '-',
                                    valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                                    formaPagamento: dado.formaPagamento ? dado.formaPagamento : '-',
                                    recorrencia: dado.recorrencia == 1 ? 'Sim' : 'Não',
                                    enderecoFatura: dado.enderecoFatura == 1 ? 'Sim' : 'Não',
                                    enderecoPagto: dado.enderecoPagto == 1 ? 'Sim' : 'Não',
                                    permitePoc: dado.permitePoc == 1 ? 'Sim' : 'Não',
                                    status: dado.status ? dado.status : '-',
                                    dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                                    dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                                    statusOportunidade: dado.statusOportunidade == 'EM_ANDAMENTO' ? 'Em Andamento' : dado.statusOportunidade == 'CANCELADO' ? 'Cancelada' : dado.statusOportunidade == 'REABERTO' ? 'Reaberta' : dado.statusOportunidade == 'PROPOSTA_GERADA' ? 'Proposta Gerada' : '-',
                                    idVendedor: dado.idVendedor,
                                    idCliente: dado.idCliente,
                                    dataVencimento: dado.dataVencimento,
                                    diaVencimento: dado.diaVencimento,
                                    emailCliente: dado.emailCliente ? dado.emailCliente : '',
                                };

                            });

                            params.success({
                                rowData: dadosTabelaOportunidades,
                                rowCount: data.totalLinhas
                            });
                            AgGridOportunidades.gridOptions.api.hideOverlay();
                        }else if (data.message) {
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridOportunidades.gridOptions.api.showNoRowsOverlay();
                        }else{
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGridOportunidades.gridOptions.api.showNoRowsOverlay();
                        }
                    },
                    error: function (error) {
                        showAlert('error', "Não foi possível listar os dados das oportunidades. Tente novamente.")
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                        AgGridOportunidades.gridOptions.api.showNoRowsOverlay();
                        resetLoadingButtonFiltro('BtnPesquisarOportunidade')
                        resetLoadingButtonLimpar('BtnLimparOportunidade')
                    },
                    complete: function () {
                        resetLoadingButtonFiltro('BtnPesquisarOportunidade')
                        resetLoadingButtonLimpar('BtnLimparOportunidade')
                    }
                });
            },
        };
    }
    const gridOptions = {
        columnDefs: [

            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 120
            },
            {
                headerName: 'Cliente',
                field: 'nomeCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Documento Cliente',
                field: 'documentoCliente',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Vendedor',
                field: 'nomeVendedor',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Forma de Pagamento',
                field: 'formaPagamento',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Recorrência',
                field: 'recorrencia',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Endereço de Fatura',
                field: 'enderecoFatura',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Endereço de Pagamento',
                field: 'enderecoPagto',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Permite Poc',
                field: 'permitePoc',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Status',
                field: 'statusOportunidade',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Data de Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Data de Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
                cellRenderer: function (options) {
                    return options.value ? options.value : '-';
                }
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let data = options.data;
                    let tabelaNome = "AgGridOportunidades";
                    let statusOportunidade = data.statusOportunidade;

                    let botoes = '';

                    switch (statusOportunidade) {
                        case 'Em Andamento': 
                            botoes = `<a class="dropdown-item-acoes" id="btnEditarOportunidade" onclick="javascript:abrirModalOportunidade(${data.id})" style="cursor: pointer">Editar</a>
                            <a class="dropdown-item-acoes" id="btnCancelarOportunidade" onclick="javascript:alterarStatusOportunidade(${data.id}, '1', 'cancelar', 'cancelada')" style="cursor: pointer">Cancelar</a>
                            <a class="dropdown-item-acoes" id="gerarProposta" onclick="javascript:gerarProposta(${data.id}, '${data.documentoCliente}', '${data.nomeCliente}', '${data.emailCliente}')" style="cursor: pointer">Gerar Proposta</a>
                            `
                            break;
                        case 'Cancelada':
                            botoes = `<a class="dropdown-item-acoes" id="btnReabrirOportunidade" onclick="javascript:alterarStatusOportunidade(${data.id}, '2', 'reabrir', 'reaberta')" style="cursor: pointer">Reabrir</a>
                            `
                            break;
                        case 'Reaberta':
                            botoes = `<a class="dropdown-item-acoes" id="btnEditarOportunidade" onclick="javascript:abrirModalOportunidade(${data.id})" style="cursor: pointer">Editar</a>
                                     <a class="dropdown-item-acoes" id="btnCancelarOportunidade" onclick="javascript:alterarStatusOportunidade(${data.id}, '1', 'cancelar', 'cancelada')" style="cursor: pointer">Cancelar</a>
                                     <a class="dropdown-item-acoes" id="gerarProposta" onclick="javascript:gerarProposta(${data.id}, '${data.documentoCliente}', '${data.nomeCliente}', '${data.emailCliente}')" style="cursor: pointer">Gerar Proposta</a>
                                     `
                            break;
                        default:
                            botoes = ``
                            break;
                    }

                    return `
                    <div class="dropdown">
                        <button onclick="javascript:abrirDropdown(this, ${data.id}, 'oportunidades', ${tabelaNome})" class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonOportunidades_${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px" ${statusOportunidade == 'Proposta Gerada' ? 'disabled' : ''}>
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonOportunidades_${data.id}" id="dropdown-menu-oportunidades-${data.id}">
                            ${botoes}
                        </div>
                    </div>`;
                }

            }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
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
                        width: 100,
                    },
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: parseInt($('#select-quantidade-por-pagina-oportunidade').val()),
        rowModelType: 'serverSide',
        serverSideStoreType: 'partial'
    };

    var gridDiv = document.querySelector('#tableOportunidades');
    AgGridOportunidades = new agGrid.Grid(gridDiv, gridOptions);
    let datasource = getServerSideDadosOportunidades();
    gridOptions.api.setServerSideDatasource(datasource);

    $('#select-quantidade-por-pagina-oportunidade').change(function () {
        var selectedValue = $('#select-quantidade-por-pagina-oportunidade').val();
        gridOptions.api.paginationSetPageSize(Number(selectedValue));
    });

    preencherExportacoesOportunidades(gridOptions);
}

var AgGridItensOportunidade;
function atualizarAgGridItensOportunidades(dados) {
    stopAgGRIDItensOportunidade();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'idProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'Produto',
                field: 'produtoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Produto Principal',
                field: 'produtoComposicao',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value) {
                        return 'Não';
                    } else {
                        return 'Sim';
                    }
                }
            },
            {
                headerName: 'Quantidade',
                field: 'quantidadeItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Unitário',
                field: 'valorUnitarioItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Percentual Desconto',
                field: 'percentualDescontoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valoTotalItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Desconto',
                field: 'valorDescontoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Observação',
                field: 'observacaoItemTabela',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let idLinha = options.node.id;
                    let tabelaNome = "AgGridItensOportunidade";
                    let dataComposicao = options.data.composicao ? options.data.composicao.length : 0;
                    let prodComposicao = options.data.produtoComposicao ? true : false;
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${idLinha}, 'itens-oportunidade', ${tabelaNome})" ${prodComposicao ? 'disabled' : ''} class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonItensOportunidade_${idLinha}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonItensOportunidade_${idLinha}" id="dropdown-menu-itens-oportunidade-${idLinha}">
                                <a class="dropdown-item-acoes" id="btnRemoverItemTabelaOportunidade" onclick="javascript:removerItemTabelaOportunidade(this, ${idLinha}, ${dataComposicao})" style="cursor: pointer">Remover</a>
                            </div>
                        </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
        onRowDataUpdated: function(event) {
            let valorTotal = 0;
            var dadosDaTabela = event.api.getModel().rowsToDisplay.map(rowNode => {
                if (rowNode && 'data' in rowNode && rowNode.data.valoTotalItemTabela) {
                    let valorLimpo = parseFloat(rowNode.data.valoTotalItemTabela.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                    if (!isNaN(valorLimpo)) {
                        valorTotal += valorLimpo;
                    }
                }
            });
            $('#valorTotalOportunidadeItens').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        },
    };

    var gridDiv = document.querySelector('#tableItensOportunidade');
    AgGridItensOportunidade = new agGrid.Grid(gridDiv, gridOptions);
    
    $('#tableItensOportunidade .ag-paging-panel').prepend(`
        <span class="ag-paging-row-summary-panel" role="info">
            <span>Valor Total: </span>
            <span id="valorTotalOportunidadeItens" style="font-weight: bold;">R$ 0,00</span>      
        </span>
    `);

    gridOptions.api.setRowData(dados);

    if (dados) {
        let valorTotal = 0;
        dados.forEach((dado, i) => {
            if (dado.valoTotalItemTabela) {
                let valorLimpo = parseFloat(dado.valoTotalItemTabela.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                if (!isNaN(valorLimpo)) {
                    valorTotal += valorLimpo;
                }
            }
        })

        $('#valorTotalOportunidadeItens').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
}

var AgGridItensOportunidadeEdit;
function atualizarAgGridItensOportunidadeEdit(dados) {
    stopAgGRIDItensOportunidadeEdit();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'Produto',
                field: 'nomeProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Quantidade',
                field: 'quantidade',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Unitário',
                field: 'valorUnitario',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Percentual Desconto',
                field: 'percentualDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Desconto',
                field: 'valorDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Observação',
                field: 'observacao',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Cadastro',
                field: 'dataCadastro',
                chartDataType: 'category',
            },
            {
                headerName: 'Atualização',
                field: 'dataUpdate',
                chartDataType: 'category',
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let data = options.data;
                    let tabelaNome = "AgGridItensOportunidadeEdit";
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${data.id}, 'lista-itens-edit', ${tabelaNome})" class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonListaItensEdit_${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonListaItensEdit_${data.id}" id="dropdown-menu-lista-itens-edit-${data.id}">
                                <a class="dropdown-item-acoes" id="btnEditarItemEditOportunidade" onclick="javascript:abrirModalItemEditOportunidade(${data.id})" style="cursor: pointer">Editar</a>
                                <a class="dropdown-item-acoes" id="btnInativarItemEditOportunidade" onclick="javascript:inativarItemEditOportunidade(${data.id})" style="cursor: pointer">Inativar</a>
                            </div>
                        </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
        onRowDataUpdated: function(event) {
            let valorTotal = 0;
            var dadosDaTabela = event.api.getModel().rowsToDisplay.map(rowNode => {
                if (rowNode && 'data' in rowNode && rowNode.data.valorTotal) {
                    let valorLimpo = parseFloat(rowNode.data.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                    if (!isNaN(valorLimpo)) {
                        valorTotal += valorLimpo;
                    }
                }
            });
            $('#valorTotalOportunidadeItensEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        },
    };

    var gridDiv = document.querySelector('#tableItensOportunidadeEdit');
    AgGridItensOportunidadeEdit = new agGrid.Grid(gridDiv, gridOptions);

    $('#tableItensOportunidadeEdit .ag-paging-panel').prepend(`
        <span class="ag-paging-row-summary-panel" role="info">
            <span>Valor Total: </span>
            <span id="valorTotalOportunidadeItensEdit" style="font-weight: bold;">R$ 0,00</span>      
        </span>
    `);

    gridOptions.api.setRowData(dados);

    if (dados) {
        let valorTotal = 0;
        dados.forEach((dado, i) => {
            if (dado.valorTotal) {
                let valorLimpo = parseFloat(dado.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());

                if (!isNaN(valorLimpo)) {
                    valorTotal += valorLimpo;
                }
            }
        })

        $('#valorTotalOportunidadeItensEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
}

var AgGridItensOportunidadeAddEdit;
function atualizarAgGridItensOportunidadeAddEdit(dados) {
    stopAgGRIDItensOportunidadeAddEdit();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'ID',
                field: 'idProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
                width: 50,
                hide: true,
            },
            {
                headerName: 'Produto',
                field: 'nomeProduto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Produto Principal',
                field: 'produtoComposicao',
                chartDataType: 'category',
                suppressSizeToFit: true,
                cellRenderer: function (params) {
                    if (params.value) {
                        return 'Não';
                    } else {
                        return 'Sim';
                    }
                }
            },
            {
                headerName: 'Quantidade',
                field: 'quantidade',
                chartDataType: 'category',
                suppressSizeToFit: true
            },
            {
                headerName: 'Valor Unitário',
                field: 'valorUnitario',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Percentual Desconto',
                field: 'percentualDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Total',
                field: 'valorTotal',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Valor Desconto',
                field: 'valorDesconto',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Observação',
                field: 'observacao',
                chartDataType: 'category',
                suppressSizeToFit: true,
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options){
                    let idLinha = options.node.id;
                    let tabelaNome = "AgGridItensOportunidadeAddEdit";
                    let dataComposicao = options.data.composicao ? options.data.composicao.length : 0;
                    let prodComposicao = options.data.produtoComposicao ? true : false;
                    return `
                        <div class="dropdown">
                            <button onclick="javascript:abrirDropdown(this, ${idLinha}, 'itens-add-edit', ${tabelaNome})" ${prodComposicao ? 'disabled' : ''} class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButtonItensAddEdit_${idLinha}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-acoes" aria-labelledby="dropdownMenuButtonItensAddEdit_${idLinha}" id="dropdown-menu-itens-add-edit-${idLinha}">
                                <a class="dropdown-item-acoes" id="btnRemoverItemAddEditOportunidade" onclick="javascript:removerItemAddEditOportunidade(this, ${idLinha}, ${dataComposicao})" style="cursor: pointer">Remover</a>
                            </div>
                        </div>`;
                }
            }, 
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,  
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Aguarde...</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">Nenhum registro para mostrar.</span>',
        popupParent: document.body,
        enableRangeSelection: true,
        enableCharts: true,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        localeText: localeText,
        onRowDataUpdated: function(event) {
            let valorTotal = 0;
            var dadosDaTabela = event.api.getModel().rowsToDisplay.map(rowNode => {
                if (rowNode && 'data' in rowNode && rowNode.data.valorTotal) {
                    let valorLimpo = parseFloat(rowNode.data.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                    if (!isNaN(valorLimpo)) {
                        valorTotal += valorLimpo;
                    }
                }
            });
            $('#valorTotalOportunidadeItensAddEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        },
    };

    var gridDiv = document.querySelector('#tableItensOportunidadeAddEdit');
    AgGridItensOportunidadeAddEdit = new agGrid.Grid(gridDiv, gridOptions);

    $('#tableItensOportunidadeAddEdit .ag-paging-panel').prepend(`
        <span class="ag-paging-row-summary-panel" role="info">
            <span>Valor Total: </span>
            <span id="valorTotalOportunidadeItensAddEdit" style="font-weight: bold;">R$ 0,00</span>      
        </span>
    `);

    gridOptions.api.setRowData(dados);

    if (dados) {
        let valorTotal = 0;
        dados.forEach((dado, i) => {
            if (dado.valorTotal) {
                let valorLimpo = parseFloat(dado.valorTotal.replace('R$', '').replace(/\./g, '').replace(',', '.').trim());
                if (!isNaN(valorLimpo)) {
                    valorTotal += valorLimpo;
                }
            }
        })

        $('#valorTotalOportunidadeItensAddEdit').html(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
}

async function preencherProposta(dados){
    let clienteEdit  = await $.ajax ({
        url: RouterController + '/buscarClientes',
        dataType: 'json',
        delay: 1000,
        type: 'GET',
        data: {q: dados['idCliente']},
        error: function(){
            showAlert('warning', "Não foi possível buscar clientes. Tente novamente.")
        }
    });

    $('#idCliente').select2({
        data: clienteEdit.results,
        placeholder: "Selecione o cliente",
        allowClear: true,
        language: "pt-BR",
        width: '100%'
    });

    $('#idCliente').on('select2:select', function (e) {
        var data = e.params.data;
    });
    $('#btnSalvarProposta').data('id', dados['id']);
    $('#idCliente').val(dados['idCliente']).trigger('change');
    if(permissao){
        await listarVendedores('idVendedor');
        $('#idVendedor').val(dados['idVendedor']).trigger('change');
    }else{
        $('#idVendedor').empty();
        $('#idVendedor').append(`<option value="${dados['idVendedor']}" readonly selected>${dados['nomeVendedor']}</option>`);
    }
    $('#formaPagamento').val(dados['formaPagamento']);
    $('#recorrencia').val(dados['recorrencia']);
    $('#enderecoFatura').val(dados['enderecoFatura']);
    $('#enderecoPagamento').val(dados['enderecoPagto']);
    $('#enderecoFatura').trigger('change');
    $('#enderecoPagamento').trigger('change');
    $('#valorTotalProposta').val(dados['valorTotal']);
    $('#documentoAutorizador').val(dados['documentoAutorizador'] ? dados['documentoAutorizador'] : '');
    $('#emailAutorizador').val(dados['emailAutorizador'] ? dados['emailAutorizador'] : '');
    $('#nomeAutorizador').val(dados['nomeAutorizador'] ? dados['nomeAutorizador'] : '');
    $('#observacaoProposta').val(dados['observacao'] ? dados['observacao'] : '');
    $('#telefoneAutorizador').val(dados['telefoneAutorizador'] ? dados['telefoneAutorizador'] : '');
    $('#dataVencimento').val(dados['dataVencimento'] ? (dados['dataVencimento']) : '');
    $('#diaVencimento').val(dados['diaVencimento'] ? (dados['diaVencimento']) : '');
    $('#permitePOC').val(dados['permitePoc']);
    if (dados['enderecoFatura'] == 1){
        if (dados['enderecoFaturamento'].length){
            preencherEnderecoFaturamento(dados['enderecoFaturamento']);
        }
    }

    if (dados['enderecoPagto'] == 1){
        if (dados['enderecoPagamento'].length){
            preencherEnderecoPagamento(dados['enderecoPagamento']);
        }
    }
    $('#idCliente').select2({
        ajax: {
            url: RouterController + '/buscarClientes',
            dataType: 'json'
        },
        placeholder: "Digite o nome do cliente",
        allowClear: true,
        language: "pt-BR",
    });
    HideLoadingScreen();
}

async function preencherItemProposta(dados){
    if ($('#produtoItemPropostaEdit').data('select2')) {
        $('#produtoItemPropostaEdit').html('').select2('destroy');
    }

    let produtoEdit = await $.ajax({
        url: RouterController + '/listarProdutoSelect2',
        dataType: 'json',
        type: 'GET',
        data: { id: dados['idProduto'], exibeIdCrm: 1 }
    }).then(async (data) => {
        HideLoadingScreen();
        if (data && data.status == 200) {
            if (data.resultado[0].idCrm) {
                return data.resultado;
            } else {
                if (permissaoProduto) {
                    await Swal.fire({
                        title: "Atenção!",
                        text: "Não é possível editar esse item, pois o produto não possui ID CRM. Deseja incluí-lo agora?",
                        icon: "warning",
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: "#007BFF",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Continuar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            abrirModalProduto(data.resultado[0].id, data.resultado[0].status);
                            $('#idCrm').focus();
                        }
                    });
                } else {
                    showAlert('warning', 'O produto deste item não possui ID CRM. Entre em contato com alguém que tenha permissão para editar o cadastro.');
                }
                $('#modalItemProposta').modal('hide');
                return [];
            }
        } else {
            showAlert('warning', "Não foi possível buscar o produto. Tente novamente.");
            $('#modalItemProposta').modal('hide');
            return [];
        }
    }).catch((jqXHR, textStatus, errorThrown) => {
        HideLoadingScreen();
        showAlert('warning', "Não foi possível buscar o produto. Tente novamente.");
        $('#modalItemProposta').modal('hide');
        return [];
    });

    $('#produtoItemPropostaEdit').select2({
        data: produtoEdit,
        placeholder: "Selecione o produto",
        allowClear: true,
        language: "pt-BR",
        width: '100%'
    });

    $('#produtoItemPropostaEdit').on('select2:select', function (e) {
        var data = e.params.data;
    });

    $('#idItem').val(dados['id']);
    $('#produtoItemPropostaEdit').val(dados['idProduto']).trigger('change');
    $('#quantidadeItemPropostaEdit').val(dados['quantidade']);
    $('#valorUnitarioItemPropostaEdit').val(dados['valorUnitario'] ? (dados['valorUnitario']).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 0);
    $('#percentualDescontoItemPropostaEdit').val(dados['percentualDesconto'] ? ((dados['percentualDesconto']).toString().replace('.', ',')) + '%' : 0 + '%');
    $('#valorTotalItemPropostaEdit').val(dados['valorTotal'] ? (dados['valorTotal']).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : "R$ 0,00");
    $('#valorDescontoItemPropostaEdit').val(dados['valorDesconto'] ? (dados['valorDesconto']).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : "R$ 0,00");
    $('#observacaoItemProspotaEdit').val(dados['observacao']);

    $('#produtoItemPropostaEdit').select2({
        ajax: {
            url: RouterController + '/listarProdutoSelect2',
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: (data.status == 200 && "resultado" in data) ? data.resultado : []
                };
            }
        },
        placeholder: "Digite o nome do produto",
        allowClear: true,
        language: "pt-BR",
    });
}

async function preencherItemOportunidade(dados){
    if ($('#produtoItemOportunidadeEdit').data('select2')) {
        $('#produtoItemOportunidadeEdit').html('').select2('destroy');
    }

    let produtoEdit = await $.ajax({
        url: RouterController + '/listarProdutoSelect2',
        dataType: 'json',
        type: 'GET',
        data: { id: dados['idProduto'], exibeIdCrm: 1 }
    }).then(async (data) => {
        HideLoadingScreen();
        if (data && data.status == 200) {
            if (data.resultado[0].idCrm) {
                return data.resultado;
            } else {
                if (permissaoProduto) {
                    await Swal.fire({
                        title: "Atenção!",
                        text: "Não é possível editar esse item, pois o produto não possui ID CRM. Deseja incluí-lo agora?",
                        icon: "warning",
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: "#007BFF",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Continuar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            abrirModalProduto(data.resultado[0].id, data.resultado[0].status);
                            $('#idCrm').focus();
                        }
                    });
                } else {
                    showAlert('warning', 'O produto deste item não possui ID CRM. Entre em contato com alguém que tenha permissão para editar o cadastro.');
                }
                $('#modalItemOportunidade').modal('hide');
                return [];
            }
        } else {
            showAlert('warning', "Não foi possível buscar o produto. Tente novamente.");
            $('#modalItemOportunidade').modal('hide');
            return [];
        }
    }).catch((jqXHR, textStatus, errorThrown) => {
        HideLoadingScreen();
        showAlert('warning', "Não foi possível buscar o produto. Tente novamente.");
        $('#modalItemOportunidade').modal('hide');
        return [];
    });

    $('#produtoItemOportunidadeEdit').select2({
        data: produtoEdit,
        placeholder: "Selecione o produto",
        allowClear: true,
        language: "pt-BR",
        width: '100%'
    });

    $('#produtoItemOportunidadeEdit').on('select2:select', function (e) {
        var data = e.params.data;
    });

    $('#idItemOportunidade').val(dados['id']);
    $('#produtoItemOportunidadeEdit').val(dados['idProduto']).trigger('change');
    $('#quantidadeItemOportunidadeEdit').val(dados['quantidade']);
    $('#valorUnitarioItemOportunidadeEdit').val(dados['valorUnitario'] ? (dados['valorUnitario']).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 0);
    $('#percentualDescontoItemOportunidadeEdit').val(dados['percentualDesconto'] ? ((dados['percentualDesconto']).toString().replace('.', ',')) + '%' : 0 + '%');
    $('#valorTotalItemOportunidadeEdit').val(dados['valorTotal'] ? (dados['valorTotal']).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : "R$ 0,00");
    $('#valorDescontoItemOportunidadeEdit').val(dados['valorDesconto'] ? (dados['valorDesconto']).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : "R$ 0,00");
    $('#observacaoItemOportunidadeEdit').val(dados['observacao']);

    $('#produtoItemOportunidadeEdit').select2({
        ajax: {
            url: RouterController + '/listarProdutoSelect2',
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: (data.status == 200 && "resultado" in data) ? data.resultado : []
                };
            }
        },
        placeholder: "Digite o nome do produto",
        allowClear: true,
        language: "pt-BR",
    });
}
async function preencherOportunidade(dados){
    $('#nomeClienteOportunidade').val(dados['nomeCliente']);
    $('#documentoClienteOportunidade').val(dados['documentoCliente']);
    $('#emailClienteOportunidade').val(dados['emailCliente']);
    $('#idVendedorOportunidade').val(dados['idVendedor']).trigger('change');
    $('#formaPagamentoOportunidade').val('BOLETO');
    $('#recorrenciaOportunidade').val(dados['recorrencia']);
    $('#dataVencimentoOportunidade').val(dados['dataVencimento']);
    $('#diaVencimentoOportunidade').val(dados['diaVencimento']);
    $('#permitePOCOportunidade').val(dados['permitePoc']);
    $('#enderecoPagamentoOportunidade').val(dados['enderecoPagto']);
    $('#enderecoFaturaOportunidade').val(dados['enderecoFatura']);
    $('#enderecoFaturaOportunidade').trigger('change');
    $('#enderecoPagamentoOportunidade').trigger('change');

    if (dados['enderecoFatura'] == 1){
        if (dados['enderecoFaturamento'].length){
            preencherEnderecoFaturamentoOportunidade(dados['enderecoFaturamento']);
        }
    }

    if (dados['enderecoPagto'] == 1){
        if (dados['enderecoPagamento'].length){
            preencherEnderecoPagamentoOportunidade(dados['enderecoPagamento']);
        }
    }
    HideLoadingScreen();
}

$('.btn-expandir').on('click', function(e) {
    e.preventDefault();
    expandirGrid();
});

async function abrirModalGerarPropostaOportunidade(idOportunidade, idCliente, nomeCliente){
    $('#btnSalvarProposta').data('idOportunidade', idOportunidade);

    try{
        let dadosOportunidade = await $.ajax({
            url: RouterController + '/buscarOportunidadePorId',
            dataType: 'json',
            type: 'POST',
            data: {id: idOportunidade},
            error: function(){
                showAlert('warning', "Não foi possível buscar a oportunidade. Tente novamente.");
                HideLoadingScreen();
            },
        });
        let idVendedor = dadosOportunidade['resultado'][0]['idVendedor'];
        let nomeVendedor = dadosOportunidade['resultado'][0]['nomeVendedor'];

        $('#btnSalvarProposta').data('nomeCliente', nomeCliente);
        $('#btnSalvarProposta').data('idCliente', idCliente);
        $('#btnSalvarProposta').data('idVendedor', idVendedor);
        $('#btnSalvarProposta').data('nomeVendedor', nomeVendedor);
        preencheCamposOportunidadeProposta(dadosOportunidade['resultado']);
        $('#idOportunidadeProposta').val(idOportunidade);
        abrirModalProposta();
    }
    catch(error){
        console.log(error);
        showAlert('warning', "Não foi possível buscar a oportunidade. Tente novamente.");
        HideLoadingScreen();
    }
}
function preencheCamposOportunidadeProposta(campos){
    let dadosOportunidade = campos[0];

    $('#formaPagamento').val(dadosOportunidade['formaPagamento']);
    $('#recorrencia').val(dadosOportunidade['recorrencia']);
    $('#permitePOC').val(dadosOportunidade['permitePoc']);
    $('#dataVencimento').val(dadosOportunidade['dataVencimento']);
    $('#diaVencimento').val(dadosOportunidade['diaVencimento']);
    $('#enderecoPagamento').val(dadosOportunidade['enderecoPagto']).trigger('change');
    $('#enderecoFatura').val(dadosOportunidade['enderecoFatura']).trigger('change');

    if (dadosOportunidade['enderecoFatura'] == 1){
        if (dadosOportunidade['enderecoFaturamento'].length){
            preencherEnderecoFaturamento(dadosOportunidade['enderecoFaturamento']);
        }
    }

    if (dadosOportunidade['enderecoPagto'] == 1){
        if (dadosOportunidade['enderecoPagamento'].length){
            preencherEnderecoPagamento(dadosOportunidade['enderecoPagamento']);
        }
    }
}
function alterarStatusProduto(id, status){
    let route = RouterController + '/alterarStatusProduto';
    let statusProduto = status == 'Ativo' ? 0 : 1;
    let msg = statusProduto == 0 ? 'inativar' : 'ativar';

    Swal.fire({
        title: 'Deseja realmente '+msg+' o produto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
        }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id: id,
                    status: statusProduto
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', data['resultado']['mensagem'] ?? 'Status alterado com sucesso.');

                        if (teveBusca) {
                            listarDadosBusca(tipoBusca, dadoBusca);
                        }else{
                            listarTop100Produtos();
                        }
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' alterar status');
                        }else{
                            showAlert('error', 'Não foi possível alterar status. Tente novamente.');
                        }
                    } 
                },
                error: function(error) {
                    showAlert('error', 'Não foi possível alterar status. Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function() {
                    HideLoadingScreen();
                }
            });
        }
    });
    
}

function alterarStatusProposta(id, status){
    let route = RouterController + '/alterarStatusProposta';
    let statusProposta = status == 'Ativo' ? 0 : 1;
    let msg = statusProposta == 0 ? 'inativar' : 'ativar';

    Swal.fire({
        title: 'Deseja realmente '+msg+' a proposta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
        }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id: id,
                    status: statusProposta
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', data['resultado']['mensagem'] ?? 'Status alterado com sucesso.');

                        if (teveBuscaProposta) {
                            listarDadosBuscaProposta(paramsBuscaProposta);
                        }else{
                            listarTop100Propostas();
                        }
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' alterar status');
                        }else{
                            showAlert('error', 'Não foi possível alterar status. Tente novamente.');
                        }
                    }
                },
                error: function(error) {
                    showAlert('error', 'Não foi possível alterar status. Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function() {
                    HideLoadingScreen();
                }
            });
        }
    });
    
}

function inativarItemEdit(id){
    let route = RouterController + '/alterarStatusItemProposta';
    let idProposta = $('#btnSalvarItensPropostaEdit').data('idProposta');

    Swal.fire({
        title: 'Deseja realmente inativar o item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
        }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id: id,
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', 'Item inativado com sucesso.');
                        listarDadosItensPropostaEdit(idProposta);
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' inativar o item');
                        }else{
                            showAlert('error', 'Não foi possível inativar o item. Tente novamente.');
                        }
                    }
                },
                error: function(error) {
                    showAlert('error', 'Não foi possível inativar o item. Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function() {
                    HideLoadingScreen();
                }
            });
        }
    });
    
}

function inativarItemEditOportunidade(id){
    let route = RouterController + '/inativarItemOportunidade';
    let idOportunidade = $('#idOportunidade').val();

    Swal.fire({
        title: 'Deseja realmente inativar o item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
        }).then((result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            $.ajax({
                cache: false,
                url: route,
                type: 'POST',
                data: {
                    id: id,
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', 'Item inativado com sucesso.');
                        listarDadosItensOportunidadeEdit(idOportunidade);
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' inativar o item');
                        }else{
                            showAlert('error', 'Não foi possível inativar o item. Tente novamente.');
                        }
                    }
                },
                error: function(error) {
                    showAlert('error', 'Não foi possível inativar o item. Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function() {
                    HideLoadingScreen();
                }
            });
        }
    });
    
}
function listarDadosBusca(tipoBusca, dadoBusca){
    let route = RouterController + '/buscarProdutoPorIdOuNome';
    let dados = {

        selectTipoBuscaProduto: tipoBusca,
        id: tipoBusca == 0 ? dadoBusca : '',
        nome: tipoBusca == 1 ? dadoBusca : '',
        exibeIdCrm: 1
    }
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: dados,
        dataType: 'json',
        beforeSend: function() {
            AgGridProdutos.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabela =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        idCrm: dado.idCrm,
                        nomeProduto: dado.nomeProduto,
                        precoUnitario: dado.precoUnitario ? (dado.precoUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        validaQuantidade: dado.validaQuantidade == 1 ? 'Sim' : 'Não',
                        quantidadeMinima: dado.quantidadeMinima ? dado.quantidadeMinima : '-',
                        quantidadeMaxima: dado.quantidadeMaxima ? dado.quantidadeMaxima : '-',
                        tipoProduto: dado.tipoProduto == 0 ? 'Software' : dado.tipoProduto == 1 ? 'Hardware' : 'Acessório',
                        temComposicao: dado.temComposicao == 1 ? 'Sim' : 'Não',
                        status: dado?.status,
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                })
                atualizarAgGridProdutos(dadosTabela);
            }else{
                atualizarAgGridProdutos();
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar o(s) produto(s). Tente novamente.');
            atualizarAgGridProdutos();
        }
    });
}

function listarDadosBuscaProposta(dadosBusca){
    let route = RouterController + '/buscarPropostaPorDocumentoVendedorData';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: dadosBusca,
        dataType: 'json',
        beforeSend: function() {
            AgGridPropostas.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaPropostas = data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        idCliente: dado.idCliente,
                        nomeCliente: dado.nomeCliente,
                        idVendedor: dado.idVendedor,
                        nomeVendedor: dado.nomeVendedor,
                        af: dado.af ? dado.af : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        quantidadeTotal: dado.quantidadeTotal,
                        formaPagamento: dado.formaPagamento,
                        recorrencia: dado.recorrencia == 0 ? 'Não recorrente' : 'Recorrente',
                        statusIntegracao: dado.statusIntegracao == "ATUALIZADO" ? 'Atualizado' : dado.statusIntegracao == 'FATURADO' ? 'Faturado' : dado.statusIntegracao == 'INTEGRADO' ? 'Integrado' : dado.statusIntegracao == 'NAO_INTEGRADO' ? 'Não integrado' : '-',
                        enderecoFatura: dado.enderecoFatura == 0 ? 'Não tem endereço fatura' : 'Tem endereço fatura',
                        enderecoPagamento: dado.enderecoPagamento == 0 ? 'Não tem endereço pagamento' : 'Tem endereço pagamento',
                        status: dado?.status,
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                        dataVencimento: dado.dataVencimento ? formataDataInserir(dado.dataVencimento) : '-',
                        diaVencimento: dado.diaVencimento ? dado.diaVencimento : '-',
                    };
                })
                atualizarAgGridPropostas(dadosTabelaPropostas);
            }else{
                atualizarAgGridPropostas();
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar a(s) proposta(s). Tente novamente.');
            atualizarAgGridPropostas();
        }
    });
}

function listarDadosItensPropostaEdit(idProposta){
    let route = RouterController + '/buscarItensPropostaPorIdProposta';

    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {idProposta: idProposta},
        dataType: 'json',
        beforeSend: function() {
            AgGridItensPropostaEdit.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaItensPropostas = data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        nomeProduto: dado.nomeProduto,
                        quantidade: dado.quantidade,
                        valorUnitario: dado.valorUnitario ? (dado.valorUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        percentualDesconto: dado.percentualDesconto ? dado.percentualDesconto + '%' : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        valorDesconto: dado.valorDesconto ? (dado.valorDesconto).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        observacao: dado.observacao ? dado.observacao : '-',
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                });
                atualizarAgGridItensPropostaEdit(dadosTabelaItensPropostas);
            }else{
                atualizarAgGridItensPropostaEdit();
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados do(s) item(ns). Tente novamente.');
            atualizarAgGridItensPropostaEdit();
        }
    });
}

function listarDadosItensOportunidadeEdit(idProposta){
    let route = RouterController + '/buscarItensOportunidadePorIdOportunidade';
    var dadosTabelaItensOportunidadeEdit = [];
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {idOportunidade: idProposta},
        dataType: 'json',
        beforeSend: function() {
            AgGridItensOportunidadeEdit.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaItensOportunidadeEdit = data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        nomeProduto: dado.nomeProduto,
                        quantidade: dado.quantidade,
                        valorUnitario: dado.valorUnitario ? (dado.valorUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        percentualDesconto: dado.percentualDesconto ? dado.percentualDesconto + '%' : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        valorDesconto: dado.valorDesconto ? (dado.valorDesconto).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        observacao: dado.observacao ? dado.observacao : '-',
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                });
                atualizarAgGridItensOportunidadeEdit(dadosTabelaItensOportunidadeEdit);
            }else{
                atualizarAgGridItensOportunidadeEdit();
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados do(s) item(ns). Tente novamente.');
            atualizarAgGridItensOportunidadeEdit();
        }
    });
}

let menuAberto = false;
function expandirGrid(){
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

function abrirModalProduto(id, status){
    $('#btnSalvarProduto').data('temComposicao', '');
    if(id){
        getProdutoByID(id, status);
        $("#titleModalProduto").html('Editar Produto');
        $('.pocDiv').hide();
        $('#produtosComposicao').hide();
        $('#composicaoAddProduto').hide();
        $('#composicaoEditProduto').show();
        $('.pocInput').attr('required',false);
    }else{
        atualizarAgGridItensComposicao();
        HideLoadingScreen();
        $('.pocDiv').show();
        $('.pocInput').attr('required',true);
        $('#composicaoAddProduto').show();
        $('#composicaoEditProduto').hide();
        $("#titleModalProduto").html('Adicionar Produto');
    }

    $("#modalProduto").modal('show');
}
function abrirModalProposta(id, status, af, quantidadeTotal){
    $('#spinnerCliente').hide();
    $('#spanSemEndereco').hide();
    let idVendedor = $('#btnSalvarProposta').data('idVendedor');
    let nomeVendedor = $('#btnSalvarProposta').data('nomeVendedor');
    let idCliente = $('#btnSalvarProposta').data('idCliente');
    let nomeCliente = $('#btnSalvarProposta').data('nomeCliente');

    if(id){
        getPropostaByID(id, status, af, quantidadeTotal);
        $('#btnSalvarItensPropostaEdit').data('idProposta', id);
        $('#btnSalvarItensPropostaEditTabela').data('idProposta', id);
        $("#titleModalProposta").html('Editar Proposta');
        $('#tab-dadosProposta').click();
        $('#tab-itensDaProposta').hide();
        $('#iconeCareRight').hide();
        $('#btnSalvarProposta').html('Editar');
        $('#tab-itensDaPropostaEdit').show();
    }else{
        HideLoadingScreen();
        $('#btnSalvarItensPropostaEdit').data('idProposta', '');
        $('#btnSalvarItensPropostaEditTabela').data('idProposta', '');
        $('#idProposta').val('');
        if ($('#idCliente').data('select2')) {
            $('#idCliente').select2('destroy');
        }
        $('#idCliente').select2({
            ajax: {
                url: RouterController + '/buscarClientes',
                dataType: 'json'
            },
            placeholder: "Digite o nome do cliente",
            allowClear: true,
            language: "pt-BR",
        });
        if (transformaOportunidade){

            $('#idVendedor').empty();
            $('#idVendedor').append(`<option value="${idVendedor}" selected>${nomeVendedor}</option>`);
            $('#idVendedor').attr('readonly', true);
            $('#idCliente').empty();
            $('#idCliente').append(`<option value="${idCliente}" selected>${nomeCliente}</option>`);
            $('#idCliente').attr('readonly', true);
            $('#tab-itensDaProposta').hide();
            $('#tab-itensDaPropostaEdit').hide();
            $('#tab-itensDaProposta').css('pointer-events', 'none');
            $('#iconeCareRight').hide();

        }else{
            $.ajax({
                url: RouterController + '/buscarVendedor',
                dataType: 'json',
                success: function(response){
                    if(response['status'] == 200){
                        $('#idVendedor').empty();
                        $('#idVendedor').append(`<option value="${response["resultado"]["id"]}" selected readonly>${response["resultado"]["nome"]}</option>`);
                    }
                }
            });
            $('#tab-itensDaProposta').show();
            $('#tab-itensDaPropostaEdit').hide();
            $('#tab-itensDaProposta').css('pointer-events', 'none');
            $('#iconeCareRight').show();
        }

        $("#titleModalProposta").html('Adicionar Proposta');
        $('#tab-dadosProposta').click();
        $('#btnSalvarProposta').html('Salvar e continuar');
        enterButton = true;
    }

    
    $("#modalProposta").modal('show');
}

function abrirModalItemEdit(id){
    if(id){
        getItemPropostaByID(id);
        $("#titleModalItemProposta").html('Editar Item');
        $('#btnAdicionarItemTabelaEdit').css('display', 'none');
        $('#divTabelaItensPropostaEdit').hide();
        $('#btnSalvarItensPropostaEdit').css('display', 'block');
        $('#btnSalvarItensPropostaEditTabela').css('display', 'none');
        $('#limparTabelaItensEditProposta').css('display', 'none');

    }else{
        $('#btnAdicionarItemTabelaEdit').css('display', 'block');
        $('#divTabelaItensPropostaEdit').show();
        $('#btnSalvarItensPropostaEditTabela').css('display', 'block');
        $('#btnSalvarItensPropostaEdit').css('display', 'none');
        $('#limparTabelaItensEditProposta').css('display', 'block');
        
        HideLoadingScreen();

        if ($('#produtoItemPropostaEdit').data('select2')) {
            $('#produtoItemPropostaEdit').html('').select2('destroy');
        }
        $('#produtoItemPropostaEdit').select2({
            ajax: {
                url: RouterController + '/listarProdutoSelect2',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: (data.status == 200 && "resultado" in data) ? data.resultado : []
                    };
                }
            },
            placeholder: "Digite o nome do produto",
            allowClear: true,
            language: "pt-BR",
        });
        $("#titleModalItemProposta").html('Adicionar Itens');
    }
    $("#modalItemProposta").modal('show');
    enterButton = true;
}

function abrirModalCliente(id, status){
    if(id){
        getClienteByID(id, status);
        $("#titleModalCliente").html('Editar Cliente');
    }else{
        HideLoadingScreen();
        $("#titleModalCliente").html('Adicionar Cliente');
    }

    $("#modalCliente").modal('show');
}

function abrirModalOportunidade(id, status){
    if(id){
        getOportunidadeByID(id, status);
        $("#titleModalOportunidade").html('Editar Oportunidade');
        $('#tab-dadosOportunidade').click();
        $('#tab-itensDaOportunidadeEdit').show();
        $('#tab-itensDaOportunidade').hide();
        $('#btnSalvarItensOportunidadeEdit').data('idOportunidade', id);
        $('#dataVencimentoOportunidade').removeAttr('min');
    }else{
        HideLoadingScreen();
        $('#dataVencimentoOportunidade').attr('min', getCurrentDate());
        $('#btnSalvarItensOportunidadeEdit').data('idOportunidade', '');
        $("#titleModalOportunidade").html('Adicionar Oportunidade');
        $('#idOportunidade').val('');
        $('#tab-dadosOportunidade').click();
        $('#tab-itensDaOportunidade').show();
        $('#tab-itensDaOportunidadeEdit').hide();
    }

    $("#modalOportunidade").modal('show');
}

function abrirModalCadastroClienteOportunidade(documento, email, nome){
    if (documento.length == 11){
        $('#tipoClienteFisica').prop('checked', true).trigger('change');
        $('#nomeCliente').val(nome != '-' ? nome : '');
        $('#cpfCliente').val(documento).trigger('input');
        $('#emailCliente').val(email ? email : '');
    }else{
        $('#tipoClienteJuridica').prop('checked', true).trigger('change');
        $('#razaoSocialCliente').val(nome ? nome : '');
        $('#cnpjCliente').val(documento).trigger('input');
        $('#emailCliente').val(email ? email : '');
    }

    $('#modalCliente').modal('show');
}
function abrirModalItemEditOportunidade(id){
    if(id){
        getItemOportunidadeByID(id);
        $("#titleModalItemOportunidade").html('Editar Item');
        $('#btnAdicionarItemTabelaEditOportunidade').css('display', 'none');
        $('#divTabelaItensOportunidadeEdit').hide();
        $('#btnSalvarItensOportunidadeEdit').css('display', 'block');
        $('#btnSalvarItensOportunidadeEditTabela').css('display', 'none');
        $('#limparTabelaItensEditOportunidade').css('display', 'none');
    }else{
        $('#btnAdicionarItemTabelaEditOportunidade').css('display', 'block');
        $('#divTabelaItensOportunidadeEdit').show();
        $('#btnSalvarItensOportunidadeEditTabela').css('display', 'block');
        $('#btnSalvarItensOportunidadeEdit').css('display', 'none');
        $('#limparTabelaItensEditOportunidade').css('display', 'block');
        
        HideLoadingScreen();

        if ($('#produtoItemOportunidadeEdit').data('select2')) {
            $('#produtoItemOportunidadeEdit').html('').select2('destroy');
        }
        $('#produtoItemOportunidadeEdit').select2({
            ajax: {
                url: RouterController + '/listarProdutoSelect2',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: (data.status == 200 && "resultado" in data) ? data.resultado : []
                    };
                }
            },
            placeholder: "Digite o nome do produto",
            allowClear: true,
            language: "pt-BR",
        });
        $("#titleModalItemOportunidade").html('Adicionar Itens');
    }
    $("#modalItemOportunidade").modal('show');
    /* enterButton = true; */
}

function visualizarSugestaoProposta(idProposta){
    $('.dropdown-menu').hide();
    let route = RouterController + '/buscarSugestaoPorIdProposta';
    var dadosTabelaSugestaoProposta = [];
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {idProposta: idProposta},
        dataType: 'json',
        beforeSend: function() {
            ShowLoadingScreen();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaSugestaoProposta = data.resultado.map(function (dado, index) {
                    return {
                        nomeAutorizador: dado.nomeAutorizador ? dado.nomeAutorizador : '-',
                        sugestaoPropost: dado.sugestaoPropost ? dado.sugestaoPropost : '-',
                    };
                });
                atualizarAgGridSugestaoProposta(dadosTabelaSugestaoProposta);
                $("#modalSugestaoProposta").modal('show');
            }else if (data.status == 404){
                showAlert('warning', 'Não há sugestões para esta proposta.');
                atualizarAgGridSugestaoProposta();
            }else{
                showAlert('warning', 'Não foi possível buscar sugestões. Tente novamente.');
                atualizarAgGridSugestaoProposta();
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar sugestões. Tente novamente.');
            HideLoadingScreen();
            atualizarAgGridSugestaoProposta();

        },
        complete: function() {
            HideLoadingScreen();
        }
    });
}

$('#tab-dadosProposta').click(function(){
    $('#bodyDadosProposta').show();
    $('#footerDadosProposta').show();
    $('#bodyDadosItensProposta').hide();
    $('#footerItensProposta').hide();
    $('#bodyDadosItensPropostaEdit').hide();
    $('#footerItensPropostaEdit').hide();
});

$('#tab-itensDaProposta').click(function(){
    $('#bodyDadosItensProposta').show();
    $('#footerItensProposta').show();
    $('#bodyDadosProposta').hide();
    $('#footerDadosProposta').hide();
    $('#bodyDadosItensPropostaEdit').hide();
    $('#footerItensPropostaEdit').hide();
});

$('#tab-itensDaPropostaEdit').click(function(){
    $('#bodyDadosItensPropostaEdit').show();
    $('#footerItensPropostaEdit').show();
    $('#bodyDadosItensProposta').hide();
    $('#bodyDadosProposta').hide();
    $('#footerDadosProposta').hide();
    $('#footerItensProposta').hide();

});

$('#tab-dadosOportunidade').click(function(){
    $('#bodyDadosOportunidade').show();
    $('#footerDadosOportunidade').show();
    $('#bodyDadosItensOportunidade').hide();
    $('#footerDadosItensOportunidade').hide();
    $('#produtoItemOportunidade').attr('required', false);
    $('#quantidadeItemOportunidade').attr('required', false);
    $('#documentoClienteOportunidade').attr('required', true);
    $('#idVendedorOportunidade').attr('required', true);
    $('#bodyDadosItensOportunidadeEdit').hide();
    $('#footerItensOportunidadeEdit').hide();
});

$('#tab-itensDaOportunidade').click(function(){
    $('#bodyDadosItensOportunidade').show();
    $('#footerDadosItensOportunidade').show();
    $('#bodyDadosOportunidade').hide();
    $('#footerDadosOportunidade').hide();
    $('#documentoClienteOportunidade').attr('required', false);
    $('#idVendedorOportunidade').attr('required', false);
    $('#produtoItemOportunidade').attr('required', true);
    $('#quantidadeItemOportunidade').attr('required', true);
    $('#bodyDadosItensOportunidadeEdit').hide();
    $('#footerItensOportunidadeEdit').hide();
});

$('#tab-itensDaOportunidadeEdit').click(function(){
    $('#bodyDadosItensOportunidadeEdit').show();
    $('#footerItensOportunidadeEdit').show();
    $('#bodyDadosItensOportunidade').hide();
    $('#bodyDadosOportunidade').hide();
    $('#footerDadosOportunidade').hide();
    $('#footerDadosItensOportunidade').hide();
});



$('#formItensProposta').submit(async function(e){
    e.preventDefault();

    if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
        let continuar = true;
        let nomesProdutos = [];
        itensComposicaoProduto.forEach((produto, index) => {
            if (index < 10) nomesProdutos.push(produto.produto.nome);
            else if (index == 10) nomesProdutos.push('entre outros...');
        });
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os seguintes produtos serão adicionados em conjunto: " + nomesProdutos.join(', ') + ". Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }
    }

    $('#percentualDescontoItemProposta').trigger('blur');
    let idProdutoItemTabela = $('#produtoItemProposta').val();
    let produtoItemTabela = $('#produtoItemProposta option:selected').text();
    let quantidadeItemTabela = $('#quantidadeItemProposta').val();
    let valorUnitarioItemTabela = $('#valorUnitarioItemProposta').val()
    let percentualDescontoItemTabela = $('#percentualDescontoItemProposta').val();
    let valoTotalItemTabela = $('#valorTotalItemProposta').val();
    let valorDescontoItemTabela = $('#valorDescontoItemProposta').val();
    let observacaoItemTabela = $('#observacaoItemProspota').val();
    let valorUnitario = valorUnitarioItemTabela ? (valorUnitarioItemTabela).replace('R$', '').trim() : '';
    valorUnitario = valorUnitario ? formataInsercao(valorUnitario) : '';

    if (valorUnitario <= 0){
        showAlert('warning', 'O valor unitário não pode ser menor ou igual a 0.');
    }else if (percentualDescontoItemTabela && (parseFloat(percentualDescontoItemTabela.replace(',', '.').replace('%', '').trim()) > 100.00)) {
        showAlert('warning', 'O percentual de desconto não pode ser maior que 100%.');
    }else if(produtoItemTabela && quantidadeItemTabela && valorUnitarioItemTabela && valoTotalItemTabela && valorDescontoItemTabela){
        let dados = {
            idProduto : idProdutoItemTabela,
            produtoItemTabela: produtoItemTabela,
            quantidadeItemTabela : quantidadeItemTabela,
            valorUnitarioItemTabela : valorUnitarioItemTabela,
            percentualDescontoItemTabela : percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela,
            valoTotalItemTabela : valoTotalItemTabela,
            valorDescontoItemTabela : valorDescontoItemTabela,
            observacaoItemTabela : observacaoItemTabela,
            composicao: itensComposicaoProduto,
            produtoComposicao: false
        }
        AgGridItensProposta.gridOptions.api.applyTransaction({add: [dados]});
        if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
            itensComposicaoProduto.forEach((produto) => {
                let percentualDesconto = percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela;
                let dadosProduto = {
                    idProduto : produto.idProdutoComposicao,
                    produtoItemTabela: produto.produto.nome,
                    quantidadeItemTabela : quantidadeItemTabela,
                    valorUnitarioItemTabela : produto.produto.precoUnt.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    percentualDescontoItemTabela : percentualDesconto,
                    valoTotalItemTabela : (produto.produto.precoUnt * quantidadeItemTabela - (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100))).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    valorDescontoItemTabela : (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100)).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    observacaoItemTabela : 'Item faz parte da composição do produto ' + produtoItemTabela + '.',
                    composicao: false,
                    produtoComposicao: true
                }
                AgGridItensProposta.gridOptions.api.applyTransaction({add: [dadosProduto]});
            });
        }
        $('#formItensProposta')[0].reset();
        $('#produtoItemProposta').val(null).trigger('change');
    }else{
        showAlert('warning', 'Verifique os campos e tente novamente.');
    }
});

$('#formItensOportunidade').submit(async function(e){
    e.preventDefault();

    if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
        let continuar = true;
        let nomesProdutos = [];
        itensComposicaoProduto.forEach((produto, index) => {
            if (index < 10) nomesProdutos.push(produto.produto.nome);
            else if (index == 10) nomesProdutos.push('entre outros...');
        });
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os seguintes produtos serão adicionados em conjunto: " + nomesProdutos.join(', ') + ". Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }
    }

    $('#percentualDescontoItemOportunidade').trigger('blur');
    let idProdutoItemTabela = $('#produtoItemOportunidade').val();
    let produtoItemTabela = $('#produtoItemOportunidade option:selected').text();
    let quantidadeItemTabela = $('#quantidadeItemOportunidade').val();
    let valorUnitarioItemTabela = $('#valorUnitarioItemOportunidade').val()
    let percentualDescontoItemTabela = $('#percentualDescontoItemOportunidade').val();
    let valoTotalItemTabela = $('#valorTotalItemOportunidade').val();
    let valorDescontoItemTabela = $('#valorDescontoItemOportunidade').val();
    let observacaoItemTabela = $('#observacaoItemOportunidade').val();
    let valorUnitario = valorUnitarioItemTabela ? (valorUnitarioItemTabela).replace('R$', '').trim() : '';
    valorUnitario = valorUnitario ? formataInsercao(valorUnitario) : '';

    if (valorUnitario <= 0){
        showAlert('warning', 'O valor unitário não pode ser menor ou igual a 0.');
    }else if (percentualDescontoItemTabela && (parseFloat(percentualDescontoItemTabela.replace(',', '.').replace('%', '').trim()) > 100.00)) {
        showAlert('warning', 'O percentual de desconto não pode ser maior que 100%.');
    }else if(produtoItemTabela && quantidadeItemTabela && valorUnitarioItemTabela && valoTotalItemTabela && valorDescontoItemTabela){
        let dados = {
            idProduto : idProdutoItemTabela,
            produtoItemTabela: produtoItemTabela,
            quantidadeItemTabela : quantidadeItemTabela,
            valorUnitarioItemTabela : valorUnitarioItemTabela,
            percentualDescontoItemTabela : percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela,
            valoTotalItemTabela : valoTotalItemTabela,
            valorDescontoItemTabela : valorDescontoItemTabela,
            observacaoItemTabela : observacaoItemTabela ? observacaoItemTabela : '',
            composicao: itensComposicaoProduto,
            produtoComposicao: false
        }
        AgGridItensOportunidade.gridOptions.api.applyTransaction({add: [dados]});
        if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
            itensComposicaoProduto.forEach((produto) => {
                let percentualDesconto = percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela;
                let dadosProduto = {
                    idProduto : produto.idProdutoComposicao,
                    produtoItemTabela: produto.produto.nome,
                    quantidadeItemTabela : quantidadeItemTabela,
                    valorUnitarioItemTabela : produto.produto.precoUnt.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    percentualDescontoItemTabela : percentualDesconto,
                    valoTotalItemTabela : (produto.produto.precoUnt * quantidadeItemTabela - (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100))).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    valorDescontoItemTabela : (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100)).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    observacaoItemTabela : 'Item faz parte da composição do produto ' + produtoItemTabela + '.',
                    composicao: false,
                    produtoComposicao: true
                }
                AgGridItensOportunidade.gridOptions.api.applyTransaction({add: [dadosProduto]});
            });
        }
        $('#formItensOportunidade')[0].reset();
        $('#produtoItemOportunidade').val(null).trigger('change');
    }else{
        showAlert('warning', 'Verifique os campos e tente novamente.');
    }
});

$('#btnSalvarOportunidadeItens').on('click', function(e){
    e.preventDefault();
    let documentoCliente = $('#documentoClienteOportunidade').val();
    let idVendedor = $('#idVendedorOportunidade').val();
    let emailCliente = $('#emailClienteOportunidade').val();
    let documentoClienteFormatado = documentoCliente ? documentoCliente.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';

    if (documentoCliente && idVendedor){
        if (documentoCliente){
            if (documentoClienteFormatado.length != 11 && documentoClienteFormatado.length != 14){
                showAlert('warning', 'Digite um CPF/CNPJ válido!');
                return;
            }
        }

        if (emailCliente) {
            if(!validaEmail(emailCliente)) {
                showAlert('warning', 'E-mail do cliente inválido.');
                return;
            }
        }
    }else{
        showAlert('warning', 'Preencha os campos obrigatórios da aba "Dados da Oportunidade".');
        return;
    }

    let dados = AgGridItensOportunidade.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
        return{
            idProduto: dado.data.idProduto,
            quantidade : dado.data.quantidadeItemTabela,
            valorUnitario : dado.data.valorUnitarioItemTabela ? (formataInsercao((dado.data.valorUnitarioItemTabela).replace('R$', '').trim())) : '',
            percentualDesconto : dado.data.percentualDescontoItemTabela ? ((dado.data.percentualDescontoItemTabela).replace('%', '').trim()).replace(',', '.') : '',
            valorTotal : dado.data.valoTotalItemTabela ? (formataInsercao((dado.data.valoTotalItemTabela).replace('R$', '').trim())) : '',
            valorDesconto : dado.data.valorDescontoItemTabela ? (formataInsercao((dado.data.valorDescontoItemTabela).replace('R$', '').trim())) : '',
            observacao : dado.data.observacaoItemTabela ? dado.data.observacaoItemTabela : null,
        };
    });

    if (dados.length > 0){
        salvarOportunidade(dados);
        return;
    }else{
        showAlert('warning', 'Adicione, pelo menos, um item na tabela.');
        return;
    }


});
async function adicionarDadosTabelaItensPropostaAddEdit(){
    if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
        let continuar = true;
        let nomesProdutos = [];
        itensComposicaoProduto.forEach((produto, index) => {
            if (index < 10) nomesProdutos.push(produto.produto.nome);
            else if (index == 10) nomesProdutos.push('entre outros...');
        });
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os seguintes produtos serão adicionados em conjunto: " + nomesProdutos.join(', ') + ". Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }
    }

    $('#percentualDescontoItemPropostaEdit').trigger('blur');
    let idProdutoItemTabela = $('#produtoItemPropostaEdit').val();
    let produtoItemTabela = $('#produtoItemPropostaEdit option:selected').text();
    let quantidadeItemTabela = $('#quantidadeItemPropostaEdit').val();
    let valorUnitarioItemTabela = $('#valorUnitarioItemPropostaEdit').val()
    let percentualDescontoItemTabela = $('#percentualDescontoItemPropostaEdit').val();
    let valoTotalItemTabela = $('#valorTotalItemPropostaEdit').val();
    let valorDescontoItemTabela = $('#valorDescontoItemPropostaEdit').val();
    let observacaoItemTabela = $('#observacaoItemProspotaEdit').val();
    let valorUnitario = valorUnitarioItemTabela ? (valorUnitarioItemTabela).replace('R$', '').trim() : '';
    valorUnitario = valorUnitario ? formataInsercao(valorUnitario) : '';
    
    if (valorUnitario <= 0){
        showAlert('warning', 'O valor unitário não pode ser menor ou igual a 0.');
    }else if (percentualDescontoItemTabela && (parseFloat(percentualDescontoItemTabela.replace(',', '.').replace('%', '').trim()) > 100.00)) {
        showAlert('warning', 'O percentual de desconto não pode ser maior que 100%.');
    }else if(produtoItemTabela && quantidadeItemTabela && valorUnitarioItemTabela && valoTotalItemTabela && valorDescontoItemTabela){
        let dados = {
            idProduto : idProdutoItemTabela,
            nomeProduto: produtoItemTabela,
            quantidade : quantidadeItemTabela,
            valorUnitario : valorUnitarioItemTabela,
            percentualDesconto : percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela,
            valorTotal : valoTotalItemTabela,
            valorDesconto : valorDescontoItemTabela,
            observacao : observacaoItemTabela,
            composicao: itensComposicaoProduto,
            produtoComposicao: false
        }
        AgGridItensPropostaAddEdit.gridOptions.api.applyTransaction({add: [dados]});
        if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
            itensComposicaoProduto.forEach((produto) => {
                let percentualDesconto = percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela;
                let dadosProduto = {
                    idProduto : produto.idProdutoComposicao,
                    nomeProduto: produto.produto.nome,
                    quantidade : quantidadeItemTabela,
                    valorUnitario : produto.produto.precoUnt.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    percentualDesconto : percentualDesconto,
                    valorTotal : (produto.produto.precoUnt * quantidadeItemTabela - (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100))).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    valorDesconto : (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100)).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    observacao : 'Item faz parte da composição do produto ' + produtoItemTabela + '.',
                    composicao: false,
                    produtoComposicao: true
                }
                AgGridItensPropostaAddEdit.gridOptions.api.applyTransaction({add: [dadosProduto]});
            });
        }
        $('#formItensPropostaEdit')[0].reset();
        $('#produtoItemPropostaEdit').val(null).trigger('change');
    }else{
        showAlert('warning', 'Verifique os campos e tente novamente.');
    
    }
}

async function adicionarDadosTabelaItensOportunidadeAddEdit(){
    if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
        let continuar = true;
        let nomesProdutos = [];
        itensComposicaoProduto.forEach((produto, index) => {
            if (index < 10) nomesProdutos.push(produto.produto.nome);
            else if (index == 10) nomesProdutos.push('entre outros...');
        });
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os seguintes produtos serão adicionados em conjunto: " + nomesProdutos.join(', ') + ". Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }
    }

    $('#percentualDescontoItemOportunidadeEdit').trigger('blur');
    let idProdutoItemTabela = $('#produtoItemOportunidadeEdit').val();
    let produtoItemTabela = $('#produtoItemOportunidadeEdit option:selected').text();
    let quantidadeItemTabela = $('#quantidadeItemOportunidadeEdit').val();
    let valorUnitarioItemTabela = $('#valorUnitarioItemOportunidadeEdit').val()
    let percentualDescontoItemTabela = $('#percentualDescontoItemOportunidadeEdit').val();
    let valoTotalItemTabela = $('#valorTotalItemOportunidadeEdit').val();
    let valorDescontoItemTabela = $('#valorDescontoItemOportunidadeEdit').val();
    let observacaoItemTabela = $('#observacaoItemOportunidadeEdit').val();
    let valorUnitario = valorUnitarioItemTabela ? (valorUnitarioItemTabela).replace('R$', '').trim() : '';
    valorUnitario = valorUnitario ? formataInsercao(valorUnitario) : '';
    
    if (valorUnitario <= 0){
        showAlert('warning', 'O valor unitário não pode ser menor ou igual a 0.');
    }else if (percentualDescontoItemTabela && (parseFloat(percentualDescontoItemTabela.replace(',', '.').replace('%', '').trim()) > 100.00)) {
        showAlert('warning', 'O percentual de desconto não pode ser maior que 100%.');
    }else if(produtoItemTabela && quantidadeItemTabela && valorUnitarioItemTabela && valoTotalItemTabela && valorDescontoItemTabela){
        let dados = {
            id : id,
            idProduto : idProdutoItemTabela,
            nomeProduto: produtoItemTabela,
            quantidade : quantidadeItemTabela,
            valorUnitario : valorUnitarioItemTabela,
            percentualDesconto : percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela,
            valorTotal : valoTotalItemTabela,
            valorDesconto : valorDescontoItemTabela,
            observacao : observacaoItemTabela,
            composicao: itensComposicaoProduto,
            produtoComposicao: false
        }
        AgGridItensOportunidadeAddEdit.gridOptions.api.applyTransaction({add: [dados]});
        if (itensComposicaoProduto && itensComposicaoProduto.length > 0) {
            itensComposicaoProduto.forEach((produto) => {
                let percentualDesconto = percentualDescontoItemTabela == "" || percentualDescontoItemTabela == "%" ? "0%" : percentualDescontoItemTabela;
                let dadosProduto = {
                    idProduto : produto.idProdutoComposicao,
                    nomeProduto: produto.produto.nome,
                    quantidade : quantidadeItemTabela,
                    valorUnitario : produto.produto.precoUnt.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    percentualDesconto : percentualDesconto,
                    valorTotal : (produto.produto.precoUnt * quantidadeItemTabela - (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100))).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    valorDesconto : (produto.produto.precoUnt * quantidadeItemTabela * (parseFloat((percentualDesconto).replace(',', '.')) / 100)).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
                    observacao : 'Item faz parte da composição do produto ' + produtoItemTabela + '.',
                    composicao: false,
                    produtoComposicao: true
                }
                AgGridItensOportunidadeAddEdit.gridOptions.api.applyTransaction({add: [dadosProduto]});
            });
        }
        $('#formItensOportunidadeEdit')[0].reset();
        $('#produtoItemOportunidadeEdit').val(null).trigger('change');
    }else{
        showAlert('warning', 'Verifique os campos e tente novamente.');
    
    }
}
async function listarVendedores(idSelect) {
    try {
        $('#' + idSelect).empty();
        $('#' + idSelect).append('<option value="" disabled selected>Buscando vendedores...</option>');
        const listarVendedores = await $.ajax({
            url: RouterController + '/listarVendedoresSelect2Propostas',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            }
        });

        $('#' + idSelect).select2({
            data: listarVendedores.resultado,
            placeholder: "Selecione o vendedor",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });

        $('#' + idSelect).on('select2:select', function (e) {
            var data = e.params.data;
        });

        
        $('#' + idSelect).val(null).trigger('change');
        
    } catch (error) {
        $('#' + idSelect).val(null).trigger('change');
        throw new Error('Não foi possível listar os vendedores. Tente novamente.');
    }
}

async function listarVendedoresSelects(idsSelects) {
    try {

        idsSelects.forEach(async (idSelect) => {
            $('#' + idSelect).empty();
            $('#' + idSelect).append('<option value="" disabled selected>Buscando vendedores...</option>');
        });

        const listarVendedores = await $.ajax({
            url: RouterController + '/listarVendedoresSelect2Propostas',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            }
        });

        idsSelects.forEach(async (idSelect) => {
            $('#' + idSelect).select2({
                data: listarVendedores.resultado,
                placeholder: "Selecione o vendedor",
                allowClear: true,
                language: "pt-BR",
                width: '100%',
            });
    
            $('#' + idSelect).on('select2:select', function (e) {
                var data = e.params.data;
            });
    
            
            $('#' + idSelect).val(null).trigger('change');
        });
        
        
    } catch (error) {
        $('#' + idSelect).val(null).trigger('change');
        throw new Error('Não foi possível listar os vendedores. Tente novamente.');
    }
}

async function retornaItensOportunidade(idOportunidade){
    let route = RouterController + '/buscarItensOportunidadePorIdOportunidade';
    let dadosTabelaItensOportunidade = [];

    await $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {idOportunidade: idOportunidade},
        dataType: 'json',
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaItensOportunidade = data.resultado;
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados do(s) item(ns). Tente novamente.');
        }
    });

    return dadosTabelaItensOportunidade;
}
$('#btnSalvarItensProposta').click(function(){
    let idPropostaItens = $('#btnSalvarItensProposta').data('idProposta');
    let dados = AgGridItensProposta.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
        return{
            idProposta: idPropostaItens,
            idProduto: dado.data.idProduto,
            quantidade : dado.data.quantidadeItemTabela,
            valorUnitario : dado.data.valorUnitarioItemTabela ? (formataInsercao((dado.data.valorUnitarioItemTabela).replace('R$', '').trim())) : '',
            percentualDesconto : dado.data.percentualDescontoItemTabela ? ((dado.data.percentualDescontoItemTabela).replace('%', '').trim()).replace(',', '.') : '',
            valorTotal : dado.data.valoTotalItemTabela ? (formataInsercao((dado.data.valoTotalItemTabela).replace('R$', '').trim())) : '',
            valorDesconto : dado.data.valorDescontoItemTabela ? (formataInsercao((dado.data.valorDescontoItemTabela).replace('R$', '').trim())) : '',
            observacao : dado.data.observacaoItemTabela ? dado.data.observacaoItemTabela : null,
        };
    });

    if(dados.length > 0){
        let route = RouterController + '/cadastrarItensProposta';
        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: {
                dados: dados
            },
            dataType: 'json',
            beforeSend: function() {
                ShowLoadingScreen();
            },
            success: function(data) {
                if (data.status == 200) {
                    showAlert('success', data['resultado']['mensagem'] ?? 'Itens salvos com sucesso.');
                    $('#modalProposta').modal('hide');
                    if (teveBuscaProposta) {
                        listarDadosBuscaProposta(paramsBuscaProposta);
                    }else{
                        listarTop100Propostas();
                    }
                }else{
                    if (data['resultado']){
                        validaMensagemRetorno(data['status'], data['resultado'], ' salvar itens');
                    }else{
                        showAlert('error', 'Não foi possível salvar o(s) item(ns). Tente novamente.');
                    }
                } 
            },
            error: function(error) {
                showAlert('error', 'Não foi possível salvar o(s) item(ns). Tente novamente.');
                HideLoadingScreen();
            },
            complete: function() {
                HideLoadingScreen();
            }
        });
    }else{
        showAlert('warning', 'Insira, pelo menos, um item para salvar.');
    }
});

$('#btnSalvarItensPropostaEditTabela').click(function(){
    let idPropostaItens = $('#btnSalvarItensPropostaEditTabela').data('idProposta');

        let dados = AgGridItensPropostaAddEdit.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
            return{
                idProposta: idPropostaItens,
                idProduto: dado.data.idProduto,
                quantidade : dado.data.quantidade,
                valorUnitario : dado.data.valorUnitario ? (formataInsercao((dado.data.valorUnitario).replace('R$', '').trim())) : '',
                percentualDesconto : dado.data.percentualDesconto ? ((dado.data.percentualDesconto).replace('%', '').trim()).replace(',', '.') : '',
                valorTotal : dado.data.valorTotal ? (formataInsercao((dado.data.valorTotal).replace('R$', '').trim())) : '',
                valorDesconto : dado.data.valorDesconto ? (formataInsercao((dado.data.valorDesconto).replace('R$', '').trim())) : '',
                observacao : dado.data.observacao ? dado.data.observacao : null,
            };
        });
        
        if (dados.length > 0){
            $.ajax({
                cache: false,
                url: RouterController + '/cadastrarItensProposta',
                type: 'POST',
                data: {
                    dados: dados
                },
                dataType: 'json',
                beforeSend: function() {
                    ShowLoadingScreen();
                },
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', data['resultado']['mensagem'] ?? 'Itens salvos com sucesso.');
                        $('#modalItemProposta').modal('hide');
                        listarDadosItensPropostaEdit(idPropostaItens);
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' salvar itens');
                        }else{
                            showAlert('error', 'Não foi possível salvar o(s) item(ns). Tente novamente.');
                        }
                    }
                },
                error: function(error) {
                    showAlert('error', 'Não foi possível salvar o(s) item(ns). Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function() {
                    HideLoadingScreen();
                }
            });
        }else{
            showAlert('warning', 'Insira, pelo menos, um item para salvar.');
        }
});

$('#btnSalvarItensOportunidadeEditTabela').click(function(){
    let idPOportunidade = $('#idOportunidade').val();

        let dados = AgGridItensOportunidadeAddEdit.gridOptions.api.rowModel.rowsToDisplay.map(function (dado, index) {
            return{
                idOportunidade: idPOportunidade,
                idProduto: dado.data.idProduto,
                quantidade : dado.data.quantidade,
                valorUnitario : dado.data.valorUnitario ? (formataInsercao((dado.data.valorUnitario).replace('R$', '').trim())) : '',
                percentualDesconto : dado.data.percentualDesconto ? ((dado.data.percentualDesconto).replace('%', '').trim()).replace(',', '.') : '',
                valorTotal : dado.data.valorTotal ? (formataInsercao((dado.data.valorTotal).replace('R$', '').trim())) : '',
                valorDesconto : dado.data.valorDesconto ? (formataInsercao((dado.data.valorDesconto).replace('R$', '').trim())) : '',
                observacao : dado.data.observacao ? dado.data.observacao : null,
            };
        });
        
        if (dados.length > 0){
            $.ajax({
                cache: false,
                url: RouterController + '/cadastrarItensOportunidade',
                type: 'POST',
                data: {
                    dados: dados
                },
                dataType: 'json',
                beforeSend: function() {
                    ShowLoadingScreen();
                },
                success: function(data) {
                    if (data.status == 200) {
                        showAlert('success', data['resultado']['mensagem'] ?? 'Itens salvos com sucesso.');
                        $('#modalItemOportunidade').modal('hide');
                        listarDadosItensOportunidadeEdit(idPOportunidade);
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' salvar itens');
                        }else{
                            showAlert('error', 'Não foi possível salvar o(s) item(ns). Tente novamente.');
                        }
                    }
                },
                error: function(error) {
                    showAlert('error', 'Não foi possível salvar o(s) item(ns). Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function() {
                    HideLoadingScreen();
                }
            });
        }else{
            showAlert('warning', 'Insira, pelo menos, um item para salvar.');
        }
});
$('#BtnAdicionarCliente').click(function(){
    abrirModalCliente();
});

$('#formCliente').submit(function(e){
    e.preventDefault();

    let documento = $('#cpfCliente').val() ? $('#cpfCliente').val() : $('#cnpjCliente').val();
    let documentoFormatado = documento ? documento.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
    let tipoDocumento = $('#cpfCliente').is(':visible') ? 'CPF' : 'CNPJ';
    let email = $('#emailCliente').val();
    let telefone = $('#telefoneCliente').val();
    let telefoneFormatado = telefone ? telefone.split(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g).join('') : '';
    let identidade = $('#identidadeCliente').val();
    let orgaoExpedidor = $('#orgaoExpedidor').val();
    let dataNascimento = $('#dataNascimentoCliente').val();
    let dataNascimentoFormatada = dataNascimento ? new Date(dataNascimento) : '';
    let dataAtual = new Date();
    let diferencaDatas = dataNascimentoFormatada ? dataAtual.getFullYear() - dataNascimentoFormatada.getFullYear() : 0;
    let inscricaoEstadual = $('#inscricaoEstadualCliente').val();
    let ufOrgaoExpedidor = $('#ufOrgaoExpedidor').val();

    if (dataNascimento){
        if (dataNascimentoFormatada > dataAtual || diferencaDatas < 18){
            showAlert('warning', 'A data de nascimento informada é inválida.');
            return;
        }
    }
    
    if (documento){
        if (documentoFormatado.length != 11 && documentoFormatado.length != 14){
            showAlert('warning', 'O ' + tipoDocumento + ' informado é inválido.');
            return;
        }
    }

    if (email){
        if (!validaEmail(email)){
            showAlert('warning', 'O e-mail informado é inválido.');
            return;
        }
    }

    if (telefone){
        if (telefoneFormatado.length < 10){
            showAlert('warning', 'O telefone informado é inválido.');
            return;
        }
    }

    if (identidade){
        if (identidade.length < 7){
            showAlert('warning', 'A identidade informada é inválida.');
            return;
        }

        if (!orgaoExpedidor){
            showAlert('warning', 'Informe o órgão expedidor do RG.');
            return;
        }else if (orgaoExpedidor.length < 2){
            showAlert('warning', 'O órgão expedidor precisa ter no mínimo 2 caracteres.');
            return;
        }else{
            if (!ufOrgaoExpedidor){
                showAlert('warning', 'Informe a UF do órgão expedidor.');
                return;
            }
        }
    }else{
        if (orgaoExpedidor && ufOrgaoExpedidor){
            showAlert('warning', 'Informe o número do RG.');
            return;
        }
            
    }



    if (inscricaoEstadual){
        if (inscricaoEstadual.length < 9){
            showAlert('warning', 'A inscrição estadual informada é inválida.');
            return;
        }
    }

    salvarCliente();
});

//Visibilidade

function ShowLoadingScreen(){
    $('#loading').show()
}

function HideLoadingScreen(){
    $('#loading').hide()
}

function showLoadingSalvarButton(idButton) {
    $('#' + idButton).html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
}

function resetSalvarButton(idButton) {
    $('#' + idButton).html('Salvar').attr('disabled', false);
}

function showLoadingButtonFiltro(idButton) {
    $('#' + idButton).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Filtrando...').attr('disabled', true);
}

function resetLoadingButtonFiltro(idButton) {
    $('#' + idButton).html('<i class="fa fa-search" aria-hidden="true"></i> Filtrar').attr('disabled', false);
}
function showLoadingButton(idButton) {
    $('#' + idButton).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>').attr('disabled', true);
}

function resetLoadingButtonEdit(idButton) {
    $('#' + idButton).html('<i class="fa fa-pencil" aria-hidden="true"></i> Editar').attr('disabled', false);
}

function showLoadingButtonLimpar(idButton) {
    $('#' + idButton).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Limpando...').attr('disabled', true);
}

function resetLoadingButtonLimpar(idButton) {
    $('#' + idButton).html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar').attr('disabled', false);
}

function stopAgGRIDProdutos() {
    var gridDiv = document.querySelector('#tableProdutos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperProdutos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableProdutos" style="height:530px;" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDComposicao() {
    var gridDiv = document.querySelector('#tableItensComposicao');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensComposicao');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensComposicao" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDPropostas() {
    var gridDiv = document.querySelector('#tablePropostas');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperPropostas');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tablePropostas" style="height:530px;" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDItensProposta() {
    var gridDiv = document.querySelector('#tableItensPropostas');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensPropostas');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensPropostas" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDItensPropostaEdit() {
    var gridDiv = document.querySelector('#tableItensPropostasEdit');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensPropostasEdit');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensPropostasEdit" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDItensPropostaAddEdit() {
    var gridDiv = document.querySelector('#tableItensPropostasAddEdit');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensPropostasAddEdit');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensPropostasAddEdit" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDAutorizacaoFaturamento() {
    var gridDiv = document.querySelector('#tableAutorizacaoFaturamento');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperAutorizacaoFaturamento');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableAutorizacaoFaturamento" style="height:530px;" class="ag-theme-alpine my-grid"></div>';
    }
}


function stopAgGRIDClientes() {
    var gridDiv = document.querySelector('#tableClientes');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperClientes');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableClientes" style="height:530px;" class="ag-theme-alpine my-grid"></div>';
    }
}
function stopAgGRIDSugestaoProposta() {
    var gridDiv = document.querySelector('#tableSugestaoProposta');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrappertableSugestaoProposta');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableSugestaoProposta" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDItensPropostaInfo() {
    var gridDiv = document.querySelector('#tableItensPropostasInfo');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensPropostasInfo');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensPropostasInfo" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDOportunidade() {
    var gridDiv = document.querySelector('#tableOportunidades');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperOportunidade');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableOportunidades" class="ag-theme-alpine my-grid" style="height: 500px"></div>';
    }
}

function stopAgGRIDItensOportunidade() {
    var gridDiv = document.querySelector('#tableItensOportunidade');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensOportunidade');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensOportunidade" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDItensOportunidadeEdit() {
    var gridDiv = document.querySelector('#tableItensOportunidadeEdit');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensOportunidadeEdit');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensOportunidadeEdit" class="ag-theme-alpine my-grid"></div>';
    }
}

function stopAgGRIDItensOportunidadeAddEdit() {
    var gridDiv = document.querySelector('#tableItensOportunidadeAddEdit');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperItensOportunidadeAddEdit');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableItensOportunidadeAddEdit" class="ag-theme-alpine my-grid"></div>';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao');

    document.getElementById('dropdownMenuButton').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButton') {
            dropdown.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_propostas');

    document.getElementById('dropdownMenuButtonPropostas').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonPropostas') {
            dropdown.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_autorizacao_faturamento');

    document.getElementById('dropdownMenuButtonAutorizacaoFaturamento').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonAutorizacaoFaturamento') {
            dropdown.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_clientes');

    document.getElementById('dropdownMenuButtonClientes').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonClientes') {
            dropdown.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_oportunidade');

    document.getElementById('dropdownMenuButtonOportunidade').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonOportunidade') {
            dropdown.style.display = 'none';
        }
    });
});

$('#BtnLimpar').click(function() {
    $('#buscaId').val('');
    $('#buscaNome').val('');
    listarTop100Produtos();
});

$('#BtnLimparProspota').click(function() {
    showLoadingButtonLimpar('BtnLimparProspota');
    $('#buscaDocumentoProposta').val('');
    $('#buscaVendedorProposta').val(null).trigger('change');
    $('#buscaDataInicialProposta').val('');
    $('#buscaDataFinalProposta').val('');
    listarTop100Propostas();
});

$('#BtnLimparAutorizacaoFaturamento').click(function() {
    $('#buscaIdPropostaAutorizacaoFaturamento').val('');
    $('#buscaDocumentoAutorizacaoFaturamento').val('');
    $('#buscaVendedorAutorizacaoFaturamento').val(null).trigger('change');
    atualizarAgGridAutorizacaoFaturamento();
});

$('#BtnLimparCliente').click(function() {
    teveBuscaCliente = false;
    $('#buscaIdCliente').val('');
    $('#buscaNomeCliente').val('');
    $('#buscaDocumentoCliente').val('');
    atualizarAgGridClientes();
});

$('#BtnLimparOportunidade').click(function() {
    showLoadingButtonLimpar('BtnLimparOportunidade');
    $('#buscaDocumentoOportunidade').val('');
    $('#buscaVendedorOportunidade').val(null).trigger('change');
    $('#buscaDataInicialOportunidade').val('');
    $('#buscaDataFinalOportunidade').val('');
    atualizarAgGridOportunidades();
});


function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

    formularioExportacoes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcao_exportacao');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivo(opcao, gridOptions);
        });

        formularioExportacoes.appendChild(div);
    });
}

function preencherExportacoesPropostas(gridOptions) {
    const formularioExportacoesPropostas = document.getElementById('opcoes_exportacao_propostas');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

    formularioExportacoesPropostas.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_propostas');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivoPropostas(opcao, gridOptions);
        });

        formularioExportacoesPropostas.appendChild(div);
    });
}

function preencherExportacoesAutorizacaoFaturamento(gridOptions) {
    const formularioExportacoesAutorizacaoFaturamento = document.getElementById('opcoes_exportacao_autorizacao_faturamento');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

    formularioExportacoesAutorizacaoFaturamento.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_autorizacao_faturamento');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivoAutorizacaoFaturamento(opcao, gridOptions);
        });

        formularioExportacoesAutorizacaoFaturamento.appendChild(div);
    });
}

function preencherExportacoesClientes(gridOptions) {
    const formularioExportacoesClientes = document.getElementById('opcoes_exportacao_clientes');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

    formularioExportacoesClientes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_clientes');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivoCliente(opcao, gridOptions);
        });

        formularioExportacoesClientes.appendChild(div);
    });
}

function preencherExportacoesOportunidades(gridOptions) {
    const formularioExportacoesClientes = document.getElementById('opcoes_exportacao_oportunidade');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
    let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
    let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

    formularioExportacoesClientes.innerHTML = '';

    opcoes.forEach(opcao => {
        let button = '';
        let texto = '';
        switch (opcao) {
            case 'csv':
                button = buttonCSV;
                texto = 'CSV';
                margin = '-5px';
                break;
            case 'excel':
                button = buttonEXCEL;
                texto = 'Excel';
                margin = '0px';
                break;
            case 'pdf':
                button = buttonPDF;
                texto = 'PDF';
                margin = '0px';
                break;
        }

        let div = document.createElement('div');
        div.classList.add('dropdown-item');
        div.classList.add('opcoes_exportacao_oportunidade');
        div.setAttribute('data-tipo', opcao);
        div.innerHTML = `
        <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
        <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
    `;

        div.style.height = '30px';

        div.style.marginTop = margin;

        div.style.borderRadius = '1px';

        div.style.transition = 'background-color 0.3s ease';

        div.addEventListener('mouseover', function() {
            div.style.backgroundColor = '#f0f0f0'; 
        });

        div.addEventListener('mouseout', function() {
            div.style.backgroundColor = '';
        });

        div.style.border = '1px solid #ccc';

        div.addEventListener('click', function(event) {
            event.preventDefault();
            exportarArquivoOportunidade(opcao, gridOptions);
        });

        formularioExportacoesClientes.appendChild(div);
    });
}


function exportarArquivo(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'RelatorioProdutos.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: ['nomeProduto', 'precoUnitario', 'validaQuantidade', 'quantidadeMinima', 'quantidadeMaxima', 'tipoProduto', 'temComposicao', 'dataCadastro', 'dataUpdate', 'status']
            });
            break;
        case 'excel':
            fileName = 'RelatorioProdutos.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: ['nomeProduto', 'precoUnitario', 'validaQuantidade', 'quantidadeMinima', 'quantidadeMaxima', 'tipoProduto', 'temComposicao', 'dataCadastro', 'dataUpdate', 'status']
            });
            break;
        case 'pdf':
            let dadosExportacao = prepararDadosExportacaoRelatorio();

            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                dadosExportacao.rodape
            );

            pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
            break;
    }
}

function exportarArquivoPropostas(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'RelatorioPropostas.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: ['id', 'nomeCliente', 'nomeVendedor', 'af', 'valorTotal', 'quantidadeTotal', 'formaPagamento', 'recorrencia', 'statusIntegracao', 'dataVencimento', 'diaVencimento', 'enderecoFatura', 'enderecoPagamento', 'status', 'dataCadastro', 'dataUpdate']
            });
            break;
        case 'excel':
            fileName = 'RelatorioPropostas.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: ['id', 'nomeCliente', 'nomeVendedor', 'af', 'valorTotal', 'quantidadeTotal', 'formaPagamento', 'recorrencia', 'statusIntegracao', 'dataVencimento', 'diaVencimento', 'enderecoFatura', 'enderecoPagamento', 'status', 'dataCadastro', 'dataUpdate']
            });
            break;
        case 'pdf':
            let dadosExportacao = prepararDadosExportacaoRelatorioPropostas();

            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                dadosExportacao.rodape
            );

            pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
            break;
    }
}

function exportarArquivoAutorizacaoFaturamento(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'RelatorioAutorizacaoFaturamento.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: ['idProposta', 'nomeCliente', 'cnpjCliente', 'nomeVendedor', 'observacao', 'nomeAutorizador', 'documentoAutorizador', 'emailAutorizador', 'telefoneAutorizador', 'statusAutorizacao','dataAutorizacao', 'dataUpdate']
            });
            break;
        case 'excel':
            fileName = 'RelatorioAutorizacaoFaturamento.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: ['idProposta', 'nomeCliente', 'cnpjCliente', 'nomeVendedor', 'observacao', 'nomeAutorizador', 'documentoAutorizador', 'emailAutorizador', 'telefoneAutorizador', 'statusAutorizacao', 'dataAutorizacao', 'dataUpdate']
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                `Relatório de Autorização de Faturamento\n ${new Date().toLocaleString('pt-br')}`
            );

            pdfMake.createPdf(definicoesDocumento).download('RelatorioAutorizacaoFaturamento.pdf');
            break;
    }
}

function exportarArquivoCliente(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'RelatorioClientes.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: ['id', 'nome', 'razaoSocial', 'documentoCliente', 'identidade', 'inscricaoEstadual', 'telefoneCliente', 'emailCliente', 'dataCadastro','dataHoraAtualizacao']
            });
            break;
        case 'excel':
            fileName = 'RelatorioClientes.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: ['id', 'nome', 'razaoSocial', 'documentoCliente', 'identidade', 'inscricaoEstadual', 'telefoneCliente', 'emailCliente', 'dataCadastro','dataHoraAtualizacao']
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                `Relatório de Clientes \n ${new Date().toLocaleString('pt-br')}`
            );

            pdfMake.createPdf(definicoesDocumento).download('RelatorioClientes.pdf');
            break;
    }
}

function exportarArquivoOportunidade(tipo, gridOptions) {
    switch (tipo) {
        case 'csv':
            fileName = 'RelatorioOportunidades.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: ['id', 'nomeCliente', 'documentoCliente', 'nomeVendedor', 'valorTotal', 'formaPagamento', 'recorrencia', 'enderecoFatura', 'enderecoPagto','permitePoc', 'status', 'dataCadastro', 'dataUpdate']
            });
            break;
        case 'excel':
            fileName = 'RelatorioOportunidades.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: ['id', 'nomeCliente', 'documentoCliente', 'nomeVendedor', 'valorTotal', 'formaPagamento', 'recorrencia', 'enderecoFatura', 'enderecoPagto','permitePoc', 'status', 'dataCadastro', 'dataUpdate']
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                `Relatório de Oportunidades \n ${new Date().toLocaleString('pt-br')}`
            );

            pdfMake.createPdf(definicoesDocumento).download('RelatorioOportunidades.pdf');
            break;
    }
}
function prepararDadosExportacaoRelatorio() {
    let informacoes = dadosTabela.map((dado) => ({
        nomeProduto: dado?.nomeProduto,
        precoUnitario: dado?.precoUnitario,
        validaQuantidade: dado?.validaQuantidade,
        quantidadeMinima: dado?.quantidadeMinima ?? '-',
        quantidadeMaxima: dado?.quantidadeMaxima ?? '-',
        tipoProduto: dado?.tipoProduto,
        status: dado?.status,
        dataCadastro: dado?.dataCadastro,
        dataUpdate: dado?.dataUpdate
    }));

    let rodape = `Relatório de Produtos - ${new Date().toLocaleString('pt-br')}`;
    let nomeArquivo = `RelatorioProdutos.pdf`;

    return {
        informacoes,
        nomeArquivo,
        rodape
    };
}

function prepararDadosExportacaoRelatorioPropostas() {
    let rodape = `Relatório de Propostas - ${new Date().toLocaleString('pt-br')}`;
    let nomeArquivo = `RelatorioPropostas.pdf`;

    return {
        nomeArquivo,
        rodape
    };
}

function printParams(pageSize) {
    return {
        PDF_HEADER_COLOR: "#ffffff",
        PDF_INNER_BORDER_COLOR: "#dde2eb",
        PDF_OUTER_BORDER_COLOR: "#babfc7",
        PDF_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
        PDF_HEADER_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
        PDF_ODD_BKG_COLOR: "#fff",
        PDF_EVEN_BKG_COLOR: "#F3F3F3",
        PDF_PAGE_ORITENTATION: "landscape",
        PDF_WITH_FOOTER_PAGE_COUNT: true,
        PDF_HEADER_HEIGHT: 25,
        PDF_ROW_HEIGHT: 25,
        PDF_WITH_CELL_FORMATTING: true,
        PDF_WITH_COLUMNS_AS_LINKS: false,
        PDF_SELECTED_ROWS_ONLY: false,
        PDF_PAGE_SIZE: pageSize,
    };
}

$('#validaQuantidade').on('change', function() {
    let valor = $(this).val();
    if (valor == '1') {
        $('.qtd').show();
        $('#quantidadeMinima').attr('required',true)
        $('#quantidadeMaxima').attr('required',true)
    } else {
        $('.qtd').hide();
        $('#quantidadeMinima').val('');
        $('#quantidadeMaxima').val('');
        $('#quantidadeMinima').attr('required',false)
        $('#quantidadeMaxima').attr('required',false)
    }
});

$('#permitePocProduto').on('change', function() {
    let valor = $(this).val();
    if (valor == '1') {
        $('.diasPOC').show();
        $('#diasValidadePoc').attr('required',true);
    } else {
        $('.diasPOC').hide();
        $('#diasValidadePoc').val('');
        $('#diasValidadePoc').attr('required',false);
    }
});

$('#diasValidadePoc').mask('00');

$('#modalProduto').on('hidden.bs.modal', function () {
    $('#formProduto').trigger("reset");
    $('#validaQuantidade').trigger("change");
    $('#permitePocProduto').trigger("change");
});

$('#modalProposta').on('hidden.bs.modal', function () {
    $('#formProposta').trigger("reset");
    $('#idCliente').val(null).trigger('change');
    $('#idVendedor').val(null).trigger('change');
    $('#formItensProposta').trigger("reset");
    $('#produtoItemProposta').val(null).trigger('change');
    $('#btnSalvarItensProposta').data('ididProposta', '');
    $('#idProposta').val('');
    $('#enderecoFatura').trigger("change");
    $('#enderecoPagamento').trigger("change");
    atualizarAgGridItensProposta();
    enterButton = false;
    limparCamposEnderecoCliente();
    $('#idVendedor').attr('readonly', false);
    $('#idCliente').attr('readonly', false);
    itensOportunidadeGerarProposta = [];
    $('#btnSalvarProposta').data('idVendedor', '');
    $('#btnSalvarProposta').data('nomeVendedor', '');
    $('#btnSalvarProposta').data('idCliente', '');
    $('#btnSalvarProposta').data('nomeCliente', '');
});

$('#modalItemProposta').on('hidden.bs.modal', function () {
    $('#formItensPropostaEdit').trigger("reset");
    $('body').addClass('modal-open');
    $('#modalProposta').modal('handleUpdate');
    $('#modalProposta').focus();
    $('#produtoItemPropostaEdit').val(null).trigger('change');
    enterButton = false;
    atualizarAgGridItensPropostaAddEdit();
});

$('#modalCliente').on('hidden.bs.modal', function () {
    $('#formCliente').trigger("reset");
    $('#tipoClienteFisica').trigger("change");
});

$('#modalInformacoesPropostaKanban').on('hidden.bs.modal', function () {
    $('#divInfoDadosEnderecoFatura').hide();
    $('#divInfoDadosEnderecoPagamento').hide();
});

$('#modalOportunidade').on('hidden.bs.modal', function () {
    $('#formOportunidade').trigger("reset");
    $('#idOportunidade').val('');
    $('#formItensOportunidade').trigger("reset");
    $('#produtoItemOportunidade').val(null).trigger('change');
    $('#idProposta').val('');
    $('#enderecoFaturaOportunidade').trigger("change");
    $('#enderecoPagamentoOportunidade').trigger("change");
    $('#idVendedorOportunidade').val(null).trigger('change');
    atualizarAgGridItensOportunidades();
});

$('#modalItemOportunidade').on('hidden.bs.modal', function () {
    $('#formItensOportunidadeEdit').trigger("reset");
    $('body').addClass('modal-open');
    $('#modalOportunidade').modal('handleUpdate');
    $('#modalOportunidade').focus();
    $('#produtoItemOportunidadeEdit').val(null).trigger('change');
    /* enterButton = false; */
    atualizarAgGridItensOportunidadeAddEdit();
});

function exibirAlerta(icon, title, text) {
    Swal.fire({
      position: 'top-start',
      icon: icon,
      title: title,
      text: text,
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    });
}

window.addEventListener('resize', function() {
    if ($('#menu-kanban').hasClass("selected")) {

        var alturaKanbanCardsNaoIntegrado = document.querySelector('.nao-integrado-column-gerenciamento .kanban-cards').offsetHeight;
        var alturaKanbanCardsIntegrado = document.querySelector('.integrado-column-gerenciamento .kanban-cards').offsetHeight;
        var alturaKanbanCardsFaturado = document.querySelector('.faturado-column-gerenciamento .kanban-cards').offsetHeight;
        var alturaKanbanCardsAtualizado = document.querySelector('.atualizado-column-gerenciamento .kanban-cards').offsetHeight;
        var valorMaior = 0;

        valorMaior = Math.max(alturaKanbanCardsNaoIntegrado, alturaKanbanCardsIntegrado, alturaKanbanCardsFaturado, alturaKanbanCardsAtualizado);
        if (valorMaior > 0){
            $('.kanban-column').css('height', ((valorMaior ) + 70) + 'px');
        }
    }
});

//Utilitários

function formatDateTime(date){
    let dates = '';
    let dateCalendar = '';

    if (date.includes('T')){
        date = date.replace('T', ' ');
        date = date.substring(0, date.length - 10);
    }

    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0]+" "+dates[1];
}

function formataDataInserir(value) {
    value = value.replaceAll('-', '/');
    value = value.split('/');
    value = value[2] + '/' + value[1] + '/' + value[0];

    return value;
}

function removeAcento(palavra){
    return palavra.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
}

function formataMoedaInput(campo) {
    var elemento = document.getElementById(campo);
    var valor = elemento.value;

    valor = valor.toString().replace(/\D/g, '');
    valor = (parseFloat(valor) / 100).toFixed(2).toString();
    valor = valor.replace('.', ',');

    if (valor.length > 6) {
        valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    }

    elemento.value = valor;
    if (valor == 'NaN') elemento.value = '';
}

function formataMoedaInputComRS(campo) {
    var elemento = document.getElementById(campo);
    var valor = elemento.value;

    valor = valor.toString().replace(/\D/g, '');
    valor = (parseFloat(valor) / 100).toFixed(2).toString();
    valor = valor.replace('.', ',');

    if (valor.length > 6) {
        valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    }

    elemento.value = 'R$ ' + valor;
    if (valor == 'NaN') elemento.value = '';
}


function formataInsercao(value) {
    value = value.replaceAll('.', '');
    value = value.replace(',', '.');

    return value;
}

function validaEmail(email) {
    const expression = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return expression.test(email);
}

function formataCpfCnpjExibicao(documento){
    documento = documento.replace(/[^0-9]/g, '');
    if (documento.length == 11){
        return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }else if (documento.length == 14){
        return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }else{
        return 'Documento inválido';
    }

}

$("#buscaDocumentoProposta").inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});


$("#buscaDocumentoAutorizacaoFaturamento").inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});

$("#documentoAutorizador").inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});

$('#buscaDocumentoCliente').inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});

$('#buscaDocumentoKanbanPropostas').inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});

$('#percentualDescontoItemProposta').mask('000,00%', {reverse: true});
$('#percentualDescontoItemPropostaEdit').mask('000,00%', {reverse: true});
$('#percentualDescontoItemOportunidade').mask('000,00%', {reverse: true});
$('#percentualDescontoItemOportunidadeEdit').mask('000,00%', {reverse: true});

$("#buscaDocumentoOportunidade").inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});

$("#documentoClienteOportunidade").inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});

$("#buscaDocumentoKanbanAutorizacaoFaturamento").inputmask({
    mask: ["999.999.999-99", "99.999.999/9999-99"],
    keepStatic: true,
});

$('#quantidadeItemProposta').on('blur', function(){
    let valorUnitario = $('#valorUnitarioItemProposta').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let quantidade = $(this).val();
    let percentualDesconto = $('#percentualDescontoItemProposta').val() ? ($('#percentualDescontoItemProposta').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemProposta').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemProposta').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#quantidadeItemPropostaEdit').on('blur', function(){
    let valorUnitario = $('#valorUnitarioItemPropostaEdit').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let quantidade = $(this).val();
    let percentualDesconto = $('#percentualDescontoItemPropostaEdit').val() ? ($('#percentualDescontoItemPropostaEdit').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemPropostaEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemPropostaEdit').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});


$('#quantidadeItemOportunidade').on('blur', function(){
    let valorUnitario = $('#valorUnitarioItemOportunidade').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let quantidade = $(this).val();
    let percentualDesconto = $('#percentualDescontoItemOportunidade').val() ? ($('#percentualDescontoItemOportunidade').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemOportunidade').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemOportunidade').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#quantidadeItemOportunidadeEdit').on('blur', function(){
    let valorUnitario = $('#valorUnitarioItemOportunidadeEdit').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let quantidade = $(this).val();
    let percentualDesconto = $('#percentualDescontoItemOportunidadeEdit').val() ? ($('#percentualDescontoItemOportunidadeEdit').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemOportunidadeEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemOportunidadeEdit').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#valorUnitarioItemProposta').on('change', function(){
    let quantidade = $('#quantidadeItemProposta').val();
    let valorUnitario = $(this).val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $('#percentualDescontoItemProposta').val() ? ($('#percentualDescontoItemProposta').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemProposta').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemProposta').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#valorUnitarioItemPropostaEdit').on('change', function(){
    let quantidade = $('#quantidadeItemPropostaEdit').val();
    let valorUnitario = $(this).val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $('#percentualDescontoItemPropostaEdit').val() ? ($('#percentualDescontoItemPropostaEdit').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemPropostaEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemPropostaEdit').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#valorUnitarioItemOportunidadeEdit').on('change', function(){
    let quantidade = $('#quantidadeItemOportunidadeEdit').val();
    let valorUnitario = $(this).val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $('#percentualDescontoItemOportunidadeEdit').val() ? ($('#percentualDescontoItemOportunidadeEdit').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemOportunidadeEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemOportunidadeEdit').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#valorUnitarioItemOportunidade').on('change', function(){
    let quantidade = $('#quantidadeItemOportunidade').val();
    let valorUnitario = $(this).val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $('#percentualDescontoItemOportunidade').val() ? ($('#percentualDescontoItemOportunidade').val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemOportunidade').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemOportunidade').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#percentualDescontoItemProposta').on('blur', function(){
    let quantidade = $('#quantidadeItemProposta').val();
    let valorUnitario = $('#valorUnitarioItemProposta').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).val('100,00').trigger('input');
        percentualDesconto = "100,00";
    } else {
        $(this).tooltip('hide');
    }

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemProposta').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }else{
            valorDesconto = 0;
            $('#valorDescontoItemProposta').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemProposta').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#percentualDescontoItemProposta').on('input', function(){
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';

    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).tooltip('show');
    } else {
        $(this).tooltip('hide');
    }
});

$('#percentualDescontoItemProposta').hover(function(){}, function(){
    $(this).tooltip('hide');
});

$('#percentualDescontoItemPropostaEdit').on('blur', function(){
    let quantidade = $('#quantidadeItemPropostaEdit').val();
    let valorUnitario = $('#valorUnitarioItemPropostaEdit').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).tooltip('show').val('100,00').trigger('input');
        percentualDesconto = "100,00";
    } else {
        $(this).tooltip('hide');
    }

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemPropostaEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }else{
            valorDesconto = 0;
            $('#valorDescontoItemPropostaEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemPropostaEdit').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#percentualDescontoItemPropostaEdit').on('input', function(){
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
    
    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).tooltip('show');
    } else {
        $(this).tooltip('hide');
    }
});

$('#percentualDescontoItemPropostaEdit').hover(function(){}, function(){
    $(this).tooltip('hide');
});

$('#percentualDescontoItemOportunidade').on('blur', function(){
    let quantidade = $('#quantidadeItemOportunidade').val();
    let valorUnitario = $('#valorUnitarioItemOportunidade').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).tooltip('show').val('100,00').trigger('input');
        percentualDesconto = "100,00";
    } else {
        $(this).tooltip('hide');
    }

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemOportunidade').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }else{
            valorDesconto = 0;
            $('#valorDescontoItemOportunidade').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemOportunidade').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#percentualDescontoItemOportunidade').on('input', function(){
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
    
    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).tooltip('show');
    } else {
        $(this).tooltip('hide');
    }
});

$('#percentualDescontoItemOportunidade').hover(function(){}, function(){
    $(this).tooltip('hide');
});

$('#percentualDescontoItemOportunidadeEdit').on('blur', function(){
    let quantidade = $('#quantidadeItemOportunidadeEdit').val();
    let valorUnitario = $('#valorUnitarioItemOportunidadeEdit').val();
    valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
    let valorTotal = 0;
    let valorDesconto = 0;

    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).tooltip('show').val('100,00').trigger('input');
        percentualDesconto = "100,00";
    } else {
        $(this).tooltip('hide');
    }

    if (quantidade >= 0 && valorUnitario >= 0) {
        valorTotal = quantidade * valorUnitario;
        if (percentualDesconto){
            valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
            valorTotal = valorTotal - valorDesconto;
            $('#valorDescontoItemOportunidadeEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }else{
            valorDesconto = 0;
            $('#valorDescontoItemOportunidadeEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
        $('#valorTotalItemOportunidadeEdit').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
    }
});

$('#percentualDescontoItemOportunidadeEdit').on('input', function(){
    let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
    
    if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
        $(this).tooltip('show');
    } else {
        $(this).tooltip('hide');
    }
});

$('#percentualDescontoItemOportunidadeEdit').on('keypress',function(e) {
    if(e.which == 13) {
        let quantidade = $('#quantidadeItemOportunidadeEdit').val();
        let valorUnitario = $('#valorUnitarioItemOportunidadeEdit').val();
        valorUnitario = formataInsercao(valorUnitario.replace('R$', '').trim());
        let percentualDesconto = $(this).val() ? ($(this).val()).replace('%', '') : '';
        let valorTotal = 0;
        let valorDesconto = 0;

        if (percentualDesconto && (parseFloat(percentualDesconto.replace(',', '.')) > 100.00)) {
            $(this).tooltip('show').val('100,00').trigger('input');
            percentualDesconto = "100,00";
        } else {
            $(this).tooltip('hide');
        }

        if (quantidade >= 0 && valorUnitario >= 0) {
            valorTotal = quantidade * valorUnitario;
            if (percentualDesconto){
                valorDesconto = valorTotal * (parseFloat(percentualDesconto.replace(',', '.')) / 100);
                valorTotal = valorTotal - valorDesconto;
                $('#valorDescontoItemOportunidadeEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
            }else{
                valorDesconto = 0;
                $('#valorDescontoItemOportunidadeEdit').val(valorDesconto.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
            }
            $('#valorTotalItemOportunidadeEdit').val(valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
        }
    }
});

$('#percentualDescontoItemOportunidadeEdit').hover(function(){}, function(){
    $(this).tooltip('hide');
});

function removerItemTabelaComposicao(botao, idRowIndex){
    let id = idRowIndex;

    AgGridItensComposicao.gridOptions.api.applyTransaction({remove: [AgGridItensComposicao.gridOptions.api.getDisplayedRowAtIndex(id).data]});
}

async function removerItemAddEdit(botao, idRowIndex, itensComposicao){
    let id = parseInt(idRowIndex);
    if (itensComposicao && itensComposicao != 0) {
        let continuar = true;
        
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os itens associados a ele também serão removidos. Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }

        let itensRemocao = [];
        for (let i = 0; i <= itensComposicao; i++) {
            itensRemocao.push(AgGridItensPropostaAddEdit.gridOptions.api.getRowNode(id + i).data);
        }

        AgGridItensPropostaAddEdit.gridOptions.api.applyTransaction({remove: itensRemocao});
    } else {
        AgGridItensPropostaAddEdit.gridOptions.api.applyTransaction({remove: [AgGridItensPropostaAddEdit.gridOptions.api.getRowNode(id).data]});
    }
}

async function removerItemTabela(botao, idRowIndex, itensComposicao){
    let id = parseInt(idRowIndex);

    if (itensComposicao && itensComposicao != 0) {
        let continuar = true;
        
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os itens associados a ele também serão removidos. Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }

        let itensRemocao = [];
        for (let i = 0; i <= itensComposicao; i++) {
            itensRemocao.push(AgGridItensProposta.gridOptions.api.getRowNode(id + i).data);
        }

        AgGridItensProposta.gridOptions.api.applyTransaction({remove: itensRemocao});
    } else {
        AgGridItensProposta.gridOptions.api.applyTransaction({remove: [AgGridItensProposta.gridOptions.api.getRowNode(id).data]});
    }
}

async function removerItemTabelaOportunidade(botao, idRowIndex, itensComposicao){
    let id = parseInt(idRowIndex);

    if (itensComposicao && itensComposicao != 0) {
        let continuar = true;
        
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os itens associados a ele também serão removidos. Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }

        let itensRemocao = [];
        for (let i = 0; i <= itensComposicao; i++) {
            itensRemocao.push(AgGridItensOportunidade.gridOptions.api.getRowNode(id + i).data);
        }

        AgGridItensOportunidade.gridOptions.api.applyTransaction({remove: itensRemocao});
    } else {
        AgGridItensOportunidade.gridOptions.api.applyTransaction({remove: [AgGridItensOportunidade.gridOptions.api.getRowNode(id).data]});
    }
}

async function removerItemAddEditOportunidade(botao, idRowIndex, itensComposicao){
    let id = parseInt(idRowIndex);

    if (itensComposicao && itensComposicao != 0) {
        let continuar = true;
        
        await Swal.fire({
            title: "Atenção! Esse produto possui composição.",
            text: "Os itens associados a ele também serão removidos. Deseja continuar?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                continuar = true
            } else {
                continuar = false
            }
        });

        if (!continuar) {
            return;
        }

        let itensRemocao = [];
        for (let i = 0; i <= itensComposicao; i++) {
            itensRemocao.push(AgGridItensOportunidadeAddEdit.gridOptions.api.getRowNode(id + i).data);
        }

        AgGridItensOportunidadeAddEdit.gridOptions.api.applyTransaction({remove: itensRemocao});
    } else {
        AgGridItensOportunidadeAddEdit.gridOptions.api.applyTransaction({remove: [AgGridItensOportunidadeAddEdit.gridOptions.api.getRowNode(id).data]});
    }
}

$('#enderecoFatura').on('change', function(){
    if($(this).val() == '1'){
        $('#divEnderecoFatura').show();
        $('#cepFatura').attr('required', true);
        $('#ruaFatura').attr('required', true);
        $('#numeroFatura').attr('required', true);
        $('#bairroFatura').attr('required', true);
        $('#cidadeFatura').attr('required', true);
        $('#estadoFatura').attr('required', true);
    }else{
        $('#divEnderecoFatura').hide();
        $('#complementoFatura').val('');
        $('#numeroFatura').val('');
        $('#bairroFatura').val('');
        $('#cidadeFatura').val('');
        $('#estadoFatura').val('');
        $('#cepFatura').val('');
        $('#ruaFatura').val('');
        $('#cepFatura').attr('required', false);
        $('#ruaFatura').attr('required', false);
        $('#numeroFatura').attr('required', false);
        $('#bairroFatura').attr('required', false);
        $('#cidadeFatura').attr('required', false);
        $('#estadoFatura').attr('required', false);
    }
});

$('#enderecoPagamentoOportunidade').on('change', function(){
    if($(this).val() == '1'){
        $('#divEnderecoPagamentoOportunidade').show();
        $('#cepPagamentoOportunidade').attr('required', true);
        $('#ruaPagamentoOportunidade').attr('required', true);
        $('#numeroPagamentoOportunidade').attr('required', true);
        $('#bairroPagamentoOportunidade').attr('required', true);
        $('#cidadePagamentoOportunidade').attr('required', true);
        $('#estadoPagamentoOportunidade').attr('required', true);
    }else{
        $('#divEnderecoPagamentoOportunidade').hide();
        $('#complementoPagamentoOportunidade').val('');
        $('#numeroPagamentoOportunidade').val('');
        $('#bairroPagamentoOportunidade').val('');
        $('#cidadePagamentoOportunidade').val('');
        $('#estadoPagamentoOportunidade').val('');
        $('#cepPagamentoOportunidade').val('');
        $('#ruaPagamentoOportunidade').val('');
        $('#cepPagamentoOportunidade').attr('required', false);
        $('#ruaPagamentoOportunidade').attr('required', false);
        $('#numeroPagamentoOportunidade').attr('required', false);
        $('#bairroPagamentoOportunidade').attr('required', false);
        $('#cidadePagamentoOportunidade').attr('required', false);
        $('#estadoPagamentoOportunidade').attr('required', false);
    }
});

$('#enderecoFaturaOportunidade').on('change', function(){
    if($(this).val() == '1'){
        $('#divEnderecoFaturaOportunidade').show();
        $('#cepFaturaOportunidade').attr('required', true);
        $('#ruaFaturaOportunidade').attr('required', true);
        $('#numeroFaturaOportunidade').attr('required', true);
        $('#bairroFaturaOportunidade').attr('required', true);
        $('#cidadeFaturaOportunidade').attr('required', true);
        $('#estadoFaturaOportunidade').attr('required', true);
    }else{
        $('#divEnderecoFaturaOportunidade').hide();
        $('#complementoFaturaOportunidade').val('');
        $('#numeroFaturaOportunidade').val('');
        $('#bairroFaturaOportunidade').val('');
        $('#cidadeFaturaOportunidade').val('');
        $('#estadoFaturaOportunidade').val('');
        $('#cepFaturaOportunidade').val('');
        $('#ruaFaturaOportunidade').val('');
        $('#cepFaturaOportunidade').attr('required', false);
        $('#ruaFaturaOportunidade').attr('required', false);
        $('#numeroFaturaOportunidade').attr('required', false);
        $('#bairroFaturaOportunidade').attr('required', false);
        $('#cidadeFaturaOportunidade').attr('required', false);
        $('#estadoFaturaOportunidade').attr('required', false);
    }
});

$('#enderecoPagamento').on('change', function(){
    if($(this).val() == '1'){
        $('#divEnderecoPagamento').show();
        $('#cepPagamento').attr('required', true);
        $('#ruaPagamento').attr('required', true);
        $('#numeroPagamento').attr('required', true);
        $('#bairroPagamento').attr('required', true);
        $('#cidadePagamento').attr('required', true);
        $('#estadoPagamento').attr('required', true);
    }else{
        $('#divEnderecoPagamento').hide();
        $('#complementoPagamento').val('');
        $('#numeroPagamento').val('');
        $('#bairroPagamento').val('');
        $('#cidadePagamento').val('');
        $('#estadoPagamento').val('');
        $('#cepPagamento').val('');
        $('#ruaPagamento').val('');
        $('#cepPagamento').attr('required', false);
        $('#ruaPagamento').attr('required', false);
        $('#numeroPagamento').attr('required', false);
        $('#bairroPagamento').attr('required', false);
        $('#cidadePagamento').attr('required', false);
        $('#estadoPagamento').attr('required', false);
    }
});

$('#cepFatura').mask('00000-000');
$('#cepPagamento').mask('00000-000');
$('#telefoneAutorizador').mask('(00) 00000-0000');
$('#cepCliente').mask('00000-000');
$('#cepFaturaOportunidade').mask('00000-000');
$('#cepPagamentoOportunidade').mask('00000-000');

$('#cepFatura').on('blur', function(){
    let cep = $(this).val();
    if(cep.length == 9){
        $.ajax({
            url: 'https://viacep.com.br/ws/'+cep+'/json/',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.erro){
                    showAlert('warning', 'CEP não encontrado.');
                }else{
                    $('#ruaFatura').val(data.logradouro);
                    $('#bairroFatura').val(data.bairro);
                    $('#cidadeFatura').val(data.localidade);
                    $('#estadoFatura').val(data.uf);
                }
            },
            error: function(error){
                showAlert('error', 'Não foi possível buscar o CEP. Tente novamente.');
            }
        });
    }
});

$('#cepPagamento').on('blur', function(){
    let cep = $(this).val();
    if(cep.length == 9){
        $.ajax({
            url: 'https://viacep.com.br/ws/'+cep+'/json/',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.erro){
                    showAlert('warning', 'CEP não encontrado.');
                }else{
                    $('#ruaPagamento').val(data.logradouro);
                    $('#bairroPagamento').val(data.bairro);
                    $('#cidadePagamento').val(data.localidade);
                    $('#estadoPagamento').val(data.uf);
                }
            },
            error: function(error){
                showAlert('error', 'Não foi possível buscar o CEP. Tente novamente.');
            }
        });
    }
});

$('#cepCliente').on('blur', function(){
    let cep = $(this).val();
    if(cep.length == 9){
        $.ajax({
            url: 'https://viacep.com.br/ws/'+cep+'/json/',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.erro){
                    showAlert('warning', 'CEP não encontrado.');
                }else{
                    $('#ruaCliente').val(data.logradouro);
                    $('#bairroCliente').val(data.bairro);
                    $('#cidadeCliente').val(data.localidade);
                    $('#estadoCliente').val(data.uf);
                }
            },
            error: function(error){
                showAlert('error', 'Não foi possível buscar o CEP. Tente novamente.');
            }
        });
    }
});

$('#cepFaturaOportunidade').on('blur', function(){
    let cep = $(this).val();
    if(cep.length == 9){
        $.ajax({
            url: 'https://viacep.com.br/ws/'+cep+'/json/',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.erro){
                    showAlert('warning', 'CEP não encontrado.');
                }else{
                    $('#ruaFaturaOportunidade').val(data.logradouro);
                    $('#bairroFaturaOportunidade').val(data.bairro);
                    $('#cidadeFaturaOportunidade').val(data.localidade);
                    $('#estadoFaturaOportunidade').val(data.uf);
                }
            },
            error: function(error){
                showAlert('error', 'Não foi possível buscar o CEP. Tente novamente.');
            }
        });
    }
});

$('#cepPagamentoOportunidade').on('blur', function(){
    let cep = $(this).val();
    if(cep.length == 9){
        $.ajax({
            url: 'https://viacep.com.br/ws/'+cep+'/json/',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.erro){
                    showAlert('warning', 'CEP não encontrado.');
                }else{
                    $('#ruaPagamentoOportunidade').val(data.logradouro);
                    $('#bairroPagamentoOportunidade').val(data.bairro);
                    $('#cidadePagamentoOportunidade').val(data.localidade);
                    $('#estadoPagamentoOportunidade').val(data.uf);
                }
            },
            error: function(error){
                showAlert('error', 'Não foi possível buscar o CEP. Tente novamente.');
            }
        });
    }
});

function preencherEnderecoFaturamento(dados){
    let dadosEndereco = dados[0];
    $('#idEnderecoFatura').val(dadosEndereco.id);
    $('#cepFatura').val(dadosEndereco.cep);
    $('#ruaFatura').val(dadosEndereco.logradouro);
    $('#numeroFatura').val(dadosEndereco.numero);
    $('#complementoFatura').val(dadosEndereco.complemento);
    $('#bairroFatura').val(dadosEndereco.bairro);
    $('#cidadeFatura').val(dadosEndereco.cidade);
    $('#estadoFatura').val(dadosEndereco.uf);
}

function preencherEnderecoPagamento(dados){
    let dadosEndereco = dados[0];
    $('#idEnderecoPagamento').val(dadosEndereco.id);
    $('#cepPagamento').val(dadosEndereco.cep);
    $('#ruaPagamento').val(dadosEndereco.logradouro);
    $('#numeroPagamento').val(dadosEndereco.numero);
    $('#complementoPagamento').val(dadosEndereco.complemento);
    $('#bairroPagamento').val(dadosEndereco.bairro);
    $('#cidadePagamento').val(dadosEndereco.cidade);
    $('#estadoPagamento').val(dadosEndereco.uf);
}

function preencherEnderecoFaturamentoOportunidade(dados){
    let dadosEndereco = dados[0];
    $('#idEnderecoFaturaOportunidade').val(dadosEndereco.id);
    $('#cepFaturaOportunidade').val(dadosEndereco.cep);
    $('#ruaFaturaOportunidade').val(dadosEndereco.logradouro);
    $('#numeroFaturaOportunidade').val(dadosEndereco.numero);
    $('#complementoFaturaOportunidade').val(dadosEndereco.complemento);
    $('#bairroFaturaOportunidade').val(dadosEndereco.bairro);
    $('#cidadeFaturaOportunidade').val(dadosEndereco.cidade);
    $('#estadoFaturaOportunidade').val(dadosEndereco.uf);
}

function preencherEnderecoPagamentoOportunidade(dados){
    let dadosEndereco = dados[0];
    $('#idEnderecoPagamentoOportunidade').val(dadosEndereco.id);
    $('#cepPagamentoOportunidade').val(dadosEndereco.cep);
    $('#ruaPagamentoOportunidade').val(dadosEndereco.logradouro);
    $('#numeroPagamentoOportunidade').val(dadosEndereco.numero);
    $('#complementoPagamentoOportunidade').val(dadosEndereco.complemento);
    $('#bairroPagamentoOportunidade').val(dadosEndereco.bairro);
    $('#cidadePagamentoOportunidade').val(dadosEndereco.cidade);
    $('#estadoPagamentoOportunidade').val(dadosEndereco.uf);
}

function abrirDropdown(botaoP, id, nomeItem, tabelaNome) {
    var dropdown = $('#dropdown-menu-' +nomeItem+ '-' + id);
    var dropdownDOM = $('#dropdown-menu-' +nomeItem+ '-' + id)[0]
    var botao = $(botaoP);
    var tamBotao = botao.outerWidth();

   if (dropdown.is(':visible')) {
        dropdown.hide();
        return;
    }
    $(".dropdown-menu").hide();

    dropdown.show();

    var posicao = botaoP.getBoundingClientRect();
    var posDrop = dropdownDOM.getBoundingClientRect();
    var gridApi = tabelaNome.gridOptions.api;
    var posBordaTabelaBottom = gridApi.gridBodyCtrl.eGridBody.getBoundingClientRect().bottom;
    var posDropdownBottom = dropdownDOM.getBoundingClientRect().bottom;
    var alturaDrop = dropdownDOM.offsetHeight;
    var tamAjuste = 0;
    var propTopDropdown = window.getComputedStyle(dropdownDOM).getPropertyValue('top');
    var distancia = 0;
    
    if ((posDropdownBottom + 5) > posBordaTabelaBottom) {
        tamAjuste = alturaDrop - 40
        dropdown.css('top', tamAjuste * -1);

        var posBordaTabelaTop = gridApi.gridBodyCtrl.eGridBody.getBoundingClientRect().top;
        var posDropdownTop = dropdownDOM.getBoundingClientRect().top;
        var propLeftDrop = window.getComputedStyle(dropdownDOM).getPropertyValue('left');

        if (posDropdownTop < posBordaTabelaTop) {
            dropdown.css('top', 35);
            distancia = posicao.left + tamBotao - (posDrop.left + posDrop.width);
            dropdown.css('left', (parseInt(propLeftDrop) + distancia) - 32);
        }

    }

    if (propTopDropdown == '0px') {
        var propLeftDropAtual = window.getComputedStyle(dropdownDOM).getPropertyValue('left');
        distancia = posicao.left + tamBotao - (posDrop.left + posDrop.width);
        dropdown.css('left', (parseInt(propLeftDropAtual) + distancia) - 32);

    }
}

$(document).on('click', function (event) {
    if (!$(event.target).closest('.dropdown').length) {
        $(".dropdown-menu").hide();
    }
});

$('#produtoItemProposta').on('change', function (e) {
    var selectedData = $(this).select2('data')[0];
    var precoUnitario = selectedData.precoUnitario ? selectedData.precoUnitario : '';

    $('#valorUnitarioItemProposta').val(precoUnitario ? precoUnitario.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00');
    $('#valorUnitarioItemProposta').trigger('change');

    if (selectedData && selectedData.id) {
        $.ajax({
            url: RouterController + '/buscarComposicaoPorId',
            type: 'POST',
            data: {
                id: selectedData.id,
                status: 'Ativo'
            },
            dataType: 'json',
            beforeSend: function () {
                ShowLoadingScreen();
            },
            success: function (data) {
                if (data && data.status == 200) {
                    itensComposicaoProduto = data.resultado;
                } else if (data && data.status == 400 || data.status == 404) {
                    itensComposicaoProduto = [];
                } else {
                    showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                    itensComposicaoProduto = [];
                    $(this).val('').trigger('change');
                }
                HideLoadingScreen();
            },
            error: function () {
                showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                itensComposicaoProduto = [];
                $(this).val('').trigger('change');
                HideLoadingScreen();
            }
        });
    }
});

$('#produtoItemPropostaEdit').on('change', function (e) {
    var selectedData = $(this).select2('data')[0];

    if (selectedData) {
        var precoUnitario = selectedData.precoUnitario || '';

        $('#valorUnitarioItemPropostaEdit').val(precoUnitario ? precoUnitario.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00');
        $('#valorUnitarioItemPropostaEdit').trigger('change');
    } else {
        $('#valorUnitarioItemPropostaEdit').val('R$ 0,00');
        $('#valorUnitarioItemPropostaEdit').trigger('change');
    }

    if (selectedData && selectedData.id) {
        $.ajax({
            url: RouterController + '/buscarComposicaoPorId',
            type: 'POST',
            data: {
                id: selectedData.id,
                status: 'Ativo'
            },
            dataType: 'json',
            beforeSend: function () {
                ShowLoadingScreen();
            },
            success: function (data) {
                if (data && data.status == 200) {
                    itensComposicaoProduto = data.resultado;
                } else if (data && data.status == 400 || data.status == 404) {
                    itensComposicaoProduto = [];
                } else {
                    showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                    itensComposicaoProduto = [];
                    $(this).val('').trigger('change');
                }
                HideLoadingScreen();
            },
            error: function () {
                showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                itensComposicaoProduto = [];
                $(this).val('').trigger('change');
                HideLoadingScreen();
            }
        });
    } else {
        itensComposicaoProduto = [];
    }
});

$('#produtoItemOportunidade').on('change', function (e) {
    var selectedData = $(this).select2('data')[0];
    var precoUnitario = selectedData.precoUnitario ? selectedData.precoUnitario : '';

    $('#valorUnitarioItemOportunidade').val(precoUnitario ? precoUnitario.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00');
    $('#valorUnitarioItemOportunidade').trigger('change');

    if (selectedData && selectedData.id) {
        $.ajax({
            url: RouterController + '/buscarComposicaoPorId',
            type: 'POST',
            data: {
                id: selectedData.id,
                status: 'Ativo'
            },
            dataType: 'json',
            beforeSend: function () {
                ShowLoadingScreen();
            },
            success: function (data) {
                if (data && data.status == 200) {
                    itensComposicaoProduto = data.resultado;
                } else if (data && data.status == 400 || data.status == 404) {
                    itensComposicaoProduto = [];
                } else {
                    showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                    itensComposicaoProduto = [];
                    $(this).val('').trigger('change');
                }
                HideLoadingScreen();
            },
            error: function () {
                showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                itensComposicaoProduto = [];
                $(this).val('').trigger('change');
                HideLoadingScreen();
            }
        });
    } else {
        itensComposicaoProduto = [];
    }
});

$('#produtoItemOportunidadeEdit').on('change', function (e) {
    var selectedData = $(this).select2('data')[0];

    if (selectedData) {
        var precoUnitario = selectedData.precoUnitario || '';

        $('#valorUnitarioItemOportunidadeEdit').val(precoUnitario ? precoUnitario.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00');
        $('#valorUnitarioItemOportunidadeEdit').trigger('change');
    } else {
        $('#valorUnitarioItemOportunidadeEdit').val('R$ 0,00');
        $('#valorUnitarioItemOportunidadeEdit').trigger('change');
    }

    if (selectedData && selectedData.id) {
        $.ajax({
            url: RouterController + '/buscarComposicaoPorId',
            type: 'POST',
            data: {
                id: selectedData.id,
                status: 'Ativo'
            },
            dataType: 'json',
            beforeSend: function () {
                ShowLoadingScreen();
            },
            success: function (data) {
                if (data && data.status == 200) {
                    itensComposicaoProduto = data.resultado;
                } else if (data && data.status == 400 || data.status == 404) {
                    itensComposicaoProduto = [];
                } else {
                    showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                    itensComposicaoProduto = [];
                    $(this).val('').trigger('change');
                }
                HideLoadingScreen();
            },
            error: function () {
                showAlert('warning', 'Não foi possível obter os dados do produto! Tente novamente.');
                itensComposicaoProduto = [];
                $(this).val('').trigger('change');
                HideLoadingScreen();
            }
        });
    } else {
        itensComposicaoProduto = [];
    }
});



document.addEventListener('keydown', function(event) {
    var key = event.key || event.code;
    if (key === 'Enter' && enterButton === true) {
        if ($('#modalItemProposta').css('display') === 'block') {
            if (!$('#observacaoItemProspotaEdit').is(':focus')) {
                event.preventDefault();
            }
        }else if ($('#modalProposta').css('display') === 'block'){
            if ($('#tab-itensDaProposta').attr('aria-expanded') === 'true') {
                if (!$('#observacaoItemProspota').is(':focus')) {
                    event.preventDefault();
                }
            }
        }
    }
});

$('#idCliente').change(function(){
    var id = $(this).val();
    let route = RouterController + '/buscarEnderecoClienteId';

    if (id) {
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',
            data: {
                idCliente: id
            },
            beforeSend: function(){
                limparCamposEnderecoCliente();
                $('#spanSemEndereco').hide();
                $('#spinnerCliente').show();
            },
            success: function(data){
                if (data.status == 200) {
                    preencherEnderecoCliente(data.resultado);
                }else{
                    showAlert('warning', 'Não foi possível buscar o endereço do cliente. Tente novamente.');
                    $('#spinnerCliente').hide();
                }
            },
            error: function(error){
                showAlert('error', 'Não foi possível buscar o endereço do cliente. Tente novamente.');
                $('#spinnerCliente').hide();
            }
        });
    }
});

function preencherEnderecoCliente(dados){
    var cepCliente = dados.cep;
    cepCliente = cepCliente ? cepCliente.replace(/[^0-9]/g, '') : '';
    validaCep = cepCliente.length == 8 ? true : false;
    var cepFormatado = '';
    if (validaCep) {
        cepFormatado = cepCliente.substring(0,5) + '-' + cepCliente.substring(5,8);
        $('#divEnderecoCliente').show();
        $('#spanCepEndereco').text(cepFormatado);
        $('#spanRuaEndereco').text(dados.rua);
        $('#spanNumeroEndereco').text(dados.numero);
        $('#spanBairroEndereco').text(dados.bairro);
        $('#spanCidadeEndereco').text(dados.cidade);
        $('#spanEstadoEndereco').text(dados.uf);
        $('#spanComplementoEndereco').text(dados.complemento);
        $('#spanSemEndereco').hide();
        $('#spinnerCliente').hide();
    }else{
        limparCamposEnderecoCliente();
        $('#spanSemEndereco').show();
        $('#spinnerCliente').hide();

    }

}

function buscarDadosProposta(botao, id){
    var route = RouterController + '/buscarPropostaPorId';
    var dadosTabelaItensPropostasInfo = [];
    var btn = $(botao);
    var iconeSpinner = $('#iconLoaderInfoDadosProposta-'+id);
    
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        beforeSend: function() {
            btn.css('display', 'none');
            iconeSpinner.css('display', 'block');
            $('.btnInfo').css('pointer-events', 'none');
        },
        success: function(data) {
            if (data.status == 200) {
                preencherDadosInfoProposta(data['resultado'][0]);
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' buscar os dados da proposta');
                }else{
                    showAlert('error', 'Não foi possível buscar os dados da proposta. Tente novamente.');
                }
            }
        },
        error: function(error) {
            showAlert('error', 'Não foi possível buscar os dados da proposta. Tente novamente.');
            btn.css('display', 'block');
            iconeSpinner.css('display', 'none');
            $('.btnInfo').css('pointer-events', 'auto');
        },
        complete: function() {
            btn.css('display', 'block');
            iconeSpinner.css('display', 'none');
            $('.btnInfo').css('pointer-events', 'auto');
            
        }
    });
    $.ajax({
        cache: false,
        url: RouterController + '/buscarItensPropostaPorIdProposta',
        type: 'POST',
        data: {idProposta: id},
        dataType: 'json',
        beforeSend: function() {
            AgGridItensPropostaInfo.gridOptions.api.showLoadingOverlay();
        },
        success: function(data) {
            if (data.status == 200) {
                dadosTabelaItensPropostasInfo =  data.resultado.map(function (dado, index) {
                    return {
                        id: dado.id,
                        nomeProduto: dado.nomeProduto,
                        quantidadeProduto: dado.quantidade,
                        valorUnitario: dado.valorUnitario ? (dado.valorUnitario).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        percentualDesconto: dado.percentualDesconto ? dado.percentualDesconto + '%' : '-',
                        valorTotal: dado.valorTotal ? (dado.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        valorDesconto: dado.valorDesconto ? (dado.valorDesconto).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '-',
                        observacao: dado.observacao ? dado.observacao : '-',
                        dataCadastro: dado.dataCadastro ? formatDateTime(dado.dataCadastro) : '-',
                        dataUpdate: dado.dataUpdate ? formatDateTime(dado.dataUpdate) : '-',
                    };
                })
                atualizarAgGridItensPropostaInfo(dadosTabelaItensPropostasInfo);
            }else{
                AgGridItensPropostaInfo.gridOptions.api.hideOverlay();
                AgGridItensPropostaInfo.gridOptions.api.setRowData([]);
            }   
        },
        error: function(error) {
            showAlert('error', 'Não foi possível listar os itens da proposta. Tente novamente.');
            AgGridItensPropostaInfo.gridOptions.api.hideOverlay();
            AgGridItensPropostaInfo.gridOptions.api.setRowData([]);
        },
    });
}

async function preencherDadosInfoProposta(dados){ 
    ShowLoadingScreen();
    let dadosEnderecoFatura = (dados.enderecoFaturamento).length > 0 ? dados.enderecoFaturamento[0] : '';
    let dadosEnderecoPagamento = (dados.enderecoPagamento).length > 0 ? dados.enderecoPagamento[0] : '';

    $('#spanIdProposta').text(dados.id);
    $('#spanAfProposta').text(dados.af != null && dados.af != "null" && dados.af != "" ? dados.af : '-');
    $('#spanNomeClienteInfoKanban').text(dados.nomeCliente ? dados.nomeCliente : '-');
    $('#spanNomeVendedor').text(dados.nomeVendedor ? dados.nomeVendedor : '-');
    $('#spanNomeAutorizador').text(dados.nomeAutorizador ? dados.nomeAutorizador : '-');
    $('#spanDocumentoAutorizador').text(dados.documentoAutorizador ? formataCpfCnpjExibicao(dados.documentoAutorizador) : '-');
    $('#spanEmailAutorizador').text(dados.emailAutorizador ? dados.emailAutorizador : '-');
    $('#spanTelefoneAutorizador').text(dados.telefoneAutorizador ? formataTelefoneExibirTabela(dados.telefoneAutorizador) : '-');
    $('#spanObservacaoAutorizacao').text(dados.observacao ? dados.observacao : '-');
    $('#spanFormaPagamento').text(dados.formaPagamento ? dados.formaPagamento : '-');
    $('#spanRecorrencia').text(dados.recorrencia == 1 ? 'Recorrente' : 'Não Recorrente');
    $('#spanPermitePoc').text(dados.permitePoc == 1 ? 'Sim' : 'Não');
    $('#spanDataVencimentoProposta').text(dados.dataVencimento ? formataDataInserir(dados.dataVencimento) : '-');
    $('#spanDiaVencimentoBoleto').text(dados.diaVencimento ? dados.diaVencimento : '-');
    $('#spanValorTotalProposta').text(dados.valorTotal ? (dados.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00');
    $('#spanStatusIntegracao').text(dados.statusIntegracao == 'NAO_INTEGRADO' ? 'Não Integrado' : dados.statusIntegracao == 'INTEGRADO' ? 'Integrado' : dados.statusIntegracao == 'ATUALIZADO' ? 'Atualizado' : dados.statusIntegracao == 'FATURADO' ? 'Faturado' : '-');
    $('#spanEnderecoFatura').text(dados.enderecoFatura == 1 ? 'Sim' : 'Não');
    $('#spanEnderecoPagamento').text(dados.enderecoPagto == 1 ? 'Sim' : 'Não');

    if (dados.enderecoFatura == 1) {
        $('#spanCepFatura').text(dadosEnderecoFatura.cep ? dadosEnderecoFatura.cep : '-');
        $('#spanRuaFatura').text(dadosEnderecoFatura.logradouro ? dadosEnderecoFatura.logradouro : '-');
        $('#spanNumeroFatura').text(dadosEnderecoFatura.numero ? dadosEnderecoFatura.numero : '-');
        $('#spanBairroFatura').text(dadosEnderecoFatura.bairro ? dadosEnderecoFatura.bairro : '-');
        $('#spanCidadeFatura').text(dadosEnderecoFatura.cidade ? dadosEnderecoFatura.cidade : '-');
        $('#spanUfFatura').text(dadosEnderecoFatura.uf ? dadosEnderecoFatura.uf : '-');
        $('#spanComplementoFatura').text(dadosEnderecoFatura.complemento ? dadosEnderecoFatura.complemento : '-');
        $('#divInfoDadosEnderecoFatura').show();
    }

    if (dados.enderecoPagto == 1) {
        $('#spanCepPagamento').text(dadosEnderecoPagamento.cep ? dadosEnderecoPagamento.cep : '-');
        $('#spanRuaPagamento').text(dadosEnderecoPagamento.logradouro ? dadosEnderecoPagamento.logradouro : '-');
        $('#spanNumeroPagamento').text(dadosEnderecoPagamento.numero ? dadosEnderecoPagamento.numero : '-');
        $('#spanBairroPagamento').text(dadosEnderecoPagamento.bairro ? dadosEnderecoPagamento.bairro : '-');
        $('#spanCidadePagamento').text(dadosEnderecoPagamento.cidade ? dadosEnderecoPagamento.cidade : '-');
        $('#spanUfPagamento').text(dadosEnderecoPagamento.uf ? dadosEnderecoPagamento.uf : '-');
        $('#spanComplementoPagamento').text(dadosEnderecoPagamento.complemento ? dadosEnderecoPagamento.complemento : '-');
        $('#divInfoDadosEnderecoPagamento').show();
    }

    HideLoadingScreen();
    $('#tab-dadosPropostaInfoModal').click();
    $('#modalInformacoesPropostaKanban').modal('show');
}

function limparCamposEnderecoCliente(){
    $('#divEnderecoCliente').hide();
    $('#spanCepEndereco').text('');
    $('#spanRuaEndereco').text('');
    $('#spanNumeroEndereco').text('');
    $('#spanBairroEndereco').text('');
    $('#spanCidadeEndereco').text('');
    $('#spanEstadoEndereco').text('');
    $('#spanComplementoEndereco').text('');
}

$('#idCliente').on('select2:clear', function (e) {
    limparCamposEnderecoCliente();
});
$('#limparTabelaComposicao').click(function(e) {
    e.preventDefault();
    atualizarAgGridItensComposicao();
});
$('#limparTabelaItensCadProposta').click(function(e) {
    e.preventDefault();
    atualizarAgGridItensProposta();
});
$('#limparTabelaItensEditProposta').click(function(e) {
    e.preventDefault();
    atualizarAgGridItensPropostaAddEdit();
});

$('#limparTabelaItensCadOportunidade').click(function(e) {
    e.preventDefault();
    atualizarAgGridItensOportunidades();
});

$('#limparTabelaItensEditOportunidade').click(function(e) {
    e.preventDefault();
    atualizarAgGridItensOportunidadeAddEdit();
});

$('#tab-dadosPropostaInfoModal').on('click', function(){
    $('#divDadosDaPropostaModalInfo').show();
    $('#divItensDaPropostaModalInfo').hide();
});

$('#tab-itensPropostaInfoModal').on('click', function(){
    $('#divItensDaPropostaModalInfo').show();
    $('#divDadosDaPropostaModalInfo').hide();
});

async function enviarAutorizacao(idProposta){
    $('.dropdown-menu').hide();
    let statusIntegracao = '';
    ShowLoadingScreen();
    statusIntegracao = await buscarStatusIngracaoProposta(idProposta);

    if (statusIntegracao == 'NAO_INTEGRADO' || statusIntegracao == 'ATUALIZADO') {
        enviarEmailAutoricaoFaturamento(idProposta);
    }else{
        showAlert('warning', 'Não é possível enviar autorização de faturamento para esta proposta com status de integração '+ statusIntegracao +'.');
        HideLoadingScreen();
    }

}

async function buscarStatusIngracaoProposta(idProposta){
    let route = RouterController + '/buscarPropostaPorId';
    let statusIntegracao = '';

    await $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: {
            id: idProposta
        },
        success: function(data){
            if (data.status == 200) {
                if (data.resultado[0]) {
                    statusIntegracao = data.resultado[0].statusIntegracao;
                }else{
                    statusIntegracao = '';
                }
            }
        },
        error: function(error){
            HideLoadingScreen();
            showAlert('error', 'Não foi possível buscar o status de integração da proposta. Tente novamente.');
        },
    });

    return statusIntegracao;
}

function enviarEmailAutoricaoFaturamento(id){
    let route = RouterController + '/enviarEmailAutorizacao';

    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: {
            idProposta: id
        },
        success: function(data){
            if (data.status == 200) {
                showAlert('success', data['resultado']['mensagem']);
            }else{
                if (data['resultado']){
                    validaMensagemRetorno(data['status'], data['resultado'], ' enviar e-mail de autorização de faturamento');
                }else{
                    showAlert('error', 'Não foi possível enviar e-mail de autorização de faturamento. Tente novamente.');
                }
            }
        },
        error: function(error){
            showAlert('error', 'Não foi possível enviar e-mail de autorização de faturamento. Tente novamente.');
            HideLoadingScreen();
        },
        complete: function(){
            HideLoadingScreen();
        }
    });
}

function alterarStatusOportunidade(idOportunidade, status, acao, mensagem){
    let route = RouterController + '/alterarStatusOportunidade';

    Swal.fire({
        title: 'Deseja realmente '+acao+' a oportunidade?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: route,
                type: 'POST',
                dataType: 'json',
                data: {
                    id: idOportunidade,
                    status: status
                },
                beforeSend: function(){
                    ShowLoadingScreen();
                },
                success: function(data){
                    if (data.status == 200) {
                        showAlert('success', 'Oportunidade '+mensagem+' com sucesso.');
                        atualizarAgGridOportunidades();
                    }else{
                        if (data['resultado']){
                            validaMensagemRetorno(data['status'], data['resultado'], ' '+acao+' a oportunidade');
                        }else{
                            showAlert('error', 'Não foi possível '+acao+' a oportunidade. Tente novamente.');
                        }
                    }
                },
                error: function(error){
                    showAlert('error', 'Não foi possível '+acao+' a oportunidade. Tente novamente.');
                    HideLoadingScreen();
                },
                complete: function(){
                    HideLoadingScreen();
                }
            }); 
        }
    });
}

async function gerarProposta(idOportunidade, documentoCliente, nome, email){
    let documento = documentoCliente ? documentoCliente.replace(/[^0-9]/g, '') : '';
    let route = RouterController + '/buscarClientesPorParametrosPaginado';
    let idCliente = '';
    let nomeCliente = '';
    
    Swal.fire({
        title: 'Deseja realmente gerar a proposta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
        }).then(async(result) => {
        if (result.isConfirmed) {
            ShowLoadingScreen();
            itensOportunidadeGerarProposta = await retornaItensOportunidade(idOportunidade);
            $.ajax({
                url: route,
                type: 'POST',
                dataType: 'json',
                data: {
                    documento: documento,
                    numeroPagina: 0,
                    tamanhoPagina: 1
                },
                success: function(data){
                    if (data.success) {
                        idCliente = data.resultado[0].id;
                        nomeCliente = data.resultado[0].nome ? idCliente + ' - ' + data.resultado[0].nome : idCliente + ' - ' + data.resultado[0].razaoSocial;
                        transformaOportunidade = true;
                        abrirModalGerarPropostaOportunidade(idOportunidade, idCliente, nomeCliente);
                    }else{
                        Swal.fire({
                            title: 'Cliente não cadastrado!',
                            text: 'É necessário cadastrrar o cliente para gerar a proposta. Deseja cadastrar o cliente?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sim',
                            cancelButtonText: 'Não'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                abrirModalCadastroClienteOportunidade(documento, email, nome);
                            }
                        });
                        HideLoadingScreen();
                    }
                },
                error: function(error){
                    showAlert('error', 'Não foi possível gerar a proposta. Tente novamente.');
                    HideLoadingScreen();
                },
            }); 
        }
    });
}

function salvaItensOportunidadeProposta(idProposta, itens){
    return new Promise((resolve, reject) => {
        let route = RouterController + '/cadastrarItensProposta';
        let itensAdd = [];

        itensAdd = itens.map(function (dado, index) {
            return {
                idProposta: idProposta,
                idProduto: dado.idProduto,
                quantidade: dado.quantidade,
                valorUnitario: dado.valorUnitario,
                percentualDesconto: dado.percentualDesconto,
                valorTotal: dado.valorTotal,
                valorDesconto: dado.valorDesconto,
                observacao: dado.observacao ? dado.observacao : null
            };
        });

        $.ajax({
            url: route,
            type: 'POST',
            dataType: 'json',
            data: {
                dados: itensAdd
            },
            success: function(data){
                if (data.status == 200) {
                    resolve(true);
                }else{
                    reject(false);
                }
            },
            error: function(error){
                reject(false);
                HideLoadingScreen();
            },
            complete: function(){
                HideLoadingScreen();
            }
        });
    });
}
$('#tipoClienteFisica').on('change', function(){
    let checked = $(this).is(':checked');

    if (checked) {
        $('.divIdentidadeCliente').show();
        $('.divDataNascimentoCliente').show();
        $('.divCpfliente').show();
        $('.divCnpjCliente').hide();
        $('.divRazaoSocialCliente').hide();
        $('.divInscricaoEstadualCliente').hide();
        $('#dataNascimentoCliente').attr('required',true)
        $('#cpfCliente').attr('required',true)
        $('#cnpjCliente').attr('required',false)
        $('#razaoSocialCliente').attr('required',false)
        $('#razaoSocialCliente').val('');
        $('#cnpjCliente').val('');
        $('#inscricaoEstadualCliente').val('');
    }
});

$('#tipoClienteJuridica').on('change', function(){
    let checked = $(this).is(':checked');

    if (checked) {
        $('.divIdentidadeCliente').hide();
        $('.divDataNascimentoCliente').hide();
        $('.divCpfliente').hide();
        $('.divCnpjCliente').show();
        $('.divRazaoSocialCliente').show();
        $('.divInscricaoEstadualCliente').show();
        $('#dataNascimentoCliente').attr('required',false)
        $('#cpfCliente').attr('required',false)
        $('#cnpjCliente').attr('required',true)
        $('#razaoSocialCliente').attr('required',true)
        $('#dataNascimentoCliente').val('');
        $('#cpfCliente').val('');
        $('#identidadeCliente').val('');
        $('#orgaoExpedidor').val('');
    }
});

function mascararNumero(input) {
    let numeroSemMascara = input.value.replace(/\D/g, '');
    input.value = numeroSemMascara;
}

function getCurrentDate() {
    var today = new Date();
    var day = String(today.getDate()).padStart(2, '0');
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var year = today.getFullYear();

    return year + '-' + month + '-' + day;
}

function validarLetras(input) {
    input.value = input.value.replace(/[^a-zA-ZÀ-ÖØ-öø-ÿ\s]/g, '');
}

function formataTelefoneExibirTabela(input) {
    let telefone = input ? input.replace(/\D/g, '').replaceAll(' ', '') : '';
    
    if (telefone){
        if (telefone.length == 11) {
            telefone = telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (telefone.length == 10) {
            telefone = telefone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else {
            telefone = input;
        }
    }else{
        telefone = '-';
    }

    return telefone;
}
$('#cpfCliente').mask('000.000.000-00', {reverse: true});
$('#cnpjCliente').mask('00.000.000/0000-00', {reverse: true});
$('#cepCliente').mask('00000-000');
$('#telefoneCliente').inputmask({
    mask: ['(99)9999-9999', '(99)99999-9999'],
    keepStatic: true
});

$('#diaVencimento').mask('00');
$('#diaVencimentoOportunidade').mask('00');

function validaMensagemRetorno(status, resposta, tipo){
    switch (status) {
        case 400:
            showAlert('warning', resposta['mensagem'] ? resposta['mensagem'] : resposta['errors'] ? resposta['errors'][0] : 'Não foi possível' + tipo + '. Verifique os campos e tente novamente.');
            break;
        case 500:
            showAlert('error', 'Não foi possível' + tipo + '. Tente novamente.');
            break;
        default:
            showAlert('error', 'Não foi possível' + tipo + '. Tente novamente.');
            break;
    };
}

var items = {};
function exibirDetalhes(id) {
    let item = items[id];
    let statusIntegracao = '-';
    let statusAutorizacao = '-';

    if (item.statusIntegracao == 'NAO_INTEGRADO'){
        statusIntegracao = "Não Integrado";
    }else if (item.statusIntegracao == 'INTEGRADO'){
        statusIntegracao = "Integrado";
    }else if (item.statusIntegracao == 'ATUALIZADO'){
        statusIntegracao = "Atualizado";
    }else if (item.statusIntegracao == 'FATURADO'){
        statusIntegracao = "Faturado";
    }

    if (item.statusAutorizacao == 'AGUARDANDO') {
        statusAutorizacao = 'Aguardando';
    }else if (item.statusAutorizacao == 'ENVIADO') {
        statusAutorizacao = 'Enviado';
    }else if (item.statusAutorizacao == 'REENVIADO') {
        statusAutorizacao = 'Reenviado';
    }else if (item.statusAutorizacao == 'RECUSADO') {
        statusAutorizacao = 'Recusado';
    }else if (item.statusAutorizacao == 'AUTORIZADO') {
        statusAutorizacao = 'Autorizado';
    }

    $('#spanIdAutorizacao').html(item.id);
    $('#spanAfAutorizacao').html(item.af ? item.af : '-');
    $('#spanNomeClienteAutorizacao').html(item.nomeCliente ? item.nomeCliente : '-');
    $('#spanCnpjClienteAutorizacao').html(item.cnpjCliente ? item.cnpjCliente : '-');
    $('#spanNomeVendedorAutorizacao').html(item.nomeVendedor ? item.nomeVendedor : '-');
    $('#spanValorTotalAutorizacao').html(item.valorTotal ? (item.valorTotal).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : 'R$ 0,00');
    $('#spanIdPropostaAutorizacao').html(item.idProposta ? item.idProposta : '-');
    $('#spanStatusIntegracaoAutorizacao').html(statusIntegracao);
    $('#spanNomeAutorizadorAutorizacao').html(item.nomeAutorizador ? item.nomeAutorizador : '-');
    $('#spanDocumentoAutorizadorAutorizacao').html(item.documentoAutorizador ? item.documentoAutorizador : '-');
    $('#spanEmailAutorizadorAutorizacao').html(item.emailAutorizador ? item.emailAutorizador : '-');
    $('#spanTelefoneAutorizadorAutorizacao').html(item.telefoneAutorizador ? item.telefoneAutorizador : '-');
    $('#spanStatusAutorizacao').html(statusAutorizacao);
    $('#spanDataAutorizacao').html(item.dataAutorizacao ? formatDateTime(item.dataAutorizacao) : '-');
    $('#spanObservacaoAutorizacao1').html(item.observacao ? item.observacao : '-');

    $('#modalInformacoesAutorizacaoKanban').modal('show');
}