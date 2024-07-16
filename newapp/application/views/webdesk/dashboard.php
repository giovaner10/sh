<style>
    .thead-alert {
        margin-bottom: 0;
    }

    .trActive {
        background: #f89406;
        color: white;
        -webkit-transition: all 1s ease-in;
        -moz-transition: all 1s ease-in;
        -o-transition: all 1s ease-in;
        -ms-transition: all 1s ease-in;
        transition: all 1s ease-in;
    }

    .content-main {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
        gap: 30px;
        justify-content: center;
        align-content: center;
        max-width: 80%;
        margin-top: 60px;
        margin-bottom: 60px;
    }

    .chart-plot-container {
        display: flex;
        justify-content: space-around;
        align-content: center;
        background-color: white;
        border: none;
        border-radius: 1rem;
        gap: 25px;
        padding: 45px;
        height: 40%;
        width: 100%;
        flex-direction: row;
    }

    .chart-plot {
        max-width: 45rem;
        text-align: center;
    }

    @media (max-width: 1280px) {
        .chart-plot-container {
            flex-direction: column;
        }
    }
    @media (max-width: 800px) {
        #filterAndPaginationDivRanking, #filterAndPaginationDiv{
            flex-direction: column;
        }

        .content-main{
            max-width: 100%;
        }
    }

    @media (max-width: 640px) {
        .chart-plot {
            max-width: 35rem;
        }
    }
</style>
<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" rel="stylesheet">

<div id="loadingMessage" class="loadingMessage" style="display: none; margin-top: 45vh;">
    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
</div>
<div class="text-title">
        <h3 style="padding: 0 20px; margin-left: 15px;">Dashboard de Tickets</h3>
        <h4 style="padding: 0 20px; margin-left: 15px;">
            <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
            <?= lang('monitoramento') ?> >
            <?= "Dashboard de Tickets" ?>
        </h4>
</div>

<main id="screen-content" style="display: none;">
    
    <div class="chart-plot-container shadow">
        <div class="box chart-plot container">
            <h4>Resumo dos últimos 30 dias</h4>
            <canvas id="chart_pizza" style="position: relative; height:150px; width:150px;"></canvas>
        </div>
        <div class="box chart-plot container">
            <h4>Resumo de Avaliações Positivas / Negativas</h4>
            <canvas id="chart_bar" style="position: relative; height:150px; width:150px;"></canvas>
        </div>
    </div>
    <div class="chart-plot-container" style="height:fit-content;">
        <div id="table-related-ranking" style="display: flex; flex-direction: column; text-align:center; width: 100%;">
            <h4>Ranking de usuários com pior avaliação</h4>
            <div class="wrapperRanking" style='margin-top: 20px;'>
                <div id="tableRanking" class="ag-theme-alpine my-grid-ranking">
                </div>
            </div>
        </div>
        <div id="table-related" style="display: flex; flex-direction: column; text-align:center; width: 100%;">
            <h4>Novos tickets</h4>
            <div class="wrapperTickets" style='margin-top: 20px;'>
                <div id="tableTickets" class="ag-theme-alpine my-grid-tickets">
                </div>
            </div>
        </div>
    </div>
</main>

<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script>
    var BaseURL = '<?= base_url('') ?>';
    var Router = '<?= site_url('dashboard') ?>';
    var localeText = AG_GRID_LOCALE_PT_BR;
</script>

<script type="text/javascript" src="
<?= versionFile(
    'assets/js/webdesk',
    'dashboard.js'
) ?>
"></script>