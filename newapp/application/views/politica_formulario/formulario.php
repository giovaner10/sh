<div id="modal_formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <form id="form_formulario" enctype="multipart/form-data">

                <input type="hidden" id="formularioId" name="formularioId" value="<?= isset($formulario->id) ? $formulario->id : '' ?>">
                <input type="hidden" id="tipo" name="tipo" value="F"> <!-- Formulario -->

                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="modalLabel"><?= $modalTitulo ?></h3>
                </div>

                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>

                            <div class="col-lg-12 input-container form-group" style="height: 59px !important;">
                                <label class="control-label" for="descricao"><?= lang("descricao") ?></label>
                                <input type="text" id="descricao" name="descricao" class="form-control" required value="<?= isset($formulario->descricao) ? $formulario->descricao : '' ?>">
                            </div>

                            <div class="col-lg-12 input-container form-group" style="height: 59px !important;">
                                <label class="<?= isset($formulario->id) ? '' : 'control-label' ?>" for="arquivo"><?= lang("arquivo") ?></label>
                                <input type="file" id="arquivo" name="arquivo" class="form-control" <?= isset($formulario->id) ? '' : 'required' ?>>
                            </div>

                            <div class="col-lg-12 input-container form-group">
                                <span class="label label-info"><?= lang('formatos_permitidos') . ': ' . $extensoesPermitidas ?></span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="footer-group" style="flex-direction: row-reverse">
                        <button type="submit" class="btn btn-success" id="buttonSalvarFormulario"><?= lang("salvar") ?></button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
       
        $("#modal_formulario").modal();

        // Salva Formulario
        $("#form_formulario").on("submit", function(evento) {
            evento.preventDefault();

            // Carregando
            $('#buttonSalvarFormulario')
                .html('<i class="fa fa-spin fa-spinner"></i> <?= lang('salvando') ?>')
                .attr('disabled', true);

            let formData = new FormData($("#form_formulario")[0]);
            let departamentoId = $("#departamentoId").val();
            let url;

            if (formData.get("formularioId"))
                url = "<?= site_url('PoliticasFormularios/editarPoliticaFormulario') ?>" +
                "/" + formData.get("formularioId") +
                "/" + departamentoId;
            else
                url = "<?= site_url('PoliticasFormularios/adicionarPoliticaFormulario') ?>" +
                "/" + departamentoId;

            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function(retorno) {
                    if (retorno.status == 1) {
                        // Mensagem de retorno
                        showAlert('success', retorno.mensagem)

                        // Fecha modal
                        $("#modal_formulario").modal('hide');
                        // Recarrega a tabela
                        getDados();
                    } else {
                        // Mensagem de retorno
                        showAlert('warning', retorno.mensagem)

                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Mensagem de erro
                    showAlert('error', "Erro na solicitação ao servidor")
                },
                complete: function() {
                    // Carregado
                    $('#buttonSalvarFormulario')
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