<?php if ($this->auth->is_allowed_block('cad_comite_guerra')){ ?>
<h3>Cadastro Comitê de Guerra</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
			<?php if(isset($totalFiles)) echo "Successfully uploaded ".count($totalFiles)." files"; ?>
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url('cadastros/cad_comite_guerra')?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" required>
    			</div>
    		</div>
    		<div class="form-group">
                <label class="col-sm-2 control-label">Arquvos</label>
                <div class="col-sm-6">
                	<input type="file" class="form-control" name="arquivo[]" multiple="">
                </div>
            </div>
    		<!-- <div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Apresentação em PPT</label>
    			<div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo">
    			</div>
    		</div> -->
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Enviar" />
        				<a href="listar_comite_guerra" class="btn btn-primary"> Voltar</a>
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