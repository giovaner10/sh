<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("cadastro_centrais") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('cadastro_centrais') ?> >
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaData serial">
                        <label for="busca">Pesquisar:</label>
                        <input type="text" name="busca" class="form-control" placeholder="Digite a busca" id="busca" />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-cadastro-central" style='margin-bottom: 20px;'>
            <h3>
                <b><?= lang("cadastro_centrais") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_central" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="BtnAdicionarCentral" type="button" style="margin-right: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-central" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageCentral" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageCentral" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperCentral">
                    <div id="tableCentral" class="ag-theme-alpine my-grid-central" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addCentral" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formAddCentral'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleCentral">Cadastro de Central</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ativa" value="1" id="ativa">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="nome">Nome: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="nome" id="nome" placeholder="Digite o nome" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="usuario">IP: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="ip" id="ip" placeholder="Digite o IP" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="porta">Porta: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="porta" id="porta" maxlength="6" placeholder="Digite a porta" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="cnpj">CNPJ: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="cnpj" id="cnpj" placeholder="Digite o CNPJ" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="cliente">Cliente: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="cliente" id="cliente" placeholder="Digite o url" required />
                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarCentral'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/cadastroCentrais', 'CadastroCentrais.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/cadastroCentrais', 'Exportacoes.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var Router = '<?= site_url('cad_centrais') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>