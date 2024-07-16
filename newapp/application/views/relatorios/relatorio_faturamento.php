<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("relatorio_faturamento") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('departamentos') ?> >
        <?= lang('financeiro') ?> >
        <?= lang('fatura') ?> >
        <?= lang('relatorio_faturamento') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formRelatorioFaturamento">
                <div class="form-group filtro">

                    <div class="input-container data_inicial">
                        <label for="data_inicial">Data Inicial:</label> <span class="text-danger">*</span></label>
                        <input type="date" max="2099-12-31T12:30:59" name="data_inicial" class="form-control" id="data_inicial" value="" autocomplete="off" />
                    </div>

                    <div class="input-container data_final">
                        <label for="data_final">Data Final:</label> <span class="text-danger">*</span></label>
                        <input type="date" max="2099-12-31T12:30:59" name="data_final" class="form-control" id="data_final" value="" autocomplete="off" />
                    </div>

                    <div class="input-container form-group empresa">
                        <label for="empresa">Empresa:</label> <span class="text-danger">*</span></label>
                        <select class="form-control" type="text" name="empresa" id="empresa" placeholder="Selecione uma Empresa" style="width: 100%;">
                            <option value="todas" selected><?= lang('todas') ?></option>
                            <option value="NORIO"><?= lang('norio') ?></option>
                            <option value="TRACKER"><?= lang('show_tecnologia') ?></option>
                        </select>
                    </div>

                    <div class="input-container form-group orgao">
                        <label for="orgao">Orgão:</label> <span class="text-danger">*</span></label>
                        <select class="form-control" type="text" name="orgao" id="orgao" placeholder="Selecione o Orgão" style="width: 100%;">
                            <option value="" selected>Todos</option>
                            <option value="publico">Público</option>
                            <option value="privado">Privado</option>
                        </select>
                    </div>

                    <div class="input-container" style="padding:0px;">
                        <label for="idStatus">Status:</label> <span class="text-danger">*</span></label>
                        <select class="js-prestadora-multiple form-control" name="status[]" multiple="multiple" autocomplete="off" style="width: 100%;" id="idStatus">
                            <option value="0" selected><?= lang('faturas_abertas') ?></option>
                            <option value="1"><?= lang('faturas_pagas') ?></option>
                            <option value="4"><?= lang('faturas_a_cancelar') ?></option>
                            <option value="3"><?= lang('faturas_canceladas') ?></option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> <?= lang('gerar') ?></button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b><?= lang('relatorio_faturamento') ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
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
                <div class="wrapperRelatorioFaturamento">
                    <div id="tabelaRelatorioFaturamento" class="ag-theme-alpine my-grid-faturas" style="height: 519px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var Router = '<?= site_url('relatorio_faturamento') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/departamentos/financeiro', 'relatorioFaturamento.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/departamentos/financeiro', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/departamentos/financeiro', 'RelatorioFaturamento.js') ?>"></script>