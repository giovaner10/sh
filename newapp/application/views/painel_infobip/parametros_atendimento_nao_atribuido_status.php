<div id="modalAtendimentoNaoAtribuidoStatus" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 40%;">
        <div class="modal-content">
            
            <form id="formAtendimentoNaoAtribuidoStatus">

                <input type="hidden" id="statusId" name="statusId" value="<?=$status->id?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel">
                        <?=$modalTitulo?>
                        <span class="icone-aten-nao-atrib-status background-color-<?=$status->icone?>"
                            style="margin-left: 5px; margin-top: 3px;"></span>
                    </h4>
                </div>

                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label" for="tempo_inicial_minutos"><?=lang("de")?></label>
                            <input type="number" id="tempo_inicial_minutos" name="tempo_inicial_minutos" class="form-control"
                                value="<?=$status->tempo_inicial_minutos?>" <?=$status->id == 1 ? 'disabled' : ''?>
                                min="<?=$status->id == 2 ? '1' : '2'?>">
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label" for="tempo_final_minutos"><?=lang("ate")?></label>
                            
                            <?php if ($status->id == 3) : ?>
                                <input type="text" id="tempo_final_minutos" name="tempo_final_minutos" class="form-control"
                                    value="*" disabled>
                            <?php else :?>
                                <input type="number" id="tempo_final_minutos" name="tempo_final_minutos" class="form-control"
                                    value="<?=$status->tempo_final_minutos?>">
                            <?php endif;?>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonAtendimentoNaoAtribuidoStatus"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalAtendimentoNaoAtribuidoStatus").modal({backdrop:'static'});

        // Trabalha com o overflow para 2 modais
        $('#modalAtendimentoNaoAtribuidoStatus').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
            $("#modalParametros").css('overflow-y', 'auto');
        });

        // Salva Treinamento
        $("#formAtendimentoNaoAtribuidoStatus").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonAtendimentoNaoAtribuidoStatus')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formAtendimentoNaoAtribuidoStatus")[0]);
            let url = "<?=site_url('PaineisInfobip/atendimentoNaoAtribuidoStatusEditar')?>/" + formData.get("statusId");

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
                        $("#modalAtendimentoNaoAtribuidoStatus").modal('hide');
                        // Recarrega a tabela
                        dataAtendimentosNaoAtribuidosStatus.ajax.reload();
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
                    $('#buttonAtendimentoNaoAtribuidoStatus')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                }
            });
        });

        // Inicializa os tempos minimos
        $("#tempo_inicial_minutos").prop("max", parseInt($("#tempo_final_minutos").val()) -1);
        $("#tempo_final_minutos").prop("min", parseInt($("#tempo_inicial_minutos").val()) +1);


        // Matem as datas válidas em conjunto início e fim
        $("#tempo_inicial_minutos").on("change", function()
        {
            $("#tempo_final_minutos").prop("min", parseInt($("#tempo_inicial_minutos").val()) +1);
        });

        $("#tempo_final_minutos").on("change", function()
        {
            $("#tempo_inicial_minutos").prop("max", parseInt($("#tempo_final_minutos").val()) -1);
        });
    });
    
</script>