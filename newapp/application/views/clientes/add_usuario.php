<form id="addUserForm">
    <input type="hidden" name="id_cliente" id="id">
    <div class="row">
        <div class="form-group col-md-6">
            <input type="hidden" value="<?php echo ($this->config->item('validar_email') ? 1 : 0)  ?>" id='permValidarEmail' />
            <input type="hidden" value="<?php echo ($this->config->item('validar_celular') ? 1 : 0)  ?>" id='permValidarSms' />
            <label class="control-label">Nome:</label>
            <input class="form-control" type="text" name="nome_usuario" id='nome_usuario' required>
            <label class="control-label" style="margin-top:10px;">Função:</label>
            <select class="form-control" id="option" name="tipo_usuario" required>
                <option value="">Escolha uma opção</option>
                <option value="administrador">Administração</option>
                <option value="monitoramento">Monitoramento</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label class="control-label">CPF:</label>
            <input class="form-control cpf" type="text" name="cpf" id="cpfNovoUsuario" min="14" max="14" required />
            <label>Senha:</label>
            <input class="form-control" type="password" name="senha" id="senha" required />
            <div class="progress">
                <div class="progress-bar" role="progressbar" id="progress-bar" style="width: 0%;"></div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-md-6">
            <label class="control-label">Email:</label>
            <input class="form-control" type="email" id="email" name="usuario" required>
            <?php if ($this->config->item('validar_email') == true) { ?>
                <label class="control-label" for='codigoEmail' style="margin-top:20px; ">Código de verificação do email:</label>
                <input class="form-control" type="text" name="codigoEmail" id="codigoEmail" disabled required />
                <button id="btnCodigoEmail" class="btn btn-primary" type="button" style=" margin-top:10px; ">Enviar código de verificação </button>
                <button id="mudarEmail" class="btn btn-primary" type="button" style=" margin-top:10px; display:none;">Mudar email</button>
            <?php } ?>
        </div>
        <div class="form-group col-md-6">
            <label class="control-label">Tel.:</label>
            <input class="form-control" type="text" id="tel" name="celular" min="13" max="14" required />
            <?php if ($this->config->item('validar_celular') == true) { ?>
                <label class="control-label" for='codigo' style="margin-top:20px; ">Código de verificação do telefone :</label>
                <input class="form-control" type="text" name="codigo" id="codigo" disabled required />
                <button id="btnCodigo" class="btn btn-primary" type="button" style=" margin-top:10px; ">Enviar código de verificação </button>
                <button id="mudarTel" class="btn btn-primary" type="button" style=" margin-top:10px; display:none;">Mudar telefone</button>
            <?php } ?>
        </div>
    </div>
    <div class="row">

        <!-- campo de verificação do codigo do celular-->
    </div>
    <div class="modal-footer">
        <button id="addUser" class="btn btn-primary"> Salvar</button>
    </div>
</form>



<script>
    $(document).ready(function() {
        let permisssaoValidarCelular = $('#permValidarSms').val()
        let permisssaoValidarEmail = $('#permValidarEmail').val()

        $(".cpf").mask("999.999.999-99");
        $("#tel").mask("(99) 99999-9999");

        var grupo = JSON.parse('<?= $grupos ?>');
        $.each(grupo, function(i, d) {
            $('#group').append('<option value="' + d.id + '">' + d.nome + '</option>');
        })

        $('#group').select2();

        $('#id').val(id_cliente);

        $('#senha').on('keyup', function() {
            senha = document.getElementById("senha").value;
            forca = 0;
            mostra = document.getElementsByClassName("progress-bar");
            if ((senha.length >= 4) && (senha.length <= 7)) {
                forca += 10;
            } else if (senha.length > 7) {
                forca += 15;
            }
            if (senha.match(/[a-z]+/)) {
                forca += 15;
            }
            if (senha.match(/[A-Z]+/)) {
                forca += 20;
            }
            if (senha.match(/[0-9]+/)) {
                forca += 30;
            }
            if (senha.match(/[^A-Za-z0-9_]/)) {
                forca += 20;
            }

            return mostra_res(mostra);
        });

        function mostra_res(mostra) {
            if (forca < 30) {
                $(mostra).css('width', forca + '%').html('<span>Fraca</span>');
            } else if ((forca >= 30) && (forca < 60)) {
                $(mostra).css('width', forca + '%').html('<span>Média</span>');
            } else if ((forca >= 60) && (forca < 85)) {
                $(mostra).css('width', forca + '%').html('<span>Forte</span>');
            } else {
                $(mostra).css('width', '100%').html('<span>Excelente<n/span>');
            }
        }

        //ADICIONAR USUARIO CLIENTE
        var senha, mostra;
        forca = 0;
        var blockAddUser = false;

        $('#addUserForm').submit(function(e) {
            e.preventDefault();

            //Se senha for fraca retorna mensagem
            if (forca < 75) return alert(lang.msg_senha_fraca);

            //Analisa se o campo email esta valido
            let email = $("#addUserForm input[name=usuario]").val();
            if (!validateEmail(email)) {
                alert(lang.msg_email_invalido);
                return false;
            }

            //Analisa se o campo telefone esta correto
            let telefone = $("#addUserForm input[name=celular]").val();
            telefone = apenasNumeros(telefone);
            if (telefone.length < 10) {
                alert(lang.msg_telefone_precisa_ter_10_ou_11_numeros);
                return false;
            }

            //Analisa se o campo cpf esta correto
            let cpf = $("#addUserForm input[name=cpf]").val();
            cpf = apenasNumeros(cpf);
            let cpfValido = validarCpf(cpf)
            if (!cpfValido) {
                alert("CPF inválido");
                return false;
            }
            if (blockAddUser == false) {
                if (permisssaoValidarCelular == 1 && permisssaoValidarEmail == 0) {
                    var codigoVericacaoSms = sessionStorage.getItem('codigoVerificacaoSms')
                    var codigoDigitadoSms = $("#codigo").val();
                    if (codigoVericacaoSms.length != 6) {
                        alert('O código de verificação precisa ter 6 dígitos');
                        return false;
                    }
                    if (codigoVericacaoSms != codigoDigitadoSms) {
                        alert("Código de verificação do telefone inválido");
                        return false;
                    } else {
                        cadastrarUsuario()
                    }
                }
                else if (permisssaoValidarCelular == 0 && permisssaoValidarEmail == 1) {
                    let codigoVericacaoEmail = sessionStorage.getItem('codigoVerificacaoEmail')
                    let codigoDigitadoEmail = $("#codigoEmail").val();
                    if (codigoVericacaoEmail.length != 6) {
                        alert('O código de verificação precisa ter 6 dígitos');
                        return false;
                    }
                    if (codigoVericacaoEmail != codigoDigitadoEmail) {
                        alert("Código de verificação do email inválido");
                        return false;
                    } else {
                        cadastrarUsuario()
                    }
                }
                else if (permisssaoValidarCelular == 1 && permisssaoValidarEmail == 1) {
                    let codigoVericacaoSms = sessionStorage.getItem('codigoVerificacaoSms')
                    let codigoDigitadoSms = $("#codigo").val();
                    let codigoVericacaoEmail = sessionStorage.getItem('codigoVerificacaoEmail')
                    let codigoDigitadoEmail = $("#codigoEmail").val();
                    if (codigoVericacaoSms != codigoDigitadoSms) {
                        alert("Código de verificação do telefone inválido");
                        return false;
                    } else if (codigoVericacaoEmail != codigoDigitadoEmail) {
                        alert("Código de verificação do email inválido");
                        return false;
                    } else {
                        cadastrarUsuario()
                    }
                }
                else {
                    cadastrarUsuario()
                }
            }
        })
    });

    function cadastrarUsuario() {
        blockAddUser = true;
        var button = $('#addUser');
        button.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
        var dados = {
            'nome_usuario': $('#nome_usuario').val(),
            'tipo_usuario': $('#option').val(),
            'senha': $('#senha').val(),
            'usuario': $('#email').val(),
            'celular': $('#tel').val(),
            'cpf': $('#cpfNovoUsuario').val(),
            'id_cliente': $('#id').val(),

        };

        

        $.ajax({
            url: '<?= site_url('usuarios_gestor/ajaxAddUser') ?>',
            type: 'POST',
            dataType: 'json',
            data: dados,
            success: function(callback) {
                
                if (callback.status == 'OK') {
                    alert(callback.msg);
                    button.removeAttr('disabled').html('Salvar')
                    $('#novo_usuario').modal('hide');

                    table_users.ajax.reload(null, false);
                } else {
                    blockAddUser = false;
                    alert(callback.msg);
                    button.removeAttr('disabled').html('Salvar');
                }
            },
            error: function(e) {
                alert('Erro ao cadastrar, tente novamente!');
                button.removeAttr('disabled').html('Salvar');
            }
        });

    }

    //FUNÇÃO PARA ENVIAR SMS PARA O CELULAR DO USUARIO
    $('#btnCodigo').click(function() {
        var celular = $('#tel').val();
        var celSemFormatacao = celular
        celular = apenasNumeros(celular);

        let celularValido = validarTelefone(celSemFormatacao);
        

        if (celularValido == false) {
            alert('Telefone inválido.Por favor, verifique o número digitado.');
            return false;
        } else {
            //Pega dados do do telefone, gera codigo de verificação e envia sms
            $('#tel').attr('disabled', 'true');
            let senha = gerarSenha();
            sessionStorage.setItem('codigoVerificacaoSms', senha);
            let dados = {
                celular: celular,
                senha: senha
            }
            $('#codigo').attr('disabled', false);
            $("#codigo").focus();
            $("#codigo").css('border', '1px solid #FF0000');
            $.ajax({
                url: '<?= site_url('usuarios_gestor/sendSms') ?>',
                type: 'POST',
                dataType: 'json',
                data: dados,
                success: function(callback) {
                
                    alert("Código de verificação enviado com sucesso.");
                }
            });

            //Desabilita o botão de enviar sms por 60 segundos
            countDown = 60;
            var timer = setInterval(function() {
                countDown--;
                $('#btnCodigo').html('Reenviar em ' + countDown + ' segundos');
                if (countDown == 0) {
                    clearInterval(timer);
                    $('#btnCodigo').removeAttr('disabled').html('Reenviar SMS');
                } else {
                    $('#btnCodigo').attr('disabled', 'true');
                }
            }, 1000);
        }
        $('#mudarTel').show();
        alert("Um código de verificação foi enviado para o número: " + celular);
    });



    //FUNÇÃO PARA ENVIAR EMAIL PARA O USUARIO
    $('#btnCodigoEmail').click(function() {
        var email = $('#email').val();
        let emailValido = validarEmail(email);
        if (!emailValido) {
            alert('Email inválido. Por favor, verifique campo')
        } else {
            //Pega dados do email, gera codigo de verificação e envia para o email
            $('#email').attr('disabled', true);
            let senhaEmail = gerarSenha()
            sessionStorage.setItem('codigoVerificacaoEmail', senhaEmail)
            let dados = {
                email: email,
                senha: senhaEmail
            }
            $.ajax({
                url: '<?= site_url('usuarios_gestor/sendEmail') ?>',
                type: 'POST',
                dataType: 'json',
                data: dados,
                success: function(callback) {
                    alert('Email enviado com sucesso!')
                }
            });
            $('#codigoEmail').attr('disabled', false);
            $('#codigoEmail').focus();
            $('#codigoEmail').css('border', '1px solid #FF0000');
            
            //Desabilita o botão de enviar email por 60 segundos
            countDownEmail = 60;
            var timer = setInterval(function() {
                countDownEmail--;
                $('#btnCodigoEmail').html('Reenviar em ' + countDownEmail + ' segundos');
                if (countDownEmail == 0) {
                    clearInterval(timer);
                    $('#btnCodigoEmail').removeAttr('disabled').html('Reenviar Email');
                } else {
                    $('#btnCodigoEmail').attr('disabled', 'true');
                }
            }, 1000);
        }
        $('#mudarEmail').show();
        alert("Um código de verificação foi enviado para o email: " + email);
    })


    $('#codigoEmail').click(function() {
        $('#codigoEmail').css('border', '0px solid #ccc');
    })
    $('#codigoEmail').click(function() {
        $('#codigoEmail').css('border', '0px solid #ccc');
    })

    //função para permitir alterar o email 
    $('#mudarEmail').click(function() {
        $('#email').attr('disabled', false);
        $('#mudarEmail').hide();
        sessionStorage.setItem('codigoVerificacaoEmail', '');
    })

    //função para permitir alterar o telefone
    $('#mudarTel').click(function() {
        $('#tel').attr('disabled', false);
        $('#mudarTel').hide();
        sessionStorage.setItem('codigoVerificacaoSms', '');
    })

    //FUNÇÃO PARA GERAR SENHA ALEATORIA
    function gerarSenha() {
        senha = Math.random().toString(36).slice(-6);
        return senha;
    }

    function validarTelefone(telefone) {
        var regex = /^\([1-9]{2}\) [9]{1}[0-9]{4}-[0-9]{4}$/;
        return regex.test(telefone);
    }

    function validarEmail(email) {
        var regex = /^[a-z0-9.]+@[a-z0-9]+\.[a-z]+(\.[a-z]+)?$/i;
        return regex.test(email);
    }

    function validarCpf(cpf) {
        if (typeof cpf !== "string") return false
        cpf = cpf.replace(/[^\d]+/g, '')
        if (cpf.length !== 11 || !!cpf.match(/(\d)\1{10}/)) return false
        cpf = cpf.split('').map(el => +el)
        const rest = (count) => (cpf.slice(0, count - 12)
            .reduce((soma, el, index) => (soma + el * (count - index)), 0) * 10) % 11 % 10
        return rest(10) === cpf[9] && rest(11) === cpf[10]

    }
</script>