<div id="modalAtendimentoTempoMedio" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 40%;">
        <div class="modal-content">
            
            <form id="formAtendimentoTempoMedio">

                <input type="hidden" id="canalTipoId" name="canalTipoId" value="<?=$canalTipo->id?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                    
                    <div class="form-group">
                        <label class="control-label" for="nome"><?=lang("nome")?></label>
                        <input type="text" id="nome" name="nome" class="form-control" disabled
                            value="<?=$canalTipo->nome?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="tempoMedio"><?=lang("tempo_medio")?></label>
                        <input type="time" id="tempoMedio" name="tempoMedio" class="form-control" required
                            value="<?=$canalTipo->tempo_medio_atendimento?>">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarAtendimentoTempoMedio"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalAtendimentoTempoMedio").modal({backdrop:'static'});

        // Trabalha com o overflow para 2 modais
        $('#modalAtendimentoTempoMedio').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
            $("#modalParametros").css('overflow-y', 'auto');
        });

        // Salva Treinamento
        $("#formAtendimentoTempoMedio").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarAtendimentoTempoMedio')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formAtendimentoTempoMedio")[0]);
            let url = "<?=site_url('PaineisInfobip/editarAtendimentoTempoMedio')?>/" + formData.get("canalTipoId");

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        // Mensagem de retorno
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                        // Fecha modal
                        $("#modalAtendimentoTempoMedio").modal('hide');
                        // Recarrega a tabela
                        tabelaAtendimentosTempoMedio.ajax.reload();
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
                    $('#buttonSalvarAtendimentoTempoMedio')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                }
            });
        });

    });

</script>