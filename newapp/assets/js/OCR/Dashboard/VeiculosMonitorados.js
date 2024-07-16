$(document).ready(function() {
    $('#card-veiculos-monitorados').click(function() {
        ShowLoadingScreen();
        getDadosVeiculosMonitorados(function(dados, error) {
            if (!error) {
                $('#search-input-veiculos-monitorados').val('');
                $('#search-input-veiculos-monitorados').popover('hide');
                $('#EmptyMessageMapaVeiculosMonitorados').show();
                carregarMapaVeiculosMonitorados(-15.39, -55.73, 4);
                criarAgGridVeiculosMonitorados(dados);
                HideLoadingScreen();
                $('#modalVeiculosMonitorados').modal('show');
            } else {
                HideLoadingScreen();
            }
            
        });
        
    });

    $('#search-input-veiculos-monitorados').popover({placement: 'bottom', trigger: 'manual'});

});

// Requests
function getDadosVeiculosMonitorados(callback, options) {
    let route;
    if (options && options.placa) {
        route = Router + '/buscarVeiculosMonitoradosByPlate'
    } else {
        route = Router + '/buscarVeiculosMonitorados'
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
                            if (dados[i][chave] == 2) {
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

function getDadosPlacaVeiculosMonitorados(callback, placa) {
    let route = Router + '/buscarPlacaVeiculosMonitoradosByPlate'
    
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
                        // Verifica se o valor ÃƒÂ© null e substitui por uma string vazia
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
var AgGridVeiculosMonitorados;
let filaDeChamadasVeiculosMonitorados = [];
let executandoChamadaVeiculosMonitorados = false;

function criarAgGridVeiculosMonitorados(dados, options) {
    stopAgGRIDVeiculosMonitorados();
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

            $('#EmptyMessageMapaVeiculosMonitorados').hide();

            let campos = document.querySelectorAll('.highlighted-row');
            campos.forEach(campo => campo.classList.remove('highlighted-row'));

            // Adicionar a classe de cor de fundo ao campo clicado
            event.event.target.parentElement.classList.add('highlighted-row');

            let placa = event.data.placa;
    
            // Adicionar a chamada Ãƒ  fila
            filaDeChamadasVeiculosMonitorados.push({ placa: placa });
    
            // Verificar se uma chamada estÃƒÂ¡ em andamento
            if (!executandoChamadaVeiculosMonitorados) {
                await executarProximaChamada();
            }
        }
    };
    var gridDiv = document.querySelector('#veiculos-monitorados-grid');
    
    AgGridVeiculosMonitorados = new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData(dados);

    async function executarProximaChamada() {
        // Verificar se hÃƒÂ¡ chamadas na fila
        if (filaDeChamadasVeiculosMonitorados.length > 0) {
            executandoChamadaVeiculosMonitorados = true;
            let chamada = filaDeChamadasVeiculosMonitorados.shift(); // Pegar a próxima chamada da fila
            let placa = chamada.placa;
    
            await new Promise((resolve, reject) => {
                $('#loadingMessageMapaVeiculosMonitorados').show();
                getDadosPlacaVeiculosMonitorados(function(dados, error) {
                    if (!error) {
                        $('#loadingMessageMapaVeiculosMonitorados').hide();
                        marcarNomapaVeiculosMonitorados(dados).then(resolve).catch(reject);
                    } else {
                        $('#loadingMessageMapaVeiculosMonitorados').hide();
                        reject(error);
                    }
                }, placa);
            });
    
            // Indicar que a chamada foi concluÃƒÂ­da
            executandoChamadaVeiculosMonitorados = false;
    
            // Executar a próxima chamada na fila
            await executarProximaChamada();
        }
    }

    $('#search-input-veiculos-monitorados').off('change').on('change', function () {
        let options = {
            placa: $(this).val()
        }

        if (options.placa == '' || (options.placa.length > 2)) {
            $('#loadingMessageVeiculosMonitorados').show();
            limparMarcasNomapaVeiculosMonitorados(marcadoresVeiculosMonitorados);
            $('#EmptyMessageMapaVeiculosMonitorados').show();
            getDadosVeiculosMonitorados(function(dados, error) {
                if (!error) {
                    gridOptions.api.setRowData(dados);
                    $('#loadingMessageVeiculosMonitorados').hide();
                } else {
                    $('#loadingMessageVeiculosMonitorados').hide();
                }
                
            }, options);
        }
    });

    $('#search-input-veiculos-monitorados').off('input').on('input', function () {
        let placa = $(this).val();
        
        if (placa.length < 3 && placa.length > 0) {
            $('#search-input-veiculos-monitorados').popover('show');
        } else {
            $('#search-input-veiculos-monitorados').popover('hide');
        }
    });
}

function stopAgGRIDVeiculosMonitorados() {
    var gridDiv = document.querySelector('#veiculos-monitorados-grid');
    if (gridDiv && gridDiv.api) {
        gridDiv.api.destroy();
    }
    var wrapper = document.querySelector('.wrapper-eventos-veiculos-monitorados');
    if (wrapper) {
        wrapper.innerHTML = `
        <div id="loadingMessageVeiculosMonitorados" class="loadingMessage" style="display: none;">
            <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #1C69AD;"></i> Carregando ...
        </div>
        <div id="veiculos-monitorados-grid" class="ag-theme-alpine" style="height: 565px;"></div>`;
    }
}

var mapaVeiculosMonitorados = "";
let mapaVeiculosMonitoradosObserver;
var marcadoresVeiculosMonitorados = [];
// ----- MAPA MODAL VEICULOS RECUPERADOS -----
//funcao responsavel por carregar mapa e o ponto
function carregarMapaVeiculosMonitorados(lat = 0, log = 0, zoom = 2) {
    resetarmapaVeiculosMonitorados();
    //carregar mapa na mesma posicao do evento
    mapaVeiculosMonitorados = L.map('mapVeiculosMonitorados', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaVeiculosMonitorados.setView([lat, log], zoom);
    mapaVeiculosMonitorados.dragging.enable();
    osm.addTo(mapaVeiculosMonitorados);
    // FunÃƒÂ§ÃƒÂ£o para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaVeiculosMonitorados.invalidateSize();
    }
    // Adicionar observador de mutaÃƒÂ§ÃƒÂ£o com jQuery
    const target = document.getElementById('mapVeiculosMonitorados');
    mapaVeiculosMonitoradosObserver = new MutationObserver(ajustarTamanhoMapa);
    const config = { attributes: true, childList: true, subtree: true };
    mapaVeiculosMonitoradosObserver.observe(target, config);
    // Certificar-se de chamar a funÃƒÂ§ÃƒÂ£o inicialmente
    ajustarTamanhoMapa();
}
function ajustarZoommapaVeiculosMonitorados(lat = 0, log = 0, zoom = 2) {
    mapaVeiculosMonitorados.setView([lat, log], zoom);
}
//funcao responsavel por resetar o mapa
function resetarmapaVeiculosMonitorados() {
    if (mapaVeiculosMonitorados != "") {
        mapaVeiculosMonitorados.off();
        mapaVeiculosMonitorados.remove();
        mapaVeiculosMonitoradosObserver.disconnect();
    }
}
function limparMarcasNomapaVeiculosMonitorados(marcadoresVeiculosMonitorados) {
    if (marcadoresVeiculosMonitorados != null) {
        marcadoresVeiculosMonitorados.forEach(marker => {
            mapaVeiculosMonitorados.removeLayer(marker);
        });
        ajustarZoommapaVeiculosMonitorados(-15.39, -55.73, 4);
    }
}
//marca os pins no mapa
async function marcarNomapaVeiculosMonitorados(dados) {
    limparMarcasNomapaVeiculosMonitorados(marcadoresVeiculosMonitorados);
    let popup;
    let marker;
    var resultData = dados;
    if (resultData != null) { // todos os eventos selecionados
        if (resultData.length != 0) {
            resultData.forEach(element => {
                popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUpVeiculosMonitorados(element));
                
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
                    }).addTo(mapaVeiculosMonitorados);
                marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });
                //ajustarZoommapaVeiculosMonitorados(element.latitude, element.longitude, 8);
                marcadoresVeiculosMonitorados.push(marker);
            });
        }
    }
    return marcadoresVeiculosMonitorados;
}
function htmlPopUpVeiculosMonitorados(data){
    return `
    <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item d-flex">
                <a class="flex-grow-1" style="font-size: 18px; font-weight: 600; margin-top: 10px; text-decoration: none;">${data.placaLida}</a>
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
                <span class="item-popup-title">Coordenadas de Chegada:</span> <br />    
                <div style="margin-top: 5px;">
                    <span class="badge badge-info" style="padding-top: 3px;">${data.latitude_fim}</span>
                    <span class="badge badge-info" style="padding-top: 3px;">${data.longitude_fim}</span> 
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