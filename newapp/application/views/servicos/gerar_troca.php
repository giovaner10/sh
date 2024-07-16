<link href="<?php echo base_url('assets') ?>/css/jquery-ui.css" rel="stylesheet" type="text/css" />
<h3>Ordens de Serviços</h3>

<hr class="featurette-divider">

<div class="well well-small">

			<div class="btn-group">
			  <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
			    Listar OS
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu">
			  	<li><a href="<?php echo site_url('servico')?>" title=""><i class="icon-th-list"></i> Todas</a></li>
			    <li><a href="<?php echo site_url('servico/os_abertas')?>" title=""><i class="icon-th-list"></i> Abertas</a></li>
			    <li><a href="<?php echo site_url('servico/os_fechadas')?>" title=""><i class="icon-th-list"></i> Fechadas</a></li>
			  </ul>
			</div>

			<div class="btn-group">
			  <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#">
			    Gerar OS
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu">
			  	<li><a href="<?php echo site_url('servico/instalacao')?>" title=""><i class="icon-th-list"></i> Instalação</a></li>
			    <li><a href="<?php echo site_url('servico/manutencao_troca_retirada')?>" title=""><i class="icon-th-list"></i> Manutenção/Troca/Retirada</a></li>
			  </ul>
			</div>

</div>

<br style="clear:both" />

<?php if ($placas): ?>

	<div class="span7" style="float: none; margin-left: auto; margin-right: auto;">
		<h4>OS Troca - Contrato <?php echo $id_contrato ?></h4>
	</div>

	<div class="span7" style="float: none; margin-left: auto; margin-right: auto; margin-top: 30px;">

			<?php foreach ($dados_cliente as $cliente): 
				$nome_cliente = $cliente->nome;
				$endereco_cliente = $cliente->endereco;
				$bairro_cliente = $cliente->bairro;
				$numero_cliente = $cliente->numero;
				$complemento_cliente = $cliente->complemento;
				$cidade_cliente = $cliente->cidade;
				$uf_cliente = $cliente->uf;
				$id_cliente = $cliente->id;
				$endereco_destino = $endereco_cliente." ".$numero_cliente." ".$complemento_cliente.", ".$bairro_cliente.", ".$cidade_cliente."-".$uf_cliente;
			endforeach ?>
	   
			<form method="post" name="formcontato" id="ContactForm" enctype="multipart/form-data" action="<?php echo site_url('servico/gerar_os_troca/'.$id_contrato.'/'.$id_cliente) ?>">

			<div class="span7" id="moldura" style="width: 575px;">
				<div class="titulo_moldura">Dados do Cliente</div> 

				<div>
					<p style="color: #A0A0A0; border: 1px #C8C8C8 solid; padding: 5px;">Cliente: <?php echo $nome_cliente ?></p>
				</div>

                <div>
                    <input type="text" name="endereco_destino" value= '<?php echo $endereco_destino ?>' placeholder="Endereço Destino:" class="span6 form-control"/>
                </div>
				
				<div class="wrapper input-prepend">
		        	<div class="bg">
		        		<input type="text" name="solicitante" id="solicitante" placeholder="Solicitante" class="input span3" />
			  			<span class="add-on" style="margin-left: 10px;">Data Solicitação</span>
			  			<input class="span2 calendarioos" id="data_solicitacao" name="data_solicitacao" type="text" value="<?php echo date('d/m/Y') ?>" required />
			  		</div>
				</div>

		        <div class="wrapper input-prepend">
		        	<div class="bg">
			          	<input type="text" name="contato" id="contato" placeholder="Contato" class="input span4" />
			            <input style="margin-left: 22px;" type="text" id="telefone" name="telefone" placeholder="Telefone" maxlength="14" class="input span2" />
		        	</div>
		        </div>

		        <div class="wrapper">
		          <div class="bg">
		            <select type="text" name="usuario" id="usuario" placeholder="Usuario" class="input span6" required>
		              <option value="">Selecione Usuário</option>
					  <?php foreach ($usuarios as $usu): ?>
						<option value="<?php echo $usu->code ?>"><?php echo $usu->usuario ?></option>
				      <?php endforeach ?>
		            </select>
		          </div>
		        </div>

		  	</div>

		  	<div class="span7" id="moldura" style="width: 575px;">
				<div class="titulo_moldura">Dados da Troca</div>
	 
		        <div class="wrapper">
		          <div class="bg">
		          	<label class="control-label" for="instalador">Instalador:</label>
                      <input type="text" data-source='<?php echo $instalador?>' name="instalador" class="span4 form-control" data-provide="typeahead" autocomplete="off"
                             data-items="6" placeholder="Digite o nome do instalador" required />
<!--			        <input required class="input span5"  id="instalador" name="instalador" type="text" autocomplete="on" />-->
		          </div>
		        </div>

		        <div class="wrapper input-prepend">
		        	<div class="bg">
		        		<span class="add-on">Data Inicial</span>
			  			<input class="span2 calendarioos" id="data_inicial" name="data_inicial" type="text" value="<?php echo date('d/m/Y') ?>" required />
		        		<input style="margin-left: 10px;" type="text" id="hora_inicial" name="hora_inicial" maxlength="5" placeholder="Hora Inicial" class="input span2" required />
			  		</div>
				</div>

				 <div class="wrapper input-prepend">
		        	<div class="bg">
		        		<span class="add-on">Data Final&nbsp;</span>
			  			<input class="span2 calendarioos" name="data_final" id="data_final" type="text" value="<?php echo date('d/m/Y') ?>" required />
		        		<input style="margin-left: 10px;" type="text" id="hora_final" name="hora_final" maxlength="5" placeholder="Hora Final" class="input span2" required />
			  		</div>
				</div>

		        <div  class="wrapper">
			     	<div class="wrapper">
			           <div class="bg">
			              <textarea name="observacoes" id="observacoes" cols="1" rows="4" placeholder="Observações" class="textarea span6" ></textarea>
			            </div>
			        </div>
		  	    </div>
		  	</div>

		  	<div class="limite_equipamento span7">
				<div class="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<div class="mensagem_limite"></div>
				</div>
			</div>

			<div class="span7" id="moldura" style="width: 575px;">
				<div class="titulo_moldura">Equipamentos</div> 

				<fieldset id="modulos">
	              <div class="control-group">
	                <div id='teste2' class="col-lg-3 ">
	                  <div id='teste' class="form-group pegar">
	                    <input data-tipo='placa' type="text" class="form-control" name="veiculo[placa0]" id="nome-filho-1" placeholder="Placa" data-provide="typeahead" data-source='<?php echo $placas ?>' data-items="20" required>
	                    <input data-tipo='serial'type="text" class="form-control" name="veiculo[serial0]" id="nome-filho-1" placeholder="Serial" data-provide="typeahead" data-source='<?php echo $equipamentos ?>' data-items="20" required>
					    <div class="checkbox">
							<label>
								<input type="checkbox" name="bloqueio">Bloqueio
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="panico">Pânico
							</label>
						</div>
						<div class="checkbox ">
							<label>
								<input type="checkbox" name="identificador">Identificador
							</label>
						</div>
					  </div>
	                </div>
	              </div>
	            </fieldset>

	            <div class="resultado_ok">
					<div class="alert alert-success">
					<div class="mensagem"></div>
					</div>
				</div>
				<div class="link_os"></div>

	            <div class="resultado_erro">
					<div class="alert alert-error">
					<div class="mensagem"></div>
					</div>
				</div>
				<div class="link_equipamento"></div>

		  	</div>

		  		<div class="botoes_resposta" style= "float: right;">
			    	<a href="#" class="btn" onClick="document.getElementById('ContactForm').reset();return false">
			          Limpar
			        </a>
			        <button type="submit" class="salvar btn btn-info" title="Enviar Dados"> Enviar</button>
			    </div>

	        </form>

	</div>

<?php else: ?>

	<div class="alert alert-block">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <h2>Ops!</h2>
	  <h4>Contrato <?php echo $id_contrato ?> não contém placas cadastradas, favor cadastrar placas ao contrato antes de gerar OS de troca!</h4>
	</div>	

<?php endif ?>

<div id="myModal_liberar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Liberar Equipamento</h4>
    </div>
    <div class="modal-body">
    	
    </div>
    
</div>

<div id="myModal_instaladores" class="modal fade higherWider modal-instaladores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="" id="loginModal">
		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  			<h3>Status do Instalador</h3>
		</div>
	<div class="modal-body">
		<form id="signup" class="" method="post" action="<?php echo site_url('servico/add_status_instalador')?>">
			<div class="control-group">
				<label class="control-label">Instalador</label>
            	<div class="controls">
                	<div class="input-prepend">
              			<span class="add-on"><i class="icon-user"></i></span>
                		<input type="text" class="input-xlarge" id="cNome" name="nome" value="<?php echo $instaladores->nome.' '.$instaladores->sobrenome.' - '.$instaladores->cidade.'/'.$instaladores->estado ?>" disabled>
              		</div>
            	</div>
          	</div>
          	<input type="hidden" name="instalador" value="<?php echo $instaladores->id ?>" />
          	<input type="hidden" name="contrato" value="<?php echo $id_contrato ?>" />
          	<input type="hidden" name="quant_veiculos" value="<?php echo $quant_veiculos ?>" />
          	<input type="hidden" name="id_cliente" value="<?php echo $id_cliente ?>" />
          	<input type="hidden" name="gerar" value="gerar_troca" />
          	<div class="control-group">
                <label class="control-label">Motivo</label>
            	<div class="controls">
                	<div class="input-prepend">
              			<span class="add-on"><i class="icon-user"></i></span>
                		<select type="text" id="status" name="status" placeholder="Status" class="input span2">
                  			<option value="1">Indisponibilidade</option>
                  			<option value="2">Pendência de Pagamento</option>
                  			<option value="3">Problemas de Saúde</option>
                  			<option value="4">Outros</option>
                  		</select>
                  	</div>
                </div>
            </div>
            <div class="control-group">
				<label class="control-label">Observações</label>
            	<div class="controls">
                	<div class="input-prepend">
                		<textarea name="msg" id="mensagem" cols="1" rows="4" placeholder="Observações" class="textarea span4" ></textarea>
              		</div>
            	</div>
          	</div>
          </div>
			<div class="modal-footer">
				<div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls">
                    	<button type="submit" class="btn btn-primary" >Enviar</button>
                    </div>
                </div>
			</div>
		</form>
	</div>
</div>
<script src="<?php echo base_url('assets') ?>/js/jquery-ui.js"></script>


<script>
	$('#proximo').click(function(e) {
		e.preventDefault();
		$('#myModal_instaladores').modal('show');
	});

	var id_contrato = <?php echo $id_contrato ?>;

	window.onload = function(){
		id('telefone').onkeypress = function(){
			mascara( this, mtel );
		}
		id('hora_inicial').onkeypress = function(){
			mascara( this, mtel2 );
		}
		id('hora_final').onkeypress = function(){
			mascara( this, mtel2 );
		}
	}

	$(document).ready(function(){
		$('.calendarioos').focus(function(){
		    $(this).calendario({target: $(this)});
		});
	
	});
	

	function appendFilho(node, id) {
		var z = 0;
		var u = 0;
		$( "#teste" ).clone().appendTo( "#teste2" );
		$('.pegar > input').each(function(i,k){
			console.log(k);
			if (k.getAttribute('data-tipo')=='placa') {
				k.setAttribute('name','veiculo[placa'+z+']');
				z++;						
			}
			else{
				k.setAttribute('name','veiculo[serial'+u+']');
				u++;					
			};
			
		}); 
	};
	function removeFilho(valorParcial) {
		var z = valorParcial;
		$('#teste').each(function(i, k) {
			k.remove('name','veiculo[placa'+z+']');
			k.remove('name','veiculo[serial'+z+']');
			z--;
		});
	};

	$(document).ready(function() {

		$("#instalador").autocomplete({
		    source: "<?php echo site_url('servico/get_instaladores')?>" // path to the get_birds method
		 });

		$(".limite_equipamento").hide();

		$(document).on('click', '#mais', function(e){
			e.preventDefault();
			var valorParcial;
			var valor = parseInt($('#qtd-modulo').val());
			valorParcial = valor + 1;
			$('#qtd-modulo').val(valorParcial);
		});

		$(document).on('click', '#menos', function(e){
			e.preventDefault();
			var valorParcial;
			var valor = parseInt($('#qtd-modulo').val());
			valorParcial = valor - 1;
			if (valorParcial < 1) {
				$('#qtd-modulo').val(1);
			}else{
				$('#qtd-modulo').val(valorParcial);
				removeFilho(valorParcial);
			};
		});

		if($('#modulos').length > 0) {
			var oldVal = 1;

			$(document).on('click', '.quantidade', function(e){
				e.preventDefault();
				
				var currentValParcial = $('#qtd-modulo').val();
				var	currentVal = parseInt(currentValParcial);
				var parentFieldset = $('#qtd-modulo').closest('#modulos');

				    if (currentVal > oldVal) {
			    		appendFilho(parentFieldset, currentVal);
				    	oldVal = currentVal;

						//var elem = document.getElementById('modulo'+currentVal);
						//elem.setAttribute('data-source','<?php echo $equipamentos ?>');

						//var elem2 = document.getElementById('placa'+currentVal);
						//elem2.setAttribute('data-source','<?php echo $placas ?>');

				    } else if (currentVal < oldVal) {
			    		parentFieldset.find('.attached').last().remove();
				    	oldVal = currentVal;
				    }	
				    
			});
	
		}

		$(".resultado_ok").hide();
		$(".resultado_erro").hide();
	    $("#ContactForm").ajaxForm({
	        target: '.resultado',
	        dataType: 'json',
	        success: function(retorno){
	        	
	            if (retorno.success) {

	            	if (retorno.serial) {

	            		$(".mensagem").html(retorno.mensagem);
		            	$(".resultado_ok").show();
		            	$(".link_os").hide();
		            	$(".link_equipamento").hide();
		            	$(".resultado_erro").hide();

	            	}else{

	            		var tpl = [
		            	'<a href="<?php echo site_url('servico/imprimir_os')?>/',retorno.id_os,'/',retorno.id_contrato,'/',retorno.tipo_os,'" target="_blank" class="btn btn-small btn-success">',
		                'Visualizar OS',
		                '</a>'
		                ].join('');

		                $('.link_os').html(tpl);
		            	$(".mensagem").html(retorno.mensagem);
		            	$(".resultado_ok").show();
		            	$(".link_os").show();
		            	$(".link_equipamento").hide();
		            	$(".resultado_erro").hide();

		            	}

	            }else{

	            	if (retorno.serial) {
	            		var tpl = [
		            	'<a data-toggle="modal" href="<?php echo site_url('servico/liberar_equipamento')?>/',retorno.serial,'" title="Liberar equipamento" data-target="#myModal_liberar" class="btn btn-small btn-warning">',
		                'Liberar',
		                '</a>'
		                ].join('');

		                $('.link_equipamento').html(tpl);
		                $(".link_equipamento").show();

	            	}else{
	            		$(".link_equipamento").hide();
	            	}

	            	$(".mensagem").html(retorno.mensagem);
	            	$(".resultado_erro").show();
	            	$(".link_os").hide();
	            	$(".resultado_ok").hide();
	            }
	            //$("#ContactForm").resetForm();
	        }
	    });

	});

	jQuery(function($) {
	  	var input = $('#qtd-modulo');
	  	input.on('keydown', function() {
		var key = event.keyCode || event.charCode;
		if( key == 8 || key == 46 )
		    return false;
	  });
	});


</script>

<script type="text/javascript" src="<?php echo base_url('media')?>/js/calendario.js"></script>




