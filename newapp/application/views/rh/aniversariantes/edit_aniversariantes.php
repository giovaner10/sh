<?php if ($this->auth->is_allowed_block('cad_aniversariantes')){ ?>
<h3>Editar Aniversariante</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php 
	if($aniversariantes->ativo == '1'){
	    $checked = 'checked="checked"';
	}else{
	    $checked = "";
	}

	?>
	<div class="tab-content">
		<form class="form-horizontal" method="post" name="formcontato"	enctype="multipart/form-data"	action="<?php echo site_url("cadastros/edit_aniversariante/$aniversariantes->id")?>">
			<div class="box-body">
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Nome completo*</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="nome" id="nome" value="<?=$aniversariantes->nome?>" required>
					</div>
				</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Cpf*</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="cpf" id="cpf" value="<?=$aniversariantes->cpf?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">E-mail*</label>
					<div class="col-sm-6">
						<input type="email" class="form-control" name="email" id="email" value="<?=$aniversariantes->email?>" required>
					</div>
				</div>
				<div class="form-group">
    				<label class="col-sm-2 control-label">Empresa *</label> 
    				<div class="col-sm-6">        					
    					<select name="empresa" class="form-control" required>   
    						<option value="Omnlink" <?php if($aniversariantes->empresa == "Omnlink"){ echo 'selected';}?>>Omnlink</option>            					
        					<option value="Shownet" <?php if($aniversariantes->empresa == "Shownet"){ echo 'selected';}?>>Show Tecnologia</option>        					
        				</select>            				
        			</div>
    			</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Cargo</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="cargo" id="cargo" value="<?=$aniversariantes->cargo?>">
					</div>
				</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Data Nascimento *</label>
					<div class="col-sm-6">
						<input type="date" class="form-control" name="datanascimento" id="datanascimento" value="<?=$aniversariantes->data_nasc?>" required>
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
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-6">
							<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" /> 
							<a href="<?=site_url('cadastros/listar_aniversariantes')?>" class="btn btn-primary"> Voltar</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script src="<?php echo base_url('assets/js/jquery.mask.js');?>"></script>
<script type="text/javascript">

$('#cpf').mask('000.000.000-00');


function excluir(id) {

	var r = confirm("Desseja realmente excluir esse produto? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_produto_editar').'/' ?>"+id;
    
         $.ajax({
            url : url,
            type : 'POST',
            beforeSend: function(){
    			alert('Aguarde um momento por favor...');
            },
            success : function(data){
            	alert(data);
    	        window.location.reload();  
            },
            error : function () {
                alert('Error...');
            }
        });
	}
}
</script>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>