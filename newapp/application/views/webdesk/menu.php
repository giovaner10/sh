<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css', 'layout.css') ?>">
<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css', 'menu_style.css') ?>">

<?php 

    $omnilink = $this->auth->get_login_dados('funcao');
    // permissões

    $icone_cadeado = '<div class="fa fa-lock icon_cadeado"></div>';
    $icone_seta = '<div class="fa fa-caret-down right icon_seta"></div>';
    $possui_permissao_registro_chamadas = $this->auth->is_allowed_block('vis_registro_chamadas_atendimento_omnilink');
    $possui_permissao_ligacoes_fila = $this->auth->is_allowed_block('vis_ligacoes_filas_atendimento_omnilink');

?>
  
<div class="d-flex justify-content-between">
  <h4 class="card_titulo"><?=lang('menu')?></h4>
</div>

<div id="menu-interno">
  <nav>
    <ul>
      <li>
        <a id="menu-show-norio" href="#" data-page="show_norio" class="active">Show / Norio <?= !$possui_permissao_registro_chamadas ? $icone_cadeado : '' ?></a>
      </li>

      <!-- submenu de solicitacoes -->
      <li class='sub-menu'>
        <a id='menu-principal-omnilink'>Omnilink <?= !$possui_permissao_registro_chamadas ? $icone_cadeado : $icone_seta ?></a>
        <ul>
          <li class="sub-itens-menu-omniscore">
            <a id="menu-omnilink" href="#" data-page="omnilink">Fila de clientes</a>
            <a id="menu-registro-chamadas" data-page="registro_chamadas">Registro de Chamadas <?= !$possui_permissao_registro_chamadas ? $icone_cadeado : '' ?></a>
            <a id="menu-fila-ligacoes" data-page="fila_ligacoes">Fila de Ligações <?= !$possui_permissao_ligacoes_fila ? $icone_cadeado : '' ?></a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</div>

<script>
	$('.sub-menu ul').hide();
	$(".sub-menu a").click(function () {
		$(this).parent(".sub-menu").children("ul").slideToggle("100");
		$(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
	});
</script>
