<?php echo form_open('faturas/form_baixa_manual/'.$id_fatura, '', array('id_fatura' => $id_fatura))?>
<div class="alert alert-danger">
	<strong>Deseja confirmar o pagamento da fatura?</strong>

</div>
<div class="row-fluid">
	<div class="span12 ">
		<div class="control-group">
			<label class="control-label">Valor do Pagamento:</label>
			<input type="text" name="valor_pagto" class="span3 money" required />
		</div>
	</div>
	<!--/span-->
</div>
<div class="row-fluid">
	<div class="span12 ">
		<div class="control-group">
			<label class="control-label">Escolha uma forma de pagamento:</label>
			<label class="radio inline">
				<input type="radio" name="f_pagamento" value="28629x_bb" required> 28629-x (BB)
			</label>
			<label class="radio inline">
				<input type="radio" name="f_pagamento" value="116394_bradesco" required> 11639-4 (Bradesco)
			</label>
			<label class="radio inline">
				<input type="radio" name="f_pagamento" value="93742_bradesco" required> 9374-2 (Bradesco)
			</label>
			<label class="radio inline">
				<input type="radio" name="f_pagamento" value="cartao" required> Cartão Crédito
			</label>
			<label class="radio inline">
				<input type="radio" name="f_pagamento" value="393215_noriobb" required> 39321-5 (BB Norio)
			</label>
		</div>
	</div>
	<!--/span-->
</div>
<div class="row-fluid">
	<div class="span12 ">
		<div class="control-group">
			<label class="control-label">Digite a senha para confirmar:</label> <input
				name="senha_exclusao" size="16" type="password" required />

		</div>
	</div>
	<!--/span-->
</div>


<div class="row-fluid">
	<div class="form-actions">
		<button type="submit" class="btn btn-success">
			<i class="icon-ok icon-white"></i> Confirmar Pagamento
		</button>
		<a onclick="fecharModal('#baixa_fatura');" class="btn fechar">Fechar</a>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
$(document).ready(function(){

	$('.money').priceFormat({
		prefix: '',
	    thousandsSeparator: ''
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
