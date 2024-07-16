

<head>
   <style>   

    .pesquisaKanbanGerenciamento{    
        font-weight: normal;
        white-space: nowrap;
        text-align: left;
        border-width: 1px;
        font-size: 14px;
        border-color: #aaa !important;
        border-radius: 4px;
        outline: none !important;
    }

    #gerenciamento_filter{
        display: flex;
        justify-content: flex-end;
        padding: 10px;
    }

    .kanban-card-count{
        display: flex;
    }

    
    .kanban-card-gerenciamento {
        padding: 10px;
        box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.2);
        margin-bottom: 5px;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 12px;
        min-height: 30px; /* Definir a altura mínima desejada */
        min-width: 120px; /* Definir a largura mínima desejada */
        overflow: hidden;
        text-overflow: ellipsis;
        transition: background-color 0.2s ease-in-out;
        align-items: center;
    }

    .kanban-card-info-gerenciamento{
        cursor: pointer;
        background-color: #5bc0de !important;
    }

    .kanban-card-info-gerenciamento:hover{
        background-color: #46b8da !important;
    }
    
    .kanban-count-gerenciamento {
        font-size: 14px;
        font-weight: bold;
        padding: 5px 5px;
        border-radius: 50%;
        background-color: #1c9de6;
        color: white;
        margin-left: 5px;
        margin-right: 5px;
        min-width: 3rem;
        text-align: center;
    }
    
    .kanban-header-gerenciamento {
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

    .valor-column {
        font-size: 12px;
        font-weight: 500;
    }

    .kanban-count-gerenciamento {
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

    .text-column{
        display: inline-block;
    }

    .text-card{
        display: flex;
        flex-direction: column;
    }

    .text-card-valor{
        font-weight: 600;
    }

    </style>
  </head>

<body>
    <div id="gerenciamento_filter" class="">
        <label class="pesquisaLabelKanban">Pesquisar: </label>
        <input type="search" class="pesquisaKanbanGerenciamento" placeholder="Pesquisar" aria-controls="kanban">
    </div>

    <div class="kanban-board">
        <div class="kanban-column andamento-column-gerenciamento" id="andamento-column-gerenciamento"  >
            <div class="kanban-header-gerenciamento">
                <div class="row">
                    Em andamento <span class="kanban-count-gerenciamento andamento-count-gerenciamento">0</span>
                </div>
                <div class="row valor-column">
                    Total: <span id="valor-column-andamento">R$ 0.00</span>
                </div>
            </div>            
        </div>
        <div class="kanban-column gerado-column-gerenciamento" id="gerado-column-gerenciamento">            
            <div class="kanban-header-gerenciamento">
                <div class="row">
                    Pedido Gerado<span class="kanban-count-gerenciamento gerado-count-gerenciamento">0</span>
                </div>
                <div class="row valor-column">
                    Total: <span id="valor-column-gerado">R$ 0.00</span>
                </div>
            </div>
            <div class="drop-zone" ></div>
        </div>
        <div class="kanban-column aguardando-column-gerenciamento" id="aguardando-column-gerenciamento">
            <div class="kanban-header-gerenciamento">
                <div class="row">
                    <span class="text-column">
                        Aguardando Assinatura
                    </span>
                    <span class="kanban-count-gerenciamento aguardando-count-gerenciamento">0</span>
                </div>
                <div class="row valor-column">
                    Total: <span id="valor-column-aguardando">R$ 0.00</span>
                </div>
            </div>
        </div>
        <div class="kanban-column assinada-column-gerenciamento" id="assinada-column-gerenciamento">
            <div class="kanban-header-gerenciamento">
                <div class="row">
                    Em validação<span class="kanban-count-gerenciamento assinada-count-gerenciamento">0</span>
                </div>
                <div class="row valor-column">
                    Total: <span id="valor-column-assinada">R$ 0.00</span>
                </div>
            </div>
        </div>
        <div class="kanban-column ganha-column-gerenciamento">
            <div class="kanban-header-gerenciamento">
                <div class="row">
                    Proposta Ganha<span class="kanban-count-gerenciamento ganha-count-gerenciamento">0</span>
                </div>
                <div class="row valor-column">
                    Total: <span id="valor-column-ganha">R$ 0.00</span>
                </div>
            </div>
        </div>
        <div class="kanban-column perdida-column-gerenciamento">
            <div class="kanban-header-gerenciamento">
                <div class="row">
                    Proposta Perdida<span class="kanban-count-gerenciamento perdida-count-gerenciamento">0</span>
                </div>
                <div class="row valor-column">
                    Total: <span id="valor-column-perdida">R$ 0.00</span>
                </div>
            </div>
        </div>
    </div> 
</body> 

<script>
// função para preencher o kanban com dados do servidor

var oportunidadesKanbanGerenciamento;
var oportunidadesGerenciamento = [];

function buscarOportunidadesGerenciamento(dataInicial = null, dataFinal = null) {
    documentKanbanCli = "";
    
  $.ajax({
    url: `<?= site_url('ComerciaisTelevendas/Pedidos/getVendedores') ?>`,
    type: "POST",
    data: {
        'dataInicial': dataInicial,
        'dataFinal': dataFinal,
    },
    dataType: 'json',
    success: function(resposta) { 
        document.getElementById('loading').style.display = 'none';   
        mapearGerenciamento(resposta);
        preencherKanbanGerenciamento();       

    },
    error: function (resposta){        
         
        document.getElementById('loading').style.display = 'none';
    }
  });
}

function buscarOportunidadesClienteGerenciamento(documento, dataInicial = null, dataFinal = null) {
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
            if (oportunidades.length) {
                oportunidadesKanbanGerenciamento = oportunidades;
                oportunidades.forEach(oportunidade => {
                    if (oportunidade?.CLIENTE_PF_x002e_fullname) {
                        var cliente = oportunidade?.CLIENTE_PF_x002e_fullname
                    } else {
                        var cliente = oportunidade?.CLIENTE_PJ_x002e_name
                    }
                    if (oportunidade?.statecode == 0) {
                        if (oportunidade?.statuscode == 1) {
                            if (oportunidade?.tz_docusign_status == 419400000) {
                                //em andamento
                                andamentoCount += 1;                            
                                $('.andamento-column-gerenciamento').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info-gerenciamento" onClick="javascript:abrirModalResumoCotacao(this)" ></i></div>')

                            }
                        }                
                        if (oportunidade?.statuscode == 419400002) {
                            if (oportunidade?.tz_docusign_status == 419400000) {
                                //pedido gerado
                                geradoCount += 1;
                                $('.gerado-column-gerenciamento').append('<div class="kanban-card dragging" data_statuscode="'+oportunidade?.quoteid+'" draggable="true" ondragstart="javascript:dragStartHandler(event)">'+oportunidade?.quotenumber+'<br>'+cliente+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                            }
                            if (oportunidade?.tz_docusign_status == 419400001) {
                                //aguardando assinatura
                                aguardandoCount += 1;
                                $('.aguardando-column-gerenciamento').append('<div class="kanban-card" style="position: relative;">'+oportunidade?.quotenumber+'<br>'+cliente+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                            }
                        }
                    }
                    if (oportunidade?.statecode == 2) {
                        if (oportunidade?.statuscode == 4) {
                            //ganha
                            ganhaCount += 1;
                            $('.ganha-column-gerenciamento').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')
                        }
                    }
                    if (oportunidade?.statecode == 3) {
                        if (oportunidade?.statuscode == 5) {
                            //perdida
                            perdidaCount += 1;
                            $('.perdida-column-gerenciamento').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<i data-statuscode="'+oportunidade?.quoteid+'"  ></i></div>')

                        }
                    } 
                    if (oportunidade?.statuscode == 419400001) {
                        //em validação adv
                        assinadaCount += 1;
                        $('.assinada-column-gerenciamento').append('<div class="kanban-card">'+oportunidade?.quotenumber+'<br>'+cliente+'<i data-statuscode="'+oportunidade?.quoteid+'" class="fa fa-info-circle kanban-card-info" onClick="javascript:abrirModalResumoCotacao(this)"></i></div>')
                    }                    
                })
                $('.andamento-count-gerenciamento').text(andamentoCount);
                $('.gerado-count-gerenciamento').text(geradoCount);
                $('.aguardando-count-gerenciamento').text(aguardandoCount);
                $('.assinada-count-gerenciamento').text(assinadaCount);
                $('.ganha-count-gerenciamento').text(ganhaCount);
                $('.perdida-count-gerenciamento').text(perdidaCount);          
            }
        },
        error: function (data){
           
            document.getElementById('loading').style.display = 'none';
        }
    });
}

function preencherKanbanGerenciamento(pesquisa = null) {
    if(pesquisa)
        pesquisa = pesquisa.toLowerCase()

    let oportunidadesFiltrado = oportunidadesGerenciamento;

    // Iteração pelo objeto
    for (const chave in oportunidadesFiltrado) {
        let oportunidadesVendedores = oportunidadesFiltrado[chave];
        let contador = 0;
        let valorTotalColuna = 0;
        for (const vendedor in oportunidadesVendedores) {
            
            let oportunidadesVendedor = oportunidadesVendedores[vendedor];
            let oportunidade = oportunidadesVendedor[0];

            let valorTotal = 0;
            for (let index = 0; index < oportunidadesVendedor.length; index++) {
                let op = oportunidadesVendedor[index];
                valorTotal += op['tz_valor_total_hardware'] ? op['tz_valor_total_hardware'] : 0;
                valorTotal += op['tz_valor_total_licenca'] ? op['tz_valor_total_licenca'] : 0;
            }
            valorTotalColuna += valorTotal;
            let valorTotalFormato = valorTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});

            for(const op in oportunidadesVendedor){
            }

            if(pesquisa){
                let filtrar = oportunidade?.user_vendedor_x002e_fullname.toLowerCase().includes(pesquisa);
                if(!filtrar){
                    continue;
                }
            }
            $('.'+chave+'-column-gerenciamento').append(
                '<div class="kanban-card-gerenciamento kanban-card-count" draggable="false">'+
                ' <span class="kanban-count-gerenciamento kanban-card-info-gerenciamento" id="'+chave + oportunidade?._ownerid_value+
                '" onClick="javascript:ListarOportunidadesGerenciamento(this)" data-ownerid="'+oportunidade?._ownerid_value+
                '" data-coluna="'+chave+'">'+ oportunidadesVendedor.length+'</span>'+
                    '<div class="text-card">'+
                        '<span>'+oportunidade?.user_vendedor_x002e_fullname+'</span>'+
                        '<span class="text-card-valor">'+valorTotalFormato+'</span>'+
                    '</div>'+
                '</div>'
            )
            contador += oportunidadesVendedor.length;
        }
        
        $('.'+chave+'-count-gerenciamento').text(contador);
        let valorTotalColunaFormatado = valorTotalColuna.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
        $('#valor-column-'+chave).text(valorTotalColunaFormatado);
    }   

}

function esvaziarKanbanGerenciamento(){
    $('.andamento-column-gerenciamento').children('.kanban-card-gerenciamento').remove();
    $('.gerado-column-gerenciamento').children('.kanban-card-gerenciamento').remove();
    $('.aguardando-column-gerenciamento').children('.kanban-card-gerenciamento').remove();
    $('.assinada-column-gerenciamento').children('.kanban-card-gerenciamento').remove();
    $('.ganha-column-gerenciamento').children('.kanban-card-gerenciamento').remove();
    $('.perdida-column-gerenciamento').children('.kanban-card-gerenciamento').remove();

    $('.andamento-count-gerenciamento').text(0);
    $('.gerado-count-gerenciamento').text(0);
    $('.aguardando-count-gerenciamento').text(0);
    $('.assinada-count-gerenciamento').text(0);
    $('.ganha-count-gerenciamento').text(0);
    $('.perdida-count-gerenciamento').text(0); 
}

function mapearGerenciamento(oportunidades){
    if (oportunidades.length) {
        oportunidadesKanbanGerenciamento = oportunidades;
        oportunidadesGerenciamento['andamento'] = [];
        oportunidadesGerenciamento['gerado'] = [];
        oportunidadesGerenciamento['aguardando'] = [];
        oportunidadesGerenciamento['ganha'] = [];
        oportunidadesGerenciamento['perdida'] = [];
        oportunidadesGerenciamento['assinada'] = [];

        oportunidades.forEach(oportunidade => {
            if (oportunidade?.Cliente_PF_x002e_fullname) {
                var cliente = oportunidade?.Cliente_PF_x002e_fullname
            } else {
                var cliente = oportunidade?.Cliente_PJ_x002e_name
            }
            if (oportunidade?.statecode == 0) {
                if (oportunidade?.statuscode == 1) {
                    if (oportunidade?.tz_docusign_status == 419400000) {
                        //em andamento     
                        if(!oportunidadesGerenciamento['andamento'][oportunidade?._ownerid_value]){
                            oportunidadesGerenciamento['andamento'][oportunidade?._ownerid_value] = [];
                            oportunidadesGerenciamento['andamento'][oportunidade?._ownerid_value].push(oportunidade);
                        }else{
                            oportunidadesGerenciamento['andamento'][oportunidade?._ownerid_value].push(oportunidade);
                        }
                    }
                }                
                if (oportunidade?.statuscode == 419400002) {
                    if (oportunidade?.tz_docusign_status == 419400000) {
                        //pedido gerado
                        
                        if(!oportunidadesGerenciamento['gerado'][oportunidade?._ownerid_value]){
                            oportunidadesGerenciamento['gerado'][oportunidade?._ownerid_value] = [];
                            oportunidadesGerenciamento['gerado'][oportunidade?._ownerid_value].push(oportunidade);
                        }else{
                            oportunidadesGerenciamento['gerado'][oportunidade?._ownerid_value].push(oportunidade);
                        }
                    }
                    if (oportunidade?.tz_docusign_status == 419400001) {
                        //aguardando assinatura
                        
                        if(!oportunidadesGerenciamento['aguardando'][oportunidade?._ownerid_value]){
                            oportunidadesGerenciamento['aguardando'][oportunidade?._ownerid_value] = [];
                            oportunidadesGerenciamento['aguardando'][oportunidade?._ownerid_value].push(oportunidade);
                        }else{
                            oportunidadesGerenciamento['aguardando'][oportunidade?._ownerid_value].push(oportunidade);
                        }
                    }
                }
            }
            if (oportunidade?.statecode == 2) {
                if (oportunidade?.statuscode == 4) {
                    //ganha

                    if(!oportunidadesGerenciamento['ganha'][oportunidade?._ownerid_value]){
                        oportunidadesGerenciamento['ganha'][oportunidade?._ownerid_value] = [];
                        oportunidadesGerenciamento['ganha'][oportunidade?._ownerid_value].push(oportunidade);
                    }else{
                        oportunidadesGerenciamento['ganha'][oportunidade?._ownerid_value].push(oportunidade);
                    }
                }
            }
            if (oportunidade?.statecode == 3) {
                if (oportunidade?.statuscode == 5) {
                    //perdida

                    if(!oportunidadesGerenciamento['perdida'][oportunidade?._ownerid_value]){
                        oportunidadesGerenciamento['perdida'][oportunidade?._ownerid_value] = [];
                        oportunidadesGerenciamento['perdida'][oportunidade?._ownerid_value].push(oportunidade);
                    }else{
                        oportunidadesGerenciamento['perdida'][oportunidade?._ownerid_value].push(oportunidade);
                    }

                }
            } 
            if (oportunidade?.statuscode == 419400001) {
                //em validação adv
                
                if(!oportunidadesGerenciamento['assinada'][oportunidade?._ownerid_value]){
                    oportunidadesGerenciamento['assinada'][oportunidade?._ownerid_value] = [];
                    oportunidadesGerenciamento['assinada'][oportunidade?._ownerid_value].push(oportunidade);
                }else{
                    oportunidadesGerenciamento['assinada'][oportunidade?._ownerid_value].push(oportunidade);
                }
            }                
        })      
    }
}

function ListarOportunidadesGerenciamento(info){
    let coluna = $(info).data('coluna');
    let ownerid = $(info).data('ownerid');
    
    let oportunidades = oportunidadesGerenciamento[coluna][ownerid];
    
    $("#tab-oportunidades").click();
    preencherTabelaOportunidades(oportunidades);  
}
// preencher o kanban quando a página for carregada
var documentKanbanCli = "";
$(document).ready(function() {

    $(".pesquisaKanbanGerenciamento").on('keyup paste click',function () {
        let pesquisa = this.value;
        esvaziarKanbanGerenciamento();
        preencherKanbanGerenciamento(pesquisa);
    });

    // let documentKanbanCli;
    documentKanbanCli = "<?php  echo isset($_SESSION['ClienteKanbanDoc']) ? $_SESSION['ClienteKanbanDoc'] : "";  ?>" 

    document.getElementById('loading').style.display = 'block';
    
    
    buscarOportunidadesGerenciamento();

});

</script>
