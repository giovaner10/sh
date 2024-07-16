<div class="card-conteudo card-propostas" style='<?php echo $menuSelecionado == 'Proposta' ? '' : 'display: none;' ?>'>
    <h3>
        <b>Dados últimas 24h: </b>
        <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
            <div class="dropdown" style="margin-right: 10px;">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                </div>
            </div>
        </div>
    </h3>
    <div style="margin-bottom: 15px;">
        <select id="select-quantidade-por-pagina" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonRelatorio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px; float: right;" title="Exportar dados da tabela">
            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
        </button>
    </div>
    <div class="wrapper">
        <div id="loadingMessage" class="loadingMessage" style="display: none;">
            <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
        </div>
        <div id="table" class="ag-theme-alpine my-grid">
        </div>
    </div>
</div>

<script>
    var Router = '<?= site_url('relatorio_iscas') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var urlGlobal = '<?php echo $_SERVER['REQUEST_URI']; ?>'.split("/");
    var serial = urlGlobal[urlGlobal.length - 1];
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('newAssets/js', 'relatorio_iscas.js') ?>"></script>