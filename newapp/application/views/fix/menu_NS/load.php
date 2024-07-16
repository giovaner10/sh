<?php
$log_admin = $this->session->userdata('log_admin');
$idioma = isset($log_admin['idioma']) ? $log_admin['idioma'] : null;
$GLOBALS['idioma'] = $idioma;
?>
<?php if ($this->menu->verificaPermissao() || true) : ?>
    <?php
    $menus = $this->menu->getComHierarquia();

    function getMenuNome($menu){
        global $idioma;
        $menu_nome = lang($menu['nome']);
        if (!isset($menu_nome) || $menu_nome == "") {
            $menu_nome = $idioma == "pt-BR" ? $menu['lang_pt'] : $menu['lang_en'];
        }
        return trim($menu_nome);
    }

    // Função de comparação para ordenar os menus por nome
    function compareMenus($a, $b) {
        
        if($a['nome'] == 'tela_inicial'){
            return -1;
        }
        if($b['nome'] == 'tela_inicial'){
            return 1;
        }

        return strcasecmp(getMenuNome($a), getMenuNome($b));
    }

    // // Ordenar os menus por nome
    usort($menus, 'compareMenus');

    foreach ($menus as $menu) :
        $menu_nome = getMenuNome($menu);
        ?>
        <li>
            <?php if (count($menu['sub_menus']) > 0) : ?>
                <!-- Menu Pai -->
                <style>
                    .custom-menu-toggle a {
                        display: inline-block;
                        background-color: transparent;
                        color: #EFF2F8;
                        padding: 4px 8px;
                        text-decoration: none;
                        transition: background-color 0.3s;
                    }

                    .custom-menu-toggle a:hover {
                        background-color: #348AD6;
                    }
                </style>
                <div class="custom-menu-toggle">
                    <a href="javascript:void(0);" class="menu-toggle" style="color:#EFF2F8">
                        <?php
                        switch ($menu_nome):
                            case 'Tela Inicial':
                                $iconeSrc = base_url('media/img/new_icons/home.png');
                                $iconeAlt = 'Tela Inicial';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'A Empresa':
                                $iconeSrc = base_url('media/img/new_icons/omnilink_lampada.png');
                                $iconeAlt = 'A Empresa';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Atendimento':
                                $iconeSrc = base_url('media/img/new_icons/omnilink_lampada.png');
                                $iconeAlt = 'Atendimento';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'BI':
                                $iconeSrc = base_url('media/img/new_icons/bi.png');
                                $iconeAlt = 'BI';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Cadastros':
                                $iconeSrc = base_url('media/img/new_icons/cadastros.png');
                                $iconeAlt = 'Cadastros';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Configurações':
                                $iconeSrc = base_url('media/img/new_icons/configuracoes.png');
                                $iconeAlt = 'Configurações';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Departamentos':
                                $iconeSrc = base_url('media/img/new_icons/departamentos.png');
                                $iconeAlt = 'Departamentos';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Iscas':
                                $iconeSrc = base_url('media/img/new_icons/iscas.png');
                                $iconeAlt = 'Iscas';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Logística':
                                $iconeSrc = base_url('media/img/new_icons/logistica.png');
                                $iconeAlt = 'Logística';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Monitoramento':
                                $iconeSrc = base_url('media/img/new_icons/monitoramento.png');
                                $iconeAlt = 'Monitoramento';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'OCR':
                                $iconeSrc = base_url('media/img/new_icons/monitoramento.png');
                                $iconeAlt = 'OCR';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Omnicom':
                                $iconeSrc = base_url('media/img/new_icons/monitoramento.png');
                                $iconeAlt = 'Monitoramento';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'configurador_omnisafe':
                                $iconeSrc = base_url('media/img/new_icons/monitoramento.png');
                                $iconeAlt = 'Omnisafe';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'OmniScore':
                                $iconeSrc = base_url('media/img/new_icons/omniscore.png');
                                $iconeAlt = 'OmniScore';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'portal_compras':
                                $iconeSrc = base_url('media/img/new_icons/portal_compras.png');
                                $iconeAlt = 'Portal de Compras';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'PCP (Planejamento e Controle da Produção)':
                                $iconeSrc = base_url('media/img/new_icons/pcp.png');
                                $iconeAlt = 'PCP';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Relatórios':
                                $iconeSrc = base_url('media/img/new_icons/relatorios.png');
                                $iconeAlt = 'Relatórios';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Suporte':
                                $iconeSrc = base_url('media/img/new_icons/suporte.png');
                                $iconeAlt = 'Suporte';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Telecom':
                                $iconeSrc = base_url('media/img/new_icons/telecom.png');
                                $iconeAlt = 'Telecom';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Vendas Gestor':
                                $iconeSrc = base_url('media/img/new_icons/logistica.png');
                                $iconeAlt = 'Vendas Gestor';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Auditoria':
                                $iconeSrc = base_url('media/img/new_icons/iscas.png');
                                $iconeAlt = 'Auditoria';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Processos de Atendimento':
                                $iconeSrc = base_url('media/img/new_icons/bi.png');
                                $iconeAlt = 'Processos de Atendimento';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Televendas':
                                $iconeSrc = base_url('media/img/new_icons/omniscore.png');
                                $iconeAlt = 'Televendas';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Autorização de Compras':
                                $iconeSrc = base_url('media/img/new_icons/portal_compras.png');
                                $iconeAlt = 'Processos de Atendimento';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Firmware':
                                $iconeSrc = base_url('media/img/new_icons/iscas.png');
                                $iconeAlt = 'Firmware';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                            case 'Nexxera':
                                $iconeSrc = base_url('media/img/new_icons/logistica.png');
                                $iconeAlt = 'Nexxera';
                                $iconeWidth = 35;
                                $iconeHeight = 35;
                                break;
                        endswitch;
                        ?>
                        <img src="<?= $iconeSrc ?>" alt="<?= $iconeAlt ?>" width="<?= $iconeWidth ?>" height="<?= $iconeHeight ?>">
                        <span class="sidecolor menu-text"><?= $menu_nome ?></span>
                    </a>

                    <!-- Menus Filhos -->
                    <ul class="ml-menu">
                        <?php $this->load->view('fix/menu_NS/sub_menus', ['menus' => $menu['sub_menus']]); ?>
                    </ul>
                </div>
            <?php elseif ($menu['caminho']) : ?>
                <style>
                    .custom-menu-toggle a {
                        display: inline-block;
                        background-color: transparent;
                        color: #EFF2F8;
                        padding: 4px 8px;
                        text-decoration: none;
                        transition: background-color 0.3s;
                    }

                    .custom-menu-toggle a:hover {
                        background-color: #348AD6;
                    }
                </style>
                <?php
                switch ($menu['nome']):
                    case 'portal_compras':
                        $iconeSrc = base_url('media/img/new_icons/portal_compras.png');
                        $iconeAlt = 'Portal de Compras';
                        $iconeWidth = 35;
                        $iconeHeight = 35;
                        break;
                    case 'Painel de Ativação':
                        $iconeSrc = base_url('media/img/new_icons/bi.png');
                        $iconeAlt = 'Painel de Ativação';
                        $iconeWidth = 35;
                        $iconeHeight = 35;
                        break;
                    default:
                        $iconeSrc = base_url('media/img/new_icons/home.png');
                        $iconeAlt = $menu_nome;
                        $iconeWidth = 35;
                        $iconeHeight = 35;
                        break;
                endswitch;
                ?>
                <!-- Menu -->
                <div class="custom-menu-toggle">
                    <a href="<?= site_url($menu['caminho']) ?>">
                        <img src="<?= $iconeSrc ?>" alt="<?= $iconeAlt ?>" width="<?= $iconeWidth ?>" height="<?= $iconeHeight ?>">
                        <span class="sidecolor menu-text"><?= $menu_nome ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
<?php else : ?>
    <script>
        alert('Você não tem acesso aos menus! O seu cadastro está sem cargo definido, solicite a sua chefia imediata para inseri-lo!')
    </script>
<?php endif; ?>
