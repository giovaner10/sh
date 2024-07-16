<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.js" integrity="sha512-yfb1lLOhiYYJh7C3dsBE4XGCnDCEe4dJ/jdVgoinVdKwVuDP2SJqrEngf0Q+m6gaU8vOjCaJ0EaeakGzXXfWIA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js" integrity="sha512-cktKDgjEiIkPVHYbn8bh/FEyYxmt4JDJJjOCu5/FQAkW4bc911XtKYValiyzBiJigjVEvrIAyQFEbRJZyDA1wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= $titulo ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('relatorios') ?> >
        <?= lang('clientes') ?> >
        <?= $titulo ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3" id="menu_nodes">
        <div id="filtroBusca" class="card " style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important; margin-left: -5px !important;">Filtrar Dados</h4>
            <form style="align-items:center" id="formGerarResult">
                
                <div class="input-container tipoProposta" id="tipoProposta" style='margin-bottom: 20px; position: relative;'>
                    <label for="tipo_proposta">Tipo de Proposta:</label>
                    <select class="form-control" name="tipo_proposta" id="tipo_proposta" style="width: 100% !important;" required>
                        <option value="" selected disabled><?= lang('selecionar_tipo') ?></option>
                        <option value="veiculos"><?= lang('veiculos') ?></option>
                        <option value="chips"><?= lang('chips') ?></option>
                        <option value="tornozeleiras"><?= lang('tornozeleiras') ?></option>
                        <option value="iscas"><?= lang('iscas') ?></option>
                    </select>
                </div>

                <div class="input-container empresa" id="empresaContainer" style='margin-bottom: 20px; position: relative;'>
                    <label for="empresa">Empresa:</label>
                    <select class="form-control" name="empresa" id="empresa" style="width: 100% !important;" required>
                        <option value="" selected disabled><?= lang('selecionar_empresa') ?></option>
                        <option value="todas"><?= lang('todas') ?></option>
                        <option value="EMBARCADORES"><?= lang('embarcadores') ?></option>
                        <option value="NORIO"><?= lang('norio') ?></option>
                        <option value="OMNILINK"><?= lang('omnilink') ?></option>
                        <option value="SIMM2M"><?= lang('simm2m') ?></option>
                        <option value="TRACKER"><?= lang('show_tecnologia') ?></option>
                    </select>
                </div>

                <div class="input-container orgao" id="orgaoContainer" style='margin-bottom: 20px; position: relative;'>
                    <label for="orgao">Órgão:</label>
                    <select class="form-control" name="orgao" id="orgao" style="width: 100% !important;" required>
                        <option value="" selected disabled><?= lang('select_orgao') ?></option>
                        <option value="todos"><?= lang('todos') ?></option>
                        <option value="publico"><?= lang('publico') ?></option>
                        <option value="privado"><?= lang('privado') ?></option>
                    </select>
                </div>

                <div class="input-container cliente" id="clienteContainer" style='margin-bottom: 20px; position: relative;'>
                    <label for="clientes">Cliente:</label>
                    <select class="form-control" name="clientes" id="clientes" style="width: 100% !important;" required>
                        <option value="todos"><?= lang('selecione_cliente') ?></option>
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary gerar_rel" style='width:100%' id="BtnPesquisar"><i class="fa fa-search" aria-hidden="true"></i> <?= lang('gerar') ?></button>
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
            <div id="emptyMessage" class="emptyMessage" style="display: none;">
                <h4><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div id="loadingMessage" class="loadingMessage" style="display: none;">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div class="wrapper-base-clientes" style='margin-top: 20px;'>
                <div id="table-base-clientes" class="ag-theme-alpine my-grid-base-clientes">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var empresa = 'todas',
        orgao = 'todos',
        cliente = 'todos',
        tipo_proposta = 'veiculos';
    var botao = $('.gerar_rel');
    var localeText = AG_GRID_LOCALE_PT_BR;

    $(document).ready(function() {
        $('#clientes').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            },
            language: 'pt-BR',
        });
        
        $('#empresa').select2({
            language: 'pt-BR',
        });

        $('#orgao').select2({
            language: 'pt-BR',
        });

        $('#tipo_proposta').select2({
            language: 'pt-BR',
        });

        var dadosForm = {
            empresa: 'todas',
            orgao: 'todos',
            tipo_proposta: 'veiculos',
            cliente: 'todos'
        }

        $('.btn-expandir').on('click', function(e) {
            e.preventDefault();
            manipularMenuNodes();
        });

        atualizarAgGrid();

        getDados(function(err, dados) {
            if (dados) {
                atualizarAgGrid(dados);
            } else {
                atualizarAgGrid()
            }
        }, dadosForm);

    });

    //RESETA DADOS DO FORM
    $('#BtnLimpar').click(function(e) {
        e.preventDefault();
        //RESETA OS SELECTS
        $("#tipo_proposta").val($("#tipo_proposta option:first").val());
        $("#empresa").val($("#empresa option:first").val());
        $("#orgao").val($("#orgao option:first").val());
        $('#clientes').val('todos').trigger('change');

        DadosAgGrid = [];
        atualizarAgGrid(DadosAgGrid);
    });

    //CARREGA A TABELA COM OS DADOS FILTRADOS
    $('#formGerarResult').submit(function(e) {
        e.preventDefault();

        empresaSelect = $('#empresa').val();
        orgaoSelect = $("#orgao").val();
        clienteSelect = $('#clientes').val();
        tipo_propostaSelect = $('#tipo_proposta').val();

        let dados = {
            empresa: empresaSelect,
            orgao: orgaoSelect,
            tipo_proposta: tipo_propostaSelect,
            cliente: clienteSelect
        };

        getDados(function(err, dados) {
            if (dados) {
                atualizarAgGrid(dados);
            } else {
                atualizarAgGrid()
            }
        }, dados);
    });

    var AgGridBaseClientes;
    var DadosAgGrid = [];

    function atualizarAgGrid(dados) {
        stopAgGRID();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'Cliente',
                    field: 'cliente',
                    chartDataType: 'category',
                },
                {
                    headerName: 'CPF/CNPJ',
                    field: 'cpf_cnpj',
                    chartDataType: 'series',
                },
                {
                    headerName: 'PF/PJ',
                    field: 'pf_pj',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Prestadora',
                    field: 'prestadora',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Orgão',
                    field: 'orgao',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Plano Disponível',
                    field: 'plano_disponivel',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Código Produto',
                    field: 'codigo_produto',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Descrição Produto',
                    field: 'descricao_produto',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Status Contrato',
                    field: 'status_contrato',
                    chartDataType: 'series',
                    cellRenderer: function(params) {
                        return params.value;
                    },
                },
                {
                    headerName: 'Valor Unitário',
                    field: 'valor_unitario',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Vencimento Mensalidade',
                    field: 'vencimento_mensalidade',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Item',
                    field: 'item',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Descrição Item',
                    field: 'descricao_item',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Serial',
                    field: 'serial',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Data Ativação',
                    field: 'data_ativacao',
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

        var gridDiv = document.querySelector('#table-base-clientes');
        AgGridBaseClientes = new agGrid.Grid(gridDiv, gridOptions);

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
            url: "<?= site_url('relatorios/ajaxBaseClientesServeSide') ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                empresa: options.empresa,
                orgao: options.orgao,
                tipo_proposta: options.tipo_proposta,
                cliente: options.cliente
            },
            success: function(data) {
                $("#loadingMessage").hide();
                if (data.data.length == 0) {
                    if (typeof callback === "function") callback(null, []);
                } else if (data.data.length > 0) {
                    DadosAgGrid = data.data.map(function(dado, index) {
                        return {
                            plus: index + 1,
                            cliente: dado?.cliente,
                            cpf_cnpj: dado?.cpf_cnpj,
                            pf_pj: dado?.pf_pj,
                            prestadora: dado?.prestadora,
                            orgao: dado?.orgao,
                            plano_disponivel: dado?.plano_disponivel,
                            codigo_produto: dado?.codigo_produto,
                            descricao_produto: dado?.descricao_produto,
                            status_contrato: dado?.status_contrato,
                            valor_unitario: dado?.valor_unitario,
                            vencimento_mensalidade: dado?.vencimento_mensalidade,
                            item: dado?.item,
                            descricao_item: dado?.descricao_item,
                            serial: dado?.serial,
                            data_ativacao: dado?.data_ativacao,
                        }
                    });
                    if (typeof callback === "function") callback(null, data.data);
                } else {
                    if (typeof callback === "function") callback(null, []);
                }
            },
            error: function(error) {
                $("#loadingMessage").hide();
                if (typeof callback === "function") callback(null, []);
                $('#emptyMessage').show();
            }
        });
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#table-base-clientes');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper-base-clientes');
        if (wrapper) {
            wrapper.innerHTML = '<div id="table-base-clientes" class="ag-theme-alpine my-grid-base-clientes"></div>';
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
                fileName = 'RelatorioBaseClientes.csv';
                gridOptions.api.exportDataAsCsv({
                    fileName: fileName
                });
                break;
            case 'excel':
                fileName = 'RelatorioBaseClientes.xlsx';
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
            cliente: dados.cliente,
            cpf_cnpj: dados.cpf_cnpj,
            pf_pj: dados.pf_pj,
            prestadora: dados.prestadora,
            orgao: dados.orgao,
            plano_disponivel: dados.plano_disponivel,
            codigo_produto: dados.codigo_produto,
            descricao_produto: dados.descricao_produto,
            status_contrato: dados.status_contrato,
            valor_unitario: dados.valor_unitario,
            vencimento_mensalidade: dados.vencimento_mensalidade,
            item: dados.item,
            descricao_item: dados.descricao_item,
            serial: dados.serial,
            data_ativacao: dados.data_ativacao,
        }));

        let rodape = `Relatório Base Clientes`;
        let nomeArquivo = `RelatorioBaseClientes.pdf`;

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