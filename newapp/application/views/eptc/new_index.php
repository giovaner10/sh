<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('contratos_eptc') ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang("cadastros") ?> >
        <?= lang('contratos_eptc') ?>
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
                            <option value="permissionario">Permissionário</option>
                            <option value="prefixo">Prefixo</option>
                        </select>
                    </div>
                    <div class="input-container nomeBusca">
                        <label id="labelforBusca" class="control-label" for="valorBusca">Permissionário</label>
                        <input type="text" maxlength="100" name="valorBusca" autocomplete="off" class="form-control" placeholder="Digite o valor a ser buscado" id="valorBusca" />
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
                <b style="margin-bottom: 5px;"><?=lang('contratos_eptc') ?>: </b>
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

<div id="myModal_digitalizar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Digitalizar Contrato</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form id='formDigitalizar'>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id_contrato" name="id_contrato" value="">
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="descricao">Descrição: <span class="text-danger">*</span></label>
                                <input class="form-control" maxlength="100" name="descricao" id="descricao" type="text" style="width: 100%;" required>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="arquivo">Arquivo: <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="arquivo" required id="arquivo" formenctype="multipart/form-data" accept="application/pdf">
                                <span id="fileSizeError" style="color: red;font-size: 12px;padding: 0 10px; display: none;">O tamanho do arquivo não pode exceder 2MB.</span>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <button type="submit" class="btn btn-primary" id="sendAnexo" style="float: right; border-radius: 5px;">Enviar</button>
                            </div>
                        </div>
                    </form>
                    <div style="margin-bottom: 10px; margin-top: 10px;">
                        <div style="position: relative;">
                            <div class="wrapperAnexos">
                                <div id="tableAnexos" class="ag-theme-alpine my-grid" style="height: 300px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/ContratosEPTC', 'index.js') ?>"></script>

<script>
    var Router = '<?= site_url('contratos_eptc') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
</script>