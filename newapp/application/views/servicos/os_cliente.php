<!-- <div class="well well-small">

			<div class="btn-group">
			  <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
			    Listar OS
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu">
			  	<li><a href="<?php echo site_url('servico')?>" title=""><i class="icon-th-list"></i> Todas</a></li>
			    <li><a href="<?php echo site_url('servico/os_abertas')?>" title=""><i class="icon-th-list"></i> Abertas</a></li>
			    <li><a href="<?php echo site_url('servico/os_fechadas')?>" title=""><i class="icon-th-list"></i> Fechadas</a></li>
			  </ul>
			</div>

			<div class="btn-group">
			  <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#">
			    Gerar OS
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu">
			  	<li><a href="<?php echo site_url('servico/instalacao')?>" title=""><i class="icon-th-list"></i> Instalação</a></li>
			    <li><a href="<?php echo site_url('servico/manutencao_troca_retirada')?>" title=""><i class="icon-th-list"></i> Manutenção/Troca/Retirada</a></li>
			  </ul>
			</div>

	   		<form action="" method="get" class="form-horizontal pull-right">
	   			<?php if ($this->input->get()): ?>
					<a href="<?php echo site_url('servico')?>" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i> Voltar</a>			
				<?php endif ?>
				<input type="text" name="palavra" value="" placeholder="Palavra">
				<select name="coluna" style="width: inherit;" disabled>

					<option value="id">OS</option>
					
				</select>
				<button type="submit" class="btn"><i class="icon-search"></i></button>
			</form>

</div> -->

<br style="clear:both" />

<div class="span12" style="float: none; margin-left: auto; margin-right: auto;">

	<?php if($this->session->flashdata('sucesso')): ?>
		<div class="alert alert-success">
			<p><?php echo $this->session->flashdata('sucesso') ?></p>
		</div>
	<?php endif; ?>

	<?php if($this->session->flashdata('erro')): ?>
		<div class="alert alert-error">
			<p><?php echo $this->session->flashdata('erro') ?></p>
		</div>
	<?php endif; ?>
	
	<?php if ($ordens): ?>
	
	<table class="table table-hover">
						  	  
			<tr>
				<th>OS</th>
				<th>Tipo</th>
				<th>Cliente</th>
				<th>Veículos</th>
				<th>Data Cadastro</th>
				<th>Usuário</th>
				<th>Status</th>
				<th>Imprimir</th>
			</tr>
									
		<?php foreach ($ordens as $ordem): ?>

			<tr>
				<td><span class="badge badge-inverse"><?php echo $ordem->id ?></span></td>
				<td><?php echo $ordem->tipo_os == 1 ? 'Instalacao' : ($ordem->tipo_os == 2 ? 'Manutencao' : ($ordem->tipo_os == 3 ? 'Troca' : 'Retirada')) ?></td>
				<td><?php echo $ordem->nome_cliente ?></td> 
				<td><?php echo $ordem->quantidade_equipamentos ?></td>   
				<td><?php echo dh_for_humans($ordem->data_cadastro) ?></td>   
				<td><?php echo $ordem->status == 0 ? 'Cadastrado' :  'Fechado' ?></td> 
				<td><a href="<?php echo site_url('servico/imprimir_os/'.$ordem->id.'/'.$ordem->id_contrato.'/'.$ordem->tipo_os)?>" target="_blank" title="Imprimir"><i class=" icon-print"></i></a></td>	
			</tr>	

		<?php endforeach ?>

	<?php else: ?>

		<div class="alert alert-block">
			<h2>Não há Ordem de Serviço</h2>
			<h4>Nenhuma Ordem de serviço gerada!</h4>
		</div>
			
	<?php endif ?>	

	</table>

	<!-- <div class="pagination" style="float: right;">
		<?php echo $this->pagination->create_links();  ?> 
	</div> -->

</div>

