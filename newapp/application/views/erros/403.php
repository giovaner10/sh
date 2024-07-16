<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Acesso Negado", site_url('Homes'), "Sem permissão", "Código  403");
?>
<div class="forbidden-page">
	<div id="error-container">
		<img class="img-403" src="<?= base_url('assets/images/erro-403.svg') ?>" />
		<h1 id="error-code">Acesso Negado, usuário sem permissão.</h1>
		<p id="error-message">O acesso à página foi negado. <br> Para obter mais informações, entre em contato com o administrador.</p>
		<a class="go-back-btn" href="<?php echo site_url('homes') ?>">Voltar</a>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/errors', '403.css') ?>">
