<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.js" integrity="sha512-yfb1lLOhiYYJh7C3dsBE4XGCnDCEe4dJ/jdVgoinVdKwVuDP2SJqrEngf0Q+m6gaU8vOjCaJ0EaeakGzXXfWIA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js" integrity="sha512-cktKDgjEiIkPVHYbn8bh/FEyYxmt4JDJJjOCu5/FQAkW4bc911XtKYValiyzBiJigjVEvrIAyQFEbRJZyDA1wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>

<style>
    .card_legenda {
        font-size: 12px;
        color: #909090;
        line-height: 18px;
        margin-bottom: 0px;
    }
</style>
<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('relatorio_iscas') ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('iscas') ?> >
        <?= lang('relatorios') ?> >
        <?= lang('relatorio_iscas') ?>
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3" id="menu_nodes">
        <div id="filtroBusca" class="card " style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important; margin-left: -5px !important;">Filtrar Dados</h4>
            <form style="align-items:center" id="formGerarResult">

                <input type="checkbox" id="checkboxPeriodo" name="checkboxPeriodo" value="false" style="display: none;" checked>

                <div class="input-container disponibilidade" id="disponibilidadeContainer" style='margin-bottom: 20px; position: relative;'>
                    <label for="disponibilidade">Disponibilidade da Isca:</label>
                    <select class="form-control" name="disponibilidade" id="disponibilidade" style="width: 100% !important;" required>
                        <option value="" selected disabled>Disponibilidade</option>
                        <option value="estoque">Em Estoque</option>
                        <option value="vinculada">Vinculada</option>
                    </select>
                </div>

                <div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
                    <label for="status">Status:</label>
                    <select class="form-control" name="status" id="status" style="width: 100% !important;" required>
                        <option value="" selected disabled>Status</option>
                        <option value="inativo">Inativo</option>
                        <option value="ativo">Ativo</option>
                    </select>
                    </select>
                </div>

                <div class="input-container" id="dateContainer1Dashboard" style='margin-bottom: 20px; position: relative;'>
                    <label for="dataInicio">Data Inicial:</label>
                    <input type="date" name="dataInicio" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicio" value="" />
                </div>

                <div class="input-container" id="dateContainer2Dashboard" style='margin-bottom: 20px; position: relative;'>
                    <label for="dataFim">Data Final:</label>
                    <input type="date" name="dataFim" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFim" value="" />
                </div>

                <p class="card_legenda"><?= lang('voce_deve_selecionar_um_periodo_de_ate_31_dias') ?></p>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary gerar_rel" style='width:100%' id="gerarRelatorioIscas"><i class="fa fa-search" aria-hidden="true"></i> <?= lang('gerar') ?></button>
                </div>

                <div class="button-container" style='margin-bottom: 20px; position: relative;'>
                    <button style='width:100%' id="BtnLimpar" class="btn btn-default" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> <?= lang('limpar') ?></button>
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
                        <button class="btn btn-gestor btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonRelatorioIscas" data-toggle="dropdown" style="height: 36.5px;">
                            <?= lang('exportar') ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_relatorio_iscas" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir-relatorio-iscas" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir-relatorio-iscas" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div id="emptyMessage" class="emptyMessage" style="display: none;">
                <h4><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div id="loadingMessage" class="loadingMessage" style="display: none;">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div class="wrapper-relatorio-iscas" style='margin-top: 20px;'>
                <div id="table-relatorio-iscas" class="ag-theme-alpine my-grid-relatorio-iscas">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var localeText = AG_GRID_LOCALE_PT_BR;

    $(document).ready(function() {
        $('.btn-expandir-relatorio-iscas').on('click', function(e) {
            e.preventDefault();
            manipularMenuNodes();
        });

        $('#disponibilidade').select2({
            language: 'pt-BR',
        });

        $('#status').select2({
            language: 'pt-BR',
        });

        atualizarAgGrid([]);
    });

    $('#BtnLimpar').on('click', function(e) {
        e.preventDefault();
        $('#disponibilidade').val("").trigger('change');
        $('#status').val("").trigger('change');
        $('#dataInicio').val("");
        $('#dataFim').val("");
        atualizarAgGrid([]);
    });

    function loadButton() {
        $('#gerarRelatorioIscas').html('<i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #fff;"></i> Gerando...');
    }

    function stopButton() {
        $('#gerarRelatorioIscas').html('<i class="fa fa-search" aria-hidden="true"></i> Gerar');
    }

    $('#formGerarResult').on('submit', function(e) {
        e.preventDefault();

        let data = $(this).serializeArray();
        let dataFormatada = {};
        data.forEach(item => {
            dataFormatada[item.name] = item.value;
        });

        if (parametrosValidos(dataFormatada) == false) {
            return;
        }

        loadButton();

        $.ajax({
            url: '<?= site_url("iscas/isca/ajaxRelatorioIscas") ?>',
            type: 'POST',
            data: dataFormatada,
            success: function(data) {
                let iscas = JSON.parse(data);
                if (iscas.length > 0) {
                    DadosAgGrid = iscas.map((dados) => ({
                        id: dados.id ? dados.id : "-",
                        serial: dados.serial ? dados.serial : "-",
                        descricao: dados.descricao ? dados.descricao : "-",
                        modelo: dados.modelo ? dados.modelo : "-",
                        marca: dados.marca ? dados.marca : "-",
                        data_cadastro: dados.data_cadastro ? formatDateTime(dados.data_cadastro) : "-",
                        data_expiracao: dados.data_expiracao ? formatDateTime(dados.data_expiracao) : "-",
                        status: dados.status ? dados.status : "-",
                        cliente: (dados.id_cliente != 0 && dados.nome) ? dados.nome : "-",
                        contrato: dados.id_contrato ? dados.id_contrato : "-",
                    }));
                    atualizarAgGrid(DadosAgGrid);
                    stopButton();
                } else {
                    atualizarAgGrid([]);
                }
                stopButton();
            },
            error: function() {
                alert("Erro ao gerar o relatório. Tente novamente.");
                stopButton();
            }
        });
    });

    function parametrosValidos(data) {
        if (data.disponibilidade == undefined || data.disponibilidade == null) {
            alert("Selecione a disponibilidade da Isca.")
            return false;
        } else if (data.status == undefined || data.status == null) {
            alert("Selecione o status da Isca.")
            return false;
        } else if (validarDatas(data.dataInicio, data.dataFim) == false) {
            return false;
        } else if (validarDiferençaDatas(data.dataInicio, data.dataFim, 30) == false) {
            alert('Informe um intervalo de 31 dias.');
            return false;
        } else {
            return true;
        }

    }

    let menuAberto = false;

    function manipularMenuNodes() {
        menuAberto = !menuAberto;
        let buttonSrc = menuAberto ? "<?php echo base_url('assets/images/icon-filter-show.svg') ?>" : "<?php echo base_url('assets/images/icon-filter-hide.svg') ?>";
        $('#img-expandir-relatorio-iscas').attr("src", buttonSrc);
        $('#filtroBusca').toggle(!menuAberto);
        $('#conteudo').toggleClass('col-md-12 col-md-9');
    }

    function returnStatusAtivoInativo(status) {
        label = '';
        if (status == 1) {
            label = '<span class="badge badge-success" style="background-color: green">Ativo</span>'
        } else {
            label = '<span class="badge badge-danger" style="">Inativo</span>';
        }
        return label;
    }

    var AgGridRelatoriosIscas;
    var DadosAgGrid = [];

    function atualizarAgGrid(dados) {
        stopAgGRID();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'ID',
                    field: 'id',
                    chartDataType: 'category',
                },
                {
                    headerName: 'Serial',
                    field: 'serial',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Descrição',
                    field: 'descricao',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Modelo',
                    field: 'modelo',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Marca',
                    field: 'marca',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Data Cadastro',
                    field: 'data_cadastro',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Data Expiração',
                    field: 'data_expiracao',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Status',
                    field: 'status',
                    chartDataType: 'series',
                    cellRenderer: function(params) {
                        return returnStatusAtivoInativo(params.value);
                    }
                },
                {
                    headerName: 'Cliente',
                    field: 'cliente',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Contrato',
                    field: 'contrato',
                    chartDataType: 'series',
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

        var gridDiv = document.querySelector('#table-relatorio-iscas');
        AgGridRelatoriosIscas = new agGrid.Grid(gridDiv, gridOptions);

        gridOptions.api.setRowData(dados);

        preencherExportacoes(gridOptions);
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#table-relatorio-iscas');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper-relatorio-iscas');
        if (wrapper) {
            wrapper.innerHTML = '<div id="table-relatorio-iscas" class="ag-theme-alpine my-grid-relatorio-iscas"></div>';
        }
    }

    function preencherExportacoes(gridOptions) {
        const formularioExportacoes = document.getElementById('opcoes_exportacao_relatorio_iscas');
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
                fileName = 'RelatorioIscas.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'RelatorioIscas.xlsx';
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
            ID: dados.ID,
            Serial: dados.Serial,
            Descrição: dados.Descrição,
            Modelo: dados.Modelo,
            Marca: dados.Marca,
            Data_Cadastro: dados.Data_Cadastro,
            Data_Expiração: dados.Data_Expiração,
            Status: dados.Status,
            Cliente: dados.Cliente,
            Contrato: dados.Contrato,
        }));

        let rodape = `Relatório Iscas`;
        let nomeArquivo = `RelatorioIscas.pdf`;

        return {
            informacoes,
            nomeArquivo,
            rodape
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('opcoes_exportacao_relatorio_iscas');

        document.getElementById('dropdownMenuButtonRelatorioIscas').addEventListener('click', function() {
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonRelatorioIscas') {
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