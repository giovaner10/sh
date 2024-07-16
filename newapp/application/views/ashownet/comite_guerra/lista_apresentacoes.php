<?php if ($this->auth->is_allowed_block('cad_comite_guerra')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/colorbox.css" />
<script src="<?php echo base_url('assets');?>/js/jquery.colorbox.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Comitê de Guerra</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/comite_guerra')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Nova Apresentação</a>
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
				$query = $this->db->query("SELECT id_apresentacao, file FROM cad_comite_guerra_arquivos
                WHERE id_apresentacao ='$apresentacao->id'");
				
				$i=0;
				$visualizarImagem = "";
				$arquivoDownload = "";
				foreach ($query->result_array() as $row) { 
				  
				    $ext = substr($row['file'], (strlen($row['file']) - 3), strlen($row['file']));
				
				    if($ext != "ppt" && $ext != "ptx"){
				        $arquivoImage = $row['file'];
				    }else{
				        $arquivoDownload = $row['file'];
				    }
				    
				    if($i !=0 && ($ext != "ppt" || $ext != "ptx")){
				        $visualizarImagem = 'style="display: none;"';
				    }else{
				        $visualizarImagem = "";
				    }
				    
				    if($i !=0 && ($ext == "ppt" || $ext == "ptx")){
				        $visualizarDownload= 'style="display: hide;"';
				    }else{
				        $visualizarDownload= 'style="display: none;"';
				    }
				
				    if($ext != "ppt" && $ext != "ptx"){
				?>
				<a <?php echo $visualizarImagem;?> href="<?php echo base_url("uploads/comite_guerra/$arquivoImage");?>" class="btn btn-mini btn-default vapresentacao<?=$row['id_apresentacao']?>"  title="Visualizar">
                    <i class="fa fa-eye"></i>
                </a>
                <?php } ?>
                <a <?php echo $visualizarDownload;?> href="<?php echo base_url("uploads/comite_guerra/$arquivoDownload");?>" class="btn btn-mini btn-default"  title="Download">
                    <i class="fa fa-file"></i>
                </a>
                <?php $i++;} ?>
				<a href="<?php echo site_url('cadastros/editar_comite_guerra/'.$apresentacao->id)?>" class="btn btn-mini btn-primary" title="Editar">
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
        var url = "<?= site_url('cadastros/excluir_comite_guerra').'/' ?>"+id;
    
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