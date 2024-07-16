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
	<!-- Arquivo CSS da tela de Login -->
	<link rel="stylesheet" href="<?php echo base_url('media/css/new_login.css'); ?>">
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
				<?php echo form_open('', array('class' => 'form-signin')) ?>
				<?php if (isset($success)) : ?>
					<div class="alert alert-success"><?php echo $success ?></div>
				<?php endif; ?>
				<div class="form-group input-container">
					<label for="exampleInputEmail1" class="icon-label">
						<img class="label-icon" src="<?php echo base_url('media/img/new_icons/user.png'); ?>" alt="User Icon">
						<?php echo lang('usuario'); ?>
					</label>
					<img class="label-icon input-icon-left" src="<?php echo base_url('media/img/new_icons/mail.png'); ?>" alt="Mail Icon">
					<input type="text" name="login" class="form-control" placeholder="<?php echo lang('digemail') ?>" value="<?php echo $this->input->post('login') ?>" required>
				</div>
				<div class="form-group input-container">
					<label for="exampleInputPassword1" class="icon-label">
						<img class="label-icon" src="<?php echo base_url('media/img/new_icons/key.png'); ?>" alt="Key Icon">
						<?php echo lang('senha'); ?>
					</label>
					<img class="label-icon input-icon-left" src="<?php echo base_url('media/img/new_icons/security.png'); ?>" alt="Security Icon">
					<input type="password" name="senha" class="form-control" placeholder="<?php echo lang('digsenha') ?>" required>
				</div>
				<a href="recuperarSenha" style="display: block; margin-bottom: 20px; text-align: left;">Esqueci minha senha</a>
				<div style="text-align: center;">
					<button type="submit" class="btn btn-entrar" id="entrar"><?php echo lang('entrar') ?></button>
				</div>
				<?php echo form_close() ?>
			</div>
		</div>
		<div id="emailSenhaInvalido" class="email-alert">
			<img class="label-icon" src="<?php echo base_url('media/img/new_icons/alert.png'); ?>" alt="Alert Icon">
			<h2>Email ou senha inválidos!</h2>
		</div>
	</div>


	<div class="footer">
		<div class="footer-images">
			<img src="<?php echo base_url('media/img/new_icons/showtec.png'); ?>" alt="ShowTec Icon" class="footer-img">
			<br>
			<img src="<?php echo base_url('media/img/new_icons/ceabs.png'); ?>" aalt="CEABS Icon" class="footer-img">
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
	$(document).ready(function() {
		$('#emailSenhaInvalido').css('visibility', 'hidden');
		<?php if (isset($erro)) : ?>
			$('#emailSenhaInvalido').css('visibility', 'visible');
			setTimeout(function() {
				$('#emailSenhaInvalido').css('visibility', 'hidden');
			}, 10000);
		<?php endif; ?>
	});
</script>