<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

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
<style>
    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: -130px;
        background: rgba(70,20,15,0.3);
        z-index: 2;
        background-image: url(<?=base_url()?>media/img/loading2.gif);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: 100px;
    }
</style>
<!--<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">-->
<link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet">
<!--<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>-->


<div class="alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span></span>
</div>

<?php if (!empty($this->session->flashdata('sucesso'))) { ?>
    <div class="alert alert-success">
        <strong>Successo!</strong> <?= $this->session->flashdata('sucesso') ?>
    </div>
<?php } elseif (!empty($this->session->flashdata('erro'))) { ?>
    <div class="alert alert-danger">
        <strong>Erro!</strong> <?= $this->session->flashdata('erro') ?>
    </div>
<?php } ?>

<div class="row-fluid">
    <div class="span8">
        <h3 style="color: #7c7c7c;">Contas a Pagar Show Tecnologia</h3>
    </div>

    <div class="span4 card-blue">
        <div class="card" id="card-blue">
            <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                <path d="M0 0h24v24H0z" fill="none"/>
            </svg>
            <strong>Caixa: <br>R$ <?=number_format($estatisticas->caixa_consolidado, 2, ',', '.')?></strong>
        </div>
    </div>
</div>

<div class="row-fluid">
    <?php if ($this->auth->is_allowed_block('valores')) { ?>
        <fieldset>
            <legend style="font-size: 15px; color: #737373;">Período: <span>01/<?= date('m/Y'); ?> à <?= date('d/m/Y') ?></span></legend>
            <div class="span3 card-orange" style="margin-left: 0px;">
                <div class="card" id="card-orange">
                    <svg fill="#F89406" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong>A pagar: <br>R$ <?=number_format($estatisticas->pagar, 2, ',', '.')?></strong>
                </div>
            </div>

            <div class="span3 card-green">

                <div class="card" id="card-green">
                    <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong>Pagos: <br>R$ <?=number_format($estatisticas->pago, 2, ',', '.')?></strong>
                </div>
            </div>

            <div class="span3 card-blue">
                <div class="card" id="card-blue">
                    <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong>Entradas: <br>R$ <?=number_format($estatisticas->caixa, 2, ',', '.')?></strong>
                </div>
            </div>

            <div class="span3 card-red">
                <div id="card-red" class="card <?=$estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo'?>">
                    <?php if ($estatisticas->saldo > 0): ?>
                        <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            <path d="M0 0h24v24H0z" fill="none"/>
                        </svg>
                    <?php else: ?>
                        <svg fill="#BF0811" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            <path d="M0 0h24v24H0z" fill="none"/>
                        </svg>
                    <?php endif ?>
                    <strong>Saldo: <br>R$ <?=number_format($estatisticas->saldo, 2, ',', '.')?></strong>
                </div>
            </div>
        </fieldset>
    <?php } ?>   
</div>
<br><br>
<div class="row-fluid">
    <div>
        <!--<a href="#myModal" data-toggle="modal" class="btn-design btn btn-primary"><i class="icon-plus icon-white"></i> Adicionar Conta</a>-->
        <a href="#cadCateg" data-toggle="modal" class="btn-design btn btn-primary"><i class="icon-plus icon-white"></i> Adicionar Categoria</a>
        <a href="<?=base_url()?>index.php/contas/remessas" class="btn-design btn btn-primary"><i class="fa fa-gears"></i> Remessas</a>
    </div>
</div><br>
<div>
    <div class="material-switch pull-left">
        <input id="someSwitchOptionPrimary" name="someSwitchOption001" type="checkbox"/>
        <label for="someSwitchOptionPrimary" class="label-primary"></label>
    </div>
    <small class="labelBt"><i>Ordens de Serviço</i></small>
</div>
<br><br>
<table id="myTable" class="table table-hover display">
    <thead>
    <th id="id" class="span1">Id</th>
    <th class="span3">Fornecedor</th>
    <th class="span4">Descrição</th>
    <th class="span2">Categoria</th>
    <th class="span2">Responsável</th>
    <th class="span1">Lançamento</th>
    <th class="span1">Vencimento</th>
    <th class="span2">Valor</th>
    <th class="span1">Status</a></th>
    <th class="span1">Pagamento</a></th>
    <th class="span3">Ferramentas</th>
    </thead>
    <tbody align="center">
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="width: 15%"><i style="color: #499BEA" id="spin" class="fa fa-refresh fa-spin fa-3x fa-fw"></i></td>
    <td></td>
    <td></td>
    <td></td>
    </tbody>
</table>

<div class="modal hide" id="myModal">
    <div id="load" style="display:none;" class="overlay"></div>
    <div class="modal-header">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Adicionar Conta</h3>
    </div>
    <div class="modal-body">
        <form method="post" action='<?php echo site_url('contas/add')?>' name="" id="form">
        
        <label>Categoria</label>
            <select required class="form-control" onchange="verifica_categoria(this,'')" id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria): ?>
                    <option><?= $categoria; ?></option>
                <?php endforeach; ?>
            </select>
            <span id="fornecedor_span">
                
            </span>
            <label>Descrição</label>
            <textarea type="text" class="span3" name="descricao" id="descricao" placeholder="Descrição" required></textarea>

            <label>Valor</label>
            <input type="text" class="span3 money2" name="valor" id="add_conta_valor" placeholder="0,00" required>

            <label>Data Vencimento</label>
            <input type="text" class="span3 date" name="data_vencimento" id="add_conta_vencimento" placeholder="__/__/___" required>

            
        
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a>
        <button type="submit" class="btn btn-primary" id="add">Adicionar</button>
    </div>
    </form>
</div>

<div class="modal hide" id="myModalEstornar">
    <div class="modal-header">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Estornar Pagamento <span id="titulo_id_conta"></span></h3>
    </div>
    
    <div class="modal-body">
        <form id="form_estornar">
            <input type="hidden" id="input_id_conta_estornar" name="id_conta" required>
            <label>senha</label>
            <input type="password" class="span3" id="input_senha_estornar" name="senha" required value="">
            <label>Observações</label>
            <input type="text" class="span3" id="input_observacoes_estornar" name="observacoes" required>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a>
        <a class="btn btn-success" onclick="request_estornar()">Estornar</a>
    </div>
</div>

<div class="modal hide" id="myModal_editar">
    <div id="load1" style="display:none;" class="overlay"></div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Editar Conta</h3>
    </div>
    <form method="post" action='<?php echo site_url('contas/update')?>' name="" id="form_editar">
        <div class="modal-body">
            <div class="alert fornec" style="display:none">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span></span>
            </div>
            <div class="form-group divCategoria">
                <label for="sel1">Categoria:</label>
                <select class="form-control" id="sel1" name="categoria" onclick="verifica_categoria(this,'1')">
                    <?php foreach ($categorias as $categoria): ?>
                        <option><?= $categoria ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <input type="hidden" name="id" id="fornecedor_id">
            
            <span id="fornecedor_span1">
                <label>Fornecedor</label>
                <input type="text" class="span5" name="fornecedor" id="fornecedor" placeholder="Fornecedor" readonly="readonly" required>
            </span>
            <label>Valor</label>
            <input type="text" class="span5 money2" name="valor" id="add_conta_valor1" placeholder="Valor" required>
            <label>Vencimento</label>
            <input type="text" class="span5" name="dt_vencimento" id="dt_vencimento" placeholder="Vencimento" required>
            <label>Descriçao</label>
            <input type="text" class="span5" name="descricao-forn" id="descricao-forn" placeholder="Descriçao" required>
            <br>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
            <button class="btn btn-primary" id="editar">Editar</button>
        </div>
    </form>
</div>

<div class="modal hide" id="myModal_update">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Pagamento</h3>
    </div>
    <form method="post" id="formPagamento">
        <div class="modal-body">
            <input type="hidden" name="id" id="idid" value="" >
            <label>Senha</label>
            <input type="password" class="span3" name="senha_pagamento" id="senha_pagamento" value="" required>
            <label>Valor Pago</label>
            <input type="text" class="span3 money2" name="valor_pago" id="money" value="" required>
            <label>Data Pagamento</label>
            <input type="text" class="span3 date" name="data_pagamento" id="data" placeholder="__/__/___" required>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
            <button type="submit" class="btn btn-primary">Pagar</button>
        </div>
    </form>
</div>

<div class="modal hide" id="myModal_cancel">
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

<div id="myModal_digitalizar" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-header" style="text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Digitalizar Conta</h4>
    </div>
    <div class="modal-body">
    </div>
</div>

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

<div id="myModal_comprovantes" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-header" style="text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Digitalizar Comprovantes</h4>
    </div>
    <div class="modal-body">
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

<script src="<?php echo base_url('media') ?>/js/validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/boleto.js/boleto.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#formPagamento').submit(function(){
            var url = "<?php echo site_url('contas/update')?>";
            var dados = jQuery( this ).serialize();

            jQuery.ajax({
                type: "POST",
                url: url,
                data: dados,
                success: function(data)
                {   
                    if(data=="4"){
                        alert('Senha incorreta!');
                    }
                    else if(data=="3"){
                        alert('Usuário sem permissão!');
                    }
                    else{
                        table.ajax.reload(null, false);
                        alert('Pagamento efetuado com sucesso!');
                    }
                    $('#myModal_update').modal('hide');
                }
            });

            return false;
        });
    });

    $(document).ready(function() {
        var table = $('#myTable').DataTable( {
            responsive: true,
            serverSide: true,
            ajax: "<?= site_url('contas/contas_ajax/1') ?>",
            oLanguage: {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
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
            ordering: false
        });

        $("#someSwitchOptionPrimary").click(function () {
            if( $("#someSwitchOptionPrimary").is(':checked') ){
                table.ajax.url( "<?= site_url('contas/contas_por_inst') ?>" ).load();
            } else{
                table.ajax.url( "<?= site_url('contas/contas_ajax/1') ?>" ).load();
            }
        });

        $("#close").click(function () {
            $("#add").prop("disabled", false);
        });

        $("#close0").click(function () {
            $("#add").prop("disabled", false);
        });

        $('#add').on('submit', function(e) {
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form').attr('action');
            $.post(url, form, function (data){
                if (data && data > 0) {
                    $("#add").prop("disabled", true);
                    $('#form').trigger('reset');
                    table.ajax.reload();
                }else{
                    $(".alert").find('span').html('').html('Não foi possível adicionar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#update').on('click', function(e) {
            $('#money').focus();
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_update').attr('action');
            $.post(url, form, function (data){
                if (data.status == 'OK') {
                    table.ajax.reload(null, false);
                } else {
                    $(".alert").find('span').html('').html('Não foi possível atualizar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#cancelar').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_cancel').attr('action');
            $.post(url, form, function (data){
                if (data == 1) {
                    table.ajax.reload(null, false);
                } else if (data == 3) {
                    $(".alert").find('span').html('').html('Você não tem permissão para cancelamento.');
                    $(".alert").addClass('alert-danger').show();
                } else{
                    $(".alert").find('span').html('').html('Não foi possível cancelar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#editar').on('click', function(e) {
            e.preventDefault();
            $('#editar').attr('disabled', 'disabled').text('Salvando...');
            var form = $(this).closest('form').serialize();
            var url = $('#form_editar').attr('action');
            if($('#fornecedor').val() != ''){
                $.post(url, form, function (data){
                    if (data) {
                        table.ajax.reload(null, false);
                    }else{
                        $('#editar').removeAttr('disabled').text('Salvar');
                        $(".fornec").find('span').html('').html('Não foi possível editar o fornecedor. Tente novamente.');
                        $(".fornec").addClass('alert-danger').show();
                    }
                });
            }else{
                $('#editar').removeAttr('disabled').text('Salvar');
                $(".fornec").find('span').html('').html('Preencha o campo fornecedor corretamente.');
                $(".fornec").addClass('alert-danger').show();
            }

        });

        $('.date').mask('99/99/9999', {clearIfNotMatch: true});

        $('#myModal_digitalizar').on('hidden', function(){
           
            $(this).data('modal', null);
        });

        $('#myModal_comprovantes').on('hidden', function(){
            $(this).data('modal', null);
            $('#myModal_comprovantes > .modal-body').html('');
        });

    });
    function update(param) {
        $('#money').focus();
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        var valor = $(param).data('valor');
        var now = new Date();
        $('#idid').val(id);
        $('#money').val(valor);
        $.ajax({
            type: "post",
            data: {id: id},
            url: controller,
            dataType: "json",
            success: function(data){
                if(data.updated != 1 || data.status == 0) {
                    $('#id').val(data.id);
                    $('#money').val(data.valor);
                    $('#data').val(now.getDate()+'/'+(now.getMonth()+1)+'/'+now.getFullYear());
                    $("#myModal_update").modal('show');
                }
            }
        });
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

    function edit(param) {
        $('#fornecedor_span1').html('<label>Fornecedor</label><input type="text" class="span5" name="fornecedor" id="fornecedor" placeholder="Fornecedor" readonly="readonly" required>');
        $('#fornecedor').val($(param).data('fornecedor'));
        $('div.divCategoria select').val($(param).data('categoria'));
        $('#add_conta_valor1').val($(param).data('valor'));
        $('#dt_vencimento').val($(param).data('vencimento'));
        $('#descricao-forn').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));
    }

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

    $('#btn-imprimir').click(function () {
        var conteudo = document.getElementById('myModal_dados').innerHTML,
            tela_impressao = window.open('about:parent');

        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    })

</script>
<script>
    function get_intaladores_pendentes(){
        $('#instaladores_pendentes')[0].innerHTML=""
        $.getJSON('<?=base_url()?>index.php/api/get_instaladores_pendentes',function (data){
            console.log(data);
            $.each(data,function (index, d){
                var template = ['<tr title="CPF/CNPJ: '+d.cpf_cnpj+' Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'"><td><input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input></td>'+'<td>'+d.conta_id+'</td><td>'+d.fornecedor+'</td><td>R$ '+d.valor+'</td></tr>'].join('');
                $('#instaladores_pendentes').append(template);
            });
            $('#myModalInstaladores').modal();
        });
    }
    function gerar_remessa(){
        $.ajax({
            type: "POST",
            url: "<?=base_url()?>index.php/api/get_remessa_instaladores",
            data: $('#myModalInstaladores input').serialize(),
            success: function (data){
                data = JSON.parse(data);
                var element = document.createElement('a');
                element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                element.setAttribute('download', data.nome);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            }
        });
    }
</script>
<script>
    function estornar(id){
        $('#titulo_id_conta')[0].innerHTML = "#"+id.toString();
        $('#input_id_conta_estornar')[0].value=id;
        $('#myModalEstornar').modal();
    }
    function request_estornar(){
        if(!$('#input_senha_estornar')[0].value){
            alert("Insira a senha!");
            return;
        }

        jQuery.ajax({
            type: "POST",
            url: "<?=base_url()?>index.php/contas/estornar",
            data: $('#form_estornar').serialize(),
            success: function(data)
            {
                if(data=="4"){
                    alert('Senha incorreta!');
                }
                else if(data=="3"){
                    alert('Usuário sem permissão!');
                }
                else{
                    table.ajax.reload(null, false);
                    alert('Pagamento estornado com sucesso!');
                }
                $('#myModalEstornar').modal('hide');
            }
        });
    }
</script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/select2.css">

<script src="<?=base_url()?>assets/js/select2.js"></script>
<script>
    var fornecedor = false;
    function verifica_categoria(e,v){
        
        if(e.value=='SALÁRIO'||e.value=='SALARIO'||e.value=='AJUDA DE CUSTO'||e.value=='ADIANTAMENTO SALARIAL'||e.value=='RESCISÃO'||e.value=='RESCISAO'||e.value=='FÉRIAS'||e.value=='FERIAS'||e.value=='DÉCIMO TERCEIRO'||e.value=='13º SALÁRIO'||e.value=='DECIMO TERCEIRO'||e.value=='PRO-LABORE'||e.value=='ADIANTAMENTO BENEFICIO'){
            document.getElementById("load"+v).style.display=null;
            var html='<input type="hidden" id="id_conta'+v+'" name="id_conta" value="-1"/> <label>Funcionário</label><select onchange="get_conta(\''+v+'\')" name="fornecedor" id="fornecedor'+v+'" class="span3" required><option></option>';
            $.getJSON('<?=base_url()?>index.php/contas/get_funcionarios', function (data){
                $.each(data,function (index, json){
                    html+='<option data-id="'+json.id+'">'+json.nome+'</option>'
                });
                html+="</select><span id='conta_fornecedor"+v+"'></span>"
                $('#fornecedor_span'+v).html(html);
                $('#fornecedor'+v).select2();
                document.getElementById("load"+v).style.display="none";
                fornecedor = false;
            });
        }
        else{
            document.getElementById("load"+v).style.display=null;
            var html='<input type="hidden" id="id_conta'+v+'" name="id_conta" value="-1"/> <label>Fornecedor</label><select onchange="get_conta(\''+v+'\')" name="fornecedor" id="fornecedor'+v+'" class="span3" required><option></option>';
            $.getJSON('<?=base_url()?>index.php/cadastro_fornecedor/getFornecedor', function (data){
                $.each(data,function (index, json){
                    html+='<option data-id="'+json.id+'">'+json.id+' - '+json.nome+'</option>'
                });
                html+="</select> <a href='<?=base_url();?>index.php/cadastro_fornecedor/add' style='color:black'><i class='fa fa-plus-circle'></i></a><span id='conta_fornecedor"+v+"'></span>"
                $('#fornecedor_span'+v).html(html);
                $('#fornecedor'+v).select2();
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
        $.getJSON('<?=base_url()?>index.php/contas/'+url+$('#fornecedor'+v+' option:selected').attr('data-id'), function (data){
            if(data.conta){
                $('#conta_fornecedor'+v).html('<label>Operação</label><select onchange="tipo_operacao(this,\''+data.id+'\',\''+v+'\')"><option></option><option value="transferencia">Transferência Bancária</option><option value="boleto">Pagamento de título (boleto)</option><option value="boleto_guia">Pagamento de guia (boleto)</option></select><span id="operacao_transferencia'+v+'" style="display:none;"><label>Conta</label><input type="text" value="CPF: '+data.cpf+', Agência: '+data.agencia+', conta: '+data.conta+'" class="span5" disabled="disabled"></span><span id="operacao_boleto'+v+'" style="display:none;"><span id="operacao_boleto_titulo'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto'+v+'" onchange="transformarEmCódigoDeBarras(this,\''+v+'\')" maxlength="54" class="span5"></span><span id="operacao_boleto_guia'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto_guia'+v+'" onchange="transformarEmCódigoDeBarrasGuia(this,\''+v+'\')" class="span5"></span><label>Código de barras</label><input type="text" id="cod_barras'+v+'" name="cod_barras" class="span5"></span>');
                $("#linha_digitavel_boleto"+v).mask('99999.99999 99999.999999 99999.999999 9 99999999999999');
                $("#linha_digitavel_boleto_guia"+v).mask('99999999999-9 99999999999-9 99999999999-9 99999999999-9');
            }
            else{
                $('#conta_fornecedor'+v).html('<label>Operação</label><select onchange="tipo_operacao(this,\'-1\',\''+v+'\')"><option></option><option value="transferencia">Transferência Bancária</option><option value="boleto">Pagamento de título (boleto)</option><option value="boleto_guia">Pagamento de guia (boleto)</option></select><span id="operacao_transferencia'+v+'" style="display:none;"> <label>Conta</label><input type="text" value="Conta não encontrada, verifique o cadastro" class="span5" disabled="disabled"></span><span id="operacao_boleto'+v+'" style="display:none;"><span id="operacao_boleto_titulo'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto'+v+'" onchange="transformarEmCódigoDeBarras(this,\''+v+'\')" maxlength="54" class="span5"></span><span id="operacao_boleto_guia'+v+'" style="display:none;"><label>Linha Digitável</label><input type="text" id="linha_digitavel_boleto_guia'+v+'" onchange="transformarEmCódigoDeBarrasGuia(this,\''+v+'\')" class="span5"></span><label>Código de barras</label><input type="text" id="cod_barras'+v+'" name="cod_barras" class="span5"></span>');
                $("#linha_digitavel_boleto"+v).mask('99999.99999 99999.999999 99999.999999 9 99999999999999');
                $("#linha_digitavel_boleto_guia"+v).mask('99999999999-9 99999999999-9 99999999999-9 99999999999-9');
                $('#id_conta'+v)[0].value="-1";
            }
            document.getElementById("load"+v).style.display='none';
            
        });
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
</script>