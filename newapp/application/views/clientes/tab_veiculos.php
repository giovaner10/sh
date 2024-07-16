<div class="row-fluid" style="margin-bottom: 10px;">
	<div class="span6">
		<span class="label label-info"><i class="icon-info-sign icon-white"></i>
			<?php echo $total_gestor?> veículo(s) encontrado(s) no Gestor</span>
	</div>

	<div class="hidden-print">
		<button class="btn btn-info pull-right" style="margin-right: 5px;" type="button" onclick="imprimir();">Imprimir</button>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<table class="table table-bordered">
			<thead>
				<th class="span2">Nº</th>
				<th class="span2">#ID</th>
				<th class="span5">Veículo</th>
				<th class="span3">Placa</th>
				<th class="span3">Serial</th>
				<th class="span3">Prefixo</th>
				<th>Contrato</th>
				<th>Administrar</th>
			</thead>
			<tbody>
				<?php if(count($veiculos['dados']) > 0):?>
					<?php foreach($veiculos['dados'] as $key => $veiculo):?>
							<?php if (isset($veiculo->code)): ?>
							<tr>
								<td><?php echo $key+1?></td>
								<td><?php echo $veiculo->code?></td>
								<td><?php echo $veiculo->veiculo?></td>
								<td><?php echo $veiculo->placa?></td>
								<td><?php echo $veiculo->serial?></td>
								<td><?php echo $veiculo->prefixo_veiculo!= '' ?$m =$veiculo->prefixo_veiculo:'';?></td>
								<td><?php echo $veiculo->contrato_veiculo?></td>
								<td><a
									href="<?php echo site_url('cadastros/view_veiculo/'.$veiculo->code)?>" title="<?php echo lang('visualizar')?>"
									class="btn btn-primary btn-mini"><i class="icon-home icon-white"></i></a>
								</td>
							</tr>
						<?php endif; ?>
					<?php endforeach;?>
				<?php else:?>
				<tr>
					<td colspan="5">Nenhum veículo encontrado no gestor desse cliente.</td>
				</tr>
				<?php endif;?>
			</tbody>
		</table>
	</div>

</div>

<script type="text/javascript">
	function imprimir(){
	    window.print();
	}
</script>
