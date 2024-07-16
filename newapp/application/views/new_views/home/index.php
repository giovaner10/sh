<link rel="stylesheet" type="text/css" href="<?= versionFile('media/css', 'new_home.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="text-title" style="margin-bottom: 10px;">
    <h3 style="padding: 0 20px;"><?= lang("bem_vindo") ?></h3>
    <h4 style="padding: 0 20px;"><?= lang("tela_inicial_shownet") ?></h4>
</div>
<hr style="margin: 0 20px; border-top: 1px solid #d6d8de;">

<div id="acessoRapidoDiv"></div>

<div class="col-md-12" style="margin: 15px 0 0 0; padding: 0 20px;">
    <div id="menuBar">
        <div class="menu-item active" id="menu-inicio">
            Início 
        </div>
        <?php
        $log_admin = $this->session->userdata('log_admin');
        $idioma = isset($log_admin['idioma']) ? $log_admin['idioma'] : null;
        $menus = $this->menu->getComHierarquia();

        // Verificar se a função já foi declarada
        if (!function_exists('compareMenus')) {
            // Função de comparação para ordenar os menus por nome
            function compareMenus($a, $b)
            {
                $menuNomeA = getMenuNome($a);
                $menuNomeB = getMenuNome($b);

                if ($menuNomeA == 'Início') {
                    return -1;
                }

                if ($menuNomeB == 'Início') {
                    return 1;
                }

                return strcasecmp($menuNomeA, $menuNomeB);
            }
        }

        // Ordenar os menus por nome
        usort($menus, 'compareMenus');

        foreach ($menus as $menu) :
            $menu_nome = getMenuNome($menu);
            if (in_array($menu_nome, ['Cadastros', 'Departamentos', 'Relatórios', 'Monitoramento', 'Suporte', 'Configurações'])) : ?>
                <?php if (count($menu['sub_menus']) > 0) : ?>
                    <!-- Menu Pai com Submenus -->
                    <div class="menu-item with-submenu" onclick="toggleMenuItem('<?= $menu_nome ?>Menu', event)">
                        <?= $menu_nome ?> <i class="fa fa-caret-down"></i>
                        <div class="submenu" id="<?= $menu_nome ?>Menu">
                            <?php
                            usort($menu['sub_menus'], 'compareMenus');
                            ?>
                            <?php foreach ($menu['sub_menus'] as $submenu) : ?>
                                <?php if (count($submenu['sub_menus']) == 0) : ?>
                                    <?php $menu_nome = getMenuNome($submenu); ?>
                                    <a class="menu-item-select" href="<?= site_url($submenu['caminho']) ?>">
                                        <?= $menu_nome ?>
                                    </a>
                                <?php else : ?>
                                    <!-- Submenu com submenus -->
                                    <a class="menu-item-select" onclick="toggleSubMenu('<?= $submenu['codigo_permissao'] ?>Menu', event)">
                                        <?= getMenuNome($submenu) ?>
                                        <i class="fa fa-caret-right" style="position: absolute; right: 0; margin-right: 10px; margin-top: 3px"></i>
                                    </a>
                                    <div class="subsubmenu" id="<?= $submenu['codigo_permissao'] ?>Menu">
                                        <?php
                                        usort($submenu['sub_menus'], 'compareMenus');
                                        ?>
                                        <?php foreach ($submenu['sub_menus'] as $subsubmenu) : ?>
                                            <?php if (count($subsubmenu['sub_menus']) == 0) : ?>
                                                <?php $menu_nome = getMenuNome($subsubmenu); ?>
                                                <a class="menu-item-select" href="<?= site_url($subsubmenu['caminho']) ?>">
                                                    <?= $menu_nome ?>
                                                </a>
                                            <?php else : ?>
                                                <a class="menu-item-select" onclick="toggleSubsubMenu('<?= $subsubmenu['codigo_permissao'] ?>Menu', event)">
                                                    <?= lang($subsubmenu['nome']) ?>
                                                    <i class="fa fa-caret-right" style="position: absolute; right: 0; margin-right: 10px; margin-top: 3px"></i>
                                                </a>
                                                <div class="subsubsubmenu" id="<?= $subsubmenu['codigo_permissao'] ?>Menu">
                                                    <?php
                                                    usort($subsubmenu['sub_menus'], 'compareMenus');
                                                    ?>
                                                    <?php foreach ($subsubmenu['sub_menus'] as $subsubsubmenu) : ?>
                                                        <?php $menu_nome = getMenuNome($subsubsubmenu); ?>
                                                        <a class="menu-item-select" href="<?= site_url($subsubsubmenu['caminho']) ?>">
                                                            <?= $menu_nome ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php elseif ($menu['caminho']) : ?>
                    <!-- Menu Simples -->
                    <div class="menu-item">
                        <a href="<?= site_url($menu['caminho']) ?>">
                            <?= $menu_nome ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Acesso rápido - Cards -->
<div class="col-md-12" style="margin: 15px 0 0 0; padding: 0 20px;">
    <div class="acesso-rapido-container">
        <h4 style="padding: 10px 5px;"><?= lang('acesso_rapido') ?>
        <span style="float: right;">
            <button class="btn" id="acessoRapidoBotao" style="position: relative;
                top: -2px; margin-right: 15px; padding: 0px 5px 3px 6px; margin-bottom: -7px; background-color: #D4EDFE;">
                <i class="fa fa-pencil"></i>
            </button>
        </span>
        </h4>
        <div class="card-container">
            <?php if (!empty($atalhosUsuario) && count($atalhosUsuario) > 0): ?>
                <?php foreach ($atalhosUsuario as $atalhoUsuario) : ?>
                    <a class="card-acesso" href="<?= $atalhoUsuario->menuCaminho ?>">
                        <i class="material-icons material-icons-atalho">
                            <?=$atalhoUsuario->menuIcone?>
                        </i>
                        <!-- <img src="<?= base_url('media/img/new_icons/acesso_rapido_clientes.png'); ?>" alt="<?= $atalhoUsuario->menuNome?>"> -->
                        <b><?= $atalhoUsuario->menuNome?></b>
                    </a>
                <?php endforeach;?>
            <?php endif; ?>
        </div>  
    </div>
</div>

<!-- Banners -->
<div class="col-md-8 equal-height-column column-responsive" style="padding: 0px 15px 0px 20px;">
    <div class="row" style="margin: 15px 0 0 0;">
        <div class="acesso-rapido-container">
            <h4 style="padding: 10px 5px;"><?= lang('ultimas_noticias') ?></h4>
            <div class="row" style="margin-right: -30px; padding-bottom: 20px; padding-right: 15px;">
                <?php if (empty($banners) || count($banners) == 0) : ?>
                    <img src="<?= base_url('media/img/shownet-noticias.jpg') ?>" class="img-responsive" style="width: 100%">
                    <hr style="margin: 30px -30px 20px -15px;">
                <?php else : ?>
                    <div id="img-noticias" class="col-md-7 col-sm-7" style="padding-right: 10px;">
                        <img src="<?= base_url('media/img/shownet-noticias.jpg') ?>" class="img-responsive" style="width: 100%;">
                    </div>
                    <div id="col-noticias" class="col-md-5 col-sm-5">
                        <div class="row" style="padding: 0; padding-bottom: 5px; height: 50%;">
                            <div class="col-md-4 col-sm-4 square-container" style="padding: 0px;">
                                <img src="<?= base_url('media/img/noticias/noticia-1.jpg') ?>" class="img-responsive" style="padding: 0px; width: 100%;" />
                            </div>
                            <div class="col-md-8 col-sm-8 corpo-noticia">
                                <b class="texto-noticia titulo-noticia" style="max-height: 40%;">
                                    A Omnilink celebra o Dia do Motorista
                                </b>
                                <p class="texto-noticia" style="text-align: justify; margin-bottom: 0; max-height: 50%;">
                                    No dia 25 de julho é celebrado o dia do motorista, instituído no país em 21 de outubro de 1968.
                                </p>
                                <!-- <a class="saiba-mais" href="" style="height: 10%;">
                                    Saiba Mais
                                </a> -->
                            </div>
                        </div>
                        <div class="row" style="padding: 0; padding-top: 5px; height: 50%;">
                            <div class="col-md-4 col-sm-4 square-container" style="padding: 0px;">
                                <img src="<?= base_url('media/img/noticias/noticia-2.jpg') ?>" class="img-responsive" style="padding: 0px; width: 100%;" />
                            </div>
                            <div class="col-md-8 col-sm-8 corpo-noticia">
                                <b class="texto-noticia titulo-noticia" style="max-height: 40%;">
                                    O poder de uma gestão inteligente
                                </b>
                                <p class="texto-noticia" style="text-align: justify; margin-bottom: 0; max-height: 50%;">
                                    Com o Omnifrota você terá o acompanhamento em tempo real dos veículos da frota.
                                </p>
                                <!-- <a class="saiba-mais" href="" style="height: 10%;">
                                    Saiba Mais
                                </a> -->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <!-- Aniversariantes -->
    <div class="row" style="margin: 15px 0 0 0;">
        <div class="acesso-rapido-container" style="padding-bottom: 10px;">
            <h4 style="padding: 10px 5px;">
                <?= lang('aniversariantes') ?>
            </h4>
            <?php if (count($aniversariantes) >= 1) : ?>
                <div class="container div-scroll-horizontal" style="padding: 0; width: 100%;">
                    <?php $i = 0;
                    foreach ($aniversariantes as $aniversariante) : ?>
                        <div class="aniversariante">
                            <div class="panel">
                                <div class="panel-body">
                                    <div style="width: 65%;">
                                        <?php
                                        $nomeCompleto = $aniversariante->nome;
                                        $nomes = array_map('ucfirst', array_map('strtolower', explode(' ', $nomeCompleto)));

                                        $primeiroNome = $nomes[0];
                                        $segundoNome = isset($nomes[1]) ? " {$nomes[1]}" : "";
                                        ?>
                                        <b class="nomeAniversariante" style="font-size: 17px; color: #1C69AD;"><?= $primeiroNome . $segundoNome ?></b>
                                        <?php
                                        $ocupacao = $aniversariante->ocupacao;
                                        $ocupacao = array_map('ucfirst', array_map('strtolower', explode(' ', $ocupacao)));
                                        $ocupacao = implode(' ', $ocupacao);
                                        ?>
                                        <p class="ocupacaoAniversariante" style="font-size: 10px; padding: 0px; color: #348AD6; margin-bottom: 0px;"><?= $ocupacao ? $ocupacao : 'Sem cargo' ?></p>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <span style="color: #1C69AD !important; font-size: 13px;"><?= data_formatada($aniversariante->data_nasc, false) ?></span>
                                </div>
                                <div class="image-container">
                                    <img src="<?= isset($aniversariante->file) ? base_url('uploads/perfil_usuarios' . '/' . $aniversariante->file) : base_url('media/img/perfil-usuarios/perfil-sem-foto.png') ?>" class="img-responsive img-aniversariante">
                                </div>
                            </div>
                        </div>
                    <?php $i++;
                    endforeach; ?>
                </div>
            <?php else : ?>
                <div class="col-12-md col-12-sm text-center" style="margin-bottom: 20px;">
                    <span style="color: #1C69AD !important;"><b>Sem aniversariantes hoje!</b></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Comunicados -->
<div id="listaComunicados" class="col-md-4 equal-height-column column-responsive" style="padding: 15px 20px 0px 0px;">
    <div class="acesso-rapido-container" style="height: 100% !important; padding-bottom: 15px;">
        <h4 style="padding: 10px 5px;"><?= lang('comunicados') ?></h4>
        <!-- Lista de comunicados -->
        <div class="div-scroll" style="padding-top: 5px;">
            <?php foreach ($comunicados as $indice => $comunicado) : ?>
                <a class="comunicado-item" href="<?= base_url('uploads/comunicados') . '/' . $comunicado->file ?>" target="_blank">
                    <div class="panel">
                        <div class="panel-body">
                            <b><?= $comunicado->comunicado ?></b>
                        </div>
                        <div class="panel-footer">
                            <span style="color: #1C69AD !important;"><?= data_formatada($comunicado->data) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<br>

<!-- Release Notes -->
<div id="releaseNotes" class="col-md-12 column-responsive" style="margin-top: 15px; padding: 0px 20px;">
    <div class="acesso-rapido-container" style="padding-bottom: 10px;">
        <h4 style="padding: 10px 5px;"><?= lang('release_notes') ?></h4>
        <!-- Lista de Release notes -->
        <div class="container div-scroll-horizontal" style="padding: 0; width: 100%;">
            <?php foreach ($releases as $indice => $release) : ?>
                <a class="releaseNote" href="<?= base_url('uploads/release_notes') . '/' . $release->file ?>" target="_blank">
                    <div class="panel">
                        <div class="panel-body">
                            <span class="releaseText" style="color: #7F7F7F"><?= $release->release_note ?></span>
                        </div>
                        <div class="panel-footer">
                            <span style="color: #7F7F7F !important; font-size: 13px; font-weight: bold"><?= data_formatada($release->data) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    let openMenuItem = null;
    let openSubmenuItem = null;
    let openSubsubmenuItem = null;
    let menuId = null;

    function closeMenu(menu) {
        menu.style.display = 'none';
        const submenus = menu.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            submenu.style.display = 'none';
        });
        const subsubmenus = menu.querySelectorAll('.subsubsubmenu');
        subsubmenus.forEach(subsubmenu => {
            subsubmenu.style.display = 'none';
        });
    }

    function toggleMenuItem(menuItemId, event) {
        event.stopPropagation();

        const menuItem = document.getElementById(menuItemId);
        menuId = menuItemId;

        if (menuItem) {
            if (openMenuItem && openMenuItem !== menuItem) {
                closeMenu(openMenuItem);
            }

            menuItem.style.display = menuItem.style.display === 'block' ? 'none' : 'block';
            openMenuItem = menuItem;

            // Seleciona todos os elementos com a classe 'menu-item'
            var menuItems = document.querySelectorAll('.menu-item');

            // Itera sobre cada elemento selecionado
            menuItems.forEach(function(item) {
                if (item.lastElementChild && item.lastElementChild.style.display === 'block') {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });


            if (menuItem.style.display == 'none') {
                document.getElementById('menu-inicio').classList.add('active');
            }

            if (openSubmenuItem) {
                closeMenu(openSubmenuItem);
                openSubmenuItem = null;
            }
        }
    }

    function toggleSubMenu(submenuId, event) {
        event.stopPropagation();

        const submenu = document.getElementById(submenuId);

        if (submenu) {
            if (openSubmenuItem && openSubmenuItem !== submenu) {
                closeMenu(openSubmenuItem);
            }

            if (menuId === 'SuporteMenu' || menuId === 'RelatóriosMenu') {
                submenu.style.left = '0';
                const submenus = submenu.querySelectorAll('.submenu');
                submenus.forEach(submenu => {
                    submenu.style.left = '0';
                });
            }

            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            openSubmenuItem = submenu;
        }
    }

    function toggleSubsubMenu(subsubsubmenuId, event) {
        event.stopPropagation();

        const subsubsubmenu = document.getElementById(subsubsubmenuId);

        if (subsubsubmenu) {
            if (openSubsubmenuItem && openSubsubmenuItem !== subsubsubmenu) {
                closeMenu(openSubsubmenuItem);
            }

            subsubsubmenu.style.display = subsubsubmenu.style.display === 'block' ? 'none' : 'block';
            openSubsubmenuItem = subsubsubmenu;
        }
    }

    $(window).on('load resize', function() {
        let h = parseInt($('#img-noticias').height());
        //mantem as colunas com a mesma altura
        if (parseInt($(window).width()) >= 768) {
            // Mantém as colunas com a mesma altura
            $('#col-noticias').height(h);
        } else {
            $('#col-noticias').height('auto');
        }
    });

    document.body.addEventListener('click', function(event) {
        if (!event.target.closest('.menu-item.with-submenu') && !event.target.closest('.submenu')) {
            document.querySelectorAll('.menu-item').forEach(function(item) {
                item.classList.remove('active');
            });
            document.getElementById('menu-inicio').classList.add('active');
            if (openMenuItem) {
                openMenuItem.style.display = 'none';
                const submenus = openMenuItem.querySelectorAll('.submenu');
                submenus.forEach(submenu => {
                    submenu.style.display = 'none';
                });
                openMenuItem = null;
                menuId = null;
            }

            if (openSubmenuItem) {
                openSubmenuItem.style.display = 'none';
                const submenus = openSubmenuItem.querySelectorAll('.submenu');
                submenus.forEach(submenu => {
                    submenu.style.display = 'none';
                });
                openSubmenuItem = null;
            }
        }
    });

    // Função para ajustar a altura da segunda coluna
    function ajustarAlturaSegundaColuna() {
        var alturaPrimeiraColuna = $('.equal-height-column').eq(0).height();

        if (parseInt($(window).width()) >= 768) {
            // Definir a altura da segunda coluna para corresponder à altura da primeira
            $('.equal-height-column').eq(1).height(alturaPrimeiraColuna - 15);
        } else {
            $('.equal-height-column').eq(1).height('300px');
        }

    }

    $(document).ready(function() {

        // Executar a função ao carregar a página
        ajustarAlturaSegundaColuna();

        // Executar a função ao redimensionar a janela
        $(window).resize(function() {
            ajustarAlturaSegundaColuna();
        });

        // Inicializar o tooltip, se necessário
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#acessoRapidoBotao').on('click', function() {
        $("#acessoRapidoBotao")
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        $("#acessoRapidoDiv").load(
            "<?= site_url('Homes/acessoRapidoModal') ?>", {},
            function() {
                $("#acessoRapidoBotao")
                    .html('<i class="fa fa-pencil"></i>')
                    .attr('disabled', false);
            }
        );
    });
</script>
