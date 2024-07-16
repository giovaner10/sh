function Configs(baseURL){
	
	var module = {};
	module.debug = true;

	$body = $("body");
	
	module.init = function(){

		if(module.debug == true) console.info('Module Configs intialized');

		this.default();


	}

	module.default = function(){

		
		
	}
	
	module.actions = function(){
		
	}
	
	module.saveMessage = function(){
		
		$("#btn-add").click(function(){
			
			var form = $("#form-save");
			var action = form.attr('action');
			var post = form.serialize();
			
			$.post(action, post, function (retorno){
				
				if(retorno.success){
															
					var template = '<tr><td>'+retorno.mensagem[0].id_msg+'</td>'+
									"<td>"+retorno.mensagem[0].descricao+"</td>"+
									"<td>"+retorno.mensagem[0].mensagem+"</td>"+
									"<td><a href='"+baseURL+"configuracoes/form_msg_sms/"+retorno.mensagem[0].id_msg+"' data-toggle='modal' data-target='#edit-msg-sms' class='btn btn-primary btn-small botao-edit'><i class='fa fa-edit'></i></a></td></tr>";
					
					$(".table > tbody").prepend(template);
					
					$("#msg").addClass("alert-success").show().find("p").html(retorno.msg);
					$("#msg").animate({opacity: 0.0}, 3000, module.invisible);
					$("#form-save").trigger('reset');
					
				}else{
					$("#msg").addClass("alert-warning").show().find("p").html(retorno.msg);
					$("#msg").animate({opacity: 0.0}, 10000, module.invisible);
					
				}
			}, 'json');
		});
		
		
	}
	
	module.editMessage = function(){
		
		var update_linha;
		
		$(".botao-edit").click(function(){
			linha_update = $(this).parent().parent();
		});
		
		$("#btn-edit").click(function(){
			
			var form = $("#form-save");
			var action = form.attr('action');
			var post = form.serialize();
			
			$.post(action, post, function (retorno){

				if(retorno.success){
															
					var template = '<td>'+retorno.mensagem[0].id_msg+'</td>'+
									"<td>"+retorno.mensagem[0].descricao+"</td>"+
									"<td>"+retorno.mensagem[0].mensagem+"</td>"+
									"<td><a href='"+baseURL+"configuracoes/form_msg_sms/"+retorno.mensagem[0].id_msg+"' data-toggle='modal' data-target='#edit-msg-sms' class='btn btn-primary btn-small botao-edit'><i class='fa fa-edit'></i></a></td>";
					
					$(linha_update).html(template);
					
					$("#msg").addClass("alert-success").show().find("p").html(retorno.msg);
					$("#msg").animate({opacity: 0.0}, 3000, module.invisible);
					
					
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