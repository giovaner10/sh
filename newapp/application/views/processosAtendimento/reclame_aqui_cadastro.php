<div id="modal_reclame_aqui" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_reclame_aqui" enctype="multipart/form-data">

                <input type="hidden" id="reclame_aquiId" name="reclame_aquiId" value="<?=isset($reclame_aqui->id) ? $reclame_aqui->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="modalLabel"><?=$modalTitulo?></h3>
                </div>

                <div class="modal-body">
                
                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="reclame_aqui"><?=lang("titulo")?></label>
                        <input type="text" id="reclame_aqui" name="reclame_aqui" placeholder="Digite o título..." class="form-control" maxlength="100" required
                            value="<?=isset($reclame_aqui->titulo_reclame_aqui) ? $reclame_aqui->titulo_reclame_aqui : ''?>">
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="<?=isset($reclame_aqui->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF)</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($reclame_aqui->id) ? '' : 'required'?>>
                    </div>

                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: end;">
                        <button type="submit" class="btn btn-primary" id="buttonSalvarReclameAqui"><?=lang("salvar")?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_reclame_aqui").modal({backdrop:'static'});

        // Salva Comunicado
        $("#form_reclame_aqui").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarReclameAqui')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_reclame_aqui")[0]);
            let url;

            if (formData.get("reclame_aquiId"))
                url = "<?=site_url('ProcessosAtendimento/ReclameAqui/editarReclameAqui')?>/"
                    + formData.get("reclame_aquiId");
            else
                url = "<?=site_url('ProcessosAtendimento/ReclameAqui/adicionarReclameAqui')?>";

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        // Mensagem de retorno
                        showAlert('success', retorno.mensagem);

                        // Fecha modal
                        $("#modal_reclame_aqui").modal('hide');
                        // Recarrega a tabela
                        getReclameAquiAgGrid();
                    }
                    else
                    {
                        // Mensagem de retorno
                        showAlert('warning', retorno.mensagem);
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    // Mensagem de erro
                    showAlert('error', '<?=lang("mensagem_erro")?>')

                    // Carregado
                    $('#buttonSalvarReclameAqui')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                complete: function ()
                {
                    // Carregado
                    $('#buttonSalvarReclameAqui')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

</script>