$(document).ready(function() {
	var pessoas = [];
    $('#placa').mask("***-****");
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

	$('#destino').change(function () {
		var es = document.getElementById('destino');
		esValor = es.options[es.selectedIndex].value;
		if (esValor==2) {
			// $.getJSON('getClientes', function(data){
			// 	pessoas=data;
			// 	$('#cliente').autocomplete({ source: pessoas, minLength: 3});
			// });
			$('#pesqnome').select2({width: '400px !important'});
			$('.pesqnome').select2({
				ajax: {
					url: '../clientes/ajaxListSelect',
					dataType: 'json',
					delay: 1000,
				},
				placeholder: "Selecione o cliente",
				allowClear: true,
				minimumInputLength: 3,
			});

			$('#input_cli').fadeIn();
			$('#input_tec').hide();
			$("#cliente").attr('required',true);
			$("#instalador").removeAttr('required');

		}else if (esValor==1) {

			// $.getJSON('getInstaladores', function(data){
			// 	pessoas=data;
			// 	$('#instalador').autocomplete({ source: pessoas, minLength: 3});
			// });

			$('#pesqInstalador').select2({width: '400px !important'});
			$('.pesqInstalador').select2({
				ajax: {
					url: '../instaladores/InstaladorListSelect',
					dataType: 'json',
					delay: 1000,
				},
				placeholder: "Selecione o instalador",
				allowClear: true,
				minimumInputLength: 3,
			});

			$('#input_cli').hide();
			$('#input_tec').fadeIn();
			$("#instalador").attr('required',true);
			$("#cliente").removeAttr('required');
		}else{
			$('#input_cli').hide();
			$('#input_tec').hide();
			$("#cliente").removeAttr('required');
			$("#instalador").removeAttr('required');
		}
	});

	$('#tipo').change(function () {
		var es1 = document.getElementById('tipo');
		esValor1 = es1.options[es1.selectedIndex].value;
		if (esValor1==1) {
			$('#input_devolucao').fadeIn();
			$("#serialAntigo").attr('required',true);
		}else{
			$('#input_devolucao').fadeOut();
			$("#serialAntigo").removeAttr('required');
		}
	});

	$('#tipoEnvio').change(function () {
		var es = document.getElementById('tipoEnvio');
		esValor = es.options[es.selectedIndex].value;
		if (esValor==1) {
			$('#rastreio').fadeIn();
			$('#complemento').hide();
			$('#tipoCorreio').fadeIn();
			$('#tipoCargo').hide();
			$("#infoTipo").attr('required',true);
		}else if (esValor==2){
			$('#rastreio').fadeIn();
			$('#complemento').hide();
			$('#tipoCorreio').hide();
			$('#tipoCargo').fadeIn();
			$("#infoTipo").attr('required',true);
		}else{
			$('#rastreio').hide();
			$('#complemento').fadeIn();
			$('#tipoCorreio').hide();
			$('#tipoCargo').hide();
			$("#infoTipo").removeAttr('required');
		}
	});

	$('#checar').click(function() {
		var d = new Date();
		var data = ("0" + d.getDate()).substr(-2) + "/" + ("0" + (d.getMonth() + 1)).substr(-2) + "/" + d.getFullYear();
	    if ($('#checar').is(':checked')) {
	    	$('#dataEnvio').attr('disabled', 'true');
	    	$('#dataEnvio').val(data);
	    }else{
	    	$('#dataEnvio').removeAttr('disabled');
	    	$('#dataEnvio').val();
	    }
	});

	$('#limpar').click(function() {
		$("#dados input").val("");
	});

	$('#okay').click(function() {
		$("#mensagem").slideUp(500);
		$.getJSON('gerencia_equipamentos/unsetSessionData', function(data) {});
	});

	$('#adicionar').click(function (e) {
		var es = document.getElementById('destino');
		esValor = es.options[es.selectedIndex].value;
		if (esValor==0) {
			alert('Você não escolheu o destino do equipamento.');
			$('#destino').addClass('selecao');
			return false;
		}else{
			$('#destino').removeClass('selecao');
		}
		es = document.getElementById('tipo');
		esValor = es.options[es.selectedIndex].value;
		if (esValor==0) {
			alert('Por favor, escolha um tipo de OS.');
			$('#tipo').addClass('selecao');
			return false;
		}else{
			$('#tipo').removeClass('selecao');
		}
		es = document.getElementById('tipoEnvio');
		esValor = es.options[es.selectedIndex].value;
		if (esValor==0) {
			alert('Opte por uma opção de envio.');
			$('#tipoEnvio').addClass('selecao');
			return false;
		}else{
			$('#tipoEnvio').removeClass('selecao');
		}

		e.preventDefault();
		var data = $('#formOrdem').serialize();
		$('#adicionar').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Adicionando...')
		$.ajax({
            type: 'post',
            dataType: 'json',
            url: 'cadOrdem_ajax',
            data: data,
            success: function (callback) {
				alert(callback.msg);
				$('#adicionar').removeAttr('disabled').html('Adicionar');
				$("#dados input").val("");
            },
            error: function (e) {
                alert("Erro ao adicionar, tente novamente!");
				$('#adicionar').removeAttr('disabled').html('Adicionar')
            }
        });
	});

	$("#serial").focusout(function() {
		var texto = $('#serial').val();
		$('#problema').hide();
		$('#problema').empty();
		if(texto == "") {
			$('#divSerial').removeClass('has-success');
			$('#divSerial').removeClass('has-error');
			$('#modelo').val("");
			$('#recebimento').val("");
			$('#dataConfig').val("");
			$('#linha').val("");
			$('#operadora').val("");
			$('#ping').val("");
			$('#placa').val("");
		}else{
			$.getJSON('getTracker', {'serial':texto}, function(data){
				if(data) {
					$('#modelo').val(data.modelo);
					$('#recebimento').val(data.data_recebimento);
					$('#dataConfig').val(data.data_cadastro);
					$('#linha').val(data.num_chip);
					$('#operadora').val(data.operadora);
					$('#ping').val(data.ping);
					$('#placa').val(data.placa);
					$('#divSerial').addClass('has-success');
					$('#divSerial').removeClass('has-error');
					if (data.num_chip=="Chip não vinculado"){
						$('#problema').append('<a class="text-danger" href="https://gestor.showtecnologia.com/sistema/newapp/index.php/equipamentos/listar?palavra='+texto+'&coluna=serial" target="_blank"><b>Clique e vincule uma linha. <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></b></a>');
						$('#problema').show(1000);
					}
				}else{
					$('#divSerial').removeClass('has-success');
					$('#divSerial').addClass('has-error');
					$('#modelo').val("");
					$('#recebimento').val("");
					$('#dataConfig').val("");
					$('#linha').val("");
					$('#operadora').val("");
					$('#ping').val("");
					$('#placa').val("");
				}
			});
		}
	});

	$("#cliente").focusout(function() {
		var texto = $('#cliente').val();
		if(texto == "") {
			$('#divCli').addClass('has-error');
			$('#divCli').removeClass('has-success');
			$("#cliente").attr('placeholder', 'Informe o código do cliente');
			$("#cliente").val('');
		}else{
			if(jQuery.inArray(texto, pessoas) != -1) {
				$('#divCli').removeClass('has-error');
				$('#divCli').addClass('has-success');
			}else{
				$('#divCli').addClass('has-error');
				$('#divCli').removeClass('has-success');
				$("#cliente").attr('placeholder', 'Cliente inexistente.');
				$("#cliente").val('');
			}
		}
	});
	$("#instalador").focusout(function() {
		var texto = $('#instalador').val();
		if(texto == "") {
			$('#divTec').addClass('has-error');
			$('#divTec').removeClass('has-success');
			$("#instalador").attr('placeholder', 'Informe o código do Técnico');
			$("#instalador").val('');
		}else{
			if(jQuery.inArray(texto, pessoas) != -1) {
				$('#divTec').removeClass('has-error');
				$('#divTec').addClass('has-success');
			}else{
				$('#divTec').addClass('has-error');
				$('#divTec').removeClass('has-success');
				$("#instalador").attr('placeholder', 'Técnico inexistente.');
				$("#instalador").val('');
			}
		}
	});
	var d = new Date();
	var data = ("0" + d.getDate()).substr(-2) + "/" + ("0" + (d.getMonth() + 1)).substr(-2) + "/" + d.getFullYear();
	$('#dataEnvio').val(data);
});

// $(document).ready(function() {
// 	$('#destino').on('click', function(){
// 		$('#destino:selected').each( function() {
// 			console.log($(this));
// 		});
// 	});
// });