<br>
<meta name="viewport" content="initial-scale=1.0">
<meta charset="utf-8">
<?php if(!isset($retorno)): ?>

<?php elseif($retorno): ?>
	<script>
		var aviso = "<?php echo lang('fr2')?>";
		swal({
			title: "Atualizado",
			text: aviso,
			icon: "success",
			button: "OK",
		});
	</script>
<?php else: ?>
	<script>
		var aviso = "<?php echo lang('fr3')?>";
		swal({
			title: "Erro",
			text: aviso,
			icon: "error",
			button: "OK",
		});
	</script>
<?php endif; ?>
<style>
#map {
	height: 100%;
}
html, body {
	height: 100%;
	margin: 0;
	padding: 0;
}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div id="top_x_div" style="width: 500px; height: 250px;"></div>
		</div>
		<div class="col-md-4">
			<div id="donutchart" style="width: 600px; height: 250px;"></div>
		</div>
		<div class="col-md-4">
			<div id="recebimento" style="width: 500px; height: 250px;"></div>
		</div>
		<div class="modal fade bd-example-modal-lg" id="info_tec" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header" >
						<h5 class="modal-title"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

						<?php echo form_open('', array('class' => 'form-horizontal'))?>
						<?php if (isset($instalador) && count($instalador)): ?>
							<div class="col-md-12">
								<div align="center">
									<label>Meus dados</label>
								</div>
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Dados pessoais</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="endereco-tab" data-toggle="tab" href="#endereco" role="tab" aria-controls="endereco" aria-selected="false">Endereço</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contato</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="bancarios-tab" data-toggle="tab" href="#bancarios" role="tab" aria-controls="bancarios" aria-selected="false">Dados bancários</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="valores-tab" data-toggle="tab" href="#valores" role="tab" aria-controls="valores" aria-selected="false">Meus valores</a>
									</li>
								</ul>
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"><pre></pre>
										<div class="form-row">
											<div class="col-sm-12 mb-1">
												<label>Nome</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-android-person"></i></span>
													</div>
													<input type="text" name="nome" value="<?php echo $instalador->nome?>" class="form-control" >
												</div>
											</div>
											<div class="col-sm-12 mb-1">
												<label>Sobrenome</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-android-person"></i></span>
													</div>
													<input type="text" name="sobrenome" value="<?php echo $instalador->sobrenome ?>" class="form-control">
												</div>
											</div>
											<div class="col-sm-12 mb-1">
												<label>CPF</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-android-person"></i></span>
													</div>
													<input type="text" name="cpf" id="cpff" value="<?php echo $instalador->cpf ?>" class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">
										<pre></pre>
										<div class="form-row">
											<div class="col-sm-12 mb-1">
												<label>Estado</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend estado">
														<span class="input-group-text"><i class="ion-ios-location"></i></span>	
														<select class="custom-select" name="estado" id="estado">
															<option value="AC">Acre</option>
															<option value="AL">Alagoas</option>
															<option value="AM">Amazonas</option>
															<option value="AP">Amapá</option>
															<option value="BA">Bahia</option>
															<option value="CE">Ceará</option>
															<option value="DF">Distrito Federal</option>
															<option value="ES">Espirito Santo</option>
															<option value="GO">Goiás</option>
															<option value="MA">Maranhão</option>
															<option value="MG">Minas Gerais</option>
															<option value="MS">Mato Grosso do Sul</option>
															<option value="MT">Mato Grosso</option>
															<option value="PA">Pará</option>
															<option value="PB">Paraíba</option>
															<option value="PE">Pernambuco</option>
															<option value="PI">Piauí</option>
															<option value="PR">Paraná</option>
															<option value="RJ">Rio de Janeiro</option>
															<option value="RN">Rio Grande do Norte</option>
															<option value="RO">Rondônia</option>
															<option value="RR">Roraima</option>
															<option value="RS">Rio Grande do Sul</option>
															<option value="SC">Santa Catarina</option>
															<option value="SE">Sergipe</option>
															<option value="SP">São Paulo</option>
															<option value="TO">Tocantins</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-sm-12 mb-1">
												<label>Cidade</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-ios-location"></i></span>

														<select class="custom-select" name="cidade" id="cidade">
															<option value="<?php echo $instalador->cidade?>"><?php echo $instalador->cidade?></option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-sm-8 mb-1">
												<label>Endereço</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-ios-home"></i></span>
													</div>
													<input type="text" name="endereco" value="<?php echo $instalador->endereco ?>" class="form-control" >
												</div>
											</div>
											<div class="col-sm-12 mb-1">
												<label>Bairro</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-ios-home"></i></span>
													</div>
													<input type="text" name="bairro" value="<?php echo $instalador->bairro ?>" class="form-control" >
												</div>
											</div>
											<div class="col-sm-12 mb-1">
												<label>Numero</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-ios-home"></i></span>
													</div>
													<input type="text" name="numero" value="<?php echo $instalador->numero ?>" class="form-control" >
												</div>
											</div>
											<div class="col-sm-12 mb-1">
												<label>CEP</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-pound"></i></span>
													</div>
													<input type="text" id="cep" name="cep" value="<?php echo $instalador->cep ?>" class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
										<pre></pre>
										<div class="form-row">
											<div class="col-sm-12 mb-1">
												<label>Telefone</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-android-call"></i></span>
													</div>
													<input type="text" name="telefone" id="telefone" value="<?php echo $instalador->telefone ?>" class="form-control" >
												</div>
											</div>
											<div class="col-sm-12 mb-1">
												<label>Celular</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-android-phone-portrait"></i></span>
													</div>
													<input type="text" name="celular" id="celular" value="<?php echo $instalador->celular ?>" class="form-control" >
												</div>
											</div>
											<div class="col-sm-8 mb-1">
												<label>Email</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-android-mail"></i></span>
													</div>
													<input type="text" name="email" value="<?php echo $instalador->email ?>" class="form-control" >
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="bancarios" role="tabpanel" aria-labelledby="bancarios-tab">
										<pre></pre>
										<div class="form-row">
											<div class="col-sm-12 mb-1">
												<label>Banco</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-cash"></i></span>
													</div>
													<select class="custom-select" id="banco" name="banco">
														<option selected>Banco do Brasil</option>
														<option value="1">Banco do Nordeste</option>
														<option value="2">Banco Bradesco</option>
														<option value="3">Caixa Econômica Federal</option>
														<option value="3">Itaú</option>
														<option value="3">Santander</option>
													</select>
												</div>		
											</div>
											<div class="col-sm-3 mb-1">
												<label>Agencia</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-stats-bars"></i></span>
													</div>
													<input name="agencia" value="<?php echo $instalador->agencia ?>" type="text" class="form-control" >
												</div>
											</div>
											<div class="col-sm-3 mb-1">
												<label>Conta</label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-card"></i></span>
													</div>
													<input type="text" name="agencia" value="<?php echo $instalador->conta ?>" class="form-control" >
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="valores" role="tabpanel" aria-labelledby="valores-tab">
										<pre></pre>
										<div class="form-row">
											<div class="col-sm-3 mb-1">
												<label>Valor Instalação</label>
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-cash"></i></span>
													</div>
													<input type="text" id="money" name="valor_instalacao" value="<?php echo $instalador->valor_instalacao?>" class="form-control" >
												</div>
												<span class="badge badge-pill badge-danger"><?php echo lang('media')?><?php echo round($valores[0]->instalacao, 2);?></span>
											</div>
											<div class="col-sm-3 mb-1">
												<label>Valor Manutenção</label>
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-cash"></i></span>
													</div>
													<input type="text" id="money1" name="valor_instalacao" value="<?php echo $instalador->valor_manutencao?>" class="form-control" >
												</div>
												<span class="badge badge-pill badge-danger"><?php echo lang('media')?><?php echo round($valores[0]->manutencao, 2);?></span>
											</div>
											<div class="col-sm-3 mb-1">
												<label>Valor Retirada</label>
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-cash"></i></span>
													</div>
													<input type="text" id="money2" name="valor_retirada" value="<?php echo $instalador->valor_retirada?>" class="form-control" >
												</div>
												<span class="badge badge-pill badge-danger"><?php echo lang('media')?><?php echo round($valores[0]->retirada, 2);?></span>
											</div>
											<div class="col-sm-5 mb-1">
												<label>Deslocamento para fora da região metropolitana por km</label>
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="ion-cash"></i></span>
													</div>
													<input type="text" id="money3" name="valor_desloc_km" value="<?php echo $instalador->valor_desloc_km?>" class="form-control" >
												</div>
												<span class="badge badge-pill badge-danger"><?php echo lang('media')?><?php echo round($valores[0]->desloc, 2);?></span>
											</div>
										</div>
									</div>
								</div>
								<div class="mb-2" align="right">
									<div class="clearfix"></div>
									<div class="form-actions">
										<input type="hidden" name="id" value="<?php echo $instalador->id?>">
										<button type="submit" class="btn btn-primary bt-salvar-veiculo" id="bt-atualizar-veiculo" class="bt-atualizar-veiculo" data-tipo="atualizar"><?php echo lang('salvar')?></button>
									</div>
								</div>
							</div>
						<?php endif; ?>	
						<?php echo form_close()?>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<pre></pre>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<form>
						<table class="table" id="table" style="font-size: 11px;">
							<thead class="thead-dark">
								<tr>
									<th scope="col">Nº O.S</th>
									<th scope="col">Cliente</th>
									<th scope="col">Endereço</th>
									<th scope="col">Tipo</th>
									<th scope="col">Status</th>
									<th scope="col">Opções</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($ord)){?>
								<?php $abertas = 0;?>
								<?php $fechadas = 0; ?>
								<?php $manutencao = 0;?>
								<?php $troca = 0; ?>
								<?php $retirada = 0;?>
								<?php $instalacao = 0; ?>
								<?php foreach($ord as $key){?>
								<tr>
									<?php $abertas = $key['abertas'] ?>
									<?php $fechadas = $key['fechadas'] ?>
									<?php $manutencao = $key['manutencao'] ?>
									<?php $troca = $key['troca'] ?>
									<?php $retirada = $key['retirada'] ?>
									<?php $instalacao = $key['instalacao'] ?>
									<?php $os_paga = $key['os_paga'] ?>
									<?php $os_n_paga = $key['os_n_paga'] ?>
										
									<th scope="row"><?php echo $key['id'] ?></th>
									<td><?php echo $key['nome_cliente']?></td>
									<td><?php echo $key['endereco_destino'] ?></td>
									<td><?php echo $key['tipo_os'] ?></td>
									<td><?php echo $key['status'] ?></td>
									<td style="font-size: 25px;"><a href="#" data-toggle="modal" data-target="#info"
										data-whateverid="<?php echo $key['id'] ?>"
										data-whatevercontrato="<?php echo $key['id_contrato'] ?>"
										data-whateverplaca="<?php echo $key['placa'] ?>"
										data-whateverserial="<?php echo $key['serial'] ?>"
										data-whatevermarca="<?php echo $key['marca'] ?>"
										data-whateverequipamentos="<?php echo $key['quantidade_equipamentos'] ?>"
										data-whateverstatus="<?php echo $key['status'] ?>"
										data-whateverdataini="<?php echo $key['data_inicial'] ?>"
										data-whateverdatafim="<?php echo $key['data_final'] ?>"
										data-whateveroservacao="<?php echo $key['observacoes'] ?>"
										><i class="ion-information-circled"></i></a>
										<?php if($key['bt_st'] == 0){?>
										<a href="<?=base_url('index.php/home/mapa_eq/'.$key['serial'])?>"><i class="ion-map"></i></a>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="infoTitle"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-clipboard"></i> Contrato:</label>
								<label id="contrato"></label>
							</div>
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-android-car"></i> Placa:</label>
								<label id="placa"></label>
							</div>
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-ios-barcode"></i> Serial:</label>
								<label id="serial"></label>
							</div>
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-ios-grid-view"></i> Marca:</label>
								<label id="marca"></label>
							</div>
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-ios-cart"></i> Quantidade de equipamentos:</label>
								<label id="equipamentos"></label>
							</div>
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-android-done-all"></i> Status:</label>
								<label id="status"></label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-calendar"></i> Data inicial:</label>
								<label id="dataini"></label>
							</div>
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-calendar"></i> Data final:</label>
								<label id="datafim"></label>
							</div>
							<div class="col-sm-12">
								<label style="font-size: 15px; font-weight: bold;"><i class="ion-pin"></i> Observações:</label>
								<label id="observacoes"></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<pre></pre>
	<footer>
		<div align="center" style="font-size: 11px;">
			©2018 Show Tecnologia - Todos os direitos reservados.
		</div>
	</footer>
	<script type="text/javascript">
		$('#info').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var id = button.data('whateverid')
			var contrato = button.data('whatevercontrato')   
			var placa = button.data('whateverplaca')  
			var serial = button.data('whateverserial')  
			var marca = button.data('whatevermarca')  
			var equipamentos = button.data('whateverequipamentos')  
			var status = button.data('whateverstatus')  
			var dataini = button.data('whateverdataini')  
			var datafim = button.data('whateverdatafim')   
			var observacoes = button.data('whateveroservacao')     

			var modal = $(this)
			modal.find('.modal-title').text('Informações O.S Nº '+ id)
			modal.find('#contrato').text(contrato)
			modal.find('#placa').text(placa)
			modal.find('#serial').text(serial)
			modal.find('#marca').text(marca)
			modal.find('#equipamentos').text(equipamentos)
			modal.find('#status').text(status)
			modal.find('#dataini').text(dataini)
			modal.find('#datafim').text(datafim)
			modal.find('#observacoes').text(observacoes)


		})
	</script>

	<script src="<?php echo base_url('media') ?>/js/jquery.mask.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () { 

			var cpf = $("#cpff");
			cpf.mask('000.000.000-00', {reverse: false});

			var cep = $("#cep");
			cep.mask("000000-000", {reverse: false});

			var telefone = $("#telefone");
			telefone.mask("(000) 00000-0000", {reverse: false});

			var celular = $("#celular");
			celular.mask("(000) 00000-0000", {reverse: false});

			var money = $("#money");
			money.mask("#.###.##", {reverse: true});

			var money = $("#money1");
			money.mask("#.###.##", {reverse: true});

			var money = $("#money2");
			money.mask("#.###.##", {reverse: true});

			var money = $("#money3");
			money.mask("#.###.##", {reverse: true});

		});

	</script>
	<script type="text/javascript">
		$(document).ready(function(e) {

			var estado = '<?php echo $instalador->estado; ?>';
			$('.estado option[value="'+estado+'"]').prop("selected", true);

			var banco = '<?php echo $instalador->banco; ?>';
			$('.banco option[value="'+banco+'"]').prop("selected", true);

			$('.form-horizontal').delegate('select', 'focus', function(e) {
				e.preventDefault();
				var options = '<option value=""></option>';
				estado = $("#estado").val();
				get_cidades = "<?php echo base_url('instaladores/get_cidades'); ?>";
				$.ajax({
					type: "POST",
					data: {sigla:estado},  
					url: get_cidades,
					dataType: "json",
					beforeSend:function(){
						$('#cidade').html('<option>Carregando...</option>');
					},
					success: function(data){
						$.each(data, function(index, i) {
							options += '<option value="' + i.nome + '">' + i.nome + '</option>';
						});
						$('#cidade').empty().html(options).show(); 
					},
				});
			});
		});
	</script>

	<script type="text/javascript">
		google.charts.load("current", {packages:["corechart"]});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
			var os_paga = <?php echo $os_paga; ?>;
			var os_n_paga = <?php echo $os_n_paga; ?>;
			var data = google.visualization.arrayToDataTable([
				['Task', 'Hours per Day'],
				['O.S Pagas', os_paga],
				['O.S Não Pagas',  os_n_paga]

				]);

			var options = {
				title: 'Pagamentos',
				pieHole: 0.4,
			};

			var chart = new google.visualization.PieChart(document.getElementById('recebimento'));
			chart.draw(data, options);
		}
	</script>

	<script type="text/javascript">
		google.charts.load('current', {'packages':['bar']});
		google.charts.setOnLoadCallback(drawStuff);

		function drawStuff() {
			var manutencao = <?php echo $manutencao; ?>;
			var troca = <?php echo $troca; ?>;
			var retirada = <?php echo $retirada; ?>;
			var instalacao = <?php echo $instalacao; ?>;
			var data = new google.visualization.arrayToDataTable([
				['Tipos de ordens de serviços', 'Total de ordens de serviços'],
				["Manutenção", manutencao],
				["Troca", troca],
				["Retirada", retirada],
				["Instalação", instalacao]

				]);
			var options = {
				width: 400,
				legend: { position: 'none' },
				bar: { groupWidth: "40%" }
			};
			var chart = new google.charts.Bar(document.getElementById('top_x_div'));
			chart.draw(data, google.charts.Bar.convertOptions(options));
		};
	</script>

	<script type="text/javascript">
		google.charts.load("current", {packages:["corechart"]});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
			var abertas = <?php echo $abertas; ?>;
			var fechadas = <?php echo $fechadas; ?>;
			var data = google.visualization.arrayToDataTable([
				['Task', 'Hours per Day'],
				['Em aberto', abertas],
				['Concluídas', fechadas]
				]);

			var options = {
				title: 'Minhas ordens de serviços',
				pieHole: 0.4,
				slices: [{color: '#FF9400'},{color: '#009F1E'}],
			};

			var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
			chart.draw(data, options);
		}
	</script>

	<script type="text/javascript">
		$(document).ready( function () {
			$('#table').DataTable( {
				"language": {
					"decimal":        "",
					"emptyTable":     "Nenhum registro encontrado",
					"info":           "Registro _START_ a _END_ de _TOTAL_ registros",
					"infoEmpty":      "0 Registros",
					"infoFiltered":   "(filtered from _MAX_ total entries)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Qntd: _MENU_",
					"loadingRecords": "Carregando...",
					"processing":     "Processando...",
					"search":         "Pesquisar:",
					"zeroRecords":    "Nenhum registro encontrado",
					"paginate": {
						"first":      "Anterior",
						"last":       "Avançar",
						"next":       "Avançar",
						"previous":   "Início"
					}
				}
			} );
		} );

		var loadFile = function(event) {
			var output = document.getElementById('output');
			output.src = URL.createObjectURL(event.target.files[0]);
		};
	</script>

