<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/colorbox.css" />
<script src="<?php echo base_url('assets');?>/js/jquery.colorbox.js"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Desenvolvimento Organizacional</h3>
<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#tead" class="show">Treinamentos EAD</a></li>
	<li class="sim"><a data-toggle="tab" href="#parc">Parcerias</a></li>
</ul>
<br>
<div class="tab-content">
    <div id="tead" class="tab-pane fade in active">
    	<?php if ($this->auth->is_allowed_block('cad_treinamentos')){ ?>
	   	<div class="row-fluid">
            <div class="span9">
                <div class="btn-group">
                    <a href="<?php echo site_url('cadastros/treinamentos')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo arquivo</a>
                </div>
            </div>
        </div>
    	<br>
        <?php if(count($lista_dados) > 0 ){ ?>
        <table id="table" class="table table-bordered table-hover">
        	<thead style="background-color: #0072cc !important; color: white !important;">
        		<th style="width: 100px; text-align: center;">Capa</th>
        		<th style="text-align: center;">Tipo</th>
        		<th style="text-align: center;">Desrição</th>
        		<th style="text-align: center;">Link</th>
                <th style="width: 100px; text-align: center;">Ação</th>
        	</thead>
        	<tbody>
        	<?php foreach ($lista_dados as $lista_dado){?>
        		<tr>
        			<td><img src="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/treinamentos/$lista_dado->file");?>" style="width: 100%"></td>
        			<td style="vertical-align: middle;"><?php echo $lista_dado->tipo ?></td>
        			<td style="vertical-align: middle;"><?php echo $lista_dado->descricao ?></td>
        			<td style="vertical-align: middle;"><?php echo $lista_dado->link ?></td>
        			<td style="text-align: center; vertical-align: middle;">
            			<a href="<?php echo site_url("cadastros/editar_treinamentos/$lista_dado->id")?>" class="btn btn-mini btn-primary" title="Editar informação">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a onclick="excluirEad(<?= $lista_dado->id ?>)" class="btn btn-mini btn-danger" title="Excluir">
                            <i class="fa fa-remove"></i>
                        </a>
        			</td>			
        		</tr>
        		<?php }?>
        	</tbody>
        </table>
        <?php }else{ ?>
        <div class="alert alert-warning">
        	<strong>Desculpe!</strong> Nenhum arquivo cadastrado até o momento.
        </div>
        <?php } 
        }else{ 
            $this->load->view('erros/403');
         } ?>
    </div>
    <div id="parc" class="tab-pane fade">
     <?php if ($this->auth->is_allowed_block('cad_parcerias')){ ?>
    	<div class="row-fluid">
            <div class="span9">
                <div class="btn-group">
                	<?php if(count($parcerias) >= 1) {?>
                    <a href="#" class="btn btn-success" disabled  title="Parceria já cadastrada"><i class="icon-plus icon-white"></i> Novo arquivo</a>
                    <?php }else{ ?>
                    <a href="<?php echo site_url('cadastros/parcerias')?>" class="btn btn-success"><i class="icon-plus icon-white"></i> Novo arquivo</a>
                    <?php } ?>
                </div>
            </div>
        </div><br>	
    	<?php 
    	
        if(count($parcerias) > 0){
        ?>
        <table id="table" class="table table-bordered table-hover">
        	<thead style="background-color: #0072cc !important; color: white !important;">
        		<th style="width: 100px; text-align: center;"></th>
        		<th style="text-align: center;">Descrição</th>
                <th style="width: 200px; text-align: center;">Excluir</th>
        	</thead>
        	<tbody>
        	<?php foreach ($parcerias as $parceria){?>
        		<tr>
        			<td style="text-align: center; vertical-align: middle;"><?php echo $parceria->id;?></td>
        			<td style="vertical-align: middle;"><?php echo $parceria->descricao; ?></td>
        			<td style="text-align: center;">
        				<?php 
        				$query = $this->db->query("SELECT id_apresentacao, file FROM cad_parcerias_arquivos
                        WHERE id_apresentacao ='$parceria->id'");
        				
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
        				<a <?php echo $visualizarImagem;?> href="<?php echo base_url("uploads//gente_gestao/desenv_organizagional/parcerias/$arquivoImage");?>" class="btn btn-mini btn-default vapresentacao<?=$row['id_apresentacao']?>"  title="Visualizar">
                            <i class="fa fa-eye"></i>
                        </a>
                        <?php } ?>
                        <a <?php echo $visualizarDownload;?> href="<?php echo base_url("uploads//gente_gestao/desenv_organizagional/parcerias/$arquivoDownload");?>" class="btn btn-mini btn-default"  title="Download">
                            <i class="fa fa-file"></i>
                        </a>
                        <?php $i++;} ?>
        				<a onclick="excluirParceria(<?=$parceria->id?>)" class="btn btn-mini btn-danger" title="Excluir">
                            <i class="fa fa-remove"></i>
                        </a>
        			</td>
        		</tr>
        		<script>
        			$(".vapresentacao<?=$parceria->id?>").colorbox({rel:'vapresentacao<?=$parceria->id?>'});
        		</script>
        		<?php }?>
        	</tbody>
        </table>
        <?php }else{ ?>
        <div class="alert alert-warning">
        	<strong>Desculpe!</strong> Nenhuma arquivo cadastrado até o momento.
        </div>
        <?php } 
        }else{ 
            $this->load->view('erros/403');
         } ?>
    </div>      
</div>
 <script>
 $(".vapresentacao14").colorbox({rel:'vapresentacao14'});
 
function excluirEad(id) {

	var r = confirm("Desseja realmente excluir esse arquivo? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_treinamentos').'/' ?>"+id;
    
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

function excluirParceria(id) {

	var r = confirm("Desseja realmente excluir esse arquivo? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluirParcerias').'/' ?>"+id;
    
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