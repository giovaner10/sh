<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<h3>Editar Contato Corporativo</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_contatos_corporativos/$contatos_corporativos->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Tipo</label>
    			<div class="col-sm-6">
    				<select class="form-control" name="tipo" id="tipo">
    					<option value="Atendimento a Clientes" <?php if($contatos_corporativos->tipo == "Atendimento a Clientes"){echo "selected='selected'";}?>>Atendimento a Clientes</option>
    					<option value="Projetos Dedicados" <?php if($contatos_corporativos->tipo == "Projetos Dedicados"){echo "selected='selected'";}?>>Projetos Dedicados</option>
    					<option value="MATRIZ" <?php if($contatos_corporativos->loja == "MATRIZ"){echo "selected='selected'";}?>>MATRIZ</option>
    					<option value="FILIAIS" <?php if($contatos_corporativos->loja == "FILIAIS"){echo "selected='selected'";}?> >FILIAIS</option>
    				</select>
    			</div>
    		</div>
    		<div class="form-group matrizfilial">
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Endere&ccedil;o</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="endereco" value="<?=$contatos_corporativos->endereco?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">N&uacute;mero</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="numero" value="<?=$contatos_corporativos->numero?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Bairro</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="bairro" value="<?=$contatos_corporativos->bairro?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Complemento</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="complemento" value="<?=$contatos_corporativos->complemento?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Cidade</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="cidade" value="<?=$contatos_corporativos->cidade?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">UF</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="uf" value="<?=$contatos_corporativos->uf?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Telefone</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="telefone" id="telefone" data-mask="(##)####-####" value="<?=$contatos_corporativos->telefone?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">CEP</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="cep" name="id" data-mask="#####-###" value="<?=$contatos_corporativos->cep?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">CNPJ</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="cnpj" name="id" data-mask="##.###.###/####-##"value="<?=$contatos_corporativos->cnpj?>">
        			</div>
        		</div>
    		</div>
    		<div class="form-group projetoatendimento">
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">T&iacute;tulo</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="titulo" value="<?=$contatos_corporativos->titulo?>">
        			</div>
        		</div>
        		<div class="form-group">
        			<label for="descricao" class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>
        			<div class="col-sm-6">
        				<input type="text" class="form-control" name="descricao" value="<?=$contatos_corporativos->descricao?>">
        			</div>
        		</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
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

<?php if($contatos_corporativos->tipo == "Atendimento a Clientes" || $contatos_corporativos->tipo == "Projetos Dedicados"){?>
	$(".projetoatendimento").show();
	$(".matrizfilial").hide();
<?php }elseif($contatos_corporativos->loja == "MATRIZ" || $contatos_corporativos->loja == "FILIAIS"){?>
	$(".matrizfilial").show();
	$(".projetoatendimento").hide();
<?php } ?>

PureMask.format("#telefone", true);
PureMask.format("#cep", true);  
PureMask.format("#cnp", true);
</script>