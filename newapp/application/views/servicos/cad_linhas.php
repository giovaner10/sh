<?php if ($msg != '') : ?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>CONCLUIDO!</strong>
        <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Cadastro Linhas - Mikrotik", site_url('Homes'), lang('cadastros'), "Linhas" . " > Mikrotik");
?>

<div class="row" style="margin: 15px;">
    <div class="col-md-12">
        <div class="card-conteudo" style="text-align: center;">
            <?php echo form_open_multipart('linhas/enviar_linhas', array('id' => 'form-linhas'), array('path' => 'uploads/linhas')); ?>
            <div class="col-md-12" style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                <div class="container" style="margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center; background-color: #1c69ad; padding: 20px; border-radius: 50%; width: 100px; height: 100px;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="72" width="72" viewBox="0 0 512 512">
                        <path fill="#FFFFFF" d="M288 109.3V352c0 17.7-14.3 32-32 32s-32-14.3-32-32V109.3l-73.4 73.4c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l128-128c12.5-12.5 32.8-12.5 45.3 0l128 128c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L288 109.3zM64 352H192c0 35.3 28.7 64 64 64s64-28.7 64-64H448c35.3 0 64 28.7 64 64v32c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V416c0-35.3 28.7-64 64-64zM432 456a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"/>
                    </svg>
                </div>
                <div class="form-group" style="display: flex; justify-content: center; gap: 10px; width: 40%;">
                    <input class="form-control input-sm" name="upload" id="upload" type="file">
                    <i class="fa fa-info-circle" style="font-size: 18px; align-self: center;" id="info-icon" aria-hidden="true" title="Clique para saber mais"></i>
                </div>
            </div>
            <?php echo form_close(); ?>
            <div class="clearfix"></div>
            <div id="bar" class="progress progress-striped active" style="margin-top: 20px;">
                <div class="bar upload"></div>
            </div>
            <div class="result_upload" style="margin-top: 20px;"></div>
            <div id="conteudo" style="margin-top: 20px;"></div>
        </div>
    </div>
</div>

<!-- MODAL MODELO DOCUMENTO ITENS DE MOVIMENTO -->
<div id="modalModeloItens" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="header-modal">Modelo de documento <span id="tituloDetalhesDoContrato"></span></h4>
            </div>
            <div class="modal-body scrollModal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content" style="padding: 0px 20px">
                            <div id="div_identificacao">
                                <div class="row">
                                    <div class="col-md-12" style="border-left: 3px solid #03A9F4; padding-bottom: 0px; margin-right: 0px">
                                        <p class="text-justify">
                                            O documento deve seguir a seguinte estrutura:
                                            <li>Estrutura do arquivo: <strong>linha;ccid;operadora</strong>;custo;MB;ID equipamento</li>
                                            <li>Campos em negrito obrigatórios.</li>
                                        </p>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <img src="<?= versionFile('arq/iscas', 'modelo_planilha_iscas.png') ?>" alt="" class="img-responsive center-block" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $("#info-icon").click(function(e) {
            $("#modalModeloItens").modal("show");
        });

        $('#upload').on('change', function() {
            const fileName = $(this).val();
            const fileExtension = fileName.split('.').pop().toLowerCase();

            if (!fileName) {
                showAlert("warning", 'Você não selecionou um arquivo para enviar.');
                
                return;
            }
            if (fileExtension !== 'csv') {
                showAlert("warning", 'Permitido apenas arquivos .CSV!');
                return;
            }

            $('#form-linhas').ajaxSubmit({
                target: '.result_upload',
                resetForm: true,
                uploadProgress: function(event, position, total, percentComplete) {
                    $('.upload').css('width', `${percentComplete - 10}%`);
                    $('.result_upload').text();
                    showAlert("success", 'Enviando Arquivo. Por favor aguarde...');
                },
                success: function() {
                    showAlert("success", 'Processando arquivo, por favor aguarde...');
                },
                complete: function(xhr) {
                    $('.upload').css('width', '100%');
                    const response = xhr.responseText;
                    try {
                        const jsonResponse = JSON.parse(response);
                        if (jsonResponse.success) {
                            loadContent();
                        } else {
                            showAlert("error", jsonResponse.message);
                        }
                    } catch (e) {
                        showAlert("warning", response);
                    }
                }
            });
        });

        function loadContent() {
            const url = '<?php echo site_url('linhas/ajax_cad_linhas') ?>';
            $('#conteudo').load(url, function() {
                $('.result_upload').html('');
                $('.upload').css('width', '0%');
            });
        }

    });
</script>

<!-- Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

<!-- Style -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/instaladores', 'layout.css') ?>">
