<style type="text/css">
	@media screen and (min-width: 992px) {
		#modal-veiculos {
			width: 1250px;
			/* New width for large modal */
		}
	}
</style>
<h3>Monitoramento de equipamento violado (s/ alimentação)</h3>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<div class="row">
	<div class="panel panel-danger span12">
		<div class="panel-heading"><i class="fa fa-warning"></i> Veículos com equipamento violado <span class="badge badge-important" id="qtd_veiculos"></span>
			<span class="pull-right"><a href="#" id="controlSound" onclick="violacao.controlSound(this);" title="Ativar/Desativar notificação por áudio" class="btn btn-pri btn-small"><i class="fa fa-bell"></i></a>
			</span>
			<span id="carregando" class="span3 pull-right"></span>
			<input class="clientes" type="text" style="margin-left: 150px;" placeholder="Filtrar por cliente">
			<span class="btn btn-default buscar" style="margin-top: -10px; margin-left: -5px;"><i class="fa fa-search"></i></span>
		</div>
		<div class="panel-body">
			<ul class="thumbnails" id="violacoes">

			</ul>
		</div>
	</div>
</div>

<div id="modal-veiculos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Veículos</h3>
	</div>
	<div class="modal-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Placa</th>
					<th>Voltagem</th>
					<th>Cliente</th>
					<th>Email</th>
					<th>Telefone</th>
					<th>Data/Hora</th>
				</tr>
			</thead>
			<tbody class="data-table">
				<!--<tr>
    				<td>12312332</td>
    				<td>12312332</td>
    				<td>12312332</td>
    				<td>12312332</td>
    				<td>12312332</td>
    				<td>12312332</td>
    			</tr>-->
			</tbody>
			<tfooter>
				<tr>
					<th></th>
					<th>Placa</th>
					<th>Voltagem</th>
					<th>Cliente</th>
					<th>Email</th>
					<th>Telefone</th>
					<th>Data/Hora</th>
				</tr>
			</tfooter>
		</table>
	</div>
	<div class="modal-footer">
		<a href="#" data-dismiss="modal" class="btn btn-default">Fechar</a>
	</div>
</div>

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<audio id="notificacao-audio" loop="true" src="<?php echo base_url('assets/audio/beep.mp3') ?>" preload="auto"></audio>
<script type="text/javascript" src="<?php echo base_url('assets/js/modules/violacao.js') ?>"></script>
<script type="text/javascript">
	var violacao;

	$(document).ready(function() {

		var clientes = <?php echo isset($clientes) ? $clientes : ' '; ?>;
		console.log(clientes)
		$('.clientes').autocomplete({
			source: clientes
		});

		violacao = new ModuleViolacao();
		var baseUrl = '<?php echo base_url() ?>';
		violacao.init(baseUrl);
		violacao.getViolacoes();

		var update = function() {
			violacao.update();
		}

		setInterval(update, 60000);

	});
</script>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
