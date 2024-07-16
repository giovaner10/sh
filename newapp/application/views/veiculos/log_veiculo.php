<h3>Log do Cadastro de Veículos</h3>
	
<hr>

<div class="well well-small">

	<button class="btn btn-info pull-left" onclick="imprimir();" type="button">Imprimir</button>

	<form action="" method="get" class="form-horizontal pull-right" style="margin: 0px;">
		<input type="text" name="palavra" value="" placeholder="Palavra">
		<select name="coluna" style="width: inherit;">
			<option value="usuario">Usuário</option>
			<option value="data">Data</option>
			<option value="veiculo">Veículo</option>
			<option value="placa">Placa</option>
			<option value="serial">Serial</option>
			<option value="cnpj">CNPJ</option>
		</select>
		<button type="submit" class="btn"><i class="icon-search"></i></button>
	</form>

	<div class="clearfix"></div>

</div>
		
<table class="table table-bordered">
	<?php if($logs) :?>
		<?php foreach($logs as $log) :?>
			<tr>
				<th colspan="2">Usuário</th>
				<th colspan="2">Ação</th>
				<th colspan="2">Data</th>
			</tr>

			<!-- <tr class="info"> -->
			<tr class="<?php echo $log->acao == '2' ? 'error' : 'info' ?>">
				<td colspan="2"><?php echo $log->usuario ?></td>
                <?php

                if ($log->acao == '0')
                    $acao = 'Inseriu';
                elseif ($log->acao == '1')
                    $acao = 'Atualizou';
                elseif ($log->acao == '2')
                    $acao = 'Apagou';
                elseif ($log->acao == '3')
                    $acao = 'Ativou';
                else
                    $acao = 'Inativou';

                ?>
				<!-- <td colspan="2"><?php echo $log->acao == '0' ? 'Inseriu' : 'Atualizou' ?></td> -->
				<td colspan="2"><?php echo $acao ?></td>
				<td colspan="2"><?php echo dh_for_humans($log->data) ?></td>
			</tr>

			<!-- <tr style="font-weight: bold;" class="success"> -->
			<tr style="font-weight: bold;" class="<?php echo $log->acao == '2' ? 'error' : 'warning' ?>">
				<td></td>
				<td>Veículo</td>
				<td>Placa</td>
				<td>Serial</td>
				<td>CNPJ</td>
				<td>Image</td>
			</tr>

			<?php if ($log->antes): ?>
				<?php $antes = json_decode($log->antes) ?>
			<?php endif ?>

			<?php if ($log->depois): ?>
				<?php $depois = json_decode($log->depois) ?>
			<?php endif ?>

			<?php if(isset($antes)): ?>
				<!-- <tr class="warning"> -->
				<tr class="<?php echo $log->acao == '2' ? 'error' : 'warning' ?>">
					<td><strong>Antes</strong></td>
					<td><?php echo $antes->veiculo != $depois->veiculo ? '<strong style="color: red;">'.$antes->veiculo.'</strong>' : $antes->veiculo ?></td>
					<td><?php echo $antes->placa != $depois->placa ? '<strong style="color: red;">'.$antes->placa.'</strong>' : $antes->placa ?></td>
					<td><?php echo $antes->serial != $depois->serial ? '<strong style="color: red;">'.$antes->serial.'</strong>' : $antes->serial ?></td>
					<td><?php echo $antes->CNPJ_ != $depois->CNPJ_ ? '<strong style="color: red;">'.$antes->CNPJ_.'</strong>' : $antes->CNPJ_ ?></td>
					<td><?php echo $antes->imagem != $depois->imagem ? '<strong style="color: red;">'.$antes->imagem.'</strong>' : $antes->imagem ?></td>
				</tr>
			<?php endif ?>

			<?php if(isset($depois)): ?>
				<tr class="error">
					<td><strong>Depois</strong></td>
					<td><?php echo $depois->veiculo != $antes->veiculo ? '<strong style="color: red;">'.$depois->veiculo.'</strong>' : $depois->veiculo ?></td>
					<td><?php echo $depois->placa != $antes->placa ? '<strong style="color: red;">'.$depois->placa.'</strong>' : $depois->placa ?></td>
					<td><?php echo $depois->serial != $antes->serial ? '<strong style="color: red;">'.$depois->serial.'</strong>' : $depois->serial ?></td>
					<td><?php echo $depois->CNPJ_ != $antes->CNPJ_ ? '<strong style="color: red;">'.$depois->CNPJ_.'</strong>' : $depois->CNPJ_ ?></td>
					<td><?php echo $depois->imagem != $antes->imagem ? '<strong style="color: red;">'.$depois->imagem.'</strong>' : $depois->imagem ?></td>
				</tr>
			<?php endif ?>

		<?php endforeach;?>

	<?php else: ?>

	<tr class="warning">
		<td>Nenhum registro e atividade encontrado no momento!</td>
	</tr>

	<?php endif;?>
</table>

<div class="pagination pagination-right">
	<?php echo $this->pagination->create_links() ?>
</div>