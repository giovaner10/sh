<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("release_notes") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('empresa') ?> >
        <?= lang('release_notes') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<?php if (
    $this->auth->is_allowed_block('vis_desenvolvimentoorganizacional') ||
    $this->auth->is_allowed_block('vis_administracaopessoal') ||
    $this->auth->is_allowed_block('vis_empresa')
) : ?>

    <div class="row" style="margin: 15px 0 0 15px;">
        <div class="col-md-3">
            <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
                <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
                <form id="formBusca">
                    <div class="form-group filtro">
                        <div class="input-container">
                            <label for="release">Release:</label>
                            <input type="text" name="release" class="form-control" placeholder="Digite a release" id="release" />
                        </div>
                        <div class="input-container buscaProcessamento">
                            <label for="dataInicial">Data Inicial:</label>
                            <input type="date" name="dataInicial" id="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" />
                        </div>
                        <div class="input-container buscaProcessamento">
                            <label for="dataFinal">Data Final:</label>
                            <input type="date" name="dataFinal" id="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" />
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
            <div class="card-conteudo card-releases" style='margin-bottom: 20px;'>
                <h3>
                    <b style="margin-bottom: 5px;"><?= lang("release_notes") ?>: </b>
                    <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                        <?php if ($this->auth->is_allowed_block('vis_empresa')) : ?>
                            <button class="btn btn-primary" id="BtnAdicionarReleases" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i>Adicionar Release</button>
                        <?php endif; ?>
                        <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                            <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                        </button>
                    </div>
                </h3>
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-releases" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <div id="emptyMessageReleases" style="display: none;">
                    <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
                </div>
                <div style="position: relative;">
                    <div id="loadingMessageReleases" class="loadingMessage" style="display: none;">
                        <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                    </div>
                    <div class="wrapperReleases">
                        <div id="tableReleases" class="ag-theme-alpine my-grid-releases" style="height: 500px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="modalReleaseNotes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form id="form_release" enctype="multipart/form-data">
                    <input type="hidden" id="idUsuario" name="idUsuario" value="<?= $idUser ?>">
                    <input type="hidden" id="idRelease" name="idRelease" value="<?= $idUser ?>">
                    <div class="modal-header header-layout">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title" id="titleRelease">Adicionar Release</h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class='row'>
                                <div class="form-group">
                                    <label class="control-label" for="release">Título da Release</label>
                                    <input type="text" id="nomeRelease" name="nomeRelease" placeholder="Título da release" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label id="arquivoEdit" class="control-label" for="arquivo">Arquivo (PDF)</label>
                                    <input type="file" id="nomeArquivo" name="nomeArquivo" class="form-control" accept="application/pdf" required>
                                </div>
                                <!-- <span style="color: red; margin-top: 10px; display: none;" id="msgEdição">*Mantenha o campo acima em branco para não atualizar o arquivo.</span> -->
                            </div>

                            <hr>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-group">
                            <button type="button" class="btn" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-success" id="buttonSalvarRelease">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="visualizarRelease" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleVisualizarRelease"></h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <div id="releaseNote">
                            </div>
                        </div>
                        <hr>
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

<?php endif; ?>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Empresa', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/Empresa', 'ReleaseNotes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    <?php if ($this->auth->is_allowed_block('edit_delete_release_notes')) : ?>
        var permissaoEditarExcluir = true;
    <?php else : ?>
        var permissaoEditarExcluir = false;
    <?php endif; ?>
</script>


<script>
    var Router = '<?= site_url('Empresas/ReleaseNotes') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>