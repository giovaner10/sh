	<div class="row">
		<div class="col-md-12">
			<div class="text-title">
				<h3 style="padding: 0;"><?= lang("dashboard") ?></h3>
				<h4 style="padding: 0;">
					<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> > 
					<?= lang('suporte') ?> > 
					<?= lang('ordem_de_servicos') ?> > 
					<?= lang('dashboard') ?>
				</h4>
				<hr>
			</div>
		</div>
	</div>

	<div id="loading">
		<div class="loader"></div>
	</div>

	<div class="row metrica-container">
		<div class="col-md-12">

			<div class="col-md-2 metrica">
				<div id="card-os-fechadas" class="card metrica-card">
					<div class="card-header">
						<svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" style="height: 30px; width: 30px;" viewBox="0 0 448 512">
							<path fill="#1c69ad" d="M64 80c-8.8 0-16 7.2-16 16V416c0 8.8 7.2 16 16 16H384c8.8 0 16-7.2 16-16V96c0-8.8-7.2-16-16-16H64zM0 96C0 60.7 28.7 32 64 32H384c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
						</svg>
						<h2 class="number-indicator" id="os-fechadas"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
					</div>
					<p>O.S FECHADA</p>
				</div>
			</div>
			<div class="col-md-2 metrica">
				<div id="card-os-aberta" class="card metrica-card">
					<div class="card-header">
						<svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" style="height: 30px; width: 30px;" viewBox="0 0 512 512">
							<path fill="#1c69ad" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
						</svg>
						<h2 class="number-indicator" id="os-abertas"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
					</div>
					<p>O.S EM ABERTO</p>
				</div>
			</div>

			<div class="col-md-2 metrica">
				<div id="card-os-manutencao" class="card metrica-card">
					<div class="card-header">
						<svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="#1c69ad" d="M352 320c88.4 0 160-71.6 160-160c0-15.3-2.2-30.1-6.2-44.2c-3.1-10.8-16.4-13.2-24.3-5.3l-76.8 76.8c-3 3-7.1 4.7-11.3 4.7H336c-8.8 0-16-7.2-16-16V118.6c0-4.2 1.7-8.3 4.7-11.3l76.8-76.8c7.9-7.9 5.4-21.2-5.3-24.3C382.1 2.2 367.3 0 352 0C263.6 0 192 71.6 192 160c0 19.1 3.4 37.5 9.5 54.5L19.9 396.1C7.2 408.8 0 426.1 0 444.1C0 481.6 30.4 512 67.9 512c18 0 35.3-7.2 48-19.9L297.5 310.5c17 6.2 35.4 9.5 54.5 9.5zM80 408a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
						</svg>
						<h2 class="number-indicator" id="os-manutencao"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
					</div>
					<p>O.S - MANUTENÇÃO</p>
				</div>
			</div>

			<div class="col-md-2 metrica">
				<div id="card-os-troca" class="card metrica-card">
					<div class="card-header">
						<svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path fill="#1c69ad" d="M272 416c17.7 0 32-14.3 32-32s-14.3-32-32-32H160c-17.7 0-32-14.3-32-32V192h32c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-64-64c-12.5-12.5-32.8-12.5-45.3 0l-64 64c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8l32 0 0 128c0 53 43 96 96 96H272zM304 96c-17.7 0-32 14.3-32 32s14.3 32 32 32l112 0c17.7 0 32 14.3 32 32l0 128H416c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l64 64c12.5 12.5 32.8 12.5 45.3 0l64-64c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8l-32 0V192c0-53-43-96-96-96L304 96z" />
						</svg>
						<h2 class="number-indicator" id="os-troca"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
					</div>
					<p>O.S - TROCA</p>
				</div>
			</div>

			<div class="col-md-2 metrica">
				<div id="card-os-retirada" class="card metrica-card">
					<div class="card-header">
						<svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="#1c69ad" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z" />
						</svg>
						<h2 class="number-indicator" id="os-retirada"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
					</div>
					<p>O.S - RETIRADA</p>
				</div>
			</div>

			<div class="col-md-2 metrica">
				<div id="card-os-instalacao" class="card metrica-card">
					<div class="card-header">
						<svg class="metrica-img " style="height: 30px; width: 30px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="#1c69ad" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
						</svg>
						<h2 class="number-indicator" id="os-instalacao"><i class="fa fa-spinner fa-spin" style="color: #1C69AD;"></i> </h2>
					</div>
					<p>O.S - INSTALAÇÃO</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top: 10px; margin-bottom: 10px;">
		<div class="col-md-12" style="padding: 0 20px;">
			<div class="card" id="charts">
				<div style="display: flex; align-items: top; flex-wrap: wrap; margin-bottom: 5px;">
					<h4 id="charts-title" style="margin-right: auto;">Gráficos:</h4>
					<div style="display: flex; align-items: top; flex-wrap: wrap; margin-bottom: 5px; margin-top: 17px;">

						<button class="btn btn-primary" type="button" id="btnTecnicos" style="margin-right: 10px; margin-bottom: 5px;">Técnicos</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 chart" id="chart-1">
						<h4 style="color: #1C69AD !important;">
							<div style="display: flex; justify-content: space-between; align-items: center;">
								<div style="margin-left: 15px;">
									<img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
									<span style="margin-right: 5px;">Ordens de Serviços</span>
								</div>
								<!-- <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-1" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i> -->
							</div>
						</h4>
						<div style="height: 450px;">
							<div id="loadingMessage1" class="loadingMessage">
								<i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
							</div>
							<div id="myDashBar1" class="ag-theme-alpine my-grid chart-div"></div>
						</div>
					</div>
					<div class="col-md-6 chart" id="chart-2">
						<h4 style="color: #1C69AD !important;">
							<div style="display: flex; justify-content: space-between; align-items: center;">
								<div style="margin-left: 15px;">
									<img src="<?= base_url('assets/css/icon/src/alert-circled.svg') ?>" style="width: 20px; margin-right: 5px;" />
									<span style="margin-right: 5px;">Tipos de Ordens de Serviços</span>
								</div>
								<!-- <i class="fa fa-arrows-alt chart-exp" title="Expandir gráfico" id="exp-2" style="font-size: 16px; color: lightgray; margin-right: 15px;"></i> -->
							</div>
						</h4>
						<div style="position: relative; height: 450px;">
							<div id="loadingMessage2" class="loadingMessage">
								<i class="fa fa-spinner fa-spin" style="font-size: 40px; color: #1C69AD;"></i>
							</div>
							<div id="myDashBar2" class="ag-theme-alpine my-grid chart-div"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="modalTecnicos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalTecnicos" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="titleModalTecnicos">Total de Equipamentos</h4>
			</div>
			<ul class="nav nav-tabs" style="margin-bottom: 15px; margin-left: 5px">
				<li class="nav-item">
					<a id="tab-dadosADevolver" href="" data-toggle="tab" class="nav-link active">Equipamentos a devolver</a>
				</li>
				<li class="nav-item">
					<a id="tab-dadosADevolvidos" href="" data-toggle="tab" class="nav-link" type="button">Equipamentos devolvidos</a>
				</li>
			</ul>
			<div class="modal-body" id="bodyDadosADevolver" style="height: 600px;">
				<div class="col-md-12">
					<input type="text" id="search-input1" placeholder="Buscar" style="width: 100%; margin-bottom: 15px; height:30px; padding-left: 10px;">
					<div id="divDadosEquipamentosADevolver">
						<div class="wrapperEquipamentosDevolver">
							<div id="tableEquipamentosDevolver" class="ag-theme-alpine my-grid-EqpDevolver"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-body" id="bodyDadosDevolvidos" style="height: 600px;" hidden>
				<div class="col-md-12">
					<input type="text" id="search-input2" placeholder="Buscar" style="width: 100%; margin-bottom: 15px; height:30px; padding-left: 10px;">
					<div id="divEquipamentosDevolvidos">
						<div class="wrapperEquipamentosDevolvidos">
							<div id="tableEquipamentosDevolvidos" class="ag-theme-alpine my-grid-EqpDevolvido"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


	<div id="visualizarEquipamentos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<input type="hidden" name="equipamentos" id="idEquipamentos">
				<div class="modal-header header-layout">
					<div class="dropdown" style="margin-right: 10px;">
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="titleEquipamentos"></h4>
				</div>
				<div class="modal-body">
					<div class="col-lg-12">
						<h3 class="subtitle" id="dadosEnvioModal" style="padding-left: 0; padding-right: 0;">Lista dos Equipamentos</h3>
						<div class="row">
							<ul>
								<li id="serialEquip"></li>
							</ul>
						</div>

					</div>
					<div class="modal-footer">
						<div class="footer-group">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<script>
		var Router = '<?= site_url('servicos') ?>';
		var BaseURL = '<?= base_url('') ?>';
		var dados = <?= $os ?>;
		var tecnicos = <?= $tec ?>;

		function ajustarAltura() {
			$(".metrica-card").height('auto');
			var heights = [];
			$(".metrica-card").each(function() {
				heights.push($(this).height());
			});
			var maxHeight = Math.max.apply(null, heights);
			$(".metrica-card").height(maxHeight);
		}

		$(document).ready(function() {

			ajustarAltura();

			$(window).resize(function() {
				ajustarAltura();
			});
		});
	</script>


	<!-- AG Charts Community edition. -->
	<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>
	<!-- Leaflet -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
	<!-- JavaScript -->
	<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
	<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
	<script type="text/javascript" src="<?= versionFile('assets/js/suporte', 'dashboard.js') ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/firmware', 'dashboard.css') ?>">