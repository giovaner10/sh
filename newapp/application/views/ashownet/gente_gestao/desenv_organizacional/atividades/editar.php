<?php if ($this->auth->is_allowed_block('cad_atividades')){ ?>
<h3>Editar Atividade</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_atividade/$listar_dados->id")?>">
    	<div class="box-body">
    		<div class="form-group">
				<label class="col-sm-2 control-label">Funcionário</label> 
				<div class="col-sm-6">        					
					<select name="funcionario" class="form-control" required>   
						<?php if(count($usuarios) > 0) { ?> 
						    <option value="" selected="selected">Selecione um funcionário...</option>
						<?php
						foreach($usuarios as $usuario){
						?> 				
    					<option value="<?=$usuario->id?>"<?php echo $usuario->id == $listar_dados->id_funcionario ? "selected = 'selected'" : $usuario->id; ?>><?=$usuario->nome?></option>           					
    					<?php } ?>
    					<?php }else{  ?>
    					<option value="">Não há usuários cadastrados</option>
    					<?php } ?>
    				</select>            				
    			</div>
			</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Curso</label>
    			<div class="col-sm-6">
    				<input type="text" name="curso" class="form-control" value="<?=$listar_dados->curso?>" required>
    			</div>
    		</div>
    		<div class="form-group">
				<label class="col-sm-2 control-label">Tipo</label> 
				<div class="col-sm-6">        					
					<select name="tipo" class="form-control" required>   
						<option value="Presencial" <?php if($listar_dados->tipo == "Presencial"){echo 'selected';}?>>Presencial</option>
						<option value="Web-Curso" <?php if($listar_dados->tipo == "Web-Curso"){echo 'selected';}?>>Web-Curso</option>
						<option value="Avaliação" <?php if($listar_dados->tipo == "Avaliação"){echo 'selected';}?>>Avaliação</option>      					
    				</select>            				
    			</div>
			</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Data Incio</label>
    			<div class="col-sm-6">
    				<input type="date" name="inicio" class="form-control" value="<?=$listar_dados->data_inicio?>">
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Data Término</label>
    			<div class="col-sm-6">
    				<input type="date" name="fim" class="form-control" value="<?=$listar_dados->data_fim?>">
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Carga Hrs</label>
    			<div class="col-sm-6">
    				<input type="number" min="1" name="cargahr" class="form-control" value="<?=$listar_dados->carga_hr?>">
    			</div>
    		</div>
    		<div class="form-group">
				<label class="col-sm-2 control-label">Status</label> 
				<div class="col-sm-6">        					
					<select name="status" class="form-control">   
						<option value="Cursando" <?php if($listar_dados->status == "Cusando"){echo 'selected';}?>>Cursando</option>
						<option value="Aprovado" <?php if($listar_dados->status == "aprovado"){echo 'selected';}?>>Aprovado</option>    					
    				</select>            				
    			</div>
			</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
        				<a href="<?php echo site_url("cadastros/listar_atividades")?>" class="btn btn-primary"> Voltar</a>
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