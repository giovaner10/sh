</div>

<div id="faturaConfig" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel">Configurar Boleto</h3>
  </div>
  <div class="modal-body">
	<p>Escolha um banco emissor para boletos</p>
	<?= form_open(site_url('faturas/salvar_config_boleto'), array('id' => 'boleto-config')) ?>
		<?php $boleto = get_boleto_default(); ?>
		<label class="checkbox inline">
		  <input name="emissor_boleto" type="radio" id="inlineCheckbox1" value="1" <?php echo $boleto == 1 ? 'checked = "true"' : '' ?>> Banco do Brasil
		</label>
		<label class="checkbox inline">
		  <input name="emissor_boleto" type="radio" id="inlineCheckbox2" value="2" <?php echo $boleto == 2 ? 'checked = "true"' : '' ?>> Unicred
		</label>
	<?= form_close(); ?>
  </div>
  <div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
	<button id="salvarBoletoConfig" class="btn btn-primary">Salvar</button>
  </div>
</div>

<div class="container-fluid no-print">
	<div class="span12" style="width: 100%"><p style="text-align: center;" class="navbar-text">&copy;<?php echo date('Y') ?> Show Tecnologia - Todos os direitos reservados.</p></div>
</div>

<!-- <link rel="stylesheet" href="<?= base_url('media/css/bootstrap.min.css') ?>"> -->
<!-- <link rel="stylesheet" href="<?= base_url('media/css/chat.css') ?>"> -->


<!-- <div id="divButtonChat">
	<button id="btnOpenChat" class="btn btn-primary" onclick="openChat()"> <i class="fa fa-comment"></i> Chat-Show</button>
</div>

<div id="app" class="container animated slideOutRight">
<div class="btnCloseChat" onclick="openChat()">
	<i class="fa fa-arrow-circle-right"> </i>
</div>
<h3 class="text-center" style="color: white">Chat - Shownet</h3>

<div id="messages">
	<div class="col s12">
		<ul class="list-unstyled" v-cloak>
			<li v-for="message in messages">
				<span class="date" v-if="message.date">[{{ message.date }}]</span>
				<span class="name" v-if="message.user">{{ message.user }}:</span>
				<span class="text" :style="{ color: message.color }">
					{{ message.text }}
				</span>
			</li>
		</ul>
	</div>
</div> -->

<!-- <div class="row-fluid">
	<div class="col-12 col-sm-9">
		<input type="text" class="form-control" placeholder="Mensagem" v-model="text"
			@keyup.enter="sendMessage">
	</div>
</div>     -->

</div>

<script type="text/javascript" src="<?php echo base_url('media') ?>/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/bootstrap-confirm.js"></script>
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/custom.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>
<!--<script type="text/javascript" src="--><?php //echo base_url('media')?><!--/js/jquery.mask.js"></script>-->
<script type="text/javascript" src="<?php echo base_url('assets') ?>/plugins/priceformat/jquery.price_format.1.8.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('assets') ?>/plugins/sticky/jquery.sticky.js"></script>

<script	src="<?php echo base_url('assets') ?>/plugins/form-jquery/jquery.form.min.js"></script>
<script type="text/javascript"
	src="<?php echo base_url('media') ?>/js/bootstrap-filestyle.min.js"> </script>

<!-- modulos JS -->
<script src="<?php echo base_url('assets') ?>/js/modules/clientes.js"></script>
<script src="<?php echo base_url('assets') ?>/js/modules/filiais.js"></script>
<script src="<?php echo base_url('assets') ?>/js/modules/app.js"></script>

<!-- <script type="text/javascript" src="<?= base_url('media/js/vue.min.js') ?>"></script> -->
<!-- <script type="text/javascript" src="<?= base_url('media/js/chat.js') ?>"></script> -->
<script type="text/javascript">

$(":file").filestyle({buttonText: "Arquivo"});

// var idleTime = 0;

$(document).ready(function(){
	var vali_senha = "<?= $this->auth->get_login('admin', 'token') ?>";
	var senha, mostra;
	var forca = 0;

	$('#pass_nova').on('keyup', function () {
		senha = document.getElementById("pass_nova").value;
		forca = 0;
		mostra = document.getElementById("progress-bar");

		if((senha.length >= 4) && (senha.length <= 7)) {
			forca += 10;
		}else if(senha.length > 7) {
			//console.log('Maior que 7')
			forca += 15;
		}
		if(senha.match(/[a-z]+/)) {
			//console.log('Tem letras minusculas');
			forca += 15;
		}
		if(senha.match(/[A-Z]+/)) {
			//console.log('Tem letras maisculas');
			forca += 20;
		}
		if(senha.match(/[0-9]+/)){
			//console.log('Tem numeros');
			forca += 30;
		}
		if(senha.match(/[^A-Za-z0-9_]/)){
			//console.log('Tem Caracter');
			forca += 20;
		}

		return mostra_res();
	});

	function mostra_res(){
		//console.log('Força = '+forca);
		if(forca < 30){
			$(mostra).css('width', forca + '%').html('<span>Fraca</span>');
		}else if((forca >= 30) && (forca < 60)){
			$(mostra).css('width', forca + '%').html('<span>Média</span>');
		}else if((forca >= 60) && (forca < 85)){
			$(mostra).css('width', forca + '%').html('<span>Forte</span>');
		}else{
			$(mostra).css('width', '100%').html('<span>Excelente<n/span>');
		}
	}

	if (!vali_senha) {
		$('#renovaSenha').modal('show');
	}

	$('#pass_nova_confirm').on('keyup', function() {
		if ($('#pass_nova').val() == $('#pass_nova_confirm').val()) {
			$('#status_confirm').html('<i class="fa fa-check" style="color: green; font-size: 20px;"></i>');
		} else {
			$('#status_confirm').html('<i class="fa fa-exclamation-triangle" style="color: orange; font-size: 20px;"></i>');
		}
	});

	$('#salve_senha').on('click', function() {
	 	let pass = $('#pass_nova').val();
	 	let pass_conf = $('#pass_nova_confirm').val();
		let pass_atual = $('#pass_atual').val();
		let button = $(this);
		let notify = $('#alert_pass');
		notify.addClass('hide');

		if (!pass_atual) {
			notify.html('Digite sua senha atual.').removeClass('hide');
		 	return;
		}

		button.attr('disabled', true);
	 	if (pass == pass_conf) {
			if (forca >= 100) {
				$.ajax({
					url: '<?= site_url('acesso/updateSenha') ?>',
					type: 'POST',
					dataType: 'json',
					data: { pass_atual: pass_atual, pass_nova: pass },
					success: callback => {
						notify.html(callback.msg).removeClass('hide');
						button.removeAttr('disabled');

						if (callback.status == 'OK')
							$('#renovaSenha').modal('hide');
					},
					error: err => {
						notify.html('Tente novamente mais tarde.').removeClass('hide');
						button.removeAttr('disabled');
						console.log(err);
					}
				})
			} else {
				button.removeAttr('disabled');
				notify.html('A senha deve conter no mínimo 7 caracteres, uma letra maiúscula, um caractere especial e um número. Ex.: Ex&mpl@20').removeClass('hide');
			}
	 	} else {
			button.removeAttr('disabled');
	 	 	notify.html('Verifique a nova senha e tente novamente.').removeClass('hide');
	 	}
	});

	$('.data').mask('99/99/9999');
	$('.tel').mask('(99) 9999-9999');
	$('.hora').mask('99:99:99');
	$('.cep').mask('99999-999');
	$('.cpf').mask('999.999.999-99');
//	$('.placa').mask('aaa9999');
	$('.mes_ano').mask('99/9999');
	// $('.money2').mask('00000000000,00');
	//$('.money2').mask('000000000.00');
	//$(".money2").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
	$('.money2').maskMoney({symbol:'R$ ', showSymbol:true, thousands: '.', decimal:',', symbolStay: true, defaultZero: false, allowZero: true});
	// $("#ajax").css('display', 'none');

	$('.money').priceFormat({
		limit: 5,
		centsLimit: 3
	});

	$("#sticker").sticky({topSpacing:50});

	 $('.datepicker').datepicker(
			{format: 'dd/mm/yyyy',
				autoclose: true
	 });
	$('.datepicker2').datepicker(
		{format: 'dd/mm/yyyy',
			autoclose: true
		});

	$("#salvarBoletoConfig").click(function(){
		var form = $("#boleto-config");
		var controller_url = form.attr("action");
		$(this).text("Salvando...")
		$.post(controller_url, $(form).serialize(), function(e){
			$("#salvarBoletoConfig").text("Salvar");
			$("#faturaConfig").modal('hide');
		});

	});

	$(".funcao-apagar").click(function(){ //apagar conteudo
		var targetUrl = $(this).attr('data-controller');
		confirmaExclusao(targetUrl);
	});

});

function confirmaExclusao(url){
	var url = url;
	$('#confirmDiv').confirmModal({
		heading:'EXCLUSÃO',
		body:'Tem certeza que deseja excluir o registro?',
		callback: function() {
			window.location.href = url;
		}
	});
}

function imprimir(){
	window.print();
}

/* Máscaras ER */
function mascara(o,f){
	v_obj=o
	v_fun=f
	setTimeout("execmascara()",1)
}
function execmascara(){
	v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
	v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
	v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
	v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
	return v;
}
function mtel2(v){
	v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
	v=v.replace(/(\d)(\d{2})$/,"$1:$2");    //Coloca dois pontos entre o segundo e o terceiro dígitos
	return v;
}
function id( el ){
	return document.getElementById( el );
}
notify_account();
setInterval(function() {
	  notify_account();
}, 90000); // 3 minutes

function atualizarHorario(){
	var url = '<?php echo base_url() ?>'+'index.php';
	$.ajax({
		url: url+'/acesso/salvar_hora',
		type: 'POST',
		dataType: 'json',
	});
}

function notify_account() {
	$.ajax({
		url: "<?= site_url('contas/ajaxNotificationAccount') ?>",
		type: "POST",
		dataType: "json",
		success: function (callback) {
			$('#notify_account').html('');

			if (callback.status == 'OK') {
				// Array de notificações
				var ns = callback.notify;

				$('#qtd_badge').text(ns.length);
				$('#sino_alerta').attr('style', 'color: red !important');
				$('#qtd_badge').attr('style', 'background-color: red !important');

				for (var i=0;i<ns.length;i++) {
					comment = ns[i].comment;
					id_conta = ns[i].id_account;

					if (i>0) {
						$('#notify_account').append('<li class="divider"></li>');
					}

					$('#notify_account').append('<li>'+
							'<div class="col-md-9 col-sm-9 col-xs-9 pd-l0">'+
								'<b>Conta: #'+id_conta+'</b>'+
								' - <a href="'+"<?= site_url('contas/view_msgAcount') ?>/"+id_conta+'/notify">'+comment.substring(0, 20)+'...'+
								'</a>'+
							'</div>'+
						'</li>'
					);
				}
			} else {
				$('#qtd_badge').text('0');
				$('#sino_alerta').removeAttr('style');
				$('#qtd_badge').removeAttr('style');
				$('#notify_account').append('<li><div class="col-md-9 col-sm-9 col-xs-9 pd-l0"><center>Vazio</center><div><li>');
			}
		}
	});
}

function openChat() {
	$('#app').toggleClass('slideOutRight slideInRight');
	$('#divButtonChat').toggleClass('chatHide');
}

/*
* AJUSTA O TAMANHO E ORIENTACAO DA PAGINA DO ARQUIVO EXPORTADO DEPENDENDO DA QUANTIDADE DE COLUNAS DA TABELA
*/
function customPageExport(id_tabela, atributo='orientation'){
    var orientation, pageSize, count = 0;

    $('#'+id_tabela).find('thead tr:first-child th').each(function () {
        count++;
    });

    if(count > 12 && count <= 24) {
        orientation = 'landscape';
        pageSize = 'LEGAL';

    }else if(count > 24 && count <= 26){
        orientation = 'landscape';
        pageSize = 'A3';

    }else if(count > 24){
        orientation = 'landscape';
        pageSize = 'A2';

    }else{
        orientation = 'portrait';
        pageSize = 'LEGAL';
    }

    if (atributo === 'orientation'){
        return orientation;
    }else{
        return pageSize;
    }

    return false;
}

</script>
</body>
</html>
