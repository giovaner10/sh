<style>
    .close {
        margin-right: 10px !important;
        margin-top: 10px !important;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        border: none !important;
        margin-top: 8px !important;
        margin-bottom: 5px !important;
        width: 100% !important;
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;">Quantitativo Contratos | Veículos</h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('relatorio') ?> >
        Assinaturas EPTC >
        <?= lang('quantitativo_contratos_veiculos') ?>
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class="menu-interno-link selected" id="menu-relatorio">Relatório</a>
                </li>
                <li>
                    <a class="menu-interno-link" id="menu-dashboard">Dashboard</a>
                </li>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formGerarResult" accept-charset="utf-8">
                <div class="form-group filtro">
                    <div class="input-container">
                        <label style="width: 100%;" class="control-label"><?= lang('data_inicial') ?>: </label>
                        <input style="width:100%" class="date form-control" type="date" name="di" 
                            placeholder="" autocomplete="off" id="dp1" value="" />
                    </div>
                    <div class="input-container">
                        <label class="control-label" style="width: 100%;"><?= lang('data_final') ?>: </label>
                        <input style="width:100%" class="date form-control" type="date" name="df" 
                            placeholder="" autocomplete="off" id="dp2" value="" />
                    </div>
                    <div class="input-container">
                        <label class="control-label" style="width: 100%;"><?= lang('prestadora') ?>: </label>
                        <select class="js-prestadora-multiple form-control" name="prestadora[]" multiple="multiple"
                            autocomplete="off" style="width: 100%;" id="prestadora-select" >
                            <option value="TRACKER" selected><?= lang('show_tecnologia') ?></option>
                            <option value="NORIO"><?= lang('norio') ?></option>
                            <option value="OMNILINK"><?= lang('omnilink') ?></option>
                            <option value="EMBARCADORES"><?= lang('embarcadores') ?></option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="btn btn-success gerar_rel"
                            style="width: 100%; margin-bottom: 10px;">
                            <?= lang('gerar') ?>
                        </button>
                        <button class="btn btn-default" style='width:100%; margin-bottom: 5px' id="BtnLimparFiltro"
                            type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-relatorio" style="margin-bottom: 20px; position: relative;">
            <div class="tablePageHeader">
                <h3><b>Relatório Quantitativo de Contratos e Veículos:</b></h3>
                <div class="d-flex align-items-center" id="internal-navbar"
                    style="display: inline-flex; gap: 10px; justify-content: end; margin: 10px 0px 10px;">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button onclick="$('.dropdown-menu-acoes').hide();" class="btn btn-primary dropdown-toggle"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"
                            id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;"></div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left"
                        title="<?= lang('expandir_grid') ?>" style="border-radius: 6px; padding: 5px;">
                        <img class="img-expandir" src="<?= base_url('assets/images/icon-filter-hide.svg') ?>"
                            posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </div>
            <div class="table-related" style="display: flex; flex-direction: column;">
                <div id="tableqtdContratos" class="ag-theme-alpine my-grid-schedule"
                    style="width: 100%; height: 500px;"></div>
            </div>
        </div>
        <div class="card-conteudo card-dashboard" style="margin-bottom: 20px; position: relative; display: none;">
            <div class="tablePageHeader">
                <h3><b>Dashboard Quantitativo de Contratos e Veículos:</b></h3>
                <div class="d-flex align-items-center" id="internal-navbar"
                    style="display: inline-flex; gap: 10px; justify-content: end; margin: 10px 0px 10px;">
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left"
                        title="<?= lang('expandir_grid') ?>" style="border-radius: 6px; padding: 5px;">
                        <img class="img-expandir" src="<?= base_url('assets/images/icon-filter-hide.svg') ?>"
                            posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </div>
            <div class="chartPlotContainer" id="chartContainer" style="flex-wrap: wrap;">

                <div class="chartContainer container" id="chartContratosButton">

                    <div class="container chartHeader">
                        <charttitle>Quantitativo de Contratos</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="chartContratos"></div>
                    </div>
                </div>

                <div class="chartContainer container" id="chartVeiculosButton">

                    <div class="container chartHeader">
                        <charttitle>Quantitativo de Veículos</charttitle>
                    </div>
                    <div class="chartBody">
                        <div id="chartVeiculos"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal para Quantitativo de Contratos -->
<div id="modalChartContratos" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalContratos">Quantitativo de Contratos</h3>
            </div>
            <div class="modal-body">
                <div id="chartContratosModal" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success downloadChartBtn">Baixar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Quantitativo de Veículos -->
<div id="modalChartVeiculos" class="modal fade modalChart" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalVeiculos">Quantitativo de Veículos</h3>
            </div>
            <div class="modal-body">
                <div id="chartVeiculosModal" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success downloadChartBtn">Baixar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/agendamento', 'Exportacoes.js') ?>"></script>

<script type="text/javascript">
    var BaseURL = '<?= base_url('') ?>';
    var Router = '<?= site_url('relatorios') ?>';

    var localeText = AG_GRID_LOCALE_PT_BR;
    var defaultOverlay = AG_GRID_DEFAULT_OVERLAYS;

    var chartContratos = null;
    var chartVeiculos = null;
    var chartContratosModal = null;
    var chartVeiculosModal = null;

    function desativarMenus() {
        $('#menu-relatorio').addClass('disabled').off('click');
        $('#menu-dashboard').addClass('disabled').off('click');
    }

    function ativarMenus() {
        $('#menu-relatorio').removeClass('disabled').on('click', function () {
            if (!$(this).hasClass("selected")) {
                alternarTelas("#menu-relatorio", "#menu-dashboard", ".card-relatorio", ".card-dashboard");
            }
        });

        $('#menu-dashboard').removeClass('disabled').on('click', function () {
            if (!$(this).hasClass("selected")) {
                alternarTelas("#menu-dashboard", "#menu-relatorio", ".card-dashboard", ".card-relatorio");
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        $(".btn-expandir").on("click", function (e) {
            e.preventDefault();
            expandirGrid();
        });

        $('.js-prestadora-multiple').select2({
            placeholder: 'Selecione uma prestadora',
            language: "pt-BR"
        });



        inicializarTelas();
        atualizarAgGridQuantitativoContratosVeiculos([], true);
        desativarMenus();
    });

    $("#BtnLimparFiltro").on("click", function () {
        $("#formGerarResult")[0].reset();
        $('.js-prestadora-multiple').val('').trigger('change');
        alternarTelas("#menu-relatorio", "#menu-dashboard", ".card-relatorio", ".card-dashboard");
        desativarMenus();
        atualizarAgGridQuantitativoContratosVeiculos([], true);
    });

    function alternarTelas(menuSelecionado, menuNaoSelecionado, telaMostrar, telaEsconder) {
        $(menuSelecionado).addClass("selected");
        $(menuNaoSelecionado).removeClass("selected");
        $(telaMostrar).show();
        $(telaEsconder).hide();
    }

    function inicializarTelas() {
        if ($("#menu-relatorio").hasClass("selected")) {
            alternarTelas("#menu-relatorio", "#menu-dashboard", ".card-relatorio", ".card-dashboard");
        } else {
            alternarTelas("#menu-dashboard", "#menu-relatorio", ".card-dashboard", ".card-relatorio");
        }
    }

    $("#menu-relatorio").on("click", function () {
        if (!$(this).hasClass("selected")) {
            alternarTelas("#menu-relatorio", "#menu-dashboard", ".card-relatorio", ".card-dashboard");
        }
    });

    $("#menu-dashboard").on("click", function () {
        if (!$(this).hasClass("selected")) {
            alternarTelas("#menu-dashboard", "#menu-relatorio", ".card-dashboard", ".card-relatorio");
        }
    });

    let menuAberto = false;

    function expandirGrid() {
        menuAberto = !menuAberto;

        let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
        let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

        if (menuAberto) {
            $(".img-expandir").attr("src", buttonShow);
            $("#filtroBusca").hide();
            $(".menu-interno").hide();
            $("#conteudo").removeClass("col-md-9");
            $("#conteudo").addClass("col-md-12");
        } else {
            $(".img-expandir").attr("src", buttonHide);
            $("#filtroBusca").show();
            $(".menu-interno").show();
            $("#conteudo").css("margin-left", "0px");
            $("#conteudo").removeClass("col-md-12");
            $("#conteudo").addClass("col-md-9");
        }
    }

    function minMaxDataImput(identificador, data = new Date(), difDias = 0, difMeses = 0, difAnos = 100) {
        diaMin = data.getDate() < 10 ? '0' + (parseInt(data.getDate()) - difDias) : (parseInt(data.getDate()) - difDias);
        mesMin = data.getMonth() < 9 ? '0' + (parseInt(data.getMonth()) + 1 - difMeses) : (parseInt(data.getMonth()) + 1 - difMeses);
        anoMin = (parseInt(data.getFullYear()) - difAnos);

        diaMax = data.getDate() < 10 ? '0' + (parseInt(data.getDate()) + difDias) : (parseInt(data.getDate()) + difDias);
        mesMax = data.getMonth() < 10 ? '0' + (parseInt(data.getMonth()) + 1 + difMeses) : (parseInt(data.getMonth()) + 1 + difMeses);
        anoMax = (parseInt(data.getFullYear()) + difAnos);

        minData = anoMin + '-' + mesMin + '-' + diaMin;
        maxData = anoMax + '-' + mesMax + '-' + diaMax;
        $(identificador).attr('min', minData).attr('max', maxData);
    }

    var AgGridQuantitativoContratosVeiculos;

    function atualizarAgGridQuantitativoContratosVeiculos(dados, isEdit) {
        stopAgGRIDQuantitativoContratosVeiculos();
        const gridOptions = {
            columnDefs: [
                {
                    headerName: 'Data',
                    field: 'data',
                    chartDataType: 'category',
                    minWidth: 150,
                    flex: 1,
                },
                {
                    headerName: 'Quantidade Contratos',
                    field: 'qtdContratos',
                    chartDataType: 'series',
                    minWidth: 200,
                },
                {
                    headerName: 'Quantidade Veículos',
                    field: 'qtdVeiculos',
                    chartDataType: 'series',
                    minWidth: 200,
                },
                {
                    headerName: 'Veículos Ativos',
                    field: 'veiculosAtivos',
                    chartDataType: 'series',
                    minWidth: 200,
                },
            ],
            defaultColDef: {
                editable: function (params) {
                    return params.data.status !== "Inativo";
                },
                sortable: true,
                minWidth: 80,
                minHeight: 100,
                filter: true,
                resizable: true,
                suppressMenu: true,
            },
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            domLayout: 'normal',
            pagination: true,
            paginationPageSize: 10,
            localeText: localeText,
        };

        var gridDiv = document.querySelector('#tableqtdContratos');
        gridDiv.style.setProperty("height", "519px");

        AgGridQuantitativoContratosVeiculos = new agGrid.Grid(gridDiv, gridOptions);
        gridOptions.api.setRowData(dados);

        preencherExportacoesQuantitativo(
            gridOptions,
            "Relatório Quantitativo de Contratos e Veículos"
        );

        desenharGraficos(dados);
    }

    function stopAgGRIDQuantitativoContratosVeiculos() {
        var gridDiv = document.querySelector('#tableqtdContratos');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.table-related');
        if (wrapper) {
            wrapper.innerHTML = '<div id="tableqtdContratos" class="ag-theme-alpine my-grid-schedule" style="width: 100%; height: 500px;"></div>';
        }
    }

    function desenharGraficos(dados) {
        if (dados.length == 0) {
            desativarMenus();
        }

        var valoresGraficoContratos = [];
        var valoresGraficoVeiculos = [];
        valoresGraficoContratos.push({ category: lang.data, value: lang.total_contratos });
        valoresGraficoVeiculos.push({ category: lang.data, value: lang.total_veiculos });

        dados.forEach(function (value) {
            valoresGraficoContratos.push({ category: value.data, value: parseInt(value.qtdContratos) });
            valoresGraficoVeiculos.push({ category: value.data, value: parseInt(value.qtdVeiculos) });
        });

        drawChartContratos(valoresGraficoContratos);
        drawChartVeiculos(valoresGraficoVeiculos);
    }

    function drawChartContratos(valoresGraficoContratos) {
        if (chartContratos) {
            chartContratos.destroy();
        }
        const options = {
            container: document.getElementById('chartContratos'),
            data: valoresGraficoContratos,
            series: [
                {
                    type: 'pie',
                    angleKey: 'value',
                    legendItemKey: "category",
                    calloutLabelKey: "category",
                    modal: true,
                    calloutLabel: {
                        fontSize: 10,
                        fontWeight: "lighter",
                        avoidCollisions: true,
                    },
                    tooltip: {
                        renderer: function (params) {
                            return (
                                '<div class="ag-chart-tooltip-title" style="background-color:' +
                                params.color +
                                '; font-weight: 900;"> Técnico: ' +
                                params.datum[params.calloutLabelKey] +
                                "</div>" +
                                '<div class="ag-chart-tooltip-content">' +
                                "Total: " +
                                params.datum[params.angleKey].toFixed(
                                    0
                                ) +
                                "</div>"
                            );
                        },
                    },
                },
            ],
            theme: {
                palette: {
                    fills: ['#f57c00', '#ffcc80', '#ffee58', '#ffeb3b', '#d4e157', '#aed581', '#81c784']
                }
            },
            background: {
                fill: "transparent",
            },
            padding: {
                top: 20,
                right: 20,
                bottom: 20,
                left: 20,
            },
        };

        chartContratos = agCharts.AgChart.create(options);

        $("#chartContratosButton").on("click", function () {
            $("#modalChartContratos").modal("show");

            if (chartContratosModal) {
                chartContratosModal.destroy();
            }

            const optionsModal = {
                container: document.getElementById('chartContratosModal'),
                data: valoresGraficoContratos,
                series: [
                    {
                        type: 'pie',
                        angleKey: 'value',
                        legendItemKey: "category",
                        calloutLabelKey: "category",
                        modal: true,
                        calloutLabel: {
                            fontSize: 10,
                            fontWeight: "lighter",
                            avoidCollisions: true,
                        },
                        tooltip: {
                            renderer: function (params) {
                                return (
                                    '<div class="ag-chart-tooltip-title" style="background-color:' +
                                    params.color +
                                    '; font-weight: 900;"> Técnico: ' +
                                    params.datum[params.calloutLabelKey] +
                                    "</div>" +
                                    '<div class="ag-chart-tooltip-content">' +
                                    "Total: " +
                                    params.datum[params.angleKey].toFixed(
                                        0
                                    ) +
                                    "</div>"
                                );
                            },
                        },
                    },
                ],
                theme: {
                    palette: {
                        fills: ['#f57c00', '#ffcc80', '#ffee58', '#ffeb3b', '#d4e157', '#aed581', '#81c784']
                    }
                },
                background: {
                    fill: "transparent",
                },
            };

            chartContratosModal = agCharts.AgChart.create(optionsModal);

            $(".downloadChartBtn")
                .off("click")
                .click(function (event) {
                    event.preventDefault();
                    agCharts.AgCharts.download(chartContratosModal, {
                        width: 800,
                        height: 500,
                        fileName: "Quantitativo de Contratos - Gráfico Pizza",
                    });
                });
        });
    }

    function drawChartVeiculos(valoresGraficoVeiculos) {
        if (chartVeiculos) {
            chartVeiculos.destroy();
        }
        const options = {
            container: document.getElementById('chartVeiculos'),
            data: valoresGraficoVeiculos,
            series: [
                {
                    type: 'pie',
                    angleKey: 'value',
                    legendItemKey: "category",
                    calloutLabelKey: "category",
                    modal: true,
                    calloutLabel: {
                        fontSize: 10,
                        fontWeight: "lighter",
                        avoidCollisions: true,
                    },
                    tooltip: {
                        renderer: function (params) {
                            return (
                                '<div class="ag-chart-tooltip-title" style="background-color:' +
                                params.color +
                                '; font-weight: 900;"> Técnico: ' +
                                params.datum[params.calloutLabelKey] +
                                "</div>" +
                                '<div class="ag-chart-tooltip-content">' +
                                "Total: " +
                                params.datum[params.angleKey].toFixed(
                                    0
                                ) +
                                "</div>"
                            );
                        },
                    },
                },
            ],
            theme: {
                palette: {
                    fills: ['#f57c00', '#ffcc80', '#ffee58', '#ffeb3b', '#d4e157', '#aed581', '#81c784']
                }
            },
            background: {
                fill: "transparent",
            },
            padding: {
                top: 20,
                right: 20,
                bottom: 20,
                left: 20,
            },
        };

        chartVeiculos = agCharts.AgChart.create(options);

        $("#chartVeiculosButton").on("click", function () {
            $("#modalChartVeiculos").modal("show");

            if (chartVeiculosModal) {
                chartVeiculosModal.destroy();
            }

            const optionsModal = {
                container: document.getElementById('chartVeiculosModal'),
                data: valoresGraficoVeiculos,
                series: [
                    {
                        type: 'pie',
                        angleKey: 'value',
                        legendItemKey: "category",
                        calloutLabelKey: "category",
                        modal: true,
                        calloutLabel: {
                            fontSize: 10,
                            fontWeight: "lighter",
                            avoidCollisions: true,
                        },
                        tooltip: {
                            renderer: function (params) {
                                return (
                                    '<div class="ag-chart-tooltip-title" style="background-color:' +
                                    params.color +
                                    '; font-weight: 900;"> Técnico: ' +
                                    params.datum[params.calloutLabelKey] +
                                    "</div>" +
                                    '<div class="ag-chart-tooltip-content">' +
                                    "Total: " +
                                    params.datum[params.angleKey].toFixed(
                                        0
                                    ) +
                                    "</div>"
                                );
                            },
                        },
                    },
                ],
                theme: {
                    palette: {
                        fills: ['#f57c00', '#ffcc80', '#ffee58', '#ffeb3b', '#d4e157', '#aed581', '#81c784']
                    }
                },
                background: {
                    fill: "transparent",
                },
            };

            chartVeiculosModal = agCharts.AgChart.create(optionsModal);

            $(".downloadChartBtn")
                .off("click")
                .click(function (event) {
                    event.preventDefault();
                    agCharts.AgCharts.download(chartVeiculosModal, {
                        width: 800,
                        height: 500,
                        fileName: "Quantitativo de Veículos - Gráfico Pizza",
                    });
                });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        $('#formGerarResult').submit(function (e) {
            e.preventDefault();
            var callbak;

            function formatDate(date) {
                const [year, month, day] = date.split('-');
                return `${day}/${month}/${year.substring(2)}`;
            }

            const di = $('#dp1').val();
            const df = $('#dp2').val();
            const prestadora = $('#prestadora-select').val();

            if (!di || !df || !prestadora) {
                showAlert("warning", 'Todos os campos de busca são obrigatórios!');
                return;
            }

            if (new Date(di) > new Date(df)) {
                showAlert("warning", 'A data inicial não pode ser maior que a data de hoje.');
                return;
            }

            const formattedDi = formatDate(di);
            const formattedDf = formatDate(df);

            const hiddenInputs = `
                <input type="hidden" name="di_formatted" value="${formattedDi}">
                <input type="hidden" name="df_formatted" value="${formattedDf}">
            `;
            $('#formGerarResult').append(hiddenInputs);

            var data_form = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "<?= site_url('relatorios/loadQuantitativoContratos') ?>",
                data: data_form,
                datatype: 'json',
                beforeSend: function () {
                    $(".alert_acao").css('display', 'none');
                    $('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> <?= lang("gerando") ?>');
                    $('.btn').attr('disabled', true);
                    AgGridQuantitativoContratosVeiculos.gridOptions.api.showLoadingOverlay();
                },
                success: function (retorno) {
                    callbak = JSON.parse(retorno);
                    if (callbak.success) {
                        var dados = callbak.table;

                        var valoresGraficoContratos = [];
                        var valoresGraficoVeiculos = [];
                        valoresGraficoContratos.push({ category: lang.data, value: lang.total_contratos });
                        valoresGraficoVeiculos.push({ category: lang.data, value: lang.total_veiculos });

                        dados.forEach(function (value) {
                            valoresGraficoContratos.push({ category: value.data, value: parseInt(value.qtdContratos) });
                            valoresGraficoVeiculos.push({ category: value.data, value: parseInt(value.qtdVeiculos) });
                        });

                        drawChartContratos(valoresGraficoContratos);
                        drawChartVeiculos(valoresGraficoVeiculos);

                        atualizarAgGridQuantitativoContratosVeiculos(dados, true);

                        $('#total_quantitativo').html("<b><?= lang('total_contratos') ?>:</b> " + callbak.total_contratos + " | " + "<b><?= lang('total_veiculos') ?>:</b> " + callbak.total_veiculos);

                    } else {
                        atualizarAgGridQuantitativoContratosVeiculos([], true);

                        $('#total_quantitativo').css('display', 'none');
                        $(".alert_acao").css('display', 'block');
                        callbak = JSON.parse(retorno);
                        $("#mensagem").html('<div class="alert alert-danger"><p><b>' + callbak.msg + '</b></p></div>');
                    }
                },
                complete: function () {
                    $('.btn').prop('disabled', false);
                    $('.gerar_rel').removeAttr('disabled').html('<?= lang("gerar") ?>');
                    ativarMenus();
                }
            });

            $(this).find('input[name="di_formatted"], input[name="df_formatted"]').remove();
        });

        $(document).on('click', '.close', function () {
            $(".alert_acao").css('display', 'none');
        });

        $(document).on('click', '.date', function () {
            minMaxDataImput('.date', new Date(), 0, 0, 1);
        });
    });

    function exportarArquivoQuantitativo(tipo, gridOptions, titulo) {
        let colunas = ['data', 'qtdContratos', 'qtdVeiculos', 'veiculosAtivos'];

        switch (tipo) {
            case 'csv':
                fileName = `${titulo}.csv`;
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName,
                    columnKeys: colunas
                });
                break;
            case 'excel':
                fileName = `${titulo}.xlsx`;
                gridOptions.api.exportDataAsExcel({
                    fileName: fileName,
                    columnKeys: colunas
                });
                break;
            case 'pdf':
                let definicoesDocumento = getDocDefinition(
                    printParams('A4'),
                    gridOptions.api,
                    gridOptions.columnApi,
                    '',
                    titulo
                );
                pdfMake.createPdf(definicoesDocumento).download(`${titulo}.pdf`);
                break;

        }
    }

    function preencherExportacoesQuantitativo(gridOptions, titulo) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao');
        const opcoes = ['csv', 'excel', 'pdf'];

        let buttonCSV = BaseURL + '/media/img/new_icons/csv.png';
        let buttonEXCEL = BaseURL + 'media/img/new_icons/excel.png';
        let buttonPDF = BaseURL + 'media/img/new_icons/pdf.png';

        formularioExportacoes.innerHTML = '';

        opcoes.forEach(opcao => {
            let button = '';
            let texto = '';
            switch (opcao) {
                case 'csv':
                    button = buttonCSV;
                    texto = 'CSV';
                    margin = '-5px';
                    break;
                case 'excel':
                    button = buttonEXCEL;
                    texto = 'Excel';
                    margin = '0px';
                    break;
                case 'pdf':
                    button = buttonPDF;
                    texto = 'PDF';
                    margin = '0px';
                    break;
            }

            let div = document.createElement('div');
            div.classList.add('dropdown-item');
            div.classList.add('opcao_exportacao');
            div.setAttribute('data-tipo', opcao);
            div.innerHTML = `
                    <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
                    <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
                    `;

            div.style.height = '30px';

            div.style.marginTop = margin;

            div.style.borderRadius = '1px';

            div.style.transition = 'background-color 0.3s ease';

            div.addEventListener('mouseover', function () {
                div.style.backgroundColor = '#f0f0f0';
            });

            div.addEventListener('mouseout', function () {
                div.style.backgroundColor = '';
            });

            div.style.border = '1px solid #ccc';

            div.addEventListener('click', function (event) {
                event.preventDefault();
                exportarArquivoQuantitativo(opcao, gridOptions, titulo);
            });

            formularioExportacoes.appendChild(div);
        });
    }
</script>