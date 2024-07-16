<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Contas a Pagar Norio Momoi", site_url('Homes'), "Departamentos", "Financeiro > Contas > Norio Momoi");
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <?php if ($this->auth->is_allowed_block('valores')) { ?>
        <div class="col-md-12">
            <div class="card" style="margin-bottom: 15px; width: 100%;">
                <div class="row" style="width: 100%">
                    <div style="display: flex; width:100%; justify-content: space-between; flex-wrap: wrap;">
                        <h4>Informações Gerais:</h4>
                        <div style="display: flex; align-items:center; justify-content:center;">
                            <legend style="font-size: 15px; color: #737373; margin-bottom: 0px;">Período: <span>01/<?= date('m/Y'); ?> à <?= date('d/m/Y') ?></span></legend>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: center; flex-wrap: wrap; margin-bottom: 15px;">
                        <div class="card card-metricas-contas" id="card-orange">
                            <strong style="display: inline-block; text-align: center;">Caixa: </strong>
                            <strong class="number-metrica-contas">R$ <?= number_format($estatisticas->caixa_consolidado, 2, ',', '.') ?></strong>
                        </div>

                        <div class="card card-metricas-contas" id="card-orange">
                            <strong style="display: inline-block; text-align: center;">A pagar:</strong>
                            <strong class="number-metrica-contas">R$ <?= number_format($estatisticas->pagar, 2, ',', '.') ?></strong>
                        </div>

                        <div class="card card-metricas-contas" id="card-green">
                            <strong style="display: inline-block; text-align: center;">Pagos: </strong>
                            <strong class="number-metrica-contas">R$ <?= number_format($estatisticas->pago, 2, ',', '.') ?></strong>
                        </div>

                        <div class="card card-metricas-contas" id="card-blue">
                            <strong style="display: inline-block; text-align: center;">Entradas:</strong>
                            <strong class="number-metrica-contas">R$ <?= number_format($estatisticas->caixa, 2, ',', '.') ?></strong>
                        </div>

                        <div id="card-red" class="card card-metricas-contas <?= $estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo' ?>">
                            <strong style="display: inline-block; text-align: center;">Saldo:</strong>
                            <strong class="number-metrica-contas">R$ <?= number_format($estatisticas->saldo, 2, ',', '.') ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-3">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px; width: 100%;">

            <form id="formBusca" style="width: 100%; margin-bottom: 15px;">

                <div class="form-group filtro">

                    <h4 style="margin-bottom: 0px !important;">Ações:</h4>

                    <div class="button-container">
                        <!--<a href="#myModal" data-toggle="modal" class="btn-design btn btn-primary"><i class="icon-plus icon-white"></i> Adicionar Conta</a>-->
                        <a class="btn btn-primary" style='width:100%; word-break: break-word; text-wrap: wrap;' data-toggle="modal" href="#cad-entrada"><i class="fa fa-plus" aria-hidden="true"></i></i> Add Entrada</a>
                        <a class="btn btn-primary" style='width:100%; margin-top: 15px; word-break: break-word; text-wrap: wrap;' data-toggle="modal" data-target="#view-entrada"><i class="fa fa-reorder"></i> Entradas</a>
                        <a href="#cadCateg" style='width:100%; margin-top: 15px; word-break: break-word; text-wrap: wrap;' data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></i> Add Categoria</a>
                        <a class="btn btn-primary" style='width:100%; margin-top: 15px; word-break: break-word; text-wrap: wrap;' href="<?= base_url(); ?>index.php/contas/remessas_norio"><i class="fa fa-gears"></i> Remessas</a>
                        <a class="btn btn-primary" style='width:100%; margin-top: 15px; word-break: break-word; text-wrap: wrap;' href="<?= base_url(); ?>index.php/contas/remessas_norio_beta"><i class="fa fa-gears"></i> Remessas - Beta</a>
                    </div>

                    <!-- <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

                    <div class="input-container" style="display: flex; flex-direction:column; align-items: start; justify-content: flex-start;">
                        <label for="filtrar-atributos">Fornecedor, Id ou Responsável</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="Fornecedor, Id ou Responsável" id="filtrar-atributos" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style="width:100%" id="BtnPesquisar" type="button"><i class="fa fa-search"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div> -->
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card">Contas Norio Momoi: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                <div class="registrosDiv">
                    <select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value=10 selected>10</option>
                        <option value=25>25</option>
                        <option value=50>50</option>
                        <option value=100>100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <input class="form-control inputBusca" type="text" id="search-input" placeholder="Pesquisar" style="margin-bottom: 10px;">
            </div>
            <div id="emptyMessageCadastroClientes" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageTecnologias" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>

                <div class="wrapperContatos">
                    <div id="tableContatos" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/contas', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/remessas/pneushow', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    var BaseURL = '<?= base_url('') ?>';
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url('media') ?>/js/jquery-mask.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets') ?>/plugins/priceformat/jquery.price_format.1.8.min.js"></script>


<style>
    .custom-input {
        border-radius: 5px;
        padding: 5px;
        border-left: 3px solid #03A9F4;
        padding-left: 10px;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
        margin-top: 3rem !important;
    }

    .bord {
        border-left: 3px solid #03A9F4;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
    }

    .card {
        display: flex;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
    }
</style>


<style>
    .modal-content {
        box-shadow: none !important;
    }

    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: -130px;
        background: rgba(70, 20, 15, 0.3);
        z-index: 2;
        background-image: url('<?= base_url() ?>media/img/loading2.gif');
        background-repeat: no-repeat;
        background-position: center center;
        background-size: 100px;
    }

    html {
        scroll-behavior: smooth
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
        display: flex;
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
        margin-bottom: 15px;
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
        font-size: 15px;
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

<div class="modal hide" id="myModal">
    <div class="modal-header">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Adicionar Conta</h3>
    </div>
    <form method="post" action='<?php echo site_url('contas/addNorio') ?>' name="" id="form">
        <div class="modal-body">
            <div id="load" style="display:none;" class="overlay"></div>
            <label>Categoria</label>
            <select required class="form-control" onchange="verifica_categoria(this)" id="categoriaNova" name="categoria">
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
    <div class="modal-dialog modal-sm" role="document" style="width: 610px">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3 style="color: #1C69AD !important;">Editar Conta</h3>
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
                            <label>Fornecedor:</label>
                            <input type="text" class="col-md-12 form-control" name="fornecedor" id="fornecedor" placeholder="Fornecedor" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Categoria:</label>
                            <input type="text" class="col-md-12 form-control" name="categoria" id="categoria" placeholder="Categoria" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Valor:</label>
                            <input type="text" class="col-md-12 form-control" name="valor" id="valor" placeholder="R$ 0.000,00" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Vencimento:</label>
                            <input type="text" class="col-md-12 form-control" name="dt_vencimento" id="dt_vencimento" placeholder="Vencimento" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Descriçao:</label>
                            <input type="text" class="col-md-12 form-control" name="descricao-form" id="descricao-form" placeholder="Descriçao" required>
                        </div>
                        <br>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-success" id="editar" style="margin-left: auto;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div id="myModal_update" style="overflow-y: auto;" class="modal fade" role="dialog">
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
</div> -->

<div id="myModal_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="formPagamento" method="post" action=''>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Pagar</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle" style="padding: 10px 0px;">Dados do Pagamento</h4>
                        <input type="hidden" name="id" id="idid" value="">
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="senha_pagamento" class="control-label">Senha:</label>
                                <input type="password" class="form-control" name="senha_pagamento" id="senha_pagamento" value="" required>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="valor_pago" class="control-label">Valor Pago:</label>
                                <input type="text" class="form-control money2" placeholder="0,00" maxlength="13" name="valor_pago" id="money" value="" required>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="data_pagamento" class="control-label">Data de Pagamento:</label>
                                <input type="date" class="form-control" name="data_pagamento" id="data" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id="pagar">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalEstornar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="close0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 style="color: #1C69AD !important;">Estornar Pagamento <span id="titulo_id_conta"></span></h3>
            </div>
            <div class="modal-body">
                <form id="form_estornar">
                    <input type="hidden" id="input_id_conta_estornar" name="id_conta" required>
                    <label>Senha *</label>
                    <input type="password" class="form-control" id="input_senha_estornar" name="senha" required value="">
                    <label>Observações</label>
                    <input type="text" class="form-control" id="input_observacoes_estornar" name="observacoes" required>
                </form>
            </div>
            <div class="modal-footer" style="display:flex;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" style="margin-left: auto;" onclick="request_estornar()">Estornar</button>
            </div>
        </div>
    </div>
</div>

<div id="myModal_cancel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" action='<?php echo site_url('contas/update/') ?>' id='form_cancel'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleCancelamento">Cancelamento</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row' style="margin-left: 0px;">
                            <label>Tem certeza que deseja cancelar?</label>
                            <input type="hidden" name="id" id="cancel-id">
                        </div>
                        <hr style="margin-top: 0px; margin-bottom: 0px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-success" id='cancelar'>Sim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_digitalizar" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document" style="width: 610px">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 id="myModalLabel" style="color: #1C69AD !important;">Digitalizar Conta</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div id="cad-entrada" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formCadEntrada'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Adicionar Entrada</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle">Dados da Entrada</h4>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="dataNorio">Data da Entrada: <span class="text-danger">*</span></label>
                                <input type="date" name="data" class="form-control" id="dataNorio" required />
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorNorio">Valor: <span class="text-danger">*</span></label>
                                <input type="text" name="valor" class="form-control" placeholder="0,00" id="valorNorio" maxlength="16" required />
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="placaBlacklist">Descrição: <span class="text-danger">*</span></label>
                                <textarea maxlength="255" cols="3" class="form-control" style="resize: vertical;" name="descricao" id="descricaoNorio" placeholder="Informe uma descrição..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='add-entrada'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="view-entrada" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Entradas do Mês</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="wrapperEntradas">
                        <div id="tableEntradas" class="ag-theme-alpine my-grid-entradas" style="height: 519px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="view-entrada" style="overflow-y: auto;" class="modal fade" role="dialog">
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
</div> -->

<!-- <div id="cadCateg" style="overflow-y: auto;" class="modal fade" role="dialog">
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
</div> -->

<div id="cadCateg" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formCadCategoria'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Nova Categoria</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="categ">Categoria: <span class="text-danger">*</span></label>
                                <input type="text" name="categoria" maxlength="255" class="form-control" id="categ" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='add-categoria'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#cadCateg').on('hidden.bs.modal', function() {
            $('#categ').val('');
            $('#add-categoria').html('Salvar').attr('disabled', false);
        });

        $('#formCadCategoria').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= site_url('/contas/add_categoria_contas') ?>',
                type: 'POST',
                data: {
                    'categoria': $('#categ').val()
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#add-categoria').html('<i class="fa fa-spinner fa-spin"></i> Salvando').attr('disabled', true);
                },
                success: function(data) {
                    if (data) {
                        showAlert('success', "Categoria cadastrada com sucesso!");
                        $('#cadCateg').modal('hide');
                    } else {
                        showAlert('warning', "Já existe categoria cadastrada com esse nome!");
                    }

                    $('#add-categoria').html('Salvar').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showAlert('error', "Não foi possível cadastrar a categoria. Tente novamente!");
                    $('#add-categoria').html('Salvar').attr('disabled', false);
                }
            });
        });
    });

    function ShowLoadingScreen() {
        $('#loading').show()
    }

    function HideLoadingScreen() {
        $('#loading').hide()
    }

    $(document).ready(function() {
        $('#valorNorio').maskMoney({
            symbol: 'R$ ',
            showSymbol: true,
            thousands: '.',
            decimal: ',',
            symbolStay: true
        });

        $('#money').maskMoney({
            symbol: 'R$ ',
            showSymbol: true,
            thousands: '.',
            decimal: ',',
            symbolStay: true
        });
    });

    var table;
    $(document).ready(function() {
        $('#formPagamento').submit(function() {
            var url = "<?php echo site_url('contas/update/') ?>";
            var dados = $(this).serialize();

            $.ajax({
                type: "POST",
                url: url,
                data: dados,
                beforeSend: function() {
                    $('#pagar').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
                },
                success: function(data) {
                    if (data && data == 4) {
                        showAlert('warning', 'Senha não autorizada para realizar pagamento!');
                    } else if (data && data == 3) {
                        showAlert('warning', 'Usuário não possui permissão para realizar pagamento!');
                    } else if (data) {
                        atualizarDadosAgGrid();
                        showAlert('success', 'Pagamento efetuado com sucesso!');
                        $('#myModal_update').modal('hide');
                    } else {
                        showAlert('error', 'Não foi possível realizar o pagamento!');
                    }
                    $('#pagar').html('Salvar').attr('disabled', false);
                },
                error: function() {
                    showAlert('error', 'Não foi possível realizar o pagamento!');
                    $('#pagar').html('Salvar').attr('disabled', false);
                }
            });

            return false;
        });
    });

    $('#myModal_update').on('hidden.bs.modal', function() {
        $('#idid').val('');
        $('#senha_pagamento').val('');
        $('#money').val('');
        $('#data').val('');
        $('#pagar').html('Salvar').attr('disabled', false);
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
                    showAlert('success', 'Cancelado com sucesso.')
                } else {
                    $(".alert").find('span').html('').html('Não foi possível cancelar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
                $("#myModal_cancel").modal("hide");
                $('#cancelar').html(html);
            });
        });

        // $('#editar').on('click', function(e) {
        //     e.preventDefault();
        //     $('#editar').attr('disabled', 'disabled').text('Salvando...');
        //     var form = $(this).closest('form').serialize();
        //     var url = $('#form_editar').attr('action');
        //     if ($('#fornecedor').val() != '') {
        //         $.post(url, form, async function(data) {
        //             if (data) {
        //                 showAlert("success", "Edição realizada com sucesso!")
        //                 table.ajax.reload(null, false);
        //                 $('#editar').removeAttr('disabled').text('Salvar');
        //                 $("#myModal_editar").modal("hide");
        //             } else {
        //                 showAlert("error", "Falha ao realizar edição.")
        //                 $('#editar').removeAttr('disabled').text('Salvar');
        //                 $(".fornec").find('span').html('').html('Não foi possível editar o fornecedor. Tente novamente.');
        //                 $(".fornec").addClass('alert-danger').show();
        //             }
        //         });
        //     } else {
        //         $('#editar').removeAttr('disabled').text('Salvar');
        //         $(".fornec").find('span').html('').html('Preencha o campo fornecedor corretamente.');
        //         $(".fornec").addClass('alert-danger').show();
        //     }

        // });

        $('.date').mask('99/99/9999', {
            clearIfNotMatch: true
        });

        $('#myModal_digitalizar').on('hidden', function() {
            $(this).data('modal', null);
        });

    });

    function update(param) {
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        var valor = $(param).data('valor');
        var now = new Date();
        $('#idid').val(id);
        $('#money').val(valor);
        $('#money').focus();
        $.ajax({
            type: "post",
            data: {
                id: id
            },
            url: controller,
            dataType: "json",
            beforeSend: function() {
                ShowLoadingScreen();
            },
            success: function(data) {
                if (data.updated != 1 || data.status == 0) {
                    $('#idid').val(data.id);
                    $('#money').val(data.valor).trigger('input');
                    $('#money').focus();
                    $('#data').val(now.toISOString().split('T')[0]);
                    $("#myModal_update").modal('show');
                    HideLoadingScreen();
                } else {
                    HideLoadingScreen();
                    showAlert('warning', 'Esta conta não pode ser paga! Por favor, verifique o status.')
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
        $('#descricao-form').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));
    }

    async function atualizarDadosAgGrid() {
        AgGrid.gridOptions.api.setRowData([]);
        $("#search-input").val("");
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        await $.ajax({
            url: "<?= site_url('contas/contas_ajax_NS_nova/3') ?>",
            type: 'GET',
            success: function(response) {
                result = JSON.parse(response);
                AgGrid.gridOptions.api.setRowData(result);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao tentar atualizar dados da tabela.');
            }
        });
    }

    $('#cad-entrada').on('hidden.bs.modal', function() {
        $('#dataNorio').val('');
        $('#valorNorio').val('');
        $('#descricaoNorio').val('');
        $('#add-entrada').html('Salvar').attr('disabled', false);
    });

    $('#formCadEntrada').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_entrada_norio',
            type: 'POST',
            data: {
                'data': $('#dataNorio').val(),
                'valor': $('#valorNorio').val(),
                'descricao': $('#descricaoNorio').val()
            },
            beforeSend: function() {
                $('#add-entrada').html('<i class="fa fa-spinner fa-spin"></i> Salvando').attr('disabled', true);
            },
            success: function(data) {
                if (data) {
                    showAlert('success', "Entrada cadastrada com sucesso!");
                    $('#cad-entrada').modal('hide');
                    atualizarDadosAgGrid();
                } else {
                    showAlert('warning', "Não foi possível cadastrar entrada. Tente novamente!");
                }

                $('#add-entrada').html('Salvar').attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showAlert('error', "Não foi possível cadastrar entrada. Tente novamente!");
                $('#add-entrada').html('Salvar').attr('disabled', false);
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
    var AgGridEntradas;

    function formatDate(date) {
        dateCalendar = date.split("-");
        return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0];
    }

    function abrirDropdown(dropdownId, buttonId, tableId) {
        var dropdown = $('#' + dropdownId);
        var posDropdown = dropdown.height() + 10;

        var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
        var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
        var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
        var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

        if (posDropdown > (posBordaTabela - posButton)) {
            if (posDropdown < (posButtonTop - posBordaTabelaTop)) {
                dropdown.css('top', `-${posDropdown - 50}px`);
            } else {
                let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
                dropdown.css('top', `-${(posDropdown - 60) - (diferenca) }px`);
            }
        }
    }

    async function buscarDadosAgGridEntradas() {
        await $.ajax({
            url: "<?= site_url('contas/lista_entradas_norio_ajax') ?>",
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                AgGridEntradas.gridOptions.api.showLoadingOverlay();
            },
            success: function(response) {
                AgGridEntradas.gridOptions.api.setRowData(response);
            },
            error: function(xhr, status, error) {
                AgGridEntradas.gridOptions.api.setRowData([]);
                showAlert('error', 'Erro ao listar entradas. Tente novamente!');
            }
        });
    }

    $('#view-entrada').on('show.bs.modal', function() {
        buscarDadosAgGridEntradas();
    });

    function removerEntrada(id) {
        Swal.fire({
            title: "Atenção!",
            text: "Deseja realmente remover o item?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('contas/remove_entrada_norio') ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_entrada: id
                    },
                    beforeSend: function() {
                        ShowLoadingScreen();
                    },
                    success: function(response) {
                        showAlert('success', 'Item removido com sucesso!');
                        HideLoadingScreen();
                        buscarDadosAgGridEntradas();
                    },
                    error: function(xhr, status, error) {
                        HideLoadingScreen();
                        showAlert('error', 'Erro tentar remover . Tente novamente!');
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
            var dropdownId = $(this).find('.dropdown-menu').attr('id');
            var buttonId = $(this).find('.btn-dropdown').attr('id');
            var tableId = $(this).attr("data-tableId");
            abrirDropdown(dropdownId, buttonId, tableId);
        });

        const gridOptions = {
            columnDefs: [{
                    headerName: "Data",
                    field: "data",
                    width: 150,
                    cellRenderer: function(params) {
                        if (params.value) {
                            return formatDate(params.value);
                        } else {
                            return '';
                        }
                    }
                },
                {
                    headerName: "Valor",
                    field: "valor",
                    cellRenderer: function(params) {
                        if (params.value) {
                            return parseFloat(params.value).toLocaleString('pt-BR', {
                                style: 'currency',
                                currency: 'BRL'
                            });
                        } else {
                            return '';
                        }
                    }
                },
                {
                    headerName: "Descrição",
                    field: "descricao",
                    flex: 1,
                    minWidth: 200,
                },
                {
                    headerName: 'Ações',
                    width: 80,
                    pinned: 'right',
                    cellClass: "actions-button-cell",
                    suppressMenu: true,
                    sortable: false,
                    filter: false,
                    resizable: false,
                    cellRenderer: function(options) {
                        let data = options.data;

                        let tableId = "tableEntradas";
                        let dropdownId = "dropdown-menu-entradas" + data.id_entrada;
                        let buttonId = "dropdownMenuButtonEntradas_" + data.id_entrada;
                        let i = options.rowIndex;

                        return `
                            <div class="dropdown dropdown-table" data-tableId=${tableId}>
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id_entrada}" nome="${data.placa_lida}" id="${data.id_entrada}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="javascript:removerEntrada(${data.id_entrada})" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Remover</a>
                                    </div>
                                </div>
                            </div>`;
                    },
                },
            ],
            rowData: [],
            pagination: true,
            defaultColDef: {
                resizable: true,
                sortable: true
            },
            overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>',
            sideBar: {
                toolPanels: [{
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
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
                }, ],
                defaultToolPanel: false,
            },
            paginationPageSize: 10,
            localeText: AG_GRID_LOCALE_PT_BR,
        };
        var gridDiv = document.querySelector("#tableEntradas");
        AgGridEntradas = new agGrid.Grid(gridDiv, gridOptions);
    });

    function estornar(id) {
        $('#titulo_id_conta')[0].innerHTML = "#" + id.toString();
        $('#input_id_conta_estornar')[0].value = id;
        $('#myModalEstornar').modal('show');
    }

    function request_estornar() {
        if (!$('#input_senha_estornar')[0].value) {
            showAlert("warning","Insira a senha!");
            return;
        }
        jQuery.ajax({
            type: "POST",
            url: "<?= base_url() ?>index.php/contas/estornar",
            data: $('#form_estornar').serialize(),
            success: function(data) {
                if (data == "4") {
                    showAlert("warning",'Senha incorreta!');
                } else if (data == "3") {
                    showAlert("warning",'Usuário sem permissão!');
                } else {
                    table.ajax.reload(null, false);
                    showAlert("success", 'Pagamento estornado com sucesso!');
                }
                $('#myModalEstornar').modal('hide');
            }
        });
    }

    var AgGrid;
    $(document).ready(async function() {
        $(".btn-expandir").on("click", function(e) {
            e.preventDefault();
            menuAberto = !menuAberto;

            if (menuAberto) {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-show.svg`
                );
                $("#filtroBusca").hide();
                $(".menu-interno").hide();
                $("#conteudo").removeClass("col-md-9");
                $("#conteudo").addClass("col-md-12");
            } else {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-hide.svg`
                );
                $("#filtroBusca").show();
                $(".menu-interno").show();
                $("#conteudo").css("margin-left", "0px");
                $("#conteudo").removeClass("col-md-12");
                $("#conteudo").addClass("col-md-9");
            }
        });

        ///// adicao de novas funcoes para ag grid
        var result = [];
        async function buscarDadosAgGrid() {
            await $.ajax({
                url: "<?= site_url('contas/contas_ajax_NS_nova/3') ?>",
                type: 'GET',
                success: function(response) {
                    result = JSON.parse(response);
                    $('#search-input').val('');
                    updateData(result)
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao fazer a requisição:', error);
                    $('#result').text('Erro ao fazer a requisição.');
                    $('#search-input').val('');
                }
            });
        }

        const gridOptions = {
            columnDefs: [{
                    headerName: "ID",
                    field: "id"
                },
                {
                    headerName: "Fornecedor",
                    field: "fornecedor",
                    valueFormatter: function(params) {
                        return params.value.toUpperCase();
                    }
                },
                {
                    headerName: "Descrição",
                    field: "descricao"
                },
                {
                    headerName: "Categoria",
                    field: "categoria"
                },
                {
                    headerName: "Responsável",
                    field: "responsavel",
                    valueFormatter: function(params) {
                        return params.value.toUpperCase();
                    }
                },
                {
                    headerName: "Data de Lançamento",
                    field: "dh_lancamento"
                },
                {
                    headerName: "Data de Vencimento",
                    field: "data_vencimento"
                },
                {
                    headerName: "Valor",
                    field: "valor_formatado"
                },
                {
                    headerName: "Status da Fatura",
                    field: "status_fatura",
                    cellRenderer: function(options) {
                        return options.data.status_fatura;
                    }
                },
                {
                    headerName: "Pagamento",
                    field: "pagamento"
                },
                {
                    headerName: 'Ações',
                    width: 80,
                    pinned: 'right',
                    cellClass: "actions-button-cell",
                    suppressMenu: true,
                    sortable: false,
                    filter: false,
                    resizable: false,
                    cellRenderer: function(options) {
                        let data = options.data;

                        let tableId = "tableContatos";
                        let dropdownId = "dropdown-menu" + data.id;
                        let buttonId = "dropdownMenuButton_" + data.id;
                        let i = options.rowIndex;

                        if (i > 9) {
                            i = 9;
                        }

                        return `
                            <div class="dropdown dropdown-table" data-tableId=${tableId}>
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        ${data.botaoEditar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoServicoPrestado}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoDigitalizar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoExcluir}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaPagar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoComprov}
                                    </div>
                                </div>
                            </div>`;
                    },
                },
            ],
            rowData: [],
            pagination: true,
            defaultColDef: {
                resizable: true,
            },
            sideBar: {
                toolPanels: [{
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
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
                }, ],
                defaultToolPanel: false,
            },
            paginationPageSize: $("#select-quantidade-por-contatos-corporativos").val(),
            localeText: AG_GRID_LOCALE_PT_BR,
        };
        var gridDiv = document.querySelector("#tableContatos");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        preencherExportacoes(gridOptions, "RelatorioContasNorioMomoi");


        $('#editar').on('click', function(e) {
            e.preventDefault();
            $('#editar').attr('disabled', 'disabled').text('Salvando...');
            var form = $(this).closest('form').serialize();
            var url = $('#form_editar').attr('action');
            if ($('#fornecedor').val() != '') {
                $.post(url, form, async function(data) {
                    if (data) {
                        showAlert("success", "Edição realizada com sucesso!")
                        table.ajax.reload(null, false);
                        $('#editar').removeAttr('disabled').text('Salvar');
                        $("#myModal_editar").modal("hide");
                        updateData([])
                        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
                        buscarDadosAgGrid()
                    } else {
                        showAlert("error", "Falha ao realizar edição.")
                        $('#editar').removeAttr('disabled').text('Salvar');
                        $(".fornec").find('span').html('').html('Não foi possível editar o fornecedor. Tente novamente.');
                        $(".fornec").addClass('alert-danger').show();
                    }
                });
            } else {
                showAlert("error", "Falha ao realizar edição.")
                $('#editar').removeAttr('disabled').text('Salvar');
                $(".fornec").find('span').html('').html('Preencha o campo fornecedor corretamente.');
                $(".fornec").addClass('alert-danger').show();
            }

        });
        function updateData(newData) {
            gridOptions.api.setRowData(newData);
        }

        var resultado = await buscarDadosAgGrid();

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
        });

        $('#search-input').off().on('input', function() {
            var searchInput = $(this).val();
            gridOptions.api.setQuickFilter(searchInput);
        });

        $("#filtrar-atributos").on("keyup", function() {
            const inputValue = $(this).val().trim().toLowerCase();

            const filteredResult = result.filter(
                (item) =>
                item.id.toLowerCase().includes(inputValue) ||
                item.fornecedor.toLowerCase().includes(inputValue) ||
                item.responsavel.toLowerCase().includes(inputValue)
            );

            updateData(filteredResult);
        });

        $("#BtnLimparFiltro").on("click", function() {
            $("#filtrar-atributos").val("");
            updateData(result);
        });

    });

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }
</script>


<style>
    .card h4 {
        padding-left: 0;
    }

    .footer-group {
        display: flex !important;
        justify-content: space-between !important;
        width: 100% !important;
        padding: 8px 18px !important;
    }
</style>

<script>
$(document).ready(function() {
    var valorInput = document.getElementById('valor');

    valorInput.addEventListener('input', function() {
        if (!this.value.includes('R$')) {
            this.value = 'R$ ' + this.value;
        }
    });

});
</script>