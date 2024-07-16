<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">

<style>
    #header-modal {
        font-family: 'Mont SemiBold';
        color: #1C69AD !important;
        font-size: 22px !important;
        font-weight: bold !important;
        text-align: center;
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("suprimentos") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('cadastros') ?> >
        <?= lang('suprimentos') ?>
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li><a aria-controls="cintas" role="tab" data-toggle="tab" id="menu_cintas">Cintas</a></li>
                <li><a aria-controls="powerbanks" role="tab" data-toggle="tab" id="menu_powerbanks">PowerBanks</a></li>
                <li><a aria-controls="carregadores" role="tab" data-toggle="tab" id="menu_carregadores">Carregadores</a></li>
            </ul>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-cintas" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b>Cintas: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonCintas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_cintas" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="button_add_cinta" type="button" style="margin-right: 11px;"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                    <button class="btn btn-light btn-expandir-cintas" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir-cintas" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div id="loadingMessage" class="loadingMessage" style="display: none; top: 57%; left: 50%; transform: translate(-57%, -50%);">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div>
                <select id="select-quantidade-por-pagina-cintas" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <input type="text" id="search-input-cintas" placeholder="Pesquisar" style="float: right; margin-top: 19px;">
            </div>
            <div class="wrapperCintas" style="margin-top: 20px;">
                <div id="tableCintas" class="ag-theme-alpine my-grid-cintas">
                </div>
            </div>
        </div>

        <div class="card-conteudo card-powerbanks" style='margin-bottom: 20px; position: relative; display: none;'>
            <h3>
                <b>PowerBanks: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonPowerbanks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_powerbanks" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="button_add_powerbank" type="button" style="margin-right: 11px;"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                    <button class="btn btn-light btn-expandir-powerbanks" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir-powerbanks" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div id="loadingMessage" class="loadingMessage" style="display: none; top: 57%; left: 50%; transform: translate(-57%, -50%);">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div>
                <select id="select-quantidade-por-pagina-powerbanks" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <input type="text" id="search-input-powerbanks" placeholder="Pesquisar" style="float: right; margin-top: 19px;">
            </div>
            <div class="wrapperPowerBanks" style="margin-top: 20px;">
                <div id="tablePowerBanks" class="ag-theme-alpine my-grid-powerbanks">
                </div>
            </div>
        </div>

        <div class="card-conteudo card-carregadores" style='margin-bottom: 20px; position: relative; display: none;'>
            <h3>
                <b>Carregadores: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonCarregadores" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_carregadores" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="button_add_carregador" type="button" style="margin-right: 11px;"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                    <button class="btn btn-light btn-expandir-carregadores" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir-carregadores" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>

            </h3>
            <div id="loadingMessage" class="loadingMessage" style="display: none; top: 57%; left: 50%; transform: translate(-57%, -50%);">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div>
                <select id="select-quantidade-por-pagina-carregadores" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <input type="text" id="search-input-carregadores" placeholder="Pesquisar" style="float: right; margin-top: 19px;">
            </div>
            <div class="wrapperCarregadores" style="margin-top: 20px;">
                <div id="tableCarregadores" class="ag-theme-alpine my-grid-carregadores">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Cinta -->
<div id="modalCadCinta" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCinta">
                <div class="modal-header">
                    <button type="button" id="close_cinta" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="header-modal">Cadastrar Cinta</h3>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">

                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group">
                                <label class="control-label" title="Serial" style="font-weight: bold !important;">Serial:</label>
                                <input class="form-control" id="cinta_serial" name="cinta_serial" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição" style="font-weight: bold !important;">Descrição:</label>
                                <input class="form-control" id="cinta_descricao" name="cinta_descricao" />
                            </div>
                        </div>

                    </div>
                </div>
                <hr style="margin-bottom: 15px">
                <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                    <button type="button" class="btn btn-primary" id="fechar_cinta" data-dismiss="modal" style="margin-left: 20px;float: inline-start;">Fechar</button>
                    <button type="button" id="submit_cinta" class="btn btn-submit btn-success" style="margin-right: 20px;float: inline-end;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Powerbank -->
<div id="modalCadPowerbank" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPowerbank">
                <div class="modal-header">
                    <button type="button" id="close_powerbank" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="header-modal">Cadastrar PowerBank</h3>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group">
                                <label class="control-label" title="Serial">Serial<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="powerbank_serial" name="powerbank_serial" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição:</label>
                                <input class="form-control" id="powerbank_descricao" name="powerbank_descricao" />
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="margin-bottom: 15px">
                <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                    <button type="button" class="btn btn-primary" id="fechar_powerbank" data-dismiss="modal" style="margin-left: 20px;float: inline-start;">Fechar</button>
                    <button type="button" id="submit_powerbank" class="btn btn-submit btn-success" style="margin-right: 20px;float: inline-end;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Carregador -->
<div id="modalCadCarregador" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCarregador">
                <div class="modal-header">
                    <button type="button" id="close_carregador" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="header-modal">Cadastrar Carregador</h3>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group">
                                <label class="control-label" title="Serial">Serial<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="carregador_serial" name="carregador_serial" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição:</label>
                                <input class="form-control" id="carregador_descricao" name="carregador_descricao" />
                            </div>
                        </div>

                    </div>
                </div>
                <hr style="margin-bottom: 15px">
                <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                    <button type="button" class="btn btn-primary" id="fechar_carregador" data-dismiss="modal" style="margin-left: 20px;float: inline-start;">Fechar</button>
                    <button type="button" id="submit_carregador" class="btn btn-submit btn-success" style="margin-right: 20px;float: inline-end;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var BaseURL = '<?= base_url('') ?>';
    var Router = '<?= site_url('suprimentos') ?>';

    function printParams(pageSize) {
        return {
            PDF_HEADER_COLOR: "#ffffff",
            PDF_INNER_BORDER_COLOR: "#dde2eb",
            PDF_OUTER_BORDER_COLOR: "#babfc7",
            PDF_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
            PDF_HEADER_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
            PDF_ODD_BKG_COLOR: "#fff",
            PDF_EVEN_BKG_COLOR: "#F3F3F3",
            PDF_PAGE_ORITENTATION: "landscape",
            PDF_WITH_FOOTER_PAGE_COUNT: true,
            PDF_HEADER_HEIGHT: 25,
            PDF_ROW_HEIGHT: 25,
            PDF_WITH_CELL_FORMATTING: true,
            PDF_WITH_COLUMNS_AS_LINKS: false,
            PDF_SELECTED_ROWS_ONLY: false,
            PDF_PAGE_SIZE: pageSize,
        }
    }
</script>
<script type="text/javascript" src="<?= versionFile('assets/js/suprimentos', 'suprimentos.js') ?>"></script>