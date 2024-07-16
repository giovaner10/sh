<?php echo form_open('faturas/form_remove_item/'.$id_item.'/'.$id_fatura, '', array('id_item' => $id_item))?>
<div class="alert alert-danger">
  <strong>CUIDADO!</strong> Tem certeza que deseja remover este item da fatura?
  
</div>
<div class="row-fluid">
	<div class="span12 ">
		<div class="control-group">
			<label class="control-label">Digite a senha para remover:</label> 
			<input name="senha_exclusao" size="16" type="password" required />
			
		</div>
	</div>
	<!--/span-->
</div>

<div class="row-fluid">
	<div class="form-actions">
		<button type="submit" class="btn btn-danger">
			<i class="icon-remove icon-white"></i> Remover
		</button>
		<a onclick="fecharModal('#remove_item');" class="btn fechar">Fechar</a>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
$(document).ready(function(){

	$('.data').mask('99/99/9999');
	$('.tel').mask('(99) 9999-9999');
	$('.hora').mask('99:99:99');
	$('.cep').mask('99999-999');
	$('.cpf').mask('999.999.999-99');
	$('.placa').mask('aaa9999');
	$('.mes_ano').mask('99/9999');
	$("#ajax").css('display', 'none');
	
});
</script>
