<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_teste_homologacao')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/listar_engenharia_teste_homologacao');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Engenharia e Tecnologia</h5>
	<h2 class="TitPage">Teste e Homologação</h2>
	<?php if (count($lista_arquivos) > 0) {	?>
	<div class="row">
		<div class="box box-info">
            <div class="box-body">
			<?php foreach ($lista_arquivos as $lista_arquivo) { ?>
				<a href="<?php echo base_url("uploads/engenharia_teste_homologacao/$lista_arquivo->file");?>" download="<?php echo $lista_arquivo->file;?>" class="list-group-item" target="_blank">&nbsp;&nbsp;<span class="glyphicon glyphicon-file  btn-xs"></span><?php echo $lista_arquivo->descricao?></a>
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