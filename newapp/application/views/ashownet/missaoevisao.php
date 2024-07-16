<section class="content">
	<?php if ($this->auth->is_allowed_block('cad_sobre_empresa')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/listar_sobreaempresa');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<div class="row">
    	<h3>Missão, Visão e Valores</h3>
    	<hr>
        <?php
         if (count($sobre) > 0) {
            foreach ($sobre as $r) {
         ?>
         <div class="col-md-4">   
            <div class="box box-info">
            	<div class="box-header with-border">
            		<h2 class="box-title">Missão</h2>
            	</div>
            	<!-- /.box-header -->
            	<div class="box-body">
            		<?php echo $r->missao; ?>
            	</div>
            </div>
        </div>
        <div class="col-md-4">   
            <div class="box box-info">
            	<div class="box-header with-border">
            		<h2 class="box-title">Visão</h2>
            	</div>
            	<!-- /.box-header -->
            	<div class="box-body">
            		<?php echo $r->visao; ?>
            	</div>
            </div>
        </div>
		<div class="col-md-4">
            <div class="box box-info">
            	<div class="box-header with-border">
            		<h2 class="box-title">Valores</h2>
            	</div>
            	<!-- /.box-header -->
            	<div class="box-body">
            		<?php echo $r->valores; ?>
            	</div>
            </div>
            <?php
                }
            }
            ?>
    	</div>
	</div>
</section>
