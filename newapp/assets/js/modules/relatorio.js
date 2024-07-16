function Relatorio(){
	
	var module = {};
	module.debug = true;

	$body = $("body");
	
	module.init = function(){

		if(module.debug == true) console.info('Module Relatorio intialized');

		this.default();
		this.contrato();
		

	}

	module.default = function(){

		
	}

	module.contrato = function(){

		$("#form-contrato").submit(function(ev){
			ev.preventDefault();

			$body.addClass('loading');

			var form = $(this).serializeArray();
			var url = $(this).attr("action");
			console.log(form);

			$.post(url, form, function(conteudo) {
				$('.table').html('');
				$("#conteudo").html(conteudo);
				$body.removeClass('loading');
			});
		});
	}
	

	return module;
	
	
}