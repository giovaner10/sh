<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<h3>Castro Contato Corporativo</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" id="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/cad_contato_corporativo")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Tipo</label>
    			<div class="col-sm-6">
    				<select class="form-control" name="tipo" id="tipo" required>
    					<option value="" selected="selected">Selecione...</option>
    					<option value="Atendimento a Clientes">Atendimento a Clientes</option>
    					<option value="Projetos Dedicados">Projetos Dedicados</option>
    					<option value="MATRIZ">MATRIZ</option>
    					<option value="FILIAIS">FILIAIS</option>
    				</select>
    			</div>
    		</div>
    		<div class="form-group matrizfilial">
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Endereço *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="endereco" id="endereco" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Número *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="numero" id="numero" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Bairro *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="bairro" id="bairro" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Complemento *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="complemento" id="complemento" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Cidade *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="cidade" id="cidade" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">UF *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="uf" id="uf" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Telefone *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="telefone" id="telefone" data-mask="(##)####-####" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">CEP *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="cep" name="id" data-mask="#####-###" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">CNPJ *</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="cnpj" name="id" data-mask="##.###.###/####-##" value="" maxlength="18">
        			</div>
        		</div>
    		</div>
    		<div class="form-group projetoatendimento">
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Título</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="titulo" id="titulo" value="">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="descricao" id="descricao" value="">
        			</div>
        		</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="button" id="enviar" class="btn btn-success btn-small" value="Salvar" />
        				<a href="<?php echo site_url("cadastros/listar_contatos_corporativos")?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
</div>
<script src="<?php echo base_url();?>assets/js/puremask.js"></script>
<script type="text/javascript">
$("#tipo").on('change', function(e){
 
    if ($(this).val() == 'Atendimento a Clientes' || $(this).val() == 'Projetos Dedicados'){
      
      $(".projetoatendimento").show();
      $(".matrizfilial").hide();

    }else if ($(this).val() == 'MATRIZ' || $(this).val() == 'FILIAIS'){
    	$(".matrizfilial").show();
        $(".projetoatendimento").hide();        
	}
	
  	return false;
});

$(".projetoatendimento").hide();
$(".matrizfilial").hide();

PureMask.format("#telefone", true);
PureMask.format("#cep", true);  
PureMask.format("#cnp", true);


$("#enviar").on('click', function() {

	if($("#tipo").val() == "MATRIZ" || $("#tipo").val() == "FILIAIS"){

    	if($("#endereco").val() == ""){
    		alert('O campo endereço é obrigatório');
    		$("#endereco").focus();
    		return false;
       	}

    	if($("#numero").val() == ""){
    		alert('O campo número é obrigatório');
    		$("#numero").focus();
    		return false;
       	}

    	if($("#bairro").val() == ""){
    		alert('O campo bairro é obrigatório');
    		$("#bairro").focus();
    		return false;
       	}

    	if($("#complemento").val() == ""){
    		alert('O campo complemento é obrigatório');
    		$("#complemento").focus();
    		return false;
       	}

    	if($("#cidade").val() == ""){
    		alert('O campo cidade é obrigatório');
    		$("#cidade").focus();
    		return false;
       	}

    	if($("#uf").val() == ""){
    		alert('O campo uf é obrigatório');
    		$("#uf").focus();
    		return false;
       	}

    	if($("#telefone").val() == ""){
    		alert('O campo telefone é obrigatório');
    		$("#telefone").focus();
    		return false;
       	}

    	if($("#cep").val() == ""){
    		alert('O campo cep é obrigatório');
    		$("#cep").focus();
    		return false;
       	}

    	if($("#cnpj").val() == ""){
    		alert('O campo cnpj é obrigatório');
    		$("#cnpj").focus();
    		return false;
       	}    
       	   	
	}else{

		if($("#titulo").val() == ""){
    		alert('O campo titulo é obrigatório');
    		$("#titulo").focus();
    		return false;
       	}

		if($("#descricao").val() == ""){
    		alert('O campo descrição é obrigatório');
    		$("#descricao").focus();
    		return false;
       	}
	}
   	
	document.getElementById("formcontato").submit();
})
</script>