<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="<?php echo base_url('media') ?>/css/jquery.tree-multiselect.css" rel="stylesheet">

<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.tree-multiselect.js"></script>

<style>
    .select2-container {
        width: 90% !important;
    }
</style>

<!-- TABELAS PERMISSOES E CARGOS -->
<div class="container-fluid">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#permissoes" aria-controls="permissoes" role="tab" data-toggle="tab"><?= lang('permissoes') ?></a></li>
        <li role="presentation"><a href="#cargos" aria-controls="cargos" role="tab" data-toggle="tab"><?= lang('cargos') ?></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="permissoes">
            <div class="permissoes-alert alert" style="display:none; margin-bottom:-20px!important;">
                <button type="button" class="close" onclick="fecharMensagem('permissoes-alert')">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="msgPermissoes"></span>
            </div>
            <div class="well well-small">
                <button type="button" class="btn btn-primary" id="button_add_permissao" style="margin-bottom: 10px" data-toggle="modal" data-target="#modalCadPermissao" onclick="resetModal()"><?= lang('adicionar_permissao') ?></button>
                <br>
                <table id="tabelaPermissoes" class="table table-bordered table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th><?= lang('nome') ?></th>
                            <th><?= lang('cod_permissao') ?></th>
                            <th><?= lang('status') ?></th>
                            <th><?= lang('acoes') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="cargos">
            <div class="cargos-alert alert" style="display:none; margin-bottom:-20px!important;">
                <button type="button" class="close" onclick="fecharMensagem('cargos-alert')">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="msgCargos"></span>
            </div>
            <div class="well well-small">
                <button type="button" class="btn btn-primary" id="button_add_cargo" style="margin-bottom: 10px"><?= lang('adicionar_cargo') ?></button>
                <br>

                <table id="tabelaCargos" class="table table-bordered table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th><?= lang('nome') ?></th>
                            <th><?= lang('status') ?></th>
                            <th><?= lang('acoes') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Permissao -->
<div id="modalCadPermissao" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="tituloCadPermissao"><?= lang('cadastrar_permissao') ?></h3>
            </div>
            <div class="modal-body">
                <div class="novaPermissao-alert alert" style="display:none; margin-bottom:-20px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('novaPermissao-alert')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span id="msgNovaPermissao"></span>
                </div>
                <form class="form-horizontal" name="formPermissao" id="formPermissao">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="col-md-2 control-label"><?= lang('finalidade') ?></label>
                                    <div class="col-md-10">
                                        <label class="radio-inline"><input type="radio" name="prefixo" value="out" required checked><?= lang('outra') ?></label>
                                        <label class="radio-inline"><input type="radio" name="prefixo" value="cad" required><?= lang('cadastrar') ?></label>
                                        <label class="radio-inline"><input type="radio" name="prefixo" value="vis" required><?= lang('visualizar') ?></label>
                                        <label class="radio-inline"><input type="radio" name="prefixo" value="edi" required><?= lang('editar') ?></label>
                                        <label class="radio-inline"><input type="radio" name="prefixo" value="rem" required><?= lang('remover') ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descricao" class="col-md-2 control-label" style="text-align:left;"><?= lang('nome') ?></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="descricao" id="descricao" value="" style="width: 90%!important;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="modulo" class="col-md-2 control-label" style="text-align:left; padding-top: 15px;"><?= lang('modulo') ?></label>
                                    <div class="col-md-10" style=" padding-top: 10px;">
                                        <select class="form-control" name="modulo" id="modulo" value="" required>
                                            <option value="" selected disabled><?=lang('selecione_modulo');?></option>
                                            <?php 
                                                foreach ($permissoes as $value) {
                                                    $Nome = isset($value['lang']) ? lang($value['lang']) : $value['nomeModulo'];
                                                    $Nome = $Nome != "" ? $Nome : $value['nomeModulo'];
                                                    echo '<option value="'.$value['nomeModulo'].'">'.$Nome.'</option>';
                                                }
                                            ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnNovaPermissao"><?= lang('salvar') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Cargo -->
<div id="modalCadCargo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCargo">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="tituloNovoCargo"><?= lang('cadastrar_cargo') ?></h4>
                </div>
                <div class="novoCargo-alert alert" style="display:none; margin-bottom:-20px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('novoCargo-alert')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span id="msgNovoCargo"></span>
                </div>
                <div class="modal-body row-fluid">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cargo_nome" class="col-md-2 control-label" style="text-align:left;"><?= lang('nome') ?></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="descricao" id="descricaoCargo" onkeyup="this.value = this.value.toUpperCase();" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <span id='msgPermissoesCargo'></span>
                        </div>
                        <div class="col-md-12">
                            <div class="control-group">
                                <label class="control-label" title="Permissões"><?= lang('selecione_permissoes_para_cargo'); ?><span class="obrigatorio"></span>:</label>
                                <select id="permissoesCargo" name="permissoes[]" class="adt" multiple="multiple" required></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit_cargo" data-acao="novo" class="btn btn-submit btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
   
    var treePermissoes;

    $('#modulo').select2({});

    var tablePermissoes, page, index, linhaTabela, tableCargos, tableModuloCargo, tableModuloCargoEdit;

    treePermissoes = $("#permissoesCargo").treeMultiselect({});
    
    var tablePermissoes = $('#tabelaPermissoes').DataTable({
        ajax: {
            url: "<?= site_url('usuarios/ajaxPermissoes') ?>",
            dataType: 'json'
        },
        order: [0, 'desc'],
        paging: true,
        responsive: true,
        columnDefs: [{
            "className": "dt-center",
            "targets": "_all"
        }],
        columns: [{
                data: 'id'
            },
            {
                data: 'descricao'
            },
            {
                data: 'cod_permissao'
            },
            {
                data: 'status'
            },
            {
                data: 'acao'
            }
        ],
        language: lang.datatable,
        initComplete: function() {
            // Exibe a tabela quando o DataTable terminar de carregar e desenhar os dados
            $('#tabelaPermissoes').show();
        }
    });

    //CADASTRA UMA NOVA PERMISSAO
    $('#formPermissao').submit(function(e) {
        e.preventDefault();

        let botao = $('#btnNovaPermissao');
        let url = "<?= site_url('usuarios/addPermissao') ?>";
        var dataForm = $(this).serialize();

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: dataForm,
            beforeSend: function() {
                // Desabilita button e inicia spinner
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
            },
            success: function(callback) {
                if (callback.success) {
                    tablePermissoes.row.add(callback.dados).draw(false, null);
                    $('#msgNovaPermissao').html('<div class="alert alert-success">' + callback.msg + '</div>');

                } else {
                    $('#msgNovaPermissao').html('<div class="alert alert-danger">' + callback.msg + '</div>');
                }
            },
            error: function() {
                $('#msgNovaPermissao').html('<div class="alert alert-danger">' + lang.tente_mais_tarde + '</div>');
            },
            complete: function() {
                $('.novaPermissao-alert').css('display', 'block');
                botao.attr('disabled', false).html(lang.salvar);
            }
        })

    });

    //MUDAR O STATUS DE UMA PERMISSAO
    $(document).on('click', '.btn_status_permissao', function() {
        let botao = $(this);
        let id = botao.attr('data-id');
        let status = botao.attr('data-status');
        let btnTexto = botao.text();

        $.ajax({
            url: "<?= site_url('usuarios/ativarInativarPermissao') ?>",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                status: status
            },
            cache: false,
            beforeSend: function() {
                // Desabilita button e inicia spinner
                if (status == '0') {
                    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.inativando);
                } else {
                    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.ativando);
                }
            },
            success: function(callback) {
                if (callback.success) {
                    if (status == '0') {
                        $('.status_' + id).removeClass('label-success').addClass('label-default').html(lang.inativo);
                        $('.btn_permissao_ativo_' + id).attr('disabled', false).html(lang.ativar);
                        botao.attr('disabled', true).html(lang.inativar);
                    } else {
                        $('.status_' + id).removeClass('label-default').addClass('label-success').html(lang.ativo);
                        $('.btn_permissao_inativo_' + id).attr('disabled', false);
                        botao.attr('disabled', true).html(lang.ativar);
                    }

                } else {
                    $('#msgPermissoes').html('<div class="alert alert-danger">' + callback.msg + '</div>');
                    $('.permissoes-alert').css('display', 'block');
                    botao.attr('disabled', false).html(btnTexto);
                }
            },
            error: error => {
                $('#msgPermissoes').html('<div class="alert alert-danger">' + msg.tente_mais_tarde + '</div>');
                $('.permissoes-alert').css('display', 'block');
                botao.attr('disabled', false).html(btnTexto);
            }
        });
    });

    //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
    function resetModal() {
        $('#formPermissao')[0].reset();
        $('.novaPermissao-alert').css('display', 'none');
        $('#msgNovaPermissao').html('');
    }

    //AÇÕES REFERENTE A CADASTRO DE CARGOS

    //CARREGA A TABELA DE CARGOS
    tableCargos = $('#tabelaCargos').DataTable({
        ajax: {
            url: "<?= site_url('usuarios/ajaxCargos') ?>",
            dataType: 'json'
        },
        order: [0, 'desc'],
        paging: true,
        responsive: true,
        destroy: true,
        info: true,
        Menulenght: true,
        columnDefs: [{
            "className": "dt-center",
            "targets": "_all"
        }],
        columns: [{
                data: 'id'
            },
            {
                data: 'descricao'
            },
            {
                data: 'status'
            },
            {
                data: 'acao'
            }
        ],
        language: lang.datatable
    });

    //Abre Modal Cadastro de Cargos
    $("#button_add_cargo").click(function() {
        clearErrors();
        resetModalCadCargo();
        $("#modalCadCargo").modal();

        //CARREGA AS PERMISSOES PARA SER ADICIONADAS AO CARGO
        loadPermissoes();
    });

    //Abre Modal Edição de Cargos
    $(document).on('click', '.btn_editar_cargo', function() {
        clearErrors();
        resetModalCadCargo();
        let botao = $(this);
        let id = botao.attr("data-id");
        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.carregando);
        $('#tituloNovoCargo').text(lang.editar_cargo + ' #' + id);
        $('#submit_cargo').attr('data-id', id);
        $('#submit_cargo').attr('data-acao', 'editar');
        $('#descricaoCargo').val(botao.attr('data-descricao'));
        botao.attr('disabled', false).html(lang.editar);

        //CARREGA AS PERMISSOES PARA SER ADICIONADAS AO CARGO
        loadPermissoes(id);

        $("#modalCadCargo").modal();
    });

    function clearErrors() {
        $(".has-error").removeClass("has-error");
        $(".help-block").html("");
    }


    function resetModalCadCargo() {
        $("#formCargo")[0].reset();
        $('#msgNovoCargo').html('');
        $('.novoCargo-alert').css('display', 'none');
        $('#tituloNovoCargo').text(lang.adicionar_cargo);
        $('#submit_cargo').attr('data-acao', 'novo');
    }

    //FORMULARIO PARA CADASTRO DE CARGO
    $('#formCargo').submit(function(e) {
        e.preventDefault();

        var permisoes_cargo = $('#permissoesCargo').val();
        if (!permisoes_cargo.length) {
            $('#msgNovoCargo').html('<div class="alert alert-danger">' + lang.necessario_selecionar_permissoes + '</div>');
            $('.novoCargo-alert').css('display', 'block');

        } else {
            var botao = $('#submit_cargo');
            var acao = botao.attr('data-acao');
            var dataForm = $(this).serialize();
            var url = "<?= site_url('usuarios/addCargo') ?>";

            if (acao == 'editar') {
                var id = botao.attr('data-id');
                url = "<?= site_url('usuarios/editCargo') ?>/" + id;
            }

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: dataForm,
                beforeSend: function() {
                    // Desabilita button e inicia spinner
                    $('#submit_cargo').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);
                },
                success: function(callback) {
                    if (callback.success) {
                        if (acao == 'novo') {
                            tableCargos.row.add(callback.dados).draw(false, null);
                        } else {
                            tableCargos.ajax.reload(null, false);
                        }
                        $('#msgNovoCargo').html('<div class="alert alert-success">' + callback.msg + '</div>');
                    } else {
                        $('#msgNovoCargo').html('<div class="alert alert-danger">' + callback.msg + '</div>');
                    }
                },
                error: function() {
                    $('#msgNovoCargo').html('<div class="alert alert-danger">' + lang.tente_mais_tarde + '</div>');
                },
                complete: function() {
                    $('#submit_cargo').attr('disabled', false).html(lang.salvar);
                    $('.novoCargo-alert').css('display', 'block');
                    //vai para o topo do modal para mostrar a mensagem ao usuario
                    $('#modalCadCargo').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            })
        }

    });

    //FORMULARIO PARA EDIÇÃO DE CARGO
    $('#formEditCargo').submit(function(e) {
        e.preventDefault();
        var permissoes_edit = new Array();
        $("input[type=checkbox][name='permissoesedit[]']:checked").each(function() {
            let name_edit = $(this).attr('data-id');
            permissoes_edit.push(name_edit);
        });
        var id_cargo_edit = $("#id_cargo_edit").val();
        var cargo_nome_edit = $("#cargo_nome_edit").val();
        var url = "<?= site_url('usuarios/editCargo') ?>";
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                'id_cargo_edit': id_cargo_edit,
                'cargo_nome_edit': cargo_nome_edit,
                'permissoes': permissoes_edit
            },
            beforeSend: function() {
                // Desabilita button e inicia spinner
                $('#submit_cargo_edit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvar');
            },
            success: function(callback) {
                if (callback.success) {
                    $('#msgEditCargo').html('<div class="alert alert-success">' + callback.msg + '</div>');
                    tableCargos.ajax.reload();
                    tableModuloCargoEdit.ajax.reload();
                    $("#formEditCargo")[0].reset();
                    $("#formCargo")[0].reset();
                    $('.editCargo-alert').css('display', 'block');
                    $('#submit_cargo_edit').attr('disabled', false).html(lang.salvar);
                    $('#modalEditCargo').modal("hide");
                } else {
                    $('#msgEditCargo').html('<div class="alert alert-danger">' + callback.msg + '</div>');
                }
            }
        })
        return false;
    });

    //MUDAR O STATUS DE UM CARGO
    $(document).on('click', '.btn_status_cargo', function() {
        let botao = $(this);
        let id = botao.attr('data-id');
        let status = botao.attr('data-status');
        let btnTexto = botao.text();

        $.ajax({
            url: "<?= site_url('usuarios/ativarInativarCargo') ?>",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                status: status
            },
            cache: false,
            beforeSend: function() {
                // Desabilita button e inicia spinner
                if (status == '0') {
                    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.inativando);
                } else {
                    botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.ativando);
                }
            },
            success: function(callback) {
                if (callback.success) {
                    if (status == '0') {
                        $('.status_' + id).removeClass('label-success').addClass('label-default').html(lang.inativo);
                        $('.btn_cargo_ativo_' + id).attr('disabled', false).addClass('btn_status_cargo').removeClass('btn_editar_cargo').html(lang.ativar);
                        botao.attr('disabled', true).html(lang.inativar);
                    } else {
                        $('.status_' + id).removeClass('label-default').addClass('label-success').html(lang.ativo);
                        $('.btn_cargo_inativo_' + id).attr('disabled', false);
                        botao.attr('disabled', false).addClass('btn_editar_cargo').removeClass('btn_status_cargo').html(lang.editar);
                    }

                } else {
                    $('#msgCargos').html('<div class="alert alert-danger">' + callback.msg + '</div>');
                    $('.cargos-alert').css('display', 'block');
                    botao.attr('disabled', false).html(btnTexto);
                }
            },
            error: error => {
                $('#msgCargos').html('<div class="alert alert-danger">' + msg.tente_mais_tarde + '</div>');
                $('.cargos-alert').css('display', 'block');
                botao.attr('disabled', false).html(btnTexto);
            }
        });
    });


    $(document).on('change', '.checked_all_permissoes', function() {
        let checked = false;
        let form = $(this).data('form');

        if ($(this).prop('checked'))
            checked = true;

        $('form#formCargo input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', checked);
        });
    });


    $(document).on('change', '.checked_all_permissoes_edit', function() {
        let checked = false;
        let form = $(this).data('form');

        if ($(this).prop('checked'))
            checked = true;

        $('form#formEditCargo input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', checked);
        });
    });

    function loadPermissoes(id_cargo = false) {
        $('#permissoesCargo').val('');
        var url = '<?= site_url('usuarios/listaTodasPermissoes'); ?>';
        if (id_cargo) url = '<?= site_url('usuarios/listaPermissoesCargo'); ?>/' + id_cargo;
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $('#msgPermissoesCargo').html(' <i class="fa fa-spinner fa-spin"></i> ' + lang.carregando);
            },
            success: function(callback) {
                if (callback.success) {
                    $('#permissoesCargo').empty();
                    $.each(callback.permissoes, function(i, option) {
                        $("#permissoesCargo").append(option);
                    });

                    treePermissoes[0].remove();

                    treePermissoes = $("#permissoesCargo").treeMultiselect({
                        searchable: true,
                        startCollapsed: true
                    });

                    $('#msgPermissoesCargo').html('');
                } else {
                    treePermissoes[0].remove();
                
                    $('#msgPermissoesCargo').html('<span class="label label-danger">' + lang.operacao_nao_finalizada + '</span>');
                }
            }
        })
    }
</script>