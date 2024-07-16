function Agenda(){
	
	var module = {};
	module.debug = true;

	$body = $("body");
	
	module.init = function(){

		if(module.debug == true) console.info('Module Agenda intialized');

		this.default();
		//this.actions();

	}

	module.default = function(){
		
		
		this.save();
		
		// Custom select
		//alert('Modulo Inicializado');
		/*
		$('.modal').on('hidden', function(){
			$(this).data('modal').$element.removeData();
		});
		*/
		
	}
	
	module.actions = function(){
		
	}

	module.save = function(){
		
		$("#btn-add").click(function(){
			
			var form = $("#save-agenda");
			var action = form.attr('action');
			var post = form.serialize();
			
			$.post(action, post, function (retorno){
				
				if(retorno.success){
					
					var servico = retorno.agenda[0].servico_agenda;
					var service;
					switch(servico){
						case '1':
							service = 'Instalação';
							break;
						case '2':
							service = 'Manutenção';
							break;
						case '3':
							service = 'Remoção';
							break;
						default:
							service = servico;
					}
					
					var template = '<tr><td>'+retorno.agenda[0].id_agenda+'</td>'+
									"<td>"+retorno.agenda[0].prefixo+"</td>"+
									"<td>"+retorno.agenda[0].placa+"</td>"+
									"><td>"+retorno.agenda[0].data_agenda+"</td>"+
									"<td>"+service+"</td>"+
									"<td></td></tr>";
					
					$(".table > tbody").prepend(template);
					
					$("#msg").addClass("alert-success").show().find("p").html(retorno.msg);
					$("#msg").animate({opacity: 0.0}, 3000, module.invisible);
					$("#save-agenda").trigger('reset');
					
				}else{
					$("#msg").addClass("alert-warning").show().find("p").html(retorno.msg);
					$("#msg").animate({opacity: 0.0}, 10000, module.invisible);
					
				}
			}, 'json');
		});
		
		
	}
	
	module.invisible = function(){
		$("#msg").hide();
	}
	

	return module;
	
	
}