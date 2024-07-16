<div id="modal_orcamento" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_orcamento" enctype="multipart/form-data">

                <input type="hidden" id="orcamentoId" name="orcamentoId" value="<?=isset($orcamento->id) ? $orcamento->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="modalLabel"><?=$modalTitulo?></h3>
                </div>

                <div class="modal-body">
                
                    <div class="col-md-12 input-container form-group">
                        <label class="control-label" for="orcamento"><?=lang("titulo")?>:</label>
                        <input type="text" id="orcamento" name="orcamento" placeholder="Digite o título..." class="form-control" maxlength="100" required
                            value="<?=isset($orcamento->titulo_orcamento) ? $orcamento->titulo_orcamento : ''?>">
                    </div>

                    <div class="col-md-12 input-container form-group">
                        <label class="<?=isset($orcamento->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF):</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($orcamento->id) ? '' : 'required'?>>
                    </div>

                    <div class="col-md-12">
                        <hr style="margin: 5px 0;">
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: end;">
                        <button type="submit" class="btn btn-success" id="buttonSalvarOrcamento"><?=lang("salvar")?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_orcamento").modal({backdrop:'static'});

        // Salva Comunicado
        $("#form_orcamento").on("submit", function (evento)
        {
            evento.preventDefault();

            let formData = new FormData($("#form_orcamento")[0]);
            let url;

            if (!formData.get('orcamento').trim()) {
                showAlert('warning', 'Digite um título válido!');
                return false;
            }

            // Carregando
            $('#buttonSalvarOrcamento')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            if (formData.get("orcamentoId"))
                url = "<?=site_url('ProcessosAtendimento/Orcamento/editarOrcamento')?>/"
                    + formData.get("orcamentoId");
            else
                url = "<?=site_url('ProcessosAtendimento/Orcamento/adicionarOrcamento')?>";

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
                        $("#modal_orcamento").modal('hide');
                        // Recarrega a tabela
                        getOrcamentosAgGrid();
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
                    showAlert('error', lang.mensagem_erro);
                    $('#buttonSalvarOrcamento')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                complete: function ()
                {
                    // Carregado
                    $('#buttonSalvarOrcamento')
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