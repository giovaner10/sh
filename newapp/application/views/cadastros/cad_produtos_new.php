<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<style>
    .modal-header {
        text-align: center;
    }

    .modal-body {
        max-height: 500px;
        overflow-y: auto;
    }

    .obrigatorio {
        color: red;
    }

    .filter_produtos {
        border: 2px solid #767676 !important;
    }

    .uppercase {
        text-transform: uppercase
    }
</style>

<!-- Modal Cadastrar Permissao -->
<div id="modalCadPermissao" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPermissao">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= lang('cadastrar_permissao') ?></h4>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">

                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="<?= lang('nome') ?>"><?= lang('nome') ?><span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="permissao_nome" name="permissao_nome" required />
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="<?= lang('modulo') ?>"><?= lang('modulo') ?><span class="obrigatorio">*</span>:</label>
                                <select id="permissao_modulo" class="form-control" name="permissao_modulo" required>
                                    <option value="" disabled selected hidden><?= lang('selecione_modulo') ?></option>
                                    <?php
                                    foreach ($modulos as $modulo) {
                                        echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="<?= lang('tecnologia') ?>"><?= lang('tecnologia') ?><span class="obrigatorio">*</span>:</label>
                                <select id="permissao_tecnologia" class="form-control" name="permissao_tecnologia" required>
                                    <option value="" disabled selected hidden><?= lang('selecione_tecnologia') ?></option>
                                    <?php
                                    foreach ($tecnologias as $tecnologia) {
                                        echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><?= lang('fechar') ?></button>
                    <button type="submit" id="submit_permissao" class="btn btn-submit btn-primary"><?= lang('salvar') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Permissao -->
<div id="modalEditPermissao" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPermissaoEdit">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= lang('editar_permissao') ?></h4>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">

                        <input class="hidden" id="id_permissao_edit" name="id_permissao_edit" />
                        <input class="hidden" id="permissao_codigo_edit" name="permissao_codigo_edit" />

                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="<?= lang('nome') ?>"><?= lang('nome') ?><span class="obrigatorio">*</span>:</label>
                                <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                                    <input class="form-control" id="permissao_nome_edit" name="permissao_nome_edit" required />
                                <?php else : ?>
                                    <input class="form-control" id="permissao_nome_edit" name="permissao_nome_edit" readonly required />
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="<?= lang('modulo') ?>"><?= lang('modulo') ?><span class="obrigatorio">*</span>:</label>
                                <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                                    <select id="permissao_modulo_edit" class="form-control" name="permissao_modulo_edit" required>
                                        <option value="" disabled selected hidden><?= lang('selecione_modulo') ?></option>
                                        <?php
                                        foreach ($modulos as $modulo) {
                                            echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                        }
                                        ?>
                                    </select>
                                <?php else : ?>
                                    <select id="permissao_modulo_edit" class="form-control" name="permissao_modulo_edit" disabled required>
                                        <option value="" disabled selected hidden><?= lang('selecione_modulo') ?></option>
                                        <?php
                                        foreach ($modulos as $modulo) {
                                            echo '<option value="' . $modulo . '">' . $modulo . '</option>';
                                        }
                                        ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="control-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="<?= lang('tecnologia') ?>"><?= lang('tecnologia') ?><span class="obrigatorio">*</span>:</label>
                                <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                                    <select id="permissao_tecnologia_edit" class="form-control" name="permissao_tecnologia_edit" required>
                                        <option value="" disabled selected hidden><?= lang('selecione_tecnologia') ?></option>
                                        <?php
                                        foreach ($tecnologias as $tecnologia) {
                                            echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                        }
                                        ?>
                                    </select>
                                <?php else : ?>
                                    <select id="permissao_tecnologia_edit" class="form-control" name="permissao_tecnologia_edit" disabled required>
                                        <option value="" disabled selected hidden><?= lang('selecione_tecnologia') ?></option>
                                        <?php
                                        foreach ($tecnologias as $tecnologia) {
                                            echo '<option value="' . $tecnologia . '">' . $tecnologia . '</option>';
                                        }
                                        ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn" data-dismiss="modal"><?= lang('fechar') ?></button>
                    <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                        <button type="submit" id="submit_permissao_edit" class="btn btn-submit btn-primary"><?= lang('salvar') ?></button>
                    <?php else : ?>
                        <button type="submit" id="submit_permissao_edit" class="btn btn-submit btn-primary" disabled><?= lang('salvar') ?></button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Cadastrar Produto -->
<div id="modalCadProduto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formProduto">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Produto</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="control-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="Nome do Produto" class="required">Nome:</label>
                                <input type="text" style="width:100%" id="produto_nome" name="produto_nome" class="uppercase" minlength="3" maxlength="45" required />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="Descrição">Descrição:</label>
                                <input type="text" style="width:100%" id="produto_descricao" name="produto_descricao" maxlength="100" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="Licença"><?= lang('licenca') ?>:</label>
                                <select name="id_licenca" class="select_licencas" id="id_licenca" disabled></select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px; height: 25px;">
                                <label title="Permissões" class="required">Permissões:</label>
                                <table id="tabela_permissoes_produto" class="pull-left table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checked_all_permissoes" data-form="formProduto" /></th>
                                            <th>Permissão</th>
                                            <th>Módulo</th>
                                            <th>Tecnologia</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn" data-dismiss="modal">Fechar</button>
                    <button type="submit" id="submit_produto" class="btn btn-submit btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Produto -->
<div id="modalEditProduto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formProdutoEdit">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Produto</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <input class="hidden" id="id_produto_edit" name="id_produto_edit" />

                        <div class="col-md-12">
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="Nome do Produto" class="required">Nome:</label>
                                <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                                    <input type="text" style="width:100%" id="produto_nome_edit" name="produto_nome_edit" class="uppercase" required minlength="3" maxlength="45" />
                                <?php else : ?>
                                    <input type="text" style="width:100%" id="produto_nome_edit" name="produto_nome_edit" class="uppercase" required minlength="3" maxlength="45" readonly />
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label title="Descrição">Descrição:</label>
                                <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                                    <input type="text" style="width:100%" id="produto_descricao_edit" name="produto_descricao_edit" maxlength="100" />
                                <?php else : ?>
                                    <input type="text" style="width:100%" id="produto_descricao_edit" name="produto_descricao_edit" maxlength="100" readonly />
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="border-left: 3px solid #03A9F4; padding-left: 10px;">
                                <label title="Descrição" class="required"><?= lang('licenca') ?>:</label>
                                <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                                    <select name="id_licenca_edit" class="select_licencas" id="id_licenca_edit" required></select>
                                <?php else : ?>
                                    <label style="margin-top: 5px; margin-left: 5px">Não possui permissão para editar a licença do produto.</label>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="border-left: 3px solid #03A9F4; padding-left: 10px; height: 25px">
                                <label title="Permissões" class="required">Permissões:</label>
                                <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                                    <table id="tabela_permissoes_produto_edit" class="pull-left table table-bordered display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="checked_all_permissoes_edit" data-form="formProdutoEdit" /></th>
                                                <th>Permissão</th>
                                                <th>Módulo</th>
                                                <th>Tecnologia</th>
                                            </tr>
                                        </thead>
                                    </table>
                                <?php else : ?>
                                    <label style="margin-top: 5px; margin-left: 5px">Não possui permissão para editar as permissões do produto.</label>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn" data-dismiss="modal"><?= lang('fechar') ?></button>
                    <?php if ($this->auth->is_allowed_block('edit_permissoes_produtos_gestor')) : ?>
                        <button type="submit" id="submit_produto_edit" class="btn btn-submit btn-primary"><?= lang('salvar') ?></button>
                    <?php else : ?>
                        <button type="submit" id="submit_produto_edit" class="btn btn-submit btn-primary" disabled><?= lang('salvar') ?></button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="container-fluid">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#permissoes" aria-controls="permissoes" role="tab" data-toggle="tab">Permissões</a></li>
        <?php if ($this->auth->is_allowed_block('cad_produtosgestor')) : ?>
            <li role="presentation"><a href="#produtos" aria-controls="dados" role="tab" data-toggle="tab">Produtos</a></li>
        <?php endif; ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="permissoes">
            <div class="well well-small">
                <button type="button" class="btn btn-new btn-primary" id="button_add_permissao" title="Adicionar" style="margin-bottom: 10px" data-toggle="modal" data-target="#modalCadPermissao"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>

                <br>

                <table id="tabela_permissao" class="table table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Descrição</th>
                            <th>Cod. Permissão</th>
                            <th>Módulo</th>
                            <th>Tecnologia</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane " id="produtos">
            <div class="well well-small">

                <?php if ($this->auth->is_allowed_block('cad_produto_gestor')) { ?>
                    <button type="button" class="btn btn-new btn-primary" id="button_add_produto" title="Adicionar" style="margin-bottom: 10px" data-toggle="modal" data-target="#modalCadProduto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>
                <?php } ?>

                <br>

                <table id="tabela_produto" class="table table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Produto</th>
                            <th>Descrição</th>
                            <th><?= lang('codigo_produto') ?></th>
                            <th>Data do Cadastro</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        //Busca as licenças e preenche o select
        preencherSelectLicencas();
    });

    const permissaoCadastrarProdutos = Boolean("<?= $this->auth->is_allowed_block('cad_produtosgestor') ?>");
    
    //Cadastro e edição de permissões
    var tabela_permissao = $('#tabela_permissao').DataTable({
        ajax: "<?= site_url('cadastros/ajax_permissoes') ?>",
        createdRow: function(row, data, dataIndex) {
            $(row).attr('id', data[0] + 'permissao');
        },
        order: [0, 'desc'],
        "language": {
            "decimal": "",
            "emptyTable": "Nenhum registro encontrado",
            "info": "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "0 Registros",
            "infoFiltered": "(Filtrados de _MAX_ registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "search": "Pesquisar: ",
            "zeroRecords": "Nenhum registro encontrado",
            "paginate": {
                "first": "Anterior",
                "last": "Próxima",
                "next": "Próxima",
                "previous": "Anterior"
            }
        }
    });

    $(document).on('click', '#button_add_permissao', function() {
        $("#formPermissao input:text").val('');
        $("#formPermissao select").val('');
    });

    $('#formPermissao').submit(function() {
        event.preventDefault();

        // Desabilita button e inicia spinner
        $('#submit_permissao').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        let dados = [];
        data = $(this).serializeArray();
        data.forEach(permissao => {
            dados[permissao.name] = permissao.value;
        });

        $.ajax({
            url: "<?= site_url('cadastros/add_permissao') ?>",
            type: "POST",
            dataType: "json",
            data: {
                descricao: dados['permissao_nome'],
                modulo: dados['permissao_modulo'],
                tecnologia: dados['permissao_tecnologia'],
            },
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    tabela_permissao.row.add(callback.dados).draw(false, null);
                    $('#modalCadPermissao').modal('hide');

                    tabela_permissoes_produto.clear();
                    tabela_permissoes_produto_edit.clear();
                    tabela_permissoes_produto.ajax.reload();
                } else {
                    alert(callback.msg);
                }

                $('#submit_permissao').attr('disabled', false).html('Salvar');
            },
            error: function() {
                alert('Não foi possível cadastrar a permissão no momento. Tente novamente mais tarde!');
                $('#submit_permissao').attr('disabled', false).html('Salvar');
            }
        })

    });

    $(document).on('click', '.editarPermissao', function() {
        id_permissao = $(this).data('id');

        dados = tabela_permissao.row('#' + id_permissao + 'permissao').data();

        $('#id_permissao_edit').val(id_permissao);
        $('#permissao_nome_edit').val(dados[1]);
        $('#permissao_modulo_edit').val(dados[3]);
        $('#permissao_tecnologia_edit').val(dados[4]);
        $('#permissao_codigo_edit').val(dados[2]);

        $('#modalEditPermissao').modal('show');
    });

    $('#formPermissaoEdit').submit(function() {
        event.preventDefault();

        let dados = [];
        data = $(this).serializeArray();
        data.forEach(permissao => {
            dados[permissao.name] = permissao.value;
        });

        $('#submit_permissao_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        $.ajax({
            url: "<?= site_url('cadastros/edit_permissao') ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_permissao: dados['id_permissao_edit'],
                descricao: dados['permissao_nome_edit'],
                modulo: dados['permissao_modulo_edit'],
                tecnologia: dados['permissao_tecnologia_edit'],
                cod_permissao: dados['permissao_codigo_edit'],
            },
            success: callback => {
                if (callback.status == true) {
                    alert('Atualizado com sucesso!');
                    tabela_permissao.row('#' + dados['id_permissao_edit'] + 'permissao').data(callback.dados).invalidate();
                    $('#modalEditPermissao').modal('hide');

                    tabela_permissoes_produto.clear();
                    tabela_permissoes_produto_edit.clear();
                    tabela_permissoes_produto.ajax.reload();
                } else {
                    alert(callback.msg);
                }

                $('#submit_permissao_edit').attr('disabled', false).html('Salvar');
            },
            error: function() {
                alert('Não foi possível editar a permissão no momento. Tente novamente mais tarde!');
                $('#submit_permissao_edit').attr('disabled', false).html('Salvar');
            }
        })

    });

    $(document).on('click', '.btn_status_permissao', function() {
        let id_permissao = $(this).attr('data-id_permissao');
        let status = $(this).attr('data-status');

        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + $(this).html());

        $.ajax({
            url: "<?= site_url('cadastros/status_permissao')  ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_permissao: id_permissao,
                status: status
            },
            cache: false,
            success: callback => {
                if (callback.status == true) {
                    let label_status = $(this).closest('tr').find('td').eq(5).children();
                    let editar_button = $(this).closest('tr').find('td').eq(6).children()[0];

                    if ($(this).attr('data-status') == '1') {
                        $(label_status).removeClass('label-danger').addClass('label-success').html('Ativo');
                        $(editar_button).attr('disabled', false);
                        $(this).attr('data-status', '0').removeClass('btn-success').addClass('btn-danger').html('Inativar');
                    } else {
                        $(label_status).removeClass('label-success').addClass('label-danger').html('Inativo');
                        $(editar_button).attr('disabled', true);
                        $(this).attr('data-status', '1').removeClass('btn-danger').addClass('btn-success').html('Ativar');
                    }

                } else {
                    alert(callback.msg);
                }

                $(this).attr('disabled', false);
            },
            error: error => {
                $(this).attr('disabled', false);
            }
        });
    });

    //Fim cadastro e edição de permissões

    //Cadastro e edição de produtos
    var tabela_produto;
    if (permissaoCadastrarProdutos) {
        tabela_produto = $('#tabela_produto').DataTable({
            ajax: "<?= site_url('cadastros/ajax_produtos') ?>",
            createdRow: function(row, data, dataIndex) {
                $(row).attr('id', data[0] + 'produto');
            },
            processing: true,
            order: [0, 'desc'],
            "language": {
                "decimal": "",
                "emptyTable": "Nenhum registro encontrado",
                "info": "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "0 Registros",
                "infoFiltered": "(Filtrados de _MAX_ registros)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Exibir: _MENU_",
                "loadingRecords": "",
                "processing": "Processando...",
                "search": "Pesquisar: ",
                "zeroRecords": "Nenhum registro encontrado",
                "paginate": {
                    "first": "Anterior",
                    "last": "Próxima",
                    "next": "Próxima",
                    "previous": "Anterior"
                }
            }
        });
    }

    var tabela_permissoes_produto_edit = $('#tabela_permissoes_produto_edit').DataTable({
        order: [1, 'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal": "",
            "emptyTable": "Nenhum registro encontrado",
            "info": "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "0 Registros",
            "infoFiltered": "(Filtrados de _MAX_ registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "search": "Pesquisar: ",
            "zeroRecords": "Nenhum registro encontrado",
            "paginate": {
                "first": "Anterior",
                "last": "Próxima",
                "next": "Próxima",
                "previous": "Anterior"
            }
        }
    });

    var search_estoque_edit = document.createRange().createContextualFragment(`
        <label style="margin:5px">
            Módulo
            <select id="filter_modulo_edit" class="filter_produtos">
                <option value="Todos">Todos</option> 
                <option value="Atendimento">Atendimento</option> 
                <option value="Cadastro">Cadastro</option> 
                <option value="Comando">Comando</option> 
                <option value="Configuração">Configuração</option> 
                <option value="Monitorados">Monitorados</option> 
                <option value="Relatório">Relatório</option>
                <option value="Notificação">Notificação</option> 
            </select>
        </label>
        <label style="margin:5px">
            Tecnologia
            <select id="filter_tecnologia_edit" class="filter_produtos">
                <option value="Todos">Todos</option> 
                <option value="Omnilink">Omnilink</option> 
                <option value="Omniweb">Omniweb</option> 
                <option value="Omnifrota">Omnifrota</option> 
            </select>
        </label>`);

    $('#tabela_permissoes_produto_edit_filter').prepend(search_estoque_edit);
    $('#tabela_permissoes_produto_edit_filter').css('left', '60%');
    $('#tabela_permissoes_produto_edit_filter').css('width', '100%');

    $("#filter_modulo_edit").change(function() {
        filter_modulo = $('#filter_modulo_edit').val();

        if (filter_modulo == 'Todos') {
            tabela_permissoes_produto_edit.columns(2).search('', true, false).draw();
        } else {
            tabela_permissoes_produto_edit.columns(2).search(filter_modulo).draw();
        }
    });

    $("#filter_tecnologia_edit").change(function() {
        filter_tecnologia = $('#filter_tecnologia_edit').val();

        if (filter_tecnologia == 'Todos') {
            tabela_permissoes_produto_edit.columns(3).search('', true, false).draw();
        } else {
            tabela_permissoes_produto_edit.columns(3).search(filter_tecnologia).draw();
        }
    });

    var tabela_permissoes_produto = $('#tabela_permissoes_produto').DataTable({
        ajax: {
            url: "<?= site_url('cadastros/ajax_permissoes/true') ?>",
            success: function(callback) {
                tabela_permissoes_produto_edit.clear();
                tabela_permissoes_produto_edit.rows.add(callback.data).draw();
                tabela_permissoes_produto.clear();
                tabela_permissoes_produto.rows.add(callback.data).draw();
            }
        },
        order: [1, 'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal": "",
            "emptyTable": "Nenhum registro encontrado",
            "info": "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "0 Registros",
            "infoFiltered": "(Filtrados de _MAX_ registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "search": "Pesquisar: ",
            "zeroRecords": "Nenhum registro encontrado",
            "paginate": {
                "first": "Anterior",
                "last": "Próxima",
                "next": "Próxima",
                "previous": "Anterior"
            }
        }
    });

    var search_estoque = document.createRange().createContextualFragment(`  
        <label style="margin:5px">
            Módulo
            <select id="filter_modulo" class="filter_produtos">
                <option value="Todos">Todos</option> 
                <option value="Atendimento">Atendimento</option> 
                <option value="Cadastro">Cadastro</option> 
                <option value="Comando">Comando</option> 
                <option value="Configuração">Configuração</option> 
                <option value="Monitorados">Monitorados</option> 
                <option value="Relatório">Relatório</option>
                <option value="Notificação">Notificação</option> 
            </select>
        </label>
        <label style="margin:5px">
            Tecnologia
            <select id="filter_tecnologia" class="filter_produtos">
                <option value="Todos">Todos</option> 
                <option value="Omnilink">Omnilink</option> 
                <option value="Omniweb">Omniweb</option> 
                <option value="Omnifrota">Omnifrota</option> 
            </select>
        </label>`);

    $('#tabela_permissoes_produto_filter').prepend(search_estoque);
    $('#tabela_permissoes_produto_filter').css('left', '60%');
    $('#tabela_permissoes_produto_filter').css('width', '100%');

    $("#filter_modulo").change(function() {
        filter_modulo = $('#filter_modulo').val();

        if (filter_modulo == 'Todos') {
            tabela_permissoes_produto.columns(2).search('', true, false).draw();
        } else {
            tabela_permissoes_produto.columns(2).search(filter_modulo).draw();
        }

    });

    $("#filter_tecnologia").change(function() {
        filter_tecnologia = $('#filter_tecnologia').val();

        if (filter_tecnologia == 'Todos') {
            tabela_permissoes_produto.columns(3).search('', true, false).draw();
        } else {
            tabela_permissoes_produto.columns(3).search(filter_tecnologia).draw();
        }
    });

    $(document).on('click', '#button_add_produto', function() {

        $('#filter_modulo').val('Todos');
        $('#filter_tecnologia').val('Todos');
        $('.select_licencas').val(null).trigger('change');

        tabela_permissoes_produto.columns(2).search('', true, false).draw();
        tabela_permissoes_produto.columns(3).search('', true, false).draw();
        tabela_permissoes_produto.search('').draw();

        $("#formProduto input:text").val('');
        $("#formProduto input:checkbox").prop('checked', false);
    });

    $(document).on('change', '.checked_all_permissoes', function() {
        let checked = false;
        let form = $(this).data('form');

        if ($(this).prop('checked'))
            checked = true;

        $('form#' + form + ' input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', checked);
        });
    });

    $(document).on('change', '.checked_all_permissoes_edit', function() {
        let checked = false;
        let form = $(this).data('form');

        if ($(this).prop('checked'))
            checked = true;

        $('form#' + form + ' input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', checked);
        });
    });


    $('#formProduto').submit(function() {
        event.preventDefault();

        // Desabilita button e inicia spinner
        $('#submit_produto').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        let permissoes_produto = new Array();
        tabela_permissoes_produto.$('.produto_permissoes:checked').each(function() {
            permissoes_produto.push(this.value);
        });

        let data = {
            produto_nome: $('#produto_nome').val()?.toUpperCase(),
            produto_descricao: $('#produto_descricao').val(),
            id_licenca: $('#id_licenca').val(),
            produto_permissoes: permissoes_produto.join(',')
        };

        $.ajax({
            url: "<?= site_url('cadastros/add_produto') ?>",
            type: "POST",
            dataType: "json",
            data: data,
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    tabela_produto.ajax.reload();
                    $('#modalCadProduto').modal('hide');
                } else {
                    alert(callback.msg);
                }

                $('#submit_produto').attr('disabled', false).html('Salvar');
            },
            error: function() {
                alert('Não foi possível cadastrar o produto no momento. Tente novamente mais tarde!');
                $('#submit_produto').attr('disabled', false).html('Salvar');
            }
        })

    });

    $(document).on('click', '.editarProduto', function(event) {
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Editar');

        id_produto = $(this).data('id');
        dados = tabela_produto.row('#' + id_produto + 'produto').data();

        $('#id_produto_edit').val(id_produto);
        $('#produto_nome_edit').val(dados[1]);
        $('#produto_descricao_edit').val(dados[2]);
        $('#id_licenca_edit').val(dados[7]).trigger('change');

        $('#filter_modulo_edit').val('Todos');
        $('#filter_tecnologia_edit').val('Todos');
        tabela_permissoes_produto_edit.columns(2).search('', true, false).draw();
        tabela_permissoes_produto_edit.columns(3).search('', true, false).draw();
        tabela_permissoes_produto_edit.search('').draw();

        $('form#formProdutoEdit input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', false);
        });

        $.ajax({
            url: "<?= site_url('cadastros/get_permissoes_produto') ?>",
            type: "GET",
            dataType: "json",
            data: {
                id_produto: id_produto
            },
            success: callback => {
                if (callback.status == true) {
                    $.each(callback.permissoes, function(key, permissao) {
                        $('form#formProdutoEdit input[value="' + permissao.id_permissao + '"]').prop('checked', true);
                    });
                } else {
                    alert(callback.msg);
                }
                $(this).html('Editar');

                $('#modalEditProduto').modal('show');
            },
        });

    });

    $('#formProdutoEdit').submit(function() {
        event.preventDefault();

        if (confirm("Editar as permissões deste produto alterará as permissões de todos os clientes que o possuam. Confirmar operação?")) {

            $('#submit_produto_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

            dados = $(this).serialize();

            let permissoes_produto_edit = new Array();

            tabela_permissoes_produto_edit.$('.produto_permissoes:checked').each(function() {
                permissoes_produto_edit.push(this.value);
            });

            let data = {
                id_produto_edit: $('#id_produto_edit').val(),
                produto_nome_edit: $('#produto_nome_edit').val()?.toUpperCase(),
                produto_descricao_edit: $('#produto_descricao_edit').val(),
                id_licenca_edit: $('#id_licenca_edit').val(),
                produto_permissoes_edit: permissoes_produto_edit.join(',')
            };

            $.ajax({
                url: "<?= site_url('cadastros/edit_produtos') ?>",
                type: "POST",
                dataType: "json",
                data: data,
                success: callback => {
                    if (callback.status == true) {
                        tabela_produto.ajax.reload();
                        $('#modalEditProduto').modal('hide');
                    }

                    alert(callback.mensagem);
                    $('#submit_produto_edit').attr('disabled', false).html('Salvar');
                },
                error: function() {
                    alert(lang.erro_edicao_produto);
                    $('#submit_produto_edit').attr('disabled', false).html('Salvar');
                }
            })
        } else {
            return false;
        }
    });

    // Remova todos os eventos de clique dos botões antes de adicionar o evento 'on'
    $(document).off('click', '.btn_status_produto').on('click', '.btn_status_produto', function() {
        let button = $(this); // Armazena a referência ao botão para uso posterior
        let id_produto = button.data('id_produto');
        let status = button.data('status');

        // Bloqueia o botão e exibe o spinner
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        // Envie o AJAX para atualizar o status do produto
        $.ajax({
            url: "<?= site_url('cadastros/status_produto') ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_produto: id_produto,
                status: status
            },
            cache: false,
            success: callback => {
                if (callback.status == true) {
                    // Atualiza o status do botão e da etiqueta de status
                    let label_status = button.closest('tr').find('td').eq(5).children();
                    let editar_button = button.closest('tr').find('td').eq(6).children()[0];

                    if (status == '1') {
                        $(label_status).removeClass('label-danger').addClass('label-success').html('Ativo');
                        $(editar_button).attr('disabled', false);
                        button.data('status', '0').removeClass('btn-success').addClass('btn-danger').html('Inativar');
                    } else {
                        $(label_status).removeClass('label-success').addClass('label-danger').html('Inativo');
                        $(editar_button).attr('disabled', true);
                        button.data('status', '1').removeClass('btn-danger').addClass('btn-success').html('Ativar');
                    }
                } else {
                    alert(callback.msg);
                }

                // Desbloqueia o botão após a conclusão do AJAX
                button.attr('disabled', false);
            },
            error: error => {
                alert("Ocorreu um erro ao processar a solicitação.");
                // Desbloqueia o botão em caso de erro no AJAX
                button.attr('disabled', false);
            }
        });
    });



    async function preencherSelectLicencas() {
        const select = $('.select_licencas');

        //instancia o select2
        select.select2({
            placeholder: lang.selecione_licenca,
            allowClear: true,
            width: '100%'
        });

        // busca os dados de licencas para preencher o select
        return await fetch('<?= site_url('vendasgestor/listar_licencas_produtos') ?>')
            .then(response => response.json())
            .then(dados => {
                let options = `<option value="" selected disabled>${lang.selecione_licenca}</option>`;
                for (const dado of dados) {
                    options += `<option value="${dado.id_licenca}">${dado.nome}</option>`;
                }

                select.html(options)
                    .removeAttr('disabled')
                    .trigger("change");
            })
            .catch(error => {});
    }

    //Fim cadastro e edição de produtos
</script>