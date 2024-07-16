<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("equipamentosExpedicao") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('logistica') ?> >
        <?= lang('equipamentosExpedicao') ?>
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="card-conteudo" id="tableGRID" style='margin-bottom: 20px; position: relative;'>
        <h3>
            <b>Dados:</b>
            <div class="d-flex align-items-center" style="display: inline-flex;">

                <div class="dropdown" style="width: 100px;">
                    <button class="btn btn-gestor btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" style="height: 35px;">
                        <?= lang('exportar') ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="margin-right: 16px; min-width: 100px; top: 62px; height: 91px;">
                    </div>
                </div>
                <button class="btn btn-light btn-grid" id="btn_grid" onclick="manipularMenuNodes()" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="height: 35px;" disabled>
                    <img id="botao_menu_nodes" src="<?php echo base_url('media/img/new_icons/icon-filter-hide.png') ?>" alt="Ícone" style="width: 30px; height: 30px; margin-top: -4px;">
                </button>
            </div>
        </h3>
        <div>
            <select id="select-quantidade-por-pagina" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <input type="text" id="search-input" placeholder="Pesquisar" style="margin-top: 19px; float: right;">
        </div>
        <div id="emptyMessage" class="emptyMessage" style="display: none;">
            <h4><b>Nenhum dado a ser listado.</b></h4>
        </div>
        <div id="loadingMessage" class="loadingMessage" style="display: none;">
            <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
        </div>
        <div class="wrapper-equipamentos-expedicao" style='margin-top: 20px;'>
            <div id="table-equipamentos-expedicao" class="ag-theme-alpine my-grid-equipamentos-expedicao">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">

<script>
    var localeText = AG_GRID_LOCALE_PT_BR;
    var AgGridEquipamentosExpedicao;
    var DadosAgGrid = [];

    $(document).ready(function() {
        atualizarAgGrid([]);

        getDados(function(error, dados) {
            if (dados) {
                atualizarAgGrid(dados);
            }
        });
    });

    function atualizarAgGrid(dados) {
        stopAgGRID();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'Serial',
                    field: 'serial',
                    chartDataType: 'series',
                    width: 500,
                },
                {
                    headerName: 'Marca',
                    field: 'marca',
                    chartDataType: 'series',
                    width: 600,
                },
                {
                    headerName: 'Modelo',
                    field: 'modelo',
                    chartDataType: 'series',
                    width: 650,
                },
            ],
            defaultColDef: {
                enablePivot: true,
                editable: false,
                sortable: true,
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

        var gridDiv = document.querySelector('#table-equipamentos-expedicao');
        AgGridEquipamentosExpedicao = new agGrid.Grid(gridDiv, gridOptions);

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

    function getDados(callback, options) {
        $("#loadingMessage").show();

        $.ajax({
            cache: false,
            url: '<?= site_url('equipamentosExpedicao/listarSeriais') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#loadingMessage").hide();
                if (data.status == 200) {
                    DadosAgGrid = data.results.map(function(dado, index) {
                        return {
                            serial: dado.serial || '-',
                            marca: dado.marca || '-',
                            modelo: dado.modelo || '-',
                        }
                    });
                    if (typeof callback === "function") callback(null, DadosAgGrid);
                } else {
                    if (typeof callback === "function") callback(null, []);
                }
            },
            error: function(error) {
                $("#loadingMessage").hide();
                if (typeof callback === "function") callback(null, []);
            }
        });
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#table-equipamentos-expedicao');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper-equipamentos-expedicao');
        if (wrapper) {
            wrapper.innerHTML = '<div id="table-equipamentos-expedicao" class="ag-theme-alpine my-grid-equipamentos-expedicao"></div>';
        }
    }

    let menuAberto = false;

    function manipularMenuNodes() {
        menuAberto = !menuAberto;

        let buttonHide = "<?php echo base_url('media/img/new_icons/icon-filter-hide.png') ?>";
        let buttonShow = "<?php echo base_url('media/img/new_icons/icon-filter-show.png') ?>";

        if (menuAberto) {
            $('#botao_menu_nodes').attr("src", buttonShow);
            $('#filtroBusca').hide();
            $('#tableGRID').css('margin-left', -($('#menu_nodes').outerWidth() + 3) + 'px');
        } else {
            $('#botao_menu_nodes').attr("src", buttonHide);
            $('#filtroBusca').show();
            $('#tableGRID').css('margin-left', '0px');
        }
    }

    function preencherExportacoes(gridOptions) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao');
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
                fileName = 'EquipamentosExpedicao.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'EquipamentosExpedicao.xlsx';
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
            serial: dados.serial,
            marca: dados.marca,
            modelo: dados.modelo,
        }));

        let rodape = `Equipamentos de Expedição`;
        let nomeArquivo = `EquipamentosExpedicao.pdf`;

        return {
            informacoes,
            nomeArquivo,
            rodape
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('opcoes_exportacao');

        document.getElementById('dropdownMenuButton').addEventListener('click', function() {
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButton') {
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