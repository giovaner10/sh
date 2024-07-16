<div class="containner">
	
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/listar_comercial');?>">INCLUIR</a>
	</div>
	<h5>A Shownet</h5>
	<h2 class="TitPage">Comercial</h2>
	<?php if (count($lista_dados) > 0) {	?>
	<div class="row">
		<div class="box box-info">
            <div class="box-body">
			<?php foreach ($lista_dados as $lista_dado) { ?>
				<a href="<?php echo base_url("uploads/comercial/$lista_dado->file");?>" download="<?php echo $lista_dado->file;?>" class="list-group-item" target="_blank">&nbsp;&nbsp;<span class="glyphicon glyphicon-file  btn-xs"></span><?php echo $lista_dado->descricao?></a>
			<?php } ?>
			</div>
		</div>
	</div>
	<?php }else{ ?>
	<div class="alert alert-warning">
      Não há arquivo do comercial cadastrados no momento.
    </div>
	<?php } ?>
</div>