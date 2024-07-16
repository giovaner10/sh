


<div class="modal-content">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true"></button>
		<h3 id="myModalLabel1">Add Taxas</h3>
  </div>
  <div class="modal-body">

	<?php echo form_open('faturas/form_add_item_contrato/'.$id_fatura)?>
		<div class="row">
			<div class="col-md-12 ">
				<div class="form-group">
					<label class="form-label">Escolha um contrato:</label> 
					<select name="id_contrato" required class="m-wrap">
						<option value="">Lista c/ Contratos</option>
						<?php if(count($contratos) > 0):?>
						<?php foreach ($contratos as $contrato):?>
						<option value="<?php echo $contrato->id?>">
							<?php echo $contrato->id?>
						</option>
						<?php endforeach;?>
						<?php else:?>
						<option value="">Nenhum contrato ativo no cliente</option>
						<?php endif;?>
					</select>
				</div>
			</div>
			<!--/span-->
		</div>
		<div class="row">
			<div class="col-md-12 ">
				<label class="form-label">Item do Contrato:</label>
				<div class="form-group">
					<label class="radio inline"> 
						<input type="radio" name="item"
						value="mensalidade" required /> Mensalidade
					</label> 
					<label class="radio inline"> 
						<input type="radio" name="item"
						value="adesao" required /> Adesão
					</label>
				</div>
			</div>
			<!--/span-->
		</div>

		<div class="row">
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="icon-plus icon-white"></i> Adicionar Item
				</button>
				<a onclick="fecharModal('#add_item_contrato');" class="btn fechar">Fechar</a>
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
