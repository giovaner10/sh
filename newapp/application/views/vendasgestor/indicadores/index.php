<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css/vendasgestor', 'anuncios.css') ?>">

<div class="row">
	<div class="col-md-12">
		<h3><?=$titulo?></h3>
	</div>
	<div class="col-sm-12 col-md-12">            
		<div class="well well-small col-md-12">
			<form style="align-items:center" id="formFiltrarIndicadores">
				<div class="col-md-3">
					<i class="fa fa-calendar" style="font-size: 20px;"></i>
					<input style="width:85%" type="date" id="data_inicial" name="data_inicial" class="data formatInput" placeholder="<?=lang('data_inicial')?>" autocomplete="off" value="<?=date('Y-m-d')?>" required />
					<label class="required"></label>
				</div>
				<div class="col-md-3">
					<i class="fa fa-calendar" style="font-size: 20px;"></i>
					<input style="width:85%" type="date" id="data_final" name="data_final" class="data formatInput" placeholder="<?=lang('data_final')?>" autocomplete="off" value="<?=date('Y-m-d')?>" required />
					<label class="required"></label>
				</div>
				<div class="col-md-3">
					<button type="submit" class="btn btn-primary" id="btnFormFiltrarIndicadores"> <?=lang('gerar') ?> </button>
				</div>
			</form>
		</div>
	</div>
	<div class="col-sm-12 col-md-12">
		<table id="tabelaIndicadores" class="table table-bordered table-hover display" style="width:100%">
			<thead>
				<tr>
					<th><?= lang('indicador') ?></th>
					<th><?= lang('tipo') ?></th>
					<th><?= lang('valor') ?></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
    var tabelaIndicadores;
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/vendasgestor/indicadores', 'index.js') ?>"></script>