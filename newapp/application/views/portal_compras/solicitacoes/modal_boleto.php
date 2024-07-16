<div id="modal-boleto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header header-layout">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Incluir Boleto</h3>
      </div>

      <form id="form-boleto">
        <div class="modal-body">
          <div class="form-group">
            <label id="div-anexo-boleto" style="width: 100%;" class="required">
              Anexo:
              <input type="file" name="anexo" id="anexo-boleto" data-max-size="5242880" accept=".pdf"
                style="display: none;">
              </input>
            </label>
            <label id="viewAnexo-boleto" for="anexo-boleto" class="form-control"></label>
            <p>
              <small>*Formatos suportados: pdf</small>
              <br>
              <small>*Tamanho máximo permitido: 5MB</small>
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <div class="footer-group-new">
            <button type="submit" class="btn btn-success" id="btn-incluir-boleto">Enviar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>