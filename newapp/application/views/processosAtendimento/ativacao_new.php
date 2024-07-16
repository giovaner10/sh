<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang('pa_ativacao') ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?=lang('processos_atendimento')?> >
        <?= lang('pa_ativacao') ?>
	</h4>
</div>

<div id="loading">
	<div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-12" id="conteudo">
        <div id="card-agendamento" class="card-conteudo card-ordem" style='margin-bottom: 20px; position: relative;'>
			<h3>
				<b style="margin-bottom: 5px;"><?= lang('pa_ativacao') ?>: </b>
				<div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="btnAddAtivacao" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
					<div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
						<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
							<?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
						</div>
					</div>
					<!-- <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;margin-bottom: 5px;">
						<img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
					</button> -->
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
                <input class="form-control inputBusca" type="text" id="search-input" placeholder="Pesquisar" style="margin-bottom: 10px;">
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

<div id="modal_processo" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_processo" enctype="multipart/form-data">
                <input type="hidden" id="processoId" name="processoId" value="<?=isset($processo->id) ? $processo->id : ''?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="modalLabel">Adicionar Ativação</h3>
                </div>
                <div class="modal-body">
                
                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="processo">Título:</label>
                        <input type="text" id="processo" name="processo" placeholder="Digite o título..." maxlength="100" class="form-control" required
                            value="">
                    </div>
                    <div class="col-md-12 input-container form-group">
                        <label class="<?=isset($processo->id) ? '' : 'control-label' ?>" id="label-arquivo" for="arquivo"><?=lang("arquivo")?> (PDF):</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($processo->id) ? '' : 'required'?>>
                    </div>
                    
                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                        <button type="submit" class="btn btn-success" id="buttonSalvarProcesso"><?=lang("salvar")?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/ProcessosAtendimento/Ativacao', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/ProcessosAtendimento/Ativacao', 'exportacoes.js') ?>"></script>

<script>
	var Router = '<?= site_url('ProcessosAtendimento/Ativacao') ?>';
	var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
    let identificadorModal = 'Ativacao'; 
    let canEdit = "<?= $this->auth->is_allowed_block('edi_agendamento_processos_atendimento') ? "true" : "false"; ?>";
    let canDel = "<?= $this->auth->is_allowed_block('del_agendamento_processos_atendimento') ? "true" : "false"; ?>";
</script>