<div id="modal_parceria_categoria" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_parceria_categoria">

                <input type="hidden" id="categoriaId" name="categoriaId" value="<?=isset($categoria->id) ? $categoria->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                    
                    <div class="form-group">
                        <label class="control-label" for="nome"><?=lang("nome")?></label>
                        <input type="text" id="nome" name="nome" class="form-control" required
                            value="<?=isset($categoria->nome) ? $categoria->nome : ''?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="ordem"><?=lang("ordem")?></label>
                        <input type="number" id="ordem" name="ordem" class="form-control" required
                            value="<?=isset($categoria->ordem) ? $categoria->ordem : ''?>" min="0">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarParceriaCategoria"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_parceria_categoria").modal({backdrop:'static'});

        // Trabalha com o overflow para 2 modais
        $('#modal_parceria_categoria').on('hidden.bs.modal', function(e){
            $("body").css('overflow-y', 'hidden');
            $("#modal_parcerias").css('overflow-y', 'auto');
        });

        // Salva Categoria
        $("#form_parceria_categoria").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarParceriaCategoria')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_parceria_categoria")[0]);
            let url;

            if (formData.get("categoriaId"))
                url = "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/editarParceriaCategoria')?>/"
                    + formData.get("categoriaId");
            else
                url = "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/adicionarParceriaCategoria')?>";

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
                        $("#modal_parceria_categoria").modal('hide');

                        // Recarrega a tabela
                        dt_parcerias_categorias.ajax.reload();

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
                    $('#buttonSalvarParceriaCategoria')
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