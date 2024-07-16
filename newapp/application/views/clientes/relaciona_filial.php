<?php if ($filial->id_nivel != 0 && $filial->id_nivel != $cliente->id_nivel):?>
	<?php $matriz = $this->cliente->get(array('id' => $filial->id_nivel)) ?>
	<div class="alert alert-error">
		<strong>ATENÇÃO!</strong> Este cliente já é filial da empresa <?php echo $matriz->nome?>.
	</div>
<?php endif;?>

<div class="alert">
	Tem certeza que deseja migrar o cliente como filial de outra empresa?
</div>


<?php echo form_open('', '', array('id_cliente' => $cliente->id,
								'id_filial' => $filial->id,
								'acao' => true
					))?>

<div class="row-fluid">
	<div class="span12">
		<div class="control-group">
			<label class="control-label"><strong>Cliente:</strong></label>
			<?php echo $filial->nome?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="control-group">
			<label class="control-label"><strong>Migrar como filial de:</strong></label>
			<?php echo $cliente->nome?>
		</div>
	</div>
</div>


<div class="row-fluid">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">
			<i class="icon-ok icon-white"></i> Salvar
		</button>
		<a onclick="fecharModal('#add_filial');" class="btn fechar">Fechar</a>
	</div>
</div>

<?php echo form_close()?>