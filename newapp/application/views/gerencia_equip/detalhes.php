<link href="<?php echo base_url('newAssets') ?>/css/jquery-ui.css" rel="stylesheet">
<link href="<?php echo base_url('newAssets') ?>/css/detalheEquip.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/detalhe.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/moment.min.js"></script>

<section id="detalhe-equip" class="container-fluid" style="padding-left: 260px; padding-right: 20px;">
	<h2 class="h2">Detalhes da Ordem Nº <?php echo $idOrdem ?>.</h2>
	<div id="content">
		<div id="mensagem">
			<?php if (isset($msgOrdem) && ($msgOrdem!="")) {
				echo ('<div class="alert alert-'.$msgtipo.' form-inline" role="alert" style="margin: 12px; padding-bottom: 26px;">'.$msgOrdem.'<a id="okay" class="pull-right btn btn-info" style="vertical-align: middle;" >OK! <i class="fa fa-thumbs-up" aria-hidden="true"></i></a></div>');
			} ?>
		</div>
		<div id="barraDeDetalhes" class="pull-left dados">
			<div id="imagem">
				<h4><?php echo 'Módulo de rastreio veicular '.$modelo ?></h4>
				<img src="<?php echo base_url('/newAssets/imagens/'.$modeloIMG.'.jpg') ?>" height="126" width="150" alt="Modelo do equipamento.">
			</div>
			<div class="cados" id="detalhes">
				<hr class="divider" />
				<label>Serial: </label> <?php echo $serial ?><br>
				<label>Número: </label> <?php echo $numero ?><br>
				<label>Operadora: </label> <?php echo $operadora ?><br>
				<label>CCID: </label> <?php echo $ccid ?><br>
				<label>Tipo da OS: </label> <?php echo $tipoOs ?><br>
				<label>Placa: </label> <?php echo $placa ?><br>
				<label><?php echo $pessoa ?> <br>
				<label>Solicitante: </label> <?php echo $solicitante ?><br>
				<label>Tipo de envio: </label> <?php echo $tipoEnvio ?><br>
				<label><?php echo $inforTipo ?>
			</div>
		</div>
		<div id="linhaTempo" class="pull-right dados">
			<div class="pull-right col-sm-5">
				<label class="col-sm-6 word-wrap text-right semMargPadd"><?php echo $estado ?></label>
				<div id="alertaStatus" class="col-sm-6" style="background-color: <?php echo $flexaCor ?>">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
				</div>
			</div>
			<h4>Ciclo de vida.</h4>
			<div id="datas" class="col-sm-12">
				<div class="flexaInicio">
				</div>
				<div class="data data1">
					Configuração
				</div>
				<div class="data data2">
					Envio
				</div>
				<div class="data data3" <?php echo $esconde3 ?>>
					Chegada
				</div>
				<div class="data data4" <?php echo $esconde4 ?>>
					Instalação
				</div>
				<div class="data data5" <?php echo $esconde5 ?>>
					Retorno
				</div>
				<div class="flexaFim" style="border-left-color: <?php echo $flexaCor ?>">
				</div>
			</div>
			<div id="forma" class="col-sm-12">
				<p class="text-center">|</p>
				<p class="text-center">|</p>
				<p class="text-center" <?php echo $esconde3 ?>>|</p>
				<p class="text-center" <?php echo $esconde4 ?>>|</p>
				<p class="text-center" <?php echo $esconde5 ?>>|</p>
			</div>
			<div id="valores" class="col-sm-12">
				<div class="tamanho">
					<div class="valor data1">
						<?php echo $data1 ?>
					</div>
				</div>
				<div class="tamanho">
					<div class="valor data2">
						<?php echo $data2 ?>
					</div>
				</div>
				<div class="tamanho" <?php echo $esconde3 ?>>
					<div class="valor data3">
						<?php echo $data3 ?>
					</div>
				</div>
				<div class="tamanho" <?php echo $esconde4 ?>>
					<div class="valor data4">
						<?php echo $data4 ?>
					</div>
				</div>
				<div class="tamanho" <?php echo $esconde5 ?>>
					<div class="valor data5">
						<?php echo $data5 ?>
					</div>
				</div>
			</div>
			<div>
				<br><br><br><br>
				<hr class="divider" />
			</div>
			<div class="col-sm-12" <?php echo $esconde6 ?>>
				<div class="col-sm-6">
					<h4>Dados de retorno gerados:</h4>
					<label>Tipo de retorno: </label> <?php echo $tipoRetorno ?><br>
					<label><?php echo $inforRetorno ?><br>
					<?php echo $datasRetorno ?>
				</div>
				<br>
				<div id="form1" class="col-sm-6" hidden>
					<form method="post" action="<?php echo site_url('gerencia_equipamentos/devolucao_1') ?>">
						<input type="hidden" value="<?php echo $data4 ?>" id="datahidden1">
						<p>Insira a data de envio do equipamento que está retornando para manutenção.</p>
						<div class="form-group">
							<label class="text-right form-label col-sm-1 semMargPadd">Data: </label>
							<div class="col-sm-6">
								<input class="form-control dataJS semMargPadd" type="text" name="dataRetorno_1" id="dataRetorno_1" required>
							</div>
							<div class="col-sm-3">
								<input type="submit" class="form-control btn btn-success semMargPadd" value="Salvar" id="salvar_1">
							</div>
						</div>
					</form>
				</div>
				<div id="form2" class="col-sm-6" hidden>
					<form method="post" action="<?php echo site_url('gerencia_equipamentos/devolucao_2') ?>">
						<input type="hidden" value="<?php echo $data5 ?>" id="datahidden2">
						<p>Insira a data de recebimento do equipamento que foi retornado para manutenção.</p>
						<div class="form-group">
							<label class="text-right form-label col-sm-1 semMargPadd">Data: </label>
							<div class="col-sm-6">
								<input class="form-control dataJS" type="text" name="dataRetorno_2" id="dataRetorno_2" required>
							</div>
							<div class="col-sm-3">
								<input type="submit" class="form-control btn btn-success semMargPadd" value="Salvar" id="salvar_2">
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-sm-12">
				<p><?php echo $observacao ?></p>
			</div>
		</div>
	</div>
</section>