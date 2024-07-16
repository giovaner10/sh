<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("configurador_omnisafe") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('configurador_omnisafe') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>


<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class="menu-interno-link" id="menu-omnisafe"><?= lang("cadastro_parametros") ?></a>
                </li>
                <!-- <li>
                    <a class="menu-interno-link" id="menu-power"><?= lang("configuracao_power") ?></a>
                </li> -->
                <li>
                    <a class="menu-interno-link" id="menu-historico-comandos"><?= lang("historico_de_comandos") ?></a>
                </li>
                <li>
                    <a class="menu-interno-link" id="menu-ultima-configuracao"><?= lang("historico_envio_configuracao") ?></a>
                </li>

            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container cliente">
                        <label for="clienteBusca">Cliente:</label>
                        <select class="form-control" name="clienteBusca" id="clienteBusca" type="text" style="width: 100%;">
                            <option value="" disabled selected>Buscando clientes...</option>
                        </select>
                    </div>

                    <div class="input-container nomePerfil">
                        <label for="perfilBusca">Nome Perfil:</label>
                        <input type="text" name="perfilBusca" class="form-control" placeholder="Digite o perfil" id="perfilBusca" />
                    </div>

                    <div class="input-container buscaData serial">
                        <label for="serialBusca">Serial:</label>
                        <input type="text" name="serialBusca" class="form-control" placeholder="Digite o serial" id="serialBusca" />
                    </div>

                    <div class="input-container statusEnvio">
                        <label for="statusEnvioBusca">Status do Envio:</label>
                        <select class="form-control" name="statusEnvioBusca" id="statusEnvioBusca" type="text" style="width: 100%;">
                            <option value="" disabled selected>Selecione o status</option>
                            <option value="0">Não Enviado</option>
                            <option value="1">Envio em Processo</option>
                            <option value="2">Enviado</option>
                            <option value="3">Falha ao Enviar</option>
                            <option value="4">Serial Inválido para Envio</option>
                        </select>
                    </div>

                    <div class="input-container statusRecebimento">
                        <label for="statusRecebimentoBusca">Status do Recebimento:</label>
                        <select class="form-control" name="statusRecebimentoBusca" id="statusRecebimentoBusca" type="text" style="width: 100%;">
                            <option value="" disabled selected>Selecione o status</option>
                            <option value="0">Não Recebido</option>
                            <option value="1">Verificando Recebimento</option>
                            <option value="2">Recebido</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-omnisafe" style='margin-bottom: 20px; <?= $menu_ocr == 'Omnisafe' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("cadastro_parametros") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; border: 1px solid transparent;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonPerfis" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_perfis" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="btnAdicionarPerfil" type="button" style="margin-right: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar Perfil</button>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-omnisafe" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageOmnisafe" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageOmnisafe" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperOmnisafe">
                    <div id="tableOmnisafe" class="ag-theme-alpine my-grid-omnisafe" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-power" style='margin-bottom: 20px; <?= $menu_ocr == 'Power' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("configuracao_power") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; border: 1px solid transparent;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonPowerConfig" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_power_config" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="btnAdicionarConfigPower" type="button" style="margin-right: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar Configuração</button>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-power" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessagePower" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessagePower" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperPower">
                    <div id="tablePower" class="ag-theme-alpine my-grid-power" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-historico-comandos" style='margin-bottom: 20px; <?= $menu_ocr == 'HistoricoComandos' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("historico_de_comandos") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; border: 1px solid transparent;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonHistoricoComandos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_historico_comandos" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="btnAdicionarHistoricoComando" type="button" style="margin-right: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> Enviar Comando</button>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-historico-comandos" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageHistoricoComandos" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="emptyMessageHistoricoComandos" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperHistoricoComandos">
                    <div id="tableHistoricoComandos" class="ag-theme-alpine my-grid-HistoricoComandos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-ultima-configuracao" style='margin-bottom: 20px; <?= $menu_ocr == 'UltimaConfiguracao' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("historico_envio_configuracao") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; border: 1px solid transparent;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonUltimaConfiguracao" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_ultima_configuracao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-ultima-configuracao" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageUltimaConfiguracao" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="emptyMessageUltimaConfiguracao" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperUltimaConfiguracao">
                    <div id="tableUltimaConfiguracao" class="ag-theme-alpine my-grid-UltimaConfiguracao" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="addPerfilOmnisafe" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" id='formAddPerfil'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleOmnisafe">Cadastrar Perfil</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <input type="hidden" name="idPerfil" id="idPerfil">
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="idCliente">Selecione o Cliente: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="idCliente" id="idCliente" placeholder="Digite o ID do Cliente" style="width: 100%;">
                                    <option value="">Selecione o Cliente</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="nomePerfil">Nome do Perfil: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="nomePerfil" id="nomePerfil" placeholder="Digite o nome do Perfil" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="serial">Selecione o Serial: </label>
                                <select class="form-control" type="text" name="serial" id="serial" placeholder="Selecione o serial" style="width: 100%;">
                                    <option value="">Selecione o serial</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id='btnSalvarPerfil'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addConfigPower" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formAddPower'>
                <input type="hidden" name="idPower" id="idPower">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titlePowerConfig">Cadastrar Configuração Power</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <!-- body power -->
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados do Power</h4>
                        <div class='row'>

                            <div class="col-md-6 input-container form-group cliente" style="height: 59px !important;">
                                <label for="idUsuario">Selecione o Cliente: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="idUsuario" id="idUsuario" placeholder="Digite o ID do Cliente" style="width: 100%;" required>
                                    <option value="">Selecione o Cliente</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="idCfgPerfil">Selecione o Perfil: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="idCfgPerfil" id="idCfgPerfil" placeholder="Digite o Perfil" style="width: 100%;" required>
                                    <option value="">Selecione o Perfil</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="serial">Selecione o Serial: </label>
                                <select class="form-control" type="text" name="serial" id="serialPower" placeholder="Digite o Serial" style="width: 100%;">
                                    <option value="">Selecione o Serial</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="switchValue">Switch: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" max="1" name="switchValue" id="switchValue" placeholder="Digite o Switch" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="delay">Delay: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="delay" id="delay" placeholder="Digite o Delay" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="reserveDelay">Reserve Delay: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="reserveDelay" id="reserveDelay" placeholder="Digite o Reserve Delay" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="hibernationReport">Hibernation Report: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" max="1" name="hibernationReport" id="hibernationReport" placeholder="Digite o Hibernation Report" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="screenOffTime">Screen Off Time: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="screenOffTime" id="screenOffTime" placeholder="Digite o Screen Off Time" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="powerOnTime">Power On Time: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="powerOnTime" id="powerOnTime" placeholder="Digite Power On Time" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="powerOffTime">Power Off Time: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="powerOffTime" id="powerOffTime" placeholder="Digite Power Off Time" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="accPowerOffRecEnable">Acc Power Off Rec Enable: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="accPowerOffRecEnable" id="accPowerOffRecEnable" placeholder="Digite Acc Power Off Rec Enable" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="accOffRecTime">Acc Off Rec Time: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="accOffRecTime" id="accOffRecTime" placeholder="Digite o Acc Off Rec Time" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="timeRebootEn">Time Reboot En: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" max="1" name="timeRebootEn" id="timeRebootEn" placeholder="Digite o Time Reboot En" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="rebootTime">Reboot Time: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="rebootTime" id="rebootTime" placeholder="Digite o Reboot Time" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="lowPowerOff">Low Power Off: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="lowPowerOff" id="lowPowerOff" placeholder="Digite o Low Power Off" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="littlePowerEnable">Little Power Enable: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" max="1" name="littlePowerEnable" id="littlePowerEnable" placeholder="Digite o Little Power Enable" required />
                            </div>


                        </div>
                        <hr>

                        <!-- body power schedule -->
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados do Power Schedule</h4>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="week0Time0">Time 0: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="week0Time0" id="week0Time0" placeholder="00:00-00:00" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="week0Time1">Time 1: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="week0Time1" id="week0Time1" placeholder="00:00-00:00" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="week0Time2">Time 2: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="week0Time2" id="week0Time2" placeholder="00:00-00:00" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="week0Time3">Time 3: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="week0Time3" id="week0Time3" placeholder="00:00-00:00" />
                            </div>
                            <div class="col-md-12 input-container form-group" style="display:flex; align-items:center; justify-content: space-between; height: 59px !important;">
                                <button type="button" class="btn btn-danger" id='btnLimparSchedule'>Limpar Tabela</button>
                                <button type="button" class="btn btn-primary" id='btnAddSchedule'>Adicionar Schedule</button>
                            </div>
                        </div>
                        <div class="wrapperItensSchedule">
                            <div id="tableItensSchedule" class="ag-theme-alpine my-grid-schedule" style="height: 500px">
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarAddPowerSchedule'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addComando" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" id='formAddComando'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleOmnisafe">Enviar Comando</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="idClienteComando">Selecione o Cliente: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="idCliente" id="idClienteComando" placeholder="Digite o ID do Cliente" style="width: 100%;" required>
                                    <option value="">Selecione o Cliente</option>
                                </select>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="idPerfilComando">Selecione o Perfil: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="idPerfil" id="idPerfilComando" placeholder="Digite o Perfil" style="width: 100%;" required>
                                    <option value="">Selecione o Perfil</option>
                                </select>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="serialComando">Selecione o Serial: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="serial" id="serialComando" placeholder="Selecione o serial" style="width: 100%;" required>
                                    <option value="">Selecione o serial</option>
                                    <option value="0" disabled>Todos os seriais selecionados</option>
                                </select>
                                <input style="margin-top: 10px;" type="checkbox" id="seriais" name="seriais">
                                <label style="padding-left: 5px !important;" for="seriais">Selecionar todos os seriais</label>
                            </div>
                            <div class="col-lg-6 input-container form-group tipoEquimento" style="height: 59px !important;">
                                <label for="tipoEquipamento">Tipo de Equipamento: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="tipoEquipamento" id="tipoEquipamento" placeholder="Selecione o equipamento" style="width: 100%;" required>
                                    <option value="" selected disabled>Selecione o equipamento</option>
                                    <option value="1">Omnisafe Plus 4CH</option>
                                    <option value="2">Omnisafe Plus 8CH</option>
                                    <option value="3">Omnisafe Dashcam</option>
                                </select>
                            </div>
                            <div class="col-lg-6 input-container form-group qtdCameras" style="height: 59px !important; margin-top: 25px;">
                                <label for="qtdCameras">Quantidade de Câmeras: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="qtdCameras" id="qtdCameras" placeholder="Selecione uma opção" style="width: 100%;" required>
                                    <option value="">Selecione uma opção</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarComando'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div id="xmlModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleOmnisafe">Comando Enviado</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class='row'>
                        <div id="xmlVisualizado">
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <svg  style="cursor: pointer; height: 30px; width: 30px;" title="Exportar para TXT" onclick="exportModalInfoToTxtFile()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.<path d="M64 464c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm56 256c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120z"/></svg> -->
                <!-- </div> -->
            <!-- </div>
        </div>
    </div>
</div> -->


<div id="modalComando" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleComando"></h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <div class="col-lg-6 input-container form-group numConfig" style="height: 59px !important; display:none;">
                                <label for="configuracaoComando">Configuração:</label>
                                <input class="form-control" id="configuracaoComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="serialComandoModal"> Serial:</label>
                                <input class="form-control" id="serialComandoModal" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="placaComando"> Placa:</label>
                                <input class="form-control" id="placaComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="clienteComando"> Cliente:</label>
                                <input class="form-control" id="clienteComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="perfilComando"> Perfil:</label>
                                <input class="form-control" id="perfilComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="dataEnvioComando"> Data de Envio:</label>
                                <input class="form-control" id="dataEnvioComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="statusEnvioComando"> Status do Envio:</label>
                                <input class="form-control" id="statusEnvioComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="dataRecebimentoComando"> Data de Recebimento:</label>
                                <input class="form-control" id="dataRecebimentoComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="statusRecebimentoComando"> Status do Recebimento:</label>
                                <input class="form-control" id="statusRecebimentoComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="dataCadastroComando"> Data de Cadastro:</label>
                                <input class="form-control" id="dataCadastroComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="dataAtualizacaoComando"> Data de Atualização:</label>
                                <input class="form-control" id="dataAtualizacaoComando" disabled>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="statusComando"> Status:</label>
                                <input class="form-control" id="statusComando" disabled>
                            </div>

                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var Router = '<?= site_url('Omnisafe/Omnisafe') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var idUsuarioLogado = '<?= $idUser; ?>'
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/omnisafe', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/omnisafe', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/omnisafe', 'Omnisafe.js') ?>"></script>