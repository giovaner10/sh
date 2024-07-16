// $('#destino option:selected').text(); // para texto (Ensino fundamental incompleto)
//     console.log($('.#destino').val()); // para valor (1)
function disableEnterKey(e) {
    var key;
    if (window.event)
        key = window.event.keyCode; //IE
    else
        key = e.which; //firefox      
    return (key != 13);
}
$(document).ready(function() {
	var boo = true;
	var pessoas = [];
	$.getJSON('gerencia_equipamentos/getClientes', function(data){
		pessoas = data;
	});
	if (boo) {
		boo = false;
		$('#listOrdem').html('');
		$.getJSON('gerencia_equipamentos/getTable', function(data) {
			$('#listOrdem').html('');
			$('#tabelaOrdens').dynatable({
				dataset: {
					records: data
				}
			});
		});
	}
	$('.dataJS').mask("99/99/9999");
	$(function() {
	    $('.dataJS').datepicker();
	});
	$(".dataJS").datepicker({
	    dateFormat: 'dd/mm/yy',
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    monthNames: ['Janeiro de','Fevereiro de','Março de','Abril de','Maio de','Junho de','Julho de','Agosto de','Setembro de','Outubro de','Novembro de','Dezembro de'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    nextText: 'Próximo',
	    prevText: 'Anterior'
	});
	var d = new Date();
	var data = ("0" + d.getDate()).substr(-2) + "/" + ("0" + (d.getMonth() + 1)).substr(-2) + "/" + d.getFullYear();
	$('.dataJS').val(data);
	$(document).on('click', '#um', function() {
		//preenche o modal 11 para assinar uma data de chegada do envio do equipamento
		idOrdem = $(this)[0].dataset.id;
		$('#ordem_1').val(idOrdem);
		$('#serial_1').val($(this)[0].dataset.serial);
		$('#topSecret_1').val(idOrdem);
		$('#upload_1').val($(this)[0].dataset.upload);
		//preenche todas os imputs hidden de data com a data cadastro preenchida na ordem que veio do BD
		$('.dataCompare').val($(this)[0].dataset.lastdate);
		$('#form_1').fadeOut("fast", function(){
			$('#conjunto').hide();
			$('.gravaDados h4').text('Carregando dados...');
			$('#process_1').fadeIn(function() {
				$.getJSON('gerencia_equipamentos/getPairs', {'ordem':idOrdem}, function(data){
					$('.gravaDados h4').text('Gravando dados...');
					$('#process_1').hide(function() {
						$('#form_1').fadeIn(function() {
							if(data){
								if (data.length>1) {
									$('#cascatear1').prop("checked", false);
									$('.ordensIguais').empty();
									$('#conjunto div p').text('O sistema identificou que você está alterarando uma Ordem similar a outras '+(data.length-1)+' Ordens. Ative o modo cascata para a alteração afetar as demais ordens com: ');
									for (var i = 0; i < data.length; i++) {
										if(data[i].idlogistica != idOrdem){
											$('.ordensIguais').append(  '<div class="col-sm-6" >'+
																			'<strong>Id: </strong>'+data[i].idlogistica+
																			'<strong> Serial: </strong>'+data[i].equipamento+
																	    '</div>'
																	);
										}
										if(i==(data.length-1)){
											$('#conjunto').show('slow');
										}
									}
								}
							}	
						});
					});
				});
			});
		});
		
	});
	$(document).on('click', '#dois', function() {
		idOrdem = $(this)[0].dataset.id;
		//adiciona o label e um campo de clientes.
		$('.codCliente').empty();
		if (($(this)[0].dataset.tecparacli) == 1) {
			console.log($(this)[0].dataset.tecparacli);
			$('.codCliente').append('<label class="form-label col-sm-5 ">Código do cliente ao qual equipamento foi instalado: </label>'+
									'<div id="divCli" class="col-sm-4 divCli">'+
										'<input class="form-control autoCliente" type="text" id="cliente_1" name="cliente_1" placeholder="Código do cliente" required></input>'+
									'</div>');
			$('.autoCliente').autocomplete({ source: pessoas, minLength: 3});
		}
		$(".autoCliente").focusout(function() {
			if(jQuery.inArray($(this).val(), pessoas) != -1) {
				$('.divCli').removeClass('has-error');
				$('.divCli').addClass('has-success');
			}else{
				$('.divCli').addClass('has-error');
				$('.divCli').removeClass('has-success');
				$('.autoCliente').attr('placeholder', 'Cliente inexistente.');
				$('.autoCliente').val('');
				$('.dataJS').attr('placeholder', 'Insira a data.');
				$('.dataJS').val('');
			}
		});
		//Preenche o modal 21 para assinar Instalação de OS do tipo: Manutenção
		$('#ordem_3').text(idOrdem);
		$('#serial_3').text($(this)[0].dataset.serial);
		$('#topSecret_3').val(idOrdem);
		$('#serial_old_1').val($(this)[0].dataset.oldserial);
		//Preenche o modal 22 para assinar Instalação de OS do tipo: Instalação
		$('#ordem_4').text(idOrdem);
		$('#serial_4').text($(this)[0].dataset.serial);
		$('#topSecret_4').val(idOrdem);
		//preenche todas os imputs hidden de data com a data de chegada assinalada na ordem que veio do BD
		$('.dataCompare').val($(this)[0].dataset.lastdate);
	});
	$('#tipoEnvio_1').change(function () {
		esValor = $("#tipoEnvio_1 option:selected").val();
		if (esValor==1) {
			$('#tipoCorreio').fadeIn();
			$('#tipoCargo').hide();
			$('#informacao').text('Cód. rastreio:');
		}else if (esValor==2){
			$('#tipoCargo').fadeIn();
			$('#tipoCorreio').hide();
			$('#informacao').text('Cód. rastreio:');
		}else{
			$('#tipoCargo').hide();
			$('#tipoCorreio').hide();
			$('#informacao').text('Informações:');
		}
	});
	$(document).on('click', '#submit_1', function() {
		if($('#dataChegada_1').val()!="") {
			var dataDigi = $('#dataChegada_1').val();
			var lastdate = $('#dataCompare').val();
			if(moment(dataDigi.split('/').reverse().join('-')).isBefore(lastdate)) {
				$('#dataChegada_1').val("");
				alert("A data informada está inválida!\nA data de chegada do equimaneto não pode ser anterior a data de envio do mesmo.");
			}else{
				$('#form_1').fadeOut("fast", function(){
					$('#process_1').fadeIn();
				});
			}
		}
	});
	// $(document).on('click', '#submit_2', function() {
	// 	if ($('#dataChegada_2').val()!="") {
	// 		var dataDigi = $('#dataChegada_2').val();
	// 		var lastdate = $('#dataCompare').val();
	// 		if(moment(dataDigi.split('/').reverse().join('-')).isBefore(lastdate)) {
	// 			$('#dataChegada_2').val("");
	// 			alert("A data informada está inválida!\nA data de chegada do equimaneto não pode ser anterior a data de envio do mesmo.");
	// 		}else{
	// 			$('#form_2').fadeOut("fast", function(){
	// 				$('#process_2').fadeIn();
	// 			});
	// 		}
	// 	}
	// });
	$(document).on('click', '#submit_3', function() {
		if ($("#tipoEnvio_1 option:selected").val()==0) {
			$('#tipoEnvio_1').addClass('selecao');
			$('#rastreio_1').val('');
			$("#rastreio_1").attr('placeholder', 'Falta o tipo');
		}else{
			if(($('#dataInstal_1').val()!="")&&($('#serial_old_1').val()!="")&&($('#rastreio_1').val()!="")) {
				var dataDigi = $('#dataInstal_1').val();
				var lastdate = $('#dataCompare').val();
				if(moment(dataDigi.split('/').reverse().join('-')).isBefore(lastdate)) {
					$('#dataInstal_1').val("");
					alert("A data informada está inválida!\nA data de instalação do equimaneto não pode ser anterior a data de chegada do mesmo.");
				}else{
					$('#form_3').fadeOut("fast", function() {
						$('#process_3').fadeIn();
					});
				}
			}
		}
	});
	$(document).on('click', '#submit_4', function() {
		if ($('#dataInstal_2').val()!="") {
			var dataDigi = $('#dataInstal_2').val();
			var lastdate = $('#dataCompare').val();
			if(moment(dataDigi.split('/').reverse().join('-')).isBefore(lastdate)) {
				$('#dataInstal_2').val("");
				alert("A data informada está inválida!\nA data de instalação do equimaneto não pode ser anterior a data de chegada do mesmo.");
			}else{
				$('#form_4').fadeOut("fast", function(){
					$('#process_4').fadeIn();
				});
			}
		}
	});
	
	$(document).on('click', '#okay', function() {
		$("#mensagem").slideUp(500);
		$.getJSON('gerencia_equipamentos/unsetSessionData', function(data) {});
	});
});