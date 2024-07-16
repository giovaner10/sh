<style>
    .label-foto {
        margin-left: 50px;
    }

    @media (max-width:640px) {
        .label-foto {
            margin-left: 150px;
        }
    }
</style>
<div id="modalPerfilUsuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form id="formPerfilUsuario" enctype="multipart/form-data">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="modalLabel"><?= $modalTitulo ?></h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-lg-5">
                            <input type="hidden" id="arquivoId" name="arquivoId" value="<?= $usuario->id_arquivos ?>">

                            <div class="alert alert-info">
                                <span style="font-size: 10pt; color: #1C69AD;">
                                    <?= lang('limite_tam_arq_1m') ?><br>
                                    <?= lang('formatos_permitidos') ?>:<br>
                                    <?= $formatosPermitidos ?>
                                </span>
                            </div>

                            <label style="cursor: pointer;" class="label-foto">
                                <div class="img-thumbnail">
                                    <img src="<?= $usuario->fotoPerfilUrl ?>" class="img-responsive" style="width: 220px; height: 220px;" id="output" />
                                    <div class="caption">
                                        <input id="input-file" name="imagem" style="display: none;" type="file" accept="image/*" onchange="leUrl(this)">
                                    </div>
                                </div>
                                <div align="center">
                                    <span class="label label-info"><?= lang('alt_img') ?></span>
                                </div>
                            </label>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <label for="titulo"><?= lang('nome') ?>:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nome" id="nome" title="Não utilize aspas simples ou duplas" value="<?= $usuario->nome ?>" pattern="[^'\x22]+" required maxlength="100" onkeyup="contagemCaracteresNome()" />
                                <span class="label" id="content-countdown" style="color:black; float:right">0/100</span>
                            </div>

                            <div class="form-group">
                                <label for="titulo"><?= lang('senha_atual') ?>:<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="senhaAtual" name="senhaAtual" value="" placeholder="*********" required />
                                <span class="help-inline" style="font-size: 8pt;">*<?= lang('info_senha_atual') ?></span>
                            </div>

                            <div class="form-group">
                                <label for="titulo"><?= lang('senha_nova') ?>:<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="senhaNova" name="senhaNova" id="senhaNova" onkeyup="validaForcaSenha()" value="" placeholder="*********" />
                                <span class="help-inline" style="font-size: 8pt;">*<?= lang('info_senha') ?></span>
                            </div>

                            <div id="senhaBarra" class="progress barrain" style="height: 20px;">
                                <div id="senhaForca" class="progress-bar progress-bar-success barra" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width : 0%; color: #0c0c0c">
                                    <span id="texoprogress" style="display: inline;"> </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="titulo"><?= ('Confirmação de Senha') ?>:<span class="text-danger">*</span></label>
                                <input type="password" class="form-control senhaout" id="confirm_password" name="senhaNovaConfirmacao" value="" placeholder="*********" />
                                <span class="help-inline" style="font-size: 8pt;">*<?= ('Digite novamente a senha') ?></span>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang("fechar") ?></button>
                        <button type="submit" class="btn btn-success" id="buttonSalvarPerfilUsuario"><?= lang("salvar") ?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">

<script>
    $(document).ready(function() {
        $("#modalPerfilUsuario").modal();

        contagemCaracteresNome();
    })

    $("#formPerfilUsuario").on("submit", function(evento) {
        evento.preventDefault();

        // Carregando
        $('#buttonSalvarPerfilUsuario')
            .html('<i class="fa fa-spin fa-spinner"></i> <?= lang('salvando') ?>')
            .attr('disabled', true);

        let url = "<?= site_url('PerfisUsuarios/perfilUsuarioEdicao') ?>";
        let formData = new FormData($("#formPerfilUsuario")[0]);

        // Codifica as senhas
        if (formData.get('senhaAtual'))
            formData.set('senhaAtual', md5(formData.get('senhaAtual')));

        if (formData.get('senhaNova'))
            formData.set('senhaNova', md5(formData.get('senhaNova')));

        if (formData.get('senhaNovaConfirmacao'))
            formData.set('senhaNovaConfirmacao', md5(formData.get('senhaNovaConfirmacao')));

        $.ajax({
            url: url,
            data: formData,
            type: "POST",
            dataType: "JSON",
            success: function(retorno) {
                if (retorno.status == 1) {
                    showAlert("success", retorno.mensagem);
                    $("#modalPerfilUsuario").modal('hide');
                } else {
                    showAlert("warning", retorno.mensagem);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                showAlert("error", 'Falha ao enviar a imagem! Tente novamente.');
            },
            complete: function() {
                // Carregado
                $('#buttonSalvarPerfilUsuario')
                    .html('<?= lang('salvar') ?>')
                    .attr('disabled', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    function validaForcaSenha() {
        var senha = $('#senhaNova').val();

        if (senha == '') {
            $('#senhaForca').css('width', '0%');
            $('#senhaForca').text('');
            return false;
        }

        var fSenha = 0;
        var texto = "";
        $('#senhaForca').css('width', '100%');
        $('#senhaForca').removeClass();
        $('#senhaForca').addClass('progress-bar');

        if (senha.length <= 6) {
            texto = lang.fraca_s;
            $('#senhaForca').addClass('progress-bar-danger');
            fSenha = 33;
        } else if (senha.length <= 8) {
            texto = lang.media_s;
            $('#senhaForca').addClass('progress-bar-warning');
            fSenha = 66;
        } else {
            texto = lang.forte_s;
            $('#senhaForca').addClass('progress-bar-success');
            fSenha = 100;
        }

        $('#senhaForca').css('width', fSenha + '%');
        $('#senhaForca').text(texto);
    }

    function leUrl(input) {
        // Instancia um objeto FileReader que permite aplicações web ler o conteúdo dos arquivos
        var reader = new FileReader();

        // Este evento é chamado cada vez que a operação de leitura é completada com sucesso.
        reader.onload = function(e) {
            // Seta a imagem no src da div a cima
            $('#output').attr('src', e.target.result);
        };

        // Inicia a leitura do conteúdo que caira após operação completar na função acima
        if (input.files && input.files[0])
            reader.readAsDataURL(input.files[0]);
        else
            $('#output').attr('src', "<?= base_url('media/img/usuario-anonimo.png') ?>");
    }

    // Faz a contagem de string do campo Nome
    function contagemCaracteresNome() {
        const maxtext = 100;
        let nome = $('#nome').val();
        let target = $("#content-countdown");
        let len = nome.length;

        if (len < maxtext) {
            target.html(len + '/' + maxtext);
            target.css("color", "black");
        } else if (len == maxtext) {
            target.html(maxtext + '/' + maxtext);
            target.css("color", "red");
            e.preventDefault();
        } else if (nome.length > maxtext) {
            nome = nome.substring(0, maxtext);
        }
    }
</script>