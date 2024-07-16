var Bars = {};
var localeText = AG_GRID_LOCALE_PT_BR;
let idBarSelected;
let intervalReferences = [];

$(document).ready(function() {
    var dropdown = $('#opcoes_atualizar');

    $('#dropdownMenuButtonAtualizar').click(function () {
        dropdown.toggle();
        $(this).blur();
    });

    $(document).click(function (event) {
        if (!dropdown.is(event.target) && event.target.id !== 'dropdownMenuButtonAtualizar') {
            dropdown.hide();
        }
    });

    $('#stopInterval').on('click', function () {
        stopIntervals();
    })
    
    $('#10seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => reloadData(), 1000 * 10);
        intervalReferences.push(myInterval);
    });

    $('#60seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => reloadData(), 1000 * 60);
        intervalReferences.push(myInterval);
    });

    $('#180seg').on('click', function (){
        stopIntervals();
        let myInterval = setInterval(() => reloadData(), 1000 * 180);
        intervalReferences.push(myInterval);
    });

    function stopIntervals(){
        intervalReferences.forEach(interval => clearInterval(interval));
        intervalReferences = [];
    }

    function reloadData(){
        getMetricas();
        createCharts();
    }

    async function getMetricas () {

        $.ajax({
            cache: false,
            url: Router + '/getMetricas',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data) {
                    $('#veiculos-monitorados').text(data.contagemVeiculosEngajados !== null ? data.contagemVeiculosEngajados.toLocaleString('pt-BR') : '--');
                    $('#placas-lidas').text(data.contagemPlacasLidasMes !== null ? data.contagemPlacasLidasMes.toLocaleString('pt-BR') : '--');
                    $('#placas-alertas').text(data.contagemPlacasComAlertas !== null ? data.contagemPlacasComAlertas.toLocaleString('pt-BR') : '--');
                    $('#eventos-hot-list').text(data.contagemHotList !== null ? data.contagemHotList.toLocaleString('pt-BR') : '--');
                    $('#eventos-cold-list').text(data.contagemColdList !== null ? data.contagemColdList.toLocaleString('pt-BR') : '--');
                    ajustarAltura()
                }
            },
            error: function () {
                $('#veiculos-monitorados').text('--');
                $('#placas-lidas').text('--');
                $('#placas-alertas').text('--');
                $('#eventos-hot-list').text('--');
                $('#eventos-cold-list').text('--');
                showAlert('error', 'Não foi possível obter as métricas do dashboard. Recarregue a página.');
            }
        })
    }
    
    function getChartData(callback, tipoMatch, id) {
        var route;
        let name = 'Gráfico'
        let periodo = 0;
        let matchName = tipoMatch == '2' ? 'Cold List' : 'Hot List';

        if (id.length > 1) { 
            id = id[1]; 
        }

        if (id == '1') {
           name = `Gráfico ${matchName} - 5 dias`;
           periodo = 5;
        } else if (id == '2') {
            name = `Gráfico ${matchName} - 7 dias`;
            periodo = 7;
        } else if (id == '3') {
            name = `Gráfico ${matchName} - 15 dias`;
            periodo = 15;
        } else if (id == '4') {
            name = `Gráfico ${matchName} - 20 dias`;
            periodo = 20;
        } else if (id == '5') {
            name = `Gráfico ${matchName} - 30 dias`;
            periodo = 30;
        } else if (id == '6') {
            name = `Gráfico ${matchName} - 60 dias`;
            periodo = 60;
        }

        if (tipoMatch == 1) {
            route = Router + '/buscarAlertasHotListPorPeriodo';
        } else {
            route = Router + '/buscarAlertasColdListPorPeriodo';
        }
        
        $('#loadingMessage').show();


        $.ajax({
            cache: false,
            url: route,
            data: {
                periodo: periodo
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if ('resultado' in data && 'message' in data.resultado[0]) {
                    if (typeof callback === "function") callback(null, []);
                } else {
                    if (typeof callback === "function") callback(null, data.resultado);
                }
            },
            error: function(error) {
                if (typeof callback === "function") callback(true, []);
                //showAlert('error', 'Não foi possível obter os dados do gráfico do dashboard. Tente novamente.')
            }
        });
    }

    async function createCharts() {
        let tipoMatch = $('#tipoEvento').val();
    
        // Array para armazenar todas as promessas
        const promises = [];
    
        // Função auxiliar para criar uma promessa para cada chamada getChartData
        function createPromise(id) {
            return new Promise((resolve, reject) => {
                $(`#loadingMessage${id}`).show();
                getChartData(function(error, data) {
                    if (error) {
                        atualizarGraficosAgGrid(data, `myDashBar${id}`, tipoMatch, id);
                        $(`#loadingMessage${id}`).hide();
                        resolve();
                    } else {
                        atualizarGraficosAgGrid(data, `myDashBar${id}`, tipoMatch, id);
                        $(`#loadingMessage${id}`).hide();
                        resolve();
                    }
                }, tipoMatch, id);
            });
        }
    
        // Criar e armazenar todas as promessas
        for (let i = 1; i <= 6; i++) {
            promises.push(createPromise(i));
        }
    
        // Aguardar a conclusão de todas as promessas
        await Promise.all(promises);
    }
    
    $('.chart-exp').click( async function (event) {
        let id = $(this).attr('id').split('-');
        let tipoMatch = $('#tipoEvento').val();
        let name = 'Gráfico'
        let matchName = tipoMatch == '2' ? 'Cold List' : 'Hot List';
        
        if (id.length > 1) { 
            id = id[1]; 
        }

        if (id == '1') {
           name = `Gráfico ${matchName} - 5 dias`;
        } else if (id == '2') {
            name = `Gráfico ${matchName} - 7 dias`;
        } else if (id == '3') {
            name = `Gráfico ${matchName} - 15 dias`;
        } else if (id == '4') {
            name = `Gráfico ${matchName} - 20 dias`;
        } else if (id == '5') {
            name = `Gráfico ${matchName} - 30 dias`;
        } else if (id == '6') {
            name = `Gráfico ${matchName} - 60 dias`;
        }

        $('#chartModalLabel').html(name);

        $('#downloadChart').off('click').click( function(event) {
            event.preventDefault();
            agCharts.AgCharts.download(
                Bars[`myDashBar${id}`], 
                { 
                    width: 800, 
                    height: 500, 
                    fileName: name
                }
            );
        })

        ShowLoadingScreen();
        
        promise = new Promise((resolve, reject) => {
            getChartData(function(error, data) {
                if (error) {
                    atualizarGraficosAgGrid(data, `myDashBarModal`, tipoMatch, id);
                    resolve();
                } else {
                    atualizarGraficosAgGrid(data, `myDashBarModal`, tipoMatch, id);
                    resolve();
                }
            }, tipoMatch, id);
        })

        promise.then(() => {
            HideLoadingScreen();
            $('#chartModal').modal('show');
        }).catch(error => {
            HideLoadingScreen();
        });

    });

    function atualizarGraficosAgGrid(dados, chartId, tipoMatch, id) {
        let dadosParaExibir = dados.slice(0, 10);

        const options = {
            container: document.getElementById(chartId),
            data: dadosParaExibir,
            theme: {
                baseTheme: "ag-default",
                palette: {
                    fills: tipoMatch == '2' ? ["#3a87ad"] : ['#ff7770'],
                    strokes: ["black"],
                },
            },
            series: [{
                type: "bar",
                direction: "horizontal",
                xKey: "placa",
                xName: "Placa",
                yKey: "qtdAlertas",
                yName: "Quantidade de Alertas",
                tooltip: { 
                    renderer: function (data) {
                        let datum = data.datum;
                        return {
                            title:  datum[data.xKey] ,
                            content: 'Qtd. de Alertas: ' + datum[data.yKey].toFixed(0)
                        };
                    }
                }
            }],
            axes: [
                {
                    type: "category",
                },
                {
                    type: "number",
                },
            ],
            overlays: {
                noData: {
                    text: 'Não há dados para serem exibidos.'
                }
            },
            autoSize: true,
            listeners: {
                seriesNodeClick: function(params) {
                    var seriesType = params.seriesId.split('-').length > 1 ? params.seriesId.split('-')[0] : params.seriesId;
                    let days;

                    if (id == '1') {
                        days = 5;
                    } else if (id == '2') {
                        days = 7;
                    } else if (id == '3') {
                        days = 15;
                    } else if (id == '4') {
                        days = 20;
                    } else if (id == '5') {
                        days = 30;
                    } else if (id == '6') {
                        days = 60;
                    }

                    // Obter a data atual
                    let dataFinal = new Date();

                    // Obter a data atual menos days
                    let dataInicial = new Date();
                    dataInicial.setDate(dataFinal.getDate() - days);

                    if (seriesType == 'BarSeries') {
                        ShowLoadingScreen();
                        let placa = params.datum[params.xKey];
                        idBarSelected = params.seriesId + '-' + placa;
                        let options = {
                            placa: placa,
                            tipoMatch: tipoMatch,
                            dataInicial: dataInicial.toLocaleDateString('pt-BR'),
                            dataFinal: dataFinal.toLocaleDateString('pt-BR'),
                            idBarSeries: params.seriesId + '-' + placa
                        }
                        
                        carregarMapaDetalhesPlacaEvento(-15.39, -55.73, 4)
                            .then(() => {
                                showLoadingEventosModal();
                                $('.modal-subtitle').html(placa);
                                atualizarAgGridEventosPlacas(options);
                            })
                            .catch(error => {
                                showAlert('error', "Erro ao carregar o mapa.");
                            });
                    }
                }
            }
        };

        if (chartId in Bars) {
            Bars[chartId].destroy();
            delete Bars[chartId];
        }
        
        Bars[chartId] = agCharts.AgCharts.create(options);
        
    }

    getMetricas();
    createCharts();
    
    $('#tipoEvento').change(async function() {
        $(this).attr('disabled', true);
        await createCharts();
        $(this).attr('disabled', false);
    });

    setInterval( async function() {
        getMetricas();
    }, 120000)

    var AgGridEventosPlacas;
    let filaDeChamadasEventosPlaca = [];
    let executandoChamadaEventosPlaca = false;

    function atualizarAgGridEventosPlacas(options) {
        stopAgGRIDEventosPlacas();
        function getServerSideDados() {
            return {
                getRows: (params) => {
            
                var route = Router + '/buscarDadosEventosPlacasServerSide';
            
                $.ajax({
                    cache: false,
                    url: route,
                    type: 'POST',
                    data: {
                        startRow: params.request.startRow,
                        endRow: params.request.endRow,
                        placa: options ? options.placa : '',
                        tipoMatch: options ? options.tipoMatch : '',
                        dataInicial: options ? options.dataInicial : '',
                        dataFinal: options ? options.dataFinal : ''
                    },
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                    if (data && data.success) {
                        var dados = data.rows;
                        for (let i = 0; i < dados.length; i++) {
                            for (let chave in dados[i]) {
                                // Verifica se o valor é null e substitui por uma string vazia
                                if (dados[i][chave] === null) {
                                    dados[i][chave] = '';
                                }
                                if (chave === 'tipo_match') {
                                    if (dados[i][chave] == '1') {
                                        dados[i][chave] = 'Hot List';
                                    } else if (dados[i][chave] == '2') {
                                        dados[i][chave] = 'Cold List';
                                    } else {
                                        dados[i][chave] = 'Indefinido';
                                    }
                                }
                            }
                        }
                        params.success({
                            rowData: dados,
                            rowCount: data.lastRow,
                        });

                        setTimeout(function() {
                            var primeiraLinhaElemento = $('#chart-evento-grid .ag-row-even').eq(0);

                            if (primeiraLinhaElemento.length > 0) {
                                primeiraLinhaElemento[0].click();
                            }
                        }, 100);
                    } else if (data && data.message){
                        showAlert('warning', data.message);
                        HideLoadingScreen();
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                    } else {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        HideLoadingScreen();
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                    }
                    },
                    error: function (error) {
                        showAlert('error', 'Erro na solicitação ao servidor');
                        HideLoadingScreen();
                        params.failCallback();
                        params.success({
                            rowData: [],
                            rowCount: 0,
                        });
                    },
                });
                },
            };
        }

        const gridOptions = {
            columnDefs: [
                {
                    headerName: 'Placa Lida',
                    field: 'placa_lida',
                    chartDataType: 'category',
                    width: 140,
                    suppressSizeToFit: true
                },
                {
                    headerName: 'Início do evento',
                    field: 'file_s_time',
                    chartDataType: 'category',
                    cellRenderer: function (options) {
                        if (options.value) {
                            return formatDateTime(options.value);
                        } else {
                            return '';
                        }
                    }
                },
                {
                    headerName: 'Fim do evento',
                    field: 'file_e_time',
                    chartDataType: 'category',
                    cellRenderer: function (options) {
                        if (options.value) {
                            return formatDateTime(options.value);
                        } else {
                            return '';
                        }
                    }
                },
                {
                    headerName: 'Tipo do evento',
                    field: 'tipo_match',
                    chartDataType: 'category',
                    width: 150,
                    suppressSizeToFit: true,
                    cellRenderer: function (options) {
                        if (options.value == 'Hot List') {
                            return `<span class="badge badge-danger">Hot List</span>`;
                        } else if (options.value == 'Cold List') {
                            return `<span class="badge badge-info">Cold List</span>`;
                        } else if (options.value == 'Indefinido') {
                            return `<span class="badge badge-default">Indefinido</span>`;
                        } else {
                            return options.value;
                        }
                    }
                },
                {
                    headerName: 'Cliente',
                    field: 'cliente',
                    chartDataType: 'category',
                    flex: 1,
                    suppressSizeToFit: true
                },
            ],
            autoGroupColumnDef: {
                minWidth: '250'
            },
            defaultColDef: {
                editable: false,
                sortable: false,
                filter: false,
                resizable: true,
                suppressMenu: true,
            },
            sideBar: {
                toolPanels: [
                    {
                        id: 'columns',
                        labelDefault: 'Colunas',
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
                        },
                    },
                ],
                defaultToolPanel: false,
            },
            popupParent: document.body,
            enableRangeSelection: true,
            domLayout: 'normal',
            localeText: localeText,
            rowModelType: 'serverSide',
            serverSideStoreType: 'partial',
            cacheBlockSize: 10,
            onRowClicked: async function(event) {
                if ('data' in event && event.data) {
                    let id = event.data.id;
            
                    // Remover a classe 'highlighted-row' de todas as linhas
                    event.api.forEachNode(node => {
                        if (node.isSelected()) {
                            node.setSelected(false); // Desmarcar a linha, se estiver selecionada
                        }
                        if (node.data?.id === id) {
                            node.setSelected(true); // Selecionar a linha com o ID correspondente
                        }
                    });
        
                    filaDeChamadasEventosPlaca.push({ id: id, idBarSeries: options.idBarSeries });
        
                    if (!executandoChamadaEventosPlaca) {
                        await executarProximaChamadaEventosPlacas();
                    }
                }
            }
        };

        var gridDiv = document.querySelector('#chart-evento-grid');
        AgGridEventosPlacas = new agGrid.Grid(gridDiv, gridOptions);
        let datasource = getServerSideDados()
        gridOptions.api.setServerSideDatasource(datasource);

        async function executarProximaChamadaEventosPlacas() {
            // Verificar se há chamadas na fila
            if (filaDeChamadasEventosPlaca.length > 0) {
                executandoChamadaEventosPlaca = true;
                let chamada = filaDeChamadasEventosPlaca.shift();
                let id = chamada.id;
                let idBarSeries = chamada.idBarSeries;
        
                // evita replicação de chamadas na fila (IMPORTANTE PARA EVITAR DESINCRONIZAÇÃO DOS DADOS)
                // caso ocorre quando modal é fechado antes das chamadas serem finalizadas
                if (idBarSeries == idBarSelected) {
                    await new Promise((resolve, reject) => {
                        showLoadingEventosModal();
                        getDadosPlacaEventosById(function(dados, error) {
                            if (!error) {
                                //reforço de verficação de chamadas
                                if (idBarSeries == idBarSelected) {
                                    $('#loadingMessageMapaEventosPlaca').hide();
                                    $('.modal-subtitle').html(dados.placa_lida + ' | ' + dados.serial);
                                    $('#file_s_time').html(dados.file_s_time ? formatDateTime(dados.file_s_time) : '--');
                                    $('#file_e_time').html(dados.file_e_time ? formatDateTime(dados.file_e_time) : '--');
                                    $('#marca-evento').html(dados.marca ? dados.marca : '--');
                                    $('#modelo-evento').html(dados.modelo ? dados.modelo : '--');
                                    $('#lat-evento').html(dados.latitude ? dados.latitude : '--');
                                    $('#long-evento').html(dados.longitude ? dados.longitude : '--');
                                    $('#endereco-evento').html(dados.endereco ? dados.endereco : '--');
                                    
                                    if (dados['file'] != null) {
                                        let urlImg = "data:image/png;base64," + dados['file'];
                                        $('#loadingMessageImg').hide();
                                        $('#emptyMessageImg').hide();
                                        $('#img-evento').attr('src', urlImg);
                                        $('#img-evento').css('opacity', '1').css('height', 'auto');
                                    }
                                    else {
                                        let urlImg = BaseURL + 'assets/css/icon/png/512/image.png';
                                        $('#loadingMessageImg').hide();
                                        $('#emptyMessageImg').show();
                                        $('#img-evento').css('opacity', '0').css('height', '200px');
                                    }
                                
                                    if (dados['file_plate'] != null) {
                                        let urlImg = "data:image/png;base64," + dados['file_plate'];
                                        $('#loadingMessageImgPlaca').hide();
                                        $('#emptyMessageImgPlaca').hide();
                                        $('#img-evento-placa').attr('src', urlImg);
                                        $('#img-evento-placa').css('opacity', '1').css('height', 'auto');
                                    }
                                    else {
                                        let urlImg = BaseURL + 'assets/css/icon/png/512/image.png';
                                        $('#loadingMessageImgPlaca').hide();
                                        $('#emptyMessageImgPlaca').show();
                                        $('#img-evento-placa').css('opacity', '0').css('height', '90px');
                                    }

                                    marcarNomapaDetalhesPlacaEvento(dados).then(resolve).catch(reject);
                                    HideLoadingScreen();
                                    $('#chartModalDetails').modal('show');
                                }
                                resolve();
                            } else {
                                HideLoadingScreen();
                                $('#loadingMessageMapaEventosPlaca').hide();
                                reject();
                            }
                        }, id);
                    });
                }
        
                executandoChamadaEventosPlaca = false;

                await executarProximaChamadaEventosPlacas();
            }
        }
    }

    $("#chartModalDetails").on('hide.bs.modal', function(){
        idBarSelected = '';
      });

});

function getDadosPlacaEventosById(callback, id) {
    let route = Router + '/buscaEventosPlacasByID'
    
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            id: id,
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == '200' && 'resultado' in data) {
                var dados = data.resultado;
                if (typeof(callback) === 'function') callback(dados);
            } else {
                if (typeof(callback) === 'function') callback([]);
            }
        },
        error: function () {
            if (typeof(callback) === 'function') callback([], true);
            showAlert('error', 'Erro ao tentar tentar realizar a listagem. Tente novamente!')
        }
    });
}

var mapaDetalhesPlacaEvento = "";
let mapaDetalhesPlacaEventoObserver;
var marcadoresDetalhesPlacaEvento = [];
// ----- MAPA MODAL VEICULOS RECUPERADOS -----
//funcao responsavel por carregar mapa e o ponto
function carregarMapaDetalhesPlacaEvento(lat = 0, log = 0, zoom = 2) {
    return new Promise((resolve, reject) => {
        resetarmapaDetalhesPlacaEvento();
        //carregar mapa na mesma posicao do evento
        mapaDetalhesPlacaEvento = L.map('mapDetalhesPlacaEvento', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
        let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });
        mapaDetalhesPlacaEvento.setView([lat, log], zoom);
        mapaDetalhesPlacaEvento.dragging.enable();
        osm.addTo(mapaDetalhesPlacaEvento);
        // Função para chamar invalidateSize
        function ajustarTamanhoMapa() {
            mapaDetalhesPlacaEvento.invalidateSize();
        }
        // Adicionar observador de mutação com jQuery
        const target = document.getElementById('mapDetalhesPlacaEvento');
        mapaDetalhesPlacaEventoObserver = new MutationObserver(ajustarTamanhoMapa);
        const config = { attributes: true, childList: true, subtree: true };
        mapaDetalhesPlacaEventoObserver.observe(target, config);
        // Certificar-se de chamar a função inicialmente
        ajustarTamanhoMapa();

        // Resolvendo a Promise após o carregamento do mapa
        resolve();
    });
}

function ajustarZoommapaDetalhesPlacaEvento(lat = 0, log = 0, zoom = 2) {
    mapaDetalhesPlacaEvento.setView([lat, log], zoom);
}

//funcao responsavel por resetar o mapa
function resetarmapaDetalhesPlacaEvento() {
    if (mapaDetalhesPlacaEvento != "") {
        mapaDetalhesPlacaEvento.off();
        mapaDetalhesPlacaEvento.remove();
        mapaDetalhesPlacaEventoObserver.disconnect();
    }
}

function limparMarcasNomapaDetalhesPlacaEvento(marcadoresDetalhesPlacaEvento) {
    if (marcadoresDetalhesPlacaEvento != null) {
        marcadoresDetalhesPlacaEvento.forEach(marker => {
            mapaDetalhesPlacaEvento.removeLayer(marker);
        });
    }
}

//marca os pins no mapa
async function marcarNomapaDetalhesPlacaEvento(dados) {
    limparMarcasNomapaDetalhesPlacaEvento(marcadoresDetalhesPlacaEvento);
    let popup;
    let marker;
    var resultData = dados;
    if (resultData != null) { // todos os eventos selecionados
        let element = resultData;

        var dotStyles = `
            background-color: #FFFFFF; /* Cor de fundo branca */
            width: 1rem;
            height: 1rem;
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%; /* Bordas arredondadas para formar uma bolinha */
        `;

        var markerHtmlStyles = `
            background-color: #${element.tipo_match == 1 ? 'FF002D' : '0064FF'};
            width: 3rem;
            height: 3rem;
            display: block;
            left: -1.5rem;
            top: -1.5rem;
            position: relative;
            border-radius: 3rem 3rem 0;
            transform: rotate(45deg);
            border: 1px solid #FFFFFF;
        `;

        var icon = L.divIcon({
            className: "my-custom-pin",
            iconAnchor: [0, 24],
            labelAnchor: [-6, 0],
            popupAnchor: [0, -36],
            html: `
                    <span style="${markerHtmlStyles}">
                    <span style="${dotStyles}">
                    </span>
                `
        });

        marker = L.marker([element.latitude, element.longitude],
            {
                draggable: false, id: -1, icon: icon
            }).addTo(mapaDetalhesPlacaEvento);

        ajustarZoommapaDetalhesPlacaEvento(element.latitude, element.longitude, 8);
        marcadoresDetalhesPlacaEvento.push(marker);
        
    }
    return marcadoresDetalhesPlacaEvento;
}

function ShowLoadingScreen() {
    $('#loading').show()
}

function HideLoadingScreen() {
    $('#loading').hide()
}

function showLoadingEventosModal() {
    $('#loadingMessageMapaEventosPlaca').show();
    $('#emptyMessageImg').hide();
    $('#emptyMessageImgPlaca').hide();
    $('#img-evento').css('opacity', '0')
    $('#img-evento-placa').css('opacity', '0')
    $('#loadingMessageImg').show();
    $('#loadingMessageImgPlaca').show();
    $('#file_s_time').html('<i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;">');
    $('#file_e_time').html('<i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;">');
    $('#marca-evento').html('<i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;">');
    $('#modelo-evento').html('<i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;">');
    $('#lat-evento').html('<i class="fa fa-spinner fa-spin" style="font-size: 12px; color: white;">');
    $('#long-evento').html('<i class="fa fa-spinner fa-spin" style="font-size: 12px; color: white;">');
    $('#endereco-evento').html('<i class="fa fa-spinner fa-spin" style="font-size: 12px; color: #1C69AD;">');
}

function stopAgGRIDEventosPlacas() {
    var gridDiv = document.querySelector('#chart-evento-grid');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }

    var wrapper = document.querySelector('.wrapperEventos');
    if (wrapper) {
        wrapper.innerHTML = '<div id="chart-evento-grid" class="ag-theme-alpine" style="height: 300px;"></div>';
    }
}

function formatDateTime(date) {
    dates = date.split(' ');
    dateCalendar = dates[0].split('-');
    return dateCalendar[2] + "/" + dateCalendar[1] + "/" + dateCalendar[0] + " " + dates[1];
}