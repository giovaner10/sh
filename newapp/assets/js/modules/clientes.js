function ModuleClientes() {
	var module = {};
	module.debug = true;
	$body = $("body");
	module.init = function() {
		if(module.debug == true) console.info('Module Clientes intialized');
		this.default();
		this.actions();
	}

	module.default = function() {
		$('.modal').on('hidden', function() {
			$(this).data('modal').$element.removeData();
		});
	}

	module.actions = function() {
		// submit form usuario
		$('#busca_cliente').submit(function() {
			var cliente = $('input[name=cliente]');
			keyWord = cliente.val();
			controller = cliente.attr('data-controller');
			url = controller;
			$.post(url, {keyword: keyWord}, function(formulario){
				$('#add_filial .modal-body').html(formulario);
				$('#add_filial').modal('show');
			});
			return false;
		});
		
		$('#tab_clientes a').click(function (e) {
			var sessao = $(this).attr('href');
		});

		$('.salvar-permissao').click(function(e) {
			var perms = new Array();
			// var inputs = $('input:checked[name=permissoes]');
			var controller = $(this).attr('data-controller');
			var cliente = $('input[name=cli]').val();
			var user = $('input[name=user]').val();
			$('input[name="permissoes[]"]').each(function() {
				if ($(this).is(':checked')) perms.push($(this).val());
			});
			
			$.post(controller, {id_cliente: cliente, id_user: user, permissoes: perms},
					function(cb) {
						if(cb.success) {
							$('.alert').show();
							$('.alert').addClass('alert-success');
							$('.alert').removeClass('alert-error');
							$('#msg-retorno').html(cb.msg);
						} else {
							$('.alert').show();
							$('.alert').addClass('alert-error');
							$('.alert').removeClass('alert-success');
							$('#msg-retorno').html(cb.msg);
						}
			}, 'json');
			$('#permissoes_usuario').modal('hide');
		});
	}

	module.listar = function(controller) {
		$body.addClass('loading');
		$.get(controller, function(content) {
			$('#conteudo').html(content);
			$body.removeClass('loading');
		});
	}

	module.bindOrdenar = function() {
		$("#bt-cliente").click(function(ev) {
			ev.preventDefault();
			$body.addClass('loading');
			var cliente = $("input[name=cliente]");
			var controller = $(this).attr("data-controller");
			if (cliente.val() == "") {
				$('.alert').addClass('alert-error').show().html('Digite o cliente para filtrar!').delay(3000).fadeOut(500);
			} else {
				$.post(controller, {nome_cliente: cliente.val()}, function(content) {
					$("#conteudo").html(content)
				});
			}
			$body.removeClass('loading');
		});

		$("select[name=status]").on('change', function() {
			$body.addClass('loading');
			var controller = $(this).attr("data-controller");
			var status = $(this).val();
			$.post(controller, {status_cliente: status}, function(content) {
				$("#conteudo").html(content)
			});
		});
	}

	module.buscar = function() {
		$("#input-cliente").keypress(function(ev) {
			if(ev.wich == 13 || ev.keyCode == 13) {
				ev.preventDefault();
				$body.addClass('loading');
				var cliente = $("input[name=cliente]");
				var controller = $("#bt-cliente").attr("data-controller");
				if (cliente.val() == "") {
					$('.alert').addClass('alert-error').show().html('Digite o cliente para filtrar!').delay(3000).fadeOut(500);
				} else {
					$.post(controller, {nome_cliente: cliente.val()}, function(content) {
						$("#conteudo").html(content)
					});
				}
				$body.removeClass('loading');
			}
		});

		$("select[name=status]").on('change', function() {
			$body.addClass('loading');
			var controller = $("#bt-cliente").attr("data-controller");
			var status = $("#bt-cliente").val();
			$.post(controller, {status_cliente: status}, function(content) {
				$("#conteudo").html(content)
			});
		});
	}
	return module;
}