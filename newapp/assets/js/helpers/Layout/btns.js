function desabilitarBotoes() {
    $('.btn').prop('disabled', true);
    $('.btn-success').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
}

function habilitarBotoes() {
    $('.btn').prop('disabled', false);
    $('.btn-success').html('<i class="fa fa-file-alt"></i> Gerar Relatório').attr('disabled', false);
}