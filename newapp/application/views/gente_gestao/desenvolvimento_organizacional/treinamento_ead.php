<div id="modal_treinamento_ead" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_treinamento_ead" enctype="multipart/form-data">

                <input type="hidden" id="treinamentoEadId" name="treinamentoEadId" value="<?=isset($treinamentoEad->id) ? $treinamentoEad->id : ''?>">
                <input type="hidden" id="path" name="path" value="<?=isset($treinamentoEad->path) ? $treinamentoEad->path : ''?>">

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
                            value="<?=isset($treinamentoEad->descricao) ? $treinamentoEad->descricao : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="tipo"><?=lang("tipo")?></label>
                        <select name="tipo" id="tipo" class="form-control" onchange="selectTipo(this)" required>
                            <option value=""><?=lang("selecione_um_tipo")?></option>

                            <option value="online" <?=isset($treinamentoEad->tipo) && $treinamentoEad->tipo == "online" ? "selected" : ""?>>
                                <?=lang("treinamentos_online")?>
                            </option>
                            <option value="videos" <?=isset($treinamentoEad->tipo) && $treinamentoEad->tipo == "videos" ? "selected" : ""?>>
                                <?=lang("videoaulas")?>
                            </option>
                            <option value="sites" <?=isset($treinamentoEad->tipo) && $treinamentoEad->tipo == "sites" ? "selected" : ""?>>
                                <?=lang("sites")?>
                            </option>
                            
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="link"><?=lang("link")?></label>
                        <input type="text" id="link" name="link" class="form-control" required
                            value="<?=isset($treinamentoEad->link) ? $treinamentoEad->link : ''?>">
                    </div>

                    <div class="form-group" id="div_foto_capa">
                        <label class="<?=isset($treinamentoEad->id) ? '' : 'control-label' ?>" for="foto_capa"><?=lang("foto_capa")?></label>
                        <input type="file" id="foto_capa" name="foto_capa" class="form-control"
                            accept="image/jpg, image/png, image/jpeg" <?=isset($treinamentoEad->id) ? '' : 'required'?>>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarTreinamentoEad"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_treinamento_ead").modal({backdrop:'static'});

        // Trabalha com o overflow para 2 modais
        $('#modal_treinamento_ead').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
            $("#modal_treinamentos_ead").css('overflow-y', 'auto');
        });

        // Inicializa o select tipo
        selectTipo($("#tipo"));

        // Salva Treinamento EAD
        $("#form_treinamento_ead").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarTreinamentoEad')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_treinamento_ead")[0]);
            let url;

            if (formData.get("treinamentoEadId"))
                url = "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/editarTreinamentoEad')?>/"
                    + formData.get("treinamentoEadId");
            else
                url = "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/adicionarTreinamentoEad')?>";

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
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                        // Fecha modal
                        $("#modal_treinamento_ead").modal('hide');
                        // Recarrega a tabela
                        dt_treinamentos_ead.ajax.reload();
                        // Recarrega a listagem da tela principal
                        atualizarListagemTreinamentosEad();
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
                    $('#buttonSalvarTreinamentoEad')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

    function selectTipo(campoTipo)
    {
        if ($(campoTipo).val() == "sites")
        {
            $("#div_foto_capa").hide();
            $("#foto_capa").prop("required", false);
        }
        else
        {
            $("#div_foto_capa").show();
            $("#foto_capa").prop("required", false);
        }
    }

</script>