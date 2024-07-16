<!DOCTYPE html>
<html>
    <head>

        <title><?php echo $titulo ?></title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="<?php echo base_url('media') ?>/css/impressao.css" type="text/css" media="print">

        <link href="<?php echo base_url('media') ?>/css/bootstrap.css"
              rel="stylesheet">
        <link href="<?php echo base_url('media') ?>/css/bootstrap-responsive.css"
              rel="stylesheet">
        <link href="<?php echo base_url('media') ?>/css/calendario.css"
              rel="stylesheet">
        <link href="<?php echo base_url('media') ?>/css/moldura.css"
              rel="stylesheet">
        <link href="<?php echo base_url('media') ?>/css/custom.css"
              rel="stylesheet">

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png') ?>">

        <link href="<?php echo base_url('assets') ?>/plugins/datepicker/css/datepicker.css" rel="stylesheet">

        <link rel="apple-touch-icon-precomposed" sizes="144x144"
              href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="apple-touch-icon-precomposed" sizes="114x114"
              href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="apple-touch-icon-precomposed" sizes="72x72"
              href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="apple-touch-icon-precomposed"
              href="<?php echo base_url('media/img/favicon.png') ?>">
        <link rel="shortcut icon"
              href="<?php echo base_url('media/img/favicon.png') ?>">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css" rel="stylesheet">
        <!-- ################## BIBLIOTECA COM ERROS NAS PÁGINAS
        <script type="text/javascript"
                src="<?php echo base_url('media') ?>/js/bootstrap-filestyle.min.js"> </script>
        -->
        <!--
        ############################# BIBLIOTECA COM PROBLEMAS NAS PÁGINAS - JÁ CARREGADO NO FOOTER
        <script type="text/javascript"
                src="<?php echo base_url('media') ?>/js/calendario.js"></script>
        -->

        <script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.js"></script>

        <script type="text/javascript"
        src="<?php echo base_url('media') ?>/js/cycle.js"></script>
        <script type="text/javascript"
        src="<?php echo base_url('media') ?>/js/jquery-form.js"></script>

        <!-- TRADUCAO DE IDIOMAS PARA ARQUIVOS JS -->
        <script type="text/javascript">
            var languageJSON = <?= json_encode($this->session->userdata('lang')) ?>;
            var lang = JSON.parse(languageJSON);
            var langDatatable = lang.datatable;
        </script>

        <style type="text/css">
            body {

            }

            .datepicker.dropdown-menu {
                z-index: 999999;
            }
        </style>

    </head>

    <body>

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse"
                       data-target=".nav-collapse"> <span class="icon-bar"></span> <span
                            class="icon-bar"></span> <span class="icon-bar"></span>
                    </a> <a class="brand" href="<?php echo site_url('Homes') ?>">Show
                        Tecnologia</a>
                    <div class="nav-collapse collapse">
                        <p class="navbar-text pull-right">
                            Logado como: <a href="#" class="navbar-link"><?php echo $this->auth->get_login('admin', 'email') ?>
                            </a> <a href="<?php echo site_url('acesso/sair/admin') ?>"
                                    class="btn btn-mini btn-danger" style="margin-left: 15px">Sair</a>
                        </p>
                        <ul class="nav">
                            <li class="active"><a href="/sistema"><i
                                        class="icon-chevron-left icon-white"></i> Versão Anterior</a></li>
                            <li
                                class="dropdown <?php echo $this->router->fetch_class() == 'cadastros' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastros
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('cadastros/clientes') ?>"> <i
                                                class="icon-briefcase"></i> Clientes
                                        </a>
                                    <li><a href="<?php echo site_url('cadastros/usuarios') ?>"> <i
                                                class="icon-user"></i> Usuários
                                        </a>
                                    </li>

                                    <li><a href="<?php echo site_url('cadastros/veiculos') ?>"> <i
                                                class="icon-user"></i> Veiculos
                                        </a>
                                    </li>

                                    <li><a href="<?php echo site_url('cadastros/linhas') ?>"> <i
                                                class="fa fa-mobile-phone"></i> Linhas
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li
                                class="dropdown <?php echo $this->router->fetch_class() == 'faturas' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Financeiro
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-submenu"><a href="javascript:;"> <i
                                                class="icon-barcode"></i> Faturas <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo site_url('faturas') ?>"> <i
                                                        class="icon-th-list"></i> Lista
                                                </a></li>
                                            <li><a href="<?php echo site_url('faturas/baixar') ?>"> <i
                                                        class="icon-download-alt"></i> Baixa Retorno
                                                </a></li>
                                                <li>
                                                <a href="<?php echo site_url('relatorio_faturamento') ?>">
                                                    <i class="fa fa-file-text-o"></i> <?=lang('relatorio_faturamento_receita_bruta')?>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li
                                class="dropdown <?php echo $this->router->fetch_class() == 'relatorios' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Relatórios
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-submenu"><a href="javascript:;"> <i
                                                class="icon-barcode"></i> Financeiro <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo site_url('relatorios/faturas') ?>"> <i
                                                        class="icon-barcode"></i> Faturas
                                                </a></li>
                                            <li><a href="<?php echo site_url('relatorios/faturas_enviadas') ?>"> <i
                                                        class="icon-inbox"></i> Envio de Faturas
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu"><a href="javascript:;"> <i
                                                class="fa fa-archive"></i> Cadastros <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo site_url('relatorios/contratos') ?>"> <i
                                                        class="fa fa-file-text-o"></i> Contratos
                                                </a></li>

                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown <?php echo $this->router->fetch_class() == 'cadastros' || $this->uri->segment(2) == 'desatualizados' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Suporte<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('servico') ?>"> <i
                                                class="icon-pencil"></i> Ordem de Serviços
                                        </a>

                                    </li>
                                    <li><a href="<?php echo site_url('webdesk') ?>"> <i
                                                class="icon-envelope"></i> Ticket
                                        </a>

                                    </li>
                                    <li><a href="<?php echo site_url('veiculos/desatualizados') ?>"> <i
                                                class="fa fa-truck"></i> Desatualizados
                                        </a>

                                    </li>
                                    <!-- <li class="dropdown-submenu"><a href="javascript:;"> <i
                                                    class="fa fa-code-fork"></i> Gateways
                                    </a>
                                            <ul class="dropdown-menu">
                                                    <li><a href="#"> <i
                                                                    class="fa fa-laptop"></i> Monitoramento
                                                    </a></li>
                                            </ul>

                                    </li> -->
                                </ul>
                            </li>

                            <!--
<li class="dropdown <?php echo $this->router->fetch_class() == 'relatorios' ? 'active' : '' ?>">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Relatórios <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?php echo site_url('relatorios/excesso_velocidade') ?>">Excesso de Velocidade</a></li>
<li><a href="<?php echo site_url('relatorios/jornada_trabalho') ?>">Jornada de Trabalho</a></li>
<li><a href="<?php echo site_url('relatorios/tempo_parado') ?>">Tempo Parado</a></li>
<li><a href="<?php echo site_url('relatorios/rota') ?>">Mapa Rota</a></li>
<li><a href="<?php echo site_url('relatorios/coordenadas') ?>">Coordenadas</a></li>
<li><a href="<?php echo site_url('relatorios/desempenho_operacional') ?>">Desempenho Operacional</a></li>
<li><a href="<?php echo site_url('relatorios/grafico_velocidade') ?>">Tacógrafo</a></li>
<li><a href="<?php echo site_url('relatorios/analitico') ?>">Analítico</a></li>
<li><a href="<?php echo site_url('relatorios/bde') ?>">BDE - Eletrônico</a></li>
              <li><a href="<?php echo site_url('relatorios/bdv') ?>">BDV - Eletrônico</a></li>
<li><a href="<?php echo site_url('relatorios/relatorio_viagem') ?>">Viagem</a></li>
<li><a href="<?php echo site_url('relatorios/ponto_motorista') ?>">Ponto Motorista</a></li>
</ul>
</li>
                            -->
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container-fluid" style="<?php echo $this->agent->is_mobile() == false ? 'padding-top: 50px' : '' ?>">
            <ul class="breadcrumb">
                <?php if ($this->router->fetch_class() != 'Homes'): ?>
                    <li><a href="<?php echo site_url('Homes') ?>">Home</a> <span
                            class="divider">/</span></li>
                    <li><a href="<?php echo site_url($this->router->fetch_class()) ?>"><?php echo ucfirst($this->router->fetch_class()) ?>
                        </a> <span class="divider">/</span></li>
                    <li class="active"><?php echo ucwords(str_replace('_', ' ', $this->router->fetch_method())) ?>
                    </li>
                <?php else: ?>
                    <li class="active">Home<span class="divider">/</span></li>
                    <li class="active">ShowNet</li>
                <?php endif; ?>
            </ul>
            <div class="span3 offset5" id="ajax">
                <img src="<?php echo base_url('media/img/ajax-loader.gif') ?>" />
            </div>
