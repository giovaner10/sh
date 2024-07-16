<style type="text/css">
	tbody tr td {
		text-align: center;
	}
</style>
<div class="row-fluid">
	<strong>Informações sobre equipamentos.</strong>
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#menu1" data-toggle="tab">Em uso <span class="badge" title="Total em Uso"><?php echo count($eq_instalados); ?></span></a></li>
			<li><a href="#menu2" data-toggle="tab">Retirados <span class="badge" title="Total Retirado"><?php echo count($eq_retirados); ?></a></li>
			<li><a href="#menu3" data-toggle="tab">Disponíveis <span class="badge" title="Total Disponível"><?php echo $count_disponiveis; ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="menu1">
				<?php if ($eq_instalados): ?>
				<table class="table table-striped">
					<thead class="thead-inverse">
						<th class="span1">#</th>
						<th class="span1">Placa</th>
						<th class="span2">Serial</th>
						<th class="span2">Linha</th>
						<th class="span2">CCID</th>
						<th class="span2">Operadora</th>
						<th class="span2">Data de Cadastro</th>
					</thead>
					<tbody>
					<?php $inc=0; 
					foreach($eq_instalados as $equipamento):?>
						<tr>
							<td><?php echo ++$inc ?></td>
							<td><?php echo $equipamento->placa ?></td>
							<td><?php echo $equipamento->serial ?></td>
							<td><?php echo $equipamento->numero ?></td>
							<td><?php echo $equipamento->ccid ?></td>
							<td><?php echo $equipamento->operadora ?></td>
							<td><?php echo $equipamento->data_cadastro ?></td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
				<?php endif ?>
			</div>

			<div class="tab-pane" id="menu2">
				<?php if ($eq_retirados) { ?>
					<table class="table table-striped">
						<thead class="thead-inverse">
							<th class="span1">#</th>
							<th class="span2">Serial</th>
							<th class="span2">Placa</th>
							<th class="span2">Data de retirada</th>
							<th class="span2">Status</th>
						</thead>
						<tbody>
						<?php $inc=0;
						foreach($eq_retirados as $equipamento): ?>
							<tr>
								<td><?php echo ++$inc ?></td>
								<td><?php echo $equipamento->serial ?></td>
								<td><?php echo $equipamento->placa ?></td>
								<td><?php echo $equipamento->data_retirada ?></td>
								<td><?php echo $equipamento->dataRecebimento != ''?"<i class='fa fa-check-square-o' title='Recebido' style='font-size:18px; color: green'></i>":"<i class='fa fa-exclamation-triangle' title='Entrega Pendente' style='font-size:18px; color: #FFFF00'></i>"; ?>
								</td><i></i>
							</tr>
						<?php endforeach ?>
						</tbody>
					</table>
				<?php } else { ?>
					<p>Não há informações sobre equipamentos retirados para esse cliente.</p>
				<?php } ?>
			</div>

			<div class="tab-pane" id="menu3">
				<?php if ($eq_disponiveis) { ?>
					<table class="table table-striped">
						<thead class="thead-inverse">
							<th class="span1">#</th>
							<th class="span2">Serial</th>
							<th class="span2">Linha</th>
							<th class="span2">CCID</th>
							<th class="span2">Operadora</th>
							<th class="span2">Data de Envio</th>
							<th class="span2">Data de Recimento</th>
							<th class="span2">Status</th>
						</thead>
						<tbody>
						<?php $inc=0;
						foreach($eq_disponiveis as $equipamento): ?>
							<?php if ($equipamento->status != "ativo") { ?>
								<tr>
									<td><?php echo ++$inc ?></td>
									<td><?php echo $equipamento->serial ?></td>
									<td><?php echo $equipamento->numero ?></td>
									<td><?php echo $equipamento->ccid ?></td>
									<td><?php echo $equipamento->operadora ?></td>
									<td><?php echo $equipamento->dataEnvio ?></td>
									<td><?php echo $equipamento->dataRecebimento ?></td>
									<td>
										<?php if ($equipamento->dataRecebimento != '') {
											echo "Disponível no cliente";
										} else {
											echo "Encaminhado ao cliente";
										} ?>
									</td>
								</tr>
							<?php } ?>
						<?php endforeach ?>
						</tbody>
					</table>
				<?php } else { ?>
					<p>Não há informações sobre equipamentos retirados para esse cliente.</p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#abbas li a').click(function(){
		target = this.href;
		$('#tabConteudo .tab-pane').removeClass('active');
		$('#'+target.substr(-5)).addClass('active');
	});
});
</script>