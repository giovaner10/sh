<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Contas a Pagar PneuShow", site_url('Homes'), "Departamentos", "Financeiro > Contas > PneuShow");
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px; width: 100%;">

            <form id="formBusca" style="width: 100%;">

                <div class="form-group filtro">

                    <div class="input-container">


                        <?php if ($this->auth->is_allowed_block('valores')) { ?>

                            <div style="display: flex; flex-direction:column; width:100%;">

                                <h4 style="margin-bottom: 0px !important;">Informações:</h4>

                                <div class="card" id="card-orange" style="  height: 55px;">
                                    <strong style="display: inline-block; ">A pagar: <br>R$ <?= number_format($estatisticas->pagar, 2, ',', '.') ?></strong>
                                </div>

                                <div class="card" id="card-green" style="  height: 55px;">
                                    <strong style="display: inline-block; ">Pagos: <br>R$ <?= number_format($estatisticas->pago, 2, ',', '.') ?></strong>
                                </div>

                                <div class="card" id="card-blue" style="  height: 55px;">
                                    <strong style="display: inline-block; ">Entradas: <br>R$ <?= number_format($estatisticas->caixa, 2, ',', '.') ?></strong>
                                </div>

                                <div id="card-red" class="card <?= $estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo' ?>" style="  height: 55px;">
                                    <?php if ($estatisticas->saldo >= 0) : ?>
                                    <?php else : ?>
                                    <?php endif ?>
                                    <strong style="display: inline-block; ">Saldo: <br>R$ <?= number_format($estatisticas->saldo, 2, ',', '.') ?></strong>
                                </div>
                            </div>

                        <?php } ?>
                    </div>

                    <h4 style="margin-bottom: 0px !important;">Ações:</h4>

                    <div class="button-container">
                        <a href="#myModal" data-toggle="modal" data-target="#myModal" class="btn btn-primary" style='width:100%; margin-top: 5px'><i class="fa fa-plus"></i> Add Conta</a>
                        <a class="btn btn-primary" style='width:100%; margin-top: 5px' data-toggle="modal" data-target="#myModalEntrada" href="#myModalEntrada"><i class="fa fa-plus"></i> Add Entrada</a>
                        <a class="btn btn-primary" style='width:100%; margin-top: 5px' data-toggle="modal" data-target="#view-entrada" href="<?= site_url('contas/lista_entradas') ?>"><i class="fa fa-reorder"></i> Ver Entradas</a>
                    </div>

                    <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

                    <div class="input-container" style="display: flex; flex-direction:column; align-items: start; justify-content: flex-start;">
                        <label for="filtrar-atributos">Fornecedor, Id ou Responsável</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="Fornecedor, Id ou Responsável" id="filtrar-atributos" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style="width:100%" id="BtnPesquisar" type="button"><i class="fa fa-search"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card">Contas PneuShow: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value=10 selected>10</option>
                    <option value=25>25</option>
                    <option value=50>50</option>
                    <option value=100>100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageCadastroClientes" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageTecnologias" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>

                <div class="wrapperContatos">
                    <div id="tableContatos" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/remessas/pneushow', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    var BaseURL = '<?= base_url('') ?>';
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url('media') ?>/js/jquery-mask.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets') ?>/plugins/priceformat/jquery.price_format.1.8.min.js"></script>


<style>
    .dropdown-menu {
        min-width: 180px !important;
    }
    .custom-input {
        border-radius: 5px;
        padding: 5px;
        border-left: 3px solid #03A9F4;
        padding-left: 10px;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
        margin-top: 3rem !important;
    }

    .bord {
        border-left: 3px solid #03A9F4;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
    }

    .card {
        display: flex;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
    }
</style>

<div class="alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span></span>
</div>

<?php if ($msg) : ?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>CONCLUIDO!</strong>
        <?php echo $msg ?>
    </div>
<?php endif; ?>


<div id="myModal_update" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Pagamento</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/update/') ?>' name="" id="form_update">
                <div class="modal-body" style="display: grid;">
                    <input type="hidden" name="id" id="idid" value="">
                    <label>Senha</label>
                    <input type="password" class="span3" name="senha_pagamento" id="senha_pagamento" value="" required>
                    <label>Valor Pago</label>
                    <input type="text" class="span3 money2" name="valor_pago" id="money" value="" required>
                    <label>Data Pagamento</label>
                    <input type="text" class="span3 date" name="data_pagamento" id="data" placeholder="__/__/___" required>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-primary" id="update">Pagar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_editar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id="form_editar">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleModalEditar">Editar Conta</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="fornecedor" class="control-label">Fornecedor: </label>
                                <input type="hidden" name="id" id="fornecedor_id">
                                <input type="text" maxlength="65" class="form-control" name="fornecedor" id="fornecedorEdit" placeholder="Digite o fornecedor" required>
                            </div>
                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label class="control-label">Categoria: </label>
                                <input type="text" maxlength="45" class="form-control" name="categoria" id="categoria" placeholder="Digite a categoria" required>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="valor" class="control-label">Valor: </label>
                                <input type="text" maxlength="13" class="form-control money2" id="valorEdit" name="valor" placeholder="0,00" required>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="dt_vencimento" class="control-label">Vencimento: </label>
                                <input type="date" class="form-control" name="dt_vencimento" id="dt_vencimento" required>
                            </div>
                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="descricao-form" class="control-label">Descrição: </label>
                                <input type="text" class="form-control" name="descricao-form" id="descricao-form" placeholder="Digite a descrição" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='editar'>Editar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_cancel" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Cancelamento</h3>
        </div>
        <div class="modal-content" style="padding-top: 10px; border:none; box-shadow: none;">
            <form method="post" action='<?php echo site_url('contas/update/') ?>' id="form_cancel">
                <div class="modal-body">
                    <label>Tem certeza que deseja cancelar?</label>
                    <input type="hidden" name="id" id="cancel-id">
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button type="submit" class="btn btn-primary" id="cancelar">Sim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="view-entrada" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Entradas do Mês</h3>
        </div>
        <div class="modal-content" style="padding-top: 10px; border:none; box-shadow: none;">
            <div class="modal-body">
                carregando entradas...
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
        </div>
    </div>
</div>

<div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-content" style="border:none;">
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3 style="text-align: center;">Adicionar Conta</h3>
        </div>
        <div class="modal-content" style="border:none;">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <form method="post" action='<?php echo site_url('contas/addPneushow') ?>'>
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Fornecedor</label>
                                <input type="text" class="form-control" name="fornecedor" id="fornecedor" data-provide="typeahead" data-source='<?php echo $fornecedores ?>' data-items="6" placeholder="Fornecedor" autocomplete="off" value="<?php echo $this->input->post('fornecedor') ?>" required>
                            </div>
                            <div class="form-group col-md-6" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Valor</label>
                                <input type="text" class="form-control money2" name="valor" id="valor" placeholder="0,00" required>
                            </div>
                            <div class="form-group col-md-6" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Data Vencimento</label>
                                <input type="date" class="form-control" name="data_vencimento" id="data_vencimento" autocomplete="off" value="" required>
                            </div>
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Descrição</label>
                                <input class="form-control" name="descricao" id="descricao" placeholder="Descrição" required />
                            </div>
                            <br>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModalEntrada" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3 style="text-align: center">Adicionar Entrada</h3>
        </div>
        <div class="modal-content" style="border: none;">
            <div class="modal-body">
                <form method="post" onsubmit="event.preventDefault();" name="form" id="form">
                    <div class="form-group col-md-6" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px;">
                        <label class="control-label">Data Entrada</label>
                        <input type="date" class="form-control" id="dataPneu" name="data" required>
                    </div>
                    <div class="form-group col-md-6" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px;">
                        <label class="control-label">Valor</label>
                        <input type="text" class="span3 money2 form-control" id="valorPneu" name="valor" placeholder="0.00" required>
                    </div>
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px;">
                        <label class="control-label">Descrição</label>
                        <input type="text" class="span3 form-control" name="descricao" id="descricaoPneu" placeholder="Descrição" required>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                        <button type="submit" class="btn btn-primary" id="add-entrada">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(async function() {
        $("#someSwitchOptionPrimary").click(function() {
            if ($("#someSwitchOptionPrimary").is(':checked')) {
                table.ajax.url("<?= site_url('contas/contas_por_inst') ?>").load();
            } else {
                table.ajax.url("<?= site_url('contas/contas_ajax/2') ?>").load();
            }
        });

        $(document).ready(function() {
            $("#myModal form").submit(function(event) {
                event.preventDefault();

                var form = $(this);

                $.ajax({
                    url: form.attr("action"),
                    method: form.attr("method"),
                    data: form.serialize(),
                    success: function(response) {
                        console.log(response)
                        alert("Conta adicionada com sucesso!");
                        $("#myModal").modal("hide");
                        $("#fornecedor, #descricao, #valor, #data_vencimento").val("");

                    },
                    error: function(xhr, status, error) {
                        alert("Erro ao adicionar conta: " + error);
                    }
                });
            });
        });

        $('#myModal').on('hidden.bs.modal', function() {
            $("#fornecedor, #descricao, #valor, #data_vencimento").val("");
        });

        $('#update').on('click', function(e) {
            $('#money').focus();
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_update').attr('action');
            $.post(url, form, function(data) {
                if (data == "4") {
                    alert('Senha incorreta!');
                } else if (data == "3") {
                    alert('Usuário sem permissão!');
                } else {
                    alert('Pagamento efetuado com sucesso!');
                    table.ajax.reload(null, false);
                }

                $('#myModal_update').modal('hide');
            });
        });

        $('#cancelar').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_cancel').attr('action');
            $.post(url, form, function(data) {
                if (data == 1) {
                    table.ajax.reload(null, false);
                } else {
                    $(".alert").find('span').html('').html('Não foi possível cancelar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        async function atualizarDadosAgGrid() {
            AgGrid.gridOptions.api.setRowData([]);
            $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
            await $.ajax({
                url: "<?= site_url('contas/contas_ajax_nv/2') ?>",
                type: 'GET',
                success: function(response) {
                    result = JSON.parse(response);
                    AgGrid.gridOptions.api.setRowData(result);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao fazer a requisição:', error);
                    $('#result').text('Erro ao fazer a requisição.');
                }
            });
        }

        $('#form_editar').submit(function(e) {
            e.preventDefault();
            var form = $(this).serialize();
            var url = '<?php echo site_url('contas/update') ?>';
            $('#editar').html('<i class="fa fa-spinner fa-spin"></i> Editando...').attr('disabled', true);
            $.post(url, form)
                .done(function(data) {
                    if (data) {
                        $('#myModal_editar').modal('hide');
                        showAlert('success', 'Conta editada com sucesso!');
                        atualizarDadosAgGrid();
                    } else {
                        showAlert('error', 'Não foi possível editar a conta. Tente novamente!');
                    }
                    $('#editar').html('Editar').attr('disabled', false);
                })
                .fail(function() {
                    $('#editar').html('Editar').attr('disabled', false);
                    showAlert('error', 'Não foi possível editar a conta. Tente novamente!');
                });
        });

        $('.date').mask('99/99/9999', {
            clearIfNotMatch: true
        });

        $('#myModal_digitalizar').on('hidden', function() {
            $(this).data('modal', null);
        });

    });

    function update(param) {
        $('#money').focus();
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        var valor = $(param).data('valor');
        var now = new Date();
        $('#idid').val(id);
        $('#money').val(valor);
        $.ajax({
            type: "post",
            data: {
                id: id
            },
            url: controller,
            dataType: "json",
            success: function(data) {
                if (data.updated != 1 || data.status == 0) {
                    $('#id').val(data.id);
                    $('#money').val(data.valor);
                    $('#data').val(now.getDate() + '/' + (now.getMonth() + 1) + '/' + now.getFullYear());
                    $("#myModal_update").modal('show');
                }
            }
        });
    }

    function cancel(param) {
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        if (!$(param).hasClass('disabled')) {
            $('#cancel-id').val(id);
            $("#myModal_cancel").modal('show');
        } else {
            $("#myModal_cancel").modal('hide');
        }
    }

    function edit(param) {
        $('#fornecedorEdit').val($(param).data('fornecedor'));
        $('#categoria').val($(param).data('categoria'));
        $('#valorEdit').val($(param).data('valor'));
        $('#dt_vencimento').val($(param).data('vencimento'));
        $('#descricao-form').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));
    }

    $('#add-entrada').click(function() {
        var dataPneu = $('#dataPneu').val();
        var valorPneu = $('#valorPneu').val();
        var descricaoPneu = $('#descricaoPneu').val();

        if (!dataPneu || !valorPneu || !descricaoPneu) {
            alert('Preencha todos os campos antes de adicionar uma entrada.');
            return;
        }

        $.ajax({
            url: 'contas/add_entrada',
            type: 'POST',
            data: {
                'data': dataPneu,
                'valor': valorPneu,
                'descricao': descricaoPneu
            },
            success: function() {
                alert('Entrada cadastrada com sucesso');
                $("#myModalEntrada").modal("hide");
                $("#dataPneu, #valorPneu, #descricaoPneu, #data_vencimento").val("");

            },
            error: function(xhr, status, error) {
                alert('Erro ao cadastrar entrada. Tente novamente mais tarde.');
            }
        });
    });

    $('#myModalEntrada').on('hidden.bs.modal', function() {
        $("#dataPneu, #valorPneu, #descricaoPneu").val("");
    });

    $(document).ready(function() {

        $('.data').mask('99/99/9999');
        $('.tel').mask('(99) 9999-9999');
        $('.hora').mask('99:99:99');
        $('.cep').mask('99999-999');
        $('.cpf').mask('999.999.999-99');
        $('.placa').mask('aaa9999');
        $('.mes_ano').mask('99/9999');
        $('.money').mask('000000000.00');
        $('.money2').maskMoney({
            symbol: 'R$ ',
            showSymbol: true,
            thousands: '.',
            decimal: ',',
            symbolStay: true
        });
        $("#ajax").css('display', 'none');

        $(document).on('focus', '.ref', function() {
            $('.ref').mask('00/0000')
        });

        $('.money').priceFormat({
            limit: 5,
            centsLimit: 3
        });

    });


    var AgGrid;
    $(document).ready(async function() {
        ///// adicao de novas funcoes para ag grid
        var result = [];
        async function buscarDadosAgGrid() {
            await $.ajax({
                url: "<?= site_url('contas/contas_ajax_nv/2') ?>",
                type: 'GET',
                success: function(response) {
                    result = JSON.parse(response);
                    updateData(result)
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao fazer a requisição:', error);
                    $('#result').text('Erro ao fazer a requisição.');
                }
            });
        }

        const gridOptions = {
            columnDefs: [{
                    headerName: "ID",
                    field: "id"
                },
                {
                    headerName: "Fornecedor",
                    field: "fornecedor",
                    valueFormatter: function(params) {
                        return params.value.toUpperCase();
                    }
                },
                {
                    headerName: "Descrição",
                    field: "descricao"
                },
                {
                    headerName: "Categoria",
                    field: "categoria"
                },
                {
                    headerName: "Responsável",
                    field: "responsavel",
                    valueFormatter: function(params) {
                        return params.value.toUpperCase();
                    }
                },
                {
                    headerName: "Data de Lançamento",
                    field: "dh_lancamento"
                },
                {
                    headerName: "Data de Vencimento",
                    field: "data_vencimento"
                },
                {
                    headerName: "Valor",
                    field: "valor_formatado"
                },
                {
                    headerName: "Status da Fatura",
                    field: "status_fatura",
                    cellRenderer: function(options) {
                        return options.data.status_fatura;
                    }
                },
                {
                    headerName: "Pagamento",
                    field: "pagamento"
                },
                {
                    headerName: 'Ações',
                    width: 80,
                    pinned: 'right',
                    cellClass: "actions-button-cell",
                    suppressMenu: true,
                    sortable: false,
                    filter: false,
                    resizable: false,
                    cellRenderer: function(options) {
                        let data = options.data;

                        let tableId = "tableAnexos";
                        let dropdownId = "dropdown-menu" + data.id;
                        let buttonId = "dropdownMenuButton_" + data.id;
                        let i = options.rowIndex;

                        if (i > 9) {
                            i = 9;
                        }

                        return `
                            <div class="dropdown dropdown-table">
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(90% - ${i * 21}px);" aria-labelledby="${buttonId}">
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoEditar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoServicoPrestado}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoDigitalizar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoExcluir}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaPagar}
                                    </div>
                                    <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                                        ${data.botaoComprov}
                                    </div>
                                </div>
                            </div>`;
                    },
                },
            ],
            rowData: [],
            pagination: true,
            defaultColDef: {
                resizable: true,
            },
            sideBar: {
                toolPanels: [{
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100,
                    },
                }, ],
                defaultToolPanel: false,
            },
            paginationPageSize: $("#select-quantidade-por-contatos-corporativos").val(),
            localeText: AG_GRID_LOCALE_PT_BR,
        };
        var gridDiv = document.querySelector("#tableContatos");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        preencherExportacoes(gridOptions);


        function updateData(newData) {
            gridOptions.api.setRowData(newData);
        }

        var resultado = await buscarDadosAgGrid();

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
        });

        $(".btn-expandir").on("click", function(e) {
            e.preventDefault();
            menuAberto = !menuAberto;

            if (menuAberto) {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-show.svg`
                );
                $("#filtroBusca").hide();
                $(".menu-interno").hide();
                $("#conteudo").removeClass("col-md-9");
                $("#conteudo").addClass("col-md-12");
            } else {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-hide.svg`
                );
                $("#filtroBusca").show();
                $(".menu-interno").show();
                $("#conteudo").css("margin-left", "0px");
                $("#conteudo").removeClass("col-md-12");
                $("#conteudo").addClass("col-md-9");
            }
        });

        $("#filtrar-atributos").on("keyup", function() {
            const inputValue = $(this).val().trim().toLowerCase();

            const filteredResult = result.filter(
                (item) =>
                item.id.toLowerCase().includes(inputValue) ||
                item.fornecedor.toLowerCase().includes(inputValue) ||
                item.responsavel.toLowerCase().includes(inputValue)
            );

            updateData(filteredResult);
        });

        $("#BtnLimparFiltro").on("click", function() {
            $("#filtrar-atributos").val("");
            updateData(result);
        });

    });
    function fecharDrop(){
        $('#opcoes_exportacao').hide();
    }
</script>