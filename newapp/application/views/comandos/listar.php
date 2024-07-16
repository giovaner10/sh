<table class="table table-hover table-bordered">
	<thead>
		<th>ID</th>
		<th>Nome</th>
		<th>#</th>
	</thead>
	<tbody>
		<?php if(isset($registros) && $registros):?>
			<?php foreach ($registros as $registro):?>
				<tr>
					<td><?php echo $registro->code ?></td>
					<td><?php echo $registro->nome ?></td>
					<td>
						<a href="#" title="Remover" data-id="<?php echo $registro->code ?>" class="remover"><i class="icon-trash"></i></a>
					</td>
				</tr>
			<?php endforeach;?>
		<?php else: ?>
			<tr>
				<td colspan="100">Nenhum registro encontrado no momento!</td>
			</tr>
		<?php endif;?>
	</tbody>
</table>

<div class="pagination pagination-right">
	<?php echo $this->pagination->create_links()?>
</div>