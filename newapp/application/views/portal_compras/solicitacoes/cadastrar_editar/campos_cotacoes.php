<div class="col-sm-12 col-md-12" id="div-mensagem-aba-cotacao">
  <?php if($acao === 'adicionar_cotacao' || $acao === 'adicionarProdutoECotacao'): ?>
    <p class="alert alert-info">
      Obs 01: Caso não exista a cotação desejada, clique no botão "Nova Cotação" para adicionar.<br>
      Obs 02: Solicitações com valor total até R$ 20.000,00 só é necessário uma cotação.<br>
      Obs 03: Solicitações com valor total maior que R$ 20.000,00 é necessário três ou mais cotações.
    </p>
  <?php endif; ?>
</div>

<div id="div-add-cotacoes">
  <div id="div-adicionar-cotacao" class="row" style="margin-right: 0px; margin-left: 0px;">
    <div class="col-md-12">
        <button type="button" title="Adicionar Nova Cotação" class="btn btn-primary mb-1" id="btn-nova-cotacao"  style="float: right;">
          Nova Cotação
        </button>
      </div>
  </div>
  
  <div class="col-md-12 col-sm-12">
    <div class="form-group wrapperShow" style="height: 30vh">
      <div id="grid-cotacoes" class="ag-theme-alpine my-grid-show" style="height: 100%"></div>
    </div>
  </div>
</div>