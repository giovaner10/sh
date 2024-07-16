

<div id="modal_noticia" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 60%;">
        <div class="modal-content">
            
            <form id="form_noticia" enctype="multipart/form-data">

                <input type="hidden" id="noticiaId" name="noticiaId" value="<?=isset($noticia->id) ? $noticia->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                
                    <div class="row" style="display: flex; align-items: center;">
                            
                        <div class="col-md-5">
                            
                            <label style="cursor: pointer;">
                                <div class="img-thumbnail">
                                    <img class="img-responsive" id="outputNoticias" src="<?=isset($noticia->conteudo_url)
                                        ? base_url("uploads/$noticia->imagem_diretorio/$noticia->imagem_nome")
                                        : base_url('media/img/imagem_vazia.png')?>"
                                    />
                                    <div class="caption">
                                        <input id="input-file" name="imagem" style="display: none;" type="file"
                                            accept="image/*" onchange="lerImagemNoticia(this)">
                                    </div>
                                </div>
                                <div style="text-align: center">
                                    <span class="label label-info"><?=lang('alt_img')?></span>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label" for="titulo"><?=lang("titulo")?></label>
                                <input type="text" id="titulo" name="titulo" class="form-control" required maxlength="100"
                                    value="<?=isset($noticia->titulo) ? $noticia->titulo : ''?>">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="descricao"><?=lang("descricao")?></label>
                                <textarea id="descricao" name="descricao" class="form-control" required
                                    ><?=isset($noticia->descricao) ? $noticia->descricao : ''?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="conteudo_url"><?=lang("url_de_direcionamento")?></label>
                                <input type="text" id="conteudo_url" name="conteudo_url" class="form-control" required maxlength="150"
                                    value="<?=isset($noticia->conteudo_url) ? $noticia->conteudo_url : ''?>">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                    <button type="submit" class="btn btn-primary" id="buttonSalvarNoticia"><?=lang("salvar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_noticia").modal({backdrop:'static'});

        // Salva Politica
        $("#form_noticia").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarNoticia')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_noticia")[0]);
            let url;

            if (formData.get("noticiaId"))
                url = "<?=site_url('Marketings/MeuOmnilink/editarNoticia')?>"
                    + "/" + formData.get("noticiaId");
            else
                url = "<?=site_url('Marketings/MeuOmnilink/adicionarNoticia')?>";

            $.ajax({
                url,
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
                        $("#modal_noticia").modal('hide');
                        // Recarrega a tabela
                        dt_noticias.ajax.reload();
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
                    $('#buttonSalvarNoticia')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

    function lerImagemNoticia(input)
    {
        // Instancia um objeto FileReader que permite aplicações web ler o conteúdo dos arquivos
        var reader = new FileReader();

        // Este evento é chamado cada vez que a operação de leitura é completada com sucesso.
        reader.onload = function(e) {
            // Seta a imagem no src da div a cima
            $('#outputNoticias').attr('src', e.target.result);
        };

        // Inicia a leitura do conteúdo que caira após operação completar na função acima
        if (input.files && input.files[0])
            reader.readAsDataURL(input.files[0]);
        else
            $('#outputNoticias').attr('src', "<?=base_url('media/img/imagem_vazia.png')?>");
    }

</script>