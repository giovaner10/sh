<h3>Editar Comunicado</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php 
	if($comunicados->ativo == '1'){
	    $checked = 'checked="checked"';
	}else{
	    $checked = "";
	}
	?>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_comunicado/$comunicados->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Comunicado</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" value="<?= $comunicados->comunicado?>">
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Arquivo *pdf</label>
    			<div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo">
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
        				<a href="<?php echo site_url("cadastros/listar_comunicados")?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
</div>