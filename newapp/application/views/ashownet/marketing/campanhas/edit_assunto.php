<?php if ($this->auth->is_allowed_block('cad_marketing_campanhas')){ ?>
<h3>Editar Assunto</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php 
	if($assunto->ativo == '1'){
	    $checked = 'checked="checked"';
	}else{
	    $checked = "";
	}
	?>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_assunto_marketing_campanhas/$assunto->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Assunto</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="assunto" id="assunto" value="<?php echo $assunto->assunto;?>" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Ativo</label>
    			<div class="col-sm-6">
    				<input type="checkbox" name="ativo" id="ativo" value="1" <?php echo $checked;?>>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
        				<a href="<?php echo site_url("ashownet/marketing_campanhas");?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
</div>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>