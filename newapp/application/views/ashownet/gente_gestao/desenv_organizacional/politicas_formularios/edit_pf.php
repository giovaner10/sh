<h3>Editar Informações</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php 
	if($informacao->status == 'ativo'){
	    $checked = 'checked="checked"';
	}else{
	    $checked = "";
	}
	?>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_informacao_formulario/$informacao->id")?>">
    	<div class="box-body">
			<div class="form-group">
				<label class="col-sm-2 control-label"></label> 
				<div class="col-sm-6">
    				<div class="radio">
    					<label><input type="radio" name="tipo" id="tipo" value="P" <?php if($informacao->tipo == "P"){echo 'checked="checked"'; }?>><strong>Políticas</strong></label>
    					<label><input type="radio" name="tipo" id="tipo" value="F" <?php if($informacao->tipo == "F"){echo 'checked="checked"'; }?>><strong>Formulários e Informações Gerais</strong></label>
    				</div>
				</div>
			</div>
			<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" value="<?=$informacao->descricao?>" required>
    			</div>
    		</div>
			<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
    			<div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo">
    				<div class="alert alert-warning">
                    	Caso não queria alterar o arquivo já cadastrado, não selecione um novo arquivo!
                    </div>  
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Ativo</label>
    			<div class="col-sm-6">
    				<input type="checkbox" name="status" id="status" value="ativo" <?php echo $checked;?>>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
        				<a href="<?php echo site_url("cadastros/politicas_formulariosrh");?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
</div>