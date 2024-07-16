var amount = function() {
	$.get('amount', function(json) {
		var obj = JSON.parse(json);
		$('#support').text(obj.support);
		$('#commercial').text(obj.commercial);
		$('#financial').text(obj.financial);
	});
}

var search = function(init=false, end=false) {
	if (init && end) {
		$.getJSON('search', { init: init, end: end }, function(obj) {
			$('#open').text(obj.open);
			$('#progress').text(obj.progress);
			$('#close').text(obj.close);
			var chart = new Chartist.Bar('.ct-chart', {
				labels: ['Respondidos', 'Abertos', 'Concluído'],
				series: [
					obj.progress, obj.open, obj.close
				]
			}, {
				// low: 0,
				// showArea: false,
				// showPoint: false,
				// fullWidth: true,
				distributeSeries: true,
			});
			// chart.on('draw', function(data) {
			// 	if(data.type === 'line' || data.type === 'area' || data.type == 'bar') {
			// 		data.element.animate({
			// 			d: {
			// 				begin: 1000 * data.index,
			// 				dur: 2000,
			// 				from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
			// 				to: data.path.clone().stringify(),
			// 				easing: Chartist.Svg.Easing.easeOutQuint
			// 			}
			// 		});
			// 	}
			// });
		});
	} else if (init) {
		$.getJSON('search', { init: init }, function(obj) {
			$('#open').text(obj.open);
			$('#progress').text(obj.progress);
			$('#close').text(obj.close);
			var chart = new Chartist.Bar('.ct-chart', {
				labels: ['Respondidos', 'Abertos', 'Concluído'],
				series: [
					obj.progress, obj.open, obj.close
				]
			}, {
				// low: 0,
				// showArea: false,
				// showPoint: false,
				// fullWidth: true,
				distributeSeries: true,
			});
			// chart.on('draw', function(data) {
			// 	if(data.type === 'line' || data.type === 'area' || data.type == 'bar') {
			// 		data.element.animate({
			// 			d: {
			// 				begin: 1000 * data.index,
			// 				dur: 2000,
			// 				from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
			// 				to: data.path.clone().stringify(),
			// 				easing: Chartist.Svg.Easing.easeOutQuint
			// 			}
			// 		});
			// 	}
			// });
		});
	} else {
		$.getJSON('search', function(obj) {
			$('#open').text(obj.open);
			$('#progress').text(obj.progress);
			$('#close').text(obj.close);
			var chart = new Chartist.Bar('.ct-chart', {
				labels: ['Respondidos', 'Abertos', 'Concluído'],
				series: [
					obj.progress, obj.open, obj.close
				]
			}, {
				// low: 0,
				// showArea: false,
				// showPoint: false,
				// fullWidth: true,
				distributeSeries: true,
			});
			// chart.on('draw', function(data) {
			// 	if(data.type === 'line' || data.type === 'area' || data.type == 'bar') {
			// 		data.element.animate({
			// 			d: {
			// 				begin: 1000 * data.index,
			// 				dur: 2000,
			// 				from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
			// 				to: data.path.clone().stringify(),
			// 				easing: Chartist.Svg.Easing.easeOutQuint
			// 			}
			// 		});
			// 	}
			// });
		});
	}
}

$( document ).ready(function() {
	$('.calendar').datepicker({
		format: "dd/mm/yyyy",
		language: "pt-BR"
	});
	amount();
	search();

	$('#one').mouseup(function() {
		$('#end').slideUp();
	});

	$('#three').mouseup(function() {
		$('#end').show( "slow" );
	});

	$(document).on('click', '#b', function() {
		$('input[type=radio][name=op]:checked').each(function() {
			switch( $(this).val() ) {
				case '0':
					search( $('#init').val() );
					break;
				case '1':
					search( $('#init').val(), $('#end').val() );
					break;
				default:
					search();
			}

		});
	});
});
