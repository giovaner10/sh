<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Comunicados</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/comunicados')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo Comunicado</a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php 
    if(count($comunicados) > 0){ 
?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 100px; text-align: center;">#</th>
		<th style="text-align: center;">Desrição</th>
        <th style="width: 100px; text-align: center;">Excluir</th>
	</thead>
	<tbody>
		<?php foreach ($comunicados as $comunicado){ ?>
		<tr>
			<td style="vertical-align: middle; text-align: center;"><?php echo $comunicado->id; ?></td>
			<td style="vertical-align: middle;"><?php echo $comunicado->comunicado; ?></td>
    		<td style="text-align: center;">
    			<a href="<?php echo site_url('cadastros/ViewEditarComunicado/'.$comunicado->id)?>" class="btn btn-mini btn-primary" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>
    			<a onclick="excluir(<?=$comunicado->id_arquivo?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i id="icon<?= $comunicado->id_arquivo ?>" class="fa fa-remove"></i>
                </a>
			</td>
		</tr>
		<?php }?>   
	</tbody>
</table>
<?php }else{ ?>
<div class="alert alert-warning">
	<strong>Desculpe!</strong> Nenhum comunicado cadastrado até o momento.
</div>
<?php } ?>
<script>

function excluir(id) {

	var r = confirm("Desseja realmente excluir esse comunicado? Esse procedimento é irreversível.");

    if (r == true) {  
        
        var url = "<?= site_url('cadastros/excluirComunicado').'/' ?>"+id;

         $.ajax({
            url : url,
            type : 'POST',
            beforeSend: function(){
				alert('Aguarde um momento por favor...');
            },
            success : function(data){
            	alert(data);
    	        window.location.reload();  
            },
            error : function () {
                alert('Error...');
            }
        });
    }
}
</script>