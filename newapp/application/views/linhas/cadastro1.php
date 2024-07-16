<link href="<?php echo base_url('assets');?>/css/select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url('assets');?>/js/select2.min.js"></script>
<style type="text/css">
.select2 {
	display: none;
}

.status {
	border: 1px solid black;
	border-radius: 50%;
	width: 18px;
	height: 18px;
}
.cadastrada {
	background-color: blue;
}
.habilitada {
	background-color: orange;
}
.ativa {
	background-color: green;
}
.cancelada {
	background-color: yellow;
}
.suspensa {
	background-color: purple;
}
.bloqueada {
	background-color: red;
}
.erro {
	background-color: grey;
}
</style>
<h3>Lista de Chips</h3>
<hr class="featurette-divider">
<!-- <div>
    <form action="<?= site_url('linhas/analise_contaVivo') ?>" method="POST" enctype="multipart/form-data">
        <h5>Importar Relatório - Contas VIVO</h5>
        <input type="file" name="conta" />
        <button class="btn btn-info" type="submit"><i class="fa fa-cloud-upload"></i></button>
    </form>
</div> -->
<div class="row-fluid">
	<div class="well well-small">
		<div class="btn-group">
			<div class="btn-group" style="margin-right: 50px;">
				<a href="<?php echo site_url('linhas/cadastrar')?>" class="btn btn-primary" data-toggle="modal" data-target="#modal_adicionar_chip" title="Adicionar Chip"> <i
					class="fa fa-plus"></i>
					Cadastrar
				</a>
			</div>
		</div>
<form action="" method="get" class="form-inline pull-right">
	<?php if ($this->input->get()): ?>
		<a href="<?php echo site_url('linhas/listar');?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Voltar</a>
	<?php endif ?>
	<input class="form-control palavra" type="text" name="palavra" value="" placeholder="Palavra" data-provide="typeahead" autocomplete="off" data-items="6" data-source='<?php echo $j_linhas ?>' />
	<select id="example" class="form-control cliente" name="cliente" style="display: none">
		<option value="">Selecione um Cliente</option>
		<?php foreach ($clientes as $cliente): ?>
			<option><?= $cliente->nome ?></option>
		<?php endforeach; ?>
	</select>
	<select id="sec" class="form-control" name="coluna" style="width: inherit;">
		<option value="numero">Linha</option>
		<option value="ccid">CCID</option>
		<option value="nome">Cliente</option>
	</select>
	<button type="submit" class="btn"><i class="icon-search"></i></button>
</form>
	</div>
</div>
<br style="clear:both" />
<div class="col-sm-12" style="float: none; margin-left: auto; margin-right: auto;">
	<?php if ($this->session->flashdata('sucesso')): ?>
		<div class="alert alert-success">
			<p><?php echo $this->session->flashdata('sucesso') ?></p>
		</div>
	<?php endif; ?>
	<?php if ($this->session->flashdata('erro')): ?>
		<div class="alert alert-error">
			<p><?php echo $this->session->flashdata('erro') ?></p>
		</div>
	<?php endif; ?>
	<?php if ($linhas): ?>
		<table class="table table-hover">
			<tr>
				<th class="span1">Id</th>
				<th class="span2">Ccid</th>
				<th class="span2">Linha</th>
				<th class="span2">Operadora</th>
				<th class="span2">Conta</th>
				<th class="span2">Serial</th>
				<th class="span2">Última Atualização</th>
				<th class="span4">Cliente</th>
				<th class="span2">Vinc. Auto</th>
				<th class="span2">Prestadora</th>
				<th class="span2">Data Cadastro</th>
				<th class="span2">Status</th>
				<th class="span1">Administrar</th>
			</tr>
			<?php foreach ($linhas as $linha): ?>
				<?php
				switch ($linha->status) {
					case 'cadastrada':
					$estado="<span style='background-color: blue;' class='badge badge-cadastrada'>CADASTRADA</span>";
					break;
					case 'habilitada':
					$estado="<span style='background-color: orange;' class='badge badge-habilitada'>HABILITADA</span>";
					break;
					case 'ativa':
					$estado="<span style='background-color: green;' class='badge badge ativa'>ATIVA</span>";
					break;
					case 'cancelada':
					$estado="<span style='background-color: yellow;' class='badge badge-cancelada'>CANCELADA</span>";
					break;
					case 'bloqueada':
					$estado="<span style='background-color: red;' class='badge badge-bloqueada'>BLOQUEADA</span>";
					break;
					case 'suspensa':
					$estado="<span style='background-color: purple;' class='badge badge-suspensa'>SUSPENSA</span>";
					break;
					default:
					$estado="<span style='background-color: grey;' class='badge badge-erro'>ERRO</span>";
					break;
				}
				?>
				<tr>
					<td><?php echo $linha->id ?></span></td> 
					<td><?php echo $linha->ccid ?></td> 
					<td><?php echo $linha->numero ?></td> 
					<td><?php echo $linha->operadora ?></td>   
					<td><?php echo $linha->conta ?></td>  
					<td><?php echo $linha->serial ?></td>
					<td><?php if ($linha->ultima_atualizacao_chip) {
						echo date('d-m-Y H:i:s',strtotime($linha->ultima_atualizacao_chip));
					} ?></td>
					<td>
						<?php
						if ($linha->nome_sim)
							echo $linha->nome_sim;
						else
							echo $linha->nome;
						?>
					</td>
					<td><?php echo $linha->ccid_auto ?></td>
					<td>
						<?php
						if ($linha->nome_sim)
							echo "SIMM2M";
						elseif ($linha->info == 'TRACKER')
							echo "SHOW TECNOLOGIA";
						elseif ($linha->info == 'NORIO')
							echo "NORIO EPP";
						elseif ($linha->info == 'EUA')
							echo "SHOW TECHNOLOGY EUA";
						?>
					</td>
					<td><?php echo dh_for_humans($linha->data_cadastro) ?></td>  
					<td><?php echo $estado ?></td>  
					<td>
						<a href="<?php echo site_url('linhas/editar/'.$linha->id)?>" class="btn btn-info" title="Editar Chip">
							<i class="fa fa-edit"></i>
						</a>
					</td>
				</tr>	
			<?php endforeach ?>
		</table> 
		<?php else: ?>
			<div class="alert alert-block">
				<h2>Não há chips</h2>
				<h4>Nenhum chip encontrado!</h4>
			</div>
		<?php endif ?>
		<div class="pagination" style="float: right;">
			<?php echo $this->pagination->create_links(); ?> 
		</div>
	</div>
<!-- MODAL CADASTRAR CHIP -->
<div id="modal_adicionar_chip" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cadastrar Chip</h4>
			</div>
			<div class="modal-body">
				<p>Carregando...</p>
			</div>
		</div>
	</div>
</div>
<!-- MODAL EDITAR CHIP -->
<div id="modal_editar_chip0" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Editar Chip</h4>
			</div>
			<div class="modal-body">
				<p id="id_f">Carregando...</p>
			</div>
		</div>
	</div>
	<!-- MODAL CADASTRAR CHIP -->
	<div id="modal_adicionar_chip" class="modal fade" tabindex="-1" 
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true"></button>
		<h4 id="myModalLabel1">Cadastrar Chip</h4>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>
<!-- MODAL EDITAR CHIP -->
<div id="modal_editar_chip0" class="modal fade" tabindex="-1" 
role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
	aria-hidden="true">&times;</button>
	<h4 id="myModalLabel1">Editar Chip</h4>
</div>
<div class="modal-body">
	<p id="id_f">Carregando...</p>
</div>
</div>
<script>
$('#example').select2({
	placeholder: 'Selecione um cliente'
});

$('#sec').on('change', function () {
	var tipo = $('#sec option:selected').val();

	if (tipo == 'nome') {
		$('.palavra').attr('style', 'display:none');
		$('.select2').attr('style', 'display:inline-block');
	} else {
		$('.palavra').removeAttr('style');
		$('.select2').attr('style', 'display:none');
	}
});	

$('#cad_cliente').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget)
	var id = button.data('whateverid')

	var modal = $(this)
	modal.find('#id_f').val(id)

})
</script>