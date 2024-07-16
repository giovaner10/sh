<link rel="stylesheet" type="text/css" href="<?php echo base_url('media/datatable/jquery.dataTables.min.css'); ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<style>
    .trSerial, .trSerial-retirado, .trPlaca, .trCliente, .trOs, .trValorServ, .trValorTotal, .trData, .trServico, .trUser, .trId{
        list-style: none;
        margin: 0 auto;
    }
    .trCliente li{
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .dBody{
        display: inline-flex;
    }
    #btn-all, #btn-tec{
        width: 30px;
        height: 45px;
        border-radius: 50px;
        font-size: 22px;
        color: #fff;
        line-height: 58px;
        text-align: center;
        -webkit-box-shadow: 0px 10px 5px -7px rgba(191,191,191,1);
        -moz-box-shadow: 0px 10px 5px -7px rgba(191,191,191,1);
        box-shadow: 0px 10px 5px -7px rgba(191,191,191,1);
    }
    .label-danger {
        background-color: #da4f49 !important;
    }
    #myModal_dados {
        text-align: center;
        padding: 0!important;
        margin: 0 auto;
        width: 95%;
        left: 2%;
        font-size: 10px;
    }
    .some{
        display: none;
    }
    #myModal_dados:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px; /* Adjusts for spacing */
    }
    .modal-dialog {
        /*display: inline-block;*/
        text-align: left;
        vertical-align: middle;
    }
    .dSerial, .dSerial-retirado, .dValorServ, .dUser{
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 125px;
    }
    .dCliente{
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 150px;
    }
    .dId{
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 40px;
    }
    .dData, .dPlaca, .dOs, .dServico{
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 70px;
    }
    .dValorTotal{
        width: 200px;
        height: 20px;
        padding: 30px;
        margin: 0 auto;
        float: right;
        position: relative;
        font-size: 20px;
        display: inline-flex;
        background: aliceblue;
    }
    .dSerial>label, .dSerial-retirado>label, .dPlaca>label, .dCliente>label, .dOs>label, .dValorServ>label, .dData>label, .dServico>label, .dUser>label, .dId>label{
        font-size: 17px;
        padding-top: 10px;
        background: aliceblue;
    }
    .dTotal{
        width: 90%;
        margin: 0 auto;
    }
    .material-switch > input[type="checkbox"] {
        display: none;
    }

    .material-switch > label {
        cursor: pointer;
        height: 0px;
        position: relative;
        width: 40px;
    }

    .material-switch > label::before {
        background: rgb(0, 0, 0);
        box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        content: '';
        height: 16px;
        margin-top: -8px;
        position:absolute;
        opacity: 0.3;
        transition: all 0.4s ease-in-out;
        width: 40px;
    }
    .material-switch > label::after {
        background: rgb(255, 255, 255);
        border-radius: 16px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        content: '';
        height: 24px;
        left: -4px;
        margin-top: -8px;
        position: absolute;
        top: -4px;
        transition: all 0.3s ease-in-out;
        width: 24px;
    }
    .material-switch > input[type="checkbox"]:checked + label::before {
        background: #499bea;
        opacity: 0.5;
    }
    .material-switch > input[type="checkbox"]:checked + label::after {
        background: #499bea;
        left: 20px;
    }
    .labelBt{
        float: left;
        margin-top: -9px;
        padding-left: 5px;
    }
</style>


<h3><?=lang("lista_pre_aprovacao")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
	<?=lang('financeiro')?> >
    <?=lang('ordem_de_pagamento')?>
</div>
<hr>
<div class="container-fluid">
    <a href="#myModal" data-toggle="modal" class="btn btn-primary" style="margin-bottom: 20px;">Adicionar Conta</a>
    <table id="example" class="responsive table-bordered table" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fornecedor</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Responsável</th>
                <th>Lançamento</th>
                <th>Vencimento</th>
                <th>Valor</th>
                <th>Empresa</th>
                <th>Status</th>
                <th>Aprov/Repro</th>
                <th>Ferramentas</th>
            </tr>
        </thead>
    </table>
</div>

<!-- MODAL DADOS -->
<div class="modal hide" id="myModal_dados">
    <style>
        @media print {
            html {
                position: relative;
                min-height: 100%;
            }
            html, body {
                height: 54mm;
                width: 101mm;
                font: 14px arial, sans-serif;
                writing-mode: horizontal-tb;
            }
            @page {
                margin-top: 0.5in;
                margin-left: 0.53in;
                margin-bottom: 0.5in;
                margin-right: 0.53in;
            }


            body	{background: #FFF; color: #000; font: 10pt serif;}
            a:link, a:visited	{color: #333; text-decoration: underline;}
            a[href]:after		{content: " (" attr(href) ")";}
            .trSerial, .trPlaca, .trCliente, .trOs, .trValorServ, .trValorTotal, .trData, .trServico, .trUser, .trId, .trSerial-retirado{
                list-style: none;
                margin: 0 auto;
                text-align: center;
                margin-right: 50px!important;
            }
            .trCliente li, .trUser li{
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                /*margin-right: 30px;*/
            }
            .dBody{
                display: inline-flex;
            }
            .modal {
                text-align: center;
                padding: 0!important;
                margin: 0 auto;
                width: 95%;
                left: 2%;
            }

            .modal:before {
                content: '';
                display: inline-block;
                height: 100%;
                vertical-align: middle;
                margin-right: -4px; /* Adjusts for spacing */
            }
            .modal-dialog {
                display: inline-block;
                text-align: left;
                vertical-align: middle;
            }
            .dSerial, .dSerial-retirado, .dValorServ, .dUser, .dPlaca{
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                padding-left: 15px;
                padding-right: 15px;
                width: 175px;
                text-align: center;
            }
            .dCliente{
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                /*padding-left: 15px;*/
                /*padding-right: 15px;*/
                width: 200px;
                text-align: center;
            }
            .dId{
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                padding-left: 15px;
                padding-right: 15px;
                width: 40px;
                text-align: center;
            }
            .dData, .dOs, .dServico{
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                padding-left: 15px;
                padding-right: 15px;
                width: 70px;
                text-align: center;
            }
            .dValorTotal{
                width: 200px;
                height: 20px;
                padding: 30px;
                margin: 0 auto;
                float: right;
                position: relative;
                font-size: 20px;
                display: inline-flex;
                background: aliceblue;
            }
            .dPlaca>label, .dSerial-retirado>label, .dCliente>label, .dOs>label, .dValorServ>label, .dData>label, .dServico>label, .dUser>label, .dId>label{
                font-size: 17px;
                padding-top: 10px;
                background: aliceblue;
                text-align: center;
            }
            .dTotal{
                width: 100%;
                margin: 0 auto;
            }
            #btn-imprimir{
                display: none;
            }
            button.close{
                display: none;
            }
            #modal-dados{
                font-size: 10px;
            }
        }
    </style>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <button class="btn btn-info pull-left" id="btn-imprimir"  type="button" style="margin-right: 5px;">Imprimir <i class="fa fa-print"></i></button>
        <h3>Dados Serviços Prestados</h3>
    </div>


    <div id="modal-dados" class="modal-body">
        <div class="dBody">
            <div class="dId">
                <label><b>Id</b><hr></label>
                <ul class="trId"></ul>
            </div>
            <div class="dOs">
                <label><b>OS</b><hr></label>
                <ul class="trOs"></ul>
            </div>
            <div class="dData">
                <label><b>Data</b><hr></label>
                <ul class="trData"></ul>
            </div>
            <div class="dServico">
                <label><b>Serviço</b><hr></label>
                <ul class="trServico"></ul>
            </div>
            <div class="dPlaca">
                <label><b>Placa</b><hr></label>
                <ul class="trPlaca"></ul>
            </div>
            <div class="dSerial">
                <label><b>Serial</b><hr></label>
                <ul class="trSerial"></ul>
            </div>
            <div class="dSerial-retirado">
                <label><b>Serial Retirado</b><hr></label>
                <ul class="trSerial-retirado"></ul>
            </div>
            <div class="dCliente">
                <label><b>Cliente</b><hr></label>
                <ul class="trCliente"></ul>
            </div>
            <div class="dUser">
                <label><b>Usuário</b><hr></label>
                <ul class="trUser"></ul>
            </div>
            <div class="dValorServ">
                <label><b>Valor</b><hr></label>
                <ul class="trValorServ"></ul>
            </div>
        </div>
    </div>
    <div class="dTotal">
        <div class="dValorTotal">
            <label><b>Valor Total:</b></label>
            <ul class="trValorTotal"></ul>
        </div>
    </div>

</div>
<!-- END MODAL DADOS -->

<!-- MODAL DADOS NOVO -->
<div id="modalDados" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width: fit-content;">
            <form name="formDados">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Dados Serviços Prestados</h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <h3 id="valorTotalOs"></h3>
                        <table class="table-responsive table-bordered table" id="tabelaDados">
                	    	<thead>
                	    	    <tr class="tableheader">
                	    	    <th>OS</th>
                	    	    <th>Data</th>
                                <th>Serviço</th>
                                <th>Placa</th>
                	    	    <th>Serial</th>
                                <th>Serial Retirado</th>
                                <th>Cliente</th>
                                <th>Usuário</th>
                                <th>Valor</th>
                	    	    </tr>
                	    	</thead>
                	    	<tbody>
                	    	</tbody>
                	    </table> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END MODAL DADOS NOVO -->
<div class="modal fade" id="myModal_editar">
    <div class="modal-dialog">
        <div id="load1" style="display:none;" class="overlay"></div>
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3 style="text-align: center;">Editar Conta</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/updateContasPreAprovacao')?>' name="" id="form_editar">
                <div class="modal-body">
                    <div class="alert fornec" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span></span>
                    </div>
                    <div class="form-group divCategoria">
                        <label for="sel1">Categoria:</label>
                        <select class="form-control input-sm" id="sel1" name="categoria" onclick='verifica_categoria(this, "1")'>
                            <?php foreach ($categorias as $categoria): ?>
                                <?php if ($categoria == '') continue; ?>
                                <option><?= $categoria ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                            
                    <input type="hidden" name="id" id="fornecedor_id">
                            
                    <span id="fornecedor_span1">
                        <label>Fornecedor</label>
                        <input type="text" class="form-control input-sm" name="fornecedor" id="fornecedor" placeholder="Fornecedor" readonly="readonly" required>
                    </span>
                    <div class="col-md-6" style="padding: initial; padding: 10px 0px 0px 0px;">
                        <label>Valor</label>
                        <input type="text" class="form-control input-sm money2" name="valor" id="add_conta_valor1" onkeyup="formatarMoeda(this.id)" placeholder="Valor" required>
                    </div>
                    <div class="col-md-6" style="padding: 10px 0px 5px 10px;">
                        <label>Vencimento</label>
                        <input type="date" class="form-control input-sm" name="dt_vencimento" id="dt_vencimento" placeholder="Vencimento" required>
                    </div>
                        <label>Descriçao</label>
                        <textarea type="text" class="form-control input-sm" name="descricao-forn" id="descricao-forn" placeholder="Descriçao" required></textarea>
                    <br>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button class="btn btn-primary" id="editar">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE CADASTRO DE CATEGORIAS -->
<div id="cadCateg" class="modal hide" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nova Categoria</h4>
            </div>
            <form action="<?= site_url('contas/add_categoria') ?>" method="POST">
                <div class="modal-body">
                    <label for="categ">Categoria: </label>
                    <input id="categ" name="categoria" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </div>
            </form>
        </div>

    </div>
</div>

<div id="myModal_comprovantes" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="myModalLabel">Digitalizar Comprovantes</h4>
            </div>
            <div id="bodyComprovantes" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div id="load" style="display:none;" class="overlay"></div>
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" id="close0" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 style="text-align: center;">Adicionar Conta</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/add')?>' name="" id="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Categoria</label>
                        <select required class="form-control input-sm" onchange="verifica_categoria(this,'')" id="categoria" name="categoria">
                            <option value="" selected disabled>Selecione a categoria</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <?php if ($categoria == '') continue; ?>
                                <option><?= $categoria; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="fornecedor_span"></span>
                    </div>
                        <label>Empresa</label>
                        <select required class="form-control input-sm" name="empresa">
                            <option value="1">Show Tecnologia</option>
                            <option value="3">Norio Momoi EPP</option>
                            <option value="4">Show Technology</option>
                        </select>
                        <div class="col-md-6" style="padding: initial; padding: 10px 0px 0px 0px;">
                            <label>Valor</label>
                            <input type="text" class="form-control input-sm money2" name="valor" id="add_conta_valor" onkeyup="formatarMoeda(this.id)" placeholder="0,00" required>
                        </div>
                        <div class="col-md-6" style="padding: 10px 0px 5px 10px;">
                            <label>Data Vencimento</label>
                            <input type="date" name="data_vencimento" class="data input-sm formatInput form-control" placeholder="Data de Vencimento" autocomplete="off" id="add_conta_vencimento" value=""/> 
                        </div>
                        <label>Descrição</label>
                        <textarea type="text" class="form-control input-sm" name="descricao" id="descricao" placeholder="Descrição" required></textarea>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-primary" id="add">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_digitalizar" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center; border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="myModalLabel">Digitalizar Conta</h4>
            </div>
            <div id="bodyDigitalizar" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal_cancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Cancelamento</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/update/')?>' id="form_cancel">
                <div class="modal-body">
                    <label>Tem certeza que deseja cancelar?</label>
                    <input type="hidden" name="id" id="cancel-id">
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-danger" id="cancelar">Sim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/boleto.js/boleto.min.js"></script>
<script>
    var fornecedor = false;
    function verifica_categoria(e,v){
        if(e.value=='SALÁRIO'||e.value=='SALARIO'||e.value=='AJUDA DE CUSTO'||e.value=='ADIANTAMENTO SALARIAL'||e.value=='RESCISÃO'||e.value=='RESCISAO'||e.value=='FÉRIAS'||e.value=='FERIAS'||e.value=='DÉCIMO TERCEIRO'||e.value=='13º SALÁRIO'||e.value=='DECIMO TERCEIRO'||e.value=='PRO-LABORE'||e.value=='ADIANTAMENTO BENEFICIO'){
            document.getElementById("load"+v).style.display=null;
            var html='<input type="hidden" id="id_conta'+v+'" name="id_conta" value="-1"/> <label>Funcionário</label><select onchange="get_conta(\''+v+'\')" name="fornecedor" id="fornecedor'+v+'" class="form-control input-sm" required><option value="" disabled selected>Selecione o funcionário</option>';
            $.getJSON('<?=base_url()?>index.php/contas/get_funcionarios', function (data){
                $.each(data,function (index, json){
                    html+='<option data-id="'+json.id+'">'+json.nome+'</option>'
                });
                html+="</select><span id='conta_fornecedor"+v+"'></span>"
                $('#fornecedor_span'+v).html(html);
                $('select[name=fornecedor]').select2();
                document.getElementById("load"+v).style.display="none";
                fornecedor = false;
            });
        }
        else{
            document.getElementById("load"+v).style.display=null;
            var html = '<input type="hidden" id="id_conta' + v + '" name="id_conta" value="-1"/> ' +
           '<a href="<?=base_url();?>index.php/cadastro_fornecedor/add" style="color:black"><i class="fa fa-plus-circle"></i></a>' +
           '<label for="fornecedor'+v+'">Fornecedor</label>' +
           '<select onchange="get_conta(\'' + v + '\')" name="fornecedor" id="fornecedor' + v + '" class="form-control input-sm" required>' +
           '<option value="" disabled selected>Selecione o fornecedor</option>';

            $.getJSON('<?=base_url()?>index.php/cadastro_fornecedor/getFornecedor', function(data) {
                $.each(data, function(index, json) {
                    html += '<option data-id="' + json.id + '">' + json.id + ' - ' + json.nome + '</option>';
                });
            
                html += '</select><span id="conta_fornecedor' + v + '"></span>';
                $('#fornecedor_span'+v).html(html);
                $('select[name=fornecedor]').select2();
                document.getElementById("load"+v).style.display="none";
                fornecedor = true;
            });
        }
    }

    function get_conta(v){
        document.getElementById("load"+v).style.display=null;
        url = "get_conta_funcionario/";
        if(fornecedor){
            url="get_conta_fornecedores/"
        }
        $.getJSON('<?=base_url()?>index.php/contas/'+url+$('select[name=fornecedor] option:selected').data('id'), function (data){
            if(data.conta){
                $('#conta_fornecedor'+v).html('<label>Operação</label><select onchange="tipo_operacao(this,\''+data.id+'\',\''+v+'\')" class="form-control input-sm"><option value="" disabled selected>Selecione a operação</option><option value="transferencia">Transferência Bancária</option><option value="boleto">Pagamento de título (boleto)</option><option value="boleto_guia">Pagamento de guia (boleto)</option></select><span id="operacao_transferencia'+v+'" style="display:none;"><label>Conta</label><input type="text" value="CPF: '+data.cpf+', Agência: '+data.agencia+', conta: '+data.conta+'" class="form-control input-sm" disabled="disabled"></span><span id="operacao_boleto'+v+'" style="display:none;"><span id="operacao_boleto_titulo'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto'+v+'" onchange="transformarEmCódigoDeBarras(this,\''+v+'\')" maxlength="54" class="form-control input-sm"></span><span id="operacao_boleto_guia'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto_guia'+v+'" onchange="transformarEmCódigoDeBarrasGuia(this,\''+v+'\')" class="form-control input-sm"></span><label>Código de barras</label><input type="text" id="cod_barras'+v+'" name="cod_barras" class="form-control input-sm"></span>');
                $("#linha_digitavel_boleto"+v).mask('99999.99999 99999.999999 99999.999999 9 99999999999999');
                $("#linha_digitavel_boleto_guia"+v).mask('99999999999-9 99999999999-9 99999999999-9 99999999999-9');
            }
            else{
                $('#conta_fornecedor'+v).html('<label>Operação</label><select onchange="tipo_operacao(this,\'-1\',\''+v+'\')" class="form-control input-sm"><option value="" disabled selected>Selecione a operação</option><option value="transferencia">Transferência Bancária</option><option value="boleto">Pagamento de título (boleto)</option><option value="boleto_guia">Pagamento de guia (boleto)</option></select><span id="operacao_transferencia'+v+'" style="display:none;"> <label>Conta</label><input type="text" value="Conta não encontrada, verifique o cadastro" class="form-control input-sm" disabled="disabled"></span><span id="operacao_boleto'+v+'" style="display:none;"><span id="operacao_boleto_titulo'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto'+v+'" onchange="transformarEmCódigoDeBarras(this,\''+v+'\')" maxlength="54" class="form-control input-sm"></span><span id="operacao_boleto_guia'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto_guia'+v+'" onchange="transformarEmCódigoDeBarrasGuia(this,\''+v+'\')" class="form-control input-sm"></span><label>Código de barras</label><input type="text" id="cod_barras'+v+'" name="cod_barras" class="form-control input-sm"></span>');
                $("#linha_digitavel_boleto"+v).mask('99999.99999 99999.999999 99999.999999 9 99999999999999');
                $("#linha_digitavel_boleto_guia"+v).mask('99999999999-9 99999999999-9 99999999999-9 99999999999-9');
                $('#id_conta'+v)[0].value="-1";
            }
            document.getElementById("load"+v).style.display='none';
            
        });
    }

    $('#myModal_digitalizar').on('hidden', function(){
        $('div#myModal_digitalizar > div.modal-body').html('');
        $(this).data('modal', null);
    });

    $('#myModal_comprovantes').on('hidden', function(){
        $(this).data('modal', null);
        $('#myModal_comprovantes > .modal-body').html('');
    });

    $(document).ready(function() {
        $('.date').mask('99/99/9999', {clearIfNotMatch: true});

        var table = $('#example').DataTable( {
            "ajax": "<?= site_url('contas/ajaxLoadAprovacao') ?>",
            ordering: false,
            paging: true,
            info: true,
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhum resultado a ser listado",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
                zeroRecords:        "Nenhum resultado encontrado.",
                paginate: {
                    first:          "Primeira",
                    last:           "Última",
                    next:           "Próxima",
                    previous:       "Anterior"
                },
                lengthMenu:         "Mostrar _MENU_ resultados por página",
            },
            
        });

        $('#form').on('submit', function(e) {
            $('#add').attr('disabled', 'true');
            $('#add').text('Salvando...');
            e.preventDefault();
            var form = $(this).serialize();
            var url = $('#form').attr('action');
            $.post(url, form, function (data){
                if (data && data > 0) {
                    $("#add").removeAttr('disabled');
                    $('#add').text('Cadastrar');
                    $('#form').trigger('reset');
                    $('#myModal').modal('hide');
                    alert('Conta adicionada com sucesso!')
                    table.ajax.reload(null, false);
                }else{
                    $("#add").removeAttr('disabled');
                    $('#add').text('Cadastrar');
                    alert('Não foi possível adicionar a conta. Tente novamente.');
                }
            });
        });

        $('#editar').on('click', function(e) {
            e.preventDefault();
            $('#editar').attr('disabled', 'disabled').text('Salvando...');
            var form = $(this).closest('form').serialize();
            var url = $('#form_editar').attr('action');
            
            if ($('#fornecedor').val() != '') {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form,
                    success: function(data) {
                        if (data == 'true') {
                            $('#editar').removeAttr('disabled').text('Editar');
                            alert('Conta editada com sucesso!');
                            $('#myModal_editar').modal('hide');
                            table.ajax.reload(null, false);
                        } else {
                            alert('Não foi possível editar o fornecedor. Tente novamente mais tarde!');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            alert('Erro 400: Solicitação inválida. Verifique os dados enviados.');
                        } else if (xhr.status == 500) {
                            alert('Erro 500: Erro interno do servidor. Tente novamente mais tarde.');
                        } else {
                            alert('Erro desconhecido: ' + status + ' - ' + error);
                        }
                    }
                });
            } else {
                alert('Preencha o campo fornecedor corretamente.');
            }
        });


        $('#cancelar').on('click', function(e) {
            $("#myModal_cancel").modal('hide');
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_cancel').attr('action');
            $.post(url, form, function (data){
                if (data == 'true') {
                    table.ajax.reload(null, false);
                } else if (data == 3) {
                    alert('Você não tem permissão para cancelamento.');
                } else {
                    alert('Não foi possível cancelar a conta. Tente novamente.');
                }
            });
        });

        $('#example').on('click', '#aprova', function() {
            obj = $(this);
            $.ajax({
                url: "<?= site_url('contas/approveAcount') ?>",
                type: "POST",
                data: {id: obj.data('id')},
                dataType: "json",
                success: function (callback) {
                    if (callback.status == 'OK') {
                        table
                            .row( obj.parents('tr') )
                            .remove()
                            .draw();
                    }

                    alert(callback.msg);
                },
                error: function () {
                    alert('Não foi possível realizar a aprovação no momento. tente novamente mais tarde!');
                }
            })
        });

        $('#example').on('click', '#reprova', function() {
            obj = $(this);
            if (obj.data('status') == 'reprovado') {
                alert('A conta já se encontra reprovada. Verifique e tente novamente!');
            } else {
                $.ajax({
                    url: "<?= site_url('contas/disapproveAcount') ?>",
                    type: "POST",
                    data: {id: obj.data('id')},
                    dataType: "json",
                    success: function (callback) {
                        if (callback.status == 'OK') {
                            $('#label'+obj.data('id')).removeClass('label-warning');
                            $('#label'+obj.data('id')).addClass('label-danger');
                            $('#label'+obj.data('id')).text('Reprovado');   
                        }

                        alert(callback.msg);
                    },
                    error: function () {
                        alert('Não foi possível realizar a aprovação no momento. tente novamente mais tarde!');
                    }
                })
            }
        });
    } );

    function dados(param) {
        $('.trSerial').html("");
        $('.trSerial-retirado').html("");
        $('.trServico').html("");
        $('.trPlaca').html("");
        $('.trCliente').html("");
        $('.trUser').html("");
        $('.trOs').html("");
        $('.trValorServ').html("");
        $('.trValorTotal').html("");
        $('.trData').html("");
        $('.trId').html("");
        var serial = $(param).data('serial');
        var serial_retirado = $(param).data('serial_ret');
        var servico = $(param).data('servico');
        var placa = $(param).data('placa');
        var clientes = $(param).data('cliente');
        var user = $(param).data('user');
        var os = $(param).data('id_os');
        var valor = $(param).data('valor');
        var valorTotal = $(param).data('total');
        var data = $(param).data('data');
        $.each(servico, function (i, serv) {
            var template2 = ['<li>'+(i + 1)+'</li><hr>'].join('');
            var template = ['<li>'+serv+'</li><hr>'].join('');
            $('.trId').append(template2);
            $('.trServico').append(template).prop('title', serv);
        });
        $.each(serial, function (i, s) {
            var template = ['<li>'+s+'</li><hr>'].join('');
            $('.trSerial').append(template).prop('title', s);
        });
        $.each(serial_retirado, function (i, ser_r) {
            if (ser_r == "" || ser_r == null){
                var template = ['<li>-------</li><hr>'].join('');
            }else{
                template = ['<li>'+ser_r+'</li><hr>'].join('');
            }
            $('.trSerial-retirado').append(template).prop('title', ser_r);
        });
        $.each(placa, function (i, p) {
            var template = ['<li>'+p+'</li><hr>'].join('');
            $('.trPlaca').append(template).prop('title', p);
        });
        $.each(clientes, function (i, c) {
            var template = ['<li>'+c+'</li><hr>'].join('');
            $('.trCliente').append(template).prop('title', c);
        });
        $.each(user, function (i, usr) {
            var template = ['<li>'+usr+'</li><hr>'].join('');
            $('.trUser').append(template).prop('title', usr);
        });
        $.each(os, function (i, o) {
            var template = ['<li><a href="<?= site_url("servico/visualizar_os") ?>/'+o+'" target="_blank">'+o+'</a></li><hr>'].join('');
            $('.trOs').append(template).prop('title', o);
        });
        $.each(valor, function (i, val) {
            var result = parseFloat(val);
            if (val == ""){
                var template = ['<li>-------</li><hr>'].join('');
            }else{
                template = ['<li>'+numberParaReal(result)+'</li><hr>'].join('');
            }
            $('.trValorServ').append(template);
        });
        $.each(data, function (i, dt) {
            var mydate = new Date(dt);
            var mes = mydate.getMonth()+1; if (mes.toString().length == 1)	mes = "0"+mes;
            var date = mydate.getDate().toString().length == 1 ? '0'+mydate.getDate() + '/' + mes + '/' + mydate.getFullYear() : mydate.getDate() + '/' + mes + '/' + mydate.getFullYear();
            if (dt == ""){
                var template = ['<li>-------</li><hr>'].join('');
            }else{
                template = ['<li>'+date+'</li><hr>'].join('');
            }

            $('.trData').append(template);
        });
        var template = ['<li>R$ '+valorTotal+'</li><hr>'].join('');
        $('.trValorTotal').append(template);
        $("#myModal_dados").modal('show');

    }

    function numberParaReal(num) {
        var num = num.toFixed(2).split('.');
        num[0] = "R$ " + num[0].split(/(?=(?:...)*$)/).join('.');
        return num.join(',');
    }

    function cancel(param) {
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        if(!$(param).hasClass('disabled')) {
            $('#cancel-id').val(id);
            $("#myModal_cancel").modal('show');
        }else{
            $("#myModal_cancel").modal('hide');
        }
    }

    function tipo_operacao(elemento,id,v){

        $('#id_conta'+v)[0].value="-1";
        $('#cod_barras'+v)[0].value="";

        if(elemento.value=="transferencia"){
            $('#id_conta'+v)[0].value=id;
            $('#operacao_transferencia'+v)[0].style.display=null;
            $('#operacao_boleto'+v)[0].style.display="none";
            $('#operacao_boleto_titulo'+v)[0].style.display="none";
            $('#operacao_boleto_guia'+v)[0].style.display="none";
        }
        else if(elemento.value=="boleto_guia"){
            $('#id_conta'+v)[0].value="-2";
            $('#operacao_boleto'+v)[0].style.display=null;
            $('#operacao_boleto_guia'+v)[0].style.display=null;
            $('#operacao_transferencia'+v)[0].style.display="none";
            $('#operacao_boleto_titulo'+v)[0].style.display="none";
        }
        else{
            $('#operacao_boleto'+v)[0].style.display=null;
            $('#operacao_boleto_titulo'+v)[0].style.display=null;
            $('#operacao_transferencia'+v)[0].style.display="none";
            $('#operacao_boleto_guia'+v)[0].style.display="none";
        }
    }

    function transformarEmCódigoDeBarras(element,v){
        try{
            b = new Boleto(element.value);
            console.log(b.barcode());
            $('#cod_barras'+v)[0].value = b.barcode();
            $('#add_conta_valor'+v)[0].value = b.amount();
        }
        catch(exception){
            console.log(exception);
            alert("Erro na linha digitável");
        }
    }

    function transformarEmCódigoDeBarrasGuia(element,v){
        try{
            b = element.value;
            b=b.split(" ");
            b[0]=b[0].split("-")[0];
            b[1]=b[1].split("-")[0];
            b[2]=b[2].split("-")[0];
            b[3]=b[3].split("-")[0];
            $('#cod_barras'+v)[0].value = b.join('');
        }
        catch(exception){
            alert("Erro na linha digitável");
        }
    }

    function edit(param) {
        var dataVencimento = $(param).data('vencimento');
        dataVencimento = dataVencimento.split('/').reverse().join('-');
        $('#fornecedor_span1').html('<label>Fornecedor</label><input type="text" class="form-control input-sm" name="fornecedor" id="fornecedor" placeholder="Fornecedor" readonly="readonly" required>');
        $('#fornecedor').val($(param).data('fornecedor'));
        $('div.divCategoria select').val($(param).data('categoria'));
        $('#add_conta_valor1').val($(param).data('valor'));
        $('#dt_vencimento').val(dataVencimento);
        $('#descricao-forn').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));

    }

    function digitalizaDocs(botao, id){
        var url = "<?= site_url('contas/digitalizar_new/') ?>/" + id;
        botao = $(botao);
        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>')
        $.post(url, function (data){
            if (data) {
                $('div#myModal_digitalizar > div.modal-body').html(data);
                $('#bodyDigitalizar').html(data)
                botao.attr('disabled', false).html('<i class="fa fa-cloud-upload"></i>')
                $('div#myModal_digitalizar').modal('show');
            } else {
                return false;
                botao.attr('disabled', false).html('<i class="fa fa-cloud-upload"></i>')
            }
        });
    }

    var tabelaDados = $('#tabelaDados').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum setor a ser listado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
    });

    function exibirDados(botao){
        tabelaDados.clear().draw();
        var serial = $(botao).data('serial');
        var serial_retirado = $(botao).data('serial_ret');
        var servico = $(botao).data('servico');
        var placa = $(botao).data('placa');
        var clientes = $(botao).data('cliente');
        var user = $(botao).data('user');
        var os = $(botao).data('id_os');
        var valor = $(botao).data('valor');
        var valorTotal = $(botao).data('total');
        var data = $(botao).data('data');

        for (i=0; i < serial.length; i++){
            var dados = [
                os[i] ? '<a href="<?= site_url("servico/visualizar_os") ?>/'+os[i]+'" target="_blank">' + os[i] + '</a>' : '-',
                data[i] ? formatarData(data[i]): '-',
                servico[i] ? servico[i] : '-',
                placa[i] ? placa[i] : '-',
                serial[i] ? serial[i] : '-',
                serial_retirado[i] ? serial_retirado[i] : '-',
                clientes[i] ? clientes[i] : '-',
                user[i] ? user[i] : '-',
                valor[i] ? numberParaReal(parseFloat(valor[i])) : '-'
            ];

            tabelaDados.row.add(dados)
        }
        $('#valorTotalOs').html("Valor Total: R$" + valorTotal)
        tabelaDados.draw();
        $('#modalDados').modal('show');
    }

    function abrirComprovantes(botao, id){
        var url = "<?= site_url('contas/view_comprovantes_new/') ?>/" + id;
        botao = $(botao);
        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>')
        $.post(url, function (data){
            if (data) {
                $('div#myModal_comprovantes > div.modal-body').html(data);
                $('#bodyComprovantes').html(data)
                botao.attr('disabled', false).html('<i class="fa fa-folder-open"></i>')
                $('div#myModal_comprovantes').modal('show');
            } else {
                return false;
                botao.attr('disabled', false).html('<i class="fa fa-folder-open"></i>')
            }
        });
    }
    function formatarData(data) {
      if (!data) return '-'; 

      var partesData = data.split("-"); 
      return partesData[2] + "/" + partesData[1] + "/" + partesData[0]; 
    }

    function formatarMoeda(campo) {
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
</script>