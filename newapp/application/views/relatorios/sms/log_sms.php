<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("relatorio_sms_log") ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?= lang('suporte') ?> >
		<?= lang('logs') ?> >
		<?= lang('envio_sms') ?>
	</h4>
</div>


<div class="row" style="margin: 15px 0 0 15px;">
	<div class="col-md-3">

		<div id="filtroBusca" class="card" style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBusca">
				<div class="form-group filtro">
					<div class="input-container buscaProcessamento">
						<label for="tipo">Buscar por:</label>
						<select class="form-control" name="tipo" id="tipo" style="width: 100%;">
							<option value="" selected>Buscar por</option>
							<option value="usuario">CNPJ Usuário</option>
							<option value="celular">Celular</option>
							<option value="placa">Placa</option>
							<option value="prefixo">Prefixo</option>
						</select>
					</div>

					<div class="input-container busca">
						<label for="keyword">Busca:</label>
						<input type="text" name="keyword" class="form-control" placeholder="Digite uma busca" id="keyword" />
					</div>

					<div class="input-container buscaProcessamento">
						<label for="dt_ini">Data Inicial:</label>
						<input type="date" name="dt_ini" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dt_ini" />
					</div>

					<div class="input-container buscaProcessamento">
						<label for="dt_fim">Data Final:</label>
						<input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dt_fim" />
					</div>


					<div class="button-container">
						<button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-md-9" id="conteudo">
		<div class="card-conteudo card-relatorio-sms" style='margin-bottom: 20px;'>
			<h3>
				<b style="margin-bottom: 5px;"> <?= lang('relatorio_sms') ?> </b>
				<div class="btn-div-responsive" id="btn-div-alertas">
					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
						<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonSms" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_sms" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
			<div class="registrosDiv">
				<select id="select-quantidade-por-pagina-sms" class="form-control" style="float: left; width: auto; height: 34px;">
					<option value="10" selected>10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
				<h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
			</div>
			<div id="emptyMessageSms" style="display: none;">
				<h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
			</div>
			<div style="position: relative;">
				<div id="loadingMessageSms" class="loadingMessage" style="display: none;">
					<b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
				</div>
				<div class="wrapperSms">
					<div id="tableSms" class="ag-theme-alpine my-grid-sms" style="height: 500px">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/relatorios/sms', 'sms.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/relatorios/sms', 'Exportacoes.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">

<script>
	var Router = '<?= site_url('relatorios') ?>';
	var BaseURL = '<?= base_url('') ?>';
</script>