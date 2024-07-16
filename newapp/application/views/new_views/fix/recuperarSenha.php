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
    <!-- Arquivo CSS da tela de recuperar login-->
    <link rel="stylesheet" href="<?php echo base_url('media/css/new_recuperarSenha.css'); ?>">
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
                <br>
                <div class="email-container">
                    <h2>Enviaremos para seu e-mail cadastrado.</h2>
                    <div class="form-group input-container">
                        <img class="label-icon" src="<?php echo base_url('media/img/new_icons/mail.png'); ?>" alt="Mail Icon">
                        <input type="text" id="login" name="login" class="form-control" placeholder="<?php echo lang('digemail') ?>" value="" required>
                    </div>
                </div>
                <div style="text-align: center;">
                    <button class="btn btn-entrar" id="entrar" type="button">Enviar</button>
                </div>
                <br>
                <div id="emailEnvio" class="email-alert-enviado">
                    <img class="label-icon" src="<?php echo base_url('media/img/new_icons/alert.png'); ?>" alt="Alert Icon">
                    <h2>E-mail enviado! Verifique seu e-mail.</h2>
                </div>
                <br>
                <a href="<?= site_url('Homes') ?>" style="display: flex; align-items: center; margin-bottom: 20px;">
                    <img class="label-icon" src="<?php echo base_url('media/img/new_icons/arrow back.png'); ?>" alt="Back Icon">
                    <h2 style="color: #1C69AD; font-weight: bold; margin: 0; font-size: 13px;">Voltar</h2>
                </a>
            </div>
        </div>

        <div id="emailRecuperarError" class="email-alert">
            <img class="label-icon" src="<?php echo base_url('media/img/new_icons/alert.png'); ?>" alt="Alert Icon">
            <h2>E-mail inválido</h2>
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
        <p>&copy; Copyright <?php echo date('Y') ?></p>
        <p>Política de Privacidade e Proteção de Dados.</p>
        <p>Todos os direitos reservados.</p>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#emailEnvio').css('visibility', 'hidden');
        $('#emailRecuperarError').css('visibility', 'hidden');

        $('#entrar').click(event => {
            let email = $('#login').val();
            if (!email) {
                $('#emailRecuperarError').css('visibility', 'visible');
                return;
            }

            $.ajax({
                url: "<?= site_url('acesso/recuperarSenha') ?>",
                method: "POST",
                dataType: 'json',
                data: {
                    "email": $('#login').val()
                },
                success: function(response) {
                    if (response.success === "true") {
                        $('#emailRecuperarError').css('visibility', 'hidden');
                        $('#emailEnvio').css('visibility', 'visible');
                        alert('Você receberá um e-mail para redefinição de senha, caso seu cadastro esteja ativo.')

                        setTimeout(function() {
                            window.location = '<?= site_url('Homes') ?>'
                        }, 1000);
                    } else if (response.status == 400) {
                        alert(response['message']);

                    } else if (response.status == 404) {
                        alert(response['message']);

                    } else if (response.status == 500) {
                        alert('Erro ao processar solicitação. Contate o administrador do sistema!');
                    }
                },
                error: function(response) {
                    $('#emailRecuperarError').css('visibility', 'visible');
                    setTimeout(function() {
                        $('#emailRecuperarError').css('visibility', 'hidden');
                    }, 10000);
                }
            });
        });
    })
</script>