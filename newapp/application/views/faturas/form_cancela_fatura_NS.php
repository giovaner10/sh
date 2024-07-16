


<div class="modal-content">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true"></button>
		<h3 id="myModalLabel1">Baixa Manual</h3>
  </div>
  <div class="modal-body">
  <?php echo form_open('faturas/form_cancelar_fatura/'.$id_fatura, '', array('id_fatura' => $id_fatura))?>
	<div class="alert alert-danger">
		<strong>Tem certeza que deseja cancelar esta fatura?</strong>

	</div>
	<div class="row">
		<div class="col-md-12 ">
			<div class="form-group">
				<label class="form-label">Qual o motivo do cancelamento?</label>
				<textarea rows="4" class="col-md-12" name="motivo" required class="form-control"></textarea>

			</div>
		</div>
		<!--/span-->
	</div>
	<div class="row">
		<div class="col-md-12 ">
			<div class="form-group">
				<label class="form-label">Digite a senha para confirmar:</label> <input
					name="senha_exclusao" size="16" type="password" required  class="form-control"/>

			</div>
		</div>
		<!--/span-->
	</div>


	<div class="row">
		<div class="form-actions">
			<button type="submit" class="btn btn-danger">
				<i class="icon-remove icon-white"></i> Cancelar Fatura
			</button>
			<a onclick="fecharModal('#cancela_fatura');" class="btn fechar">Fechar</a>
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
