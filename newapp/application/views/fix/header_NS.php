<!DOCTYPE html>
<html>

<head>
    <title><?=$titulo ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?=base_url('media/img/favicon.png');?>">

    <?php $idioma = $this->auth->get_login_dados('idioma'); ?>

    <!-- ############ Carrega CSS da estrutura ############ -->
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">

        <!-- Estilo geral do sistema -->
        <link rel="stylesheet" type="text/css" href="<?=versionFile('media/css', 'style_NS.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?=versionFile('media/css/themes', 'all-themes.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?=versionFile('media/css', 'scrollbar.css') ?>">

        <!-- CSS Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?=versionFile('media/plugins/bootstrap-3.3.7/dist/css', 'bootstrap.min.css') ?>">
        
        <!-- Material Icons -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

        <!-- CSS DataTable -->
        <link rel="stylesheet" type="text/css" href="<?=versionFile('media/datatable_NS', 'datatables.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?=versionFile('media/datatable_NS', 'datatables.bootstrap.min.css') ?>">
        
        <!-- CSS DataTableResponse mobile -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" />

        <!-- CSS Chosen -->
        <?php if (isset($load) && in_array('select-chosen', $load)) : ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" />
        <?php endif; ?>

        <!-- CSS Jquery UI -->
        <?php if (isset($load) && in_array('jquery-ui', $load)) : ?>
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <?php endif; ?>
        
        <!-- CSS Toastr -->
        <link href="<?= base_url('media/plugins/toastr/build/toastr.css') ?>" rel="stylesheet"/>

        <!-- CSS BootStrap Multiselect -->
        <?php if (isset($load) && in_array('bootstrap-multiselect', $load)) : ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        <?php endif; ?>

    <!-- ################################################## -->


    <!-- ############ JS da estrutura ############# -->

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript"src="<?= versionFile('media/js', 'admin.js') ?>"></script>
        <script src="<?= versionFile('media/plugins/JavaScript-MD5/js', 'md5.min.js') ?>"></script>

        <!-- Jquery UI -->
        <?php if (isset($load) && in_array('jquery-ui', $load)) : ?>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <?php endif; ?> 
        
        
        <!-- Jquery Form -->
        <?php if (isset($load) && in_array('jquery-form', $load)) : ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
        <?php endif; ?>
        
        <!-- AG GRID -->
        <?php if (isset($load) && in_array('ag-grid', $load)) : ?>
            <script type="text/javascript" src="<?= base_url('media/js/ag-grid-enterprise.min.js') ?>"></script>
            <script>
                var TOKEN = "CompanyName=Zatix Tecnologia S/A,LicensedApplication=Omnilink,LicenseType=SingleApplication,LicensedConcurrentDeveloperCount=1,LicensedProductionInstancesCount=1,AssetReference=AG-022406,ExpiryDate=17_November_2022_[v2]_MTY2ODY0MzIwMDAwMA==096777d61698efec6a3ae1015743f3a2";
                agGrid.LicenseManager.setLicenseKey(TOKEN);
            </script>

            <script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_grid-helper.js') ?>"></script>
        <?php endif; ?>
        

        <!-- JS Utils -->
        <script type="text/javascript"src="<?= versionFile('media/js', 'utils.js') ?>"></script>
        
        <!-- JS BootStrap 3.3.7 -->
        <script type="text/javascript"src="<?= versionFile('media/plugins/bootstrap-3.3.7/dist/js', 'bootstrap.js') ?>"></script>
        <script type="text/javascript"src="<?= versionFile('media/js', 'scrollbar.js') ?>"></script>

        <!-- JS DataTable -->
        <script type="text/javascript"src="<?= versionFile('media/datatable_NS', 'datatables.js') ?>"></script>
        <script type="text/javascript"src="<?= versionFile('media/datatable_NS', 'datatables.bootstrap.min.js') ?>"></script>

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
            <script src="<?= base_url('assets/js/jquery.mask.js');?>"></script>
        <?php endif; ?>
        
        <!-- JS BootStrap Multiselect -->
        <?php if (isset($load) && in_array('bootstrap-multiselect', $load)) : ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>

    <!-- ################################################## -->
        
    <!-- BIBLIOTECAS PARA A RESPONSIVIDADE DA DATATABLE -->
    <?php if (isset($load) && in_array('datatable_responsive', $load)) : ?>
        <script type="text/javascript"src="<?= versionFile('assets/js', 'dataTables.responsive.min.js') ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css', 'responsive.dataTables.css') ?>">
    <?php endif; ?>

    <!-- Select2 -->
    <?php if (isset($load) && in_array('select2', $load)) : ?>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- traducoes -->        
        <?php if ($idioma === 'pt-BR'): ?>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
        <?php else: ?>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/<?= $idioma ?>.js"></script>
        <?php endif; ?>
    <?php endif; ?>

    <script type="text/javascript">
        const BASE_URL_API_TELEVENDAS = '<?= config_item('url_api_televendas') ?>';
        const TOKEN_URL_API_TELEVENDAS = `<?= $this->auth->get_login_dados('tokenApiTelevendas') ?>`;
    </script>
    
    <!-- Carrega JS Axios -->
    <script src="<?= base_url('media/js/axios.js'); ?>"></script>
    <!-- Script personalisado para uso geral no sistema - HEADER -->
    <script type="text/javascript"src="<?= versionFile('assets/js/fix', 'header.js') ?>"></script>

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

        // TRADUCAO DE IDIOMAS PARA ARQUIVOS JS
        var languageJSON = <?= json_encode($this->session->userdata('lang')) ?>;
        var lang = JSON.parse(languageJSON);
        var langDatatable = lang.datatable;

        // Filtra menu pelo input de pesquisa
        function filterMenu(filter = '')
        {
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
        function filenameGenerator(title)
        {
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

</head>

<body class="theme-red ls-closed">

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
            <p><?//= lang('carregando')?></p>
        </div>
    </div> -->

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

    <!-- Top Bar -->
    <div>
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                    <a href="javascript:void(0);" class="bars" id='toggle-navbar' style="padding: 15px"></a>
                    <a href="<?=site_url('Homes') ?>" class="navbar-brand"  style="padding-top: 5px !important; margin-left: -10px;">
                        <img class="img-logo" src="<?=base_url('media/img/logo_NS.svg')?>" />                        
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">

                        <!-- Notifications -->
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                <i id="iconNotify" class="material-icons">notifications_none</i>
                                <span class="label-count" id="count_notify">0</span>
                            </a>
                        </li>
                        <!-- #END# Notifications -->

                        <!-- User -->
                        <li>
                            <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">                                
                                <i class="material-icons">account_circle</i>
                                <i style="margin-left: -5px; font-size: 16px; position: relative; top: -2px;"
                                    class="material-icons">keyboard_arrow_down</i>
                            </a>
                            <ul class="dropdown-menu pull-right" style="margin-top: -10px!important;">
                                <li><a href="javascript:void(0);" onclick="exibirPerfilUsuario()"><i class="material-icons">person</i><?=lang('perfil')?></a></li>
                                <li><a href="<?= site_url('acesso/sair/admin') ?>"><i class="material-icons">input</i>Sair</a></li>
                            </ul>
                        </li>
                        <!-- #END# User -->

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!-- #Top Bar -->

    <!-- Menu -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" style='z-index: 100 !important;' class="sidebar">

            <a href="javascript:void(0);" class="bars" id='toggle-navbar' style="color: #fff;"></a>
            <a href="<?=site_url('Homes') ?>">
                <img class="img-logo-branco" src="<?=base_url('media/img/logo_branco_NS.svg')?>" />                        
            </a>
            <!-- Menu -->
            <div class="menu scrollbar" style="overflow-y: hidden">

                <ul class='list'>
                    <li>
                        <!-- Busca Funcionalidades -->
                        <div class='container' style='width:100% !important'>
                            <div class="row">
                                <form>
                                    <input autocomplete='off' type="text" class="form-control menu-filter" id="feature-search-input" aria-describedby="feature-search" placeholder="Pesquise uma opção do menu">
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
                    <?php $this->load->view('fix/menu_NS/load');?>
                </ul>

                <!-- Footer -->
                <div style="width: 100%; position: relative; height: 30%;">
                  <div class="legal" style="position: relative">
                    <div class="copyright ">
                        <p>
                            &copy; <?= date('Y') ?> <b>Show Tecnologia & Omnilink</b>
                        </p>
                        <p>
                            Todos os direitos reservados.
                        </p>
                    </div>
                  </div>
                </div>
                <!-- #Footer -->

            </div>
            <!-- #Menu -->

        </aside>
        <!-- #END# Left Sidebar -->
        <!-- #END# Right Sidebar -->
    </section>
    <!-- #Menu -->

    <!-- Conteúdo da página -->
    <section class="content">

        <!-- Separação do body -->
        <div id="container_home" class="container-fluid container-main">