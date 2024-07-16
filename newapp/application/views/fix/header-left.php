<!DOCTYPE html>
<html>
<head>
    <title><?php echo $titulo ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url('media') ?>/css/impressao.css" type="text/css" media="print">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link href="<?php echo base_url('media') ?>/css/calendario.css" rel="stylesheet">
    <link href="<?php echo base_url('media') ?>/css/moldura.css" rel="stylesheet">
    <link href="<?php echo base_url('media') ?>/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url('media') ?>/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url('media') ?>/css/checkbox.css" rel="stylesheet">
    <link href="<?php echo base_url('media') ?>/css/styleCompras.css" rel="stylesheet">

    <link href="<?php echo base_url('assets/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png') ?>">

    <link href="<?php echo base_url('assets') ?>/plugins/datepicker/css/datepicker.css" rel="stylesheet">

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png') ?>">
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jspdf.debug.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/tableExport.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('media') ?>/js/cycle.js"></script>
    <script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery-form.js"></script>
    <script type="text/javascript" src="<?php echo base_url('media') ?>/js/bootstrap-checkbox.min.js"></script>

    <!-- TRADUCAO DE IDIOMAS PARA ARQUIVOS JS -->
    <script type="text/javascript">
        var languageJSON = <?= json_encode($this->session->userdata('lang')) ?>;
        var lang = JSON.parse(languageJSON);
        var langDatatable = lang.datatable;
    </script>
    
    <style type="text/css">

    </style>

</head>

<div id="menu" class="navbar navbar-inverse navbar-fixed-left"><div class="navbar-header"></div>
    <div id="logo" class="img img-responsive">
        <img src="<?php echo base_url('media')?>/img/show-logo-bc.png">
    </div>
    <ul class="nav navbar-nav">

        <ul class="nav nav-list accordion">
            <li id="imagem" class="nav-header">
                <i id="user" class="fa fa-user-circle-o fa-5x"></i>
                <a class="link">John Lennon <i id="chevron" class="fa fa-chevron-down"></i></a>
                <ul id="demo" class="submenu">
                    <li><a href="">Configurações</a></li>
                    <li><a href="">Sair</a></li>
                </ul>
            </li><hr style="margin: 0"/>
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
                    <li><a href="<?php echo site_url('representantes/listar_representantes') ?>">Representates</a></li>
                    <li><a href="<?php echo site_url('cadastros_comandos') ?>">Comandos</a></li>
                </ul>
            </li>
            <li class="nav-header">
                <a class="link">Financeiro <i id="chevron" class="fa fa-chevron-down"></i></a>
                <ul id="demo" class="submenu">
                    <li><a href="<?php echo site_url('faturas') ?>">Lista</a></li>
                    <li><a href="<?php echo site_url('faturas/baixar') ?>">Baixa Retorno</a></li>
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

</div>
<script>
    $(function() {
        var accordionActive = false;

        $(window).on('resize', function() {
            var windowWidth = $(window).width();
            var $topMenu = $('#top-menu');
            var $sideMenu = $('#side-menu');

            if (windowWidth < 768) {
                if ($topMenu.hasClass("active")) {
                    $topMenu.removeClass("active");
                    $sideMenu.addClass("active");

                    var $ddl = $('#top-menu .movable.dropdown');
                    $ddl.detach();
                    $ddl.removeClass('dropdown');
                    $ddl.addClass('nav-header');

                    $ddl.find('.dropdown-toggle').removeClass('dropdown-toggle').addClass('link');
                    $ddl.find('.dropdown-menu').removeClass('dropdown-menu').addClass('submenu');

                    $ddl.prependTo($sideMenu.find('.accordion'));
                    $('#top-menu #qform').detach().removeClass('navbar-form').prependTo($sideMenu);

                    if (!accordionActive) {
                        var Accordion2 = function(el, multiple) {
                            this.el = el || {};
                            this.multiple = multiple || false;

                            // Variables privadas
                            var links = this.el.find('.movable .link');
                            // Evento
                            links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown);
                        }

                        Accordion2.prototype.dropdown = function(e) {
                            var $el = e.data.el;
                            $this = $(this),
                                $next = $this.next();

                            $next.slideToggle();
                            $this.parent().toggleClass('open');

                            if (!e.data.multiple) {
                                $el.find('.movable .submenu').not($next).slideUp().parent().removeClass('open');
                            };
                        }

                        var accordion = new Accordion2($('ul.accordion'), false);
                        accordionActive = true;
                    }
                }
            }
            else {
                if ($sideMenu.hasClass("active")) {
                    $sideMenu.removeClass('active');
                    $topMenu.addClass('active');

                    var $ddl = $('#side-menu .movable.nav-header');
                    $ddl.detach();
                    $ddl.removeClass('nav-header');
                    $ddl.addClass('dropdown');

                    $ddl.find('.link').removeClass('link').addClass('dropdown-toggle');
                    $ddl.find('.submenu').removeClass('submenu').addClass('dropdown-menu');

                    $('#side-menu #qform').detach().addClass('navbar-form').appendTo($topMenu.find('.nav'));
                    $ddl.appendTo($topMenu.find('.nav'));
                }
            }
        });

        /**/
        var $menulink = $('.side-menu-link'),
            $wrap = $('.wrap');

        $menulink.click(function() {
            $menulink.toggleClass('active');
            $wrap.toggleClass('active');
            return false;
        });

        /*Accordion*/
        var Accordion = function(el, multiple) {
            this.el = el || {};
            this.multiple = multiple || false;

            // Variables privadas
            var links = this.el.find('.link');
            // Evento
            links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown);
        }

        Accordion.prototype.dropdown = function(e) {
            var $el = e.data.el;
            $this = $(this),
                $next = $this.next();

            $next.slideToggle();
            $this.parent().toggleClass('open');

            if (!e.data.multiple) {
                $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
            };
        }

        var accordion = new Accordion($('ul.accordion'), false);


    });
</script>
