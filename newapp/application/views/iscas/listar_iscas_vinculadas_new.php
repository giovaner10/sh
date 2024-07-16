<style>

</style>

<?php
include (dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente($titulo, site_url('Homes'), lang('iscas'), "Equipamentos > Iscas Vinculadas");
?>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container" >
                        <label for="coluna_search">Buscar Por:</label>
                        <select id="coluna_search" class="form-control" style="width:100%;">
                            <option value="cad_iscas.id">ID</option>
                            <option value="cad_iscas.serial" selected>Serial</option>
                            <option value="cad_iscas.descricao">Descrição</option>
                            <option value="cad_iscas.modelo">Modelo</option>
                            <option value="cad_iscas.marca">Marca</option>
                            <option value="cad_iscas.data_cadastro">Data de Cadastro</option>
                            <option value="cad_iscas.data_expiracao">Data de Expiração</option>
                            <option value="cad_iscas.status">Status</option>
                            <option value="cad_clientes.nome">Cliente</option>
                            <option value="cad_iscas.id_contrato">Contrato</option>
                        </select>
                    </div>

                    <div class="input-container" style="width:100%;">
                        <label for="search_tabela">Busca:</label>
                        <input type="search" id="search_tabela" class="form-control" name="search_tabela"
                            placeholder="Digite um serial">
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success search-button" id="btnBuscarEstoque" type="button"
                            style="width:100%;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default clear-search-button" id="btnResetEstoque" type="button"
                            style="margin-top: 10px;width:100%;"><i class="fa fa-leaf" aria-hidden="true"></i>
                            Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-relatorio-instalacao" style='margin-bottom: 20px; position: relative;'>
            <div class="tablePageHeader">
                <h3>Gerenciamento de Iscas Vinculadas: </h3>
                <div class="tableButtonWrapper">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonAcaoExterna"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            Operações <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-acoes opcoes_importacao dropdown-menu-importacao"
                            aria-labelledby="dropdownMenuButton" id="opcoes_acao" style="width: 200px; height: 123px;">
                            <h5 class="dropdown-title"> O que você deseja fazer? </h5>
                            <div class="dropdown-item opcao_importacao" id="btnDesvincular"
                                title="Desvincular iscas selecionadas">
                                <svg xmlns="http://www.w3.org/2000/svg" height="12" width="15" viewBox="0 0 640 512">
                                    <path fill="#ff004c"
                                        d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L489.3 358.2l90.5-90.5c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114l-96 96-31.9-25C430.9 239.6 420.1 175.1 377 132c-52.2-52.3-134.5-56.2-191.3-11.7L38.8 5.1zM239 162c30.1-14.9 67.7-9.9 92.8 15.3c20 20 27.5 48.3 21.7 74.5L239 162zM116.6 187.9L60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5l61.8-61.8-50.6-39.9zM220.9 270c-2.1 39.8 12.2 80.1 42.2 110c38.9 38.9 94.4 51 143.6 36.3L220.9 270z" />
                                </svg>
                                <span style="margin-left: 5px;">Desvincular</span>
                            </div>
                            <div class="dropdown-item opcao_importacao" id="btnExibirModalAlterarDataExpiracao"
                                title="Alterar data de expiração das iscas selecionadas">
                                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i>
                                Alterar Expiração
                            </div>
                        </div>
                    </div>
                    <div class="dropdown" id="dropdown_exportar">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.6px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao"
                            style="min-width: 100px; top: 67px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left"
                        title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>"
                            posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </div>

            <div id="notFoundMessage" style="display: none;">
                <h5>Nenhum dado encontrado para a pesquisa feita</h5>
            </div>
            <div class="registrosDiv">
                <select id="paginationSelect" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;" id="tabelaInstalacoes">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid-instaladores" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalMigrarIsca" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="migrarEquipamentoModalLabel">Migrar Equipamento</h3>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <form id="editarContaBancariaInstaladorForm">
                    <div class="modal-body-container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Isca</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="">Serial</label>
                                <h5 id="serialIsca"></h5>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Descrição</label>
                                <h5 id="descricaoIsca"></h5>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Data de Cadastro</label>
                                <h5 id="dataCadastroIsca"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="">Modelo</label>
                                <h5 id="modeloIsca"></h5>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Marca</label>
                                <h5 id="marcaIsca"></h5>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Status</label>
                                <h5 id="statusIsca"></h5>
                            </div>
                        </div>
                        <!-- Dados Cliente -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Cliente Atual</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="">Nome do Cliente</label>
                                <h5 id="nomeCliente"></h5>
                            </div>
                            <div class="col-sm-4">
                                <label>CNPJ</label>
                                <h5 id="cnpjCliente"></h5>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Data do Contrato</label>
                                <h5 id="dataContrato"></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Migrar Isca Para</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <select class="form-control" name="tipoBuscaCliente" id="tipoBuscaCliente">
                                    <option value="cnpj" selected>CNPJ</option>
                                    <option value="cpf">CPF</option>
                                    <option value="id_contrato">Contrato</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="cnpjBuscarNovoCliente"
                                        placeholder="Buscar Cliente por CNPJ">
                                    <input type="text" class="form-control" id="cpfBuscarNovoCliente"
                                        placeholder="Buscar Cliente por CPF" style="display: none;">
                                    <input type="text" class="form-control numeric" id="idContratoBuscarNovoCliente"
                                        placeholder="Buscar Cliente por Contrato" style="display: none;">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <a id="btnBuscarClientePorCNPJ" class="btn btn-primary" title="Buscar Cliente"><i
                                        class="fa fa-search" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <!-- Dados novo Cliente -->
                        <div id="dadosNovoCliente" style="display: none;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="">Nome do Cliente</label>
                                    <h5 id="nomeNovoCliente"></h5>
                                </div>
                                <div class="col-sm-6">
                                    <label id="labelTipoBusca" for="">CNPJ</label>
                                    <h5 id="cnpjNovoCliente"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="">Endereço</label>
                                    <h5 id="enderecoNovoCliente"></h5>
                                </div>
                            </div>
                            <input type="hidden" id="id_isca" name="id_isca">
                            <input type="hidden" id="idNovoCliente" name="idNovoCliente">
                            <input type="hidden" id="idContratoNovoCliente" name="idContratoNovoCliente">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button type="button" class="btn btn-success" id="MigrarIsca">Migrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal adicionado dinamicamente para alterar data de expiracao das iscas -->
<div id="modalDataExpiracao"></div>

<script>
    var Router = '<?= site_url('iscas/isca') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

<!-- Estilo do botão status -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/iscas/iscasVinculadas', 'layout.css') ?>">

<link rel="stylesheet" type="text/css" href="<?= base_url("media/css/toggle-button.css") ?>">
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("media/js/helpers/DataExpiracaoIscaAgGrid.js") ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/iscas/iscasVinculadas', 'Exportacoes.js') ?>"></script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>

<script type="text/javascript" src="<?= versionFile('assets/js/iscas/iscasVinculadas', 'index.js') ?>"></script>