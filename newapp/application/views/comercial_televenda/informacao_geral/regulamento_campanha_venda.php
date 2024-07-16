<div id="modalRegulamentoCampanhaVenda" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="formRegulamentoCampanhaVenda" enctype="multipart/form-data">

                <input type="hidden" id="regulamentoCampanhaVendaId" name="regulamentoCampanhaVendaId" value="<?=isset($regulamentoCampanhaVenda->id) ? $regulamentoCampanhaVenda->id : ''?>">

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
                            value="<?=isset($regulamentoCampanhaVenda->descricao) ? $regulamentoCampanhaVenda->descricao : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="<?=isset($regulamentoCampanhaVenda->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF)</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($regulamentoCampanhaVenda->id) ? '' : 'required'?>>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSalvarRegulamento"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalRegulamentoCampanhaVenda").modal({backdrop:'static'});

        // Salva Comunicado
        $("#formRegulamentoCampanhaVenda").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#btnSalvarRegulamento')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formRegulamentoCampanhaVenda")[0]);
            let url;

            if (formData.get("regulamentoCampanhaVendaId"))
                url = "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/editarRegulamentoCampanhaVenda')?>/"
                    + formData.get("regulamentoCampanhaVendaId");
            else
                url = "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/adicionarRegulamentoCampanhaVenda')?>";

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

                        $("#modalRegulamentoCampanhaVenda").modal('hide');
                        tabelaRegulamentosCampanhaVendas.ajax.reload();
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
                    $('#btnSalvarRegulamento')
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