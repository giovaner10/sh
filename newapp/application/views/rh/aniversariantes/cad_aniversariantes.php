<?php if ($this->auth->is_allowed_block('cad_aniversariantes')){ ?>
<h3>Cadastro de Aniversariantes</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<div class="tab-content">
		<form class="form-horizontal" method="post" name="formcontato"	enctype="multipart/form-data"	action="<?php echo site_url('cadastros/cad_aniversariante')?>">
			<div class="box-body">
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Nome completo*</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="nome" id="nome" required>
					</div>
				</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Cpf*</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="cpf" id="cpf" required>
					</div>
				</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">E-mail*</label>
					<div class="col-sm-6">
						<input type="email" class="form-control" name="email" id="email" required>
					</div>
				</div>
				<div class="form-group">
    				<label class="col-sm-2 control-label">Empresa *</label> 
    				<div class="col-sm-6">        					
    					<select name="empresa" class="form-control" required>   
    						<option value="" selected>Selecione...</option> 
    						<option value="Omnlink">Omnlink</option>            					
        					<option value="Shownet">Show Tecnologia</option>        					
        				</select>            				
        			</div>
    			</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Cargo</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="cargo" id="cargo">
					</div>
				</div>
				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Data Nascimento *</label>
					<div class="col-sm-6">
						<input type="date" class="form-control" name="datanascimento" id="datanascimento" required>
					</div>
				</div>
				<div class="box-footer">
					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-6">
							<input type="submit" id="enviar" class="btn btn-success btn-small" value="salvar" /> 
							<a href="listar_aniversariantes" class="btn btn-primary"> Voltar</a>
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
</script>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>