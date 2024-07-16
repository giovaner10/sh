function ModulePanico(){
	
	var module = {};

	$body = $("body");
	
	module.debug = false;
	
	module.violacaoLoaded = false;
	module.baseURL = false;
	module.violacoes = '';
	module.totalVeiculos = 0;
	module.cliente = ' ';
	module.clientes = [];
	module.veiculos = false;

	module.notify = true;

	module.init = function(baseURL){

		if(module.debug == true) console.info('Modulo violacao intialized');
		
		module.baseURL = baseURL;
		module.violacoes = $("#violacoes");

		this.default();

	}

	module.default = function(){

		$(document).on('click', '.buscar', function(e){
			module.cliente = $('.clientes').val();
			module.update();
			$('#modal-veiculos').modal('show');
		});

		$(document).on('click', '.client', function(e) {
			module.cliente = $(this).data('cliente');
			module.update();
			$('#modal-veiculos').modal('show');
		});

		$("#modal-veiculos").css({
			'margin-left': function () { 
			    return -($(this).width() / 2);
			}
		});
		
	}

	module.getViolacoes = function(){
		var url = module.baseURL+"index.php/monitor/load_panico";
		$("#carregando").html('<i class="fa fa-spinner fa-spin"></i> <small>Localizando veículos...</small>');

		$.ajax({
       		type: "post",
          	data: {cliente: module.cliente},  
          	url: url,
          	dataType: "json",
          	success: function(veiculos) {
          		var totalVeic = veiculos.length;
            	
				if(module.cliente != ' ') {
					$('.data-table').html('');
					$.each(veiculos, function(i, veiculo) {
						var template = ['<tr>',
											'<td>'+i+'</td>',
						                	'<td>'+veiculo.placa+'</td>',
						                	'<td>'+Math.round(veiculo.VOLTAGE)+'</td>',
						                	'<td>'+veiculo.nome.substr(0, 35)+'</td>',
						                	'<td>'+veiculo.email+'</td>',
						                	'<td>'+veiculo.fone+'</td>',
						                	'<td>'+veiculo.DATA+'</td>',
			               				'</tr>'].join('');
						$('.data-table').append(template);
					});	
				}else{
					module.totalVeiculos = totalVeic;
					module.updateQtdVeiculos();
	            	if (totalVeic) {
						$("#violacoes").html('');

						$.each(veiculos, function(i, veiculo) {
							if(module.clientes.indexOf(veiculo.nome) == -1) 
								module.clientes.push(veiculo.nome);
						});
						
						$.each(module.clientes, function(i, c) {
							var template = ['<li class="span3 client" data-cliente="'+c+'">',
						                		'<div class="thumbnail">',
						                			'<span><i class="fa fa-user"></i><small> '+c.substr(0, 32)+'</small></span>',
						                		'</div>',
						               		'</li>'].join('');
						    $("#violacoes").append(template);
						});

					}
				}
				if (module.notify)
					module.playSound();
          	},
    	});

		module.violacaoLoaded = true;
		$("#carregando").html('');
		
	}
	
	module.update = function()
	{
		console.log('update');
		if(module.violacaoLoaded){
			console.log('updated');
			this.getViolacoes();
		}		
		
	}

	module.playSound = function (){
		document.getElementById('notificacao-audio').play();
		module.notify = true;
	}

	module.stopSound = function (){
		document.getElementById('notificacao-audio').pause();
		module.notify = false;
	}

	module.controlSound = function (control){

		if (module.notify){
			module.stopSound();
			$(control).find("i").attr("class","fa fa-bell-slash");
		}else{
			module.playSound();
			$(control).find("i").attr("class", "fa fa-bell");
		}
	}
	
	module.updateQtdVeiculos = function(){
		$("#qtd_veiculos").html(module.totalVeiculos+" veículos");
	}
	
	return module;
	
	
}