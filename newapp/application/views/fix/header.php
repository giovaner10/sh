<!DOCTYPE html>
<html>
<head>
    <title><?php echo $titulo;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('media');?>/css/impressao.css" type="text/css" media="print">
    <link href="<?php echo base_url('media');?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url('media');?>/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url('media');?>/css/calendario.css" rel="stylesheet">
    <link href="<?php echo base_url('media');?>/css/moldura.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=versionFile('media/css', 'custom.css') ?>">
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap.css');?>" rel="stylesheet">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png');?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png');?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png');?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png');?>">
    <link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png');?>">
    <link href="<?php echo base_url('assets') ?>/plugins/datepicker/css/datepicker.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js');?>" ></script> <!-- VERSÃO QUE FUNCIONA AS PERMISSÕES CORRETAMENTE -->
    <!-- <script type="text/javascript" src="<?php echo base_url('media');?>/js/jquery.js"></script> -->
    <!--<script type="text/javascript" src="<?php echo base_url('assets/js/jspdf.debug.js');?>"></script>-->
    <!--<script type="text/javascript" src="<?php echo base_url('assets/js/tableExport.js');?>"></script> -->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('media');?>/js/cycle.js"></script>
    <script type="text/javascript" src="<?php echo base_url('media');?>/js/jquery-form.js"></script>
    <link href="<?php echo base_url('media') ?>/css/jquery.tree-multiselect.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.tree-multiselect.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.2.3/jspdf.plugin.autotable.js"></script>
   
    <!-- TRADUCAO DE IDIOMAS PARA ARQUIVOS JS -->
    <script type="text/javascript">
        var languageJSON = <?= json_encode($this->session->userdata('lang')) ?>;
        var lang = JSON.parse(languageJSON);
        var langDatatable = lang.datatable;
    </script>

    <style type="text/css">
        .datepicker.dropdown-menu {
            z-index: 999999;
        }
        body{
            font-size: 12px;
        }
        #notify_account {
            margin: 10px;
        }
        .notify-drop {
            max-height: 350px !important;
            overflow: auto;
        }
    </style>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="<?php echo site_url('home');?>">
                Show Tecnologia
            </a>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
            <div class="nav-collapse collapse">
                <?php if ($this->auth->get_login('admin', 'email')) : ?>
                    <ul class="nav pull-right">
                        <!-- LISTA DE NOTIFICAÇÔES PENDENTES -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i id="sino_alerta" class="fa fa-bell"></i> <span class="badge" id="qtd_badge">0</span></a>
                            <ul class="dropdown-menu notify-drop">
                                <!-- notify content -->
                                <div id="notify_account" class="drop-content">
                                    <li>
                                        <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                            <center>Vazio</center>
                                        </div>
                                    </li>
                                </div>
                            </ul>
                        </li>
                        <!-- END LISTA -->

                        <li class="dropdown">
                            <a href="#" class="navbar-link" data-toggle="dropdown">
                                <?= explode(" ", $this->auth->get_login('admin', 'nome'))[0] ?>
                                <b class="caret"></b>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('acesso/sair/admin') ?>">
                                        <i class="fa fa-sign-out"> Sair</i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <p class="navbar-text pull-right">
                    <?php elseif ($this->auth->get_login('representante', 'email')) : ?>
                        <a href="#" class="navbar-link"><?php echo $this->auth->get_login('representante', 'email') ?>
                        </a> <a href="<?php echo site_url('representantes/sair') ?>"
                                class="btn btn-mini btn-danger" style="margin-left: 15px; margin-top: -3px;">Sair</a>
                    <?php elseif ($this->auth->get_login('instalador', 'email')) : ?>
                        <a href="#" class="navbar-link"><?php echo $this->auth->get_login('instalador', 'email') ?>
                        </a> <a href="<?php echo site_url('instaladores/sair') ?>"
                                class="btn btn-mini btn-danger" style="margin-left: 15px; margin-top: -3px;">Sair</a>
                    <?php endif; ?>
                </p>
                <?php if ($this->auth->get_login('admin', 'email')) : ?>
                    <ul class="nav">
                    	<li class="dropdown <?php echo $this->router->fetch_class() == 'ashownet' ? 'active' : '' ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">A Shownet <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
                                <?php if ($this->auth->is_allowed_block('vis_sobreaempresa')) : ?>
                                    <li>
                                        <a href="<?= site_url('Empresas/Sobre');?>">
                                            <i class="fa fa-briefcase"></i> <?=lang('sobre')?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?php echo site_url('ashownet/apresentacoes'); ?>">
                                        <i class="fa fa-file-image-o"></i> Apresentações
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= site_url('Empresas/ContatosCorporativos');?>">
                                        <i class="fa fa-address-book"></i> <?=lang('contatos_corporativos')?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ashownet/empresa_folhetos'); ?>">
                                        <i class="fa fa-file-text"></i> Folhetos
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ashownet/politicas_formularios'); ?>">
                                        <i class="fa fa-file-text"></i> Políticas, Formulário e Informações Gerais
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ashownet/produtos'); ?>">
                                        <i class="fa fa-file-text"></i> Produtos
                                    </a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="">
                                        <i class="fa fa-file-text"></i> Engenharia e técnologia
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo site_url('ashownet/engenharia_suporte');?>"> Suporte Técnico</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/engenharia_teste_homologacao');?>"> Teste e Homologação</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ashownet/espaco_ti');?>">
                                        <i class="fa fa-file-text"></i> Espaço TI
                                    </a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="">
                                        <i class="fa fa-file-text"></i> Marketing
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo site_url('ashownet/marketing_briefing');?>"> Briefing</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/marketing_campanhas');?>"> Campanhas</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="">
                                        <i class="fa fa-file-text"></i> Televendas
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo site_url('ashownet/apresentacoes_comerciais');?>"> Apresentações comerciais</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/comite_guerra');?>"> Comitê de Guerra</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/televendas_comunicados');?>"> Comunicados</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/propostas_comerciais');?>"> Propostas Comerciais</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/politicas_procedimentos');?>"> Políticas e Procedimentos</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/guia_produtos');?>"> Guia de Produtos</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/empresa_folhetos');?>"> Folhetos</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/precos_acessorios');?>"> Tabela de Preços e Acessórios</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('ashownet/inteligencia_mercado');?>"> Inteligência de Mercado</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ashownet/governanca_corporativa');?>">
                                        <i class="fa fa-file-text"></i> Governança Corporativa
                                    </a>
                                </li>
                                <?php if ($this->auth->is_allowed_block('vis_genteegestao')) : ?>
                                    <li>
                                        <a href="<?= site_url('InformacoesGerais/GentesGestoes');?>">
                                            <i class="fa fa-file-text"></i> <?=lang('gente_gestao')?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                       	</li>
                        <li class="dropdown <?php echo $this->router->fetch_class() == 'cadastros' ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastros
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($this->auth->is_allowed_block('clientes_visualiza')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('clientes') ?>">
                                            <i class="icon-briefcase"></i> Clientes
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cadastro_fornecedor')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('cadastro_fornecedor') ?>">
                                            <i class="fa fa-users"></i> Fornecedores
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php //if ($this->auth->is_allowed_block('documentacoes')) : ?>
                                    <!-- <li>
                                        <a href="<?php echo site_url('documentacoes/') ?>">
                                            <i class="icon-file"></i> Documentações
                                        </a>
                                    </li> -->
                                <?php //endif; ?>

                                <?php if ($this->auth->is_allowed_block('usuarios_visualiza')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('cadastros/usuarios') ?>">
                                            <i class="icon-user"></i> Funcionários
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_veiculos')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('cadastros/veiculos') ?>">
                                            <i class="fa fa-truck"></i> Veículos
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_permissoes')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('cadastros/cadastro_produtos') ?>">
                                            <i class="fa fa-folder"></i> Permissões (Gestor)
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- </?php if ($this->auth->is_allowed_block('cad_permissoes')) : ?>
                                    <li>
                                        <a href="</?php echo site_url('cadastros/cad_permissoes') ?>">
                                            <i class="fa fa-eye"></i> Permissões (Gestor)
                                        </a>
                                    </li>
                                </?php endif; ?> -->

                                <!-- </?php if ($this->auth->is_allowed_block('cad_planos')) : ?>
                                    <li>
                                        <a href="</?php echo site_url('cadastros/cad_planos') ?>">
                                            <i class="fa fa-folder"></i> Planos (Gestor)
                                        </a>
                                    </li>
                                </?php endif; ?> -->

                                <?php if ($this->auth->is_allowed_block('cad_equipamento')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('equipamentos/listar') ?>">
                                            <i class="icon-edit"></i> Equipamentos
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_contratos_eptc')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('contratos_eptc/listar_contratos') ?>">
                                            <i class="icon-file"></i> Contratos EPTC
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_agend_servico')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('agendamento') ?>">
                                            <i class="fa fa-calendar"></i> Agendamento de Serviços
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_instaladores')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('instaladores/listar_instaladores') ?>">
                                            <i class="icon-user"></i> Instaladores
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('monitoramento')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('gerencia_equipamentos') ?>">
                                            <i class="fa fa-columns" aria-hidden="true" ></i> Logística de equipamentos
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_anunciosprodutos')) : ?>
                                    <li>
                                        <a href="<?= site_url('vendasgestor/anuncios') ?>"> <?=lang('anuncios_produtos')?> </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_representantes')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('representantes/listar_representantes') ?>">
                                            <i class="icon-user"></i> Representantes
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_comandos')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('cadastros_comandos') ?>">
                                            <i class="fa fa-terminal"></i> Comandos
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->auth->is_allowed_block('licitacoes')) : ?>
                                <li>
                                    <a href="<?= base_url() ?>index.php/licitacao/acompanhamento">
                                        <i class="fa fa-book"></i> Licitações
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if ($this->auth->is_allowed_block('cad_auditoriashownet')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('usuarios/getAuditoriaShownet') ?>">
                                            <i class="fa fa-file"></i> Auditoria Shownet
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="divider"></li>

                                <?php if ($this->auth->is_allowed_block('cad_linhas')) : ?>
                                    <li class="dropdown-submenu">
                                        <a href="">
                                            <i class="fa fa-mobile-phone"></i> Linhas
                                        </a>
                                        <ul class="dropdown-menu">

                                            <?php if ($this->auth->is_allowed_block('cad_mikrotik')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/linhas') ?>"> Mikrotik</a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if ($this->auth->is_allowed_block('cad_chips')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('linhas/listar') ?>"> Chips</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                 <?php if ($this->auth->is_allowed_block('cad_rh')){?>
                                    <li class="dropdown-submenu">
                                        <a href="">
                                            <i class="fa fa-address-book"></i> RH
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php if ($this->auth->is_allowed_block('cad_aniversariantes')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_aniversariantes');?>"> Aniversariantes</a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('cad_apresentacoes')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_apresentacoes');?>"> Apresentações</a>
                                                </li>
                                            <?php } ?>
                                            <?php if($this->auth->is_allowed_block('cad_banner')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_banners');?>"> Banners</a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('cad_comunicado')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_comunicados');?>"> Comunicados</a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('cad_contatos_corporativos')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_contatos_corporativos');?>"> Contatos Corporativos</a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('cad_folhetos')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_folhetos');?>"> Folhetos</a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('cad_sobre_empresa')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_sobreaempresa');?>"> Sobre a empresa</a>
                                                </li>
                                                <?php if ($this->auth->is_allowed_block('cad_produtos')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_produtos');?>"> Produtos</a>
                                                </li>
                                            <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_veiculos')) : ?>
                                <li>
                                    <a href="<?php echo site_url('comandos/view') ?>">
                                        <i class="fa fa-envelope-open"></i> Comandos SMS
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li class="dropdown <?php echo $this->router->fetch_class() == 'faturas' ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Financeiro
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($this->auth->is_allowed_block('inadimplencias_faturas')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('faturas/inadimplencia') ?>">
                                            <i class="fa fa-line-chart"></i> Inadimplências
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('lancamentos')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('contas/pre_aprovacao') ?>">
                                            <i class="fa fa-money"></i> Ordem de Pagamento
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <?php if ($this->auth->is_allowed_block('faturas')) : ?>
                                    <li class="dropdown-submenu">
                                        <a href="javascript:">
                                            <i class="icon-barcode"></i> Faturas <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">

                                            <?php if ($this->auth->is_allowed_block('faturas_visualiza')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('faturas') ?>">
                                                        <i class="icon-th-list"></i> Lista
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->auth->is_allowed_block('config_boleto')) : ?>
                                                <li>
                                                    <a href="#faturaConfig" data-toggle="modal">
                                                        <i class="fa fa-cogs"></i> Config. Boleto
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                                <?php if ($this->auth->is_allowed_block('cad_relatoriofaturamento')) : ?>
                                                    <li>
                                                        <a href="<?php echo site_url('relatorio_faturamento') ?>">
                                                            <i class="fa fa-file-text-o"></i> <?=lang('relatorio_faturamento_receita_bruta')?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->auth->is_allowed_block('faturas_retorno')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('faturas/baixar') ?>">
                                            <i class="icon-download-alt"></i> Baixa por Retorno
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->auth->is_allowed_block('chave_desconto')) : ?>
                                    <li class="dropdown-submenu">
                                        <a href="javascript:;">
                                            <i class="fa fa-key"></i> Chave de Desconto <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">

                                            <?php if ($this->auth->is_allowed_block('criar_chave_desconto')) : ?>
                                                <li>
                                                    <a href="#NovaChave" data-toggle="modal" data-target="#NovaChave">
                                                        <i class="fa fa-plus-square"></i> Criar
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if ($this->auth->is_allowed_block('listar_chave_desconto')) : ?>
                                                <li>
                                                    <a href="#ListarChaves" data-toggle="modal" data-target="#ListarChaves">
                                                        <i class="icon-th-list"></i> Listar
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('contas_a_pagar')) : ?>
                                    <li class="dropdown-submenu">
                                        <a href="javascript:;">
                                            <i class="icon-barcode"></i> Contas
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">

                                            <?php if ($this->auth->is_allowed_block('contas_showtecnologia')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('contas') ?>">
                                                        <i class="icon-th-list"></i> ShowTecnologia
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if ($this->auth->is_allowed_block('contas_eua')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('contas/contas_eua') ?>">
                                                        <i class="icon-th-list"></i> ShowTechnology
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if ($this->auth->is_allowed_block('contas_showtecnologia')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('contas/norio') ?>">
                                                        <i class="icon-th-list"></i> Norio Momoi
                                                    </a>
                                                </li>
                                            <?php endif; ?>


                                            <?php if ($this->auth->is_allowed_block('contas_pneushow')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('contas/pneushow') ?>">
                                                        <i class="icon-th-list"></i> Pneu Show
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('baixa_extrato_show') || $this->auth->is_allowed_block('baixa_extrato_norio')) : ?>
                                    <li>
                                        <a href="<?= site_url('extract'); ?>">
                                            <i class="fa fa-wpforms"></i> <?=lang('baixa_por_extrato')?> <span class="arrow"></span>
                                        </a>                                        
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('baixa_extrato_show') || $this->auth->is_allowed_block('baixa_extrato_norio')) : ?>
                                    <li class="dropdown-submenu">
                                        <a href="javascript:">
                                            <i class="fa fa-wpforms"></i> Baixa por Extrato <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php if ($this->auth->is_allowed_block('baixa_extrato_show')) : ?>
                                            <li class="dropdown-submenu">
                                                <a href="<?php echo site_url('extract'); ?>">
                                                    <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                    Show Tecnologia
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/0/0'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Tudo
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/0/1'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Banco do Brasil
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/0/3'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Bradesco
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/0/2'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Caixa
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="dropdown-submenu">
                                                <a href="<?php echo site_url('extract'); ?>">
                                                    <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                    Show Technology
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/2/0'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Tudo
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/2/4'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Paypal
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <?php endif; ?>
                                            <?php if ($this->auth->is_allowed_block('baixa_extrato_norio')) : ?>
                                            <li class="dropdown-submenu">
                                                <a href="<?php echo site_url('extract/index/1'); ?>">
                                                    <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                    Norio Momoi EPP
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/1/0'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Tudo
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/1/1'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Banco do Brasil
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/1/3'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Bradesco
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo site_url('extract/index/1/2'); ?>">
                                                            <i class="fa fa-wpforms" aria-hidden="true"></i>
                                                            Caixa
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li
                                class="dropdown <?php echo $this->router->fetch_class() == 'relatorios' ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Relatórios
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('relatorios/assinatura_eptc')?>">
                                        <i class="icon-briefcase"></i> Assinaturas EPTC
                                    </a>
                                </li>

                                <li class="dropdown-submenu">
                                    <a href="javascript:;">
                                        <i class="fa fa-file-text-o"></i> Contratos <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php if ($this->auth->is_allowed_block('rel_contratos')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/contratos') ?>">
                                                    <i class="fa fa-file-text-o"></i> Contratos
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->auth->is_allowed_block('rel_contratos')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/rescisao_contratos_privados') ?>">
                                                    <i class="fa fa-file"></i> Cálculo Rescisão de Contratos Privados
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->auth->is_allowed_block('rel_contratos')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/quantitativoContratos') ?>">
                                                    <i class="fa fa-bar-chart" aria-hidden="true"></i> <?=lang('quantitativo_contratos_veiculos')?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>

                                <?php if ($this->auth->is_allowed_block('rel_tempo_logado')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('relatorios/tempo_logado') ?>">
                                            <i class="fa fa-clock-o"></i> Tempo Logado
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('visualizar_tickets')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('relatorios/rel_tickets') ?>">
                                            <i class="icon-envelope"></i> Tickets
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->auth->is_allowed_block('rel_placas_ativas_inativas')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('relatorios/placas_ativas_inativas')?>">
                                            <i class="fa fa-car"></i> Placas Ativas/Inativas
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li class="divider"></li>

                                <li class="dropdown-submenu"><a href="javascript:;"> 
                                    <i class="icon-barcode"></i> Financeiro <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php if ($this->auth->is_allowed_block('rel_financeiro_faturas')) : ?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/faturas') ?>">
                                                        <i class="icon-barcode"></i> Faturas
                                                    </a>
                                                </li>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/faturas_atrasadas') ?>">
                                                    <i class="icon-barcode"></i> Faturas Atrasadas
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/resumo_faturas') ?>">
                                                    <i class="icon-barcode"></i> Resumo Faturamento
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/fatura_disponibilidade') ?>">
                                                    <i class="icon-barcode"></i> Resumo Fatura por Disonibilidade
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->auth->is_allowed_block('rel_financeiro_faturas')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/fatura_cliente') ?>">
                                                    <i class="fa fa-thumbs-down"></i> Clientes Inadimplentes
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->auth->is_allowed_block('rel_financeiro_fatenviadas')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/faturas_enviadas') ?>">
                                                    <i class="icon-inbox"></i> Envio de Faturas
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_contas')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/contas') ?>">
                                                    <i class="fa fa-money"></i> Contas a Pagar
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('comissao')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/comissao') ?>">
                                                    <i class="fa fa-money"></i> Comissão
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_tipo_servico')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/relatorio_tipo_servico') ?>">
                                                    <i class="fa fa-adjust"></i> Relatorio por Tipo de Serviço
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->auth->is_allowed_block('comissao_showroutes')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/comissao_dev') ?>">
                                                    <i class="fa fa-money"></i> Comissão - ShowRoutes
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->auth->is_allowed_block('rel_adesao')) : ?>
                                            <li>
                                                <a href="<?= site_url('relatorios/rel_adesao') ?>">
                                                    <i class="fa fa-plus"></i> <?=lang('geracao_adesao')?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                    </ul>
                                </li>

                                <li class="dropdown-submenu">
                                    <a href="javascript:;">
                                        <i class="fa fa-microchip"></i> Chips/Linhas <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php if ($this->auth->is_allowed_block('rel_eqp_desat')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('equipamentos/equipamentos_parado') ?>">
                                                <i class="fa fa-tag"></i> Equipamentos/Linhas Desatualizados
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                           <?php if ($this->auth->is_allowed_block('rel_eqp_desat')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('linhas/listarchip') ?>">
                                                <i class="fa fa-tag"></i> Relatório Linhas
                                            </a>
                                        </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('analise_contaOp')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('linhas/detConta') ?>">
                                                    <i class="fa fa-search-plus"></i> Analise de Fatura Operadora
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>

                                <li class="dropdown-submenu">
                                    <a href="javascript:;">
                                        <i class="fa fa-group"></i> Clientes <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php if ($this->auth->is_allowed_block('rel_clientes_uf')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/clientes_uf') ?>">
                                                    <i class="fa fa-address-book-o"></i> Clientes por UF
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_resumo_veic_disponiveis')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/resumoVeiculosDisponiveis') ?>">
                                                    <i class="fa fa-align-center"></i> Resumo veículos disponíveis
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_veic_disponiveis')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/veiculosDisponiveis') ?>">
                                                    <i class="fa fa-check"></i> Veículos disponíveis
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_veic_disponiveis')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/veiculosDiaAtualizacao') ?>">
                                                    <i class="fa fa-check"></i> Atualização de Veículos
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_veic_disponiveis')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/veiculos_por_atividades') ?>">
                                                    <i class="fa fa-car"></i> <?=lang('veiculos_por_atividades');?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_monitorados_dia_atividade')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/monitoradosDiaAtualizacao') ?>">
                                                    <i class="fa fa-lock"></i> <?=lang('monitorados_dia_atualizacao')?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_veic_tempo_contrato')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/veiculos_tempo_contrato') ?>">
                                                    <i class="icon-file"></i> Relatorio por Tempo de Contrato
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_clients_publicos')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/rel_clientes') ?>">
                                                    <i class="icon-user"></i> Relatório Clientes
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('rel_dash_veic')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/dashboardVeiculosDisponiveis') ?>">
                                                    <i class="fa fa-line-chart"></i> Dashboard Veículos disponíveis
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($this->auth->is_allowed_block('vis_relatoriobasedeclientes')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/base_clientes') ?>">
                                                    <i class="fa fa-info-circle"></i> <?=lang('base_clientes')?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                    </ul>
                                </li>

                            </ul>
                        </li>

                        <li class="dropdown <?php echo $this->router->fetch_class() == 'cadastros' || $this->uri->segment(2) == 'desatualizados' ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Suporte<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php if ($this->auth->is_allowed_block('downloads_os')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('servico') ?>">
                                            <i class="icon-pencil"></i> Ordem de Serviços
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('visualizar_tickets')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('webdesk') ?>">
                                            <i class="icon-envelope"></i> Ticket
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('vis_painelinfobip')) :?>
                                    <li>
                                        <a href="<?= site_url('PaineisInfobip') ?>">
                                            <?=lang('painel_infobip')?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li class="divider"></li>

                                <?php if ($this->auth->is_allowed_block('logs')) : ?>
                                    <li class="dropdown-submenu">
                                        <a href=""><i class="icon-file"></i> Logs</a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?php echo site_url('veiculos/log_veiculos') ?>">
                                                    <i class="fa fa-automobile"></i> Cadastro de Veículos
                                                </a>
                                            </li>

                                            <?php if ($this->auth->is_allowed_block('rel_envio_sms')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/sms') ?>">
                                                    <i class="fa fa-mobile"></i> Envio SMS
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                             
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <?php if ($this->auth->is_allowed_block('configuracoes')) : ?>
                            <li class="dropdown <?php echo $this->router->fetch_class() == 'configuracoes' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Configurações
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if ($this->auth->is_allowed_block('mensagem_notificacao')) : ?>
                                        <li class="dropdown-submenu">
                                            <a href="javascript:;">
                                                <i class="fa fa-comment"></i> Mensagens Notificações <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="<?php echo site_url('configuracoes/notificacoes/sms') ?>">
                                                        <i class="fa fa-mobile"></i>SMS
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if ($this->auth->is_allowed_block('monitoramento')) : ?>
                            <li class="dropdown <?php echo $this->router->fetch_class() == 'monitoramento' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Monitoramento
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo site_url('webdesk/view_dash') ?>">
                                            <i class="fa fa-dashboard"></i> Dashboard Tickets
                                        </a>
                                    </li>

                                    <?php if ($this->auth->is_allowed_block('veiculos_desatualizados')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('veiculos/desatualizados') ?>">
                                            <i class="fa fa-truck"></i> Desatualizados
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('monitor_panico')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('monitor/monitor_panico') ?>">
                                            <i class="fa fa-bullhorn"></i> Pânico
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('equipamentos_violados')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('monitor/equipamento_violado') ?>">
                                            <i class="fa fa-exclamation-triangle"></i> Equipamento Violado
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('monitor_contrato')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('monitor/monitor_contratos') ?>">
                                                <i class="fa fa-handshake-o"></i> Monitoramento de Contratos
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li>
                                        <a href="<?php echo site_url('monitoramento/tickets') ?>">
                                            <i class="fa fa-ticket"></i> Tickets
                                        </a>
                                    </li>

                                    <li>
                                        <a href="https://gestor.showtecnologia.com/gtw/gateway/" target="_blank">
                                            <i class="fa fa-line-chart"></i> Gateways
                                        </a>
                                    </li>

                                    <!-- <li>
                                        <a href="<?php echo site_url('tarefas/index') ?>">
                                            <i class="fa fa-diamond"></i> Desempenho - Atividades
                                        </a>
                                    </li> -->
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li class="dropdown <?php echo $this->router->fetch_class() == 'monitoramento' ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Contrato
                                <b class="caret"></b>
                            </a>
                            <?php if ($this->auth->is_allowed_block('add_termo')) : ?>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('licitacao') ?>">
                                        <i class="fa fa-handshake-o"></i> Termo de Adesão
                                    </a>
                                </li>
                            </ul>
                            <?php endif; ?>
                        </li>

                        <?php if ($this->auth->is_allowed_block('vis_dashboards')) :?>
                            <li class="dropdown <?php echo $this->router->fetch_class() == 'dashboards' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?=lang('dashboards')?>
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">

                                    <?php if ($this->auth->is_allowed_block('vis_dashboardspainelos')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/painelOs') ?>">
                                                <?=lang('painel_os')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboard-painelprocessos')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/painelProcessos') ?>">
                                                <?=lang('painel_processos')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('out_dashboard-ndicesestoqueecompras')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/indicesEstoqueCompras') ?>">
                                                <?=lang('indices_estoque_compras')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboard-paineldefaturamento')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/painelAF') ?>">
                                                <?=lang('painel_af')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboard-operaes')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/operacoes') ?>">
                                                <?=lang('operacoes')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('out_indicadores-sac')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/indicadoresSac') ?>">
                                                <?=lang('indicadores_sac')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboardspainelcallcenter')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/painelCallCenter') ?>">
                                                <?=lang('painel_call_center')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboardsestoquedeterceiros')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/estoqueTerceiros') ?>">
                                                <?=lang('estoque_terceiros')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboardspaineldevendas')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/painelVendas') ?>">
                                                <?=lang('painel_vendas')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboardsprimeiracomunicacao')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/primeiraComunicacao') ?>">
                                                <?=lang('primeira_comunicacao')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_dashboardspagamentoprestadores')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/pagamentoPrestadores') ?>">
                                                <?=lang('prestadores_pso_e_rvo')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_consultaaferede')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/consultaAFRede') ?>">
                                                <?=lang('consulta_af_e_rede')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_custofaturamentoxcustoa52')) :?>
                                        <li>
                                            <a href="<?php echo site_url('Dashboards/custo_faturamento_a52') ?>">
                                                <?=lang('custo_faturamento_a52')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if ($this->auth->is_allowed_block('vis_visualizarperfisdeprofissionais')) :?>
                            <li class="dropdown <?php echo $this->router->fetch_class() == 'omnisearch' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Omniscore
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if ($this->auth->is_allowed_block('vis_visualizarperfisdeprofissionais')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('PerfisProfissionais') ?>">
                                                <i class="fa fa-search"></i> <?=lang('consultas_realizadas')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($this->auth->is_allowed_block('vis_custosdosperfisdeprofissionais')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('relatorios/custos_perfis_profissionais') ?>">
                                                <i class="fa fa-money"></i> <?=lang('custos_consultas_omniscore')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </li>
                        <?php endif; ?>
                        
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div id="NovaChave" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">x</button>
        <h3 id="myModalLabel1">Gerar Chave de Desconto</h3>
    </div>

</div>

<div id="ListarChaves" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">x</button>
        <h3 id="myModalLabel1">Chaves de Desconto</h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <table class="table table-bordered" id="">
                <thead>
                <th class="span4">Chave</th>
                <th class="span5">Descrição</th>
                </thead>
                <tbody>
                <tr>
                    <td>Chave</td>
                    <td>Cliente</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="gatewaysParados" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">x</button>
        <h3 id="myModalLabel1">Gateways</h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <table class="table table-bordered" id="gateways">
                <thead>
                <th class="span4">Gateway</th>
                <th class="span5">Data/Hora Atualização</th>
                <th class="span4">Status</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="container-fluid" style="<?php echo $this->agent->is_mobile() == false ? 'padding-top: 50px' : '' ?>">
	<ul class="breadcrumb">
		<?php if ($this->router->fetch_class() != 'Homes') : ?>
			<li>
                <a href="<?php echo site_url('Homes') ?>">
                    Home
                </a>
                <span class="divider">/</span>
            </li>
			<li><a href="<?php echo site_url($this->router->fetch_class()) ?>"><?php echo ucfirst($this->router->fetch_class()) ?>
				</a> <span class="divider">/</span></li>
			<li class="active"><?php echo ucwords(str_replace('_', ' ', $this->router->fetch_method())) ?>
			</li>
		<?php else : ?>
			<li class="active">Home<span class="divider">/</span></li>
			<li class="active">ShowNet</li>
		<?php endif; ?>
	</ul>

    <div id="renovaSenha" class="modal fade hide" role="dialog" data-backdrop="static">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Senha Expirada</h4>
            </div>

            <div class="alert alert-warning hide" id="alert_pass"></div>

            <div class="modal-body">
                <form id="formSenha">
                    <div class="form-group">
                        <label for="pass_atual">Senha Atual:</label>
                        <input type="password" class="form-control" id="pass_atual">
                    </div>

                    <div>
                        <div class="form-group" style="float: left; margin-right: 10px;">
                            <label for="pass_nova">Nova Senha:</label>
                            <input type="password" class="form-control" id="pass_nova">
                        </div>

                        <div class="form-group">
                            <label for="pass_nova">Nível de Segurança:</label>
                            <div class="progress">
                                <div class="bar" id="progress-bar" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pass_nova_confirm">Confirme a Senha:</label>
                        <input type="password" class="form-control" id="pass_nova_confirm">
                        <span id="status_confirm"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="salve_senha" class="btn btn-success">Salvar</button>
            </div>
            </div>

        </div>
    </div>