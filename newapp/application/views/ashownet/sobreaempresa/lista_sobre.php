<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3><?=lang('sobre_a_empresa')?></h3>
<hr>
<?php 
    if(count($sobre) > 0){ 
?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 200px; text-align: center;">Titulo</th>
		<th style="width: 350px; text-align: center;">Desrição</th>
		<th style="text-align: center;">Missão</th>
		<th style="text-align: center;">Visão</th>
		<th style=" width: 350px; text-align: center;">Valores</th>
        <th style="width: 100px; text-align: center;">Alterar</th>
	</thead>
	<tbody>
		<?php foreach ($sobre as $r){ ?>
		<tr>
			<td><?php echo $r->titulo; ?></td>
			<td><?php echo $r->descricao; ?></td>
			<td><?php echo $r->missao; ?></td>
			<td><?php echo $r->visao; ?></td>
			<td><?php echo $r->valores; ?></td>
    		<td style="text-align: center; vertical-align: middle; ">
    			<a href="<?php echo site_url('cadastros/ViewEditarSobre/'.$r->id)?>" class="btn btn-mini btn-primary" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>    			
			</td>
		</tr>
		<?php }?>   
	</tbody>
</table>
<?php } ?>