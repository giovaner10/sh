<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('reclame_aqui') ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?=lang('processos_atendimento')?> >
        <?= lang('reclame_aqui') ?>
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
                    <div class="input-container buscaData serial">
                        <label for="busca">Pesquisar:</label>
                        <input type="text" name="busca" class="form-control" placeholder="Digite a busca" id="busca" />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div id="card-key-account" class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative;'>
			<h3>
				<b style="margin-bottom: 5px;"><?= lang('reclame_aqui') ?>: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="buttonNovoReclameAqui" onclick="formularioNovoReclameAqui()" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
						<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
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
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
            </div>
			<div style="position: relative;">
				<div class="wrapper">
					<div id="table" class="ag-theme-alpine my-grid" style="height: 500px">
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<div id="modalReclameAqui"></div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/ProcessosAtendimento/ReclameAqui', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/ProcessosAtendimento/ReclameAqui', 'exportacoes.js') ?>"></script>

<script>
	var Router = '<?= site_url('ProcessosAtendimento/ReclameAqui') ?>';
	var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
    const canEdit = "<?= $this->auth->is_allowed_block('edi_reclame_aqui_processos_atendimento') ? "true" : "false"; ?>";
    const canDel = "<?= $this->auth->is_allowed_block('del_reclame_aqui_processos_atendimento') ? "true" : "false"; ?>";
    let identificadorModalReclameAqui = 'ReclameAqui';
</script>