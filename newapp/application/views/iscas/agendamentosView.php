<style>
    .primary_color {
        color: #03A9F4;
    }

    .secondary_color {
        color: gray;
    }

    .danger_color {
        color: #F35353;
    }

    .success_color {
        color: #00c01f;
    }

    .margin_top {
        margin-top: 20px;
    }

    .modal-content {
        overflow: hidden !important;;
    }

    .select2-container .select2-selection--single {
        height: 35px !important;
        border-color: #d2d6de !important;
    }

    #loading-indicator {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 9999;
    }

    .spinnerr {
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

    :root {
        --altura-referencia: 0px;
        /* Valor inicial */
    }

    .elemento-alvo {
        position: absolute;
        left: -183px;
        top: calc(var(--altura-referencia) + 10px);
        /* Exemplo: adiciona um offset de 10px */
    }
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('media/calendario_agendamento/calendario_agendamento.css'); ?>">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente($titulo, site_url('Homes'), 'Isca', $titulo);
?>
<div class="row" style="margin: 15px 0 0 15px;">

    <div id="overlay">
        <div id="loading-indicator">
            <div class="spinner"></div>
        </div>
    </div>
    <div class="col-md-3 filtro_lateral" style="display: flex; flex-direction: column;">

        <div id="filtroBusca" class="card" style="margin-bottom: 20px;">
            <form id="formBusca">
                <div class="container_calendar">
                    <div class="calendar">
                        <div class="month">
                            <i class="fa fa-angle-double-left prev" aria-hidden="true"></i>
                            <div class="date">
                                <!-- Mês -->
                                <h4 id="title_month"></h4>
                                <!-- <p>Fri May 29, 2020</p> -->
                            </div>

                            <i class="fa fa-angle-double-right next" aria-hidden="true"></i>
                        </div>

                        <div class="weekdays">
                            <div>Dom</div>
                            <div>Seg</div>
                            <div>Ter</div>
                            <div>Qua</div>
                            <div>Qui</div>
                            <div>Sex</div>
                            <div>Sab</div>
                        </div>

                        <div class="days"></div>
                    </div>
                </div>
            </form>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 30px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca2">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="filtrar-atributos">ID ou Serial</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="ID ou Serial" id="filtrar-atributos" />
                    </div>


                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card">Agendamentos<span id="diaExibido"></span> </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center; ">
                    <button type="button" id="btnAbrirmodalCadastroAgendamento" class="btn btn-primary" style="margin-right: 10px; margin-bottom: 5px;">
                        <i class="fa fa-plus-square"></i>
                        Novo Agendamento
                    </button>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 92px;">
                            <div id="loadingMessage" style="text-align: center;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i>
                            </div>  
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
                    <div id="tableAgedamentos" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-2">

    </div>
</div>

<!-- Modal Cadastro/Edição Agend-->
<div class="modal fade" id="modalCadastroAgendamento" tabindex="-1" role="dialog" aria-labelledby="cadastroIscaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="cadastroIscaModalLabel" style="color: #1C69AD !important"></h3>
            </div>
            <div class="modal-body">
                <form id="formCadastroIsca">
                    <div class="col-md-12">
                        <input type="hidden" name="id_agendamento" id="id_agendamento">
                        <input type="hidden" name="edit_data_anterior_agendamento" id="edit_data_anterior_agendamento">
                        <div class="row">
                            <div class="col-sm-3 form-group">
                                <label for="dataAgendamento">Data</label>
                                <input type="date" class="form-control" name="dataAgendamento" id="dataAgendamento">
                            </div>
                            <div class="col-sm-3 form-group">
                                <label for="horaAgendamento">Hora</label>
                                <input type="text" class="form-control" name="horaAgendamento" id="horaAgendamento">
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        <option value="" selected></option>
                                        <option value="instalacao">Instalação</option>
                                        <option value="manutencao">Manutenção</option>
                                        <option value="transferencia">Transferência</option>
                                        <option value="retirada">Retirada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="serial">Serial</label>
                                    <select name="serial" id="serial" style="width: 100%">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="cliente">Cliente</label>
                                    <select name="cliente" id="cliente" style="width: 100%">
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="instalador">Instalador</label>
                                    <select name="instalador" id="instalador" style="width: 100%">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-10 form-group">
                                <label for="rua">Rua</label>
                                <input type="text" class="form-control" name="rua" id="rua">
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="numero">Número</label>
                                <input type="text" class="form-control" name="numero" id="numero">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label for="bairro">Bairro</label>
                                <input type="text" class="form-control" name="bairro" id="bairro">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control" name="cidade" id="cidade">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="uf">UF</label>

                                <select class="form-control" name="uf" id="uf">
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="obs">Observação</label>
                                    <textarea class="form-control" id="obs" name="obs" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="cadastrarIsca">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Isca-->
<div class="modal fade" id="modalVisualizarAgendamento" tabindex="-1" role="dialog" aria-labelledby="visualizarAgendamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" style="color: #1C69AD !important">Visualizar Agendamento</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-3 form-group">
                            <label for="showData">Data</label>
                            <input disabled type="text" class="form-control" name="showData" id="showData">
                        </div>
                        <div class="col-sm-3 form-group">
                            <label for="showHora">Hora</label>
                            <input disabled type="text" class="form-control" name="showHora" id="showHora">
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="showTipo">Tipo</label>
                                <input disabled type="text" class="form-control" name="showTipo" id="showTipo">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="showSerial">Serial</label>
                                <input disabled type="text" class="form-control" name="showSerial" id="showSerial">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="showCliente">Cliente</label>
                                <input disabled type="text" class="form-control" name="showCliente" id="showCliente">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="showInstalador">Instalador</label>
                                <input disabled type="text" class="form-control" name="showInstalador" id="showInstalador">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="showRua">Rua</label>
                            <input disabled type="text" class="form-control" name="showRua" id="showRua">
                        </div>
                        <div class="col-sm-2 form-group">
                            <label for="showNumero">Número</label>
                            <input disabled type="text" class="form-control" name="showNumero" id="showNumero">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label for="showBairro">Bairro</label>
                            <input disabled type="text" class="form-control" name="showBairro" id="showBairro">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="showCidade">Cidade</label>
                            <input disabled type="text" class="form-control" name="showCidade" id="showCidade">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="showUF">UF</label>
                            <input disabled type="text" class="form-control" name="showUF" id="showUF">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label for="showSituacao">Situação</label>
                            <input disabled type="text" class="form-control" name="showSituacao" id="showSituacao">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label for="showStatus">Status</label>
                            <input disabled type="text" class="form-control" name="showStatus" id="showStatus">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label for="showDataFim">Data Finalização</label>
                            <input disabled type="text" class="form-control" name="showDataFim" id="showDataFim">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="showObs">Observação</label>
                                <textarea disabled class="form-control" name="showObs" rows="3" id="showObs" style="resize: vertical;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <!-- <button type="button" class="btn btn-primary" id="cadastrarIsca">Salvar</button> -->
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.min.js"></script>


<!-- Estilo do botão status -->
<link rel="stylesheet" type="text/css" href="<?= base_url("media/css/toggle-button.css") ?>">
<!-- helper iscas -->
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
    var BaseURL = '<?= base_url('') ?>';
    var localeText = {
        // Set Filter
        selectAll: "(Selecionar tudo)",
        selectAllSearchResults: "(Selecionar todos os resultados da pesquisa)",
        searchOoo: "Pesquisando...",
        blanks: "(Em branco)",
        noMatches: "Nenhum resultado encontrado",

        // Number Filter & Text Filter
        filterOoo: "Filtrando...",
        equals: "Igual a",
        notEqual: "Diferente de",
        empty: "Vazio",

        // Number Filter
        lessThan: "Menor que",
        greaterThan: "Maior que",
        lessThanOrEqual: "Menor ou igual a",
        greaterThanOrEqual: "Maior ou igual a",
        inRange: "Entre",
        inRangeStart: "De",
        inRangeEnd: "Até",

        // Text Filter
        contains: "Contém",
        notContains: "Não contém",
        startsWith: "Começa com",
        endsWith: "Termina com",

        // Date Filter
        dateFormatOoo: "dd/mm/yyyy",

        // Filter Conditions
        andCondition: "E",
        orCondition: "OU",

        // Filter Buttons
        applyFilter: "Aplicar filtro",
        resetFilter: "Redefinir filtro",
        clearFilter: "Limpar filtro",
        cancelFilter: "Cancelar filtro",

        // Filter Titles
        textFilter: "Filtro de texto",
        numberFilter: "Filtro numérico",
        dateFilter: "Filtro de data",
        setFilter: "Definir filtro",

        // Side Bar
        columns: "Colunas",
        filters: "Filtros",

        // columns tool panel
        pivotMode: "Modo de agrupamento",
        groups: "Grupos",
        rowGroupColumnsEmptyMessage: "Arraste uma coluna para o grupo",
        values: "Valores",
        valueColumnsEmptyMessage: "Arraste uma coluna para o valor",
        pivots: "Agrupamentos",
        pivotColumnsEmptyMessage: "Arraste uma coluna para o agrupamento",

        // Header of the Default Group Column
        group: "Grupo",

        // Other
        loadingOoo: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b>',
        noRowsToShow: "Nenhum registro para mostrar",
        enabled: "Habilitado",

        // Menu
        pinColumn: "Fixar coluna",
        pinLeft: "Fixar à esquerda",
        pinRight: "Fixar à direita",
        noPin: "Não fixar",
        valueAggregation: "Agregação de valores",
        autosizeThiscolumn: "Redimensionar esta coluna",
        autosizeAllColumns: "Redimensionar todas as colunas",
        groupBy: "Agrupar por",
        ungroupBy: "Desagrupar por",
        resetColumns: "Redefinir colunas",
        expandAll: "Expandir todos",
        collapseAll: "Recolher todos",
        copy: "Copiar",
        ctrlC: "Ctrl+C",
        copyWithHeaders: "Copiar com cabeçalhos",
        paste: "Colar",
        ctrlV: "Ctrl+V",
        export: "Exportar",
        csvExport: "Exportar para CSV",
        excelExport: "Exportar para Excel",

        // Enterprise Menu Aggregation and Status Bar
        sum: "Soma",
        min: "Mínimo",
        max: "Máximo",
        none: "Nenhum",
        count: "Contagem",
        avg: "Média",
        filteredRows: "Linhas filtradas",
        selectedRows: "Linhas selecionadas",
        totalRows: "Total de linhas",
        totalAndFilteredRows: "Total e linhas filtradas",
        more: "Mais",
        to: "para",
        of: "de",
        page: "Página",
        nextPage: "Próxima página",
        lastPage: "Última página",
        firstPage: "Primeira página",
        previousPage: "Página anterior",

        // Pivoting
        pivotColumnGroupTotals: "Total de grupo de colunas",

        // Enterprise Menu (Charts)
        pivotChartAndPivotMode: "Grafíco Pivô & Modo Pivô",
        pivotChart: "Gráfico Pivô",
        chartRange: "Alcance do gráfico",

        columnChart: "Coluna",
        groupedColumn: "Agrupada",
        stackedColumn: "Empilhada",
        normalizedColumn: "100% empilhada",

        barChart: "Barras",
        groupedBar: "Agrupada",
        stackedBar: "Empilhada",
        normalizedBar: "100% empilhada",

        pieChart: "Pizza",
        pie: "Pizza",
        doughnut: "Rosquinha",

        line: "Linha",

        xyChart: "X Y (Dispersão)",
        scatter: "Dispersão",
        bubble: "Bolhas",

        areaChart: "Área",
        area: "Área",
        stackedArea: "Empilhada",
        normalizedArea: "100% empilhada",

        histogramChart: "Histograma",

        // Charts
        pivotChartTitle: "Gráfico Pivô",
        rangeChartTitle: "Alcance do gráfico",
        settings: "Configurações",
        data: "Dados",
        format: "Formato",
        categories: "Categorias",
        defaultCategory: "(padrão)",
        series: "Séries",
        xyValues: "Valores X Y",
        paired: "Par",
        axis: "Eixo",
        navigator: "Navegador",
        color: "Cor",
        thickness: "Espessura",
        xType: "Tipo X",
        automatic: "Automático",
        category: "Categoria",
        number: "Número",
        time: "Tempo",
        xRotation: "Rotação X",
        yRotation: "Rotação Y",
        ticks: "Ticks",
        width: "Largura",
        height: "Altura",
        length: "Comprimento",
        padding: "Preenchimento",
        spacing: "Espaçamento",
        chart: "Gráfico",
        title: "Título",
        titlePlaceholder: "Título aqui",
        background: "Fundo",
        font: "Fonte",
        top: "Topo",
        right: "Direita",
        bottom: "Fundo",
        left: "Esquerda",
        labels: "Rótulos",
        size: "Tamanho",
        minSize: "Tamanho mínimo",
        maxSize: "Tamanho máximo",
        legend: "Legenda",
        position: "Posição",
        markerSize: "Tamanho do marcador",
        markerStroke: "Marcador de traço",
        markerPadding: "Marcador de preenchimento",
        itemSpacing: "Espaçamento do item",
        itemPaddingX: "Preenchimento X do item",
        itemPaddingY: "Preenchimento Y do item",
        layoutHorizontalSpacing: "Espaçamento horizontal do layout",
        layoutVerticalSpacing: "Espaçamento vertical do layout",
        strokeWidth: "Espessura do traço",
        offset: "Offset",
        offsets: "Offsets",
        tooltips: "Dicas",
        callout: "Callout",
        markers: "Marcadores",
        shadow: "Sombra",
        blur: "Desfocar",
        xOffset: "Offset X",
        yOffset: "Offset Y",
        lineWidth: "Largura da linha",
        normal: "Normal",
        bold: "Negrito",
        italic: "Itálico",
        boldItalic: "Negrito itálico",
        predefined: "Predefinido",
        fillOpacity: "Opacidade de preenchimento",
        strokeOpacity: "Opacidade do traço",
        histogramBinCount: "Contagem de binário do histograma",
        columnGroup: "Agrupar coluna",
        barGroup: "Agrupar barra",
        pieGroup: "Agrupar pizza",
        lineGroup: "Agrupar linha",
        scatterGroup: "Agrupar dispersão",
        areaGroup: "Agrupar área",
        histogramGroup: "Agrupar histograma",
        groupedColumnTooltip: "Agrupar por coluna",
        stackedColumnTooltip: "Empilhada por coluna",
        normalizedColumnTooltip: "100% empilhada por coluna",
        groupedBarTooltip: "Agrupar por barra",
        stackedBarTooltip: "Empilhada por barra",
        normalizedBarTooltip: "100% empilhada por barra",
        pieTooltip: "Pizza",
        doughnutTooltip: "Rosquinha",
        lineTooltip: "Linha",
        groupedAreaTooltip: "Agrupar por área",
        stackedAreaTooltip: "Empilhada por área",
        normalizedAreaTooltip: "100% empilhada por área",
        scatterTooltip: "Dispersão",
        bubbleTooltip: "Bolha",
        histogramTooltip: "Histograma",
        noDataToChart: "Não há dados para gráfico",
        pivotChartRequiresPivotMode: "Gráfico Pivô requer modo pivô",
        chartSettingsToolbarTooltip: "Configurações do gráfico",
        chartLinkToolbarTooltip: "Link do gráfico",
        chartUnlinkToolbarTooltip: "Desvincular gráfico",
        chartDownloadToolbarTooltip: "Download do gráfico",

        // ARIA
        ariaHidden: "escondido",
        ariaVisible: "visível",
        ariaChecked: "verificado",
        ariaUnchecked: "não verificado",
        ariaIndeterminate: "indeterminado",
        ariaDefaultListName: "lista",
        ariaColumnSelectAll: "Selecionar todas as colunas",
        ariaInputEditor: "Editor de entrada",
        ariaDateFilterInput: "Filtrar entrada de data",
        ariaFilterList: "Filtrar list",
        ariaFilterInput: "Filtrar entrada",
        ariaFilterColumnsInput: "Entrada de filtro de colunas",
        ariaFilterValue: "Filtrar valor",
        ariaFilterFromValue: "Filtrar pelo valor de",
        ariaFilterToValue: "Filtrar pelo valor para",
        ariaFilteringOperator: "Operador de filtragem",
        ariaColumn: "Coluna",
        ariaColumnList: "Lista de colunas",
        ariaColumnGroup: "Grupo de colunas",
        ariaRowSelect: "Aperte ESPAÇO para selecionar esta linha",
        ariaRowDeselect: "Aperte ESPAÇO para desmarcar esta linha",
        ariaRowToggleSelection: "Aperte ESPAÇO para alternar a seleção desta linha",
        ariaRowSelectAll: "Aperte ESPAÇO para alternar a seleção de todas as linhas",
        ariaToggleVisibility: "Aperte ESPAÇO para alternar a visibilidade",
        ariaSearch: "Pesquisar",
        ariaSearchFilterValues: "Pesquisar valores de filtro",

        // ARIA LABEL FOR DIALOGS
        ariaLabelColumnMenu: "Menu de coluna",
        ariaLabelCellEditor: "Editor de célula",
        ariaLabelDialog: "Diálogo",
        ariaLabelSelectField: "Selecionar campo",
        ariaLabelTooltip: "Dica",
        ariaLabelContextMenu: "Menu de contexto",
        ariaLabelSubMenu: "Sub-menu",
        ariaLabelAggregationFunction: "Função de agregação",
    };;



    $(document).ready(() => {
        // Desabilita serial. A busca deve ser feita somente quando for selecionado o tipo
        $("#serial").prop('disabled', true);
        // abilita o serial quando o tipo for selecionado
        $("#tipo").change(() => {
            if ($("#tipo").val() == '') {
                $("#serial").prop('disabled', true);
            } else {
                $("#serial").prop('disabled', false);
            }
        })
        // Select Serial
        let selectSerial = $("#serial").select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?php echo site_url('iscas/isca/ajaxGetIscaAgendamento') ?>',
                dataType: "json",
                type: "POST",
                delay: 250,
                data: function(params) {

                    return {
                        searchTerm: params.term,
                        tipo: $("#tipo").val()
                    };

                },
                processResults: function(data) {

                    return {
                        results: data
                    };
                },

                cache: true
            }
        });
        // Select Cliente
        $("#cliente").select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?php echo site_url('iscas/isca/ajaxGetCliente') ?>',
                dataType: "json",
                type: "POST",
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                    };
                },
                processResults: function(data) {

                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        // Select Instalador
        $("#instalador").select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?php echo site_url('iscas/isca/ajaxGetInstalador') ?>',
                dataType: "json",
                type: "POST",
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                    };
                },
                processResults: function(data) {

                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $(".dt-buttons").prepend(`
            <button type="button" id="btnAbrirmodalCadastroAgendamento" class="btn btn-primary">
                <i class="fa fa-plus-square"></i>
                Novo Agendamento
            </button>
        `);
        $("#btnAbrirmodalCadastroAgendamento").click(() => {
            limparModal();
            $("#cadastroIscaModalLabel").html("Cadastrar Agendamento");
            $("#modalCadastroAgendamento").modal("show");

        });
    });
    // Mask
    $(document).ready(function() {
        var mask = function(val) {
            val = val.split(":");
            return (parseInt(val[0]) > 19) ? "HZ:M0:M0" : "H0:M0:M0";
        }

        pattern = {
            onKeyPress: function(val, e, field, options) {
                field.mask(mask.apply({}, arguments), options);
            },
            translation: {
                'H': {
                    pattern: /[0-2]/,
                    optional: false
                },
                'Z': {
                    pattern: /[0-3]/,
                    optional: false
                },
                'M': {
                    pattern: /[0-5]/,
                    optional: false
                }
            },
        };
        $('#horaAgendamento').mask(mask, pattern);
    });

    const months = [
        "Janeiro",
        "Fevereiro",
        "Março",
        "Abril",
        "Maio",
        "Junho",
        "Julho",
        "Agosto",
        "Setembro",
        "Outubro",
        "Novembro",
        "Dezembro"
    ];
    // dicionario que guarda o número de agendamentos por dia
    let num_agendamentos_por_dia = {};

    $(document).ready(function() {
        // Retorna os agendamentos do dia atual
        get_agendamentos_por_dia();

        let dia_atual = new Date();
        const stringYear = dia_atual.getFullYear();
        const stringMonth = parseInt(dia_atual.getMonth() + 1) < 10 ? '0' + (dia_atual.getMonth() + 1) : (dia_atual.getMonth() + 1);
        const stringDay = (parseInt(dia_atual.getDate()) < 10) ? '0' + parseInt(dia_atual.getDate()) : parseInt(dia_atual.getDate());
        const inicioMes = stringYear + '-' + stringMonth + '-01';
        const ultimoDiaDoMes = new Date(
            dia_atual.getFullYear(),
            dia_atual.getMonth() + 1,
            0
        ).getDate();
        const fimMes = stringYear + '-' + stringMonth + '-' + ultimoDiaDoMes;
        ajax_get_agendamentos(inicioMes, fimMes);
    });

    /**
     * Datatable
     */
    let tabela_agendamentos = $("#tabela_agendamentos").DataTable({
        ordering: true,
        scrollCollapse: true,
        paging: true,
        dom: 'Bfrtip',
        columnDefs: [{
            orderable: false,
            targets: [5, 6]
        }],
        initComplete: function() {
            $(document).on('click', '#gerar_pdf', function() {
                $(".buttons-pdf").trigger("click");
            });

            $(document).on('click', '#gerar_excel', function() {
                $(".buttons-excel").trigger("click");
            });

            $(document).on('click', '#gerar_csv', function() {
                $(".buttons-csv").trigger("click");
            });

            $(document).on('click', '#gerar_print', function() {
                $(".buttons-print").trigger("click");
            });
        },
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        buttons: [{
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'portrait',
                pageSize: 'LEGAL',
                extend: 'pdfHtml5',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, col, node) {
                            const tipoElemento = node.firstChild;

                            if (tipoElemento != null || tipoElemento != undefined) {
                                // o único elemento da tabela que está dentro de uma div é o status
                                if (tipoElemento.nodeName == "DIV") {
                                    const element = tipoElemento.children.item(0); // elemento (select ou span)
                                    if (element != null) {
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if (element.type == 'select-one') { // select
                                            return (element.options[element.selectedIndex].text)
                                        } else { // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }

                                } else {
                                    return data
                                }
                            } else {
                                return data;
                            }
                        }

                    }
                },



            },
            {
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, col, node) {
                            const tipoElemento = node.firstChild;

                            if (tipoElemento != null || tipoElemento != undefined) {
                                // o único elemento da tabela que está dentro de uma div é o status
                                if (tipoElemento.nodeName == "DIV") {
                                    const element = tipoElemento.children.item(0); // elemento (select ou span)

                                    if (element != null) {
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if (element.type == 'select-one') { // select
                                            return (element.options[element.selectedIndex].text)
                                        } else { // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }

                                } else {
                                    return data
                                }
                            } else {
                                return data;
                            }
                        }

                    }
                },


            },
            {
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, col, node) {
                            const tipoElemento = node.firstChild;

                            if (tipoElemento != null || tipoElemento != undefined) {
                                // o único elemento da tabela que está dentro de uma div é o status
                                if (tipoElemento.nodeName == "DIV") {
                                    const element = tipoElemento.children.item(0); // elemento (select ou span)

                                    if (element != null) {
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if (element.type == 'select-one') { // select
                                            return (element.options[element.selectedIndex].text)
                                        } else { // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }

                                } else {
                                    return data
                                }
                            } else {
                                return data;
                            }
                        }

                    }
                },

            },
            {
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'print',
                text: 'Imprimir',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, col, node) {
                            const tipoElemento = node.firstChild;

                            if (tipoElemento != null || tipoElemento != undefined) {
                                // o único elemento da tabela que está dentro de uma div é o status
                                if (tipoElemento.nodeName == "DIV") {
                                    const element = tipoElemento.children.item(0); // elemento (select ou span)

                                    if (element != null) {
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if (element.type == 'select-one') { // select
                                            return (element.options[element.selectedIndex].text)
                                        } else { // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }

                                } else {
                                    return data
                                }
                            } else {
                                return data;
                            }
                        }

                    }
                },

            }
        ],
    });

    const gridOptions = {
        columnDefs: [{
                headerName: "ID",
                field: "id"
            },
            {
                headerName: "Serial",
                field: "serial"
            },
            {
                headerName: "Situação",
                field: "situacao",
                width: 400
            },
            {
                headerName: "Data",
                colId: "data_agendamento",
                valueGetter: function(params) {
                    return returnData(params.data.data_agendamento)
                }
            },
            {
                headerName: "Hora",
                colId: "hora_agendamento",
                valueGetter: function(params) {
                    return params.data.data_agendamento.split(" ")[1]
                }
            },
            {
                headerName: "Tipo",
                width: 300,
                colId: "tipo",
                valueGetter: function(params) {
                    return returnTipo(params.data.tipo)
                }
            },
            {
                headerName: "Ações",
                pinned: 'right',
                width: 95,
                field: "acoes",
                cellClass: "actions-button-cell",
                cellRenderer: function(options) {

                    let data = options.data;
                    let id = data.id;
                    let status = data.status;
                    let i = options.rowIndex;

                    return `
                    <div class="dropdown" style="position: absolute;">
                        <button  class="btn btn-dropdown dropdown-toggle mydropheight" type="button" id="mydropheight" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes dropdown-menu-dados-gerenciamento" id="btnsAdministrar${id}" style="position:absolute; left: -183px; top: calc(90% - ${i * 12}px);" aria-labelledby="{buttonId}">
                          
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a onclick="javascript:visualizarAgendamento(${id})" id="btnVisualizarAgend${id}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Visualizar</a>
                            </div>

                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a onclick="javascript:showModalEditarAgendamento(${id}, ${status})" id="btnEditarAgend${id}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Editar</a>
                            </div>

                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a onclick="javascript:confirmarAgend(${id}, ${status})" id="btnConfirmarAgend${id}" style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;">Confirmar</a>
                            </div>

                        </div>
                    </div>`;
                }
            }

        ],
        rowData: [],
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
        localeText: localeText,
    };
    var gridDiv = document.querySelector("#tableAgedamentos");
    new agGrid.Grid(gridDiv, gridOptions);
    let result = []

    $("#filtrar-atributos").on("keyup", function() {
        const inputValue = $(this).val().toString().trim().toLowerCase();
        const filteredResult = result.filter(
            (item) =>
            item.serial.toString().toLowerCase().includes(inputValue) ||
            item.id.toLowerCase().includes(inputValue)
        );

        updateData(filteredResult);
    });

    $("#BtnPesquisar").on("click", function(e) {
        e.preventDefault()
        const inputValue = $('#filtrar-atributos').val().toString().trim().toLowerCase();
        const filteredResult = result.filter(
            (item) =>
            item.serial.toString().toLowerCase().includes(inputValue) ||
            item.id.toLowerCase().includes(inputValue)
        );

        updateData(filteredResult);
    });


    $(document).ready(function() {

        $("#mydropheight").click(function() {
            console.log("adicao clique")
        });
    });


    $("#BtnLimparFiltro").on("click", function() {
        $("#filtrar-atributos").val("");
        updateData(result);
    });

    $("#select-quantidade-por-contatos-corporativos").change(function() {
        gridOptions.api.paginationSetPageSize($(this).val());
    });

    function updateData(newData = []) {
        gridOptions.api.setRowData(newData);
    }

    /**
     * Função que atualiza a tabela com os agendamentos do dia de acordo com o grupo selecionado
     */
    async function get_agendamentos_por_dia(dia = null) {

        let data;

        $("#diaExibido").html('<i class="fa fa-spinner fa-spin"></i>');
        updateData();
        $(".ag-overlay-no-rows-center").html('<div class="spinnerr"></div>');


        if (dia === null) {
            let dia_atual = new Date();
            const stringYear = dia_atual.getFullYear();
            const stringMonth = parseInt(dia_atual.getMonth() + 1) < 10 ? '0' + (dia_atual.getMonth() + 1) : (dia_atual.getMonth() + 1);
            const stringDay = (parseInt(dia_atual.getDate()) < 10) ? '0' + parseInt(dia_atual.getDate()) : parseInt(dia_atual.getDate());
            const dataAgendamentos = stringYear + '-' + stringMonth + '-' + stringDay
            data = {
                dia: dataAgendamentos
            }
        } else {
            data = {
                dia: dia
            }
        }

        await $.ajax({
            url: '<?= site_url('iscas/isca/ajax_get_agendamentos_por_dia'); ?>',
            type: 'GET',
            data: data,
            success: function(callback) {
                let agendamentos = JSON.parse(callback);

                $("#diaExibido").html(` - ${returnData(data.dia)}`);
                $("#dataExibida").val(data.dia) // adiciona em um input hidden, a data que está sendo exibida no datatable

                updateData(agendamentos);
                result = agendamentos;
                preencherExportacoes(gridOptions, "Relatorio")
            },
            error: function(error) {
                console.log(error)
                $("#diaExibido").html(` - ${returnData(data.dia)}`);
            }
        });
    }

    /**
     * Função que atualiza calendário com todos os agendamentos do grupo selecionado
     */
    async function ajax_get_agendamentos(initDate, endDate) {

        $("#spinnner_titulo_calendario").html('<i class="fa fa-spinner fa-spin"></i>');
        $(".ag-overlay-no-rows-center").html('<div class="spinnerr"></div>');

        const data = {
            initDate: initDate,
            endDate: endDate,
        }

        // limpa o calendário
        num_agendamentos_por_dia = {};
        renderCalendar();

        await $.ajax({
            url: '<?= site_url('iscas/isca/ajax_get_agendamentos'); ?>',
            type: 'GET',
            data: data,
            success: function(callback) {
                let agendamentos = JSON.parse(callback);
                // limpa os agendamentos
                num_agendamentos_por_dia = {}

                if (agendamentos.length > 0) {
                    agendamentos.forEach(agend => {
                        // caso o agendamento ainda não tenha sido finalizado, faz a contagem dos agendamentos e adiciona
                        // no dicionário que será inserido no calendário
                        if (agend.status_confirmacao != "1") {

                            const data_agendamento = agend.data_agendamento.split(' ')[0];
                            if (num_agendamentos_por_dia[data_agendamento] != undefined) {
                                num_agendamentos_por_dia[data_agendamento] += 1;
                            } else {
                                num_agendamentos_por_dia[data_agendamento] = 1;
                            }
                        }

                    });

                    // Chama função para renderizar o calendário
                    renderCalendar();
                }
                $("#spinnner_titulo_calendario").html('');
            },
            error: function(error) {
                $("#spinnner_titulo_calendario").html('');
                console.log(error)
            }
        });
    }




    $("#cadastrarIsca").click(() => {
        if ($("#id_agendamento").val() === "") {
            cadastrarAgendamento();
        } else {
            editarAgendamento();
        }
    });
    /**
     * Cadastrar Agendamento
     */
    function cadastrarAgendamento() {
        const button = $("#cadastrarIsca");
        const data = {
            dataAgendamento: $("#dataAgendamento").val(),
            horaAgendamento: $("#horaAgendamento").val(),
            tipo: $("#tipo").val(),
            serial: $("#serial").val(),
            cliente: $("#cliente").val(),
            instalador: $("#instalador").val(),
            rua: $("#rua").val(),
            numero: $("#numero").val(),
            bairro: $("#bairro").val(),
            cidade: $("#cidade").val(),
            uf: $("#uf").val(),
            obs: $("#obs").val(),
        };

        if (validarParametros(data)) {

            button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando');
            $.ajax({
                url: '<?php echo site_url('iscas/isca/ajaxCadastrarAgendamento') ?>',
                dataType: "json",
                type: "POST",
                data: data,
                success: function(callback) {
                    let agend = callback

                    alert("Agendamento cadastrado com sucesso.");
                    button.attr('disabled', false).html('Salvar');
                    $("#modalCadastroAgendamento").modal("hide");

                    // se o agendamento inserido for na data que está sendo exibida, adiciona ele no datatable
                    if ($("#dataExibida").val() === agend.data_agendamento.split(" ")[0]) {
                        // Adiciona linha ao datatable

                        tabela_agendamentos.row.add([
                            agend.id,
                            agend.serial,
                            returnData(agend.data_agendamento),
                            agend.data_agendamento.split(" ")[1],
                            returnTipo(agend.tipo),
                            returnSituacao(agend.situacao, agend.status, agend.id),
                            returnBtnAcoes(agend.status, agend.id)
                        ]).draw();
                        // reordena tabela pela data dos agendamentos
                        tabela_agendamentos.column(1).data().order([1, 'asc']).draw();
                    }

                    // caso o agendamento ainda não tenha sido finalizado, faz a contagem dos agendamentos e adiciona
                    // no dicionário que será inserido no calendário
                    const data_agendamento = data.dataAgendamento;
                    if (num_agendamentos_por_dia[data_agendamento] != undefined) {
                        num_agendamentos_por_dia[data_agendamento] += 1;
                    } else {
                        num_agendamentos_por_dia[data_agendamento] = 1;
                    }

                    // renderiza o calendário
                    renderCalendar();

                },
                error: function(error) {
                    button.attr('disabled', false).html('Salvar');
                }
            });
        }
    }
    /**
     * Editar Agendamento
     */
    function editarAgendamento() {
        const button = $("#cadastrarIsca");
        const data = {
            id: $("#id_agendamento").val(),
            dataAgendamento: $("#dataAgendamento").val(),
            horaAgendamento: $("#horaAgendamento").val(),
            tipo: $("#tipo").val(),
            serial: $("#serial").val(),
            cliente: $("#cliente").val(),
            instalador: $("#instalador").val(),
            rua: $("#rua").val(),
            numero: $("#numero").val(),
            bairro: $("#bairro").val(),
            cidade: $("#cidade").val(),
            uf: $("#uf").val(),
            obs: $("#obs").val(),
        };

        const data_agendamento_anterior = $("#edit_data_anterior_agendamento").val();

        if (validarParametros(data)) {

            button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando');
            $.ajax({
                url: '<?php echo site_url('iscas/isca/ajaxEditarAgendamento') ?>',
                dataType: "json",
                type: "POST",
                data: data,
                success: function(callback) {
                    alert(callback.msg);
                    if (callback.status) {
                        const agend = callback.agend;
                        button.attr('disabled', false).html('Salvar');
                        $("#modalCadastroAgendamento").modal("hide");

                        const data_agendamento_atual = agend.data_agendamento.split(' ')[0];

                        // Atualiza a contagem de agendamentos por data se a data de agendamento anterior for diferente da atual
                        if (data_agendamento_atual != data_agendamento_anterior) {
                            // subtrai a data anterior
                            if (num_agendamentos_por_dia[data_agendamento_anterior] > 0) {
                                num_agendamentos_por_dia[data_agendamento_anterior] -= 1;
                            }
                            // soma a data atual
                            if (num_agendamentos_por_dia[data_agendamento_atual] != undefined) {
                                num_agendamentos_por_dia[data_agendamento_atual] += 1;
                            } else {
                                num_agendamentos_por_dia[data_agendamento_atual] = 1;
                            }
                        }
                        renderCalendar();

                        // Atualiza Datatable
                        tabela_agendamentos.rows().every(function() {
                            const row = this;
                            const linha = row.data();

                            if (linha != undefined) {
                                const id_row = linha[0];
                                if (id_row == agend.id) { //verifica o id do agendamento
                                    if (linha[2] != returnData(agend.data_agendamento.split(" ")[0])) { // verifica se a nova data é diferente da anterior

                                        row.remove().draw();; //remove a linha
                                    } else { // atualiza a linha
                                        row.data([
                                            agend.id,
                                            agend.serial,
                                            returnData(agend.data_agendamento.split(" ")[0]),
                                            agend.data_agendamento.split(" ")[1],
                                            returnTipo(agend.tipo),
                                            returnSituacao(agend.situacao, agend.status, agend.id),
                                            returnBtnAcoes(agend.status, agend.id)
                                        ]).draw();

                                    }

                                }
                            }
                        });
                    }


                },
                error: function(error) {
                    button.attr('disabled', false).html('Salvar');
                }
            });
        }
    }

    function limparModal() {
        $("#id_agendamento").val("");
        $("#edit_data_anterior_agendamento").val("");
        $("#dataAgendamento").val("");
        $("#horaAgendamento").val("");
        $("#tipo").val("");
        $("#serial").val("").change();
        $("#cliente").val("").change();
        $("#instalador").val("").change();
        $("#rua").val("");
        $("#numero").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
        $("#obs").val("");

    }
    // Valida parâmetros da requisição
    function validarParametros(data) {

        if (validarDataHora(data.dataAgendamento, data.horaAgendamento) == false) {
            return false;
        } else if (data.tipo == "") {
            alert("Selecione o tipo.");
            return false;
        } else if (data.serial == null) {
            alert("Selecione o serial.");
            return false;
        } else if (data.cliente == null) {
            alert("Selecione o cliente.");
            return false;
        } else if (data.instalador == null) {
            alert("Selecione o instalador.");
            return false;
        } else if (data.rua == "") {
            alert("Informe a rua.");
            return false;
        } else if (data.numero == "") {
            alert("Informe o número.");
            return false;
        } else if (data.bairro == "") {
            alert("Informe o bairro.");
            return false;
        } else if (data.cidade == "") {
            alert("Informe a cidade.");
            return false;
        } else if (data.uf == "") {
            alert("Selecione o Estado.");
            return false;
        } else {
            return true;
        }
    }

    /**
     * Valida campos de data e hora
     */
    function validarDataHora(data, hora) {
        if (data == "") {
            alert("Informe a data do agendamento");
            return false;
        } else if (hora == "") {
            alert("Informe a hora do agendamento");
            return false;
        } else {
            const dataAgend = new Date(data + " " + hora);
            const now = new Date();

            if (dataAgend === "Invalid Date") {
                alert("Informe uma data de gendamento válida");
                return false;
            } else if (dataAgend < now) {
                alert("Informe uma data de agendamento posterior a data atual");
                return false;
            } else if (dataAgend > now.setFullYear(now.getFullYear() + 1)) {
                alert("Informe uma data de agendamento com, no máximo, um ano da data atual");
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    function returnTipo(tipo) {
        switch (tipo) {
            case 'instalacao':
                return "Instalação"
                break;
            case 'manutencao':
                return "Manutenção"
                break;
            case 'transferencia':
                return "Transferência"
                break;
            case 'retirada':
                return "Retirada"
                break;
            default:
                return ""
                break;
        }
    }
    // 
    function returnSituacao(situacao, status_confirmacao, id_agendamento) {
        let html = `<div id="divStatusAgendamento${id_agendamento}">`;

        // Caso o agendamento esteja confirmado retorna apenas o span
        if (status_confirmacao == 1) {
            switch (situacao) {
                case 'em_aberto':
                    html += `<span>Em Aberto</span>`;
                    break;
                case 'executado':
                    html += `<span>Executado</span>`;
                    break;
                case 'cancelado':
                    html += `<span>Cancelado</span>`;
                    break;
                case 'visita_frustrada':
                    html += `<span>Visita Frustrada</span>`;
                    break;
                case 'com_pendencias':
                    html += `<span>Com Pendências<span>`;
                    break;
                default:
                    break;
            }
        } else {
            switch (situacao) {
                case 'em_aberto':
                    html += `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto" selected>Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'executado':

                    html += `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado" selected>Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'cancelado':
                    html += `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado" selected>Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'visita_frustrada':
                    html += `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada selected">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'com_pendencias':
                    html += `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias" selected>Com Pendências</option>
                                </select>`;
                    break;
                default:
                    break;
            }
        }

        html += '</div>';
        return html;
    }

    function returnData(data) {
        const arrayData = data.split(" ")[0];
        const dia = arrayData.split("-")[2];
        const mes = arrayData.split("-")[1];
        const ano = arrayData.split("-")[0];
        return `${dia}/${mes}/${ano}`;
    }


    async function visualizarAgendamento(id) {

        document.getElementById('loading-indicator').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';

        const button = $("#btnVisualizarAgend" + id);

        limparModal();
        await $.ajax({
            url: '<?php echo site_url('iscas/isca/ajaxGetAgendamento') ?>',
            dataType: "json",
            type: "GET",
            data: {
                id: id
            },
            beforeSend: function () {
                $("#showData").val(returnData(""));
                $("#showHora").val("");
                $("#showTipo").val("");
                $("#showSerial").val("");
                $("#showCliente").val("");
                $("#showInstalador").val("");
                $("#showRua").val("");
                $("#showNumero").val("");
                $("#showBairro").val("");
                $("#showCidade").val("");
                $("#showUF").val("");
                $("#showObs").val("");
                $("#showSituacao").val("");
                $("#showStatus").val("");
                $("#showDataFim").val("");
            },
            success: function(callback) {
                const agend = (callback);

                if (agend.status == true) {
                    let situacao;
                    switch (agend.dados.situacao) {
                        case 'em_aberto':
                            situacao = "Em Aberto";
                            break;
                        case 'executado':
                            situacao = "Executado";
                            break;
                        case 'cancelado':
                            situacao = "Cancelado";
                            break;
                        case 'visita_frustrada':
                            situacao = "Visita Frustrada";
                            break;
                        case 'com_pendencias':
                            situacao = "Com Pendências";
                            break;
                        default:
                            situacao = "";
                            break;
                    }

                    $("#showData").val(returnData(agend.dados.data_agendamento.split(" ")[0]));
                    $("#showHora").val(agend.dados.data_agendamento.split(" ")[1]);
                    $("#showTipo").val(returnTipo(agend.dados.tipo));
                    $("#showSerial").val(agend.dados.serial);
                    $("#showCliente").val(agend.dados.nome_cliente);
                    $("#showInstalador").val(agend.dados.nome_instalador);
                    $("#showRua").val(agend.dados.rua);
                    $("#showNumero").val(agend.dados.numero);
                    $("#showBairro").val(agend.dados.bairro);
                    $("#showCidade").val(agend.dados.cidade);
                    $("#showUF").val(agend.dados.uf);
                    $("#showObs").val(agend.dados.obs);
                    $("#showSituacao").val(situacao);
                    $("#showStatus").val(agend.dados.status == 1 ? "Finalizado" : "Aguardando Confirmação");
                    $("#showDataFim").val(agend.dados.data_resultado != null ? returnData(agend.dados.data_resultado.split(" ")[0]) : '');
                    $("#modalVisualizarAgendamento").modal('show');

                } else {
                    alert(agend.msg);
                }
                // button.attr('disabled', false).html('<i class="fa fa-eye" aria-hidden="true"></i>');
            },
            error: function(error) {
                //   button.attr('disabled', false).html('<i class="fa fa-eye" aria-hidden="true"></i>');
                alert("Erro ao carregar agendamento.")
            }
        });
        document.getElementById('loading-indicator').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';

    }
    /**
     * Abre modal com informações para editar o agendamento
     */
    async function showModalEditarAgendamento(id, status) {
        if (status == 1) {
            alert('O agendamento já foi confirmado anteriormente');
        } else {
            document.getElementById('loading-indicator').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';

            const button = $(`#btnEditarAgend${id}`);
            limparModal();
            await $.ajax({
                url: '<?php echo site_url('iscas/isca/ajaxGetAgendamento') ?>',
                dataType: "json",
                type: "GET",
                data: {
                    id: id
                },
                success: function(callback) {
                    const agend = (callback);

                    if (agend.status == true) {
                        $("#cadastroIscaModalLabel").html("Editar Agendamento");
                        $("#id_agendamento").val(agend.dados.id);
                        $("#edit_data_anterior_agendamento").val(agend.dados.data_agendamento.split(" ")[0]);
                        $("#dataAgendamento").val(agend.dados.data_agendamento.split(" ")[0]);
                        $("#horaAgendamento").val(agend.dados.data_agendamento.split(" ")[1]);
                        $("#tipo").val(agend.dados.tipo);
                        $("#serial")
                            .prop("disabled", false)
                            .append($("<option selected></option>")
                                .val(agend.dados.isca_id)
                                .text(agend.dados.serial))
                            .trigger('change');

                        $("#cliente")
                            .append($("<option selected></option>")
                                .val(agend.dados.cliente_id)
                                .text(agend.dados.nome_cliente))
                            .trigger('change');
                        $("#instalador")
                            .append($("<option selected></option>")
                                .val(agend.dados.instalador_id)
                                .text(agend.dados.nome_instalador))
                            .trigger('change');

                        $("#rua").val(agend.dados.rua);
                        $("#numero").val(agend.dados.numero);
                        $("#bairro").val(agend.dados.bairro);
                        $("#cidade").val(agend.dados.cidade);
                        $("#uf").val(agend.dados.uf);
                        $("#obs").val(agend.dados.obs);

                        $("#modalCadastroAgendamento").modal("show");
                    } else {
                        alert(agend.msg);
                    }
                },
                error: function(error) {
                    alert("Erro ao carregar agendamento.")
                }
            });
        }
        document.getElementById('loading-indicator').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
    /**
     * Confirma o agendamento
     */
    function confirmarAgend(id, status) {
        if (status == 1) {
            alert('O agendamento já foi confirmado anteriormente');
        } else {
            document.getElementById('loading-indicator').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';

            const statusAgendamento = $("#select_status_agendamento" + id).val();

            if (statusAgendamento === 'em_aberto') {
                alert('Informe a situação do agendamento.');
                return;
            } else {
                let confirma = confirm("Você deseja finalizar o agendamento?");
                if (confirma) {
                    const button = $("#btnConfirmarAgend" + id);
                    const data = {
                        id: id,
                        situacao: statusAgendamento,
                    }
                    $.ajax({
                        url: '<?= site_url('iscas/isca/ajax_confirmar_agendamento'); ?>',
                        type: 'POST',
                        data: data,
                        success: function(callback) {
                            resposta = JSON.parse(callback);
                            agendamento = resposta.agendamento;
                            if (resposta.status == true) {
                                alert(returnAlertConfirmSituacao(resposta.agendamento.situacao));

                                $(`#btnsAdministrar${id}`).html(returnBtnAcoes(1, id));
                                $("#divStatusAgendamento" + id).html(returnSituacao(resposta.agendamento.situacao, 1, id), );
                                // Subtrai o agendamento do dic com os agendamentos diários para ser renderizado no calendário
                                const data_agend = agendamento.data_agendamento.split(' ')[0];
                                if (num_agendamentos_por_dia[data_agend] != undefined && num_agendamentos_por_dia[data_agend] > 0) {
                                    num_agendamentos_por_dia[data_agend] -= 1;
                                }
                                renderCalendar();
                            }
                            button.attr('disabled', false).html('<i class="fa fa-check" aria-hidden="true"></i>');

                        },
                        error: function(error) {
                            console.log(error)
                            button.attr('disabled', false).html('<i class="fa fa-check" aria-hidden="true"></i>');
                        }
                    });
                }
            }
        }
        document.getElementById('loading-indicator').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    function returnAlertConfirmSituacao(situacao) {

        if (situacao == 'executado') {
            return 'Executado com Sucesso!';
        } else if (situacao == 'cancelado') {
            return 'Cancelado com Sucesso!';
        } else {
            return 'Situação alterada com Sucesso!'
        }

    }
</script>

<script type="text/javascript" charset="utf8" src="<?php echo base_url('media/calendario_agendamento/calendario_agendamento.js'); ?>"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url('media/calendario_agendamento/exportacoes.js'); ?>"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>


<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">