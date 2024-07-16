<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("relatorio_tempo_contrato") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('relatorios') ?> >
        <?= lang('relatorio_tempo_contrato') ?>
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
                    <div class="input-container baseBusca">
                        <label for="baseCalculo">Base de Cálculo: <span class="text-danger">*</span></label>
                        <select class="form-control" name="baseCalculo" id="baseCalculo" type="text" style="width: 100%;" required>
                            <option value="1"><?=lang('30_dias')?></option>
                            <option value="0"><?=lang('total_dias_mes')?></option>
                        </select>
                    </div>
                    <div class="input-container buscaData" id="dateContainerInicial">
                        <label for="dataInicial">Data Inicial: <span class="text-danger">*</span></label></label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" required />
                    </div>

                    <div class="input-container buscaData" id="dateContainerFinal">
                        <label for="dataFinal">Data Final: <span class="text-danger">*</span></label></label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" required />
                    </div>
                    <div class="input-container buscaCliente">
                        <label for="clienteBusca">Cliente: <span class="text-danger">*</span></label></label>
                        <select class="form-control" name="clienteBusca" id="clienteBusca" type="text" style="width: 100%;" required>
                        </select>
                    </div>
                    <div class="input-container buscaStatus">
                        <label for="statusBusca">Status: <span class="text-danger">*</span></label></label>
                        <select class="form-control" name="statusBusca" id="statusBusca" type="text" style="width: 100%;" required>
                            <option value='0'>Todos</option>
                            <option value='1'>Ativos</option>
                            <option value='2'> Inativos</option>
                            <option value='3'> Cancelado</option>
                        </select>
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
                <b><?= lang("relatorio_tempo_contrato") ?>: </b>
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
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/relatorios/tempo_contrato', 'index.js') ?>"></script>

<script>
    var Router = '<?= site_url('relatorios') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>