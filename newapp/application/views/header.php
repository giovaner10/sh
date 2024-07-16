<!DOCTYPE html>
<html>
<head>
  
<title><?php echo $titulo?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo base_url('media')?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url('media')?>/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?php echo base_url('media')?>/css/calendario.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('media')?>/css/impressao.css" type="text/css" media="print">

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png') ?>">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png') ?>">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png') ?>">
  <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png') ?>">
  <link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png') ?>">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/cycle.js"></script>

<style type="text/css">
body{
}
</style>
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo site_url('index') ?>">Show Tecnologia</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logado como: <a href="#" class="navbar-link"><?php echo $this->auth->get_login('user')?></a>
              <a href="<?php echo site_url('login/sair')?>" class="btn btn-mini btn-danger" style="margin-left:15px">Sair</a>
            </p>
            <ul class="nav">
              <!-- <li class="<?php echo $this->router->fetch_class() == 'index' ? 'active' : ''?>"><a href="#">Home</a></li> -->
              <li class="dropdown <?php echo $this->router->fetch_class() == 'relatorios' ? 'active' : ''?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Relatórios <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('relatorios/excesso_velocidade')?>">Excesso de Velocidade</a></li>
                  <li><a href="<?php echo site_url('relatorios/jornada_trabalho')?>">Jornada de Trabalho</a></li>
                  <li><a href="<?php echo site_url('relatorios/tempo_parado')?>">Tempo Parado</a></li>
                  <li><a href="<?php echo site_url('relatorios/rota')?>">Mapa Rota</a></li>
                  <li><a href="<?php echo site_url('relatorios/coordenadas')?>">Coordenadas</a></li>
                  <li><a href="<?php echo site_url('relatorios/desempenho_operacional')?>">Desempenho Operacional</a></li>
                  <li><a href="<?php echo site_url('relatorios/grafico_velocidade')?>">Tacógrafo</a></li>
                  <li><a href="<?php echo site_url('relatorios/analitico')?>">Analítico</a></li>
                  <li><a href="<?php echo site_url('relatorios/bde')?>">BDE - Eletrônico</a></li>
				          <li><a href="<?php echo site_url('relatorios/bdv')?>">BDV - Eletrônico</a></li>
                  <li><a href="<?php echo site_url('relatorios/relatorio_viagem')?>">Viagem</a></li>
                  <li><a href="<?php echo site_url('relatorios/ponto_motorista')?>">Ponto Motorista</a></li>
                </ul>
              </li>
              <li class="dropdown <?php echo $this->router->fetch_class() == 'cadastros' ? 'active' : ''?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastros <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('cadastros/veiculos')?>">Veículos</a></li>
                  <li><a href="<?php echo site_url('motoristas/index')?>">Motoristas</a></li>
                  <li><a href="<?php echo site_url('cadastros/ponto_interesse')?>">Ponto Interesse</a></li>
                </ul>
              </li>
              <li class="dropdown <?php echo $this->router->fetch_class() == 'configuracoes' ? 'active' : ''?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Configurações<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('configuracoes/parametros_relatorio')?>">Parâmetros</a></li>
                </ul>
              </li>
              <?php if ($this->auth->get_login('id_user') == '335' || $this->auth->get_login('id_user') == '381' ): ?>
                <li class="dropdown <?php echo $this->router->fetch_class() == 'suporte' ? 'active' : ''?>">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Suporte<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="https://gestor.showtecnologia.com:85/mibew/operator/login.php" target="_blank">Atendimento Online</a></li>
                    <li><a href="<?php echo site_url('comandos_maxtrack/lista_comandos') ?>" >Comandos</a></li>
                    <li class="dropdown-submenu"><a href="<?php echo site_url('comandos_maxtrack/lista_comandos') ?>" >Atividade dos Usuários</a>
                      <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url('veiculos/log_veiculos') ?>">Veículos</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
              <?php endif ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
  <div class="container-fluid" style="<?php echo $this->agent->is_mobile() == false ? 'padding-top: 50px' : ''?>">
    <ul class="breadcrumb">
      <li><a href="<?php echo site_url('index')?>">Home</a> <span class="divider">/</span></li>
      <li><a href="<?php echo site_url($this->router->fetch_class())?>"><?php echo ucfirst($this->router->fetch_class())?></a> <span class="divider">/</span></li>
      <li class="active"><?php echo ucwords(str_replace('_', ' ', $this->router->fetch_method()))?></li>
    </ul>
    <div class="span3 offset5" id="ajax"><img src="<?php echo base_url('media/img/ajax-loader.gif')?>" /></div>
    <br style="clear:both" />
    
    