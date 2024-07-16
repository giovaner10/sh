$(document).ready(function() {
	$(document).on('click', '.save-etapa', function() {
		let stagnado = $('#'+$(this).attr('data-companyname')+'stagnado').val();
		stagnado = stagnado ? stagnado : 60;
		if ($('.etapa-delete').attr('style') == 'display: inline-block;') {
			let date = new Date();
			let day = String(date.getDate());
			let month = String(date.getMonth()+1);
			let second = String(date.getSeconds());
			let hour = String(date.getHours());
			let minute = String(date.getMinutes());
			let d = date.getFullYear()+'-'+(month.length == 1 ? '0'+month : month)+'-'+(day.length == 1 ? '0'+day : day);
			let h = (hour.length == 1 ? '0'+hour : hour)+':'+(minute.length == 1 ? '0'+minute : minute)+':'+(second.length == 1 ? '0'+second : second);
			$.post('editEtapa', {
				id: $('.etapa-delete').attr('data-id'),
				name: $('#'+$(this).attr('data-companyname')+'nome').val(),
				is_on: $("input[name='porcentagem']:checked").val() == 'true' ? '1' : '0' ,
				company_id: parseInt($(this).attr('data-companyid')),
				stagnado: stagnado,
				updated_on: d+ ' ' +h
			}, function(callback) {
				console.log(callback);
			}).done(function() {
				alert( "second success" );
			}).fail(function() {
				alert( "error" );
			}).always(function() {
				alert( "finished" );
				location.reload();
			});
		} else {
			$.post('saveEtapa', {
				name: $('#'+$(this).attr('data-companyname')+'nome').val(),
				is_on: $("input[name='porcentagem']:checked").val() == 'true' ? '1' : '0' ,
				company_id: parseInt($(this).attr('data-companyid')),
				stagnado: stagnado,
			}, function(callback) {
				console.log(callback);
			}).done(function() {
				alert( "second success" );
			}).fail(function() {
				alert( "error" );
			}).always(function() {
				alert( "finished" );
				location.reload();
			});
		}
	});

	$(document).on('click', '.add-etapa', function() { $('.etapa-delete').css('display', 'none'); ''});

	$(document).on('click', '.model-etapa-link', function() {
		$('.etapa-delete').css('display', 'inline-block');
		$('.etapa-delete').attr('data-id', $(this).attr('data-id'));
		$('#'+$(this).attr('data-companyname')+'nome').val($(this).attr('data-name'));
		$('#'+$(this).attr('data-companyname')+'stagnado').val($(this).attr('data-stagnado'));
		if ($(this).attr('data-is_on') == '1') {
			$('#'+$(this).attr('data-companyname')+'Inline1').attr('checked', true);
			$('#'+$(this).attr('data-companyname')+'Inline2').attr('checked', false);
		} else {
			$('#'+$(this).attr('data-companyname')+'Inline2').attr('checked', true);
			$('#'+$(this).attr('data-companyname')+'Inline1').attr('checked', false);
		}
	});
	
	$(document).on('click', '.etapa-delete', function() {
		$.getJSON('deleteEtapa', { id: $(this).attr('data-id') }, function(callback) {
			console.log(callback);
		}).done(function() {
			// location.reload();
			alert( "second success" );
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
		});
	});

	$('#cancel-company').click(function() { $('#tituloFunilFormInputGroup').val('') });

	$('#save-company').click(function() {
		$.post('saveFunil', {
			name: $('#tituloFunilFormInputGroup').val().replace(/[&\/\\#,+()-_$~%.'": *?<>{}]/g, ''),
		}, function(callback) {
			console.log(callback);
		}).done(function() {
			alert( "second success" );
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
			location.reload();
		});
	});

	$(document).on('click', '.delete-company', function() {
		$.getJSON('deleteFunil', { id: $(this).attr('data-obj').split('-')[1] }, function(callback) {
			console.log(callback);
		}).done(function() {
			alert( "second success" );
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
			location.reload();
		});
	});

	$(document).on('click', '.edit-company', function() {
		let date = new Date();
		let day = String(date.getDate());
		let month = String(date.getMonth()+1);
		let second = String(date.getSeconds());
		let hour = String(date.getHours());
		let minute = String(date.getMinutes());
		let d = date.getFullYear()+'-'+(month.length == 1 ? '0'+month : month)+'-'+(day.length == 1 ? '0'+day : day);
		let h = (hour.length == 1 ? '0'+hour : hour)+':'+(minute.length == 1 ? '0'+minute : minute)+':'+(second.length == 1 ? '0'+second : second);
		$.post('editFunil', {
			id: $('#'+$(this).attr('data-idModal')+'Id').val(),
			name: $('#'+$(this).attr('data-idModal')).val().replace(/[&\/\\#,+()-_$~%.'": *?<>{}]/g, ''),
			updated_on: d+ ' ' + h
		}, function(callback) {
			console.log(callback);
		}).done(function() {
			alert( "second success" );
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
			location.reload();
		});
	});

	$.getJSON(dic['base_url']+'index.php/gestor_vendas/getFunil', {}, function(callback) {
		$.each(callback, function(i, obj) {
			(i==0) 
			? $('#myTab').append('\
					<li class="nav-item">\
						<a class="nav-link active" id="'+obj.name+'-tab" data-toggle="tab" href="#'+obj.name+'" role="tab" aria-controls="'+obj.name+'" aria-selected="true">'+obj.name+'</a>\
					</li>\
				')
			: $('#myTab').append('\
					<li class="nav-item">\
						<a class="nav-link " id="'+obj.name+'-tab" data-toggle="tab" href="#'+obj.name+'" role="tab" aria-controls="'+obj.name+'" aria-selected="true">'+obj.name+'</a>\
					</li>\
				')
			;
			
			(i==0) 
			? $('#myTabContent').append('\
				<div class="tab-pane fade active show" id="'+obj.name+'" role="tabpanel" aria-labelledby="'+obj.name+'-tab">\
					<h5 class="float-left" ><strong>'+obj.name+'</strong></h5>\
					<div class="float-left">\
						<button class="btn btn-default btn-sm ml-2" type="button" name="button" data-toggle="modal" data-target="#'+obj.name+'ModalEdit">editar</button>\
						<div class="modal fade" id="'+obj.name+'ModalEdit" tabindex="-1" role="dialog" aria-labelledby="funilModalCenterTitle" aria-hidden="true">\
								<div class="modal-dialog modal modal-dialog-centered" role="document">\
									<div class="modal-content">\
										<div class="modal-header">\
											<h5 class="modal-title" id="funilModalCenterTitle">Editar funil</h5>\
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
												<span aria-hidden="true">&times;</span>\
											</button>\
										</div>\
										<div class="modal-body">\
											<div class="container-fluid">\
												<form >\
													<div class="row">\
														<div class="col">\
															<div class="form-group">\
																<label class="sr-only" for="tituloFunilEdit"><b>Titulo do Funil</b></label>\
																<div class="input-group mb-2">\
																	<div class="input-group-prepend">\
																		<div class="input-group-text"><i class="fas fa-font"></i></div>\
																	</div>\
																	<input type="hidden" value="'+obj.id+'" id="'+obj.name+'FunilEditId">\
																	<input value="'+obj.name+'" type="text" class="form-control" id="'+obj.name+'FunilEdit" placeholder="Titulo do Funil">\
																</div>\
															</div>\
														</div><!--modal col -->\
													</div>\
												</form>\
											</div>\
										</div>\
										<div class="modal-footer">\
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>\
											<button type="button" data-idModal="'+obj.name+'FunilEdit" class="btn btn-success edit-company">Salvar</button>\
										</div>\
									</div>\
								</div>\
							</div><!--End Modal Funil-->\
						<button class="btn btn-default btn-sm ml-2 delete-company" data-obj="'+obj.name+'-'+obj.id+'" type="button" name="button">apagar funil</button>\
					</div>\
					<div class="float-right">\
						<button class="btn btn-success btn-sm add-etapa" type="button" name="button" data-toggle="modal" data-target="#'+obj.name+'Modal">Adicionar etapa</button>\
						<div class="modal fade" id="'+obj.name+'Modal" tabindex="-1" role="dialog" aria-labelledby="etapaModalCenterTitle" aria-hidden="true">\
							<div class="modal-dialog modal-lg modal-dialog-centered" role="document">\
								<div class="modal-content">\
									<div class="modal-header">\
										<h5 class="modal-title" id="etapaModalCenterTitle">Adicionar etapa</h5>\
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
											<span aria-hidden="true">&times;</span>\
										</button>\
									</div>\
									<div class="modal-body">\
										<div class="container-fluid">\
											<form class="" action="#" method="get">\
												<div class="row">\
													<div class="col">\
														<div class="form-group">\
															<label class="sr-only" for="'+obj.name+'nome"><b>Nome da Pessoa</b></label>\
															<div class="input-group mb-2">\
																<div class="input-group-prepend">\
																	<div class="input-group-text"><i class="fas fa-location-arrow"></i></div>\
																</div>\
																<input type="text" class="form-control" id="'+obj.name+'nome" placeholder="Nome da Etapa">\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="sr-only" for="'+obj.name+'stagnado"><b>Stagnado de Negócio</b></label>\
															<div class="input-group mb-2">\
																<div class="input-group-prepend">\
																	<div class="input-group-text"><i class="fas fa-hourglass-start"></i></div>\
																</div>\
																<input type="number" min="0" class="form-control" id="'+obj.name+'stagnado" placeholder="stagnado em (0...100) dias de inatividade ">\
															</div>\
														</div>\
														<div class="form-group">\
															<label for="negocioEstagnadoFormInputGroup"><b>Negócio "estagnando":</b></label>\
															<div class="custom-control custom-radio custom-control-inline">\
																<input type="radio" id="'+obj.name+'Inline1" value="true" name="porcentagem" class="custom-control-input">\
																<label class="custom-control-label" for="'+obj.name+'Inline1">Ligado</label>\
															</div>\
															<div class="custom-control custom-radio custom-control-inline">\
																<input type="radio" id="'+obj.name+'Inline2" value="false" name="porcentagem" class="custom-control-input">\
																<label class="custom-control-label" for="'+obj.name+'Inline2">Desligado</label>\
															</div>\
														</div>\
													</div><!--modal col -->\
												</div>\
											</form>\
											<div class="row">\
												<div class="col-12 text-center">\
													<button type="button" class="btn btn-success save-etapa" data-companyid="'+obj.id+'" data-companyname="'+obj.name+'">Salvar</button>\
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>\
													<button type="button" id="etapa-delete" class="btn btn-danger etapa-delete">Apagar</button>\
												</div>\
											</div>\
										</div>\
									</div>\
									<div class="modal-footer">\
										<div class="row">\
											<div class="col-12" style="text-align:center">\
												<small class="text-gray text-center" style="text-align:center">Por favor, tenha em mente que as etapas de vendas serão compartilhadas com todos os usuários da empresa.</small>\
											</div>\
										</div>\
									</div>\
								</div>\
							</div>\
						</div><!--End Modal Etapa-->\
					</div>\
					<br>\
					<div id="'+obj.name+'-etapas" class="container mt-4 container-funil d-flex flex-row bd-highlight mb-3" style="overflow: auto;">\
					</div>\
					<br>\
					Probabilidade de negócio\
					<div class="btn-group btn-group-toggle" data-toggle="buttons">\
						<label class="btn btn-secondary btn-sm active">\
							<input type="radio" name="options" id="option1" autocomplete="off" checked> SIM\
						</label>\
						<label class="btn btn-secondary btn-sm">\
							<input type="radio" name="options" id="option2" autocomplete="off"> NÃO\
						</label>\
					</div>\
						Permite que você configure a porcentagem de probabilidade de êxito para cada negócio.\
				</div><!--END TAB -->\
			')
			: $('#myTabContent').append('\
				<div class="tab-pane fade " id="'+obj.name+'" role="tabpanel" aria-labelledby="'+obj.name+'-tab">\
					<h5 class="float-left" ><strong>'+obj.name+'</strong></h5>\
					<div class="float-left">\
						<button class="btn btn-default btn-sm ml-2" type="button" name="button" data-toggle="modal" data-target="#'+obj.name+'ModalEdit">editar</button>\
						<div class="modal fade" id="'+obj.name+'ModalEdit" tabindex="-1" role="dialog" aria-labelledby="funilModalCenterTitle" aria-hidden="true">\
								<div class="modal-dialog modal modal-dialog-centered" role="document">\
									<div class="modal-content">\
										<div class="modal-header">\
											<h5 class="modal-title" id="funilModalCenterTitle">Editar funil</h5>\
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
												<span aria-hidden="true">&times;</span>\
											</button>\
										</div>\
										<div class="modal-body">\
											<div class="container-fluid">\
												<form >\
													<div class="row">\
														<div class="col">\
															<div class="form-group">\
																<label class="sr-only" for="tituloFunilEdit"><b>Titulo do Funil</b></label>\
																<div class="input-group mb-2">\
																	<div class="input-group-prepend">\
																		<div class="input-group-text"><i class="fas fa-font"></i></div>\
																	</div>\
																	<input type="hidden" value="'+obj.id+'" id="'+obj.name+'FunilEditId">\
																	<input value="'+obj.name+'" type="text" class="form-control" id="'+obj.name+'FunilEdit" placeholder="Titulo do Funil">\
																</div>\
															</div>\
														</div><!--modal col -->\
													</div>\
												</form>\
											</div>\
										</div>\
										<div class="modal-footer">\
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>\
											<button type="button" data-idModal="'+obj.name+'FunilEdit" class="btn btn-success edit-company">Salvar</button>\
										</div>\
									</div>\
								</div>\
							</div><!--End Modal Funil-->\
						<button class="btn btn-default btn-sm ml-2 delete-company" data-obj="'+obj.name+'-'+obj.id+'" type="button" name="button">apagar funil</button>\
					</div>\
					<div class="float-right">\
						<button class="btn btn-success btn-sm add-etapa" type="button" name="button" data-toggle="modal" data-target="#'+obj.name+'Modal">Adicionar etapa</button>\
						<div class="modal fade" id="'+obj.name+'Modal" tabindex="-1" role="dialog" aria-labelledby="etapaModalCenterTitle" aria-hidden="true">\
							<div class="modal-dialog modal-lg modal-dialog-centered" role="document">\
								<div class="modal-content">\
									<div class="modal-header">\
										<h5 class="modal-title" id="etapaModalCenterTitle">Adicionar etapa</h5>\
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
											<span aria-hidden="true">&times;</span>\
										</button>\
									</div>\
									<div class="modal-body">\
										<div class="container-fluid">\
											<form class="" action="#" method="get">\
												<div class="row">\
													<div class="col">\
														<div class="form-group">\
															<label class="sr-only" for="'+obj.name+'nome"><b>Nome da Pessoa</b></label>\
															<div class="input-group mb-2">\
																<div class="input-group-prepend">\
																	<div class="input-group-text"><i class="fas fa-location-arrow"></i></div>\
																</div>\
																<input type="text" class="form-control" id="'+obj.name+'nome" placeholder="Nome da Etapa">\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="sr-only" for="'+obj.name+'stagnado"><b>Stagnado de Negócio</b></label>\
															<div class="input-group mb-2">\
																<div class="input-group-prepend">\
																	<div class="input-group-text"><i class="fas fa-hourglass-start"></i></div>\
																</div>\
																<input type="number" min="0" class="form-control" id="'+obj.name+'stagnado" placeholder="stagnado em (0...100) dias de inatividade ">\
															</div>\
														</div>\
														<div class="form-group">\
															<label for="negocioEstagnadoFormInputGroup"><b>Negócio "estagnando":</b></label>\
															<div class="custom-control custom-radio custom-control-inline">\
																<input type="radio" id="'+obj.name+'Inline1" value="true" name="porcentagem" class="custom-control-input">\
																<label class="custom-control-label" for="'+obj.name+'Inline1">Ligado</label>\
															</div>\
															<div class="custom-control custom-radio custom-control-inline">\
																<input type="radio" id="'+obj.name+'Inline2" value="false" name="porcentagem" class="custom-control-input">\
																<label class="custom-control-label" for="'+obj.name+'Inline2">Desligado</label>\
															</div>\
														</div>\
													</div><!--modal col -->\
												</div>\
											</form>\
											<div class="row">\
												<div class="col-12 text-center">\
													<button type="button" class="btn btn-success save-etapa" data-companyid="'+obj.id+'" data-companyname="'+obj.name+'">Salvar</button>\
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>\
													<button type="button" id="etapa-delete" class="btn btn-danger etapa-delete">Apagar</button>\
												</div>\
											</div>\
										</div>\
									</div>\
									<div class="modal-footer">\
										<div class="row">\
											<div class="col-12" style="text-align:center">\
												<small class="text-gray text-center" style="text-align:center">Por favor, tenha em mente que as etapas de vendas serão compartilhadas com todos os usuários da empresa.</small>\
											</div>\
										</div>\
									</div>\
								</div>\
							</div>\
						</div><!--End Modal Etapa-->\
					</div>\
					<br>\
					<div id="'+obj.name+'-etapas" class="container mt-4 container-funil d-flex flex-row bd-highlight mb-3" style="overflow: auto;">\
					</div>\
					<br>\
					Probabilidade de negócio\
					<div class="btn-group btn-group-toggle" data-toggle="buttons">\
						<label class="btn btn-secondary btn-sm active">\
							<input type="radio" name="options" id="option1" autocomplete="off" checked> SIM\
						</label>\
						<label class="btn btn-secondary btn-sm">\
							<input type="radio" name="options" id="option2" autocomplete="off"> NÃO\
						</label>\
					</div>\
						Permite que você configure a porcentagem de probabilidade de êxito para cada negócio.\
				</div><!--END TAB -->\
			')
			;

			$.getJSON(dic['base_url']+'index.php/gestor_vendas/getEtapas', { company_id: obj.id }, function(etapas) {
				if (etapas instanceof Array && etapas.length) {
					$.each(etapas, function(j, etapa) {
						$('#'+obj.name+'-etapas').append(
							'<div class="funil float-left">\
								<div class="text-funil">\
									<h5><a href="#" data-toggle="modal" class="model-etapa-link" data-id="'+etapa.id+'" data-name="'+etapa.name+'" data-is_on="'+etapa.is_on+'" data-company_id="'+obj.id+'" data-companyname="'+obj.name+'" data-stagnado="'+etapa.stagnado+'" data-target="#'+obj.name+'Modal">'+etapa.name+'</a></h5>\
									<small>estagnando '+(etapa.is_on == '1' ? 'Ligado' : 'Desligado')+' </small>\
								</div>\
							</div><!--end funil-->\
						');
					});
				}
			});
			$('#myTabContent').append('<div class="clearfix"></div>');
		});
		$('.etapa-delete').css('display', 'none');
	});
});
