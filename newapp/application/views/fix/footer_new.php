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

<footer class="footerNew no-print col-md-12">
    <strong>Copyright &copy; <?=date('Y')?> <a href="#"><?=lang('show_tecnologia')?></a> & <a href="#"><?=lang('omnilink')?></a> - </strong> <?=lang('copyright')?>
</footer>

<!-- MODAL RELOGAR-->
<div id="modalRelogar" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="height:500px;">
                <div class="col-md-12 form-group">
                    <div class="col-md-12" style="background-color: #0079bf; ">
                        <div class="col-md-12" style="text-align: center; height:100px; margin-top:20px;">
                            <img src="<?=base_url('media/img/logo-topo-login.png');?>" alt="">
                        </div>
                        <div class="col-md-12" style="color:#fff; text-align:right; margin-bottom:25px;">
                            <span>
                                SHOWNET<br>
                                <small>Acesso Restrito</small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="relogar-alert alert" style="display:none; margin-bottom:0px;">
                        <span id="msg_relogar"></span>
                    </div>
                    <form id="formRelogar">
                        <div class="">
                            <div class="col-md-12 form-group">
                                <label for="exampleInputEmail1"><?=lang('email');?></label>
                                <input  type="text" name="login" class="form-control" placeholder="<?=lang('digemail')?>" value="" required>
                                <small id="emailHelp" class="form-text text-muted"></small>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="exampleInputPassword1"><?=lang('senha');?></label>
                                <input type="password" name="senha" class="form-control" placeholder="<?=lang('digsenha')?>" required>
                            </div>
                            <div align="center">
                                <button type="submit" class="btn btn-large btn-primary" style="width: 100%" type="button" id="btnRelogar"><?=lang('entrar')?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets') ?>/plugins/priceformat/jquery.price_format.1.8.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('assets') ?>/plugins/sticky/jquery.sticky.js"></script>
<script	src="<?= base_url('assets') ?>/plugins/form-jquery/jquery.form.min.js"></script>

<!-- modulos JS -->
<script src="<?php echo base_url('assets') ?>/js/modules/clientes.js"></script>
<script src="<?php echo base_url('assets') ?>/js/modules/filiais.js"></script>
<script src="<?php echo base_url('assets') ?>/js/modules/app.js"></script>

<script type="text/javascript">
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
    			forca += 15;
    		}
    		if(senha.match(/[a-z]+/)) {
    			forca += 15;
    		}
    		if(senha.match(/[A-Z]+/)) {
    			forca += 20;
    		}
    		if(senha.match(/[0-9]+/)){
    			forca += 30;
    		}
    		if(senha.match(/[^A-Za-z0-9_]/)){
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

        //MASCARAS DE INPUT
	    $(document).on('focus', '.cpf', function(){ $('.cpf').mask('999.999.999-99'); });
	    $(document).on('focus', '.cnpj', function(){ $('.cnpj').mask('99.999.999/9999-99'); });
	    $(document).on('focus', '.cpfCnpj', function(){ $('.cpfCnpj').mask('000.000.000-000', options); });
	    $(document).on('focus', '.cep', function(){ $('.cep').mask('99.999-999'); });
	    $(document).on('focus', '.fone', function(){ $('.fone').mask('0000-0000'); });
	    $(document).on('focus', '.cell', function(){ $('.cell').mask('(00)00000-0000'); });
    	$('.data').mask('99/99/9999');
    	$('.tel').mask('(99) 9999-9999');
    	$('.hora').mask('99:99:99');
    	$('.mes_ano').mask('99/9999');
    	$('.money2').maskMoney({symbol:'R$ ', showSymbol:true, thousands: '.', decimal:',', symbolStay: true, defaultZero: false, allowZero: true});
    	$('.money').priceFormat({
    		limit: 5,
    		centsLimit: 3
    	});
		
		// MASCARA DE DINHEIRO
		$('.dinheiro').mask("#.##0,00", {
			reverse: true
		});

        var options = {
            onKeyPress: function (cpf, ev, el, op) {
                var masks = ['000.000.000-000', '00.000.000/0000-00'],
                    mask = (cpf.length > 14) ? masks[1] : masks[0];
                el.mask(mask, op);
            }
        };




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

        //A CADA 10S VE SE O USUARIO ESTA EM SESSAO, SE NAO ESTIVER MANDA LOGAR
        var logged = setInterval(is_logged, 300000);

        /*
        * VER SE O USUARIO ESTA DESLOGADO NA SESSAO E ABRE MODAL DE LOGIN
        */
        async function is_logged(){
    		$.ajax({
    			url: "<?=site_url('acesso/isLogged')?>",
    			type: "POST",
    			dataType: "json",
    			data:{area: 'admin'},
    			success: function (callback) {
    				if (callback.logado === false) {
    					$('.relogar-alert').css('display', 'block');
    					$('#msg_relogar').html('<div class="alert alert-danger"><p><b>'+lang.sessao_expirada+'</b></p></div>');
    					//ABRE O MODAL PARA RELOGAR
    					$('#modalRelogar').modal({
    						keyboard: false,
    						show: true
    					});
    					//PARA O LOOP
    					clearInterval(logged);
    				}
    			}
    		});
    	}

        /*
        * RELOGAR
        */
        $("#formRelogar").submit(function(e){
             e.preventDefault();
             var dadosform = $(this).serialize();
             var botao = $('#btnRelogar');

            $.ajax({
                url: "<?=site_url('acesso/entrarAjax')?>",
                type: "POST",
                dataType: "json",
                data: dadosform,
                beforeSend: function (callback) {
                    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> '+lang.entrando);
                },
                success: function (callback) {
                    if (callback.success) {
                        $("#msg_relogar").html('<div class="alert alert-success"><p><b>'+callback.msg+'</b></p></div>');
                        setTimeout(
                            function(){
                                $('#modalRelogar').modal('hide');
                                logged = setInterval(is_logged, 300000);
                            },
                            1000
                        );

                    }else {
                        $("#msg_relogar").html('<div class="alert alert-danger"><p><b>'+callback.msg+'</b></p></div>');
                    }
                },
                error: function(callback){
                    $("#msg_relogar").html('<div class="alert alert-danger"><p><b>'+lang.tente_mais_tarde+'</b></p></div>');
                },
                complete: function(callback){
                    //MOSTRA A MENSAGEM DE RETORNO
                    $('.relogar-alert').css('display', 'block');
                    botao.attr('disabled', false).html(lang.entrar);
                }
            })

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

        if(count > 10 && count <= 15) {
            orientation = 'landscape';
            pageSize = 'LEGAL';

        }else if(count > 15 && count <= 24){
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

    /*
    * AO CLICAR NO BOTAO DE FEICHAR A MENSAGEM, A MESMA SERA REMOVIDA DA VIEW
    */
    function fecharMensagem(mensagem){
        //esconde o campo da mensagem de cadastro de placa
        $('.'+mensagem).css('display', 'none');
    }

    /*
    * CRIA OS LIMITES MINIMOS E MAXIMOS PARA IMPUTS DE DATAS
    */
    //parametros:
    // identificador, nome do identificador do imput que recebera os limites
    // data: a data apartir da quel se calculara os limites
    // difDias,difMeses,difAnos serão os demilitadores, por exemplo, se vier 1 no parametro difAno, entao sera min:-1 ano max:+1 ano

    function minMaxDataImput(identificador, data=new Date(), difDias=0,difMeses=0,difAnos=100){
        diaMin = data.getDate()<10 ? '0'+(parseInt(data.getDate())-difDias) : (parseInt(data.getDate())-difDias);
        mesMin = data.getMonth()<9 ? '0'+(parseInt(data.getMonth())+1-difMeses) : (parseInt(data.getMonth())+1-difMeses);
        anoMin = (parseInt(data.getFullYear())-difAnos);

        diaMax = data.getDate()<10 ? '0'+(parseInt(data.getDate())+difDias) : (parseInt(data.getDate())+difDias);
        mesMax = data.getMonth()<10 ? '0'+(parseInt(data.getMonth())+1+difMeses) : (parseInt(data.getMonth())+1+difMeses);
        anoMax = (parseInt(data.getFullYear())+difAnos);

        minData = anoMin+'-'+mesMin+'-'+diaMin;
        maxData = anoMax+'-'+mesMax+'-'+diaMax;
        $(identificador).attr('min', minData).attr('max', maxData);
    }

    //ORDENA A DATATABLE CORRETAMENTE POR COLUNAS COM DATAS NO FORMATO 'DD/MM/YYYY'
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-uk-pre": function ( a ) {
            if (a == null || a == "") {
                return 0;
            }
            var ukDatea = a.split('/');
            return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
        },

        "date-uk-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-uk-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );
	

	/*
	* DEVOLVE APENAS OS NUMEROS DA STRING PASSADA POR PARAMENTRO
	*/
	function apenasNumeros(string=''){
		return string.replace(/[^0-9]/g, '');
	}

	/**
	 * VERIFICA SE UM EMAIL EH VALIDO
	*/
	function validateEmail(email){
        const expressao = /^\w+([\.\+-]*\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        return expressao.test(email);
    }

</script>

</body>
</html>
