<?php
include (dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente('Agendamento de Instalação', site_url('Homes'), "Pós-vendas", 'Agendamento de Instalação');
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
                    <a style="background: #1C69AD !important; color: #fff !important; font-family: 'Mont SemiBold';"
                        class='menu-interno-link <?= $menu_omnicom == 'CadastroDeClientes' ? 'selected' : '' ?>'
                        id="menu-cadastro-clientes">Agendamento de Instalação</a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="buscaSelection" id="filtrarPor">
                        <label for="tipoData">Buscar por:</label>
                        <select id="tipoData" name="tipoData" class="form-control">
                            <option value="dateRangeAgendamentoInstalacao">Intervalo de dias</option>
                            <option value="status">Status</option>
                        </select>
                    </div>

                    <div class="input-container" id="dataInicialContainer">
                        <label for="dataInicial" class="control-label">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="data formatInput form-control"
                            placeholder="Data Início" autocomplete="off" id="dataInicial" />
                    </div>

                    <div class="input-container" id="dataFinalContainer">
                        <label for="dataFinal" class="control-label">Data Final:</label>
                        <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim"
                            autocomplete="off" id="dataFinal" />
                    </div>

                    <div class="input-container" style="display:none;" id="statusContainer">
                        <label for="statusInput">Status: </label>
                        <select class="form-control" id="statusInput">
                            <option value="" disabled selected>Selecione um status</option>
                            <option value="0">Aguardando Instalador</option>
                            <option value="1">Agendado</option>
                            <option value="2">Atendente</option>
                            <option value="3">Não Agendado</option>
                            <option value="4">Agendado/Atendente</option>
                            <option value="5">Cancelado/Ausente</option>
                            <option value="6">Em Atendimento</option>
                            <option value="7">Concluído/Finalizado</option>
                            <option value="8">Cancelado</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i> Buscar
                        </button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro"
                            type="button">
                            <i class="fa fa-leaf" aria-hidden="true"></i> Limpar
                        </button>
                    </div>
                </div>
            </form>


        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes"
            style='margin-bottom: 20px;'>
            <h3>
                <b id="titulo-card">Agendamento de Instalação: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"
                            id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left"
                        title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>"
                            posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-agendamentos" class="form-control"
                    style="float: left; width: auto; height: 34px;">
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

<!-- Modal  -->

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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'actionButton.js') ?>"></script>

<script>
    // Define as URLs necessárias
    var Router = '<?= site_url('PosVenda/Gestao') ?>';
    var BaseURL = '<?= base_url('') ?>';

    var localeText = AG_GRID_LOCALE_PT_BR;

    var AgGrid;
    var gridOptions;

    $("#tipoData").change(function () {
        $(".input-container").hide();

        $("#statusInput").val('');
        $("#dataInicial").val('');
        $("#dataFinal").val('');

        switch ($(this).val()) {
            case "status":
                $("#statusContainer").show();
                break;
            case "dateRangeAgendamentoInstalacao":
                $("#dataInicialContainer, #dataFinalContainer").show();
                break;
            default:
                $("#dataInicialContainer, #dataFinalContainer").show();
                break;
        }
    });

    function ShowLoadingScreen() {
        $('#loading').show()
    }

    function HideLoadingScreen() {
        $('#loading').hide()
    }

    function getServerSideDados() {
        return {
            getRows: (params) => {
                $.ajax({
                    cache: false,
                    url: '<?php echo base_url("PosVenda/Gestao/listar_agendamento_server_side"); ?>',
                    type: "POST",
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        dataInicial: $("#dataInicial").val() || null,
                        dataFinal: $("#dataFinal").val() || null,
                        status: $("#statusInput").val() || null,
                    },
                    dataType: "json",
                    async: true,
                    beforeSend: function () {
                        $('#BtnPesquisar').prop('disabled', true);
                        $('#BtnLimparFiltro').prop('disabled', true);
                    },
                    success: function (data) {
                        if (data && data.success) {
                            var dados = data.rows;
                            params.success({
                                rowData: dados,
                                rowCount: data.lastRow,
                            });
                            AgGrid.gridOptions.api.hideOverlay();
                        } else {
                            if (data && data.status == 404) {
                                showAlert('warning', "Dados não encontrados.");
                            } else {
                                showAlert('error', "Erro na solicitação ao servidor");
                            }
                            AgGrid.gridOptions.api.showNoRowsOverlay();
                            params.failCallback();
                            params.success({
                                rowData: [],
                                rowCount: 0,
                            });
                        }
                    },
                    error: function (error) {
                        console.error("Erro na solicitação ao servidor:", error);
                        showAlert('error', "Erro na solicitação ao servidor");
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                    },
                    complete: function () {
                        $('#BtnPesquisar').prop('disabled', false);
                        $('#BtnLimparFiltro').prop('disabled', false);
                    }
                });
            },
        };
    }

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }

    function updateData(newData = []) {
        gridOptions.api.setServerSideDatasource(getServerSideDados());
    }

    async function renderAgGrid() {
        let uniqueIdCounter = 0;
        gridOptions = {
            columnDefs: [
                {
                    headerName: "Documento",
                    field: "documento",
                    flex:1,
                    valueGetter: function (params) {
                        return params.data?.documento || "";
                    }
                },
                {
                    headerName: "Nome do Cliente",
                    field: "nomeCliente",
                    flex: 1,
                    valueGetter: function (params) {
                        return params.data?.nomeCliente || "";
                    }
                },
                {
                    headerName: "Nome do Técnico",
                    field: "nomeTecnico",
                    flex: 1,
                    valueGetter: function (params) {
                        return params.data?.nomeTecnico || "";
                    }
                },
                {
                    headerName: "Data da Criação",
                    field: "createdAt",
                    flex: 1,
                    valueGetter: function (params) {
                        return params.data?.createdAt || "";
                    }
                },
                {
                    headerName: "Status",
                    field: "status",
                    valueGetter: function (params) {
                        return params.data?.status || "";
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
                    cellRenderer: function (options) {
                        let data = options.data;

                        let tableId = "tableContatos";
                        let dropdownId = "dropdown-menu" + data.id;
                        let buttonId = "dropdownMenuButton_" + data.id;
                        let i = options.rowIndex;
                        let ajusteAltura = 0;
                        let paginaAtual = gridOptions.api.paginationGetCurrentPage();
                        let qtd = $('#select-quantidade-por-agendamento').val()

                        return `
                        <div class="dropdown dropdown-table-contratos" data-tableId=${tableId}>
                            <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}% - ${i}px);"  aria-labelledby="${buttonId}">
                               
                                <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                <a class="editar_id_usuario" data-id-user=${data.id} style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Detalhes</a>
                                </div>

                            </div>
                        </div>`;
                    },
                },
            ],
            rowData: [],
            getRowId: params => {
                const {nomeCliente, documento, createdAt} = params.data;
                return `${nomeCliente}-${documento}-${createdAt}-${uniqueIdCounter++}`;
            },
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
                },],
                defaultToolPanel: false,
            },
            popupParent: document.body,
            pagination: true,
            paginationPageSize: parseInt($("#select-quantidade-por-agendamentos").val()),
            cacheBlockSize: 25,
            localeText: AG_GRID_LOCALE_PT_BR,
            domLayout: "normal",
            rowModelType: "serverSide",
            serverSideStoreType: "partial",
        };
        var gridDiv = document.querySelector("#tableContatos");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        preencherExportacoes(gridOptions, "Agendamento de Instalação", ["documento", "nomeCliente", "nomeTecnico", "createdAt", "status"]);

        $("#select-quantidade-por-agendamentos")
            .off()
            .change(function () {
                var selectedValue = $("#select-quantidade-por-agendamentos").val();
                gridOptions.api.paginationSetPageSize(Number(selectedValue));
            });

        gridOptions.api.addEventListener("paginationChanged", function (event) {
            $("#loadingMessage").show();

            let paginaAtual = Number(event.api.paginationGetCurrentPage());
            let tamanhoPagina = Number(event.api.paginationGetPageSize());

            const filteredData = [];
            event.api.forEachNode((n) => {
                filteredData.push(n.data);
            });

            const startIndex = paginaAtual * tamanhoPagina;
            const endIndex = startIndex + tamanhoPagina;
            const pageData = filteredData.slice(startIndex, endIndex);

            var dados = [];
            pageData.forEach((data) => {
                if (data) {
                    dados.push(data);
                }
            });
            $("#loadingMessage").hide();
        });

        let dataSource = getServerSideDados();
        gridOptions.api.setServerSideDatasource(dataSource);
    }

    $(document).ready(function () {
        var menuButton = $("#tableContatos .menu-drop-abrir-fechar");
        if (menuButton.length) {
            menuButton.on("click", function () {
                fecharDrop();
            });
        }
    });

    $(document).ready(function () {
        $(".btn-expandir").on("click", function (e) {
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
            }
        });

        renderAgGrid();

        $("#BtnLimparFiltro").on("click", function () {
            $("#formBusca")[0].reset();
            $("#statusContainer").hide();
            $("#dataInicialContainer, #dataFinalContainer").show();
            $("#tipoData").val("dateRangeAgendamentoInstalacao").trigger("change");

            gridOptions.api.refreshServerSideStore();
        });

        $(document).ready(function () {
            $("#tipoData").trigger("change");
        });

        $('#BtnPesquisar').click(function (e) {
            e.preventDefault();

            if (new Date($("#dataInicial").val()) > new Date($("#dataFinal").val())) {
                showAlert("warning", "A data inicial não pode ser maior que a data final!");
                return;
            }

            gridOptions.api.refreshServerSideStore();
        });
    });

</script>