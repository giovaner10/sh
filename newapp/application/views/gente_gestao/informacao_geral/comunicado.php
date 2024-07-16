<div id="modal_comunicado" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_comunicado" enctype="multipart/form-data">

                <input type="hidden" id="comunicadoId" name="comunicadoId" value="<?=isset($comunicado->id) ? $comunicado->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                
                    <div class="form-group">
                        <label class="control-label" for="comunicado"><?=lang("titulo_comunicado")?></label>
                        <input type="text" id="comunicado" name="comunicado" class="form-control" required
                            value="<?=isset($comunicado->comunicado) ? $comunicado->comunicado : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="<?=isset($comunicado->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF)</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($comunicado->id) ? '' : 'required'?>>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarComunicado"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_comunicado").modal({backdrop:'static'});

        // Salva Comunicado
        $("#form_comunicado").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarComunicado')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_comunicado")[0]);
            let url;

            if (formData.get("comunicadoId"))
                url = "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/editarComunicado')?>/"
                    + formData.get("comunicadoId");
            else
                url = "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/adicionarComunicado')?>";

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
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                        // Fecha modal
                        $("#modal_comunicado").modal('hide');
                        // Recarrega a tabela
                        dt_comunicados.ajax.reload();
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
                    $('#buttonSalvarComunicado')
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