<h3>Representantes</h3>

<hr class="featurette-divider">

<div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Digitalizar Arquivos</h3>
    </div>
    <div class="modal-body">	
    </div> 
</div>

<div id="myModal_info" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Informações</h3>
    </div>
    <div class="modal-body">	
    </div> 
</div>

<div id="myModal_digitalizado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Arquivos Digitalizados</h3>
    </div>
    <div class="modal-body">	
    </div> 
</div>

<div id="myModal_email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Editar Email Showtecnologia</h3>
    </div>
    <div class="modal-body">	
    </div> 
</div>

<div class="well well-small" style="height: 30px;">

    <div class="btn-group">
        <a data-toggle="modal" href="<?php echo site_url('representantes/digitalizar/000')?>" title="Digitalizar Arquivos" data-target="#myModal_digitalizar" class="btn btn-primary"><i class="icon-folder-open icon-white"></i> Digitalizar Arquivos</a>
    </div>
	

	<form action="" method="get" class="form-horizontal pull-right">
		<?php if ($this->input->get()): ?>
		<a href="<?php echo site_url('representantes/listar_representantes')?>" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i> Voltar</a>			
		<?php endif ?>
		<input type="text" name="palavra" value="" placeholder="Palavra">
		<select name="coluna" style="width: inherit;">
			<option value="cidade">Cidade</option>
			<option value="estado">Estado</option>
			<option value="pais">País</option>
			<option value="nome">Nome</option>
			<option value="email">Email</option>
		</select>
		<button type="submit" class="btn"><i class="icon-search"></i></button>
	</form>

</div>

<br style="clear:both" />

<div class="span13" style="float: none; margin-left: auto; margin-right: auto;">

	<?php if ($representantes): ?>

	<table class="table table-striped">
						  	  
		<tr>
			<th>ID</th>
			<th>Nome</th>
			<th>Data/Hora de cadastro</th>
			<th>Cidade</th>
			<th>Estado</th>
			<th>País</th>
			<th>Telefone</th>
			<th>Celular</th>
			<th>Email</th>
			<th>Email Showtecnologia</th>
			<th>Administrar</th>
		</tr>
								
		<?php foreach ($representantes as $representante): ?>

		<tr>
			<td><span class="badge badge-inverse"><?php echo $representante->id;?></span></td>
			<td><?php echo $representante->nome.' '.$representante->sobrenome;?></td> 
			<td>
			<?php 	
			$date = $representante->data_criacao;
			echo $date_formatada = date('d-m-Y H:i:s', strtotime($date));
			?>
			</td>
			<td><?php echo $representante->cidade;?></td>
			<td><?php echo $representante->estado;?></td> 
			<td><?php echo $representante->pais;?></td> 
			<td><?php echo $representante->telefone;?></td>
			<td><?php echo $representante->celular;?></td>
			<td><?php echo $representante->email;?></td>
			<td><?php echo $representante->emailshow;?></td>
			<td>
                <a data-toggle="modal" href="<?php echo site_url('representantes/editar/'.$representante->id)?>" title="Editar email Showtecnologia" data-target="#myModal_email" class="btn btn-mini"><i class="icon-edit icon-black"></i></a>
				</a>
            
				<a data-toggle="modal" href="<?php echo site_url('representantes/infos/'.$representante->id)?>" title="Mais info" data-target="#myModal_info" class="btn btn-mini btn-primary"><i class="icon-plus icon-white"></i> Info</a>
				</a> 

				<a data-toggle="modal" href="<?php echo site_url('representantes/digitalizar/'.$representante->id)?>" title="Arquivos digitalizados" data-target="#myModal_digitalizado" class="btn btn-mini btn-primary"><i class="icon-folder-open icon-white"></i></a>
				</a> 
            </td>
		</tr>	

		<?php endforeach ?>

	<?php else: ?>

		<div class="alert alert-block">
			<h2>Não há Representantes</h2>
		</div>
			
	<?php endif ?>	


	</table>

	 <div class="pagination" style="float: right;">
		<?php echo $this->pagination->create_links();  ?> 
	</div>

</div>
<script type="text/javascript">
	$(document).ready(function(e) {

		$('#myModal_digitalizar').on('hidden', function(){
	    	$(this).data('modal', null);
		});

		$('#myModal_info').on('hidden', function(){
	    	$(this).data('modal', null);
		});

		$('#myModal_digitalizado').on('hidden', function(){
	    	$(this).data('modal', null);
		});

		$('#myModal_email').on('hidden', function(){
	    	$(this).data('modal', null);
		});

	});
</script>
