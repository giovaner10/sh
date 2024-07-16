<?php if($msg != ''):?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>CONCLUIDO!</strong>
	<?php echo $msg?>
</div>
<?php endif;?>
<h3>Relatório Envio de Faturas</h3>
<hr>
<div class="well well-small">
	<?php echo form_open('')?>
	<div class="span5 input-prepend input-append">
		<span class="add-on"><i class="icon-calendar"></i> </span> <input
			type="text" name="dt_ini" class="span2 datepicker "
			placeholder="Data Início" autocomplete="off" id="dp1"
			value="<?php echo $this->input->post('dt_ini') ?>" required /> <span
			class="add-on"><i class="icon-calendar"></i> </span> <input
			type="text" name="dt_fim" class="span2 datepicker" 
			placeholder="Data Fim" autocomplete="off" id="dp2"
			value="<?php echo $this->input->post('dt_fim') ?>" required /> 
	</div>
	<div class="span3">
		<label
			class="checkbox inline">
		<input type="checkbox" id="inlineCheckbox1" value="erro" <?php echo set_checkbox('erro_envio', 'erro')?> name="erro_envio"> Exibir apenas Não Enviados
		</label>
	</div>
	<div class="span2">
		<button type="submit" class="btn">
			<i class="icon-filter"></i> Filtrar
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
<table class="table table-hover">
	<thead>
		<th class="span1">Número</th>
		<th class="span4">Cliente</th>
		<th class="span2">Data Envio</th>
		<th class="span2">Status</th>
		<th class="span1">Obs.</th>
	</thead>
	<tbody>
		<?php if($faturas):?>
		<?php foreach ($faturas as $fatura):?>
		<tr class="<?php echo $fatura->status_envio == 'pendente' && $fatura->msg_envio != '' ? 'error' : ''?>">
			<td><?php echo $fatura->id_fatura ?></td>
			<td><?php echo $fatura->nome?></td>
			<td><?php echo dh_for_humans($fatura->dhcad_envio) ?></td>
			<td><?php echo $fatura->status_envio?></td>
			<td><?php echo $fatura->msg_envio?></td>
		</tr>
		<?php endforeach;?>
		<?php else:?>
		<tr>
			<td colspan="8">Nenhum item encontrado para este filtro.</td>
		</tr>
		<?php endif;?>
	</tbody>
</table>

