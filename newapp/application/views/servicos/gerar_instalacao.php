<link href="<?php echo base_url('assets') ?>/css/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

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
		<h4>OS Instalação - Contrato <?php echo $id_contrato ?></h4>
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

		<form method="post" name="formcontato" id="ContactForm" enctype="multipart/form-data" action="<?php echo site_url('servico/gerar_os_instalacao/'.$id_contrato.'/'.$id_cliente) ?>">

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
						<select name="usuario" id="usuario" placeholder="Usuario" class="input span6" required>
							<option value="">Selecione Usuário</option>
							<?php foreach ($usuarios as $usu): ?>
								<option value="<?php echo $usu->code ?>"><?php echo $usu->usuario ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>

			</div>

			<div class="span7" id="moldura" style="width: 575px;">
				<div class="titulo_moldura">Dados da Instalação</div>
				<div class="wrapper">
					<div class="bg">
						<label class="control-label">Instalador:</label>
						<input type="text" data-source='<?php echo $instalador?>' name="instalador" class="span4 form-control" data-provide="typeahead" autocomplete="off"
							   data-items="6" placeholder="Digite o nome do instalador" required />
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

			<div class="span7" id="moldura" style="width: 575px;">
				<div class="titulo_moldura">Equipamentos</div>
				<fieldset id="modulos">
					<div class="control-group">
                        <div class="col-lg-3"></div>
						<div class="col-lg-3">
							<div class="recipiente form-inline">
								<p class="form-group">
									<input type="text" class="typeahead-placas" name="veiculo[placa0]" placeholder="Placa" required>
								</p>

								<select data-tipo='serial' class="js-example-basic-single" placeholder="Serial" name="veiculo[serial0]" required>
								</select>
								<br/>
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

			<div class="botoes_resposta" style="float: right;">
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
		<h4>Contrato <?php echo $id_contrato ?> não contém placas cadastradas, favor cadastrar placas ao contrato antes de gerar OS de instalação!</h4>
	</div>

<?php endif ?>

<div id="myModal_liberar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
				<input type="hidden" name="gerar" value="gerar_instalacao" />
				<div class="control-group">
					<label class="control-label">Motivo</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<select type="text" id="status" name="status" placeholder="Status" class="input span3">
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

	var quant_modulos = <?php echo $max_veiculos ?>;
	var quant_os = <?php echo $equip_os_instalacao ?>;
	var quant_contrato = <?php echo $quant_veiculos ?>;
	var id_contrato = <?php echo $id_contrato ?>;
	var placas = <?php echo $placas ?>;

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

	$(document).ready(function() {
		$('.js-example-basic-single').select2({
			ajax: {
				url: '<?= site_url('equipamentos/ajaxListSelect') ?>',
				dataType: 'json'
			},
			placeholder: "Serial",
    		allowClear: true
		});

		$('.calendarioos').focus(function(){
			$(this).calendario({target: $(this)});
		});

		$("#instalador").autocomplete({
			source: "<?php echo site_url('servico/get_instaladores')?>" // path to the get_birds method
		});

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

					} else {

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

	// jQuery(function($) {
	//   	var input = $('#qtd-modulo');
	//   	input.on('keydown', function() {
	// 	var key = event.keyCode || event.charCode;
	// 	if( key == 8 || key == 46 )
	// 	    return false;
	//   });
	// });

	$(function() {

		var modulos = $('#modulos');
		var quantity = modulos.find('.quantity');
		var recipiente = modulos.find('.recipiente');

		var typeaPlacas = {
			source: placas,
			items: 20
		};

		var typeaSeriais = {
			source: seriais,
			items: 20
		};

		var alert = [
			'<div class="alert">',
			'<button type="button" class="close" data-dismiss="alert">&times;</button>',
			'<strong>Atenção!</strong>',
			'Faltando gerar OS de instalação de apenas {quantidade} veículo(s) do total de {contrato} pertencente(s) ao contato {id}',
			'</div>'
		].join('');

		var template = [
			'<p class="form-group">',
			'<input type="text" class="typeahead-placas" name="veiculo[placa{id}]" placeholder="Placa" required> ',
			'<input type="text" class="typeahead-seriais" name="veiculo[serial{id}]" placeholder="Serial" required> ',
			'<button type="button" class="btn btn-warning btn-remover"><i class="icon-minus icon-white"></i></button>',
			'</p>'
		].join('');

		var validarQuantidade = function(inserindo, permitido, callback) {
			var total = parseInt(inserindo) + recipiente.find('.form-group').length;
			modulos.find('.alert').remove();
			if (total > permitido) {
				modulos.prepend(
					alert.replace('{quantidade}', inserindo).replace('{contrato}', permitido).replace('{id}', id_contrato)
				);
			} else {
				callback();
			}
		};


		var adicionarModulo = function() {
			var id = parseInt(quantity.val());
			var tmp = template.replace('{id}', id);
			tmp = tmp.replace('{id}', id);

			var modulo = $(tmp).appendTo(recipiente);

			$(modulo).find('.typeahead-placas').typeahead(typeaPlacas);
			$(modulo).find('.typeahead-seriais').typeahead(typeaSeriais);

			atualizarQuantidade();
		};

		var removerModulo = function(modulo) {
			$(modulo).closest('.form-group').remove();
			atualizarQuantidade();
		};

		var atualizarQuantidade = function() {
			quantity.val(recipiente.find('.form-group').length);
		};

		quantity.on('keypress', function(e) {
			e.preventDefault();
			return false;
			// var valor = $(this).val();
			// var code = e.keyCode || e.which;
			//
			// if (code == 13) {
			// 	var total = recipiente.find('.form-group').length;
			//
			// 	if (valor > total) {
			// 		validarQuantidade(valor, quant_modulos, function() {
			// 			for (var i = 0; i < (valor - total); i++) {
			// 				adicionarModulo();
			// 			}
			// 		});
			// 	} else {
			// 		recipiente.find('.form-group').each(function(index) {
			// 			if (index >= valor) {
			// 				removerModulo(recipiente.find('.form-group').last());
			// 			}
			// 		});
			// 	}
			//
			// 	return false;
			// }
		});

		modulos.on('click', '.btn-adicionar', function() {
			validarQuantidade(1, quant_modulos, function() {
				adicionarModulo();
			});
		});

		modulos.on('click', '.btn-remover', function() {
			removerModulo(this);
		});

		$('.typeahead-placas').typeahead(typeaPlacas);
		$('.typeahead-seriais').typeahead(typeaSeriais);
	});

</script>

<script type="text/javascript" src="<?php echo base_url('media')?>/js/calendario.js"></script>
