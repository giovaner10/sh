<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("relatorio_acessos") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('auditoria') ?> >
        <?= lang('relatorio_acessos') ?>
    </h4>
</div>

<style>
    #search-input::placeholder {
        font-style: italic;
    }

    #search-input {
        font-style: normal;
    }
</style>

<div id="loading">
    <div class="loader"></div>
</div>


<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaProcessamento">
                        <label for="tipoData">Filtrar por:</label>
                        <select id="tipoData" name="tipoData" class="form-control">
                            <option value="dateRange" selected>Intervalo de dias</option>
                            <option value="mes">Mês</option>
                            <option value="ano">Ano</option>
                            <option value="periodo">Período</option>
                        </select>
                    </div>

                    <div class="input-container" id="dateContainer1">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value="" />
                    </div>
                    <div class="input-container" id="dateContainer2">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value="" />
                    </div>

                    <div class="input-container" id="mesContainer" style="display:none;">
                        <label for="mesInput">Mês:</label>
                        <input type="month" id="mesInput" class="form-control">
                    </div>
                    <div class="input-container" id="anoContainer" style="display:none;">
                        <label for="anoInput">Ano:</label>
                        <input type="number" id="anoInput" class="form-control" min="1900" max="2099" step="1" value="2024" />
                    </div>
                    <div class="input-container" id="periodoContainer" style="display:none;">
                        <label for="periodoInput">Período:</label>
                        <select id="periodoInput" class="form-control">
                            <option value="7days">Últimos 7 dias</option>
                            <option value="1mes">Último mês</option>
                            <option value="3mes">Últimos 3 meses</option>
                            <option value="6mes">Últimos 6 meses</option>
                            <option value="12mes">Último ano</option>
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
        <div class="card-conteudo card-relatorio-acesso" style='margin-bottom: 20px;'>
            <h3>
                <b style="margin-bottom: 5px;"> <?= lang('relatorio_acessos') ?> </b>
                <div class="btn-div-responsive" id="btn-div-alertas">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonRelatorioAcessos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_relatorio_acessos" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina-relatorio-acesso" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label for="select-quantidade-por-pagina-relatorio-acesso" style="float: left; margin-top: 18px; margin-left: 10px; color: #7F7F7F; font-size: 15px;">Registros por página</label>
                <input type="text" id="search-input" placeholder="Buscar" style="float: right; margin-top: 19px; height:30px; padding-left: 10px;">
            </div>
            <div id="emptyMessageSms" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessage" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperRelatorioAcesso">
                    <div id="tableRelatorioAcesso" class="ag-theme-alpine my-grid-relatorio-acesso" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/relatorios/acessos', 'relatorioAcessos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/relatorios/acessos', 'Exportacoes.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">

<script>
    var Router = '<?= site_url('relatorios') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>
