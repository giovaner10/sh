<div id="faturaConfig" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Configurar Boleto</h3>
    </div>
    <div class="modal-body">
        <p>Escolha um banco emissor para boletos</p>
        <?=form_open(site_url('faturas/salvar_config_boleto'), array('id' => 'boleto-config')) ?>
        <?php $boleto = get_boleto_default();?>
        <label class="checkbox inline">
            <input name="emissor_boleto" type="radio" id="inlineCheckbox1" value="1" <?php echo $boleto == 1 ? 'checked = "true"' : ''?>> Banco do Brasil
        </label>
        <label class="checkbox inline">
            <input name="emissor_boleto" type="radio" id="inlineCheckbox2" value="2" <?php echo $boleto == 2 ? 'checked = "true"' : ''?>> Unicred
        </label>
        <?=form_close();?>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
        <button id="salvarBoletoConfig" class="btn btn-primary">Salvar</button>
    </div>
</div>

<div class="container-fluid">
    <div class="col-md-6 col-lg-6 col-md-push-4"><p style="text-align: center;" class="navbar-text">&copy;<?php echo date('Y')?> Show Tecnologia - Todos os direitos reservados.</p></div>
</div>

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

<?php //endif;?>


<!-- javascripts (melhora desempenho no final da página) -->
<!-- JA CARREGADO NO HEADER
<script type="text/javascript" src="<?php echo base_url('media')?>/js/jquery.js"></script>
-->
<script type="text/javascript" src="<?php echo base_url('media')?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/bootstrap-confirm.js"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/custom.js"></script>
<?php if($this->auth->get_login('admin', 'email')):
    echo '<script type="text/javascript" src="'.base_url("media").'/js/jquery.maskedinput-1.3.js"></script>';
endif; ?>
<script type="text/javascript" src="<?php echo base_url('assets')?>/plugins/priceformat/jquery.price_format.1.8.min.js"></script>

<!--
###################### BIBLIOTECA COM PROBLEMAS NA PÁGINA CLIENTES ############################
<script type="text/javascript" src="<?php echo base_url('media')?>/js/calendario.js"></script>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('assets')?>/plugins/sticky/jquery.sticky.js"></script>

<script	src="<?php echo base_url('assets')?>/plugins/form-jquery/jquery.form.min.js"></script>
<script type="text/javascript"
        src="<?php echo base_url('media')?>/js/bootstrap-filestyle.min.js"> </script>

<!-- modulos JS -->
<script src="<?php echo base_url('assets')?>/js/modules/clientes.js"></script>
<script src="<?php echo base_url('assets')?>/js/modules/filiais.js"></script>
<script src="<?php echo base_url('assets')?>/js/modules/app.js"></script>

<script type="text/javascript">

    $(":file").filestyle({buttonText: "Arquivo"});

    // var idleTime = 0;

    $(document).ready(function(){

        $('.data').mask('99/99/9999');
        $('.tel').mask('(99) 9999-9999');
        $('.hora').mask('99:99:99');
        $('.cep').mask('99999-999');
        $('.cpf').mask('999.999.999-99');
        $('.placa').mask('aaa9999');
        $('.mes_ano').mask('99/9999');
        $('.money').mask('000000000.00');
        $('.money2').maskMoney({symbol:'R$ ', showSymbol:true, thousands:'.', decimal:',', symbolStay: true});
        $("#ajax").css('display', 'none');
        
        $(document).on('focus', '.ref', function(){ $('.ref').mask('00/0000') });

        $('.money').priceFormat({
            limit: 5,
            centsLimit: 3
        });

        $("#sticker").sticky({topSpacing:50});

        // $('.datepicker').datepicker(
        // 		{format: 'dd/mm/yyyy'
        // });

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
        var logged = setInterval(is_logged, 30000);

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
                                logged = setInterval(is_logged, 10000);
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

    setInterval(function() {
        atualizarHorario();
    }, 180000); // 3 minutes

    function atualizarHorario(){

        var url = '<?php echo base_url()?>'+'index.php';
        $.ajax({
            url: url+'/acesso/salvar_hora',
            type: 'POST',
            dataType: 'json',
        });
    }


</script>

</body>
</html>
