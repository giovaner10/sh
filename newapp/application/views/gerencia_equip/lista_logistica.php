<script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/logistica', 'gerencia_equipamentos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/logistica', 'exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/empresa/contato', 'exportacoes.js') ?>"></script>
<link href='<?php echo base_url('assets'); ?>/css/jquery.dataTables.css' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets'); ?>/js/jquery.dataTables.min.js"></script>
<script defer src="<?php echo base_url('assets/js/jquery.dynatable.js') ?>"></script>
<style type="text/css">
	.ordensIguais div {
		margin-bottom: 5px;
	}

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
<br>
<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Logística", site_url('Homes'), "Gestão de equipamentos", "Listagem");
?>


<div id="loading">
	<div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
	<div class="col-md-3">
		<div id="filtroBusca" class="card" style="margin-bottom: 50px;">
			<h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
			<form id="formBusca">
				<div class="form-group filtro">

					<div class="input-container">
						<label for="filtrar-atributos">Nome, E-mail ou Cargo</label>
						<input type="text" name="filtrar-atributos" class="form-control" placeholder="Nome, E-mail ou Cargo" id="filtrar-atributos" />
					</div>


					<div class="button-container">
						<button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>
				</div>
			</form>
		</div>

		<div  class="card acoes-logistica" style="margin-bottom: 50px; height: 180px; display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
			<a class="btn btn-primary" target="_blank" style="margin-top: 25px; width:180px;" href="<?php echo site_url('gerencia_equipamentos/cadOrdem') ?>">Nova Ordem</a>
			<a class="pull-right btn btn-primary btnMarg" target="_blank"  style="width:180px; margin-top:15px; margin-bottom: 15px;" href="<?php echo site_url('gerencia_equipamentos/relatorio') ?>">Detalhes Gerais</a>
			<a class="pull-right btn btn-primary" target="_blank" style="margin-right: 0px; width:180px;" href="<?php echo site_url('gerencia_equipamentos/correspondencias') ?>">Correspondências</a><br /><br />
		</div>
	</div>

	<div class="col-md-9" id="conteudo">

		<div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
			<h3>
				<b id="titulo-card"> Logística de Equipamentos: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">

						<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
								<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
							</div>
						</div>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 15px;">
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
			</div>
		</div>
	</div>
</div>



<div id="modal_ordem_11" class="modal fade">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="color: #1C69AD !important; font-size: 22px !important;">Confirmação de chegada de equipamento.</h4>
			</div>
			<div id="form_1" onkeypress="return disableEnterKey(event)">
				<form method="post" action="<?php echo site_url('gerencia_equipamentos/chegada') ?>" class="form-horizontal">
					<div class="modal-body" style="padding: 30px;">
						<input type="hidden" name="topSecret_1" id="topSecret_1" value="um">
						<input type="hidden" name="upload_1" id="upload_1" value="um">
						<input type="hidden" class="dataCompare" id="dataCompare">
						<div class="form-group">
							<label class="form-label col-sm-3">Ordem número: </label>
							<div class="col-sm-3">
								<input class="form-control" type="text" id="ordem_1" name="ordem_1" readonly>
							</div>
							<label class="form-label col-sm-2">Serial: </label>
							<div class="col-sm-4">
								<input class="form-control" type="text" id="serial_1" name="serial_1" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="form-label col-sm-3 ">Data de chegada: </label>
							<div class="col-sm-3">
								<input class="form-control dataJS" type="text" name="dataChegada_1" id="dataChegada_1" required>
							</div>
						</div>
						<div id="conjunto" class="form-group">
							<div class="col-sm-12">
								<p>Nada</p>
							</div>
							<div class="ordensIguais">
							</div>
							<div class="col-sm-12">
								<div class="col-sm-1" style="width: 10%;">
									<input class="cascatear" type="checkbox" name="cascatear1" id="cascatear1" title="O sistema identificou que você está tentando alterar uma Ordem que possui o mesmo destino de outras Ordens com a mesma Data de Envio, o mesmo Modo de envio e mesmo Tipo de rastreio de envio. Selecione essa checkbox se desejar que a alteração afete todas as demais ordens listadas." value="1">
								</div>
								<label class="form-label col-sm-3">Ativar modo cascata</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input id="submit_1" type="submit" class="btn btn-success" value="Salvar">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</form>
			</div>
			<div id="process_1" class="gravaDados" hidden>
				<img src="<?php echo base_url('newAssets/imagens/loader.gif') ?>" width="80">
				<h4>Gravando dados...</h4>
			</div>
		</div>
	</div>
</div>

<div id="modal_ordem_21" class="modal fade"><!--#Assinar Instalação de OS do tipo: Manutenção-->
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="color: #1C69AD !important; font-size: 22px !important;">Confirmação da instalação de equipamento.</h4>
			</div>
			<div id="form_3">
				<form method="post" action="<?php echo site_url('gerencia_equipamentos/instalacao_1') ?>">
					<div class="modal-body" style="padding: 30px;">
						<p class="h4">Ordem número: <label id="ordem_3"></label></p>
						<div class="form-group codCliente col-sm-12">
							<!--Se o equipamento for de um técnico para cliente o tableOrdem.js adiciona
								um campo para digitar o cliente que irá receber o equipamento-->
						</div>
						<div class="form-group col-sm-12">
							<label class="form-label col-sm-2">Data de instalação: </label>
							<div class="col-sm-4">
								<input class="form-control dataJS" type="text" name="dataInstal_1" id="dataInstal_1" required>
							</div>
							<label style="padding: 0px;" class="form-label col-sm-3">Serial devolvido: </label>
							<div style="padding: 0px;" class="col-sm-3">
								<input class="form-control" type="text" id="serial_old_1" name="serial_old_1" required>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<label for="tipoEnvio" class="col-sm-2 control-label">Tipo: </label>
							<div class="col-sm-4">
								<select class="form-control custom-select" name="tipoEnvio_1" id="tipoEnvio_1">
									<option selected value="0">opções</option>
									<option id="correio" value="1">Correios</option>
									<option id="empregado" value="2">Tam Cargo</option>
									<option id="outro" value="3">Outro...</option>
								</select>
							</div>
							<label style="padding: 0px;" id="informacao" class="form-label col-sm-3">Informações:</label>
							<div style="padding: 0px;" class="col-sm-3">
								<input class="form-control" type="text" id="rastreio_1" name="rastreio_1" required>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<div id="tipoCargo" class="col-sm-6" hidden>
								<label class="col-sm-6 text-center"><input id="ra1" class="form-control" type="radio" name="encomendaT" value="1" checked>Pré-pago</label>
								<label class="col-sm-6 text-center"><input id="ra2" class="form-control" type="radio" name="encomendaT" value="2">Próximo voo</label>
							</div>
							<div id="tipoCorreio" class="col-sm-6" hidden>
								<label class="col-sm-6 text-center"><input id="ra1" class="form-control" type="radio" name="encomendaC" value="1" checked>P.A.C.</label>
								<label class="col-sm-6 text-center"><input id="ra2" class="form-control" type="radio" name="encomendaC" value="2">Sedex</label>
							</div>
						</div>
						<br><br>
						<p>Deseja confirmar a correta instalação do equipamento de serial: <b><label id="serial_3"></label></b> para o seu devido destino?</p>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="topSecret_3" id="topSecret_3" value="tres">
						<input type="hidden" class="dataCompare" id="dataCompare">
						<input type="submit" class="btn btn-success" value="Salvar" id="submit_3">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</form>
			</div>
			<div id="process_3" class="gravaDados" hidden>
				<img src="<?php echo base_url('newAssets/imagens/loader.gif') ?>" width="80">
				<h4>Gravando dados...</h4>
			</div>
		</div>
	</div>
</div>

<div id="modal_ordem_22" class="modal fade"><!--#Assinar Instalação de OS do tipo: Instalação-->
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="color: #1C69AD !important; font-size: 22px !important;">Confirmação da instalação de equipamento.</h4>
			</div>
			<div id="form_4" onkeypress="return disableEnterKey(event)">
				<form method="post" action="<?php echo site_url('gerencia_equipamentos/instalacao_2') ?>" class="form-horizontal">
					<div class="modal-body" style="padding: 30px;">
						<p class="h4">Ordem número: <label id="ordem_4"></label></p>
						<div class="form-group codCliente">
							<!--Se o equipamento for de um técnico para cliente o tableOrdem.js adiciona
								um campo para digira o cliente que irá receber o equipamento-->
						</div>
						<div class="form-group">
							<label class="form-label col-sm-5 ">Data de instalação: </label>
							<div class="col-sm-4">
								<input class="form-control dataJS" type="text" name="dataInstal_2" id="dataInstal_2" required>
							</div>
						</div><br><br>
						<p>Deseja confirmar a correta instalação do equipamento de serial: <b><label id="serial_4"></label></b> para o seu devido destino?</p>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="topSecret_4" id="topSecret_4" value="quatro">
						<input type="hidden" class="dataCompare" id="dataCompare">
						<input type="submit" class="btn btn-success" value="Salvar" id="submit_4">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</form>
			</div>
			<div id="process_4" class="gravaDados" hidden>
				<img src="<?php echo base_url('newAssets/imagens/loader.gif') ?>" width="80">
				<h4>Gravando dados...</h4>
			</div>
		</div>
	</div>
</div>

<!-- Script -->
<script type="text/javascript">
	var Router = '<?= site_url('gerencia_equipamentos/ajaxGetPagination') ?>';
	var RouterGet = '<?= site_url('gerencia_equipamentos/ajaxGetPaginationFind') ?>';
	var BaseURL = '<?= base_url('') ?>';
</script>