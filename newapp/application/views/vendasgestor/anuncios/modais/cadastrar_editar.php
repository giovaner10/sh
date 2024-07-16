<div id="modalCadastrarEditarAnuncio" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="tituloModalCadastrarEditarAnuncio"><?=lang('cadastrar_anuncio')?></h4>
            </div>
            
            <form id="formularioCadastrarEditarAnuncio" action="" method="post">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required" for="titulo"><?=lang("titulo")?></label>
                                    <input 
                                        type="text" 
                                        id="titulo" 
                                        name="titulo" 
                                        class="form-control" 
                                        minlength="3"
                                        maxlength="60"
                                        placeholder="<?=lang('digite_o_titulo')?>"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required labelNotRequired" for="descricao"><?=lang("descricao")?></label>
                                    <textarea
                                        id="descricao" 
                                        name="descricao" 
                                        class="form-control inputNotRequired" 
                                        rows="3"
                                        cols="1"
                                        minlength="3"
                                        maxlength="240"
                                        placeholder="<?=lang('digite_a_descricao')?>"
                                        required 
                                    ></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required labelNotRequired" for="inicio"><?=lang("data_inicio")?></label>
                                    <input 
                                        type="datetime-local"
                                        id="dataInicio"
                                        name="dataInicio"
                                        class="form-control inputNotRequired"
                                        min="<?=date('Y-m-d H:i')?>"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required labelNotRequired" for="termino"><?=lang("data_fim")?></label>
                                    <input 
                                        type="datetime-local" 
                                        id="dataFim" 
                                        name="dataFim" 
                                        class="form-control inputNotRequired"
                                        min="<?=date('Y-m-d H:i')?>"
                                        max="9999-12-31 23:59:59"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required labelNotRequired" for="termino"><?=lang("produto")?></label>
                                    <select 
                                        name="idProduto" 
                                        id="selectProdutos" 
                                        data-placeholder="<?=lang('selecione_produto')?>" 
                                        class="form-control inputNotRequired"
                                        disabled
                                        required
                                    >
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required labelNotRequired" for="midia"><?=lang("midia")?></label>

                                <div style="text-align:center; border:1px solid #eee; padding: 10px; margin-left: 5px;">
                                    <div>
                                        <figure class="img-thumbnail divFile d-inline-block">
                                            <img 
                                                id="previa-midia-imagem"
                                                src="<?=base_url('media/img/120x150.png')?>"
                                                width="100%"
                                                height="200"
                                                class="previa-midia"
                                                style="object-fit: cover;"
                                            />
                                            <video
                                                id="previa-midia-video"
                                                src=""
                                                width="100%"
                                                height="250"
                                                class="hide previa-midia"
                                                style="object-fit: cover;"
                                                controls
                                            >
                                            </video>

                                        </figure>
                                    </div>

                                    <label for="midia-anuncio" class="btn btn-primary btn-sm" style="margin-top: 5px;"><?=lang('escolher_midia')?></label>
                                    <input 
                                        id="midia-anuncio" 
                                        name="midia" 
                                        class="inputfile" 
                                        type="file" 
                                        data-max-size="5242880"
                                        accept="video/mp4,image/jpeg,image/jpg,image/png"
                                    >
                                    <div style="margin-top: 10px;">
                                        <i class="fa fa-info-circle"></i>
                                        <span style="font-size: 12px;"> <?= lang('msg_formatos_suportados_imagem_video') ?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSalvarAnuncio"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>