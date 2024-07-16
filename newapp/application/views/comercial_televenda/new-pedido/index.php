<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/pedidos', 'layout.css') ?>">

<div style="padding: 0 20px; margin-left: 15px">
    <div class="text-title">
        <h3>Pedidos</h3>
        <h4 style="">
            <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
            <?= lang('suporte') ?> >
            Pedidos
        </h4>
    </div>
    <div class="row">
        <div class="col-md-3" id="menu-lateral">
            <div class="card menu-interno">
                <h4 style="margin-bottom: 0px !important;">Menu</h4>
                <ul>
                    <li>
                        <a class='menu-interno-link selected' id="menu-show">Clientes</a>
                    </li>
                    <li>
                        <a class='menu-interno-link' id="menu-omnilink"
                            href="<?= base_url('ComerciaisTelevendas/Pedidos') ?>">Oportunidades</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9" id="conteudo">
            <div class="card-conteudo card-dados-show" style='margin-bottom: 20px; position: relative;'>
                <h3 style="align-items: center; text-align: center;">
                    <b>Clientes</b>
                    <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                        <div class="dropdown" style="margin-right: 10px;">
                            <button class="btn btn-primary" type="button" style="height: 36.5px;" id="novoCliente"
                                onclick="abrirModalCriarCliente()">
                                Novo Cliente
                            </button>
                            <button class="btn btn-primary" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                                <?= lang('exportar') ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"
                                id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                            </div>
                        </div>
                        <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left"
                            title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                            <img id="img_grid_expandir" class="img-expandir"
                                src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>"
                                posicao="posicao_grid_vertical" style="width: 25px;">
                        </button>
                    </div>
                </h3>
                <div style="margin-bottom: 15px;">
                    <select id="select-quantidade-por-pagina-clientes-televendas" class="form-control"
                        style="width: 100px; float: left; margin-top: 10px;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50" selected>50</option>
                        <option value="100">100</option>
                    </select>
                    <input type="text" id="pesquisarClientes" class="form-control" placeholder="Pesquisar"
                        style="max-width: 250px; margin-bottom: 10px; margin-top: 10px; float: right;"
                        oninput="pesquisarClientes()">
                </div>
                <div class="wrapperShow" style="height: 70vh">
                    <div id="gridClientes" class="ag-theme-alpine my-grid-show" style="height: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include_once('application/views/comercial_televenda/new-pedido/modal_criar_cliente.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript" src="<?= versionFile('media/js', 'html2pdf.bundle.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

<script type="text/javascript">

    const baseUrl = '<?php echo base_url() ?>';
    const siteUrl = '<?php echo site_url() ?>';

    const dadosVendedor = JSON.parse('<?= json_encode($this->auth->get_login_dados()) ?>');

</script>

<script type="text/javascript" src="<?= versionFile('assets/js/televendas/pedidos', 'index.js') ?>"></script>