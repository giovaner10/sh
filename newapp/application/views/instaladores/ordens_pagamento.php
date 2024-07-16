<style>
    .ag-theme-alpine .ag-ltr .ag-header-select-all {
        margin-right: 5px;
    }

    .centralizado {
        display: flex;
        justify-content: center;
    }
</style>


<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/instaladores/OrdensPagamento', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/instaladores/OrdensPagamento', 'Exportacoes.js') ?>"></script>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= $titulo ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang("cadastro") ?> >
        <a style="text-decoration: none" href="<?= site_url('/instaladores/listar_instaladores') ?>"><?= lang("instaladores") ?></a> >
        <?= $titulo ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link selected' id="menu-os-pagar"><?= lang("os_pagar") ?></a>
                </li>
                <li>
                    <a class='menu-interno-link' id="menu-contas"><?= lang("enviado_contas") ?></a>
                </li>
            </ul>
        </div>

        <!-- <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container">
                        <label for="clienteBusca">Cliente:</label>
                        <input type="text" maxlength="200" name="clienteBusca" class="form-control" placeholder="Digite o cliente" id="clienteBusca" />
                    </div>
                    <div class="input-container">
                        <label for="vendedorBusca">Vendedor:</label>
                        <input type="text" maxlength="100" name="vendedorBusca" class="form-control" placeholder="Digite o vendedor" id="vendedorBusca" />
                    </div>
                    <div class="input-container">
                        <label for="contratoBusca">ID Contrato:</label>
                        <input type="text" maxlength="11" name="contratoBusca" class="form-control" placeholder="Digite o ID do contrato" id="contratoBusca" />
                    </div>
                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div> -->
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-os-pagar" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b style="margin-bottom: 5px;"><?= lang("os_pagar") ?>: </b>
                <div class="btn-div-responsive">
                    <button class="btn btn-primary" id="btnPagar" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-usd" aria-hidden="true"></i> Pagar</button>
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
            <div class="alert alert-info">* Os campos Data e Valor são obrigarórios!</div>
            <form id="form-os-pagar" style="display: flex; flex-wrap: wrap; justify-content: space-between; width: 100%;">
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <input name="pesquisaRapida" autocomplete="no" class="form-control" type="text" id="search-input-os-pagar" placeholder="Pesquisar" style="float: right; max-width: 200px; float: right; margin-bottom: 10px;">
            </form>
            <!-- <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div> -->

            <div style="position: relative;">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 530px">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-conteudo card-contas" style='margin-bottom: 20px; position: relative; display: none;'>
            <h3>
                <b style="margin-bottom: 5px;"><?= lang("enviado_contas") ?>: </b>
                <div class="btn-div-responsive">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonContas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_contas" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <form id="form-contas" style="display: flex; flex-wrap: wrap; justify-content: space-between; width: 100%;">
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-contas" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <input name="pesquisaRapida" autocomplete="no" class="form-control" type="text" id="search-input-contas" placeholder="Pesquisar" style="float: right; max-width: 200px; float: right; margin-bottom: 10px;">
            </form>
            <div style="position: relative;">
                <div class="wrapperContas">
                    <div id="tableContas" class="ag-theme-alpine my-grid" style="height: 530px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Modal-contas" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title" style="display: block; text-align: left;">Selecionar conta</h3>
            </div>
            <div class="modal-body">
                <div style="position: relative;" class="col-md-12" style="margin-bottom: 15px;">
                    <div class="wrapperContasInstalador">
                        <div id="tableContasInstalador" class="ag-theme-alpine my-grid" style="height: 530px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button id="pagar" onclick="nova_conta();" class="btn btn-success pull-left"><i class="fa fa-plus"></i> Criar nova conta</button>
                    <button onclick="selecionar_conta();" class="btn btn-success pull-left"><i class="fa fa-check"></i> Selecionar conta</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auth Modal -->
<div class="modal fade" id="modal-auth" class="modal-auth" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title" style="display: block; text-align: left;">Valor de serviço <b>REPROVADO</b>!</h3>
            </div>
            <div class="modal-body">
                <form class="col-md-12" style="margin-bottom: 15px;">
                    <h4 class="subtitle">O valor do serviço foi reprovado pelo sistema, pois ultrapassou o limite. Caso mesmo assim queira continuar com o lançamento, por favor inserir chave de liberação.</h4>
                    <div class="form-group">
                        <label for="pwdInst">Senha:</label>
                        <input type="password" class="form-control" id="pwdInst" name="pwdInst">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button class="btn btn-danger" id="close">Cancelar</button>
                    <button class="btn btn-success" id="auth">Autorizar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<!-- <div id="myModal" class="modal-auth">
    <div class="modal-content-auth">
        <div><h3>Valor de serviço <b>REPROVADO</b>!</h3><i>O valor do serviço foi reprovado pelo sistema. Caso mesmo assim queira continuar com o lançamento por favor inserir chave de liberação.</i></div>
        <div>
            <div class="form-group">
                <label for="pwd">Senha:</label>
                <input type="password" class="form-control" id="pwd" name="senha">
            </div>
            <div>
                <button class="btn btn-success" id="auth" ">Autorizar</button>
                <button class="btn btn-danger" id="close">Cancelar</button>
            </div>
        </div>
    </div>

</div> -->

<script>
    var Router = '<?= site_url('instaladores') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
    var idInstalador = '<?= $id ?>';
</script>