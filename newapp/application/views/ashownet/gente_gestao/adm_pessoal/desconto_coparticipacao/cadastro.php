<?php if ($this->auth->is_allowed_block('cad_desconto_coparticipacao')){ ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<h3>Desconto de Coparticipação</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/cad_desconto_coparticipacao")?>">
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
    			<label for="descricao" class="col-sm-2 control-label">Valor coparticipação</label>
    			<div class="col-sm-6">
    				<input type="text" name="valcoparticipacao" class="form-control dinheiro" value="" required>
    			</div>
    		</div>
    		<div class="form-group">
        		<label class="control-label col-sm-2" for="destino">Dependentes:</label>
        		<div class="col-sm-7" style="height: auto;">
        			<div id="viewdestino"></div>
        		</div>
        	</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Mês de competência </label>
    			<div class="col-sm-6">
    				<select class="form-control" name="mescompetencia">
    					<option value="<?=date('Y-01-00')?>">Janeiro</option>
    					<option value="<?=date('Y-02-00')?>">Fevereiro</option>
    					<option value="<?=date('Y-03-00')?>">Março</option>
    					<option value="<?=date('Y-04-00')?>">Abril</option>
    					<option value="<?=date('Y-05-00')?>">Maio</option>
    					<option value="<?=date('Y-06-00')?>">Junho</option>
    					<option value="<?=date('Y-07-00')?>">Julho</option>
    					<option value="<?=date('Y-08-00')?>">Agosto</option>
    					<option value="<?=date('Y-09-00')?>">Setembro</option>
    					<option value="<?=date('Y-10-00')?>">Outubro</option>
    					<option value="<?=date('Y-11-00')?>">Novembro</option>
    					<option value="<?=date('Y-12-00')?>">Dezembro</option>
    				</select>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Salvar" />
        				<a href="<?php echo site_url("cadastros/listar_desconto_coparticipacao")?>" class="btn btn-primary"> Voltar</a>
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
 <script type="text/javascript">
 $(".dinheiro").maskMoney();
 
 $("select[name=funcionario]").change(function(){
     $("#viewdestino").html('<div class="form-control">Aguarde um momento....</div>');
     
     $.post("<?php echo site_url("cadastros/lista_dependentes");?>",{
     	idfuncionario:$(this).val()
     },	    
     function(valor){
     	$("#viewdestino").html(valor);        	
      })
 })
 </script>