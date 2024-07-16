<div id="modal-listar-dados-post" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="h3-busca-na">Informações da ocorrência</h4>
            </div>
            <div class="modal-body">
                    <div>
                        <strong>ID:</strong> <?= $modal_data->id ?>
                    </div>
                    <div>
                        <strong>Data e Hora:</strong> <?= $modal_data->dataHora ?>
                    </div>
                    <div>
                        <strong>Terminal:</strong> <?= $modal_data->terminal ?>
                    </div>
                    <div>
                        <strong>Post:</strong> <?= $modal_data->post ?>
                    </div>
                    <div>
                        <strong>Codmsg:</strong> <?= $modal_data->codmsg ?>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.open-modal').click(function(e) {
        e.preventDefault();
        var terminal = $(this).data('terminal');
        
        $.ajax({
            url: '<?php echo site_url("veiculos/listarDadosPost/"); ?>' + terminal,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Verifique se os dados foram recebidos corretamente
                if (data && data.id) {
                    // Preencha os elementos do modal com os dados
                    $('#modal-listar-dados-post #modal-id').text(data.id);
                    $('#modal-listar-dados-post #modal-data-hora').text(data.dataHora);
                    $('#modal-listar-dados-post #modal-terminal').text(data.terminal);
                    $('#modal-listar-dados-post #modal-post').text(data.post);
                    $('#modal-listar-dados-post #modal-codmsg').text(data.codmsg);
                    
                    $('#modal-listar-dados-post').modal('show');
                } else {
                    alert("Nenhum dado disponível.");
                }
            },
            error: function() {
                alert("Erro ao acessar a API.");
            }
        });
    });
});


</script>

