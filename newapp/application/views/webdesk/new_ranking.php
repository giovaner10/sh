<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("ranking_tickets") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('suporte') ?> >
        <a style="text-decoration: none" href="<?= site_url('/webdesk') ?>"><?= lang("gerenciador_tickets") ?></a> >
        <?= lang("ranking_tickets") ?>
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
                        <label for="clienteBusca">Nome do Cliente:</label>
                        <input type="text" maxlength="60" name="clienteBusca" class="form-control" placeholder="Digite o nome do cliente" id="clienteBusca" />
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
                <b style="margin-bottom: 5px;"><?= lang("ranking_tickets") ?>: </b>
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

<div id="detalhes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleBlacklist">Detalhes</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <!-- <h4 class="subtitle">Dados do Hot List</h4> -->
                    <div class='row'>
                        <div class="col-md-12 input-container form-group divSeguradoraBlacklist" style="height: 59px !important;">
                            <label for="clienteTicket">Cliente:</label>
                            <input type="text" name="clienteTicket" class="form-control" id="clienteTicket" readonly />
                        </div>
                        <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                            <label for="usuarioTickets">Usuário com mais tickets:</label>
                            <input type="text" name="usuarioTickets" class="form-control" id="usuarioTickets" readonly />
                        </div>
                        <div class="col-md-4 input-container form-group" style="height: 59px !important;">
                            <label for="qtdTickets">Quantidade de Tickets:</label>
                            <input type="text" name="qtdTickets" class="form-control" id="qtdTickets" readonly />
                        </div>
                        <div class="col-md-4 input-container form-group" style="height: 59px !important;">
                            <label for="mesTickets">Mês com maior número:</label>
                            <input type="text" name="mesTickets" class="form-control" id="mesTickets" readonly />
                        </div>
                        
                        <div class="col-md-4 input-container form-group" style="height: 59px !important;">
                            <label for="estadoCliente">Estado:</label>
                            <select type="text" name="estadoCliente" class="form-control" id="estadoCliente" disabled >
                                <option value="" disabled>Indefinido</option>
                                <option value="Adimplente">Adimplente</option>
                                <option value="Inadimplente">Inadimplente</option>
                            </select>
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

<style>
    h4.subtitle {
        padding: 10px 0px !important;
    }
</style>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/tickets', 'RankingTickets.js') ?>"></script>

<script>
    var Router = '<?= site_url('webdesk') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>