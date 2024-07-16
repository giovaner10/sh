<div id="modalApresentacao" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="formApresentacao" enctype="multipart/form-data">

                <input type="hidden" id="apresentacaoId" name="apresentacaoId" value="<?=isset($apresentacao->id) ? $apresentacao->id : ''?>">

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
                            value="<?=isset($apresentacao->descricao) ? $apresentacao->descricao : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="<?=isset($apresentacao->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?></label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf,.ppt,.pptx" <?=isset($apresentacao->id) ? '' : 'required'?>>
                        <span class="label label-info"><?=lang('formatos_permitidos').': '.$extensoesPermitidas?></span>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSalvarApresentacao"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalApresentacao").modal({backdrop:'static'});

        // Salva a Apresentação
        $("#formApresentacao").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#btnSalvarApresentacao')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formApresentacao")[0]);
            let url;

            if (formData.get("apresentacaoId"))
                url = "<?=site_url('ComerciaisTelevendas/Apresentacoes/editar')?>/"
                    + formData.get("apresentacaoId");
            else
                url = "<?=site_url('ComerciaisTelevendas/Apresentacoes/adicionar')?>";

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

                        $("#modalApresentacao").modal('hide');
                        tabelaApresentacoes.ajax.reload();
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
                    $('#btnSalvarApresentacao')
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