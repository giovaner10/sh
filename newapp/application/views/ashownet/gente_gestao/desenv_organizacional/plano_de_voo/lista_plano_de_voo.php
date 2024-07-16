<?php if ($this->auth->is_allowed_block('cad_plano_de_voo')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h5 style="font-weight:bold;">Gestão de Carreira</h5>
<h2 class="TitPage">Plano de Voo</h2>    
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/plano_de_voo')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo</a>
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
		<th style="width: 100px; text-align: center;"></th>
		<th style="text-align: center;">Desrição</th>
        <th style="width: 100px; text-align: center;">Ações</th>
	</thead>
	<tbody>
	<?php foreach ($dados as $dado){?>
		<tr>
			<td style="vertical-align: middle; text-align: center;"><?php echo $dado->id;?></td>
			<td style="vertical-align: middle;"><?php echo $dado->nome;?></td>
			<td style="text-align: center;">
				<a href="<?php echo site_url("cadastros/editar_plano_de_voo/$dado->id")?>" class="btn btn-mini btn-primary" title="Editar informação">
                	<i class="fa fa-edit"></i>
                </a>	
    			<a  onclick="excluir()" class="btn btn-mini btn-danger" title="Excluir">
                    <i class="fa fa-remove"></i>
                </a>
			</td>
		</tr>
		<?php }?>
	</tbody>
</table>
<?php }else{ ?>
<div class="alert alert-warning">
	<strong>Desculpe!</strong> Nenhum plano cadastrado até o momento.
</div>
<?php } ?>
<script>
function excluir(id) {

	var r = confirm("Desseja realmente excluir esse plano? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_banner').'/' ?>"+id;
    
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
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>