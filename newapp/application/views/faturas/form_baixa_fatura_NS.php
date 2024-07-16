


<div class="modal-content">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true"></button>
		<h3 id="myModalLabel1">Baixa Manual</h3>
  </div>
  <div class="modal-body">
  	<?php echo form_open('faturas/form_baixa_manual/'.$id_fatura, '', array('id_fatura' => $id_fatura))?>
		<div class="alert alert-danger">
			<strong>Deseja confirmar o pagamento da fatura?</strong>

		</div>
		<div class="row">
			<div class="col-md-12 ">
				<div class="form-group">
					<label class="form-label">Valor do Pagamento:</label>
					<input type="text" name="valor_pagto" class="money form-control" required />
				</div>
			</div>
			<!--/span-->
		</div>
		<div class="row-fluid">
			<div class="col-md-12 ">
				<div class="form-group">
					<label class="form-label">Escolha uma forma de pagamento:</label>
					<label class="radio inline">
						<input type="radio" name="f_pagamento" value="28629x_bb" required class="form-control"> 28629-x (BB)
					</label>
					<label class="radio inline">
						<input type="radio" name="f_pagamento" value="116394_bradesco" required class="form-control"> 11639-4 (Bradesco)
					</label>
					<label class="radio inline">
						<input type="radio" name="f_pagamento" value="93742_bradesco" required class="form-control"> 9374-2 (Bradesco)
					</label>
					<label class="radio inline">
						<input type="radio" name="f_pagamento" value="cartao" required class="form-control"> Cartão Crédito
					</label>
					<label class="radio inline">
						<input type="radio" name="f_pagamento" value="393215_noriobb" required class="form-control"> 39321-5 (BB Norio)
					</label>
				</div>
			</div>
			<!--/span-->
		</div>
		<div class="row">
			<div class="col-md-12 ">
				<div class="form-group">
					<label class="form-label">Digite a senha para confirmar:</label> <input
						name="senha_exclusao" size="16" type="password" required class="form-control"/>

				</div>
			</div>
			<!--/span-->
		</div>


		<div class="row">
			<div class="form-actions">
				<button type="submit" class="btn btn-success">
					<i class="icon-ok icon-white"></i> Confirmar Pagamento
				</button>
				<a onclick="fecharModal('#baixa_fatura');" class="btn fechar">Fechar</a>
			</div>
		</div>
	<?php echo form_close()?>
  </div>
  <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>


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
