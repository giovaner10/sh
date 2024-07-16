<div id="guia-agenda" class="p-1" style="display: none">

  <div>
    <input type="text" id="pesquisarNaAggridContatos" class="form-control" placeholder="Pesquisar"
      style="max-width: 100%; margin-bottom: 10px; margin-top: 10px; float: right;" oninput="pesquisarNaAggridContatos(this)">
  </div>

  
  <div id="grid-agenda" class="list-group lista-de-contatos" style="height: 350px;"> </div>
  
  <button id="btn-add-contato-listar" class="btn btn-lg btn-success-discador rounded-pill" style="background-color:#1C69AD !important;">
    <img 
      id="img-btn-add-contato-listar" 
      class="d-none" 
      width="95%" 
      height="20" 
      src="<?= base_url('media/img/new_icons/omnicom/icon-add-contato.svg') ?>">
  </button>
</div>