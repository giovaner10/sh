<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("relatorio_manutencao") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('auditoria') ?> >
        <?= lang('auditoria_agendamento') ?> >
        <?= lang('relatorio_manutencao') ?>
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
                    <a class='menu-interno-link selected' id="menu-relatorio-manutencao"><?= lang("relatorio_manutencao") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link' id="menu-dashboard-manutencao">Dashboard de Manutenção</a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaTecnico" id="dateContainer1">
                        <label for="tecnicoBusca">Técnico: <span class="text-danger">*</span></label>
                        <select type="date" name="tecnicoBusca" class="form-control" id="tecnicoBusca">
                            <option value="" disabled selected>Buscando técnicos...</option>
                        </select>
                    </div>

                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="dataInicial">Data Inicial: <span class="text-danger">*</span></label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Iní­cio" autocomplete="off" id="dataInicial" />
                    </div>

                    <div class="input-container buscaData" id="dateContainer2">
                        <label for="dataFinal">Data Final: <span class="text-danger">*</span></label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn btn-success search-button" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default clear-search-button" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b style="margin-bottom: 5px;"><?= $titulo ?>: </b>
                <div class="btn-div-responsive">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 62px; padding: 0 !important;">
                            <div class="dropdown-item opcao_exportacao" data-tipo="xlsx" style="border: 1px solid #ccc;">
                                <img src="<?php echo base_url('/media/img/new_icons/excel.png') ?>" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato Excel">
                                <label style="cursor: pointer; margin-top: 7px; color: #1C69AD !important; font-family: 'Mont SemiBold';" title="Exportar no formato Excel">Excel</label>
                            </div>

                            <div class="dropdown-item opcao_exportacao" data-tipo="pdf" style="border: 1px solid #ccc;">
                                <img src="<?php echo base_url('/media/img/new_icons/pdf.png') ?>" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato PDF">
                                <label style="cursor: pointer; margin-top: 7px; color: #1C69AD !important; font-family: 'Mont SemiBold';" title="Exportar no formato PDF">PDF</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
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
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 530px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-dashboard" style='margin-bottom: 20px; position: relative; display: none;'>
            <h3>
                <b>Dashboard de Manutenção: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>

            <div class="chartPlotContainer" id="chartContainer" style="flex-wrap: wrap;">

                <div class="chartContainer container" style="position: relative;" id="chartBarButton">
                    <div id="loadingMessageChartBar" class="loadingMessage">
                        <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                    </div>
                    <div class="container chartHeader">
                        <charttitle>Relatório de Manutenção - Gráfico de Barra</charttitle>
                    </div>

                    <div class="chartBody">
                        <div id="myChartBar"></div>
                    </div>
                </div>

                <div class="chartContainer container" style="position: relative;" id="chartLineButton">
                    <div id="loadingMessageChartLine" class="loadingMessage">
                        <i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
                    </div>
                    <div class="container chartHeader">
                        <charttitle>Relatório de Manutenção - Gráfico de Linha</charttitle>
                    </div>

                    <div class="chartBody">
                        <div id="myChartLine"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Relatório de Manutenção - Gráfico de Barra</h3>
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

<div class="modal fade" id="chartLineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Relatório de Manutenção - Gráfico de Linha</h3>
            </div>
            <div class="modal-body" style="height: 500px;">
                <div class="col-md-12" style="height: 500px;">
                    <div id="myModalCharLinha" style="height: 100%;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="downloadChartLine" type="button" class="btn btn-success">Baixar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="reportGeneratorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleDefaultValue">Gerar Relatório</h3>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success" id='generateReport'>Salvar</button>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    var Router = '<?= site_url('Auditoria/Agendamento') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<!-- Style -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/agendamento', 'relatorio.css') ?>">

<!-- Libraries -->
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>

<!-- Util -->
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/EzMock', 'app.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'agGridTable.js') ?>"></script>

<!-- Page Related -->
<script type="text/javascript" src="<?= versionFile('assets/js/agendamento/RelatorioManutencao', 'index.js') ?>"></script>