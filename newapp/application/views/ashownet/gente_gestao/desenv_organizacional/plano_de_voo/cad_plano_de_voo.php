<?php if ($this->auth->is_allowed_block('cad_plano_de_voo')){ ?>
<h3>Cadastro de Plano de voo</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
			<?php if(isset($totalFiles)) echo "Successfully uploaded ".count($totalFiles)." files"; ?>
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
    <form action="<?php echo site_url('cadastros/cad_plano_de_voo')?>" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="nome">Nome:</label>
    		<div class="col-sm-7">
    			<input type="text" name="nome" value="" class="form-control" required>
    		</div>
    	</div>
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="obs">Descrição:</label>
    		<div class="col-sm-7">
    			<textarea class="form-control" rows="5" id="descricao" name="descricao" ></textarea>
    		</div>
    	</div>
    	<div class="form-group">
    		<div id="anexoDiv"></div>
    	</div>            	
    	<div class="form-group"> 
    		<div class="col-sm-offset-2 col-sm-5">
    			<button type="submit" name="salvar" class="btn btn-success">Salvar</button>
    			<a href="listar_plano_de_voo" class="btn btn-primary"> Voltar</a>		    
    		</div>
    	</div>
    </form>		
</div>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>