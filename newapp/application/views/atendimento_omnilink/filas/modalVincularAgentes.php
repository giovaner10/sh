<div id="modal-vincular-agentes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
	aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title">Vincular Agentes</h3>
			</div>
			<form id="form-vincular-agentes" novalidate="novalidate">
				<div class="modal-body">
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="agentes-fila" class="required">Agentes: </label>
							<select name="usuarios" id="agentes-fila" multiple require></select>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="numeros-fila" class="required">Telefones: </label>
							<select name="numeros" id="numeros-fila" require></select>
						</div>
					</div>

					<hr>
				</div>
				<div class="modal-footer">
					<div class="footer-group-new">
						<button type="submit" class="btn btn-success" data-acao="cadastrar"
							id='btn-vincular-agentes'>Salvar</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>