<?php if ($this->auth->is_allowed_block('cad_treinamentos')){ ?>
<h3>Editar Desenvolvimento Organizacional Treinamentos</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php foreach ($dados_treinamentos as $dados_treinamento){?>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_treinamentos/$dados_treinamento->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" value="<?php echo $dados_treinamento->descricao;?>" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Tipo</label>
    			<div class="col-sm-6">
    				<select name="tipo" class="form-control" required>
    					<option value="" selected="selected">Selecione</option>
    					<option value="online" <?php if($dados_treinamento->tipo == "online"){echo "selected='selected'";}?>>Treinamentos Online</option>
    					<option value="videos" <?php if($dados_treinamento->tipo == "videos"){echo "selected='selected'";}?>>Vídeo Aulas</option>
    				</select>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Link</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="link" id="link" value="<?php echo $dados_treinamento->link;?>" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Foto Capa</label>
    			<div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo">
    				<div class="alert alert-warning">
                    	Caso não queria alterar o arquivo já cadastrado, não selecione um novo arquivo!
                    </div>    
    			</div>
    		</div>    		
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="alterar" />
        				<a href="<?php echo site_url('cadastros/listar_treinamentos');?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
	<?php } ?>
</div>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>