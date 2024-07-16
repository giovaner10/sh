$(document).ready(function() {

	document.getElementById('dataFechaNegocio').valueAsDate = new Date();

	// console.log( moment().format('LTS') );
	// console.log( moment.locale() );
	// console.log( moment("12-25-1995", "MM-DD-YYYY", ).format('llll') );

	$(document).on('click', 'a.addAtividade', function() {
		$('#save_agend').attr('data-id', $(this).attr('data-id'));
	});

	$('#save-model').click(function () {
		if ($('select#company').val() == 'Selecione o Funil') {
			alert('Por favor escolha um funil');
		} else {
			if($('#cnpj').val()!=""){
				$.getJSON(dic['base_url']+'index.php/gestor_vendas/consulta_cnpj/'+$('#cnpj').val().replace(/\D/g, ''),function(data){
					if(data.status=='ERROR'){
						alert("CNPJ inválido");
						return false;
					}
				});
			}
			$.post(dic['base_url']+'index.php/gestor_vendas/saveBusiness', {
				name_contact:       $('#name_contact').val(),
				company:            $('#organizacaoFormInputGroup').val(),
				title:              $('#tituloNegocioFormInputGroup').val(),
				value: 		        $('#valorNegocioFormInputGroup').val(),
				coin:               $('select#moeda').val(),
				funil_id:           $('select#company').val(),
				etapa_id:           $("input[name='options']:checked").val(),
				date:               $('#dataFechaNegocio').val(),
				phone_company:      $('#phone_company').val(),
				email_company:      $('#email_company').val(),
				phone_contact:      $('#phone_contact').val(),
				email_contact:      $('#email_contact').val(),
				type_phone:         $('#type_phone').val(),
				type_email:         $('#type_email').val(),
				cnpj: 				$('#cnpj').val(),
				provider: 			$('#fornecedor').val(),
				street: 			$('#street').val(),
				number: 			$('#number').val(),
				district: 			$('#district').val(),
				city: 				$('#city').val(),
				state: 				$('#state').val(),
				segmento: 			$('#segmento').val(),
				etapa_name: 		$("input[name='options']:checked").attr('data-name')
			}, function(callback) {
				console.log(callback);
				try {
					if(callback.status==false){
						window.location.replace(dic['base_url']+"index.php/acesso/entrar");
					}
				}
				catch(err) {
					console.log(err);
				}
				
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

	$('select#company').click(function() {
		let isNumber = function(n) { return !isNaN(parseFloat(n)) && isFinite(n); }
		let id = $(this).val();
		if (isNumber(id)) {
			$.getJSON(dic['base_url']+'index.php/gestor_vendas/getEtapas', { company_id: id }, function(callback) {
				$('#etapas').html('');
				$.each(callback, function(i, obj) {
					i == 0
					? $('#etapas').append(
							`<label class="btn btn-secondary btn-sm active p-2 flex-fill bd-highlight"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
									<input type="radio" name="options" id="option1" autocomplete="off" data-id="`+obj.id+`" data-name="`+obj.name+`" value="`+obj.id+`" checked="checked"> `+obj.name+`
							</label>`
						)
					: $('#etapas').append(
							`<label class="btn btn-secondary btn-sm p-2 flex-fill bd-highlight"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
									<input type="radio" name="options" id="option1" autocomplete="off" data-id="`+obj.id+`" data-name="`+obj.name+`" value="`+obj.id+`"> `+obj.name+`
							</label>`
						)
					;
				});
			});
		}
	});

	let editBusinessEtapa = function(etapa_id, etapa_name, $item) {
		$.post(dic['base_url']+'index.php/gestor_vendas/editBusinessEtapa', {
			id:       $item.attr('data-id'),
			etapa_id: etapa_id,
			etapa_old: $item.attr('data-etapa_id'),
			business: $item.attr('data-business'),
			etapa_old_name: $item.attr('data-etapa'),
			etapa_name: etapa_name
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

	let dragDropMoveObj = function($item, e, u) {
		let $ul = e.target;
		$item.fadeOut(function() {
			let $list = $('#'+$ul.attributes[0].value).appendTo( $('#'+$ul.attributes[0].value) );
			editBusinessEtapa( $list[0].attributes[0].value.substring(4), $list[0].attributes[1].value, $item );
			$item.appendTo( $list ).fadeIn(function() { });
		});
	}

	let dragDrop = function() {

		$('li', $('.drag-and-drop')).draggable({
			// cancel: 'a.ui-icon',
			revert: 'invalid',
			containment: 'document',
			helper: 'clone',
			cursor: 'move'
		});

		for (let i = 0; i < $('.drag-and-drop').length; i++) {
			$('#'+$('.drag-and-drop')[i].attributes[0].value).droppable({
				classes: {
					"ui-droppable-active": "custom-state-active"
				},
				drop: function(e, u) {
					dragDropMoveObj(u.draggable, e, u);
				}
			});
		}

		for (let i = 0; i < $('.drag-and-drop').children().length; i++) { $('.drag-and-drop').children()[i].style.width = ($('.drag-and-drop').width() - 3)+'px'; }
	}

	var select_negocios = [];
	let getEtapas = function() {
		$.getJSON(dic['base_url']+'index.php/gestor_vendas/getEtapas', { company_id: $('#prin').attr('data-id') }, function(callback) {
			$('#etapas').html('');
			$.each(callback, function(i, obj) {
				$('.container-negocios').append(
						'<div class="colum">\
							<div class="header-colum">\
								<h5><strong>'+obj.name+'</strong></h5>\
								<small id="small'+obj.id+'" class="text-gray" class="text-gray">R$0 - 0 negocios</small>\
							</div>\
							<ul id="drag'+obj.id+'" ondragenter="return dragEnter(event)" ondrop="return dragDrop(event)" ondragover="return dragOver(event)" data-id="'+obj.id+'" data-etapa="'+obj.name+'" class="drag drag-and-drop ui-helper-reset ui-helper-clearfix">\
							</ul>\
						</div>'
					);

				i == 0
				? $('#etapas').append(
						`<label class="btn btn-secondary btn-sm active p-2 flex-fill bd-highlight"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
								<input type="radio" name="options" id="option1" autocomplete="off" data-id="`+obj.id+`" data-name="`+obj.name+`" value="`+obj.id+`" checked="checked"> `+obj.name+`
						</label>`
					)
				: $('#etapas').append(
						`<label class="btn btn-secondary btn-sm p-2 flex-fill bd-highlight"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
								<input type="radio" name="options" id="option1" autocomplete="off" data-id="`+obj.id+`" data-name="`+obj.name+`" value="`+obj.id+`"> `+obj.name+`
						</label>`
					)
				;
				dados = { etapa_id: obj.id }
				if(funcionario){
					dados.user_id =funcionario;
				}
				$.getJSON(dic['base_url']+'index.php/gestor_vendas/getBusiness', dados, function(negocios) {
					
					try {
						if(negocios.status==false){
							window.location.replace(dic['base_url']+"index.php/acesso/entrar");
						}
					}
					catch(err) {
						console.log(err);
					}
					let value = 0.0;
					$.each(negocios, function(j, negocio) {
						empresas_select2.push( {
							id: negocio.id,
							text: negocio.title
						})
						select_negocios.push({id:negocio.id,text:negocio.title});
						let stag = (obj.is_on == '1' && negocio.diffStagnado > obj.stagnado) ? 'alert-stagnado' : '' ;

						if (negocio.diffTime && negocio.diffDays) {
							if (negocio.diffDays > 0) {
								$('#drag'+obj.id).append(
									'<li draggable="true" ondragstart="return dragStart(event)" id="'+negocio.id+'" class="content-colum '+stag+'" data-id="'+negocio.id+'"\
																data-name="'+negocio.name_contact+'"\
																data-organization="'+negocio.company+'"\
																data-business="'+negocio.title+'"\
																data-value="'+negocio.value+'"\
																data-company_id="'+negocio.funil_id+'"\
																data-etapa_id="'+negocio.etapa_id+'"\
																data-etapa="'+obj.name+'"\
																data-date="'+negocio.date+'"\ >\
										\
										<div style="width: 100%; height:100%;" onclick="location.href=\''+dic['base_url']+'index.php/gestor_vendas/deal/'+negocio.id+'\'">\
											<h6>'+negocio.title+'</h6>\
											<small class="text-gray">R$'+negocio.value+' negocios</small>\
										</div>\
										<div class="dropdown btn-status-atividade">\
											<button class="btn" type="button" id="dropdownMenuButton" style="background-color: transparent;margin: 0px;padding: 0px; width: 30px; height: 30px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
												<i class="fa fa-angle-right" style="color: gray"></i>\
											</button>\
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\
												<ul>\
													<li class="dropdown-item border-bottom">\
														<a class="dropdown-item" data-toggle="modal" data-target="#addAtividadeModal" href="#">\
															<input type="checkbox" class="agend_id" data-agend-id="'+negocio.agend_id+'" > Ligar\
															<small class="dropdown-item"><b style="color: gray">Entregar em '+negocio.diffDays+' dia(s)</b> - '+negocio.user_id+'</small>\
														</a>\
													</li>\
												</ul>\
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#addAtividadeModal" onclick="$(\'#save_agend\')[0].dataset.id=\''+negocio.id+'\'">+ Adicionar atividade</a>\
											</div>\
										</div>\
									</li>'
								);

							} else if (negocio.diffDays == 0) {
								if (negocio.diffTime[0] == '-') {
									$('#drag'+obj.id).append(
										'<li draggable="true" ondragstart="return dragStart(event)" id="'+negocio.id+'" class="content-colum '+stag+'" data-id="'+negocio.id+'"\
																	data-name="'+negocio.name_contact+'"\
																	data-organization="'+negocio.company+'"\
																	data-business="'+negocio.title+'"\
																	data-value="'+negocio.value+'"\
																	data-company_id="'+negocio.funil_id+'"\
																	data-etapa_id="'+negocio.etapa_id+'"\
																	data-etapa="'+obj.name+'"\
																	data-date="'+negocio.date+'"\ >\
											\
											<div style="width: 100%; height:100%;" onclick="location.href=\''+dic['base_url']+'index.php/gestor_vendas/deal/'+negocio.id+'\'">\
												<h6>'+negocio.title+'</h6>\
												<small class="text-gray">R$'+negocio.value+' negocios</small>\
											</div>\
											<div class="dropdown btn-status-atividade">\
												<button class="btn" type="button" id="dropdownMenuButton" style="background-color: transparent;margin: 0px;padding: 0px; width: 30px; height: 30px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
													<i class="fa fa-angle-right" style="color: green"></i>\
												</button>\
												<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\
													<ul>\
														<li class="dropdown-item border-bottom">\
															<a class="dropdown-item" data-toggle="modal" data-target="#addAtividadeModal" href="#">\
																<input type="checkbox" class="agend_id" data-agend-id="'+negocio.agend_id+'" > Ligar\
																<small class="dropdown-item"><b style="color: red">HOJE, Atrazado em '+negocio.diffTime.replace('-','')+'</b> - '+negocio.user_id+'</small>\
															</a>\
														</li>\
													</ul>\
													<a class="dropdown-item" href="#" data-toggle="modal" data-target="#addAtividadeModal" onclick="$(\'#save_agend\')[0].dataset.id=\''+negocio.id+'\'">+ Adicionar atividade</a>\
												</div>\
											</div>\
										</li>'
									);
								} else {
									$('#drag'+obj.id).append(
										'<li draggable="true" ondragstart="return dragStart(event)" id="'+negocio.id+'" class="content-colum '+stag+'" data-id="'+negocio.id+'"\
																	data-name="'+negocio.name_contact+'"\
																	data-organization="'+negocio.company+'"\
																	data-business="'+negocio.title+'"\
																	data-value="'+negocio.value+'"\
																	data-company_id="'+negocio.funil_id+'"\
																	data-etapa_id="'+negocio.etapa_id+'"\
																	data-etapa="'+obj.name+'"\
																	data-date="'+negocio.date+'"\ >\
											\
											<div style="width: 100%; height:100%;" onclick="location.href=\''+dic['base_url']+'index.php/gestor_vendas/deal/'+negocio.id+'\'">\
												<h6>'+negocio.title+'</h6>\
												<small class="text-gray">R$'+negocio.value+' negocios</small>\
											</div>\
											<div class="dropdown btn-status-atividade">\
												<button class="btn" type="button" style="background-color: transparent;margin: 0px;padding: 0px;width: 30px; height: 30px;" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
													<i class="fa fa-angle-right" style="color: green"></i>\
												</button>\
												<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\
													<ul>\
														<li class="dropdown-item border-bottom">\
															<a class="dropdown-item" data-toggle="modal" data-target="#addAtividadeModal" href="#">\
																<input type="checkbox" class="agend_id" data-agend-id="'+negocio.agend_id+'" > Ligar\
																<small class="dropdown-item"><b style="color: green">HOJE, restam '+negocio.diffTime+'</b> - '+negocio.user_id+'</small>\
															</a>\
														</li>\
													</ul>\
													<a class="dropdown-item" href="#" data-toggle="modal" data-target="#addAtividadeModal" onclick="$(\'#save_agend\')[0].dataset.id=\''+negocio.id+'\'">+ Adicionar atividade</a>\
												</div>\
											</div>\
										</li>'
									);
								}
							} else {
								$('#drag'+obj.id).append(
									'<li style="    background-color: #e84646;color: white;border-color: white;" draggable="true" ondragstart="return dragStart(event)" id="'+negocio.id+'" class="content-colum '+stag+'" data-id="'+negocio.id+'"\
																data-name="'+negocio.name_contact+'"\
																data-organization="'+negocio.company+'"\
																data-business="'+negocio.title+'"\
																data-value="'+negocio.value+'"\
																data-company_id="'+negocio.funil_id+'"\
																data-etapa_id="'+negocio.etapa_id+'"\
																data-etapa="'+obj.name+'"\
																data-date="'+negocio.date+'"\ >\
										\
										<div style="width: 100%; height:100%;" onclick="location.href=\''+dic['base_url']+'index.php/gestor_vendas/deal/'+negocio.id+'\'">\
											<h6>'+negocio.title+'</h6>\
											<small class="text-gray">R$'+negocio.value+' negocios</small>\
										</div>\
										<div class="dropdown btn-status-atividade">\
											<button class="btn" style="background-color: transparent;margin: 0px;padding: 0px;width: 30px; height: 30px;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
												<i class="fa fa-angle-right" style="color: #F90"></i>\
											</button>\
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\
												<ul>\
													<li class="dropdown-item border-bottom">\
														<a class="dropdown-item" data-toggle="modal" data-target="#addAtividadeModal" href="#">\
															<input type="checkbox" class="agend_id" data-agend-id="'+negocio.agend_id+'" > Ligar\
															<small class="dropdown-item"><b style="color: red">Atrazado há '+negocio.diffDays.replace('-','')+' dias</b> - '+negocio.user_id+'</small>\
														</a>\
													</li>\
												</ul>\
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#addAtividadeModal" onclick="$(\'#save_agend\')[0].dataset.id=\''+negocio.id+'\'">+ Adicionar atividade</a>\
											</div>\
										</div>\
									</li>'
								);
							}
						} else {
							$('#drag'+obj.id).append(
								'<li draggable="true" ondragstart="return dragStart(event)" id="'+negocio.id+'" class="content-colum '+stag+'" data-id="'+negocio.id+'"\
															data-name="'+negocio.name_contact+'"\
															data-organization="'+negocio.company+'"\
															data-business="'+negocio.title+'"\
															data-value="'+negocio.value+'"\
															data-company_id="'+negocio.funil_id+'"\
															data-etapa_id="'+negocio.etapa_id+'"\
															data-etapa="'+obj.name+'"\
															data-date="'+negocio.date+'"\ >\
									\
									<div style="width: 100%; height:100%;" onclick="location.href=\''+dic['base_url']+'index.php/gestor_vendas/deal/'+negocio.id+'\'">\
										<h6>'+negocio.title+'</h6>\
										<small class="text-gray">R$'+negocio.value+' negocios</small>\
									</div>\
									<div class="dropdown btn-status-atividade">\
										<button class="btn" type="button" style="background-color: transparent;margin: 0px;padding: 0px;width: 30px; height: 30px;"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
											<i class="fa fa-angle-right" style="color: #F90"></i>\
										</button>\
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\
											<a class="dropdown-item addAtividade" href="#" data-toggle="modal" data-target="#addAtividadeModal" onclick="$(\'#save_agend\')[0].dataset.id=\''+negocio.id+'\'">+ Adicionar atividade</a>\
										</div>\
									</div>\
								</li>'
							);
						}
						value += parseFloat(negocio.value.replace(',','.'));
					});
					$("#clientes").select2({
						data: empresas_select2
					})
					$('#small'+obj.id).text('R$'+value+' - '+negocios.length+' negocios' );
				// }).done(function() {
				// 	alert( "second success" );
				// }).fail(function() {
				// 	alert( "error" );
				// }).always(function() {
				// 	alert( "finished" );
				// 	location.reload();
				});
			});
			setTimeout(dragDrop, 200);
		});
	}

	$.getJSON(dic['base_url']+'index.php/gestor_vendas/getFunil', {}, function(callback) {
		$.each(callback, function(i, obj) {
			i == 0
			? $('#prin').text(obj.name).attr('data-name', obj.name).attr('data-id', obj.id)
			: $('#drop-btn').append('<button class="dropdown-item company-dropdown-item" data-name="'+obj.name+'" data-id="'+obj.id+'" type="button" name="'+obj.name+'">'+obj.name+'</button>')
			;
			$('select#company').append('<option value="'+obj.id+'">'+obj.name+'</option>');
		});
		/*$('#drop-btn').append('\
				<hr>\
				<a href="'+dic['base_url']+'index.php/gestor_vendas/company" class="dropdown-item" data-name="funil" >Configurações</a>\
			');*/
		$('#prin').attr('name', $('#prin').text());
		$('.container-negocios').html('');
		getEtapas();
	}).done(function() {
		// alert( "second success" );
	}).fail(function() {
		/*$('#drop-btn').append('\
				<hr>\
				<a href="'+dic['base_url']+'index.php/gestor_vendas/company" class="dropdown-item" data-name="funil" >Configurações</a>\
			');*/
	}).always(function() {
		// alert( "finished" );
		// location.reload();
	});;
	
	$(document).on('click', 'button.company-dropdown-item', function() {
		let axbtn = $('#prin').text();
		$('#prin').text($(this).text());
		$(this).text(axbtn);
		let axbtnid = $('#prin').attr('data-id');
		$('#prin').attr('data-id', $(this).attr('data-id'));
		$(this).attr('data-id', axbtnid);
		let axbtnname = $('#prin').attr('data-name');
		$('#prin').attr('data-name', $(this).attr('data-name'));
		$(this).attr('data-name', axbtnname);
		$('.container-negocios').html('');
		getEtapas();
	});

	$('#phone_company').mask('(00) 00000-0000');
	$('#phone_contact').mask('(00) 00000-0000');
	$('#cnpj').mask('00.000.000/0000-00', {reverse: true});
	$('#valorNegocioFormInputGroup').mask('000.000.000.000.000,00', {reverse: true});
});
