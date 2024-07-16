

<div id="modal_banner" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 60%;">
        <div class="modal-content">
            
            <form id="form_banner" enctype="multipart/form-data">

                <input type="hidden" id="bannerId" name="bannerId" value="<?=isset($banner->id) ? $banner->id : ''?>">

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
                                    <img class="img-responsive" id="outputBanners" src="<?=isset($banner->conteudo_url)
                                        ? base_url("uploads/$banner->imagem_diretorio/$banner->imagem_nome")
                                        : base_url('media/img/imagem_vazia.png')?>"
                                    />
                                    <div class="caption">
                                        <input id="input-file" name="imagem" style="display: none;" type="file"
                                            accept="image/*" onchange="lerImagemBanner(this)">
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
                                    value="<?=isset($banner->titulo) ? $banner->titulo : ''?>">
                            </div>

                            <div class="form-group">
                                <label for="conteudo_url"><?=lang("url_de_direcionamento")?></label>
                                <input type="text" id="conteudo_url" name="conteudo_url" class="form-control" maxlength="150"
                                    value="<?=isset($banner->conteudo_url) ? $banner->conteudo_url : ''?>">
                            </div>

                            <div class="form-group">
                                <label for="ordem"><?=lang("ordem_de_exibicao")?></label>
                                <input type="number" id="ordem" name="ordem" class="form-control"
                                    value="<?=isset($banner->ordem) ? $banner->ordem : ''?>" min="1">
                            </div>

                            <div class="form-group">
                                <label class="checkbox-inline" for="exibe_na_home">
                                    <input type="checkbox" id="exibe_na_home" name="exibe_na_home" value="1"
                                        <?=isset($banner->exibe_na_home) && $banner->exibe_na_home === 'nao' ? '' : 'checked'?>>
                                    <?=lang("exibir_na_tela_inicial")?></label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                    <button type="submit" class="btn btn-primary" id="buttonSalvarBanner"><?=lang("salvar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_banner").modal({backdrop:'static'});

        // Salva Politica
        $("#form_banner").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarBanner')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_banner")[0]);
            let url;

            if (formData.get("bannerId"))
                url = "<?=site_url('Marketings/MeuOmnilink/editarBanner')?>"
                    + "/" + formData.get("bannerId");
            else
                url = "<?=site_url('Marketings/MeuOmnilink/adicionarBanner')?>";

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
                        $("#modal_banner").modal('hide');
                        // Recarrega a tabela
                        dt_banners.ajax.reload();
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
                    $('#buttonSalvarBanner')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

    function lerImagemBanner(input)
    {
        // Instancia um objeto FileReader que permite aplicações web ler o conteúdo dos arquivos
        var reader = new FileReader();

        // Este evento é chamado cada vez que a operação de leitura é completada com sucesso.
        reader.onload = function(e) {
            // Seta a imagem no src da div a cima
            $('#outputBanners').attr('src', e.target.result);
        };

        // Inicia a leitura do conteúdo que caira após operação completar na função acima
        if (input.files && input.files[0])
            reader.readAsDataURL(input.files[0]);
        else
            $('#outputBanners').attr('src', "<?=base_url('media/img/imagem_vazia.png')?>");
    }

</script>