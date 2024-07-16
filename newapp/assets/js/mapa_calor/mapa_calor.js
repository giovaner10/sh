var localeText = AG_GRID_LOCALE_PT_BR;
var ctxBar = document.getElementById("myChartBar");
var ctxLine = document.getElementById("myChartLine");
var ctxPie = document.getElementById("myChartPie");
$(document).ready(function() {
    listarEmailsUsuariosAtivos();
    $('#menu-nuvem-palavras').on('click', function() {
        if (!$(this).hasClass('selected')) {
            $(this).addClass('selected');
            $('#menu-relatorio').removeClass('selected');
            $('#menu-dashboard').removeClass('selected');
            $('.card-nuvem-palavras').show();
            $('.card-relatorio').hide();
            $('.card-dashboard').hide();
            getMapaCalorData(function(error, data) {
                if (!error) {
                    atualizarNuvemPalavras(data);
                } else {
                    stopWordCloud();
                }
            });
        }
    });

    $('#menu-relatorio').on('click', function() {
        if (!$(this).hasClass('selected')) {
            $(this).addClass('selected');
            $('#menu-nuvem-palavras').removeClass('selected');
            $('#menu-dashboard').removeClass('selected');
            $('.card-nuvem-palavras').hide();
            $('.card-relatorio').show();
            $('.card-dashboard').hide();
            atualizarGraficosAgGrid([]);
            getMapaCalorData(function(error, data) {
                if (!error) {
                    atualizarGraficosAgGrid(data);
                } else {
                    stopAgGRID();
                }
            });
        }
    });

    $('#menu-dashboard').on('click', function() {
        if (!$(this).hasClass('selected')) {
            $(this).addClass('selected');
            $('#menu-nuvem-palavras').removeClass('selected');
            $('#menu-relatorio').removeClass('selected');
            $('.card-nuvem-palavras').hide();
            $('.card-relatorio').hide();
            $('.card-dashboard').show();
            getMapaCalorData(function(error, data) {
                if (!error) {
                    montarDashboards(data);
                } else {
                    var barChart = Chart.getChart(ctxBar);
                    var lineChart = Chart.getChart(ctxLine);
                    var pieChart = Chart.getChart(ctxPie);

                    if (barChart) {
                        barChart.destroy();
                    }
                    if (lineChart) {
                        lineChart.destroy();
                    }
                    if (pieChart) {
                        pieChart.destroy();
                    }
                }
            });
        }
    });

    async function listarEmailsUsuariosAtivos() {
        $('#loginInput').empty();
        $('#loginInput').append('<option value="" disabled selected>Buscando emails...</option>');
        try {
            
            const listarEmails = await $.ajax({
                url: RouterControllerRelatorios + '/listaUsuariosAtivosMapa',
                dataType: 'json',
                delay: 1000,
                type: 'GET',
                data: function (params) {
                    return {
                        q: params.term
                    };
                }
            });
    
            $('#loginInput').select2({
                data: listarEmails.results,
                placeholder: "Selecione o email",
                allowClear: true,
                language: "pt-BR",
                width: '100%',
            });
    
            $('#loginInput').on('select2:select', function (e) {
                var data = e.params.data;
            });
            
            $('#loginInput').val(null).trigger('change');
            
        } catch (error) {
            throw new Error('Erro ao listar emails. Tente novamente.');
        }
    }

    function getMapaCalorData(callback, options) {
        var route;
        var nuvemPalavras = $('#menu-nuvem-palavras').hasClass('selected') ? true : false;
        var relatorio = $('#menu-relatorio').hasClass('selected') ? true : false;
        var dashboard = $('#menu-dashboard').hasClass('selected') ? true : false;

        if (options && (options.user || options.dataInicial || options.dataFinal || options.mes || options.ano || options.periodo)) {
            route = RouterControllerMapaCalor + '/listarMapaCalorByUserOrData';
            showLoadingPesquisarButton();
        } else {
            route = RouterControllerMapaCalor + '/listarMapaCalor';
        }


        $.ajax({
            cache: false,
            url: route,
            type: 'POST',
            data: options || {},
            dataType: 'json',
            beforeSend: function() {
                if (nuvemPalavras){
                    $("#loadingNuvemPalavras").show();
                    $('#emptyNuvem').hide();
                    stopWordCloud();
                }else if (relatorio){
                    if (AgGridRelatorios) {
                        AgGridRelatorios.gridOptions.api.showLoadingOverlay();
                    }
                }else if (dashboard){
                    $('#emptyDashboard').hide();
                    $('#dashboard').hide();
                    $("#loadingDashboard").show();
                }
            },
            success: function(data) {
                resetPesquisarButton();

                if (data.length === 0) {
                    if (nuvemPalavras){
                        $('#emptyNuvem').show();
                    }else if (relatorio){
                        atualizarGraficosAgGrid([]);
                        AgGridRelatorios.gridOptions.api.showNoRowsOverlay();

                    }else if (dashboard){
                        $('#emptyDashboard').show();
                    }
                } else {
                    if (typeof callback === "function") callback(null, data);

                    if (dashboard){
                        $('#dashboard').show();
                    }else if (nuvemPalavras){
                        $('#nuvem-palavras').show();
                    }else{
                        if (AgGridRelatorios) {
                            AgGridRelatorios.gridOptions.api.hideOverlay();
                        }
                    }
                }
            },
            error: function(error) {
                resetPesquisarButton();
                stopWordCloud();
                resetLimparButton();
                if (nuvemPalavras){
                    $("#loadingNuvemPalavras").hide();
                    $('#emptyNuvem').show();
                }else if (relatorio){
                    atualizarGraficosAgGrid([]);
                    AgGridRelatorios.gridOptions.api.showNoRowsOverlay();
                }else if (dashboard){
                    $("#loadingDashboard").hide();
                    $('#emptyDashboard').show();
                }
            },
            complete: function() {
                resetLimparButton();
                if (nuvemPalavras){
                    $("#loadingNuvemPalavras").hide();
                }else if (dashboard){
                    $("#loadingDashboard").hide();
                }
            }
        });
    }

    function atualizarNuvemPalavras(dados) {
        var wordCloudList = dados.map(entrada => [entrada.urlAcessada, entrada.count, entrada.link]);

        var MAX_SIZE = 80;
        var MIN_SIZE = 20;

        var canvas = document.getElementById('wordCloudCanvas');
        
        var settings = {
            list: wordCloudList,
            gridSize: 18,
            weightFactor: function(size) {
                var scalingFactor = Math.pow(size, 0.6) * 3;
                return Math.max(MIN_SIZE, Math.min(scalingFactor, MAX_SIZE));
            },
            fontFamily: 'Montserrat, sans-serif',
            fontWeight: 'bold',
            color: function() {
                var colors = [
                    '#2c3e50', '#e67e22', '#3498db', '#e74c3c', '#9b59b6',
                    '#f39c12', '#27ae60', '#d35400', '#8e44ad', '#c0392b',
                    '#1abc9c', '#2ecc71', '#f1c40f'
                ];
                return colors[Math.floor(Math.random() * colors.length)];
            },
            backgroundColor: '#FFFFFF',
            rotateRatio: 0.5,
            rotationSteps: 2,
            shuffle: true,
            hover: function(item, dimension, event) {
                if (item) {
                    $('#wordCloudCanvas').attr('title', 'Acessos: ' + item[1])
                }
            },
        };

        WordCloud(canvas, settings);
    }
    var groupedBar;
    var groupedLine;
    var groupedPie;
    var AgGridRelatorios;

    function atualizarGraficosAgGrid(dados) {
        stopAgGRID();
        const mappedData = dados.map(entrada => ({
            UrlAcessada: entrada.urlAcessada || '-',
            QuantidadeDeAcessos: entrada.count || '-',
            Link: entrada.link || '-'
        }));

        const gridOptions = {
            columnDefs: [{
                    headerName: 'Url Acessada',
                    field: 'UrlAcessada',
                    chartDataType: 'category',
                    width: 300
                },
                {
                    headerName: 'Quantidade de Acessos',
                    field: 'QuantidadeDeAcessos',
                    chartDataType: 'series',
                    width: 300
                },
                {
                    headerName: 'Link',
                    field: 'Link',
                    chartDataType: 'series',
                    width: 500
                }
            ],
            defaultColDef: {
                editable: false,
                sortable: true,
                minWidth: 100,
                minHeight: 100,
                filter: true,
                resizable: true,
            },
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            domLayout: 'autoHeight',
            localeText: localeText,
            pagination: true,
            paginationPageSize: 10,
        };

        gridOptions.quickFilterText = '';
        document.querySelector('#search-input-relatorio').addEventListener('input', function() {
            var searchInput = document.querySelector('#search-input-relatorio');
            gridOptions.api.setQuickFilter(searchInput.value);
        });

        document.querySelector('#select-quantidade-por-pagina-relatorio').addEventListener('change', function() {
            var selectedValue = document.querySelector('#select-quantidade-por-pagina-relatorio').value;
            gridOptions.api.paginationSetPageSize(Number(selectedValue));
        });

        var gridDiv = document.querySelector('#myGrid');
        AgGridRelatorios = new agGrid.Grid(gridDiv, gridOptions);

        gridOptions.api.setRowData(mappedData);
        preencherExportacoes(gridOptions);
    }


    $('#BtnLimpar').on('click', function() {
        showLoadingLimparButton();
        $('#dataInicial').val('');
        $('#dataFinal').val('');
        $('#mesInput').val('');
        $('#anoInput').val('');
        $('#loginInput').val('').trigger('change');
        $('#periodoInput').val('7days');

        if ($('#menu-nuvem-palavras').hasClass('selected')) {
            getMapaCalorData(function(error, data) {
                if (!error) {
                    atualizarNuvemPalavras(data);
                } else {
                    stopWordCloud();
                }
            });
        }else if ($('#menu-relatorio').hasClass('selected')){
            getMapaCalorData(function(error, data) {
                if (!error) {
                    atualizarGraficosAgGrid(data);
                } else {
                    stopAgGRID();
                }
            });
        }else{
            getMapaCalorData(function(error, data) {
                if (!error) {
                    montarDashboards(data);
                } else {
                    var barChart = Chart.getChart(ctxBar);
                    var lineChart = Chart.getChart(ctxLine);
                    var pieChart = Chart.getChart(ctxPie);

                    if (barChart) {
                        barChart.destroy();
                    }
                    if (lineChart) {
                        lineChart.destroy();
                    }
                    if (pieChart) {
                        pieChart.destroy();
                    }
                }
            });
        }
    });

    $('#formBusca').on('submit', function(event) {
        event.preventDefault();
        showLoadingPesquisarButton();
        $('#emptyMessage').hide();
        var tipoData = $('#tipoData').val();
        var searchOptions = {
            user: null,
            dataInicial: null,
            dataFinal: null,
            mes: null,
            ano: null,
            periodo: null
        };

        if (tipoData == 'dateRange'
            && ($('#dataInicial').val() > $('#dataFinal').val())) {
            alert("Data inicial não pode ser maior que a data final.");
            resetPesquisarButton();
            return;
        }

        switch (tipoData) {
            case 'login':
                searchOptions.user = $('#loginInput').val();
                break;
            case 'dateRange':
                searchOptions.dataInicial = $('#dataInicial').val();
                searchOptions.dataFinal = $('#dataFinal').val();
                break;
            case 'mes':
                searchOptions.mes = $('#mesInput').val();
                break;
            case 'ano':
                searchOptions.ano = $('#anoInput').val();
                break;
            case 'periodo':
                searchOptions.periodo = $('#periodoInput').val();
                break;
        }

        if ($('#menu-nuvem-palavras').hasClass('selected')) {
            getMapaCalorData(function(error, data) {
                if (!error) {
                    atualizarNuvemPalavras(data);
                } else {
                    stopWordCloud();
                }
            }, searchOptions);
        }else if ($('#menu-relatorio').hasClass('selected')){
            getMapaCalorData(function(error, data) {
                if (!error) {
                    atualizarGraficosAgGrid(data);
                } else {
                    stopAgGRID();
                }
            }, searchOptions);
        }else{
            getMapaCalorData(function(error, data) {
                if (!error) {
                    montarDashboards(data);
                } else {
                    var barChart = Chart.getChart(ctxBar);
                    var lineChart = Chart.getChart(ctxLine);
                    var pieChart = Chart.getChart(ctxPie);

                    if (barChart) {
                        barChart.destroy();
                    }
                    if (lineChart) {
                        lineChart.destroy();
                    }
                    if (pieChart) {
                        pieChart.destroy();
                    }
                }
            }, searchOptions);
        }
    });

    $('#tipoData').change(function() {
        switch ($(this).val()) {
            case 'login':
                $('#loginContainer').show();
                $('#loginInput').attr('required', true);
                $('#dateContainer1, #dateContainer2, #mesContainer, #anoContainer, #periodoContainer').hide();
                $('#dataInicial').val('');
                $('#dataFinal').val('');
                $('#mesInput').val('');
                $('#anoInput').val('');
                $('#periodoInput').val('7days');
                $('#dataInicial').attr('required', false);
                $('#dataFinal').attr('required', false);
                $('#mesInput').attr('required', false);
                $('#anoInput').attr('required', false);
                $('#periodoInput').attr('required', false);
                break;
            case 'mes':
                $('#mesContainer').show();
                $('#mesInput').attr('required', true);
                $('#dateContainer1, #dateContainer2, #loginContainer, #anoContainer, #periodoContainer').hide();
                $('#dataInicial').val('');
                $('#dataFinal').val('');
                $('#loginInput').val('');
                $('#anoInput').val('');
                $('#periodoInput').val('7days');
                $('#dataInicial').attr('required', false);
                $('#dataFinal').attr('required', false);
                $('#loginInput').attr('required', false);
                $('#anoInput').attr('required', false);
                $('#periodoInput').attr('required', false);
                break;
            case 'ano':
                $('#anoContainer').show();
                $('#anoInput').attr('required', true);
                $('#dateContainer1, #dateContainer2, #loginContainer, #mesContainer, #periodoContainer').hide();
                $('#dataInicial').val('');
                $('#dataFinal').val('');
                $('#loginInput').val('');
                $('#mesInput').val('');
                $('#periodoInput').val('7days');
                $('#dataInicial').attr('required', false);
                $('#dataFinal').attr('required', false);
                $('#loginInput').attr('required', false);
                $('#mesInput').attr('required', false);
                $('#periodoInput').attr('required', false);
                break;
            case 'periodo':
                $('#periodoContainer').show();
                $('#periodoInput').attr('required', true);
                $('#dateContainer1, #dateContainer2, #loginContainer, #mesContainer, #anoContainer').hide();
                $('#dataInicial').val('');
                $('#dataFinal').val('');
                $('#loginInput').val('');
                $('#mesInput').val('');
                $('#anoInput').val('');
                $('#dataInicial').attr('required', false);
                $('#dataFinal').attr('required', false);
                $('#loginInput').attr('required', false);
                $('#mesInput').attr('required', false);
                $('#anoInput').attr('required', false);
                break;
            case 'dateRange':
                $('#dateContainer1, #dateContainer2').show();
                $('#dataInicial').attr('required', true);
                $('#dataFinal').attr('required', true);
                $('#loginContainer, #mesContainer, #anoContainer, #periodoContainer').hide();
                $('#loginInput').val('');
                $('#mesInput').val('');
                $('#anoInput').val('');
                $('#periodoInput').val('7days');
                $('#loginInput').attr('required', false);
                $('#mesInput').attr('required', false);
                $('#anoInput').attr('required', false);
                $('#periodoInput').attr('required', false);
                break;
        }
    });

    function showLoadingPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...').attr('disabled', true);
    }

    function resetPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
    }

    function showLoadingLimparButton() {
        $('#BtnLimpar').html('<i class="fa fa-spinner fa-spin"></i> Limpando...').attr('disabled', true);
    }

    function resetLimparButton() {
        $('#BtnLimpar').html('<i class="fa fa-leaf"></i> Limpar').attr('disabled', false);
    }

    function stopWordCloud() {
        if (typeof WordCloud !== 'undefined') {
            WordCloud.stop(document.getElementById('wordCloudCanvas'));
            var canvas = document.getElementById('wordCloudCanvas');
            var context = canvas.getContext('2d');
            
            context.clearRect(0, 0, canvas.width, canvas.height);
            $('#nuvem-palavras').hide();
        }
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#myGrid');
        
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }

        var wrapper = document.querySelector('.wrapper');
        if (wrapper) {
            wrapper.innerHTML = '<div id="myGrid" class="ag-theme-alpine my-grid"></div>';
        }

    }

    getMapaCalorData(function(error, data) {
        if (!error) {
            atualizarNuvemPalavras(data);
        } else {
            stopWordCloud();
        }
    });

    let menuAberto = false;
    function expandirGrid(){
        menuAberto = !menuAberto;

        let buttonHide = `${BaseURL}/assets/images/icon-filter-hide.svg`;
        let buttonShow = `${BaseURL}/assets/images/icon-filter-show.svg`;

        if (menuAberto) {
            $('.img-expandir').attr("src", buttonShow);
            $('#filtroBusca').hide();
            $('.menu-interno').hide();
            $('#conteudo').removeClass('col-md-9');
            $('#conteudo').addClass('col-md-12');
        } else {
            $('.img-expandir').attr("src", buttonHide);
            $('#filtroBusca').show();
            $('.menu-interno').show();
            $('#conteudo').removeClass('col-md-12');
            $('#conteudo').addClass('col-md-9');
        }
    }

    $('.btn-expandir').on('click', function(e) {
        e.preventDefault();
        expandirGrid();
    });

    function montarDashboards(dados){
        var barChart = Chart.getChart(ctxBar);
        var lineChart = Chart.getChart(ctxLine);
        var pieChart = Chart.getChart(ctxPie);

        if (barChart) {
            barChart.destroy();
        }
        if (lineChart) {
            lineChart.destroy();
        }
        if (pieChart) {
            pieChart.destroy();
        }
        var labels = dados.map(function(entrada) {
            return entrada.urlAcessada;
        });

        var data = dados.map(function(entrada) {
            return entrada.count;
        });

        barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Contagens por URL Acessada',
                    data: data,
                    backgroundColor: "rgba(75, 192, 192, 1)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Contagens por URL Acessada',
                    data: data,
                    backgroundColor: "#28A745",
                    borderColor: "#28A745",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Contagens por URL Acessada',
                    data: data,
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(54, 162, 235, 0.2)",
                        "rgba(255, 206, 86, 0.2)",
                        "rgba(75, 192, 192, 0.2)",
                        "rgba(153, 102, 255, 0.2)",
                        "rgba(255, 159, 64, 0.2)"
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)",
                        "rgba(255, 159, 64, 1)"
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right',
                    } 
                },
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('opcoes_exportacao_mapa_calor');

    document.getElementById('dropdownMenuButtonMapaDeCalor').addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && event.target.id !== 'dropdownMenuButtonMapaDeCalor') {
            dropdown.style.display = 'none';
        }
    });
});

function preencherExportacoes(gridOptions) {
    const formularioExportacoes = document.getElementById('opcoes_exportacao_mapa_calor');
    const opcoes = ['csv', 'excel', 'pdf'];

    let buttonCSV = BaseURL + 'media/img/new_icons/csv.png';
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
        div.classList.add('opcoes_exportacao_mapa_calor');
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
            fileName = 'RelatórioMapaDeCalor.csv';
            gridOptions.api.exportDataAsCsv({
                fileName: fileName,
                columnKeys: ['UrlAcessada', 'QuantidadeDeAcessos', 'Link']
            });
            break;
        case 'excel':
            fileName = 'RelatórioMapaDeCalor.xlsx';
            gridOptions.api.exportDataAsExcel({
                fileName: fileName,
                columnKeys: ['UrlAcessada', 'QuantidadeDeAcessos', 'Link']
            });
            break;
        case 'pdf':
            let definicoesDocumento = getDocDefinition(
                printParams('A4'),
                gridOptions.api,
                gridOptions.columnApi,
                '',
                `Relatório Mapa de Calor\n ${new Date().toLocaleString('pt-br')}`
            );

            pdfMake.createPdf(definicoesDocumento).download('RelatórioMapaDeCalor.pdf');
            break;
    }
}

function printParams(pageSize) {
    return {
        PDF_HEADER_COLOR: "#ffffff",
        PDF_INNER_BORDER_COLOR: "#dde2eb",
        PDF_OUTER_BORDER_COLOR: "#babfc7",
        PDF_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
        PDF_HEADER_LOGO: BaseURL + 'media/img/new_icons/omnilink.png',
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

function mascararNumero(input) {
    let numeroSemMascara = input.value.replace(/\D/g, '');
    input.value = numeroSemMascara;
}