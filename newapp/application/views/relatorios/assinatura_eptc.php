<h3>Assinaturas EPTC</h3>
<hr>

<table class="table" style="width:100%;">
	<thead>
		<th class="">#</th>
		<th class="">Data</th>
		<th class="">ID do usuário</th>
		<th class="">Email</th>
		<th class="">Nome</th>
		<th class="">Email informado</th>
		<th class="">Telefone informado</th>			
	</thead>
	<tbody>
	<?php foreach ($assinaturas as $assinatura) : ?>
		<tr>
			<td><?php echo $assinatura->id ?></td>
			<td><?php echo dh_for_humans($assinatura->datahora) ?></td>
			<td><?php echo $assinatura->user ?></td>
			<td><?php echo $assinatura->usuario ?></td>
			<td><?php echo $assinatura->nome_usuario ?></td>
			<td><?php echo $assinatura->email ?></td>
			<td><?php echo $assinatura->telefone ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	
</table>