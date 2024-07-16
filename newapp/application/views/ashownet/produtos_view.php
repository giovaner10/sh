<div class="containner">
	<div class="btn-group pull-right">
    	<a class="btn btn-primary btn-xs" href="<?php echo site_url('ashownet/produtos');?>">voltar</a>
    </div>
	<h5>A Shownet</h5>
	<?php if (count($lista_produtos) > 0) {	?>
	<h2 class="TitPage"><?php echo $lista_produtos[0]->assunto;?></h2>	
	<div class="row">
		<div class="box box-info">
            <div class="box-body">
			<?php foreach ($lista_produtos as $lista_produto) { 
			    $ext = substr($lista_produto->file, (strlen($lista_produto->file) - 3), strlen($lista_produto->file));
			    
			    if($ext != "jpg" && $ext != "png"){			        
			    ?>
				<a href="<?php echo base_url("uploads/produtos/$lista_produto->file");?>" download="<?php echo $lista_produto->file;?>" class="list-group-item" target="_blank">&nbsp;&nbsp;<span class="glyphicon glyphicon-file  btn-xs"></span><?php echo $lista_produto->descricao?></a>
			<?php }
			} ?>
			</div>
		</div>
	</div>
	<?php }else{ ?>
	<div class="alert alert-warning">
      Não há produtos cadastrados no momento.
    </div>
	<?php } ?>
</div>