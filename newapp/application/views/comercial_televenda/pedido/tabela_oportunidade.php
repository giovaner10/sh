<table id="dt_tableOportunidade" class="table table-striped table-bordered custom-table">
    <thead>
        <tr class="tableheader">
            <th><?=lang('quotenumber')?></th>
            <th><?=lang('cliente')?></th>
            <th type="date" class="sorting_desc"><?=lang('createdon')?></th>
            <th><?=lang('statecode')?></th>
            <th><?=lang('statuscode')?></th>
            <th><?=lang('tz_analise_credito')?></th>
            <th><?=lang('tz_valor_total_licenca')?></th>
            <th><?=lang('tz_valor_total_hardware')?></th>
            <th><?=lang('vendedor')?></th>
            <th><?=lang('acoes')?></th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>

<script>
    
function preencherTabelaOportunidades(resposta, callback) {
    let data = [];
    resposta.forEach(element => {
        let quotenumber = "";
        let createdon = "";
        let statecode = "";
        let tz_analise_credito = "";
        let tz_valor_total_licenca = "";
        let tz_valor_total_hardware = "";
        let effectivefrom = "";
        let effectiveto = "";
        let cliente = "";
        let acoes = "";
        let nomeVendedor = "";
        let statusCode = "";
        let idCliente = "";
        let tipoCliente = "";
        let botoesDropDown = "";

        if (element['quotenumber'])
            quotenumber = element['quotenumber'];

        if (element['createdon'])
            createdon = new Date(element['createdon']).toLocaleDateString('pt-BR');

        if (element['statuscode']) {
            if (element['statecode'] == 0) {
                if (element['statuscode'] == 1 && element['tz_docusign_status'] == 419400000)
                    statecode = "Em Andamento";
                else if (element['statuscode'] == 419400002) {
                    if (element['tz_docusign_status'] == 419400000)
                        statecode = "Pedido Gerado";
                    else if (element['tz_docusign_status'] == 419400001)
                        statecode = "Aguardando Assinatura";
                    else if (element['tz_docusign_status'] == 419400002)
                        statecode = "Proposta Assinada";
                }

                else if (element['statuscode'] == 419400001 && element['tz_docusign_status'] == 419400002)
                    statecode = "Em Validação ADV";
            }

            else if (element['statecode'] == 2 && element['statuscode'] == 4)
                statecode = "Ganha";

            else if (element['statecode'] == 3 && element['statuscode'] == 5)
                statecode = "Perdida";
        }

        if(element['tz_analise_credito']){

            if (element['tz_analise_credito'] == 0)
                tz_analise_credito = "Não avaliado";
                
            else if (element['tz_analise_credito'] == 1)
                tz_analise_credito = "Aprovado";
                
            else if (element['tz_analise_credito'] == 2)
                tz_analise_credito = "Reprovado";
                
            else if (element['tz_analise_credito'] == 3)
                tz_analise_credito = "Aprovado - Cliente da Base";
                
            else if (element['tz_analise_credito'] == 4)
                tz_analise_credito = "Aprovado com restrição";
                
            else if (element['tz_analise_credito'] == 5)
                tz_analise_credito = "Inconsistente";
                
            else if (element['tz_analise_credito'] == 6)
                tz_analise_credito = "Aprovado pelo financeiro";

        }

        if (element['tz_valor_total_licenca'])
            tz_valor_total_licenca = element['tz_valor_total_licenca'].toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

        if (element['tz_valor_total_hardware'])
            tz_valor_total_hardware = element['tz_valor_total_hardware'].toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });


        if (element['effectivefrom'])
            effectivefrom = new Date(element['effectivefrom']).toLocaleDateString('pt-BR');
        if (element['effectiveto'])
            effectiveto = new Date(element['effectiveto']).toLocaleDateString('pt-BR');

        if (element['Cliente_PF_x002e_fullname']){
            cliente = element['Cliente_PF_x002e_fullname'];
            tipoCliente = 'PF';
        }else{
            cliente = element['Cliente_PJ_x002e_name'];
            tipoCliente = 'PJ';
        }

        if (!cliente && element['CLIENTE_PF_x002e_fullname']){
            cliente = element['CLIENTE_PF_x002e_fullname'];
            tipoCliente = 'PF';
        }else if (!cliente){
            cliente = element['CLIENTE_PJ_x002e_name'];
            tipoCliente = 'PJ';
        }

        if (element.hasOwnProperty('br_x002e_fullname')){
            nomeVendedor = element['br_x002e_fullname'];
        }else if (element.hasOwnProperty('user_vendedor_x002e_fullname')){
            nomeVendedor = element['user_vendedor_x002e_fullname'];
        }else if (element.hasOwnProperty('systemuser3_x002e_fullname')){
            nomeVendedor = element['systemuser3_x002e_fullname'];
        }

        if ((element['statecode'] == 0) && (element['statuscode'] == 1) && (element['tz_docusign_status'] ==419400000))
            if(element['Cliente_PF_x002e_zatix_cpf']) {
                acoes += '<div> <a data-serial="'+ element['quoteid']+'" data-documento="'+element['Cliente_PF_x002e_zatix_cpf']+'" class="btn btn-success gerar_pedido" role="button" id="gerar_pedido" title="Gerar Pedido"  ><i class="fa fa-cloud-upload"></i> Gerar Pedido</a></div>';
            } else {
                acoes += '<div> <a data-serial="'+ element['quoteid']+'" data-documento="'+element['Cliente_PJ_x002e_zatix_cnpj']+'" class="btn btn-success gerar_pedido" role="button" id="gerar_pedido" title="Gerar Pedido"  ><i class="fa fa-cloud-upload"></i> Gerar Pedido</a></div>';
            }
        if ((element['statecode'] == 0) && (element['statuscode'] == 419400002) && (element[
                'tz_docusign_status'] == 419400000))
            acoes += '<div > <a data-docsign="' + element['quoteid'] +
            '" class="btn btn-info enviar_docsign"  role="button" id="enviar_docsign" style="margin-top: 4px;" title="Solicitar assinatura" ><i class="fa fa-pencil"></i> Solicitar Assinatura</a></div>';
 
        if ((element['statecode'] == 2) && (element['statuscode'] == 4))
            acoes += '<div > <a data-status="' + element['quoteid'] +
            '" class="btn btn-warning solicitar_status"  role="button" id="solicitar_status" style="margin-top: 4px;" title="Solicitar status" ><i class="fa fa-file"></i> Status</a></div>';

        acoes += '<div> <button class="btn btn-primary" data-statuscode="' + element['quoteid'] +
            '" title="Resumo cotação" style="margin-top: 4px;" id="resumoCotacaoCliente" onClick="javascript:abrirModalResumoCotacao(this)"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> Resumo </button> </div>';
 
        acoes += '<div > <a data-customerid="' + element['_customerid_value'] +
            '" class="btn btn-primary listagem_docs_oportunidades"  role="button" id="listagem_docs" style="margin-top: 4px;" title="Documentos"><i class="fa fa-folder-open-o"></i>Documentos</a></button></div>';

        idCliente = element['Cliente_PF_x002e_contactid'] ?? element['Cliente_PJ_x002e_accountid'] ?? element['CLIENTE_PF_x002e_contactid'] ?? element['CLIENTE_PJ_x002e_accountid'];
        
        acoes += '<div> <button class="btn btn-primary" data-quoteid="' + element['quoteid'] +
            '" data-idclientecot="'+ idCliente +'" data-tipocliente="'+ tipoCliente +'" title="Editar cotação" style="margin-top: 4px;" onClick="javascript:EditarOportunidade(this)"> <i class="fa fa-pencil"></i> Editar </button> </div>';
        
        acoes += '<div> <button class="btn btn-primary" " title="Itens de Contrato de Venda" style="margin-top: 4px;" onClick="javascript:abrirModalItensContratoDeVenda(this, \'' + element['_customerid_value'] + '\', \'' + element['quoteid'] + '\' )"> <i class="fa fa-list"></i> Relacionar</button> </div>';
        
        acoes += '<div> <button class="btn btn-primary" data-quoteid="' + element['quoteid'] +
            '" title="Detalhamento da frota" style="margin-top: 4px;" onClick="javascript:abrirDetalhamentoFrota(this, \'' + element['quoteid'] + '\' )"> <i class="fa fa-file-text-o"></i> Frota</button> </div>';
        
        if (element['statuscode'] == 1 || element['statuscode'] == 419400002 || element['statuscode'] == 419400001){
            botoesDropDown += '<div class="row"><button class="btn btn-danger opcoesDrop" data-idCotacao="'+element['quoteid']+'" title="Excluir cotação" id="btnExcluirCotacao" onClick="javascript:excluirCotacao(this,\'' + element['quoteid'] + '\', \'' + element['quotenumber'] + '\')"><i class="fa fa-ban" aria-hidden="true"></i> Excluir</button></div>';
            botoesDropDown += '<div class="row"><button class="btn btn-primary opcoesDrop" data-idCotacao="'+element['quoteid']+'" title="Revisar cotação" id="btnRevisarCotacao" onClick="javascript:revisarCotacao(this,\'' + element['quoteid'] + '\')"><i class="fa fa-undo" aria-hidden="true"></i> Revisar</button></div>';
            botoesDropDown += '<div class="row"><button class="btn btn-primary opcoesDrop" data-idCotacao="'+element['quoteid']+'" title="Perder Cotação" id="btnPerderCotacao" onClick="javascript:perderCotacao(this,\'' + element['quoteid'] + '\')"><i class="fa fa-times" aria-hidden="true"></i> Perder</button></div>';
        }
        
        if (botoesDropDown != ''){
            acoes += '<div style="position: relative; display: flex; margin-top: 4px"><button class="btn btn-primary" id="btnDropAbrir" onClick="javascript:abrirDropDownAcoes(this, \'acoesDropdown' + element['quoteid'] + '\', \'iconeBtnMaisAcoes' + element['quoteid'] + '\')"><i class="fa fa-caret-left" id="iconeBtnMaisAcoes'+element['quoteid']+'" style="top: 0px !important; float: inline-start; pointer-events: none"></i> Mais Ações</button><div class="divAcoesDrop" id="acoesDropdown' + element['quoteid'] + '" style="position:absolute;bottom: 0;left: -51%; display:none">'+botoesDropDown+'</div></div>';
        }

        if (element['statuscode'] == 419400001){
            acoes += '<div> <button class="btn btn-primary" data-idCotacao="'+element['quoteid']+'" title="Listar Razão da Validação" style="margin-top: 4px;" id="btnRazaoValidacao" onClick="javascript:abrirModalRazaoValicacao(this,\'' + element['quoteid'] + '\')"><i class="fa fa-bars" aria-hidden="true"></i>  Razão da Validação</button> </div>';
        }
        
        if (element.hasOwnProperty('statuscode')){
            statusNum = element['statuscode'];
            let tipoStatus = {
                1: "Em Andamento",
                2: "Em Andamento",
                3: "Aberta",
                4: "Ganha",
                5: "Perdida",
                6: "Cancelada",
                7: "Revisado",
                419400001: "Em Validação",
                419400002: "Pedido Gerado"
            };
            statusCode = tipoStatus[statusNum] || "";
        }
        data.push([
            quotenumber,
            cliente,
            createdon,
            statecode,
            statusCode,
            tz_analise_credito,
            tz_valor_total_licenca,
            tz_valor_total_hardware,
            nomeVendedor,
            acoes,
        ])

    });

    dt_tableOportunidade.clear().draw();
    dt_tableOportunidade.rows.add(data).draw();

    if (callback && typeof callback === 'function') {
        callback();
    }

}


$(document).ready(function() {     

    url = '<?php echo isset($_SESSION['documento'])? site_url('ComerciaisTelevendas/Pedidos/pegarOportunidade_clienteCRM'): site_url('ComerciaisTelevendas/Pedidos/pegarOportunidade_vendedorCRM');?>'
   
    $.ajax({
        url: url,
        dataType: "json",
        type: "GET",
        success: function(resposta) {
            preencherTabelaOportunidades(resposta);       
        },

        error: function(data) {
            
        },

    });

    $('#quotes_vendedor').click(function(e){
        $('.feedback-alert').html('');

        $('#documentoPesquisa').val('');
        
        document.getElementById('loading').style.display = 'block';
        $.ajax({
            url: '<?php echo  site_url('ComerciaisTelevendas/Pedidos/pegarOportunidade_vendedorCRM');?>',
            dataType: "json",
            type: "GET",
            success: function(resposta) {
                preencherTabelaOportunidades(resposta, function() {
                    document.getElementById('loading').style.display = 'none';
                });
            },

            error: function(data) {
                document.getElementById('loading').style.display = 'none';
            }
        });
    })
   

});

function BuscarOportunidadesClientes(documento, dataInicial = null, dataFinal = null){
    url = '<?php echo site_url('ComerciaisTelevendas/Pedidos/pegarOportunidade_clienteCRM');?>'
    
    $.ajax({
        url: url,
        dataType: "json",
        type: "POST",
        data: {
            'documento': documento,
            'dataInicial': dataInicial,
            'dataFinal': dataFinal,
        },
        success: function(resposta) {
            preencherTabelaOportunidades(resposta);       
        },
        error: function(data) {
            
        },
        complete: function(){
            document.getElementById('loading').style.display = 'none';
        }
    });
}

function BuscarOportunidadesVendedor(dataInicial = null, dataFinal = null){
    url = '<?php echo site_url('ComerciaisTelevendas/Pedidos/pegarOportunidade_vendedorCRM');?>'
    
    if ((document.getElementById('loading').style.display == 'none')){
        document.getElementById('loading').style.display = 'block';
    }

    $.ajax({
        url: url,
        dataType: "json",
        type: "POST",
        data: {
            'dataInicial': dataInicial,
            'dataFinal': dataFinal,
        },
        success: function(resposta) {
            preencherTabelaOportunidades(resposta); 
            $('html, body').scrollTop(0); 
        },
        error: function(data) {
            document.getElementById('loading').style.display = 'none';
        },
        complete: function(){
            document.getElementById('loading').style.display = 'none';
        }

    });
}

var dropdownAbertoId = null;
var iconeMaisAcoes = null;
$(document).on('click', function (event) {
    var target = $(event.target);

    if (!target.is('#btnDropAbrir') && !target.closest('.divAcoesDrop').length) {
        $('.divAcoesDrop').hide();
        dropdownAbertoId = null;
        if (iconeMaisAcoes){
            iconeMaisAcoes.removeClass('fa fa-caret-right');
            iconeMaisAcoes.addClass('fa fa-caret-left');
        }
    }
});

function abrirDropDownAcoes(botao, dropdownId, iconeId) {
    var btn = $(botao);
    var dropdown = $('#' + dropdownId);
    iconeMaisAcoes = $('#' + iconeId);

    if (dropdown.is(':visible') && dropdownAbertoId === dropdownId) {
        dropdown.hide();
        dropdownAbertoId = null;
        iconeMaisAcoes.removeClass('fa fa-caret-right');
        iconeMaisAcoes.addClass('fa fa-caret-left');
    } else {
        if (dropdownAbertoId !== null) {
            $('#' + dropdownAbertoId).hide();
        }
        dropdown.show();
        dropdownAbertoId = dropdownId;
        iconeMaisAcoes.removeClass('fa fa-caret-left');
        iconeMaisAcoes.addClass('fa fa-caret-right');
    }
}
</script>