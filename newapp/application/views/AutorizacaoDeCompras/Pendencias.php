<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("autorizacoes_pendentes") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('autorizacao_de_compras') ?> >
        <?= lang('autorizacoes_pendentes') ?>
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
                    <a class='menu-interno-link <?= $menu_autorizacao == 'AutorizadoresPendentes' ? 'selected' : '' ?>' id="menu-autorizadores-pendentes"><?= lang("autorizacoes_pendentes") ?></a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="dataInicial">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" />
                    </div>

                    <div class="input-container buscaData" id="dateContainer2">
                        <label for="dataFinal">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" />
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
        <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative; <?= $menu_autorizacao == 'AutorizadoresPendentes' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("autorizacoes_pendentes") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button id="aprovarSelecionados" class="btn btn-primary" style="height: 36.5px;">Aprovar / Rejeitar</button>
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

    <div id="infoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                        <h3 class="modal-title modalCenter" id="infoModalLabel">Detalhes do Pedido # <span id="modalPedido"></span></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div style="display: flex; flex-direction: column; flex-direction: flex-start">
                        <div class="modal">
                        </div>
                        <div class="dados-gerais">
                            <h5>Fornecedor: <span id="modalFornecedor"></span> | Emissão: <span id="modalEmissao"></span> | Cond. Pagamento: <span id="modalCondPagamento"></span> | Moeda: <span id="modalMoeda"></span></h5>
                            <h5>Aprovador: <span id="modalAprovador" style="font-weight: bold;"> </span></h5>
                            <h4>Comprador: <strong><span id="modalComprador" style="font-weight: bold;"></span></strong></h4>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="tabela-itens">
                        <h4 style="text-align: center">Itens do Pedido de Compra</h4>
                        <div class="wrapperModal">
                            <div id="tableModal" class="ag-theme-alpine my-grid-modal" style="height: 500px">
                            </div>
                        </div>
                        <h4 style="text-align: rigth">Valor Total do Pedido: <span id="modalValorTotalPedido"></span></h4>
                    </div>

                </div>

                <hr class="custom-separator">


                <div class="form-approval" style="margin: 20px;">
                    <h4 class="aprovarLabel">Aprovação:</h4>
                    <div class="radio-options">
                        <label><input type="radio" name="aprovacao" value="sim" style="margin-right: 10px;">Sim </label>
                    </div>
                    <div class="radio-options">
                        <label><input type="radio" name="aprovacao" value="nao" style="margin-right: 10px;">Não (Especificar o motivo no espaço abaixo)</label>
                    </div>
                    <textarea id="motivoRejeicao" placeholder="Digite o motivo da rejeição aqui..." style="width: 100%; margin-top: 10px;" rows="5" maxlength="255" disabled></textarea>
                    <div id="contador" style="text-align: right;">Restam 255 caracteres</div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group" style="flex-direction: row-reverse">
                            <button type="button" class="btn btn-success" id="enviarModal">Enviar</button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const appLocation = "";
            window.__basePath = appLocation;
        })();
        var Router = '<?= site_url('AutorizacaoDeCompras/AutorizacaoDeCompras') ?>';
        var BaseURL = '<?= base_url('') ?>';
        var idUsuarioLogado = '<?= $idUser; ?>'
        var nomeUsuario = '<?= $nomeUsuario; ?>'
        var emailUsuario = '<?= $email; ?>'

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


    <script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/AutorizacaoDeCompras', 'layout.css') ?>">
    <script type="text/javascript" src="<?= versionFile('assets/js/AutorizacaoDeCompras', 'Pendencias.js') ?>"></script>