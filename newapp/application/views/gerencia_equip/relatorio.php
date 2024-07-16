<link href="<?php echo base_url('newAssets') ?>/css/jquery-ui.css" rel="stylesheet">
<link href="<?php echo base_url('newAssets') ?>/css/relatorio.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/relatorio.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/Chart.min.js"></script>

<section id="detalhe-equip" class="container-fluid" style="padding-left: 260px; padding-right: 20px;">
	<div>
		<a class = " pull-right text-left btn btn-default" href="<?php echo site_url('gerencia_equipamentos') ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a>
		<h2 class="h2">Detalhes Gerais.</h2>
	</div>
	<div id="content">
		<div id="grafico">
			<div id="form" onkeypress="return disableEnterKey(event)">
				<form id="periodo" class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-3">
							<h4>Filtrar por período</h4>
						</div>
						<div class="col-sm-9">
							<label class="col-sm-2 text-right noPadd" style="padding-top: 4px">Data início: </label>
							<div class="col-sm-2 noPadd">
								<input class="form-control dataJS" type="text" id="dateHead" name="dateHead">
							</div>
							<label class="col-sm-2 text-right noPadd" style="padding-top: 4px">Data fim: </label>
							<div class="col-sm-2 noPadd">
								<input class="form-control dataJS " type="text" id="dateTale" name="dateTale">
							</div>
							<div class="col-sm-3">
								<input id="filtrar" type="button" class="btn btn-success" value="Buscar">
								<div id="buscando" class="pull-right" style="padding: 2px 40px 0px 3px;" hidden>
									<img src="<?php echo base_url('newAssets/imagens/loader.gif') ?>" width="30">
								</div>
								<p>*(máx: 60 dias)</p>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div id="canvas">
				
			</div>
		</div>
		<div id="numeros">
			<div class="block" style="background-color: #88e2d5">
				<label>EQUIPAMENTOS ENVIADOS</label>
				<hr class="divider" />
				<div class="letras col-sm-12">
					<div class="pull-right">
						<label>A Técnicos</label>
					</div>
					<div class="pull-right">
						<label>A Clientes</label>
					</div>
				</div>
				<div class="informacao col-sm-12">
					<div class="letras">
						<div class="dados pull-right" style="margin-left: 2.5px;">
							<p class="para"><a href="<?php echo site_url('gerencia_equipamentos') ?>?queries[search]=enviado%20ao%20instalador" target="_blank"><?php echo $envioTec ?></a></p>
						</div>
						<div class="dados pull-right" style="margin-right: 2.5px;">	
							<p class="para"><a href="<?php echo site_url('gerencia_equipamentos') ?>?queries[search]=enviado%20ao%20cliente" target="_blank"><?php echo $envioCli ?></a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="block" style="background-color: #76b8d6">
				<label>EQUIPAMENTOS EM POSSE</label>
				<hr class="divider" />
				<div class="letras col-sm-12">
					<div class="pull-right">
						<label>De Técnicos</label>
					</div>
					<div class="pull-right">
						<label>De Clientes</label>
					</div>
				</div>
				<div class="informacao col-sm-12">
					<div class="letras">
						<div class="dados pull-right" style="margin-left: 2.5px;">
							<p class="para"><a href="<?php echo site_url('gerencia_equipamentos') ?>?queries[search]=posse%20do%20técnico" target="_blank"><?php echo $possesTec ?></a></p>
						</div>
						<div class="dados pull-right" style="margin-right: 2.5px;">	
							<p class="para"><a href="<?php echo site_url('gerencia_equipamentos') ?>?queries[search]=posse%20do%20cliente" target="_blank"><?php echo $possesCli ?></a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="block" style="background-color: #6c99c3">
				<label>EQUIPAMENTOS EM USO (INSTALADOS)</label>
				<hr class="divider" />
				<div class="informacao col-sm-12">
					<br>
					<div class="letras">
						<div class="dados">
							<p class="para"><a href="<?php echo site_url('gerencia_equipamentos') ?>?queries[search]=Em%20uso" target="_blank"><?php echo $instalacoes ?></a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="block" style="background-color: #a088bd">
				<label>EQUIPAMENTOS RETORNANDO</label>
				<hr class="divider" />
				<div class="informacao col-sm-12">
					<br>
					<div class="letras">
						<div class="dados">	
							<p class="para"><a href="<?php echo site_url('gerencia_equipamentos') ?>?queries[search]=manutenção" target="_blank"><?php echo $retornos ?></a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="block" style="background-color: #b3bbce">
				<label>EQUIPAMENTOS EM MANUTENÇÃO</label>
				<hr class="divider" />
				<div class="informacao col-sm-12">
					<br>
					<div class="letras">
						<div class="dados">	
							<p class="para"><a href="<?php echo site_url('gerencia_equipamentos') ?>?queries[search]=manutenção" target="_blank"><?php echo $manutencoes ?></a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="block" style="background-color: #ffffff">
				<label>TOTAL DE ORDENS CADASTRADAS</label>
				<hr class="divider" />
				<div class="informacao col-sm-12">
					<br>
					<div class="letras">
						<div class="dados">	
							<p class="para"><?php echo $totalOrdens ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>