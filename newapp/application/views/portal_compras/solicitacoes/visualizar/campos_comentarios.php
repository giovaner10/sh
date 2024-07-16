<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css/portal_compras', 'comentario.css') ?>">

<div class="col-md-12 col-sm-12">
	<div class="row">
		<div class="col-md-8 col-sm-12">
			<div class="form-group">
				<label for="mensagem-comentario" class="required">Nova mensagem: </label>
				<textarea class="form-control" id="mensagem-comentario" rows="3" cols="50" minlength="3" maxlength="240" required></textarea>
			</div>
		</div>

		<div class="col-md-4 col-sm-12">
			<div class="input-group">
					<label style="width: 100%;">
						Anexo:
						<input 
							type="file" 
							name="anexo-comentario" 
							id="anexo-comentario" 
							data-max-size="5242880" 
							accept=".jpeg, .jpg, .png, .pdf" 
							style="display: none;" 
						></input>
					</label>
					<label id="view-anexo-omentario" for="anexo-comentario" class="form-control"></label>
					<p>
						<small>*Formatos suportados: pdf, jpg, png e jpeg</small>
						<br>
						<small>*Tamanho máximo permitido: 5MB</small>
					</p>
				</div>

				<div style="display: flex; justify-content: end;">
					<button id="btn-limpar-comentario" class="btn btn-default mr-1" onclick="limparCamposComentario()">Limpar</button>
					<button id="btn-enviar-comentario" class="btn btn-success">Enviar</button>
				</div>
			</div>
			
		</div>
	</div>

	<div class="col-md-12 col-sm-12 chat-container mb-10">
		<!-- listagem dos comentarios -->
		<div id="messages" class="chat-box"></div>
  </div>
</div>