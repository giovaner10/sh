<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 0;">
    <div class="col-md-3" style="padding-left: 0;">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="filtrar-atributos-base">Providência:</label>
                        <input type="text" name="filtrar-atributos-base" class="form-control" placeholder="Filtrar Base Instalada" id="filtrar-atributos-base" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltroOcorrencias" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo" style="padding: 0;">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card" style="margin-left: 5px;">Base Instalada: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">

                    <button class="btn btn-primary" id="btnAddBase" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Nova Base Instalada</button>

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
                    <div id="tableBaseInstalada" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalProvidencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="formProvidencia" id="formProvidencia">
                <div class="modal-header">
                    <h3 style="margin-top: 10px" id="titleModalProvi">Visualizar Providência <span id="tz_name"></span></h3>
                    <button style="margin-top: -37px" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="padding: 0 !important">
                    <div class="pos-venda-container">
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Pergunta e Resposta</div>
                            <div class="pos-venda-info-group">
                                <div class="pos-venda-info"><strong>Pergunta:</strong> <span id="pergunta"></span></div>
                                <div class="pos-venda-info"><strong>Resposta:</strong> <span id="resposta"></span></div>
                            </div>
                        </div>
                        <div id="infoPessoasProvidencia"></div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;">
                    <button type="button" class="btn btn-secondary" id="fecharModalSalvarProvidencia" data-dismiss="modal">Fechar</button>
                    <button type="submit" style="margin-left: auto;" class="btn btn-success" id="btnSalvarProvidencia" disabled>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalBaseInstalada" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title titulo-base-instalada" id="myModalLabel">Base Instalada</h3>
            </div>
            <form id="formBaseInstalada">
                <input type="hidden" id="base_instalada_tz_base_instalada_clienteid">
                <div class="modal-body max_height_modal" style="padding: 0 15px;">
                    <!-- GERAL -->
                    <div class="div_row_geral">
                        <!-- CLIENTE -->
                        <div class="div_row_cliente">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Cliente</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente PJ (Contrato)</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_pjid" name="base_instalada_tz_cliente_pjid"></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente PF (Contrato)</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_pfid" name="base_instalada_tz_cliente_pfid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente PJ (Matriz)</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_pj_matrizid" name="base_instalada_tz_cliente_pj_matrizid"></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente PF (Matriz)</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_pf_matrizid" name="base_instalada_tz_cliente_pf_matrizid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente PJ (Instalado)</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_pj_instaladoid" name="base_instalada_tz_cliente_pj_instaladoid"></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente PF (Instalado)</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_pf_instaladoid" name="base_instalada_tz_cliente_pf_instaladoid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente Anterior PJ</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_anterior_pj" name="base_instalada_tz_cliente_anterior_pj"></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cliente Anterior PF</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_anterior_pf" name="base_instalada_tz_cliente_anterior_pf"></select>
                                </div>
                            </div>
                        </div>

                        <!-- VEICULO -->
                        <div class="div_row_veiculo">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Veículo</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Veículo</label>
                                    <select class="form-control tz_veiculos" name="base_instalada_tz_veiculoid" id="base_instalada_tz_veiculoid"></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Item de Contrato</label>
                                    <select class="form-control tz_item_contrato_vendas" name="base_instalada_tz_item_contratoid" id="base_instalada_tz_item_contratoid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Marca</label>
                                    <select class="form-control tz_marcas" name="base_instalada_tz_marcaid" id="base_instalada_tz_marcaid"></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Modelo</label>
                                    <select class="form-control tz_modelos" name="base_instalada_tz_modelo_ativacao" id="base_instalada_tz_modelo_ativacao"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Chassi</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_chassi" id="base_instalada_tz_chassi" />
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Cor</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_cor" id="base_instalada_tz_cor" />
                                </div>
                            </div>
                        </div>

                        <!-- PRODUTO -->
                        <div class="div_row_produto">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Produto</b></h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label class="control-label">Produto</label>
                                    <select class="form-control products" name="base_instalada_tz_produtoid" id="base_instalada_tz_produtoid" required></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label class="control-label">Número de Série</label>
                                    <input type="text" class="form-control" id="base_instalada_tz_numero_serie" name="base_instalada_tz_numero_serie" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label class="control-label">Tipo de Produto</label>
                                    <select class="form-control" name="base_instalada_tz_tipo_produto" id="base_instalada_tz_tipo_produto" required>
                                        <option title=""></option>
                                        <option value="1" title="Rastreador">Rastreador</option>
                                        <option value="6" title="Rastreador Telemetria">Rastreador Telemetria</option>
                                        <option value="2" title="Antena Satelital">Antena Satelital</option>
                                        <option value="5" title="Acessórios">Acessórios</option>
                                        <option value="4" title="Terminal">Terminal</option>
                                        <option value="3" title="Trava">Trava</option>
                                    </select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Data de Desinstalação</label>
                                    <input type="date" class="form-control" id="base_instalada_tz_data_desinstalacao" name="base_instalada_tz_data_desinstalacao">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Data de Instalação</label>
                                    <input type="date" class="form-control" id="base_instalada_tz_data_instalacao" name="base_instalada_tz_data_instalacao">
                                </div>
                            </div>
                        </div>

                        <!-- RASTREADOR -->
                        <div class="div_row_rastreador">
                            <div class="col-md-12" style="padding: 0;">
                                <h4><b>Rastreador</b></h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label class="control-label">Plataforma</label>
                                    <select class="form-control tz_plataformas" name="base_instalada_tz_plataformaid" id="base_instalada_tz_plataformaid" required></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label class="control-label">Tecnologia</label>
                                    <select class="form-control tz_tecnologias" name="base_instalada_tz_tecnologiaid" id="base_instalada_tz_tecnologiaid" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Local do Rastreador</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_local_rastreador" id="base_instalada_tz_local_rastreador">
                                </div>
                            </div>
                        </div>

                        <!-- CHIP 1 -->
                        <div class="div_row_chip1">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Chip 1</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Simcard</label>
                                    <input type="text" class="form-control chip" name="base_instalada_tz_simcard1" id="base_instalada_tz_simcard1">
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Linha</label>
                                    <input type="tel" class="form-control phone" name="base_instalada_tz_linha1" id="base_instalada_tz_linha1" placeholder="(xx) 9xxxx-xxxx">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Operadora</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_operadora1" id="base_instalada_tz_operadora1">
                                </div>
                            </div>
                        </div>
                        <!-- CHIP 2 -->
                        <div class="div_row_chip2">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Chip 2</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Simcard</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_simcard2" id="base_instalada_tz_simcard2">
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Linha</label>
                                    <input type="tel" class="form-control phone" name="base_instalada_tz_linha2" id="base_instalada_tz_linha2" placeholder="(xx) 9xxxx-xxxx">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Operadora</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_operadora2" id="base_instalada_tz_operadora2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FICHA DE ATIVAÇÃO -->
                    <div class="div_row_ficha_ativacao">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Ficha de Ativação</h4>
                            </div>
                        </div>

                        <div class="div_row_informacoes">
                            <div class="row">
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Grupo de Emails do Cliente</label>
                                    <select class="form-control tz_grupo_emails_clientes" name="base_instalada_tz_grupo_emails_clienteid" id="base_instalada_tz_grupo_emails_clienteid"></select>
                                </div>
                                <div class="col-md-6 input-container input-container-aux">
                                    <label>Versão do Firmware</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_versao_firmware" id="base_instalada_tz_versao_firmware" maxlength="6">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 11px;">
                                <div class="col-md-12">
                                    <label>Observações</label>
                                    <textarea class="form-control" name="base_instalada_tz_observacoes" id="base_instalada_tz_observacoes" rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="div_row_sensores_atuadores" id="div_row_sensores_atuadores" hidden>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Sensores e Atuadores</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Insere selects dinamicamente -->
                                <div id="selects_sensores_atuadores_base_estalada"></div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="padding: 15px 0px;">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                        <button class="btn btn-success" id="btnSubmitBaseInstalada" type="submit">Salvar</button>
                    </div>
                </div>
            </form>
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

    .input-container-aux {
        margin-top: 8px;
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<!-- //<script type="text/javascript" src="<?= versionFile('assets/js/posvendas', 'exportacoes.js') ?>"></script> -->

<script>
    // var Router = '<?= site_url('Empresas/ContatosCorporativos') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var emailUser = cliente_selecionado_atual?.email

    $(document).ready(async function() {

        var result = [];

        let edicaoModal = 'nao';
        let id_usuario = 0;
        emailUser = cliente_selecionado_atual?.email


        cliente_selecionado_atual.clienteAuxiliarModel.idCrm = '6bc95e40-41b4-de11-9386-00237de5099c' ///lembrar de apagar

        let usuario_logado = '<?= site_url("PaineisOmnilink") ?>'

        async function buscarDadosAgGrid() {
            $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
            cliente_selecionado_atual.cnpj = '02.884.318/0001-08' //remover
            await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_listar_bases_instaladas/${cliente_selecionado_atual.cnpj ? "pj" : "pf"}/${cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm}`,
                type: 'GET',
                data: {
                    'search[value]': $('#filtrar-atributos-base').val(),
                    length: 100,
                    start: 0,
                },
                success: function(response) {
                    if (response.status == 200) {
                        updateData(response.data)
                    } else {
                        updateData([])
                    }
                },
                error: function(xhr, status, error) {
                    showAlert("error", "Erro ao receber dados, contate o suporte técnico!");
                }
            });
        }

        const gridOptions = {
            columnDefs: [{
                    headerName: "Nome",
                    field: "nome",
                    valueGetter: function(params) {
                        return params.data.nome !== null ? params.data.nome : "";
                    }
                },
                {
                    headerName: "Veículo",
                    field: "placa_veiculo",
                    valueGetter: function(params) {
                        return params.data.placa_veiculo !== null ? params.data.placa_veiculo : "";
                    }
                },
                {
                    headerName: "Data de Instalação",
                    field: "data_instalacao",
                    valueGetter: function(params) {
                        return params.data.data_instalacao !== null ? params.data.data_instalacao : "";
                    }
                },
                {
                    headerName: "Data de Desinstalação",
                    field: "data_desinstalacao",
                    valueGetter: function(params) {
                        return params.data.data_desinstalacao !== null ? params.data.data_desinstalacao : "";
                    }
                },
                {
                    headerName: "Produto",
                    field: "nome_produto",
                    valueGetter: function(params) {
                        return params.data.nome_produto !== null ? params.data.nome_produto : "";
                    }
                },
                {
                    headerName: "Número De Série",
                    field: "numero_serie",
                    valueGetter: function(params) {
                        return params.data.numero_serie !== null ? params.data.numero_serie : "";
                    }
                },
                {
                    headerName: 'Ações',
                    width: 80,
                    pinned: 'right',
                    cellClass: "actions-button-cell",
                    suppressMenu: true,
                    sortable: false,
                    filter: false,
                    resizable: false,
                    cellRenderer: function(options) {
                        let data = options.data;

                        let tableId = "tableBaseInstalada";
                        let dropdownId = "dropdown-menu" + data.id;
                        let buttonId = "dropdownMenuButton_" + data.id;
                        let i = options.rowIndex;
                        let ajusteAltura = 0;
                        let paginaAtual = gridOptions.api.paginationGetCurrentPage();
                        let qtd = $('#select-quantidade-por-contatos-corporativos').val()


                        if (paginaAtual > 0) {
                            i = i - (paginaAtual) * qtd
                        }

                        if (i > 9) {
                            i = 9;
                        }

                        if (i > 4) {
                            ajusteAltura = 330;
                        } else if (i > 2) {
                            ajusteAltura = 40;
                        } else {
                            ajusteAltura = 0;
                        }
                        return `
                            <div class="dropdown dropdown-table" data-tableId=${tableId}>
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}%);" aria-labelledby="${buttonId}">
                                   
                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-id=${data.id} class="modalRemoverBase_acoes" title="Remover"> Remover</a>
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-id=${data.id} class="modalEditarBase_acoes" title="Editar"> Editar</a>
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-prod=${data.nome_produto} data-serie=${data.numero_serie} class="modalTicketBase_acoes" title="Abrir Ticket Movidesk"> Abrir Ticket Movidesk</a>
                                    </div>

                                </div>
                            </div>`;
                    },
                },
            ],
            rowData: [],
            getRowId: params => params.data.id,
            pagination: true,
            defaultColDef: {
                resizable: true,
            },
            sideBar: {
                toolPanels: [{
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100,
                    },
                }, ],
                defaultToolPanel: false,
            },
            paginationPageSize: $("#select-quantidade-por-contatos-corporativos").val(),
            localeText: AG_GRID_LOCALE_PT_BR,
        };
        var gridDiv = document.querySelector("#tableBaseInstalada");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        preencherExportacoes(gridOptions, "RelatorioDeBasesIntaladas", ["data_desinstalacao", "data_instalacao", "id", "nome", "nome_produto", "numero_serie", "placa_veiculo"]);

        function updateData(newData = []) {
            gridOptions.api.setRowData(newData);
        }

        await buscarDadosAgGrid();

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
        });

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
            gridOptions.api.refreshCells({
                force: true
            });
        });

        $(document).on('click', '.btn-expandir', async function(e) {
            e.preventDefault();
            menuAberto = !menuAberto;

            if (menuAberto) {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-show.svg`
                );
                $("#filtroBusca").hide();
                $(".menu-interno").hide();
                $("#conteudo").removeClass("col-md-9");
                $("#conteudo").addClass("col-md-12");
                $('.tab-acoes-pesquisa').hide()
            } else {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-hide.svg`
                );
                $("#filtroBusca").show();
                $(".menu-interno").show();
                $("#conteudo").css("margin-left", "0px");
                $("#conteudo").removeClass("col-md-12");
                $("#conteudo").addClass("col-md-9");
                $('.tab-acoes-pesquisa').show()

            }
        });

        $("#BtnLimparFiltroOcorrencias").on("click", async function() {
            if ($('#filtrar-atributos-base').val().trim().length > 0) {
                $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando');
                $('#BtnPesquisar').prop('disabled', true);
                $('#BtnLimparFiltroOcorrencias').prop('disabled', true);
                $("#filtrar-atributos-base").val("");
                updateData()
                await buscarDadosAgGrid()
                $('#BtnPesquisar').html('<i class="fa fa-search"></i> Buscar');
                $('#BtnPesquisar').prop('disabled', false);
                $('#BtnLimparFiltroOcorrencias').prop('disabled', false);
            }
        });


        $('#BtnPesquisar').click(async function(e) {
            e.preventDefault()
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando');
            $(this).prop('disabled', true);
            $('#BtnLimparFiltroOcorrencias').prop('disabled', true);
            updateData()
            await buscarDadosAgGrid()
            $(this).html('<i class="fa fa-search"></i> Buscar');
            $(this).prop('disabled', false);
            $('#BtnLimparFiltroOcorrencias').prop('disabled', false);

            gridOptions.api.refreshCells({
                force: true
            })
        })

        async function salvar_auditoria(url_api, clause, valores_antigos, valores_novos) {
            if (valores_antigos && typeof(valores_antigos) === 'object') {
                valoresAuditoriaAntigo = [];
                Object.keys(valores_antigos).forEach(i => {
                    valoresAuditoriaAntigo.push(i)
                });
            } else {
                valoresAuditoriaAntigo = valores_antigos;
            }
            return new Promise((resolve, reject) => {
                const cpf_cpnj_cliente = (cliente_selecionado_atual.cnpj ? cliente_selecionado_atual.cnpj : cliente_selecionado_atual.cpf).replace(/[^0-9]/g, '');
                $.ajax({
                    url: URL_PAINEL_OMNILINK + '/ajax_salvar_auditoria',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        url_api,
                        clause,
                        valoresAuditoriaAntigo,
                        valores_novos,
                        cpf_cpnj_cliente
                    },
                    success: function(callback) {
                        if (callback.status) {
                            resolve(callback);
                        } else {
                            showAlert("error", 'Erro ao salvar auditoria! Não foi possível completar a operação.');
                            reject(callback);
                        }
                    },
                    error: function(error) {
                        showAlert("error", 'Erro ao salvar auditoria! Não foi possível completar a operação.');
                        reject(error);
                    }

                });
            });
        }


        function removeRowFromGrid(rowId) {
            const rowNode = gridOptions.api.getRowNode(rowId);
            gridOptions.api.applyTransaction({
                remove: [rowNode.data]
            });
        }

        //modal remover base
        let idBaseInstalada = 0
        $(document).on('click', '.modalRemoverBase_acoes', async function() {
            idBaseInstalada = $(this).data('id');
            Swal.fire({
                title: "Atenção!",
                text: "Você tem certeza que deseja excluir a base instalada do cliente?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007BFF",
                cancelButtonColor: "#d33",
                confirmButtonText: "Continuar",
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ShowLoadingScreen()
                    const url = `${URL_PAINEL_OMNILINK}/ajax_remover_base_instalada/${idBaseInstalada}`;
                    salvar_auditoria(url, 'delete', null, {
                            idBaseInstaladaRemovida: idBaseInstalada
                        })
                        .then(async () => {
                            await $.ajax({
                                url: url,
                                type: 'DELETE',
                                success: function(callback) {
                                    callback = JSON.parse(callback);
                                    if (callback.status) {
                                        removeRowFromGrid(idBaseInstalada)
                                        showAlert("success", "Base Instalada removida com sucesso!");
                                        HideLoadingScreen()
                                    } else {
                                        showAlert("error", 'Erro ao excluir Base Instalada!');
                                        HideLoadingScreen()
                                    }
                                },
                                error: function(error) {
                                    showAlert("error", 'Erro ao excluir Base Instalada!');
                                    HideLoadingScreen()
                                }
                            });
                        })
                        .catch(error => {
                            showAlert("error", 'Erro ao excluir Base Instalada!');
                            HideLoadingScreen()
                        });
                }
            })

        })



        // abrir tickert movie desk
        $(document).on('click', '.modalTicketBase_acoes', async function() {

            let numSerie = $(this).data('serie')
            let nomeProd = $(this).data('prod')

            let str = "Produto: " + nomeProd + "\nNúmero de Série: " + numSerie + " ";
            $('#assuntoMovidesk').val("Base Instalada - CPF/CNPJ: " + cliente_selecionado_atual.cnpj ? cliente_selecionado_atual.cnpj : cliente_selecionado_atual.cpf);
            $('#mensagemMovidesk').val(str);
            $(document).off('servicosPaiLoaded.ticketPersonalizado');
            $(document).on('servicosPaiLoaded.ticketPersonalizado', function() {
                $('#servicoMovidesk').val(788489).trigger('change');
                $(document).off('servicosPaiLoaded.ticketPersonalizado');
            });
            $(document).off('servicosFilhoLoaded.ticketPersonalizado');
            $(document).on('servicosFilhoLoaded.ticketPersonalizado', function() {
                $('#subservicoMovidesk').val(788493).trigger('change');
                $('#urgenciaMovidesk').val(2).trigger('change');
                $(document).off('servicosFilhoLoaded.ticketPersonalizado');
            });

            $('#ccMovidesk').val("lucas.cabral@omnilink.com.br");
            $('#clienteIdMovidesk').val(cliente_selecionado_atual?.id);
            $('#prestadoraMovidesk').val(1);

            emailUser = cliente_selecionado_atual?.email
            $('#modalMovidesk').modal('show')

        })


        // adicionar base btnAddBase
        let isEditBase = false

        $('#base_instalada_tz_tipo_produto').select2({
            width: '100%'
        });

        $(document).on('click', '#btnAddBase', async function() {

            isEditBase = false
            resetSelects2()
            limpaInputsModal('formBaseInstalada');
            const nomeCliente = cliente_selecionado_atual?.nome;
            const idCliente = cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm
            const option = `<option value="${idCliente}">${nomeCliente}</option>`;
            $('.titulo-base-instalada').text('Adicionar Base Instalada')
            $("#selects_sensores_atuadores_base_estalada").html('');
            $("#div_row_sensores_atuadores").hide();
            if (cliente_selecionado_atual.cnpj) {
                $("#base_instalada_tz_cliente_pfid").attr('disabled', true);
                $("#base_instalada_tz_cliente_pf_matrizid").attr('disabled', true);
                $("#base_instalada_tz_cliente_pf_instaladoid").attr('disabled', true);
                $("#base_instalada_tz_cliente_anterior_pf").attr('disabled', true);
                $("#base_instalada_tz_cliente_pjid").html(option).trigger('change.select2');
                $("#base_instalada_tz_cliente_pj_matrizid").html(option).trigger('change.select2');
                $("#base_instalada_tz_cliente_pj_instaladoid").html(option).trigger('change.select2');
            } else {
                $("#base_instalada_tz_cliente_pjid").attr('disabled', true);
                $("#base_instalada_tz_cliente_pj_matrizid").attr('disabled', true);
                $("#base_instalada_tz_cliente_pj_instaladoid").attr('disabled', true);
                $("#base_instalada_tz_cliente_anterior_pj").attr('disabled', true);
                $("#base_instalada_tz_cliente_pfid").html(option).trigger('change.select2');
                $("#base_instalada_tz_cliente_pf_matrizid").html(option).trigger('change.select2');
                $("#base_instalada_tz_cliente_pf_instaladoid").html(option).trigger('change.select2');
            }
            $('#modalBaseInstalada').modal('show');
        })

        function resetSelects2() {
            $("#base_instalada_tz_cliente_pfid").attr('disabled', false);
            $("#base_instalada_tz_cliente_pf_matrizid").attr('disabled', false);
            $("#base_instalada_tz_cliente_pf_instaladoid").attr('disabled', false);
            $("#base_instalada_tz_cliente_anterior_pf").attr('disabled', false);
            $("#base_instalada_tz_cliente_pjid").attr('disabled', false);
            $("#base_instalada_tz_cliente_pj_matrizid").attr('disabled', false);
            $("#base_instalada_tz_cliente_pj_instaladoid").attr('disabled', false);
            $("#base_instalada_tz_cliente_anterior_pj").attr('disabled', false);
        }

        function limpaInputsModal(idForm) {
            $(`#${idForm}`).find('select,input,textarea').each(function() {
                $(this).val('').trigger('change');
            });
        }

        function getConfigBuscaServerSideSelect2(url, id, name, placeholder, minimumInputLength = 3) {
            return {
                ajax: {
                    url: `${URL_PAINEL_OMNILINK}/ajax_search_select/${url}`,
                    dataType: 'json',
                    delay: 2000,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: id,
                            name: name
                        }
                    }
                },
                width: '100%',
                placeholder: placeholder,
                allowClear: true,
                minimumInputLength: minimumInputLength,
            }
        }

        $("#tz_rastreadorid").select2(
            getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o rastreador")
        );
        $("#tz_plano_linkerid").select2(
            getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o plano")
        );
        let selectProvidencias = $("#tz_providenciasid").select2({
            width: '100%',
            placeholder: "Selecione a providência",
            allowClear: true,
        });
        $("#tz_indice_reajusteid").select2(
            getConfigBuscaServerSideSelect2('tz_indice_reajustes', 'tz_indice_reajusteid', 'tz_name', "Selecione o índice de reajuste")
        );
        $("#tz_item_contrato_originalid").select2(
            getConfigBuscaServerSideSelect2('tz_item_contrato_vendas', 'tz_item_contrato_vendaid', 'tz_name', "Selecione o item de contrato original")
        );
        $("#tz_motivo_alteracao").select2(
            getConfigBuscaServerSideSelect2('tz_motivo_cancelamentos', 'tz_motivo_cancelamentoid', 'tz_name', "Selecione o motivo da alteração")
        );
        $("#tz_plataformaid").select2(
            getConfigBuscaServerSideSelect2('tz_plataformas', 'tz_plataformaid', 'tz_name', "Selecione a plataforma")
        );
        $("#tz_cenario_vendaid").select2(
            getConfigBuscaServerSideSelect2('tz_cenario_vendas', 'tz_cenario_vendaid', 'tz_name', "Selecione o cenário de venda")
        );
        $("#tz_tecnologiaid").select2(
            getConfigBuscaServerSideSelect2('tz_tecnologias', 'tz_tecnologiaid', 'tz_name', "Selecione a tecnologia")
        );
        $("#tz_veiculoid").select2(
            getConfigBuscaServerSideSelect2('tz_veiculos', 'tz_veiculoid', 'tz_placa', "Selecione o veículo")
        );
        $("#alteracao_contrato_tz_motivoid").select2(
            getConfigBuscaServerSideSelect2('tz_motivo_manutencao_contratos', 'tz_motivo_manutencao_contratoid', 'tz_name', "Selecione o motivo")
        );
        $("#alteracao_contrato_tz_incidentid").select2({
            width: '100%',
            placeholder: "Selecione a ocorrência",
            allowClear: true,
        });
        $("#servico_contratado_tz_codigo_item_contratoid").select2({
            width: '100%',
            placeholder: "Selecione o item de contrato",
            allowClear: true,
        });
        $("#servico_contratado_tz_grupo_receitaid").select2(
            getConfigBuscaServerSideSelect2('tz_grupo_receitas', 'tz_grupo_receitaid', 'tz_name', "Selecione o grupo de receita")
        );
        $("#servico_contratado_tz_classificacao_produtoid").select2(
            getConfigBuscaServerSideSelect2('tz_classificacao_produtos', 'tz_classificacao_produtoid', 'tz_name', "Selecione a classificação de produto")
        );
        $("#servico_contratado_transactioncurrencyid").select2(
            getConfigBuscaServerSideSelect2('transactioncurrencies', 'transactioncurrencyid', 'currencyname', "Selecione a moeda")
        );
        $("#servico_contratado_tz_produtoid").select2(
            getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o serviço")
        );
        $(".accounts").select2(
            getConfigBuscaServerSideSelect2('accounts', 'accountid', 'name', "Selecione o cliente Pessoa Jurídica")
        );
        $(".contacts").select2(
            getConfigBuscaServerSideSelect2('contacts', 'contactid', 'fullname', "Selecione o cliente pessoa Física")
        );
        $(".tz_veiculos").select2(
            getConfigBuscaServerSideSelect2('tz_veiculos', 'tz_veiculoid', 'tz_placa', "Selecione o veículo")
        );
        $(".tz_item_contrato_vendas").select2(
            getConfigBuscaServerSideSelect2('tz_item_contrato_vendas', 'tz_item_contrato_vendaid', 'tz_name', "Selecione o item de contrato")
        );
        $(".tz_marcas").select2(
            getConfigBuscaServerSideSelect2('tz_marcas', 'tz_marcaid', 'tz_name', "Selecione a marca")
        );
        $(".products").select2(
            getConfigBuscaServerSideSelect2('products', 'productid', 'name', "Selecione o produto")
        );
        $(".tz_plataformas").select2(
            getConfigBuscaServerSideSelect2('tz_plataformas', 'tz_plataformaid', 'tz_name', "Selecione a plataforma")
        );
        $(".tz_tecnologias").select2(
            getConfigBuscaServerSideSelect2('tz_tecnologias', 'tz_tecnologiaid', 'tz_name', "Selecione a tecnologia")
        );
        $(".tz_grupo_emails_clientes").select2(
            getConfigBuscaServerSideSelect2('tz_tecnologias', 'tz_grupo_emails_clienteid', 'tz_name', "Selecione o grupo de email do cliente")
        );
        $(".tz_modelos").select2(
            getConfigBuscaServerSideSelect2('tz_modelos', 'tz_modeloid', 'tz_name', "Selecione o modelo")
        );

        function getDadosForm(idForm) {
            let dadosForm = $(`#${idForm}`).serializeArray();
            let dados = {};
            for (let c in dadosForm) {
                dados[dadosForm[c].name] = dadosForm[c].value;
            }

            return dados;
        }

        let valor_antigo_base_instalada = {}


        $("#formBaseInstalada").submit(async event => {
            event.preventDefault();
            let data = getDadosForm("formBaseInstalada");

            if (data.base_instalada_tz_cliente_pjid === "undefined") {
                showAlert("warning", 'É necessário informar o cliente.')
                return;
            }

            if (data.base_instalada_tz_operadora1) {
                if (isNaN(parseInt(data.base_instalada_tz_operadora1))) {
                    showAlert("warning", 'O campo "Operadora" do chip 1 precisa ser um número.');
                    return;
                }
            }

            if (data.base_instalada_tz_operadora2) {
                if (isNaN(parseInt(data.base_instalada_tz_operadora2))) {
                    showAlert("warning", 'O campo "Operadora" do chip 2 precisa ser um número.');
                    return;
                }
            }

            if (data.base_instalada_tz_cor) {
                if (data.base_instalada_tz_cor.length > 100) {
                    showAlert("warning", 'O campo "Cor" do veículo deve ter no máximo 100 caracteres.');
                    return;
                }
            }

            ShowLoadingScreen()
            if (isEditBase) {
                const url = `${URL_PAINEL_OMNILINK}/ajax_editar_base_instalada/${idBaseInstalada}`;
                salvar_auditoria(url, 'update', valor_antigo_base_instalada, data)
                    .then(async () => {
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: 'json',
                            data: data,
                            success: function(callback) {
                                callback = typeof callback == 'string' ? JSON.parse(callback) : callback;
                                if (callback.status) {
                                    showAlert("success", "Base instalada editada com sucesso!");
                                    valor_antigo_base_instalada = {}
                                    $('#modalBaseInstalada').modal("hide");
                                    HideLoadingScreen()
                                    buscarDadosAgGrid()
                                } else {
                                    showAlert("error", "Erro ao editar base instalada!");
                                    HideLoadingScreen()
                                }
                            },
                            error: function(error) {
                                showAlert("error", "Erro ao editar base instalada!");
                                HideLoadingScreen()
                            }
                        });
                    })
                    .catch(error => {
                        showAlert("error", "Erro ao editar base instalada!");
                        HideLoadingScreen()
                    });
            } else {
                const url = `${URL_PAINEL_OMNILINK}/ajax_cadastrar_base_instalada`;
                salvar_auditoria(url, 'insert', null, data)
                    .then(async () => {
                        await $.ajax({
                            url: url,
                            type: "POST",
                            dataType: 'json',
                            data: data,
                            success: async function(callback) {
                                callback = typeof callback == 'string' ? JSON.parse(callback) : callback;
                                if (callback.status) {
                                    showAlert("success", "Base instalada cadastrada com sucesso!");
                                    $('#modalBaseInstalada').modal("hide");
                                    HideLoadingScreen()
                                    buscarDadosAgGrid()
                                } else {
                                    showAlert("error", "Erro ao cadastrar base instalada!");
                                    HideLoadingScreen()
                                }
                            },
                            error: function(error) {
                                showAlert("error", "Erro ao cadastrar base instalada!");
                                HideLoadingScreen()
                            }
                        });
                    })
                    .catch(error => {
                        showAlert("error", "Erro ao cadastrar base instalada!");
                        HideLoadingScreen()
                    });

            }
        })

        const SELECTS_SENSORES_ATUADORES_BASE_INSTALADA = [{
                name: "base_instalada_tz_ignicao",
                label: "Ignição",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_bloqueio_solenoide",
                label: "Bloqueio Solenoide",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_boto_panico",
                label: "Botão de Pânico",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_bloqueio_eletronico",
                label: "Bloqueio Eletrônico",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_painel",
                label: "Painel",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_trava_bau_traseira",
                label: "Trava Baú Traseira",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_portas_cabine",
                label: "Portas Cabine",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_trava_bau_lateral",
                label: "Trava Baú Lateral",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_bau_traseiro",
                label: "Baú Traseiro",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_trava_bau_intermediria",
                label: "Trava Baú Intermediária",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_bau_lateral",
                label: "Baú Lateral",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_trava_quinta_roda",
                label: "Trava Quinta Roda",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_bau_intermediario",
                label: "Baú Intermediário",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_teclado",
                label: "Teclado Compacto",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_engate_aspiral",
                label: "Engate Espiral",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_teclado_multimidia",
                label: "Teclado Multimídia",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_engate_eletronico",
                label: "Engate Eletrônico",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_bateria_backup",
                label: "Bateria Backup",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_temperatura",
                label: "Temperatura",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_telemetria",
                label: "Telemetria",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_sirene",
                label: "Sirene",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_painel_read_switch",
                label: "Painel Read Switch",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_setas_pulsantes",
                label: "Setas Pulsantes",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_setas_continuas",
                label: "Setas Contínuas",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_painel_micro_switch",
                label: "Painel Micro Switch",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_tipo_trava_bau",
                label: "Tipo de Trava Baú",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    1: 'Solenoide',
                    2: 'Motorizada',
                    3: 'Inteligente'
                }
            },
            {
                name: "base_instalada_tz_imobilizador_bt5",
                label: "Imobilizador BT5",
                required: false,
                disabled: true,
                containerSize: 'col-md-6',
                options: {
                    'false': 'Não',
                    'true': 'Sim',
                }
            },
            {
                name: "base_instalada_tz_sensor_configuravel1_1",
                label: "Sensor Config. 1",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    1: 'Início Mistura',
                    2: 'Sensor de Carreta Bi-Trem',
                    3: 'Sensor de Motorista',
                    4: 'Sensor de Painel',
                    5: 'Sensor de Para-brisa',
                    6: 'Sensor de Grade Janela',
                    7: 'Sensor de Baú Carretinha',
                    8: 'Sensor de Impacto',
                    9: 'Início Descarga',
                    10: 'Fim Descarga'
                }
            },
            {
                name: "base_instalada_tz_sensor_configuravel2_1",
                label: "Sensor Config. 2",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    1: 'Início Mistura',
                    2: 'Sensor de Carreta Bi-Trem',
                    3: 'Sensor de Motorista',
                    4: 'Sensor de Painel',
                    5: 'Sensor de Para-brisa',
                    6: 'Sensor de Grade Janela',
                    7: 'Sensor de Baú Carretinha',
                    8: 'Sensor de Impacto',
                    9: 'Início Descarga',
                    10: 'Fim Descarga'
                }
            },
            {
                name: "base_instalada_tz_sensor_configuravel3_1",
                label: "Sensor Config. 3",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    1: 'Início Mistura',
                    2: 'Sensor de Carreta Bi-Trem',
                    3: 'Sensor de Motorista',
                    4: 'Sensor de Painel',
                    5: 'Sensor de Para-brisa',
                    6: 'Sensor de Grade Janela',
                    7: 'Sensor de Baú Carretinha',
                    8: 'Sensor de Impacto',
                    9: 'Início Descarga',
                    10: 'Fim Descarga'
                }
            },
            {
                name: "base_instalada_tz_sensor_configuravel4_1",
                label: "Sensor Config. 4",
                required: false,
                disabled: false,
                containerSize: 'col-md-6',
                options: {
                    1: 'Início Mistura',
                    2: 'Sensor de Carreta Bi-Trem',
                    3: 'Sensor de Motorista',
                    4: 'Sensor de Painel',
                    5: 'Sensor de Para-brisa',
                    6: 'Sensor de Grade Janela',
                    7: 'Sensor de Baú Carretinha',
                    8: 'Sensor de Impacto',
                    9: 'Início Descarga',
                    10: 'Fim Descarga'
                }
            }
        ];

        function criarSelects(arraySelects, containerId) {
            let selects = "";
            arraySelects.forEach(props => {
                let select = `<div class="${props.containerSize ? props.containerSize : 'col-md-6'}">`;
                if (props.label) select += `<label ${props.required ? 'class="control-label"' : ""}>${props.label}</label>`;
                select += `<select class="form-control ${props.class ? props.class : ''}" name="${props.name}" id="${props.name}" ${props.required ? 'required' : ""} ${props.disabled ? 'disabled' : ""}>`;
                select += "<option></option>";
                if (props.options) {
                    Object.keys(props.options).forEach(keyOption => {
                        const option = props.options[keyOption]
                        select += `<option value="${keyOption}" title="${option}">${option}</option>`;
                    });
                }
                select += `</select></div>`;
                selects += select;
            });
            $(`#${containerId}`).html("").html(selects);
        }


        $(document).on('click', '.modalEditarBase_acoes', async function() {

            isEditBase = true;
            idBaseInstalada = $(this).data('id')
            resetSelects2()
            limpaInputsModal('formBaseInstalada');

            ShowLoadingScreen()
            await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_get_info_base_instalada/${idBaseInstalada}`,
                type: 'GET',
                dataType: 'json',
                success: function(callback) {
                    callback = typeof callback == 'string' ? JSON.parse(callback) : callback;
                    if (callback.status) {
                        $("#div_row_sensores_atuadores").show();
                        criarSelects(SELECTS_SENSORES_ATUADORES_BASE_INSTALADA, 'selects_sensores_atuadores_base_estalada');
                        let inputs = callback.data.inputs;
                        let selects = callback.data.selects;
                        valor_antigo_base_instalada = {};
                        for (key in inputs) {
                            $(`#formBaseInstalada`).find(`#${key}`).val(inputs[key]);
                            valor_antigo_base_instalada[key] = inputs[key];
                        }
                        for (key in selects) {
                            if (selects[key].id) {
                                $(`#formBaseInstalada`).find(`#${key}`).html(`<option value="${selects[key].id}">${selects[key].text ? selects[key].text : "-"}</option>`).trigger('change');
                                valor_antigo_base_instalada[key] = selects[key].id;
                            }
                        }
                        const nomeCliente = cliente_selecionado_atual?.nome;
                        const idCliente = cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm
                        const option = `<option value="${idCliente}">${nomeCliente}</option>`;
                        if (cliente_selecionado_atual.cnpj) {
                            $("#base_instalada_tz_cliente_pfid").attr('disabled', true);
                            $("#base_instalada_tz_cliente_pf_matrizid").attr('disabled', true);
                            $("#base_instalada_tz_cliente_pf_instaladoid").attr('disabled', true);
                            $("#base_instalada_tz_cliente_anterior_pf").attr('disabled', true);
                            $("#base_instalada_tz_cliente_pjid").html(option).trigger('change.select2');
                            $("#base_instalada_tz_cliente_pj_matrizid").html(option).trigger('change.select2');
                            $("#base_instalada_tz_cliente_pj_instaladoid").html(option).trigger('change.select2');
                        } else {
                            $("#base_instalada_tz_cliente_pjid").attr('disabled', true);
                            $("#base_instalada_tz_cliente_pj_matrizid").attr('disabled', true);
                            $("#base_instalada_tz_cliente_pj_instaladoid").attr('disabled', true);
                            $("#base_instalada_tz_cliente_anterior_pj").attr('disabled', true);
                            $("#base_instalada_tz_cliente_pfid").html(option).trigger('change.select2');
                            $("#base_instalada_tz_cliente_pf_matrizid").html(option).trigger('change.select2');
                            $("#base_instalada_tz_cliente_pf_instaladoid").html(option).trigger('change.select2');
                        }
                        $('.titulo-base-instalada').text('Editar Base Instalada')
                        $('#modalBaseInstalada').modal('show');
                        HideLoadingScreen()
                    } else {
                        showAlert("error", "Erro ao buscar base instalada do cliente!");
                        HideLoadingScreen()
                    }
                },
                error: function(erro) {
                    showAlert("error", "Erro ao buscar base instalada do cliente!");
                    HideLoadingScreen()
                }
            });
        })


    })

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }
</script>

<style>
</style>