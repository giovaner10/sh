<div id="modalDicaVenda" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="formDicaVenda" enctype="multipart/form-data">

                <input type="hidden" id="dicaVendaId" name="dicaVendaId" value="<?=isset($dicaVenda->id) ? $dicaVenda->id : ''?>">

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
                            value="<?=isset($dicaVenda->descricao) ? $dicaVenda->descricao : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="<?=isset($dicaVenda->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF)</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($dicaVenda->id) ? '' : 'required'?>>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSalvarDicaVenda"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalDicaVenda").modal({backdrop:'static'});

        // Salva Comunicado
        $("#formDicaVenda").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#btnSalvarDicaVenda')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formDicaVenda")[0]);
            let url;

            if (formData.get("dicaVendaId"))
                url = "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/editarDicaVenda')?>/"
                    + formData.get("dicaVendaId");
            else
                url = "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/adicionarDicaVenda')?>";

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

                        $("#modalDicaVenda").modal('hide');
                        tabelaDicasVendas.ajax.reload();
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
                    $('#btnSalvarDicaVenda')
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