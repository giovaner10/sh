<style>
	.status_0,
	.status_Conectado {
		background-color: green !important;
	}

	.status_1,
	.status_Desconectado {
		background-color: red !important;
	}

	.label-w {
		margin-left: 10px;
	}
</style>


<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif') ?>" /></div>
<?php if ($posicao) : ?>
	<div class="row-fluid">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Serial</th>
					<th>Ignição</th>
					<th>GPS/GPRS</th>
					<th>Hodômetro</th>
					<th>Vel</th>
					<th>Data GPS</th>
					<th>Data Registro</th>
					<?php if (isset($porcentagemBateria)) echo "<th>Bateria</th>"; ?>

				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class="label label-default"><?php echo $serial ?></span></td>
					<td>
						<?php if ($ignicao == 1) : ?>
							<span title="Ignição ligada" class="label label-success"><i class="fa fa-power-off"></i></span>
						<?php else : ?>
							<span title="Ignição desligada" class="label label-danger"><i class="fa fa-power-off"></i></span>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($gps == 1) : ?>
							<span title="GPS com sinal" class="label label-success"><i class="fa fa-map-marker"></i></span>
						<?php else : ?>
							<span title="GPS sem sinal" class="label label-danger"><i class="fa fa-ban"></i></span>
						<?php endif; ?>

						<?php if ($gprs == 1) : ?>
							<span title="GPRS com sinal" class="label label-success"><i class="fa fa-signal"></i></i></span>
						<?php elseif ($gprs == 0) : ?>
							<span title="GPRS sem sinal " class="label label-danger"><i class="fa fa-ban"></i></span>
						<?php elseif ($gprs == 2) : ?>
							<span title="GPRS sinal de satélite" class="label label-success"><img src="<?php echo base_url('assets/css/icon/src/icon-tower-blue.svg') ?>" /></span>
						<?php endif; ?>
					</td>
					<td>
						<?= $hodometro ?> Km
					</td>
					<td><span class="label label-default"><?php echo $velocidade ?> Km/h</span></td>
					<td><?php echo dh_for_humans($data) ?></td>
					<td><?php echo dh_for_humans($data_sys) ?></td>
					<?php if (isset($porcentagemBateria)) echo "<td>{$bateria} %</td>"; ?>

				</tr>
			</tbody>
		</table>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>
						Endereço:<a href="<?= "https://www.google.com.br/maps/place/" . $latitude . "," . $longitude ?>" target="_blank"> <?php echo $endereco ?></a>
					</th>
				</tr>
			</thead>
		</table>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>
						Latitude: <span class="label label-info" id="latitude"><?php echo $latitude ?></span>
						Longitude: <span class="label label-info" id="longitude"><?php echo $longitude ?></span>
					</th>
				</tr>
			</thead>
		</table>
		<div class="row">
			<hr style="margin: 0;">
			<div class="col-lg-12 justify-items-center">
				<h4 class="subtitle">Localização</h4>

				<div class="col-lg-12" style="position: relative; margin-bottom: 10px; padding: 0px;">
					<div id="loadingMessage" class="loadingMessage" style="display: none;">
						<b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
					</div>
					<div id="mapaDadosPartidaChegada" style="width:100%; height:580px; border-radius: 9px; z-index: 1;"></div>
				</div>
			</div>
		</div>
		<?php if (substr($serial, 0, 1) == 'W') : ?>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>
							Data Comunicação: <span class="label label-info"><?php echo dh_for_humans($data_sys) ?></span>
						</th>
					</tr>
				</thead>
			</table>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>
							Status:
							<span class="label label-w status_<?= $cinta ?>">CINTA</span>
							<span class="label label-w status_<?= $case ?>">CASE</span>
							<span class="label label-w status_<?= $carregador ?>">CARREGADOR: <?= $carregador ?></span>
							<span class="label label-w label-success">FW: <?= $fw ?></span>
							<span class="label label-w label-success">OP.: <?= $operadora ?></span>
							<span class="label label-w label-success">GSM: <?= $sinalGSM ?></span>
							<span class="label label-w label-default">BATERIA: <?= $bateria ?>%</span>
						</th>
					</tr>
				</thead>
			</table>
		<?php endif; ?>
	</div>
	<!-- <div class="row-fluid" style="margin-top: 10px;">
		<?php if ($fw !== '342' && substr($serial, 0, 1) == 'W') : ?>
			<div>
				<button class="btn-small btn-success btn" id="btn_atualizar" data-serial="<?= $serial ?>" data-fw_atual="<?= $fw ?>">Atualizar Firmware</button>
			</div>
			<div style="max-height: 200px; overflow: auto;">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Atualização</th>
							<th>Usuário</th>
							<th>Data Envio</th>
							<th>Data Processamento</th>
							<th>Data Confirmação</th>
						</tr>
					</thead>
					<tbody id="tbody_atualizacoes">
						<?php $c_status = 'false';
						foreach ($comandos as $c) : if ($c['data_confirma'] == ' - ') {
								$c_status = 'true';
							} ?>
							<tr>
								<td><?= $c['comando'] ?></td>
								<td><?= $c['usuario'] ?></td>
								<td><?= $c['data_envio'] ?></td>
								<td><?= $c['data_processa'] ?></td>
								<td><?= $c['data_confirma'] ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div> -->
<?php else : ?>
	<div>
		Veículo sem informação de posição!
	</div>

<?php endif; ?>

<script>
	$('#btn_atualizar').on('click', function(a) {
		let button = $(this);
		let serial = button.data('serial');
		let versao_atual = button.data('fw_atual');
		let status_c = '<?= $c_status ?>';
		let carregador = '<?= $carregador ?>';

		if (status_c == 'true') {
			alert('Existe um comando de atualização pendente. Aguarde até que o mesmo seja processado e tente novamente!');
			return false;
		} else if (carregador == 'Desconectado') {
			alert('O carregador deve estar conectado, para que seja possível iniciar a atualização!');
			return false;
		}

		// Desativa o botão
		button.attr('disabled', true).html('Enviando...');

		$.ajax({
			url: '<?= site_url('equipamentos/atualizar_fw_trz') ?>',
			data: {
				atual: versao_atual,
				serial: serial
			},
			type: 'POST',
			dataType: 'JSON'
		}).then(callback => {
			button.removeAttr('disabled').html('Atualizar Firmware');

			if (callback.status === true) {
				<?php $c_status = true; ?>

				$('#tbody_atualizacoes').prepend(`
					<tr>
						<td>${ versao_atual } >>> ${ callback.new_fw }</td>
						<td><?= $this->auth->get_login_dados('email') ?></td>
						<td><?= date('d/m/Y H:i:s') ?></td>
						<td> - </td>
						<td> - </td>
					</tr>
				`);
			} else {
				alert('Não foi possível realizar a atualização.');
			}

		}, err => {
			alert('Não foi possível realizar atualização. Por favor tente novamente mais tarde! ');
			button.removeAttr('disabled').html('Atualizar Firmware');
		});
	});

	$('.modal-dialog').css("width", "800px");
</script>