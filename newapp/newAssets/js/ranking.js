$(document).ready(function() {
	var inc = 0;
	var ranking = [];
	function aumentaDez(pos) {
		for (inc=0 ; inc<10; inc++) {
			if (pos>=ranking.length){
				inc = 10;
				$('#botao').empty();
			}else{
				$('#infors').append('<div id="um" class="um" style="width: 100%;" hidden>'+
										'<div id="pos" class="tdImportante"><label>'+ranking[pos].bot.substr(7)+(pos<3 ? '<img src="https://gestor.showtecnologia.com/sistema/newapp/newAssets/imagens/crown.ico"> ' : '')+(pos+1)+'º</label></div>'+
										'<div class="pull-left" style="width: 69%; padding-left: 5px;"><label>'+ranking[pos].cliente+'</label></div>'+
										'<div class="tdImportante"><label>'+ranking[pos].num+'</label></div>'+
										'<div class="tdImportante"><a id="toModal" data-id="'+ranking[pos].id_cliente+'" data-nome="'+ranking[pos].cliente+'" data-quant="'+ranking[pos].num+'" data-status = "'+ranking[pos].bot.substr(6,1)+'" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal_ranking" title="Ver detalhes" aria-disabled="true"><i class="fa fa-eye" aria-hidden="true"></i></a></div>'+
									'</div><br style="clear:both" /><hr class="featurette-divider">'
								);
				$('.um').show(1500);
			}
			pos++;
		}
		return pos;
	};
	clientes = [];
	$.getJSON('getRanking', function(data){
		ranking = data;
		inc = aumentaDez(inc);
		$('#botao').slideDown();
		for(ind=0; ind<ranking.length; ind++) {
			clientes[ind] = ranking[ind].cliente;
		}
		$('#filtro').autocomplete({ source: clientes, minLength: 2});
		$('#pesquisa').removeAttr('disabled');
		$('#filtro').attr('readonly', false);
	});
	$('#plus').click(function() {
		inc = aumentaDez(inc);
	});

	$(document).on('click', '#toModal', function() {
		$('#detalhes').hide();
		$('#loading').show();
		var cliente = $(this)[0].dataset.nome;
		var id_cli = $(this)[0].dataset.id;
		var quant = $(this)[0].dataset.quant;
		var status = $(this)[0].dataset.status;
		$.getJSON('getRicketsInfors', {'id_cliente':id_cli}, function(dados){
			//preenche o modal após fazer a busca no banco sobre o cliente.
			$('#pessoa').html('<u>'+cliente+'</u><br><br>');
			$('#quant').html('<b>Quantidades de Tickets:</b>  '+quant);
			$('#mes').html('<b>Mês como maior número:</b>  '+dados['months']+' = '+dados['valor']);
			$('#drogado').html('<b>Usuário com mais Tickets:</b>  '+dados['users']);
			$('#status').html('<b>'+(status == 0 ? 'Inadimplente </b><a class="blem" title="Situação: Inadimplente"><i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i></a> ' : 'Adimplente </b><a title="Situação: Adimplente"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i></a> '));
			$('#detalhes').show();
			$('#loading').hide();
		});
	});
	$('#filtro').on('keydown', function (event) {
	    if (event.keyCode !== 13) return;
		    pesquisar();
	});
	$('#filtro').focusout(function() {
		$('#filtro').attr('placeholder', 'Nome do Cliente');
	});
	$('#pesquisa').on('click', function() {
		pesquisar();
	});
	function pesquisar() {
		if($('#filtro').val() != '') {
			indice = jQuery.inArray($('#filtro').val(), clientes);
			if(indice != -1) {
				$('#filtro').val('');
				$('#infors').slideUp(1500, function() {
					$('#infors').empty();
					$('#botao').empty();
					$('#infors').append('<div id="um" class="um" style="width: 100%;">'+
											'<div id="pos" class="tdImportante"><label>'+ranking[indice].bot.substr(7)+' '+(indice+1)+'º</label></div>'+
											'<div class="pull-left" style="width: 69%; padding-left: 5px;"><label>'+ranking[indice].cliente+'</label></div>'+
											'<div class="tdImportante"><label>'+ranking[indice].num+'</label></div>'+
											'<div class="tdImportante"><a id="toModal" data-id="'+ranking[indice].id_cliente+'" data-nome="'+ranking[indice].cliente+'" data-quant="'+ranking[indice].num+'" data-status = "'+ranking[indice].bot.substr(6,1)+'" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal_ranking" title="Ver detalhes" aria-disabled="true"><i class="fa fa-eye" aria-hidden="true"></i></a></div>'+
										'</div><br style="clear:both" /><hr class="featurette-divider">'+
										'<div id="um" class="um" style="width: 100%;">'+
											'<div id="pos" class="tdImportante"><label></label></div>'+
											'<div class="pull-left" style="width: 69%; text-align: center; padding-left: 5px;"><a id="listaTodos" class="btn btn-default"> Listar todos novamente</a></div>'+
											'<div class="tdImportante"><label></label></div>'+
											'<div class="tdImportante"></div>'+
										'</div>'
									);
					$('#infors').slideDown(2000);
					$('#listaTodos').on('click', function() {
						$('#infors').slideUp(1000, function() {
							$('#infors').empty();
							inc = aumentaDez(0);
							$('#botao').append('<a id="plus"><i class="fa fa-angle-double-down" aria-hidden="true"></i> Mostrar mais</a>');
							$('#plus').click(function() {
								inc = aumentaDez(inc);
							});
							$('#infors').slideDown(1000);
						});
					});
				});
			}else{
				$('#filtro').attr('placeholder', 'Sem Tickets, tente outro cliente');
				$('#filtro').val('');
			}
		}
	};
});