<?php echo form_open('', '', array('id_cliente' => $cliente->id, 'code' => $usuario->code))?>

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
			<input type="text" name="nome_usuario" value="<?php echo $usuario->nome_usuario?>"required />
		</div>
	</div>
	<div class="span6">
		<div class="control-group">
			<label class="control-label">Email:</label> 
			<input type="email" name="usuario" value="<?php echo $usuario->usuario?>" required />
		</div>
	</div>

</div>

<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label class="control-label">Senha:</label> 
			<input type="password" name="senha" placeholder="***********" />
		</div>
	</div>
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

</div>
<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label class="control-label">CNPJ_:</label> 
			<input type="text" name="CNPJ_" value="<?php echo $usuario->CNPJ_?>" required />
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">
			<i class="icon-ok icon-white"></i> Atualizar
		</button>
		<a onclick="fecharModal('#view_usuario');" class="btn fechar">Fechar</a>
	</div>
</div>

<?php echo form_close()?>