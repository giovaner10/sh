<div id="modal-cadastrar-editar-fila" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
	aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="titulo-modal-fila">Cadastrar Fila</h3>
			</div>
			<form id="form-fila" novalidate="novalidate">
				<div class="modal-body">

					<div class="">
						<div class="col-md-12">
							<div class="form-group">
								<label for="nome" class="required">Nome: </label>
								<input 
									type="text" 
									class="form-control" 
									name="nome" 
									id="nome" 
									minlength="5"
									maxlength="60" 
									placeholder="Digite o nome" 
									required />
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label for="descricao" class="">Descrição: </label>
								<textarea 
									class="form-control"
									style="resize: none"
									name="descricao"
									id="descricao"
									minlength="5" 
									maxlength="240" 
									rows="3"
									cols="50"
									placeholder="Digite a descrição"
								></textarea>
							</div>
						</div>

						<div class="col-md-12">
							<div class="row">
									<div class="col-md-6">
											<div class="form-group">
													<label for="dia_inicial" class="required">Dia inicial: </label>
													<select id="dia_inicial" name="dia_inicial" class="form-control" required>
															<option value="Segunda-feira">Segunda-feira</option>
															<option value="Terça-feira">Terça-feira</option>
															<option value="Quarta-feira">Quarta-feira</option>
															<option value="Quinta-feira">Quinta-feira</option>
															<option value="Sexta-feira">Sexta-feira</option>
															<option value="Sábado">Sábado</option>
															<option value="Domingo">Domingo</option>
													</select>
											</div>
									</div>
									<div class="col-md-6">
											<div class="form-group">
													<label for="dia_final" class="required">Dia final: </label>
													<select id="dia_final" name="dia_final" class="form-control" required>
															<option value="Segunda-feira">Segunda-feira</option>
															<option value="Terça-feira">Terça-feira</option>
															<option value="Quarta-feira">Quarta-feira</option>
															<option value="Quinta-feira">Quinta-feira</option>
															<option value="Sexta-feira" selected>Sexta-feira</option>
															<option value="Sábado">Sábado</option>
															<option value="Domingo">Domingo</option>
													</select>
											</div>
									</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="horario_inicial" class="required">Hora inicial: </label>
								<input 
									type="time" 
									class="form-control"
									name="horario_inicial"
									id="horario_inicial"									
									minlength="5" 
									maxlength="240" 
									rows="3"
									cols="50"
									placeholder="Digite a hora inicial"
									required
								/>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="horario_final" class="required">Hora final: </label>
								<input 
									type="time" 
									class="form-control"
									name="horario_final"
									id="horario_final"
									minlength="5" 
									maxlength="240" 
									rows="3"
									cols="50"
									placeholder="Digite a hora final"
									required
								/>
							</div>
						</div>

					</div>

					<hr>
				</div>
				<div class="modal-footer">
					<div class="footer-group-new">
						<button type="submit" class="btn btn-success" data-acao="cadastrar"
							id='btn-salvar-fila'>Salvar</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>