<div id="modalProposta" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="formProposta" enctype="multipart/form-data">

                <input type="hidden" id="propostaId" name="propostaId" value="<?=isset($proposta->id) ? $proposta->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                
                    <div class="form-group">
                        <label class="control-label" for="descricao"><?=lang("descricao")?></label>
                        <input type="text" id="descricao" name="descricao" class="form-control" required
                            value="<?=isset($proposta->descricao) ? $proposta->descricao : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="<?=isset($proposta->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?></label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept=".doc,.docx" <?=isset($proposta->id) ? '' : 'required'?>>
                        <span class="label label-info"><?=lang('formatos_permitidos').': '.$extensoesPermitidas?></span>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSalvarProposta"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalProposta").modal({backdrop:'static'});

        // Salva a Apresentação
        $("#formProposta").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#btnSalvarProposta')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formProposta")[0]);
            let url;

            if (formData.get("propostaId"))
                url = "<?=site_url('ComerciaisTelevendas/Propostas/editar')?>/"
                    + formData.get("propostaId");
            else
                url = "<?=site_url('ComerciaisTelevendas/Propostas/adicionar')?>";

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                        $("#modalProposta").modal('hide');
                        tabelaPropostas.ajax.reload();
                    }
                    else
                    {
                        toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
                },
                complete: function ()
                {
                    // Carregado
                    $('#btnSalvarProposta')
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