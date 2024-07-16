<div id="modalVisualizarAnuncio" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=lang('visualizar_anuncio')?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?=lang("titulo")?>: </label>
                                <span id="vis_titulo"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?=lang("descricao")?>: </label>
                                <span id="vis_descricao"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?=lang("produto")?>: </label>
                                <span id="vis_produto"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?=lang("data_inicio")?>: </label>
                                <span id="vis_dataInicio"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?=lang("data_fim")?>: </label>
                                <span id="vis_dataFim"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?=lang("midia")?>: </label>

                            <div style="text-align:center; border:1px solid #eee; padding: 10px; margin-left: 5px;">
                                <div>
                                    <figure class="img-thumbnail divFile d-inline-block">
                                        <img 
                                            id="vis_previaMidiaImagem"
                                            src="<?=base_url('media/img/120x150.png')?>"
                                            width="100%"
                                            height="250"
                                            class="previa-midia"
                                            style="object-fit: cover;"
                                        />

                                        <video
                                            id="vis_previaMidiaVideo"
                                            width="100%"
                                            height="250"
                                            class="hide previa-midia"
                                            style="object-fit: cover;"
                                            controls
                                        >
                                        </video>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
            </div>
        </div>
    </div>
</div>