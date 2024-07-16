<!DOCTYPE html>
<html>

<head>
    <title><?= $titulo ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url('media/img/favicon.png'); ?>">

    <?php $idioma = $this->auth->get_login_dados('idioma'); ?>
    <?php $emailUser = $this->auth->get_login_dados('email'); ?>
    
	<!-- SETA AS VARIAVEIS DE USO GERAL -->
	<script type="text/javascript">
		const BASE_URL_API_TELEVENDAS = '<?= config_item('url_api_televendas') ?>';
		const BASE_URL_API_TELEVENDAS_WEBSOCKET = '<?= config_item('url_api_televendas_websocket') ?>';
		const TOKEN_URL_API_TELEVENDAS = `<?= $this->auth->get_login_dados('tokenApiTelevendas') ?>`;
        const LINHA_ATIVA_CANAL_VOZ = '<?= config_item('linha_ativa_canal_voz')?>';
        const LINHA_ATIVA_ATENDIMENTO = '<?= config_item('linha_ativa_atendimento')?>';
		let BASE_URL = "<?= base_url() ?>";
		let SITE_URL = "<?= site_url() ?>";

		// TRADUCAO DE IDIOMAS PARA ARQUIVOS JS
		var languageJSON = <?= json_encode($this->session->userdata('lang')) ?>;
		var lang = JSON.parse(languageJSON);
		var langDatatable = lang.datatable;
	</script>

	<!-- ############ Carrega CSS da estrutura ############ -->

	<!-- Carrega JS de alert -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/helpers/alert.js') ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/alerts/alerts_style.css') ?>">

	<!-- Estilo geral do sistema -->
	<link rel="stylesheet" type="text/css" href="<?= versionFile('media/css', 'styles_new_layout.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= versionFile('media/css/themes', 'all-themes.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= versionFile('media/css', 'scrollbar.css') ?>">    

    <!-- CSS Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?= versionFile('media/plugins/bootstrap-3.3.7/dist/css', 'bootstrap.min.css') ?>">

    <!-- Material Icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

    <!-- CSS DataTable -->
    <link rel="stylesheet" type="text/css" href="<?= versionFile('media/datatable_NS', 'datatables.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= versionFile('media/datatable_NS', 'datatables.bootstrap.min.css') ?>">

    <!-- CSS DataTableResponse mobile -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" />

    <!-- CSS Chosen -->
    <?php if (isset($load) && in_array('select-chosen', $load)) : ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" />
    <?php endif; ?>

    <!-- CSS Jquery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- CSS Toastr -->
    <link href="<?= base_url('media/plugins/toastr/build/toastr.css') ?>" rel="stylesheet" />

    <!-- CSS BootStrap Multiselect -->
    <?php if (isset($load) && in_array('bootstrap-multiselect', $load)) : ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <?php endif; ?>

		<?php if (isset($load) && in_array('css-new-style', $load)): ?>
			<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css', 'layout.css') ?>">
		<?php endif; ?>

    <!-- ################################################## -->


    <!-- ############ JS da estrutura ############# -->

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= versionFile('media/js', 'admin.js') ?>"></script>
    <script src="<?= versionFile('media/plugins/JavaScript-MD5/js', 'md5.min.js') ?>"></script>

    <!-- Jquery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!--- Scripts de bandeiras do DDI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>

    <!-- Jquery Mask -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>


    <!-- Jquery Form -->
    <?php if (isset($load) && in_array('jquery-form', $load)) : ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <?php endif; ?>

    <!-- Bibliote para validacao de formularios -->
    <?php if (isset($load) && in_array('validate-form', $load)) : ?>
        <script type="text/javascript" src="<?= base_url('media/js/validate-form/jquery.validate.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('media/js/validate-form/additional-methods.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('media/js/validate-form/messages_pt_BR.min.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/helpers/validate-form.js') ?>"></script>
    <?php endif; ?>


    <!-- AG GRID -->
    <script type="text/javascript" src="<?= base_url('media/js/ag-grid-enterprise.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/helpers/aggrid/locale.pt-br.js') ?>"></script>
    <script>
        var TOKEN = "CompanyName=Zatix Tecnologia S/A,LicensedApplication=Omnilink,LicenseType=SingleApplication,LicensedConcurrentDeveloperCount=1,LicensedProductionInstancesCount=1,AssetReference=AG-022406,ExpiryDate=17_November_2022_[v2]_MTY2ODY0MzIwMDAwMA==096777d61698efec6a3ae1015743f3a2";
        agGrid.LicenseManager.setLicenseKey(TOKEN);
    </script>

    <script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_grid-helper.js') ?>"></script>

    <script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'utils.js') ?>"></script>
    <script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'exportar.js') ?>"></script>


    <!-- JS Utils -->
    <script type="text/javascript" src="<?= versionFile('media/js', 'utils.js') ?>"></script>

    <!-- JS BootStrap 3.3.7 -->
    <script type="text/javascript" src="<?= versionFile('media/plugins/bootstrap-3.3.7/dist/js', 'bootstrap.js') ?>"></script>

    <!-- JS DataTable -->
    <script type="text/javascript" src="<?= versionFile('media/datatable_NS', 'datatables.js') ?>"></script>
    <script type="text/javascript" src="<?= versionFile('media/datatable_NS', 'datatables.bootstrap.min.js') ?>"></script>

    <!-- JS DataTableResponse mobile -->
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <!-- JS Chosen -->
    <?php if (isset($load) && in_array('select-chosen', $load)) : ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <?php endif; ?>

    <!-- JS Toastr -->
    <script type="text/javascript" src="<?= base_url('media/plugins/toastr/toastr.js') ?>"></script>

    <!-- Mask -->
    <?php if (isset($load) && in_array("mask", $load)) : ?>
        <script src="<?= base_url('assets/js/jquery.mask.js'); ?>"></script>
    <?php endif; ?>

    <!-- JS BootStrap Multiselect -->
    <?php if (isset($load) && in_array('bootstrap-multiselect', $load)) : ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <?php endif; ?>

    <!-- XLSX -->
    <?php if (isset($load) && in_array('XLSX', $load)) : ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <?php endif; ?>

    <!-- ################################################## -->

    <!-- BIBLIOTECAS PARA A RESPONSIVIDADE DA DATATABLE -->
    <?php if (isset($load) && in_array('datatable_responsive', $load)) : ?>
        <script type="text/javascript" src="<?= versionFile('assets/js', 'dataTables.responsive.min.js') ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css', 'responsive.dataTables.css') ?>">
    <?php endif; ?>

    <!-- Select2 -->
    <?php if (isset($load) && in_array('select2', $load)) : ?>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- traducoes -->
        <?php if ($idioma === 'pt-BR') : ?>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
        <?php else : ?>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/<?= $idioma ?>.js"></script>
        <?php endif; ?>

			<script>
					$.fn.select2.defaults.set('language', "<?= $idioma ?>");
			</script>
    <?php endif; ?>


    <!-- Carrega JS Axios -->
    <script src="<?= base_url('media/js/axios.js'); ?>"></script>
    <!-- Script personalisado para uso geral no sistema - HEADER -->
	<script type="text/javascript" src="<?= versionFile('assets/js/fix', 'header.js') ?>"></script>

	<!-- Carrega DayJs -->
	<script type="text/javascript" src="<?= base_url('media/js/dayjs.min.js') ?>"></script>

    <!-- Carrega JS para uso de SharedWorker -->
    <?php if($this->auth->is_allowed_block("atendimento_whatsapp") || $this->auth->is_allowed_block("vis_ligacoes_filas_atendimento_omnilink")): ?>
        <script>
            const caminhoWorker = "<?= base_url("newAssets/js/sharedWorker/worker.js") ?>"
        </script>

        <script type="text/javascript" src="<?= base_url("newAssets/js/sharedWorker/index.js") . "?v=" . filesize("newAssets/js/sharedWorker/index.js") ?>"></script>
    <?php endif; ?>

    <script type="text/javascript">
        // Filtra menu pelo input de pesquisa
        function filterMenu(filter = '') {
            let links = $('#sidebar-items').find('a').toArray();
            let shown = [];

            links.forEach(link => {
                if (
                    !filter == '' &&
                    !$(link).html().toLowerCase().includes(filter.toLowerCase())
                ) {
                    $(link).hide();
                } else {
                    $(link).show();
                    shown.push(link);
                }
            });

            shown.forEach(element => {
                $(element).parents().toArray().forEach(parent => {
                    $(parent).children('a').show();
                });
            });
        }

        /**
         * GERA NOME DE ARQUIVOS DE EXPORTACAO
         */
        function filenameGenerator(title) {
            var now = new Date();
            var dia = now.getDate();
            var mes = now.getMonth();
            var ano = now.getFullYear();
            var hora = now.getHours();
            var min = now.getMinutes();
            var seg = now.getSeconds();

            var texto = title + " - Show Tecnologia " + dia + "-" + (parseInt(mes) + 1) + "-" + ano + "_" + hora + "-" + min + "-" + seg;

            return texto;
        };

        //Salva o idioma para geral no javascript
        var idioma = "<?= $idioma ?>";
    </script>

    <!-- Carregando socket.io -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.5/socket.io.js" integrity="sha512-luMnTJZ7oEchNDZAtQhgjomP1eZefnl82ruTH/3Oj/Yu5qYtwL7+dVRccACS/Snp1lFXq188XFipHKYE75IaQQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        body {
            position: relative;
            min-height: 100%;
            background-color: #EFF2F8;
        }

        #menu-sidebar {
            position: absolute;
            width: 45px;
            height: 100%;
            min-height: 100vh;
            background-color: #1C69AD;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .icon-container {
            margin: 5px 0;
        }

        #container_home {
            margin-left: 40px;
            margin-top: 60px
        }

        .custom-navbar {
            position: fixed;
            background-color: #EFF2F8;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            width: 100vw;
            top: 0;
            z-index: 1000 !important;
        }

        .custom-navbar:after {
            content: "";
            position: absolute;
            border-bottom: 5px solid #1C69AD;
            width: 100%;
            left: 0;
            bottom: 0;
        }

        .custom-navbar::before {
            content: "";
            position: absolute;
            border-bottom: 1px solid #7F7F7F;
            width: 100%;
            left: 0;
            top: 2px;
        }

        .custom-logo {
            width: 140px;
            height: auto;
            margin-left: 30px;
        }

        .custom-nav {
            margin-left: auto;
            align-items: center;
            margin-right: 30px;
            margin-top: 5px;
        }

        .custom-nav li {
            display: inline-block;
        }

        .custom-dropdown-toggle {
            position: relative;
        }

        .custom-label-count {
            background-color: red;
            color: #fff;
            border-radius: 50%;
            padding: 1px 5px;
            position: absolute;
            top: -10px;
            right: 5px;
        }

        .custom-icon {
            width: 25px;
            height: 25px;
        }

        #toggle-navbar {
            display: block;
        }

        .input-group-addon input.form-control {
            box-shadow: none;
            border-color: #0357A2;

        }

        /* scroll personalizado */
        .div-scroll {
            overflow: hidden;
        }

        .div-scroll:hover {
            overflow-y: scroll;
        }

        ::-webkit-scrollbar-track {
            background-color: #F4F4F4;
        }

        ::-webkit-scrollbar {
            width: 9px;
            background: #F4F4F4;
        }

        ::-webkit-scrollbar-thumb {
            background: #dad7d7;
        }

        .row-menu {
            max-width: 200% !important;
        }

        input.menu-filter:valid {
            box-shadow: none;
            border-color: #0357A2 !important;
        }

        #leftsidebar {
            position: fixed;
        }

        .footer {
            flex-shrink: 0;
            width: 100%;
            text-align: center;
            z-index: 1000;
            color: #EFF2F8;
            position: relative;
            bottom: 70px;
            margin-top: 90px;
        }

        .footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        .footer-images {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .footer-img {
            width: 6rem;
            height: auto;
        }

        #loadingHomePage {
            display: none;
            z-index: 9999;
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: 0px;
            top: 0px;
            background: rgba(0, 0, 0, 0.58);
        }

        .loaderHomePage {
            position: absolute;
            top: 40%;
            left: 45%;
            transform: translate(-50%, -50%);
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }
    </style>

    <script>
        $(document).ready(function() {
            let flag = false;

            $('#leftsidebar').on('mouseenter', function() {
                $(document.activeElement).blur();
                $('.select2-container--open').each(function() {
                    $(this).prev('select').select2('close');
                });
                flag = true;
                setTimeout(() => {
                    if (flag) {
                        $(".sidebar .menu-text").stop(true, true).fadeIn(280);
                    }
                }, 280);
            });

            $('#leftsidebar').on('mouseleave', function() {
                flag = false;
                $('#leftsidebar').scrollTop(0);
                $(".sidebar .menu-text").stop(true, true).css('display', 'none');
                $('#feature-search-input').blur();
                $(".toggled").click();

            });

            $('#leftsidebar').on('wheel', function(event) {
                event.preventDefault();

                var posicaoY = event.originalEvent.deltaY;

                var leftsidebar = document.getElementById('leftsidebar');
                leftsidebar.scrollTop += posicaoY;

                var maxScroll = leftsidebar.scrollHeight - leftsidebar.clientHeight;
                if (leftsidebar.scrollTop < 0) {
                    leftsidebar.scrollTop = 0;
                } else if (leftsidebar.scrollTop > maxScroll) {
                    leftsidebar.scrollTop = maxScroll;
                }
            });

            $(window).scroll(function() {
                // Fecha todos os Select2 quando ocorrer um scroll
                $('.select2-container--open').each(function () {
                    var $select2 = $(this).prev('select');
                    $select2.select2('close');
                });
            });
        });
    </script>

    <div id="alertas-notification" style="z-index: 99999999999; margin-left: 60px; position: fixed;"></div>
</head>

<body class="theme-red ls-closed">

    <!-- Importa o modal de discagem -->
    <?php if($this->auth->is_allowed_block('vis_discador_atendimento_omnilink')): ?>
        <?php include_once("application/views/atendimento_omnilink/discador/discador.php"); ?>
    <?php endif; ?>

    <?php include_once("application/views/movidesk/aberturaTicket.php"); ?>
    

    <!-- Modals -->
    <div id="divPerfilUsuario"></div>

    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p><? //= lang('carregando')
                ?></p>
        </div>
    </div> -->

    <!-- Loading -->
    <div id="loadingHomePage">
        <div class="loaderHomePage"></div>
    </div>

    <!-- Top Bar -->
    <div class="custom-navbar">
        <div class="custom-navbar-header">
            <a href="<?= site_url('Homes') ?>" class="custom-navbar-brand">
                <img class="custom-logo" src="<?= base_url('media/img/new_icons/shownet_logo_azul.png') ?>" alt="Shownet Logo" />
            </a>
        </div>
        <ul class="custom-nav">
            <!-- Notifications -->
            <?php if($this->auth->is_allowed_block('vis_discador_atendimento_omnilink')): ?>
                <li class="dropdown">
                    <button type="button" class="item-notificacao-menu-header butao-notificacao-menu-header" data-toggle="modal" data-target="#modalDiscador">
                        <img 
                            src="<?= base_url('media/img/new_icons/omnicom/Icon-disc-msg.svg') ?>" 
                            alt="Discador"  
                            style="width: 25px; height: 25px;"
                        >
                    </button>
                </li>
            <?php endif; ?>

            <li class="dropdown">
                    <button type="button" class="item-notificacao-menu-header butao-notificacao-menu-header" data-toggle="modal" data-target="#modalMovidesk" title="Abrir Ticket" id="btnMovidesk">
                        <img 
                            src="<?= base_url('media/img/new_icons/omnicom/icon-add-ticket.svg') ?>" 
                            alt="Ticket"  
                            style="width: 22px; height: 22px;"
                        >
                    </button>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <img src="<?= base_url('media/img/new_icons/notificacao.png') ?>" alt="Ícone de notificação" style="width: 32px; height: 32px; margin-top: 7px">
                    <span class="label-count" id="count_notify">0</span>
                </a>
            </li>
            <!-- #END# Notifications -->

            <!-- User -->
            <li style="margin-left: 35px;">
                <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <img src="<?= base_url('media/img/new_icons/perfil.png') ?>" alt="Ícone de perfil" style="width: 25px; height: 25px;" class="material-icons">
                </a>
                <ul class="dropdown-menu pull-right" style="margin-top: -10px!important; background-color: #EFF2F8;">
                    <li><a href="javascript:void(0);" id="exibirPerfilusuario" onclick="exibirPerfilUsuario()"><i class="material-icons">person</i><?= lang('perfil') ?></a></li>
                    <li><a href="<?= site_url('acesso/sair/admin') ?>"><i class="material-icons">input</i>Sair</a></li>
                </ul>
            </li>
            <!-- #END# User -->
        </ul>
    </div>

    <!-- #Top Bar -->
    <!-- #Menu -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" style='z-index: 1000 !important;' class="sidebar sidebar-small">

            <a href="javascript:void(0);" class="bars" id='toggle-navbar'></a>

            <!-- Menu -->
            <div class="menu scrollbar" style="position: relative;">

                <ul class='list'>
                    <li>
                        <!-- Busca Funcionalidades -->
                        <div class='container' style='width:100% !important'>
                            <div class="row row-menu">
                                <br>
                                <form>
                                    <span class="input-group-addon" style="background-image: url('<?= base_url('media/img/new_icons/pesquisar.png') ?>'); background-size: 35px; background-repeat: no-repeat; background-position: left center; padding-left: 30px; background-color: #0357A2; border: none; background-position-x: 6px">
                                        <input autocomplete="off" type="text" class="form-control menu-filter" id="feature-search-input" aria-describedby="feature-search" placeholder="Pesquisar">
                                    </span>
                                    <script>
                                        $('#feature-search-input').on('input', () => {
                                            filterMenu($('#feature-search-input').val())
                                        });
                                    </script>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- Lista de menus -->
                <ul class="list" id='sidebar-items'>
                    <?php $this->load->view('fix/menu_NS/load'); ?>
                </ul>
                <!-- Footer -->
                <div style="width: 100%; position: relative; text-align: center;">
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
                </div>
                <!-- #Footer --->

            </div>
            <!-- #Menu -->

        </aside>
        <!-- #END# Left Sidebar -->
        <!-- #END# Right Sidebar -->
    </section>
    <!-- #Menu -->
    </div>

    <!-- Conteúdo da página -->
    <section class="content">

        <!-- Separação do body -->
        <div id="container_home" class="container-fluid container-main">