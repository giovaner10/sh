<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("chips") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('cadastros') ?> >
        <?= lang('linhas') ?> >
        <?= lang('chips') ?>
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
                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="CCID">CCID:</label>
                        <input type="number" min="0" name="ccid" class="data formatInput form-control" placeholder="Digite o CCID" autocomplete="off" id="ccid" />
                    </div>

                    <div class="input-container buscaData" id="dateContainer2">
                        <label for="linha">Linha:</label>
                        <input type="number" min="0" name="linha" class="data formatInput form-control" placeholder="Digite a linha" autocomplete="off" id="linha" />
                    </div>

                    <div class="input-container buscaData buscaCliente">
                        <label for="operadora">Operadora:</label>
                        <input type="text" name="operadora" class="form-control" placeholder="Digite a operadora" id="operadora" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-chips" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b><?= lang('chips') ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: right; align-items: center;">
                    <button class="btn btn-primary" id="addChips" type="button" style="margin-right: 11px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <button class="btn btn-primary" id="addChipsLote" type="button" style="margin-right: 11px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar em Lote</button>
                    <div class="dropdown" style="margin-right: 10px; border: 1px solid transparent;">
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
            <div id="loadingMessage" class="loadingMessage" style="display: none; top: 57%; left: 50%; transform: translate(-57%, -50%);">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div>
                <select id="select-quantidade-por-pagina-chips" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label for="select-quantidade-por-pagina" style="float: left; margin-top: 18px; margin-left: 10px; color: #7F7F7F; font-size: 15px;">Registros por página</label>
            </div>
            <div class="wrapperChips" style="margin-top: 20px;">
                <div id="tableChips" class="ag-theme-alpine my-grid-chips">
                </div>
            </div>
        </div>
    </div>
</div>


<div id="addModalChip" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="" id='formAddChip'>
                <input type="hidden" name="id" id="id">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleChip">Cadastrar Chip</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="ccidForm">CCID: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="ccidForm" id="ccidForm" placeholder="Digite o CCID" required>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="linhaForm">Linha: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="linhaForm" id="linhaForm" placeholder="Digite a linha" required>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="contaForm">Conta: <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="0" name="contaForm" id="contaForm" placeholder="Digite a Conta" required>
                            </div>
                            <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                                <label for="operadoraForm">Operadora: </label>
                                <select class="form-control" name="operadoraForm" id="operadoraForm" required>
                                    <option value="" selected disabled>Selecione a operadora</option>
                                    <option value="ALGAR">ALGAR</option>
                                    <option value="CLARO">CLARO</option>
                                    <option value="OI">OI</option>
                                    <option value="TIM">TIM</option>
                                    <option value="VIVO">VIVO</option>
                                    <option value="VODAFONE">VODAFONE</option>
                                    <option value="CONNECT 4.0">CONNECT 4.0</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group inputRadio" style="height: 59px !important; margin-top: 25px; display: none;">
                                <div class="col-md-4">
                                    <input type="radio" name="status" id="statusCadastrado" value="0">
                                    <label for="statusCadastrado">Cadastrado</label>
                                </div>

                                <div class="col-md-4">
                                    <input type="radio" name="status" id="statusHabilitado" value="1">
                                    <label for="statusHabilitado">Habilitado</label>
                                </div>

                                <div class="col-md-4">
                                    <input type="radio" name="status" id="statusAtivo" value="2">
                                    <label for="statusAtivo">Ativo</label>
                                </div>

                                <div class="col-md-4">
                                    <input type="radio" name="status" id="statusCancelado" value="3">
                                    <label for="statusCancelado">Cancelado</label>
                                </div>

                                <div class="col-md-4">
                                    <input type="radio" name="status" id="statusBloqueado" value="4">
                                    <label for="statusBloqueado">Bloqueado</label>
                                </div>

                                <div class="col-md-4">
                                    <input type="radio" name="status" id="statusSuspenso" value="5">
                                    <label for="statusSuspenso">Suspenso</label>
                                </div>

                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarChip'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addModalChipLote" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="" id='formAddChipLote'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleChipLote">Cadastrar Chip em Lote</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <p class="alert alert-warning placa-alert"><?= lang('msn_atencao_multiChips') ?></p>
                            <b>
                                <p class="alert alert-info placa-alert"><?= lang('msn2_atencao_multiChips') ?></p>
                            </b>
                            <input class="form-control-file" type="file" name="file" id="fileUpload" />
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id='btnSalvarChipLote'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/cadastro', 'chips.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">

<script>
    var Router = '<?= site_url('linhas') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>