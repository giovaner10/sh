<div id="modal_release" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_release" enctype="multipart/form-data">

                <input type="hidden" id="releaseId" name="releaseId" value="<?=isset($release->id) ? $release->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                
                    <div class="form-group">
                        <label class="control-label" for="release"><?=lang("titulo_release")?></label>
                        <input type="text" id="release" name="release" class="form-control" required
                            value="<?=isset($release->release_note) ? $release->release_note : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="<?=isset($release->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF)</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($release->id) ? '' : 'required'?>>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarRelease"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_release").modal({backdrop:'static'});

        // Salva Comunicado
        $("#form_release").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarRelease')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_release")[0]);
            let url;
            let urlAdd = "<?=site_url('Empresas/ReleaseNotes/adicionarRelease')?>";

            if (formData.get("releaseId"))
                url = "<?=site_url('Empresas/ReleaseNotes/editarRelease')?>/"
                    + formData.get("releaseId");
            else
                url = "<?=site_url('Empresas/ReleaseNotes/adicionarRelease')?>";

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        if (url == urlAdd){
                            enviarEmailReleases($("#release").val(), retorno.data);
                        }
                            
                        // Mensagem de retorno
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                        // Fecha modal
                        $("#modal_release").modal('hide');
                        // Recarrega a tabela
                        dt_releases.ajax.reload();
                    }
                    else
                    {
                        // Mensagem de retorno
                        toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    // Mensagem de erro
                    toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
                },
                complete: function ()
                {
                    // Carregado
                    $('#buttonSalvarRelease')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

function enviarEmailReleases($titulo, $data){
    $.ajax({
        url: "<?=site_url('Empresas/ReleaseNotes/notificarRelease')?>",
        type: "POST",
        data: {
            titulo: $titulo,
            data: $data
        },
        dataType: "json",
        success: function (retorno){
            retorno = JSON.parse(retorno)
            if (retorno.code != 200){
                alert("Não foi possível enviar a notificação da nova release para os usuários.");
            }
        },
        error: function (error){
            alert("Não foi possível enviar a notificação da nova release para os usuários.");
        }
    
    })
}
</script>