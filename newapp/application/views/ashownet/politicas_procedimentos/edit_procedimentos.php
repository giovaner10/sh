<?php if ($this->auth->is_allowed_block('cad_politicas_procedimentos')){ ?>
<h3>Alterar Política e Procedimentos Comerciais</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php foreach ($dados_espaco_ti as $dado_espaco_ti){ ?>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_politicas_procedimentos/$dado_espaco_ti->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" value="<?php echo $dado_espaco_ti->descricao;?>" id="descricao" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
    			<div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo">
    				<div class="alert alert-warning">
                    	Caso não queria alterar o Arquivo já cadastrado, não selecione um novo arquivo!
                    </div>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
        				<a href="<?php echo site_url('cadastros/listar_politicas_procedimentos');?>" class="btn btn-primary"> Voltar</a>
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