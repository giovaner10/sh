<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Contas a Pagar ShowTechnology", site_url('Homes'), "Departamentos", "Financeiro > Contas > ShowTechnology");
?>


<div id="loading">
    <div class="loader"></div>
</div>

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


<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px; width: 100%;">

            <form id="formBusca" style="width: 100%;">

                <div class="form-group filtro">

                    <div class="input-container">


                        <?php if ($this->auth->is_allowed_block('valores')) { ?>

                            <div style="display: flex; flex-direction:column; width:100%;">

                                <h4 style="margin-bottom: 10px !important;">Informações:</h4>

                                <div class="card" id="card-orange" style="margin-bottom: 18px;  height: 55px;">
                                    <strong style="display: inline-block; ">A pagar: <br>US$ <?= number_format($estatisticas->pagar, 2, '.', ',') ?></strong>
                                </div>

                                <div class="card" id="card-green" style="margin-bottom: 18px;  height: 55px;">
                                    <strong style="display: inline-block; ">Pagos: <br>US$ <?= number_format($estatisticas->pago, 2, '.', ',') ?></strong>
                                </div>

                                <div class="card" id="card-blue" style="margin-bottom: 18px;  height: 55px;">
                                    <strong style="display: inline-block; ">Entradas: <br>US$ <?= number_format($estatisticas->caixa, 2, '.', ',') ?></strong>
                                </div>

                                <div id="card-red" class="card <?= $estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo' ?>" style="margin-bottom: 18px;  height: 55px;">
                                    <?php if ($estatisticas->saldo >= 0) : ?>
                                    <?php else : ?>
                                    <?php endif ?>
                                    <strong style="display: inline-block; ">Saldo: <br>US$ <?= number_format($estatisticas->saldo, 2, '.', ',') ?></strong>
                                </div>
                            </div>

                        <?php } ?>
                    </div>

                    <h4 style="margin-bottom: 0px !important;">Ações:</h4>

                    <div class="button-container">
                        <a href="#cadCateg" style='width:100%; margin-top: 5px' data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Categoria</a>
                    </div>

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
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card">Contas ShowTechnology:</b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
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
            <div class="registrosDiv">
                <select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value=10 selected>10</option>
                    <option value=25>25</option>
                    <option value=50>50</option>
                    <option value=100>100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
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
<script type="text/javascript" src="<?= versionFile('assets/js/remessas/pneushow', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    var BaseURL = '<?= base_url('') ?>';
</script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js">
</script>
<script src="<?php echo base_url('media') ?>/js/jquery-mask.js"></script>
<link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

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
        margin-top: -9px;
        padding-left: 5px;
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
    <div class="modal-body">
        <form method="post" action='<?php echo site_url('contas/add/true') ?>' name="" id="form">
            <label>Fornecedor</label>
            <input type="number" value="4" name="empresa" style="display: none;">
            <input type="text" name="fornecedor" class="span3" data-provide="typeahead" data-source='<?php echo $fornecedores ?>' data-items="6" placeholder="Fornecedor" autocomplete="off" value="<?php echo $this->input->post('fornecedor') ?>" />

            <label>Descrição</label>
            <textarea type="text" class="span3" name="descricao" id="descricao" placeholder="Descrição" required></textarea>

            <label>Categoria</label>
            <select class="form-control" id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria) : ?>
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

<div id="myModal_editar" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="width: 610px; margin: auto;">
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
                            <input type="text" class="col-md-12 form-control" name="categoria" id="categoria_ct" placeholder="Categoria" required>
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
                            <label>Descrição:</label>
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
                        <input type="hidden" name="id" id="idid" value="">
                        <label class="control-label">Senha</label>
                        <input type="password" class="span3 form-control" name="senha_pagamento" id="senha_pagamento" value="" required>
                    </div>
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4;padding-left: 10px;">
                        <label class="control-label">Valor Pago</label>
                        <input type="text" class="span3 money2 form-control" name="valor_pago" id="money" value="" required>
                    </div>
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4;padding-left: 10px;">
                        <label class="control-label">Data Pagamento</label>
                        <input type="text" class="span3 date form-control" name="data_pagamento" id="data" placeholder="__/__/____" required>
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

<div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

        <div class="modal-content" style="border:none;">
            <div class="modal-body">
            </div>
        </div>
    </div>
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
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="button" class="btn btn-success" onclick="request_estornar()" id="btnEstornar">Estornar</button>
                    </div>
                </div>
            </form>
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


            body {
                background: #FFF;
                color: #000;
                font: 10pt serif;
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

        }
    </style>
    <div class="modal-header">
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

<!-- MODAL DE CADASTRO DE CATEGORIAS -->
<div id="cadCateg" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-content" style="background-color: #fff!important;width: 610px;margin: auto;margin-top: 25px;">
        <div class="modal-header" style="text-align: center; border-bottom: 2px solid #e5e5e5;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="color: #1C69AD !important; font-size: 22px !important; font-family: 'Mont SemiBold';">Nova Categoria</h4>
        </div>
        <div style="border:none; padding-top: 10px;">
            <form id="categoriaForm" action="<?= site_url('contas/add_categoria') ?>" method="POST">
                <div class="modal-body" style="padding-top: 0;">
                    <div class="form-group" style="display: inline-grid; padding: 20px; padding-bottom: 0;">
                        <label class="control-label" for="categ">Categoria: </label>
                        <input id="categ" name="categoria" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer" style="padding-top: 0;">
                    <div class="footer-group">
                        <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="loading">
    <div class="loader"></div>
</div>
<style>

</style>

<script type="text/javascript">
    $(document).ready(function() {

        function showLoading() {
            $('#loading').show();
        }

        function hideLoading() {
            $('#loading').hide();
        }




        $('#categoriaForm').on('submit', async function(event) {
            event.preventDefault();
            showLoading()

            var formData = $(this).serialize();
            await $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {

                    response = JSON.parse(response)

                    if (response) {
                        showAlert("success", 'Categoria adicionada com sucesso!');
                        $('#cadCateg').modal('hide');
                        $('#categ').val('');
                    } else {
                        showAlert("error", 'Categoria já adicionada, tente novamente.');
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showAlert("error", 'Ocorreu um erro ao adicionar a categoria. Tente novamente.');
                }
            });

            hideLoading()

        });


        var table = $('#myTable').on('processing.dt', function() {
            // Centralizar na tela o Elemento que mostra o carregamento de
            // dados da tabela
            $('.dataTables_processing')[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        }).DataTable({
            "ajax": {
                "url": "<?= site_url('contas/contas_ajax2/1') ?>",
                "data": function(d) {
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
                table.ajax.url("<?= site_url('contas/contas_por_inst') ?>").load();
            } else {
                table.ajax.url("<?= site_url('contas/contas_ajax2/1') ?>").load();
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
                url: "<?php echo site_url('contas/deleteConta'); ?>",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response) {
                        showAlert("success", 'Conta cancelada com sucesso!');
                        location.reload();
                    } else {
                        showAlert("warning", "Não foi possível cancelar a conta");
                    }
                },
                error: function() {
                    showAlert("warning", 'Erro ao deletar a conta.');
                }
            });

            $('#myModal_cancel').modal('hide');
        });


        $('#dt_vencimento').mask('00/00/0000');
        $('.money2').mask('000.000.000.000.000,00', {
            reverse: true
        });

        $('.date').mask('99/99/9999', {
            clearIfNotMatch: true
        });

        $('#myModal_digitalizar').on('hidden', function() {

            $(this).data('modal', null);
        });

        $('#myModal_comprovantes').on('hidden', function() {
            $(this).data('modal', null);
            $('#myModal_comprovantes > .modal-body').html('');
        });

    });

    jQuery(document).ready(function() {
        $('.us').mask("#,##0.00", {
            reverse: true
        });

        // Função para formatar a data no formato YYYY-MM-DD
        function formatDate(date) {
            const parts = date.split('/');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return date; // Se a data não estiver no formato esperado, retorna como está
        }

        jQuery('#formPagamento').submit(function(event) {
            event.preventDefault();

            const $dataPagamento = $('#data');
            const dataFormatada = formatDate($dataPagamento.val());
            $dataPagamento.val(dataFormatada);

            var url = "<?php echo site_url('contas/update') ?>";
            var dados = jQuery(this).serialize();

            jQuery.ajax({
                type: "POST",
                url: url,
                data: dados,
                success: function(response) {
                    if (response === "true") {
                        $.getJSON('contas_ajax/4', function(data) {
                            var dynatable = $('#myTable').dynatable({
                                dataset: {
                                    records: data
                                }
                            }).data('dynatable');

                            dynatable.settings.dataset.originalRecords = data;
                            dynatable.process();
                        });

                        showAlert("success", 'Pagamento efetuado com sucesso!');
                        $('#myModal_update').modal('hide');
                        location.reload();
                    } else if (response == "4") {
                        showAlert("warning", 'Senha incorreta!');
                    } else if (response == "3") {
                        showAlert("warning", 'Usuário sem permissão!');
                    } else {
                        showAlert("error", "Erro desconhecido");
                    }
                },
                error: function() {
                    showAlert("error", "Erro desconhecido");
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
        $('#categoria_ct').val($(param).data('categoria'));
        $('#valor').val($(param).data('valor'));
        $('#dt_vencimento').val($(param).data('vencimento'));
        $('#descricao-form').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));
    }

    $('#btn-imprimir').click(function() {
        var conteudo = document.getElementById('myModal_dados').innerHTML,
            tela_impressao = window.open('about:parent');

        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    })

    function estornar(id) {
        $('#titulo_id_conta')[0].innerHTML = "#" + id.toString();
        $('#input_id_conta_estornar')[0].value = id;
        $('#myModalEstornar').modal();
    }

    $('#myModalEstornar').on('hide.bs.modal', function() {
        $("#input_senha_estornar").val('').trigger('change');
        $("#input_observacoes_estornar").val('').trigger('change');
    })


    function request_estornar() {
        if (!$('#input_senha_estornar')[0].value) {
            showAlert("warning", "Insira a senha!");
            return;
        }

        if (!$('#input_observacoes_estornar')[0].value) {
            showAlert("warning", "Insira uma observação!");
            return;
        }
        $('#btnEstornar').html('<i class="fa fa-spinner fa-spin"></i> Processando...').attr('disabled', true);
        jQuery.ajax({
            type: "POST",
            url: "<?= base_url() ?>index.php/contas/estornar",
            data: $('#form_estornar').serialize(),
            success: function(data, textStatus, jqXHR) {
                if (jqXHR.status === 200 && data != "3" && data != "4") {
                    $('#btnEstornar').html('Estornar').attr('disabled', false);
                    showAlert("success", 'Pagamento estornado com sucesso!');
                    location.reload();
                } else if (data == "4") {
                    $('#btnEstornar').html('Estornar').attr('disabled', false);
                    showAlert("warning", 'Senha incorreta!');
                } else if (data == "3") {
                    $('#btnEstornar').html('Estornar').attr('disabled', false);
                    showAlert("warning", 'Usuário sem permissão!');
                } else {
                    $('#btnEstornar').html('Estornar').attr('disabled', false);
                    showAlert("error", "Erro desconhecido");
                }
                $('#myModalEstornar').modal('hide');
            }
        });
    }


    $(document).ready(async function() {

        function ShowLoadingScreen() {
            $('#loading').show()
        }

        function HideLoadingScreen() {
            $('#loading').hide()
        }

        ///// adicao de novas funcoes para ag grid
        var result = [];
        async function buscarDadosAgGrid(search = null) {
            search = search ? '?search=' + search : ''
            await $.ajax({
                url: "<?= site_url('contas/') ?>" + '/contas_ajax2_nova/1/' + search,
                type: 'GET',
                success: function(response) {
                    result = JSON.parse(response);
                    updateData(result)
                },
                error: function(xhr, status, error) {
                    showAlert("error", "Erro ao fazer a requisição");
                    $('#result').text('Erro ao fazer a requisição.');
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

                        if (i > 3) {
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
                                     <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer; display: ${data.botaoExcluir ? "block": "none"};" onclick="confirmDelete(this, '${data.id}');">
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
        };
        var gridDiv = document.querySelector("#tableContatos");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        AgGrid.gridOptions.api.showLoadingOverlay()
        preencherExportacoes(gridOptions, "ContasAPagarShowTechnology");


        function updateData(newData = []) {
            showSpinner()
            gridOptions.api.setRowData(newData);
        }

        function showSpinner() {
            AgGrid.gridOptions.api.showLoadingOverlay()
        }

        var resultado = await buscarDadosAgGrid();

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
            gridOptions.api.refreshCells({
                force: true
            });
        });

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

        $("#BtnPesquisar").on("click", function() {
            var inputValue = $('#filtrar-atributos').val().toString();
            if (inputValue.trim().length > 0) {
                $("#BtnPesquisar").blur();
                updateData()
                showSpinner()
                buscarDadosAgGrid(inputValue)
            } else {
                $("#BtnPesquisar").blur();
                showAlert("warning", "Insira um valor valido.")
            }

        });

        $('#editar').on('click', async function(e) {
            e.preventDefault();
            $('#editar').attr('disabled', 'disabled').text('Salvando...');
            var form = $(this).closest('form').serialize();
            var url = $('#form_editar').attr('action');
            if ($('#fornecedor').val() != '') {

                try {
                    await $.post(url, form, async function(data) {
                        if (data) {
                            showAlert("success", "Edição realizada com sucesso!");
                            $('#editar').removeAttr('disabled').text('Salvar');
                            $("#myModal_editar").modal("hide");
                            updateData([]);
                            $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
                            buscarDadosAgGrid();
                        } else {
                            showAlert("error", "Falha ao realizar edição.");
                            $('#editar').removeAttr('disabled').text('Salvar');
                        }
                    }, 'json');
                } catch (E) {
                    showAlert("error", "Falha ao realizar edição.");
                    $('#editar').removeAttr('disabled').text('Salvar');
                }
            } else {
                showAlert("error", "Falha ao realizar edição.");
                $('#editar').removeAttr('disabled').text('Salvar');
            }
            $('#editar').removeAttr('disabled').text('Salvar');
        });

        $("#BtnLimparFiltro").on("click", function() {
            var inputValue = $('#filtrar-atributos').val().toString();
            $("#filtrar-atributos").val("");
            if (inputValue.trim().length > 0) {
                updateData()
                showSpinner()
                buscarDadosAgGrid()
            }

        });
    });

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }

    function confirmDelete(element, idCancel) {
    const deleteUrl = element.getAttribute('data-controller');
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente Excluir a conta ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#007BFF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $('#loading').show()            
            event.preventDefault();
            var id = idCancel;
            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('contas/deleteConta'); ?>",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response) {
                        location.reload();
                        $('#loading').hide()            
                        showAlert("success",'Conta cancelada com sucesso!');
                    } else {
                        $('#loading').hide()            
                        showAlert("warning","Não foi possível cancelar a conta");
                    }
                },
                error: function() {
                    $('#loading').hide()            
                    showAlert("warning",'Erro ao deletar a conta.');
                }
            });
        }
    });
}

</script>