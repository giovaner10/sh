function disableEnterKey(e) {
    var key;
    if (window.event)
        key = window.event.keyCode; //IE
    else
        key = e.which; //firefox      
    return (key != 13);
}
function ordens(head, tale) {
	head = head.split('/').reverse().join('-');
	tale = tale.split('/').reverse().join('-');
	$('#canvas').append('<canvas id="lineChart" width="950" height="430"></canvas>');
	const CHART = document.getElementById("lineChart");
	$.getJSON('graficoInfor', {'inicio':head, 'fim':tale}, function(data){
		var content = data;
		var conteudo = {
		    labels: content.etiquetas,
		    datasets: [
		        {
		            label: "Envios",
		            fill: false,
		            lineTension: 0.1,
		            backgroundColor: "#e87e7b",
		            borderColor: "#e0221f",
		            borderCapStyle: 'butt',
		            borderDash: [],
		            borderDashOffset: 0.0,
		            borderJoinStyle: 'miter',
		            pointBorderColor: "#0068B1",
		            pointBackgroundColor: "#1C94FF",
		            pointBorderWidth: 1,
		            pointHoverRadius: 5,
		            pointHoverBackgroundColor: "#e87e7b",
		            pointHoverBorderColor: "#e0221f",
		            pointHoverBorderWidth: 2,
		            pointRadius: 1,
		            pointHitRadius: 10,
		            data: content.valoresEnvio,
		        }, {
		            label: "Instalações",
		            fill: false,
		            lineTension: 0.1,
		            backgroundColor: "#65ce65",
		            borderColor: "#0baa0b",
		            borderCapStyle: 'butt',
		            borderDash: [],
		            borderDashOffset: 0.0,
		            borderJoinStyle: 'miter',
		            pointBorderColor: "#0068B1",
		            pointBackgroundColor: "#1C94FF",
		            pointBorderWidth: 1,
		            pointHoverRadius: 5,
		            pointHoverBackgroundColor: "#65ce65",
		            pointHoverBorderColor: "#0baa0b",
		            pointHoverBorderWidth: 2,
		            pointRadius: 1,
		            pointHitRadius: 10,
		            data: content.valoresInstal,
		        }, {
		            label: "Retornos",
		            fill: false,
		            lineTension: 0.1,
		            backgroundColor: "#e4c128",
		            borderColor: "#d8ad00",
		            borderCapStyle: 'butt',
		            borderDash: [],
		            borderDashOffset: 0.0,
		            borderJoinStyle: 'miter',
		            pointBorderColor: "#0068B1",
		            pointBackgroundColor: "#1C94FF",
		            pointBorderWidth: 1,
		            pointHoverRadius: 5,
		            pointHoverBackgroundColor: "#e4c128",
		            pointHoverBorderColor: "#d8ad00",
		            pointHoverBorderWidth: 2,
		            pointRadius: 1,
		            pointHitRadius: 10,
		            data: content.valoresRetorno,
		        }
		    ]
		};
		var meuChart = new Chart (CHART, {
			type: 'bar',
			data: conteudo,
			options: {
				responsive: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
		$('#buscando').hide(500);
	});
}
$(document).ready(function() {
	var d = new Date();
	var dateTale = ("0" + d.getDate()).substr(-2) + "/" + ("0" + (d.getMonth() + 1)).substr(-2) + "/" + d.getFullYear();
	$('#dateTale').val(dateTale);
	d.setDate((d.getDate()) - 6);
	var dateHead = ("0" + d.getDate()).substr(-2) + "/" + ("0" + (d.getMonth() + 1)).substr(-2) + "/" + d.getFullYear();
	$('#dateHead').val(dateHead);
	ordens($('#dateHead').val(), $('#dateTale').val());
	$('#dateHead').focusin(function() {
		dateHead = $('#dateHead').val();
	});
	$('#dateTale').focusin(function() {
		dateTale = $('#dateTale').val();
	});
	$('#filtrar').click(function() {
		const numMaximoDePesquisa = 60;
		if (($('#dateHead').val() != "") && ($('#dateTale').val() != "")) {
			if (($('#dateHead').val() != dateHead) || ($('#dateTale').val() != dateTale)) {
				if (moment($('#dateTale').val().split('/').reverse().join('-')).isAfter(moment(),'day')) {
					alert("AVISO! Favor, não informar data futura.");
				} else if (moment($('#dateTale').val().split('/').reverse().join('-')).isBefore($('#dateHead').val().split('/').reverse().join('-'))) {
					alert("As datas informadas estão inválidas!\nA data de inicio deve ser anterior ou igual a data de fim da pesquisa.");
				}else if (moment($('#dateTale').val().split('/').reverse().join('-')).diff(moment($('#dateHead').val().split('/').reverse().join('-')), 'days') > numMaximoDePesquisa){
					alert("O número máximo de "+numMaximoDePesquisa+" dias de pesquisa foi excedido.\nFavor adicionar uma data válida para a pesquisa.");
				}else{
					$('#buscando').show(100, function() {
						$('#lineChart').slideUp(1000, function() {
							$('#canvas').empty();
							ordens($('#dateHead').val(), $('#dateTale').val());
						});
					});
				}
				dateHead = $('#dateHead').val();
				dateTale = $('#dateTale').val();
			}
		}
	});
	
	$('.dataJS').mask("99/99/9999");
	$(function() {
	    $('.dataJS').datepicker();
	});
	$(".dataJS").datepicker({
	    dateFormat: 'dd/mm/yy',
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    monthNames: ['Janeiro de','Fevereiro de','Março de','Abril de','Maio de','Junho de','Julho de','Agosto de','Setembro de','Outubro de','Novembro de','Dezembro de'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    nextText: 'Próximo',
	    prevText: 'Anterior'
	});
});