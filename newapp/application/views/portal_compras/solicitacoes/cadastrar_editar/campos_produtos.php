<div id="modal-produto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header header-layout">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title" id="titulo-modal-produto">Adicionar Produto</h3>
      </div>

      <form id="form-produtos">
        <div class="modal-body row g-3">
          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label for="produto" class="required">Produto: </label>
              <select name="produto" id="produto-solicitacao" class="form-control" required></select>
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label for="quantidade" class="required">Quantidade: </label>
              <input type="number" class="form-control input-adicionar-produto" name="quantidade" id="quantidade"
                min="1" maxlength="5" required readonly />
            </div>
          </div>
        </div>
        <div class="modal-footer-solicitacao">
          <div class="footer-group-new">
            <button type="submit" class="btn btn-success" data-acao="cadastrar"
              id="btn-adicionar-produto">Salvar</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="col-sm-12 col-md-12">
  <?php if($acao === 'adicionar_produto_cotacao'): ?>
    <p class="alert alert-info">
      Obs.: Caso não exista o produto desejado, Entre em contato com o setor responsável para fazer o cadastro no ERP, e
      logo após retorne para adiciona-lo à solicitação.<br>
    </p>
    <?php elseif(!empty($tipoSolicitacao) && $tipoSolicitacao === 'requisicao' && in_array($acao, ['cadastrar', 'editar'])): ?>
      <p class="alert alert-info">
      Obs.: Caso não exista o produto desejado, cadastre a solicitação sem um produto que posteriormente o setor de
      compras irá criar o produto.<br>
    </p>
    <?php endif; ?>
</div>

<div style="display: flex;" class="col-md-12 col-sm-12">
	<div class="row col-sm-12 col-md-12" id="div-produto-nao-encontrado">
		<label for="produto-nao-encontrado" style="margin-right: 5px;">Produto não encontrado?</label>
		<label class="switch">
			<input type="checkbox" name="produto-nao-encontrado" id="produto-nao-encontrado">
			<span class="slider round"></span>
		</label>
	</div>

  <button type="button" title="Adicionar Produto" style="margin-bottom: 10px"
    class="btn btn-primary campos-add-produtos" id="btn-novo-produto">
    Adicionar
  </button>
</div>

<div class="col-md-12 col-sm-12 campos-add-produtos">
  <div class="form-group wrapperShow" style="height: 40vh">
    <div id="grid-produtos" class="ag-theme-alpine my-grid-show" style="height: 100%"></div>
  </div>
</div>