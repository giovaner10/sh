$('span.label-stagnado').css('display', 'none');
$(document).ready(function() {

	console.log(window.location);
	$('[data-toggle="tooltip"]').tooltip();

	Dropzone.options.dzone = {  maxFiles: 2,  };

	$('#gain').click(function() {
		$.post(dic['base_url']+'index.php/gestor_vendas/gain', { id: $('#h4-title').attr('data-id') }, function(callback) { })
		.done(function() {
			alert( "second success" );
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
			// window.location = dic['base_url']+'index.php/gestor_vendas';
		});
	});

	$('#lost').click(function() {
		if ( $('#commentPerda').val() && $('#motivoPerda').val()) {
			let boo = '';
			$.post(dic['base_url']+'index.php/gestor_vendas/lost', { 
				id: $('#h4-title').attr('data-id'),
				comment: $('#commentPerda').val(),
				motive: $('#motivoPerda').val(),
			}, function(callback) {
				boo = callback;
				console.log(callback);
			}).done(function() {
				alert( "second success" );
			}).fail(function() {
				alert( "error" );
			}).always(function() {
				alert( "finished" );
				if (boo) window.location = dic['base_url']+'index.php/gestor_vendas';
			});
		} else {
			alert( "CAMPOS EM BRANCO!" );
		}
	});

	$('#delete-business').click(function() {
		let boo = '';
		$.post(dic['base_url']+'index.php/gestor_vendas/deleteBusiness', {
			id: $('#h4-title').attr('data-id'),
			etapa_id: $('#h4-title').attr('data-etapa')
		}, function(callback) {
			boo = callback;
			console.log(callback);
		}).done(function() {
			alert( "second success" );
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
			if (boo) window.location = dic['base_url']+'index.php/gestor_vendas';
		});
	});

	$('#save-name-business').click(function() {
		if ( $('#name-business').val() ) {
			$.post(dic['base_url']+'index.php/gestor_vendas/editNameBusiness', {
				id: $('#h4-title').attr('data-id'),
				name: $('#name-business').val()
			}, function(callback) {
				console.log(callback);
			}).done(function() {
				// alert( "second success" );
			}).fail(function() {
				// alert( "error" );
			}).always(function() {
				// alert( "finished" );
			});
		}
	});

	$('#person').click(function() { 
		$('form#deal_personal').find('.form-hidden').css('display', 'initial');
		$('#type_phone').val( $(this).attr('data-type-phone') );
		$('#type_email').val( $(this).attr('data-type-email') );
	});
	$('#person_cancelar').click(function() { $('form#deal_personal').find('.form-hidden').css('display', 'none'); });
	$('#person_save').click(function() {
		$('label[for=inputPerson]').text($('#name_contact').val());
		$('label[for=inputTelefone]').text('Telefone: '+$('#phone_contact').val()+' ('+$('#type_phone').val()+')');
		$('label[for=inputEmail]').text('Email: '+$('#email_contact').val()+' ('+$('#type_email').val()+')');
		$.post(dic['base_url']+'index.php/gestor_vendas/editPerson', {
			id: $('#h4-title').attr('data-id'),
			phone: $('#phone_contact').val(),
			email: $('#email_contact').val(),
			type_phone: $('#type_phone').val(),
			type_email: $('#type_email').val(),
			name: $('#name_contact').val()
		}, function(callback) {

		}).done(function() {
			$('form#deal_personal').find('.form-hidden').css('display', 'none');
			alert( "second success" );
		}).fail(function() {
			alert( "error" );
		}).always(function() {
			alert( "finished" );
		});
	});

	$('#org').click(function() { $('form#deal_org').find('.form-hidden').css('display', 'initial'); });
	$('#org_cancelar').click(function() { $('form#deal_org').find('.form-hidden').css('display', 'none'); });
	$('#org_save').click(function() {
		if($('#cnpj').val()!=""){
			$.getJSON(dic['base_url']+'index.php/gestor_vendas/consulta_cnpj/'+$('#cnpj').val().replace(/\D/g, ''),function(data){
				if(data.status=='ERROR'){
					alert("CNPJ inválido");
					return false;
				}
			});
		}
		$('label[for=inputEndereco]').text('Endereço: Rua '+$('#street').val()+' Nº '+$('#number').val()+', '+$('#district').val()+', '+$('#city').val()+', '+$('#state').val());
		$('label[for=inputCNPJ]').text('CNPJ: '+$('#cnpj').val());
		$('label[for=inputSegmento]').text('Segmento: '+$('#segmento').val());
		$('label[for=inputPhoneCompany]').text('Telefone: '+$('#phone_company').val());
		$('label[for=inputEmailCompany]').text('Email: '+$('#email_company').val());
		$.post(dic['base_url']+'index.php/gestor_vendas/editOrg', {
			id: $('#h4-title').attr('data-id'),
			street: $('#street').val(),
			number: $('#number').val(),
			district: $('#district').val(),
			city: $('#city').val(),
			state: $('#state').val(),
			cnpj: $('#cnpj').val(),
			segmento: $('#segmento').val(),
			phone_company: $('#phone_company').val(),
			email_company: $('#email_company').val()
		}, function(callback) {
			console.log(callback);
		}).done(function() {
			$('form#deal_org').find('.form-hidden').css('display', 'none');
		// alert( "second success" );
		}).fail(function() {
			// alert( "error" );
		}).always(function() {
			// alert( "finished" );
		});
	});

	$('#provider').click(function() { $('form#deal_details').find('.form-hidden').css('display', 'initial'); });
	$('#provider_cancelar').click(function() { $('form#deal_details').find('.form-hidden').css('display', 'none'); });
	$('#provider_save').click(function() { 
		$('label[for=inputFornecedor]').text('Fornecedor: '+$('#inputFornecedor').val());
		$.post(dic['base_url']+'index.php/gestor_vendas/editProvider', {
			id: $('h4-title').attr('data-id'),
			provider: $('#inputFornecedor').val()
		}, function(callback) {
			console.log(callback);
		}).done(function() {
			$('form#deal_details').find('.form-hidden').css('display', 'none');
		// alert( "second success" );
		}).fail(function() {
			// alert( "error" );
		}).always(function() {
			// alert( "finished" );
		});
	});

	$.getJSON(dic['base_url']+'index.php/gestor_vendas/getEtapas', { company_id: $('#h4-title').attr('data-funil') }, function(callback) {
		$('#etapa').html('');
		$.each(callback, function(i, obj) {
			if (obj.id == $('#h4-title').attr('data-etapa')) {
				$('#etapa').append(
					`<label id="label-`+obj.id+`" data-etapa="`+obj.id+`" data-business="`+$('#h4-title').attr('data-id')+`" class="btn btn-secondary btn-sm border active p-2 flex-fill bd-highlight" data-toggle="tooltip" data-placement="bottom" title="`+obj.name+`">
						<input type="radio" name="options" id="option1" autocomplete="off" checked>
					</label>`);
				$('#h4-title').attr('data-etapa-name', obj.name);
				$('#h4-title').attr('data-etapa-is-on', obj.is_on);
				$('#h4-title').attr('data-etapa-stagnado', obj.stagnado);
				obj.is_on == '1' && $('#h4-title').attr('data-diffStagnado') > obj.stagnado ? $('span.label-stagnado').css('display', '') : $('span.label-stagnado').css('display', 'none') ;
			} else {
				$('#etapa').append(
					`<label id="label-`+obj.id+`" data-etapa="`+obj.id+`" data-business="`+$('#h4-title').attr('data-id')+`" class="btn btn-secondary btn-sm border p-2 flex-fill bd-highlight" data-toggle="tooltip" data-placement="bottom" title="`+obj.name+`">
						<input type="radio" name="options" id="option1" autocomplete="off" > <!--<i class="fa fa-spinner fa-spin" style="font-size:15px"></i>-->
					</label>`);
			}
			diffEtapaBusiness(obj.id);
		});
		idadeNegocio();
	}).done(function() {
		// alert( "second success" );
	}).fail(function() {
		// alert( "error" );
	}).always(function() {
		// alert( "finished" );
	});

	let editBusinessEtapa = function(etapa_id, business_id, label, text) {
		let etapa_old = $('#h4-title').attr('data-etapa');
		if (etapa_id == etapa_old ? false : true) {
			$.post(dic['base_url']+'index.php/gestor_vendas/editBusinessEtapa', {
				id:       business_id,
				etapa_id: etapa_id,
				etapa_old: etapa_old,
				business: $('#h4-title').attr('data-title'),
				etapa_old_name: $('#h4-title').attr('data-etapa-name'),
				etapa_name: label.attr('title')
			}, function(callback) {
				label.html('');
				$('input[type=radio]').attr('checked', false);
				label.html('<input type="radio" name="options" id="option1" autocomplete="off" checked="checked">');
				diffEtapaBusiness(etapa_id);
				label.addClass('active');
				$('#h4-title').attr('data-etapa-name', label.attr('title'));
			}).done(function() {
				location.reload();
				// alert( "second success" );
			}).fail(function() {
				// alert( "error" );
			}).always(function() {
				// alert( "finished" );
			});
		} else {
			alert('Escolher outa etapa');
			label.html('');
			label.html('<input type="radio" name="options" id="option1" autocomplete="off" checked="checked">');
			label.append(text);

		}
	}

	$(document).on('click', 'label.btn.btn-secondary', function() {
		$('label.btn.btn-secondary').removeClass('active');
		let text = $(this).text().replace(/[	\n]/g, '');
		$(this).html('');
		$(this).html('<i class="fa fa-spinner fa-spin" style="font-size:15px"></i>');
		editBusinessEtapa($(this).attr('data-etapa'), $(this).attr('data-business'), $(this), text);
	});
	// código duplicado
	let diffEtapaBusiness = function(id) {
		$.getJSON(dic['base_url']+'index.php/gestor_vendas/diffEtapaBusiness', { 
			business_id: $('#h4-title').attr('data-id'),
			etapa_id: id,
		}, function(callback) {
			if (callback.length) {
				$.each(callback, function(i, obj) {
					$('label#label-'+id).append(obj.diff == '-1' ? '0 dias' : obj.diff+' dias');
				});
			} else {
				$('label#label-'+id).append('0 dias');
			}
		}).done(function() {
			// alert( "second success" );
			// return callback;
		}).fail(function() {
			// alert( "error" );
		}).always(function() {
			// alert( "finished" );
		});
	}

	let idadeNegocio = function() {
		let created = $('#h4-title').attr('data-created');
		let dh  = created.split(' ');
		let d = dh[0].split('-');
		let h = dh[1].split(':');
		let date1 = new Date(d[0], d[1]-1, d[2], h[0], h[1], h[2]);
		let date2 = new Date();
		let timeDiff = Math.abs(date2.getTime() - date1.getTime());
		let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
		$('#idade').text('Idade do negócio - '+diffDays+' dias');
		$('#created').text('Criado em '+d[2]+'/'+d[1]+'/'+d[0]);
	}

	$('#nota_cancelar').click(function() { $('#tomarNotaTextarea1').val(''); });
	$('#nota_save').click(function() {
		if ($('#tomarNotaTextarea1').val()) {
			$.post(dic['base_url']+'index.php/gestor_vendas/addNotation', {
				id: $('#h4-title').attr('data-id'),
				nota: $('#tomarNotaTextarea1').val()
			}, function(callback) {
				console.log(callback);
			}).done(function() {
				$('#tomarNotaTextarea1').val('');
				// alert( "second success" );
			}).fail(function() {
				// alert( "error" );
			}).always(function() {
				// alert( "finished" );
			});
		}
	});

	$.getJSON(dic['base_url']+'index.php/gestor_vendas/getHistoric', { id: $('#h4-title').attr('data-id'), }, function(callback) {
		let ax = 0;
		let ve = ['pos-left', 'pos-right'];
		let lr = ['right', 'left'];
		var date2 = false;
		var timeDiff = false;
		var diffDays = false; 
		var month = false; //months from 1-12
		var day = false;
		var year = false;
		$.each(callback.agend, function(i, obj) {
			let stag = ($('#h4-title').attr('data-etapa-is-on') == '1' && $('#h4-title').attr('data-diffStagnado') > $('#h4-title').attr('data-etapa-stagnado')) ? 'alert-stagnado' : '' ;
			//let diffDays = $('#h4-title').attr('data-diffDays');
			let diffTime = $('#h4-title').attr('data-diffTime');
			let companyName = $('#h4-title').attr('data-company');
			let realized = obj.realized == 1 ? 'checked' : '';
			
			date2 = new Date(obj.date);
			diffDays = '-';
			month = date2.getUTCMonth() + 1; //months from 1-12
			day = date2.getUTCDate();
			year = date2.getUTCFullYear();
			//console.log(diffDays);
			//console.log(obj.date);
			if(obj.date!='0000-00-00'){
				timeDiff = Math.abs(date2.getTime() - tempo_agora.getTime());
				diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
				if(date2.getTime() < tempo_agora.getTime()){
					diffDays *= -1;
				}
			}
			if(month<10){
				month = "0"+month;
			}
			if(day<10){
				day = "0"+day;
			}
			if(!obj.id_user ){
				obj.id_user="" ;
			}
			if(!obj.notation ){
				obj.notation="" ;
			}
			//console.log(timeDiff);
			console.log(diffDays);
			if (diffDays > 0) {
				
				$('dl#activity').append(`
					<dd class="`+ve[ax]+` clearfix `+stag+`">
						<div class="circ"></div>
						<div class="time">`+day+"/"+month+`</div>
						<div class="events">
							<div class="events-header">
							<input type="checkbox" `+realized+` class="agend_id" data-agend-id="`+obj.id+`" > `+obj.activity+` - <b style="color:gray">em `+diffDays+` dia(s)</b></div>
							<div class="events-body">
								<div class="row" style="display: block;">
									<div class="events-desc">
										<i class="fas fa-user"></i> `+obj.id_user +` <i class="fas fa-building"></i> `+companyName+`
									</div>
								</div>

								<div class="events-footer">
									`+obj.notation+`
								</div>
							</div>
						</div>
					</dd>
				`);
			} else if (diffDays == 0) {
				if (diffTime[0] == '-') {
					$('dl#activity').append(`
						<dd class="`+ve[ax]+` clearfix `+stag+`">
							<div class="circ"></div>
							<div class="time">`+day+"/"+month+`</div>
							<div class="events">
								<div class="events-header">
								<input type="checkbox" `+realized+` class="agend_id" data-agend-id="`+obj.id+`" > `+obj.activity+` - <b style="color:red">HOJE, Atrazado em `+diffDays+` dia(s)</b></div>
								<div class="events-body">
									<div class="row" style="display: block;">
										<div class="events-desc">
											<i class="fas fa-user"></i> `+obj.id_user +` <i class="fas fa-building"></i> `+companyName+`
										</div>
									</div>

									<div class="events-footer">
										`+obj.notation+`
									</div>
								</div>
							</div>
						</dd>
					`);
				} else {
					$('dl#activity').append(`
						<dd class="`+ve[ax]+` clearfix `+stag+`">
							<div class="circ"></div>
							<div class="time">`+day+"/"+month+`</div>
							<div class="events">
								<div class="events-header">
								<input type="checkbox" `+realized+` class="agend_id" data-agend-id="`+obj.id+`" > Ligar - <b style="color:green">HOJE, restam `+diffTime+` dia(s)</b></div>
								<div class="events-body">
									<div class="row" style="display: block;">
										<div class="events-desc">
											<i class="fas fa-user"></i> `+obj.id_user +` <i class="fas fa-building"></i> `+companyName+`
										</div>
									</div>

									<div class="events-footer">
										`+obj.notation+`
									</div>
								</div>
							</div>
						</dd>
					`);
				}
			} else {
				$('dl#activity').append(`
					<dd class="`+ve[ax]+` clearfix `+stag+`">
						<div class="circ"></div>
						<div class="time">`+day+"/"+month+`</div>
						<div class="events">
							<div class="events-header">
							<input type="checkbox" `+realized+` class="agend_id" data-agend-id="`+obj.id+`" > Ligar - <b style="color:red"> Atrazado em `+diffDays+` dia(s)</b></div>
							<div class="events-body">
								<div class="row" style="display: block;">
									<div class="events-desc">
										<i class="fas fa-user"></i> `+obj.id_user +` <i class="fas fa-building"></i> `+companyName+`
									</div>
								</div>

								<div class="events-footer">
									`+obj.notation+`
								</div>
							</div>
						</div>
					</dd>
				`);
			}

			$('dl#activity-historic').append(`
					<dd class="`+ve[ax]+` clearfix">
						<div class="circ"></div>
						<div class="time" style="`+(ax == 0 ? 'width: 122px;' : 'width: 122px; left: 48%;')+`">`+obj.created_on.substring(0, 10)+`</div>
						<div class="events">
							<div class="events-header"><i class="fas fa-calendar-alt"></i> Atividade</div>
							<div class="events-body">
								<div class="row" style="display: block;">
									<div class="events-desc">
									Tipo da atividade: `+obj.type+`<br>
									Atividade: `+obj.activity+`<br>
									Data da atividade: `+obj.date+`<br>
									Horário: `+obj.hour+`<br>
									Duração: `+obj.duration+`<br>
									Realizada: `+(obj.realized == '1' ? 'Sim' : 'não' )+`<br>
									Nota: `+obj.notation+`
									</div>
								</div>
							</div>
						</div>
					</dd>
				`);
			ax = (ax == 0 ? 1 : 0);
		});

		$.each(callback.notation, function(i, obj) {
			$('dl#notation').append(`
					<dd class="`+ve[ax]+` clearfix">
						<div class="circ"></div>
						<div class="time" style="`+(ax == 0 ? 'width: 122px;' : 'width: 122px; left: 48%;')+`">`+obj.created_on.substring(0, 10)+`</div>
						<div class="events">
							<div class="events-header"><i class="fas fa-sticky-note"></i> Nota</div>
							<div class="events-body">
								<div class="row" style="display: block;">
									<div class="events-desc">
									`+obj.text+`
									</div>
								</div>
							</div>
						</div>
					</dd>
				`);
			ax = (ax == 0 ? 1 : 0);
		});

		$.each(callback.historic, function(i, obj) {
			$('dl#all').append(`
					<dd class="`+ve[ax]+` clearfix">
						<div class="circ"></div>
						<div class="time" style="`+(ax == 0 ? 'width: 122px;' : 'width: 122px; left: 48%;')+`">`+obj.created_on.substring(0, 10)+`</div>
						<div class="events">
							<div class="events-header"><i class="fas fa-history"></i> Histórico</div>
							<div class="events-body">
								<div class="row" style="display: block;">
									<div class="events-desc">
									`+obj.description+`
									</div>
								</div>
							</div>
						</div>
					</dd>
				`);
			ax = (ax == 0 ? 1 : 0);
		});
	}).done(function() {
		// alert( "second success" );
	}).fail(function() {
		// alert( "error" );
	}).always(function() {
		// alert( "finished" );
	});

	$('.VivaTimeline').vivaTimeline({
		carousel: true,
		carouselTime: 3000
	});

	$('#phone_contact').mask('(00) 00000-0000');
	$('#cnpj').mask('00.000.000/0000-00', {reverse: true});
	
});
