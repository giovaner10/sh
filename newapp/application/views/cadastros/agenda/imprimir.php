<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			.table{
				width: 100%;
			}
			.table th{
				text-align: left
			}
			.table thead tr {
				border-bottom: 2px solid;
			}
			.table tbody tr {
				border-bottom: 1px solid;
			}
		</style>
	
	</head>
	
	<body onload="javascript:window.print();">
		
		<table class="table">
			<thead>
			  <tr>
			    <th class="span1">#</th>
			    <th>Prefixo</th>
			    <th>Placa</th>
			    <th>Data</th>
			    <th>Serviço</th>
			  </tr>
			</thead>
			<tbody>
			<?php if (count($agendados)):?>
				<?php foreach ($agendados as $agenda):?>
					  <tr>
					    <td><?php echo $agenda->id_agenda?></td>
					    <td><?php echo $agenda->prefixo?></td>
					    <td><?php echo $agenda->placa?></td>
					    <td><?php echo dh_for_humans($agenda->data_agenda)?></td>
					    <td>
					    	<?php switch ($agenda->servico_agenda){
					    		case 1:
					    			$servico = 'Instalação';
					    			break;
					    		case 2:
					    			$servico = 'Manutenção';
					    			break;
					    		case 3:
					    			$servico = 'Remoção';
					    			break;
					    		default:
					    			$servico = 'Não informado';
					    	}
					    		echo $servico?>
					    </td>
					   
					  </tr>
				<?php endforeach;?>
			<?php endif;?>
		  	</tbody>
		</table>
	</body>


</html>