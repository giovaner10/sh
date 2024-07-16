var map;
const pathMarkers = '/gestor/media/img/markers/';
var markers = [];
var markerCluster;
// let infoWindow = [];

var templates = {
    iscaPopup: function (context) {
        var ender = context.isca.endereco ? context.isca.endereco : context.isca[14];
        var resultado = "";

        if(ender)
            resultado = ender.split(" ");

        var enderecoRes= '';
        var res='';

        for (var i = 0; i < resultado.length; i++) {
            res +=resultado[i]+' ';
            if(res.length >= 40){
                enderecoRes += "<br>";
                res = '';
            }
            enderecoRes += resultado[i]+' ';
        }


        var source = [
            '<ul class="popup-isca" data-id="{{isca.[0]}}">',
            '<li><b>Serial:</b> {{isca.[1]}}</li>',
            '<li><b>Rótulo:</b> {{isca.[2]}}</li>',
            '<li><b>Fabricante:</b> {{isca.[3]}}</li>',
            '<li><b>Cliente:</b> {{isca.[4]}}</li>',
            '<li><b>Placa:</b> {{isca.[5]}}</li>',
            '<li><b>Coordenadas:</b> '+ context.isca[8]+', '+ context.isca[9]+'</li>',
            '<li><b>Endereço:</b> <a target="_blank" href="https://www.google.com/maps/@{{isca.[8]}},{{isca.[9]}},17z/data=!5m1!1e1"><b>' + enderecoRes + '</b></a></li>',
            context.isca[11] ? '<li><b>GPS:</b> ' + 'Ligado' + '</li>' : '<li><b>LBS:</b> ' + 'Ligado' + '</li>',
            '<li></li>',
            '</ul>'
        ].join('');

        var template = Handlebars.compile(source);
        return template(context);
    },
}

$('#exibirMapa').on('click', function () {
    if($(event.target).prop('checked')) {
        $(".map-container").show();
    } else {
        $(".map-container").hide();
    }
});

function carregarMapa(tabela) {
    let dados = tabela.rows().data();

    // $("#map").toggle();

    if(!map) {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -15.77972, lng: -47.92972 },
            zoom: 5,
            gestureHandling: 'greedy',
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_TOP
                },
                scaleControl: true,
                streetViewControl: true,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.LEFT_TOP
                }
        });
    }

    map.setCenter({ lat: -15.77972, lng: -47.92972 });
    map.setZoom(5);

    deleteMarkers();

    dados.toArray().forEach(linha => {
        setMarkers(linha);
    });

    markerCluster = new MarkerClusterer(map, markers.map(marker => { return marker[0]; }), {
        imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
    });
    
    setMapOnAll();
}

function centralizarMapa(lat,lng){
    $('.map-container').show();
    $('#exibirMapa').prop('checked', 'checked');

    if(lng){
        map.setCenter({lat:lat,lng:lng});
        map.setZoom(20);
    }
    else{
        map.fitBounds(bounds);
    }
};

// Adds a marker to the map and push to the array.
function setMarkers(linha) {
    const marker = new MarkerWithLabel({
        position: { lat: parseFloat(linha[9]), lng: parseFloat(linha[8]) },
        map,
        icon: pathMarkers.concat('radar.png'),
        // label: linha.serial,
        // labelOrigin: new google.maps.Point(10, 40) 
        labelContent: linha[1],
        labelAnchor: new google.maps.Point(30, 0),
        labelClass: "markerLabel",
    });

    const infoWindow = new google.maps.InfoWindow({
        content: templates.iscaPopup({
            isca: linha,
        })
    })

    markers.push([
        marker,
        infoWindow
    ]);


    marker.addListener('click', function() {
        markers.forEach(
            function (data,index){
                data[1].close();
            }
        );
        infoWindow.open(map, this);
    })
}

// Sets the map on all markers in the array.
function setMapOnAll() {
    for (let i = 0; i < markers.length; i++) {
      markers[i][0].setMap(map);
    }
}
  
// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    for (let i = 0; i < markers.length; i++) {
        markers[i][0].setMap(null);
    }
    
    if(markerCluster) {
        markerCluster.clearMarkers();
    }
}
  
// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}
  
