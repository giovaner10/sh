<link href="<?php echo base_url('assets') ?>/css/jquery-ui.css" rel="stylesheet" type="text/css" />
<h3>Ordens de Serviços</h3>
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

<style type="text/css">
	.display{
		display: none !important;
	}
</style>

<div class="alert alert-success">OS de Instalação gerada com sucesso!</div>
<div class="alert alert-error">ERRO - OS não foi gerada!</div>

<section id="form">
	<fieldset>
		<legend>Informações do Cliente</legend>
		<label>
			<input type="radio" name="choice" id="choice1" value="0" checked>
			Endereço do cliente
		</label>
		<label>
			<input type="radio" name="choice" id="choice2" value="1">
			Usar endereço diferente
		</label>
		<input type="text" name="address" placeholder="Endereço do serviço" class="display">
		<input type="text" name="solicitante" placeholder="Solicitante">
		<input type="text" name="data" placeholder="01/01/2000" data-mask="00/00/0000">
		<input type="email" name="email" placeholder="exemplo@exemplo.com">
		<input type="text" name="phone" placeholder="(00) 00000 - 0000" data-mask="(00) 0000 - 0000">
	</fieldset>
	<fieldset>
		<legend>Informações da instalação</legend>
		<input type="text" name="instalador" placeholder="fora Michel Temer" data-provide="typeahead" autocomplete="off" data-source='<?php echo $instaladores; ?>'>
		<input type="text" name="d_init" placeholder="data inicio" data-mask="00/00/0000">
		<input type="text" name="h_init" placeholder="hora inicio" data-mask="00:00:00">
		<input type="text" name="d_end" placeholder="data final" data-mask="00/00/0000">
		<input type="text" name="h_end" placeholder="hora final" data-mask="00:00:00">
		<textarea cols="20" rows="6" placeholder="Observações"></textarea>
	</fieldset>
	<fieldset>
		<legend>Equipamentos</legend>
		<ul id="boardsOrSeriais"></ul>
		<label id="qtd">1</label><button id="more">more</button>
		<input type="text" name="placas[]" id="placas" placeholder="Placa" data-provide="typeahead" autocomplete="off" data-source='<?php echo $boards; ?>' >
		<input type="text" name="seriais[]" id="seriais" placeholder="Serial" data-provide="typeahead" autocomplete="off" data-source='<?php echo $seriais; ?>' >
		<div id="recipiente"></div>
	</fieldset>
	<button>Limpar</button>
	<button id="submit" >Enviar</button>
</section>

<script src="<?php echo base_url('assets') ?>/js/jquery-ui.js"></script>
<script type="text/javascript">
	$('.alert-success').css('display', 'none');
	$('.alert-error').css('display', 'none');
	$(document).ready(function() {
		var qtd         = 1;
		var qtdCar      = <?php echo $qtdCar; ?> - 1;
		var id_cliente  = <?php echo $id_cliente; ?>;
		var id_contrato = <?php echo $id_contrato; ?>;

		$('input[name=choice]').click(function() {
			if ( $(this).val() == 1)
				$('input[name=address]').removeClass('display');
			else
				$('input[name=address]').addClass('display');
		});

		$('#more').click(function() {
			if (qtd <= qtdCar) {
				$('#recipiente').append(
						'<fieldset>'+
							"<input type='text' name='placas[]'' placeholder='Placa' data-provide='typeahead' autocomplete='off' data-source='"+$('#placas').attr('data-source')+"'>"+
							"<input type='text' name='seriais[]' placeholder='Serial' data-provide='typeahead' autocomplete='off' data-source='"+$('#seriais').attr('data-source')+"'>"+
							'<button id="less">less</button>'+
						'</fieldset>'
					);
				$('#qtd').text((qtd += 1));
			} else {
				/*mensagem de limite*/
			}
		});

		$(document).on('click', '#less', function() {
			$(this).closest('fieldset').remove();
			$('#qtd').text(qtd -= 1);
		});

		var validateBoard = function(board) {
			$.getJSON('../../../validateBoard', {
				id_cliente: id_cliente,
				id_contrato: id_contrato,
				board: board
			}, function(callback) {

			});
		}

		var validateSerial = function(serial) {
			$.getJSON('../../../validateSerial', {
				serial: serial
			}, function(callback) {

			});
		}

		var boardOrserialDupli = function(board=false, serial=false) {
			if (board)
				$('#boardsOrSeriais').append('<li>A placa <strong>'+board+'</strong> está duplicada, favor escolher outra!</li>');
			else if (serial)
				$('#boardsOrSeriais').append('<li>O serial <strong>'+serial+'</strong> está duplicada, favor escolher outra!</li>');
			else {
				// outros casos
			}
		}

		$('#submit').click(function() {
			$('.alert-success').css('display', 'none');
			$('.alert-error').css('display', 'none');
			$('#boardsOrSeriais').html('');
			var solic = $('input[name=solicitante]').val();
			if (!solic) {/*mensagem*/}
			var data = $('input[name=data]').val();
			if (!data) {/*mensagem*/}
			var email = $('input[name=email]').val();
			if (!email) {/*mensagem*/}
			var phone = $('input[name=phone]').val();
			if (!phone) {/*mensagem*/}

			var inst = $('input[name=instalador]').val();
			if (!inst) {/*mensagem*/}
			var d_init = $('input[name=d_init]').val();
			if (!d_init) {/*mensagem*/}
			var h_init = $('input[name=h_init]').val();
			if (!h_init) {/*mensagem*/}
			var d_end = $('input[name=d_end').val();
			if (!d_end) {/*mensagem*/}
			var h_end = $('input[name=h_end]').val();
			if (!h_end) {/*mensagem*/}
			var obs = $('textarea').val();
			
			var oldBoard = null;
			var boards = $('input[name^=placas]').map(function(i, obj) { 
				var board = $(obj).val();
				if (oldBoard == board && board) {
					oldBoard = board;
					boardOrserialDupli(board=board);
				} else if (board) {
					oldBoard = board;
					validateBoard(board);
				} else {
					// outros casos
				}
				return board ? board : null; 
			}).get();

			var oldseri = null;
			var seriais = $('input[name^=seriais]').map(function(i, obj) { 
				var serial = $(obj).val();
				if (oldseri == serial && serial) {
					oldseri = serial;
					boardOrserialDupli(borad=false, serial=serial);
				} else if (serial) {
					oldseri = serial;
					validateSerial(serial);
				} else {
					// outros casos
				}
				return serial ? serial : null;
			}).get();

			if ( !$('#boardsOrSeriais li').length ) {
				$.post('../../../saveInstalacaoOS', {
					solic:       solic,
					data:        data,
					email:       email,
					phone:       phone,
					inst:        inst,
					d_init:      d_init,
					h_init:      h_init,
					d_end:       d_end,
					h_end:       h_end,
					obs:         obs,
					qtd:         qtd,
					boards:      boards,
					seriais:     seriais,
					address:     $('input[name=choice]').val() == 1 ? $('input[name=address]').val() : '' ,
					id_cliente:  id_cliente,
					id_contrato: id_contrato,
				}, function(callback) {
					if (callback == '1') {
						$('.alert-success').css('display', 'inline-block');
						$('.alert-error').css('display', 'none');
					} else {
						$('.alert-error').css('display', 'inline-block');
						$('.alert-success').css('display', 'none');
					}
				});
			}
		});
	});
</script>