<div id="modal_agendamento" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_agendamento" enctype="multipart/form-data">

                <input type="hidden" id="agendamentoId" name="agendamentoId" value="<?=isset($agendamento->id) ? $agendamento->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="modalLabel"><?=$modalTitulo?></h3>
                </div>

                <div class="modal-body">
                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="agendamento"><?=lang("titulo")?></label>
                        <input type="text" id="agendamento" name="agendamento" placeholder="Digite o título do agendamento..." class="form-control" maxlength="100" required
                            value="<?=isset($agendamento->titulo_agendamento) ? $agendamento->titulo_agendamento : ''?>">
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="<?=isset($agendamento->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF)</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($agendamento->id) ? '' : 'required'?>>
                    </div>

                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                        <button type="submit" class="btn btn-success" id="buttonSalvarAgendamento"><?=lang("salvar")?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_agendamento").modal({backdrop:'static'});

        // Salva Comunicado
        $("#form_agendamento").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarAgendamento')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_agendamento")[0]);
            let url;

            if (formData.get("agendamentoId"))
                url = "<?=site_url('ProcessosAtendimento/Agendamento/editarAgendamento')?>/"
                    + formData.get("agendamentoId");
            else
                url = "<?=site_url('ProcessosAtendimento/Agendamento/adicionarAgendamento')?>";

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
                        $("#modal_agendamento").modal('hide');
                        // Recarrega a tabela
                        getAgendamentosAgGrid();
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
                    showAlert('error', '<?=lang("mensagem_erro")?>');
                },
                complete: function ()
                {
                    // Carregado
                    $('#buttonSalvarAgendamento')
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