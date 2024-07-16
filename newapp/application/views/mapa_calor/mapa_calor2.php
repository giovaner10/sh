<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("mapa_calor") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('auditoria') ?> >
        <?= lang('mapa_calor') ?>
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
                    <a class='menu-interno-link selected' id="menu-nuvem-palavras" ><?= lang("nuvem_de_palavras") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link' id="menu-relatorio"><?= lang("mapa_calor_relatorio") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link' id="menu-dashboard"><?= lang("mapa_calor_dashboard") ?></a>
                </li>
            </ul>
        </div>
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group">
                    <div class="input-container">
                        <label for="searchTypeData">Buscar por:</label>
                            <select id="tipoData" name="tipoData" class="form-control">
                                <option value="dateRange">Intervalo de dias</option>
                                <option value="mes">Mês</option>
                                <option value="ano">Ano</option>
                                <option value="periodo">Período</option>
                                <option value="login">E-mail</option>
                        </select>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container" id="dateContainer1">
                        <label for="dataInicial" class="control-label">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value="" required/>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container" id="dateContainer2">
                        <label for="dataFinal" class="control-label">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value="" required/>
                    </div>
                    <div class="input-container" id="mesContainer" style="display:none;">
                        <label for="mesInput" class="control-label">Mês:</label>
                        <input type="month" id="mesInput" class="form-control">
                    </div>
                    <div class="input-container" id="anoContainer" style="display:none;">
                        <label for="anoInput" class="control-label">Ano:</label>
                        <input type="number" id="anoInput" class="form-control" min="1900" max="2099" step="1" placeholder="Digite o ano" onkeyup="mascararNumero(this)" />
                    </div>
                    <div class="input-container" id="loginContainer" style="display:none;">
                        <label for="loginInput" class="control-label">E-mail:</label>
                        <select class="form-control input-sm" id="loginInput" name="email" type="text" style="width: 100%">
                            <option value="" selected disabled>Selecione um e-mail</option>
                        </select>
                    </div>
                    <div class="input-container" id="periodoContainer" style="display:none;">
                        <label for="periodoInput" class="control-label">Período:</label>
                        <select id="periodoInput" class="form-control">
                            <option value="7days">Últimos 7 dias</option>
                            <option value="1mes">Último mês</option>
                            <option value="3mes">Últimos 3 meses</option>
                            <option value="6mes">Últimos 6 meses</option>
                            <option value="12mes">Último ano</option>
                        </select>
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-nuvem-palavras">
            <h3>
                <b>Nuvem de Palavras: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            
            <div id="loadingNuvemPalavras" style="display: none;">
                <i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b>
            </div>

            <span id="emptyNuvem" style="display: none;">Nenhum resultado encontrado!</span>


            <div id="nuvem-palavras" style="width: auto">
                <canvas id="wordCloudCanvas" width="800" height="700" title="Tooltip"></canvas>
            </div>
        </div> 
        <div class="card-conteudo card-relatorio" style="display: none;">
            <h3>
                <b>Relatório: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">                
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonMapaDeCalor" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_mapa_calor" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina-relatorio" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
                <input class="form-control inputBusca" type="text" id="search-input-relatorio" placeholder="Pesquisar" style="margin-top: 10px; float: right;">
            </div>

            <div class="wrapper">
                <div id="myGrid" class="ag-theme-alpine my-grid">
                </div>
            </div>
        </div>
        <div class="card-conteudo card-dashboard" style="display: none;">
            <h3>
                <b>Dashboard: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expand
                        ir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>

            <div id="loadingDashboard" style="display: none;">
                <i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b>
            </div>

            <span id="emptyDashboard">Nenhum resultado encontrado!</span>

            <div id="dashboard">
                <div class="chart-container">
                    <canvas id="myChartBar"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="myChartLine"></canvas>
                </div>
                <div class="chart-container container-pie">
                    <canvas id="myChartPie"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/mapa_calor', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/mapa_calor', 'style.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/mapa_calor', 'mapa_calor.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/pt-BR.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wordcloud@1.2.2/src/wordcloud2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community/dist/ag-charts-community.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var RouterControllerMapaCalor = '<?= site_url('Auditoria/mapa_calor') ?>';
    var RouterControllerRelatorios = '<?= site_url('relatorios') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>