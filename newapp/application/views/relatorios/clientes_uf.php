<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

<style>
    .close {
        margin-right: 10px !important;
        margin-top: 10px !important;
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= $titulo ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('relatorios') ?> >
        <?= lang('clientes') ?> >
        <?= $titulo ?>
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3" id="menu_nodes">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important; margin-left: -5px !important;">Filtrar Dados</h4>
            <form id="formGerarResult">
                <div class="form-group filtro">
                    <div class="input-container buscaEstado">
                        <h5 class="add-on"><?= lang('selec_estado') ?>: </h5>
                        <select class="form-control" name="uf" id="uf" style="width: 100% !important;" required>
                            <option value="" disabled selected><?= lang('selecione') ?></option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select>
                    </div>

                    <div class="input-container buscaEmpresa">
                        <h5 class="add-on"><?= lang('empresa') ?>: </h5>
                        <select class="form-control" name="empresa" id="empresa" style="width: 100% !important;" required>
                            <option value="todos" selected><?= lang('todos') ?></option>
                            <option value="TRACKER"><?= lang('show_tecnologia') ?></option>
                            <option value="NORIO"><?= lang('norio') ?></option>
                            <option value="SIMM2M"><?= lang('simm2m') ?></option>
                        </select>
                    </div>

                    <div class="input-container buscaOrgao">
                        <h5 class="add-on"><?= lang('orgao') ?>: </h5>
                        <select class="form-control" name="orgao" id="orgao" style="width: 100% !important;" required>
                            <option value="todos" selected><?= lang('todos') ?></option>
                            <option value="publico"><?= lang('publico') ?></option>
                            <option value="privado"><?= lang('privado') ?></option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-primary gerar_rel" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> <?= lang('gerar') ?></button>
                    </div>
                    <div class="button-container" style='margin-bottom: 5px; position: relative;'>
                        <button style='width:100%' id="BtnLimpar" class="btn btn-default" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> <?= lang('limpar') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados-gerenciamento" id="tableGRID" style='margin-bottom: 20px; position: relative;'>
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
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="height: 35px;">
                        <img id="botao_menu_nodes" class="botao_menu_nodes" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" alt="Ícone" style="width: 30px; height: 30px; margin-top: -4px;">
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
            <div id="loadingMessage" class="loadingMessage" style="display: none;">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div class="wrapper_relatorios_clientes_uf" style='margin-top: 20px;'>
                <div id="table_relatorios_clientes_uf" class="ag-theme-alpine my-grid">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var localeText = AG_GRID_LOCALE_PT_BR;

    $(document).ready(function() {
        $('#orgao').select2({
            language: 'pt-BR',
        });

        $('#empresa').select2({
            language: 'pt-BR',
        });

        $('#uf').select2({
            language: 'pt-BR',
        });

        $('.btn-expandir').on('click', function(e) {
            e.preventDefault();
            manipularMenuNodes();
        });

        atualizarAgGrid();
    });

    //RESETA DADOS DO FORM
    $('#BtnLimpar').click(function(e) {
        e.preventDefault();
        //RESETA OS SELECTS
        $('#uf').val('').trigger('change');
        $('#empresa').val('todos').trigger('change');
        $('#orgao').val('todos').trigger('change');

        atualizarAgGrid();
    });

    //CARREGA A TABELA COM OS DADOS FILTRADOS
    $('#formGerarResult').submit(function(e) {
        e.preventDefault();

        var data_form = {
            uf: $('#uf').val(),
            empresa: $('#empresa').val(),
            orgao: $('#orgao').val(),
        };

        getDados(function(err, dados) {
            if (dados) {
                atualizarAgGrid(dados);
            } else {
                stopAgGRID();
            }
        }, data_form);
    });

    function showLoadingPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
    }

    function resetPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
    }

    function getDados(callback, options) {
        var dadosReturn;
        $("#loadingMessage").show();

        if (options && (options.uf || options.empresa || options.orgao)) {
            showLoadingPesquisarButton();
            data_form = options;
        } else {
            alert('Selecione os filtros para gerar o relatório.');
            resetPesquisarButton();
            $("#loadingMessage").hide();
            return;
        }

        $.ajax({
            cache: false,
            type: 'POST',
            url: "<?= site_url('relatorios/listClienesUF') ?>",
            data: data_form,
            dataType: 'json',
            success: function(data) {
                $("#loadingMessage").hide();
                resetPesquisarButton();
                if (data.status == true) {
                    DadosClientesUF = data.result.map((dado, index) => {
                        return {
                            linha: dado.linha || '-',
                            id: dado.id || '-',
                            nome: dado.nome || '-',
                            endereco: dado.endereco || '-',
                            cidade: dado.cidade || '-',
                            uf: dado.uf || '-',
                            telefone: dado.telefone || '-',
                            data_cadastro: dado.data_cadastro || '-',
                        };
                    });
                    if (typeof callback === "function") callback(null, DadosClientesUF);
                } else {
                    if (typeof callback === "function") callback(null, []);
                }
            },
            error: function(error) {
                $("#loadingMessage").hide();
                resetPesquisarButton();
                if (typeof callback === "function") callback(null, []);
            }
        });
    }

    var AgGridClientesUF;
    var DadosClientesUF = [];

    function atualizarAgGrid(dados) {
        stopAgGRID();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'Linha',
                    field: 'linha',
                    chartDataType: 'category',
                },
                {
                    headerName: 'ID',
                    field: 'id',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Nome',
                    field: 'nome',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Endereço',
                    field: 'endereco',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Cidade',
                    field: 'cidade',
                    chartDataType: 'series',
                },
                {
                    headerName: 'UF',
                    field: 'uf',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Telefone',
                    field: 'telefone',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Data Cadastro',
                    field: 'data_cadastro',
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
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            domLayout: 'autoHeight',
            pagination: true,
            paginationPageSize: 10,
            localeText: localeText,
        };

        var gridDiv = document.querySelector('#table_relatorios_clientes_uf');
        AgGridClientesUF = new agGrid.Grid(gridDiv, gridOptions);

        // gridOptions.api.setRowData(mappedData);
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
        var gridDiv = document.querySelector('#table_relatorios_clientes_uf');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper_relatorios_clientes_uf');
        if (wrapper) {
            wrapper.innerHTML = '<div id="table_relatorios_clientes_uf" class="ag-theme-alpine my-grid"></div>';
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
                fileName = 'RelatorioClientesUF.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'RelatorioClientesUF.xlsx';
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
        let informacoes = DadosClientesUF.map((dados) => ({
            linha: dados.linha,
            id: dados.id,
            nome: dados.nome,
            endereco: dados.endereco,
            cidade: dados.cidade,
            uf: dados.uf,
            telefone: dados.telefone,
            data_cadastro: dados.data_cadastro,
        }));

        let rodape = `Relatório Clientes por UF`;
        let nomeArquivo = `RelatorioClientesUF_${$('#uf').val()}_Empresa_${$('#empresa').val()}_Orgao_${$('#orgao').val()}`;

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