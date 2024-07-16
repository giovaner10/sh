<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('instalacao') ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?= lang('suporte') ?> >
		<a style="text-decoration: none" href="<?= site_url('servico') ?>"><?= lang('ordem_de_servicos') ?> </a> >
        <?= lang('instalacao') ?>
	</h4>
</div>

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
						<label for="coluna">Tipo de busca:</label>
						<select class="form-control" name="coluna" id="coluna" style="width: 100%;">
							<option value="nome" default>Cliente</option>
							<option value="contratos.id">Contrato</option>
						</select>
					</div>

                    <div class="input-container buscaCliente">
						<label for="nomeCliente">Cliente:</label>
						<input type="text" class="form-control" name="nomeCliente" id="nomeCliente" placeholder="Digite o nome do Cliente...">
					</div>

                    <div class="input-container buscaContrato" style="display: none;">
						<label for="idContrato">Contrato:</label>
						<input type="text" class="form-control" name="idContrato" id="idContrato" placeholder="Digite o número do contrato...">
					</div>

					<div class="button-container">
						<button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
						<button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
					</div>

				</div>
			</form>
		</div>
	</div>

	<div class="col-md-9" id="conteudo">
		<div class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative;'>
			<h3>
				<b style="margin-bottom: 5px;"><?= lang('instalacao') ?>: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
					<div class="dropdown" style="margin-right: 10px; height: 36.5px;">
						<button class="btn btn-primary dropdown-toggle" style="height: 36.5px; border: 0;" type="button" id="dropdownMenuButtonOS" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Gerar OS <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-os" aria-labelledby="dropdownMenuButtonOS" id="dropdownOS" style="top: 62px; text-align: center;">
							<a class="dropdown-item dropdown-item-os" target="_blank" href="<?php echo site_url('servico/instalacao') ?>" title="Instalação">
								Instalação
							</a>
							<a class="dropdown-item dropdown-item-os" style="height: 40px !important;" target="_blank" href="<?php echo site_url('servico/manutencao_troca_retirada') ?>" title="Manutenção/Troca/Retirada">
								Manutenção / Troca / Retirada
							</a>
						</div>
					</div>
					<div class="dropdown" style="margin-right: 10px;">
						<button class="btn btn-primary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button>
				</div>
			</h3>
			<div class="registrosDiv">
				<select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
					<option value="10" selected>10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
				<h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
			</div>
			<div style="position: relative;">
				<div class="wrapper">
					<div id="table" class="ag-theme-alpine my-grid-ordem" style="height: 500px">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/suporte/Instalacao', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/suporte/Instalacao', 'Exportacoes.js') ?>"></script>

<script>
	var Router = '<?= site_url('servico') ?>';
	var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
</script>