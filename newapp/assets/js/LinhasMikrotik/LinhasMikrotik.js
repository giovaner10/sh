$(document).ready(function() {
    $('#upload:hidden').on('change', function() {
        var arquivo = $(this);
        var ext = arquivo.val().split('.').pop().toLowerCase();
        var extPermitida = 'csv';

        if (arquivo.val() == '') {
            alert('Escolha um arquivo para enviar.');
        } else if (ext !== extPermitida) {
            alert('Permitido apenas arquivos .' + extPermitida.toUpperCase() + '!');
        } else {
            var botao = $('#bt_enviar');
            var bt_status = true;
            var retorno;

            $('#form-linhas').ajaxSubmit({
                target: '#result_upload',
                resetForm: true,
                uploadProgress: function(event, position, total, percentComplete) {
                    $('.upload').css('width', (percentComplete - 10) + '%');
                    $('.result_upload').html('Enviando Arquivo. Por favor aguarde...');
                    if (bt_status) {
                        botao.attr('disabled', 'disabled');
                        bt_status = false;
                    }
                },
                success: function() {
                    $('.result_upload').html('Processando arquivo, por favor aguarde...');
                },
                complete: function(xhr) {
                    retorno = xhr.responseText;
                    $('.upload').css('width', '100%');
                    if (!bt_status) {
                        botao.removeAttr('disabled');
                        bt_status = true;
                    }
                    if (retorno == '1') {
                        $('.result_upload').html('Processando arquivo, por favor aguarde...');
                        $('#conteudo').load(ajax_cad_linhas_url, '', function() {
                            $('.result_upload').html('');
                            $('.upload').css('width', '0%');
                        });
                    } else {
                        $('.result_upload').html(retorno);
                    }
                }
            });
        }
    });
});
