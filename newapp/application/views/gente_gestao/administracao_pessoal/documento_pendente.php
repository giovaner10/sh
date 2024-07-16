<div id="modalDocumentoPendente" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="formDocumentoPendente" enctype="multipart/form-data">

                <input type="hidden" id="documentoPendenteId" name="documentoPendenteId" value="<?=isset($documentoPendente->id) ? $documentoPendente->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>  

                <div class="modal-body">
                    
                    <div class="form-group">
                        <label class="control-label" for="funcionario_id"><?=lang("funcionario")?></label>
                        <select name="funcionario_id" id="funcionario_id" class="form-control" required>
                            <option value=""><?=lang("selecione")?></option>

                            <?php foreach ($funcionarios as $funcionario) :?>
                                <option value="<?=$funcionario->id?>"
                                        <?=isset($documentoPendente->id_usuario)
                                            && $documentoPendente->id_usuario == $funcionario->id
                                        ? "selected" : ""?>
                                >
                                    <?=$funcionario->nome?>
                                </option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="documentos"><?=lang("documentos")?></label>
                        <select name="documentos[]" id="documentos" class="form-control" multiple data-placeholder="<?=lang("selecione")?>">

                            <option value="residencia" <?=isset($documentoPendente) && $documentoPendente->residencia == 'sim' ? 'selected' : ''?>>
                                <?=lang("comprovante_residencia");?>
                            </option>
                            <option value="cpf" <?=isset($documentoPendente) && $documentoPendente->cpf == 'sim' ? 'selected' : ''?>>
                                CPF
                            </option>
                            <option value="rg" <?=isset($documentoPendente) && $documentoPendente->rg == 'sim' ? 'selected' : ''?>>
                                RG
                            </option>
                            <option value="banco" <?=isset($documentoPendente) && $documentoPendente->banco == 'sim' ? 'selected' : ''?>>
                                <?=lang("comprovante_dados_bancarios");?>
                            </option>

                        </select>
                    </div>
                
                    <div class="form-group">
                        <label class="control-label" for="prazo_entrega"><?=lang("prazo_entrega")?></label>
                        <input type="date" min="<?=date('Y-m-d');?>" id="prazo_entrega" name="prazo_entrega" class="form-control" required
                            value="<?=isset($documentoPendente->prazo_maximo) ? $documentoPendente->prazo_maximo : ''?>">
                    </div>

                    
                    <?php if (isset($documentoPendente->id)) : ?>
                        
                        <div class="form-group">
                            <label for="recebido"><?=lang("recebi_todos_documentos")?>:</label>&nbsp;
                            <input type="checkbox" min="<?=date('Y-m-d');?>" id="recebido" name="recebido"
                                style="top: 2px; position: relative;"
                                value="<?=isset($documentoPendente->recebido) == 'sim' ? 'sim' : 'nao'?>">
                        </div>

                    <?php endif; ?>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonDocumentoPendente"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#documentos").chosen();

        $("#modalDocumentoPendente").modal({backdrop:'static'});

        // Trabalha com o overflow para 2 modais
        $('#modalDocumentoPendente').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
            $("#modal_treinamentos_ead").css('overflow-y', 'auto');
        });

        // Salva Documento Pendente
        $("#formDocumentoPendente").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonDocumentoPendente')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#formDocumentoPendente")[0]);
            let url;

            if (formData.get("documentoPendenteId"))
                url = "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesEditar')?>/"
                    + formData.get("documentoPendenteId");
            else
                url = "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesAdicionar')?>";

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
                        $("#modalDocumentoPendente").modal('hide');
                        // Recarrega a tabela
                        dtDocumentosPendentes.ajax.reload();
                        // Recarrega a listagem da tela principal
                        documentosPendentesAtualizarListagem();
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
                    $('#buttonDocumentoPendente')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                }
            });
        });
    });

</script>