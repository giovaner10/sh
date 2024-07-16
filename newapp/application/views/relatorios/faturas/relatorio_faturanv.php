<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<!-- SELECT2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<!-- Traduções -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/pt-BR.js"></script>
<!---------------->

<style type="text/css">
	.select2-container--default .select2-selection--multiple .select2-selection__choice {
		border: none !important;
		margin-top: 8px !important;
		margin-bottom: 5px !important;
	}

	html {
		scroll-behavior: smooth
	}

	body {
		background-color: #fff !important;
	}

	table {
		width: 100% !important;
	}

	.blem {
		color: red;
	}

	.container-fluid {
		padding: 0;
	}

	.dataTables_wrapper .dataTables_processing {
		background: none;
	}

	.dataTables_wrapper .dataTables_processing div {
		display: inline-block;
		vertical-align: center;
		font-size: 68px;
		height: 100%;
		top: 0;
	}

	th,
	td.wordWrap {
		max-width: 100px;
		word-wrap: break-word;
		text-align: center;
	}

	.checkbox label {
		font-weight: 700;
	}

	.select-container .select-selection--single {
		height: 35px !important;
	}

	.my-1 {
		margin-top: 1em !important;
		margin-bottom: 1em !important;
	}

	.mx-1 {
		margin-left: 1em;
		margin-right: 1em;
	}

	.pt-1 {
		padding-top: 1em;
	}

	.d-flex {
		display: flex;
	}

	.justify-content-between {
		justify-content: space-between;
	}

	.justify-content-end {
		justify-content: flex-end;
	}

	.align-center {
		align-items: center;
	}

	.modal-xl {
		max-width: 1300px;
		width: 100%;
	}

	.border-0 {
		border: none !important;
	}

	.markerLabel {
		background-color: #fff;
		border-radius: 4px;
		padding: 4px;
	}

	.action-bar * {
		margin-left: 5px;
	}

	.select-selection--multiple .select-search__field {
		width: 100% !important;
	}

	.bold-text {
		font-weight: bold;
	}


	.select2-container .select2-search--inline {
		float: none;
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice__display {
		margin: 10px;
	}
</style>




<?php
include(dirname(__FILE__) . '/../../../componentes/comum/comum.php');
tituloPaginaComponente("Relatório Faturas", site_url('Homes'), "Relatórios", "Financeiro > Faturas");
?>


<div id="loading">
	<div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
	<div class="col-md-3" id="menu_nodes">
		<div id="filtroBusca" class="card " style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important; margin-left: -5px !important;">Parâmetros</h4>
			<form style="align-items:center" action="#" id="formRel" method="POST">

				<input type="checkbox" id="checkboxPeriodo" name="checkboxPeriodo" value="false" style="display: none;" checked>

				<div class="input-container disponibilidade" id="disponibilidadeContainer" style='margin-bottom: 20px; position: relative;'>
					<strong>Selecione o(s) cliente(s):</strong>
					<select class="js-example-basic-multiple form-control" name="cliente[]" multiple="multiple" autocomplete="off" style="width: 100%;" id="teste">
					</select>
				</div>


				<div class="input-container" id="dt_ini" style='margin-bottom: 20px; position: relative;'>
					<label for="dt_ini">Data Inicial:</label>
					<input type="date" name="dt_ini" class="form-control" autocomplete="off" id="dp1" value="<?php echo $this->input->post('dt_ini') ?>" required />
				</div>

				<div class="input-container" id="dt_fim" style='margin-bottom: 20px; position: relative;'>
					<label for="dt_fim">Data Final:</label>
					<input type="date" name="dt_fim" class="form-control" autocomplete="off" id="dp2" value="<?php echo $this->input->post('dt_fim') ?>" required />
				</div>


				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<label for="vendedor">Vendedor:</label>
					<select id="vendedor" name="vendedor" class="form-control" style="width: 100%;">
						<option value="todos" <?= $this->input->post() ? ($_POST['vendedor'] == 'todos' ? 'selected' : '') : '' ?>>Todos</option>
						<?php foreach ($vendedores as $vend) : ?>
							<option value="<?= $vend->id ?>" <?= $this->input->post() ? ($_POST['vendedor'] == $vend->id ? 'selected' : '') : '' ?>><?= strtoupper($vend->nome) ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<label for="emp">Empresa:</label>
					<select id="emp" name="emp" class="form-control" style="width: 100%;">
						<option value="todos">Todas</option>
						<option value="TRACKER">SHOW TECNOLOGIA</option>
						<option value="NORIO">SIGA-ME RASTREAMENTO</option>
						<option value="SIMM2M">SIMM2M</option>
						<option value="EUA">SHOW TECHNOLOGY EUA</option>
						<option value="SHOW_CURITIBA"> SHOW CURITIBA </option>
					</select>
				</div>

				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<label for="orgao">Órgão:</label>
					<select name="orgao" id="orgao" style="width: 100%;" class="form-control">
						<option value="">Ambos</option>
						<option value="privado">Privado</option>
						<option value="publico">Público</option>
					</select>
				</div>

				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<label for="uf">UF:</label>
					<select id="uf" name="uf" style="width: 100%;" class="form-control">
						<option value="">UF</option>
						<option value="AC-BR">AC - BR</option>
						<option value="AL-BR">AL - BR</option>
						<option value="AP-BR">AP - BR</option>
						<option value="AM-BR">AM - BR</option>
						<option value="BA-BR">BA - BR</option>
						<option value="CE-BR">CE - BR</option>
						<option value="DF-BR">DF - BR</option>
						<option value="ES-BR">ES - BR</option>
						<option value="GO-BR">GO - BR</option>
						<option value="MA-BR">MA - BR</option>
						<option value="MT-BR">MT - BR</option>
						<option value="MS-BR">MS - BR</option>
						<option value="MG-BR">MG - BR</option>
						<option value="PA-BR">PA - BR</option>
						<option value="PB-BR">PB - BR</option>
						<option value="PR-BR">PR - BR</option>
						<option value="PE-BR">PE - BR</option>
						<option value="PI-BR">PI - BR</option>
						<option value="RJ-BR">RJ - BR</option>
						<option value="RN-BR">RN - BR</option>
						<option value="RS-BR">RS - BR</option>
						<option value="RO-BR">RO - BR</option>
						<option value="RR-BR">RR - BR</option>
						<option value="SC-BR">SC - BR</option>
						<option value="SP-BR">SP - BR</option>
						<option value="SE-BR">SE - BR</option>
						<option value="TO-BR">TO - BR</option>
						<option value="AK-EUA">AK - EUA</option>
						<option value="AL-EUA">AL - EUA</option>
						<option value="AR-EUA">AR - EUA</option>
						<option value="AZ-EUA">AZ - EUA</option>
						<option value="CA-EUA">CA - EUA</option>
						<option value="CO-EUA">CO - EUA</option>
						<option value="CT-EUA">CT - EUA</option>
						<option value="DE-EUA">DE - EUA</option>
						<option value="FL-EUA">FL - EUA</option>
						<option value="GA-EUA">GA - EUA</option>
						<option value="HI-EUA">HI - EUA</option>
						<option value="IA-EUA">IA - EUA</option>
						<option value="ID-EUA">ID - EUA</option>
						<option value="IL-EUA">IL - EUA</option>
						<option value="IN-EUA">IN - EUA</option>
						<option value="KS-EUA">KS - EUA</option>
						<option value="KY-EUA">KY - EUA</option>
						<option value="LA-EUA">LA - EUA</option>
						<option value="MA-EUA">MA - EUA</option>
						<option value="MD-EUA">MD - EUA</option>
						<option value="ME-EUA">ME - EUA</option>
						<option value="MI-EUA">MI - EUA</option>
						<option value="MN-EUA">MN - EUA</option>
						<option value="MO-EUA">MO - EUA</option>
						<option value="MS-EUA">MS - EUA</option>
						<option value="MT-EUA">MT - EUA</option>
						<option value="NC-EUA">NC - EUA</option>
						<option value="ND-EUA">ND - EUA</option>
						<option value="NE-EUA">NE - EUA</option>
						<option value="NH-EUA">NH - EUA</option>
						<option value="NJ-EUA">NJ - EUA</option>
						<option value="NM-EUA">NM - EUA</option>
						<option value="NV-EUA">NV - EUA</option>
						<option value="NY-EUA">NY - EUA</option>
						<option value="OH-EUA">OH - EUA</option>
						<option value="OK-EUA">OK - EUA</option>
						<option value="OR-EUA">OR - EUA</option>
						<option value="PA-EUA">PA - EUA</option>
						<option value="RI-EUA">RI - EUA</option>
						<option value="SC-EUA">SC - EUA</option>
						<option value="SD-EUA">SD - EUA</option>
						<option value="TN-EUA">TN - EUA</option>
						<option value="TX-EUA">TX - EUA</option>
						<option value="UT-EUA">UT - EUA</option>
						<option value="VA-EUA">VA - EUA</option>
						<option value="VT-EUA">VT - EUA</option>
						<option value="WI-EUA">WI - EUA</option>
						<option value="WA-EUA">WA - EUA</option>
						<option value="WI-EUA">WI - EUA</option>
						<option value="WV-EUA">WV - EUA</option>
						<option value="WY-EUA">WY - EUA</option>
					</select>
				</div>

				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<label for="tipoAtividade">Tipo de Atividade</label>
					<select name="tipoAtividade" id="tipoAtividade" style="width: 100%;" class="form-control">
						<option value="" selected>Todos</option>
						<option value="1">Atividade de Monitoramento</option>
						<option value="2">Serviços Técnicos</option>
						<option value="3">Aluguel de Outras Máquinas e Equipamentos</option>
						<option value="4">Suporte técnico, manutenção e outros serviços em tecnologia da informação</option>
						<option value="5">Desenvolvimento e licenciamento de programas de computador customizáveis</option>
						<option value="6">Serviços combinados de escritório e apoio administrativo</option>
						<option value="0">Outros</option>
					</select>
				</div>

				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<label for="informacoes">Parcerio/Cliente:</label>
					<select name="informacoes" id="informacoes" style="width: 100%;" class="form-control">
						<option value="all" selected>Todos</option>
						<option value="cliente">Clientes</option>
						<option value="parceiro">Parceiros</option>
					</select>
				</div>

				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<label for="status_financeiro_cliente">Status Cliente:</label>
					<select name="status_financeiro_cliente" id="status_financeiro_cliente" style="width: 100%;" class="form-control">
						<option value="">Todos</option>
						<option value="1">Ativos</option>
						<option value="5">Inativos</option>
						<option value="7">Negativados</option>
					</select>
				</div>
<!-- 
				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<strong>Tipo de órgão:</strong>
					<input type="radio" name="tipo_pessoa[]" value="all"> Todos
					<input type="radio" name="tipo_pessoa[]" value="pessoaFisica"> Físico
					<input type="radio" name="tipo_pessoa[]" value="pessoaJuridica"> Jurídico
				</div> -->


				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<strong> Status:</strong>
					<input type="checkbox" value="0" name="status_fatura[]"> Pendente
					<input type="checkbox" value="1" name="status_fatura[]"> Pago
					<input type="checkbox" value="3" name="status_fatura[]"> Cancelado
				</div>

				<div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
					<strong>Agrupar por Cliente:</strong>
					<input type="checkbox" value="1" name="agrupar" title="Agrupar por cliente">
				</div>


				<div class="button-container">
					<button type="submit" class="btn btn-success gerar_rel" style='margin-bottom: 10px; position: relative; width: 100%;' id="gerarRelatorioIscas"><i class="fa fa-search" aria-hidden="true"></i> Gerar Relatório</button>
					<button class="btn btn-default" style='width:100%; margin-bottom: 20px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
				</div>
				<!-- 
				<div class="button-container" style='margin-bottom: 20px; position: relative;'>
					<button style='width:100%' id="BtnLimpar" class="btn btn-default" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> <?= lang('limpar') ?></button>
				</div> -->

			</form>
		</div>
	</div>
	<div class="col-md-9" id="conteudo">

		<div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px;'>
			<h3>
				<b id="titulo-card">Relatório: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">

					<!-- <div class="legend" style="margin-bottom: 10px !important;">
						<h5 class="bold-text">Faturado: <span id="valor_total">R$ 0,00</span> | Taxa(s): <span id="valor_taxa">R$ 0,00</span> | Pago: <span id="valor_pago">R$ 0,00</span> | Líquido: <span id="valor_liquido">R$ 0,00</span></h5>
					</div> -->

					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
						<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px; margin-right: 10px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
			<div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
				<div class="registrosDiv">
					<select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
						<option value=10 selected>10</option>
						<option value=25>25</option>
						<option value=50>50</option>
						<option value=100>100</option>
					</select>
					<h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
				</div>
				<input class="form-control inputBusca" type="text" id="search-input" placeholder="Pesquisar" style="margin-bottom: 10px;">
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
			</div>
			<div class="legend" style="margin-bottom: 10px !important;">
				<h5 class="bold-text">Total Faturado: <span id="valor_total">R$ 0,00</span> | Total Taxa(s): <span id="valor_taxa">R$ 0,00</span> | Total Pago: <span id="valor_pago">R$ 0,00</span> | Valor Líquido: <span id="valor_liquido">R$ 0,00</span></h5>
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
<script type="text/javascript" src="<?= versionFile('assets/js/fatura', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
	var BaseURL = '<?= base_url('') ?>';
</script>


<script type="text/javascript">
	jQuery(function($) {
		var localeText = AG_GRID_LOCALE_PT_BR;
		var gridDiv = document.querySelector("#tableContatos");

		$('.js-example-basic-multiple').select2({
			ajax: {
				url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
				dataType: 'json',
			},
			placeholder: 'Selecione um vendedor',
			language: "pt-BR"
		});

		$('select[name=vendedor]').select2({
			language: "pt-BR",
		});
		$('select[name="vendedor"]').next('.select2-container').find('.select2-selection').css('height', '33px');

		var dataInicio = '';
		var dataFim = '';

		var columnDefs = [{
				headerName: "Cód Fatura",
				field: "id",
				valueGetter: function(params) {
					return params.data.id == null ? "" : params.data.id;
				}
			},
			{
				headerName: "Cliente",
				field: "nome",
				valueGetter: function(params) {
					return params.data.nome == null ? "" : params.data.nome;
				}
			},
			{
				headerName: "CNPJ/CPF",
				field: "cnpj_cpf",
				valueGetter: function(params) {
					return params.data.cnpj_cpf == null ? "" : params.data.cnpj_cpf;
				}
			},
			{
				headerName: "Status do Cli.",
				field: "status_cliente",
				valueGetter: function(params) {
					return params.data.status_cliente == null ? "" : params.data.status_cliente;
				}
			},
			{
				headerName: "Tipo do Cli.",
				field: "orgao",
				valueGetter: function(params) {
					return params.data.orgao == null ? "" : params.data.orgao;
				}
			},
			{
				headerName: "Prestadora",
				field: "prestadora",
				valueGetter: function(params) {
					return params.data.prestadora == null ? "" : params.data.prestadora;
				}
			},
			{
				headerName: "Data de Venc.",
				field: "data_vencimento",
				valueGetter: function(params) {
					return params.data.data_vencimento == null ? "" : params.data.data_vencimento;
				}
			},
			{
				headerName: "Data de Emissão",
				field: "data_emissao",
				valueGetter: function(params) {
					return params.data.data_emissao == null ? "" : params.data.data_emissao;
				}
			},
			{
				headerName: "Valor Fatura",
				field: "valor_total",
				valueGetter: function(params) {
					return params.data.valor_total == null ? "" : params.data.valor_total;
				}
			},
			{
				headerName: "Taxas e Juros",
				field: "total_taxa",
				valueGetter: function(params) {
					return params.data.total_taxa == null ? "" : params.data.total_taxa;
				}
			},
			{
				headerName: "Nº Nota Fiscal",
				field: "nota_fiscal",
				valueGetter: function(params) {
					return params.data.nota_fiscal == null ? "" : params.data.nota_fiscal;
				}
			},
			{
				headerName: "Mês de Referência",
				field: "mes_referencia",
				valueGetter: function(params) {
					return params.data.mes_referencia == null ? "" : params.data.mes_referencia;
				}
			},
			{
				headerName: "Inicío do P.",
				field: "periodo_inicial",
				valueGetter: function(params) {
					return params.data.periodo_inicial == null ? "" : params.data.periodo_inicial;
				}
			},
			{
				headerName: "Fim do P.",
				field: "periodo_final",
				valueGetter: function(params) {
					return params.data.periodo_final == null ? "" : params.data.periodo_final;
				}
			},
			{
				headerName: "Data Pag.",
				field: "data_pagto",
				valueGetter: function(params) {
					return params.data.data_pagto == null ? "" : params.data.data_pagto;
				}
			},
			{
				headerName: "Valor Pago.",
				field: "valor_pago",
				valueGetter: function(params) {
					return params.data.valor_pago == null ? "" : params.data.valor_pago;
				}
			},
			{
				headerName: "Forma Pag.",
				field: "tipo_pag",
				valueGetter: function(params) {
					return params.data.tipo_pag == null ? "" : params.data.tipo_pag;
				}
			},
			{
				headerName: "Fim do Contrato",
				field: "fim_contrato",
				valueGetter: function(params) {
					return params.data.fim_contrato == null ? "" : params.data.fim_contrato;
				}
			},
			{
				headerName: "Ger.",
				field: "generator",
				valueGetter: function(params) {
					return params.data.generator == null ? "" : params.data.generator;
				}
			},
			{
				headerName: "Status",
				field: "status_fatura",
				valueGetter: function(params) {
					return params.data.status_fatura == null ? "" : params.data.status_fatura;
				}
			},
			{
				headerName: "Atividade de Serviço",
				field: "atividade_fatura",
				valueGetter: function(params) {
					return params.data.atividade_fatura == null ? "" : params.data.atividade_fatura;
				}
			}
		];


		const gridOptions = {
			columnDefs: columnDefs,
			rowData: [],
			pagination: true,
			defaultColDef: {
				resizable: true,
			},
			sideBar: {
				toolPanels: [{
					id: "columns",
					labelDefault: "Colunas",
					iconKey: "columns",
					toolPanel: "agColumnsToolPanel",
					toolPanelParams: {
						suppressRowGroups: true,
						suppressValues: true,
						suppressPivots: true,
						suppressPivotMode: true,
						suppressColumnFilter: false,
						suppressColumnSelectAll: false,
						suppressColumnExpandAll: true,
						width: 100,
					},
				}, ],
				defaultToolPanel: false,
			},
			paginationPageSize: $("#select-quantidade-por-contatos-corporativos").val(),
			localeText: localeText,
		};

		new agGrid.Grid(gridDiv, gridOptions);
		preencherExportacoes(gridOptions);

		function updateData(newData) {
			gridOptions.api.setRowData(newData);
		}

		$('#search-input').on('input', function() {
			var searchInput = $('#search-input').val();
			gridOptions.api.setQuickFilter(searchInput);
		});

		$("#BtnLimparFiltro").on("click", function() {
			$("#formRel")[0].reset();
			$('#search-input').val('').trigger('input');
			$('.js-example-basic-multiple').val('').trigger('change');
			$('select[name=vendedor]').val('todos').trigger('change');
			updateData([]);
		});

		var menuAberto = false;
		$(".btn-expandir").on("click", function(e) {
			e.preventDefault();
			menuAberto = !menuAberto;

			if (menuAberto) {
				$(".img-expandir").attr(
					"src",
					`${BaseURL}/assets/images/icon-filter-show.svg`
				);
				$("#menu_nodes").hide();
				$("#conteudo").removeClass("col-md-9");
				$("#conteudo").addClass("col-md-12");
			} else {
				$(".img-expandir").attr(
					"src",
					`${BaseURL}/assets/images/icon-filter-hide.svg`
				);
				$("#menu_nodes").show();
				$("#conteudo").css("margin-left", "0px");
				$("#conteudo").removeClass("col-md-12");
				$("#conteudo").addClass("col-md-9");
			}
		});


		$('#formRel').submit(async function(event) {
			event.preventDefault()
			$('#search-input').val('').trigger('input');
			updateData([]);
			$(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');

			dataInicio = ($('input[name=dt_ini]').val()).split('-').reverse().join('/');
			dataFim = ($('input[name=dt_fim]').val()).split('-').reverse().join('/');

			let orgao = $('#orgao').val();
			let vendedor = $('#vendedor').val();
			let uf = $('#uf').val();
			let prestadora = $('#emp').val();

			if (vendedor === 'todos' && prestadora == 'todos' && orgao == '' && uf == '') {
				showAlert('warning','Não é permitido gerar esse relatório sem nenhum filtro. (Orgão, Vendedor, Prestadora ou Estado');
				updateData([]);

			} else {
				$('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');

				let data_ini = $('#dp1').val();
				data_ini = data_ini.split('-').reverse().join('/');
				let dataFim = $('#dp2').val();
				dataFim = dataFim.split('-').reverse().join('/');

				let total_fatura = 0.0;
				let total_taxa = 0.0;
				let total_pago = 0.0;
				let total_liquido = 0.0;

				var filter = $(this).serialize() + '&dataInicio=' + data_ini + '&dataFim=' + dataFim;
				await $.ajax({
					url: '<?= site_url('relatorios/ajaxRelFaturas') ?>',
					type: 'POST',
					dataType: 'json',
					data: filter,
					success: function(callback) {
						if (callback.status == 'OK') {

							updateData(callback.dataag);
							preencherExportacoes(gridOptions);

							total_fatura += parseFloat(callback.tfooter.total_fatura);
							total_taxa += parseFloat(callback.tfooter.total_taxa);
							total_pago += parseFloat(callback.tfooter.total_pago);
							total_liquido += parseFloat(callback.tfooter.total_liquido);

							$('#valor_total').text('R$ ' + total_fatura.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
							$('#valor_taxa').text('R$ ' + total_taxa.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
							$('#valor_pago').text('R$ ' + total_pago.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
							$('#valor_liquido').text('R$ ' + total_liquido.toLocaleString('pt-br', {
								minimumFractionDigits: 2
							}));
						} else {
							alert(callback.msg);
							return;
						}
					},
					error: function() {
						updateData([]);
					},
					complete: function() {
						$('.gerar_rel').removeAttr('disabled').html('<i class="fa fa-search" aria-hidden="true"></i> Gerar Relatório');
					}
				});
			}
		});
	});
</script>