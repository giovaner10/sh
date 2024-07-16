
<div class="alert">
	Tem certeza que deseja retirar essa filial e convertê-la como uma matriz?
</div>


<?php echo form_open('', '', array('id_filial' => $filial->id))?>

<div class="row-fluid">
	<div class="span12">
		<div class="control-group">
			<label class="control-label"><strong>Cliente:</strong></label>
				<?php echo $filial->nome?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">
			<i class="icon-ok icon-white"></i> Salvar
		</button>
		<a onclick="fecharModal('#migra_matriz');" class="btn fechar">Fechar</a>
	</div>
</div>

<?php echo form_close()?>