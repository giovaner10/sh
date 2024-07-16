<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente(lang('veiculos'), site_url('Homes'), lang('cadastros'), lang('veiculos'));
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important; padding: 10px 0px;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container">
                        <label class="control-label" for="selectBuscaTipo">Buscar por:</label>
                        <select class="form-control" name="selectBuscaTipo" id="selectBuscaTipo" type="text" style="width: 100%;" required>
                            <option value="placa" selected>Placa</option>
                            <option value="code">Código</option>
                            <option value="serial">Serial</option>
                            <option value="veiculo">Veículo</option>
                            <option value="cliente">Cliente</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <label class="control-label" id="labelForValor" for="filtrar-atributos">Placa:</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="Digite a placa..." id="filtrar-busca" required />
                    </div>

                    <div class="button-container">

                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparReiniciar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>
                </div>

            </form>
        </div>

        <!-- <div id="filtroLista" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Lista:</h4>
            <form id="formLista">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="filtrar-atributos">Código, Cliente, Veículo, Placa ou Serial</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="Código, Cliente, Veículo, Placa ou Serial" id="filtrar-atributos" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div> -->
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card"> <?= lang('veiculos'); ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
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
                <select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value=10 selected>10</option>
                    <option value=25>25</option>
                    <option value=50>50</option>
                    <option value=100>100</option>
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

                <div class="wrapperContatos">
                    <div id="tableContatos" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal posicao do veiculo -->
    <div id="modal_posicao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 id="myModalLabel1" style="color: #1C69AD !important;">Posição Equipamento</h4>
                </div>
                <div class="modal-body">
                    <div style="background-color: #f5f5f5; padding: 10px;">
                        <div id="posicaoveic"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDesvincular" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 style="color: #1C69AD !important;">Confirmar Desvinculação</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Placa</th>
                            <th>Serial</th>
                        </tr>
                        <tr>
                            <td id="placa-desvincular"></td>
                            <td id="serial-desvincular"></td>
                        </tr>
                    </table>
                    Tem certeza de que deseja desvincular o veículo?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="desvincularVeiculo()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal atualizacao do veiculo -->
<div class="modal fade" id="modalVeiculoCadastro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleWhitelist" style="color: #1C69AD !important;">Cadastro de Veículo</h3>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success bt-salvar-veiculo" id="salvar" data-tipo="atualizar">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal grupos -->
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: #1C69AD !important;">Grupos do Veiculo</h4>
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonGrupo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                    <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_grupo" style="min-width: 100px; top: 109px; right:initial; height: 91px;">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="myGrid" class="ag-theme-alpine" style="width: 100%; height: 500px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>

            </div>
        </div>
    </div>

</div>
<style>
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .geral-posicao {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }
</style>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/cadastro', 'cadastro.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/cadastro', 'modais.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/cadastro', 'exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/cadastro', 'exportacoesGruposVeiculos.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    var Router = '<?= site_url('cadastros') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var urlPosicao = '<?= site_url('/equipamentos/posicao/') ?>';
    var coordenadasDiaria = '<?= site_url('relatorio_coordenadas/getCoordenadasDia/') ?>';
    var coordenadaSemana = '<?= site_url('relatorio_coordenadas/getCoordenadasSemana/') ?>';
    var administrarVeiculos = '<?= $this->auth->is_allowed_block('administrar_veiculos') ?>';
    var cadastroVeiculo = '<?= site_url('cadastros/cadastro_veiculo_editar/') ?>'
    var gruposVeiculos = '<?= site_url('veiculos/listar_grupos_veiculos_ag') ?>';
    var envioComando = '<?= site_url('comandos/envio/') ?>';
    var desvincularComando = '<?= site_url('veiculos/desvincular_confirmado') ?>';
</script>