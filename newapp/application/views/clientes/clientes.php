
<?php if ($msg != ''): ?>
	<div class="alert">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>CONCLUIDO!</strong>
		<?php echo $msg ?>
	</div>
<?php endif; ?>
<div class="alert hide"></div>
<div class="well well-small">
	<div class="btn-group">
		<a href="<?php echo site_url('clientes/registro') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
	</div>
</div>
<small>*Horário de Brasília</small>
<table class="table table-hover table-bordered">
	<thead style="background-color: #f5f5f5">
		<th class="span1">ID</th>
		<th class="span6">Cliente</th>
		<th class="span2">Data/hora de cadastro</th>
		<th class="span1">Origem</th>
		<th class="span2">Status</th>
		<th class="span1">Administrar</th>
	</thead>
	<tbody>
		<tr style="background-color: #f5f5f5">
		<td></td>
		<td>
			<div class="input-append span6">
				<input id="input-cliente"  type="text" name="cliente" class="span12" data-provide="typeahead" autocomplete="off"
					data-items="6" data-source='<?php echo $j_clientes?>'
					placeholder="Digite o nome ou ID do cliente"
					required />
				<button class="btn" id="bt-cliente" data-controller="<?php echo site_url('clientes/filtrar') ?>">
					<i class="icon-search"></i> 
				</button>
			</div>
		</td>
		<td></td>
		<td></td>
		<td>
			<div class="controls">
				<select class="span9" name="status" data-controller="<?php echo site_url('clientes/filtrar') ?>">
					Add a comment to this line
					<option value="all" 
						<?php echo isset($status_cliente) ? set_selecionado('all', $status_cliente, 'selected') : '' ?>>
						Todos
					</option>
					 <option value="5" 
						<?php echo isset($status_cliente) ? set_selecionado(5, $status_cliente, 'selected') : '' ?>>
						Site
					</option>
					<option value="0" 
						<?php echo isset($status_cliente) ? set_selecionado('0', $status_cliente, 'selected') : '' ?>>
						Bloqueado
					</option>
					<option value="1" 
						<?php echo isset($status_cliente) ? set_selecionado(1, $status_cliente, 'selected') : '' ?>>
						Ativo
					</option>
					<option value="2" 
						<?php echo isset($status_cliente) ? set_selecionado(2, $status_cliente, 'selected') : '' ?>>
						Prospectado
					</option>
					<option value="3" 
						<?php echo isset($status_cliente) ? set_selecionado(3, $status_cliente, 'selected') : '' ?>>
						Em Teste
					</option>
					<option value="4" 
						<?php echo isset($status_cliente) ? set_selecionado(4, $status_cliente, 'selected') : '' ?>>
						A reativar
					</option>
				</select>
			</div>
		</td>
		 <td></td>
		</tr>
		<?php if ($clientes): ?>
			<?php foreach ($clientes as $cliente): ?>
				<tr>
					<td><?php echo $cliente->id ?></td>
					<td><?php echo $cliente->nome ?></td>
					<td>
						<?php 
							$date = $cliente->data_cadastro;
							$date_formatada = date('d-m-Y H:i:s', strtotime(str_replace('-', '/', $date)));
							echo  $date_formatada;
						?>
					</td>
					<td>
						<?php
							$origem = $cliente->cad_site;
							if($origem  === '1')
								$origem = "<span style='color:green;'><i>Site</i></span>";
							else
								$origem = "<span style='color:blue;'><i>ShowNet</i></span>";
							echo $origem;
						?>
					</td>
					<td>
						<?php echo show_status_cliente($cliente->status) ?>
					</td>
					<td>
						<a href="<?php echo site_url('clientes/view/' . $cliente->id) ?>" class="btn btn-mini btn-primary">
							<i class="icon-folder-open icon-white"></i>
							Abrir
						</a> 
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="8">Nenhum item encontrado para este filtro.</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<div class="pagination pagination-right">
	<?php echo $this->pagination->create_links() ?>
</div>
<script>
	$(document).ready(function () {
		var cli = new ModuleClientes();
		cli.bindOrdenar();
		cli.buscar();
		var app = new App();
		app.init();
	});
</script>
