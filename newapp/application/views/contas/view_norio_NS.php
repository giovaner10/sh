<!-- <link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet"> -->
<!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script> -->

<style>
    body {
        background-color: #fff;
    }

    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: -130px;
        background: rgba(70, 20, 15, 0.3);
        z-index: 2;
        background-image: url(<?= base_url() ?>media/img/loading2.gif);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: 100px;
    }

    html {
        scroll-behavior: smooth
    }

    body {
        background-color: #fff !important;
    }

    table {
        width: 100% !important;
    }

    .blem {
        color: red;
    }

    .container-fluid {
        padding: 0;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
    }

    .bord {
        border-left: 3px solid #03A9F4;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th,
    td.wordWrap {
        max-width: 100px;
        word-wrap: break-word;
        text-align: center;
    }

    .checkbox label {
        font-weight: 700;
    }

    .select-container .select-selection--single {
        height: 35px !important;
    }

    .my-1 {
        margin-top: 1em !important;
        margin-bottom: 1em !important;
    }

    .mx-1 {
        margin-left: 1em;
        margin-right: 1em;
    }

    .pt-1 {
        padding-top: 1em;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .justify-content-end {
        justify-content: flex-end;
    }

    .align-center {
        align-items: center;
    }

    .modal-xl {
        max-width: 1300px;
        width: 100%;
    }

    .border-0 {
        border: none !important;
    }

    .markerLabel {
        background-color: #fff;
        border-radius: 4px;
        padding: 4px;
    }

    .action-bar * {
        margin-left: 5px;
    }

    .select-selection--multiple .select-search__field {
        width: 100% !important;
    }

    .card-orange {
        /*outline: 2px solid #F89406;*/
        color: #F89406;
        font-size: 1.6em;
        line-height: 1.3;
    }

    .card-green {
        /*outline: 1px solid #468847;*/
        color: #468847;
        font-size: 1.6em;
        line-height: 1.3;
        display: flex;
    }

    .card-blue {
        /*outline: 2px solid #2744ac;*/
        color: #499BEA;
        font-size: 1.6em;
        line-height: 1.3;

    }

    .card-red {
        /*outline: 2px solid #BF0811;*/
        color: #BF0811;
        font-size: 1.6em;
        line-height: 1.3;

    }

    #card-orange,
    #card-green,
    #card-blue,
    #card-red {
        width: calc(100% - 50px);
        display: flex;
        height: 80px;
        float: left;
        background-color: #ffffff;
        padding: 10px;
        -webkit-box-shadow: 1px 0px 5px 0px rgba(191, 191, 191, 1);
        -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        -moz-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        /*-moz-box-shadow: 1px 0px 5px 0px rgba(191,191,191,1);*/
        /*box-shadow: 1px 0px 5px 0px rgba(191,191,191,1);*/
        transition: all 0.3s ease;
    }

    #card-orange:hover {
        background: #F89406;
        color: #ffffff;
    }

    #card-green:hover {
        background: #468847;
        color: #ffffff;
    }

    #card-blue:hover {
        background: #499BEA;
        color: #ffffff;
    }

    #card-red:hover {
        background: #BF0811;
        color: #ffffff;
    }

    .card:hover svg {
        fill: white;
    }

    .card strong {
        padding-left: 15px;
        padding-top: 5px;
        font-size: 20px;
    }


    #myTable th:nth-child(2),
    #myTable td:nth-child(2) {
        width: 20%;
    }

    #myTable th:nth-child(10),
    #myTable td:nth-child(10) {
        width: 5%;
    }

    #myTable th:nth-child(9),
    #myTable td:nth-child(9) {
        width: 5%;
    }


    #myTable th:nth-child(11),
    #myTable td:nth-child(11) {
        width: 10%;
    }

    #myTable th:nth-child(1),
    #myTable td:nth-child(1) {
        width: 5%;
    }

    #myTable th:nth-child(3),
    #myTable td:nth-child(3) {
        width: 25%;
    }

    #myTable th:nth-child(4),
    #myTable td:nth-child(4) {
        width: 10%;
    }

    #myTable th:nth-child(5),
    #myTable td:nth-child(5) {
        width: 10%;
    }

    #myTable th:nth-child(6),
    #myTable td:nth-child(6) {
        width: 5%;
    }

    #myTable th:nth-child(7),
    #myTable td:nth-child(7) {
        width: 5%;
    }

    #myTable th:nth-child(8),
    #myTable td:nth-child(8) {
        width: 10%;
    }

    #myTable th,
    #myTable td {
        word-wrap: break-word !important;
        white-space: normal !important;
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


<h3><?= lang("contas_norio_momoi") ?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?= site_url('Homes') ?>">Home</a> >
    <?= lang('departamentos') ?> >
    <?= lang('financeiro') ?> >
    <?= lang('contas') ?> >
    <?= lang('contas_norio_momoi') ?>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-3 card-blue" style="float: right; margin-bottom: -30px;">
        <div class="card" id="card-blue">
            <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 21 22">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z" />
                <path d="M0 0h24v24H0z" fill="none" />
            </svg>
            <strong>Caixa: <br>R$ <?= number_format($estatisticas->caixa_consolidado, 2, ',', '.') ?></strong>
        </div>
    </div>
</div>


<div class="row">
    <?php if ($this->auth->is_allowed_block('valores')) { ?>
        <fieldset>
            <legend style="font-size: 15px; color: #737373;margin-left: 15px">Período: <span>01/<?= date('m/Y'); ?> à <?= date('d/m/Y') ?></span></legend>
            <div class="col-md-3 card-orange">
                <div class="card" id="card-orange">
                    <svg fill="#F89406" xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z" />
                        <path d="M0 0h24v24H0z" fill="none" />
                    </svg>
                    <strong>A pagar: <br>R$ <?= number_format($estatisticas->pagar, 2, ',', '.') ?></strong>
                </div>
            </div>

            <div class="col-md-3 card-green">

                <div class="card" id="card-green">
                    <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z" />
                        <path d="M0 0h24v24H0z" fill="none" />
                    </svg>
                    <strong>Pagos: <br>R$ <?= number_format($estatisticas->pago, 2, ',', '.') ?></strong>
                </div>
            </div>

            <div class="col-md-3 card-blue">
                <div class="card" id="card-blue">
                    <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z" />
                        <path d="M0 0h24v24H0z" fill="none" />
                    </svg>
                    <strong>Entradas: <br>R$ <?= number_format($estatisticas->caixa, 2, ',', '.') ?></strong>
                </div>
            </div>

            <div class="col-md-3 card-red">
                <div id="card-red" class="card <?= $estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo' ?>">
                    <?php if ($estatisticas->saldo >= 0) : ?>
                        <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z" />
                            <path d="M0 0h24v24H0z" fill="none" />
                        </svg>
                    <?php else : ?>
                        <svg fill="#BF0811" xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z" />
                            <path d="M0 0h24v24H0z" fill="none" />
                        </svg>
                    <?php endif ?>
                    <strong>Saldo: <br>R$ <?= number_format($estatisticas->saldo, 2, ',', '.') ?></strong>
                </div>
            </div>
        </fieldset>
    <?php } ?>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <!--<a href="#myModal" data-toggle="modal" class="btn-design btn btn-primary"><i class="icon-plus icon-white"></i> Adicionar Conta</a>-->
        <a class="btn btn-primary" data-toggle="modal" href="#cad-entrada"><i class="fa fa-plus" aria-hidden="true"></i></i> Adicionar Entrada</a>
        <a class="btn btn-primary" data-toggle="modal" data-target="#view-entrada" href="<?= site_url('contas/lista_entradas_norio_NS') ?>"><i class="fa fa-reorder"></i> Ver Entradas</a>
        <a href="#cadCateg" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></i> Adicionar Categoria</a>
        <a class="btn btn-primary" type="button" href="<?= base_url(); ?>index.php/contas/remessas_norio"><i class="fa fa-gears"></i> Remessas</a>
        <a class="btn btn-primary" type="button" href="<?= base_url(); ?>index.php/contas/remessas_norio_beta"><i class="fa fa-gears"></i> Remessas (Novo Layout - Beta)</a>
    </div>

</div>
<br>
<table id="myTable" class="table-responsive table-bordered table" style="width: 100%">
    <thead class="tableheader">
        <th id="id">ID</th>
        <th>Fornecedor</th>
        <th>Descrição</th>
        <th>Categoria</th>
        <th>Responsável</th>
        <th>Lançamento</th>
        <th>Vencimento</th>
        <th>Valor</th>
        <th>Status</a></th>
        <th>Pagamento</a></th>
        <th>Ferramentas</th>
    </thead>
    <tbody align="center">
        <tr id="loading-row">
            <td colspan="11">
                <i style="color: #499BEA" id="spin" class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
            </td>
        </tr>
    </tbody>
</table>

<div class="modal hide" id="myModal">
    <div class="modal-header">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Adicionar Conta</h3>
    </div>
    <form method="post" action='<?php echo site_url('contas/addNorio') ?>' name="" id="form">
        <div class="modal-body">
            <div id="load" style="display:none;" class="overlay"></div>
            <label>Categoria</label>
            <select required class="form-control" onchange="verifica_categoria(this)" id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria) : ?>
                    <option><?= $categoria; ?></option>
                <?php endforeach; ?>
            </select>
            <span id="fornecedor_span">

            </span>
            <label>Descrição</label>
            <textarea type="text" class="col-md-3" name="descricao" id="descricao" placeholder="Descrição" required></textarea>



            <label>Tipo Movimentação</label>
            <select class="form-control" id="mov" name="tipo_mov" disabled>
                <option>TRANSFERENCIA</option>
                <option>BOLETO</option>
            </select>

            <label>Valor</label>
            <input type="text" class="col-md-3 money2" name="valor" placeholder="0,00" required>

            <label>Data Vencimento</label>
            <input type="text" class="col-md-3 date" name="data_vencimento" placeholder="__/__/___" required>

            <br>

        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-primary" id="close" data-dismiss="modal">Fechar</a>
            <button type="submit" class="btn btn-primary" id="add">Adicionar</button>
        </div>
    </form>
</div>

<div id="myModal_editar" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Editar Conta</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/update') ?>' name="" id="form_editar">
                <div class="modal-body">
                    <div class="alert fornec" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span></span>
                    </div>
                    <input type="hidden" name="id" id="fornecedor_id">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Fornecedor</label>
                            <input type="text" class="col-md-12 form-control" name="fornecedor" id="fornecedor" placeholder="Fornecedor" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Categoria</label>
                            <input type="text" class="col-md-12 form-control" name="categoria" placeholder="Categoria" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Valor</label>
                            <input type="text" class="col-md-12 form-control" name="valor" id="valor" placeholder="Valor" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Vencimento</label>
                            <input type="text" class="col-md-12 form-control" name="dt_vencimento" id="dt_vencimento" placeholder="Vencimento" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Descriçao</label>
                            <input type="text" class="col-md-12 form-control" name="descricao-forn" id="descricao-forn" placeholder="Descriçao" required>
                        </div>
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="editar">Editar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_update" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Pagamento</h3>
            </div>
            <form id="formPagamento" method="post" action=''>
                <div class="modal-body">
                    <input type="hidden" name="id" id="idid" value="">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Senha</label>
                            <input type="password" class="col-md-12 form-control" name="senha_pagamento" id="senha_pagamento" value="" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Valor Pago</label>
                            <input type="text" class="col-md-12 form-control money2" name="valor_pago" id="money" value="" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Data Pagamento</label>
                            <input type="text" class="col-md-12 form-control date" name="data_pagamento" id="data" placeholder="__/__/___" required>
                        </div>
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Pagar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </form>
        </div>
    </div>
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
            <input type="password" class="col-md-3" id="input_senha_estornar" name="senha" required value="">
            <label>Observações</label>
            <input type="text" class="col-md-3" id="input_observacoes_estornar" name="observacoes" required>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-primary" id="close" data-dismiss="modal">Fechar</a>
        <a class="btn btn-primary" onclick="request_estornar()">Estornar</a>
    </div>
</div>

<div id="myModal_cancel" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Cancelamento</h3>
            </div>

            <form method="post" action='<?php echo site_url('contas/update/') ?>' id="form_cancel">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Tem certeza que deseja cancelar?</label>
                            <input type="hidden" name="id" id="cancel-id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-primary" id="cancelar">Sim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_digitalizar" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 id="myModalLabel">Digitalizar Conta</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div id="cad-entrada" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Adicionar Entrada</h3>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Data Entrada</label>
                        <input type="text" class="col-md-12 date form-control" id="dataNorio" name="data" placeholder="__/__/___" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Valor</label>
                        <input type="text" class="col-md-12 money2 form-control" id="valorNorio" name="valor" placeholder="0.00" required>
                    </div>

                    <div class="col-md-12 form-group">
                        <label>Descrição</label>
                        <textarea type="text" class="col-md-12 form-control" name="descricao" id="descricaoNorio" placeholder="Descrição" required></textarea>
                    </div>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="add-entrada">Adicionar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="view-entrada" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Entradas do Mês</h3>
            </div>
            <form>
                <div class="modal-body" id="modal-body-view-entrada">
                    carregando entradas...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="cadCateg" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Nova Categoria</h4>
            </div>
            <form action="<?= site_url('contas/add_categoria') ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="categ">Categoria</label>
                            <input type="text" class="col-md-12 form-control" id="categ" name="categoria" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#dataNorio').mask('00/00/0000');

        $('#valorNorio').mask('000,000,000.00', {
            reverse: true
        });
    });

    var table;
    jQuery(document).ready(function() {
        jQuery('#formPagamento').submit(function() {
            var url = "<?php echo site_url('contas/update/') ?>";
            var dados = jQuery(this).serialize();

            jQuery.ajax({
                type: "POST",
                url: url,
                data: dados,
                success: function() {
                    table.ajax.reload(null, false);

                    alert('Pagamento efetuado com sucesso!');
                    $('#myModal_update').modal('hide');
                }
            });

            return false;
        });
    });

    $(document).ready(function() {
        table = $('#myTable').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            order: [0, 'desc'],
            serverSide: true,
            ajax: {
                url: '<?= site_url('contas/contas_ajax_NS/3') ?>',
            },
            language: {
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
            ordering: false,
        });

        $("#close").click(function() {
            $("#add").prop("disabled", false);
        });

        $("#close0").click(function() {
            $("#add").prop("disabled", false);
        });

        $('#add').on('submit', function(e) {
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form').attr('action');
            $.post(url, form, function(data) {
                if (data && data > 0) {
                    $("#add").prop("disabled", true);
                    $('#form').trigger('reset');
                    table.ajax.reload(null, false);
                } else {
                    $(".alert").find('span').html('').html('Não foi possível adicionar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#update').on('click', function(e) {
            e.preventDefault();
            $('#money').focus();
            var form = $(this).closest('form').serialize();
            var url = $('#form_update').attr('action');
            $.post(url, form, function(data) {
                if (data == 1) {
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
            let html = $('#cancelar').html();
            $('#cancelar').html('<i class="fa fa-spinner fa-spin"></i>');

            $.post(url, form, function(data) {
                if (data == 1 || data == 'true') {
                    table.ajax.reload(null, false);
                    $(".alert").find('span').html('').html('Cancelado com sucesso.');
                    $(".alert").addClass('alert-success').show();
                } else {
                    $(".alert").find('span').html('').html('Não foi possível cancelar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
                $("#myModal_cancel").modal("hide");
                $('#cancelar').html(html);
            });
        });

        $('#editar').on('click', function(e) {
            e.preventDefault();
            $('#editar').attr('disabled', 'disabled').text('Salvando...');
            var form = $(this).closest('form').serialize();
            var url = $('#form_editar').attr('action');
            if ($('#fornecedor').val() != '') {
                $.post(url, form, function(data) {
                    if (data) {
                        table.ajax.reload(null, false);
                        $('#editar').removeAttr('disabled').text('Salvar');
                        $("#myModal_editar").modal("hide");
                    } else {
                        $('#editar').removeAttr('disabled').text('Salvar');
                        $(".fornec").find('span').html('').html('Não foi possível editar o fornecedor. Tente novamente.');
                        $(".fornec").addClass('alert-danger').show();
                    }
                });
            } else {
                $('#editar').removeAttr('disabled').text('Salvar');
                $(".fornec").find('span').html('').html('Preencha o campo fornecedor corretamente.');
                $(".fornec").addClass('alert-danger').show();
            }

        });

        $('.date').mask('99/99/9999', {
            clearIfNotMatch: true
        });

        $('#myModal_digitalizar').on('hidden', function() {
            $(this).data('modal', null);
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
            data: {
                id: id
            },
            url: controller,
            dataType: "json",
            success: function(data) {
                if (data.updated != 1 || data.status == 0) {
                    $('#id').val(data.id);
                    $('#money').val(data.valor);
                    $('#data').val(now.getDate() + '/' + (now.getMonth() + 1) + '/' + now.getFullYear());
                    $("#myModal_update").modal('show');
                }
            }
        });
    }

    function cancel(param) {
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        if (!$(param).hasClass('disabled')) {
            $('#cancel-id').val(id);
            $("#myModal_cancel").modal('show');
        } else {
            $("#myModal_cancel").modal('hide');
        }
    }

    function edit(param) {
        $('#fornecedor').val($(param).data('fornecedor'));
        $('#categoria').val($(param).data('categoria'));
        $('#valor').val($(param).data('valor'));
        $('#dt_vencimento').val($(param).data('vencimento'));
        $('#descricao-forn').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));
    }

    $('#add-entrada').click(function() {

        $.ajax({
            url: 'add_entrada_norio',
            type: 'POST',
            data: {
                'data': $('#dataNorio').val(),
                'valor': $('#valorNorio').val(),
                'descricao': $('#descricaoNorio').val()
            },
            success: function() {
                alert("Entrada cadastrada com sucesso!")
                table.ajax.reload(null, false);
                location.reload()
            }
        })

    })
</script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/select2.css">

<script src="<?= base_url() ?>assets/js/select2.js"></script>
<script>
    function verifica_categoria(e) {

        if (e.value == 'SALÁRIO' || e.value == 'SALARIO' || e.value == 'ADIANTAMENTO SALARIAL' || e.value == 'AJUDA DE CUSTO' || e.value == 'RESCISÃO' || e.value == 'RESCISAO' || e.value == 'FÉRIAS' || e.value == 'FERIAS' || e.value == 'DÉCIMO TERCEIRO' || e.value == '13º SALÁRIO' || e.value == 'DECIMO TERCEIRO' || e.value == 'PRO-LABORE' || e.value == 'ADIANTAMENTO BENEFICIO') {
            document.getElementById("load").style.display = null;
            var html = '<input type="hidden" id="id_conta" name="id_conta" value="-1"/> <label>Funcionário</label><select onchange="get_conta()" name="fornecedor" id="fornecedor" class="col-md-3" required><option></option>';
            $.getJSON('<?= base_url() ?>index.php/contas/get_funcionarios', function(data) {
                $.each(data, function(index, json) {
                    html += '<option data-id="' + json.id + '">' + json.nome + '</option>'
                });
                html += "</select><span id='conta_fornecedor'></span>"
                $('#fornecedor_span').html(html);
                $('#fornecedor').select2();
                document.getElementById("load").style.display = "none";
            });
        } else {
            var html = ['<input type="hidden" id="id_conta" name="id_conta" value="-1"/><label>Fornecedor</label>',
                '<input type="text" name="fornecedor" class="col-md-3"',
                'data-provide="typeahead" data-source=\'<?php echo $fornecedores ?>\'',
                'data-items="6"',
                'placeholder="Fornecedor"',
                'autocomplete="off" value="<?php echo $this->input->post('fornecedor') ?>" />'
            ];
            $('#fornecedor_span').html(html.join(''));
        }
    }

    function get_conta() {
        document.getElementById("load").style.display = null;
        $.getJSON('<?= base_url() ?>index.php/contas/get_conta_funcionario/' + $('#fornecedor option:selected').attr('data-id'), function(data) {
            if (data.conta) {
                $('#conta_fornecedor').html('<label>Conta</label><input type="text" value="CPF: ' + data.cpf + ', Agência: ' + data.agencia + ', conta: ' + data.conta + '" class="col-md-5" disabled="disabled">');
                $('#id_conta')[0].value = data.id;
            } else {
                $('#conta_fornecedor').html('<label>Conta</label><input type="text" value="Conta não encontrada, verifique o cadastro" class="col-md-5" disabled="disabled">');
                $('#id_conta')[0].value = "-1";
            }
            document.getElementById("load").style.display = 'none';

        });
    }
</script>

<script>
    function estornar(id) {
        $('#titulo_id_conta')[0].innerHTML = "#" + id.toString();
        $('#input_id_conta_estornar')[0].value = id;
        $('#myModalEstornar').modal();
    }

    function request_estornar() {
        if (!$('#input_senha_estornar')[0].value) {
            alert("Insira a senha!");
            return;
        }
        jQuery.ajax({
            type: "POST",
            url: "<?= base_url() ?>index.php/contas/estornar",
            data: $('#form_estornar').serialize(),
            success: function(data) {
                if (data == "4") {
                    alert('Senha incorreta!');
                } else if (data == "3") {
                    alert('Usuário sem permissão!');
                } else {
                    table.ajax.reload(null, false);
                    alert('Pagamento estornado com sucesso!');
                }
                $('#myModalEstornar').modal('hide');
            }
        });
    }
</script>