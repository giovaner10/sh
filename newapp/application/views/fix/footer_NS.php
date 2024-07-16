        <!-- MODAL RELOGAR-->
        <div id="modalRelogar" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="loginModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="login-icon-container">
                            <img src="<?php echo base_url('media/img/new_icons/shownet.png'); ?>" alt="ShowNet Icon" class="login-icon">
                        </div>
                        <div class="login-card">
                            <div class="login-body">
                                <br>
                                <div class="title-container">
                                    <h2>ShowNet</h2>
                                    <p>Acesso Restrito</p>
                                </div>
                                <br>
                                <div class="relogar-alert" style="display:none;">
                                    <span id="msg_relogar"></span>
                                </div>
                                <?php echo form_open('', array('class' => 'form-signin', 'id' => 'newFormRelogar')) ?>
                                <?php if (isset($success)) : ?>
                                    <div class="alert alert-success"><?php echo $success ?></div>
                                <?php endif; ?>
                                <div class="form-group input-container">
                                    <label for="login" class="icon-label">
                                        <img class="label-icon" src="<?php echo base_url('media/img/new_icons/user.png'); ?>" alt="User Icon">
                                        <?php echo lang('usuario'); ?>
                                    </label>
                                    <img class="label-icon input-icon-left" src="<?php echo base_url('media/img/new_icons/mail.png'); ?>" alt="Mail Icon">
                                    <input type="text" id="login" name="login" class="form-control" placeholder="<?php echo lang('digemail') ?>" value="<?php echo $this->input->post('login') ?>" required>
                                </div>
                                <div class="form-group input-container">
                                    <label for="senha" class="icon-label">
                                        <img class="label-icon" src="<?php echo base_url('media/img/new_icons/key.png'); ?>" alt="Key Icon">
                                        <?php echo lang('senha'); ?>
                                    </label>
                                    <img class="label-icon input-icon-left" src="<?php echo base_url('media/img/new_icons/security.png'); ?>" alt="Security Icon">
                                    <input type="password" id="senha" name="senha" class="form-control" placeholder="<?php echo lang('digsenha') ?>" required>
                                </div>
                                <br>
                                <div style="text-align: center;">
                                    <button type="submit" class="btn btn-btnRelogar" type="button" id="btnRelogar"><?= lang('entrar') ?></button>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                        <div id="emailSenhaInvalido" class="email-alert" style="visibility: hidden;">
                            <img class="label-icon" src="<?php echo base_url('media/img/new_icons/alert.png'); ?>" alt="Alert Icon">
                            <h2>E-mail ou senha inválidos!</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Estilo PDF Relatórios -->
<link rel="stylesheet" href="<?php echo base_url('media/css/new_modal_relogar.css'); ?>">
<script src="<?=versionFile('media/js', 'templateRelatorios.js') ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/custom.js"></script>

<!-- Constantes Utilizadas no template da geração dos relatórios -->
<script>
    // / Imagens
    const URL_WAVE = "<?= base_url('media/img/wave.png'); ?>"
    const URL_LOGO = "<?= base_url('media/img/Logo.png'); ?>" 
    const URL_LOGOOMNI = "<?= base_url('media/img/LogoOmni.png'); ?>"
    const ICON_CALENDAR = "<?= base_url('media/img/icons/calendar.png'); ?>"
    const ICON_CNPJ = "<?= base_url('media/img/icons/cpf.png'); ?>"
    const ICON_CLIENT = "<?= base_url('media/img/icons/friends.png'); ?>"
    const ICON_USER = "<?= base_url('media/img/icons/user.png'); ?>"

    // Dados muito utilizados - uso geral
    BASE_URL = "<?= base_url() ?>";
    SITE_URL = "<?= site_url() ?>";
        
</script>

<script>

    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-144270704-1');
    
    $(document).ready(function()
    {      
        if ($.fn.perfectScrollbar) {
        // Verifica se a função está definida
        $('.scrollbar').perfectScrollbar();
        }
        $(".dataTables_filter > label > input").attr("placeholder", lang.datatable.sSearch);

        $(".funcao-apagar").click(function(){ //apagar conteudo
            var targetUrl = $(this).attr('data-controller');
            confirmaExclusao(targetUrl);
        });

        //A CADA 30S VE SE O USUARIO ESTA EM SESSAO, SE NAO ESTIVER MANDA LOGAR
        var intervaloEmMilissegundos = 30 * 1000;
        var logged = setInterval(is_logged, intervaloEmMilissegundos);

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
                        $('#msg_relogar').html('<div class="alert alert-danger" style="white-space: normal;"><p><b>'+lang.sessao_expirada+'</b></p></div>');
                        //ABRE O MODAL PARA RELOGAR
                        $('#modalRelogar').modal({
                            keyboard: false,
                            show: true
                        });
                    }
                }
            });
        }

        $('#modalRelogar').on('show.bs.modal', function() {
            $('select').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('close');
                }
            });
        });

        /*
        * RELOGAR
        */
        $("#newFormRelogar").submit(function(e){
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
                        $("#msg_relogar").html('<div class="alert alert-success" style="white-space: normal;"><p><b>'+callback.msg+'</b></p></div>');
                        setTimeout(
                            function(){
                                $('#modalRelogar').modal('hide');
                                logged = setInterval(is_logged, 60000);
                            },
                            1000
                        );

                    }else {
                        $("#msg_relogar").html('<div class="alert alert-danger" style="white-space: normal;"><p><b>'+callback.msg+'</b></p></div>');
                    }
                },
                error: function(callback){
                    $("#msg_relogar").html('<div class="alert alert-danger" style="white-space: normal;"><p><b>'+lang.tente_mais_tarde+'</b></p></div>');
                },
                complete: function(callback){
                    //MOSTRA A MENSAGEM DE RETORNO
                    $('.relogar-alert').css('display', 'block');
                    botao.attr('disabled', false).html(lang.entrar);
                }
            })

        });

    });

    function confirmaExclusao(url)
    {
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

    function atualizarHorario()
    {
        var url = '<?php echo base_url()?>'+'index.php';

        $.ajax({
            url: url+'/acesso/salvar_hora',
            type: 'POST',
            dataType: 'json',
        });
    }

    /*
    * AJUSTA O TAMANHO E ORIENTACAO DA PAGINA DO ARQUIVO EXPORTADO DEPENDENDO DA QUANTIDADE DE COLUNAS DA TABELA
    */
    function customPageExport(id_tabela, atributo='orientation'){
        var orientation, pageSize, count = 0;

        $('#'+id_tabela).find('thead tr:first-child th').each(function () {
            count++;
        });

        if(count >= 10 && count <= 15) {
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
    * CRIA MASCARAS PARA VALORES (EX.: CPF, CNPJ, CEP...), BASTA PASSAR '#' ONDE SERA OS NUMERO
    */
    function criarMascara(valor, mascara){
        var mascarado = '';
        var k = 0;
        for (let i = 0; i <= (mascara.length) - 1; i++) {
            if(mascara[i] == '#') {
                if(typeof valor[k] !== 'undefined') mascarado += valor[k++];
            } else {
                if(typeof mascara[i] !== 'undefined') mascarado += mascara[i];
            }
        }

        return mascarado;
    }

    function exibirPerfilUsuario()
    {
        $('#loadingHomePage').show();
        $('#divPerfilUsuario').load('<?=site_url("PerfisUsuarios/modalPerfil")?>', function(response, status, xhr) {
            if (!response) {
                showAlert('warning', 'Não foi possível abrir o modal de perfil. Verifique se o login não foi expirado.')
            }
            $('#loadingHomePage').hide();
        });
    }
    
	/**
	 * VERIFICA SE UM EMAIL EH VALIDO
	*/
	function validateEmail(email){
        const expression = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return expression.test(email);
    }
    
	/*
	* DEVOLVE APENAS OS NUMEROS DA STRING PASSADA POR PARAMENTRO
	*/
	function apenasNumeros(string=''){
		return string.replace(/[^0-9]/g, '');
	}
</script>

</body>

</html>