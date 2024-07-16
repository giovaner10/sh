$(document).ready(function() {
    $('#card-cold-list').click(function() {
        ShowLoadingScreen();
        getDadosEventosColdList(function(dados, error) {
            if (!error) {
                $('#search-input-cold-list').val('');
                $('#search-input-cold-list').popover('hide');
                $('#EmptyMessageMapaEventosColdList').show();
                carregarMapaEventosColdList(-15.39, -55.73, 4);
                criarAgGridEventosColdList(dados);
                HideLoadingScreen();
                $('#modalEventosColdList').modal('show');
            } else {
                HideLoadingScreen();
            }
            
        });
        
    });

    $('#search-input-cold-list').popover({placement: 'bottom', trigger: 'manual'});

});

// Requests
function getDadosEventosColdList(callback, options) {
    let route;
    if (options && options.placa) {
        route = Router + '/buscarEventosColdListByPlate'
    } else {
        route = Router + '/buscarEventosColdList'
    }
    
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            options: options || {},
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == '200') {
                var dados = data.resultado;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        if (chave == 'tipo_match') {
                            if (dados[i][chave] !== 2) {
                                dados.splice(i, 1);
                            }
                        }
                    }
                }
                if (typeof(callback) === 'function') callback(dados);
            } else {
                if (typeof(callback) === 'function') callback([]);
            }
        },
        error: function () {
            if (typeof(callback) === 'function') callback([], true);
            showAlert('error', 'Erro ao tentar tentar realizar a listagem. Tente novamente!')
        }
    })
}

function getDadosPlacaEventosColdList(callback, placa) {
    let route = Router + '/buscarPlacaEventosColdListByPlate'
    
    $.ajax({
        cache: false,
        url: route,
        type: 'POST',
        data: {
            placa: placa,
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == '200') {
                var dados = data.resultado;
                for (let i = 0; i < dados.length; i++) {
                    for (let chave in dados[i]) {
                        // Verifica se o valor é null e substitui por uma string vazia
                        if (dados[i][chave] === null) {
                            dados[i][chave] = '';
                        }
                    }
                }
                if (typeof(callback) === 'function') callback(dados);
            } else {
                if (typeof(callback) === 'function') callback([]);
            }
        },
        error: function () {
            if (typeof(callback) === 'function') callback([], true);
            showAlert('error', 'Erro ao tentar tentar realizar a listagem. Tente novamente!')
        }
    })
}

// Ag-Grid
var AgGrid;
let filaDeChamadas = [];
let executandoChamada = false;

function criarAgGridEventosColdList(dados, options) {
    stopAgGRIDEventosColdList();
    const gridOptions = {
        columnDefs: [
            {
                headerName: 'Placa',
                field: 'placa',
                chartDataType: 'category',
                width: 140
            },
            {
                headerName: 'Qtd. Eventos',
                field: 'quantidadeEventos',
                flex: 1
            }
        ],
        defaultColDef: {
            editable: false,
            sortable: false,
            filter: false,
            resizable: true,
            suppressMenu: true
        },
        popupParent: document.body,
        cacheBlockSize: 10,
        localeText: localeText,
        domLayout: 'normal',
        onRowClicked: async function(event) {

            $('#EmptyMessageMapaEventosColdList').hide();

            let campos = document.querySelectorAll('.highlighted-row');
            campos.forEach(campo => campo.classList.remove('highlighted-row'));

            // Adicionar a classe de cor de fundo ao campo clicado
            event.event.target.parentElement.classList.add('highlighted-row');

            let placa = event.data.placa;
    
            // Adicionar a chamada à fila
            filaDeChamadas.push({ placa: placa });
    
            // Verificar se uma chamada está em andamento
            if (!executandoChamada) {
                await executarProximaChamada();
            }
        }
    };
    var gridDiv = document.querySelector('#eventos-cold-list-grid');
    
    AgGrid = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);

    async function executarProximaChamada() {
        // Verificar se há chamadas na fila
        if (filaDeChamadas.length > 0) {
            executandoChamada = true;
            let chamada = filaDeChamadas.shift(); // Pegar a próxima chamada da fila
            let placa = chamada.placa;
    
            await new Promise((resolve, reject) => {
                $('#loadingMessageMapaEventosColdList').show();
                getDadosPlacaEventosColdList(function(dados, error) {
                    if (!error) {
                        $('#loadingMessageMapaEventosColdList').hide();
                        marcarNoMapaEventosColdList(dados).then(resolve).catch(reject);
                    } else {
                        $('#loadingMessageMapaEventosColdList').hide();
                        reject(error);
                    }
                }, placa);
            });
    
            // Indicar que a chamada foi concluída
            executandoChamada = false;
    
            // Executar a próxima chamada na fila
            await executarProximaChamada();
        }
    }

    $('#search-input-cold-list').off('change').on('change', function () {
        let options = {
            placa: $(this).val()
        }

        if (options.placa == '' || (options.placa.length > 2)) {
            $('#loadingMessageEventosColdList').show();
            limparMarcasNoMapaEventosColdList(marcadores);
            $('#EmptyMessageMapaEventosColdList').show();
            getDadosEventosColdList(function(dados, error) {
                if (!error) {
                    gridOptions.api.setRowData(dados);
                    $('#loadingMessageEventosColdList').hide();
                } else {
                    $('#loadingMessageEventosColdList').hide();
                }
                
            }, options);
        }
    });

    $('#search-input-cold-list').off('input').on('input', function () {
        let placa = $(this).val();
        
        if (placa.length < 3 && placa.length > 0) {
            $('#search-input-cold-list').popover('show');
        } else {
            $('#search-input-cold-list').popover('hide');
        }
    });
}

function stopAgGRIDEventosColdList() {
    var gridDiv = document.querySelector('#eventos-cold-list-grid');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapper-eventos-cold-list');
    if (wrapper) {
        wrapper.innerHTML = `
        <div id="loadingMessageEventosColdList" class="loadingMessage" style="display: none;">
            <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
        </div>
        <div id="eventos-cold-list-grid" class="ag-theme-alpine" style="height: 565px;"></div>`;
    }
}

var mapaEventosColdList = "";
let mapaEventosColdListObserver;
var marcadores = [];
// ----- MAPA MODAL VEICULOS RECUPERADOS -----
//funcao responsavel por carregar mapa e o ponto
function carregarMapaEventosColdList(lat = 0, log = 0, zoom = 2) {
    resetarMapaEventosColdList();
    //carregar mapa na mesma posicao do evento
    mapaEventosColdList = L.map('mapEventosColdList', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaEventosColdList.setView([lat, log], zoom);
    mapaEventosColdList.dragging.enable();
    osm.addTo(mapaEventosColdList);
    // Função para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaEventosColdList.invalidateSize();
    }
    // Adicionar observador de mutação com jQuery
    const target = document.getElementById('mapEventosColdList');
    mapaEventosColdListObserver = new MutationObserver(ajustarTamanhoMapa);
    const config = { attributes: true, childList: true, subtree: true };
    mapaEventosColdListObserver.observe(target, config);
    // Certificar-se de chamar a função inicialmente
    ajustarTamanhoMapa();
}
function ajustarZoomMapaEventosColdList(lat = 0, log = 0, zoom = 2) {
    mapaEventosColdList.setView([lat, log], zoom);
}
//funcao responsavel por resetar o mapa
function resetarMapaEventosColdList() {
    if (mapaEventosColdList != "") {
        mapaEventosColdList.off();
        mapaEventosColdList.remove();
        mapaEventosColdListObserver.disconnect();
    }
}
function limparMarcasNoMapaEventosColdList(marcadores) {
    if (marcadores != null) {
        marcadores.forEach(marker => {
            mapaEventosColdList.removeLayer(marker);
        });
        ajustarZoomMapaEventosColdList(-15.39, -55.73, 4);
    }
}
//marca os pins no mapa
async function marcarNoMapaEventosColdList(dados) {
    limparMarcasNoMapaEventosColdList(marcadores);
    let popup;
    let marker;
    var resultData = dados;
    if (resultData != null) { // todos os eventos selecionados
        if (resultData.length != 0) {
            resultData.forEach(element => {
                popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUpEventosColdList(element));
                
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
                    }).addTo(mapaEventosColdList);
                marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });
                //ajustarZoomMapaEventosColdList(element.latitude, element.longitude, 8);
                marcadores.push(marker);
            });
        }
    }
    return marcadores;
}
function htmlPopUpEventosColdList(data){
    return `
    <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item d-flex">
                <a class="flex-grow-1" style="font-size: 18px; font-weight: 600; margin-top: 10px; text-decoration: none;">${data.placa_lida}</a>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">${lang.serial}:</span>      
                <span class="float-right" style="color:#909090">${data.serial}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Inicial:</span>      
                <span class="float-right" style="color:#909090">${data.file_s_time}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Final:</span>      
                <span class="float-right" style="color:#909090">${data.file_e_time}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Marca:</span>      
                <span class="float-right" style="color:#909090">${data.marca}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Modelo:</span>      
                <span class="float-right" style="color:#909090">${data.modelo}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Coordenadas:</span> <br/>   
                <div style="margin-top: 5px;">
                    <span class="badge badge-info" style="padding-top: 3px;">${data.latitude}</span>
                    <span class="badge badge-info" style="padding-top: 3px;">${data.longitude}</span> 
                </div>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Ref.:</span>      
                <span class="" style="color:#909090">${data.endereco ? data.endereco : 'Referência não encontrada'}</span>
            </li>
        </ul>
    </div>
    `;
}