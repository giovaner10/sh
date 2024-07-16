<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_propostas_comerciais')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/listar_propostas_comerciais');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Televendas</h5>
	<h2 class="TitPage">Propostas Comerciais</h2>
	<?php if (count($lista_arquivos) > 0) {	?>
	<div class="row">
		<div class="box box-info">
            <div class="box-body">
			<?php foreach ($lista_arquivos as $lista_arquivo) { ?>
				<a href="<?php echo base_url("uploads/propostas_comerciais/$lista_arquivo->file");?>" download="<?php echo $lista_arquivo->file;?>" class="list-group-item" target="_blank">&nbsp;&nbsp;<span class="glyphicon glyphicon-file  btn-xs"></span><?php echo $lista_arquivo->descricao?></a>
			<?php } ?>
			</div>
		</div>
	</div>
	<?php }else{ ?>
	<div class="alert alert-warning">
      Não há arquivos cadastrados no momento.
    </div>
	<?php } ?>
</div>