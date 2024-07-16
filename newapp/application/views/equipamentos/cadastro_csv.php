<h3>Cadastrar Equipamentos</h3>

<span class="label label-info"> O arquivo deve seguir o seguinte formato:</span>
<a target="_blank" href="https://gestor.showtecnologia.com:85/sistema/newapp/uploads/exemplo.csv"> Exemplo </a>
<div class="alert hide">
	<span></span>
</div>
<hr>
<form action="<?php echo site_url('cadastros_comandos/salvar_csv') ?>" method="post" enctype="multipart/form-data" style="margin-bottom: 0">
	<div class="row-fluid">
		<div class="span12">
			<div class="progress progress-info progress-striped hide">
				<div class="bar"></div>
			</div>			
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<input required type="file" name="arquivos" class="input-files hide">
			<button type="button" class="btn btn-info selecionar-arquivos"><i class="icon-folder-open icon-white"></i> Selecionar Arquivo</button>
			<span class="nome-arquivo"></span>
		</div>
		<div class="span6">
			<div class="pull-right">
				Tipos de Arquivos: 
				<span class="label label-warning ">.csv</span>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="form-actions">
			<button type="submit" class="btn btn-primary" title="Salvar arquivo">Salvar</button>
			<button type="reset" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
		</div>
	</div>
</form>
<div class="tabela"></div>
<script>
	$(function() {
		var fileInput = $('.input-files');
		var nomeArquivo = $('.nome-arquivo');
		var progress = $('.progress');
		var bar = $('.bar');
		var alerta = $('.alert');

		$('.btn-cancelar').click(function(){
			$(fileInput).val();
			$(nomeArquivo).text('');
		});
		$('.selecionar-arquivos').click(function(){
			$(fileInput).click();
		});
		$(fileInput).change(function(){
			$(nomeArquivo).text($(this).val());
		});
		$('form').ajaxForm({
		    beforeSend: function() {
		        $(progress).fadeIn(300);
		        $(bar).width('0%');
		        $(alerta).removeClass('show').addClass('hide');
		    },
		    uploadProgress: function(event, position, total, percentComplete) {
		        $(bar).width(percentComplete + '%');
		    },
		    success: function() {
		    	$(progress).removeClass('progress-info').addClass('progress-success').delay(1000).fadeOut(300);
		        $(bar).width('100%');
		        $(nomeArquivo).text('');
		        $(fileInput).val('');
		    },
			complete: function(xhr) {
				var retorno = JSON.parse(xhr.responseText);
				//var mensagem = retorno.msg;
				console.log(retorno);
				$(alerta).addClass('show');
				$(alerta).find('span').html(retorno.msg);
				if(!retorno.success) {
					$(alerta).addClass('alert-error');
					$(alerta).removeClass('alert-success');
				} else {
					$(alerta).removeClass('alert-error');
					$(alerta).addClass('alert-success');
				}
			}
		});
	});
</script>