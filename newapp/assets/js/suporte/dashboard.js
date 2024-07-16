const intervalReferences = [];
const localeText = AG_GRID_LOCALE_PT_BR;
let Bars = {};


// Inicialização e Eventos
$(document).ready(function () {
    setupEventHandlers();
    getMetricas();
    let dadosGraficoTotal = [{ abertas: dados[0].abertas, fechadas: dados[0].fechadas }];
    montarGraficoBarrasTotal(dadosGraficoTotal, 'myDashBar1');
    
    let dadosGraficoSeriado =  [{manutencao: dados[0].manutencao, troca: dados[0].troca, retirada: dados[0].retirada, instalacao: dados[0].instalacao }];
    montarGraficoBarrasSeriado(dadosGraficoSeriado, 'myDashBar2')

})

function setupEventHandlers() {
    $(document).on('shown.bs.dropdown', '.dropdown-table', function() {
        var dropdownId = $(this).find('.dropdown-menu').attr('id');
        var buttonId = $(this).find('.btn-dropdown').attr('id');
        var tableId = "tableEquipamentosDevolver";
        abrirDropdown(dropdownId, buttonId, tableId);
    });

    $("#btnTecnicos").click(() => {
        $("#modalTecnicos").modal("show");
        $('#tab-dadosADevolver').trigger('click');
        $(this).blur();
    });

    $('#tab-dadosADevolver').click(function(){
        $('#bodyDadosDevolvidos').hide();
        $('#bodyDadosADevolver').show();
        atualizarAgGridADevolver()
       
    });

    $('#tab-dadosADevolvidos').click(function(){
        $('#bodyDadosDevolvidos').show();
        $('#bodyDadosADevolver').hide();
        atualizarAgGridADevolvidos()       
    });
}


function montarGraficoBarrasTotal(dados, chartId) {
    // Escondendo o elemento gráfico durante a construção
    $(`#${chartId}`).hide();
    let data = [];

    // Verificando se os dados são válidos
    if (dados && dados.length > 0) {
        const abertas = parseFloat(dados[0].abertas);
        const fechadas = parseFloat(dados[0].fechadas);

        if (!isNaN(abertas) && !isNaN(fechadas)) {
            data.push({
                categoria: "Ordens de Serviço",
                abertas: abertas,
                fechadas: fechadas,
            });
        }
    }

    // Configurações do gráfico
    const options = {
        container: document.getElementById(chartId),
        data: data,
        series: [
            {
                type: "bar",
                xKey: "categoria",
                yKey: '',
                yName: '',
                fill: "#264653",
                stroke: "#264653",
            },
            {
                type: "bar",
                xKey: "categoria",
                yKey: "fechadas",
                yName: "O.S Fechadas",
                fill: "#2a9d8f",
                stroke: "#2a9d8f",
            },
            {
                type: "bar",
                xKey: "categoria",
                yKey: "abertas",
                yName: "O.S em Aberto",
                fill: "#ff9400",
                stroke: "#ff9400",
            },
            {
                type: "bar",
                xKey: "categoria",
                yKey: '',
                yName: '',
                fill: "#009f1e",
                stroke: "#009f1e",
            }
        ],
        axes: [
            {
                type: "category",
                position: "bottom"
            },
            {
                type: "number",
                position: "left",
                min: 0,
                max: (dados[0].abertas + dados[0].fechadas)
            }
        ],
        overlays: {
            noData: {
                text: "Não há dados disponíveis."
            }
        },
        legend: {
            enabled: true,
            position: "bottom"
        },
        padding: { top: 20, right: 30, bottom: 20, left: 30 },
    };

    // Destruir o gráfico anterior, se houver
    if (window.Bars && window.Bars[chartId]) {
        window.Bars[chartId].destroy();
    }

    // Criar o novo gráfico
    window.Bars = window.Bars || {};
    window.Bars[chartId] = agCharts.AgCharts.create(options);

    // Mostrar o elemento gráfico após a construção
    $(`#${chartId}`).show();
    $('#loadingMessage1').hide();
}

function montarGraficoBarrasSeriado(dados, chartId) {
    $(`#${chartId}`).hide();
    let data = [];
    
    if (dados && dados.length > 0) {
        const manutencao = parseFloat(dados[0].manutencao);
        const troca = parseFloat(dados[0].troca);
        const retirada = parseFloat(dados[0].retirada);
        const instalacao = parseFloat(dados[0].instalacao);

        if (!isNaN(manutencao) && !isNaN(troca) && !isNaN(retirada) && !isNaN(instalacao)) {
            data.push({
                categoria: "Tipos de O.S",
                manutencao: manutencao,
                troca: troca,
                retirada: retirada,
                instalacao: instalacao
            });
        }
    }

    // Configurações do gráfico
    const options = {
        container: document.getElementById(chartId),
        data: data,
        series: [
            {
                type: "bar",
                xKey: "categoria",
                yKey: "manutencao",
                yName: "O.S - Manutenção",
                fill: "#4cb399",
                stroke: "#4cb399",
            },
            {
                type: "bar",
                xKey: "categoria",
                yKey: "troca",
                yName: "O.S - Troca",
                fill: "#0095cc",
                stroke: "#0095cc",
            },
            {
                type: "bar",
                xKey: "categoria",
                yKey: "retirada",
                yName: "O.S - Retirada",
                fill: "#e9c46a",
                stroke: "#e9c46a",
            },
            {
                type: "bar",
                xKey: "categoria",
                yKey: "instalacao",
                yName: "O.S - Instalação",
                fill: "#f4a261",
                stroke: "#f4a261",
            }
        ],
        axes: [
            {
                type: "category",
                position: "bottom"
            },
            {
                type: "number",
                position: "left",
                min: 0,
                max: (dados[0].abertas + dados[0].fechadas)
            }
        ],
        overlays: {
            noData: {
                text: "Não há dados disponíveis."
            }
        },
        legend: {
            enabled: true,
            position: "bottom",
        },
        padding: { top: 20, right: 30, bottom: 20, left: 30 },
    };

    // Destruir o gráfico anterior, se houver
    if (window.Bars && window.Bars[chartId]) {
        window.Bars[chartId].destroy();
    }

    // Criar o novo gráfico
    window.Bars = window.Bars || {};
    window.Bars[chartId] = agCharts.AgCharts.create(options);

    // Mostrar o elemento gráfico após a construção
    $(`#${chartId}`).show();
    $('#loadingMessage2').hide();
}

function getMetricas() {
          $("#os-fechadas").text(dados[0].fechadas ?? "--").toLocaleString("pt-BR");
          $("#os-abertas").text(dados[0].abertas ?? "--").toLocaleString("pt-BR");
          $("#os-manutencao").text(dados[0].manutencao ?? "--").toLocaleString("pt-BR");
          $("#os-troca").text(dados[0].troca ?? "--").toLocaleString("pt-BR");
          $("#os-retirada").text(dados[0].retirada ?? "--").toLocaleString("pt-BR");
          $("#os-instalacao").text(dados[0].instalacao ?? "--").toLocaleString("pt-BR");
          ajustarAltura();
}

let agGridDevolver;
function atualizarAgGridADevolver() {
    stopAgGRIDSADevolver();
    let dados = tecnicos;

    const gridOptions = {
        columnDefs: [
            {
                headerName: "ID",
                field: "id",
                width: 200,
                autoHeight: true,
                sortable: true,
            },
            {
                headerName: "Nome Completo",
                flex: 1,
                cellRenderer: function (params) {
                    const nomeCompleto = params.data.nome + " " + params.data.sobrenome;
                    return nomeCompleto;
                }
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options) {
                    var firstRandom = Math.random() * 10;
                    var secRandom = Math.random() * 10;
                    var thirdRandom = Math.random() * 10;

                    var varAleatorioIdBotao =
                        (firstRandom * secRandom).toFixed(0) +
                        "-" +
                        (thirdRandom * firstRandom).toFixed(0) +
                        "-" +
                        (secRandom * thirdRandom).toFixed(0);

                    let data = options.data;
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown dropdown-table">
                        <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:visualizarInfos('${data.id}')" style="cursor: pointer; color: black;">Visualizar</a>
                            </div>
                        </div>
                    </div>`;
                }
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
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
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 50,
        isExternalFilterPresent: function () {
            return true;
        },
        doesExternalFilterPass: function (node) {
            var searchValue = document.querySelector('#search-input1').value.toLowerCase();
            return node.data.nome.toLowerCase().includes(searchValue) ||
                node.data.sobrenome.toLowerCase().includes(searchValue);
        }
    };

    var gridDiv = document.querySelector('#tableEquipamentosDevolver');
    gridDiv.style.setProperty('height', '519px');
    agGridDevolver = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);

    gridOptions.quickFilterText = '';

    document.querySelector('#search-input1').addEventListener('input', applyExternalFilter);

    function applyExternalFilter() {
        gridOptions.api.onFilterChanged();
    }
}

let agGridDevolvidos;
function atualizarAgGridADevolvidos() {
    stopAgGRIDSDevolvidos();
    let dados = tecnicos;

    const gridOptions = {
        columnDefs: [
            {
                headerName: "ID",
                field: "id",
                width: 200,
                autoHeight: true,
                sortable: true,
            },
            {
                headerName: "Nome Completo",
                flex: 1,
                cellRenderer: function (params) {
                    const nomeCompleto = params.data.nome + " " + params.data.sobrenome;
                    return nomeCompleto;
                }
            },
            {
                headerName: 'Ação',
                pinned: 'right',
                width: 80,
                cellClass: "actions-button-cell",
                sortable: false,
                cellRenderer: function (options) {
                    var firstRandom = Math.random() * 10;
                    var secRandom = Math.random() * 10;
                    var thirdRandom = Math.random() * 10;

                    var varAleatorioIdBotao =
                        (firstRandom * secRandom).toFixed(0) +
                        "-" +
                        (thirdRandom * firstRandom).toFixed(0) +
                        "-" +
                        (secRandom * thirdRandom).toFixed(0);

                    let data = options.data;
                    let dropdownId = "dropdown-menu-" + data.id + varAleatorioIdBotao;
                    let buttonId = "dropdownMenuButton_" + data.id + varAleatorioIdBotao;

                    return `
                    <div class="dropdown dropdown-table">
                        <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                            <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:relative; margin-left: -10px;" aria-labelledby="${buttonId}">
                            <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                <a href="javascript:visualizarInfosDevolvidos('${data.id}')" style="cursor: pointer; color: black;">Visualizar</a>
                            </div>
                        </div>
                    </div>`;
                }
            },
        ],
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 80,
            minHeight: 100,
            filter: true,
            resizable: true,
            suppressMenu: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
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
                },
            ],
        },
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></span>',
        popupParent: document.body,
        domLayout: 'normal',
        pagination: true,
        localeText: localeText,
        cacheBlockSize: 50,
        paginationPageSize: 50,
        isExternalFilterPresent: function () {
            return true;
        },
        doesExternalFilterPass: function (node) {
            var searchValue = document.querySelector('#search-input2').value.toLowerCase();
            return node.data.nome.toLowerCase().includes(searchValue) ||
                node.data.sobrenome.toLowerCase().includes(searchValue);
        }
    };

    var gridDiv = document.querySelector('#tableEquipamentosDevolvidos');
    gridDiv.style.setProperty('height', '519px');
    agGridDevolvidos = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);

    gridOptions.quickFilterText = '';

    document.querySelector('#search-input2').addEventListener('input', applyExternalFilter);

    function applyExternalFilter() {
        gridOptions.api.onFilterChanged();
    }
}


function visualizarInfos(id){
    $("#titleEquipamentos").text("Equipamentos a devolver")
    $("#serialEquip").text("325232423HHGLHYFYYF")
    $("#visualizarEquipamentos").modal("show");    
}

function visualizarInfosDevolvidos(id){
    $("#titleEquipamentos").text("Equipamentos Devolvidos")
    $("#serialEquip").text("42423GGHFHFHF2342")
    $("#visualizarEquipamentos").modal("show");   

}
function stopAgGRIDSADevolver() {
    var gridDiv = document.querySelector('#tableEquipamentosDevolver');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperEquipamentosDevolver');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableEquipamentosDevolver" class="ag-theme-alpine my-grid-EqpDevolver"></div>';
    }
}
function stopAgGRIDSDevolvidos() {
    var gridDiv = document.querySelector('#tableEquipamentosDevolvidos');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperEquipamentosDevolvidos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="tableEquipamentosDevolvidos" class="ag-theme-alpine my-grid-EqpDevolvido"></div>';
    }
}

function abrirDropdown(dropdownId, buttonId, tableId) {
    var dropdown = $('#' + dropdownId);
    var posDropdown = dropdown.height() + 10;
 
    var posBordaTabela = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().bottom;
    var posBordaTabelaTop = $('#' + tableId + ' .ag-body-viewport').get(0).getBoundingClientRect().top;
    var posButton = $('#' + buttonId).get(0).getBoundingClientRect().bottom;
    var posButtonTop = $('#' + buttonId).get(0).getBoundingClientRect().top;

    if (posDropdown > (posBordaTabela - posButton)) {
        if (posDropdown < (posButtonTop - posBordaTabelaTop)){
            dropdown.css('top', `-${10}px`);
        } else {
            let diferenca = posDropdown - (posButtonTop - posBordaTabelaTop);
            dropdown.css('top', `-${(posDropdown - 150) - (diferenca) }px`);
        }
    }
}