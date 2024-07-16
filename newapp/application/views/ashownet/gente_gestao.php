<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_gente_gestao')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/listar_gente_gestao');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Gente &amp; Gestão</h5>
	<div class="row">
		<div class="box box-info">
			<div class="box-body">
				<h2 class="TitPage">Espaço Gente &amp; Gestão</h2>
				<?php 
				if(count($dados) > 0){
				    foreach ($dados as $dado) {
				        echo $dado->descricao;				        
				?>				    
				<div class="row" style="height: 100%">
					<div class="col-sm-12" style="padding: 0px; border: none; height: 100%">
						<a href="desenv_organizacional" class="col-sm-offset-1 col-sm-5" style="padding-bottom: 15px; border: none"> 
							<img src="<?php echo base_url("uploads/gente_gestao/rh_desenvOrganiz.png");?>" style="width: 300px;">
						</a> 
						<a href="adm_pessoal" class="col-sm-5" style="padding-bottom: 15px; border: none">
							<img src="<?php echo base_url("uploads/gente_gestao/rh_admPessoal.png");?>" style="width: 300px;">
						</a>
					</div>
				</div>
				<?php }}?>
			</div>
		</div>
	</div>
</div>