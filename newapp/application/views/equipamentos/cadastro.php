<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("listagem_equipamentos") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('cadastros') ?> >
        <?= lang("listagem_equipamentos") ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        
        <div id="filtroBusca" class="card">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container">
                        <label for="searchTypeData">Buscar por:</label><span class="text-danger">*</span>
                        <select class="form-control" id="filtroEquipamentos" type="text" name="filtroEquipamentos" style="width: 100%;">
                            <option value="todos" selected disabled><?= lang('selec_filtro') ?></option>
                            <option value="id"><?= lang('codigo') ?></option>
                            <option value="serial"><?= lang('serial') ?></option>
                            <option value="modelo"><?= lang('modelo') ?></option>
                            <option value="data_cadastro"><?= lang('data_cadastro') ?></option>
                            <option value="linha1"><?= lang('linha') . ' 1' ?></option>
                            <option value="linha2"><?= lang('linha') . ' 2' ?></option>
                            <option value="ccid1">CCID 1</option>
                            <option value="ccid2">CCID 2</option>
                        </select>
                    </div>
                    <div class="input-container descricaoFirmwareBusca">
                        <label for="labelSearchEquipamentos" id="labelSearchEquipamentos" style="margin-bottom: -4px;">Descrição:</label><span class="text-danger">*</span>
                        <input type="searchTableEquipamentos" id="searchTableEquipamentos" class="form-control" placeholder="Selecione o tipo de filtro..." disabled autocomplete="off">
                    </div>
                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="informacoes" class="card" style="margin-top: -10px !important;">
            <h4 style="margin-bottom: 0px !important;">Informações Gerais:</h4>
            <div class="form-group filtro">
                <div style="float: right;">
                    <div class="input-container">
                        <label for="encaminhados" id="encaminhadosLabel" style="margin-bottom: -4px;"><?= lang('encaminhados_aos_clientes') ?>:</label>
                        <input type="text" id="encaminhados" class="form-control" value='<?php echo $count_estoque['est_env']; ?>' disabled>
                    </div>

                    <div class="input-container">
                        <label for="disponieisEmClientes" id="disponieisEmClientesLabel" style="margin-bottom: -4px;"> <?= lang('disponiveis_em_clientes') ?>:</label>
                        <input type="text" id="disponieisEmClientes" class="form-control" value='<?php echo $count_estoque['est_dis']; ?>' disabled>
                    </div>

                    <div class="input-container">
                        <label for="instaladosLabel" id="instaladosLabel" style="margin-bottom: -4px;"> <?= lang('instalados_em_clientes') ?>:</label>
                        <input type="text" id="instalados" class="form-control" value='<?php echo $count_estoque['est_clie']; ?>' disabled>
                    </div>

                    <div class="input-container">
                        <label for="estoqueLaben" id="estoqueLaben" style="margin-bottom: -4px;"><?= lang('em_estoque') ?>:</label>
                        <input type="text" id="estoque" class="form-control" value='<?php echo $count_estoque['est_atual']; ?>' disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados-equipamentos" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b><?= lang("listagem_equipamentos") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">

                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonCadastrar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <i class="fa fa-plus" aria-hidden="true"></i> <?= lang('cadastrar') ?> <span class="caret"></span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-cadastro" aria-labelledby="dropdownMenuButtonCadastrar" id="opcoes_cadastro" style="margin-top: 30px;">
                            <h5 class="dropdown-title"> Como deseja Cadastrar? </h5>
                            <div class="dropdown-item opcao-cadastro" id="btnCadastrar" data-toggle="modal" data-target="#modal_adicionar_equipamento">
                                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i><?= lang('cadastrar') ?>
                            </div>

                            <div class="dropdown-item opcao-cadastro" id="btnCadastrarLote" data-toggle="modal" data-target="#modal_adicionar_equipamento_lote">
                                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i> Arquivo
                            </div>
                        </div>

                    </div>

                    <a href="dashboard_eqp" target="_blank">
                        <button class="btn btn-primary" id="btnDashboard" title="Dashboard" style="margin-right: 10px;">
                            <i class="fa fa-bar-chart"></i>
                            <?= lang('dashboard_equipamentos') ?>
                        </button>
                    </a>

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
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>


            <div style="position: relative;">
                <div class="wrapperEquipamentos">
                    <div id="tableEquipamentos" class="ag-theme-alpine my-grid-equipamentos" style="height: 519px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POSIÇÃO DO EQUIPAMENTO -->
<div id="modal_posicao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="myModalLabel1"><?= lang('posicao_equipamento') ?></h3>
            </div>
            <div id="body_posicao" class="modal-body">

            </div>
            <div class="modal-footer">
                <div class="footer-group">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CADASTRO DE EQUIPAMENTOS -->
<div id="modal_adicionar_equipamento" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formcadastrar" enctype="multipart/form-data">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAddFirmware">Cadastrar Equipamento</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <h4 class="subtitle" id="dadosEquipamento" style="padding-left: 0; padding-right: 0;">Dados para Cadastro</h4>
                        <div class='row'>
                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="serial">Serial: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="serial" id="serial" required autocomplete="off" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="marca">Marca: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="marca" id="marca" required autocomplete="off" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="modelo">Modelo: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="modelo" id="modelo" required autocomplete="off" />
                            </div>
                            <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                <label for="linha1">Linha 1:</label>
                                <select class="form-control pesq_linhas" type="text" name="linha1" id="linha1" autocomplete="off">
                                    <option value="" selected>Digite a linha...</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                <label for="linha2">Linha 2:</label>
                                <select class="form-control pesq_linhas" type="text" name="linha2" id="linha2" autocomplete="off">
                                    <option value="" selected>Digite a linha...</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end">
                        <button type="submit" class="btn btn-success" id='btnSalvarCadastro'>Salvar</button>
                        <button type="button" class="btn btn-default" id='btnLimparCadastro'>Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CADASTRO DE EQUIPAMENTOS LOTE -->
<div id="modal_adicionar_equipamento_lote" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="serialModal">Cadastrar Equipamentos em Lote</h3>
            </div>
            <div class="modal-body">
                <h4 class="subtitle" style="margin-left: 5px">Dados da Importação:</h4>

                <p class="alert alert-info placa-alert">Apenas equipamentos que não constam no sistema serão cadastradas!</p>

                <div class="col-md-12" style="padding-left: 0;">
                    <div class="col-md-8 form-group" style="display: flex; padding-left: 8px;">
                        <input class="form-control input-sm" type="file" name="file" id="fileEquipamentos" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" data-buttonText="Arquivo" required>
                        <div class="col-md-1 form-group" style="margin-top: 5px;">
                            <i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon" aria-hidden="true" title="Clique para saber mais"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-top: 30px;">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-success" id='btnSalvarCadastroLote'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MODELO DOCUMENTO ITENS DE MOVIMENTO -->
<div id="modalModeloItens" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Modelo de documento <span id="tituloDetalhesDoContrato"></span></h3>
            </div>
            <div class="modal-body scrollModal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content" style="padding: 0px 20px">
                            <div id="div_identificacao">
                                <div class="row">
                                    <div class="col-md-12" style="border-left: 3px solid #03A9F4; padding-bottom: 0px; margin-right: 0px">
                                        <p class="text-justify">
                                            A planilha deve conter as seguintes colunas:
                                        <ul>
                                            <li>Serial</li>
                                            <li>Marca</li>
                                            <li>Modelo</li>
                                            <li>Linha1</li>
                                            <li>Linha2</li>
                                        </ul>
                                        Formatos suportados: .xlsx.
                                        <p>
                                        <ul>
                                            <li>
                                                <a href="<?= base_url('uploads/equipamentos/modelo_cadastro_equipamentos_lote.xlsx') ?>" download="Cadastro de Equipamentos em Lote.xlsx">Clique aqui</a> para baixar a planilha modelo.
                                            </li>
                                        </ul>
                                        </p>
                                        </p>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <img src="<?= versionFile('arq/cadastro', 'mdelo_planilha_equipamentos.png') ?>" alt="" class="img-responsive center-block" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR EQUIPAMENTO -->
<div id="editar_equipamento" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="serialModalEditar">Editar Equipamento</h3>
            </div>
            <div class="modal-body" style="padding: 0px !important;">
                <form name="formEditar" id="formEditar" enctype="multipart/form-data">
                    <h4 class="subtitle" style="margin-left: 5px">Dados para Edição:</h4>
                    <div class="form-group col-md-6">
                        <label for="linha1">Linha 1:</label><span class="text-danger">*</span></label>
                        <select class="form-control pesq_linhas" style="width:100%" id="linha1Edit" name="linha1" type="text" required></select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="linha2">Linha 2:</label><span class="text-danger">*</span></label>
                        <select class="form-control pesq_linhas" style="width:100%" id="linha2Edit" name="linha2" type="text" required></select>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-group" style="justify-content: flex-end; margin-top: 30px;">
                            <button type="submit" class="btn btn-success" id='btnEditarEqp'>Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var Router = '<?= site_url('equipamentos') ?>';
    var RouterLinhas = '<?= site_url('linhas/ajaxListSelect') ?>'
    var BaseURL = '<?= base_url('') ?>';
</script>

<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/equipamentos', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/equipamentos', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/equipamentos', 'equipamentos.js') ?>"></script>