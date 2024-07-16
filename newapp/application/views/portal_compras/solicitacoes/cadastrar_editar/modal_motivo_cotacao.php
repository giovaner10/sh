<div id="modal-salvar-motivo-cotacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="titulo-modal-motivo-cotacao">Justificativa da cotação</h3>
			</div>
      
			
        <div class="modal-body">
          <div class="col-md-12">
            <div class="form-group">
              <p class="leganda-label" style="text-align: justify">
                A justificativa é necessária quando o número de cotações é inferior a três.
              </p>
              
							<textarea 
								class="form-control"
								name="motivo_cotacao"
								id="motivo_cotacao"
								minlength="5" 
								maxlength="240" 
								rows="7"
								cols="50"
								placeholder="Digite aqui..."
							></textarea>
						</div>
					</div>
					<hr>
				</div>

				<div class="modal-footer">
					<div class="footer-group-new">
						<button type="button" class="btn btn-success" data-acao= "salvar" id='btn-salvar-motivo-cotacao'>Confirmar</button>
					</div>
				</div>
	
		</div>
	</div>
</div>
