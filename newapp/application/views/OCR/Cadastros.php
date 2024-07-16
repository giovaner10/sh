<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("cadastros") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('ocr') ?> >
        <?= lang('cadastros') ?>
    </h4>
</div>

<?php
$menu_ocr = $_SESSION['cadastro_ocr'];
?>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link <?= $menu_ocr == 'AlertasEmail' ? 'selected' : '' ?>' id="menu-alertas-email"><?= lang("cadastro_alertas") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link <?= $menu_ocr == 'Blacklist' ? 'selected' : '' ?>' id="menu-blacklist"><?= lang("cadastro_blacklists") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link <?= $menu_ocr == 'Whitelist' ? 'selected' : '' ?>' id="menu-whitelist"><?= lang("cadastro_whitelists") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link <?= $menu_ocr == 'ProcessamentoLote' ? 'selected' : '' ?>' id="menu-processamento"><?= lang("processamento_lote") ?></a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container buscaData buscaCliente" style="<?= ($menu_ocr == 'AlertasEmail' || $menu_ocr == 'ProcessamentoLote') ? 'display:none;' : '' ?>">
                        <label for="placaBusca">Placa:</label>
                        <input type="text" name="placaBusca" class="form-control" placeholder="Digite a Placa" id="placaBusca" />
                    </div>

                    <div class="input-container buscaAlertasEmail" style="<?= ($menu_ocr == 'Whitelist' || $menu_ocr == 'DadosGerenciamentoOCR' || $menu_ocr == 'Blacklist' || $menu_ocr == 'ProcessamentoLote') ? 'display:none;' : '' ?>">
                        <label for="emailBusca">E-mail:</label>
                        <input type="email" name="emailBusca" class="form-control" placeholder="Digite o E-mail" id="emailBusca" />
                    </div>

                    <div class="input-container buscaCliente buscaAlertasEmail" style="<?= ($menu_ocr == 'DadosGerenciamentoOCR' || $menu_ocr == 'EventosPlacas' || $menu_ocr == 'ProcessamentoLote') ? 'display:none;' : '' ?>">
                        <label for="clienteBusca">Cliente:</label>
                        <select class="form-control" name="clienteBusca" id="clienteBusca" type="text" style="width: 100%;">
                        </select>
                    </div>

                    <div class="input-container buscaStatus" style="<?= ($menu_ocr == 'AlertasEmail' || $menu_ocr == 'ProcessamentoLote') ? 'display:none;' : '' ?>">
                        <label for="statusBusca">Status:</label>
                        <select class="form-control" name="statusBusca" id="statusBusca" style="width: 100%;">
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>

                    <div class="input-container buscaProcessamento" style="<?= $menu_ocr == 'ProcessamentoLote' ? '' : 'display:none;' ?>">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" />
                    </div>

                    <div class="input-container buscaProcessamento" style="<?= $menu_ocr == 'ProcessamentoLote' ? '' : 'display:none;' ?>">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" />
                    </div>

                    <div class="input-container buscaProcessamento" style="<?= $menu_ocr == 'ProcessamentoLote' ? '' : 'display:none;' ?>">
                        <label for="statusProcessamento">Status:</label>
                        <select class="form-control" name="statusProcessamento" id="statusProcessamento" style="width: 100%;">
                            <option value="0">Recebido</option>
                            <option value="1">Processado com Sucesso</option>
                            <option value="2">Falha</option>
                            <option value="3">Processado com Falhas</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>

                    <!-- <div class="button-container">
                        <button class="btn btn-primary" style='width:100%' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div> -->
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-alertas-email" style='margin-bottom: 20px; <?= $menu_ocr == 'AlertasEmail' ? '' : 'display: none;' ?>'>
            <h3>
                <b style="margin-bottom: 5px;"><?= lang("cadastro_alertas") ?>: </b>
                <div class="btn-div-responsive" id="btn-div-alertas">
                    <button class="btn btn-primary" id="BtnAdicionarAlertasEmail" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonAlertasEmail" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_alertas_email" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-alertas-email" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageAlertasEmail" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageAlertasEmail" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperAlertasEmail">
                    <div id="tableAlertasEmail" class="ag-theme-alpine my-grid-alertas-email" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-blacklist" style="margin-bottom: 20px; <?= $menu_ocr == 'Blacklist' ? '' : 'display: none;' ?>">
            <h3>
                <b style="margin-bottom: 5px;"><?= lang("cadastro_blacklists") ?>: </b>
                <div class="btn-div-responsive" id="btn-div-hot-list">
                    <button class="btn btn-primary" id="BtnAdicionarBlacklist" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonBlacklistImportacao" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('importar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-acoes opcoes_importacao" aria-labelledby="dropdownMenuButton" id="opcoes_importacao_blacklist" style="width: 180px; height: 100px;">    
                            <h5 class="dropdown-title"> O que você deseja fazer? </h5>
                            <div class="dropdown-item opcao_importacao" id="BtnImportarBlacklist">
                                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i> Adicionar em lote
                            </div>
                            <div class="dropdown-item opcao_importacao" id="BtnImportarBlacklistRemocao">
                                <i class="fa fa-minus" aria-hidden="true" style="margin-right: 5px; color: #FF0000;"></i> Remover em lote
                            </div>
                        </div>
                    </div>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonBlacklist" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_blacklist" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-blacklist" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageBlacklist" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperBlacklist">
                    <div id="tableBlacklist" class="ag-theme-alpine my-grid-blacklist" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-whitelist" style="margin-bottom: 20px; <?= $menu_ocr == 'Whitelist' ? '' : 'display: none;' ?>">
            <h3>
                <b style="margin-bottom: 5px;"><?= lang("cadastro_whitelists") ?>: </b>
                <div class="btn-div-responsive" id="btn-div-cold-list">
                    <button class="btn btn-primary" id="BtnAdicionarWhitelist" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonWhitelistImportacao" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('importar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right opcoes_importacao" aria-labelledby="dropdownMenuButton" id="opcoes_importacao_whitelist">
                            <h5 class="dropdown-title"> O que você deseja fazer? </h5>
                            <div class="dropdown-item opcao_importacao" id="BtnImportarWhitelist">
                                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i> Adicionar em lote
                            </div>
                            <div class="dropdown-item opcao_importacao" id="BtnImportarWhitelistRemocao">
                                <i class="fa fa-minus" aria-hidden="true" style="margin-right: 5px; color: #FF0000;"></i> Remover em lote
                            </div>
                        </div>
                    </div>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonWhitelist" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_whitelist" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-whitelist" class="form-control" style="width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageWhitelist" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperWhitelist">
                    <div id="tableWhitelist" class="ag-theme-alpine my-grid-whitelist" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-processamento" style='margin-bottom: 20px; <?= $menu_ocr == 'ProcessamentoLote' ? '' : 'display: none;' ?>'>
            <h3>
                <b style="margin-bottom: 5px; word-break: break-word;"><?= lang("processamento_lote") ?>: </b>
                <div class="btn-div-responsive" id="btn-div-processamento">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonProcessamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_processamento" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-processamento" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageProcessamento" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageProcessamento" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperProcessamento">
                    <div id="tableProcessamento" class="ag-theme-alpine my-grid-processamento" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="blacklist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" id='formBlacklist'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleBlacklist">Cadastrar Hot List</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle">Dados do Hot List</h4>
                        <input type="text" hidden name='idBlacklist' id="idBlacklist" value='0' />
                        <input class="form-control" name="usuarioBlacklist" id="usuarioBlacklist" type="hidden" style="width: 100%;" required />
                        <div class='row'>
                            <div class="col-md-6 input-container form-group divSeguradoraBlacklist" style="height: 59px !important;">
                                <label for="seguradoraBlacklist">Cliente: <span class="text-danger">*</span></label>
                                <select class="form-control" name="seguradoraBlacklist" id="seguradoraBlacklist" type="text" style="width: 100%;" required>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="placaBlacklist">Placa: <span class="text-danger">*</span></label>
                                <input type="text" name="placaBlacklist" class="form-control" placeholder="Digite a Placa" id="placaBlacklist" required maxlength="10" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="chassiBlacklist">Chassi: </label>
                                <input type="text" name="chassiBlacklist" class="form-control" placeholder="Digite o Chassi" id="chassiBlacklist" maxlength="45" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="ocorrenciaBlacklist">Tipo de Ocorrência: </label>
                                <select name="ocorrenciaBlacklist" class="form-control" type="text" style="width: 100%;" id="ocorrenciaBlacklist">
                                    <option value="0">Roubo</option>
                                    <option value="1">Furto</option>
                                    <option value="2">Apropriação Indébita</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="marcaBlacklist">Marca:</label>
                                <input type="text" name="marcaBlacklist" class="form-control" placeholder="Digite a Marca" id="marcaBlacklist" maxlength="45" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="modeloBlacklist">Modelo:</label>
                                <input type="text" name="modeloBlacklist" class="form-control" placeholder="Digite o Modelo" id="modeloBlacklist" maxlength="45" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="corBlacklist">Cor:</label>
                                <input type="text" name="corBlacklist" class="form-control" placeholder="Digite a Cor" id="corBlacklist" maxlength="45" />
                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-success" id='btnSalvarBlacklist'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="whitelist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" id='formWhitelist'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleWhitelist">Cadastrar Cold List</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle">Dados do Cold List</h4>
                        <input type="text" hidden name='idWhitelist' id="idWhitelist" value='0' />
                        <input class="form-control" name="usuarioWhitelist" id="usuarioWhitelist" type="hidden" style="width: 100%;" required />
                        <div class='row'>
                            <div class="col-md-6 input-container form-group divSeguradoraWhitelist" style="height: 59px !important;">
                                <label for="seguradoraWhitelist">Cliente: <span class="text-danger">*</span></label>
                                <select class="form-control" name="seguradoraWhitelist" id="seguradoraWhitelist" type="text" style="width: 100%;" required>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="placaWhitelist">Placa: <span class="text-danger">*</span></label>
                                <input type="text" name="placaWhitelist" class="form-control" placeholder="Digite a Placa" id="placaWhitelist" required maxlength="10" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="chassiWhitelist">Chassi: </label>
                                <input type="text" name="chassiWhitelist" class="form-control" placeholder="Digite o Chassi" id="chassiWhitelist" maxlength="45" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="ocorrenciaWhitelist">Tipo de Ocorrência: </label>
                                <select name="ocorrenciaWhitelist" class="form-control" type="text" style="width: 100%;" id="ocorrenciaWhitelist">
                                    <option value="0">Roubo</option>
                                    <option value="1">Furto</option>
                                    <option value="2">Apropriação Indébita</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="marcaWhitelist">Marca:</label>
                                <input type="text" name="marcaWhitelist" class="form-control" placeholder="Digite a Marca" id="marcaWhitelist" maxlength="45" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="modeloWhitelist">Modelo:</label>
                                <input type="text" name="modeloWhitelist" class="form-control" placeholder="Digite o Modelo" id="modeloWhitelist" maxlength="45" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="corWhitelist">Cor:</label>
                                <input type="text" name="corWhitelist" class="form-control" placeholder="Digite a Cor" id="corWhitelist" maxlength="45" />
                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-success" id='btnSalvarWhitelist'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="alertasEmail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div id="modalDivAlerta" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formAlertasEmail'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAlertasEmail">Cadastrar Alerta</h3>
                </div>
                <ul class="nav nav-tabs" style="margin-left: 5px">
                    <li class="nav-item active">
                        <a id="tab-dadosAlerta" href="" data-toggle="tab" class="nav-link tab-alertas active" type="button">Dados do Alerta</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-hotlist" href="" data-toggle="tab" class="nav-link tab-alertas" type="button">Hot List</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-coldlist" href="" data-toggle="tab" class="nav-link tab-alertas" type="button">Cold List</a>
                    </li>
                </ul>
                <div class="modal-body" style="padding: 0 15px 0px 15px;">
                    <div class="col-md-12 conteudo-tab" id="conteudoDadosAlerta">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados do Alerta</h4>
                        <input type="text" hidden name='idAlertasEmail' id="idAlertasEmail" value='0' />
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="seguradoraAlertasEmail">Cliente:</label>
                                <select class="form-control" name="seguradoraAlertasEmail" id="seguradoraAlertasEmail" type="text" style="width: 100%;">
                                </select>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="intregraCSSAlertasEmail">Integra CSS (CEABS):</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" name="intregraCSSAlertasEmail" id="intregraCSSAlertasEmail" required>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="notificaEmailAlertasEmail">Notifica E-mail:</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" name="notificaEmailAlertasEmail" id="notificaEmailAlertasEmail" required>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="notificaTelaAlertaAlertasEmail">Notifica Tela Aberta:</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" name="notificaTelaAlertaAlertasEmail" id="notificaTelaAlertaAlertasEmail" required>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="input-tag">E-mails:</label>
                                <br>
                                <div class="tags-input">
                                    <ul id="tags">
                                    </ul>
                                    <input type="email" name="input-tag" id="input-tag" placeholder="Digite um e-mail" maxlength="120" />
                                </div>
                            </div>
                        </div>

                        <hr>
                    </div>
                    <div id="conteudoHotList" class="conteudo-tab">
                        <div style="display:flex; justify-content: space-between; align-items: center;">
                            <h4 class="subtitle" style="margin-top: 10px;">Hot List</h4>
                            <button class="btn btn-primary" id="btnAssociarHotlist" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Associar</button>
                        </div>
                        <div class="wrapperAbaBlacklist">
                            <div id="tableAbaBlacklist" class="ag-theme-alpine my-grid-abaBlacklist">
                            </div>
                        </div>
                    </div>
                    <div id="conteudoColdList" class="conteudo-tab">
                        <div style="display:flex; justify-content: space-between; align-items: center;">
                            <h4 class="subtitle" style="margin-top: 10px;">Cold List</h4>
                            <button class="btn btn-primary" id="btnAssociarColdlist" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Associar</button>
                        </div>
                        <div class="wrapperAbaWhitelist">
                            <div id="tableAbaWhitelist" class="ag-theme-alpine my-grid-abaWhitelist">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="button" class="btn btn-success" id='btnSalvarAlertasEmail'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="associacaoBlacklist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleAssociacaoBlacklist">Solicitar associação de Hot List</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Listagem das Hot Lists</h4>
                    <input type="text" hidden name='idAlertasEmailBlacklist' id="idAlertasEmailBlacklist" value='0' />
                    <input type="text" hidden name='idClienteHotList' id="idClienteHotList" value='0' />
                    <input type="number" hidden name='removeModalBlackList' id="removeModalBlackList" value='0' min='0' max='1' />
                    <div class="col-md-12 input-container form-group associarHotList" style="padding-left: 0; padding-right: 0;">
                            <label for="associarHotList">Associar todas as placas:</label>
                            <br>
                            <label class="switch">
                            <input type="checkbox" name="associarHotList" id="associarHotList" required>
                            <span class="slider round"></span>
                            </label>
                    </div>
                    <div class='row'>
                        <div id="divPlacasBlacklists" class="col-md-12 input-container form-group">
                            <label id="labelPlacasBlacklists" for="placasBlacklists">Selecione as placas das hot lists a serem associadas:</label>
                            <select class="form-control" name="placasBlacklists" id="placasBlacklists" type="text" style="width: 100%;" required>
                            </select>
                        </div>
                        <div id="divPlacasBlacklistsEditar" class="col-md-12 input-container form-group">
                            <label id="labelPlacasBlacklistsEditar" for="placasBlacklistsEditar">Selecione a placa da hot list a ser associada:</label>
                            <select class="form-control" name="placasBlacklistsEditar" id="placasBlacklistsEditar" type="text" style="width: 100%;" required>
                            </select>
                        </div>
                    </div>

                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-success" id='btnSalvarAssociacaoBlacklist'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="associacaoWhitelist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleAssociacaoWhitelist">Solicitar associação de Cold List</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Listagem das Cold Lists</h4>
                    <input type="text" hidden name='idAlertasEmailWhitelist' id="idAlertasEmailWhitelist" value='0' />
                    <input type="text" hidden name='idCliente' id="idCliente" value='0' />
                    <input type="number" hidden name='removeModalWhitelist' id="removeModalWhitelist" value='0' min='0' max='1' />
                    <div class="col-md-12 input-container form-group associarColdList" style="padding-left: 0; padding-right: 0;">
                            <label for="associarColdList">Associar todas as placas:</label>
                            <br>
                            <label class="switch">
                            <input type="checkbox" name="associarColdList" id="associarColdList" required>
                            <span class="slider round"></span>
                            </label>
                    </div>
                    <div class='row'>
                        <div id="divPlacasWhitelists" class="col-md-12 input-container form-group">
                            <label id="labelPlacasWhitelists" for="placasWhitelists">Selecione as placas das cold lists a serem associadas:</label>
                            <select class="form-control" name="placasWhitelists" id="placasWhitelists" type="text" style="width: 100%;" required>
                            </select>
                        </div>
                        <div id="divPlacasWhitelistsEditar" class="col-md-12 input-container form-group">
                            <label id="labelPlacasWhitelistsEditar" for="placasWhitelistsEditar">Selecione a placa da cold list a ser desassociada:</label>
                            <select class="form-control" name="placasWhitelistsEditar" id="placasWhitelistsEditar" type="text" style="width: 100%;" required >
                            </select>
                        </div>
                    </div>

                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-success" id='btnSalvarAssociacaoWhitelist'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="importarBlacklist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Importar Hot List</h3>
            </div>
            <div class="modal-body">
                <h4 class="subtitle" style="padding: 0px 5px;">Dados Gerais</h4>
                <div class="col-md-12">
                    <div class="col-md-12 input-container form-group">
                        <label for="seguradoraImportacao">Cliente:</label>
                        <select class="form-control" name="seguradoraImportacao" id="seguradoraImportacao" type="text" style="width: 100%;" required>
                        </select>
                    </div>
                    <input class="form-control" name="usuarioImportacao" id="usuarioImportacao" type="hidden" style="width: 100%;" readonly required />
                </div>

                <hr>
                <h4 class="subtitle">Dados Importação</h4>
                <div class="col-md-12">
                    <div class="col-md-8 form-group" style="display: flex;">
                        <input class="form-control input-sm" name="arquivoItens" id="arquivoItens" type="file">
                        <div class="col-md-1 form-group" style="margin-top: 5px;">
                            <i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon" aria-hidden="true" title="Clique para saber mais"></i>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" style="margin-left: auto;">
                        <button class="btn btn-primary" onclick="importarItensExcel(event)" type="button" id="botao-adicionar-item-arquivo">Importar</button>
                        <button class="btn" id="limparTabelaItens" style="background-color: red;color: white">Limpar Tabela</button>
                    </div>
                </div>

                <div class="wrapperImportacaoBlacklist">
                    <div id="tableImportacaoBlacklist" class="ag-theme-alpine my-grid-blacklist">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-success" id='btnSalvarImportacaoBlacklist'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="importarBlacklistRemocao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Remoção de Hot List</h3>
            </div>
            <div class="modal-body">
                    <h4 class="subtitle" style="padding: 0px 5px;">Dados Gerais</h4>
                <div class="col-md-12">
                    <div class="col-md-12 input-container form-group">
                        <label for="seguradoraImportacaoBlacklistRemocao">Cliente:</label>
                        <select class="form-control" name="seguradoraImportacaoBlacklistRemocao" id="seguradoraImportacaoBlacklistRemocao" type="text" style="width: 100%;" required>
                        </select>
                    </div>
                    <input class="form-control" name="usuarioImportacaoBlacklistRemocao" id="usuarioImportacaoBlacklistRemocao" type="hidden" style="width: 100%;" readonly required/>
                </div>
                <hr>    
                <h4 class="subtitle">Dados Importação</h4>
                <div class="col-md-12">
                    <div class="col-md-8 form-group" style="display: flex;">
                        <input class="form-control input-sm" name="arquivoItensBlacklistRemocao" id="arquivoItensBlacklistRemocao" type="file">
                        <div class="col-md-1 form-group" style="margin-top: 5px;">
                            <i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon-blacklist-remocao" aria-hidden="true" title="Clique para saber mais"></i>
                        </div>
                    </div>
                    <div class="col-md-4 form-group"  style="margin-left: auto;">
                        <button class="btn btn-primary" onclick="importarItensExcelBlacklistRemocao(event)" type="button" id="botao-adicionar-item-arquivo-whitelist-remocao">Importar</button>
                        <button class="btn" id="limparTabelaItensBlacklistRemocao" style="background-color: red;color: white">Limpar Tabela</button>  
                    </div>
                </div>
                <div class="wrapperImportacaoBlacklistRemocao">
                    <div id="tableImportacaoBlacklistRemocao" class="ag-theme-alpine my-grid-blacklist">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-success" id='btnSalvarImportacaoBlacklistRemocao'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MODELO DOCUMENTO ITENS DE MOVIMENTO -->
<div id="modalModeloItens" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="header-modal">Modelo de documento <span id="tituloDetalhesDoContrato"></span></h3>
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
                                             <li><strong>Placa</strong> (obrigatória)</li>
                                             <li> <strong>Chassi</strong> (opcional)</li>
                                             <li> <em>Marca</em> (opcional)</li>
                                             <li> <em>Modelo</em> (opcional)</li>
                                             <li> <em>Cor</em> (opcional)</li>
                                             <li> <em>Tipo de Ocorrência</em> (opcional e aceita os valores Roubo, Furto e Apropriação Indébita, sem considerar capitalização e acentuação).</li>
                                            </ul>
                                            Formatos suportados: .xls e .xlsx.
                                        </p>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <img src="<?= base_url("uploads/ocr/modelo-exemplo.png") ?>" alt="" class="img-responsive center-block" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button id="btnBaixarModelo" class="btn btn-success" type="button" onclick="baixarModeloItens()">Baixar Modelo</button>
            </div>
        </div>
    </div>
</div>

<div id="importarWhitelist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Importar Cold List</h3>
            </div>
            <div class="modal-body">
                <h4 class="subtitle" style="padding: 0px 5px;">Dados Gerais</h4>
                <div class="col-md-12">
                    <div class="col-md-12 input-container form-group">
                        <label for="seguradoraImportacaoWhitelist">Cliente:</label>
                        <select class="form-control" name="seguradoraImportacaoWhitelist" id="seguradoraImportacaoWhitelist" type="text" style="width: 100%;" required>
                        </select>
                    </div>
                    <input class="form-control" name="usuarioImportacaoWhitelist" id="usuarioImportacaoWhitelist" type="hidden" style="width: 100%;" readonly required />
                </div>
                <hr>
                <h4 class="subtitle">Dados Importação</h4>
                <div class="col-md-12">
                    <div class="col-md-8 form-group" style="display: flex;">
                        <input class="form-control input-sm" name="arquivoItensWhitelist" id="arquivoItensWhitelist" type="file">
                        <div class="col-md-1 form-group" style="margin-top: 5px;">
                            <i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon-whitelist" aria-hidden="true" title="Clique para saber mais"></i>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" style="margin-left: auto;">
                        <button class="btn btn-primary" onclick="importarItensExcelWhitelist(event)" type="button" id="botao-adicionar-item-arquivo-whitelist">Importar</button>
                        <button class="btn" id="limparTabelaItensWhitelist" style="background-color: red;color: white">Limpar Tabela</button>
                    </div>
                </div>
                <div class="wrapperImportacaoWhitelist">
                    <div id="tableImportacaoWhitelist" class="ag-theme-alpine my-grid-whitelist">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-success" id='btnSalvarImportacaoWhitelist'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="importarWhitelistRemocao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Remoção de Cold List</h3>
            </div>
            <div class="modal-body">
                <h4 class="subtitle" style="padding: 0px 5px;">Dados Gerais</h4>
                <div class="col-md-12">
                    <div class="col-md-12 input-container form-group">
                        <label for="seguradoraImportacaoWhitelistRemocao">Cliente:</label>
                        <select class="form-control" name="seguradoraImportacaoWhitelistRemocao" id="seguradoraImportacaoWhitelistRemocao" type="text" style="width: 100%;" required>
                        </select>
                    </div>
                    <input class="form-control" name="usuarioImportacaoWhitelistRemocao" id="usuarioImportacaoWhitelistRemocao" type="hidden" style="width: 100%;" readonly required />
                </div>
                <hr>
                <h4 class="subtitle">Dados Importação</h4>
                <div class="col-md-12">
                    <div class="col-md-8 form-group" style="display: flex;">
                        <input class="form-control input-sm" name="arquivoItensWhitelistRemocao" id="arquivoItensWhitelistRemocao" type="file">
                        <div class="col-md-1 form-group" style="margin-top: 5px;">
                            <i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon-whitelist-remocao" aria-hidden="true" title="Clique para saber mais"></i>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" style="margin-left: auto;">
                        <button class="btn btn-primary" onclick="importarItensExcelWhitelistRemocao(event)" type="button" id="botao-adicionar-item-arquivo-whitelist-remocao">Importar</button>
                        <button class="btn" id="limparTabelaItensWhitelistRemocao" style="background-color: red;color: white">Limpar Tabela</button>
                    </div>
                </div>
                <div class="wrapperImportacaoWhitelistRemocao">
                    <div id="tableImportacaoWhitelistRemocao" class="ag-theme-alpine my-grid-whitelist">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-success" id='btnSalvarImportacaoWhitelistRemocao'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL MODELO DOCUMENTO ITENS DE REMOÇÂO -->
<div id="modalModeloItensRemocao" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="header-modal-remocao">Modelo de documento <span id="tituloDetalhesDoContratoRemocao"></span></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content" style="padding: 0px 20px">
                            <div id="div_identificacao_remocao">
                                <div class="row">
                                    <div class="col-md-12" style="border-left: 3px solid #03A9F4; padding-bottom: 0px; margin-right: 0px">
                                        <p class="text-justify">
                                            A planilha deve conter as seguintes colunas:
                                        <ul>
                                            <li><strong>Placa</strong> (obrigatória)</li>
                                            <li id="motivo-desc"> <strong>Motivo</strong> (obrigatória e aceita os valores Veículo Recuperado, Contrato Cancelado, Suspeita Resolvida, Ocorrência Cancelada, Item Indevido e Teste, sem considerar capitalização e acentuação)</li>
                                            <li> <em>Chassi</em> (opcional)</li>
                                            <li> <em>Marca</em> (opcional)</li>
                                            <li> <em>Modelo</em> (opcional)</li>
                                            <li> <em>Cor</em> (opcional)</li>
                                            <li> <em>Tipo de Ocorrência</em> (opcional e aceita os valores Roubo, Furto e Apropriação Indébita, sem considerar capitalização e acentuação).</li>
                                        </ul>
                                        Formatos suportados: .xls e .xlsx.
                                        </p>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <img src="<?= base_url("uploads/ocr/modelo-exemplo-remocao.png") ?>" alt="" class="img-responsive center-block" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button id="btnBaixarModelo" class="btn btn-success" type="button" onclick="baixarModeloItensRemocao()">Baixar Modelo</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RESPOSTA IMPORTAÇÃO -->
<div id="modal-resposta-importacao" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="header-resposta-importacao">Relatório da Importação</h3>
            </div>
            <div class="modal-body scrollModal">
                <h4 id="titulo-resposta" style="text-align: center; margin: 0; margin-bottom: 20px; font-size: 30px !important;"> 
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i> 
                    <!-- <i class="fa fa-times-circle-o" aria-hidden="true"></i> -->
                    Sucesso!
                </h4>
                <div class="row">
                    <div id="quantidades" class="col-sm-12" style="margin-bottom: 20px;">
                        <div class="col-sm-6" style="text-align: center; margin-bottom: 10px;">
                            <div id="qtd-sucesso" class="qtd-exibicao">Quantidade de sucesso: 0</div>
                        </div>
                        <div class="col-sm-6" style="text-align: center; margin-bottom: 10px;">
                            <div id="qtd-falha" class="qtd-exibicao">Quantidade de falhas: 0</div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <p id="mensagem-resposta" style="margin: 0; text-align: center">
                                Cold List atualizados com sucesso. No entanto, algumas falharam na atualização.
                            </p>
                        </div>
                    </div>
                    <div id="div-falhas" class="col-sm-12" style="padding: 0 30px;">
                        <hr style="margin: 20px 0 !important;">
                        <div style="border-left: 3px solid #1C69AD; overflow-y: auto; max-height: 300px;">
                            <p class="text-justify">
                                <h4 style="padding-left: 10px; font-size: 16px;">Detalhes das falhas: </h4>
                                <ul id="lista-falhas">
                                    <li> Status não pode ser null </li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <!-- <button id="btnBaixarModelo" class="btn btn-primary" type="button" onclick="baixarModeloItensRemocao()">Baixar Modelo</button> -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'Cadastros.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'AlertasEmail.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'Blacklist.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'Whitelist.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'ProcessamentoLote.js') ?>"></script>

<script>
    var Router = '<?= site_url('OCR/DadosGerenciamentoOCR') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>