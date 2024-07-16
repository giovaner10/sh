<h3>Ordens de Serviços</h3>

<hr class="featurette-divider">

<div class="well well-small">

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
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
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
			<a href="<?php echo site_url('servico/instalacao')?>" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i> Voltar</a>			
		<?php endif ?>
		<input type="text" name="palavra" value="" placeholder="Palavra">
		<select name="coluna" style="width: inherit;">

			<option value="nome">Cliente</option>
			<option value="id">Contrato</option>
			
		</select>
		<button type="submit" class="btn"><i class="icon-search"></i></button>
	</form>

</div>

<br style="clear:both" />

<div class="span12" style="float: none; margin-left: auto; margin-right: auto;">

	<?php if ($contratos): ?>

	<h4>Contratos Novos</h4>

	<table class="table table-hover">
						  	  
		<tr>
			<th>Contrato</th>
			<th>Cliente</th>
			<th>Veículos</th>
			<th>Data Cadastro</th>
			<th>Status</th>
			<th>Gerar OS</th>
		</tr>
									
		<?php foreach ($contratos as $contrato): ?>

			<tr>
				<td><span class="badge badge-inverse"><?php echo $contrato->id ?></span></td>
				<td><?php echo $contrato->nome_cliente ?></td> 
				<td><?php echo $contrato->quantidade_veiculos ?></td>   
				<td><?php echo dh_for_humans($contrato->data_cadastro) ?></td>   
				<td><?php echo $contrato->status == 0 ? 'Cadastrado' :  ( $contrato->status == 1 ? 'OS' : ( $contrato->status == 2 ? 'Ativo' : 'Cancelado' ) ) ?></td>  
				<td><a href="<?php echo site_url('servico/gerar_ordem_servico/1/'.$contrato->id.'/'.$contrato->id_cliente.'/'.$contrato->quantidade_veiculos) ?>" target="_blank" title="Gerar OS"><i class="icon-pencil"></i></a></td>	
			</tr>	

		<?php endforeach ?>

	<?php else: ?>

		<div class="alert alert-block">
			<h2>Não há Contratos</h2>
			<h4>Nenhum contrato encontrado!</h4>
		</div>
			
	<?php endif ?>	


	</table>

	 <div class="pagination" style="float: right;">
		<?php echo $this->pagination->create_links();  ?> 
	</div>

</div>

