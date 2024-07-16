

  <head>
   <style>   

    /* Estilos para telas menores */
    @media (max-width: 768px) {
        .kanban-column {
        flex: 40%; /* Defina a largura para 100% para ocupar a largura total da tela */
        margin: 5px 0; /* Remova as margens laterais para ocupar o espaço total disponível */
        }
    }

    .kanban-board {
        display: flex;
        flex-wrap: wrap;
      }
      .kanban-column {
        flex: 1;
        margin: 5px;
        padding: 5px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        height: 600px;
        box-sizing: border-box;
        overflow-y: scroll;
      }
      .kanban-header {
        font-size: 15px;
        font-weight: bold;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 10px;
        padding-left: 5px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        position: sticky;
        background-color: #e5e4e2;
        height: 70px;
        top: 0;
        z-index: 1; /* propriedade para garantir que o cabeçalho fique na frente das outras colunas */
        flex-direction: column;
      }
    
    .kanban-count {
        font-size: 14px;
        font-weight: bold;
        padding: 5px 5px;
        border-radius: 50%;
        background-color: #1c9de6;
        color: white;
        margin-left: 5px;
        margin-right: 5px;
        width: 3rem;
        text-align: center;
        display: inline-block;
      }
      
    
    .kanban-card {
        padding: 10px;
        box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.2);
        margin-bottom: 5px;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 12px;
        min-height: 30px; /* Definir a altura mínima desejada */
        min-width: 120px; /* Definir a largura mínima desejada */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: background-color 0.2s ease-in-out;
        cursor: move;
        
    }
    
        .kanban-card:hover {
        background-color: lightgray;
      }
      

    .fa-info-circle {
        font-size: 20px;
        color: gray;
        cursor: pointer;
        text-align: right;
        float: right;
        }

    .configurometroLoaderQuote {        
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    #loadingConfigurometroQuote {
        display: block;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pesquisaKanban{    
        font-weight: normal;
        white-space: nowrap;
        text-align: left;
        border-width: 1px;
        font-size: 14px;
        border-color: #aaa !important;
        border-radius: 4px;
        outline: none !important
    }
    .pesquisaLabelKanban{    
        font-weight: normal;
        margin-right: 10px;
    }

    #kanban_filter{
        display: flex;
        justify-content: flex-end;
        padding: 10px;
    }
    .valor-total {
        font-weight: normal; 
        font-size: 12px;
    }

    </style>
  </head>
  <body>

    <div id="kanban_filter" class="">
        <label class="pesquisaLabelKanban">Pesquisar: </label>
        <input type="search" id="pesquisaKanban" class="pesquisaKanban" placeholder="Pesquisar" aria-controls="kanban">
    </div>

    <div class="kanban-board">
        <div class="kanban-column andamento-column" id="andamento-column" >
            <div class="kanban-header">
                <div class="row"> 
                    Em andamento<span class="kanban-count andamento-count">0</span>
                </div>
                <div class="row valor-total">
                    <span class="totalValorEmAndamento"></span>  
                </div> 
            </div>         
        </div>
        <div class="kanban-column gerado-column" id="gerado-column" ondragover="dragOverHandler(event)" ondrop="dropHandler(event)">            
            <div class="kanban-header">
                <div class="row">
                    Pedido Gerado<span class="kanban-count gerado-count">0</span>
                </div>
                <div class="row valor-total">
                    <span class="totalValorPedidoGerado"></span>
                </div>
                <div class="drop-zone" ></div>
            </div>
        </div>
        <div class="kanban-column aguardando-column" id="aguardando-column" ondragover="dragOverHandler(event)" ondrop="dropEnvioAssinatura(event)">
            <div class="kanban-header">
                <div class="row">
                    Aguardando Assinatura<span class="kanban-count aguardando-count">0</span>
                </div>
                <div class="row valor-total">
                    <span class="totalValorAguardandoAssinatura"></span>
                </div>
            </div>
        </div>
        <div class="kanban-column assinada-column" id="assinada-column">
            <div class="kanban-header">
                <div class="row">
                    Em validação<span class="kanban-count assinada-count">0</span>
                </div>
                <div class="row valor-total">
                    <span class="totalValorEmValidacao"></span>
                </div>
            </div>
        </div>
        <div class="kanban-column ganha-column">
            <div class="kanban-header">
                <div class="row">
                    Proposta Ganha<span class="kanban-count ganha-count">0</span>
                </div>
                <div class="row valor-total">
                    <span class="totalValorPropostaGanha"></span>
                </div>
            </div>
        </div>
        <div class="kanban-column perdida-column">
            <div class="kanban-header">
                <div class="row">
                    Proposta Perdida<span class="kanban-count perdida-count">0</span>
                </div>
                <div class="row valor-total">
                    <span class="totalValorPropostaPerdida"></span>
                </div>
            </div>
        </div>
    </div> 

</body> 

<script>

</script>

<script>

function esvaziarKanban(){
    $('.andamento-column').children('.kanban-card').remove();
    $('.gerado-column').children('.kanban-card').remove();
    $('.aguardando-column').children('.kanban-card').remove();
    $('.assinada-column').children('.kanban-card').remove();
    $('.ganha-column').children('.kanban-card').remove();
    $('.perdida-column').children('.kanban-card').remove();

    $('.andamento-count').text(0);
    $('.gerado-count').text(0);
    $('.aguardando-count').text(0);
    $('.assinada-count').text(0);
    $('.ganha-count').text(0);
    $('.perdida-count').text(0);
    
    $('.totalValorEmAndamento').text('');
    $('.totalValorPedidoGerado').text('');
    $('.totalValorAguardandoAssinatura').text('');
    $('.totalValorEmValidacao').text('');
    $('.totalValorPropostaGanha').text('');
    $('.totalValorPropostaPerdida').text('');
}


// função para preencher o kanban com dados do servidor

var oportunidadesKanban ;

function preencherKanban(dataInicial = null, dataFinal = null) {
    documentKanbanCli = "";
  $.ajax({
    url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegarOportunidade_vendedorCRM') ?>`,
    type: "POST",
    data: {
        'emailUsuario': `<?= $_SESSION['emailUsuario']?>`,
        'dataInicial': dataInicial,
        'dataFinal': dataFinal,
    },
    dataType: 'json',
    success: function(resposta) {    
        document.getElementById('loading').style.display = 'none';   
        let oportunidades   = resposta;    
        let andamentoCount  = 0;
        let geradoCount     = 0;
        let aguardandoCount = 0;
        let assinadaCount   = 0;
        let ganhaCount      = 0;
        let perdidaCount    = 0;
        let totalValorEmAndamento = 0;
        let totalValorPedidoGerado = 0;
        let totalValorAguardandoAssinatura = 0;
        let totalValorEmValidacao = 0;
        let totalValorPropostaGanha = 0;
        let totalValorPropostaPerdida = 0;
        if (oportunidades.length) {
            oportunidadesKanban = oportunidades;
            oportunidades.forEach(oportunidade => {
                if (oportunidade?.Cliente_PF_x002e_fullname) {
                    var cliente = oportunidade?.Cliente_PF_x002e_fullname
                } else {
                    var cliente = oportunidade?.Cliente_PJ_x002e_name
                }
                var valorTotalCotacao = 0;
                if (oportunidade?.tz_valor_total_hardware){
                    valorTotalCotacao += oportunidade?.tz_valor_total_hardware
                }
                if (oportunidade?.tz_valor_total_licenca){
                    valorTotalCotacao += oportunidade?.tz_valor_total_licenca
                }
                let valorTotalFormatado= valorTotalCotacao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                if (oportunidade?.statecode == 0) {
                    if (oportunidade?.statuscode == 1) {
                        if (oportunidade?.tz_docusign_status == 419400000) {
                            //em andamento
                            andamentoCount += 1;
                            totalValorEmAndamento += valorTotalCotacao;                            
                            $('.andamento-column').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+ '<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)" ></i></div>')

                        }
                    }                
                    if (oportunidade?.statuscode == 419400002) {
                        if (oportunidade?.tz_docusign_status == 419400000) {
                            //pedido gerado
                            geradoCount += 1;
                            totalValorPedidoGerado += valorTotalCotacao;
                            $('.gerado-column').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+ '<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                        }
                        if (oportunidade?.tz_docusign_status == 419400001) {
                            //aguardando assinatura
                            aguardandoCount += 1;
                            totalValorAguardandoAssinatura += valorTotalCotacao;
                            $('.aguardando-column').append('<div class="kanban-card" style="position: relative;">'+oportunidade?.quotenumber+'<br>'+cliente+'<br>'+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                        }
                    }
                }
                if (oportunidade?.statecode == 2) {
                    if (oportunidade?.statuscode == 4) {
                        //ganha
                        ganhaCount += 1;
                        totalValorPropostaGanha += valorTotalCotacao;
                        $('.ganha-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br>'+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')
                    }
                }
                if (oportunidade?.statecode == 3) {
                    if (oportunidade?.statuscode == 5) {
                        //perdida
                        perdidaCount += 1;
                        totalValorPropostaPerdida += valorTotalCotacao;
                        $('.perdida-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br>'+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')

                    }
                } 
                if (oportunidade?.statuscode == 419400001) {
                    //em validação adv
                    assinadaCount += 1;
                    totalValorEmValidacao += valorTotalCotacao;
                    $('.assinada-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br>'+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                }
                         
                
            })
            $('.andamento-count').text(andamentoCount);
            $('.gerado-count').text(geradoCount);
            $('.aguardando-count').text(aguardandoCount);
            $('.assinada-count').text(assinadaCount);
            $('.ganha-count').text(ganhaCount);
            $('.perdida-count').text(perdidaCount);  
            $('.totalValorEmAndamento').text('Total: '+totalValorEmAndamento.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
            $('.totalValorPedidoGerado').text('Total: '+totalValorPedidoGerado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
            $('.totalValorAguardandoAssinatura').text('Total: '+totalValorAguardandoAssinatura.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
            $('.totalValorEmValidacao').text('Total: '+totalValorEmValidacao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
            $('.totalValorPropostaGanha').text('Total: '+totalValorPropostaGanha.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
            $('.totalValorPropostaPerdida').text('Total: '+totalValorPropostaPerdida.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
        }

    },
    error: function (resposta){        
         
        document.getElementById('loading').style.display = 'none';
    }
  });
}

function preencherKanbanCliente(documento, dataInicial = null, dataFinal = null) {
    documentKanbanCli = documento;
    
    $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/getOportunidadeClienteKanban') ?>`,
        type: "POST",
        data: {
            'documento': documento,
            'dataInicial': dataInicial,
            'dataFinal': dataFinal
        },
        dataType: 'json',
        success: function(resposta) {   
            document.getElementById('loading').style.display = 'none';
            let oportunidades   = resposta;    
            let andamentoCount  = 0;
            let geradoCount     = 0;
            let aguardandoCount = 0;
            let assinadaCount   = 0;
            let ganhaCount      = 0;
            let perdidaCount    = 0;
            let totalValorEmAndamento = 0;
            let totalValorPedidoGerado = 0;
            let totalValorAguardandoAssinatura = 0;
            let totalValorEmValidacao = 0;
            let totalValorPropostaGanha = 0;
            let totalValorPropostaPerdida = 0;
            if (oportunidades.length) {
                oportunidadesKanban = oportunidades;
                oportunidades.forEach(oportunidade => {
                    if (oportunidade?.CLIENTE_PF_x002e_fullname) {
                        var cliente = oportunidade?.CLIENTE_PF_x002e_fullname
                    } else {
                        var cliente = oportunidade?.CLIENTE_PJ_x002e_name
                    }
                    var valorTotalCotacao = 0;
                    if (oportunidade?.tz_valor_total_hardware){
                        valorTotalCotacao += oportunidade?.tz_valor_total_hardware
                    }
                    if (oportunidade?.tz_valor_total_licenca){
                        valorTotalCotacao += oportunidade?.tz_valor_total_licenca
                    }
                    let valorTotalFormatado= valorTotalCotacao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                    if (oportunidade?.statecode == 0) {
                        if (oportunidade?.statuscode == 1) {
                            if (oportunidade?.tz_docusign_status == 419400000) {
                                //em andamento
                                andamentoCount += 1;  
                                totalValorEmAndamento += valorTotalCotacao;                          
                                $('.andamento-column').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)" ></i></div>')

                            }
                        }                
                        if (oportunidade?.statuscode == 419400002) {
                            if (oportunidade?.tz_docusign_status == 419400000) {
                                //pedido gerado
                                totalValorPedidoGerado += valorTotalCotacao;
                                geradoCount += 1;
                                $('.gerado-column').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                            }
                            if (oportunidade?.tz_docusign_status == 419400001) {
                                //aguardando assinatura
                                aguardandoCount += 1;
                                totalValorAguardandoAssinatura += valorTotalCotacao;
                                $('.aguardando-column').append('<div class="kanban-card" style="position: relative;">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                            }
                        }
                    }
                    if (oportunidade?.statecode == 2) {
                        if (oportunidade?.statuscode == 4) {
                            //ganha
                            totalValorPropostaGanha += valorTotalCotacao;
                            ganhaCount += 1;
                            $('.ganha-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')
                        }
                    }
                    if (oportunidade?.statecode == 3) {
                        if (oportunidade?.statuscode == 5) {
                            //perdida
                            totalValorPropostaPerdida
                            perdidaCount += 1;
                            $('.perdida-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')

                        }
                    } 
                    if (oportunidade?.statuscode == 419400001) {
                        //em validação adv
                        totalValorEmValidacao += valorTotalCotacao;
                        assinadaCount += 1;
                        $('.assinada-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                    }
                            
                    
                })
                $('.andamento-count').text(andamentoCount);
                $('.gerado-count').text(geradoCount);
                $('.aguardando-count').text(aguardandoCount);
                $('.assinada-count').text(assinadaCount);
                $('.ganha-count').text(ganhaCount);
                $('.perdida-count').text(perdidaCount);  
                $('.totalValorEmAndamento').text('Total: '+totalValorEmAndamento.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
                $('.totalValorPedidoGerado').text('Total: '+totalValorPedidoGerado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
                $('.totalValorAguardandoAssinatura').text('Total: '+totalValorAguardandoAssinatura.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
                $('.totalValorEmValidacao').text('Total: '+totalValorEmValidacao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
                $('.totalValorPropostaGanha').text('Total: '+totalValorPropostaGanha.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
                $('.totalValorPropostaPerdida').text('Total: '+totalValorPropostaPerdida.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));        
            }
        },
        error: function (data){
           
            document.getElementById('loading').style.display = 'none';
        }
    });
}


function preencherKanbanPesquisa(pesquisa) {
    document.getElementById('loading').style.display = 'none';   
    pesquisa = pesquisa.toLowerCase()
    let oportunidadesFiltrado;
    if(pesquisa){
        if(documentKanbanCli){    
            oportunidadesFiltrado = oportunidadesKanban.filter(
                oportunidade => oportunidade?.quotenumber.toLowerCase().includes(pesquisa) || 
                (oportunidade?.CLIENTE_PF_x002e_fullname && oportunidade?.CLIENTE_PF_x002e_fullname.toLowerCase().includes(pesquisa)) || 
                (oportunidade?.CLIENTE_PJ_x002e_name && oportunidade?.CLIENTE_PJ_x002e_name.toLowerCase().includes(pesquisa))
            );

        }else{    
            oportunidadesFiltrado = oportunidadesKanban.filter(
                oportunidade => oportunidade?.quotenumber.toLowerCase().includes(pesquisa) || 
                (oportunidade?.Cliente_PF_x002e_fullname && oportunidade?.Cliente_PF_x002e_fullname.toLowerCase().includes(pesquisa)) || 
                (oportunidade?.Cliente_PJ_x002e_name && oportunidade?.Cliente_PJ_x002e_name.toLowerCase().includes(pesquisa))
            );
        }
    }else{
        oportunidadesFiltrado = oportunidadesKanban;
    }

    let oportunidades   = oportunidadesFiltrado;    
    let andamentoCount  = 0;
    let geradoCount     = 0;
    let aguardandoCount = 0;
    let assinadaCount   = 0;
    let ganhaCount      = 0;
    let perdidaCount    = 0;
    let totalValorEmAndamento = 0;
    let totalValorPedidoGerado = 0;
    let totalValorAguardandoAssinatura = 0;
    let totalValorEmValidacao = 0;
    let totalValorPropostaGanha = 0;
    let totalValorPropostaPerdida = 0;
    if (oportunidades.length) {
        oportunidades.forEach(oportunidade => {

            if(documentKanbanCli){    
                if (oportunidade?.CLIENTE_PF_x002e_fullname) {
                    var cliente = oportunidade?.CLIENTE_PF_x002e_fullname
                } else {
                    var cliente = oportunidade?.CLIENTE_PJ_x002e_name
                }
            }else{    
                if (oportunidade?.Cliente_PF_x002e_fullname) {
                    var cliente = oportunidade?.Cliente_PF_x002e_fullname
                } else {
                    var cliente = oportunidade?.Cliente_PJ_x002e_name
                }
            }
            var valorTotalCotacao = 0;
            if (oportunidade?.tz_valor_total_hardware){
                valorTotalCotacao += oportunidade?.tz_valor_total_hardware
            }
            if (oportunidade?.tz_valor_total_licenca){
                valorTotalCotacao += oportunidade?.tz_valor_total_licenca
            }
            let valorTotalFormatado= valorTotalCotacao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});

            if (oportunidade?.statecode == 0) {
                if (oportunidade?.statuscode == 1) {
                    if (oportunidade?.tz_docusign_status == 419400000) {
                        //em andamento
                        totalValorEmAndamento += valorTotalCotacao;
                        andamentoCount += 1;                            
                        $('.andamento-column').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)" ></i></div>')

                    }
                }                
                if (oportunidade?.statuscode == 419400002) {
                    if (oportunidade?.tz_docusign_status == 419400000) {
                        //pedido gerado
                        totalValorPedidoGerado += valorTotalCotacao;
                        geradoCount += 1;
                        $('.gerado-column').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                    }
                    if (oportunidade?.tz_docusign_status == 419400001) {
                        //aguardando assinatura
                        totalValorAguardandoAssinatura += valorTotalCotacao;
                        aguardandoCount += 1;
                        $('.aguardando-column').append('<div class="kanban-card" style="position: relative;">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                    }
                }
            }
            if (oportunidade?.statecode == 2) {
                if (oportunidade?.statuscode == 4) {
                    //ganha
                    totalValorPropostaGanha += valorTotalCotacao;
                    ganhaCount += 1;
                    $('.ganha-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')
                }
            }
            if (oportunidade?.statecode == 3) {
                if (oportunidade?.statuscode == 5) {
                    //perdida
                    totalValorPropostaPerdida += valorTotalCotacao;
                    perdidaCount += 1;
                    $('.perdida-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')

                }
            } 
            if (oportunidade?.statuscode == 419400001) {
                //em validação adv
                totalValorEmValidacao += valorTotalCotacao;
                assinadaCount += 1;
                $('.assinada-column').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<br> '+valorTotalFormatado+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
            }
                        
            
        })
        $('.andamento-count').text(andamentoCount);
        $('.gerado-count').text(geradoCount);
        $('.aguardando-count').text(aguardandoCount);
        $('.assinada-count').text(assinadaCount);
        $('.ganha-count').text(ganhaCount);
        $('.perdida-count').text(perdidaCount); 
        $('.totalValorEmAndamento').text('Total: '+totalValorEmAndamento.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
        $('.totalValorPedidoGerado').text('Total: '+totalValorPedidoGerado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
        $('.totalValorAguardandoAssinatura').text('Total: '+totalValorAguardandoAssinatura.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
        $('.totalValorEmValidacao').text('Total: '+totalValorEmValidacao.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
        $('.totalValorPropostaGanha').text('Total: '+totalValorPropostaGanha.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));
        $('.totalValorPropostaPerdida').text('Total: '+totalValorPropostaPerdida.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}));         
        
    }
}
// preencher o kanban quando a página for carregada
var documentKanbanCli = "";
$(document).ready(function() {
    // let documentKanbanCli;
    documentKanbanCli = "<?php  echo isset($_SESSION['ClienteKanbanDoc']) ? $_SESSION['ClienteKanbanDoc'] : "";  ?>" 

    document.getElementById('loading').style.display = 'block';
    
    if(documentKanbanCli){
        preencherKanbanCliente(documentKanbanCli)
    }else{
        preencherKanban();
    }
  
    $("#minhasOportunidades").click(async function(e) {
        e.preventDefault();
        esvaziarKanban();
        document.getElementById('loading').style.display = 'block';
        $("#documentoKanban").val("")
        preencherKanban();
    })
});

</script>


<script type="text/javascript">

    var elements = document.querySelectorAll('#resumoCotacaoQuote');

    for (var i = 0; i < elements.length; i++) {
        elements[i].id = 'resumoCotacaoQuote' + i;
    }
</script>

<script>

    let draggedCard; // define draggedCard as a global variable

    const dropZone = document.querySelector('.drop-zone');
    dropZone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropZone.classList.add('hover');
    });

    dropZone.addEventListener('dragleave', (event) => {
        dropZone.classList.remove('hover');
    });

    dropZone.addEventListener('drop', (event) => {
        draggedCard = document.querySelector('.dragging');
        dropZone.before(draggedCard);
        dropZone.classList.remove('hover');
    });

    const geradoColumn = document.querySelector("#gerado-column");
    geradoColumn.addEventListener("dragover", (event) => {
        event.preventDefault();
      
        const draggable = document.querySelector(".dragging");
       
    });

   
    function dragStartHandler(event) {
        let div = event.target;
        event.dataTransfer.setData('text/plain', div.getAttribute('data_statuscode'));
        event.target.classList.add('dragged');
    }

    function dragOverHandler(event) {
        event.preventDefault();
    }

    async function dropHandler(ev) {
        ev.preventDefault();
    
        const cardText = event.dataTransfer.getData('text/plain');                          
        
        // exibir tela de carregamento
        showLoadingScreen();

        //chamar a função de integrar com o ERP e gerar pedido
        let integrado = false;
        let oportunidadeCliente  = JSON.parse(await getOportunidadesCliente());
        let oportunidadeVendedor = JSON.parse(await getOportunidadesVendedor());
        let oportunidades = oportunidadeCliente.concat(oportunidadeVendedor);
        let arrayFiltrado = oportunidades.filter(function (el) {
            return el.quoteid == cardText;
        });

        if (arrayFiltrado.length >= 1 ) {            
            if((arrayFiltrado[0].CLIENTE_PJ_x002e_zatix_codigocliente && arrayFiltrado[0].CLIENTE_PJ_x002e_zatix_codigocliente != null) || (arrayFiltrado[0].Cliente_PJ_x002e_zatix_codigocliente && arrayFiltrado[0].Cliente_PJ_x002e_zatix_codigocliente != null)){
                integrado = true;
            }  
            if((arrayFiltrado[0].CLIENTE_PF_x002e_zatix_codigocliente && arrayFiltrado[0].CLIENTE_PF_x002e_zatix_codigocliente != null) || (arrayFiltrado[0].Cliente_PF_x002e_zatix_codigocliente && arrayFiltrado[0].Cliente_PF_x002e_zatix_codigocliente != null)){
                integrado = true;
            }  
        }
         
        esvaziarKanban();
        
        let escolha = await  integrarClientePeloPedido(arrayFiltrado[0]._customerid_value, cardText);

        hideLoadingScreen();      

        if(documentKanbanCli){
            preencherKanbanCliente(documentKanbanCli)
        }else{
            preencherKanban();
        }
        $('.kanban-card dragging').hide();
       
    }
    
    async function dropEnvioAssinatura(ev) {
        ev.preventDefault();
    
        const cardText = event.dataTransfer.getData('text/plain');                          
                
        showLoadingScreen();
        esvaziarKanban();
              
        let oportunidadeCliente  = JSON.parse(await getOportunidadesCliente());
        let oportunidadeVendedor = JSON.parse(await getOportunidadesVendedor());
        let oportunidades = oportunidadeCliente.concat(oportunidadeVendedor);
        let arrayFiltradoP = oportunidades.filter(function (el) {
            return el.quoteid == cardText;
        });

        if (arrayFiltradoP.length >= 1 ) {     
            
            if ((arrayFiltradoP[0].statecode == 0) && (arrayFiltradoP[0].statuscode == 419400002) && (arrayFiltradoP[0].tz_docusign_status == 419400000)){
                //se gerado true então envia pra assinatura, se false alerta que o cliente precisa ser integrado e o pedido gerado.
                $.ajax({
                url: "<?php echo site_url('comerciaisTelevendas/Pedidos/enviarAssinaturaCRM') ?>",
                type: "POST",
                data: {
                    idCotacao: cardText
                },
                success: function(data) {                    
                    if (data) {
                        data = JSON.parse(data);
                        //alerta com a resposta do php ou alerta de erro
                        data ? alert(data?.Message) : alert("Ocorreu um problema ao Enviar Assinatura, tente novamente mais tarde.");
                    } else {
                        alert("Ocorreu um problema ao Enviar Assinatura, tente novamente mais tarde.");
                    }                     
                    
                },
                error: function(xhr, textStatus, errorThrown) {                    
                    alert("Ocorreu um erro ao processar a solicitação. Contate o administrador");
                }
            });
            }
            else
            {
                alert ("Antes de enviar para a assinatura, favor gerar o pedido");
            } 
        }               
        
        hideLoadingScreen();      
        if(documentKanbanCli){
            preencherKanbanCliente(documentKanbanCli)
        }else{
            preencherKanban();
        }
        $('.kanban-card dragging').hide();
       
    }
</script>

<script type="text/javascript">
     $("#cep-principal-erp-pf-quote").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-principal-erp-pf-quote").val(dadosRetorno.logradouro);
				$("#bairro-principal-erp-pf-quote").val(dadosRetorno.bairro);
				$("#cidade-principal-erp-pf-quote").val(dadosRetorno.localidade);
				$("#estado-principal-erp-pf-quote").val(dadosRetorno.uf);
                $("#complemento-principal-erp-pf-quote").val('')
			}catch(ex){}
		});
    })

    $("#cep-principal-erp-pj-quote").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-principal-erp-pj-quote").val(dadosRetorno.logradouro);
				$("#bairro-principal-erp-pj-quote").val(dadosRetorno.bairro);
				$("#cidade-principal-erp-pj-quote").val(dadosRetorno.localidade);
				$("#estado-principal-erp-pj-quote").val(dadosRetorno.uf);
                $("#complemento-principal-erp-pj-quote").val('')
			}catch(ex){}
		});
    })

    $("#cep-cobranca-erp-pf-quote").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-cobranca-erp-pf-quote").val(dadosRetorno.logradouro);
				$("#bairro-cobranca-erp-pf-quote").val(dadosRetorno.bairro);
				$("#cidade-cobranca-erp-pf-quote").val(dadosRetorno.localidade);
				$("#estado-cobranca-erp-pf-quote").val(dadosRetorno.uf);
                $("#complemento-cobranca-erp-pf-quote").val('')
			}catch(ex){}
		});
    })

    $("#cep-cobranca-erp-pj-quote").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-cobranca-erp-pj-quote").val(dadosRetorno.logradouro);
				$("#bairro-cobranca-erp-pj-quote").val(dadosRetorno.bairro);
				$("#cidade-cobranca-erp-pj-quote").val(dadosRetorno.localidade);
				$("#estado-cobranca-erp-pj-quote").val(dadosRetorno.uf);
                $("#complemento-cobranca-erp-pj-quote").val('')
			}catch(ex){}
		});
    })

    $("#cep-entrega-erp-pf-quote").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-entrega-erp-pf-quote").val(dadosRetorno.logradouro);
				$("#bairro-entrega-erp-pf-quote").val(dadosRetorno.bairro);
				$("#cidade-entrega-erp-pf-quote").val(dadosRetorno.localidade);
				$("#estado-entrega-erp-pf-quote").val(dadosRetorno.uf);
                $("#complemento-entrega-erp-pf-quote").val('')
			}catch(ex){}
		});
    })

    $("#cep-entrega-erp-pj-quote").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-entrega-erp-pj-quote").val(dadosRetorno.logradouro);
				$("#bairro-entrega-erp-pj-quote").val(dadosRetorno.bairro);
				$("#cidade-entrega-erp-pj-quote").val(dadosRetorno.localidade);
				$("#estado-entrega-erp-pj-quote").val(dadosRetorno.uf);
                $("#complemento-entrega-erp-pj-quote").val('')
			}catch(ex){}
		});
    })

    $(".pesquisaKanban").on('keyup paste click',function () {
        let pesquisa = this.value;
        esvaziarKanban();
        preencherKanbanPesquisa(pesquisa);
    })

</script>

<script type="text/javascript">
    
    $("#submit-form-integracao-pj-quote").click(async function(e) {
        e.preventDefault();
        let data = getDadosForm('integracaoERPPJ-quote');

        let botao = $("#submit-form-integracao-pj-quote");
        let htmlBotao = botao.html();
        botao.html(ICONS.spinner+' '+htmlBotao);
        botao.attr('disabled', true);
        
        let retorno = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/integrarClienteERP') ?>`,
            type: 'POST',
            data: data,
            success: function (response){   
                documentoERP = '';
                $("#getResumoClienteERP").click()
                
                response = JSON.parse(response)
                alert(response?.Message)
                return response?.Status
            }
        })

        retorno = JSON.parse(retorno)
        retorno =retorno?.dados?.Status

        if(retorno == 200){
            await integrarClientePeloPedido(documento, cotacaoPedido)
        }

        botao.html(htmlBotao);
        botao.attr('disabled', false);
    })

    $("#submit-form-integracao-pf-quote").click(async function(e) {
        e.preventDefault();

        let botao = $("#submit-form-integracao-pf-quote");
        let htmlBotao = botao.html();
        botao.html(ICONS.spinner+' '+htmlBotao);
        botao.attr('disabled', true);

        
        let data = getDadosForm('formIntegracaoERPPF-quote');
        // exibir tela de carregamento
        showLoadingScreen();

        let retorno = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/integrarClienteERP') ?>`,
            type: 'POST',
            data: data,
            success: function (response){   
                documentoERP = '';
                $("#getResumoClienteERP").click()
                
                response = JSON.parse(response)
                alert(response?.Message)
                return response?.Status
            }

        }) 

        hideLoadingScreen(); 
        
        retorno = JSON.parse(retorno)
        retorno =retorno?.dados?.Status

        if(retorno == 200){
            await integrarClientePeloPedido(documento, cotacaoPedido)
        }
        botao.html(htmlBotao);
        botao.attr('disabled', false);
    })
</script>
