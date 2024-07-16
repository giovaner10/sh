<!DOCTYPE html>

<head>
	<title><?php echo $titulo ?></title>
	<meta name="google-play-app" content="app-id=com.show.diego.gestormovel">
	<meta name="apple-itunes-app" content="app-id=1093132531">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png') ?>">

	<!-- CSS Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- Font Awesome para ícones -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<!-- CSS da página -->
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;700;800&display=swap" rel="stylesheet">
	<!-- Arquivo CSS da tela de refedinição de senha -->
	<link rel="stylesheet" href="<?php echo base_url('media/css/new_redefinirSenha.css'); ?>">
</head>


<body>
	<div class="main-container">
		<div class="login-icon-container">
			<img src="<?php echo base_url('media/img/new_icons/shownet.png'); ?>" alt="ShowNet Icon" class="login-icon">
		</div>
		<div class="login-card">
			<div class="login-body">
				<div class="title-container">
					<h2>ShowNet</h2>
					<p>Acesso Restrito</p>
				</div>
				<form id="formSenha" method="POST">
					<div class="form-group input-container">
						<?php
							if (isset($token)){
								echo "<input name='token' id='token' type='text' hidden value='$token'>";
							} else{
							}
						?>
						<label for="password" class="icon-label">
							<img class="label-icon" src="<?php echo base_url('media/img/new_icons/key.png'); ?>" alt="Key Icon">
							<?php echo lang('senha');?>
						</label>
						<img class="label-icon input-icon-left" src="<?php echo base_url('media/img/new_icons/security.png'); ?>" alt="Security Icon">
						<input type="password" name="senha" id="senha" class="form-control" placeholder="<?php echo lang('digsenha')?>" required>
					</div>
					<div class="form-group input-container">
						<label for="password_confirmation" class="icon-label">
							<img class="label-icon" src="<?php echo base_url('media/img/new_icons/key.png'); ?>" alt="Key Icon">
							<?php echo lang('csenha');?>
						</label>
						<img class="label-icon input-icon-left" src="<?php echo base_url('media/img/new_icons/security.png'); ?>" alt="Security Icon">
						<input type="password" name="confirmacao_senha" id="confirmacao_senha" class="form-control" placeholder="<?php echo lang('csenha')?>" required>
					</div>
					<br>
					<div style="text-align: center;">
						<button class="btn btn-redefinir" type="submit" id="redefinir">Redefinir</button>
					</div>
				</form>
			</div>
		</div>
		<div id="validacaoRecuperacao" style="visibility: hidden;">
			<img class="label-icon" src="<?php echo base_url('media/img/new_icons/alert.png'); ?>" alt="Alert Icon">
			<h2 id="msgValidacao"></h2>
		</div>
	</div>

	<div class="footer">
		<div class="footer-images">
			<img src="<?php echo base_url('media/img/new_icons/showtec.png'); ?>" alt="ShowTec Icon" class="footer-img">
			<br>
			<img src="<?php echo base_url('media/img/new_icons/ceabs.png'); ?>" alt="CEABS Icon" class="footer-img">
			<br>
			<img src="<?php echo base_url('media/img/new_icons/omnilink.png'); ?>" alt="Omnilink Icon" class="footer-img">
		</div>
		<p>&copy; Copyright <?php echo date('Y')?></p>
		<p>Política de Privacidade e Proteção de Dados.</p>
		<p>Todos os direitos reservados.</p>
	</div>
</body>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


<script>
	$("#formSenha").submit(event => {
		event.preventDefault();
		if($('#senha').val() === $('#confirmacao_senha').val()){
			$('#redefinir').attr('disabled', true).html('Redefinindo...');
			$.ajax({
				url: '<?= site_url('acesso/redefinirSenha')?>',
				method: "POST",
				dataType: 'json',
				data: {
					"token" : $('#token').val(),
					"senha" : $('#senha').val()
				},
				success: function(response){
					if (response.resultado === "success"){
						document.getElementById('validacaoRecuperacao').style.visibility = 'visible';
						$('#validacaoRecuperacao').addClass('success').delay(1000).queue(function(next){
							$(this).removeClass('success');
							next();
						});

						$('#msgValidacao').text("Senha alterada com sucesso!");
						setTimeout(function() {
							window.location.href = '<?= site_url('acesso/entrar')?>'
						}, 3000);
						
					} else {
						document.getElementById('validacaoRecuperacao').style.visibility = 'visible';
						$('#validacaoRecuperacao').addClass('animate').delay(1000).queue(function(next){
							$(this).removeClass('animate');
							next();
						});
						$('#msgValidacao').text("Não foi possível alterar sua senha! Tente novamente.");
					}
				},
				error: function(response){
					document.getElementById('validacaoRecuperacao').style.visibility = 'visible';
					$('#validacaoRecuperacao').addClass('animate').delay(1000).queue(function(next){
						$(this).removeClass('animate');
						next();
					});
					$('#msgValidacao').text("Não foi possível alterar sua senha! Tente novamente");
					$('#redefinir').attr('disabled', false).html('Redefinir');
				},
				complete: function(){
					$('#redefinir').attr('disabled', false).html('Redefinir');
				}
			});
		}else {
			document.getElementById('validacaoRecuperacao').style.visibility = 'visible';
			$('#validacaoRecuperacao').addClass('animate').delay(1000).queue(function(next){
				$(this).removeClass('animate');
				next();
			});
			$('#msgValidacao').text("As senhas devem ser idênticas!");
		}
   	});
</script>