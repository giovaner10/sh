<!DOCTYPE html>
<head>
	<title><?php echo $titulo?></title>
	<meta name="google-play-app" content="app-id=com.show.diego.gestormovel">

	<meta name="apple-itunes-app" content="app-id=1093132531">

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png') ?>">
	<link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png') ?>">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>


	<style type="text/css">
	#r{
		border-radius: 60px;
	}

</style>
</head>

<style type="text/css">

.login,
.recuperar {
	width: 400px;
}

.recuperar {
	display: block;
}

</style>
<body style="background-image: url('<?php echo base_url('media/img/showadm.jpg');?>');"></br>
	<div class="container">
		<div class="row" >
			<div class="col-md-3">


			</div>

			<div class="col-md-6">
				<pre></pre>
				<div class="card">
					<div class="card-body" style="background-color: #0079bf; ">
						<div align="center">
							<img src="<?php echo base_url('media/img/logo-topo-login.png');?>" alt="">
							<div align="right" style="color: #fff;">
								<span>
									SHOWNET<br>
									<small>Acesso Restrito</small></div>
								</span>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<?php if(isset($erro)):?>
								<div class="alert alert-danger"><?php echo $erro?></div>
							<?php endif;?>
							<?php if(isset($success)):?>
								<div class="alert alert-success"><?php echo $success?></div>
							<?php endif;?>
							<div>
								<div class="form-group">
									<label for="exampleInputEmail1"><?php echo lang('email');?></label>

									<input type="text" id="login" class="form-control" placeholder="<?php echo lang('digemail')?>" value="<?php echo $this->input->post('login') ?>" required>
									<small id="emailHelp" class="form-text text-muted"></small>
								</div>
								<div align="center">
									<button class="btn btn-large btn-primary" style="width: 100%" type="button" id="entrar">Enviar</button>
								</div>
								<br>
						    </div>
					    </div>
				    </div>
			    </div>
		    </div>
        </div>
    </div>

    <script>
        $('#entrar').click(event => {
			$.ajax({
				url: "<?= site_url('acesso/recuperarSenha')?>",
				method: "POST",
				dataType: 'json',
				data: {
					"email" : $('#login').val() 
				},
				success: function(response){
					alert("Você receberá um e-mail para redefinição de senha, caso seu cadastro esteja ativo.")
					window.location = '<?= site_url('Homes') ?>'
				}
			});

             
        })
    </script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	</body>
