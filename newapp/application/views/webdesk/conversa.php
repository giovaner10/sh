<link rel="stylesheet" type="text/css" href="<?=versionFile('newAssets/css', 'conversa.css') ?>">

<h3>Gerenciador de Tickets</h3>
<hr class="featurette-divider">
<div class="well well-small">
	<a href="<?php echo site_url('webdesk') ?>" title="" class="btn"><i class="icon-th-list"></i> Todos</a>
	<a href="<?php echo site_url('webdesk/abertos') ?>" title="" class="btn"><i class="icon-th-list"></i> Em andamento</a>
	<a href="<?php echo site_url('webdesk/fechados') ?>" title="" class="btn"><i class="icon-th-list"></i> Concluídos</a>
</div>
<br style="clear:both" />

<div id="div-pai">
	<div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
		<div class="card heigth_100pc">
			<div class="card-header" style="display: flex; justify-content: space-between;">
				<div>
					Cliente
				</div>
				<a onclick="redirecionarParaSuporteOmnilink()" id="linkCliente"
					style="font-weight: normal; font-size: 12px; cursor: pointer">
					Mais informações
				</a>
			</div>
			<div class="card-body">
				<div class="col-md-12" style="display: flex; flex-direction: row">
					<label>Status: </label><span id="StatusCliente">-</span>
				</div>
				<div class="col-md-12" style="display: flex; flex-direction: row">
					<label>Nome: </label><span id="NomeCliente">-</span>
				</div>
				<div class="col-md-12" style="display: flex; flex-direction: row">
					<label>CNPJ/CPF: </label><span id="CNPJCliente">-</span>
				</div>
				<div class="col-md-12" style="display: flex; flex-direction: row">
					<label for="">Logradouro: </label><span id="EnderecoCliente">-</span>
				</div>
				<div class="col-md-12" style="display: flex; flex-direction: row">
					<label for="">Bairro: </label><span id="BairroCliente">-</span>
				</div>
				<div class="col-md-12" style="display: flex; flex-direction: row">
					<label for="">CEP: </label><span id="CEPCliente">-</span>
				</div>
				<div class="col-md-12" style="display: flex; flex-direction: row">
					<label for="">Cidade/Estado: </label>
					<span>
						<span id="CidadeCliente"></span> - <span id="UFCliente"></span>
					</span>
				</div>
			</div>

			<div class="border_top_bottom">
				<div class="card-header">Situação financeira</div>
				<div class="card-body">
					<div class="col-md-6" style="display: flex; flex-direction: row">
						<label>Agendamento: </label>
						<select class="form-control alterStatus" id="selectAtendimentoVeic" name="status_atendimentoriveiculo"
							disabled="true">
							<option value="true">Liberado</option>
							<option value="false">Não Liberado</option>
							<option value="null">Não encontrado</option>
						</select>
					</div>
					<div class="col-md-6" style="display: flex; flex-direction: row">
						<label>Comunicação Chip: </label>
						<select class="form-control alterStatus" id="selectComunicacaoChip" name="status_comunicacaochip"
							disabled="true">
							<option value="true">Liberado</option>
							<option value="false">Não Liberado</option>
							<option value="null">Não encontrado</option>
						</select>
					</div>
					<div class="col-md-6" style="display: flex; flex-direction: row">
						<label>Comunicação Satelital: </label>
						<select class="form-control alterStatus" id="selectComunicacaoSatelital" name="status_comunicacaosatelital"
							disabled="true">
							<option value="true">Liberado</option>
							<option value="false">Não Liberado</option>
							<option value="null">Não encontrado</option>
						</select>
					</div>
					<div class="col-md-6" style="display: flex; flex-direction: row">
						<label>Data Desbloqueio Portal: </label>
						<input type="text" style="margin-bottom: 5px;" class="form-control" id="statusDataDesbloqueio"
							name="status_data_desbloqueio_portal" readonly="true">
					</div>
					<div class="col-md-6" style="display: flex; flex-direction: row">
						<label>Emissão PV: </label>
						<select class="form-control alterStatus" id="selectEmissaoPV" name="status_emissaopv" disabled="true">
							<option value="true">Liberado</option>
							<option value="false">Não Liberado</option>
							<option value="null">Não encontrado</option>
						</select>
					</div>
					<div class="col-md-6" style="display: flex; flex-direction: row">
						<label>Bloqueio Total: </label>
						<select class="form-control alterStatus" id="selectBloqueioTotal" name="status_bloqueiototal"
							disabled="true">
							<option value="true">SIM</option>
							<option value="false">NÃO</option>
							<option value="null">Não encontrado</option>
						</select>
					</div>
					<div class="col-md-6" style="display: flex; flex-direction: row">
						<label>Desbloqueio Portal: </label>
						<select class="form-control alterStatus" id="selectDesbloqueioPortal" name="status_desbloqueioportal"
							disabled="true">
							<option value="true">SIM</option>
							<option value="false">NÃO</option>
							<option value="null">Não encontrado</option>
						</select>
					</div>
				</div>
			</div>

			<div class="border_top_bottom">
				<div class="card-header">Contrato</div>
				<div class="card-body">
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Nome Fantasia: </label><span id="NomeFantasiaCliente">-</span>
					</div>
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Telefone: </label><span id="TelefoneCliente">-</span>
					</div>
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>E-mail: </label><span id="EmailCliente">-</span>
					</div>
				</div>

				<div class="card-header">Segmentação</div>
				<div class="card-body">
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Segmentação de Cliente: </label><span id="SegmentacaoCliente">-</span>
					</div>
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Analista de Suporte: </label><span id="SuporteCliente">-</span>
					</div>
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Vendedor: </label><span id="VendedorCliente">-</span>
					</div>

					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Código Cliente (Microsiga): </label><span id="CodigoClienteZatix">-</span>
					</div>
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Loja: </label><span id="Loja">-</span>
					</div>
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Código Cliente (Graber): </label><span id="CodigoClienteGraber">-</span>
					</div>
					<div class="col-md-12" style="display: flex; flex-direction: row">
						<label>Código Cliente (Show): </label><span id="CodigoClienteShow">-</span>
					</div>
				</div>
			</div>

			<div class="border_top_bottom">
				<div class="card-header">Últimos tickets</div>
				<div class="card-body">
					<?php if ($ultimosTickets): ?>
						<table border="1">
							<thead>
								<tr>
									<th>Assunto</th>
									<th>Última interação</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($ultimosTickets as $ticket): ?>
									<tr>
										<td>
											<a href="<?php echo site_url('webdesk') ?>/ticket/<?php echo $ticket->id ?>">
												<?php echo $ticket->assunto ?>
											</a>
										</td>
										<td>
											<?php echo date_format(date_create($ticket->ultima_interacao), 'd/m/Y') ?>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					<?php else: ?>
						<p>Não há tickets</p>
					<?php endif ?>
				</div>
			</div>



		</div>
	</div>

	<div class="span9" style="float: none; margin-left: auto; margin-right: auto;">

		<?php

		if ($this->session->flashdata('sucesso')): ?>
			<div class="alert alert-success">
				<p>
					<?php echo $this->session->flashdata('sucesso') ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('erro')): ?>
			<div class="alert alert-error">
				<p>
					<?php echo $this->session->flashdata('erro') ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ($tickets): ?>
			<?php foreach ($tickets as $ticket): ?>

				<?php if ($ticket->suporte == 'sim'): ?>
					<?php if ($ticket->ticketnumber_crm === null): ?>
						<h4>Ticket #
							<?php echo $ticket->id ?>
							<?php echo $ticket->placa == '' ? '' : '(Placa ' . $ticket->placa . ')' ?> -
							<?php echo $ticket->assunto ?>
						</h4>
					<?php else: ?>
						<h4>Ocorrência
							<?php echo $ticket->ticketnumber_crm ?>
							<?php echo $ticket->placa == '' ? '' : '(Placa ' . $ticket->placa . ')' ?> -
							<?php echo $ticket->assunto ?>
						</h4>
					<?php endif ?>


					<p>Em
						<?php echo dh_for_humans($ticket->data_abertura); ?>
					</p>

					<div style="background-color: #ffffff; border:1px solid #D0D0D0 ;">
						<div class="media" style="margin: 10px;">
							<a class="pull-left" href="#">
								<img class="media-object" src="<?php echo base_url('media') ?>/img/avatar2.png">
							</a>
							<div class="media-body" style="float:right;">
								<p>
									<?php echo dh_for_humans($ticket->data_abertura) ?>
								</p>
							</div>
							<div class="media-body">
								<h5 class="media-heading">
									<?php echo $ticket->usuario . ' - ' . $ticket->nome_usuario ?>
								</h5>
								<p>
									<?php echo $ticket->mensagem ?>
								</p>
								<?php if ($ticket->arquivo != ""): ?>
									<?php $caminho = $ticket->arquivo; ?>
									<a href="<?php echo $caminho ?>" target="_blank"><i class="icon-file"></i>Visualizar anexo</a>
								<?php endif ?>
							</div>
						</div>
					</div>

				<?php else: ?>
					<?php if ($ticket->ticketnumber_crm === null): ?>
						<h4>Ticket #
							<?php echo $ticket->id ?>
							<?php echo $ticket->placa == '' ? '' : '(Placa ' . $ticket->placa . ')' ?> -
							<?php echo $ticket->assunto ?>
						</h4>
					<?php else: ?>
						<h4>Ocorrência
							<?php echo $ticket->ticketnumber_crm ?>
							<?php echo $ticket->placa == '' ? '' : '(Placa ' . $ticket->placa . ')' ?> -
							<?php echo $ticket->assunto ?>
						</h4>
					<?php endif ?>

					<p>Em
						<?php echo dh_for_humans($ticket->data_abertura) ?>
					</p>

					<div style="background-color: #f5f5f5; border:1px solid #D0D0D0 ;">
						<div class="media" style="margin: 10px;">
							<a class="pull-left" href="#">
								<img class="media-object" src="<?php echo base_url('media') ?>/img/avatar.png">
							</a>
							<div class="media-body" style="float:right;">
								<p>
									<?php echo dh_for_humans($ticket->data_abertura) ?>
								</p>
							</div>
							<div class="media-body">
								<h5 class="media-heading">
									<?php echo $ticket->usuario . ' - ' . $ticket->nome_usuario ?>
								</h5>
								<p>
									<?php echo $ticket->mensagem ?>
								</p>
								<?php if ($ticket->arquivo != ""): ?>
									<?php $caminho = $ticket->arquivo; ?>
									<a href="<?php echo $caminho ?>" target="_blank"><i class="icon-file"></i>Visualizar anexo</a>
								<?php endif ?>
							</div>
						</div>
					</div>

					<div style="background-color: #FFFFFF; border:1px solid #D0D0D0; margin-top: 10px;">
						<div class="media" style="margin: 10px;">
							<a class="pull-left" href="#">
								<img class="media-object" src="<?php echo base_url('media') ?>/img/avatar2.png">
							</a>
							<div class="media-body" style="float:right;">
								<p>
									<?php echo dh_for_humans($ticket->data_abertura) ?>
								</p>
							</div>
							<div class="media-body">
								<h5 class="media-heading">
									<?php echo $ticket->departamento ?>
								</h5>
								<p>Olá
									<?php echo $ticket->usuario ?>, estaremos analisando a sua solicitação, o seu contato é muito importante
									para nós, o mais breve possível estaremos lhe contactando. Obrigado.
								</p>
							</div>
						</div>
					</div>

				<?php endif; ?>

				<?php if ($tickets2): ?>

					<?php foreach ($tickets2 as $ticket2): ?>

						<?php if ($ticket2->id_user != 425): ?>

							<div style="background-color: #f5f5f5; border:1px solid #D0D0D0; margin-top: 10px;">
								<div class="media" style="margin: 10px;">
									<a class="pull-left" href="#">
										<img class="media-object" src="<?php echo base_url('media') ?>/img/avatar.png">
									</a>
									<div class="media-body" style="float:right;">
										<p>
											<?php echo dh_for_humans($ticket2->data_resposta) ?>
										</p>
									</div>
									<div class="media-body">
										<h5 class="media-heading">
											<?php echo $ticket2->usuario . ' - ' . $ticket2->nome_usuario ?>
										</h5>
										<p>
											<?php echo $ticket2->resposta ?>
										</p>
										<?php if ($ticket2->arquivo != ""): ?>
											<?php $caminho = $ticket2->arquivo; ?>
											<a href="<?php echo $caminho ?>" target="_blank"><i class="icon-file"></i>Visualizar anexo</a>
										<?php endif ?>
									</div>
								</div>
							</div>

						<?php else: ?>

							<div style="background-color: #FFFFFF; border:1px solid #D0D0D0; margin-top: 10px;">
								<div class="media" style="margin: 10px;">
									<a class="pull-left" href="#">
										<img class="media-object" src="<?php echo base_url('media') ?>/img/avatar2.png">
									</a>
									<div class="media-body" style="float:right;">
										<p>
											<?php echo dh_for_humans($ticket2->data_resposta) ?>
										</p>
									</div>
									<div class="media-body">
										<h5 class="media-heading">
											<?php echo $ticket->departamento ?>
										</h5>
										<p>
											<?php echo $ticket2->resposta ?>
										</p>
										<?php if ($ticket2->arquivo != ""): ?>
											<?php $caminho = $ticket2->arquivo; ?>
											<a href="<?php echo $caminho ?>" target="_blank"><i class="icon-file"></i>Visualizar anexo</a>
										<?php endif ?>
									</div>
								</div>
							</div>

						<?php endif ?>

					<?php endforeach ?>
				<?php else: ?>

				<?php endif ?>

				<?php if ($ticket->status != "3"): ?>
					<form method="post" name="form_resposta" id="ContactForm" enctype="multipart/form-data"
						action="<?php echo site_url('webdesk') ?>/enviar_resposta/<?php echo $ticket->id ?>/<?php echo $ticket->status ?>">

						<div class="wrapper" style="margin-top: 15px;">
							<div class="textarea_box">
								<div class="bg">
									<textarea name="resposta" cols="1" rows="3" placeholder="Nova resposta" class="textarea span8"></textarea>
								</div>
							</div>
							<div class="wrapper">
								<div class="bg" style="display: table; position: relative; width: 50%; float: left;">
									<input type="file" name="arquivo" class="filestyle" data-buttonText="Arquivo">
								</div>
								<input type="hidden" name="id_cliente" value="<?= $ticket->id_cliente ?>">
								<div class="email_trello" style="width: 50%; display: table; position: relative; float: right;">
									<label class="label label-primary" style="vertical-align: 40%;">Email de Comentários - TRELLO: </label>
									<input type="text" name="coment_trello" class="form-control"
										value="<?php echo $ticket->coment_trello; ?>" />
								</div>
							</div>
							<div id="botoes" style="margin-top: 15px;">
								<div class="botao_fechar" style="float: left;">
									<a href="<?php echo site_url('webdesk') ?>/fechar_ticket/<?php echo $ticket->id ?>/<?php echo $ticket->status ?>"
										class="btn btn-primary">
										Fechar Ticket
									</a>
								</div>
								<div class="botoes_resposta" style="float: right;">
									<a href="#" class="btn" onClick="document.getElementById('ContactForm').reset();return false">
										Limpar
									</a>
									<a href="#" class="btn btn-info" onClick="document.getElementById('ContactForm').submit()">
										Salvar
									</a>
								</div>
							</div>
						</div>
					</form>

				<?php else: ?>
					<div style="float: right; margin-top: 15px;">
						<a href="<?php echo site_url('webdesk') ?>/reabrir_ticket/<?php echo $ticket->id ?>/<?php echo $ticket->status_anterior ?>"
							title="" class="btn btn-warning">Reabrir ticket</a></li>
					</div>
				<?php endif ?>

				<br>
				<br>
				<br>
			<?php endforeach ?>
		<?php else: ?>
			<h2>Não há tickets</h2>
		<?php endif ?>

	</div>

</div>

<script>

	const cliente = JSON.parse(<?= $cliente ?>);

	const informacoesCliente = cliente.customers;

	if (informacoesCliente?.statusCadastro == 0) {
		$('#StatusCliente').text("Ativo");
		$('#StatusCliente').css('color', 'green');
		$('#StatusCliente').css('font-weight', 'bold');
	} else {
		$('#StatusCliente').text("Inativo");
		$('#StatusCliente').css('color', 'red');
		$('#StatusCliente').css('font-weight', 'bold');
	}

	$('#NomeCliente').text(informacoesCliente?.name || "Não encontrado");
	$('#CNPJCliente').text(informacoesCliente?.document || "Não encontrado");

	$('#EnderecoCliente').text(informacoesCliente?.address || "Não encontrado");
	$('#BairroCliente').text(informacoesCliente?.district || "Não encontrado");
	$('#CEPCliente').text(informacoesCliente?.postalCode?.Name || "Não encontrado");
	$('#CidadeCliente').text(informacoesCliente?.city?.Name || "Não encontrado");
	$('#UFCliente').text(informacoesCliente?.city?.Uf || "Não encontrado ");

	$('#selectAtendimentoVeic').val(informacoesCliente?.status_atendimentoriveiculo ? informacoesCliente.status_atendimentoriveiculo.toString() : "null");
	$('#selectComunicacaoChip').val(informacoesCliente?.status_comunicacaochip ? informacoesCliente.status_comunicacaochip.toString() : "null");
	$('#selectComunicacaoSatelital').val(informacoesCliente?.status_comunicacaosatelital ? informacoesCliente.status_comunicacaosatelital.toString() : "null");
	$('#statusDataDesbloqueio').val(informacoesCliente?.status_data_desbloqueio_portal ? informacoesCliente.status_data_desbloqueio_portal : "Não encontrado");
	$('#selectEmissaoPV').val(informacoesCliente?.status_emissaopv ? informacoesCliente.status_emissaopv.toString() : "null");
	$('#selectBloqueioTotal').val(informacoesCliente?.status_bloqueiototal ? informacoesCliente.status_bloqueiototal.toString() : "null");
	$('#selectDesbloqueioPortal').val(informacoesCliente?.status_desbloqueioportal ? informacoesCliente.status_desbloqueioportal.toString() : "null");

	$('#NomeFantasiaCliente').text(informacoesCliente?.fantasyName || "Não encontrado");
	$('#TelefoneCliente').text(informacoesCliente.ddd && informacoesCliente.telephone ? `(${informacoesCliente.ddd}) ${informacoesCliente.telephone}` : "Não encontrado");
	$('#EmailCliente').text(informacoesCliente?.email || "Não encontrado");

	$('#SegmentacaoCliente').text(informacoesCliente?.segmentation || "Não encontrado");
	$('#SuporteCliente').text(informacoesCliente?.analistaSuporte?.nome || "Não encontrado");
	$('#VendedorCliente').text(informacoesCliente?.seller?.Nome || "Não encontrado");
	$('#CodigoClienteZatix').text(informacoesCliente?.codeERP || "Não encontrado");
	$('#Loja').text(informacoesCliente?.storeERP || "Não encontrado");
	$('#CodigoClienteGraber').text(informacoesCliente?.codigoClienteGraber || "Não encontrado");
	$('#CodigoClienteShow').text(informacoesCliente?.codigoClienteShow || "Não encontrado");

	function redirecionarParaSuporteOmnilink() {
		if(!informacoesCliente.document) {
			alert('Não foi possível encontrar o CNPJ/CPF do cliente');
			return;
		}
		window.open('<?= site_url("PaineisOmnilink") ?>' + '/index/' + informacoesCliente.document.replace('.', '').replace('.', '').replace('/', '').replace('-', ''), '_blank');
	}

</script>