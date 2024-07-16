<?php if ($this->auth->is_allowed_block('cad_apresentacoes_comerciais')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/colorbox.css" />
<script src="<?php echo base_url('assets');?>/js/jquery.colorbox.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Apresentações Comerciais</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/apresentacao_comercial')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Nova Apresentação</a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php 
if(count($apresentacoes) > 0){
?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 100px; text-align: center;"></th>
		<th style="text-align: center;">Descrição</th>
        <th style="width: 200px; text-align: center;">Excluir</th>
	</thead>
	<tbody>
	<?php foreach ($apresentacoes as $apresentacao){?>
		<tr>
			<td style="text-align: center; vertical-align: middle;"><?php echo $apresentacao->id;?></td>
			<td style="vertical-align: middle;"><?php echo $apresentacao->descricao; ?></td>
			<td style="text-align: center;">
				<?php 
				$query = $this->db->query("SELECT id_apresentacao, file FROM cad_apresentacao_comerciais_arquivos
                WHERE id_apresentacao ='$apresentacao->id'");
				
				$i=0;
				$visualizarImagem = "";
				$arquivoDownload = "";
				foreach ($query->result_array() as $row) { 
				  
				    $ext = strtolower(substr($row['file'], (strlen($row['file']) - 3), strlen($row['file'])));
				
				    if($ext != "pdf" && $ext != "ptx"){
				        $arquivoImage = $row['file'];
				    }else{
				        $arquivoDownload = $row['file'];
				    }
				    
				    if($i !=1 && ($ext != "pdf" || $ext != "ptx")){
				        $visualizarImagem = 'style="display: none;"';
				    }else{
				        $visualizarImagem = "";
				    }
				    
				    if($i !=1 && ($ext == "pdf" || $ext == "ptx")){
				        $visualizarDownload= 'style="display: hide;"';
				    }else{
				        $visualizarDownload= 'style="display: none;"';
				    }				
				    
				?>
				<?php if($ext != "pdf" && $ext != "ptx"){?>
				<a <?php echo $visualizarImagem;?> href="<?php echo base_url("uploads/apresentacoes_comerciais/$arquivoImage");?>" class="btn btn-mini btn-default vapresentacao<?=$row['id_apresentacao']?>"  title="Visualizar">
                    <i class="fa fa-eye"></i>
                </a>
                <?php } ?>
                <a <?php echo $visualizarDownload;?> href="<?php echo base_url("uploads/apresentacoes_comerciais/$arquivoDownload");?>" class="btn btn-mini btn-default" target="_blank" title="Download">
                    <i class="fa fa-file"></i>
                </a>
                <?php $i++;} ?>
				<a href="<?php echo site_url('cadastros/editar_apresentacao_comercial/'.$apresentacao->id)?>" class="btn btn-mini btn-primary" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>
    			<a onclick="excluir(<?=$apresentacao->id?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i class="fa fa-remove"></i>
                </a>
			</td>
		</tr>
		<script>
			$(".vapresentacao<?=$apresentacao->id?>").colorbox({rel:'vapresentacao<?=$apresentacao->id?>'});
		</script>
		<?php }?>
	</tbody>
</table>
<?php }else{ ?>
<div class="alert alert-warning">
	<strong>Desculpe!</strong> Nenhuma apresentação cadastrada até o momento.
</div>
<?php } ?>
<script>
$(".vapresentacao14").colorbox({rel:'vapresentacao14'});

function excluir(id) {

	var r = confirm("Desseja realmente excluir essa apresentação? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_apresentacao_comercial').'/' ?>"+id;
    
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