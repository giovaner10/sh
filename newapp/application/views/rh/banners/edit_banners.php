<h3>CadAlterar Banner</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php foreach ($dados_banner as $dado_banner){ ?>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_banners/$dado_banner->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" value="<?php echo $dado_banner->descricao;?>"  required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Banner</label>
    			<div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo">
    				<div class="alert alert-warning">
                    	Caso não queria alterar o Banner já cadastrado, não selecione um novo arquivo!
                    </div>    				
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
        				<a href="<?php echo site_url('cadastros/listar_banners');?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
	<?php } ?>
</div>