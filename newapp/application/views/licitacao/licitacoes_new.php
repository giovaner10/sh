<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.js" integrity="sha512-yfb1lLOhiYYJh7C3dsBE4XGCnDCEe4dJ/jdVgoinVdKwVuDP2SJqrEngf0Q+m6gaU8vOjCaJ0EaeakGzXXfWIA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js" integrity="sha512-cktKDgjEiIkPVHYbn8bh/FEyYxmt4JDJJjOCu5/FQAkW4bc911XtKYValiyzBiJigjVEvrIAyQFEbRJZyDA1wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://npmcdn.com/chart.js@2.7.2/dist/Chart.bundle.js"></script>
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>

<style type="text/css">
    .display {
        display: none;
    }

    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .informations {
        display: flex;
        flex-direction: row;
        flex-flow: column wrap;
        max-height: fit-content;
        justify-items: center;
        align-content: center;
        width: 100%;
        gap: 10px;
        margin-top: 20px;
        padding: 1rem;

    }

    .information-card {
        display: flex;
        flex-direction: row;
        min-width: 160px;
        max-width: 100vw;
        width: 100%;
        height: auto;
        padding: 1rem;
        justify-content: space-around;
        gap: 10px;
    }

    .inner-item {
        height: 10rem;
        display: flex;
        flex-direction: column;
        border: none;
        border-radius: 1rem;
        background-color: #f8f9fb;
        width: 100%;
        align-items: center;
        justify-content: center;
    }

    #chart-licitacao {
        display: flex;
        flex-direction: row;
        width: 100%;
        max-width: fit-content;
        align-items: center;
        justify-content: space-around;
        justify-items: center;
        padding: 1rem;
    }

    .chart-plot-container {
        display: inline-flex;
        justify-content: space-around;
        align-content: center;
        align-items: center;
        width: inherit;
        gap: 10px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .chart-plot {
        position: flex;
        flex-wrap: wrap;
        justify-content: center;
        justify-items: center;
        align-content: center;
        align-items: center;
        border-radius: 1rem;
        padding: 1rem;
        height: auto;
        width: 100%;
        max-width: 40rem;
        font-size: 5pt;
        background-color: #f8f9fb;
    }

    .chart-plot:hover {
        opacity: 0.8;
        transition: 180ms linear;
        border: 4px solid #0066ff;
    }

    .modal-content {
        gap: 25px;
        padding: 18px;
    }

    #div2 {
        width: 100%;
    }

    #search-input::placeholder {
        font-style: italic;
    }

    #search-input {
        font-style: normal;
    }

    #exibir_dashboard {
        text-align: center;
        align-content: center;
    }

    @media (max-width: 1600px) {
        .chart-plot-container {

            flex-wrap: wrap;

        }
    }

    @media (max-width: 1220px) {
        .information-card {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            height: 100%;
        }

        #total-licitacoes-div {
            height: inherit;
        }

        .chart-plot-container {
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .chart-plot {
            max-width: 60rem;
        }
    }

    @media (max-width: 920px) {
        .informations {
            flex-direction: column;
            justify-content: center;
        }

        .information-card {
            width: inherit;
        }

        #search-input {
            width: 35vw;
            justify-self: center;
            float: none;
            margin: auto;
        }

        #filterAndPaginationDiv {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-content: center;
        }
    }

    @media (max-width: 600px) {
        #internal-navbar {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .btn {
            width: 100%;
        }

        .dropdown {
            width: 100%;
        }
    }
</style>

<div class="alert alert-success display"></div>
<div class="alert alert-error display"></div>

<!-- Modal Grafico Veiculos -->
<div id="modalCharts1" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage1" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Quantidade de veículos</h3>
            </div>

            <canvas id="myChartModal1" style="position: relative; height:26rem; width:30rem;"></canvas>

        </div>
    </div>
</div>

<!-- Modal Grafico Licitacoes -->
<div id="modalCharts2" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage2" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Quantidade de licitações</h3>
            </div>

            <canvas id="myChartModal2" style="position: relative; height:26rem; width:30rem;"></canvas>

        </div>
    </div>
</div>

<!-- Modal Grafico Valores -->
<div id="modalCharts3" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="loadingMessage3" class="loadingMessage" style="display: none; margin-top: 20px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProduto">Valores</h3>
            </div>

            <canvas id="myChartModal3" style="position: relative; height:26rem; width:30rem; "></canvas>

        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="text-title">
        <h3 style="padding: 0 20px; margin-left: 15px;">Licitações</h3>
        <h4 style="padding: 0 20px; margin-left: 15px;">
            <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
            <?= lang('cadastros') ?> >
            <?= lang('licitacoes') ?>
        </h4>
    </div>

    <div class="container-fluid">
        <div class="row" style="margin: 15px 0 0 15px;">
            <div class="col-md-12" id="conteudo">
                <div class="card-conteudo" id="tableGRID" style='margin-bottom: 20px; position: relative;'>
                    <h3>
                        <b>Dados:</b>
                    </h3>

                    <div class="d-flex align-items-center" id="internal-navbar" style="display: inline-flex; gap: 10px; justify-content: end; margin: 10px 0px 10px;">

                        <a href="<?= base_url() ?>index.php/licitacao/add" class="btn btn-gestor btn-primary" title="Adicionar">
                            <i class="fa fa-plus"></i> Adicionar
                        </a>

                        <div class="dropdown btn btn-gestor btn-primary" style="width: auto; margin: 0; border: none;">
                            <button type="button" id="dropdownMenuButton_licitacoes" data-toggle="dropdown" style="background-color: transparent; border: none;">
                                <i class="fa fa-download"></i> <?= lang('exportar') ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton_licitacoes" id="opcoes_exportacao_licitacoes" style="min-width: 100px; top: 62px; height: 105px; padding: 10px; border: none; color: black;">
                            </div>
                        </div>

                        <a class="btn btn-gestor btn-primary" id="exibir_dashboard" onclick="exibir_dashboard()" title="Exibir dashboard">
                            <i class="fa fa-bar-chart"></i> <span>Dashboard</span>
                        </a>

                    </div>

                    <div id="loadingMessage" class="loadingMessage" style="display: none; margin-top: 35px;">
                        <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                    </div>

                    <div id="table-related" style="display: flex; flex-direction: column;">
                        <div id="filterAndPaginationDiv">
                            <select id="select-quantidade-por-pagina" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <label for="select-quantidade-por-pagina" style="float: left; margin-top: 18px; margin-left: 10px; color: #7F7F7F; font-size: 15px;">Registros por página</label>
                            <input type="text" id="search-input" placeholder="Buscar" style="float: right; margin: 10px; height:30px; padding-left: 10px;">
                                      
                        </div>
                        <div class="wrapperAcompanhamento" style='margin-top: 20px;'>
                            <div id="tableAcompanhamento" class="ag-theme-alpine my-grid-acompanhamento">
                            </div>
                        </div>
                    </div>

                    <div id="div2" style="display:none">
                        <center id="load_dash">
                            <i class="fa fa-spinner fa-spin fa-fw" style="font-size: 50px; "></i>
                        </center>

                        <body>
                            <div style="display: flex; flex-direction: column; gap: 20px; justify-content: center; width: 100%;">
                                <div id="quadros_dash" style="display:none">
                                    <div class="container" style="border-radius: 5px;font-size: 14px; width: 100%; text-align:center; align-content: center;">
                                        <h4>Esferas</h4>
                                        <div class="information-card">
                                            <div class="inner-item span4" style="text-align: center;">
                                                Municipal
                                                <br>
                                                <span id="esfera_municipal" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center; justify-items: center;">
                                                Estadual
                                                <br>
                                                <span id="esfera_estadual" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center;">
                                                Federal
                                                <br>
                                                <span id="esfera_federal" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container" style="border-radius: 5px;font-size: 14px; width: 100%; text-align:center;">
                                        <h4>Tipo</h4>
                                        <div class="information-card">
                                            <div class="inner-item span4" style="text-align: center;">
                                                Presencial
                                                <br>
                                                <span id="tipo_presencial" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center;">
                                                Eletrônico
                                                <br>
                                                <span id="tipo_eletrônico" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                            <div class="inner-item span4" style="text-align: center;">
                                                Carona
                                                <br>
                                                <span id="tipo_carona" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container" style="border-radius: 5px;font-size: 14px; width: 100%; text-align:center;">
                                        <h4>Total licitações</h4>
                                        <div class="information-card">
                                            <div class="inner-item span12" id="total-licitacoes-div" style="text-align: center;">
                                                <span id="total_licitacoes" style="font-size: 28pt;">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="chart-plot-container">
                                    <div class="box chart-plot container" style="margin-top: 10px;">
                                        <canvas id="myChart1" onclick="abrirModalVeiculos()" style="position: relative; height:26rem; width:30rem;"></canvas>
                                    </div>
                                    <div class="box chart-plot container" style="margin-top: 10px;">
                                        <canvas id="myChart2" onclick="abrirModalLicitacoes()" style="position: relative; height:26rem; width:30rem;"></canvas>
                                    </div>
                                    <div class="box chart-plot container" style="margin-top: 10px;">
                                        <canvas id="myChart3" onclick="abrirModalValores()" style="position: relative; height:26rem; width:30rem;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </body>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function($) {
        const baseUrl = '<?= base_url() ?>';
        const siteUrl = '<?= site_url('licitacoes') ?>';
        const localeText = AG_GRID_LOCALE_PT_BR;
        const chartBackgroundColor = ["#3e95cd", "#8e5ea2", "#3cba9f", "#c42840", "#e8c3b9", "#c45850", "#a98585"];

        $(document).ready(function() {
            initializeDashboard();
            initializeAgGrid([]);
        });

        function initializeDashboard() {
            const dashboardLink = $('body > div:nth-child(5) > ul > li:nth-child(2) > a');
            if (dashboardLink.length) {
                dashboardLink[0].href = `${baseUrl}index.php/licitacao/acompanhamento`;
            }

            $('#exibir_dashboard').on('click', toggleDashboard);
            loadDashboardData();
        }

        function toggleDashboard() {
            const tableRelated = $('#table-related');
            const div2 = $('#div2');
            const dashboardButton = $('#exibir_dashboard');
            const dashboardContainer = $('#quadros_dash');

            if (tableRelated.is(':visible')) {
                tableRelated.hide();
                div2.show();
                dashboardButton.html('<i class="fa fa-table"></i> <span>Relatórios</span>');
                dashboardContainer.addClass("informations container");
            } else {
                div2.hide();
                tableRelated.css('display', 'flex');
                dashboardButton.html('<i class="fa fa-bar-chart"></i> <span>Dashboard</span>');
                dashboardContainer.removeClass("informations container");
            }
        }

        function loadDashboardData() {
            $.getJSON(`${baseUrl}index.php/licitacao/dash_acompanhamento`, function(data) {
                updateDashboard(data);
            }).fail(function() {
                console.error('Failed to load dashboard data');
            });
        }

        function updateDashboard(data) {
            $('#esfera_municipal').text(data.qtd_esfera[2].qtd);
            $('#esfera_estadual').text(data.qtd_esfera[1].qtd);
            $('#esfera_federal').text(data.qtd_esfera[0].qtd);
            $('#load_dash').hide();
            $('#quadros_dash').show();

            $('#tipo_presencial').text(data.qtd_tipo[0].qtd);
            $('#tipo_eletrônico').text(data.qtd_tipo[1].qtd);
            $('#tipo_carona').text(data.qtd_tipo[2].qtd);

            $('#total_licitacoes').html(`${data.qtd_total}<span style="color:#0066ff; font-weight: bold; font-size: 20pt;"> x</span>`);

            createChart('myChart1', 'Quantidade de Veículos', data.grafico_veiculos);
            createChart('myChart2', 'Quantidade de licitações', data.grafico_licitacao);
            createChart('myChart3', 'Valores', data.grafico_valor);
        }

        function createChart(elementId, title, data) {
            const labels = data.map(item => item.situacao_final);
            const chartData = data.map(item => item.qtd);

            new Chart(document.getElementById(elementId), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: title,
                        backgroundColor: chartBackgroundColor,
                        data: chartData
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: title
                    }
                }
            });
        }

        function initializeAgGrid(dados) {
            const gridOptions = getGridOptions();
            const gridDiv = document.querySelector('#tableAcompanhamento');
            AgGridLicitacoes = new agGrid.Grid(gridDiv, gridOptions);

            gridOptions.api.setRowData(dados);
            setupEventListeners(gridOptions);

            preencherExportacoes(gridOptions);
        }

        function getGridOptions() {
            return {
                columnDefs: getColumnDefs(),
                defaultColDef: {
                    enablePivot: true,
                    editable: false,
                    sortable: true,
                    minWidth: 100,
                    filter: true,
                    resizable: true,
                },
                statusBar: {
                    statusPanels: getStatusPanels(),
                },
                localeText: localeText,
                popupParent: document.body,
                enableRangeSelection: true,
                enableCharts: true,
                domLayout: 'autoHeight',
                pagination: true,
                paginationPageSize: 10,
            };
        }

        function getColumnDefs() {
            return [{
                    headerName: 'ID',
                    field: 'ID',
                    chartDataType: 'category'
                },
                {
                    headerName: 'Orgão',
                    field: 'Orgao',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Data da Licitação',
                    field: 'Data_licitacao',
                    chartDataType: 'date'
                },
                {
                    headerName: 'Estado',
                    field: 'Estado',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Esfera',
                    field: 'Esfera',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Empresa',
                    field: 'Empresa',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Tipo',
                    field: 'Tipo',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Tipo de contrato',
                    field: 'Tipo_contrato',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Ata de registro de preços',
                    field: 'Ata_registro_precos',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Plataforma',
                    field: 'Plataforma',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Quantidade de veículos',
                    field: 'Quantidade_veiculos',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Valor unitário ref.',
                    field: 'Valor_unitario_ref',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Valor Global ref.',
                    field: 'Valor_global_ref',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Valor unitário Arremate',
                    field: 'Valor_uni_arremate',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Valor Global Arremate',
                    field: 'Valor_global_arremate',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Valor Instalação',
                    field: 'Valor_instalacao',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Descrição do serviço',
                    field: 'Descricao_servico',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Vencedor',
                    field: 'Vencedor',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Status preliminar',
                    field: 'Status_preliminar',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Status final',
                    field: 'Status_final',
                    chartDataType: 'series'
                },
                {
                    headerName: 'Observações',
                    field: 'Observacoes',
                    chartDataType: 'series'
                }
            ];
        }

        function getStatusPanels() {
            return [{
                    statusPanel: 'agTotalRowCountComponent',
                    align: 'right'
                },
                {
                    statusPanel: 'agFilteredRowCountComponent'
                },
                {
                    statusPanel: 'agSelectedRowCountComponent'
                },
                {
                    statusPanel: 'agAggregationComponent'
                }
            ];
        }

        function setupEventListeners(gridOptions) {
            $('#search-input').on('input', function() {
                gridOptions.api.setQuickFilter(this.value);
            });

            $('#select-quantidade-por-pagina').on('change', function() {
                gridOptions.api.paginationSetPageSize(Number(this.value));
            });

            $(document).on('click', function(event) {
                const dropdown = $('#opcoes_exportacao_licitacoes');
                if (!dropdown.is(event.target) && dropdown.has(event.target).length === 0) {
                    dropdown.hide();
                }
            });

            $('#dropdownMenuButton_licitacoes').on('click', function() {
                $('#opcoes_exportacao_licitacoes').toggle();
            });
        }

        function preencherExportacoes(gridOptions) {
            const formularioExportacoes = $('#opcoes_exportacao_licitacoes');
            const exportOptions = [{
                    type: 'csv',
                    label: 'CSV',
                    icon: 'csv.png'
                },
                {
                    type: 'excel',
                    label: 'Excel',
                    icon: 'excel.png'
                },
                {
                    type: 'pdf',
                    label: 'PDF',
                    icon: 'pdf.png'
                }
            ];

            formularioExportacoes.empty();

            exportOptions.forEach(option => {
                const div = $(`
                <div class="dropdown-item opcao_exportacao" data-tipo="${option.type}">
                    <img src="${baseUrl}media/img/new_icons/${option.icon}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer; border: none;" title="Exportar no formato ${option.label}">
                    <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${option.label}">${option.label}</label>
                </div>
            `);

                div.on('mouseover', function() {
                    $(this).css('background-color', '#f0f0f0');
                }).on('mouseout', function() {
                    $(this).css('background-color', '');
                }).on('click', function(event) {
                    event.preventDefault();
                    exportarArquivo(option.type, gridOptions);
                });

                formularioExportacoes.append(div);
            });
        }

        function exportarArquivo(tipo, gridOptions) {
            const fileName = `licitações.${tipo}`;
            switch (tipo) {
                case 'csv':
                    gridOptions.api.exportDataAsCsv({
                        fileName
                    });
                    break;
                case 'excel':
                    gridOptions.api.exportDataAsExcel({
                        fileName
                    });
                    break;
                case 'pdf':
                    const dadosExportacao = prepararDadosExportacaoRelatorio();
                    const definicoesDocumento = getDocDefinition(printParams('A4'), gridOptions.api, gridOptions.columnApi, dadosExportacao.informacoes, dadosExportacao.rodape);
                    pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
                    break;
            }
        }

        function prepararDadosExportacaoRelatorio() {
            const informacoes = DadosAgGrid.map(item => ({
                ID: item.id,
                Orgao: item.orgao,
                Data_licitacao: item.data_licitacao,
                Estado: item.estado,
                Esfera: item.esfera,
                Empresa: item.empresa,
                Tipo: item.tipo,
                Tipo_contrato: item.tipo_contrato,
                Ata_registro_precos: item.ata_registro_preco,
                Plataforma: item.plataforma,
                Quantidade_veiculos: item.qtd_veiculos,
                Valor_unitario_ref: item.valor_unitario_ref,
                Valor_global_ref: item.valor_global_ref,
                Valor_uni_arremate: item.valor_unitario_arremate,
                Valor_global_arremate: item.valor_global_arremate,
                Valor_instalacao: item.preco_instalacao,
                Descricao_servico: item.descricao_servico,
                Vencedor: item.vencedor,
                Status_preliminar: item.situacao_preliminar,
                Status_final: item.situacao_final,
                Observacoes: item.observacoes,
            }));

            return {
                informacoes,
                nomeArquivo: 'Licitações.pdf',
                rodape: 'Licitações'
            };
        }

        function printParams(pageSize) {
            return {
                PDF_HEADER_COLOR: "#ffffff",
                PDF_INNER_BORDER_COLOR: "#dde2eb",
                PDF_OUTER_BORDER_COLOR: "#babfc7",
                PDF_LOGO: `${baseUrl}media/img/new_icons/omnilink.png`,
                PDF_HEADER_LOGO: `${baseUrl}media/img/new_icons/omnilink.png`,
                PDF_ODD_BKG_COLOR: "#fff",
                PDF_EVEN_BKG_COLOR: "#F3F3F3",
                PDF_PAGE_ORITENTATION: "landscape",
                PDF_WITH_FOOTER_PAGE_COUNT: true,
                PDF_HEADER_HEIGHT: 25,
                PDF_ROW_HEIGHT: 25,
                PDF_WITH_CELL_FORMATTING: true,
                PDF_WITH_COLUMNS_AS_LINKS: false,
                PDF_SELECTED_ROWS_ONLY: false,
                PDF_PAGE_SIZE: pageSize,
            };
        }

        // Utility functions
        function convertToReal(value) {
            return new Intl.NumberFormat('pt-br', {
                style: 'currency',
                currency: 'BRL',
            }).format(value);
        }

        function formatDate(dataHora) {
            const dataHoraCorrigida = new Date(dataHora);
            dataHoraCorrigida.setHours(dataHoraCorrigida.getHours() + 3);
            return dataHoraCorrigida.toLocaleDateString('pt-BR', {
                timezone: 'UTC'
            });
        }

        // Functions to map item values
        function esfera(item) {
            const esferas = ["Federal", "Estadual", "Municipal"];
            return esferas[item.esfera] || '-';
        }

        function empresa(item) {
            return item.empresa === 1 ? "Norio Momoi" : "Show Tecnologia";
        }

        function tipo(item) {
            const tipos = ["Presencial", "Eletrônico", "Carona"];
            return tipos[item.tipo] || '-';
        }

        function tipoContrato(item) {
            const tiposContrato = ["Licitação", "Adesão à ata"];
            return tiposContrato[item.tipo_contrato] || '-';
        }

        function ataRegistroPreco(item) {
            return item.ata_registro_preco === 1 ? "Sim" : "Não";
        }

        function situacaoPreliminar(item) {
            const situacoesPreliminares = ["Arrematado", "Perdido", "Não Participou", "Suspenso", "Anulado", "Em andamento"];
            return situacoesPreliminares[item.situacao_preliminar] || '-';
        }

        function situacaoFinal(item) {
            const situacoesFinais = ["Aguardando", "Contrato Assinado", "Perdido", "Suspenso", "Em andamento"];
            return situacoesFinais[item.situacao_final] || '-';
        }
    })(jQuery);
</script>