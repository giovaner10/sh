<div style="margin-left: 15px;">
    <div class="row">
        <div class="col-md-12">
            <div class="text-title">
                <h3 style="padding: 0;"><?= lang("dashboard") ?></h3>
                <h4 style="padding: 0;">
                    <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
                    <?= lang('ocr') ?> >
                    <?= lang('dashboard') ?>
                </h4>
                <hr>
            </div>
        </div>
    </div>

    <div id="loading">
        <div class="loader"></div>
    </div>

    <div class="row metrica-container">
        <div class="col-md-12">
            <div class="col-sm-4 metrica">
                <div id="card-veiculos-monitorados" class="card metrica-card">
                    <div class="card-header">
                        <img src="<?= base_url('assets/images/icon-shield-car.png') ?>" class="metrica-img" />
                        <h2 class="number-indicator" id="veiculos-monitorados"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                    </div>
                    <p>TOTAL DE VEÍCULOS MONITORADOS</p>
                </div>
            </div>
            <div class="col-sm-4 metrica">
                <div id="card-placas-alertas-mensal" class="card metrica-card">
                    <div class="card-header">
                        <img src="<?= base_url('assets/images/icon-plate.png') ?>" class="metrica-img" />
                        <h2 class="number-indicator" id="placas-lidas"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                    </div>
                    <p>TOTAL DE PLACAS LIDAS DO MÊS</p>
                </div>
            </div>
            <div class="col-sm-4 metrica">
                <div id="card-placas-alertas" class="card metrica-card">
                    <div class="card-header">
                        <img src="<?= base_url('assets/images/icon-plate.png') ?>" class="metrica-img" />
                        <h2 class="number-indicator" id="placas-alertas"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                    </div>
                    <p>TOTAL DE PLACAS COM ALERTAS</p>
                </div>
            </div>
            <div class="col-sm-4 metrica">
                <div id="card-hot-list" class="card metrica-card">
                    <div class="card-header">
                        <img src="<?= base_url('assets/images/icon-plate.png') ?>" class="metrica-img" />
                        <h2 class="number-indicator" id="eventos-hot-list"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                    </div>
                    <p>VEÍCULOS COM EVENTOS - HOT LIST</p>
                </div>
            </div>
            <div class="col-sm-4 metrica">
                <div id="card-cold-list" class="card metrica-card">
                    <div class="card-header">
                        <img src="<?= base_url('assets/images/icon-plate.png') ?>" class="metrica-img" />
                        <h2 class="number-indicator" id="eventos-cold-list"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                    </div>
                    <p>VEÍCULOS COM EVENTOS - COLD LIST</p>
                </div>
            </div>
            <div class="col-sm-4 metrica" style="display: none;">
                <div class="card metrica-card">
                    <div class="card-header">
                        <img src="<?= base_url('assets/images/icon-car-money.png') ?>" class="metrica-img" />
                        <h2 class="number-indicator" id="veículos-recuperados"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                    </div>
                    <p>TOTAL DE VEÍCULOS RECUPERADOS</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
        <div class="col-md-12" style="padding: 0 20px;">
            <div class="card" id="charts">
                <div style="display: flex; align-items: top; flex-wrap: wrap; margin-bottom: 5px;">
                    <h4 id="charts-title" style="margin-right: auto;">Veículos com alertas frequentes</h4>
                    <div style="display: flex; align-items: top; flex-wrap: wrap; margin-bottom: 5px; margin-top: 17px;">
                        <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                            <button style="display: flex !important; align-items: center;" class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButtonAtualizar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                                <svg style="margin-right: 5px;" heigth="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="#f5f5f5" d="M463.5 224H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1c-87.5 87.5-87.5 229.3 0 316.8s229.3 87.5 316.8 0c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0c-62.5 62.5-163.8 62.5-226.3 0s-62.5-163.8 0-226.3c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5z" />
                                </svg> Atualizar <span class="caret" style="margin-left: 5px;"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-acoes opcoes_importacao dropdown-menu-atualizacao" aria-labelledby="dropdownMenuButtonAtualizar" id="opcoes_atualizar" style="width: 180px; height: 100px;">
                                <h5 class="dropdown-title"> Atualização automática </h5>
                                <div class="dropdown-item opcao_importacao" id="10seg">
                                    A cada 10 Segundos
                                </div>
                                <div class="dropdown-item opcao_importacao" id="60seg">
                                    A cada 60 Segundos
                                </div>
                                <div class="dropdown-item opcao_importacao" id="180seg">
                                    A cada 180 Segundos
                                </div>
                                <div class="dropdown-item opcao_importacao" id="stopInterval">
                                    Desativar
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <label for="tipoEvento" style="margin: 0 10px 0 0; color: #7F7F7F !important; font-size: 12px;">Filtrar por: </label>
                        <div style="display: flex; align-items: center;">
                            <select id="tipoEvento" name="tipoEvento" class="form-control" style="width: auto; height: 34px;">
                                <option value="1" selected>Hot List</option>
                                <option value="2">Cold List</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 chart" id="chart-1">
                        <h4 style="color: #1C69AD !important;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="margin-left: 15px;">
                                    <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                    <span style="margin-right: 5px;">5 dias</span>
                                </div>
                                <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-1" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i>
                            </div>
                        </h4>
                        <div style="height: 300px;">
                            <div id="loadingMessage1" class="loadingMessage">
                                <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                            </div>
                            <!-- Coloque o gráfico aqui -->
                            <div id="myDashBar1" class="ag-theme-alpine my-grid chart-div"></div>
                        </div>
                    </div>
                    <div class="col-md-4 chart" id="chart-2">
                        <h4 style="color: #1C69AD !important;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="margin-left: 15px;">
                                    <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                    <span style="margin-right: 5px;">7 dias</span>
                                </div>
                                <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-2" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i>
                            </div>
                        </h4>
                        <div style="position: relative; height: 300px;">
                            <div id="loadingMessage2" class="loadingMessage">
                                <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                            </div>
                            <div id="myDashBar2" class="ag-theme-alpine my-grid chart-div"></div>
                        </div>
                    </div>
                    <div class="col-md-4 chart" id="chart-3">
                        <h4 style="color: #1C69AD !important;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="margin-left: 15px;">
                                    <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                    <span style="margin-right: 5px;">15 dias</span>
                                </div>
                                <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-3" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i>
                            </div>
                        </h4>
                        <div style="position: relative; height: 300px;">
                            <div id="loadingMessage3" class="loadingMessage">
                                <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                            </div>
                            <div id="myDashBar3" class="ag-theme-alpine my-grid chart-div"></div>
                        </div>
                    </div>
                    <div class="col-md-4 chart" id="chart-4">
                        <h4 style="color: #1C69AD !important;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="margin-left: 15px;">
                                    <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                    <span style="margin-right: 5px;">20 dias</span>
                                </div>
                                <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-4" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i>
                            </div>
                        </h4>
                        <div style="position: relative; height: 300px;">
                            <div id="loadingMessage4" class="loadingMessage">
                                <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                            </div>
                            <div id="myDashBar4" class="ag-theme-alpine my-grid chart-div"></div>
                        </div>
                    </div>
                    <div class="col-md-4 chart" id="chart-5">
                        <h4 style="color: #1C69AD !important;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="margin-left: 15px;">
                                    <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                    <span style="margin-right: 5px;">30 dias</span>
                                </div>
                                <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-5" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i>
                            </div>
                        </h4>
                        <div style="position: relative; height: 300px;">
                            <div id="loadingMessage5" class="loadingMessage">
                                <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                            </div>
                            <div id="myDashBar5" class="ag-theme-alpine my-grid chart-div"></div>
                        </div>
                    </div>
                    <div class="col-md-4 chart" id="chart-6">
                        <h4 style="color: #1C69AD !important;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="margin-left: 15px;">
                                    <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                    <span style="margin-right: 5px;">60 dias</span>
                                </div>
                                <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-6" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i>
                            </div>
                        </h4>
                        <div style="position: relative; height: 300px;">
                            <div id="loadingMessage6" class="loadingMessage">
                                <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                            </div>
                            <div id="myDashBar6" class="ag-theme-alpine my-grid chart-div"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="chartModalLabel">Gráfico</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12" style="height: 500px;">
                    <div id="myDashBarModal" class="ag-theme-alpine" style="height: 100%;"></div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button id="downloadChart" type="button" class="btn btn-success">Baixar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEventosHotList" tabindex="-1" role="dialog" aria-labelledby="modalEventosHotListLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalEventosHotListLabel">Veículos com Eventos - Hot List</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="col-md-4" style="padding: 0 5px; margin-bottom: 5px;">
                        <input data-toggle="popover" placement="bottom" data-content="Digite pelo menos 3 caracteres para pesquisar." autocomplete="off" type="text" class="form-control" id="search-input-hot-list" minlength="3" placeholder="Digite uma placa" style="width: 100%; height: 30px; margin-bottom: 5px;">
                        <div class="wrapper-eventos-hot-list" style="position: relative;">
                            <div id="loadingMessageEventosHotList" class="loadingMessage" style="display: none;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                            </div>
                            <div id="eventos-hot-list-grid" class="ag-theme-alpine" style="height: 565px;"></div>
                        </div>
                    </div>
                    <div class="col-md-8" style="padding: 0 5px; margin-bottom: 5px;">
                        <div id="loadingMessageMapaEventosHotList" class="loadingMessage" style="display: none;">
                            <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                        </div>
                        <div id="EmptyMessageMapaEventosHotList" class="loadingMessage" style="display: none;">
                            Clique em um registro para visualizar os eventos
                        </div>
                        <div id="mapEventosHotList" style="width:100%; height:600px; border-radius: 5px; z-index: 1;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEventosColdList" tabindex="-1" role="dialog" aria-labelledby="modalEventosColdListLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalEventosColdListLabel">Veículos com Eventos - Cold List</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="col-md-4" style="padding: 0 5px; margin-bottom: 5px;">
                        <input data-toggle="popover" placement="bottom" data-content="Digite pelo menos 3 caracteres para pesquisar." autocomplete="off" type="text" class="form-control" id="search-input-cold-list" placeholder="Digite uma placa" style="width: 100%; height: 30px; margin-bottom: 5px;">
                        <div class="wrapper-eventos-cold-list" style="position: relative;">
                            <div id="loadingMessageEventosColdList" class="loadingMessage" style="display: none;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                            </div>
                            <div id="eventos-cold-list-grid" class="ag-theme-alpine" style="height: 565px;"></div>
                        </div>
                    </div>
                    <div class="col-md-8" style="padding: 0 5px; margin-bottom: 5px;">
                        <div id="loadingMessageMapaEventosColdList" class="loadingMessage" style="display: none;">
                            <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                        </div>
                        <div id="EmptyMessageMapaEventosColdList" class="loadingMessage" style="display: none;">
                            Clique em um registro para visualizar os eventos
                        </div>
                        <div id="mapEventosColdList" style="width:100%; height:600px; border-radius: 5px; z-index: 1;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVeiculosMonitorados" tabindex="-1" role="dialog" aria-labelledby="modalVeiculosMonitoradosLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalVeiculosMonitoradosLabel">Veículos Monitorados</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="col-md-4" style="padding: 0 5px; margin-bottom: 5px;">
                        <input data-toggle="popover" placement="bottom" data-content="Digite pelo menos 3 caracteres para pesquisar." autocomplete="off" type="text" class="form-control" id="search-input-veiculos-monitorados" minlength="3" placeholder="Digite uma placa" style="width: 100%; height: 30px; margin-bottom: 5px;">
                        <div class="wrapper-eventos-veiculos-monitorados" style="position: relative;">
                            <div id="loadingMessageVeiculosMonitorados" class="loadingMessage" style="display: none;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                            </div>
                            <div id="veiculos-monitorados-grid" class="ag-theme-alpine" style="height: 565px;"></div>
                        </div>
                    </div>
                    <div class="col-md-8" style="padding: 0 5px; margin-bottom: 5px;">
                        <div id="loadingMessageMapaVeiculosMonitorados" class="loadingMessage" style="display: none;">
                            <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                        </div>
                        <div id="EmptyMessageMapaVeiculosMonitorados" class="loadingMessage" style="display: none;">
                            Clique em um registro para visualizar os eventos
                        </div>
                        <div id="mapVeiculosMonitorados" style="width:100%; height:600px; border-radius: 15px; z-index: 1;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEventosPlacasAlertas" tabindex="-1" role="dialog" aria-labelledby="modalEventosPlacasAlertasLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalEventosPlacasAlertasLabel">Placas com Alertas</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="col-md-4" style="padding: 0 5px; margin-bottom: 5px;">
                        <input data-toggle="popover" placement="bottom" data-content="Digite pelo menos 3 caracteres para pesquisar." autocomplete="off" type="text" class="form-control" id="search-input-placas-alertas" placeholder="Digite uma placa" style="width: 100%; height: 30px; margin-bottom: 5px;">
                        <div class="wrapper-eventos-placas-alertas" style="position: relative;">
                            <div id="loadingMessagePlacasAlertas" class="loadingMessage" style="display: none;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                            </div>
                            <div id="placas-alertas-grid" class="ag-theme-alpine" style="height: 565px;"></div>
                        </div>
                    </div>
                    <div class="col-md-8" style="padding: 0 5px; margin-bottom: 5px;">
                        <div id="loadingMessageMapaPlacasAlertas" class="loadingMessage" style="display: none;">
                            <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                        </div>
                        <div id="EmptyMessageMapaPlacasAlertas" class="loadingMessage" style="display: none;">
                            Clique em um registro para visualizar os eventos
                        </div>
                        <div id="mapEventosPlacasAlertas" style="width:100%; height:600px; border-radius: 15px; z-index: 1;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEventosPlacasAlertasMensal" tabindex="-1" role="dialog" aria-labelledby="modalEventosPlacasAlertasMensalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalEventosPlacasAlertasMensalLabel">Placas Lidas do Mês</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="col-md-4" style="padding: 0 5px; margin-bottom: 5px;">
                        <input data-toggle="popover" placement="bottom" data-content="Digite pelo menos 3 caracteres para pesquisar." autocomplete="off" type="text" class="form-control" id="search-input-placas-alertas-mensal" placeholder="Digite uma placa" style="width: 100%; height: 30px; margin-bottom: 5px;">
                        <div class="wrapper-eventos-placas-alertas-mensal" style="position: relative;">
                            <div id="loadingMessagePlacasAlertasMensal" class="loadingMessage" style="display: none;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                            </div>
                            <div id="placas-alertas-grid-mensal" class="ag-theme-alpine" style="height: 565px;"></div>
                        </div>
                    </div>
                    <div class="col-md-8" style="padding: 0 5px; margin-bottom: 5px;">
                        <div id="loadingMessageMapaPlacasAlertasMensal" class="loadingMessage" style="display: none;">
                            <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                        </div>
                        <div id="EmptyMessageMapaPlacasAlertasMensal" class="loadingMessage" style="display: none;">
                            Clique em um registro para visualizar os eventos
                        </div>
                        <div id="mapEventosPlacasAlertasMensal" style="width:100%; height:600px; border-radius: 15px; z-index: 1;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModalDetails" tabindex="-1" role="dialog" aria-labelledby="chartModalDetailsLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header my-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="chartModalDetailsLabel">Gráfico</h4>
                <h5 class="modal-subtitle" style="margin: 0; margin-right: 30px;">Placa | Serial</h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="div-img-evento" class="div-img">
                                <div id="loadingMessageImg" class="loadingMessage" style="border: 1px solid #ddd;">
                                    <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                                </div>
                                <div id="emptyMessageImg" class="loadingMessage" style="border: 1px solid #ddd;">
                                    Não há imagem para ser exibida.
                                </div>
                                <img src="<?= base_url('assets/css/icon/png/512/image.png') ?>" alt="Imagem do Veículo Rastreado" style="width: 100%; opacity: 0; border-radius: 10px; height: 200px;" id="img-evento">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div id="div-img-evento-placa" class="div-img">
                                <div id="loadingMessageImgPlaca" class="loadingMessage" style="border: 1px solid #ddd;">
                                    <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                                </div>
                                <div id="emptyMessageImgPlaca" class="loadingMessage" style="border: 1px solid #ddd;">
                                    Não há imagem para ser exibida.
                                </div>
                                <img src="<?= base_url('assets/css/icon/png/512/image.png') ?>" alt="Imagem da Placa Lida" style="width: 50%; opacity: 0; border-radius: 10px; height: 90px;" id="img-evento-placa">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-group event-list" style="width: 100%;">
                                <li class="list-group-item event-item">
                                    <span class="item-popup-title evento-item-title">Data Inicial:</span>
                                    <span id="file_s_time" class="float-right" style="color:#909090"><i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;"></i></span>
                                </li>
                                <li class="list-group-item event-item">
                                    <span class="item-popup-title evento-item-title">Data Final:</span>
                                    <span id="file_e_time" class="float-right" style="color:#909090"><i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;"></i></span>
                                </li>
                                <li class="list-group-item event-item">
                                    <span class="item-popup-title evento-item-title">Marca:</span>
                                    <span id="marca-evento" class="float-right" style="color:#909090"><i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;"></i></span>
                                </li>
                                <li class="list-group-item event-item">
                                    <span class="item-popup-title evento-item-title">Modelo:</span>
                                    <span id="modelo-evento" class="float-right" style="color:#909090"><i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;"></i></span>
                                </li>
                                <li class="list-group-item event-item">
                                    <span class="item-popup-title evento-item-title">Coordenadas:</span>
                                    <div class="float-right" style="float: right;">
                                        <span id="lat-evento" class="badge badge-info"><i class="fa fa-spinner fa-spin" style="font-size: 12px; color: white;"></i></span>
                                        <span id="long-evento" class="badge badge-info"><i class="fa fa-spinner fa-spin" style="font-size: 12px; color: white;"></i></span>
                                    </div>
                                </li>
                                <li class="list-group-item event-item">
                                    <span class="item-popup-title evento-item-title">Endereço:</span>
                                    <span id="endereco-evento" class="float-right" style="color:#909090"><i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;"></i></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-12">
                            <div id="loadingMessageMapaEventosPlaca" class="loadingMessage" style="display: none;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
                            </div>
                            <div id="mapDetalhesPlacaEvento" style="width:100%; height:200px; border-radius: 10px; z-index: 1; margin-bottom: 10px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 300px;">
                    <div class="wrapperEventos">
                        <div id="chart-evento-grid" class="ag-theme-alpine" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- AG Charts Community edition. -->
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>
<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- JavaScript -->
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'dashboard.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/OCR/Dashboard', 'Dashboard.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR/Dashboard', 'EventosHotList.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR/Dashboard', 'EventosColdList.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR/Dashboard', 'VeiculosMonitorados.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR/Dashboard', 'PlacasAlertas.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR/Dashboard', 'PlacasAlertasMensal.js') ?>"></script>

<script>
    var Router = '<?= site_url('OCR/Dashboard') ?>';
    var BaseURL = '<?= base_url('') ?>';

    function ajustarAltura() {
        $(".metrica-card").height('auto');
        var heights = [];
        $(".metrica-card").each(function() {
            heights.push($(this).height());
        });
        var maxHeight = Math.max.apply(null, heights);
        $(".metrica-card").height(maxHeight);
    }

    $(document).ready(function() {

        ajustarAltura();

        $(window).resize(function() {
            ajustarAltura();
        });
    });
</script>