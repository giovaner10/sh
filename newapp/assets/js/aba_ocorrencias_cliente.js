$(document).ready(function () {
    function enviarOcorrencia(){
        var idCliente = $('#idCliente').text().match(/\d+/)[0];
        var descricao = $('#desc_ocorrencia').val();



        $.ajax({
            url: Router + "/salvarOcorrencia",  
            type: 'POST',
            data: {
                idCliente: idCliente,
                descricao: descricao
            },
            success: function(response) {
                console.log('Sucesso:', response);
            },
            error: function(xhr, status, error) {
                // Código para executar em caso de erro
                console.error('Erro:', error);
            }
        });
    }

});
