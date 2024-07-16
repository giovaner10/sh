$(document).ready( function() {

	$.getJSON('https://gestor.showtecnologia.com/show/m2m/index.php/chip/amountActive', function(data) {
		var chart = new Chartist.Pie('#chart-home', {
			series: [{
				name: 'done',
				className: 'ct-done',
				value: data.active
			}, {
				name: 'outstanding',
				className: 'ct-outstanding',
				value: data.deactive
			}]
		}, {
			donut: true,
			height: 300,
			labelInterpolationFnc: function(value) {
				var total = chart.data.series.reduce(function(prev, series) {
					return prev + series.value;
				}, 0);
				if (data.active != 0 && data.total != 0)
					return Math.round(data.active / data.total * 100) + '%';
				else
					return '0 %';
			}
		});

		chart.on('draw', function(ctx) {
			if(ctx.type === 'label') {

				if(ctx.index === 0) {
					ctx.element.attr({
						dx: ctx.element.root().width() / 2,
						dy: ctx.element.root().height() / 2
					});
				} else {
					ctx.element.remove();
				}
			}
		});
	});

	$.getJSON('https://gestor.showtecnologia.com/show/m2m/index.php/chip/recent', function(data) {
		$.each(data, function(index, obj) {
			obj.date = obj.date.split(' ');
			$('#recent').append(
					'<li><i class="fa fa-check"></i><div><h4>'+ obj.number +'</h4></div> <span><small> '+ obj.date[0] +'</small></span> </li>'
				);
		});
	});

	$.getJSON('https://gestor.showtecnologia.com/show/m2m/index.php/chip/amount', function(data) {
		$('#vivo').text(data.vivo);
		$('#claro').text(data.claro);
		$('#oi').text(data.oi);
		$('#voda').text(data.voda);
	});

	var boo = true;

	// $(document).on('click', '#chipsAll', function() {
		if (boo) {
			boo = false;
			$('#tbody').html('');
			$.getJSON('https://gestor.showtecnologia.com/show/m2m/index.php/chip/getAll', function(data) {
				$('#tbody').html('');
				$('#myTable').dynatable({
					dataset: {
						records: data
					}
				});
				// $.each(data, function(index, obj) {
				// 	$('#tbody').append(
				// 		['<tr>',
				// 			'<td>'+ obj.operator +'</td>',
				// 			'<td>'+ obj.number +'<td>',
				// 			'<td>'+ obj.plan +'</td>',
				// 			'<td>'+ obj.type +'</td>',
				// 			'<td>'+ (obj.status ? 'Ativo' : 'Desativado') +'</td>',
				// 			'<td>'+ obj.date +'</td>',
				// 		'</tr>'].join('')
				// 	);
				// });
			});
		}
	//});

	$(document).on('click', '#chipsTask', function() {
		boo = true;
	});

});
