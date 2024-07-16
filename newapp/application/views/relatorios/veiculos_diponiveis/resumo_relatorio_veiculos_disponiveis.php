<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("resumo_veiculos_disponiveis") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('relatorios') ?> >
        <?= lang('clientes') ?> >
        <?= lang('resumo_veiculos_disponiveis') ?>
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
                <div class="form-group">
                    <div class="input-container">
                        <label for="clientes" class="control-label">Cliente:</label>
                        <select class="form-control" name="id_cliente" id="clientes" type="text" style="width: 100%;" required>
                        </select>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container">
                        <label for="dp1" class="control-label">Data Inicial:</label>
                        <input type="date" name="di" class="form-control" placeholder="Digite a data inicial" id="dp1" required />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container">
                        <label for="dp2" class="control-label">Data Final:</label>
                        <input type="date" name="df" class="form-control" placeholder="Digite a data final" id="dp2" required />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-resumo">
            <h3>
                <b>Relatório - Resumo Veículos Disponíveis: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <span class="info" id="total_veiculos" style="display: none"></span>
            <span class="info" id="valor_total" style="display: none"></span>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
                <input class="form-control inputBusca" type="text" id="search-input" placeholder="Pesquisar" style="margin-top: 10px; float: right;">
            </div>
            <div class="wrapperResumoVeiculos">
                <div id="tableResumoVeiculos" class="ag-theme-alpine my-grid">
                </div>
            </div>
        </div> 
    </div>
</div>
<div id="modalDatasPlacas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formProduto'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleModalDatasPlacas">Dias de Trabalho da Placa</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <span class="diasPlaca" id="diasPlacas"></span>
                    </div>
                </div>
                <br>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= versionFile('assets/js/resumoRelatorioVeiculos', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/resumoRelatorioVeiculos', 'style.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/resumoRelatorioVeiculos', 'resumoRelatorioVeiculos.js') ?>"></script>


<script>
    var RouterController = '<?= site_url('relatorios') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>
