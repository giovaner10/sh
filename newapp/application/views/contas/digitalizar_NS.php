<div class="modal-content">
    <div class="modal-header header-layout">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Digitalizar Conta</h3>
    </div>
    <form action="<?= site_url('contas/add_categoria') ?>" method="POST">
        <div class="modal-body">
            <div class="row">
                <div class="resultado col-md-11" style="float: none; margin-left: auto; margin-right: auto;"></div>
                <div class="resultado2 col-md-11" style="float: none; margin-left: auto; margin-right: auto;"></div>

                <div class="formulario">
                    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('contas/digitalizacao/' . $id_conta) ?>">
                        <div class="col-md-11" style="float: none; margin-left: auto; margin-right: auto;">
                            <div>
                                Descrição:
                            </div>
                            <input style="margin: 5px 0 5px 0;" type="text" id="descricaoArquivo" name="descricao" placeholder="Descrição" class="input form-control" />

                            <label for="comprovante" style="cursor: pointer;">
                                <input type="checkbox" value="1" id="comprovante" name="comprovante" />&nbsp;Comprovante de pagamento
                            </label>

                            <!-- <div class="col-md-12" style="float: none; margin-left: auto; margin-right: auto;" >
                                <input type="file" name="arquivo" class="filestyle" data-buttonText="Arquivo">
                            </div> 
                             -->
                            <div class="row" style="display: flex; align-items: flex-end;">
                                <div class="col-md-9">
                                    <label for="filesForm">Arquivo (PDF): <span class="text-danger">*</span></label>
                                    <input class="form-control" id="filesForm" type="file" name="arquivo" accept="application/pdf"/>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary" title="Adicionar" id="AddArquivoForm"><i class="fa fa-plus"></i> Adicionar</button>
                                </div>
                            </div>

                        </div>
                        <!-- <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
                            <a href="#contas"  class="btn btn-small" data-dismiss="modal" data-url="<?php echo site_url('contas/tab_listar_contas/') ?>" 
                            data-nome="contas"  data-toggle="tab" id="atualizar-contas">Cancelar</a>
                            <input type="submit" id="enviar" class="btn btn-primary btn-small" value="Enviar"/>
                        </div> -->
                    </form>
                </div>

                <div class="col-md-11 tabela" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Visualizar</th>
                        </thead>
                        <?php if ($arquivos) : ?>
                            <tbody class="inner">
                                <?php foreach ($arquivos as $arquivo) : ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $arquivo->id ?></td>
                                        <td style="text-align: center;"><?php echo $arquivo->descricao ?></td>
                                        <td style="justify-content: center; display:flex;">
                                            <a href="<?php echo site_url('contas/view_file/' . $arquivo->file) ?>" target="_blank" class="btn btn-primary btn-mini">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                Visualizar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        <?php else : ?>
                            <tbody class="inner">
                            </tbody>
                        <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="footer-group" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        $(".resultado").hide();
        $(".resultado2").hide();

        $("#AddArquivoForm").on('click', function(e) {
            e.preventDefault();

            var dataForm = new FormData();

            //File data
            var descricao = $('#descricaoArquivo').val();
            var comprovante = $('#comprovante').prop("checked") == "checked";

            var file_data = $('#filesForm')[0];
            file = file_data.files[0];

            if (!file) {
                alert("Selecione um Arquivo.");
                return;
            }

            dataForm.append("arquivo", file);
            dataForm.append("descricao", descricao);
            dataForm.append("comprovante", comprovante);

            $.ajax({
                url: "<?php echo site_url('contas/digitalizacao/' . $id_conta) ?>",
                type: "POST",
                data: dataForm,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    var carregar = [
                        '<div class="progress progress-striped active">',
                        '<div class="bar" style="width: 100%;"><b>Carregando...</b></div>',
                        '</div>'
                    ].join('');
                    $('.resultado2').html(carregar);
                    $(".resultado2").show();
                    $(".resultado").hide();
                    $("#enviar").hide();
                },
                success: function(retorno) {
                    retorno = JSON.parse(retorno);
                    if (retorno.success) {
                        var tpl = [
                            '<tr>',
                            '<td>', retorno.registro.id, '</td>',
                            '<td>', retorno.registro.descricao, '</td>',
                            '<td>',
                            '<a href="<?php echo site_url('contas/view_file') ?>/', retorno.registro.file, '" target="_blank" class="btn btn-success btn-mini">',
                            '<i class="fa fa-eye" aria-hidden="true"></i>',
                            'Visualizar',
                            '</a>',
                            '</td>',
                            '</tr>'
                        ].join('');

                        $('tbody.inner').append(tpl);
                    }

                    $(".resultado").html(retorno.mensagem);
                    $(".resultado").show();
                    $(".resultado2").hide();
                    $("#enviar").show();
                    $("#descricaoArquivo").val('');
                    $("#comprovante").val('');
                    $("#filesForm").val('');
                },
                error: function(error) {
                    console.log(error)
                },

            });
        });


        $('input[type=file]').change(function() {
            var t = $(this).val();
            var labelText = 'Arquivo : ' + t.substr(12, t.length);
            $(this).prev('label').text(labelText);
        });
    });
</script>