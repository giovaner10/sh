<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css/tickets', 'layout.css') ?>">

<style>
    .legend {
        margin-top: 10px; /* Espaço entre o formulário e a legenda */
        display: flex; /* Faz com que os itens da legenda fiquem em linha */
        align-items: center; /* Alinha os itens verticalmente */
        justify-content: flex-start; /* Alinha os itens horizontalmente à esquerda */
    }
    .legend .item {
        display: flex;
        align-items: center;
        margin-right: 20px; /* Espaço entre os itens da legenda */
    }
    .legend .color-indicator {
        height: 15px;
        width: 15px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .legend .cliente-color {
        background-color: #dff0d8;
    }
    .legend .atendente-externa-color {
        background-color: #d9edf7;
    }
    .legend .atendente-interna-color {
        background-color: #f2dede;
    }
    .legend p {
        margin: 0; /* Remove a margem padrão do parágrafo */
        font-size: 14px; /* Tamanho da fonte para a legenda */
    }
    .informativo {
        margin: 10px 0;
        padding: 10px;
        background-color: #f7f7f9;
        border-left: 3px solid #d9534f;
        text-align: center;
        font-style: italic;
    }

    .message-footer {
        text-align: center;
    }

    .informativo-header {
        font-size: 0.9em;
        color: #333;
        margin-bottom: 5px;
    }

    .informativo-text {
        font-size: 1em;
        margin-bottom: 5px;
    }

    /* Estilos anteriores... */
    .message-header {
        font-size: 0.8em;
        color: #666;
        margin-bottom: 5px;
    }

    #messageInput {
        border: ridge !important;
    }

    .chat-container {
        border: 1px solid #ced4da;
        padding: 10px;
        border-radius: 5px;
    }
    
    .chat-box {
        height: 300px;
        overflow-y: scroll;
        border: 1px solid #ced4da;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .col-md-12 > label {
        font-weight: bold;
        font-family: auto;
    }

    /* Estilos anteriores... */
    .message-balloon {
        padding: 10px;
        border-radius: 15px;
        margin-bottom: 5px;
        display: block;
        width: fit-content;
        max-width: 80%;
    }

    .atendente-externa-message {
        background-color: #d9edf7;
        margin-left: auto;
        margin-right: 0;
        text-align: left;
    }
    .atendente-interna-message {
        background-color: #f2dede;
        margin-left: auto;
        margin-right: 0;
        text-align: left;
    }
    .cliente-message {
        background-color: #dff0d8;
        margin-right: 20%;
        text-align: left;
    }
    .input-group-btn {
        width: 1%;
    }
    .iconLabel {
        font-size: xx-large;
    }
    .textLabel {
        font-size: x-large;
        font-family: fantasy;
    }
    #tagsList {
        list-style: none; /* Remove marcadores de lista */
        padding-left: 0; /* Remove o padding padrão */
    }

    #tagsList li {
        margin-bottom: 5px; /* Espaço entre as tags */
        background-color: #e9ecef; /* Cor de fundo para as tags */
        padding: 5px; /* Espaçamento interno para as tags */
        border-radius: 5px; /* Borda arredondada para as tags */
    }
    .numberRed {
        color: red;
    }

    .card-conteudo:contains("Conecto") {
        border: 1px solid #007bff;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
    }

    .typing-indicator span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #333;
        margin-right: 3px;
        animation: bounce 0.5s infinite alternate;
    }

    .container-legend-info {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    .info-call > button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .info-call{
        margin-right: 12px;
    }

    @keyframes bounce {
        to {
            transform: translateY(-50%);
        }
    }
</style>

<!-- Modal de Chat com IA -->
<div class="modal fade" id="chatIaModal" tabindex="-1" role="dialog" aria-labelledby="chatIaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatIaModalLabel">Conecto - Assitente Omnilink</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="chatAreaIa" class="chat-box"></div>
                <textarea id="userInputIa" class="form-control" rows="3" placeholder="Digite sua pergunta..."></textarea>
            </div>
            <div class="modal-footer">
                <button id="sendMessageIa" class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tagsModal" tabindex="-1" role="dialog" aria-labelledby="tagsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tagsModalLabel">Gerenciar Tags do Chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="linkedTags">
                    <h6>Tags Vinculadas:</h6>
                    <ul id="tagsList"></ul>
                </div>
                <div id="newTag" class="form-group">
                    <h6>Inserir Nova Tag:</h6>
                    <div class="input-group">
                        <select class="form-control" id="tagSelect">
                            <option value="">Selecione uma tag</option>
                            <option value="Incidente">Incidente</option>
                            <option value="Requisicao">Requisicao</option>
                            <option value="Orientacao">Orientacao</option>
                            <option value="Reclamacao">Reclamacao</option>
                            <option value="Encerrado_Inatividade">Encerrado Inatividade</option>
                            <option value="Gestor">Gestor</option>
                            <option value="OmniTurbo">OmniTurbo</option>
                            <option value="OmniDual">OmniDual</option>
                            <option value="OmniTab">OmniTab</option>
                            <option value="OmniMob">OmniMob</option>
                            <option value="Telemetria_ME">Telemetria ME</option>
                            <option value="OmniLoRa">OmniLoRa</option>
                            <option value="OmniFrota">OmniFrota</option>
                            <option value="OmniCarreta">OmniCarreta</option>
                            <option value="OmniCarga">OmniCarga</option>
                            <option value="OmniSafe">OmniSafe</option>
                            <option value="OmniTelemetria">OmniTelemetria</option>
                            <option value="OmniJornada">OmniJornada</option>
                            <option value="OmniLog">OmniLog</option>
                            <option value="OmniScore">OmniScore</option>
                            <option value="OmniCom">OmniCom</option>
                            <option value="ERP_Simplificado">ERP Simplificado</option>
                            <option value="SDK">SDK</option>
                            <option value="Acessorios">Acessorios</option>
                            <option value="Saver">Saver</option>
                            <option value="Segunda_Via_Boleto">Segunda Via Boleto</option>
                            <option value="Aviso_Pagamento">Aviso Pagamento</option>
                            <option value="Contestacao_Fatura">Contestacao Fatura</option>
                            <option value="Excendente_Satelite">Excendente Satelite</option>
                            <option value="Reset_Chip">Reset Chip</option>
                            <option value="Liberacao_Sinal">Liberacao Sinal</option>
                            <option value="Firmware">Firmware</option>
                            <option value="Cancelamento">Cancelamento</option>
                            <option value="Espelhamento_Sinal">Espelhamento Sinal</option>
                            <option value="Ficha_Ativacao">Ficha Ativacao</option>
                            <option value="Relatorio">Relatorio</option>
                            <option value="Recuperacao_Senha">Recuperacao Senha</option>
                            <option value="Venda_PJ">Venda PJ</option>
                            <option value="Venda_PF">Venda PF</option>
                            <option value="Agendamento">Agendamento</option>
                            <option value="Treinamento">Treinamento</option>
                            <option value="Desbloqueio_Emergencial">Desbloqueio Emergencial</option>
                            <option value="Troca_Titularidade">Troca Titularidade</option>
                            <option value="OmniWeb">OmniWeb</option>
                            <option value="OmniID">OmniID</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" id="addTag">Adicionar Tag</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-2 card-conteudo" style="margin: 0px 5px 5px 5px; text-align: center;">
            <i class="fa fa-suitcase iconLabel"></i>
            <label>Itens Ativos</label>
            <small class="textLabel" id="totalItensAtivos"><i class="fa fa-spinner fa-spin"></i></small>
        </div>
        <div class="col-md-2 card-conteudo" style="margin: 0px 5px 5px 20px; text-align: center;">
            <i class="fa fa-star iconLabel"></i>
            <label>Produtos Ativos</label>
            <small class="textLabel" id="totalProdutos"><i class="fa fa-spinner fa-spin"></i></small>
        </div>
        <div class="col-md-2 card-conteudo" style="margin: 0px 5px 5px 20px; text-align: center;">
            <i class="fa fa-comments iconLabel"></i>
            <label>Tickets Abertos</label>
            <small class="textLabel" id="totalTickets"><i class="fa fa-spinner fa-spin"></i></small>
        </div>
        <div class="col-md-2 card-conteudo" style="margin: 0px 5px 5px 20px; text-align: center;">
            <i class="fa fa-tag iconLabel"></i>
            <label>Tag's Vinculadas</label>
            <small class="textLabel" id="totalTags"><i class="fa fa-spinner fa-spin"></i></small>
        </div>
        <div class="col-md-2 card-conteudo" style="margin: 0px 5px 5px 20px; text-align: center;">
            <i class="fa fa-slideshare iconLabel"></i>
            <label>Conecto</label>
            <small class="textLabel" id="totalItensAtivos">Assistente</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 card-conteudo">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        Atendimento <small id="numberTicket"></small></br>
                        <small id="smallAssunto"></small></br>
                        <small id="smallDataAbertura"></small>
                    </h4>
                    
                </div>
                <div class="col-md-6">
                    <form id="formCategoria">
                        <div class="form-group" style="margin-top:10px;">
                            <div class="input-group">
                                <select class="form-control" style="border: inset;" id="departmentSelect">
                                    <option value="Suporte Técnico">Suporte Técnico</option>
                                    <option value="Atendimento ao Cliente">Atendimento ao Cliente</option>
                                    <option value="Financeiro / Cobrança">Financeiro</option>
                                    <option value="Vendas/Novos négocios">Vendas</option>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="buttonCategoria" onclick="trocaCategoria(this)">Transferir</button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="chat-container">
                <!-- Espaço do Chat -->
                <div id="messages" class="chat-box"></div>
                <div>
                    <div>
                        <div class="radio-inline mt-2">
                            <label>
                                <input type="radio" name="messageType" id="externalMessage" value="publica" checked> Pública
                            </label>
                        </div>
                        <div class="radio-inline">
                            <label>
                                <input type="radio" name="messageType" id="internalMessage" value="privada"> Privada
                            </label>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="text" id="messageInput" class="form-control" placeholder="Digite uma mensagem..." aria-label="Digite uma mensagem...">
                        <input type="file" id="fileInput" class="form-control" style="display: none;">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" style="margin-left:10px;" id="fileButton">📎</button>
                            <button class="btn btn-default" type="button" style="margin-left:5px;" id="sendButton">Enviar</button>
                        </span>
                    </div>
                </div>
                <div class="container-legend-info">
                    <div class="legend">
                        <label style="margin-right: 5px;">Legenda:</label>
                        <div class="item">
                            <span class="color-indicator cliente-color"></span>
                            <p>Cliente</p>
                        </div>
                        <div class="item">
                            <span class="color-indicator atendente-externa-color"></span>
                            <p>Pública</p>
                        </div>
                        <div class="item">
                            <span class="color-indicator atendente-interna-color"></span>
                            <p>Privada</p>
                        </div>
                    </div>
                    <div class="info-call">
                        <button class="btn" id="show-details">
                            <i class="fa fa-info"></i>
                            Detalhes
                        </button>
                    </div>

                    <div id="botao-atender" class="info-call hidden">
                        <button class="btn btn-success" id="connect-call" onclick="conectarChamada()">
                            <i class="fa fa-phone"></i>
                            Atender
                        </button>
                    </div>
                    <div id="div-encerrar" class="info-call hidden">
                        <button class="btn btn-danger" id="disconnect-call">
                            <i class="fa fa-phone"></i>
                            Encerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-md-4 card-conteudo" style="margin: 0px 10px 10px 10px;">
            <button class="btn-sm btn-default" id="buttonAction"></button>
        </div>
        <div class="row col-md-4 card-conteudo" style="margin: 0px 10px 10px 10px; min-height: 250px;">
            <h4>Cadastro do cliente</h4>
            <div class="container" id="dadosCadastrais">
            
            </div>
        </div>
        <div class="row col-md-4 card-conteudo" style="margin: 0px 10px 10px 10px;">
            <h4>Últimos tickets</h4>
            <div id="myGrid" style="height: 180px;" class="ag-theme-balham"></div>
        </div>
    </div>

    <div id="show-modal">
        <?php include_once('application/views/webdesk/caminho_usuario/modal_caminho_usuario.php'); ?>
    </div>
</div>

<script>
    const idTicket = <?= $idTicket ?>;
    var idThread = null;
    var idCliente;

    // Definição de colunas
    var columnDefs = [
        {headerName: "#", field: "id", width: 60, cellRenderer: params => {
            return `<a href="${params.value}" target="_blank" class="btn-sm btn-primary"><i class="fa fa-comments-o"></i></a>`;
        }},
        {headerName: "Assunto", field: "assunto"},
        {headerName: "Status", field: "status", width: 110, cellRenderer: params => {
            return `<span class="label label-${params.value == '3' ? 'success' : 'warning'}">${params.value == '3' ? 'Concluído' : 'Em Atendimento'}</span>`;
        }},
        {headerName: "Data Cadastro", field: "data_abertura", cellRenderer: params => {
            return formatDate(new Date(params.value));
        }}
    ];

    // Configuração do ag-Grid
    var gridOptions = {
        columnDefs: columnDefs,
        pagination: true,
        paginationPageSize: 3
    };

    // Espera a página ser carregada para criar a tabela dentro do div 'myGrid'
    document.addEventListener('DOMContentLoaded', function() {
        var gridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(gridDiv, gridOptions);
    });

    /** Função troca a categoria do ticket */
    function trocaCategoria(button) {
        // Desabilita botão e cria efeito de carregamento
        $(button).attr('disabled', true).html('Salvando');
        
        $.post('../updateCategoriaTicket', { id: idTicket, categoria: $('#departmentSelect').val() }, response => {
            if (response.status == true) {
                let data = response.data;

                insertarInformativo(data.nome_usuario, data.resposta, new Date());
            } else {
                alert('Não foi possível transferir o chamado. Tente novamente em alguns minutos!');
            }
        }, 'JSON')
        .then(e => $(button).removeAttr('disabled').html('Transferir'))
        .catch(e => alert('Não foi possível transferir o chamado. Tente novamente em alguns minutos!'));
    }

    /** Inseri informativos no chat */
    function insertarInformativo(usuario, texto, date) {
        var dateTimeStr = formatDate(new Date(date));

        var informativoDiv = document.createElement('div');
        informativoDiv.className = 'informativo';
        informativoDiv.innerHTML = `
            <div class="informativo-header">${usuario} - ${dateTimeStr}</div>
            <div class="informativo-text">${texto}</div>
        `;
        
        var messagesContainer = document.getElementById('messages');
        messagesContainer.appendChild(informativoDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scroll para a última mensagem
    }

    $(document).ready(function() {
        getDataConversa();

        $('.card-conteudo:contains("Conecto")').css('cursor', 'pointer').on('click', function() {
            $('#chatIaModal').modal('show');
        });

        // Lógica para enviar mensagens e receber respostas da IA
        $('#sendMessageIa').click(function() {
            var userInput = $('#userInputIa').val();
            if (userInput.trim() !== "") {
                $('#userInputIa').val(''); // Limpa o campo de entrada

                // Exibe a mensagem do usuário com estilo de cliente
                $('#chatAreaIa').append('<div class="message-balloon cliente-message"><b>Você:</b> ' + userInput + '</div>');

                // Insere efeito de digitação da IA
                $('#chatAreaIa').append('<div id="iaDigita" class="message-balloon atendente-externa-message"><b>Digitando ...</div>');

                $.post('../ajaxRequestConecto', { text: userInput, idThread: idThread }, response => {
                    // Remove efeito de digitação
                    $('div#iaDigita').remove();

                    if (response.status == true) {
                        // Atualiza idThread utilizada
                        idThread = response.idThread;

                        // Insere a resposta no chat
                        $('#chatAreaIa').append('<div class="message-balloon atendente-externa-message"><b>Conecto:</b> ' + response.resposta + '</div>');
                        $('#chatAreaIa').scrollTop($('#chatAreaIa')[0].scrollHeight); // Scroll to bottom
                    }
                }, 'JSON').catch(e => console.log(e));
            }
        });

        /** Função remove tag vinculada */
        $(document).on('click', '.removeTag', function() {
            $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            let tag = $(this).data('tag');
            $.post('../removeTagTicket', { nome: tag, id_ticket: idTicket }, response => {
                if (response.status == true) {
                    // Adiciona nova tag ao contador
                    let total = Number($('#totalTags > a').html()) - 1;
                    $('#totalTags').html(`<a type="button" class="${total == 0 ? 'numberRed': 'numberDefault' }" data-toggle="modal" data-target="#tagsModal">${total}</a>`);

                    $(this).parent().remove();
                } else {
                    alert(response.message);
                }

                $(this).removeAttr('disabled').html('<i class="fa fa-trash-o"></i>');
            }, 'JSON').catch(e => {
                alert('Não foi possível remover a tag. Tente novamente em alguns minutos!');
                $(this).removeAttr('disabled').html('<i class="fa fa-trash-o"></i>');
            })
        });

        /** Função adiciona nova Tag */
        $('#addTag').click(function() {
            $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Adicionando');

            var selectedTag = $('#tagSelect').val();
            if (selectedTag) {
                $.post('../addTagTicket', { id_ticket: idTicket, nome: selectedTag }, response => {
                    if (response.status == true) {
                        // Adiciona nova tag ao contador
                        let total = Number($('#totalTags > a').html()) + 1;
                        $('#totalTags').html(`<a type="button" data-toggle="modal" data-target="#tagsModal">${total}</a>`);

                        $('#tagsList').append('<li class="liTag"><button class="btn-sm btn-danger removeTag" data-tag="'+ selectedTag +'"><i class="fa fa-trash-o"></i></button> ' + selectedTag + '</li>');
                        $('#tagSelect').val('');
                    } else {
                        alert(response.message);
                    }

                    $(this).removeAttr('disabled').html('Adicionar Tag');
                }, 'JSON').catch(e => {
                    alert('Não foi possível adicionar a tag. Tente novamente em alguns minutos!');
                    $(this).removeAttr('disabled').html('Adicionar Tag');
                });
            } else {
                alert('Por favor, selecione uma tag.');
            }
        });

        /** Função envio de novas mensagens */
        $('#sendButton').click(function() {
            // Valida se mensagem está sendo enviada
            if (!$('#messageInput').val()) {
                alert('Digite a mensagem que deseja enviar.');
                return false;
            }

            // Inicia efeito do botão e o bloqueia
            $('#sendButton').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando');

            var formData = new FormData();
            formData.append('message', $('#messageInput').val());
            formData.append('type', $('input[name="messageType"]:checked').val());
            formData.append('id', idTicket);
            formData.append('id_cliente', idCliente);
            formData.append('file', $('#fileInput')[0].files[0]);

            $.ajax({
                url: '../sendMessage',
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        let element = response.data; // recupera dados de retorno
                        // Insere nova mensagem no chat
                        sendMessage(element.tipo == 'privada' ? 'internal' : 'external', element.nome_usuario, element.resposta, element.data_resposta, element.link);
                    } else {
                        // Alerta usuário do erro
                        alert(response.message);
                    }

                    // Reinicia campos de mensagens
                    $('#messageInput').val('');
                    $('#fileInput').val('');
                    $('#sendButton').removeAttr('disabled').html('Enviar');
                },
                error: function(xhr, status, error) {
                    alert('Não foi possível enviar a mensagem. Tente novamente em alguns minutos!');
                    
                    // Reinicia campos de mensagens
                    $('#messageInput').val('');
                    $('#fileInput').val('');
                    $('#sendButton').removeAttr('disabled').html('Enviar');
                }
            });
        });

        /** Função para preenchimento dos dados sobre o ticket */
        function setDataTicket(data)
        {
            $('#departmentSelect').val(data.departamento);
            $('#smallAssunto').html('Assunto: ' + data.assunto);
            $('#smallDataAbertura').html('Data da Abertura: ' + formatDate(new Date(data.data_abertura)) );
            $('#numberTicket').html('Ticket - #' + data.id);
        }

        function getDataClient(idClient)
        {
            $.getJSON(`../getDataClient/${idClient}`, response => {
                if (response.status == true) {
                    let data = response.data;
                    setDataClient({
                        nome: data.nome || '',
                        email: data.email || '',
                        telefone: data.cel || '',
                        documento: `<a href="../../PaineisOmnilink" target="_blank">${data.cnpj && data.cnpj != "" ? data.cnpj : data.cpf}</a>`,
                        endereco: data.endereco || '',
                        cadastro: formatDate(new Date(data.data_cadastro)),
                        link: data.link
                    });
                }
            });
        }

        function changeInputs(disabled = true) {
            if (disabled) {
                $("input[name='messageType']").attr('disabled', true);
                $('#messageInput').attr('disabled', true);
                $('#fileButton').attr('disabled', true);
                $('#sendButton').attr('disabled', true);
                $('#departmentSelect').attr('disabled', true);
                $('#buttonCategoria').attr('disabled', true);
            } else {
                $("input[name='messageType']").removeAttr('disabled');
                $('#messageInput').removeAttr('disabled');
                $('#fileButton').removeAttr('disabled');
                $('#sendButton').removeAttr('disabled');
                $('#departmentSelect').removeAttr('disabled');
                $('#buttonCategoria').removeAttr('disabled');
            }
        }

        function setDataTableTicket(idClient)
        {
            $.getJSON(`../getTicketsClient/${idClient}/${idTicket}`, response => {
                if (response.status == true) {
                    let data = response.data;

                    data.forEach(element => {
                        gridOptions.api.applyTransaction({add: [element]});
                    });
                } else {
                    gridOptions.api.applyTransaction({add: []})
                }
            }).catch(e => gridOptions.api.applyTransaction({add: []}));
        }

        function getTotais(idCliente) {
            $.post('../getTotaisAtendimento', { idTicket, idCliente }, response => {
                $('#totalItensAtivos').html(response.itens);
                $('#totalProdutos').html(response.produtos);
                $('#totalTickets').html(response.tickets);
                $('#totalTags').html(`<a type="button" class="${response.tags.length == 0 ? 'numberRed': 'numberDefault' }" data-toggle="modal" data-target="#tagsModal">${response.tags.length}</a>`);

                response.tags.forEach(element => {
                    $('#tagsList').append('<li class="liTag"><button class="btn-sm btn-danger removeTag" data-tag="'+ element.nome +'"><i class="fa fa-trash-o"></i></button> ' + element.nome + '</li>');
                });
            }, 'JSON')
            .catch(e => {
                $('#totalItensAtivos').html('0');
                $('#totalProdutos').html('0');
                $('#totalTickets').html('0');
                $('#totalTags').html(`<a type="button" class="numberRed" data-toggle="modal" data-target="#tagsModal">0</a>`);
            });
        }

        /** Função responsável por definir se botão será para encerrar ou reabrir o chamado */
        function setButtonAction(status)
        {
            switch (status) {
                case '3':
                    $('#buttonAction').addClass('btn-primary').attr('data-action', 'reabrir').html('Reabrir Chamado');
                    break;
                default:
                    $('#buttonAction').addClass('btn-danger').attr('data-action', 'encerrar').html('Encerrar Chamado');
                    break;
            }
        }

        /** Função encerra ou reabre chamado */
        $(document).on('click', '#buttonAction', function() {
            let action = $(this).attr('data-action');

            if (Number($('#totalTags > a').html()) == 0) {
                alert('Vincule ao menos uma "tag" ao chamado.');
                return false;
            }

            // Inicia efeito do botão
            $(this).attr('disabled', true).html(action == 'reabrir' ? `<i class="fa fa-spinner fa-spin"></i> Reabrindo` : `<i class="fa fa-spinner fa-spin"></i> Encerrando`);

            $.post('../acaoTicket', { id: idTicket, status: action == 'reabrir' ? '1' : '3' }, response => {
                if (response.status == true) {
                    let element = response.data;
                    // Insere o informativo a conversa
                    insertarInformativo(element.nome_usuario, element.resposta, element.data_resposta);

                    // Controla botões de envio de mensagens
                    changeInputs(action == 'encerrar' ? true : false);

                    // Caso sucesso, troca o botão e ação a ser executada
                    $(this).removeAttr('disabled')
                        .removeClass(action == 'reabrir' ? 'btn-primary' : 'btn-danger')
                        .addClass(action == 'reabrir' ? 'btn-danger' : 'btn-primary')
                        .attr('data-action', action == 'reabrir' ? 'encerrar' : 'reabrir')
                        .html(`${ action == 'reabrir' ? 'Encerrar Chamado' : 'Reabrir Chamado' }`);
                } else {
                    // Caso dê algum erro, retorna ao padrão
                    $(this).removeAttr('disabled').html(`${ action == 'reabrir' ? 'Reabrir Chamado' : 'Encerrar Chamado' }`);
                }
            }, 'JSON').catch(e => {
                $(this).removeAttr('disabled').attr('data-action', action == 'reabrir' ? 'encerrar' : 'reabrir').html(`${ action == 'reabrir' ? 'Encerrar Chamado' : 'Reabrir Chamado' }`);

                alert(`Não foi possível ${action == 'reabrir' ? 'reabrir': 'encerrar'} o chamado no momento. Tente novamente em alguns minutos!`);
            })

        });

        function getDataConversa()
        {
            $.getJSON(`../getDataTicket/${idTicket}`, response => {
                if (response.status == true) {
                    let data = response.data;

                    // Registra idCliente proprietário do ticket
                    idCliente = data[0].id_cliente;

                    // Controla botões de envio de mensagens
                    changeInputs(data[0].status == '3' ? true : false);

                    // Preenche dados sobre o ticket
                    setDataTicket(data[0]);

                    // Preenche dados dos ultimos tickets
                    setDataTableTicket(data[0].id_cliente);

                    // Busca dados do cliente
                    getDataClient(data[0].id_cliente);

                    // Atualiza totais topo
                    getTotais(data[0].id_cliente);

                    // Define botão de ação (Encerrar ou Reabrir)
                    setButtonAction(data[0].status);

                    data.forEach(element => {
                        if ($.inArray(element.id_usuario, ['1', '425']) != -1) {
                            if (element.status == 5) {
                                insertarInformativo(element.nome_usuario, element.resposta, element.data_resposta);
                            } else {
                                sendMessage(element.tipo == 'privada' ? 'internal' : 'external', element.nome_usuario, element.resposta, element.data_resposta, element.link);
                            }
                        } else {
                            sendMessage('cliente', element.nome_usuario, element.resposta, element.data_resposta, element.link);
                        }
                    });
                }
            }).catch(e => alert('Serviço indisponível. Tente acessar novamente em alguns minutos!'));
        }

        $('#fileButton').click(function() {
            $('#fileInput').click();
        });

        /** Função seta dados cadastrais do cliente */
        function setDataClient(dataClient)
        {
           // Criar elementos HTML para os dados do cliente
            var html = `
                <div class="row">
                    <div class="col-md-12">
                        <label>Nome:</label> ${dataClient.nome}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Documento:</label> ${dataClient.documento}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Email:</label> ${dataClient.email}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Telefone:</label> ${dataClient.telefone}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Data Cadastro:</label> ${dataClient.cadastro}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Endereco:</label> ${dataClient.endereco}
                    </div>
                </div>
            `;

            // Inserir os dados no container
            $('#dadosCadastrais').html(html);
        }

        function sendMessage(type, author, text, date, link) {
            const now = new Date(date);
            const messageClass = type === 'internal' ? 'atendente-interna-message' : (type === 'external' ? 'atendente-externa-message' : 'cliente-message');
            const headerText = `${author} - ${formatDate(now)} ${type === 'internal' ? '(Privada)' : (type === 'external' ? '(Pública)' : '')}`;

            const messageHtml = `
                <div class="message-balloon ${messageClass}" role="alert">
                    <div class="message-header">${headerText}</div>
                    ${text}
                    ${link ? `<div class="message-footer"><a href="${link}" target="_blank">Visualizar anexo</a></div>` : ''}
                </div>
            `;

            $('#messages').append(messageHtml);
            $('#messages').scrollTop($('#messages')[0].scrollHeight); // Scroll to bottom
        }
    });

    // Função para formatar a data e hora
    function formatDate(date) {
        return date.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    }

</script>

<script type="text/javascript" src="<?= versionFile('assets/js/tickets', 'caminho_usuario.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink', 'atendimento.js') ?>"></script>