<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css', 'menu_style.css') ?>">

<?php
  $icone_cadeado = '<div class="fa fa-lock icon_cadeado"></div>';
  $icone_seta = '<div class="fa fa-caret-down right icon_seta"></div>';
  // permissões
  $possui_permissao_visualizar_requisicao = $this->auth->is_allowed_block('vis_solicitacoes_portal_compras');
  $possui_permissao_visualizar_pedidos = $this->auth->is_allowed_block('vis_pedidos_portal_compras');
  $possui_permissao_configuracoes = $this->auth->is_allowed_block('vis_configuracoes_portal_compras');
  $possui_permissao_cadastrar_solicitacoes = $this->auth->is_allowed_block('cad_solicitacoes_portal_compras');
	$pode_cadastrar_solicitacoes = (!empty($funcaoUsuario) && $funcaoUsuario === 'solicitante') && $possui_permissao_cadastrar_solicitacoes;
?>

<div class="d-flex justify-content-between">
  <h4 class="card_titulo"><?=lang('menu')?></h4>
</div>
<div id="menu-interno">
  <nav>
    <ul>
      <!-- submenu de solicitacoes -->
      <li class='sub-menu'>
        <a href="#solicitacoes" class="<?= (!$possui_permissao_visualizar_requisicao && !$possui_permissao_visualizar_pedidos) ? 'btn_disabled' : '' ?> 
          <?= $this->router->fetch_class() == 'Solicitacoes' ? 'active' : '' ?>">
            Solicitações
            <?= (!$possui_permissao_visualizar_requisicao && !$possui_permissao_visualizar_pedidos) ? $icone_cadeado : $icone_seta ?>
        </a>

        <ul>
          <li class="sub-itens-menu-omniscore sub-menu">
						<a href="#cadastar" class="<?= !$pode_cadastrar_solicitacoes ? 'btn_disabled' : '' ?>">
							Cadastrar
							<?= !$pode_cadastrar_solicitacoes ? $icone_cadeado : $icone_seta ?>
						</a>
						
						<ul>
							<li class="sub-itens-menu-omniscore">
								<a href="<?= $pode_cadastrar_solicitacoes ? site_url('PortalCompras/Solicitacoes/cadastrar/requisicao') : ''?>" class="<?= !$pode_cadastrar_solicitacoes ? 'btn_disabled' : '' ?>">
									Requisição
									<?= !$pode_cadastrar_solicitacoes ? $icone_cadeado : '' ?>
								</a>
								<a href="<?= $pode_cadastrar_solicitacoes ? site_url('PortalCompras/Solicitacoes/cadastrar/pedido') : ''?>" class="<?= !$pode_cadastrar_solicitacoes ? 'btn_disabled' : '' ?>">
									Pedido
									<?= !$pode_cadastrar_solicitacoes ? $icone_cadeado : '' ?>
								</a>
							</li>
						</ul>
          </li>

          <li class="sub-itens-menu-omniscore sub-menu">
						<a href="#listar" class="<?= (!$possui_permissao_visualizar_requisicao || !$possui_permissao_visualizar_pedidos) ? 'btn_disabled' : '' ?>">
							Listar
							<?= (!$possui_permissao_visualizar_requisicao || !$possui_permissao_visualizar_pedidos) ? $icone_cadeado : $icone_seta ?>
						</a>

						<ul>
							<li class="sub-itens-menu-omniscore">
								<a href="<?= site_url('PortalCompras/Solicitacoes/index/requisicao')?>" class="<?= !$possui_permissao_visualizar_requisicao ? 'btn_disabled' : '' ?>">
									Requisições
									<?= !$possui_permissao_visualizar_requisicao ? $icone_cadeado : '' ?>
								</a>
								<a href="<?= site_url('PortalCompras/Solicitacoes/index/pedido')?>" class="<?= !$possui_permissao_visualizar_pedidos ? 'btn_disabled' : '' ?>">
									Pedidos
									<?= !$possui_permissao_visualizar_pedidos ? $icone_cadeado : '' ?>
								</a>
							</li>
						</ul>

          </li>
        </ul>
      </li>

			<li>
				<a 
        href="<?= site_url('PortalCompras/Configuracoes') ?>" class="<?= !$possui_permissao_configuracoes ? 'btn_disabled' : '' ?> 
					<?= $this->router->fetch_class() == 'Configuracoes' ? 'active' : '' ?>">
					Configuração
					<?= !$possui_permissao_configuracoes ? $icone_cadeado : '' ?>
				</a>
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

	const funcaoUsuario = "<?= !empty($funcaoUsuario) ? $funcaoUsuario : '' ?>";
	const idUsuario = "<?= !empty($idUsuario) ? $idUsuario : '' ?>";
 </script>
