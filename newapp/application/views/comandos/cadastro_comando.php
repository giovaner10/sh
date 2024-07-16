<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/nexxera', 'layout.css') ?>">

<div class="text-title">
	<h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("cadastrar_comando") ?></h3>
	<h4 style="padding: 0 20px; margin-left: 15px;">
		<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
		<?= lang('cadastros') ?> >
		<?= lang('comandos') ?>
	</h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
	<div class="col-md-12" id="conteudo">
		<div class="card-conteudo" style='margin-bottom: 20px;'>
			<form id="formComando" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<div class="col-md-8 form-group input-container">
						<label>String do Comando: <span class="text-danger">*</span></label>
						<input type="text" name="comando" id="comando" class="form-control" placeholder="Digite a string do comando" required>
					</div>
					<div class="col-md-4 form-group input-container">
						<label>Serial: <span class="text-danger">*</span></label>
						<input type="text" name="serial" id="serial" class="form-control" placeholder="Digite o serial" required>
					</div>
					<div class="col-md-12 form-group input-container">
						<button type="submit" class="btn btn-success" id="salvar" title="Salvar comando" style="margin-top: 5px;">Salvar</button>
					</div>
				</div>
			</form>
			<div class="alert alert-success avisoSucesso" style="display: none;">
			</div>
			<div class="alert alert-info aviso" style="display: none;">
			</div>
		</div>
	</div>
</div>

<script>
	var Router = '<?= site_url('cadastros_comandos') ?>'

	$('#formComando').on('submit', function(e) {
		e.preventDefault();
		let serial = $('#serial').val();
		let comando = $('#comando').val();

		$.ajax({
			url: Router + '/enviarComando',
			data: {
				serial: serial,
				comando: comando
			},
			type: 'POST',
			dataType: 'JSON',
			beforeSend: function(){
				showLoadingSalvarButton();
			},
			success: function(data) {
				if (data.success == 'true') {
					$('.aviso').html(
						`<strong>Aviso!</strong> <a target="_blank" href="<?= site_url('comandos/envio') . '/' ?>${serial}">Clique Aqui,</a> para acessar o status do comando enviado.`
					).show();
					showAlert('success', data.message)

				} else {
					showAlert('error', data.message)
				}
			},
			error: function(error){
				showAlert('error', 'Erro na solicitação ao servidor.')
			},
			complete: function(){
				resetSalvarButton();
			}
		})
	})

	function showLoadingSalvarButton() {
		$('#salvar').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
	}

	function resetSalvarButton() {
		$('#salvar').html('Salvar').attr('disabled', false);
	}
</script>