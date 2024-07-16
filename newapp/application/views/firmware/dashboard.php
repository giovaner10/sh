<div class="row">
    <div class="col-md-12">
        <div class="text-title">
            <h3 style="padding: 0;"><?= lang("firmware_dashboard") ?></h3>
            <h4 style="padding: 0;">
                <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
                <?= lang('firmware_lista') ?> >
                <?= lang('firmware_dashboard') ?>
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

        <div class="col-md-2 metrica" style="width: 20%;">
            <div id="card-seriais-atualizados" class="card metrica-card card-list">
                <div class="card-header">
                    <svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#1c69ad" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                    </svg>
                    <h2 class="number-indicator" id="seriais-atualizados"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                </div>
                <p>SERIAIS ATUALIZADOS</p>
            </div>
        </div>

        <div class="col-md-2 metrica" style="width: 20%;">
            <div id="card-seriais-desatualizados" class="card metrica-card card-list">
                <div class="card-header">
                    <svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#1c69ad" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
                    </svg>
                    <h2 class="number-indicator" id="seriais-desatualizados"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                </div>
                <p>SERIAIS DESATUALIZADOS</p>
            </div>
        </div>

        <div class="col-md-2 metrica" style="width: 20%;">
            <div id="card-versao-anterior" class="card metrica-card card-list">
                <div class="card-header">
                    <svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#1c69ad" d="M305.8 2.1C314.4 5.9 320 14.5 320 24V64h16c70.7 0 128 57.3 128 128V358.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3V192c0-35.3-28.7-64-64-64H320v40c0 9.5-5.6 18.1-14.2 21.9s-18.8 2.3-25.8-4.1l-80-72c-5.1-4.6-7.9-11-7.9-17.8s2.9-13.3 7.9-17.8l80-72c7-6.3 17.2-7.9 25.8-4.1zM104 80A24 24 0 1 0 56 80a24 24 0 1 0 48 0zm8 73.3V358.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3V153.3C19.7 141 0 112.8 0 80C0 35.8 35.8 0 80 0s80 35.8 80 80c0 32.8-19.7 61-48 73.3zM104 432a24 24 0 1 0 -48 0 24 24 0 1 0 48 0zm328 24a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
                    </svg>
                    <h2 class="number-indicator" id="versao-anterior"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                </div>
                <p>CLIENTES COM ATUALIZAÇÕES ANTERIORES A ATUAL</p>
            </div>
        </div>

        <div class="col-md-2 metrica" style="width: 20%;">
            <div id="card-atualizacao-desabilitada" class="card metrica-card card-list">
                <div class="card-header">
                    <svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#1c69ad" d="M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z" />
                    </svg>
                    <h2 class="number-indicator" id="atualizacao-desabilitada"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                </div>
                <p>CADASTRO DE EXCEÇÕES DE ATUALIZAÇÕES AUTOMÁTICAS DESABILITADAS</p>
            </div>
        </div>

        <div class="col-md-2 metrica" style="width: 20%;">
            <div id="card-dias-especifico" class="card metrica-card card-list">
                <div class="card-header">
                    <svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="#1c69ad" d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm80 64c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16h96c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80z" />
                    </svg>
                    <h2 class="number-indicator" id="dia-especifico"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                </div>
                <p>CADASTRO DE EXCEÇÕES DE ATUALIZAÇÃO EM DIAS ESPECÍFICOS</p>
            </div>
        </div>

        <div class="col-md-2 metrica" style="width: 20%;">
            <div id="card-hora-especifica" class="card metrica-card card-list">
                <div class="card-header">
                    <svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#1c69ad" d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                    </svg>
                    <h2 class="number-indicator" id="hora-especifica"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                </div>
                <p>CADASTRO DE EXCEÇÕES DE ATUALIZAÇÃO EM HORÁRIOS ESPECÍFICOS</p>
            </div>
        </div>

        <div class="col-md-2 metrica" style="width: 20%;">
            <div id="card-total-firmware" class="card metrica-card card-list">
                <div class="card-header">
                    <svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#1c69ad" d="M176 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64c-35.3 0-64 28.7-64 64H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H64v56H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H64v56H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H64c0 35.3 28.7 64 64 64v40c0 13.3 10.7 24 24 24s24-10.7 24-24V448h56v40c0 13.3 10.7 24 24 24s24-10.7 24-24V448h56v40c0 13.3 10.7 24 24 24s24-10.7 24-24V448c35.3 0 64-28.7 64-64h40c13.3 0 24-10.7 24-24s-10.7-24-24-24H448V280h40c13.3 0 24-10.7 24-24s-10.7-24-24-24H448V176h40c13.3 0 24-10.7 24-24s-10.7-24-24-24H448c0-35.3-28.7-64-64-64V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H280V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H176V24zM160 128H352c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H160c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32zm192 32H160V352H352V160z" />
                    </svg>
                    <h2 class="number-indicator" id="total-firmware"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                </div>
                <p>TOTAL DE FIRMWARES CADASTRADOS</p>
            </div>
        </div>


    </div>
</div>

<div class="row" style="margin-top: 10px; margin-bottom: 10px;">
    <div class="col-md-12" style="padding: 0 20px;">
        <div class="card" id="charts">
            <div style="display: flex; align-items: top; flex-wrap: wrap; margin-bottom: 5px;">
                <h4 id="charts-title" style="margin-right: auto;">FIRMWARES CADASTRADOS:</h4>
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
            </div>
            <div class="row">
                <div class="col-md-4 chart" id="chart-1">
                    <h4 style="color: #1C69AD !important;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="margin-left: 15px;">
                                <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                <span style="margin-right: 5px;">30 dias</span>
                            </div>
                            <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-1" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i>
                        </div>
                    </h4>
                    <div style="height: 300px;">
                        <div id="loadingMessage1" class="loadingMessage">
                            <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                        </div>
                        <div id="myDashBar1" class="ag-theme-alpine my-grid chart-div"></div>
                    </div>
                </div>
                <div class="col-md-4 chart" id="chart-2">
                    <h4 style="color: #1C69AD !important;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="margin-left: 15px;">
                                <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                <span style="margin-right: 5px;">60 dias</span>
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
                                <span style="margin-right: 5px;">90 dias</span>
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
                <hr>
                <h4 id="charts-title2" style="margin-left: 15px; margin-top: 5px;">FIRMWARES ATUALIZADOS:</h4>

                <div class="col-md-4 chart" id="chart-4">
                    <h4 style="color: #1C69AD !important;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="margin-left: 15px;">
                                <img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
                                <span style="margin-right: 5px;">7 dias</span>
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
                                <span style="margin-right: 5px;">15 dias</span>
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
                                <span style="margin-right: 5px;">30 dias</span>
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
                <div class="footer-group" style="justify-content: flex-end">
                    <button id="downloadChart" type="button" class="btn btn-success">Baixar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFirmwaresCadastrados" tabindex="-1" role="dialog" aria-labelledby="modalFirmwaresCadastradosPeriodo">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalFirmwaresCadastradosPeriodo">FIRMWARES CADASTRADOS</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalFirmware"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonModalFirmwaresCadastrados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonModalFirmwaresCadastrados" id="opcoes_exportacao_modal-firmwares" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperDadosFirmware">
                        <div id="tableFirmware" class="ag-theme-alpine my-grid-firmware">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRegraAtualizacoesDesabilitadas" tabindex="-1" role="dialog" aria-labelledby="modalRegraAtualizacoesDesabilitadas">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalRegraAtualizacoesDesabilitadas">Cadastro de Exceções de Atualizações Automáticas Desabilitadas</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalAtualizacoesDesabilitadas"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonRegraAtualizacoesDesabilitadas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonRegraAtualizacoesDesabilitadas" id="opcoes_exportacao_atualizacoes_desabilitadas" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperDadosAtualizacoesDesabilitadas">
                        <div id="tableAtualizacoesDesabilitadas" class="ag-theme-alpine my-grid-atualizacoes-desabilitadas">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalRegraDiasEspecificos" tabindex="-1" role="dialog" aria-labelledby="modalRegraDiasEspecificos">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalRegraDiasEspecificos">Cadastro de Exceções de Atualização em Dias Específicos</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalRegraDiasEspecificos"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonRegraAtualizacoesDia" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonRegraAtualizacoesDia" id="opcoes_exportacao_atualizacoes_dia" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperRegraDiasEspecificos">
                        <div id="tableRegraDiasEspecificos" class="ag-theme-alpine my-grid-dias-especificos">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRegraHorarioEspecifico" tabindex="-1" role="dialog" aria-labelledby="modalRegraHorarioEspecifico">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalRegraHorarioEspecifico">Cadastro de Exceções de Atualização em Horários Específicos</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalRegraHorarioEspecifico"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonRegraAtualizacoesHora" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonRegraAtualizacoesHora" id="opcoes_exportacao_atualizacoes_hora" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperRegraHorarioEspecifico">
                        <div id="tableRegraHorarioEspecifico" class="ag-theme-alpine my-grid-horario-especifico">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTotalFirmwaresCadastrados" tabindex="-1" role="dialog" aria-labelledby="modalTotalFirmwaresCadastrados">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalTotalFirmwaresCadastrados">Firmwares Cadastrados</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalTotalFirmwaresCadastrados"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonTotalFirmwaresCadastrados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonTotalFirmwaresCadastrados" id="opcoes_exportacao_total-firmwares" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperTotalFirmwaresCadastrados">
                        <div id="tableTotalFirmwaresCadastrados" class="ag-theme-alpine my-grid-total-firmwares-cadastrados">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSeriaisAtualizados" tabindex="-1" role="dialog" aria-labelledby="modalSeriaisAtualizados" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalSeriaisAtualizados">Seriais Atualizados</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalSeriaisAtualizados"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <div style="margin-right: 20px; display:flex;">
                        <input style="margin-right: 10px;" class="form-control input" type="date" id="search-input-date">
                        <input class="form-control input" type="text" id="search-input-serial" placeholder="Serial">
                    </div>
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonseriaisAtualizados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonseriaisAtualizados" id="opcoes_exportacao_seriais_atualizados" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperSeriaisAtualizados">
                        <div id="tableSeriaisAtualizados" class="ag-theme-alpine my-grid-seriais-atualizados">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalClientesSemAtualizacao" tabindex="-1" role="dialog" aria-labelledby="modalClientesSemAtualizacao" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalClientesSemAtualizacao">Clientes com Atualizações Anterior a Atual</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalClientesDesatualizados"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonClientesDesatualizados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonClientesDesatualizados" id="opcoes_exportacao_clientes_desatualizados" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperClientesDesatualizados">
                        <div id="tableClientesDesatualizados" class="ag-theme-alpine my-grid-clientes-desatualizados">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSeriaisDesatualizados" tabindex="-1" role="dialog" aria-labelledby="modalSeriaisDesatualizados" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalSeriaisDesatualizados">Seriais Desatualizados</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="tituloModalSeriaisDesatualizados"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px;  margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonSeriaisDesatualizados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonSeriaisDesatualizados" id="opcoes_exportacao_seriais_desatualizados" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperSeriaisDesatualizados">
                        <div id="tableSeriaisDesatualizados" class="ag-theme-alpine my-grid-seriais-desatualizados">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFirmwaresAtualizados" tabindex="-1" role="dialog" aria-labelledby="modalFirmwaresAtualizadosPeriodo">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalFirmwaresAtualizadosPeriodo">FIRMWARES CADASTRADOS</h4>
            </div>
            <div class="modal-body" style="margin-top: 20px">
                <h4 class="modal-title" id="modalFirmwaresAtualizadosPeriodo"></h4>
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end; margin-right: 10px; margin-bottom: 15px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonModalFirmwaresAtualizados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                    </button>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonModalFirmwaresAtualizados" id="opcoes_exportacao_modal-atualizados" style="min-width: 100px; top: 77px; right: 23px; height: 91px;">
                    </div>
                </div>
                <div>
                    <div class="wrapperDadosFirmwareAtualizados">
                        <div id="tableFirmwareAtualizados" class="ag-theme-alpine my-grid-firmware-atualizados">
                        </div>
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
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/firmware', 'dashboard.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/firmware', 'dashboard.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/firmware', 'Exportacoes.js') ?>"></script>


<script>
    var Router = '<?= site_url('Firmware/Firmware') ?>';
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