<?php 

switch (date("m")) {
    case "01":    $mes = "Janeiro";     break;
    case "02":    $mes = "Fevereiro";   break;
    case "03":    $mes = "Março";       break;
    case "04":    $mes = "Abril";       break;
    case "05":    $mes = "Maio";        break;
    case "06":    $mes = "Junho";       break;
    case "07":    $mes = "Julho";       break;
    case "08":    $mes = "Agosto";      break;
    case "09":    $mes = "Setembro";    break;
    case "10":    $mes = "Outubro";     break;
    case "11":    $mes = "Novembro";    break;
    case "12":    $mes = "Dezembro";    break;
}


if ($this->auth->is_allowed_block('cad_aniversariantes')){ ?>
	<br>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/listar_aniversariantes');?>">INCLUIR</a>
	</div>
	<?php } ?>
<h3>Aniversariantes do mês de <?php echo $mes;?></h3>
<hr>
<?php if(count($aniversariantes) > 0) { ?>
<table id="table" class="table table-bordered table-hover">
	<thead
		style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 200px;" class="span1">Dia/mês Nascimento</th>
		<th class="span3">Nome</th>
		<th class="span2">Empresa</th>
		<th class="span3">E-mail</th>
		<th class="span2">Ocupação</th>
	</thead>
	<tbody>
		<?php 		
		  foreach ($aniversariantes as $aniversariante){ 
		?>
		<tr>
			<td><center><?php echo date("d/m", strtotime($aniversariante->data_nasc));?><center></td>
			<td><?php echo $aniversariante->nome ?></td>
			<td style="text-align: center; vertical-align: middle;"><?php echo $aniversariante->empresa ?></td>
			<td style="vertical-align: middle;"><?php echo $aniversariante->email ?></td>
			<td style="text-align: center; vertical-align: middle;"><?= $aniversariante->cargo ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php }else{ ?>
<div class="alert alert-warning">
  Não há aniversariantes no mês atual
</div>
<?php } ?>