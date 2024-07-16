<style type="text/css">
	@media screen and (min-width: 992px) {
		#modal-veiculos {
			width: 1250px;
			/* New width for large modal */
		}
	}
</style>

<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Equipamento Violado", site_url('Homes'), "Monitor", "Equipamento Violado");
?>
<div class="row" style="margin: 15px 0 0 15px;">
	<div class="col-md-3">

		<div id="filtroBusca" class="card" style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBusca">
				<div class="form-group filtro">

					<div class="input-container">
						<label for="filtrar-atributos">Nome</label>
						<input type="text" name="filtrar-atributos" class="form-control" placeholder="Nome" id="filtrar-atributos" />
					</div>


					<div class="button-container">
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-md-9" id="conteudo">

		<div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
			<h3>
				<b id="titulo-card">Lista de clientes: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
			<div class="registrosDiv">
				<select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
					<option value=10 selected>10</option>
					<option value=25>25</option>
					<option value=50>50</option>
					<option value=100>100</option>
				</select>
				<h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
			</div>
			<div id="emptyMessageCadastroClientes" style="display: none;">
				<h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
			</div>
			<div style="position: relative;">
				<div id="loadingMessageTecnologias" class="loadingMessage" style="display: none;">
					<b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
				</div>

				<div class="wrapperContatos">
					<div id="tableContatos" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
					</div>
				</div>


				<div id="modals" class="modals">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Dados do Veículo</h4>

							<button type="button" class="btn btn-secondary closes" id="closes" data-dismiss="modal">Fechar</button>

							<!-- <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;"> -->
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
								<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
							</div>
							<!-- </div> -->
						</div>
						<div class="modal-body">
							<div id="myGrid" class="ag-theme-alpine"></div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>



</div>
<style>
	.spinner {
		border: 4px solid rgba(0, 0, 0, 0.1);
		border-left-color: #7983ff;
		border-radius: 50%;
		width: 50px;
		height: 50px;
		animation: spin 1s linear infinite;
	}

	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}
</style>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/monitor', 'violado.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/monitor/violado', 'violado.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/monitor/violado', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
	var Router = '<?= site_url('monitor') ?>';
	var BaseURL = '<?= base_url('') ?>';
</script>