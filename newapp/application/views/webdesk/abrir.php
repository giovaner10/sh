<form action="<?php echo site_url('webdesk/abrir_ticket') ?>" method="post" enctype="multipart/form-data" class="form-inline" id="form-abrir-ticket">
	
	<div class="control-form">
		<div class="controls">
			<select name="id_cliente" class="input-block-level">
				<option value="">Selecione Cliente</option>
				<?php if ($clientes): ?>
					<?php foreach ($clientes as $cliente): ?>
						<option value="<?php echo $cliente->id ?>"><?php echo $cliente->nome ?></option>
					<?php endforeach ?>
				<?php endif ?>
			</select>
			<input type="hidden" name="nome_cliente">
		</div>
	</div>

	<div class="control-form">
		<div class="controls">
			<select name="id_usuario" class="input-block-level" disabled>
				<option value="">...</option>
			</select>
			<input type="hidden" name="nome_usuario">
		</div>
	</div>

	<div class="control-form">
		<div class="controls">
			<input type="text" name="assunto" value="" placeholder="Assunto" class="input-block-level">
		</div>
	</div>

	<div class="control-form">
		<div class="controls">
			<select name="departamento" class="input-block-level">
				<option value="">Selecione Departamento</option>
				<option value="Financeiro">Financeiro</option>
				<option value="Comercial">Comercial</option>
				<option value="Suporte">Suporte</option>
			</select>
		</div>
	</div>

	<div class="control-form">
		<div class="controls">
			<select name="prioridade" class="input-block-level">
				<option value="">Selecione Prioridade</option>
				<option value="1">Baixa</option>
				<option value="2">Média</option>
				<option value="3">Alta</option>
			</select>
		</div>
	</div>

	<div class="control-form">
		<div class="controls">
			<textarea name="mensagem" rows="7" class="input-block-level" placeholder="Mensagem"></textarea>
		</div>
	</div>

	<div class="control-form">
		<div class="controls">
			<input type="file" name="arquivo" class="filestyle" data-classButton="btn" data-input="true">
		</div>
	</div>

</form>

<script type="text/javascript" src="<?php echo base_url('media/js/bootstrap-filestyle.min.js') ?>"></script>

<script>
	$(function(){

		// Envia o formulário para abertura do ticket
		$(document).on('click', '#enviar-form-abrir-ticket', function(){
			$('#form-abrir-ticket').ajaxSubmit({
				dataType: 'json',
				success: function(res){
					if (res.success) {
						$('#modal-ticket').find('.modal-body').prepend('<div class="alert alert-success"><p>'+ res.msg +'</p></div>');
					} else {
						$('#modal-ticket').find('.modal-body').prepend('<div class="alert alert-error"><p>'+ res.msg +'</p></div>');
					};
				}
			});
		});

		// Popula o combobox de usuarios dependendo do cliente selecionado.
		$('[name="id_cliente"]').on('change', function(){

			var texto = $(this).find('option:selected').text();
			$('[name="nome_cliente"]').val(texto != 'Selecione Cliente' ? texto : '');

			$('[name="id_usuario"]').html('<option value="">Carregando...</option>').prop('disabled', true);

			$.get('<?php echo site_url('clientes/get_usuarios') ?>', {id: $(this).val()}, function(res){

				if (res.length) {

					$('[name="id_usuario"]').html('<option value="">Selecione</option>').prop('disabled', false);

					$.map(res, function(el){
						$('[name="id_usuario"]').append('<option value="'+el.id+'">'+el.nome+'</option>');
					});
				};

			}, 'json');

		});

		// Popula o campo do id do usuario dependendo do usuario selecionado
		$('[name="id_usuario"]').on('change', function(){

			var texto = $(this).find('option:selected').text();
			$('[name="nome_usuario"]').val(texto != '...' && texto != 'Carregando...' ? texto : '');

		});

	});
</script>