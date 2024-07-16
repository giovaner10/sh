<?php if ($msg): ?>
<div class="alert alert-error">
	<?php echo $msg ?>
</div>
<?php endif; ?>

<table class="table">
	<tbody>
		<?php if (count($relatorio)): $total_contratos = 0; $total_veiculos = 0; $total_mensalidades = 0;?>
			<?php foreach ($relatorio as $cliente => $contratos) : ?>
				<?php 
					$totais = 
						function($contratos, $tipo) { 
							$total = array('veiculos' => 0, 'contratos' => 0);
							foreach ($contratos as $contrato) 
								{
									$total['contratos'] += ($contrato->valor_mensal * 
											   $contrato->quantidade_veiculos);

									$total['veiculos'] += $contrato->quantidade_veiculos;
									$total['id_cliente'] = $contrato->id_cliente;

								}

							return $total[$tipo];
						}
				?>
				<?php
					$total_contratos += count($contratos); 
					$total_mensalidades += $totais($contratos, 'contratos');
					$total_veiculos += $totais($contratos, 'veiculos');
				?>
				<tr style="background-color: #f5f5f5">
					<td class="span6"><b>Cliente:</b> <?php echo $cliente ?></td>
					<td><b>Contratos:</b> <?php echo count($contratos) ?></td>
					<td><b>Veículos no Contrato:</b> <?php echo $totais($contratos, 'veiculos'); ?> &nbsp&nbsp&nbsp&nbsp&nbsp <b>Veículos no Gestor:</b> <button class="btn btn-mini btn-info" data-id="<?php echo $totais($contratos, 'id_cliente');?>"><i class="fa fa-car"></i></button></td>
					<td><b>Total Mensalidades:</b> R$ <?php echo number_format($totais($contratos, 'contratos'), 2, ',', '.') ?></td>
				</tr>
				<?php if (count($contratos)) : ?>
				<tr>
					<td colspan="4">
						<div class="span12">
							<table class="table">
								<thead>
									<th class="span1">ID</th>
									<th class="span1">Veículos</th>
									<th class="span2">Mens. veículo</th>
									<th class="span2">Valor Total Mensal.</th>
									<th class="span2">Adesão</th>
									<th class="span5">Vendedor</th>
									<th class="span1">Data Contrato</th>
									<th class="span2">Prazo</th>
									<th class="span1">Status</th>
									<th class="span2">Valor Total Contrato.</th>
									
								</thead>
								<tbody>
								<?php foreach ($contratos as $ctr) : ?>
									<tr>
										<td><?php echo $ctr->id ?></td>
										<td><?php echo $ctr->quantidade_veiculos ?></td>
										<td>R$ <?php echo number_format($ctr->valor_mensal, 2, ',', '.') ?></td>
										<td>R$ <?php echo number_format(
													($ctr->valor_mensal * $ctr->quantidade_veiculos), 2, ',', '.') ?></td>
										<td>R$ <?php echo number_format($ctr->valor_instalacao, 2, ',', '.') ?></td>
										<td><?php echo $ctr->vendedor ?></td>
										<td><?php echo data_for_humans($ctr->data_contrato) ?></td>
										<td><?php echo $ctr->meses ?> meses</td>
										<td><?php echo show_status_contrato($ctr->status) ?></td>
										<td>R$ <?php echo number_format(
													($ctr->valor_instalacao +
													(($ctr->valor_mensal * $ctr->quantidade_veiculos) *
													  $ctr->meses)), 2, ',', '.') ?></td>
										
									</tr>
								<?php endforeach; ?>
								</tbody>
								
							</table>

						</div>
					</td>
				</tr>
				<?php endif; ?>
			<?php endforeach ?>
		<?php endif; ?>
	</tbody>	
</table>
<table class="table">
	<tr style="background-color: #f5f5f5">
		<td><b>Total de Contratos:</b> <?php echo $total_contratos; ?></td>
		<td><b>Total de Veículos:</b> <?php echo $total_veiculos; ?></td>
		<td><b>Total de Mensalidades:</b> R$ <?php echo number_format($total_mensalidades, 2, ',', '.') ?></td>
		<td><b>Ticket Médio:</b> R$ <?php $ticket = $total_mensalidades/$total_veiculos; echo number_format($ticket, 2, ',', '.') ?></td>
	</tr>
</table>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/modules/relatorio.js') ?>"></script>

<script type="text/javascript">
	$(document).ready(function(){

		$('.btn-mini').on('click', function(e) {
			var id = $(this).data('id');
			var text = $(this);
			var url = "<?php echo base_url('index.php/relatorios/veiculos_gestor');?>";
			$(text).find('i').removeClass('fa fa-car').addClass('fa fa-spinner fa-spin');
			$.post(url, {id: id}, function(resultado) {
				$(text).removeClass('fa fa-spinner fa-spin').html(resultado);
			});
		});

	});

</script>