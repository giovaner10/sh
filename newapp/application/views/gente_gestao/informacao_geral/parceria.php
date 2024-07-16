<div id="modal_parceria" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_parceria" enctype="multipart/form-data">

                <input type="hidden" id="parceriaId" name="parceriaId" value="<?=isset($parceria->id) ? $parceria->id : ''?>">
                <input type="hidden" id="arquivoId" name="arquivoId" value="<?=isset($parceria->id_arquivo) ? $parceria->id_arquivo : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                    
                        <div class="form-group">
                            <label class="control-label" for="descricao"><?=lang("descricao")?></label>
                            <textarea maxlength="100" required name="descricao" id="descricao" rows="2"
                                class="form-control"><?=isset($parceria->descricao) ? $parceria->descricao : ''?></textarea>
                            <span id="content-countdown" style="color:black; float:right">0/100</span>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="tipo"><?=lang("categoria")?></label>
                            <select name="categoriaId" id="categoriaId" class="form-control" required>
                                <option value=""><?=lang("selecione")?></option>

                                <?php foreach ($parceriasCategorias as $categoria) :?>
                                    <option value="<?=$categoria->id?>" <?=isset($parceria->id) && $parceria->id_categoria == $categoria->id ? "selected" : ""?>>
                                        <?=$categoria->nome?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="link"><?=lang("link")?></label>
                            <input type="text" id="link" name="link" class="form-control" required
                                value="<?=isset($parceria->link) ? $parceria->link : ''?>">
                        </div>

                        <div class="form-group" id="div_foto_capa">
                            <label class="<?=isset($parceria->id) ? '' : 'control-label' ?>" for="foto_capa"><?=lang("foto_capa")?></label>
                            <input type="file" id="foto_capa" name="foto_capa" class="form-control"
                                accept="image/jpg, image/png, image/jpeg" <?=isset($parceria->id) ? '' : 'required'?>>
                        </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarParceria"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_parceria").modal({backdrop:'static'});

        // Trabalha com o overflow para 2 modais
        $('#modal_parceria').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
            $("#modal_parcerias").css('overflow-y', 'auto');
        });

        // Salva Treinamento EAD
        $("#form_parceria").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarParceria')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_parceria")[0]);
            let url;

            if (formData.get("parceriaId"))
                url = "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/editarParceria')?>/"
                    + formData.get("parceriaId");
            else
                url = "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/adicionarParceria')?>";

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
                        $("#modal_parceria").modal('hide');
                        // Recarrega a tabela
                        dt_parcerias.ajax.reload();
                        // Recarrega a listagem da tela principal
                        atualizarListagemParcerias();
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
                    $('#buttonSalvarParceria')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


        // Faz o count de string do campo de descrição de feriado
        const maxtext = 100;
        $("#content-countdown").html($("#descricao").val().length+'/'+maxtext);

        $("#descricao").keyup(function (e)
        {
            var target = $("#content-countdown");
            var len = this.value.length;

            if (len < maxtext)
            {
                target.html(len+'/'+maxtext);
                target.css("color", "black");
            }
            else if (len == maxtext)
            {
                target.html(maxtext+'/'+maxtext);
                target.css("color", "red");
                e.preventDefault();
            }
            else if (this.value.length > maxtext)
            {
                this.value = this.value.substring(0, maxtext);
            }
        });

    });

</script>