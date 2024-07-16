<?php echo form_open('', 'id=formEditUser', array('id_cliente' => $cliente->id, 'code' => $usuario->code))?>
<div class="row-fluid">
	<div class="span12">
		<div class="control-group">
			<label class="control-label">Cliente:</label> 
			<?php echo $cliente->nome?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label class="control-label">Nome:</label> 
			<input type="text" name="nome_usuario" value="<?php echo $usuario->nome_usuario?>" required />
		</div>
	</div>
	<div class="span6">
		<div class="control-group">
			<label class="control-label">Email:</label> 
			<input type="email" name="usuario" value="<?php echo $usuario->usuario?>"required />
		</div>
	</div>

</div>

<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label class="control-label">Senha:</label> 
			<input type="password" name="senha" placeholder="*********" />
		</div>
	</div>
	<div class="span6">
		<div class="control-group">
			<label class="control-label">CPF:</label> 
			<input type="text" name="cpf" class="cpf" value="<?php echo $usuario->cpf?>" required />
		</div>
	</div>

</div>
<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label class="control-label">Função:</label> 
			<select name="tipo_usuario" required>
				<option value="">Escolha uma opção</option>
				<option value="administrador" <?php echo set_selecionado('administrador', $usuario->tipo_usuario, 'selected')?>>Administração</option>
				<option value="monitoramento" <?php echo set_selecionado('monitoramento', $usuario->tipo_usuario, 'selected')?>>Monitoramento</option>
			</select>
		</div>
		
	</div>
	<div class="span6">
		<div class="control-group">
			<label class="control-label">Tel.:</label> 
			<input type="text" class="tel" name="celular" value="<?php echo $usuario->celular?>" required />
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span6">
        <div class="form-group">
            <label>Grupo: </label>
            <select class="form-control" name="group">
                <?php foreach ($groups as $group): ?>
                    <option value="<?= $group->id; ?>"><?= $group->nome; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
	</div>
</div>

<div class="row-fluid">
	<div class="span6">
		<a class="btn btn-primary submit_editUser">
			<i class="icon-ok icon-white"></i> Salvar
		</a>
	</div>
</div>

<?php echo form_close()?>

<script type="text/javascript">
	$('.submit_editUser').click(function () {
		var button_editUser = $(this);
		button_editUser.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
		var dados_edit = $('#formEditUser').serialize();

		$.ajax({
			url: '<?= site_url('usuarios_gestor/ajaxEditUser') ?>',
			type: 'POST',
			dataType: 'json',
			data: dados_edit,
			success: function (callback) {
				if (callback.status == 'OK') {
					button_editUser.attr('disabled', 'true').removeClass('btn-primary submit_editUser').addClass('btn-success').html('<i class="fa fa-check"></i> Editado :)');
				} else {
					button_editUser.removeAttr('disabled').html('Salvar');
					alert(callback.msg);
				}
			},
			error: function (e){
				button_editUser.attr('disabled', 'false').html('<i class="icon-ok icon-white"></i> Salvar');
				alert('Erro ao salvar, tente novamente!');
			}
		});
	});
</script>