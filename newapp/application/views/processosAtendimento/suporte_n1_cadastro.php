<div id="modal_suporte" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="form_suporte" enctype="multipart/form-data">

                <input type="hidden" id="suporteId" name="suporteId" value="<?= isset($suporte->id) ? $suporte->id : '' ?>">

                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="modalLabel"><?= $modalTitulo ?></h3>
                </div>

                <div class="modal-body">
                    <div class="col-md-12">

                        <div class='row'>
                            <div class="col-lg-12 input-container form-group">
                                <label class="control-label" for="suporte"><?= lang("titulo_suporte_n1") ?>:</label>
                                <input type="text" placeholder="Digite o suporte" id="suporte" name="suporte" class="form-control" minlength="3" maxlength="100" required value="<?= isset($suporte->titulo_suporte) ? $suporte->titulo_suporte : '' ?>">
                            </div>

                            <div class="col-lg-12 input-container form-group">
                                <label class="<?= isset($suporte->id) ? '' : 'control-label' ?>" for="arquivo"><?= lang("arquivo") ?> (PDF):</label>
                                <input type="file" id="arquivo" name="arquivo" class="form-control" accept="application/pdf" <?= isset($suporte->id) ? '' : 'required' ?>>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group" style="flex-direction: row-reverse">
                        <button type="submit" class="btn btn-success" id="buttonSalvarSuporte"><?= lang("salvar") ?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#modal_suporte").modal({
            backdrop: 'static'
        });

        $("#form_suporte").on("submit", function(evento) {
            evento.preventDefault();

            $('#buttonSalvarSuporte').html('<i class="fa fa-spin fa-spinner"></i> <?= lang('salvando') ?>').attr('disabled', true);

            let formData = new FormData($("#form_suporte")[0]);
            let url;

            if (formData.get("suporteId"))
                url = "<?= site_url('ProcessosAtendimento/SuporteN1/editarSuporte') ?>/" +
                formData.get("suporteId");
            else
                url = "<?= site_url('ProcessosAtendimento/SuporteN1/adicionarSuporte') ?>";

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function(retorno) {
                    if (retorno.status == 1) {
                        showAlert('success', retorno.mensagem);

                        $("#modal_suporte").modal('hide');

                        getDados();
                    } else {
                        showAlert('warning', retorno.mensagem);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    showAlert('error', 'Erro na solicitação ao servidor.');
                },
                complete: function() {
                    $('#buttonSalvarSuporte').html('<?= lang('salvar') ?>').attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>