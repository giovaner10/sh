$(document).ready(function() {

	$('#ligar').click(function() { $('#activity').attr('placeholder', 'Ligar').val(''); });

	$('#reuniao').click(function() { $('#activity').attr('placeholder', 'Reunião').val(''); });

	$('#tarefa').click(function() { $('#activity').attr('placeholder', 'Tarefa').val(''); });

	$('#prazo').click(function() { $('#activity').attr('placeholder', 'Prazo').val(''); });

	$('#almoco').click(function() { $('#activity').attr('placeholder', 'Almoço').val(''); });

	$('#cancelar_agend').click(function() {
		$('#activity').attr('placeholder', 'Digite aqui uma atividade');
		$('#activity').val('');
		$('#date').val(null);
		$('#hour').val(null);
		$('#duration').val(null);
		$('#textarea').val(null);
		$('#realized').prop("checked", false);
	});

	$('#save_agend').click(function() {
		let ids = $('#save_agend').attr('data-id');
		let idh = $('#h4-title').attr('data-id');
		let url = idh ? dic['base_url']+'index.php/gestor_vendas/addAgend' : dic['base_url']+'index.php/gestor_vendas/addAgend';
		$.post(url, {
			id: ids ? ids : idh,
			type: $('#activity').attr('placeholder'),
			activity: $('#activity').val(),
			date: $('#date').val(),
			hour: $('#hour').val(),
			duration: $('#duration').val(),
			notation: $('#textarea').val(),
			realized: $('#realized:checked').val() == 'on' ? 1 : 0
		}, function(callback) {
			console.log(callback);
		}).done(function() {
			alert( "second success" );
			$('#addAtividadeModal').modal('toggle');
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
		});
	});

	$(document).on('click', '.agend_id', function() {
		let boo = $(this).prop('checked');
		let url = $('#h4-title').attr('data-id') ? dic['base_url']+'index.php/gestor_vendas/checkedAgend' : dic['base_url']+'index.php/gestor_vendas/checkedAgend';
		boo ? $(this).attr('checked', 'checked') : $(this).removeAttr('checked');
		$.post(url, {
			id: $(this).attr('data-agend-id'),
			boo: boo
		}, function(callback) {
			console.log(callback);
		});
	});

	// $('#date').mask('00/00/0000');
	$('#hour').mask('00:00:00');
	$('#duration').mask('00:00:00');
});