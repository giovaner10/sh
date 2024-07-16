<div id="modal_treinamento" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 70%;">
        <div class="modal-content">
            
            <form id="form_treinamento" enctype="multipart/form-data">

                <input type="hidden" id="treinamentoId" name="treinamentoId" value="<?=isset($treinamento->id) ? $treinamento->id : ''?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
                </div>

                <div class="modal-body">
                    
                    <?php if ($this->auth->is_allowed_block('cad_atividades')) : ?>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                
                                <label class="control-label" for="funcionario"><?=lang("funcionario")?></label>
                                <select name="funcionario" id="funcionario" class="form-control">
                                    <option value=""><?=lang("selecione")?></option>

                                    <?php foreach ($funcionarios as $funcionario) :?>
                                        <option value="<?=$funcionario->id?>"
                                                <?=isset($treinamento->id_usuario)
                                                    && $treinamento->id_usuario == $funcionario->id
                                                ? "selected" : ""?>
                                        >
                                            <?=$funcionario->nome?>
                                        </option>
                                    <?php endforeach;?>
                                </select>

                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="descricao"><?=lang("descricao")?></label>
                                <input type="text" id="descricao" name="descricao" class="form-control" required
                                    value="<?=isset($treinamento->curso) ? $treinamento->curso : ''?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="tipo"><?=lang("tipo")?></label>
                                <select name="tipo" id="tipo" class="form-control" required>
                                    <option value=""><?=lang("selecione")?></option>

                                    <option value="presencial" <?=isset($treinamento->tipo) && $treinamento->tipo == "presencial" ? "selected" : ""?>>
                                        <?=lang("presencial")?>
                                    </option>
                                    <option value="webcurso" <?=isset($treinamento->tipo) && $treinamento->tipo == "webcurso" ? "selected" : ""?>>
                                        <?=lang("webcurso")?>
                                    </option>
                                    <option value="avaliacao" <?=isset($treinamento->tipo) && $treinamento->tipo == "avaliacao" ? "selected" : ""?>>
                                        <?=lang("avaliacao")?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inicio"><?=lang("inicio")?></label>
                                <input type="date" id="inicio" name="inicio" class="form-control" required max="9999-12-31"
                                    value="<?=isset($treinamento->data_inicio) ? $treinamento->data_inicio : ''?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="termino"><?=lang("termino")?></label>
                                <input type="date" id="termino" name="termino" class="form-control" required max="9999-12-31"
                                    value="<?=isset($treinamento->data_fim) ? $treinamento->data_fim : ''?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="carga_hr"><?=lang("carga_hr")?></label>
                                <input type="number" id="carga_hr" name="carga_hr" class="form-control" required min="0"
                                    value="<?=isset($treinamento->carga_hr) ? $treinamento->carga_hr : ''?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="status"><?=lang("status")?></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value=""><?=lang("selecione")?></option>

                                    <option value="cursando" <?=isset($treinamento->status) && $treinamento->status == "cursando" ? "selected" : ""?>>
                                        <?=lang("cursando")?>
                                    </option>
                                    <option value="aprovado" <?=isset($treinamento->status) && $treinamento->status == "aprovado" ? "selected" : ""?>>
                                        <?=lang("aprovado")?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarTreinamento"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_treinamento").modal({backdrop:'static'});

        // Salva Treinamento
        $("#form_treinamento").on("submit", function (evento)
        {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarTreinamento')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_treinamento")[0]);
            let url;

            if (formData.get("treinamentoId"))
                url = "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/editarTreinamento')?>/"
                    + formData.get("treinamentoId");
            else
                url = "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/adicionarTreinamento')?>";

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
                        $("#modal_treinamento").modal('hide');
                        // Recarrega a tabela
                        dt_treinamentos.ajax.reload();
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
                    $('#buttonSalvarTreinamento')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        // Matem as datas válidas em conjunto início e fim
        $("#inicio").on("change", function()
        {
            $("#termino").prop("min", $("#inicio").val());
        });

        $("#termino").on("change", function()
        {
            $("#inicio").prop("max", $("#termino").val());
        });
    });

</script>