
<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css', 'menu_style.css') ?>">

<?php
  $icone_cadeado = '<div class="fa fa-lock icon_cadeado"></div>';
  $icone_seta = '<div class="fa fa-caret-down right icon_seta"></div>';
  // permissões
  $possui_permissao_filas = $this->auth->is_allowed_block('vis_filas_atendimento_omnilink');
?>


<div class="d-flex justify-content-between">
  <h4 class="card_titulo"><?=lang('menu')?></h4>
</div>
<div id="menu-interno">
  <nav>
    <ul>
      <li>
        <a 
          href="<?= site_url('AtendimentoOmnilink/Filas')?>" class="<?= !$possui_permissao_filas ? 'btn_disabled' : '' ?> 
          <?= $this->router->fetch_class() == 'Filas' ? 'active' : '' ?>">
          Filas
          <?= !$possui_permissao_filas ? $icone_cadeado : '' ?>
        </a>
      </li>
    </ul>
  </nav>
</div>