<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

<style>
    .modal-header {
        text-align: center;
    }
    .modal-body {
        max-height:500px;
        overflow-y:auto;
    }
    .obrigatorio{
        color: red;
    }
</style>

<!-- Modal Cadastrar Permissao -->
<div id="modalCadPermissao" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPermissao">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Permissão</h4>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="permissao_descricao" name="permissao_descricao" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Código da Permissão">Código da Permissão<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="permissao_codigo" name="permissao_codigo" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_permissao" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
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
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Permissão</h4>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">

                        <input class="hidden" id="id_permissao_edit" name="id_permissao_edit"/>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="permissao_descricao_edit" name="permissao_descricao_edit" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Código da Permissão">Código da Permissão<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="permissao_codigo_edit" name="permissao_codigo_edit" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_permissao_edit" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Módulo -->
<div id="modalCadModulo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formModulo">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Módulo</h4>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="control-group">
                                <label class="control-label" title="Nome">Nome<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="modulo_nome" name="modulo_nome" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Permissões">Permissões<span class="obrigatorio">*</span>:</label>
                                <table id="tabela_permissoes_modulo" class="pull-left table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checked_all_permissoes" data-form="formModulo"/></th>
                                            <th>Permissão</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_modulo" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Módulo -->
<div id="modalEditModulo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formModuloEdit">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Módulo</h4>
                </div>

                <div class="modal-body row-fluid">
                    <div class="row">
                        <input class="hidden" id="id_modulo_edit" name="id_modulo_edit"/>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Nome">Nome<span class="obrigatorio">*</span>:</label>
                                <input class="form-control" id="modulo_nome_edit" name="modulo_nome_edit" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Permissões">Permissões<span class="obrigatorio">*</span>:</label>
                                <table id="tabela_permissoes_modulo_edit" class="pull-left table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checked_all_permissoes_edit" data-form="formModuloEdit"/></th>
                                            <th>Permissão</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_modulo_edit" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Plano -->
<div id="modalCadPlano" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPlano">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Plano</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Nome do Plano">Nome<span class="obrigatorio">*</span>:</label>
                                <input style="width:100%" id="plano_nome" name="plano_nome" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição:</label>
                                <input style="width:100%" id="plano_descricao" name="plano_descricao"/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label row col-md-12" title="Plano editável">Permitir edição das permissões do plano?<span class="obrigatorio">*</span></label>

                                <div class="col-md-6">
                                    <input type="radio" id="plano_editavel_sim" name="plano_editavel" value="1" checked>
                                    <label for="plano_editavel_sim">Sim</label>
                                </div>

                                <div class="col-md-6">
                                    <input type="radio" id="plano_editavel_nao" name="plano_editavel" value="0">
                                    <label for="plano_editavel_nao">Não</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Módulos">Módulos<span class="obrigatorio">*</span>:</label>
                                <table id="tabela_modulos_plano" class="pull-left table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checked_all_modulos" data-form="formPlano"/></th>
                                            <th>Módulo</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_plano" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Plano -->
<div id="modalEditPlano" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPlanoEdit">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Plano</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <input class="hidden" id="id_plano_edit" name="id_plano_edit"/>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Nome do Plano">Nome<span class="obrigatorio">*</span>:</label>
                                <input style="width:100%" id="plano_nome_edit" name="plano_nome_edit" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição:</label>
                                <input style="width:100%" id="plano_descricao_edit" name="plano_descricao_edit"/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label row col-md-12" title="Plano editável">Permitir edição das permissões do plano?<span class="obrigatorio">*</span></label>

                                <div class="col-md-6">
                                    <input type="radio" id="plano_editavel_sim_edit" name="plano_editavel_edit" value="1">
                                    <label for="plano_editavel_sim_edit">Sim</label>
                                </div>

                                <div class="col-md-6">
                                    <input type="radio" id="plano_editavel_nao_edit" name="plano_editavel_edit" value="0" checked>
                                    <label for="plano_editavel_nao_edit">Não</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Módulos">Módulos<span class="obrigatorio">*</span>:</label>
                                <table id="tabela_modulos_plano_edit" class="pull-left table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checked_all_modulos_edit" data-form="formPlanoEdit"/></th>
                                            <th>Módulo</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_plano_edit" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Produto -->
<div id="modalCadProduto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formProduto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Produto</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Nome do Produto">Nome<span class="obrigatorio">*</span>:</label>
                                <input style="width:100%" id="produto_nome" name="produto_nome" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição:</label>
                                <input style="width:100%" id="produto_descricao" name="produto_descricao"/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Perfis/Planos">Perfis/Planos<span class="obrigatorio">*</span>:</label>
                                <table id="tabela_planos_produto" class="pull-left table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checked_all_planos" data-form="formProduto"/></th>
                                            <th>Perfl/Plano</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_produto" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Produto -->
<div id="modalEditProduto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formProdutoEdit">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Produto</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <input class="hidden" id="id_produto_edit" name="id_produto_edit"/>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Nome do Produto">Nome<span class="obrigatorio">*</span>:</label>
                                <input style="width:100%" id="produto_nome_edit" name="produto_nome_edit" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Descrição">Descrição:</label>
                                <input style="width:100%" id="produto_descricao_edit" name="produto_descricao_edit"/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Perfis/Planos">Perfis/Planos<span class="obrigatorio">*</span>:</label>
                                <table id="tabela_planos_produto_edit" class="pull-left table table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checked_all_planos_edit" data-form="formProdutoEdit"/></th>
                                            <th>Perfil/Plano</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_produto_edit" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#permissoes" aria-controls="permissoes" role="tab" data-toggle="tab">Permissões</a></li>
        <li role="presentation"><a href="#modulos" aria-controls="modulos" role="tab" data-toggle="tab">Módulos</a></li>
        <li role="presentation"><a href="#planos" aria-controls="planos" role="tab" data-toggle="tab">Perfis/Planos</a></li>
        <li role="presentation"><a href="#produtos" aria-controls="dados" role="tab" data-toggle="tab">Produtos</a></li>
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
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="modulos">
            <div class="well well-small">
                <button type="button" class="btn btn-new btn-primary" id="button_add_modulo" title="Adicionar" style="margin-bottom: 10px" data-toggle="modal" data-target="#modalCadModulo"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>

                <br>

                <table id="tabela_modulo" class="table table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Módulo</th>
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

        <div class="tab-pane" id="planos">
            <div class="well well-small">
                <button type="button" class="btn btn-new btn-primary" id="button_add_plano" title="Adicionar" style="margin-bottom: 10px" data-toggle="modal" data-target="#modalCadPlano"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>

                <br>

                <table id="tabela_plano" class="table table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Perfil/Plano</th>
                            <th>Descrição</th>
                            <th>Data do Cadastro</th>
                            <th>Editar Permissões</th>
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
                <button type="button" class="btn btn-new btn-primary" id="button_add_produto" title="Adicionar" style="margin-bottom: 10px" data-toggle="modal" data-target="#modalCadProduto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>

                <br>

                <table id="tabela_produto" class="table table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Produto</th>
                            <th>Descrição</th>
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

    //Cadastro e edição de permissões
    var tabela_permissao = $('#tabela_permissao').DataTable( {
        ajax: "<?= site_url('cadastros/ajax_permissoes') ?>",
        createdRow: function (row, data, dataIndex) {
            $(row).attr('id', data[0]+'permissao');
        },
        order: [0, 'desc'],
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    $(document).on('click', '#button_add_permissao', function () {
        $( "#formPermissao input:text" ).val('');
        $( "#formPermissao select" ).val('');
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
                descricao: dados['permissao_descricao'],
                cod_permissao: dados['permissao_codigo'],
            },
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    tabela_permissao.row.add(callback.dados).draw(false, null);
                    $('#modalCadPermissao').modal('hide');

                    tabela_permissoes_modulo.clear();
                    tabela_permissoes_modulo_edit.clear();
                    tabela_permissoes_modulo.ajax.reload();
                } else {
                    alert(callback.msg);
                }

                $('#submit_permissao').attr('disabled', false).html('Salvar');
            },
            error: function () {
                alert('Não foi possível cadastrar a permissão no momento. Tente novamente mais tarde!');
                $('#submit_permissao').attr('disabled', false).html('Salvar');
            }
        })
        
    });

    $(document).on('click', '.editarPermissao', function() {
        id_permissao = $(this).data('id');

        dados = tabela_permissao.row('#'+id_permissao+'permissao').data(); 
        
        $('#id_permissao_edit').val(id_permissao);
        $('#permissao_descricao_edit').val(dados[1]);
        $('#permissao_codigo_edit').val(dados[2]);
        
        $('#modalEditPermissao').modal('show');
    });

    $('#formPermissaoEdit').submit(function() {
        event.preventDefault();

        $('#submit_permissao_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        let dados = [];
        data = $(this).serializeArray();
        data.forEach(permissao => {
            dados[permissao.name] = permissao.value; 
        });

        $.ajax({
            url: "<?= site_url('cadastros/edit_permissao') ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_permissao: dados['id_permissao_edit'],
                descricao: dados['permissao_descricao_edit'],
                cod_permissao: dados['permissao_codigo_edit'],
            },
            success: callback => {
                if (callback.status == true) {
                    alert('Atualizado com sucesso!');
                    tabela_permissao.row('#'+dados['id_permissao_edit']+'permissao').data(callback.dados).invalidate();
                    $('#modalEditPermissao').modal('hide');
                } else {
                    alert(callback.msg);
                }

                $('#submit_permissao_edit').attr('disabled', false).html('Salvar');
            },
            error: function () {
                alert('Não foi possível editar a permissão no momento. Tente novamente mais tarde!');
                $('#submit_permissao_edit').attr('disabled', false).html('Salvar');
            }
        })
        
    });

    $(document).on('click', '.btn_status_permissao', function () {
        let id_permissao = $(this).attr('data-id_permissao');
        let status = $(this).attr('data-status');

        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + $(this).html());

        $.ajax({
            url: "<?= site_url('cadastros/status_permissao')  ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_permissao : id_permissao,
                status: status
            },
            cache: false,
            success: callback => {                
                if (callback.status == true) {
                    let label_status = $(this).closest('tr').find('td').eq(4).children();
                    let editar_button = $(this).closest('tr').find('td').eq(5).children()[0];
                    
                    if ($(this).attr('data-status') == '1') {
                        $(label_status).removeClass('label-danger').addClass('label-success').html('Ativo');
                        $(editar_button).attr('disabled', false);
                        $(this).attr('data-status', '0').removeClass('btn-success').addClass('btn-danger').html('Inativar');
                    } else {
                        $(label_status).removeClass('label-success').addClass('label-danger').html('Inativo');
                        $(editar_button).attr('disabled', true);
                        $(this).attr('data-status', '1').removeClass('btn-danger').addClass('btn-success').html('Ativar');
                    }

                    tabela_permissoes_modulo.clear();
                    tabela_permissoes_modulo_edit.clear();
                    tabela_permissoes_modulo.ajax.reload();
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

    //Cadastro e edição de módulos
    var tabela_modulo = $('#tabela_modulo').DataTable( {
        ajax: "<?= site_url('cadastros/ajax_modulos') ?>",
        createdRow: function (row, data, dataIndex) {
            $(row).attr('id', data[0]+'modulo');
        },
        processing: true,
        order: [0, 'desc'],
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    var tabela_permissoes_modulo_edit = $('#tabela_permissoes_modulo_edit').DataTable( {
        order: [1,'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    var tabela_permissoes_modulo = $('#tabela_permissoes_modulo').DataTable( {
        ajax: {
            url: "<?= site_url('cadastros/ajax_permissoes/true') ?>",
            success: function (callback) {
                tabela_permissoes_modulo_edit.rows.add(callback.data).draw();
                tabela_permissoes_modulo.rows.add(callback.data).draw();
            }
        },
        order: [1,'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    $(document).on('change', '.checked_all_permissoes', function () {
        let checked = false;
        let form = $(this).data('form');
        
        if ($(this).prop('checked'))
            checked = true;

        $('form#'+form+' input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', checked);
        });
    });

    $(document).on('change', '.checked_all_permissoes_edit', function () {
        let checked = false;
        let form = $(this).data('form');
        
        if ($(this).prop('checked'))
            checked = true;

        $('form#'+form+' input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', checked);
        });
    });

    $(document).on('click', '#button_add_modulo', function () {
        $( "#formModulo input:text" ).val('');
        $( "#formModulo input:checkbox" ).prop('checked', false);
    });

    $('#formModulo').submit(function() {
        event.preventDefault();

        // Desabilita button e inicia spinner
        $('#submit_modulo').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        dados = $(this).serialize();

        $.ajax({
            url: "<?= site_url('cadastros/add_modulo') ?>",
            type: "POST",
            dataType: "json",
            data: dados,
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    $('#submit_modulo').attr('disabled', false).html('Salvar');

                    $('#modalCadModulo').modal('hide');

                    tabela_modulo.ajax.reload();
                    tabela_modulos_plano.clear();
                    tabela_modulos_plano_edit.clear();
                    tabela_modulos_plano.ajax.reload();
                } else {
                    alert(callback.msg);
                }

                $('#submit_modulo').attr('disabled', false).html('Salvar');
            },
            error: function () {
                alert('Não foi possível cadastrar o módulo no momento. Tente novamente mais tarde!');
                $('#submit_modulo').attr('disabled', false).html('Salvar');
            }
        })
        
    });

    $(document).on('click', '.editarModulo', function() {
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Editar');

        id_modulo = $(this).data('id');

        dados = tabela_modulo.row('#'+id_modulo+'modulo').data(); 
        
        $('#id_modulo_edit').val(id_modulo);
        $('#modulo_nome_edit').val(dados[1]);

        $('form#formModuloEdit input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', false);
        });

        $('#submit_modulo_edit').attr('disabled', true);

        $.ajax({
            url: "<?= site_url('cadastros/get_permissoes_modulo') ?>",
            type: "GET",
            dataType: "json",
            data: {id_modulo: id_modulo},
            success: callback => {
                if (callback.status == true) {                    
                    $.each(callback.permissoes, function(key, permissao) {
                        //console.log(permissao.id_permissao);
                        $('form#formModuloEdit input[value="'+permissao.id_permissao+'"]').prop('checked', true);
                    });

                }else{
                    alert(callback.msg);
                }
                $('#modalEditModulo').modal('show');

                $(this).html('Editar');

                $('#submit_modulo_edit').attr('disabled', false);
            },
        });

    });

    $('#formModuloEdit').submit(function() {
        event.preventDefault();

        $('#submit_modulo_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        dados = $(this).serialize();

        $.ajax({
            url: "<?= site_url('cadastros/edit_modulo') ?>",
            type: "POST",
            dataType: "json",
            data: dados,
            success: callback => {
                if (callback.status == true) {
                    alert('Atualizado com sucesso!');
                    tabela_modulo.ajax.reload();
                    $('#modalEditModulo').modal('hide');
                } else {
                    alert(callback.msg);
                }

                $('#submit_modulo_edit').attr('disabled', false).html('Salvar');
            },
            error: function () {
                alert('Não foi possível editar o módulo no momento. Tente novamente mais tarde!');
                $('#submit_modulo_edit').attr('disabled', false).html('Salvar');
            }
        })
        
    });

    $(document).on('click', '.btn_status_modulo', function () {
        let id_modulo = $(this).attr('data-id_modulo');
        let status = $(this).attr('data-status');

        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + $(this).html());

        $.ajax({
            url: "<?= site_url('cadastros/status_modulo')  ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_modulo : id_modulo,
                status: status
            },
            cache: false,
            success: callback => {                
                if (callback.status == true) {
                    let label_status = $(this).closest('tr').find('td').eq(3).children();
                    let editar_button = $(this).closest('tr').find('td').eq(4).children()[0];
                    
                    if ($(this).attr('data-status') == '1') {
                        $(label_status).removeClass('label-danger').addClass('label-success').html('Ativo');
                        $(editar_button).attr('disabled', false);
                        $(this).attr('data-status', '0').removeClass('btn-success').addClass('btn-danger').html('Inativar');
                    } else {
                        $(label_status).removeClass('label-success').addClass('label-danger').html('Inativo');
                        $(editar_button).attr('disabled', true);
                        $(this).attr('data-status', '1').removeClass('btn-danger').addClass('btn-success').html('Ativar');

                    }

                    tabela_modulos_plano.clear();
                    tabela_modulos_plano_edit.clear();
                    tabela_modulos_plano.ajax.reload();
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

    //Fim cadastro e edição de módulos

    //Cadastro e edição de planos
    var tabela_plano = $('#tabela_plano').DataTable( {
        ajax: "<?= site_url('cadastros/ajax_planos') ?>",
        createdRow: function (row, data, dataIndex) {
            $(row).attr('id', data[0]+'plano');
        },
        processing: true,
        order: [0, 'desc'],
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    var tabela_modulos_plano_edit = $('#tabela_modulos_plano_edit').DataTable( {
        order: [1,'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    var tabela_modulos_plano = $('#tabela_modulos_plano').DataTable( {
        ajax: {
            url: "<?= site_url('cadastros/ajax_modulos/true') ?>",
            success: function (callback) {
                tabela_modulos_plano_edit.rows.add(callback.data).draw();
                tabela_modulos_plano.rows.add(callback.data).draw();
            }
        },
        order: [1,'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    $(document).on('change', '.checked_all_modulos', function () {
        let checked = false;
        let form = $(this).data('form');
        
        if ($(this).prop('checked'))
            checked = true;

        $('form#'+form+' input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', checked);
        });
    });

    $(document).on('change', '.checked_all_modulos_edit', function () {
        let checked = false;
        let form = $(this).data('form');
        
        if ($(this).prop('checked'))
            checked = true;

        $('form#'+form+' input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', checked);
        });
    });
    
    $(document).on('click', '#button_add_plano', function () {
        $("#formPlano input:text" ).val('');
        $("#formPlano input:checkbox" ).prop('checked', false);
        $("#formPlano input[type=radio][name=plano_editavel]").val([1]);
    });

    $('#formPlano').submit(function() {
        event.preventDefault();

        // Desabilita button e inicia spinner
        $('#submit_plano').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        dados = $(this).serialize();

        $.ajax({
            url: "<?= site_url('cadastros/add_plano') ?>",
            type: "POST",
            dataType: "json",
            data: dados,
            success: callback => {
                if (callback.status == true) {
                    alert('Cadastrado com sucesso!');
                    tabela_plano.ajax.reload();
                    $('#modalCadPlano').modal('hide');
                } else {
                    alert(callback.msg);
                }

                $('#submit_plano').attr('disabled', false).html('Salvar');
            },
            error: function () {
                alert('Não foi possível cadastrar o plano no momento. Tente novamente mais tarde!');
                $('#submit_plano').attr('disabled', false).html('Salvar');
            }
        })
        
    });

    $(document).on('click', '.editarPlano', function() {
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Editar');

        id_plano = $(this).data('id');

        dados = tabela_plano.row('#'+id_plano+'plano').data(); 
        
        $('#id_plano_edit').val(id_plano);
        $('#plano_nome_edit').val(dados[1]);
        $('#plano_descricao_edit').val(dados[2]);

        value = $(dados[4]).html() == 'Sim' ? 1 : 0;
        $("#formPlanoEdit input[name=plano_editavel_edit]").val([value]);

        $('form#formPlanoEdit input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', false);
        });

        $('#submit_plano_edit').attr('disabled', true);

        $.ajax({
            url: "<?= site_url('cadastros/get_modulos_plano') ?>",
            type: "GET",
            dataType: "json",
            data: {id_plano: id_plano},
            success: callback => {
                if (callback.status == true) {                    
                    $.each(callback.modulos, function(key, modulo) {
                        $('form#formPlanoEdit input[type="checkbox"][value="'+modulo.id_modulo+'"]').prop('checked', true);
                    });
                }else{
                    alert(callback.msg);
                }
                $('#modalEditPlano').modal('show');

                $(this).html('Editar');

                $('#submit_plano_edit').attr('disabled', false);
            },
        });

    });

    $('#formPlanoEdit').submit(function() {
        event.preventDefault();

        $('#submit_plano_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        dados = $(this).serialize();

        $.ajax({
            url: "<?= site_url('cadastros/edit_plano') ?>",
            type: "POST",
            dataType: "json",
            data: dados,
            success: callback => {
                if (callback.status == true) {
                    alert('Atualizado com sucesso!');
                    tabela_plano.ajax.reload();
                    $('#modalEditPlano').modal('hide');
                } else {
                    alert(callback.msg);
                }

                $('#submit_plano_edit').attr('disabled', false).html('Salvar');
            },
            error: function () {
                alert('Não foi possível editar o plano no momento. Tente novamente mais tarde!');
                $('#submit_plano_edit').attr('disabled', false).html('Salvar');
            }
        })   
    });

    $(document).on('click', '.btn_status', function () {
        let id_plano = $(this).attr('data-id_plano');
        let status = $(this).attr('data-status');

        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + $(this).html());

        $.ajax({
            url: "<?= site_url('cadastros/status_plano')  ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_plano : id_plano,
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

                    tabela_planos_produto.ajax.reload();
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

    //Fim cadastro e edição de planos

    //Cadastro e edição de produtos

    var tabela_produto = $('#tabela_produto').DataTable( {
        ajax: "<?= site_url('cadastros/ajax_produtos') ?>",
        createdRow: function (row, data, dataIndex) {
            $(row).attr('id', data[0]+'produto');
        },
        processing: true,
        order: [0, 'desc'],
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    var tabela_planos_produto_edit = $('#tabela_planos_produto_edit').DataTable( {
        order: [1,'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    var tabela_planos_produto = $('#tabela_planos_produto').DataTable( {
        ajax: {
            url: "<?= site_url('cadastros/ajax_planos/true') ?>",
            success: function (callback) {
                tabela_planos_produto_edit.clear();
                tabela_planos_produto_edit.rows.add(callback.data).draw();
                tabela_planos_produto.clear();
                tabela_planos_produto.rows.add(callback.data).draw();
            }
        },
        order: [1,'asc'],
        searching: true,
        paging: false,
        info: false,
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar: ",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Próxima",
                "next":       "Próxima",
                "previous":   "Anterior"
            }
        }
    });

    $(document).on('click', '#button_add_produto', function () {
        $( "#formProduto input:text" ).val('');
        $( "#formProduto input:checkbox" ).prop('checked', false);
    });

    $(document).on('change', '.checked_all_planos', function () {
        let checked = false;
        let form = $(this).data('form');
        
        if ($(this).prop('checked'))
            checked = true;

        $('form#'+form+' input[type="checkbox"]').each(function (i, a) {            
            $(a).prop('checked', checked);
        });
    });

    $(document).on('change', '.checked_all_planos_edit', function () {
        let checked = false;
        let form = $(this).data('form');
        
        if ($(this).prop('checked'))
            checked = true;

        $('form#'+form+' input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', checked);
        });
    });
    
    $('#formProduto').submit(function() {
        event.preventDefault();

        // Desabilita button e inicia spinner
        $('#submit_produto').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        dados = $(this).serialize();

        $.ajax({
            url: "<?= site_url('cadastros/add_produto') ?>",
            type: "POST",
            dataType: "json",
            data: dados,
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
            error: function () {
                alert('Não foi possível cadastrar o produto no momento. Tente novamente mais tarde!');
                $('#submit_produto').attr('disabled', false).html('Salvar');
            }
        })
        
    });

    $(document).on('click', '.editarProduto', function(event) {
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Editar');

        id_produto= $(this).data('id');

        dados = tabela_produto.row('#'+id_produto+'produto').data(); 
        
        $('#id_produto_edit').val(id_produto);
        $('#produto_nome_edit').val(dados[1]);
        $('#produto_descricao_edit').val(dados[2]);

        $('form#formProdutoEdit input[type="checkbox"]').each(function (i, a) {
            $(a).prop('checked', false);
        });

        $('#submit_produto_edit').attr('disabled', true);

        $.ajax({
            url: "<?= site_url('cadastros/get_planos_produto') ?>",
            type: "GET",
            dataType: "json",
            data: {id_produto: id_produto},
            success: callback => {
                if (callback.status == true) {                    
                    $.each(callback.planos, function(key, plano) {
                        $('form#formProdutoEdit input[value="'+plano.id_plano+'"]').prop('checked', true);
                    });
                }else{
                    alert(callback.msg);
                }
                $(this).html('Editar');

                $('#modalEditProduto').modal('show');
                
                $('#submit_produto_edit').attr('disabled', false);
            },
        });

    });

    $('#formProdutoEdit').submit(function() {
        event.preventDefault();

        $('#submit_produto_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');

        dados = $(this).serialize();

        $.ajax({
            url: "<?= site_url('cadastros/edit_produtos') ?>",
            type: "POST",
            dataType: "json",
            data: dados,
            success: callback => {
                if (callback.status == true) {
                    alert('Atualizado com sucesso!');
                    tabela_produto.ajax.reload();
                    $('#modalEditProduto').modal('hide');
                } else {
                    alert(callback.msg);
                }

                $('#submit_produto_edit').attr('disabled', false).html('Salvar');
            },
            error: function () {
                alert('Não foi possível editar o produto no momento. Tente novamente mais tarde!');
                $('#submit_produto_edit').attr('disabled', false).html('Salvar');
            }
        })   
    });

    $(document).on('click', '.btn_status_produto', function () {
        let id_produto = $(this).attr('data-id_produto');
        let status = $(this).attr('data-status');

        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + $(this).html());

        $.ajax({
            url: "<?= site_url('cadastros/status_produto')  ?>",
            type: "POST",
            dataType: "json",
            data: {
                id_produto : id_produto,
                status: status
            },
            cache: false,
            success: callback => {                
                if (callback.status == true) {
                    let label_status = $(this).closest('tr').find('td').eq(4).children();
                    let editar_button = $(this).closest('tr').find('td').eq(5).children()[0];
                    
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

    //Fim cadastro e edição de produtos

</script>