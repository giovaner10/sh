<div style="padding: 0 20px; margin-left: 15px;">
  <div class="text-title">
		<h3><?= lang('portal_compras') ?></h3>
		<h4>
			<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
			<?= lang('portal_compras') ?> >
			<?= lang('solicitacoes') ?> >
			<?php if($funcaoUsuario === 'area_compras'): ?>
				<?php if($acao === 'adicionar_produto_cotacao'): ?>
					Adicionar Produto e Cotação
				<?php else: ?>
					Adicionar Cotação
				<?php endif; ?>
			<?php else: ?>
				<?php if($acao === 'selecionar_cotacao'): ?>
					Selecionar Cotação
				<?php else: ?>
					<?= !empty($tipoSolicitacao) && $tipoSolicitacao == 'requisicao' ? 'Cadastrar Requisição' : 'Cadastrar Pedido' ?>
				<?php endif; ?>
			<?php endif; ?>
		</h4>
	</div>

  <div class="row">
    <div class="col-md-3" id="menu-lateral">
      <div class="card menu-interno">
        <?php include_once('application/views/portal_compras/menu.php'); ?>
      </div>
    </div>
    <div class="col-md-9" id="conteudo">
      <div class="card-conteudo card-dados-show" style='margin-bottom: 20px; position: relative;'>

				<div id="overlay">
					<div id="overlay-mensagem">
						<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>Carregando...
					</div>
				</div>

				<div class="row col-sm-12 col-md-12 pr-0">
					<h3>
						<b id="titulo-cadatrar-editar"><?= !empty($tipoSolicitacao) && $tipoSolicitacao == 'requisicao' ? 'Cadastrar Requisição' : 'Cadastrar Pedido' ?></b>
						<span id="span-total-solicitacao" style="color: #666 !important; font-size: 16px !important; align-content: end; display: none;">
							<b>Total da Solicitação:</b> <span id="total-solicitacao">R$ 0,00</span>
						</span>
					</h3>
				</div>
					<div class="modal-body row">

						<ul class="nav nav-tabs">
							<li id="li-aba-solicitacao" class="active"><a data-toggle="tab" href="#aba-solicitacao" class="link-desabled required">Dados da Solicitação</a></li>
							<li id="li-aba-produtos"><a data-toggle="tab" href="#aba-produtos" class="link-desabled">Produtos</a></li>
							<li id="li-aba-cotacoes"><a data-toggle="tab" href="#aba-cotacoes" class="link-desabled">Cotações</a></li>
						</ul>

						<div class="tab-content">
							<div id="aba-solicitacao" class="tab-pane fade in active">
								<?php include_once('application/views/portal_compras/solicitacoes/cadastrar_editar/campos_solicitacao.php'); ?>
							</div>
							<div id="aba-produtos" class="tab-pane fade">
								<?php include_once('application/views/portal_compras/solicitacoes/cadastrar_editar/campos_produtos.php'); ?>
							</div>
							<div id="aba-cotacoes" class="tab-pane fade">
								<?php include_once('application/views/portal_compras/solicitacoes/cadastrar_editar/campos_cotacoes.php'); ?>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class="footer-group-new">
							<button type="button" class="btn btn-secundary prev-step mr-1">Anterior</button>
							<button type="button" class="btn btn-primary next-step">Próxima</button>
						</div>
					</div>

      </div>
    </div>
  </div>
</div>

<script>	
	const idSolicitacao = "<?= !empty($idSolicitacao) ? $idSolicitacao : '' ?>";
	const acao = "<?= !empty($acao) ? $acao : '' ?>";
	const tipoDeUsuario = `<?= $funcaoUsuario ?>`;
	const tipoSolicitacao = "<?= !empty($tipoSolicitacao) ? $tipoSolicitacao : '' ?>";
	const cadastroRequisicao = tipoSolicitacao && tipoSolicitacao === 'requisicao' && tipoDeUsuario === "solicitante";
	const cadastroPedido = tipoSolicitacao && tipoSolicitacao === 'pedido' && tipoDeUsuario === "solicitante";
	let meusProdutos = [];

	$(document).ready(function() {
		validar_campos_form('form-produtos', adicionarProduto);
		validar_campos_form('form-cotacoes', cadastarCotacao);
	});
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'mascaras.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'utils.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'filiais.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'empresas.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'centro_custo.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes/cadastrar_editar', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes/cadastrar_editar', 'solicitacao.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes/cadastrar_editar', 'cotacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes/cadastrar_editar', 'produtos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'editar.js') ?>"></script>