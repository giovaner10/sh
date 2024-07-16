<style>
    .pesquisaKanbanGerenciamento,
    .pesquisaKanban {
        font-weight: normal;
        white-space: nowrap;
        text-align: left;
        font-size: 14px;
        border-radius: 4px;
        outline: none !important;
        border: 1px solid #aaa !important;
    }

    #gerenciamento_filter,
    #kanban_filter {
        display: flex;
        justify-content: flex-end;
        padding: 10px;
    }

    .kanban-board {
        display: flex;
        flex-wrap: wrap;
    }

    .kanban-column {
        flex: 1;
        margin: 5px;
        padding: 5px;
        border-radius: 5px;
        height: 100vh;
        box-sizing: border-box;
        margin-bottom: 80px !important;
    }

    .kanban-header-gerenciamento {
        font-size: 14px;
        font-weight: bold;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 10px;
        padding-left: 5px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        /* position: sticky; */
        height: 70px;
        top: 0;
        z-index: 1;
        flex-direction: column;
        border-top: 3px solid #1c9de6;
        border-bottom: 3px solid #1c9de6;
    }

    .kanban-count-gerenciamento {
        font-size: 16px;
        font-weight: bolder;
        padding: 5px 5px;
        border-radius: 50%;
        color: #1c9de6;
        margin-left: 5px;
        margin-right: 5px;
        width: 2rem;
        text-align: center;
        min-width: fit-content;
    }

    .kanban-card {
        padding: 10px;
        box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.2);
        margin-bottom: 5px;
        border-radius: 5px;
        font-size: 12px;
        min-height: 30px;
        min-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: background-color 0.2s ease-in-out;
        border-left: 3px solid #1c9de6;
    }

    .kanban-card:hover {
        background-color: lightgray;
    }

    .text-card {
        display: flex;
        flex-direction: column;
    }

    .text-card-valor {
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .kanban-column {
            flex: 100%;
            margin: 5px 0;
        }
        .kanban-column .kanban-cards {
            height: auto;
            overflow-y: auto;
        }
    }
    .dados-header{
        display: contents;
    }
    .kanban-column .kanban-cards {
        height: 100%;
        overflow-y: scroll;
    }
    @keyframes fadeInOut {
        0% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }

    .kanban-card-placeholder {
        animation: fadeInOut 2s infinite;
        background-color: #ccc;
    }
    .kanban-card-sombra{
        padding: 10px;
        box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.2);
        margin-bottom: 5px;
        border-radius: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: background-color 0.2s ease-in-out;
        background-color: #E9E9E9;
        height: 70px;

    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 25px;
    }

    .switch input {
        display:none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(34px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    .auto-kanban {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        justify-content: right;
    }
    #temporizador {
        color: orangered;
        font-size: large;
        font-weight: 700;
        margin-left: 5px;
        width: 30px;
        margin-right: 10px;
    }
    .destacadoAtivada {
        color: green;
    
    }
    .destacadoDesativada {
        color: red;
    }
    
</style>

<br>
<div class="auto-kanban">
    <label id="labelAtivarKanbanAuto" style="margin-bottom: none !important">Atualização automática <span class="destacadoDesativada"> desativada</span></label>
    <label class="switch" style="margin-left: 5px;">
      <input type="checkbox" id="checkAtualizarKanbanAuto">
      <div class="slider round"></div>
    </label>
    <span id="toggleEspace" style="width: 30px; margin-left: 15px;"></span>
    <span id="temporizador" hidden></span>
</div>
<div class="kanban-board">
    <div class="kanban-column gerados-column-gerenciamento" id="andamento-column-gerenciamento"  >
        <div class="kanban-header-gerenciamento">
            <div class="dados-header">
                Pedidos Gerados <span class="kanban-count-gerenciamento">0</span>
            </div>
        </div>    
        <div class="kanban-cards">

        </div>      
    </div>
    <div class="kanban-column geradosNF-column-gerenciamento" id="gerado-column-gerenciamento">            
        <div class="kanban-header-gerenciamento">
            <div class="dados-header">
                NF <span class="kanban-count-gerenciamento">0</span>
            </div>
        </div>
        <div class="kanban-cards">

        </div>
    </div>
    <div class="kanban-column geradoNFAmarraBI-column-gerenciamento" id="aguardando-column-gerenciamento">
        <div class="kanban-header-gerenciamento">
            <div class="dados-header">
                NF Amarra BI <span class="kanban-count-gerenciamento">0</span>
            </div>
        </div>
        <div class="kanban-cards">

        </div> 
    </div>
    <div class="kanban-column geradoNFAmarraBIExpedicao-column-gerenciamento" id="assinada-column-gerenciamento">
        <div class="kanban-header-gerenciamento">
            <div class="dados-header">
                NF Amarra BI Expedição <span class="kanban-count-gerenciamento">0</span>
            </div>
        </div>
        <div class="kanban-cards">

        </div> 
    </div>
    <div class="kanban-column geradoNFAmarraBIRomaneio-column-gerenciamento">
        <div class="kanban-header-gerenciamento">
            <div class="dados-header">
                NF Amarra BI Romaneio <span class="kanban-count-gerenciamento">0</span>
            </div>
        </div>
        <div class="kanban-cards">

        </div> 
    </div>
</div>


<script>
    $(document).ready(function () {
        var cartaoVazio = '<div class="kanban-card-sombra kanban-card-placeholder"></div>';

        function adicionarCartoesVazios(quantidade, coluna) {
            for (var i = 0; i < quantidade; i++) {
                $('.'+coluna+'-column-gerenciamento .kanban-cards').append(cartaoVazio);
            }
        }

        clearInterval(temporizadorExecucao);
        carregarKanbanPedidosGerados('listaUltimos100PedidosGerados', 'gerados', 'pedidos gerados');
        carregarKanbanPedidosGerados('listar100UltimosPedidosGeradosComNF', 'geradosNF', 'pedidos gerados com NF');
        carregarKanbanPedidosGerados('listarPedidosGeradosNFAmarraBI', 'geradoNFAmarraBI', 'pedidos gerados com NF amarra BI');
        carregarKanbanPedidosGerados('listarPedidosGeradosComNFBiExpedicao', 'geradoNFAmarraBIExpedicao', 'pedidos gerados com NF amarra BI Expedição');
        carregarKanbanPedidosGerados('listarPedidosGeradosComNFBiRomaneio', 'geradoNFAmarraBIRomaneio', 'pedidos gerados com NF amarra BI Romaneio');

        function carregarKanbanPedidosGerados(funcao, tipoClasse, tipo){
            $.ajax({
                url: "<?=site_url('levantamentoPedidos')?>/" + funcao,
                dataType: "json",
                tyooe: "GET",
                beforeSend: function () {
                    $('.'+tipoClasse+'-column-gerenciamento .kanban-cards').html('');
                    $('.'+tipoClasse+'-column-gerenciamento .kanban-count-gerenciamento').html('<i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>');
                    adicionarCartoesVazios(50, tipoClasse);

                },
                success: function (data) {
                    if (data.status == 200){
                        var gerados = data.results;
                        var html = '';
                        var geradosCount = gerados.length;

                        $('.'+tipoClasse+'-column-gerenciamento .kanban-cards').html('');
                        $('.'+tipoClasse+'-column-gerenciamento .kanban-count-gerenciamento').html('');
                        $('.'+tipoClasse+'-column-gerenciamento .kanban-count-gerenciamento').text(geradosCount);

                        gerados.forEach(function (item) {
                            var numPedido = item.numeroPedido ?? item.numero ?? item.numPedido;
                            if (funcao == 'listarPedidosGeradosComNFBiRomaneio'){
                                var numPedido2 = item.numPedido2 ?? item.numPedido2 ?? '';
                                html = '<div class="kanban-card" id="card-' + numPedido + '>';
                                html += '<div class="text-card">';
                                html += 'Número do pedido: <br><span class="text-card-valor">' + numPedido + '</span><br>';
                                html += 'Número do pedido 2: <br><span class="text-card-valor">' + numPedido2 + '</span>';
                                html += '</div>';
                                html += '</div>';
                            }else{
                                var numPedido = item.numeroPedido ?? item.numero ?? item.numPedido;
                                html = '<div class="kanban-card" id="card-' + numPedido + '>';
                                html += '<div class="text-card">';
                                html += 'Número do pedido: <br><span class="text-card-valor">' + numPedido + '</span>';
                                html += '</div>';
                                html += '</div>';
                            }

                            $('.'+tipoClasse+'-column-gerenciamento .kanban-cards').append(html);
                        });
                    }else{
                        $('.'+tipoClasse+'-column-gerenciamento .kanban-count-gerenciamento').html('0');
                        $('.'+tipoClasse+'-column-gerenciamento .kanban-cards').html('');
                        alert('Não foi possível carregar os '+ tipo +'. Tente novamente.');
                    }
                },
                error: function (data) {
                    $('.'+tipoClasse+'-column-gerenciamento .kanban-count-gerenciamento').html('0');
                    $('.'+tipoClasse+'-column-gerenciamento .kanban-cards').html('');
                    alert('Ocorreu um erro ao carregar os '+ tipo +'. Tente novamente.');
                }
            })
        }

        var temporizadorExecucao;
        $('#checkAtualizarKanbanAuto').on('change', function(){
            if ($(this).is(':checked')) {
                $('#temporizador').show();
                $('#toggleEspace').hide();
                $('#labelAtivarKanbanAuto').html('Atualização automática <span class="destacadoAtivada"> ativada</span>');
                temporizadorExecucao = setInterval(function(){
                    var tempo = $('#temporizador').text();
                    if (tempo == ''){
                        $('#temporizador').text('30s');
                    }else{
                        var tempoNum = tempo.replace('s', '');
                        var tempoAtual = parseInt(tempo);
                        var tempoNovo = tempoAtual - 1;
                        if (tempoNovo < 0){
                            carregarKanbanPedidosGerados('listaUltimos100PedidosGerados', 'gerados', 'pedidos gerados');
                            carregarKanbanPedidosGerados('listar100UltimosPedidosGeradosComNF', 'geradosNF', 'pedidos gerados com NF');
                            carregarKanbanPedidosGerados('listarPedidosGeradosNFAmarraBI', 'geradoNFAmarraBI', 'pedidos gerados com NF amarra BI');
                            carregarKanbanPedidosGerados('listarPedidosGeradosComNFBiExpedicao', 'geradoNFAmarraBIExpedicao', 'pedidos gerados com NF amarra BI Expedição');
                            carregarKanbanPedidosGerados('listarPedidosGeradosComNFBiRomaneio', 'geradoNFAmarraBIRomaneio', 'pedidos gerados com NF amarra BI Romaneio');
                            tempoNovo = 30;
                        }
                        $('#temporizador').text(tempoNovo + 's');
                    }
                }, 1000);
                
            } else {
                $('#labelAtivarKanbanAuto').html('Atualização automática <span class="destacadoDesativada"> desativada</span>');
                clearInterval(temporizadorExecucao);
                $('#toggleEspace').show();
                $('#temporizador').hide();
                $('#temporizador').text('');
            }
        });

        $('a[data-toggle="tab"]').on('click', function (e) {
            clearInterval(temporizadorExecucao);
            $('#temporizador').hide();
            $('#temporizador').text('');
        });

    });
</script>