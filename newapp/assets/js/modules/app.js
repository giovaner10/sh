function App() {
	var module = {};
	module.debug = true;
	$body = $("body");
	module.init = function() {
		if(module.debug == true) console.info('Module App intialized');
		this.default();
		this.paginacao();
	}

	module.default = function() {
		$('.modal').on('hidden', function() {
			$(this).data('modal').$element.removeData();
		});
	}

	module.paginacao = function() {
		$('.pag-ajax a').click(function (ev) {
			ev.preventDefault();
			console.log('clicou paginacao');
			$('#loading').css('display', 'block');
			var urlPag = $(this).attr('href');
			$('#conteudo').load(urlPag, function(result){
				console.log(result);
			    $('#loading').css('display', 'none');
			});
		});	
	}
	return module;
}