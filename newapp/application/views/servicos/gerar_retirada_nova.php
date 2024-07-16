<!-- <link href="<?php echo base_url('assets') ?>/css/jquery-ui.css" rel="stylesheet" type="text/css" /> -->
<script>
	$(document).ready(function() {
		$(document).ready(function() {
			$(".listar-os-click .dropdown-toggle").click(function() {
				$(".gerar-os-click .dropdown-menu").hide();
				$(this).siblings('.dropdown-menu').toggle();
			});

			$(".gerar-os-click .dropdown-toggle").click(function() {
				$(".listar-os-click .dropdown-menu").hide();
				$(this).siblings('.dropdown-menu').toggle();
			});

			$(document).click(function(e) {
				if (!$(e.target).closest('.listar-os-click, .gerar-os-click').length) {
					$(".dropdown-menu").hide();
				}
			});
		});
	});
</script>
<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente('Ordem de Serviço', site_url('Homes'), 'Serviço', 'Ordem de Serviço');

?>

<div class="card-conteudo">
	<h3>Ordens de Serviços</h3>

	<hr class="featurette-divider">

	<div class="well well-small">

		<div class="btn-group listar-os-click">
			<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#" id="listar-os-click">
				Listar OS
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo site_url('servico') ?>" title=""><i class="icon-th-list"></i> Todas</a></li>
				<li><a href="<?php echo site_url('servico/os_abertas') ?>" title=""><i class="icon-th-list"></i> Abertas</a></li>
				<li><a href="<?php echo site_url('servico/os_fechadas') ?>" title=""><i class="icon-th-list"></i> Fechadas</a></li>
			</ul>
		</div>

		<div class="btn-group gerar-os-click">
			<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#" id="gerar-os-click">
				Gerar OS
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo site_url('servico/instalacao') ?>" title=""><i class="icon-th-list"></i> Instalação</a></li>
				<li><a href="<?php echo site_url('servico/manutencao_troca_retirada') ?>" title=""><i class="icon-th-list"></i> Manutenção/Troca/Retirada</a></li>
			</ul>
		</div>

	</div>

	<br style="clear:both" />

	<?php if ($placas) : ?>


		<h4><?= $subTitle . $id_contrato ?></h4>
		<br>

		<!-- <div class="span7" style="float: none; margin-left: auto; margin-right: auto; margin-top: 30px;"> -->

		<?php foreach ($dados_cliente as $cliente) :
			$nome_cliente = $cliente->nome;
			$endereco_cliente = $cliente->endereco;
			$bairro_cliente = $cliente->bairro;
			$numero_cliente = $cliente->numero;
			$complemento_cliente = $cliente->complemento;
			$cidade_cliente = $cliente->cidade;
			$uf_cliente = $cliente->uf;
			$id_cliente = $cliente->id;
			$endereco_destino = $endereco_cliente . " " . $numero_cliente . " " . $complemento_cliente . ", " . $bairro_cliente . ", " . $cidade_cliente . "-" . $uf_cliente;
		endforeach ?>

		<form name="formcontato" id="ContactForm">

			<div class="" id="moldura">

				<div class="titulo_moldura">Dados do Cliente</div>

				<div class="card-input-dados">
					<label for="cliente" class=" control-label labelpad">Cliente:</label> <br>
					<p style="color: #A0A0A0; border: 1px #C8C8C8 solid; padding: 5px;"><?= $nome_cliente ?></p>
				</div>

				<div class="card-input-dados">
					<label for="endereco_destino" class=" control-label labelpad">Endereço: </label> <br>
					<input type="text" name="endereco_destino" value='<?= $endereco_destino ?>' placeholder="Endereço Destino:" class="form-control" />
				</div>

				<div class="card-input-dados">
					<label for="solicitante" class=" control-label labelpad">Solicitante: </label> <br>
					<input type="text" name="solicitante" placeholder="Solicitante" class="form-control" required />
				</div>

				<div class="card-input-dados">
					<label for="data_solicitacao" class=" control-label labelpad">Data solicitação:</label> <br>
					<input class="form-control" id="data_solicitacao" name="data_solicitacao" size="16" type="date" max="2999-12-31" value="<?php echo date('Y-m-d'); ?>" required />
				</div>

				<div class="card-input-dados">
					<label for="data_solicitacao" class=" control-label labelpad">Nome Contato:</label> <br>
					<input type="text" name="contato" placeholder="Nome do contato" class="form-control" required />
				</div>

				<div class="card-input-dados">
					<label for="telefone" class=" control-label labelpad">Telefone Contato</label> <br>
					<input id="telefone" type="text" name="telefone" placeholder="Telefone" class="form-control" required />
				</div>

				<div class="card-input-dados">
					<label for="usuario" class=" control-label labelpad">Usuário:</label> <br>
					<select type="text" name="usuario" id="usuario" placeholder="Usuario" class="form-control" required>
						<option value="">Selecione Usuário</option>
						<?php foreach ($usuarios as $usu) : ?>
							<option value="<?= $usu->code ?>"><?= $usu->usuario ?></option>
						<?php endforeach ?>
					</select>
				</div>


				<label>&nbsp</label>
			</div>

			<div class="" id="moldura">
				<div class="titulo_moldura">Dados da OS</div>

				<div class="card-input-dados">
					<label for="instalador" class=" control-label labelpad">Instalador:</label> <br>
					<select class="form-control pesqinstalador" id="pesqinstalador" name="instalador" type="text" required></select>
				</div>

				<div class="card-input-dados">
					<label for="data_inicial" class=" control-label labelpad">Data Inicial:</label>
					<input class="form-control" id="data_inicial" name="data_inicial" size="16" type="date" max="2999-12-31" value="<?php echo date('Y-m-d'); ?>" required>
				</div>

				<div class="card-input-dados">
					<label for="data_inicial" class=" control-label labelpad">Hora Inicial:</label>
					<input type="text" id="hora_inicial" name="hora_inicial" maxlength="5" placeholder="00:00" class="form-control time" required />
				</div>

				<div class="card-input-dados">
					<label for="data_final" class=" control-label labelpad">Data Final:</label>
					<input class="form-control" id="data_final" name="data_final" size="16" type="date" max="2999-12-31" value="<?php echo date('Y-m-d'); ?>" required>
				</div>

				<div class="card-input-dados">
					<label for="data_final" class=" control-label labelpad">Hora Final:</label>
					<input type="text" id="hora_final" name="hora_final" maxlength="5" placeholder="00:00" class="form-control time" required>
				</div>


				<div class="card-input-dados">
					<label for="observacoes" class=" control-label labelpad">Observações:</label>
					<textarea name="observacoes" id="observacoes" cols="1" rows="4" placeholder="Observações" class="form-control"></textarea>
				</div>
			</div>

			<label>&nbsp</label>
			<!-- <div class="wrapper">
				  <div class="bg">
					  <label class="control-label" for="instalador">Instalador:</label>
                      <input type="text" data-source='<?php echo $instalador ?>' name="instalador" class="span4 form-control" data-provide="typeahead" autocomplete="off"
                             data-items="6" placeholder="Digite o nome do instalador" required />
				  </div>
				</div> -->

			<!-- <div class="wrapper input-prepend">
					<div class="bg">
						<span class="add-on">Data Inicial</span>
						<input class="span2 calendarioos" id="data_inicial" name="data_inicial" type="text" value="<?php echo date('d/m/Y') ?>" required />
						<input style="margin-left: 10px;" type="text" id="hora_inicial" name="hora_inicial" maxlength="5" placeholder="Hora Inicial" class="input span2" required />
					</div>
				</div> -->

			<!-- <div class="wrapper input-prepend">
					<div class="bg">
						<span class="add-on">Data Final&nbsp;</span>
						<input class="span2 calendarioos" name="data_final" id="data_final" type="text" value="<?php echo date('d/m/Y') ?>" required />
						<input style="margin-left: 10px;" type="text" id="hora_final" name="hora_final" maxlength="5" placeholder="Hora Final" class="input span2" required />
					</div>
				</div> -->

			<!-- <div  class="wrapper">
					<div class="wrapper">
					   <div class="bg">
						  <textarea name="observacoes" id="observacoes" cols="1" rows="4" placeholder="Observações" class="textarea span6" ></textarea>
						</div>
					</div>
				</div>
			 -->

			<!-- <div class="limite_equipamento span7">
				<div class="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<div class="mensagem_limite"></div>
				</div>
			</div> -->

			<div class="" id="moldura">

				<fieldset id="modulos">

					<div class="titulo_moldura">Equipamentos</div>


					<div class="card-input-dados">
						<label for="placa" class=" control-label labelpad">Placa:</label> <br>
						<select class="form-control js-example-basic-single pesqplaca" id="pesqplaca" name="placa" type="text" required></select>
					</div>

					<div class="card-input-dados">
						<label for="serial" class=" control-label labelpad">Serial:</label> <br>
						<select class="form-control pesqserial" id="pesqserial" name="serial" type="text" required></select>
					</div>

					<?php if ($tipoOS == 4) : ?>
						<div class="card-input-dados">
							<label for="serial" class="control-label labelpad">Serial retirado:</label> <br>
							<select data-tipo='serial' class="form-control js-example-basic-single" name="serial_retirado" required></select>
						</div>
					<?php endif ?>


					<div class="card-input-dados">
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

			<div class="botoes_resposta" style="float: right; padding: 5px;">
				<a href="#" class="btn btn-default" onclick="limparFormulario()"><i class="fa fa-leaf"></i>
					Limpar
				</a>
				<button type="submit" id="enviarDados" class="salvar btn btn-success" title="Enviar Dados"><i class="fa fa-arrow-circle-o-right"></i> Enviar</button>
			</div>

		</form>

		<!-- </div> -->

	<?php else : ?>

		<div class="alert alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h2>Ops!</h2>
			<h4>Contrato <?php echo $id_contrato ?> não contém placas cadastradas, favor cadastrar placas ao contrato antes de gerar OS!</h4>
		</div>

	<?php endif ?>

	<!-- <div id="myModal_liberar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">Liberar Equipamento</h4>
	</div>
	<div class="modal-body">
		
	</div>
	
</div> -->

	<div id="myModal_liberar" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 id="myModalLabel" style="color: #1C69AD;">Liberar Equipamento</h3>
				</div>
				<form name="liberarSerial" id="liberar_eqp">
					<div class="modal-body liberar">

					</div>
					<div class="modal-footer">
						<button type="submit" id="btnLiberar" class="btn btn-primary">Liberar equipamento</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- <div id="myModal_instaladores" class="modal fade higherWider modal-instaladores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3>Status do Instalador</h3>
				</div>
				<div class="modal-body">
					<form id="signup" class="" method="post" action="<?= site_url('servico/add_status_instalador') ?>">
						<div class="form-group">
							<label class="control-label">Instalador</label>
							<div class="form-control col-sm-8">																
								<input type="text" class="form-control" id="cNome" name="nome" value="<?= $instaladores->nome . ' ' . $instaladores->sobrenome . ' - ' . $instaladores->cidade . '/' . $instaladores->estado ?>" disabled>								
							</div>
						</div>
						<input type="hidden" name="instalador" value="<?= $instaladores->id ?>" />
						<input type="hidden" name="contrato" value="<?= $id_contrato ?>" />
						<input type="hidden" name="quant_veiculos" value="<?= $quant_veiculos ?>" />
						<input type="hidden" name="id_cliente" value="<?= $id_cliente ?>" />
						<input type="hidden" name="gerar" value="gerar_retirada" />
						<div class="control-group">
							<label class="control-label">Motivo</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><i class="icon-user"></i></span>
									<select type="text" id="status" name="status" placeholder="Status" class="input span4">
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
									<textarea name="msg" id="mensagem" cols="1" rows="4" placeholder="Observações" class="textarea" ></textarea>
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
</div> -->
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">


<script>
	function limparFormulario() {
		document.getElementById('ContactForm').reset();
		$('select').each(function() {
			$(this).val(null).trigger('change.select2');
		});
	}
	$('#proximo').click(function(e) {
		e.preventDefault();
		$("#myModal_liberar").modal('show');
	});

	var id_contrato = <?php echo $id_contrato ?>;
	var tipoOS = <?php echo $tipoOS ?>;

	$(document).ready(function() {
		$('.time').mask('99:99');
		var pesq_instalador = '<?= site_url("instaladores/InstaladorListSelect") ?>';
		var pesq_placa = '<?= site_url("servico/getPlacasByContrato") ?>' + '?contrato=' + id_contrato;
		var pesq_serial = '<?= site_url("contratos/busca_equipamento_select2") ?>';
		$('.pesqplaca').select2({
			ajax: {
				url: pesq_placa,
				dataType: 'json',
			},

			placeholder: "Selecione a placa",
			allowClear: true,
		});

		$('.pesqserial').select2({
			ajax: {
				url: pesq_serial,
				dataType: 'json',
			},

			placeholder: "Selecione o serial",
			allowClear: true,
		});

		$('.pesqinstalador').select2({
			ajax: {
				url: pesq_instalador,
				dataType: 'json',
			},

			placeholder: "Selecione o instalador",
			allowClear: true,
		});

		$('select[name=serial_retirado]').select2({
			ajax: {
				url: '<?= site_url('equipamentos/ajaxListSelect') ?>',
				dataType: 'json'
			},
			placeholder: "Serial Retirado",
			allowClear: true
		});

	});

	$(document).ready(function() {


		function validarDatas() {
			const dataInicial = new Date($('#data_inicial').val());
			const dataFinal = new Date($('#data_final').val());
			const dataAtual = new Date();

			var [horaInicial, minutoInicial] = $('#hora_inicial').val().split(':').map(Number);

			var [horaFinal, minutoFinal] = $('#hora_final').val().split(':').map(Number);

			if (dataInicial > dataFinal) {
				showAlert("warning", "A data inicial não pode ser maior que a data final.");
				return true;
			}

			if (dataInicial.toString() == dataFinal.toString()) {
				if (horaInicial > horaFinal) {
					showAlert("warning", "A Hora inicial não pode ser maior que a Hora final.");
					return true;
				}

				if (horaInicial == horaFinal && minutoInicial > minutoFinal) {
					showAlert("warning", "O minuto inicial não pode ser maior que o minuto final.");
					return true;
				}
			}

			return false;
		}

		$(".limite_equipamento").hide();

		$(document).on('click', '#mais', function(e) {
			e.preventDefault();
			var valorParcial;
			var valor = parseInt($('#qtd-modulo').val());
			valorParcial = valor + 1;
			$('#qtd-modulo').val(valorParcial);
		});

		$(document).on('click', '#menos', function(e) {
			e.preventDefault();
			var valorParcial;
			var valor = parseInt($('#qtd-modulo').val());
			valorParcial = valor - 1;
			if (valorParcial < 1) {
				$('#qtd-modulo').val(1);
			} else {
				$('#qtd-modulo').val(valorParcial);
				removeFilho(valorParcial)
			};
		});

		if ($('#modulos').length > 0) {
			var oldVal = 1;

			$(document).on('click', '.quantidade', function(e) {
				e.preventDefault();

				var currentValParcial = $('#qtd-modulo').val();
				var currentVal = parseInt(currentValParcial);
				var parentFieldset = $('#qtd-modulo').closest('#modulos');

				if (currentVal > oldVal) {
					appendFilho(parentFieldset, currentVal);
					oldVal = currentVal;

				} else if (currentVal < oldVal) {
					parentFieldset.find('.attached').last().remove();
					oldVal = currentVal;
				}

			});

		}

		$(".resultado_ok").hide();
		$(".resultado_erro").hide();

		$("#liberar_eqp").submit(function(e) {
			$('#btnLiberar').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Liberando...')
			var liberar_serial = $('#liberar_eqp').serialize();
			url = '<?= site_url("servico/liberar_equip") ?>';
			$.ajax({
				type: 'post',
				data: liberar_serial,
				url: url,
				dataType: 'json',
				success: function(retorno) {
					$(".link_equipamento").hide();
					$(".resultado_erro").hide();
					$("#myModal_liberar").modal('hide');
					showAlert("success", retorno.mensagem);
					$('#btnLiberar').removeAttr('disabled').html('Liberar equipamento');
				},
				error: function(err) {}
			});
			return false;
		});

		$("#ContactForm").submit(function(e) {
			var url = null;
			var data = $('#ContactForm').serialize();
			var validador = validarDatas();
			if (validador) {
				return false;
			}
			if (tipoOS == 1) {
				url = '<?= site_url("servico/gerar_os_instalacao/$id_contrato/$id_cliente") ?>';
			} else if (tipoOS == 2) {
				url = '<?= site_url("servico/gerar_os_troca/$id_contrato/$id_cliente") ?>';
			} else if (tipoOS == 3) {
				url = '<?= site_url("servico/gerar_os_retirada/$id_contrato/$id_cliente") ?>';
			} else if (tipoOS == 4) {
				url = '<?= site_url("servico/gerar_os_manutencao/$id_contrato/$id_cliente") ?>';
			}

			$('#enviarDados').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando...')

			$.ajax({
				type: 'post',
				data: data,
				url: url,
				dataType: 'json',
				success: function(retorno) {

					if (retorno.success) {

						if (retorno.serial) {

							$(".mensagem").html(retorno.mensagem);
							$(".resultado_ok").show();
							$(".link_os").hide();
							$(".link_equipamento").hide();
							$(".resultado_erro").hide();

						} else {

							var tpl = [
								'<a href="<?php echo site_url('servico/imprimir_os') ?>/', retorno.id_os, '/', retorno.id_contrato, '/', retorno.tipo_os, '" target="_blank" class="btn btn-small btn-success">',
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

					} else {

						if (retorno.serial) {
							var tpl = [
								'<a data-toggle="modal" title="Liberar equipamento" data-target="#myModal_liberar" class="btn btn-small btn-warning">',
								'Liberar',
								'</a>'
							].join('');

							var tplModal = [
								'<p>Deseja liberar o serial?</p><input type="text" name="serial" class="form-control" value="',
								retorno.serial,
								'"readonly/>'
							].join('');

							$('.liberar').html(tplModal);
							$('.link_equipamento').html(tpl);
							$(".link_equipamento").show();

						} else {
							$(".link_equipamento").hide();
						}

						$(".mensagem").html(retorno.mensagem);
						$(".resultado_erro").show();
						$(".link_os").hide();
						$(".resultado_ok").hide();
					}
					$('#enviarDados').removeAttr('disabled').html('<i class="fa fa-arrow-circle-o-right"></i> Enviar')
					//$("#ContactForm").resetForm();
				},
				error: function(e) {
					$('#enviarDados').removeAttr('disabled').html('<i class="fa fa-arrow-circle-o-right"></i> Enviar')
					showAlert("error", 'Erro ao gerar OS, tente novamente!');
				}
			});
			return false;
		});

	});

	jQuery(function($) {
		var input = $('#qtd-modulo');
		input.on('keydown', function() {
			var key = event.keyCode || event.charCode;
			if (key == 8 || key == 46)
				return false;
		});
	});

	// $('#listar-os-click').on('click', function(e) {
	// 	$('#gerar-os-click').modal('hide');
	// });
</script>

<style>
	.titulo_moldura {
		font-size: 17px;
		color: #1C69AD !important;
	}

	.card-conteudo h4 {
		font-size: 19px !important;
		padding: 0;
	}

	.card-input-dados {
		margin-top: 12px;
	}

	.well {
		border: none;
		background-color: #fff;
		padding: 0;
		margin-bottom: 0;
	}

	.btn-group {
		box-shadow: none;
	}

	.text-title h3,
	.text-title h4 {
		padding: 0 0px !important;
		margin-left: 0px !important;
	}

	#container_home {
		margin-left: 50px;
	}
</style>