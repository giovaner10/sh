<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/nexxera', 'layout.css') ?>">

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;">Arquivos Controle de Qualidade</h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('departamentos') ?> >
        <?= lang('controle_de_qualidade') ?> >
        <?= lang('arquivos_iso') ?> >
        Arquivos Controle de Qualidade
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-12" id="conteudo">
        <div class="card-conteudo card-formularios" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b>Arquivos Controle de Qualidade: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button id="btnAddArquivo" target="_blank" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Novo Arquivo</button>
                </div>
            </h3>
            <div style="position: relative;">
                <?php
                if (count($arq_iso) > 0) {
                ?>
                    <table id="table" class="table table-bordered table-hover">
                        <thead style="background-color: #1C69AD !important; color: white !important;">
                            <th style="width: 100px; text-align: center; color: white !important;">Preview</th>
                            <th style="text-align: center; color: white !important;">Descrição</th>
                            <th style="width: 150px; text-align: center; color: white !important;">Ações</th>
                        </thead>
                        <tbody>
                            <?php foreach ($arq_iso as $iso) { ?>
                                <tr>
                                    <td><iframe src="<?php echo base_url("uploads/iso/$iso->file"); ?>" width="200" height="200" style="border: none;"></iframe></td>
                                    <td style="vertical-align: middle;"><?php echo $iso->descricao ?></td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <a style="width: 42px;" onclick="editarArquivo(<?= $iso->id ?>)" class="btn btn-mini btn-primary" title="Editar informação">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a style="width: 42px;" onclick="excluir(<?= $iso->id ?>)" class="btn btn-mini btn-danger" title="Excluir">
                                            <i id="icon<?= $iso->id ?>" class="fa fa-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-info">
                        <strong>Desculpe!</strong> Nenhum arquivo cadastrado até o momento.
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div id="addArquivo" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="formArquivo" enctype="multipart/form-data">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleCadastro">Cadastro de Arquivos</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>

                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="descricao">Descrição: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Informe a descrição" required>
                            </div>

                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="arquivo">Arquivo: <span class="text-danger">*</span></label>
                                <input type="file" name="arquivo" id="arquivo" class="form-control" data-buttonText="Arquivo" required>
                            </div>

                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="submit" id="enviar" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIÇÃO -->
<div id="editArquivo" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="formEdit" enctype="multipart/form-data">
                <input type="hidden" id="idArquivo">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleCadastro">Editar Arquivo</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>

                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="descricao">Descrição: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="descricao" id="descricaoEdit" placeholder="Informe a descrição" required>
                            </div>

                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="arquivo">Arquivo: </label>
                                <input type="file" name="arquivo" id="arquivoEdit" class="form-control" data-buttonText="Arquivo">
                            </div>
                            
                            <div class="col-md-12">
                                <span class="text-danger">*Caso não deseje alterar o arquivo, deixe o campo vazio!</span>
                            </div>
                            
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end;">
                        <button type="submit" id="enviarEdit" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function editarArquivo(id) {
        $.ajax({
            url: "<?= site_url('cadastros/editar_iso') ?>/" + id,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                ShowLoadingScreen();
            },
            success: function(data) {
                if (data.success == 'true') {
                    $('#descricaoEdit').val(data['data'][0]['descricao']);
                    $('#idArquivo').val(id);
                    $('#editArquivo').modal('show');

                } else {
                    showAlert('error', data.message);
                }
            },
            error: function() {
                showAlert('error', "Erro na solicitação ao servidor.");
            },
            complete: function() {
                HideLoadingScreen();
            }
        })
    }

    function excluir(id) {

        Swal.fire({
            title: "Atenção!",
            text: "Deseja realmente excluir este arquivo ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007BFF",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuar",
            cancelButtonText: "Cancelar",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "<?= site_url('cadastros/excluir_iso') . '/' ?>" + id;

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        ShowLoadingScreen();
                    },
                    success: function(data) {
                        if (data.success == 'true') {
                            showAlert('success', data.message);
                            window.location.reload();
                        } else {
                            showAlert('error', "Erro ao excluir arquivo.");
                        }
                    },
                    error: function() {
                        showAlert('error', "Erro na solicitação ao servidor.");
                    },
                    complete: function() {
                        HideLoadingScreen();
                    }
                });
            }

        })
    };

    $('#formEdit').on('submit', function(e) {
        e.preventDefault();
        let id = $('#idArquivo').val();

        var formData = new FormData(this);

        $.ajax({
            url: "<?php echo site_url("cadastros/edit_iso")?>/" + id,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                showLoadingSalvarButtonEdit();
            },
            success: function(data) {
                if (data.success == 'true') {
                    showAlert('success', data.message);
                    $('#editArquivo').modal('hide');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000)
                } else {
                    showAlert('error', data.message);
                }
            },
            error: function() {
                showAlert('error', "Erro na solicitação ao servidor.");
            },
            complete: function() {
                resetSalvarButtonEdit();
            }
        })
    })

    $('#formArquivo').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "<?php echo site_url('cadastros/cad_iso') ?>",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                showLoadingSalvarButton();
            },
            success: function(data) {
                if (data.success == 'true') {
                    showAlert('success', data.message);
                    $('#addArquivo').modal('hide');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000)
                } else {
                    showAlert('error', data.message);
                }
            },
            error: function() {
                showAlert('error', "Erro na solicitação ao servidor.");
            },
            complete: function() {
                resetSalvarButton();
            }
        })
    })

    $('#btnAddArquivo').on('click', function() {
        $('#addArquivo').modal('show');
    });

    $('#addArquivo').on('hide.bs.modal', function() {
        $('#formArquivo').trigger('reset');
    })

    function ShowLoadingScreen() {
        $('#loading').show()
    }

    function HideLoadingScreen() {
        $('#loading').hide()
    }

    function showLoadingSalvarButton() {
        $('#enviar').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
    }

    function resetSalvarButton() {
        $('#enviar').html('Salvar').attr('disabled', false);
    }

    function showLoadingSalvarButtonEdit() {
        $('#enviarEdit').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
    }

    function resetSalvarButtonEdit() {
        $('#enviarEdit').html('Salvar').attr('disabled', false);
    }
</script>