<?php if($msg != ''):?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>CONCLUIDO!</strong>
	<?php echo $msg?>
</div>
<?php endif;?>
<h3>Baixar Contas </h3>
<hr>
<div class="well well-small">
<?php echo form_open_multipart('contas/enviar_retorno', array('id' => 'form-retorno'), array('path' =>'uploads/retorno'))?>
	<div class="span8">		
		<button id="bt_enviar" class="btn btn-primary upload-file">
			<i class="icon-hdd icon-white"></i> Enviar Retorno
		</button>
		<input type="file" name="arquivo" id="upload" style="display: none" />
		<a href="<?php echo site_url('contas/baixa_pendente')?>" class="btn btn-danger"><i class="icon-exclamation-sign icon-white"></i> Faturas Pendentes</a>
	</div>
	<?php echo form_close()?>
	<div class="clearfix"></div>
</div>
<div class="result_upload"></div>
<div id="bar" class="progress progress-striped active">
	<div class="bar upload"></div>
</div>
<div id="conteudo"></div>
<!-- modals -->
<div id="impressao_contrato" class="modal hide fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Impressão de Faturas por Contrato</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>
<div id="baixa_automatica" class="modal hide fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true"
	style="width: 700px !important">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Baixa Automática</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#upload:hidden').on('change', function() {
		var arquivo = $('#upload:hidden');
		var ext = arquivo.val().split('.').pop().toLowerCase();
		if(arquivo.val() == '') {
			alert('Escolha um arquivo para enviar.');
		} else {
			var botao = $('#bt_enviar');
			var bt_status = true;
			var retorno;
			$('#form-retorno').ajaxSubmit({
				target: '#result_upload',
				resetForm: true,
				uploadProgress: function(event, position, total, percentComplete) {
					$('.upload').css('width', (percentComplete - 10)+'%');
					$('.result_upload').html('Enviando Arquivo. Por favor aguarde...');
					if (bt_status) {
						botao.attr('disabled','disabled');
						bt_status = false;
					}
				},
				success: function() {
					$('.result_upload').html('Processando arquivo, por favor aguarde...');
				},
				complete: function(xhr) {
					retorno = xhr.responseText;
					console.log(xhr);
					$('.upload').css('width', 100+'%');
					if (!bt_status) {
						botao.removeAttr('disabled');
						bt_status = true;
					}
					if (retorno == '1') {
						$('.result_upload').html('Processando arquivo, por favor aguarde...');
						url = '<?php echo site_url('contas/ajax_baixa_retorno')?>';
						$('#conteudo').load(url, '',
											function() {
												$('.result_upload').html('');
												$('.upload').css('width', 0+'%');
											});
					} else {
						$('.result_upload').html(retorno);
					}
				}
			});
		}
	}); //fim submit form
	return false;
});

function valida_arquivo() {	
	var ext = $('#arquivo').val().split('.').pop().toLowerCase();
	arquivo = $('input[name=arquivo]');
	if(arquivo.val() == '') {
		alert('Escolha um arquivo!');
		return false;
	}
	if($.inArray(ext, ['zip']) == -1) {
		alert('invalid extension!');
		return false;
	}
}
</script>
