<form id="form-solicitacao">
  <div class="col-md-6 col-sm-12">
    <div class="form-group">
      <label for="empresa" class="required">Departamento: </label>
      <select name="empresa" id="empresa" class="form-control" required readonly></select>
    </div>

    <div class="form-group">
      <label for="centro_custo" class="required">Centro de Custo: </label>
      <select name="centro_custo" id="centro_custo" class="form-control centro-custo" required ></select>
    </div>

    <div class="form-group" style="margin-top: 10px;">
      <label for="capex" style="margin-right: 5px;">CAPEX</label>
      <label class="switch">
        <input type="checkbox" name="capex" id="capex">
        <span class="slider round"></span>
      </label>
    </div>

    <div class="form-group" style="margin-top: 10px;">
      <label for="rateio" style="margin-right: 5px;">Rateio</label>
      <label class="switch">
        <input type="checkbox" name="rateio" id="rateio">
        <span class="slider round"></span>
      </label>
    </div>

    <div class="form-group">
      <label id="div-anexo-rateio" style="width: 100%;">
        Anexo Rateio:
        <input 
          type="file" 
          name="anexo_rateio" 
          id="anexo-rateio" 
          data-max-size="5242880" 
          accept=".pdf, .xls, .xlsx"
          style="display: none;" 
          disabled
          >
        </input>
				<a href="#" target="_blank" id="visualizar-anexo-rateio" style="float: right;" hidden>Visualizar Anexo</a>
      </label>
      <label id="viewAnexo-rateio" for="anexo-rateio" class="form-control" disabled></label>
      <p>
        <small>*Formatos suportados: pdf, xls, xlsx</small>
        <br>
        <small>*Tamanho máximo permitido: 5MB</small>
      </p>
    </div>
    
  </div>

  <div class="col-md-6 col-sm-12">
    <div class="form-group">
      <label for="filial" class="required">Filial: </label>
      <select name="filial" id="filial" required></select>
    </div>

    <div class="form-group">
      <label for="tipo_requisicao" class="required">Tipo de Requisição: </label>
      <select name="tipo_requisicao" id="tipo_requisicao" class="form-control" required >
        <option value="" selected disabled >Selecione o tipo de requisição</option>
        <option value="nao_recorrente">Não Recorrente</option>
        <option value="recorrente">Recorrente</option>
      </select>
    </div>
    
    
    <div class="form-group">
      <label style="width: 100%;">
        Anexo Solicitação:
        <input 
          type="file" 
          name="anexo" 
          id="anexo" 
          data-max-size="5242880" 
          accept=".jpeg, .jpg, .png, .pdf" 
          style="display: none;" 
        ></input>
				<a href="#" target="_blank" id="visualizar-anexo-solicitacao" style="float: right;" hidden>Visualizar Anexo</a>
      </label>
      <label id="viewAnexo" for="anexo" class="form-control"></label>
      <p>
        <small>*Formatos suportados: pdf, jpg, png e jpeg</small>
        <br>
        <small>*Tamanho máximo permitido: 5MB</small>
      </p>
    </div>

    <div class="form-group">
      <label for="motivo_compra" class="required">Motivo da Compra: </label>
      <textarea type="text" class="form-control" name="motivo_compra" id="motivo_compra" rows="5" cols="50" minlength="20" maxlength="500" required></textarea>
    </div>

  </div>
</form>