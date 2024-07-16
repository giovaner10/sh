<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">

<style>
    #header-modal {
        font-family: 'Mont SemiBold';
        color: #1C69AD !important;
        font-size: 22px !important;
        font-weight: bold !important;
        text-align: center;
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("rastreamentoLote") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('suporte') ?> >
        <?= lang('andromeda') ?> >
        <?= lang('rastreamentoLote') ?>
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3" id="menu_nodes">
        <div id="filtroBusca" class="card " style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important; margin-left: -5px !important;">Filtrar Dados</h4>
            <div class="button-container" style="margin-bottom: 10px;">
                <button class="btn btn-primary" style='width:100%' id="openModalButton"><i class="fa fa-search" aria-hidden="true"></i> <?= lang('pesquisarLote') ?></button>
            </div>
            <div class="button-container" style='margin-bottom: 15px;'>
                <button style='width:100%' id="BtnLimparPesquisar" class="btn btn-default" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> <?= lang('limpar') ?></button>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo" id="tableGRID" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b>Dados:</b>
                <div class="d-flex align-items-center" style="display: inline-flex;">
                    <div class="dropdown" style="width: 100px;">
                        <button class="btn btn-gestor btn-primary dropdown-toggle" type="button" id="dropdownMenuButton_RastreamentoLote" data-toggle="dropdown" style="height: 35px;">
                            <?= lang('exportar') ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton_RastreamentoLote" id="opcoes_exportacao_rastreamentoLote" style="margin-right: 16px; min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="height: 35px;">
                        <img id="botao_menu_nodes" class="botao_menu_nodes" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" alt="Ícone" style="width: 30px; height: 30px; margin-top: -4px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <input type="text" id="search-input" placeholder="Pesquisar" style="margin-top: 19px; float: right;">
            </div>
            <div id="loadingMessage" class="loadingMessage" style="display: none;">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div class="wrapper-RastreamentoLote" style='margin-top: 20px;'>
                <div id="table-RastreamentoLote" class="ag-theme-alpine my-grid-RastreamentoLote">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal dos Números de Seriais-->
<div class="modal fade" id="textInputModal" tabindex="-1" role="dialog" aria-labelledby="textInputModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="header-modal"><?= lang('pesquisarLote') ?></h4>
            </div>
            <div class="modal-body">
                <p>Digite o(s) <u>número(s) de série</u> para localizar o rastreador desejado.</p>
                <textarea id="serial" type="number" style="max-width: 100%; min-width: 100%; height: 250px; min-height: 100px;" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button id="clearButton" class="btn btn-warning">Limpar</button>
                <button id="submitButton" class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_PAINEL_OMNILINK = '<?= site_url("Andromeda") ?>';
    var localeText = AG_GRID_LOCALE_PT_BR;

    $(document).ready(function() {
        $('.btn-expandir').on('click', function(e) {
            e.preventDefault();
            manipularMenuNodes();
        });

        atualizarAgGrid([]);
    });

    $('#BtnLimparPesquisar').click(function(e) {
        e.preventDefault();
        $('#serial').val('');
        atualizarAgGrid([]);
    })

    $('#openModalButton').click(function() {
        $('#textInputModal').modal('show');
    });

    $('#textInputModal').on('shown.bs.modal', function() {
        $('#serial').trigger('focus');
    });

    $('#textInputModal').on('hidden.bs.modal', function() {
        $('#serial').val('');
    });

    $('#clearButton').click(function() {
        $('#serial').val('');
    });

    $('#submitButton').click(function() {
        const serialValue = document.getElementById('serial').value;

        if (serialValue.trim()) {
            const serialArray = serialValue.match(/\d+/g);

            if (serialArray && serialArray.length > 0) {
                const seriaisFormatados = serialArray.join('\n');
                processSerialInput(seriaisFormatados);
            } else {
                alert("Não foram encontrados números válidos. Digite o(s) número(s) de série para realizar a pesquisa.");
                return;
            }
        } else {
            alert("Digite o(s) número(s) de série para realizar a pesquisa.");
            return;
        }
    });

    function processSerialInput(lines) {
        var pesquisados = [];
        var allData = [];

        for (var i = 0; i < lines.split('\n').length; i++) {
            if (!pesquisados.includes(lines.split('\n')[i].trim())) {
                pesquisados.push(lines.split('\n')[i].trim());
                var dataRequests = new Promise(function(resolve, reject) {
                    $.ajax({
                        url: `${URL_PAINEL_OMNILINK}/rastrearSerialIndividual`,
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            'serial': lines.split('\n')[i].trim()
                        },
                        success: function(resposta) {
                            if (resposta.status === 200) {
                                if (Object.keys(resposta.dados).length >= 2) {
                                    DadosAgGrid.push({
                                        ID: resposta?.dados[0].idEquipamento ?? '-',
                                        Tecnologia: resposta?.dados[0].nomeTecnologia ?? '-',
                                        Modelo: resposta?.dados[0].nomeModelo ?? '-',
                                        Status: resposta?.dados[0].descricaoStatus ?? '-',
                                        Op1: resposta?.dados[0].operadoraNome,
                                        Linha_1: resposta?.dados[0].ddd + resposta?.dados[0].fone,
                                        Ult_Con_1: corrigirDataHora(resposta?.dados[0].data).toLocaleString(),
                                        Op2: resposta?.dados[1].operadoraNome,
                                        Linha_2: resposta?.dados[1].ddd + resposta?.dados[1].fone,
                                        Ult_Con_2: corrigirDataHora(resposta?.dados[1].data).toLocaleString()
                                    })

                                } else if (Object.keys(resposta.dados).length == 1) {
                                    DadosAgGrid.push({
                                        ID: resposta?.dados[0].idEquipamento ?? '-',
                                        Tecnologia: resposta?.dados[0].nomeTecnologia ?? '-',
                                        Modelo: resposta?.dados[0].nomeModelo ?? '-',
                                        Status: resposta?.dados[0].descricaoStatus ?? '-',
                                        Op1: resposta?.dados[0].operadoraNome,
                                        Linha_1: resposta?.dados[0].ddd + resposta?.dados[0].fone,
                                        Ult_Con_1: corrigirDataHora(resposta?.dados[0].data).toLocaleString(),
                                        Op2: '-',
                                        Linha_2: '-',
                                        Ult_Con_2: '-'
                                    })
                                } else {
                                    alert("Não foi possível encontrar o serial solicitado.")
                                }
                                resolve();
                            } else {
                                alert("Não foi possível encontrar o serial solicitado.")
                                DadosAgGrid = [];
                                atualizarAgGrid(DadosAgGrid);
                            }
                        },
                        error: function(e) {
                            reject();
                            alert("Não foi possível encontrar o serial solicitado.")
                        }
                    })
                });

                allData.push(dataRequests);
            }
        };

        Promise.all(allData).then(function() {
            $("#pesquisaserial").attr("disabled", false);
            atualizarAgGrid(DadosAgGrid);
            DadosAgGrid = [];
            $('#textInputModal').modal('hide');
        });
    }

    function corrigirDataHora(dataHora) {
        const dataHoraCorrigida = new Date(dataHora);

        dataHoraCorrigida.setHours(dataHoraCorrigida.getHours() + 3);

        return dataHoraCorrigida
    }

    var AgGridRastreamentoLote;
    var DadosAgGrid = [];

    function atualizarAgGrid(dados) {
        stopAgGRID();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'ID',
                    field: 'ID',
                    chartDataType: 'category',
                },
                {
                    headerName: 'Tecnologia',
                    field: 'Tecnologia',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Modelo',
                    field: 'Modelo',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Status',
                    field: 'Status',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Operadora 1',
                    field: 'Op1',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Linha 1',
                    field: 'Linha_1',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Última Conexão 1',
                    field: 'Ult_Con_1',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Operadora 2',
                    field: 'Op2',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Linha 2',
                    field: 'Linha_2',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Última Conexão 2',
                    field: 'Ult_Con_2',
                    chartDataType: 'series',
                },
            ],
            defaultColDef: {
                enablePivot: true,
                editable: false,
                sortable: true,
                minWidth: 100,
                minHeight: 100,
                filter: true,
                resizable: true,
            },
            statusBar: {
                statusPanels: [{
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
                    },
                ],
            },
            localeText: localeText,
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            domLayout: 'autoHeight',
            pagination: true,
            paginationPageSize: 10,
        };

        var gridDiv = document.querySelector('#table-RastreamentoLote');
        AgGridRastreamentoLote = new agGrid.Grid(gridDiv, gridOptions);

        gridOptions.api.setRowData(dados);

        gridOptions.quickFilterText = '';
        document.querySelector('#search-input').addEventListener('input', function() {
            var searchInput = document.querySelector('#search-input');
            gridOptions.api.setQuickFilter(searchInput.value);
        });
    
        document.querySelector('#select-quantidade-por-pagina').addEventListener('change', function() {
            var selectedValue = document.querySelector('#select-quantidade-por-pagina').value;
            gridOptions.api.paginationSetPageSize(Number(selectedValue));
        });
        
        preencherExportacoes(gridOptions);
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#table-RastreamentoLote');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper-RastreamentoLote');
        if (wrapper) {
            wrapper.innerHTML = '<div id="table-RastreamentoLote" class="ag-theme-alpine my-grid-RastreamentoLote"></div>';
        }
    }

    let menuAberto = false;

    function manipularMenuNodes() {
        menuAberto = !menuAberto;
        let buttonSrc = menuAberto ? "<?php echo base_url('assets/images/icon-filter-show.svg') ?>" : "<?php echo base_url('assets/images/icon-filter-hide.svg') ?>";

        $('#botao_menu_nodes').attr("src", buttonSrc);
        $('#filtroBusca').toggle(!menuAberto);
        $('#conteudo').toggleClass('col-md-12 col-md-9');
    }

    function preencherExportacoes(gridOptions) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao_rastreamentoLote');
        const opcoes = ['csv', 'excel', 'pdf'];

        let buttonCSV = "<?php echo base_url('media/img/new_icons/csv.png') ?>";
        let buttonEXCEL = "<?php echo base_url('media/img/new_icons/excel.png') ?>";
        let buttonPDF = "<?php echo base_url('media/img/new_icons/pdf.png') ?>";

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
            div.classList.add('opcao_exportacao_rastreamento_lote');
            div.setAttribute('data-tipo', opcao);
            div.innerHTML = `
            <img src="${button}" alt="Ícone" style="width: 15px; margin-left: 10px; cursor: pointer;" title="Exportar no formato ${texto}">
            <label style="cursor: pointer; margin-top: 7px;" title="Exportar no formato ${texto}">${texto}</label>
        `;

            div.style.height = '30px';

            div.style.marginTop = margin;

            div.style.borderRadius = '1px';

            div.style.transition = 'background-color 0.3s ease';

            div.addEventListener('mouseover', function() {
                div.style.backgroundColor = '#f0f0f0';
            });

            div.addEventListener('mouseout', function() {
                div.style.backgroundColor = '';
            });

            div.style.border = '1px solid #ccc';

            div.addEventListener('click', function(event) {
                event.preventDefault();
                exportarArquivo(opcao, gridOptions);
            });

            formularioExportacoes.appendChild(div);
        });
    }

    function exportarArquivo(tipo, gridOptions) {
        switch (tipo) {
            case 'csv':
                fileName = 'RastreamentoLote.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'RastreamentoLote.xlsx';
                gridOptions.api.exportDataAsExcel({
                    fileName: fileName
                });
                break;
            case 'pdf':
                let dadosExportacao = prepararDadosExportacaoRelatorio();

                let definicoesDocumento = getDocDefinition(
                    printParams('A4'),
                    gridOptions.api,
                    gridOptions.columnApi,
                    dadosExportacao.informacoes,
                    dadosExportacao.rodape
                );
                pdfMake.createPdf(definicoesDocumento).download(dadosExportacao.nomeArquivo);
                break;

        }
    }

    function prepararDadosExportacaoRelatorio() {
        let informacoes = DadosAgGrid.map((dados) => ({
            'ID': dados.ID,
            'Tecnologia': dados.Tecnologia,
            'Modelo': dados.Modelo,
            'Status': dados.Status,
            'Op1': dados.Op1,
            'Linha_1': dados.Linha_1,
            'Ult_Con_1': dados.Ult_Con_1,
            'Op2': dados.Op2,
            'Linha_2': dados.Linha_2,
            'Ult_Con_2': dados.Ult_Con_2,
        }));

        let rodape = `Rastreamento Lote`;
        let nomeArquivo = `RastreamentoLote.pdf`;

        return {
            informacoes,
            nomeArquivo,
            rodape
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('opcoes_exportacao_rastreamentoLote');

        document.getElementById('dropdownMenuButton_RastreamentoLote').addEventListener('click', function() {
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButton_RastreamentoLote') {
                dropdown.style.display = 'none';
            }
        });
    });

    function printParams(pageSize) {
        return {
            PDF_HEADER_COLOR: "#ffffff",
            PDF_INNER_BORDER_COLOR: "#dde2eb",
            PDF_OUTER_BORDER_COLOR: "#babfc7",
            PDF_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
            PDF_HEADER_LOGO: '<?php echo base_url('media/img/new_icons/omnilink.png') ?>',
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
        }
    }
</script>