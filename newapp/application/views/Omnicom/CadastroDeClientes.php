<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("omnicom") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('omnicom') ?> >
        <?= lang('cadastro_clientes_omnicom') ?>
    </h4>
</div>

<?php
$menu_omnicom = $_SESSION['menu_omnicom'];
?>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link <?= $menu_omnicom == 'CadastroDeClientes' ? 'selected' : '' ?>' id="menu-cadastro-clientes"><?= lang("cadastro_clientes_omnicom") ?></a>
                </li>
            </ul>
        </div>


        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">

                    <!-- <div class="input-container">
                        <label for="placaBusca">Usuário:</label>
                        <input type="text" name="usuario" class="form-control" placeholder="Digite o Usuário" id="usuario" />
                    </div> -->

                    <div class="input-container">
                        <label for="idTecnologia">ID Tecnologia:</label>
                        <input type="number" min="1" name="idTecnologia" class="form-control" placeholder="Digite o ID da Tecnologia" id="idTecnologia" />
                    </div>

                    <div class="input-container">
                        <label for="clienteBusca">Cliente:</label>
                        <select class="form-control" name="clienteBusca" id="clienteBusca" type="text" style="width: 100%;">
                            <option value="" disabled selected>Buscando clientes...</option>
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

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b style="margin-bottom: 5px;"><?= lang("listagem_de_tecnologias") ?>: </b>
                <div class="btn-div-responsive" id="btn-div-alertas">
                    <button class="btn btn-primary" id="BtnAdicionarCliente" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <div class="dropdown" style="margin-right: 10px;  margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_tecnologias" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-tecnologias" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageCadastroClientes" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageTecnologias" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperTecnologias">
                    <div id="tableTecnologias" class="ag-theme-alpine my-grid-tecnologias" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addClientes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="" id='formAddClientes'>
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="status" id="status">
                    <div class="modal-header header-layout">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title" id="titleTecnologias">Cadastrar Cliente</h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class='row'>
                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="idCliente">Selecione o Cliente: <span class="text-danger">*</span></label>
                                    <select class="form-control" type="text" name="idCliente" id="idCliente" placeholder="Selecione o cliente" style="width: 100%;" required>
                                        <option value="">Buscando clientes...</option>
                                    </select>
                                </div>
                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="idTecnologias">ID da Tecnologia: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" min="1" size="11" name="idTecnologias" id="idTecnologias" placeholder="Digite o ID da tecnologia" required />
                                </div>
                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="usuario">Usuário: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Digite o usuário" required />
                                </div>
                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="senhaUsuario">Senha: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="password" name="senhaUsuario" id="senhaUsuario" placeholder="Digite a senha" required />
                                </div>
                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="tipoUrl">Tipo URL: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="tipoUrl" id="tipoUrl" type="text" required style="width: 100%;">
                                    </select>
                                </div>
                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="url">URL: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="url" id="url" placeholder="Digite o url" required />
                                </div>
                            </div>

                            <hr>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-group">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success" id='btnSalvarCliente'>Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/Omnicom', 'CadastroClientes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/Omnicom', 'Exportacoes.js') ?>"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<script>
    var RouterCliente = '<?= site_url('clientes') ?>';
    var Router = '<?= site_url('Omnicom/CadastroDeClientes') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>