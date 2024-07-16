<?php if ($this->auth->is_allowed_block('cad_correcao_irrf')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Correção Imposto de Renda</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/correcao_irrf')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo</a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php 
    if(count($dados) > 0){ 
?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 200px; text-align: center;">Titulo</th>
		<th style="width: 600px; text-align: center;">Desrição</th>
        <th style="width: 100px; text-align: center;">Ações</th>
	</thead>
	<tbody>
		<?php foreach ($dados as $r){ ?>
		<tr>
			<td><?php echo $r->titulo; ?></td>
			<td><?php echo $r->descricao; ?></td>
    		<td style="text-align: center; vertical-align: middle; ">
    			<a href="<?php echo site_url('cadastros/editar_correcao_irrf/'.$r->id)?>" class="btn btn-mini btn-primary" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>  
                <a onclick="excluir(<?=$r->id?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i class="fa fa-remove"></i>
                </a>  			
			</td>
		</tr>
		<?php }?>   
	</tbody>
</table>
<script>

function excluir(id) {

	var r = confirm("Desseja realmente excluir esse comunicado? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_correcao_irrf').'/' ?>"+id;
    
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
<?php }else{ ?>
<div class="alert alert-warning">
	<strong>Desculpe!</strong> Nenhum dado cadastrado até o momento.
</div>
<?php } ?>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>