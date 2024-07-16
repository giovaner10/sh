function ModuleFiliais(){
	
	var module = {};
	module.debug = true;
	
	module.init = function(){

		if(module.debug == true) console.info('Module Filiais intialized');

		this.default();
		//this.actions();

	}

	module.default = function(){

		// Custom select
		//alert('Modulo Inicializado');

	}

	module.actions = function(){
		
		
		
	}
	

	return module.init();
	
	
}