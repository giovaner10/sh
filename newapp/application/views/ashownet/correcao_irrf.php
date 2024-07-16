<div class="containner"><br>
	<?php if ($this->auth->is_allowed_block('cad_correcao_irrf')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/listar_correcao_irrf');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Gente &amp; Gestão</h5>
	<h2 class="TitPage">Administração de Pessoal</h2>
	<div style="float: right">
		<a href="adm_pessoal">Voltar</a>
	</div> 
	<br> 
	<br>
	<div class="row">
		<?php 
		$nome = $this->auth->get_login('admin', 'nome');
		
        if(count($dados) > 0){
            foreach ($dados as $dado){
        ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<h4 class="page-header text-center" style="margin-top: 5px;">
						<strong><?php echo $dado->titulo;?></strong>
					</h4>
				</div>
				<div class="form-group">
					<div class="row" id="topo">
						<div class="col-xs-12" style="font-size: 14px;">
							<p>Olá <?php echo $nome;?></p>
							<?php echo $dado->descricao;?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
		<?php }else{ ?>
		<div class="alert alert-warning">
        	<strong>Desculpe!</strong> Nenhuma correção cadastrada até o momento.
        </div>
        <?php } ?>
	</div>
</div>