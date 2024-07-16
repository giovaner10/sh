<?php if ($this->auth->is_allowed_block('cad_desconto_coparticipacao')){ ?>
<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<h3>Editar Documentação necessária</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>	
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_docs_pendentes/$dados->id")?>">
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
    					
    					<option value="<?=$usuario->id?>"<?php echo $usuario->id == $dados->id_funcionario ? "selected = 'selected'" : $usuario->id; ?>><?=$usuario->nome?></option>           					
    					<?php } ?>
    					<?php }else{  ?>
    					<option value="">Não há usuários cadastrados</option>
    					<?php } ?>
    				</select>            				
    			</div>
			</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Documentação</label>
    			<div class="col-sm-6">
    				<span><input type="checkbox" name="residencia" value="1" <?php if($dados->residencia == '1'){ echo 'checked';}?>>Comprovante de residência</span><br>
    				<span><input type="checkbox" name="cpf"value="1" <?php if($dados->cpf == '1'){ echo 'checked';}?>>CPF</span><br>
    				<span><input type="checkbox" name="rg" value="1" <?php if($dados->rg == '1'){ echo 'checked';}?>>RG</span><br>
    				<span><input type="checkbox" name="banco" value="1" <?php if($dados->banco == '1'){ echo 'checked';}?>>Comprovante de dados bancários</span>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Prazo para entrega</label>
    			<div class="col-sm-6">
    				<input type="date" name="prazo" class="form-control" value="<?php echo $dados->prazo_maximo;?>" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Recebido</label>
    			<div class="col-sm-6">
    				<span><input type="checkbox" name="recebido" value="1" <?php if($dados->recebido == '1'){ echo 'checked';}?>> Recebi todos os documentos</span><br>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Atualizar" />
        				<a href="<?php echo site_url("cadastros/listar_docs_pendentes")?>" class="btn btn-primary"> Voltar</a>
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