<?php if ($this->auth->is_allowed_block('cad_docs_pendentes')){ ?>
<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<h3>Documentação necessária</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/cad_docs_pendentes")?>">
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
    					<option value="<?=$usuario->id?>"><?=$usuario->nome?></option>            					
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
    				<span><input type="checkbox" name="residencia" value="1"> Comprovante de residência</span><br>
    				<span><input type="checkbox" name="cpf"value="1"> CPF</span><br>
    				<span><input type="checkbox" name="rg" value="1"> RG</span><br>
    				<span><input type="checkbox" name="banco" value="1"> Comprovante de dados bancários</span>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Prazo para entrega</label>
    			<div class="col-sm-6">
    				<input type="date" name="prazo" class="form-control" value="" required>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Salvar" />
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