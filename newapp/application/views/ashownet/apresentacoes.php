<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/colorbox.css" />
<script src="<?php echo base_url('assets');?>/js/jquery.colorbox.js"></script>
<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_apresentacoes')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/listar_apresentacoes');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5><?php echo $titulo;?></h5>
	<h2 class="TitPage">Apresentações dos Produtos</h2>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php 
                if(count($apresentacoes) > 0){
                    foreach ($apresentacoes as $apresentacao){
                ?>
				<div class="col-sm-4">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<div class="list-group-item-text text-center" ><h4><?=$apresentacao->descricao?></h4></div><br>    
							<?php 
							$query = $this->db->query("SELECT id_apresentacao, file FROM cad_apresentacao_arquivos WHERE id_apresentacao ='$apresentacao->id'");
							
							$i=0;
							$visualizarImagem = "";
							$arquivoDownload = "";
							foreach ($query->result_array() as $row) {
							    
							    $ext = substr($row['file'], (strlen($row['file']) - 3), strlen($row['file']));
							    
							    if($ext != "ppt" && $ext  != "ptx"){
							        $arquivoImage = $row['file'];
							    }else{
							        $arquivoDownload = $row['file'];
							    }
							    
							    if($i != 1 && ($ext  != "ppt" || $ext  != "ptx")){
							        $visualizarImagem = 'style="display: none;"';
							    }else{
							        $visualizarImagem = "";
							    }
							    
							if($i !=0 && ($ext != "ppt" || $ext != "ptx")){ ?>
							<div class="col-sm-12" <?php echo $visualizarImagem;?> >
								<img src="<?php echo base_url("uploads/apresentacoes/$arquivoImage");?>" width="100%"><br>
								<br>
							</div>     					
							<div class="col-sm-6 text-center " <?php echo $visualizarImagem;?>>
								<a href="<?php echo base_url("uploads/apresentacoes/$arquivoImage");?>"class="btn btn-primary vapresentacao<?=$row['id_apresentacao']?>">Reproduzir</a>
							</div>
							<?php } ?>
							<?php $i++;} ?>
							<div class="col-sm-6 text-center">
								<a href="<?php echo base_url("uploads/apresentacoes/$arquivoDownload");?>" class="btn btn-primary">Download</a>
							</div>
						</div>
					</div>
				</div>
				<script>
        			$(".vapresentacao<?=$apresentacao->id?>").colorbox({rel:'vapresentacao<?=$apresentacao->id?>'});
        		</script>
				<?php }?>
				<?php }else{ ?>
				<div class="alert alert-warning">
                	<strong>Desculpe!</strong> Nenhuma apresentação cadastrada até o momento.
                </div>
                <?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
	$(".vapresentacao").colorbox({rel:'vapresentacao'});
</script>