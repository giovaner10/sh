<?php if ($this->auth->is_allowed_block('cad_aniversariantes')){ ?>
<h3>Configurações de conexão com servidor de e-mail</h3>
<hr>
<div class="containner">
	<div class="row-fluid">
		<div class="panel">
			<div class="row">
    			<div class="col-md-6">
                    <?php echo $this->session->flashdata('sucesso');?>
                    <?php echo $this->session->flashdata('error');?>
                </div>
        	</div>
			<form name="f-frete" id="f-frete" class="form-horizontal" method="post" onSubmit="return valida()" action="<?php echo site_url('cadastros/smtp_atualizar/')?>">
				<div class="box-body">
    				<div class="form-group">
    					<label class="col-sm-2 control-label">Servidor SMTP: *</label>
    					<div class="col-sm-3">
    						<input class="form-control" name="smtp_host" id="smtp_host" type="text" value="<?=$config_stmp->smtp_host?>" placeholder="Ex: smtp.site.com.br" required> 
    					</div>
    					<label class="col-sm-1 control-label">Porta: *</label>
    					<div class="col-sm-3">
    						<input class="form-control" name="smtp_port" id="smtp_port" type="text" title="Porta SMTP" value="<?=$config_stmp->smtp_port?>" required>
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-2 control-label">E-mail: *</label>
    					<div class="col-sm-3">
    						<input class="form-control" name="smtp_username" id="smtp_username" type="text" value="<?=$config_stmp->smtp_username?>" placeholder="Conta válida de e-mail. Ex: contato@site.com.br" required> 
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-2 control-label">Senha:</label>
    					<div class="col-sm-3">
    						<input class="form-control" name="smtp_password" id="smtp_password" type="password" title="Preencha apenas se desejar alterar" value="<?=$config_stmp->smtp_password?>" placeholder="Preencha apenas se desejar alterar" />
    					</div>
    				</div>    				
    				<div class="form-group">
    					<label class="col-sm-2 control-label">Nome de Exibição: *</label>
    					<div class="col-sm-3">
    						<input class="form-control" name="smtp_fromname" id="smtp_fromname" type="text" value="<?=$config_stmp->smtp_fromname?>" placeholder="Ex: e-Shop Presentes" required>
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-2 control-label">Cópia Oculta para:</label>
    					<div class="col-sm-3">
    						<input class="form-control" name="smtp_bcc" id="smtp_bcc" type="text" value="<?=$config_stmp->smtp_bcc?>" placeholder="Informe o e-mail onde deseja receber cópia" >
    					</div>
    				</div>
    				<br />
    				<button type="submit" class="btn-success btn" id="btn-add">
    					<i class="fa fa-edit icon-white"></i> Atualizar Parâmetros de E-mail
    				</button>
    				<button type="button" class="btn-info btn" id="btn-test">
    					<i class="fa fa-cog icon-white"></i> Testar Configurações
    				</button>
    				<a href="listar_aniversariantes" class="btn btn-primary"> Voltar</a>
    				<br />
    				<div id="result"></div>
    			</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
    $('#btn-test').click(function(e){      
        
 		e.preventDefault();

 		$('#btn-test').html('Processando...');
 		$('#btn-test').prop('disabled', true);

        $('#result').html('');
        $.ajax({  
            url: "<?php echo site_url("cadastros/smtp_test");?>",
            method:"POST",  
            data: $('#f-frete').serialize(),             
            success:function(data)  
            {  
            	if(data == 0)
                {
            		$('#btn-test').removeClass('btn-danger').addClass('btn-success btn');
                    $('#btn-test').html('<i class="fa fa-cog icon-white"></i> Enviado com sucesso!');
                    $('#btn-test').prop('disabled', false);
                }
                else{
                    $('#btn-test').removeClass('btn-success').addClass('btn-danger');
                    $('#btn-test').html('<i class="fa fa-cog icon-white"></i> Tentar novamente');
                    $('#result').append('<h5 class="alert alert-danger">'+data+'</h5>');
                    $('#btn-test').prop('disabled', false);
                }
            } 
       });  
    })
</script>
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>