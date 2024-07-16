<?php $logado = 425 ?>
<style>
	.typeahead	{
		width: 475px;
	}
	table > tbody > .tr-topo:hover{
		background: black!important;
	}
	.linha:hover{
		background: white;
	}
    thead{    background: darkslateblue;}
    .tr-topo{
		background: #09090bcc;
		color: white;
	}
	.tr-topo > th{
		text-align: center;
	}
	.tb-center > tr > td{
		text-align: center;
	}
	.btn-finish{
		background: #468847;
		color: white;
		text-shadow: none;
	}
	.btn-finish:hover{
		background: #2b572b;
		color: white;
	}
	.btn-aguard{
		background: #f89406;
		color: white;
		text-shadow: none;
	}
	.btn-aguard:hover{
		background: #a26206;
		color: white;
	}
	.btn-all{
		background: #05aefe;
		color: white;
		text-shadow: none;
	}
	.btn-all:hover{
		background: #0578ad;
		color: white;
	}
	body{
		background: #f5f5f5;
	}
	#well{
		border: none;
		box-shadow: none;
	}
	.inputs {
		width: 110px;
	}
    .blem{
         color: red;
     }
</style>
<h3>Gerenciador de Tickets</h3>

<hr class="featurette-divider">

<div id="well" class="well well-small">

	<a href="<?php echo site_url('webdesk')?>" title="" class="btn btn-all"> Todos</a>
	<a href="<?php echo site_url('webdesk/abertos')?>" title="" class="btn btn-aguard"> Em andamento</a>
	<a href="<?php echo site_url('webdesk/fechados')?>" title="" class="btn btn-finish"> Concluídos</a>

	<form action="" method="get" class="form-horizontal pull-right">
		<div class="input-prepend">
			<select  class="form-control custom-select inputs" name="tipoPes" id="tipoPes">
				<option selected value="0">#Ticket</option>
			  	<option id="cli" value="1">Cliente</option>
			</select>
		</div>
		
		<input type="text" id="palavra" name="palavra" value="" placeholder="Número do ticket" required class="span2 inputs"
			   data-provide="typeahead" autocomplete="off"
			   data-items="6" data-source='<?php echo $lista_pesquisa ?>'/>

		<div class="wrapper input-prepend call">
			<span class="add-on"><i class="fa fa-calendar"></i></span>
			<input class="inputs calendarioos" id="data_inicial" name="data_inicial" type="text" value="" placeholder="Data inicial"/>
		</div>

		<div class="wrapper input-prepend call">
			<span class="add-on"><i class="fa fa-calendar"></i></span>
			<input class="inputs calendarioos" id="data_final" name="data_final" type="text" value="" placeholder="Data final"/>
		</div>
		<button id="btnSearch" type="submit" class="btn btn-small btn-info"><i class="fa fa-search" style="color: white"></i></button>
	</form>

</div>

<br style="clear:both" />

<div class="container-fluid" style="float: none; margin-left: auto; margin-right: auto;">

	<?php if ($logado != 425): ?>

		<?php if ($tickets): ?>

			<h4>Tickets Abertos</h4>


			<table class="table table-bordered table-hover">

			<thead>

			<tr class="tr-topo">
				<th>Id</th>
				<th>Depart</th>
				<th>Assunto</th>
				<th>Última int</th>
				<th>Prestadora</th>
				<th>Status</th>
				<th>Visualizar</th>
			</tr>
			</thead>
			<tbody class="tb-center">

			<?php foreach ($tickets as $ticket): ?>

				<tr>
					<td><?php echo $ticket->id ?></td>
					<td><?php echo $ticket->departamento ?></td>
					<td><?php echo $ticket->assunto ?></td>
					<td><?php echo dh_for_humans($ticket->ultima_interacao) ?></td>
					<td>
                            <?php
                            switch ($ticket->empresa) {
                                case 'NORIO':
                                    echo 'NORIO MOMOI EPP';
                                    break;
                                case 'SIMM2M':
                                    echo 'SIMM2M';
                                    break;
                                case 'SIM2M':
                                    echo 'SIMM2M';
                                    break;
                                case 'OMNILINK':
                                    echo 'OMNILINK TECNOLOGIA';
                                    break;
                                case 'SIGAMY':
                                    echo 'SIGAMY RASTREAMENTO';
                                    break;
                                case 'EUA':
                                    echo 'SHOW TECHNOLOGY';
                                    break;
                                default:
                                    echo 'SHOW TECNOLOGIA';
                                    break;
                            }
                            ?>
                        </td>
					<td><?php echo $this->ticket->tempo_espera($ticket->id,$ticket->status) ?></td>
					<td><a class="btn btn-primary" href="<?php echo site_url('webdesk/ticket/'.$ticket->id)?>" title="Visualizar"><i class="fa fa-eye" style="color: white"></i></a></td>
				</tr>

			<?php endforeach ?>

			</tbody>
		<?php else: ?>

			<div class="alert alert-block">

				<h2>Não há tickets</h2>
				<h4>Nenhum ticket em aberto !</h4>
			</div>

		<?php endif ?>


		</table>

	<?php else: ?>

		<?php if ($tickets): ?>


			<h4>Tickets Abertos</h4>


			<table class="table table-bordered table-hover">

			<thead>
			<tr class="tr-topo">
				<th>Id</th>
				<th>Cliente</th>
                <th>Situação</th>
				<th>Depart</th>
				<th>Assunto</th>
				<th>Última int</th>
				<th>Prioridade</th>
				<th>Prestadora</th>
				<th>Status</th>
				<th>Visualizar</th>
			</tr>
			</thead>
			<tbody class="tb-center">
			<?php foreach ($tickets as $key => $ticket): ?>

				<tr>
					<td><?php echo $ticket->id ?></td>
					<td><?php echo $ticket->cliente ?></td>
                    <td><?php echo $situ[$key] ?></td>
					<td><?php echo $ticket->departamento ?></td>
					<td><?php echo $ticket->assunto ?></td>
					<td><?php echo dh_for_humans($ticket->ultima_interacao) ?></td>
					<td><?php echo $ticket->prioridade == 3 ? '<i class="icon-flag"></i><i class="icon-flag"></i><i class="icon-flag"></i>' : ($ticket->prioridade == 2 ? '<i class="icon-flag"></i><i class="icon-flag"></i>' : '<i class="icon-flag"></i>') ?></td>
					<td>
						<?php
						switch ($ticket->empresa) {
							case 'NORIO':
								echo 'NORIO MOMOI EPP';
								break;
							case 'SIMM2M':
								echo 'SIMM2M';
								break;
							case 'SIM2M':
								echo 'SIMM2M';
								break;
							case 'OMNILINK':
								echo 'OMNILINK TECNOLOGIA';
								break;
							case 'SIGAMY':
								echo 'SIGAMY RASTREAMENTO';
								break;
							case 'EUA':
								echo 'SHOW TECHNOLOGY';
								break;
							default:
								echo 'SHOW TECNOLOGIA';
								break;
						}
						?>
					</td>
					<td><?php echo $this->ticket->tempo_espera($ticket->id,$ticket->status) ?></td>
					<td><a class="btn btn-primary" href="<?php echo site_url('webdesk/ticket/'.$ticket->id)?>" title="Visualizar"><i class="fa fa-eye" style="color: white"></i></a>
				</tr>

			<?php endforeach ?>
			</tbody>
		<?php else: ?>

			<div class="alert alert-block">

				<h2>Não há tickets</h2>
				<h4>Nenhum ticket em aberto !</h4>
			</div>

		<?php endif ?>

		</table>

	<?php endif ?>

	<div class="pagination" style="float: right;">
		<?php echo $this->pagination->create_links();  ?>
	</div>



</div>



<script>
	$('.call').hide();
	$(document).ready(function(){
		$('.calendarioos').focus(function(){
			$(this).calendario({target: $(this)});
		});
		esValor=0;
		$('#tipoPes').change(function () {
			var es = document.getElementById('tipoPes');
			esValor = es.options[es.selectedIndex].value;
			if (esValor==1) {
				$('.call').show();
				$("#palavra").attr('placeholder', 'Cliente/Usuário');
				$(".calendarioos").attr('required',true);
			}else{
				$('.call').hide();
				$("#palavra").attr('placeholder', 'Número do ticket');
				$(".calendarioos").removeAttr('required');
			}
		});
		$('#btnSearch').click(function() {
			if (esValor==0) {
				if(($("#palavra").val()!='') && (!$.isNumeric($("#palavra").val()))) {
					$("#palavra").val('');
					alert('Código inválido');
				}
			}
		});
	});
</script>

<script type="text/javascript" src="<?php echo base_url('media')?>/js/calendario.js"></script>