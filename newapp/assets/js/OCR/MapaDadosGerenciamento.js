var mapaDadosGerenciamento = "";
var mapaDadosPartidaChegada = "";
var mapaEventos = "";
let mapaEventosObserver
var mapaEventoIndividual = "";
let mapaEventoIndividualObserver
let mapaDadosGerenciamentoObserver
let mapaDadosPartidaChegadaObserver
var markers = L.layerGroup();

$(document).ready(function () {
    carregarMapa(-15.39, -55.73, 4);
    carregarMapaEventos(-15.39, -55.73, 4);
    carregarMapaPartidaChegada(-15.39, -55.73, 4);
});

// ----- MAPA DADOS GERENCIAMENTO -----
//funcao responsavel por carregar mapa e o ponto
function carregarMapa(lat = 0, log = 0, zoom = 2) {
    resetarMapa();
    //carregar mapa na mesma posicao do evento
    mapaDadosGerenciamento = L.map('mapDadosGerenciamento', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaDadosGerenciamento.setView([lat, log], zoom);
    mapaDadosGerenciamento.dragging.enable();

    osm.addTo(mapaDadosGerenciamento);

    // Função para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaDadosGerenciamento.invalidateSize();
    }

    // Adicionar observador de mutação com jQuery
    const target = document.getElementById('mapDadosGerenciamento');
    mapaDadosGerenciamentoObserver = new MutationObserver(ajustarTamanhoMapa);

    const config = { attributes: true, childList: true, subtree: true };

    mapaDadosGerenciamentoObserver.observe(target, config);

    // Certificar-se de chamar a função inicialmente
    ajustarTamanhoMapa();

}
//funcao responsavel por resetar o mapa
function resetarMapa() {
    if (mapaDadosGerenciamento != "") {
        mapaDadosGerenciamento.off();
        mapaDadosGerenciamento.remove();
        mapaDadosGerenciamentoObserver.disconnect();
    }
}

function limparMarcasNoMapa(marcadores) {
    if (marcadores != null) {
        marcadores.forEach(marker => {
            mapaDadosGerenciamento.removeLayer(marker);
        });
    }
}

//marca os pins no mapa
function marcarNoMapa(dados) {
    let popup;
    let marker;
    var placasCores = {};
    var indiceCor = 0;
    var marcadores = [];

    if (dados != null) { // todos os eventos selecionados
        if (dados.length != 0) {
            dados.forEach(element => {
                popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUp(element));

                var markerHtmlStyles = `
                        background-color: ${element.tipo_match ==  'Hot List' ? '#ff0000' : '#0064FF'};
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

                marker = L.marker([element.latitude_fim, element.longitude_fim],
                    {
                        draggable: false, id: -1, icon: icon
                    }).addTo(mapaDadosGerenciamento);

                marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });

                marcadores.push(marker);
            })
        }
    }

    return marcadores;
}

//funcao interna responsavel pelo popup com as informacoes dos eventos
function htmlPopUp(data) {
    return `
    <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item d-flex">
                <a class="flex-grow-1" style="font-size: 18px; font-weight: 600; margin-top: 10px; text-decoration: none;">${data.placa_lida}</a>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">${lang.serial}:</span>      
                <span class="float-right" style="color:#909090">${data.serial ? data.serial : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Inicial:</span>      
                <span class="float-right" style="color:#909090">${data.file_s_time ? formatDateTime(data.file_s_time) : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Final:</span>      
                <span class="float-right" style="color:#909090">${data.file_e_time ? formatDateTime(data.file_e_time) : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Marca:</span>      
                <span class="float-right" style="color:#909090">${data.marca ? data.marca : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Modelo:</span>      
                <span class="float-right" style="color:#909090">${data.modelo ? data.modelo : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Coordenadas de Partida:</span> <br/>   
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
                <span class="" style="color:#909090">${data.enredeco ? data.enredeco : "Referência não encontrada"}</span>
            </li>
        </ul>
    </div>
    `;
}


// ----- MAPA MODAL DADOS DETALHADOS - PONTO PARTIDA -----

//funcao responsavel por carregar mapa e o ponto
function carregarMapaPartidaChegada(lat = 0, log = 0, zoom = 2) {
    resetarMapaPartidaChegada();
    //carregar mapa na mesma posicao do evento
    mapaDadosPartidaChegada = L.map('mapaDadosPartidaChegada', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaDadosPartidaChegada.setView([lat, log], zoom);
    mapaDadosPartidaChegada.dragging.enable();

    osm.addTo(mapaDadosPartidaChegada);

    // Função para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaDadosPartidaChegada.invalidateSize();
    }

    // Adicionar observador de mutação com jQuery
    const target = document.getElementById('mapaDadosPartidaChegada');
    mapaDadosPartidaChegadaObserver = new MutationObserver(ajustarTamanhoMapa);

    const config = { attributes: true, childList: true, subtree: true };

    mapaDadosPartidaChegadaObserver.observe(target, config);

    // Certificar-se de chamar a função inicialmente
    ajustarTamanhoMapa();

}

//funcao responsavel por resetar o mapa
function resetarMapaPartidaChegada() {
    if (mapaDadosPartidaChegada != "") {
        mapaDadosPartidaChegada.off();
        mapaDadosPartidaChegada.remove();
        mapaDadosPartidaChegadaObserver.disconnect();
    }
}

function limparMarcasNoMapaPartidaChegada(marcadores) {
    if (marcadores != null) {
        marcadores.forEach(marker => {
            mapaDadosPartidaChegada.removeLayer(marker);
        });
    }
}

//marca os pins no mapa
function marcarNoMapaPartidaChegada(dados) {
    let popup;
    let marker;
    let markerFim;

    if (dados != null) { // todos os eventos selecionados
        if (dados.length != 0) {

            popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUpPartidaChegada(dados));
            

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
                background-color: #${dados.tipo_match == 1 ? 'FF002D' : '0064FF'};
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


            marker = L.marker([dados.latitude, dados.longitude],
                {
                    draggable: false, id: -1, icon: icon
                }).addTo(mapaDadosPartidaChegada);

            marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });

            marcadores.push(marker);

            markerFim = L.marker([dados.latitude_fim, dados.longitude_fim],
                {
                    draggable: false, id: -1, icon: icon
                }).addTo(mapaDadosPartidaChegada);

            markerFim.bindPopup(popup, { maxWidth: "auto", closeButton: false });

            marcadores.push(markerFim);
        }
    }

    return marcadores;
}

//funcao interna responsavel pelo popup com as informacoes dos eventos
function htmlPopUpPartidaChegada(data) {
    return `
    <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item d-flex">
                <a class="flex-grow-1" style="font-size: 18px; font-weight: 600; margin-top: 10px; text-decoration: none;">${data.placa_lida}</a>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">${lang.serial}:</span>      
                <span class="float-right" style="color:#909090">${data.serial ? data.serial : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Inicial:</span>      
                <span class="float-right" style="color:#909090">${data.file_s_time ? formatDateTime(data.file_s_time) : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Final:</span>      
                <span class="float-right" style="color:#909090">${data.file_e_time ? formatDateTime(data.file_e_time) : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Marca:</span>      
                <span class="float-right" style="color:#909090">${data.marca ? data.marca : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Modelo:</span>      
                <span class="float-right" style="color:#909090">${data.modelo ? data.modelo : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Coordenadas de Partida:</span> <br/>   
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
                <span class="" style="color:#909090">${data.enredeco ? data.enredeco : "Referência não encontrada"}</span>
            </li>
        </ul>
    </div>
    `;
}


// ----- MAPA DETECÇÂO DE EVENTOS DE PLACAS -----
function carregarMapaEventos(lat = 0, log = 0, zoom = 2) {
    resetarMapaEventos();
    //carregar mapa na mesma posicao do evento
    mapaEventos = L.map('mapEventos', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaEventos.setView([lat, log], zoom);
    mapaEventos.dragging.enable();

    osm.addTo(mapaEventos);
    setTimeout(() => { mapaEventos.invalidateSize() }, 250);

    // Função para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaEventos.invalidateSize();
    }

    // Adicionar observador de mutação com jQuery
    const target = document.getElementById('mapDadosGerenciamento');
    mapaEventosObserver = new MutationObserver(ajustarTamanhoMapa);

    const config = { attributes: true, childList: true, subtree: true };

    mapaEventosObserver.observe(target, config);

    // Certificar-se de chamar a função inicialmente
    ajustarTamanhoMapa();

}
//funcao responsavel por resetar o mapa
function resetarMapaEventos() {
    if (mapaEventos != "") {
        mapaEventos.off();
        mapaEventos.remove();
        mapaEventosObserver.disconnect();
    }
}


//marca os pins no mapa
async function marcarNoMapaEventos(dados, atualizar = false) {
    let popup;
    let marker;
    markers.clearLayers();
 
    if (dados != null) {
        if (dados.length != 0) {
            dados.forEach(element => {
                if (element && element.latitude && element.longitude) {
                    popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUpEventos(element));
 
                    var markerHtmlStyles = `
                        background-color: ${element.tipoMatch == 'Hot List' ? '#ff0000' : '#0064FF'};
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
                        { draggable: false, id: -1, icon: icon });
 
                    marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });
 
                    markers.addLayer(marker)
                }
            })
 
            markers.addTo(mapaEventos);
        }
    }
    return Promise.resolve();
}

function randomIntFromInterval(min, max) { // min and max included 
    return Math.floor(Math.random() * (max - min + 1) + min)
}

//funcao interna responsavel pelo popup com as informacoes dos eventos
function htmlPopUpEventos(data) {
    var status = '';
    if (data.status == 'Inserido') {
        status = `<span class="badge badge-info">Inserido</span>`;
    } else if (data.status == 'Visualizado') {
        status = `<span class="badge badge-success">Visualizado</span>`;
    } else if (data.status == 'Tratado') {
        status = `<span class="badge badge-warning">Tratado</span>`;
    } else if (data.status == 'Em Tratativa') {
        status = `<span class="badge badge-primary status-primary">Em Tratativa</span>`;
    } else if (data.status == 'Finalizado Evento Real') {
        status = `<span class="badge badge-secondary status-secondary">Finalizado Evento Real</span>`;
    } else if (data.status == 'Finalizado Evento Falso') {
        status = `<span class="badge badge-dark status-dark">Finalizado Evento Falso</span>`;
    }
 
    return `
    <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item d-flex">
                <a class="flex-grow-1" style="font-size: 18px; font-weight: 600; margin-top: 10px; text-decoration: none;">${data.placaLida}</a>
            </li>
            <li class="list-group-item">
                <span class="item-popup-title">${lang.serial}:</span>      
                <span class="float-right" style="color:#909090">${data.serial ? data.serial : ''}</span>
            </li>
            <li class="list-group-item">
                <span class="item-popup-title">Data Inicial:</span>      
                <span class="float-right" style="color:#909090">${data.fileStartTime ? formatDateTime(data.fileStartTime) : ''}</span>
            </li>
            <li class="list-group-item">
                <span class="item-popup-title">Data Final:</span>      
                <span class="float-right" style="color:#909090">${data.fileEndTime ? formatDateTime(data.fileEndTime) : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Marca:</span>      
                <span class="float-right" style="color:#909090">${data.marca ? data.marca : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Modelo:</span>      
                <span class="float-right" style="color:#909090">${data.modelo ? data.modelo : ''}</span>
            </li>
            <li class="list-group-item">
                <span class="item-popup-title">Status:</span>      
                <span class="float-right" style="color:#909090">${status}</span>
            </li>
            <li class="list-group-item">
                <span class="item-popup-title">Coordenadas:</span> <br/>
                <div style="margin-top: 5px;">
                    <span class="badge badge-info" style="padding-top: 3px;">${data.latitude}</span>
                    <span class="badge badge-info" style="padding-top: 3px;">${data.longitude}</span>
                </div>
            </li>
            <li class="list-group-item">
                <span class="item-popup-title">Cliente:</span>      
                <span class="" style="color:#909090">${data.nome + ' (' + data.razaoSocial + ')'}</span>
            </li>
            <li class="list-group-item">
                <span class="item-popup-title">Ref.:</span>      
                <span class="" style="color:#909090">${data.endereco ? data.endereco : "Referência não encontrada"}</span>
            </li>
        </ul>
    </div>
    `;
}

// ----- MAPA MODAL DADOS DETALHADOS - PONTO PARTIDA -----
//funcao responsavel por carregar mapa e o ponto
function carregarMapaEventoIndividual(lat = 0, log = 0, zoom = 2) {
    resetarMapaEventoIndividual();
    //carregar mapa na mesma posicao do evento
    mapaEventoIndividual = L.map('mapEventoDeteccao', { maxZoom: 15, minZoom: 3, zoomControl: false, worldCopyJump: true, dragging: false })
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    mapaEventoIndividual.setView([lat, log], zoom);
    mapaEventoIndividual.dragging.enable();
    osm.addTo(mapaEventoIndividual);
    // Função para chamar invalidateSize
    function ajustarTamanhoMapa() {
        mapaEventoIndividual.invalidateSize();
    }
    // Adicionar observador de mutação com jQuery
    const target = document.getElementById('mapEventoDeteccao');
    mapaEventoIndividualObserver = new MutationObserver(ajustarTamanhoMapa);
    const config = { attributes: true, childList: true, subtree: true };
    mapaEventoIndividualObserver.observe(target, config);
    // Certificar-se de chamar a função inicialmente
    ajustarTamanhoMapa();
}
//funcao responsavel por resetar o mapa
function resetarMapaEventoIndividual() {
    if (mapaEventoIndividual != "") {
        mapaEventoIndividual.off();
        mapaEventoIndividual.remove();
        mapaEventoIndividualObserver.disconnect();
    }
}
function limparMarcasNoMapaEventoIndividual(marcadores) {
    if (marcadores != null) {
        marcadores.forEach(marker => {
            mapaEventoIndividual.removeLayer(marker);
        });
    }
}
//marca os pins no mapa
function marcarNoMapaEventoIndividual(dados) {
    let popup;
    let marker;

    var resultData = dados.resultado;

    if (resultData != null) { // todos os eventos selecionados
        if (resultData.length != 0) {
            popup = L.popup({ closeButton: false, autoClose: true }).setContent(htmlPopUpEventoIndividual(resultData));
            
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
                background-color: #${resultData.tipo_match == 1 ? 'FF002D' : '0064FF'};
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
            marker = L.marker([resultData.latitude, resultData.longitude],
                {
                    draggable: false, id: -1, icon: icon
                }).addTo(mapaEventoIndividual);
            marker.bindPopup(popup, { maxWidth: "auto", closeButton: false });
            marcadores.push(marker);
        }
    }
    return marcadores;
}

function htmlPopUpEventoIndividual(data){
    return `
    <div class="card-text-map">
        <ul class="list-group list-group-flush" style="border-radius: 10px;width: 250px;">
            <li class="list-group-item d-flex">
                <a class="flex-grow-1" style="font-size: 18px; font-weight: 600; margin-top: 10px; text-decoration: none;">${data.placa_lida}</a>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">${lang.serial}:</span>      
                <span class="float-right" style="color:#909090">${data.serial ? data.serial : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Inicial:</span>      
                <span class="float-right" style="color:#909090">${data.file_s_time ? formatDateTime(data.file_s_time) : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Data Final:</span>      
                <span class="float-right" style="color:#909090">${data.file_e_time ? formatDateTime(data.file_e_time) : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Marca:</span>      
                <span class="float-right" style="color:#909090">${data.marca ? data.marca : ''}</span>
            </li>
            <li class="list-group-item"> 
                <span class="item-popup-title">Modelo:</span>      
                <span class="float-right" style="color:#909090">${data.modelo ? data.modelo : ''}</span>
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
                <span class="" style="color:#909090">${data.endereco ? data.endereco : ''}</span>
            </li>
        </ul>
    </div>
    `;
}