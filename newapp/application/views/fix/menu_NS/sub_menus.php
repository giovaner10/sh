<?php
$log_admin = $this->session->userdata('log_admin');
$idioma = isset($log_admin['idioma']) ? $log_admin['idioma'] : null;
usort($menus, 'compareMenus');
?>

<?php foreach ($menus as $menu) : ?>
    
    <?php
        $menu_nome = getMenuNome($menu);
    ?>
    <li>
        <?php if (count($menu['sub_menus']) > 0) : ?>

            <!-- Menu Pai -->
            <a href="javascript:void(0);" class="menu-toggle" style="color:#EFF2F8">
                <span class="sidecolor"><?= $menu_nome ?></span>
            </a>
            <!-- Ordenar submenus por nome -->
            <?php
            $subMenus = $menu['sub_menus'];
            usort($subMenus, function ($a, $b) {
                return strcasecmp(lang($a['nome']), lang($b['nome']));
            });
            ?>
            <!-- Menus Filhos ordenados -->
            <ul class="ml-menu">
                <?php $this->load->view('fix/menu_NS/sub_menus', ['menus' => $subMenus]); ?>
            </ul>

        <?php elseif ($menu['caminho']) : ?>

            <!-- Menu Filho (link) -->
            <a href="<?= (site_url($menu['caminho']) . ($menu['link_bi'] ? '?link=' . $menu['link_bi'] : ''))?>">
                <span class="sidecolor">
                    <?= $menu_nome ?>
                </span>
            </a>

        <?php endif; ?>
    </li>
<?php endforeach; ?>
