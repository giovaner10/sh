<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Contas a Pagar Show Tecnologia", site_url('Homes'), "Departamentos", "Financeiro > Contas > Show Tecnologia");
?>


<div id="loading">
    <div class="loader"></div>
</div>


<div class="row" style="margin: 15px 0 0 15px;">
    <?php if ($this->auth->is_allowed_block('valores')) { ?>
        <div class="col-md-12">
            <div class="card informacoes-gerais" style="margin-bottom: 15px; width: 100%;">
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
    <div id="conteudo-lateral" class="col-md-3">
        <div class="card menu-interno">
            <div class="filtro">
                <h4 style="margin-bottom: 0px !important;">Menu</h4>
                <ul>
                    <li>
                        <a class='menu-interno-link selected' id="menu-contas-show">Contas Show Tecnologia</a>
                    </li>
                    <li>
                        <a class='menu-interno-link' id="menu-os-show">OS Show Tecnologia</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <div class="filtro" style="width: 100%; margin-bottom: 30px;">
                <h4 style="margin-bottom: 0px !important;">Ações:</h4>
                <div class="button-container">
                    <a href="#cadCateg" style='width:100%; word-break: break-word; text-wrap: wrap;' data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Categoria</a>
                    <a href="<?= base_url() ?>index.php/contas/remessas" style='width:100%; word-break: break-word; text-wrap: wrap; margin-top: 15px;' class="btn btn-primary"><i class="fa fa-gears"></i> Remessas</a>
                    <a href="<?= base_url() ?>index.php/contas/remessas_beta" style='width:100%; word-break: break-word; text-wrap: wrap; margin-top: 15px;' class="btn btn-primary"><i class="fa fa-gears"></i> Remessas - Beta</a>
                </div>
            </div>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px; width: 100%;">

            <div id="formBusca" style="width: 100%;">

                <div class="form-group filtro">
                    <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

                    <div class="input-container" style="display: flex; flex-direction:column; align-items: start; justify-content: flex-start;">
                        <label for="filtrar-atributos">Fornecedor, Id ou Descrição</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="Fornecedor, Id ou Responsável" id="filtrar-atributos" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style="width:100%" id="BtnPesquisar" type="button"><i class="fa fa-search"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
    </div>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px;'>
            <h3>
                <b id="titulo-card">Contas Show Tecnologia:</b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
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
    .informacoes-gerais.card {
        display: flex;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
    }
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


<style>
    .trSerial,
    .trSerial-retirado,
    .trPlaca,
    .trCliente,
    .trOs,
    .trValorServ,
    .trValorTotal,
    .trData,
    .trServico,
    .trUser,
    .trId {
        list-style: none;
        margin: 0 auto;
    }

    .trCliente li {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dBody {
        display: inline-flex;
    }

    #btn-all,
    #btn-tec {
        width: 30px;
        height: 45px;
        border-radius: 50px;
        font-size: 22px;
        color: #fff;
        line-height: 58px;
        text-align: center;
        -webkit-box-shadow: 0px 10px 5px -7px rgba(191, 191, 191, 1);
        -moz-box-shadow: 0px 10px 5px -7px rgba(191, 191, 191, 1);
        box-shadow: 0px 10px 5px -7px rgba(191, 191, 191, 1);
    }

    #myModal_dados {
        text-align: center;
        padding: 0 !important;
        margin: 0 auto;
        width: 95%;
        left: 2%;
        font-size: 10px;
    }

    .some {
        display: none;
    }

    #myModal_dados:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
        /* Adjusts for spacing */
    }

    .modal-dialog {
        /*display: inline-block;*/
        text-align: left;
        vertical-align: middle;
    }

    .dSerial,
    .dSerial-retirado,
    .dValorServ,
    .dUser {
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 125px;
    }

    .dCliente {
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 150px;
    }

    .dId {
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 40px;
    }

    .dData,
    .dPlaca,
    .dOs,
    .dServico {
        border-left: 1px solid #e6dfdf;
        border-right: 1px solid #e6dfdf;
        padding-left: 15px;
        padding-right: 15px;
        width: 70px;
    }

    .dValorTotal {
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

    .dSerial>label,
    .dSerial-retirado>label,
    .dPlaca>label,
    .dCliente>label,
    .dOs>label,
    .dValorServ>label,
    .dData>label,
    .dServico>label,
    .dUser>label,
    .dId>label {
        font-size: 17px;
        padding-top: 10px;
        background: aliceblue;
    }

    .dTotal {
        width: 90%;
        margin: 0 auto;
    }

    .material-switch>input[type="checkbox"] {
        display: none;
    }

    .material-switch>label {
        cursor: pointer;
        height: 0px;
        position: relative;
        width: 40px;
    }

    .material-switch>label::before {
        background: rgb(0, 0, 0);
        box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        content: '';
        height: 16px;
        margin-top: -8px;
        position: absolute;
        opacity: 0.3;
        transition: all 0.4s ease-in-out;
        width: 40px;
    }

    .material-switch>label::after {
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

    .material-switch>input[type="checkbox"]:checked+label::before {
        background: #499bea;
        opacity: 0.5;
    }

    .material-switch>input[type="checkbox"]:checked+label::after {
        background: #499bea;
        left: 20px;
    }

    .labelBt {
        float: left;
        margin-top: 2px;
        padding-left: 20px;
    }
</style>
<style>
    [type="checkbox"]+label {
        padding-left: 10px;
    }

    [type="checkbox"]:checked+label:before {
        top: -1px;
        left: 0px;
        width: 35px;
    }
</style>
<style>
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


    .label-radio {
        padding: 0 !important;
    }

    .card-red {
        /*outline: 2px solid #BF0811;*/
        color: #BF0811;
        font-size: 1.6em;
        line-height: 1.3;
    }

    .ag-row {
        z-index: 0 !important;
    }

    .ag-row.ag-row-focus {
        z-index: 1 !important;
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
        align-items: center;
        justify-content: center;
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

    .card h4 {
        padding-left: 0;
    }

    #card-red:hover {
        background: #BF0811;
        color: #ffffff;
    }

    .card:hover svg {
        fill: white;
    }

    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
</style>


<!--<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">-->
<!-- <link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet"> -->
<!--<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>-->

<div class="modal hide" id="myModal">
    <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Adicionar Conta</h3>
    </div>
    <div class="modal-body">
        <form method="post" action='<?php echo site_url('contas/add') ?>' name="" id="form">

            <label>Categoria</label>
            <select required class="form-control" onchange="verifica_categoria(this,'')" id="categoriaNova" name="categoria">
                <?php foreach ($categorias as $categoria) : ?>
                    <option><?= $categoria; ?></option>
                <?php endforeach; ?>
            </select>
            <span id="fornecedor_span">

            </span>
            <label>Descrição</label>
            <textarea type="text" class="col-md-3" name="descricao" id="descricao" placeholder="Descrição" required></textarea>

            <label>Valor</label>
            <input type="text" class="col-md-3 money2" name="valor" id="add_conta_valor" placeholder="0,00" required>

            <label>Data Vencimento</label>
            <input type="text" class="col-md-3 date" name="data_vencimento" id="add_conta_vencimento" placeholder="__/__/___" required>



    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a>
        <button type="submit" class="btn btn-primary" id="add">Adicionar</button>
    </div>
    </form>
</div>

<div id="myModalEstornar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalEstornarLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="form_estornar">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" style="justify-content: left !important;">Estornar Pagamento <span style="margin-left: 10px;" id="titulo_id_conta"></span></h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <input type="hidden" id="input_id_conta_estornar" name="id_conta" required>
                                <label for="input_senha_estornar">Senha: <span class="text-danger">*</span></label>
                                <input class="form-control" type="password" class="col-md-3" id="input_senha_estornar" name="senha" required>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="input_observacoes_estornar">Observações: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" class="col-md-3" id="input_observacoes_estornar" name="observacoes" required>
                            </div>
                        </div>
                        <hr style="margin: 0;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" onclick="request_estornar()" id="btnEstornar">Estornar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="myModal_editar">
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

                    <div class="form-group divCategoria col-md-12">
                        <label for="sel1">Categoria:</label>
                        <select class="form-control" id="sel1" name="categoria" onclick="verifica_categoria(this,'1')">
                            <?php foreach ($categorias as $categoria) : ?>
                                <option><?= $categoria ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <input type="hidden" name="id" id="fornecedor_id">

                    <div class="col-md-12">
                        <span id="fornecedor_span1">
                            <label>Fornecedor</label>
                            <input type="text" class="form-control" name="fornecedor" id="fornecedor" placeholder="Fornecedor" readonly="readonly" required>
                        </span>
                    </div>
                    <div class="col-md-12">
                        <label>Valor</label>
                        <input type="text" class="form-control money2" name="valor" id="add_conta_valor1" placeholder="Valor" required>
                    </div>
                    <div class="col-md-12">
                        <label>Vencimento</label>
                        <input type="text" class="form-control" name="" id="dt_vencimento" placeholder="Vencimento" required>
                    </div>
                    <div class="col-md-12">
                        <label>Descriçao</label>
                        <input type="text" class="form-control" name="descricao-form" id="descricao-form" placeholder="Descriçao" required>
                    </div>

                    <div class="row"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button class="btn btn-primary" id="editar">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<div id="myModal_editar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='form_editar'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Editar Conta</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-12 input-containe divCategoria form-group">
                                <label for="sel1">Categoria: <span class="text-danger">*</span></label>
                                <select required class="form-control" id="sel1" name="categoria" onchange="verifica_categoria(this,'1')">
                                    <?php foreach ($categorias as $categoria) : ?>
                                        <option><?= $categoria ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label id="labelFornecedor1" for="fornecedor1">Fornecedor: <span class="text-danger">*</span></label><i id="loadingFornecedor1" class="fa fa-spinner fa-spin" style="display: none;"></i>
                                <input type="hidden" name="id" id="fornecedor_id">
                                <span id="fornecedor_span1">
                                    <input type="hidden" name="fornecedor" id="fornecedorNome">
                                    <input type="text" class="form-control" id="fornecedor1" placeholder="Fornecedor" readonly="readonly" required>
                                </span>
                            </div>
                            <span id='conta_fornecedor1'>

                            </span>
                            <div class="col-md-12 input-container form-group">
                                <label for="add_conta_valor1">Valor: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control money2" name="valor" id="add_conta_valor1" placeholder="0,00" required>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="dt_vencimento">Vencimento: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="dt_vencimento" id="dt_vencimento" placeholder="Vencimento" required>
                            </div>
                            
                            <div class="col-md-12 input-container form-group">
                                <label for="descricao-form">Descrição: <span class="text-danger">*</span></label>
                                <textarea style="resize: vertical;" cols="3" type="text" class="form-control" name="descricao-form" maxlength="500" id="descricao-form" placeholder="Descrição" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='editar'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="myModal_update" style="overflow-y: auto;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Pagamento</h3>
            </div>
            <form method="post" id="formPagamento">
                <div class="modal-body">
                    <input type="hidden" name="id" id="idid" value="">
                    <div class="col-md-12">
                        <label>Senha</label>
                        <input type="password" class="form-control" name="senha_pagamento" id="senha_pagamento" value="" required>
                    </div>

                    <div class="col-md-12">
                        <label>Valor Pago</label>
                        <input type="text" class="form-control money2" name="valor_pagamento" id="valor_pagamento" placeholder="0,00" required>
                    </div>

                    <div class="col-md-12">
                        <label>Data Pagamento</label>
                        <input type="text" class="form-control date" name="data_pagamento" id="data" placeholder="__/__/___" required>
                    </div>
                </div>
                <div class="row"></div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-primary">Pagar</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<div id="myModal_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form method="post" id="formPagamento">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Pagar Conta</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <input type="hidden" name="id" id="idid" value="">
                            <div class="col-md-12 input-container form-group">
                                <label for="senha_pagamento">Senha: <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="senha_pagamento" id="senha_pagamento" placeholder="Digite a senha..." value="" required>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="valor_pago">Valor Pago: <span class="text-danger">*</span></label>
                                <input type="text" maxlength="13" class="form-control money1" name="valor_pago" id="valor_pago" placeholder="R$ 0,00" required>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="data_pagamento">Data Pagamento: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="data_pagamento" id="data" required>
                            </div>
                        </div>
                        <hr style="margin: 5px 0;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id="btnPagar">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div class="modal hide" id="myModal_cancel">

    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Cancelamento</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/update/') ?>' id="form_cancel">
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
</div> -->

<!-- <div class="modal" id="myModal_cancel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Cancelamento</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/update/') ?>' id="form_cancel">
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
</div> -->

<div id="myModal_cancel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" action='<?php echo site_url('contas/update/') ?>' id="form_cancel">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleTecnologias">Cancelamento</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <label for="cancel-id">Tem certeza que deseja cancelar?</label>
                            <input type="hidden" name="id" id="cancel-id">
                        </div>

                        <hr style="margin: 0;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-success" id="cancelar">Sim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="myModal_digitalizar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Digitalizar Conta</h3>
            </div>
            <div class="modal-body">
                <div>
                    <div class="col-md-12" style="margin-bottom: 20px;">
                        <form method="post" name="formcontato" id="digitalizar_conta" enctype="multipart/form-data" action="<?php echo site_url('contas/digitalizacao/') ?>">
                        
                            <input type="hidden" name="idConta" value="" id="idContaDig" />

                            <div class="input-container form-group">
                                <label for="add_conta_valor1">Descrição: <span class="text-danger">*</span></label>
                                <input type="text" id="descricaoArquivo" name="descricao" placeholder="Informe a descrição..." class="input form-control" required/>
                                <label for="comprovante" style="cursor: pointer;">
                                    <input type="checkbox" value="1" id="comprovante" name="comprovante" />&nbsp;Comprovante de pagamento
                                </label>
                            </div>

                            <div style="display: flex; align-items: flex-end;">
                                <div style="flex: 1; margin-right: 15px;">
                                    <label class="control-label" id="labelForFilesForm" style="word-break: break-word;" for="filesForm">Arquivo (PDF):</label>
                                    <input class="form-control" id="filesForm" type="file" name="arquivo" accept="application/pdf"/>
                                </div>
                                <div cstyle="display: flex;">
                                    <button type="button" class="btn btn-primary" title="Adicionar" id="AddArquivoForm"><i class="fa fa-plus"></i> Adicionar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <div style="position: relative;">
                            <div class="wrapperDigitalizacao">
                                <div id="tableDigitalizacao" class="ag-theme-alpine my-grid-digitalizacao" style="height: 360px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal_dados">
    <style>
        .modal-content {
            box-shadow: none !important;
        }

        @media print {
            html {
                position: relative;
                min-height: 100%;
            }

            html,
            body {
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


            a:link,
            a:visited {
                color: #333;
                text-decoration: underline;
            }

            a[href]:after {
                content: " (" attr(href) ")";
            }

            .trSerial,
            .trPlaca,
            .trCliente,
            .trOs,
            .trValorServ,
            .trValorTotal,
            .trData,
            .trServico,
            .trUser,
            .trId,
            .trSerial-retirado {
                list-style: none;
                margin: 0 auto;
                text-align: center;
                margin-right: 50px !important;
            }

            .trCliente li,
            .trUser li {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                /*margin-right: 30px;*/
            }

            .dBody {
                display: inline-flex;
            }

            .modal {
                text-align: center;
                padding: 0 !important;
                margin: 0 auto;
                width: 95%;
                left: 2%;
            }

            .modal:before {
                content: '';
                display: inline-block;
                height: 100%;
                vertical-align: middle;
                margin-right: -4px;
                /* Adjusts for spacing */
            }

            .modal-dialog {
                display: inline-block;
                text-align: left;
                vertical-align: middle;
            }

            .dSerial,
            .dSerial-retirado,
            .dValorServ,
            .dUser,
            .dPlaca {
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                padding-left: 15px;
                padding-right: 15px;
                width: 175px;
                text-align: center;
            }

            .dCliente {
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                /*padding-left: 15px;*/
                /*padding-right: 15px;*/
                width: 200px;
                text-align: center;
            }

            .dId {
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                padding-left: 15px;
                padding-right: 15px;
                width: 40px;
                text-align: center;
            }

            .dData,
            .dOs,
            .dServico {
                border-left: 1px solid #e6dfdf;
                border-right: 1px solid #e6dfdf;
                padding-left: 15px;
                padding-right: 15px;
                width: 70px;
                text-align: center;
            }

            .dValorTotal {
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

            .dPlaca>label,
            .dSerial-retirado>label,
            .dCliente>label,
            .dOs>label,
            .dValorServ>label,
            .dData>label,
            .dServico>label,
            .dUser>label,
            .dId>label {
                font-size: 17px;
                padding-top: 10px;
                background: aliceblue;
                text-align: center;
            }

            .dTotal {
                width: 100%;
                margin: 0 auto;
            }

            #btn-imprimir {
                display: none;
            }

            button.close {
                display: none;
            }

            #modal-dados {
                font-size: 10px;
            }
        }
    </style>

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <button class="btn btn-info pull-left" id="btn-imprimir" type="button" style="margin-right: 5px;">Imprimir <i class="fa fa-print"></i></button>
                <h3>Dados Serviços Prestados</h3>
            </div>

            <div id="modal-dados" class="modal-body">
                <div class="dBody">
                    <div class="dId">
                        <label><b>Id</b>
                            <hr>
                        </label>
                        <ul class="trId"></ul>
                    </div>
                    <div class="dOs">
                        <label><b>OS</b>
                            <hr>
                        </label>
                        <ul class="trOs"></ul>
                    </div>
                    <div class="dData">
                        <label><b>Data</b>
                            <hr>
                        </label>
                        <ul class="trData"></ul>
                    </div>
                    <div class="dServico">
                        <label><b>Serviço</b>
                            <hr>
                        </label>
                        <ul class="trServico"></ul>
                    </div>
                    <div class="dPlaca">
                        <label><b>Placa</b>
                            <hr>
                        </label>
                        <ul class="trPlaca"></ul>
                    </div>
                    <div class="dSerial">
                        <label><b>Serial</b>
                            <hr>
                        </label>
                        <ul class="trSerial"></ul>
                    </div>
                    <div class="dSerial-retirado">
                        <label><b>Serial Retirado</b>
                            <hr>
                        </label>
                        <ul class="trSerial-retirado"></ul>
                    </div>
                    <div class="dCliente">
                        <label><b>Cliente</b>
                            <hr>
                        </label>
                        <ul class="trCliente"></ul>
                    </div>
                    <div class="dUser">
                        <label><b>Usuário</b>
                            <hr>
                        </label>
                        <ul class="trUser"></ul>
                    </div>
                    <div class="dValorServ">
                        <label><b>Valor</b>
                            <hr>
                        </label>
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
    </div>
</div>

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


<div id="myModal_comprovantes" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header" style="border-bottom: 3px solid #03A9F4;" style="text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Digitalizar Comprovantes</h4>
    </div>
    <div class="modal-body">
    </div>
</div>

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

<!-- <script src="<?php echo base_url('media') ?>/js/validate.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/boleto.js/boleto.min.js"></script>

<script type="text/javascript">
    var SiteURL = '<?= site_url() ?>';
    $(document).ready(function() {
        $('#formPagamento').submit(function(e) {
            e.preventDefault();
            var url = "<?php echo site_url('contas/update') ?>";
            var dados = {
                'id': $('#idid').val(),
                'senha_pagamento': $('#senha_pagamento').val(),
                'valor_pago': parseFloat($('#valor_pago').val().replace('R$', '').replaceAll('.', '').replace(',', '.').trim()),
                'data_pagamento': $('#data').val()
            };
            $('#btnPagar').html('<i class="fa fa-spinner fa-spin"></i>  Salvando...').attr('disabled', true);
            $.ajax({
                type: "POST",
                url: url,
                data: dados,
                success: function(data) {
                    if (data == "4") {
                        showAlert('warning', 'Senha incorreta!');
                    } else if (data == "3") {
                        showAlert('warning', 'Usuário sem permissão!');
                    } else if (data) {
                        atualizarDadosAgGrid();
                        showAlert('success', 'Pagamento efetuado com sucesso!');
                        $('#myModal_update').modal('hide');
                    } else {
                        showAlert('error', 'Não foi possível realizar o pagamento. Tente novamente!');
                    }
                    $('#btnPagar').html('Salvar').attr('disabled', false);
                },
                error: function() {
                    $('#btnPagar').html('Salvar').attr('disabled', false);
                    showAlert('error', 'Não foi possível realizar o pagamento. Tente novamente!');
                }
            });

            return false;
        });
    });

    $("#myModal_update").on('hidden.bs.modal', function() {
        $('#senha_pagamento').val('');
        $('#valor_pago').val('');
        $('#data').val('');
        $('#idid').val('');
    });

    $('.money1').maskMoney({
        prefix:'R$ ',
        allowZero: false,
        thousands: '.',
        decimal: ',',
    });

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
                beforeSend: function () {
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
                error: function (jqXHR, textStatus, errorThrown) {
                    showAlert('error', "Não foi possível cadastrar a categoria. Tente novamente!");
                    $('#add-categoria').html('Salvar').attr('disabled', false);
                }
            });
        });

        var table = $('#myTable').on('processing.dt', function() {
            // Centralizar na tela o Elemento que mostra o carregamento de
            // dados da tabela
            $('.dataTables_processing')[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        }).DataTable({
            "ajax": "<?= site_url('contas/contas_ajax_NS/1') ?>",
            ordering: false,
            paging: true,
            info: true,
            processing: true,
            serverSide: true,
            lengthChange: false,
            responsive: true,
            "bLengthChange": false,
            language: {
                sProcessing: '<STRONG >Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>',
                searchPlaceholder: 'Pesquisar',
                emptyTable: "Nenhum resultado a ser listado",
                info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
                zeroRecords: "Nenhum resultado encontrado.",
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Próxima",
                    previous: "Anterior"
                },
                lengthMenu: "Mostrar _MENU_ resultados por página",
            },

        });

        $("#someSwitchOptionPrimary").click(function() {
            if ($("#someSwitchOptionPrimary").is(':checked')) {
                table.ajax.url("<?= site_url('contas/contas_por_inst_NS') ?>").load();
            } else {
                table.ajax.url("<?= site_url('contas/contas_ajax_NS/1') ?>").load();
            }
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
                    table.ajax.reload();
                } else {
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
            $.post(url, form, function(data) {
                if (data.status == 'OK') {
                   atualizarDadosAgGrid()
                } else {
                    $(".alert").find('span').html('').html('Não foi possível atualizar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#cancelar').on('click', function(e) {
            showLoadingCancelar();
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_cancel').attr('action');
            $.post(url, form, function(data) {
                if (data == 1) {
                   atualizarDadosAgGrid()
                } else if (data == 3) {
                    $(".alert").find('span').html('').html('Você não tem permissão para cancelamento.');
                    $(".alert").addClass('alert-danger').show();
                } else if (data == 'true') {
                    showAlert('success', 'Cancelada com sucesso.');
                    resetCancelar();
                    $('#myModal_cancel').modal('hide');
                } else {
                    $(".alert").find('span').html('').html('Não foi possível cancelar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        async function atualizarDadosAgGrid() {
            $("#search-input").val("");
            $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
            let tabela = menuAtivo;
            let urlAjax = (tabela == 'contas' ? '/contas_ajax_grid/1' : '/contas_por_inst_NS_nova/1')
            let datasource = getServerSideDados(urlAjax);
            AgGrid.gridOptions.api.setServerSideDatasource(datasource);
        }

        $('#form_editar').on('submit', function(e) {
            e.preventDefault();
            $('#editar').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
            var form = $(this).serialize();
            var url = '<?php echo site_url('contas/update') ?>';
            if ($('#form_editar input[name=fornecedor]').val() != '') {
                if ($('#select_tipo_operacao').val() == 'transferencia' && $('#id_conta1').val() == '-1') {
                    $('#editar').removeAttr('disabled').html('Salvar');
                    showAlert('warning', 'Não foi possível identificar a conta. Tente com outra operação ou verifique os dados no cadastro.');
                    return;
                }
                if (($('#select_tipo_operacao').val() == 'boleto' || $('#select_tipo_operacao').val() == 'boleto_guia') && !$('#cod_barras1').val().trim()) {
                    $('#editar').removeAttr('disabled').html('Salvar');
                    $('#cod_barras1').val('');
                    showAlert('warning', 'Digite um código de barras para poder editar com esse tipo de operação.');
                    return
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form,
                    success: function(data) {
                        if (data) {
                            atualizarDadosAgGrid();
                            showAlert('success', 'Conta editada com sucesso.');
                            $('#editar').removeAttr('disabled').html('Salvar');
                            $('#myModal_editar').modal('hide');
                        } else {
                            $('#editar').removeAttr('disabled').html('Salvar');
                            showAlert('error', 'Não foi possível editar a conta. Tente novamente!');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#editar').removeAttr('disabled').html('Salvar');
                        showAlert('error', 'Ocorreu um erro ao tentar editar a conta. Tente novamente!');
                    }
                });
            } else {
                $('#editar').removeAttr('disabled').html('Salvar');
                showAlert('warning', 'Preencha o campo fornecedor corretamente.');
            }

        });

        $('.money2').mask('000.000.000,00', {reverse: true});

        $('.date').mask('99/99/9999', {
            clearIfNotMatch: true
        });

        // $('#myModal_digitalizar').on('hidden', function(){

        //     $(this).data('modal', null);
        // });

        $('#myModal_comprovantes').on('hidden', function() {
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
                    $('#valor_pago').val(data.valor).maskMoney('mask');
                    $('#idid').val(id);

                    var day = ("0" + now.getDate()).slice(-2);
                    var month = ("0" + (now.getMonth() + 1)).slice(-2);
                    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
                    $('#data').val(today);

                    $("#myModal_update").modal('show');
                } else {
                    showAlert("warning", "Conta não pode ser paga.");
                }
                HideLoadingScreen();
            },
            error: function() {
                showAlert("warning", "Conta não pode ser paga.");
                HideLoadingScreen();
            }
        });
    }

    async function cancel(button) {
        var contaId = $(button).data('conta');
        var url = '<?php echo site_url('contas/update/') ?>';
        var status = $(button).data('status');

        if (status != '3') {
            await Swal.fire({
                title: 'Atenção!',
                text: "Tem certeza que deseja cancelar a conta?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        data: {
                            id: contaId
                        },
                        url: url,
                        dataType: "json",
                        beforeSend: function() {
                            ShowLoadingScreen();
                        },
                        success: function(data) {
                            if (data == 3) {
                                showAlert('warning', 'Você não tem permissão para cancelamento.');
                                $(".alert").addClass('alert-danger').show();
                            } else if (data) {
                                atualizarDadosAgGrid();
                                showAlert('success', 'Cancelada com sucesso.');
                            } else {
                                showAlert('error', 'Não foi possível cancelar a conta. Tente novamente.');
                            }
                            HideLoadingScreen();
                        },
                        error: function() {
                            HideLoadingScreen();
                            showAlert('error', 'Não foi possível cancelar a conta. Tente novamente.');
                        }
                    });
                }
            });
        } else {
            showAlert('warning', 'A conta já está cancelada!');
        }
    }

    function edit(param) {
        $('#fornecedor_span1').html('<input type="hidden" name="fornecedor" id="fornecedorNome"><input type="text" class="form-control" id="fornecedor1" placeholder="Fornecedor" readonly="readonly" required>');
        $('#conta_fornecedor1').html('');
        $('#fornecedor1').val($(param).data('fornecedor'));
        $('#fornecedorNome').val($(param).data('fornecedor'));
        $('div.divCategoria select').val($(param).data('categoria'));
        $('div.divCategoria select').select2({
            placeholder: "Selecione uma categoria...",
            allowClear: false,
            language: "pt-BR",
            width: '100%'
        });
        $('#add_conta_valor1').val($(param).data('valor')).trigger('input');
        $('#dt_vencimento').val($(param).data('vencimento'));
        $('#descricao-form').val($(param).data('descricao'));
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
        $.each(servico, function(i, serv) {
            var template2 = ['<li>' + (i + 1) + '</li><hr>'].join('');
            var template = ['<li>' + serv + '</li><hr>'].join('');
            $('.trId').append(template2);
            $('.trServico').append(template).prop('title', serv);
        });
        $.each(serial, function(i, s) {
            var template = ['<li>' + s + '</li><hr>'].join('');
            $('.trSerial').append(template).prop('title', s);
        });
        $.each(serial_retirado, function(i, ser_r) {
            if (ser_r == "" || ser_r == null) {
                var template = ['<li>-------</li><hr>'].join('');
            } else {
                template = ['<li>' + ser_r + '</li><hr>'].join('');
            }
            $('.trSerial-retirado').append(template).prop('title', ser_r);
        });
        $.each(placa, function(i, p) {
            var template = ['<li>' + p + '</li><hr>'].join('');
            $('.trPlaca').append(template).prop('title', p);
        });
        $.each(clientes, function(i, c) {
            var template = ['<li>' + c + '</li><hr>'].join('');
            $('.trCliente').append(template).prop('title', c);
        });
        $.each(user, function(i, usr) {
            var template = ['<li>' + usr + '</li><hr>'].join('');
            $('.trUser').append(template).prop('title', usr);
        });
        $.each(os, function(i, o) {
            var template = ['<li><a href="<?= site_url("servico/visualizar_os") ?>/' + o + '" target="_blank">' + o + '</a></li><hr>'].join('');
            $('.trOs').append(template).prop('title', o);
        });
        $.each(valor, function(i, val) {
            var result = parseFloat(val);
            if (val == "") {
                var template = ['<li>-------</li><hr>'].join('');
            } else {
                template = ['<li>' + numberParaReal(result) + '</li><hr>'].join('');
            }
            $('.trValorServ').append(template);
        });
        $.each(data, function(i, dt) {
            var mydate = new Date(dt);
            var mes = mydate.getMonth() + 1;
            if (mes.toString().length == 1) mes = "0" + mes;
            var date = mydate.getDate().toString().length == 1 ? '0' + mydate.getDate() + '/' + mes + '/' + mydate.getFullYear() : mydate.getDate() + '/' + mes + '/' + mydate.getFullYear();
            if (dt == "") {
                var template = ['<li>-------</li><hr>'].join('');
            } else {
                template = ['<li>' + date + '</li><hr>'].join('');
            }

            $('.trData').append(template);
        });
        var template = ['<li>R$ ' + valorTotal + '</li><hr>'].join('');
        $('.trValorTotal').append(template);
        $("#myModal_dados").modal('show');

    }


    var tabelaDados = $('#tabelaDados').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhum setor a ser listado",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            zeroRecords: "Nenhum resultado encontrado.",
            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próxima",
                previous: "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
    });

    function exibirDados(botao) {
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

        for (i = 0; i < serial.length; i++) {
            var dados = [
                os[i] ? '<a href="<?= site_url("servico/visualizar_os") ?>/' + os[i] + '" target="_blank">' + os[i] + '</a>' : '-',
                data[i] ? formatarData(data[i]) : '-',
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

    function formatarData(data) {
        if (!data) return '-';

        var partesData = data.split("-");
        return partesData[2] + "/" + partesData[1] + "/" + partesData[0];
    }

    function numberParaReal(num) {
        var num = num.toFixed(2).split('.');
        num[0] = "R$ " + num[0].split(/(?=(?:...)*$)/).join('.');
        return num.join(',');
    }

    $('#btn-imprimir').click(function() {
        var conteudo = document.getElementById('myModal_dados').innerHTML,
            tela_impressao = window.open('about:parent');

        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    })
</script>
<script>
    function get_intaladores_pendentes() {
        $('#instaladores_pendentes')[0].innerHTML = ""
        $.getJSON('<?= base_url() ?>index.php/api/get_instaladores_pendentes', function(data) {
            $.each(data, function(index, d) {
                var template = ['<tr title="CPF/CNPJ: ' + d.cpf_cnpj + ' Banco: ' + d.banco + ', Agência: ' + d.agencia + ', Conta: ' + d.conta + '"><td><input type="checkbox" name="id_contas_instaladores[]" value="' + d.conta_id + '"></input></td>' + '<td>' + d.conta_id + '</td><td>' + d.fornecedor + '</td><td>R$ ' + d.valor + '</td></tr>'].join('');
                $('#instaladores_pendentes').append(template);
            });
            $('#myModalInstaladores').modal();
        });
    }

    function gerar_remessa() {
        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>index.php/api/get_remessa_instaladores",
            data: $('#myModalInstaladores input').serialize(),
            success: function(data) {
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
    function estornar(id) {
        $('#titulo_id_conta')[0].innerHTML = "#" + id.toString();
        $('#input_id_conta_estornar')[0].value = id;
        $('#myModalEstornar').modal('show');
    }

    $('#myModalEstornar').on('hidden.bs.modal', function() {
        $('#input_senha_estornar').val('');
        $('#input_id_conta_estornar').val('');
        $('#input_observacoes_estornar').val('');
    });

    function request_estornar() {
        if (!$('#input_senha_estornar')[0].value) {
            showAlert("warning", "Insira a senha!");
            return;
        }

        if (!$('#input_observacoes_estornar')[0].value) {
            showAlert("warning", "Insira a observação!");
            return;
        }
        showLoadingEstornar();
        
        if (!$('#input_observacoes_estornar')[0].value) {
            showAlert("warning", "Insira a observação!");
            return;
        }
        showLoadingEstornar();

        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>index.php/contas/estornar",
            data: $('#form_estornar').serialize(),
            success: function(data) {
                if (data == "4") {
                    showAlert('error', 'Senha incorreta')
                } else if (data == "3") {
                    showAlert('error', 'Usuário sem permissão!')
                    $('#myModalEstornar').modal('hide');
                } else {
                    atualizarDadosAgGrid()
                    showAlert('success', 'Pagamento estornado com sucesso!')
                    $('#myModalEstornar').modal('hide');
                }
                resetEstornar();
            },
            error: function(error){
                showAlert('error', 'Erro na solicitação ao servidor.')
                resetEstornar();
            }
        });
    }
</script>

<script>
    var fornecedor = false;

    function verifica_categoria(e, v) {
        $('#loadingFornecedor' + v).show();
        $('#conta_fornecedor' + v).html('');
        $('#fornecedorNome').val('');
        $('#fornecedor' + v).val('').trigger('change');
        if (e.value == 'SALÁRIO' || e.value == 'SALARIO' || e.value == 'AJUDA DE CUSTO' || e.value == 'ADIANTAMENTO SALARIAL' || e.value == 'RESCISÃO' || e.value == 'RESCISAO' || e.value == 'FÉRIAS' || e.value == 'FERIAS' || e.value == 'DÉCIMO TERCEIRO' || e.value == '13º SALÁRIO' || e.value == 'DECIMO TERCEIRO' || e.value == 'PRO-LABORE' || e.value == 'ADIANTAMENTO BENEFICIO') {
            var html = `
                <a href='<?= base_url(); ?>index.php/cadastro_fornecedor' style='color:black'><i class='fa fa-plus-circle'></i></a>
                <input type="hidden" id="id_conta${v}" name="id_conta" value="-1"/>
                <select style="width: 100%" onchange="get_conta('${v}')" name="fornecedor" id="fornecedor${v}" required>
                    <option value="" selected disabled>Selecione um funcionário...</option>
            `;
            $.getJSON('<?= base_url() ?>index.php/contas/get_funcionarios', function(data) {
                $.each(data, function(index, json) {
                    html += '<option data-id="' + json.id + '">' + json.nome + '</option>'
                });
                html += "</select>"
                $('#fornecedor_span' + v).html(html);
                $('#labelFornecedor' + v).html('Funcionário: <span class="text-danger">*</span>');
                $('#fornecedor' + v).select2({
                    placeholder: "Selecione um funcionário...",
                    allowClear: false,
                    language: "pt-BR",
                    width: '100%'
                });
                $('#loadingFornecedor' + v).hide();
                fornecedor = false;
            });
        } else {
            var html = `
                <input type="hidden" id="id_conta${v}" name="id_conta" value="-1"/> 
                <select style="width: 100%" onchange="get_conta('${v}')" name="fornecedor" id="fornecedor${v}" required>
                    <option value="" selected disabled>Selecione um fornecedor...</option>
            `;
            $.getJSON('<?= base_url() ?>index.php/cadastro_fornecedor/getFornecedor', function(data) {
                $.each(data, function(index, json) {
                    html += '<option data-id="' + json.id + '">' + json.id + ' - ' + json.nome + '</option>'
                });
                html += "</select>"
                $('#fornecedor_span' + v).html(html);
                $('#labelFornecedor' + v).html('Fornecedor: <span class="text-danger">*</span>');
                $('#fornecedor' + v).select2({
                    placeholder: "Selecione um fornecedor...",
                    allowClear: false,
                    language: "pt-BR",
                    width: '100%'
                });
                $('#loadingFornecedor' + v).hide();
                fornecedor = true;
            });
        }
    }

    function get_conta(v) {
        url = "get_conta_funcionario/";
        if (fornecedor) {
            url = "get_conta_fornecedores/"
        }
        $('#conta_fornecedor' + v).html('');
        if ($('#fornecedor' + v).val() != '' && $('#fornecedor' + v).val() != null) {
            $('#select_tipo_operacao').val('').trigger('change.select2');
            $('#loadingFornecedor' + v).show();
            $.getJSON('<?= base_url() ?>index.php/contas/' + url + $('#fornecedor' + v + ' option:selected').attr('data-id'), function(data) {
                if (data.conta) {
                    $('#conta_fornecedor' + v).html(`
                        <div class="col-md-12 input-container form-group">
                            <label for="operacao1">Operação: <span class="text-danger">*</span></label>
                            <select required id="select_tipo_operacao" class="form-control" id='operacao1' onchange="tipo_operacao(this,'${data.id}', '${v}')">
                                <option value="" selected disabled>Selecione uma operação...</option>
                                <option value="transferencia">Transferência Bancária</option>
                                <option value="boleto">Pagamento de título (boleto)</option>
                                <option value="boleto_guia">Pagamento de guia (boleto)</option>
                            </select>
                        </div>
                        <span id="operacao_transferencia${v}" style="display:none;">
                            <div class="col-md-12 input-container form-group">
                                <label>Conta</label>
                                <input class="form-control" type="text" value="CPF: ${data.cpf}, Agência: ${data.agencia}, Conta: ${data.conta}" disabled="disabled">
                            </div>
                        </span>
                        <span id="operacao_boleto${v}" style="display:none;">
                            <span id="operacao_boleto_titulo${v}" style="display:none;">
                                <div class="col-md-12 input-container form-group">
                                    <label>Linha Digitável: </label>
                                    <input class="form-control" type="text" id="linha_digitavel_boleto${v}" onchange="transformarEmCódigoDeBarras(this,'${v}')" maxlength="54">
                                </div>
                            </span>
                            <span id="operacao_boleto_guia${v}" style="display:none;">
                                <div class="col-md-12 input-container form-group">
                                    <label>Linha Digitável: </label>
                                    <input class="form-control" type="text" id="linha_digitavel_boleto_guia${v}" onchange="transformarEmCódigoDeBarrasGuia(this,'${v}')">
                                </div>
                            </span>
                            <div class="col-md-12 input-container form-group">
                                <label>Código de barras: <span id="obrigatorio-cod${v}" style="display: none;" class="text-danger">*</span></label>
                                <input class="form-control" type="text" maxlength="44" id="cod_barras${v}" name="cod_barras">
                            </div>
                        </span>
                    `);
                    $('#select_tipo_operacao').select2({
                        placeholder: "Selecione uma operação...",
                        allowClear: false,
                        language: "pt-BR",
                        width: '100%'
                    });
                    $("#linha_digitavel_boleto" + v).mask('99999.99999 99999.999999 99999.999999 9 99999999999999');
                    $("#linha_digitavel_boleto_guia" + v).mask('99999999999-9 99999999999-9 99999999999-9 99999999999-9');
                } else {
                    $('#conta_fornecedor' + v).html(`
                        <div class="col-md-12 input-container form-group">
                            <label>Operação: <span class="text-danger">*</span></label>
                            <select required id="select_tipo_operacao" class="form-control" onchange="tipo_operacao(this,'-1','${v}')">
                                <option value="" selected disabled>Selecione uma operação...</option>
                                <option value="transferencia">Transferência Bancária</option>
                                <option value="boleto">Pagamento de título (boleto)</option>
                                <option value="boleto_guia">Pagamento de guia (boleto)</option>
                            </select>
                        </div>
                        <span id="operacao_transferencia${v}" style="display:none;"> 
                            <div class="col-md-12 input-container form-group">
                                <label>Conta: </label>
                                <input class="form-control" type="text" value="Conta não encontrada, verifique o cadastro" disabled="disabled">
                            </div>
                        </span>
                        <span id="operacao_boleto${v}" style="display:none;">
                            <span id="operacao_boleto_titulo${v}" style="display:none;">
                                <div class="col-md-12 input-container form-group">
                                    <label>Linha Digitável: </label>
                                    <input class="form-control" type="text" id="linha_digitavel_boleto${v}" onchange="transformarEmCódigoDeBarras(this,'${v}')" maxlength="54">
                                </div>
                            </span>
                            <span id="operacao_boleto_guia${v}" style="display:none;">
                                <div class="col-md-12 input-container form-group">
                                    <label>Linha Digitável: </label>
                                    <input class="form-control" type="text" id="linha_digitavel_boleto_guia${v}" onchange="transformarEmCódigoDeBarrasGuia(this,'${v}')">
                                </div>
                            </span>
                            <div class="col-md-12 input-container form-group">
                                <label>Código de barras: <span id="obrigatorio-cod${v}" style="display: none;" class="text-danger">*</span></label>
                                <input class="form-control" type="text" maxlength="44" id="cod_barras${v}" name="cod_barras">
                            </div>
                        </span>
                    `);
                    $('#select_tipo_operacao').select2({
                        placeholder: "Selecione uma operação...",
                        allowClear: false,
                        language: "pt-BR",
                        width: '100%'
                    });
                    $("#linha_digitavel_boleto" + v).mask('99999.99999 99999.999999 99999.999999 9 99999999999999');
                    $("#linha_digitavel_boleto_guia" + v).mask('99999999999-9 99999999999-9 99999999999-9 99999999999-9');
                    $('#id_conta' + v)[0].value = "-1";
                }

                $('#loadingFornecedor' + v).hide();
            });
        }
    }

    function tipo_operacao(elemento, id, v) {

        $('#id_conta' + v)[0].value = "-1";
        $('#cod_barras' + v)[0].value = "";
        $('#linha_digitavel_boleto' + v).val("");
        $('#linha_digitavel_boleto_guia' + v).val("");

        if (elemento.value == "transferencia") {
            $('#id_conta' + v)[0].value = id;
            $('#operacao_transferencia' + v)[0].style.display = null;
            $('#operacao_boleto' + v)[0].style.display = "none";
            $('#operacao_boleto_titulo' + v)[0].style.display = "none";
            $('#operacao_boleto_guia' + v)[0].style.display = "none";
            $('#cod_barras' + v).removeAttr('required');
            $('#obrigatorio-cod' + v).hide();
        } else if (elemento.value == "boleto_guia") {
            $('#id_conta' + v)[0].value = "-2";
            $('#operacao_boleto' + v)[0].style.display = null;
            $('#operacao_boleto_guia' + v)[0].style.display = null;
            $('#operacao_transferencia' + v)[0].style.display = "none";
            $('#operacao_boleto_titulo' + v)[0].style.display = "none";
            $('#cod_barras' + v).attr('required', 'required');
            $('#obrigatorio-cod' + v).show();
        } else if (elemento.value == "boleto") {
            $('#operacao_boleto' + v)[0].style.display = null;
            $('#operacao_boleto_titulo' + v)[0].style.display = null;
            $('#operacao_transferencia' + v)[0].style.display = "none";
            $('#operacao_boleto_guia' + v)[0].style.display = "none";
            $('#cod_barras' + v).attr('required', 'required');
            $('#obrigatorio-cod' + v).show();
        } else {
            $('#operacao_transferencia' + v)[0].style.display = 'none';
            $('#operacao_boleto' + v)[0].style.display = "none";
            $('#operacao_boleto_titulo' + v)[0].style.display = "none";
            $('#operacao_boleto_guia' + v)[0].style.display = "none";
            $('#cod_barras' + v).removeAttr('required');
            $('#obrigatorio-cod' + v).hide();
        }
    }

    function transformarEmCódigoDeBarras(element, v) {
        try {
            b = new Boleto(element.value);
            $('#cod_barras' + v)[0].value = b.barcode();
            $('#add_conta_valor' + v)[0].value = b.amount().toString().replaceAll(',', '').replace('.', ',');
            $('#add_conta_valor' + v).trigger('input');
        } catch (exception) {
            $('#cod_barras' + v)[0].value = '';
            showAlert("warning", "Linha digitável inválida!");
        }
    }

    function transformarEmCódigoDeBarrasGuia(element, v) {
        try {
            b = element.value;
            b = b.split(" ");
            b[0] = b[0].split("-")[0];
            b[1] = b[1].split("-")[0];
            b[2] = b[2].split("-")[0];
            b[3] = b[3].split("-")[0];
            $('#cod_barras' + v)[0].value = b.join('');
        } catch (exception) {
            $('#cod_barras' + v)[0].value = '';
            showAlert("warning", "Linha digitável inválida!");
        }
    }

    var AgGrid;
    var menuAtivo = 'contas'
    var carregamentoAgGrid = 0;
    var pesquisar = false;

    function getServerSideDados(urlGrid = '/contas_ajax_grid/1') {
        return {
            getRows: (params) => {
        
                var route = "<?= site_url('contas/') ?>" + urlGrid;
            
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'GET',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow
                    },
                    dataType: 'json',
                    async: true,
                    beforeSend: function() {
                        AgGrid.gridOptions.api.showLoadingOverlay();
                        carregamentoAgGrid++;
                    },
                    success: function (data) {
                        carregamentoAgGrid--;
                        if (!carregamentoAgGrid) {
                            if (data && data.status == 200) {
                                AgGrid.gridOptions.api.hideOverlay();
                                params.success({
                                    rowData: data.rows,
                                    rowCount: data.lastRow,
                                });
                            } else if (data && data.status == 404) {
                                showAlert('warning', "Dados não encontrados para o parâmetro informado!");
                                params.failCallback();
                                params.success({
                                    rowData: [],
                                    rowCount: 0,
                                });
                                AgGrid.gridOptions.api.showNoRowsOverlay();
                            } else {
                                showAlert('error', 'Erro na solicitação ao servidor.');
                                params.failCallback();
                                params.success({
                                    rowData: [],
                                    rowCount: 0,
                                });
                                AgGrid.gridOptions.api.showNoRowsOverlay();
                            }
                        } else {
                            params.failCallback();
                            AgGrid.gridOptions.api.showLoadingOverlay();
                        }
                        resetPesquisarButton();
                        resetLimparButton();
                    },
                    error: function (error) {
                        carregamentoAgGrid--;
                        if (!carregamentoAgGrid) {
                            showAlert('error', 'Erro na solicitação ao servidor.');
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                        } else {
                            params.failCallback();
                            AgGrid.gridOptions.api.showLoadingOverlay();
                        }
                        resetPesquisarButton();
                        resetLimparButton();
                    },
                });
            },
        };
    }

    async function atualizarDadosAgGrid() {
        $("#search-input").val("");
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        let tabela = menuAtivo;
        let urlAjax = (tabela == 'contas' ? '/contas_ajax_grid/1' : '/contas_por_inst_NS_nova/1')
        let datasource = getServerSideDados(urlAjax);
        AgGrid.gridOptions.api.setServerSideDatasource(datasource);
    }

    var AgGridDigitalizacao;
    $(document).ready(function () {
        $("#AddArquivoForm").on('click', function(e) {
            e.preventDefault();

            var dataForm = new FormData();

            //File data
            var descricao = $('#descricaoArquivo').val();
            var comprovante = $('#comprovante').prop("checked") == "checked";
            let idConta = $('#idContaDig').val();

            var file_data = $('#filesForm')[0];
            file = file_data.files[0];

            if (!file) {
                showAlert("warning", "Selecione um Arquivo.");
                return;
            }

            if (!$('#descricaoArquivo').val()) {
                showAlert("warning", "Informe uma descrição para o arquivo!");
                return;
            }

            dataForm.append("arquivo", file);
            dataForm.append("descricao", descricao);
            dataForm.append("comprovante", comprovante);

            $.ajax({
                url: "<?php echo site_url('contas/digitalizacao') ?>/" + idConta,
                type: "POST",
                data: dataForm,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#AddArquivoForm").html('<i class="fa fa-spinner fa-spin"></i> Adicionando...').attr('disabled', true);
                },
                success: function(retorno) {
                    retorno = JSON.parse(retorno);
                    if (retorno.success) {
                        showAlert('success', "Arquivo adicionado com sucesso!");
                        AgGridDigitalizacao.gridOptions.api.applyTransaction({ add: [ retorno.registro ] });
                        $("#descricaoArquivo").val('');
                        $("#comprovante").val('');
                        $("#filesForm").val('');
                        $('#labelForFilesForm').html('Arquivo (PDF):');
                    } else {
                        showAlert('error', "Não foi possível adicionar o arquivo. Tente novamente!");
                    }
                    $("#AddArquivoForm").html('<i class="fa fa-plus"></i> Adicionar').attr('disabled', false);
                },
                error: function(error) {
                    showAlert('error', "Não foi possível adicionar o arquivo. Tente novamente!");
                    $("#AddArquivoForm").html('<i class="fa fa-plus"></i> Adicionar').attr('disabled', false);
                },

            });
        });


        $('#filesForm').change(function() {
            var t = $(this).val();
            var labelText = 'Arquivo (PDF): ' + t.substr(12, t.length);
            $(this).prev('label').text(labelText);
        });

        const gridOptions = {
            columnDefs: [{
                    headerName: "ID",
                    field: "id",
                    width: 120
                },
                {
                    headerName: "Descrição",
                    field: "descricao",
                    flex: 1,
                    minWidth: 200
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

                        let tableId = "tableDigitalizacao";
                        let dropdownId = "dropdown-menu-dig" + data.id;
                        let buttonId = "dropdownMenuButtonDig_" + data.id;
                        let i = options.rowIndex;
                        let ajusteAltura = 0;
                        let paginaAtual = gridOptions.api.paginationGetCurrentPage();
                        let qtd = 6;


                        if (paginaAtual > 0) {
                            i = i - (paginaAtual) * qtd
                        }

                        if (i > 9) {
                            i = 9;
                        }

                        if (i > 4) {
                            ajusteAltura = 105;
                        } else {
                            ajusteAltura = 0;
                        }

                        return `
                            <div class="dropdown dropdown-table">
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${100 - ajusteAltura}% - ${i}px);" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        <a href="${SiteURL + '/contas/view_file/' + options.data.file}" target="_blank">
                                            Visualizar
                                        </a>
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
            paginationPageSize: 6,
            localeText: AG_GRID_LOCALE_PT_BR,
            overlayLoadingTemplate: '<div class="spinner"></div>'
        };

        var gridDiv = document.querySelector("#tableDigitalizacao");
        AgGridDigitalizacao = new agGrid.Grid(gridDiv, gridOptions);

        $(document).on('click', '.digitalizarDocumento', function () {
            let idConta = $(this).data('conta');
            $.ajax({
                url: '<?= site_url('/contas/getFiles') ?>/' + idConta,
                type: 'GET',
                dataType: 'json',
                cache: false,
                beforeSend: function() {
                    ShowLoadingScreen();
                },
                success: function (data) {
                    if (data && "arquivos" in data) {
                        AgGridDigitalizacao.gridOptions.api.setRowData(data.arquivos);
                        $('#idContaDig').val(idConta);
                        $('#myModal_digitalizar').modal('show');
                    } else {
                        showAlert('error', "Não foi possível buscar arquivos da conta. Tente novamente!");
                    }
                    HideLoadingScreen();
                },
                error: function() {
                    showAlert('error', "Não foi possível buscar arquivos da conta. Tente novamente!");
                    HideLoadingScreen();
                }
            });
        });

        $('#myModal_digitalizar').on('hidden.bs.modal', function() {
            $('#labelForFilesForm').html('Arquivo (PDF):');
            $("#AddArquivoForm").html('<i class="fa fa-plus"></i> Adicionar').attr('disabled', false);
            $("#descricaoArquivo").val('');
            $("#comprovante").val('');
            $("#filesForm").val('');
            $('#idContaDig').val('');
            $('#comprovante').prop('checked', false);
        });
    });

    $(document).ready(async function() {
        ///// adicao de novas funcoes para ag grid
        var result = [];
        async function buscarDadosAgGrid(urlGrid = '/contas_ajax_grid/1') {
            $("#search-input").val("");
            let datasource = getServerSideDados(urlGrid);
            AgGrid.gridOptions.api.setServerSideDatasource(datasource);
            
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

                        let tableId = "tableAnexos";
                        let dropdownId = "dropdown-menu" + data.id;
                        let buttonId = "dropdownMenuButton_" + data.id;
                        let i = options.rowIndex;
                        let ajusteAltura = 0;
                        let paginaAtual = gridOptions.api.paginationGetCurrentPage();
                        let qtd = $('#select-quantidade-por-contatos-corporativos').val()


                        if (paginaAtual > 0) {
                            i = i - (paginaAtual) * qtd
                        }

                        if (i > 9) {
                            i = 9;
                        }

                        if (i > 4) {
                            ajusteAltura = 385;
                        } else {
                            ajusteAltura = 0;
                        }

                        return `
                            <div class="dropdown dropdown-table">
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}% - ${i}px);" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer; display: ${data.botaoDigitalizar ? "block": "none"};">
                                        ${data.botaoDigitalizar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer; display: ${data.botaoEditar ? "block": "none"};">
                                        ${data.botaoEditar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer; display: ${data.botaoServicoPrestado.includes() ? "block": "none"};">
                                        ${data.botaoServicoPrestado}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer; display: ${data.botaoExcluir ? "block": "none"};">
                                        ${data.botaoExcluir}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer; display: ${data.botaPagar ? "block": "none"};">
                                        ${data.botaPagar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes"  style="cursor: pointer; display: ${data.botaoComprov ? "block": "none"};">
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
            rowModelType: 'serverSide',
            serverSideStoreType: 'partial',
            overlayLoadingTemplate: '<div class="spinner"></div>'
        };
        var gridDiv = document.querySelector("#tableContatos");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        preencherExportacoes(gridOptions, "ContasAPagarShowTecnologia");

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
        });

        $(".btn-expandir").on("click", function(e) {
            e.preventDefault();
            menuAberto = !menuAberto;

            if (menuAberto) {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-show.svg`
                );
                $("#conteudo-lateral").hide();
                $("#conteudo").removeClass("col-md-9");
                $("#conteudo").addClass("col-md-12");
            } else {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-hide.svg`
                );
                $("#conteudo-lateral").show();
                $("#conteudo").css("margin-left", "0px");
                $("#conteudo").removeClass("col-md-12");
                $("#conteudo").addClass("col-md-9");
            }
        });

        $("#BtnPesquisar").on("click", function() {
            $("#search-input").val("");
            var inputValue = $('#filtrar-atributos').val().toString();

            if (menuAtivo == 'contas' && inputValue.trim().length > 0) {
                showLoadingPesquisarButton();
                buscarDadosAgGrid('/contas_ajax_grid/1/?search=' + inputValue)
                pesquisar = true;
            } else {
                if (inputValue.trim().length > 0) {
                    showLoadingPesquisarButton();
                    buscarDadosAgGrid('/contas_por_inst_NS_nova/1/?search=' + inputValue)
                    pesquisar = true;
                } else {
                    showAlert('warning', 'Informe algum parâmetro para realizar a pesquisa!')
                }
            }
        });

        $("#BtnLimparFiltro").on("click", function() {
            $("#filtrar-atributos").val("");
            $("#search-input").val("");
            showLoadingLimparButton();
            pesquisar = false;

            if (menuAtivo == 'contas') {
                buscarDadosAgGrid()
            } else {
                buscarDadosAgGrid('/contas_por_inst_NS_nova/1')
            }
        });

        $('#menu-contas-show').click(function() {
            if (!$(this).hasClass("selected")) {
                $(this).addClass("selected");
                $('#menu-os-show').removeClass("selected");
                $('#filtrar-atributos').val('');
                preencherExportacoes(gridOptions, "ContasAPagarShowTecnologia");
                $('#titulo-card').text("Contas Show Tecnologia:");
                buscarDadosAgGrid();
                menuAtivo = 'contas';
                pesquisar = false;
            }
        });

        $('#menu-os-show').click(function() {
            if (!$(this).hasClass("selected")) {
                $(this).addClass("selected");
                $('#menu-contas-show').removeClass("selected");
                $('#filtrar-atributos').val('');
                $('#titulo-card').text("OS Show Tecnologia:");
                preencherExportacoes(gridOptions, "OSShowTecnologia");
                buscarDadosAgGrid('/contas_por_inst_NS_nova/1');
                menuAtivo = 'os';
                pesquisar = false;
            }
        });

        var resultado = await buscarDadosAgGrid();
    });

    function ShowLoadingScreen() {
        $('#loading').show()
    }

    function HideLoadingScreen() {
        $('#loading').hide()
    }

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }

    function showLoadingCancelar() {
        $('#cancelar').html('<i class="fa fa-spinner fa-spin"></i> Cancelando...').attr('disabled', true);
    }

    function resetCancelar() {
        $('#cancelar').html('Cancelar').attr('disabled', false);
    }

    function showLoadingEstornar() {
        $('#btnEstornar').html('<i class="fa fa-spinner fa-spin"></i> Estornando...').attr('disabled', true);
    }

    function resetEstornar() {
        $('#btnEstornar').html('Estornar').attr('disabled', false);
    }

    function showLoadingPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...').attr('disabled', true);
        $('#BtnLimparFiltro').attr('disabled', true);
    }

    function resetPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
        $('#BtnLimparFiltro').attr('disabled', false);
    }

    function showLoadingLimparButton() {
        $('#BtnLimparFiltro').html('<i class="fa fa-spinner fa-spin"></i> Limpando...').attr('disabled', true);
        $('#BtnPesquisar').attr('disabled', true);
    }

    function resetLimparButton() {
        $('#BtnLimparFiltro').html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
        $('#BtnPesquisar').attr('disabled', false);
    }
</script>