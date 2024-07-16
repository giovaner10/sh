var now = new Date();
var arrayMes = new Array(12);
arrayMes[0] = "Janeiro";
arrayMes[1] = "Fevereiro";
arrayMes[2] = "Março";
arrayMes[3] = "Abril";
arrayMes[4] = "Maio";
arrayMes[5] = "Junho";
arrayMes[6] = "Julho";
arrayMes[7] = "Agosto";
arrayMes[8] = "Setembro";
arrayMes[9] = "Outubro";
arrayMes[10] = "Novembro";
arrayMes[11] = "Dezembro";

var alreadyLoaded = [false, false, false, false];

function getMesExtenso(mes) {
    return this.arrayMes[mes];
}

// Grafico Pizza
function pizza() {
    return new Promise((resolve) => {
        $.getJSON('get_data_dash', function (callback) {
            let dataReturn = callback.p == 0 && callback.r == 0 && callback.b == 0 && callback.o == 0 && callback.e == 0;
            const data = {
                labels: [
                    'Péssimo',
                    'Ruim',
                    'Bom',
                    'Ótimo',
                    'Excelente',
                ],
                datasets: [{
                    label: 'Resumo ultimos 30 dias',
                    data: dataReturn ? [1,1,1,1,1] : [
                        callback.p,
                        callback.r,
                        callback.b,
                        callback.o,
                        callback.e
                    ],
                    backgroundColor: [
                        'rgb(210, 40, 100)',
                        'rgb(140, 40, 100)',
                        'rgb(110, 40, 110)',
                        'rgb(100, 40, 140)',
                        'rgb(100, 40, 210)',
                    ],
                    hoverOffset: 4
                }]
            };

            new Chart(document.getElementById("chart_pizza"), {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                }
            });
            alreadyLoaded[0] = true;
            resolve();
        });
    });
}

// Grafico Barra
function barra() {
    return new Promise((resolve) => {
        $.getJSON('get_rating_mes', function (callback) {

            new Chart(document.getElementById("chart_bar"), {
                type: 'bar',
                data: {
                    labels: ['Positivas', 'Negativas'],
                    datasets: [{
                        label: getMesExtenso(now.getMonth()) + '/' + now.getFullYear(),
                        data: [callback.p, callback.n],
                        borderWidth: 1,
                        backgroundColor: [
                            'rgb(132, 99, 255)',
                            'rgb(255, 99, 132)'
                        ],
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

            alreadyLoaded[1] = true;
            resolve();
        });
    });
}

/* Tabela Ranking */

$.getJSON('get_new_tickets', function (data) {
    var dadosMapeados = data.map(function (item) {
        // Mapeia os dados conforme necessário
        return {
            cliente: item.cliente || '-',
            usuario: item.usuário || '-',
            status: item.status || '-',
            assunto: item.assunto || '-',
            last_id: item.last_id || '-',
            data: item.data || '-',
        };
    });
    if (data != null) {
        atualizarAgGridTickets(dadosMapeados);
    } else {
        atualizarAgGridTickets([]);
    }
    alreadyLoaded[2] = true;
})

// AgGrid de Tickets
var AgGridTickets;
var DadosAgGrid = [];

function atualizarAgGridTickets(dados) {
    return new Promise((resolve) => {
        stopAgGRIDTickets();
        const gridOptions = {
            columnDefs: [{
                headerName: 'Cliente',
                field: 'cliente',
                chartDataType: 'series',
            },
            {
                headerName: 'Usuário',
                field: 'usuario',
                chartDataType: 'series',
            },
            {
                headerName: 'Status',
                width: 180,
                suppressSizeToFit: true,
                cellRenderer: function (options) {
                    let data = options.data;
                    return `${data.status}`;
                },
            },
            {
                headerName: 'Assunto',
                field: 'assunto',
                chartDataType: 'date',
            },
            {
                headerName: 'Último ID',
                field: 'last_id',
                chartDataType: 'series',
            },
            {
                headerName: 'Data',
                field: 'data',
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
            paginationPageSizeSelector: false
        };

        var gridDiv = document.querySelector('#tableTickets');
        AgGridTickets = new agGrid.Grid(gridDiv, gridOptions);

        gridOptions.api.setRowData(dados);

        gridOptions.quickFilterText = '';

        resolve();
    });
}

function stopAgGRIDTickets() {
    var gridDiv = document.querySelector('#tableTickets');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperTickets');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableTickets" class="ag-theme-alpine my-grid-tickets"></div>';
    }
}

/* Tabela Ranking */
$.getJSON('get_ranking_user', function (data) {
    var dadosMapeados = data.map(function (item) {
        // Mapeia os dados conforme necessário
        return {
            id: item.id || '-',
            nome: item.nome || '-',
            media: item.media || '-',
            excelente: item.e || '-',
            otimo: item.o || '-',
            bom: item.b || '-',
            ruim: item.r || '-',
            pessimo: item.p || '-',
        };
    });
    if (data != null) {
        atualizarAgGridRanking(dadosMapeados);
    } else {
        atualizarAgGridRanking([]);
    }
    alreadyLoaded[3] = true;
})

// AgGrid de Licitacoes
var AgGridRanking;
var DadosAgGrid = [];

function atualizarAgGridRanking(dados) {
    return new Promise((resolve) => {
        stopAgGRIDRanking();
        const gridOptions = {
            columnDefs: [{
                headerName: 'ID',
                field: 'id',
                chartDataType: 'category',
            },
            {
                headerName: 'Nome',
                field: 'nome',
                chartDataType: 'series',
            },
            {
                headerName: 'Média',
                field: 'media',
                chartDataType: 'series',
            },
            {
                headerName: 'Excelente',
                field: 'excelente',
                chartDataType: 'date',
            },
            {
                headerName: 'Ótimo',
                field: 'otimo',
                chartDataType: 'series',
            },
            {
                headerName: 'Bom',
                field: 'bom',
                chartDataType: 'series',
            },
            {
                headerName: 'Ruim',
                field: 'ruim',
                chartDataType: 'series',
            },
            {
                headerName: 'Péssimo',
                field: 'pessimo',
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
            paginationPageSizeSelector: false
        };

        var gridDiv = document.querySelector('#tableRanking');
        AgGridRanking = new agGrid.Grid(gridDiv, gridOptions);

        gridOptions.api.setRowData(dados);

        gridOptions.quickFilterText = '';

        resolve();
    });
}

function stopAgGRIDRanking() {
    var gridDiv = document.querySelector('#tableRanking');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperRanking');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableRanking" class="ag-theme-alpine my-grid-ranking"></div>';
    }
}


$(document).ready(async function () {
    $("#loadingMessage").show();

    try {
        await Promise.all([pizza(), barra(), atualizarAgGridRanking(), atualizarAgGridTickets()]);
        pageReady();
    } catch (error) {
        console.error("Erro ao carregar dados:", error);
    }
});

function pageReady() {
    var exp = alreadyLoaded.every((item) => item == true);

    if (exp) {
        $("#loadingMessage").hide();
        $('#screen-content')[0].style.display = "";
        $('#screen-content').addClass("content-main container");
    }
}