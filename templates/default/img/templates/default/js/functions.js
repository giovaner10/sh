
	// Funcao para inserir o tipo de imagem no menu vertical
	function tipo_imagem(e){
		
		var imagem;
		
		switch(e){
			case 'Cadastros':	 imagem = 'templates/default/img/ico-cadastro.png';   break;
			case 'Financeiro':	 imagem = 'templates/default/img/ico-financeiro.png'; break;
			case 'Contratos':	 imagem = 'templates/default/img/ico-contratos.png';  break;
			case 'OS':		 imagem = 'templates/default/img/ico-os.png';		  break;
			case 'Agenda':		 imagem = 'templates/default/img/ico-agenda.png'; 	  break; 
                        case 'Documentos':	 imagem = 'templates/default/img/ico-scanner.png'; 	  break; 
			case 'Relatorios':	 imagem = 'templates/default/img/ico-relatorios.png'; break;
			case 'MeusDados':	 imagem = 'templates/default/img/ico-meusdados.png';  break;
		}
		
		return imagem;
	}

	// initialise plugins
	jQuery(function(){	
		
		// Desabilitando a tecla ENTER
		$("input").live('keypress', function(event) { 
	        if (event.keyCode == 13 || event.wich == 13) {
	            return false; 
	        }
		});
		
		var loader = "<center><p>&nbsp</p><img id='loader' hspace='5' src='templates/default/img/ajax-loader.gif'><br>Carregando...</center>";

		// Desabilitando a tecla ENTER
		$("input").live('keypress', function(event) { 
	        if (event.keyCode == 13 || event.wich == 13) {
	            return false; 
	        } 
	    });
	    
		
		// Arrendondando a borda do menu:        
		$('.menu').corner({
            tl: { radius: 0 },
            tr: { radius: 0 },
            bl: { radius: 10 },
            br: { radius: 10 },
            antiAlias: true,
            autoPad: true,
            validTags: ["div"]
		});	
		

		// funcoes do Menu da Barra Superior
		$('.menu a').click(function() {
			
			if (this.id){
				
				var modulo = this.id;
				
				var params = {
						modulo: modulo
				}
				
				//Verifica se o usuario tem acesso ao modulo
				$.get('includes/incVerificaAcesso.php', params, function(data) {
					if (data == 'true') {
						
						
						$("#conteudo").html("");
						$("#menu_modulo").html(loader);
						
					    $.get('includes/'+ modulo +'/incMenu'+ modulo +'.php', function(data) {
					    	
					    	var imagem = tipo_imagem(modulo);
					    	var tit = "";
					    	
							switch(modulo){
								case 'Cadastros':	 tit = 'CADASTROS'; 				break;
								case 'Financeiro':	 tit = 'FINANCEIRO'; 				break;
								case 'Contratos':	 tit = 'CONTRATOS'; 				break;
								case 'OS':	 		 tit = 'ORDEM DE SERVI&Ccedil;OS';	break;
								case 'Agenda':		 tit = 'AGENDA'; 					break; 
                                                                case 'Documentos':	 tit = 'DIGITALIZAR'; 					break; 
								case 'Relatorios':	 tit = 'RELAT&Oacute;RIOS'; 		break;
								case 'MeusDados':	 tit = 'MEUS DADOS'; 				break;
							}			    	
					    	
					    	$("#menu_modulo").html('<img src="'+imagem+'"></img><br>'+tit);
					    	
					        $('#menu_modulo').append(data);

						    $('ul.sf-menu').superfish({
					            delay:       100,                            // one second ( 1000 ) delay on mouseout 
					            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
					            speed:       'fast',                          // faster animation speed 
					            autoArrows:  false,                           // disable generation of arrow mark-up 
					            dropShadows: false                            // disable drop shadows 
							});
					        
					        // funcoes do Menu
							$('.sf-menu li').click(function() {
								
								var arquivo = new Array();
								arquivo = explode('-', this.id);
								
								var params = "";
								
								if(arquivo[1])
									params = "?acao="+arquivo[1];
								
								if (this.id){
									$("#conteudo").html(loader);
								    $.get('includes/'+ modulo +'/inc'+ arquivo[0] +'.php'+ params, function(data) {		    	
								        $('#conteudo').html(data);
								    });
								}
							});
					    });	
						
					//Caso o usuario nao tem acesso ao modulo exibe o alerta	
					} else {
						alert("Sua SESSAO EXPIROU, ou voce NAO TEM ACESSO a esse item do sistema!");
					}
				});
			}
		}); 

		// Funcao que gera o menu horizontal
		$('#fisheye').Fisheye(
			{
				maxWidth: 15,
				items: 'a',
				itemsText: 'span',
				container: '.fisheyeContainter',
				itemWidth: 50,
				proximity: 80,
				alignment: 'left',
				valign: 'bottom',
				halign : 'center'
			}
		);
		
	});
	
	/* CONVERSOR DE ACENTOS PARA JAVASCRIPT */
	
	var _chr=[];
	var _etn=[];
	for(var x=0;x<95;x++){
		_chr[x]=(x+161);_etn[x]="&#"+(x+161)+";";
	} // replacing all chars could be slow you would like to restrict it to only the accent chars or the ones that you like
	
	function chr(x){
		return String.fromCharCode(x);
	}
	
	function e2c(s){
		for(var x=0;x<95;x++){
			var m=s.indexOf(_etn[x]);
			while(m!=-1){
				s=s.replace(_etn[x],chr(_chr[x]));m=s.indexOf(_etn[x]);
			}
		}
		return s;
	}
	
	