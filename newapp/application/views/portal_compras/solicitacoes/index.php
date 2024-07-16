<?php
  // permissões
  $possui_permissao_cadastrar_solicitacao = $this->auth->is_allowed_block('cad_solicitacoes_portal_compras');
  $possui_permissao_remover_solicitacoes = $this->auth->is_allowed_block('rem_solicitacoes_portal_compras');
  $possui_permissao_visualizar_solicitacoes = $this->auth->is_allowed_block('vis_solicitacoes_portal_compras');
  $possui_permissao_editar_solicitacoes = $this->auth->is_allowed_block('edi_solicitacoes_portal_compras');
  $possui_permissao_adicionar_cotacao = $this->auth->is_allowed_block('cad_cotacoes_portal_compras');
  $possui_permissao_incluir_nota_fiscal = $this->auth->is_allowed_block('cad_nota_fiscal_portal_compras');
  $possui_permissao_incluir_boleto = $this->auth->is_allowed_block('cad_boleto_portal_compras');
?>

<div style="padding: 0 20px; margin-left: 15px;">
	<div class="text-title">
		<h3><?= lang('portal_compras') ?></h3>
		<h4>
			<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
			<?= lang('portal_compras') ?> >
			<?= lang('solicitacoes') ?> >
			<?= $tipoSolicitacao == 'requisicao' ? 'Listar Requisições' : 'Listar Pedidos' ?>
		</h4>
	</div>
  
  <div class="row">
    <div class="col-md-3" id="menu-lateral">
      <div class="card menu-interno">
        <?php include_once('application/views/portal_compras/menu.php'); ?>
      </div>
      <div>
        <div class="card" style="margin-top: 2rem;">
          <div class="card-filtro-portal" id="card_filtro_portal">
            <h4 class="card_titulo" style="margin-left: 1px;">Filtro</h4>
          </div>

          <div class="card-body-portal">
            <div class="form-group filtro">
              <label for="dataInicio" class="label_input">Data inicial:</label>
              <input type="date" name="dataInicio" id="dataInicio" class="form-control" max="<?= date('Y-m-d') ?>" value="" />
              <div id="dataInicio-invalid" class="invalid-feedback"></div>
            </div>

            <div class="form-group filtro">
              <label for="dataFim" class="label_input">Data final:</label>
              <input type="date" name="dataFim" id="dataFim" class="form-control" max="<?= date('Y-m-d') ?>" value="" />
              <div id="dataFim-invalid" class="invalid-feedback"></div>
            </div>

            <div class="form-group situacao-portal" id="situacao-portal">
              <label for="sitPortal" class="portal_select">Situação:</label>
              <select class="form-control" id="situacao-portal-filtro" placeholder="Selecione uma situação" required>
                <option value="" disabled selected></option>
                <option value="aguardando_produto_cotacao">Aguardando produto e cotação</option>
                <option value="aguardando_cotacao">Aguardando Cotação</option>
                <option value="aguardando_confirmacao_cotacao">Aguardando selecionar a cotação</option>
                <option value="aguardando_aprovacao">Aguardando Aprovação<A:link></A:link></option>
                <option value="aprovado">Aprovado</option>
                <option value="reprovado">Reprovado</option>
              </select>
            </div>

            <div class="form-group">
              <label for="centro_custo">Centro de Custo: </label>
              <select name="centro_custo" id="centro_custo" class="form-control centro-custo" required></select>
            </div>

            <div class="nome-requerente form-group">
              <div class="form-group mr-1">
                <label for="req-portal">Requerente:</label>
                <select name="diretor" id="diretor"></select>
              </div>
            </div>



            <button type="submit" id="btnFormFiltroPortal" class=" btn-success btn-filtro">
              Filtrar
            </button>

            <button type="reset" id="btnResetFormPortal" onclick="limparSelecteds()" class=" bnt-light btn-filtro" style="border:0 !important;background-color:white;margin-bottom:5px;">
              Limpar
            </button>

          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9" id="conteudo">
      <div class="card-conteudo card-dados-show" style='margin-bottom: 20px; position: relative;'>
        <h3 style="align-items: center; text-align: center;">
          <b><?= $tipoSolicitacao == 'requisicao' ? 'Requisições' : 'Pedidos' ?></b>
          <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
            <div style="display: flex;">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 10px;">
                <?= lang('exportar') ?>
              </button>
              <div class="dropdown-menu dropdown-exportar dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <button onclick="gerarRelatorio('csv', 'Solicitações', 'Relatório de Solicitações - PORTAL DE COMPRAS', 'acoes,aprovacoes')" class="dropdown-item item-exportar" style="color: #1C69AD;" href="#"><img class="mr-1" src="<?= base_url('media/img/new_icons/relatorios/csv.svg') ?>" />CSV</button>
                <button onclick="gerarRelatorio('excel', 'Solicitações', 'Relatório de Solicitações - PORTAL DE COMPRAS', 'acoes,aprovacoes')" class="dropdown-item item-exportar" style="color: #1A9A20;" href="#"><img class="mr-1" src="<?= base_url('media/img/new_icons/relatorios/excel.svg') ?>" />Excel</button>
                <button onclick="gerarRelatorio('pdf', 'Solicitações','Relatório de Solicitações - PORTAL DE COMPRAS')" class="dropdown-item item-exportar" style="color: #EA1A1A;" href="#"><img class="mr-1" src="<?= base_url('media/img/new_icons/relatorios/pdf.svg') ?>" />PDF</button>
              </div>
            </div>

            <button class="btn btn-light" data-toggle="tooltip" data-placement="left" title="<?= lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;" onclick="expandirGrid()">
              <img id="img_grid_expandir" class="img-expandir" src="<?= base_url('assets/images/icon-filter-hide.svg') ?>" style="width: 25px;">
            </button>
          </div>
        </h3>
        <div>
          <select class="form-control" onchange="selecionarQuantidadePorPagina(this)" style="width: 100px; float: left; margin-top: 10px;">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <input type="text" id="pesquisarNaAggrid" class="form-control" placeholder="Pesquisar" style="max-width: 220px; margin-bottom: 10px; margin-top: 10px; float: right;" oninput="pesquisarNaAggrid(this)">
        </div>
        <div class="wrapperShow" style="height: 60vh">
          <div id="grid-solicitacoes" class="ag-theme-alpine my-grid-show" style="height: 100%">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
	const idSolicitacao = "<?= !empty($idSolicitacao) ? $idSolicitacao : '' ?>";
  const tipoSolicitacao = "<?= !empty($tipoSolicitacao) ? $tipoSolicitacao : '' ?>";
		
	const permissaoVisualizarSolicitacoes = Boolean(`<?= $possui_permissao_visualizar_solicitacoes ?>`) == true;
	const permissaoRemoverSolicitacoes = Boolean(`<?= $possui_permissao_remover_solicitacoes ?>`) == true;
	const permissaoEditarSolicitacoes = Boolean(`<?= $possui_permissao_editar_solicitacoes ?>`) == true;
	const permissaoAdicionarCotacoes = Boolean(`<?= $possui_permissao_adicionar_cotacao ?>`) == true;
	const permissaoIncluirNotaFiscal = Boolean(`<?= $possui_permissao_incluir_nota_fiscal ?>`) == true;
  const permissaoIncluirBoleto = Boolean(`<?= $possui_permissao_incluir_boleto ?>`) == true;
  
	$(document).ready(function() {
		validar_campos_form('form-nota-fiscal', incluirNotaFiscal);
    validar_campos_form('form-boleto', incluirBoleto);
	});

</script>

<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'utils.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'mascaras.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'centro_custo.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'remover.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'nota_fiscal.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/solicitacoes', 'boleto.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'error.js') ?>"></script>