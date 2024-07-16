<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js" >
</script><script src="<?php echo base_url('media') ?>/js/jquery-mask.js"></script>
<link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

<h3><?=lang("contas_a_pagar") . " ". lang("showtechnology")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> > 
    <?=lang('financeiro')?> > 
    <a href="<?=site_url('Contas')?>">Contas</a> >
    ShowTechnology
</div>

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

    .dataTables_wrapper .dataTables_processing {
        background: none;
        margin-top: 3rem !important;
    }
    .bord{
        border-left: 3px solid #03A9F4;
    }
    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
    }

</style>

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

<hr>
<div style="display: flex;">
    <?php if ($this->auth->is_allowed_block('valores')) { ?>
        <div class="span3 card-orange" style="margin-right: 10px; width: 280px;">
            <div class="card" id="card-orange" style="height: 88px;">
                <svg fill="#F89406" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>
                <strong>A pagar: <br>US$ <?=number_format($estatisticas->pagar, 2, '.', ',')?></strong>
            </div>
        </div>

        <div class="span3 card-green" style="margin-right: 10px; width: 280px">
            <div class="card" id="card-green" style="height: 88px;">
                <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>
                <strong>Pagos: <br>US$ <?=number_format($estatisticas->pago, 2, '.', ',')?></strong>
            </div>
        </div>

        <div class="span3 card-blue" style="margin-right: 10px; width: 280px;" >
            <div class="card" id="card-blue" style="height: 88px;">
                <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>
                <strong>Entradas: <br>US$ <?=number_format($estatisticas->caixa, 2, '.', ',')?></strong>
            </div>
        </div>

        <div class="span3 card-red" style="margin-right: 10px; width: 280px;;">
            <div id="card-red" class="card <?=$estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo'?>" style="height: 88px;">
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
                <strong>Saldo: <br>US$ <?=number_format($estatisticas->saldo, 2, '.', ',')?></strong>
            </div>
        </div>
    <?php } ?>
</div>
<br><br>
<div class="row-fluid">
    <div tyle="display: contents;">
        <!--<a href="#myModal" data-toggle="modal" class="btn-design btn btn-primary"><i class="icon-plus icon-white"></i> Adicionar Conta</a>-->
        <a href="#cadCateg" data-toggle="modal" class="btn-design btn btn-primary" style="height: 40px;"><i class="fa fa-plus"></i> Adicionar Categoria</a>
    </div>
</div><br>
<br><br>

<table id="myTable" class="display table responsive table-bordered">
    <thead style="background-color: white;">
    <th id="id" class="span1" style="color: #03A9F4 !important;">Id</th>
    <th class="span3" style="color: #03A9F4 !important;">Fornecedor</th>
    <th class="span4" style="color: #03A9F4 !important;">Descrição</th>
    <th class="span2" style="color: #03A9F4 !important;">Categoria</th>
    <th class="span2" style="color: #03A9F4 !important;">Responsável</th>
    <th class="span1" style="color: #03A9F4 !important;">Lançamento</th>
    <th class="span1" style="color: #03A9F4 !important;">Vencimento</th>
    <th class="span2" style="color: #03A9F4 !important;">Valor</th>
    <th class="span1" style="color: #03A9F4 !important;">Status</a></th>
    <th class="span1" style="color: #03A9F4 !important;">Pagamento</a></th>
    <th class="span3" style="color: #03A9F4 !important;">Ferramentas</th>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="modal hide" id="myModal">
    <div class="modal-header">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Adicionar Conta</h3>
    </div>
    <div class="modal-body">
        <form method="post" action='<?php echo site_url('contas/add/true')?>' name="" id="form">
            <label>Fornecedor</label>
            <input type="number" value="4" name="empresa" style="display: none;">
            <input type="text" name="fornecedor" class="span3"
                   data-provide="typeahead" data-source='<?php echo $fornecedores?>'
                   data-items="6"
                   placeholder="Fornecedor"
                   autocomplete="off" value="<?php echo $this->input->post('fornecedor') ?>" />

            <label>Descrição</label>
            <textarea type="text" class="span3" name="descricao" id="descricao" placeholder="Descrição" required></textarea>

            <label>Categoria</label>
            <select class="form-control" id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria): ?>
                    <option><?= $categoria; ?></option>
                <?php endforeach; ?>
            </select>
            <label>Valor</label>
            <input type="text" class="span3 us" name="valor" placeholder="0.00" required>

            <label>Data Vencimento</label>
            <input type="text" class="span3 date" name="data_vencimento" placeholder="__/__/___" required>

            <br>
            <button type="submit" class="btn btn-primary" id="add">Adicionar</button>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a>
    </div>
</div>

<div id="myModal_editar" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <div id="load1" style="display:none;" class="overlay"></div>
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3 style="text-align: center;">Editar Conta</h3>
        </div>
        <form method="post" action='<?php echo site_url('contas/update')?>' name="" id="form_editar">
            <div class="modal-body">
                <div class="alert fornec" style="display:none">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span></span>
                </div>
                <div class="form-group divCategoria" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px; color: #777;">
                    <label class="control-label" for="sel1">Categoria:</label>
                    <select class="form-control" id="sel1" name="categoria" onclick="verifica_categoria(this,'1')">
                        <?php foreach ($categorias as $categoria): ?>
                            <option><?= $categoria ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <input type="hidden" name="id" id="fornecedor_id">
                <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px; color: #777;">
                    <label class="control-label">Fornecedor</label>
                    <input type="text" class="span5 form-control" name="fornecedor" id="fornecedor" placeholder="Fornecedor" readonly="readonly" required>
                </div>
                <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px; color: #777;">
                    <label class="control-label">Valor</label>
                    <input type="text" class="span5 money2 form-control" name="valor" id="add_conta_valor1" placeholder="Valor" required>
                </div>   
                <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px; color: #777;">
                    <label class="control-label">Vencimento</label>
                    <input type="text" class="span5 form-control" name="dt_vencimento" id="dt_vencimento" placeholder="Vencimento" placeholder="__/__/___" required>
                </div>
                <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px; color: #777;">
                    <label class="control-label">Descriçao</label>
                    <input type="text" class="span5 form-control" name="descricao-forn" id="descricao-forn" placeholder="Descriçao" required>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                <button class="btn btn-primary" id="editar">Editar</button>
            </div>
        </form>
    </div>
</div>

<div id="myModal_update" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3 style="text-align: center;">Pagamento</h3>
        </div>
        <div class="modal-content" style="border:none;">
            <form method="post" style="display: grid;" id="formPagamento">
                <div class="modal-body">
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4;padding-left: 10px;">
                        <input type="hidden" name="id" id="idid" value="" >
                        <label class="control-label">Senha</label>
                        <input type="password" class="span3 form-control" name="senha_pagamento" id="senha_pagamento" value="" required>
                    </div>
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4;padding-left: 10px;">
                        <label class="control-label">Valor Pago</label>
                        <input type="text" class="span3 money2 form-control" name="valor_pago" id="money" value="" required>
                    </div>
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4;padding-left: 10px;">
                        <label class="control-label">Data Pagamento</label>
                        <input type="text" class="span3 date form-control" name="data_pagamento" id="data" placeholder="__/__/___" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-primary">Pagar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_cancel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Cancelamento</h3>
        </div>
        <div class="modal-content" style="border:none;">
            <form method="post">
                <div class="modal-body">
                    <label>Tem certeza que deseja cancelar?</label>
                    <input type="hidden" name="id" id="cancel-id">
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-primary" id="confirmar-cancelamento">Sim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_digitalizar"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

            <div class="modal-content" style="border:none;">
                <div class="modal-body">
                </div>
            </div>
    </div>
</div>

<div id="myModalEstornar" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" class="modal-dialog" style="background-color: #fff!important;border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
            <h3 style="text-align: center;">Estornar Pagamento <span id="titulo_id_conta"></span></h3>
        </div>
        <div class="modal-content" style="border:none; box-shadow: none;">
            <div class="modal-body">
                <form id="form_estornar">
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4;padding-left: 10px;">
                        <input type="hidden" id="input_id_conta_estornar" name="id_conta" required>
                        <label class="control-label">Senha</label>
                        <input type="password" class="span3 form-control" id="input_senha_estornar" name="senha" required value="">
                    </div>
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4;padding-left: 10px;">
                        <label class="control-label">Observações</label>
                        <input type="text" class="span3 form-control" id="input_observacoes_estornar" name="observacoes" required>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a>
            <a class="btn btn-primary" onclick="request_estornar()">Estornar</a>
        </div>
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

<!-- MODAL DE CADASTRO DE CATEGORIAS -->
<div id="cadCateg" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
            <div class="modal-header" style="text-align: center; border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nova Categoria</h4>
            </div>
            <div class="modal-content" style="border:none; padding-top: 10px;">
                <form action="<?= site_url('contas/add_categoria') ?>" method="POST">
                    <div class="modal-body">
                        <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px;">
                            <label class="control-label" for="categ">Categoria: </label>
                            <input id="categ" name="categoria" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>

    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        var table = $('#myTable').on('processing.dt', function () {
            // Centralizar na tela o Elemento que mostra o carregamento de
            // dados da tabela
            $('.dataTables_processing')[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        }).DataTable( {
            "ajax": {
                "url": "<?= site_url('contas/contas_ajax2/1') ?>",
                "data": function (d) {
                    d.start = d.start || 0;
                    d.length = d.length || 10;
                    d.per_page = d.length; // Adicione per_page à solicitação AJAX
                }
            },
            ordering: false,
            paging: true,
            info: true,
            processing: true,
            serverSide: true,
            lengthChange: true,
            "bLengthChange": true,
            language: {
                sProcessing: '<STRONG >Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>',
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

        $("#someSwitchOptionPrimary").click(function () {
            if( $("#someSwitchOptionPrimary").is(':checked') ){
                table.ajax.url( "<?= site_url('contas/contas_por_inst') ?>" ).load();
            } else{
                table.ajax.url( "<?= site_url('contas/contas_ajax2/1') ?>" ).load();
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

    $('#confirmar-cancelamento').click(function(event) {
        event.preventDefault();

        var id = $('#cancel-id').val();

        $.ajax({
            type: 'POST',
            url:"<?php echo site_url('contas/deleteConta');?>",
            data: { id: id },
            success: function(response) {
                if(response){
                    alert('Conta cancelada com sucesso!');
                    location.reload();
                }
                else{
                    alert("Não foi possível cancelar a conta");
                }
            },
            error: function() {
                alert('Erro ao deletar a conta.');
            }
        });

        $('#myModal_cancel').modal('hide');
    });


        $('#dt_vencimento').mask('00/00/0000');
        $('.money2').mask('000.000.000.000.000,00', {reverse: true});
        $('#editar').on('click', function(e) {
        e.preventDefault();
        $('#editar').attr('disabled', 'disabled').text('Salvando...');

            // Verificar se todos os campos obrigatórios estão preenchidos
            var fornecedor = $('#fornecedor').val();
            var valor = $('#add_conta_valor1').val();
            var vencimento = $('#dt_vencimento').val();
            var descricao = $('#descricao-forn').val();

            if (fornecedor !== '' && valor !== '' && vencimento !== '' && descricao !== '') {
                var form = $('#form_editar').serialize();
                var url = $('#form_editar').attr('action');

                $.post(url, form, function (data) {
                    if (data == true) {
                        $('#myModal_editar').modal('hide');
                        $(".fornec").removeClass('alert-danger').hide().find('span').html('');
                        alert('Conta editada com sucesso!');
                        location.reload();
                    } else {
                        $('#editar').removeAttr('disabled').text('Salvar');
                        $(".fornec").find('span').html('').html('Não foi possível editar a conta. Tente novamente.');
                        $(".fornec").addClass('alert-danger').show();
                    }
                }, 'json');
            } else {
                // Pelo menos um campo obrigatório está vazio, exiba mensagem de erro
                alert('Preencha todos os campos obrigatórios antes de salvar.');
                $('#editar').removeAttr('disabled').text('Salvar');
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

    jQuery(document).ready(function(){
        $('.us').mask("#,##0.00", {reverse: true});

        jQuery('#formPagamento').submit(function(){
            var url = "<?php echo site_url('contas/update')?>";
            var dados = jQuery( this ).serialize();

            jQuery.ajax({
                type: "POST",
                url: url,
                data: dados,
                success: function()
                {
                    $.getJSON('contas_ajax/4', function (data) {
                        var dynatable = $('#myTable').dynatable({
                            dataset: {
                                records: data
                            }
                        }).data('dynatable');

                        dynatable.settings.dataset.originalRecords = data;
                        dynatable.process();
                    });

                    alert('Pagamento efetuado com sucesso!');
                    $('#myModal_update').modal('hide');
                }
            });

            return false;
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
        $('#fornecedor').val($(param).data('fornecedor'));
        $('div.divCategoria select').val($(param).data('categoria'));
        $('#valor').val($(param).data('valor'));
        $('#dt_vencimento').val($(param).data('vencimento'));
        $('#descricao-forn').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));
    }

    $('#btn-imprimir').click(function () {
        var conteudo = document.getElementById('myModal_dados').innerHTML,
            tela_impressao = window.open('about:parent');

        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    })

    function estornar(id){
        $('#titulo_id_conta')[0].innerHTML = "#"+id.toString();
        $('#input_id_conta_estornar')[0].value=id;
        $('#myModalEstornar').modal();
    }

    function request_estornar() {
        if (!$('#input_senha_estornar')[0].value) {
            alert("Insira a senha!");
            return;
        }

        jQuery.ajax({
            type: "POST",
            url: "<?=base_url()?>index.php/contas/estornar",
            data: $('#form_estornar').serialize(),
            success: function(data, textStatus, jqXHR) {
                if (jqXHR.status === 200) {
                    alert('Pagamento estornado com sucesso!');
                    location.reload();
                } else if (data == "4") {
                    alert('Senha incorreta!');
                } else if (data == "3") {
                    alert('Usuário sem permissão!');
                } else {
                    alert("Erro desconhecido");
                }
                $('#myModalEstornar').modal('hide');
            }
        });
    }


</script>