<div id="modal_retencao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-md" role="document">
        <div class="modal-content">

            <form id="form_retencao" enctype="multipart/form-data">

                <input type="hidden" id="retencaoId" name="retencaoId" value="<?= isset($retencao->id) ? $retencao->id : '' ?>">

                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="modalLabel"><?= $modalTitulo ?></h3>
                </div>

                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>

                            <div class="col-lg-12 input-container form-group">
                                <label for="retencao"><?= lang("titulo_retencao") ?>: <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Digite o título da release" id="retencao" name="retencao" class="form-control" maxlength="100" required value="<?= isset($retencao->titulo_retencao) ? $retencao->titulo_retencao : '' ?>">
                            </div>

                            <div class="col-lg-12 input-container form-group">
                                <label for="arquivo"><?= lang("arquivo") ?> (PDF): <span class="text-danger">*</span></label>
                                <input type="file" id="arquivo" name="arquivo" class="form-control" accept="application/pdf" <?= isset($retencao->id) ? '' : 'required' ?>>
                            </div>

                        </div>
                        <hr>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group" style="flex-direction: row-reverse">
                        <button type="submit" class="btn btn-success" id="buttonSalvarRetencao"><?= lang("salvar") ?></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#modal_retencao").modal({
            backdrop: 'static'
        });

        $("#form_retencao").on("submit", function(evento) {
            evento.preventDefault();

            $('#buttonSalvarRetencao')
                .html('<i class="fa fa-spin fa-spinner"></i> <?= lang('salvando') ?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_retencao")[0]);
            let url;

            if (formData.get("retencaoId"))
                url = "<?= site_url('ProcessosAtendimento/Retencao/editarRetencao') ?>/" +
                formData.get("retencaoId");
            else
                url = "<?= site_url('ProcessosAtendimento/Retencao/adicionarRetencao') ?>";

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function(retorno) {
                    if (retorno.status == 1) {
                        showAlert('success', retorno.mensagem)
                        $('#modal_retencao').modal('hide');
                        getDados();
                    } else {
                        showAlert('warning', retorno.mensagem)
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    showAlert('error', 'Erro na solicitação ao servidor.')
                },
                complete: function() {
                    $('#buttonSalvarRetencao')
                        .html('<?= lang('salvar') ?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>