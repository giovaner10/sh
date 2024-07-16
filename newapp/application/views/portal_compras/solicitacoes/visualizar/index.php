<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css/portal_compras', 'comentario.css') ?>">

<div style="padding: 0 20px; margin-left: 15px;">
  <div class="text-title">
		<h3><?= lang('portal_compras') ?></h3>
		<h4>
			<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
			<?= lang('portal_compras') ?> >
			<?= lang('solicitacoes') ?> >
			Visualizar
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
						<b id="titulo-cadatrar-editar">Visualizar Solicitação #<?= $idSolicitacao ?></b>
						<span style="color: #666 !important; font-size: 16px !important; align-content: end;">
							<b>Total da Solicitação:</b> <span id="total-solicitacao">R$ 0,00</span>
						</span>
					</h3>
				</div>

				<div class="modal-body row">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#aba-solicitacao">Dados da Solicitação</a></li>
						<li><a data-toggle="tab" href="#aba-produtos">Produtos</a></li>
						<li><a data-toggle="tab" href="#aba-cotacoes">Cotação</a></li>
						<li><a data-toggle="tab" href="#aba-comentarios">Comentários</a></li>
						<li><a data-toggle="tab" href="#aba-historicos">Histórico</a></li>
						<li><a data-toggle="tab" href="#aba-nota-fiscal">Nota Fiscal</a></li>
					</ul>

					<div class="tab-content">
						<div id="aba-solicitacao" class="tab-pane fade in active">
							<?php include_once('application/views/portal_compras/solicitacoes/visualizar/campos_solicitacao.php'); ?>
						</div>
						<div id="aba-produtos" class="tab-pane fade">
							<?php include_once('application/views/portal_compras/solicitacoes/visualizar/campos_produtos.php'); ?>
						</div>
						<div id="aba-cotacoes" class="tab-pane fade">
							<?php include_once('application/views/portal_compras/solicitacoes/visualizar/campos_cotacoes.php'); ?>
						</div>
						<div id="aba-historicos" class="tab-pane fade">
							<?php include_once('application/views/portal_compras/solicitacoes/visualizar/campos_historico.php'); ?>
						</div>
						<div id="aba-nota-fiscal" class="tab-pane fade">
							<?php include_once('application/views/portal_compras/solicitacoes/visualizar/campos_nota_fiscal.php'); ?>
						</div>
						<div id="aba-comentarios" class="tab-pane fade">
							<?php include_once('application/views/portal_compras/solicitacoes/visualizar/campos_comentarios.php'); ?>
						</div>
					</div>

					<div class="modal-footer row">
						<div class="">
							<div id="div-mensagem-aprovacao-realizada" style="display: none;">
								<p class="alert alert-info" style="text-align: left;">
									Obs.: A solicitação já foi aprovada/reprovada.
								</p>
							</div>
						</div>

						<div class="footer-group-new" id="div-btn-aprovacao" style="display: none;">
							<button type="button" class="btn btn-danger btn-aprovacao" id="btn-reprovar-solicitacao">Reprovar</button>
							<button type="button" class="btn btn-success btn-aprovacao" id="btn-aprovar-solicitacao">Aprovar</button>
						</div>
					</div>
				</div>

      </div>
    </div>
  </div>
</div>

<script>	
	const idSolicitacao = "<?= !empty($idSolicitacao) ? $idSolicitacao : '' ?>";
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'utils.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'mascaras.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'comentarios.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'historico.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'nota_fiscal.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'visualizar.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'aprovacao.js') ?>"></script>