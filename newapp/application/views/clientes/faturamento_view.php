<h3>Faturamento por disponibilidade - <?=$cliente->nome?></h3>
<hr>

<div class="well well-small">
	<?php echo form_open('')?>
	<div class="span3 input-prepend input-append">
		
		<span class="add-on"><i class="icon-calendar"></i> </span> <input
			type="text" name="dt_ini" class="input-small data"
			placeholder="Data Início" autocomplete="off" id="dp1"
			value="<?php echo $this->input->post('dt_ini') ?>" required /> <span
			class="add-on"><i class="icon-calendar"></i> </span> <input
			type="text" name="dt_fim" class="input-small data"
			placeholder="Data Fim" autocomplete="off" id="dp2"
			value="<?php echo $this->input->post('dt_fim') ?>" required />
	</div>
	
	<div class="span2">
		<button type="submit" class="btn">
			<i class="icon-list-alt"></i> Enviar
		</button>
		<?php if($this->input->post()):?>
		<a href="javascript:window.print();" class="btn btn-primary"
			title="Imprimir"> <i class="icon-print icon-white"></i>
		</a>
		<?php endif;?>
	</div>
	<?php echo form_close()?>
	<div class="clearfix"></div>
</div>

<?php if (count($consumo)): ?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th class="span3">Veículos: <?=$resumo['veiculos']?></th>
			<th class="span3">Valor Total: R$<?=number_format($resumo['total'], 2, ',', '.')?></th>
			<th  class="span4">Contratos: <?=count($resumo['contratos'])?></th>	
		</tr>
		<tr>
			<th  class="span4">Placa</th>
			<th  class="span4">Valor</th>
			<th  class="span4">Datas</th>	
		</tr>
		
	</thead>
	<tbody>
		<?php foreach ($consumo as $veiculo):?>
			<tr>
				<td class="span4"><b><?=$veiculo->placa?></b></td>
				<td class="span4">R$ <?=round($veiculo->total,2)?></td>
				<td  class="span4"><?=$veiculo->datas?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>