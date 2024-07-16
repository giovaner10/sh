<div id="modal-nota-fiscal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="titulo-modal-produto">Incluir Nota Fiscal</h3>
			</div>

			<form id="form-nota-fiscal">
				<div class="modal-body">

          <div class="col-md-6 col-sm-12">
						<div class="form-group">
              <label for="numero-nota" class="required">Número: </label>
              <input type="text" class="form-control" name="numero" id="numero-nota" minlength="1" maxlength="9" placeholder="Digite o número da nota" required />
            </div>

						<div class="form-group">
              <label for="especie-nota" class="required">Espécie: </label>
							<select name="especie" id="especie-nota" class="form-control" placeholder="Digite a espécie da nota"  required></select>
            </div>

						<div class="form-group">
              <label for="data_emissao-nota" class="required">Data de Emissão: </label>
              <input type="date" id="data_emissao-nota" class="form-control" name="data_emissao" max="<?= date('Y-m-d') ?>" required />
            </div>

						<div class="form-group">
							<label id="div-anexo-nota" style="width: 100%;" class="required">
								Anexo:
								<input 
									type="file" 
									name="anexo" 
									id="anexo-nota" 
									data-max-size="5242880" 
									accept=".pdf"
									style="display: none;"
									>
								</input>
							</label>
							<label id="viewAnexo-nota" for="anexo-nota" class="form-control"></label>
							<p>
								<small>*Formatos suportados: pdf</small>
								<br>
								<small>*Tamanho máximo permitido: 5MB</small>
							</p>
						</div>
          </div>

          <div class="col-md-6 col-sm-12">
						<div class="form-group">
              <label for="serie-nota" class="required">Série: </label>
              <input type="text" class="form-control" name="serie" id="serie-nota" minlength="1" maxlength="9" placeholder="Digite a série da nota" required />
            </div>

						<div class="form-group">
              <label for="valor-nota" class="required">Valor: </label>
              <input type="text" class="form-control moeda" name="valor" id="valor-nota" placeholder="Digite o valor da nota" required />
            </div>

            <div class="form-group">
              <label for="data_vencimento-nota" class="required">Data de Vencimento: </label>
              <input id="data_vencimento-nota" type="date" class="form-control" name="data_vencimento" min="<?= date('Y-m-d') ?>" required />
            </div>

          </div>

          <hr>
        </div>
				<div class="modal-footer">
					<div class="footer-group-new">
						<button type="submit" class="btn btn-success" id="btn-incluir-nota-fiscal">Enviar</button>
					</div>
				</div>
      </form>

		</div>
	</div>
</div>