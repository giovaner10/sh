<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= $titulo ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang("monitoramento") ?> >
        <?= $titulo ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" required />
                    </div>

                    <div class="input-container buscaData" id="dateContainer2">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" required/>
                    </div>
                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b style="margin-bottom: 5px;"><?= $titulo ?>: </b>
                <div class="btn-div-responsive">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="position: relative;">
                <div class="col-sm-4 metrica">
                    <div id="card-tickets-suporte" class="card metrica-card">
                        <h4 style="text-align: center;">Suporte</h4>
                        <div class="card-header">
                            <h2 class="number-indicator" id="tickets-suporte"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                        </div>
                        <p>Tickets</p>
                    </div>
                </div>
                <div class="col-sm-4 metrica">
                    <div id="card-tickets-comercial" class="card metrica-card">
                        <h4 style="text-align: center;">Comercial</h4>
                        <div class="card-header">
                            <h2 class="number-indicator" id="tickets-comercial"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                        </div>
                        <p>Tickets</p>
                    </div>
                </div>
                <div class="col-sm-4 metrica">
                    <div id="card-tickets-financeiro" class="card metrica-card">
                        <h4 style="text-align: center;">Financeiro</h4>
                        <div class="card-header">
                            <h2 class="number-indicator" id="tickets-financeiro"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                        </div>
                        <p>Tickets</p>
                    </div>
                </div>
                <div class="col-sm-4 metrica">
                    <div id="card-tickets-abertos" class="card metrica-card">
                        <h4 style="text-align: center;">Aberto</h4>
                        <div class="card-header">
                            <h2 class="number-indicator" id="tickets-abertos"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                        </div>
                        <p>Tickets</p>
                    </div>
                </div>
                <div class="col-sm-4 metrica">
                    <div id="card-tickets-andamento" class="card metrica-card">
                        <h4 style="text-align: center;">Andamento</h4>
                        <div class="card-header">
                            <h2 class="number-indicator" id="tickets-andamento"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                        </div>
                        <p>Tickets</p>
                    </div>
                </div>
                <div class="col-sm-4 metrica">
                    <div id="card-tickets-concluidos" class="card metrica-card">
                        <h4 style="text-align: center;">Concluído</h4>
                        <div class="card-header">
                            <h2 class="number-indicator" id="tickets-concluidos"> <i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
                        </div>
                        <p>Tickets</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="position: relative;">
                <div id="loadingMessageChartBar" class="loadingMessage">
                    <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                </div>
                <div class="chartContainer container" id="chartBarButton">
                    <div class="container chartHeader">
                        <charttitle>Quantitativo de Tickets por Status</charttitle>
                    </div>
                    <div id="clickableArea" style="width: 100%;height: 100%;position: absolute;z-index: 10;cursor: pointer;" class=""></div>
                    <div class="chartBody">
                        <div id="myChartBar"></div>
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
                <h3 class="modal-title">Quantitativo de Tickets por Status</h3>
            </div>
            <div class="modal-body" style="height: 500px;">
                <div class="col-md-12" style="height: 500px;">
                    <div id="myModalChartBar" style="height: 100%;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="downloadChart" type="button" class="btn btn-success">Baixar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/monitoramento', 'tickets_dash.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/monitoramento', 'TicketsDash.js') ?>"></script>

<script>
    var Router = '<?= site_url('monitoramento') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>