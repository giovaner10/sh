<!DOCTYPE html>
<html>
<head>
	<title><?php echo $titulo ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="<?php echo base_url('newAssets') ?>/css/styleChips.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/animatecss/3.5.2/animate.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/chartist.js/0.10.1/chartist.min.css" rel="stylesheet">
	<link href="<?php echo base_url('newAssets/css/chartist-tooltip.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('newAssets') ?>/css/jquery.dynatable.css" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">

	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png') ?>">

	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script defer src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/chartist.js/0.10.1/chartist.min.js"></script>

	<script defer src="<?php echo base_url('newAssets/js/chartist-tooltip.js') ?>"></script>
	<script defer src="<?php echo base_url('newAssets/js/chartist.fill.donut.js') ?>"></script>
	<script defer src="<?php echo base_url('newAssets/js/loadPages.js') ?>"></script>
	<script defer src="<?php echo base_url('newAssets/js/jquery.dynatable.js') ?>"></script>

	<!-- TRADUCAO DE IDIOMAS PARA ARQUIVOS JS -->
    <script type="text/javascript">
        var languageJSON = <?= json_encode($this->session->userdata('lang')) ?>;
        var lang = JSON.parse(languageJSON);
        var langDatatable = lang.datatable;
    </script>

</head>
<body>

<nav id="nav-horizintal" class="navbar navbar-default">
	<div id="date" class="pull-left"><h1 id="hora" onload="moveRelogio()"> </h1></div>
	<div id="time" class="pull-left"><p id="data" class="text-muted"></p></div>
	<div id="user" class="pull-right">
		<span><img src="<?php echo base_url('newAssets') ?>/imagens/andre"/></span>
	</div>
	<div id="userName" class="pull-right">
		<li class="dropdown">
			<h5><strong><?php echo $obj['name'] ?></strong></h5>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><p><h6><?php echo $obj['email'] ?><span><i class="fa fa-chevron-down"></i></span></h6></p> </a>
			<!-- <ul class="dropdown-menu" role="menu">
				<li><a href="#">Action</a></li>
				<li><a href="#">Another action</a></li>
				<li><a href="#">Something else here</a></li>
			</ul> -->
		</li>

	</div>
</nav>
<div id="menu" class="navbar navbar-inverse navbar-fixed-left"><div class="navbar-header"></div>
	<div id="logo" class="img img-responsive">
		<img src="<?php echo base_url('newAssets') ?>/imagens/show-logo-bc.png">
	</div>
	<ul class="nav navbar-nav">

		<ul id="listaMenu" class="nav nav-list accordion">
			<li class="nav-header">
				<a class="link">Cadastros <i id="chevron" class="fa fa-chevron-down"></i></a>
				<ul id="demo" class="submenu">
					<li><a href="<?php echo site_url('cadastros/clientes') ?>">Clientes</a></li>
					<li><a href="<?php echo site_url('documentacoes/./') ?>">Documentações</a></li>
					<li><a href="<?php echo site_url('cadastros/usuarios') ?>">Usuários</a></li>
					<li><a href="<?php echo site_url('cadastros/veiculos') ?>">Veículos</a></li>
					<li><a href="<?php echo site_url('equipamentos/listar') ?>">Equipamentos</a></li>
					<li><a href="<?php echo site_url('cadastros/linhas') ?>">Linhas</a></li>
					<li><a href="<?php echo site_url('contratos_eptc/listar_contratos') ?>">Contratos EPTC</a></li>
					<li><a href="<?php echo site_url('agendamento') ?>">Serviços Agendados</a></li>
					<li><a href="<?php echo site_url('instaladores/listar_instaladores') ?>">Instaladores</a></li>
					<li><a href="<?php echo site_url('gerencia_equipamentos') ?>">Logística de equipamentos</a></li>
					<li><a href="<?php echo site_url('representantes/listar_representantes') ?>">Representates</a></li>
					<li><a href="<?php echo site_url('cadastros_comandos') ?>">Comandos</a></li>
				</ul>
			</li>
			<li class="nav-header">
				<a class="link">Financeiro <i id="chevron" class="fa fa-chevron-down"></i></a>
				<ul id="demo" class="submenu">
					<li><a href="<?php echo site_url('faturas') ?>">Lista</a></li>
					<li><a href="<?php echo site_url('faturas/baixar') ?>">Baixa Retorno</a></li>
					<li><a href="<?php echo site_url('relatorio_faturamento') ?>"><?=lang('relatorio_faturamento_receita_bruta')?></a></li>
					<li><a href="#faturaConfig">Config. Boleto</a></li>

				</ul>
			</li>
			<li class="nav-header">
				<a class="link">Relatórios <i id="chevron" class="fa fa-chevron-down"></i></a>
				<ul id="demo" class="submenu">
					<li><a href="<?php echo site_url('relatorios/faturas') ?>">Faturas</a></li>
					<li><a href="<?php echo site_url('relatorios/faturas_enviadas') ?>">Envio de Faturas</a></li>
					<li><a href="<?php echo site_url('relatorios/contas') ?>">Contas a Pagar</a></li>
					<li><a href="<?php echo site_url('relatorios/contratos') ?>">Contratos</a></li>
					<li><a href="<?php echo site_url('relatorios/tempo_logado') ?>">Tempo Logado</a></li>
					<li><a href="<?php echo site_url('relatorios/sms') ?>">Envio de SMS</a></li>

				</ul>
			</li>
			<li class="nav-header">
				<a class="link">Suporte <i id="chevron" class="fa fa-chevron-down"></i></a>
				<ul id="demo" class="submenu">
					<li><a href="<?php echo site_url('servico') ?>">Ordem de Serviço</a></li>
					<li><a href="<?php echo site_url('webdesk') ?>">Ticket</a></li>
					<li><a href="<?php echo site_url('veiculos/desatualizados') ?>">Desatualizados</a></li>
					<li><a href="<?php echo site_url('veiculos/log_veiculos') ?>">Cadastro de Veículos</a></li>
				</ul>
			</li>
			<li class="nav-header">
				<a class="link">Configurações <i id="chevron" class="fa fa-chevron-down"></i></a>
				<ul id="demo" class="submenu">
					<li><a href="<?php echo site_url('configuracoes/notificacoes/sms') ?>">SMS</a></li>
				</ul>
			</li>
			<li class="nav-header">
				<a class="link">Monitoramento <i id="chevron" class="fa fa-chevron-down"></i></a>
				<ul id="demo" class="submenu">
					<li><a href="<?php echo site_url('monitor/equipamento_violado') ?>">Equipamento Violado</a></li>
					<li><a href="https://gestor.showtecnologia.com/gtw/gateway/" target="_blank">Gateway</a></li>
				</ul>
			</li>
		</ul>
	</ul>
</div>
