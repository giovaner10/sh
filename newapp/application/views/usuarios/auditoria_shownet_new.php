<style>
    .ag-theme-alpine .ag-details-row {
        padding: 10px !important;
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('auditoriashownet') ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang("logs") ?> >
        <?= lang('auditoriashownet') ?>
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
                    <div class="input-container">
                        <label for="coluna">Pesquisar por:</label>
                        <select id="coluna" name="coluna" class="form-control" style="width: 100%;">
                            <option value="0">ID Registro</option>
                            <option value="1">ID Usuário</option>
                            <option value="2">Evento</option>
                            <option value="3">Tabela</option>
                        </select>
                    </div>
                    <div class="input-container nomeBusca">
                        <label id="labelforBusca" for="valorBusca">ID Registro</label>
                        <input type="text" maxlength="100" name="valorBusca" autocomplete="off" class="form-control" placeholder="Digite o valor a ser buscado" id="valorBusca" />
                    </div>
                    <div class="input-container eventoBusca" style="display: none;">
                        <label id="labelforBusca" for="eventoBusca">Evento</label>
                        <select name="eventoBusca" class="form-control" id="eventoBusca">
                            <option value="atualizar">Atualizar</option>
                            <option value="criar">Criar</option>
                            <option value="deletar">Deletar</option>
                        </select>
                    </div>
                    <div class="input-container tabelaBusca" style="display: none;">
                        <label id="labelforBusca" for="tabelaBusca">Tabela</label>
                        <select name="tabelaBusca" class="form-control" id="tabelaBusca">
                        </select>
                    </div>
                    <div class="input-container dataBusca">
						<label class="control-label" for="dataInicial">Data Inicial:</label>
						<input type="date" name="dataInicial" id="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" required/>
					</div>
                    <div class="input-container dataBusca">
						<label class="control-label" for="dataFinal">Data Final:</label>
						<input type="date" name="dataFinal" id="dataFinal" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" required/>
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
                <b style="margin-bottom: 5px;"><?=lang('auditoriashownet') ?>: </b>
                <div class="btn-div-responsive">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
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
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/logs/Auditoria', 'index.js') ?>"></script>

<script>
    var Router = '<?= site_url('usuarios') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var dadosTabelas = <?= $tabelas ?>;
    var referenciaTabelas = <?= $referencia ?>;
</script>