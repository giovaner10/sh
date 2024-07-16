<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente('Contratos Ativos', site_url('Homes'), "Pós-vendas", 'Contratos Ativos');
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
                    <a style="background: #1C69AD !important; color: #fff !important; font-family: 'Mont SemiBold';" class='menu-interno-link <?= $menu_omnicom == 'CadastroDeClientes' ? 'selected' : '' ?>' id="menu-cadastro-clientes">Contratos Ativos</a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="cliente_nome">Cliente:</label>
                        <input type="text" name="cliente_nome" class="form-control" placeholder="Nome do cliente" id="cliente_nome" />
                    </div>

                    <div class="input-container">
                        <label for="serial">Serial:</label>
                        <input type="text" name="serial" class="form-control" placeholder="Serial" id="serial" />
                    </div>

                    <div class="input-container">
                        <label for="status">Status:</label>
                        <select name="status" id="status" style="width: 100%;" class="form-control">
                            <option value="">Todos</option>
                            <option value="ativo">Ativos</option>
                            <option value="cancelado">Cancelados</option>
                            <option value="inativo">Inativos</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card">Contratos Ativos: </b>
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
                <select id="select-quantidade-por-contratos-ativos" class="form-control" style="float: left; width: auto; height: 34px;">
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
</div>

<!-- Modal Detalhes do Contrato -->
<div id="modalDetalhesDoContrato" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-x-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Contrato Visualizar Detalhes</h3>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="subtitle">Dados do Contrato</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin: 1.5rem 3rem;">
                        <div class="col-md-3 bord">
                            <label for="">Cod.</label>
                            <h4 id="codeDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Status do Item de Contrato</label>
                            <h4 id="statusDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Data de Ativação</label>
                            <h4 id="activationDateDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Data Fim</label>
                            <h4 id="endDateDetalhesDoContrato">-</h4>
                        </div>
                    </div>
                    <div class="col-md-12 m-2" style="margin: 1.5rem 3rem;">
                        <div class="col-md-3 bord">
                            <label for="">Tecnologia</label>
                            <h4 id="technologyDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Plano</label>
                            <h4 id="trackerPlanDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Serial</label>
                            <h4 id="trackerSerialNumberDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Veículo</label>
                            <h4 id="vehicleLicenseNumberDetalhesDoContrato">-</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="modal-title">Itens</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="wrapperItens">
                            <div id="tableItens" class="ag-theme-alpine my-grid-itens" style="height: 500px">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>

        </div>
    </div>
</div>

<div id="loading">
    <div class="loader"></div>
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


    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
</style>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/EzMock', 'app.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/posvendas', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'actionButton.js') ?>"></script>

<script>
    //var RouterOCR = '';
    var Router = '<?= site_url('PosVenda/Gestao') ?>';
    var BaseURL = '<?= base_url('') ?>';

    var localeText = AG_GRID_LOCALE_PT_BR;

    var AgGrid;
    var gridOptions;

    function ShowLoadingScreen() {
        $('#loading').show()
    }

    function HideLoadingScreen() {
        $('#loading').hide()
    }

    // Função para obter os dados do servidor
    function getServerSideDados() {
        return {
            getRows: (params) => {
                $.ajax({
                    cache: false,
                    url: '<?php echo base_url("PosVenda/Gestao/listar_contratos_server_side"); ?>',
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        cliente: $("#cliente_nome").val() || null,
                        serial: $("#serial").val() || null,
                        status: $("#status").val() || null,
                    },
                    dataType: "json",
                    async: true,
                    beforeSend: disableSearchButtons,
                    success: (data) => handleSuccess(params, data),
                    error: (error) => handleError(params, error),
                    complete: enableSearchButtons
                });
            },
        };
    }

    // Funções auxiliares para manipulação de botões
    function disableSearchButtons() {
        $('#BtnPesquisar').prop('disabled', true);
        $('#BtnLimparFiltro').prop('disabled', true);
    }

    function enableSearchButtons() {
        $('#BtnPesquisar').prop('disabled', false);
        $('#BtnLimparFiltro').prop('disabled', false);
    }

    // Função para manipular sucesso na requisição
    function handleSuccess(params, data) {
        if (data && data.success) {
            params.success({
                rowData: data.rows,
                rowCount: data.lastRow,
            });
        } else {
            showAlert('error', data.message || "Erro na solicitação ao servidor");
            showNoRowsOverlay(params);
        }
    }

    // Função para manipular erro na requisição
    function handleError(params, error) {
        console.error("Erro na solicitação ao servidor:", error);
        showAlert('error', "Erro na solicitação ao servidor");
        showNoRowsOverlay(params);
    }

    // Função para exibir mensagem de nenhum dado encontrado
    function showNoRowsOverlay(params) {
        AgGrid.gridOptions.api.showNoRowsOverlay();
        params.failCallback();
        params.success({
            rowData: [],
            rowCount: 0,
        });
    }

    // Função para fechar dropdown
    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }

    // Função para atualizar dados
    function updateData(newData = []) {
        gridOptions.api.setServerSideDatasource(newData);
    }

    // Função para renderizar AG Grid
    async function renderAgGrid() {
        gridOptions = {
            columnDefs: getColumnDefs(),
            rowData: [],
            getRowId: params => params.data.id,
            pagination: true,
            defaultColDef: {
                resizable: true,
            },
            sideBar: getSideBarConfig(),
            popupParent: document.body,
            paginationPageSize: parseInt($("#select-quantidade-por-contratos-ativos").val()),
            cacheBlockSize: 25,
            localeText: AG_GRID_LOCALE_PT_BR,
            domLayout: "normal",
            rowModelType: "serverSide",
            serverSideStoreType: "partial",
        };

        initializeAgGrid();

        const data = await getServerSideDados();
        updateData(data);
    }

    // Função para obter definições de colunas
    function getColumnDefs() {
        return [
            getColumnDef("Nome/Cod.Venda", "ref_contrato"),
            getColumnDef("Veiculo", "placa"),
            getColumnDef("Serial", "equipamento"),
            getColumnDef("Equipamento", "produto_rastreador"),
            getColumnDef("Modalidade", "modalidade"),
            getColumnDef("Data Ativação", "data_instalacao"),
            getColumnDef("Status Item de Contrato", "status"),
            getActionsColumnDef()
        ];
    }

    // Função para obter definição de uma coluna
    function getColumnDef(headerName, field) {
        return {
            headerName,
            field,
            valueGetter: params => params.data[field] !== null ? params.data[field] : ""
        };
    }

    // Função para obter definição da coluna de ações
    function getActionsColumnDef() {
        return {
            headerName: 'Ações',
            width: 80,
            pinned: 'right',
            cellClass: "actions-button-cell",
            suppressMenu: true,
            sortable: false,
            filter: false,
            resizable: false,
            cellRenderer: (options) => getActionsCellRenderer(options),
        };
    }

    // Função para renderizar célula de ações
    function getActionsCellRenderer(options) {
        const data = options.data;
        const buttonId = `dropdownMenuButton_${data.id}`;
        const dropdownId = `dropdown-menu${data.id}`;
        const i = options.rowIndex;
        const ajusteAltura = 0;

        return `
        <div class="dropdown dropdown-table-contratos">
            <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick="fecharDrop()" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
            </button>
            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}% - ${i}px);"  aria-labelledby="${buttonId}">
                <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                    <a class="editar_id_usuario" data-id-user=${data.id} style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar Detalhes</a>
                </div>
            </div>
        </div>`;
    }

    // Função para obter configuração da barra lateral
    function getSideBarConfig() {
        return {
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
            }],
            defaultToolPanel: false,
        };
    }

    // Função para inicializar AG Grid
    function initializeAgGrid() {
        const gridDiv = document.querySelector("#tableContatos");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        preencherExportacoes(gridOptions, "Relatorio Contratos Ativos", ["ref_contrato", "placa", "equipamento", "produto_rastreador", "modalidade", "data_instalacao", "status"]);

        setupPageSizeSelector();
        setupPaginationChangedEvent();
    }

    // Função para configurar seletor de tamanho da página
    function setupPageSizeSelector() {
        $("#select-quantidade-por-contratos-ativos").off().change(function() {
            const selectedValue = $("#select-quantidade-por-contratos-ativos").val();
            gridOptions.api.paginationSetPageSize(Number(selectedValue));
        });
    }

    // Função para configurar evento de mudança de paginação
    function setupPaginationChangedEvent() {
        gridOptions.api.addEventListener("paginationChanged", function(event) {
            $("#loadingMessage").show();

            const paginaAtual = Number(event.api.paginationGetCurrentPage());
            const tamanhoPagina = Number(event.api.paginationGetPageSize());

            const filteredData = [];
            event.api.forEachNode((node) => {
                filteredData.push(node.data);
            });

            const startIndex = paginaAtual * tamanhoPagina;
            const endIndex = startIndex + tamanhoPagina;
            const pageData = filteredData.slice(startIndex, endIndex);

            const dados = pageData.filter(data => data !== null);
            $("#loadingMessage").hide();
        });
    }


    $(document).ready(function() {

        renderAgGrid();

        $("#BtnLimparFiltro").on("click", async function() {
            $("#formBusca")[0].reset();
            let data = await getServerSideDados();
            updateData(data);

        });


        $('#BtnPesquisar').click(async function(e) {
            e.preventDefault()
            let data = await getServerSideDados();
            updateData(data);
        })
    })
</script>