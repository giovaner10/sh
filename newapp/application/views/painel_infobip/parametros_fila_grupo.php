<div id="modalFilaGrupo" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="formFilaGrupo">

                <input type="hidden" id="filaGrupoId" name="filaGrupoId" value="<?=isset($grupo->id) ? $grupo->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                    
                    <div class="form-group">
                        <label class="control-label" for="grupoNome"><?=lang("nome")?></label>
                        <input type="text" id="grupoNome" name="grupoNome" class="form-control" required
                            value="<?=isset($grupo->nome) ? $grupo->nome : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="grupoFilasIds"><?=lang('filas_atendimentos')?></label>
                        <select name="grupoFilasIds[]" id="grupoFilasIds" multiple class="form-control" data-placeholder="<?=lang("validacao_filas")?>">

                            <?php foreach ($filas as $fila) : ?>
                                <option <?= isset($grupoFilasIds) && in_array($fila->id, $grupoFilasIds) ? 'selected' : ''?> value="<?=$fila->id?>"><?=$fila->name?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarFilaGrupo"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalFilaGrupo").modal({backdrop:'static'});

        // Trabalha com o overflow para 2 modais
        $('#modalFilaGrupo').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
            $("#modalParametros").css('overflow-y', 'auto');
        });
        
        // Multiselect
        $("#grupoFilasIds").multiselect({
            buttonWidth: '100%',
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: false,
            nSelectedText: ' <?=lang("selecionados")?>'.toLowerCase(),
            onChange: function(option, checked)
            {
                // Get selected options.
                var selectedOptions = $('#grupoFilasIds option:selected');

                if (selectedOptions.length >= 15) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#grupoFilasIds option').filter(function() {
                        return !$(this).is(':selected');
                    });
 
                    nonSelectedOptions.each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('.multiselect-option').addClass('disabled');
                    });
                }
            }
        });
        
        // Salva grupo de filas
        $("#formFilaGrupo").on("submit", function (evento)
        {
            evento.preventDefault();

            // Campo fila obrigatório
            if ($("#grupoFilasIds option:selected").length == 0)
            {
                // Aviso
                toastr.warning('<?=lang("validacao_filas")?>', '<?=lang("atencao")?>');
                return false;
            }

            // Carregando
            $('#buttonSalvarFilaGrupo')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formFilaGrupo")[0]);
            let url;

            if (formData.get("filaGrupoId"))
                url = "<?=site_url('PaineisInfobip/editarFilaGrupo')?>/"
                    + formData.get("filaGrupoId");
            else
                url = "<?=site_url('PaineisInfobip/adicionarFilaGrupo')?>";

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
                        $("#modalFilaGrupo").modal('hide');
                        // Recarrega a tabela
                        tabelaFilasGrupos.ajax.reload();
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
                    $('#buttonSalvarFilaGrupo')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                }
            });
        });

    });

</script>