<div class="col-md-9 conteudo" id="conteudo">
  <div class="card-conteudo card-dados-show" style='margin-bottom: 20px;'>

    <h3 style="align-items: center; text-align: center;">
      <b>Registro Geral de Chamadas</b>
      <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">            
        
        <button 
          class="btn btn-light" 
          data-toggle="tooltip" 
          data-placement="left" 
          title="<?= lang('expandir_grid') ?>" 
          style="border-radius:6px; padding:5px;" 
          onclick="expandirGrid()"
        >
          <img 
            id="img_grid_expandir" 
            class="img-expandir" 
            src="<?= base_url('assets/images/icon-filter-hide.svg') ?>" 
            style="width: 25px;"
          />
        </button>
      </div>

    </h3>

    <div>
      <select 
        class="form-control" 
        onchange="selecionarQuantidadePorPagina(this)" 
        style="width: 100px; float: left; margin-top: 10px;"
      >
        <option value="10" selected>10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>

        <input 
          type="text" 
          id="pesquisarNaAggrid" 
          class="form-control" 
          placeholder="Pesquisar"
          style="max-width: 250px; margin-bottom: 10px; margin-top: 10px; float: right;" 
          oninput="pesquisarNaAggrid(this)"
        >

    </div>

    <div style="height: 70vh">
      <div id="grid-registros-chamadas" class="ag-theme-alpine" style="height: 100%"> </div>
    </div>

  </div>
</div>
