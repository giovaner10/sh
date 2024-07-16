<div class="modal-content">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true"></button>
	<h3 id="myModalLabel1">Alterar Data Emissão</h3>
  </div>
  <div class="modal-body">
	<?php echo form_open('faturas/form_update_emissao/'.$id_fatura, '', array('id_fatura' => $id_fatura))?>
		<div class="row">
			<div class="col-md-12 ">
				<div class="form-group">
					<label class="form-label">Digite uma nova de emissão:</label> 
					<input name="data_emissao form-control" class="data" size="16" type="date" value="" required />
					
				</div>
			</div>
			<!--/span-->
		</div>

		<div class="row">
			<div class="col-md-12 ">
				<div class="form-group">
					<label class="form-label">Digite a senha para confirmar:</label> 
					<input name="senha_exclusao form-control" size="16" type="password" required />
				</div>
			</div>
			<!--/span-->
		</div>


		<div class="row">
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="icon-ok icon-white"></i> Atualizar
				</button>
				<a onclick="fecharModal('#update_emissao');" class="btn fechar">Fechar</a>
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
