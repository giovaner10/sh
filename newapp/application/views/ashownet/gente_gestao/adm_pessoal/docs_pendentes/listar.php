<?php if ($this->auth->is_allowed_block('cad_docs_pendentes')){ ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/colorbox.css" />
<script src="<?php echo base_url('assets');?>/js/jquery.colorbox.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Documentos Pendentes</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="span9">
            <div class="btn-group">
                <a href="<?php echo site_url('cadastros/docs_pendentes')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Nova Solicitação</a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
</div>
<hr>
<?php 
    if(count($lista_dados) > 0){ 
?>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th style="width: 300px; text-align: center;">Funcionários</th>
		<th style="width: 400px; text-align: center;">Documentos</th>
		<th style="text-align: center;">Status</th>
        <th style="width: 200px; text-align: center;">Ações</th>
	</thead>
	<tbody>
		<?php 
		$documentos = "";
		$status = "";
		foreach ($lista_dados as $lista_dado){ 
		
		    if($lista_dado->residencia == '1'){
		        $documentos .= "Comprovante de residência,";
		    }
		    
		    if($lista_dado->cpf == '1'){
		        $documentos .= " CPF,";
		    }
		    
		    if($lista_dado->rg == '1'){
		        $documentos .= " RG,";
		    }
		    
		    if($lista_dado->banco == '1'){
		        $documentos .= " comprovante bancário";
		    }
		    
		    if($lista_dado->status == '1'){
		        $status = "Pendente";
		    }else{
		        $status = "Documentos enviados";
		    }
		 ?>
		<tr>
			<td><?=$lista_dado->nome?></td>
			<td><?=$documentos?></td>
			<td style="text-align: center;"><?=$status?></td>
    		<td style="text-align: center; vertical-align: middle; ">
    			<?php 
				$query = $this->db->query("SELECT id, id_funcionario, file FROM cad_docs_pendente_arquivos
                WHERE id_funcionario ='$lista_dado->id_funcionario'");
				
				$i=0;
				$visualizarImagem = "";
				
				foreach ($query->result_array() as $row) { 
				    $arquivoImage = $row['file'];
				    
				    $ext = substr($row['file'], (strlen($row['file']) - 3), strlen($row['file']));
				    
				    if($i !=0 && ($ext != "ppt" || $ext != "ptx")){
				        $visualizarImagem = 'style="display: none;"';
				    }else{
				        $visualizarImagem = "";
				    }
				    
				    if($status != "Pendente"){
				?>  
			    <a <?php echo $visualizarImagem;?> href="<?php echo base_url("uploads/docs_pendentes/$arquivoImage");?>" class="btn btn-mini btn-default vapresentacao<?=$row['id_funcionario']?>"  title="Visualizar">
                	<i class="fa fa-eye"></i>
                </a>
                <?php } ?>
                <script>
        			$(".vapresentacao<?=$lista_dado->id_funcionario?>").colorbox({rel:'vapresentacao<?=$lista_dado->id_funcionario?>'});
        		</script>
                <?php $i++;} ?>
    			<a href="<?php echo site_url('cadastros/editar_docs_pendentes/'.$lista_dado->id)?>" class="btn btn-mini btn-primary" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>  
                <a onclick="excluir(<?=$lista_dado->id_funcionario?>)" class="btn btn-mini btn-danger" title="Excluir">
                    <i class="fa fa-remove"></i>
                </a>  			
			</td>
		</tr>
		<?php }?>   
	</tbody>
</table>
<script>

function excluir(id) {

	var r = confirm("Desseja realmente excluir essa solicitacao? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_docs_pendentes').'/' ?>"+id;
    
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