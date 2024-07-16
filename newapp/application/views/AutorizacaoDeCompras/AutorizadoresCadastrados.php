<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("autorizadores_cadastrados") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('autorizacao_de_compras') ?> >
        <?= lang('autorizadores_cadastrados') ?>
    </h4>
</div>


<?php
$menu_autorizacao = $_SESSION['menu_autorizacao'];
?>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px; align-items: flex-start;">
    <div class="col-md-3">

        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link <?= $menu_autorizacao == 'AutorizadoresCadastrados' ? 'selected' : '' ?>' id="menu-autorizadores-cadastrados"><?= lang("autorizadores_cadastrados") ?></a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaAutorizadores">
                        <label for="autorizadoresBusca">Autorizador:</label>
                        <select class="form-control" name="autorizadorBusca" id="autorizadorBusca" type="text" style="width: 100%; margin-bottom: 5px;">
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="filtrar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="btnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative; <?= $menu_autorizacao == 'AutorizadoresCadastrados' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("autorizadores_cadastrados") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <!-- <button id="cadastrarAssociacao" class="btn btn-primary" style="height: 36.5px;">Cadastrar Associação</button> -->
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

    <div id="addAssociacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id='formAssociarAprovador'>
                    <input type="hidden" name="idAssociacao" id="idAssociacao">
                    <div class="modal-header header-layout">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title" id="titleAddFirmware">Cadastrar Associação</h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <h4 class="subtitle" id="dadosCadastro" style="padding-left: 0; padding-right: 0;">Dados para Associação</h4>
                            <div class='row'>
                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="usuarioAprovador">Usuário: </label>
                                    <input class="form-control" type="text" name="usuarioAprovador" id="usuarioAprovador" style="width: 100%;" disabled>
                                </div>
                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="codAprovador">Código do Aprovador: </label>
                                    <input class="form-control" type="text" name="codAprovador" id="codAprovador" style="width: 100%;" disabled>
                                </div>
                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="nomeAprovador">Nome Aprovador: </label>
                                    <input class="form-control" type="text" name="nomeAprovador" id="nomeAprovador" style="width: 100%;" disabled>
                                </div>
                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="limite">Limite:</label>
                                    <input class="form-control" type="text" name="limite" id="limite" style="width: 100%;" disabled>
                                </div>
                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="limiteMaximo">Limite Máximo:</label>
                                    <input class="form-control" type="text" name="limiteMaximo" id="limiteMaximo" style="width: 100%;" disabled>
                                </div>
                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="loginAprovador">Login: </label>
                                    <input class="form-control" type="text" name="loginAprovador" id="loginAprovador" style="width: 100%;" disabled>
                                </div>

                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="idAprovador">Aprovador: </label>
                                    <select class="form-control" type="text" name="idAprovador" id="idAprovador" style="width: 100%;" disabled>
                                    </select>
                                </div>

                                <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                    <label for="idUsuarioShow">Usuário: <span class="text-danger">*</span></label>
                                    <select class="form-control" type="text" name="isUsuario" id="idUsuarioShow" placeholder="Digite pelo menos 4 caracteres..." style="width: 100%;">
                                        <option value="">Digite o nome do usuário...</option>
                                    </select>
                                </div>

                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-group" style="flex-direction: row-reverse">
                            <button type="submit" style="text-align: left;" class="btn btn-success" id='btnSalvarAssociacao'>Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/AutorizacaoDeCompras', 'layout.css') ?>">
    <script type="text/javascript" src="<?= versionFile('assets/js/AutorizacaoDeCompras', 'AutorizadoresCadastrados.js') ?>"></script>

    <script>
        (function() {
            const appLocation = "";
            window.__basePath = appLocation;
        })();
        var Router = '<?= site_url('AutorizacaoDeCompras/AutorizacaoDeCompras') ?>';
        var BaseURL = '<?= base_url('') ?>';
        var idUsuarioLogado = '<?= $idUser; ?>'

        $(document).ready(function() {
            $(".metrica-card").height('auto');
            var height = Math.max($(".metrica-card").height());
            $(".metrica-card").height(height);
        });

        $(window).resize(function() {
            $(".metrica-card").height('auto');
            var height = Math.max($(".metrica-card").height());
            $(".metrica-card").height(height);
        })
    </script>