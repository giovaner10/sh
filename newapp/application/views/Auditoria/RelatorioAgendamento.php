<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente(lang("relatorio_instalacao"), site_url('Homes'), lang('relatorio_agendamento'), lang('relatorio_instalacao'));
?>

<?php 
    loadingSpinner();
?>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <?php
        menuLateralComponente(['Relatorio', 'Dashboard']);
        ?>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="buscaSelection" id="filtrarPor">
                        <label for="selectPesqTecnico">Técnico:</label>
                        <select name="nomeTecnico" id="selectPesqTecnico" class="form-control">
                        </select>
                    </div>

                    <div class="input-container_ag" id="dateContainerStart">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value="" />
                    </div>

                    <div class="input-container_ag" id="dateContainerEnd">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value="" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success search-button" id="BtnPesquisar" type="button" style="width:100%;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default clear-search-button" id="BtnLimparPesquisar" type="button" style="margin-top: 10px;width:100%;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-relatorio-instalacao" style='margin-bottom: 20px; position: relative;'>

            <div class="tablePageHeader">
                <h3>Relatórios de Instalação: </h3>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center; margin-top:15px; margin-bottom:15px;">
                    <div class="dropdown" style="margin-right: 10px;" id="dropdown_exportar">
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
            </div>

            <div id="notFoundMessage" style="display: none;">
                <h5>Nenhum dado encontrado para a pesquisa feita</h5>
            </div>
            <div class="registrosDiv">
                <select id="paginationSelect" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;" id="tabelaInstalacoes">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-dashboard-instalacao" style='margin-bottom: 20px; position: relative; display: none;'>
            <h3>
                <b>Dashboard de Instalações: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>

            <!-- <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 500px">
                    </div>
                </div> -->

            <div id="loadingDashboard" style="display: none;">
                <i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b>
            </div>
            <div class="chartPlotContainer" id="chartContainer" style="flex-wrap: wrap;">

                <div class="chartContainer container" id="chartBarButton">
                    <div class="container chartHeader">
                        <charttitle>Estatísticas de Agendamento de Instalação - Gráfico Barra</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="myChartBar"></div>
                        <div id="anoAgInstBar" class="chartHeaderFooterText">Ano</div>
                    </div>
                </div>

                <div class="chartContainer container" id="chartLineButton">
                    <div class="container chartHeader">
                        <charttitle>Estatísticas de Agendamento de Instalação - Gráfico Linha</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="myChartLine"></div>
                        <div id="anoAgInstLine" class="chartHeaderFooterText">Ano</div>
                    </div>
                </div>

                <div class="chartContainer container" id="chartMotivosRecusaInstButton">
                    <div class="container chartHeader">
                        <charttitle>Recusa de Instalação - Motivos Frequentes</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="totalRecusasSubtitle" class="chartHeaderFooterText">
                            <div style="
                            width: 12px; 
                            height:12px; 
                            background-color: #006DF9; 
                            border-radius:100%;">
                            </div>
                            <div> Nenhuma recusa</div>
                        </div>
                        <div id="recusaInstalacaoChart" style="max-height: 280px !important;"></div>
                        <div id="mesRecusas" class="chartHeaderFooterText">Mês</div>
                    </div>
                </div>

            </div>
            <div id="emptyMessage" style="display: none; position: relative; margin: 15px;">
                <b>Nenhuma informação a ser exibida.</b>
            </div>
        </div>
    </div>
</div>

<script>
    var Router = '<?= site_url('Auditoria/Agendamento') ?>';
    var BaseURL = '<?= base_url('') ?>';
    console.log(BaseURL);
</script>

<!-- Style -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/agendamento', 'layout.css') ?>">

<!-- Default Scripts -->
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/agendamento', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/EzMock', 'app.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'agGridTable.js') ?>"></script>

<!-- Custom Scripts -->
<script type="text/javascript" src="<?= versionFile('assets/js/Auditoria/Relatorio/Instalacao', 'Relatorio.js') ?>"></script>
