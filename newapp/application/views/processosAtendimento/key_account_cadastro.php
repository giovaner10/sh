<div id="modal_key_account" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_key_account" enctype="multipart/form-data">

                <input type="hidden" id="key_accountId" name="key_accountId" value="<?=isset($key_account->id) ? $key_account->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="modalLabel"><?=$modalTitulo?></h3>
                </div>

                <div class="modal-body">
                
                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="key_account"><?=lang("titulo")?>: </label>
                        <input type="text" id="key_account" name="key_account" placeholder="Digite o título..." class="form-control" maxlength="100" required
                            value="<?=isset($key_account->titulo_key_account) ? $key_account->titulo_key_account : ''?>">
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="<?=isset($key_account->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF):</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($key_account->id) ? '' : 'required'?>>
                    </div>

                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                        <button type="submit" class="btn btn-success" id="buttonSalvarKeyAccount"><?=lang("salvar")?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_key_account").modal({backdrop:'static'});

        // Salva Comunicado
        $("#form_key_account").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarKeyAccount')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_key_account")[0]);
            let url;

            if (formData.get("key_accountId"))
                url = "<?=site_url('ProcessosAtendimento/KeyAccount/editarKeyAccount')?>/"
                    + formData.get("key_accountId");
            else
                url = "<?=site_url('ProcessosAtendimento/KeyAccount/adicionarKeyAccount')?>";

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
                        $("#modal_key_account").modal('hide');
                        // Recarrega a tabela
                        getKeyAccountsAgGrid();
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
                    $('#buttonSalvarKeyAccount')
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