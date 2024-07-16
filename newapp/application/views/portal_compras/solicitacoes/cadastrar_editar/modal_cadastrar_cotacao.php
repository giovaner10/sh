<div id="modal-cotacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="titulo-modal-cotacao">Cadastrar Cotação</h3>
			</div>

			<form id="form-cotacoes">
				<div class="modal-body row g-3">
          
          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label for="fornecedor" class="required">Fornecedor: </label>
              <select name="fornecedor" id="fornecedor" class="form-control" required></select>
            </div>

            <div class="form-group">
              <label for="forma_pagamento" class="required">Forma de Pagamento: </label>
              <select name="forma_pagamento" id="forma_pagamento" class="form-control input-adicionar-cotacao" required
                readonly>
                <option value="" selected disabled>Selecione a forma de pagamento</option>
                <option value="pix">Pix</option>
                <option value="boleto">Boleto</option>
                <option value="ted">TED</option>
                <option value="deposito">Depósito</option>
              </select>
            </div>

						<div class="form-group">
              <label style="width: 100%;" class="required">
                Anexo da Cotação:
                <input 
                  type="file" 
                  name="anexo_cotacao" 
                  id="anexo_cotacao" 
                  data-max-size="5242880" 
                  accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .eml"
                  style="display: none;"
                  class="form-control input-adicionar-cotacao"
                  readonly
                  required
                ></input>
                <a href="#" target="_blank" id="visualizar-anexo_cotacao" style="float: right;" hidden>Visualizar Anexo</a>
              </label>
              <label id="viewAnexo-cotacao" for="anexo_cotacao" class="form-control input-adicionar-cotacao"
                readonly></label>
              <p>
                <small>*Formatos suportados: eml, pdf, doc, docx, xls, xlsx, ppt e pptx</small>
                <br>
                <small>*Tamanho máximo permitido: 5MB</small>
              </p>
            </div>
          </div>

          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label for="condicao_pagamento" class="required">Condição de Pagamento: </label>
              <select name="condicao_pagamento" id="condicao_pagamento" class="form-control input-adicionar-cotacao" required readonly ></select>
            </div>

						<div class="form-group">
							<label for="tipo-especie" class="required">Tipo espécie: </label> 
							
							<div class="informacacao-tipo-especie informativo_shownet">
								<i class="fa fa-info-circle" aria-hidden="true"></i>
								<div class="pop-informativo_shownet" id="pop_informativo_shownet">
									<em><u>Tipo</u> <u>espécie</u> determina se o recebimento da nota fiscal será direto do ERP ou precisará ser incluida a pré-nota pelo portal de compras </em>
								</div>
							</div>

							<select name="tipo_especie" id="tipo_especie" class="form-control input-adicionar-cotacao" required readonly>
								<option value="" selected disabled>Selecione um tipo de espécie</option>
								<option value="sped">SPED</option>
								<option value="nfe">NFE</option>
								<option value="cte">CTE</option>
								<option value="nfs">NFS</option>
								<option value="nf">NF</option>
								<option value="outro">OUTRO</option>
							</select>
						</div>
          </div>

					<div class="col-md-12 col-sm-12" style="display:flex; justify-content:space-between; align-items:end;">
						<label class="required">Produtos da Cotação: </label>
					</div>
					<div class="col-md-12 col-sm-12">
						<div class="form-group wrapperShow" style="height: 30vh">
							<div id="grid-produtos-cotacao" class="ag-theme-alpine my-grid-show" style="height: 100%"></div>
						</div>
					</div>

        </div>

				<div class="modal-footer-solicitacao">
					<div class="footer-group-new">
						<button type="submit" class="btn btn-success" id="btn-cadastrar-cotacao" data-cao="cadastrar">Cadastrar</button>
					</div>
				</div>
      </form>

    </div>
  </div>
</div>