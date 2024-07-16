<?php echo form_open_multipart('faturas/run_baixa_automatica', array('target' => 'resultado'))?>
<div class="row-fluid">
	<div class="span12 ">
		<div class="control-group">
			<label class="control-label">Escolha o arquivo de retorno para enviar:</label> <input
				type="file" name="arquivo" required>
		</div>
	</div>
	<!--/span-->
</div>
<div class="row-fluid">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">
			<i class="icon-ok icon-white"></i> Baixar Faturas
		</button>
		<a onclick="fecharModal('#baixa_automatica');" class="btn fechar">Fechar</a>
	</div>
</div>
<?php echo form_close()?>
<div class="row-fluid">
	<iframe name="resultado" id="resultado" width="650px" height="250px"></iframe>
</div>
<script type="text/javascript">
$(document).ready(function(){

	$('.datepicker').focus(function(){
	    $(this).calendario({target: $(this)});
	});

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