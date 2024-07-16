<?php if ($this->auth->is_allowed_block('cad_espaco_ti')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Espaço TI</h3>
<hr>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 100px; text-align: center;"></th>
		<th style="text-align: center;">Desrição</th>
        <th style="width: 100px; text-align: center;">Ação</th>
	</thead>
	<tbody>
	<?php foreach ($lista_dados as $lista_dado){?>
		<tr>
			<td style="vertical-align: middle; text-align: center;"><?php echo $lista_dado->id;?></td>
			<td style="vertical-align: middle;"><?php echo $lista_dado->descricao ?></td>
			<td style="text-align: center; vertical-align: middle;">
    			<a href="<?php echo site_url("cadastros/editar_gente_gestao/$lista_dado->id")?>" class="btn btn-mini btn-primary" title="Editar informação">
                    <i class="fa fa-edit"></i>
                </a>
			</td>
		</tr>
		<?php }?>
	</tbody>
</table>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>