<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $titulo;?></title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="<?php echo base_url('media');?>/css/impressao.css" type="text/css" media="print">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url('assets');?>/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url('assets');?>/Ionicons/css/ionicons.min.css">
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/AdminLTE.min.css">
<link href="<?php echo base_url('media');?>/css/calendario.css" rel="stylesheet">
<link href="<?php echo base_url('media');?>/css/moldura.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=versionFile('media/css', 'custom.css') ?>">
<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/skins/_all-skins.min.css">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('media/img/favicon.png');?>">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('media/img/favicon.png');?>">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('media/img/favicon.png');?>">
<link rel="apple-touch-icon-precomposed" href="<?php echo base_url('media/img/favicon.png');?>">
<link href="<?php echo base_url('assets/css/dataTables.bootstrap.css');?>" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo base_url('media/img/favicon.png');?>">
<script src="<?php echo base_url('assets');?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('media');?>/js/cycle.js"></script>
<script type="text/javascript" src="<?php echo base_url('media');?>/js/jquery-form.js"></script>
<link href="<?php echo base_url('media') ?>/css/jquery.tree-multiselect.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.tree-multiselect.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script><link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" />
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<!-- TRADUCAO DE IDIOMAS PARA ARQUIVOS JS -->
<script type="text/javascript">
	var languageJSON = <?= json_encode($this->session->userdata('lang')) ?>;
	var lang = JSON.parse(languageJSON);
	var langDatatable = lang.datatable;
</script>

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
	<div class="wrapper">
		<header class="main-header">
			<nav class="navbar navbar-static-top">
				<div class="container">
					<div class="navbar-header">
						<a href="<?php echo site_url('Homes');?>" class="navbar-brand"><b>Show</b>Net</a>
						<button type="button" class="navbar-toggle collapsed"
							data-toggle="collapse" data-target="#navbar-collapse">
							<i class="fa fa-bars"></i>
						</button>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
						<?php if ($this->auth->get_login('admin', 'email')){?>
						<ul class="nav navbar-nav">
							<li class="dropdown <?php echo $this->router->fetch_class() == 'ashownet' ? 'active' : '' ?>">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Informações <span class="caret"></span></a>
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
                                    <?php if ($this->auth->is_allowed_block('vis_comercialetelevendasinformacaogeral')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais'); ?>">
                                                <i class="fa fa-file-text"></i> Comercial
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a href="<?php echo site_url('ashownet/iso'); ?>">
                                            <i class="fa fa-file-text"></i> Arquivos ISO - Controle de Qualidade
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
                                            <a href="<?= site_url('GentesGestoes/GentesGestoesInfoGerais');?>">
                                                <i class="fa fa-file-text"></i> <?=lang('gente_gestao')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                           	</li>
							<li class="dropdown <?php echo $this->router->fetch_class() == 'cadastros' ? 'active' : '' ?>">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastros <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
								<?php if ($this->auth->is_allowed_block('clientes_visualiza')){ ?>
                                    <li>
                                        <a href="<?php echo site_url('clientes'); ?>">
                                            <i class="fa fa-briefcase"></i> Clientes
                                        </a>
                                    </li>
                                <?php } ?>

								<?php if ($this->auth->is_allowed_block('cadastro_fornecedor')){?>
                                    <li>
                                        <a href="<?php echo site_url('cadastro_fornecedor');?>">
                                            <i class="fa fa-users"></i> Fornecedores
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if ($this->auth->is_allowed_block('usuarios_visualiza')){?>
                                    <li>
                                        <a href="<?= site_url('usuarios');?>">
                                            <i class="fa fa-user"></i> Funcionários
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if ($this->auth->is_allowed_block('cad_veiculos')){?>
                                    <li>
                                        <a href="<?php echo site_url('cadastros/veiculos');?>">
                                            <i class="fa fa-truck"></i> Veículos
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if ($this->auth->is_allowed_block('cad_permissoes')){?>
                                    <li>
                                        <a href="<?php echo site_url('cadastros/cadastro_produtos');?>">
                                            <i class="fa fa-eye"></i> Permissões (Gestor)
                                        </a>
                                    </li>
                                <?php } ?>
                                <!--
                                </?php if ($this->auth->is_allowed_block('cad_planos')){?>
                                    <li>
                                        <a href="</?php echo site_url('cadastros/cad_planos');?>">
                                            <i class="fa fa-folder"></i> Planos (Gestor)
                                        </a>
                                    </li>
                                </?php } ?> -->

								<?php if ($this->auth->is_allowed_block('cad_permissoes_funcionarios')):?>
                                    <li>
                                        <a href="<?php echo site_url('usuarios/permissoesFuncionarios');?>">
                                            <i class="fa fa-eye"></i> <?=lang('permissoes_usuarios_show')?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->auth->is_allowed_block('cad_equipamento')){?>
                                    <li>
                                        <a href="<?php echo site_url('equipamentos/listar');?>">
                                            <i class="fa fa-edit"></i> Equipamentos
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_equipamento')){?>
                                    <li>
                                        <a href="<?php echo site_url('suprimentos/listar');?>">
                                            <i class="fa fa-edit"></i> Suprimentos
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_contratos_eptc')){?>
                                    <li>
                                        <a href="<?php echo site_url('contratos_eptc/listar_contratos');?>">
                                            <i class="fa fa-file"></i> Contratos EPTC
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_agend_servico')){?>
                                    <li>
                                        <a href="<?php echo site_url('agendamento');?>">
                                            <i class="fa fa-calendar"></i> Agendamento de Serviços
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_instaladores')){?>
                                    <li>
                                        <a href="<?php echo site_url('instaladores/listar_instaladores');?>">
                                            <i class="fa fa-user"></i> Instaladores
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('monitoramento')){?>
                                    <li>
                                        <a href="<?php echo site_url('gerencia_equipamentos');?>">
                                            <i class="fa fa-columns" aria-hidden="true" ></i> Logística de Equipamentos
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_anunciosprodutos')) : ?>
                                    <li>
                                        <a href="<?= site_url('vendasgestor/anuncios') ?>"> <?=lang('anuncios_produtos')?> </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->auth->is_allowed_block('cad_representantes')){?>
                                    <li>
                                        <a href="<?php echo site_url('representantes/listar_representantes');?>">
                                            <i class="fa fa-user"></i> Representantes
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_comandos')){?>
                                    <li>
                                        <a href="<?php echo site_url('cadastros_comandos');?>">
                                            <i class="fa fa-terminal"></i> Comandos
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('licitacoes')){?>
                                <li>
                                    <a href="<?= base_url() ?>index.php/licitacao/acompanhamento">
                                        <i class="fa fa-book"></i> Licitações
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_logfuncionarios')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('usuarios/getAuditoriaShownet') ?>">
                                            <i class="fa fa-file"></i> Auditoria Shownet
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <?php if ($this->auth->is_allowed_block('cad_linhas')){?>
                                    <li class="dropdown-submenu">
                                        <a href="">
                                            <i class="fa fa-mobile-phone"></i> Linhas
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php if($this->auth->is_allowed_block('cad_mikrotik')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/linhas');?>"> Mikrotik</a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('cad_chips')){?>
                                                <li>
                                                    <a href="<?php echo site_url('linhas/listChips');?>"> Chips</a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
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
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('cad_produtos')){?>
                                                <li>
                                                    <a href="<?php echo site_url('cadastros/listar_produtos');?>"> Produtos</a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_veiculos')){?>
                                <li>
                                    <a href="<?php echo site_url('comandos/view');?>">
                                        <i class="fa fa-envelope-open"></i> Comandos SMS
                                    </a>
                                </li>                                
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('cad_centrais')) : ?>
                                    <li>
                                        <a href="<?php echo site_url('cad_centrais/index');?>">
                                            <i class="fa fa-automobile"></i> Centrais MHS
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
                                    <?php if ($this->auth->is_allowed_block('inadimplencias_faturas')){?>
                                        <li>
                                            <a href="<?php echo site_url('faturas/inadimplencia') ?>">
                                                <i class="fa fa-line-chart"></i> Inadimplências
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('lancamentos')){?>
                                        <li>
                                            <a href="<?php echo site_url('contas/pre_aprovacao') ?>">
                                                <i class="fa fa-money"></i> Ordem de Pagamento
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="divider"></li>
                                    <?php if ($this->auth->is_allowed_block('faturas')){?>
                                        <li class="dropdown-submenu">
                                            <a href="javascript:">
                                                <i class="fa fa-barcode"></i> Faturas <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php if ($this->auth->is_allowed_block('faturas_visualiza')){?>
                                                    <li>
                                                        <a href="<?php echo site_url('faturas') ?>">
                                                            <i class="icon-th-list"></i> Lista
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->auth->is_allowed_block('config_boleto')){?>
                                                    <li>
                                                        <a href="#faturaConfig" data-toggle="modal">
                                                            <i class="fa fa-cogs"></i> Config. Boleto
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->auth->is_allowed_block('cad_relatoriofaturamento')) : ?>
                                                <li>
                                                        <a href="<?php echo site_url('relatorio_faturamento') ?>">
                                                        <i class="fa fa-file-text-o"></i> <?=lang('relatorio_faturamento_receita_bruta')?>
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('faturas_retorno')){?>
                                        <li>
                                            <a href="<?php echo site_url('faturas/baixar') ?>">
                                                <i class="fa fa-download"></i> Baixa por Retorno
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('chave_desconto')){?>
                                        <li class="dropdown-submenu">
                                            <a href="javascript:;">
                                                <i class="fa fa-key"></i> Chave de Desconto <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php if ($this->auth->is_allowed_block('criar_chave_desconto')){?>
                                                    <li>
                                                        <a href="#NovaChave" data-toggle="modal" data-target="#NovaChave">
                                                            <i class="fa fa-plus-square"></i> Criar
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->auth->is_allowed_block('listar_chave_desconto')){?>
                                                    <li>
                                                        <a href="#ListarChaves" data-toggle="modal" data-target="#ListarChaves">
                                                            <i class="fa fa-th-list"></i> Listar
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('contas_a_pagar')){?>
                                        <li class="dropdown-submenu">
                                            <a href="javascript:;">
                                                <i class="fa fa-barcode"></i> Contas
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php if ($this->auth->is_allowed_block('contas_showtecnologia')){?>
                                                    <li>
                                                        <a href="<?php echo site_url('contas') ?>">
                                                            <i class="fa fa-th-list"></i> ShowTecnologia
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->auth->is_allowed_block('contas_eua')){?>
                                                    <li>
                                                        <a href="<?php echo site_url('contas/contas_eua') ?>">
                                                            <i class="fa fa-th-list"></i> ShowTechnology
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->auth->is_allowed_block('contas_showtecnologia')){?>
                                                    <li>
                                                        <a href="<?php echo site_url('contas/norio') ?>">
                                                            <i class="fa fa-th-list"></i> Norio Momoi
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->auth->is_allowed_block('contas_pneushow')){?>
                                                    <li>
                                                        <a href="<?php echo site_url('contas/pneushow') ?>">
                                                            <i class="fa fa-th-list"></i> Pneu Show
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <?php if ($this->auth->is_allowed_block('baixa_extrato_show') || $this->auth->is_allowed_block('baixa_extrato_norio')) : ?>
                                        <li>
                                            <a href="<?= site_url('extract'); ?>">
                                                <i class="fa fa-wpforms"></i> <?=lang('baixa_por_extrato')?> <span class="arrow"></span>
                                            </a>                                        
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($this->auth->is_allowed_block('baixa_extrato_show') || $this->auth->is_allowed_block('baixa_extrato_norio')){?>
                                        <li class="dropdown-submenu">
                                            <a href="javascript:">
                                                <i class="fa fa-wpforms"></i> Baixa por Extrato <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php if ($this->auth->is_allowed_block('baixa_extrato_show')){?>
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
                                                <?php } ?>
                                                <?php if ($this->auth->is_allowed_block('baixa_extrato_norio')){?>
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
                                                <?php } ?>
                                            </ul>
                                        </li>

                                    <?php } ?>
                                </ul>
                            </li>
                            <li class="dropdown <?php echo $this->router->fetch_class() == 'relatorios' ? 'active' : '' ?>">
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
                                            <?php if ($this->auth->is_allowed_block('rel_contratos')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/contratos') ?>">
                                                        <i class="fa fa-file-text-o"></i> Contratos
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_contratos')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/rescisao_contratos_privados') ?>">
                                                        <i class="fa fa-file"></i> Cálculo Rescisão de Contratos Privados
                                                    </a>
                                                </li>
                                            <?php } ?>
											<?php if ($this->auth->is_allowed_block('rel_contratos')) : ?>
	                                            <li>
	                                                <a href="<?php echo site_url('relatorios/quantitativoContratos') ?>">
	                                                    <i class="fa fa-bar-chart" aria-hidden="true"></i> <?=lang('quantitativo_contratos_veiculos')?>
	                                                </a>
	                                            </li>
	                                        <?php endif; ?>
                                        </ul>
                                    </li>

                                    <?php if ($this->auth->is_allowed_block('rel_tempo_logado')){?>
                                        <li>
                                            <a href="<?php echo site_url('relatorios/tempo_logado') ?>">
                                                <i class="fa fa-clock-o"></i> Tempo Logado
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if ($this->auth->is_allowed_block('visualizar_tickets')){?>
                                        <li>
                                            <a href="<?php echo site_url('relatorios/rel_tickets') ?>">
                                                <i class="fa fa-envelope"></i> Tickets
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('rel_placas_ativas_inativas')){?>
                                        <li>
                                            <a href="<?php echo site_url('relatorios/placas_ativas_inativas')?>">
                                                <i class="fa fa-car"></i> Placas Ativas/Inativas
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                    	<a href="javascript:;">
                                    		<i class="fa fa-barcode"></i> Financeiro <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php if ($this->session->userdata('log_admin')['funcao']=="ven"){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/faturas') ?>">
                                                        <i class="fa fa-barcode"></i> Faturas
                                                    </a>
                                                </li>
                                            <?php }?>
                                            <?php if ($this->auth->is_allowed_block('rel_financeiro_faturas')){?>
                                                <?php if ($this->session->userdata('log_admin')['funcao']!="ven"){?>
                                                    <li>
                                                        <a href="<?php echo site_url('relatorios/faturas') ?>">
                                                            <i class="fa fa-barcode"></i> Faturas
                                                        </a>
                                                    </li>
                                                <?php }?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/faturas_atrasadas') ?>">
                                                        <i class="fa fa-barcode"></i> Faturas Atrasadas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?= site_url('relatorios/faturas_processadas') ?>">
                                                        <i class="fa fa-file-text-o"></i> Faturas Processadas 
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/resumo_faturas') ?>">
                                                        <i class="fa fa-barcode"></i> Resumo Faturamento
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/fatura_disponibilidade') ?>">
                                                        <i class="fa fa-barcode"></i> Resumo Fatura por Disonibilidade
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_financeiro_faturas')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/fatura_cliente') ?>">
                                                        <i class="fa fa-thumbs-down"></i> Clientes Inadimplentes
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_financeiro_fatenviadas')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/faturas_enviadas') ?>">
                                                        <i class="fa fa-inbox"></i> Envio de Faturas
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_contas')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/contas') ?>">
                                                        <i class="fa fa-money"></i> Contas a Pagar
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('comissao')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/comissao') ?>">
                                                        <i class="fa fa-money"></i> Comissão
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_tipo_servico')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/relatorio_tipo_servico') ?>">
                                                        <i class="fa fa-adjust"></i> Relatorio por Tipo de Serviço
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('comissao_showroutes')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/comissao_dev') ?>">
                                                        <i class="fa fa-money"></i> Comissão - ShowRoutes
                                                    </a>
                                                </li>
                                            <?php } ?>
											<?php if ($this->auth->is_allowed_block('rel_adesao')) : ?>
	                                            <li>
	                                                <a href="<?= site_url('relatorios/rel_adesao') ?>">
	                                                    <i class="fa fa-plus"></i> <?=lang('geracao_adesao')?>
	                                                </a>
	                                            </li>
	                                        <?php endif; ?>
											<?php if ($this->auth->is_allowed_block('rel_financeiro_faturas')) : ?>
	                                            <li>
	                                                <a href="<?php echo site_url('relatorios/faturas_geradas') ?>">
	                                                    <i class="fa fa-file"></i> <?=lang('faturas_geradas')?>
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
                                            <?php if ($this->auth->is_allowed_block('rel_eqp_desat')){?>
                                            <li>
                                                <a href="<?php echo site_url('equipamentos/equipamentos_parado') ?>">
                                                    <i class="fa fa-tag"></i> Equipamentos/Linhas Desatualizados
                                                </a>
                                            </li>
                                            <?php } ?>
                                               <?php if ($this->auth->is_allowed_block('rel_eqp_desat')){?>
                                            <li>
                                                <a href="<?php echo site_url('linhas/listarchip') ?>">
                                                    <i class="fa fa-tag"></i> Relatório Linhas
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('analise_contaOp')){?>
                                                <li>
                                                    <a href="<?php echo site_url('linhas/detConta') ?>">
                                                        <i class="fa fa-search-plus"></i> Analise de Fatura Operadora
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a href="javascript:;">
                                            <i class="fa fa-group"></i> Clientes <span class="arrow"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php if ($this->auth->is_allowed_block('rel_clientes_uf')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/clientes_uf') ?>">
                                                        <i class="fa fa-address-book-o"></i> Clientes por UF
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_resumo_veic_disponiveis')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/resumoVeiculosDisponiveis') ?>">
                                                        <i class="fa fa-align-center"></i> Resumo Veículos Disponíveis
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_veic_disponiveis')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/veiculosDisponiveis') ?>">
                                                        <i class="fa fa-check"></i> Veículos Disponíveis
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_veic_disponiveis')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/veiculosDiaAtualizacao') ?>">
                                                        <i class="fa fa-lock"></i> Atualização de Veículos
                                                    </a>
                                                </li>
                                            <?php } ?>
											<?php if ($this->auth->is_allowed_block('rel_monitorados_dia_atividade')) : ?>
													<li>
															<a href="<?php echo site_url('relatorios/monitoradosDiaAtualizacao') ?>">
																	<i class="fa fa-check"></i> <?=lang('monitorados_dia_atualizacao')?>
															</a>
													</li>
											<?php endif; ?>
                                            <?php if ($this->auth->is_allowed_block('rel_veic_tempo_contrato')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/veiculos_tempo_contrato') ?>">
                                                        <i class="fa fa-file"></i> Relatorio por Tempo de Contrato
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_clients_publicos')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/rel_clientes') ?>">
                                                        <i class="fa fa-user"></i> Relatório Clientes
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($this->auth->is_allowed_block('rel_dash_veic')){?>
                                                <li>
                                                    <a href="<?php echo site_url('relatorios/dashboardVeiculosDisponiveis') ?>">
                                                        <i class="fa fa-line-chart"></i> Dashboard Veículos Disponíveis
                                                    </a>
                                                </li>
                                            <?php } ?>
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
                                <?php if ($this->auth->is_allowed_block('downloads_os')){?>
                                    <li>
                                        <a href="<?php echo site_url('servico') ?>">
                                            <i class="fa fa-pencil"></i> Ordem de Serviços
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->auth->is_allowed_block('visualizar_tickets')){?>
                                    <li>
                                        <a href="<?php echo site_url('webdesk') ?>">
                                            <i class="fa fa-envelope"></i> Ticket
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if ($this->auth->is_allowed_block('visualizar_tickets')){?>
                                    <li>
                                        <a href="<?php echo site_url('PaineisOmnilink') ?>">
                                            <i class="fa fa-ticket"></i> Suporte Omnilink
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if ($this->auth->is_allowed_block('vis_painelinfobip')) :?>
                                    <li>
                                        <a href="<?= site_url('PaineisInfobip') ?>">
                                        <i class="fa fa-info-circle"></i> <?=lang('painel_infobip')?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <li class="divider"></li>
                                <?php if ($this->auth->is_allowed_block('logs')){?>
                                    <li class="dropdown-submenu">
                                        <a href=""><i class="fa fa-file"></i> Logs</a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?php echo site_url('veiculos/log_veiculos') ?>">
                                                    <i class="fa fa-automobile"></i> Cadastro de Veículos
                                                </a>
                                            </li>
                                            <?php if ($this->auth->is_allowed_block('rel_envio_sms')){?>
                                            <li>
                                                <a href="<?php echo site_url('relatorios/sms') ?>">
                                                    <i class="fa fa-mobile"></i> Envio SMS
                                                </a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php if ($this->auth->is_allowed_block('configuracoes')){?>
                            <li class="dropdown <?php echo $this->router->fetch_class() == 'configuracoes' ? 'active' : '' ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Configurações
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if ($this->auth->is_allowed_block('mensagem_notificacao')){ ?>
                                        <li class="dropdown-submenu">
                                            <a href="javascript:;">
                                                <i class="fa fa-comment"></i> Mensagens Notificações <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="<?php echo site_url('configuracoes/notificacoes/sms') ?>">
                                                        <i class="fa fa-mobile-phone"></i>SMS
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($this->auth->is_allowed_block('monitoramento')){?>
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
                                    <?php if ($this->auth->is_allowed_block('veiculos_desatualizados')){?>
                                    <li>
                                        <a href="<?php echo site_url('veiculos/desatualizados') ?>">
                                            <i class="fa fa-truck"></i> Desatualizados
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('monitor_panico')){?>
                                    <li>
                                        <a href="<?php echo site_url('monitor/monitor_panico') ?>">
                                            <i class="fa fa-bullhorn"></i> Pânico
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('equipamentos_violados')){?>
                                    <li>
                                        <a href="<?php echo site_url('monitor/equipamento_violado') ?>">
                                            <i class="fa fa-exclamation-triangle"></i> Equipamento Violado
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($this->auth->is_allowed_block('monitor_contrato')){?>
                                        <li>
                                            <a href="<?php echo site_url('monitor/monitor_contratos') ?>">
                                                <i class="fa fa-handshake-o"></i> Monitoramento de Contratos
                                            </a>
                                        </li>
                                    <?php } ?>
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
                            <li class="dropdown <?php echo $this->router->fetch_class() == 'monitoramento' ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Contrato
                                <b class="caret"></b>
                            </a>
                            <?php if ($this->auth->is_allowed_block('add_termo')){ ?>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('licitacao') ?>">
                                        <i class="fa fa-handshake-o"></i> Termo de Adesão
                                    </a>
                                </li>
                            </ul>
                            <?php } ?>
                        </li>
                        <?php } ?>
                        <!-- MENU ISCAS -->
                        <!-- Array com as permissões de iscas -->

                        <!-- Permissões necessárias para acessar a aba administrativa de iscas

                            "dashboard_iscas"
                            "equipamentos_iscas"
                            "relatorios_iscas"
                            "comandos_iscas"

                        -->
                        <!-- permissões para acessar o menu de iscas -->
                        <?php if ($this->auth->is_allowed_block('dashboard_iscas')
                                    || $this->auth->is_allowed_block('equipamentos_iscas')
                                    || $this->auth->is_allowed_block('relatorios_iscas')
                                    || $this->auth->is_allowed_block('comandos_iscas'))
                        {?>

                        <li class="dropdown <?php echo $this->router->fetch_class() == 'relatorios' ? 'active' : '' ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Iscas
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">

                                <li>
                                    <a href="<?php echo site_url('iscas/isca/dashboard') ?>">
                                        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard Iscas
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('monitoramento_iscas') ?>">
                                        <i class="fa fa-tag"></i> Monitoramento de Iscas
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('iscas/isca/agendamentos') ?>">
                                    <i class="fa fa-calendar-o" aria-hidden="true"></i> Agendamentos
                                    </a>
                                </li>

                            <?php if ($this->auth->is_allowed_block('equipamentos_iscas')){?>
                                <li class="dropdown-submenu">
                                    <a href="javascript:;">
                                        <i class="fa fa-cogs" aria-hidden="true"></i> Equipamentos <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo site_url('iscas/isca');?>">
                                                <i class="fa fa-microchip"></i> Iscas em Estoque
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('iscas/isca/listarIscasVinculadas');?>">
                                                <i class="fa fa-microchip"></i> Iscas Vinculadas
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <?php if ($this->auth->is_allowed_block('relatorios_iscas')){?>
                                <li class="dropdown-submenu">
                                    <a href="javascript:;">
                                    <i class="fa fa-list" aria-hidden="true"></i> Relatórios <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo site_url('iscas/comandos_isca') ?>">
                                                <i class="fa fa-file-text-o"></i> Comandos
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('iscas/isca/relatorioIscas') ?>">
                                                <i class="fa fa-file-text-o"></i> Iscas
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <?php if($this->auth->is_allowed_block('comandos_iscas')){?>
                                <li class="dropdown-submenu">
                                    <a href="javascript:;">
                                    <i class="fa fa-terminal" aria-hidden="true"></i> Comandos <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo site_url('iscas/comandos_isca/envio_comandos') ?>">
                                                <i class="fa fa-terminal" aria-hidden="true"></i> Envio único
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('monitoramento_iscas/comandos_iscas') ?>">
                                                <i class="fa fa-terminal"></i> Envio em Massa
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>

                            </ul>
                        </li>
                        <?php } ?>

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
						<?php } ?>
					</div>
					<!-- /.navbar-collapse -->
					<!-- Navbar Right Menu -->
					<div class="navbar-custom-menu">
						<?php if ($this->auth->get_login('admin', 'email')){?>
						<ul class="nav navbar-nav">
							<!-- Notifications Menu -->
							<li class="dropdown notifications-menu">
								<!-- Menu toggle button -->
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i id="sino_alerta" class="fa fa-bell"></i> <span class="badge" id="qtd_badge">0</span></a>
                            	<ul class="dropdown-menu">
                            		<li class="header">Notifica&ccedil;&otilde;es</li>
    								<li>
    									<ul class="menu">
    										<div id="notify_account" class="drop-content" style="padding: 10px;"></div>
    									</ul>
    								</li>
    							</ul>
							</li>
							<!-- User Account Menu -->
							<li class="dropdown user user-menu">
								<!-- Menu Toggle Button -->
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <!-- The user image in the navbar-->
									<!-- <img src="../../dist/img/user2-160x160.jpg" class="user-image" alt=""> -->
									<span class="hidden-xs"><?= explode(" ", $this->auth->get_login('admin', 'nome'))[0] ?></span>
								</a>
								<ul class="dropdown-menu">
									<li class="user-footer">
										<div class="pull-right">
											<a href="<?php echo site_url('acesso/sair/admin');?>" class="btn btn-default btn-flat">Sair</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
						<?php } ?>
					</div>
					<!-- /.navbar-custom-menu -->
				</div>
				<!-- /.container-fluid -->
			</nav>
		</header>
		<!-- Full Width Column -->
		<div class="content-wrapper">
			<div class="container">
				<section class="content-header">
					<ol class="breadcrumb">
					<?php if($this->router->fetch_class() != 'Homes'){ ?>
            			<li>
                            <a href="<?php echo site_url('Homes') ?>"> Home</a>
                        </li>
            			<li><a href="<?php echo site_url($this->router->fetch_class()) ?>"><?php echo ucfirst($this->router->fetch_class()) ?></a></li>
            			<li class="active"><?php echo ucwords(str_replace('_', ' ', $this->router->fetch_method())) ?>
            			</li>
            		<?php }else{ ?>
            			<li class="active">Home </li>
            			<li class="active">ShowNet</li>
            		<?php } ?>
					</ol>
				</section>
				<div id="renovaSenha" class="modal fade" role="dialog" data-backdrop="static">
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
				<br>
