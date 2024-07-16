<?php if ($this->auth->is_allowed_block('cad_marketing_campanhas')){ ?>
<h3 class="TitPage">Cadastro Marketing  Campanhas</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<ul class="nav nav-tabs">
    	<li class="active"><a data-toggle="tab" href="#show" class="show">Informações Gerais</a></li>
    	<li class="sim"><a data-toggle="tab" href="#sim" >Assuntos</a></li>
	</ul>
	<div class="tab-content">   
    	<div id="show" class="tab-pane fade in active">
    		<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url('cadastros/cad_marketing_campanhas')?>">
            	<div class="box-body">
            		<div class="form-group">
        				<label class="col-sm-2 control-label">Grupo</label> 
        				<div class="col-sm-6">        					
        					<select name="assunto" class="form-control" required>   
        						<?php if(count($assuntos) > 0) { ?> 
        						    <option value="" selected="selected">Selecione um assunto...</option>
        						<?php
        						  foreach($assuntos as $assunto){
        						?> 				
            					<option value="<?=$assunto->id?>"><?=$assunto->assunto?></option>            					
            					<?php } ?>
            					<?php }else{  ?>
            					<option value="">Não há assuntos cadastrados</option>
            					<?php } ?>
            				</select>            				
            			</div>
        			</div>
        			<div class="form-group">
            			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
            			<div class="col-sm-6">
            				<input type="text" class="form-control" name="descricao" id="descricao" required>
            			</div>
            		</div>
        			<div class="form-group">
            			<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
            			<div class="col-sm-6">
            				<input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo" required>
            			</div>
            		</div>
            		<div class="box-footer">
                		<div class="form-group">
                			<label for="arquivo" class="col-sm-2 control-label"></label>
                			<div class="col-sm-6">
                				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Enviar" />
                				<a href="<?php echo site_url('ashownet/marketing_campanhas');?>" class="btn btn-primary"> Voltar</a>
                			</div>
                		</div>
            		</div>
            	</div>
        	</form>
    	</div>
    	<div id="sim" class="tab-pane fade">
            <form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url('cadastros/cad_assuntos_marketing_campanhas')?>">
            	<div class="box-body">
            		<div class="form-group">
            			<label for="descricao" class="col-sm-2 control-label">Assunto</label>
            			<div class="col-sm-6">
            				<input type="text" class="form-control" name="assunto" id="assunto" required>
            			</div>
            		</div>
            		<div class="box-footer">
                		<div class="form-group">
                			<label for="arquivo" class="col-sm-2 control-label"></label>
                			<div class="col-sm-6">
                				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Salvar" />
                				<a href="<?php echo site_url('ashownet/marketing_campanhas');?>" class="btn btn-primary"> Voltar</a>
                			</div>
                		</div>
            		</div>
            	</div>
        	</form>
    	</div>
	</div>
</div>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>