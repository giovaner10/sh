<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_sobre_empresa')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-primary" href="<?php echo site_url('cadastros/listar_sobreaempresa');?>"><?=lang('editar')?></a>
	</div>
	<?php } ?>
	<h5><?=lang('sobre_a_empresa')?></h5>
	<hr>
<?php
if (count($sobre) > 0) {
    foreach ($sobre as $r) {
        ?>
 <div class="box box-info">
		<div class="box-header with-border">
			<h2 class="box-title"><?php echo $r->titulo; ?></h2>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
		<?php echo $r->descricao;?>
	</div>
	</div>
<?php
    }
}
?>
</div>
