<style>
    html {
        scroll-behavior: smooth
    }

    body {
        background-color: #fff !important;
    }

    table {
        width: 100% !important;
    }

    .blem {
        color: red;
    }

    .container-fluid {
        padding: 0;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th,
    td.wordWrap {
        text-align: center;
    }

    table.dataTable tbody tr td {
        word-wrap: break-word !important;
        word-break: break-all !important;
    }

    .checkbox label {
        font-weight: 700;
    }

    .select-container .select-selection--single {
        height: 35px !important;
    }

    .my-1 {
        margin-top: 1em !important;
        margin-bottom: 1em !important;
    }

    .mx-1 {
        margin-left: 1em;
        margin-right: 1em;
    }

    .pt-1 {
        padding-top: 1em;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .justify-content-end {
        justify-content: flex-end;
    }

    .align-center {
        align-items: center;
    }

    .modal-xl {
        max-width: 1300px;
        width: 100%;
    }

    .border-0 {
        border: none !important;
    }

    .markerLabel {
        background-color: #fff;
        border-radius: 4px;
        padding: 4px;
    }

    .action-bar * {
        margin-left: 5px;
    }

    .select-selection--multiple .select-search__field {
        width: 100% !important;
    }
</style>

<h3><?= lang("linhas") ?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?= site_url('Homes') ?>">Home</a> >
    <?= lang('telecom') ?> >
    <?= lang('linhas') ?>
</div>

<div class="col-md-12 alert alert-info">
    <div class="col-md-12">
        <p>Informe a operadora e a linha ou o ccid!</p>
        <span class="help help-block"></span>
        <form action="" id="formPesquisa">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Operadora: </label>
                    <select class="form-control input-sm" id="pesqOperadora" name="nome" type="text" style="width: 100%;">
                        <option value="0" selected disabled>Buscando...</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label>Buscar por: </label>
                    <select class="form-control input-sm" id="pesqFiltro" type="text" style="width: 100%;">
                        <option value="linha" selected>Linha</option>
                        <option value="ccid">Último CCID</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label id="labelLinha">Linha:</label>
                    <label id="labelCcid" hidden>CCID:</label>
                    <input class="form-control input-sm" id="pesqFiltroInputLinha" name="nome" type="text" style="width: 100%" placeholder="Digite a linha">
                    <input class="form-control input-sm" id="pesqFiltroInputCcid" name="nome" type="text" style="width: 100%; display: none;" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Digite o ccid">
                </div>
                <div class="col-md-4 form-group">
                    <button class="btn btn-primary" id="pesquisaOperadora" type="button" style="margin-top: 22px; margin-right: 15px;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                    <button class="btn btn-primary" id="limpaOperadora" type="button" style="margin-top: 22px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="col-md-12" style="padding-left: 0px;">
    <a id="abrirModalInserirLinha" class="btn btn-primary"><?= lang("nova_linha") ?></a>
</div>

<div class="container-fluid my-1">
    <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
        <div id="linhas" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeralLinhas" style="max-width: 100%;">
                <table class="table-responsive table-bordered table table-hover table-striped" id="tabelaLinhas" style="width: 100%; max-width: 100%;">
                    <thead>
                        <tr class="tableheader">
                            <th hidden>Id</th>
                            <th hidden>Id Operadora</th>
                            <th hidden>Id Empresa</th>
                            <th hidden>Id Fornecedor</th>
                            <th>Linha</th>
                            <th>Ultimo Ccid</th>
                            <th>Status da Linha</th>
                            <th>Número da Conta</th>
                            <th>Último Serial do Equipamento</th>
                            <th>Último Status Crm</th>
                            <th>Última Comunicação Radius</th>
                            <th>Data do Cadastro</th>
                            <th>Data da Atualização</th>
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

<!-- Modal Inserir Linha -->
<div id="modalCadLinha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formCadLinha">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?= lang("nova_linha") ?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Empresa:</label>
                                            <select class="form-control input-sm" id="pesqCadEmpresa" name="empresa" style="width: 100%;" required></select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Linha:</label>
                                            <input type="text" class="form-control input-sm" id="nLinha" placeholder="Número da linha" style="height: 28px" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Ccid:</label>
                                            <input type="text" class="form-control input-sm" id="ccidLinha" style="height: 28px" placeholder="Digite o Ccid" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="20" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Operadora:</label>
                                            <select class="form-control input-sm" id="pesqCadOperadora" name="operadora" style="height: 28px; width: 100%;" required>
                                                <option value="0" selected disabled>Buscando...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Fornecedor:</label>
                                            <select class="form-control input-sm" id="pesqCadFornecedor" name="fornecedor" style="width: 100%;" required></select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Número da Conta:</label>
                                            <input type="number" class="form-control input-sm" id="numeroLinha" placeholder="Digite o número da conta" style="height: 28px" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Serial do Equipamento:</label>
                                            <input type="text" class="form-control input-sm" id="serialEquipamentoLinha" placeholder="Digite o serial do equipamento" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Status da Linha:</label>
                                            <select type="text" class="form-control input-sm" id="statusOperadora" style="width: 100%;" required>
                                                <option selected value="0">Cadastro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarCadastroLinha" style="margin-right: 10px">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Linha -->
<div id="modalEditarLinha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEditLinha">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?= lang("editar_linha") ?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" hidden>
                                            <label class="control-label">Id:</label>
                                            <input type="text" class="form-control" id="IdEdit" placeholder="Número da linha">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Empresa:</label>
                                            <select class="form-control input-sm" id="pesqCadEmpresaEdit" name="empresa" style="width: 100%;" required></select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Linha:</label>
                                            <input type="text" class="form-control input-sm" id="nLinhaEdit" placeholder="Número da linha" style="height: 28px;" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Ccid:</label>
                                            <input type="text" class="form-control input-sm" id="ccidLinhaEdit" placeholder="Digite o Ccid" style="height: 28px" placeholder="Digite o Ccid" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="20" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Operadora:</label>
                                            <select class="form-control input-sm" id="pesqCadOperadoraEdit" name="operadora" style="height: 28px; width: 100%;" required>
                                                <option value="0" selected disabled>Buscando...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Fornecedor:</label>
                                            <select class="form-control input-sm" id="pesqCadFornecedorEdit" name="fornecedor" style="width: 100%;" required></select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Número da Conta:</label>
                                            <input type="number" class="form-control input-sm" id="numeroLinhaEdit" placeholder="Digite o número da conta" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Serial do Equipamento:</label>
                                            <input type="text" class="form-control input-sm" id="serialEquipamentoLinhaEdit" placeholder="Digite o serial do equipamento" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Status da Linha:</label>
                                            <select type="text" class="form-control input-sm" id="statusOperadoraEdit" style="width: 100%;" required>
                                                <option selected disabled value="5">Selecione</option>
                                                <option value="0">Cadastro</option>
                                                <option value="1">Suspensão</option>
                                                <option value="2">Cancelamento</option>
                                                <option value="3">Vincular Chip</option>
                                                <option value="4">Vincular Equipamento</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Status</label>
                                            <select type="text" class="form-control input-sm" id="statusLinhaEdit">
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarEditLinha" style="margin-right: 10px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Histórico da Linha -->
<div id="modalHistoricoLinha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formHistoricoLinha">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?= lang("historico_linha") ?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="container-fluid" id="divTabelaHistoricoLinha">
                                        <table class="table-responsive table-bordered table" id="tabelaHistoricoLinha" style="width: 100%;">
                                            <thead>
                                                <tr class="tableheader">
                                                    <th>Ação</th>
                                                    <th>Data do Registro</th>
                                                    <th>Início da Ação</th>
                                                    <th>Fim da Ação</th>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Adicionar Histórico da Linha -->
<div id="modalAddHistoricoLinha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formAddHistoricoLinha">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?= lang("add_historico_linha") ?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" hidden>
                                            <label class="control-label">Id Linha</label>
                                            <input type="text" class="form-control" id="idLinhaAddHistorico">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Data Ínicio:</label>
                                            <input type="date" class="form-control input-sm" id="dataInicio" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Ação:</label>
                                            <select type="text" class="form-control input-sm" id="acaoLinha" style="width: 100%;" required>
                                                <option selected disabled value="5">Selecione</option>
                                                <option value="0">Cadastro</option>
                                                <option value="1">Suspensão</option>
                                                <option value="2">Cancelamento</option>
                                                <option value="3">Vincular Chip</option>
                                                <option value="4">Vincular Equipamento</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarAddHistorico" style="margin-right: 10px;">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Histórico da Linha -->
<div id="modalEditarHistoricoLinha" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditarHistoricoLinha">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?= lang("editar_historico_linha") ?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Ação:</label>
                                            <select type="text" class="form-control input-sm" id="acaoLinhaEdit" style="width: 100%;" required>
                                                <option selected disabled value="5">Selecione</option>
                                                <option value="0">Cadastro</option>
                                                <option value="1">Suspensão</option>
                                                <option value="2">Cancelamento</option>
                                                <option value="3">Vincular Chip</option>
                                                <option value="4">Vincular Equipamento</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Data Ínicio:</label>
                                            <input type="date" class="form-control" id="dataInicioEdit" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group" hidden>
                                            <label class="control-label">Id Linha</label>
                                            <input type="text" class="form-control" id="idLinhaEditHistorico">
                                        </div>
                                        <div class="col-md-6 form-group" hidden>
                                            <label class="control-label">IdHistorico</label>
                                            <input type="text" class="form-control" id="idHistoricoEdit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarEditHistorico" style="margin-right: 10px;">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Resetar Chip -->
<div id="modalResetarChip" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formResetarChip">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Resetar Linha</h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_resetar_chip">
                                    <div class="row">
                                        <div class="col-md-12 form-group" hidden>
                                            <input type="text" class="form-control" id="idOperadoraResetarChip">
                                            <input type="text" class="form-control" id="linhaResetarChip">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label class="control-label">Confirmação:</label>
                                            <p style="margin-top: 5px;">Tem certeza de que deseja resetar a linha? Esta ação não pode ser desfeita.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    <a class="btn btn-primary" id="btnSalvarResetarChip" style="margin-right: 10px;" onclick="resetarChip(this)">Resetar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    // url do controller painel_omnilink
    const URL_PAINEL_OMNILINK = '<?= site_url("PaineisOmnilink") ?>';

    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-br-pre": function(a) {
            var brDatea = a.split('/');
            return (brDatea[2] + brDatea[1] + brDatea[0]) * 1;
        },
        "date-br-asc": function(a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
        "date-br-desc": function(a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });

    var tabelaLinhas = $('#tabelaLinhas').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        autoWidth: false,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhuma linha a ser listada",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords: "Nenhum resultado encontrado.",
            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próxima",
                previous: "Anterior"
            },
        },
        ajax: {
            url: '<?= site_url('GestaoDeChips/linhas/listarLinhasRecentes') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    tabelaLinhas.clear().draw();
                    tabelaLinhas.rows.add(data.results).draw();
                    tabelaLinhas.columns.adjust().draw();
                } else {
                    tabelaLinhas.clear().draw();
                }
            },
            error: function() {
                alert('Erro ao buscar linhas. Tente novamente!');
                tabelaLinhas.clear().draw();
            }
        },
        deferRender: true,
        lengthChange: false,
        order: [12, 'desc'],
        columns: [{
                data: 'id',
                visible: false
            },
            {
                data: 'idOperadora',
                visible: false
            },
            {
                data: 'idEmpresa',
                visible: false
            },
            {
                data: 'idFornecedor',
                visible: false
            },
            {
                data: 'linha',
                render: function(data) {
                    if (data.length == 13) {
                        return $('<span>').text(data).mask('+00 (00) 00000-0000').html();
                    } else if (data.length == 12) {
                        return $('<span>').text(data).mask('+00 (00) 0000-0000').html();
                    } else {
                        return data;
                    }
                },
                width: "12%",
            },
            {
                data: 'ultimoCcid',
                orderable: false,
                width: "14%"
            },
            {
                data: 'statusOperadora',
                orderable: false,
                width: "5%"
            },
            {
                data: 'numeroConta',
                orderable: false,
                width: "8%"
            },
            {
                data: 'ultimoSerialEquipamento',
                orderable: false,
                width: "8%"
            },
            {
                data: 'ultimoStatusCrm',
                orderable: false,
                width: "8%"
            },
            {
                data: 'ultimaComunicacaoRadius',
                orderable: false,
                width: "8%",
                type: "date-br",
                render: function(data) {
                    if (data != null) {
                        return new Date(data).toLocaleDateString();
                    } else {
                        return '-'
                    }
                }
            },
            {
                data: 'dataCadastro',
                orderable: false,
                width: "8%",
                type: "date-br",
                render: function(data) {
                    if (data != null) {
                        return new Date(data).toLocaleDateString();
                    } else {
                        return '-'
                    }
                }
            },
            {
                data: 'dataUpdate',
                orderable: false,
                width: "8%",
                type: "date-br",
                render: function(data) {
                    if (data != null) {
                        return new Date(data).toLocaleDateString();
                    } else {
                        return '-'
                    }
                }
            },
            {
                data: 'status',
                orderable: false,
                width: "6%"
            },
            {
                data: {
                    'id': 'id',
                    'linha': 'linha',
                    'status': 'status',
                    'ultimoCcid': 'ultimoCcid',
                    'idOperadora': 'idOperadora',
                    'idEmpresa': 'idEmpresa',
                    'idFornecedor': 'idFornecedor',
                    'statusOperadora': 'statusOperadora',
                    'numeroConta': 'numeroConta',
                    'ultimoSerialEquipamento': 'ultimoSerialEquipamento',
                    'ultimoStatusCrm': 'ultimoStatusCrm'
                },
                orderable: false,
                width: "20%",
                render: function(data) {
                    if (data['idOperadora'] == 3) {
                        return `
                        <div style="display: flex">
                            <button 
                                class="btn btn-primary"
                                title="Editar Linha"
                                id="btnEditarLinha"
                                onClick="javascript:editLinha(this,'${data['id']}', '${data['linha']}', '${data['status']}', '${data['ultimoCcid']}',
                                                                        '${data['idOperadora']}', '${data['idEmpresa']}', '${data['idFornecedor']}', '${data['statusOperadora']}', '${data['numeroConta']}', '${data['ultimoSerialEquipamento']}', '${data['ultimoStatusCrm']}')">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            <button
                                id="btnAlterarStatusLinha"
                                class="btn btn-primary"
                                title="Alterar Status"
                                style="margin-left: 5px"
                                onClick="javascript:alterarStatusLinha(this,'${data['id']}', '${data['status']}')">
                                <i class="fa fa-exchange" aria-hidden="true"></i>
                            </button>
                            <button
                                id="btnHistoricoLinha"
                                class="btn btn-primary"
                                title="Histórico da Linha"
                                style="margin-left: 5px"
                                onClick="javascript:historicoLinha(this,'${data['id']}')">
                                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                            </button>
                            <button
                                class="btn btn-primary"
                                title="Adicionar Histórico da Linha"
                                id="btnHistoricoLinha"
                                style="margin-left: 5px"
                                onClick="javascript:addHistoricoLinha(this,'${data['id']}')">
                                <i class="fa fa-history" aria-hidden="true"></i>
                            </button>
                            <button
                                class="btn btn-primary"
                                title="Resetar Linha"
                                id="btnResetarChip"
                                style="margin-left: 5px"
                                onClick="javascript:modalResetarChip(this, '${data['linha']}', '${data['idOperadora']}')" disabled>
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </button>
                        </div>
                        `;
                    } else {
                        return ` 
                        <div style="display: flex">
                            <button 
                                class="btn btn-primary"
                                title="Editar Linha"
                                id="btnEditarLinha"
                                onClick="javascript:editLinha(this,'${data['id']}', '${data['linha']}', '${data['status']}', '${data['ultimoCcid']}',
                                                                        '${data['idOperadora']}', '${data['idEmpresa']}', '${data['idFornecedor']}', '${data['statusOperadora']}', '${data['numeroConta']}', '${data['ultimoSerialEquipamento']}', '${data['ultimoStatusCrm']}')">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            <button
                                id="btnAlterarStatusLinha"
                                class="btn btn-primary"
                                title="Alterar Status"
                                style="margin-left: 5px"
                                onClick="javascript:alterarStatusLinha(this,'${data['id']}', '${data['status']}')">
                                <i class="fa fa-exchange" aria-hidden="true"></i>
                            </button>
                            <button
                                id="btnHistoricoLinha"
                                class="btn btn-primary"
                                title="Histórico da Linha"
                                style="margin-left: 5px"
                                onClick="javascript:historicoLinha(this,'${data['id']}')">
                                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                            </button>
                            <button
                                class="btn btn-primary"
                                title="Adicionar Histórico da Linha"
                                id="btnHistoricoLinha"
                                style="margin-left: 5px"
                                onClick="javascript:addHistoricoLinha(this,'${data['id']}')">
                                <i class="fa fa-history" aria-hidden="true"></i>
                            </button>
                            <button
                                class="btn btn-primary"
                                title="Resetar Linha"
                                id="btnResetarChip"
                                style="margin-left: 5px"
                                onClick="javascript:modalResetarChip(this, '${data['linha']}', '${data['idOperadora']}')">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </button>
                        </div>
                        `;
                    }
                }
            }

        ],
        createdRow: function(row, data, dataIndex) {
            $('td:eq(0)', row).css('min-width', '140px');
        },
        dom: 'Bfrtip',
        buttons: [{
                filename: filenameGenerator("Linhas"),
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                filename: filenameGenerator("Linhas"),
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function(doc, tes) {
                    titulo = `Linhas`;
                    // Personaliza a página do PDF
                    pdfTemplateIsolated(doc, titulo, 'A4', 'auto')
                }
            },
            {
                filename: filenameGenerator("Linhas"),
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                filename: filenameGenerator("Linhas"),
                extend: 'print',
                exportOptions: {
                    columns: [4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function(win) {
                    titulo = `Linhas`;
                    // Personaliza a página Impressale
                    printTemplateOmni(win, titulo);
                }
            }
        ],
    });

    let tabelaHistoricoLinha = $('#tabelaHistoricoLinha').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        language: lang.datatable,
        deferRender: true,
        lengthChange: false,
        order: [
            [1, 'desc']
        ],
        columns: [{
                data: 'acao'
            },
            {
                data: 'dataRegistro',
                type: "date-br",
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: 'dataInicioAcao',
                type: "date-br",
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: 'dataFimAcao',
                type: "date-br",
                render: function(data) {
                    if (data != null) {
                        return new Date(data).toLocaleDateString();
                    } else {
                        return '-'
                    }
                }
            },
            {
                data: 'status'
            },
            {
                data: {
                    'status': 'status'
                },
                orderable: false,
                render: function(data) {
                    return `<button
                            class="btn"
                            title="Alterar Status"
                            style="width: 38px;background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                            id="btnAlterarStatusHistoricoLinha"
                            onClick="javascript:alterarStatusHistoricoLinha(this,'${data['id']}','${data['idLinha']}', '${data['status']}', event)">
                            <i class="fa fa-exchange"></i>
                        </button>
                        <button 
					    	class="btn"
					    	title="Editar Histórico"
					    	style="width: 38px;background-color: #04acf4; color: white;"
					    	id="btnEditarHistorico"
                            onClick="javascript:editHistorico(this,'${data['id']}', '${data['idLinha']}','${data['dataInicioAcao']}', '${data['dataFimAcao']}', '${data['acao']}', event)">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					    </button>
                        `;
                }
            },
        ]
    });

    $('#pesquisaOperadora').click(function(e) {
        e.preventDefault();

        var linha = '';
        var ccid = '';
        var idOperadora = $('#pesqOperadora').val();

        if ($('#pesqFiltro').val() == 'linha' && $('#pesqFiltroInputLinha').val() == '') {
            alert('Digite uma linha para realizar a busca!')
        } else if ($('#pesqFiltro').val() == 'ccid' && $('#pesqFiltroInputCcid').val() == '') {
            alert('Digite um ccid para realizar a busca!')
        } else {
            var url = '';

            $('#pesquisaOperadora')
                .html('<i class="fa fa-spinner fa-spin"></i> Buscando...')
                .attr('disabled', true);

            if ($('#pesqFiltro').val() == 'linha') {
                linha = $('#pesqFiltroInputLinha').val();
                linha = linha.replace(/\D/g, '');
                ccid = '';
                url = '<?= site_url('GestaoDeChips/linhas/listarlinhasPorOperadoraLinha') ?>'

            } else {
                linha = '';
                ccid = $('#pesqFiltroInputCcid').val();
                url = '<?= site_url('GestaoDeChips/linhas/listarlinhasPorOperadoraCcid') ?>'
            }

            $.ajax({
                url: url,
                dataType: 'json',
                type: 'POST',
                data: {
                    idOperadora: idOperadora,
                    linha: linha,
                    ccid: ccid,
                },
                beforeSend: function() {
                    $('#tabelaLinhas > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="11" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                        '</tr>'
                    );
                },
                success: function(data) {
                    if (data.status == 200) {
                        tabelaLinhas.clear().draw();
                        tabelaLinhas.rows.add(data.results).draw();
                        tabelaLinhas.columns.adjust().draw();
                        $('#tabelaGeralLinhas').show();
                    } else if (data.status == 404) {
                        alert('Nenhuma linha encontrada!');
                        tabelaLinhas.clear().draw();
                    } else if (data.status == 400) {
                        alert('Não foi possível realizar a busca. Verifique os campos e tente novamente!');
                        tabelaLinhas.clear().draw();
                    } else {
                        alert('Erro ao realizar a busca. Tente novamente!');
                        tabelaLinhas.clear().draw();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Erro ao realizar a busca. Tente novamente!');
                    tabelaLinhas.clear().draw();
                    $('#pesquisaOperadora')
                        .html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar')
                        .attr('disabled', false);
                },
                complete: function() {
                    $('#pesquisaOperadora')
                        .html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar')
                        .attr('disabled', false);
                }

            })

        }

    })

    $('#limpaOperadora').click(function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/listarLinhasRecentes') ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#limpaOperadora')
                    .html('<i class="fa fa-spinner fa-spin"></i> Limpando...')
                    .attr('disabled', true);
                $('#pesqOperadora').val(null).trigger('change');
                $('#pesqFiltroInputLinha').val('');
                $('#pesqFiltroInputCcid').val('');
                $('#tabelaLinhas > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="11" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data) {
                if (data.status === 200) {
                    tabelaLinhas.clear().draw();
                    tabelaLinhas.rows.add(data.results).draw();
                    tabelaLinhas.columns.adjust().draw();
                } else {
                    tabelaLinhas.clear().draw();
                }
            },
            error: function() {
                alert('Erro ao buscar linhas. Tente novamente!');
                tabelaLinhas.clear().draw();
                $('#limpaOperadora')
                    .html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar')
                    .attr('disabled', false);
            },
            complete: function() {
                $('#limpaOperadora')
                    .html('<i class="fa fa-leaf" aria-hidden="true"></i> Limpar')
                    .attr('disabled', false);
            }
        });
    });

    $('#abrirModalInserirLinha').click(function(e) {
        e.preventDefault();
        $('#modalCadLinha').modal('show');
        $('#nLinha').val('');
        $('#ccidLinha').val('');
        $('#numeroLinha').val('');
        $('#serialEquipamentoLinha').val('');
        $('#statusCrmLinha').val('');
        $('#comunicacaoRadiusLinha').val('');
        $('#statusOperadora').val(0);
    })

    $(document).ready(async function() {

        let operadorasCad = await $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/listarOperadoras') ?>',
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                return data;
            }
        })

        $('#pesqCadOperadora').select2({
            data: operadorasCad,
            placeholder: "Selecione a operadora",
            allowClear: true,
            language: "pt-BR",

        });

        $('#pesqCadOperadora').on('select2:select', function(e) {
            var data = e.params.data;
        });

        $('#pesqCadOperadora').find('option').get(0).remove();
        $('#pesqCadOperadora').prepend('<option value="0" selected="selected" disabled>Selecione a operadora</option>');

    });



    $(document).ready(function() {
        $('#pesqCadEmpresa').select2({
            ajax: {
                url: '<?= site_url('setores/buscarEmpresas') ?>',
                dataType: 'json',
                delay: 1000,
                type: 'GET',
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
            },
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
        });
    });

    async function listarFornecedores($campo) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '<?= site_url('GestaoDeChips/linhas/listarFornecedores') ?>',
                dataType: 'json',
                type: 'POST',
                data: {
                    idEmpresa: $campo.val()
                },
                success: function(data) {
                    resolve(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var texto = jqXHR.responseText.substring(42, 123);
                    resolve(texto);
                }
            })
        });

    }



    $(document).ready(async function() {
        var idEmpresaCad = $('#pesqCadEmpresa');
        $("#pesqCadFornecedor").empty()
        $("#pesqCadFornecedor").append(`<option value="0">Aguardando seleção da empresa...</option>`)
        $("#pesqCadFornecedor").select2({
            width: '100%',
            placeholder: "Aguardando seleção da empresa...",
            allowClear: true
        })
        $('#pesqCadFornecedor').attr('disabled', true);


        $("#pesqCadEmpresa").change(async function() {
            $('#pesqCadFornecedor').prepend('<option value="0" selected="selected" disabled>Buscando...</option>');
            $('#pesqCadFornecedor').attr('disabled', true);
            const fornecedores = await listarFornecedores(idEmpresaCad);
            const tipo = typeof(fornecedores);

            if ((tipo == "object") && fornecedores['results'].length > 0) {
                $('#pesqCadFornecedor').find('option').get(0).remove();
                $("#pesqCadFornecedor").empty();
                $('#pesqCadFornecedor').prepend('<option value="0" selected="selected" disabled>Selecione o fornecedor</option>');
                $('#pesqCadFornecedor').attr('disabled', false);
                for (let i = 0; i < fornecedores['results'].length; i++) {
                    const fornecedor = fornecedores['results'][i];
                    $('#pesqCadFornecedor').append('<option value="' + fornecedores['results'][i].id + '">' + fornecedores['results'][i].text + '</option>');
                }

            } else {
                $('#pesqCadFornecedor').find('option').get(0).remove();
                $("#pesqCadFornecedor").empty();
                $('#pesqCadFornecedor').prepend('<option value="0" selected="selected" disabled>Não existem fornecedores para essa empresa</option>');
            }

        });
    });

    $('#formCadLinha').submit(function(e) {
        e.preventDefault();

        $('#btnSalvarCadastroLinha')
            .html('<i class="fa fa-spinner fa-spin"></i> Salvando...')
            .attr('disabled', true);

        var nLinha = $('#nLinha').val();
        nLinha = nLinha.replace(/\D/g, '');
        var ccidLinha = $('#ccidLinha').val();
        var operadoraLinha = $('#pesqCadOperadora').val();
        var empresaLinha = $('#pesqCadEmpresa').val();
        var fornecedorLinha = $('#pesqCadFornecedor').val();
        var numeroLinha = $('#numeroLinha').val();
        var serialEquipamentoLinha = $('#serialEquipamentoLinha').val();
        var statusOperadora = $('#statusOperadora').val();

        $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/cadastrarLinha') ?>',
            dataType: 'json',
            type: 'POST',
            data: {
                linha: nLinha,
                ultimoCcid: ccidLinha,
                idOperadora: operadoraLinha,
                idEmpresa: empresaLinha,
                idFornecedor: fornecedorLinha,
                statusOperadora: statusOperadora,
                numeroConta: numeroLinha,
                ultimoSerialEquipamento: serialEquipamentoLinha,
            },
            success: function(data) {
                if (data.status == 201) {
                    alert(data.dados.mensagem)
                    $('#modalCadLinha').modal('hide');
                    tabelaLinhas.ajax.reload();
                } else if (data.status == 404) {
                    alert(data?.dados?.mensagem ? data.dados.mensagem : 'Não foi possível cadastrar a linha. Tente novamente.')
                } else if (data.status == 400) {
                    alert(data?.dados?.mensagem ? data.dados.mensagem : 'Não foi possível cadastrar a linha. Verifique os campos e tente novamente.')
                } else {
                    alert('Erro ao cadastrar linha. Tente novamente');
                }
            },
            error: function(data) {
                alert('Erro ao cadastrar linha. Tente novamente')
                $('#btnSalvarCadastroLinha')
                    .html('Salvar')
                    .attr('disabled', false);
            },
            complete: function() {
                $('#btnSalvarCadastroLinha')
                    .html('Salvar')
                    .attr('disabled', false);
            }
        })
    })

    $('#nLinha').mask('+55 (00) 00000-0000', {
        placeholder: '+55 (00) 00000-0000'
    });
    $('#nLinhaEdit').mask('+55 (00) 00000-0000', {
        placeholder: '+55 (00) 00000-0000'
    });
    $('#pesqFiltroInputLinha').mask('+55 (00) 00000-0000', {
        placeholder: '+55 (00) 00000-0000'
    });

    valorStatusAntigo = ''
    async function editLinha(botao, id, linha, status, ccid, idOperadora, idEmpresa, idFornecedor, statusOperadora, numeroConta, ultimoSerialEquipamento, ultimoStatusCrm) {
        if (ultimoStatusCrm != 'cancelado') {
            let btn = $(botao);
            btn.html('<i class="fa fa-spin fa-spinner"></i>');
            btn.attr('disabled', true);
            $('#pesqCadFornecedorEdit').attr('disabled', true);

            fornecedorId = idFornecedor;

            //Listagem de operadoras no modal de editar linhas
            let operadorasEdit = await $.ajax({
                url: '<?= site_url('GestaoDeChips/linhas/listarOperadoras') ?>',
                dataType: 'json',
                type: 'GET',
                success: function(data) {
                    return data;
                }
            })

            $('#pesqCadOperadoraEdit').select2({
                data: operadorasEdit,
                placeholder: "Selecione a operadora",
                allowClear: true,
                language: "pt-BR",

            });

            $('#pesqCadOperadoraEdit').on('select2:select', function(e) {
                var data = e.params.data;
            });

            $('#pesqCadOperadoraEdit').find('option').get(0).remove();
            $('#pesqCadOperadoraEdit').prepend('<option value="0" selected="selected" disabled>Selecione a operadora</option>');


            //Listagem de empresas no modal de editar linhas
            let empresasEdit = await $.ajax({
                url: '<?= site_url('setores/buscarEmpresas') ?>',
                dataType: 'json',
                delay: 1000,
                type: 'GET',
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
            })

            $('#pesqCadEmpresaEdit').select2({
                data: empresasEdit.results,
                placeholder: "Selecione a empresa",
                allowClear: true,
                language: "pt-BR",
            });

            $('#pesqCadEmpresaEdit').on('select2:select', function(e) {
                var data = e.params.data;
            });


            $("#pesqCadFornecedorEdit").select2({
                width: '100%',
                placeholder: "Aguardando seleção da empresa...",
                allowClear: true,
            })

            $('#modalEditarLinha').modal('show');
            btn.html('<i class="fa fa-pencil"></i>');
            btn.attr('disabled', false);


            $('#IdEdit').val(id);
            if (status == "Ativo") {
                $('#statusLinhaEdit').val(0);
            } else {
                $('#statusLinhaEdit').val(1);
            }

            $('#nLinhaEdit').val(linha).trigger('input');
            $('#ccidLinhaEdit').val(ccid);
            $('#pesqCadOperadoraEdit').val(idOperadora);
            $('#pesqCadOperadoraEdit').trigger('change');
            $('#pesqCadEmpresaEdit').val(idEmpresa);
            $('#pesqCadEmpresaEdit').trigger('change');



            if (statusOperadora == "Cadastro") {
                $('#statusOperadoraEdit').val(0);
            } else if (statusOperadora == "Suspensao") {
                $('#statusOperadoraEdit').val(1);
            } else if (statusOperadora == "Cancelamento") {
                $('#statusOperadoraEdit').val(2);
            } else if (statusOperadora == "Vincular Chip") {
                $('#statusOperadoraEdit').val(3);
            } else if (statusOperadora == "Vincular Equipamento") {
                $('#statusOperadoraEdit').val(4);
            } else {
                $('#statusOperadoraEdit').val(5);
            }
            $('#numeroLinhaEdit').val(numeroConta);
            $('#serialEquipamentoLinhaEdit').val(ultimoSerialEquipamento);
        } else {
            alert('Essa linha não pode ser editada, pois foi cancelada no CRM.');
        }
    }

    var fornecedorId = 0;

    //Listagem de fornecedores no modal de editar linhas
    $("#pesqCadEmpresaEdit").change(async function() {
        var idEmpresaEdit = $('#pesqCadEmpresaEdit');
        $('#pesqCadFornecedorEdit').prepend('<option value="0" selected="selected" disabled>Buscando...</option>');
        $('#pesqCadFornecedorEdit').attr('disabled', true);

        const fornecedores = await listarFornecedores(idEmpresaEdit);
        const tipo = typeof(fornecedores);


        if ((tipo == "object") && fornecedores['results'].length > 0) {
            $('#pesqCadFornecedorEdit').find('option').get(0).remove();
            $("#pesqCadFornecedorEdit").empty();
            $('#pesqCadFornecedorEdit').prepend('<option value="0" selected="selected" disabled>Selecione o fornecedor</option>');

            for (let i = 0; i < fornecedores['results'].length; i++) {
                const pesqCadFornecedorEdit = fornecedores['results'][i];
                $('#pesqCadFornecedorEdit').append('<option value="' + fornecedores['results'][i].id + '">' + fornecedores['results'][i].text + '</option>');
            }

            $('#pesqCadFornecedorEdit').attr('disabled', false);

        } else {
            $('#pesqCadFornecedorEdit').find('option').get(0).remove();
            $("#pesqCadFornecedorEdit").empty();
            $('#pesqCadFornecedorEdit').prepend('<option value="0" selected="selected" disabled>Não existem fornecedores para essa empresa</option>');
        }
        if (fornecedorId != 0) {
            $('#pesqCadFornecedorEdit').val(fornecedorId).trigger('change');
        } else {
            $('#pesqCadFornecedorEdit').val(0).trigger('change');
        }

        fornecedorId = 0;

    });


    $('#formEditLinha').submit(function(e) {
        e.preventDefault();
        $('#btnSalvarEditLinha')
            .html('<i class="fa fa-spinner fa-spin"></i> Editando...')
            .attr('disabled', true);

        var id = $('#IdEdit').val();
        var status = $('#statusLinhaEdit').val();
        var nLinha = $('#nLinhaEdit').val();
        nLinha = nLinha.replace(/\D/g, '');
        var ccidLinha = $('#ccidLinhaEdit').val();
        var operadoraLinha = $('#pesqCadOperadoraEdit').val();
        var empresaLinha = $('#pesqCadEmpresaEdit').val();
        var fornecedorLinha = $('#pesqCadFornecedorEdit').val();
        var numeroLinha = $('#numeroLinhaEdit').val();
        var serialEquipamentoLinha = $('#serialEquipamentoLinhaEdit').val();
        var statusOperadora = $('#statusOperadoraEdit').val();

        $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/editarLinha') ?>',
            dataType: 'json',
            type: 'POST',
            data: {
                idLinha: id,
                status: status,
                linha: nLinha,
                ultimoCcid: ccidLinha,
                idOperadora: operadoraLinha,
                idEmpresa: empresaLinha,
                idFornecedor: fornecedorLinha,
                statusOperadora: statusOperadora,
                numeroConta: numeroLinha,
                ultimoSerialEquipamento: serialEquipamentoLinha,
            },
            success: function(data) {
                if (data.status == 200) {
                    alert(data.dados.mensagem)
                    $('#modalEditarLinha').modal('hide');
                    $('#nLinhaEdit').val('');
                    $('#ccidLinhaEdit').val('');
                    $('#pesqCadOperadoraEdit').val(0).trigger('change');
                    $('#pesqCadEmpresaEdit').prepend('<option value="0" selected="selected" disabled>Selecione a empresa</option>');
                    $('#pesqCadFornecedorEdit').prepend('<option value="0" selected="selected" disabled>Aguardando a seleção da empresa...</option>');
                    $('#pesqCadFornecedorEdit').attr('disabled', true);
                    $('#numeroLinhaEdit').val('');
                    $('#serialEquipamentoLinhaEdit').val('');
                    $('#statusOperadoraEdit').val(5);
                    tabelaLinhas.ajax.reload();
                } else if (data.status == 404) {
                    alert(data?.dados?.mensagem ? data.dados.mensagem : 'Não foi possível editar a linha. Verifique os campos e tente novamente.')
                } else if (data.status == 400) {
                    alert(data?.dados?.mensagem ? data.dados.mensagem : 'Não foi possível editar a linha. Tente novamente.')
                } else {
                    alert('Erro ao cadastrar linha. Tente novamente');
                }
            },
            error: function(data) {
                alert('Erro ao atualizar linha. Tente novamente.')
                $('#btnSalvarEditLinha')
                    .html('Salvar')
                    .attr('disabled', false);
            },
            complete: function() {
                $('#btnSalvarEditLinha')
                    .html('Salvar')
                    .attr('disabled', false);
            }
        })
    })

    function alterarStatusLinha(botao, id, status) {
        if (confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')) {
            btn = $(botao);
            btn.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
            if (status == 'Ativo') {
                status = 1
            } else {
                status = 0
            }
            $.ajax({
                url: '<?= site_url('GestaoDeChips/linhas/alterarStatusLinha') ?>',
                type: 'POST',
                data: {
                    idLinha: id,
                    status: status
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        tabelaLinhas.ajax.reload();
                    } else {
                        alert(data?.dados?.mensagem ? data.dados.mensagem : 'Erro ao alterar status da linha, tente novamente')
                    }
                },
                error: function(data) {
                    alert('Erro ao alterar status da linha, tente novamente');
                    btn.html('<i class="fa fa-exchange"></i>').attr('disabled', false);
                },
                complete: function() {
                    btn.html('<i class="fa fa-exchange"></i>').attr('disabled', false);
                }
            })
        } else {
            return false;
        }
    }

    function historicoLinha(botao, id) {
        btn = $(botao);
        btn.html('<i class="fa fa-spinner fa-spin"></i>');
        btn.attr('disabled', true);

        $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/listarHistoricoLinha') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idLinha: id
            },
            success: function(data) {
                if (data.status == 200 && data.results.length) {
                    tabelaHistoricoLinha.clear().draw();
                    tabelaHistoricoLinha.rows.add(data.results).draw();
                    $('#modalHistoricoLinha').modal('show');
                    btn.html('<i class="fa fa-newspaper-o"></i>')
                    btn.attr('disabled', false);
                } else {
                    tabelaHistoricoLinha.clear().draw();
                    $('#modalHistoricoLinha').modal('show');
                    btn.html('<i class="fa fa-newspaper-o"></i>');
                    btn.attr('disabled', false);
                }
            },
            error: function(data) {
                alert('Erro ao listar histórico da linha, tente novamente');
                btn.html('<i class="fa fa-newspaper-o"></i>');
                btn.attr('disabled', false);
            }
        })
    }

    function alterarStatusHistoricoLinha(botao, id, idLinha, status, event) {
        btn = $(botao);
        event.preventDefault();
        if (confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')) {
            btn.html('<i class="fa fa-spinner fa-spin"></i>');
            btn.attr('disabled', true);
            if (status == 'Ativo') {
                status = 0
            } else {
                status = 1
            }
            $.ajax({
                url: '<?= site_url('GestaoDeChips/linhas/alterarStatusHistoricoLinha') ?>',
                type: 'POST',
                data: {
                    idLinhaHistorico: id,
                    status: status
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        btn.html('<i class="fa fa-exchange"></i>');
                        btn.attr('disabled', false);
                        $.ajax({
                            url: '<?= site_url('GestaoDeChips/linhas/listarHistoricoLinha') ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                idLinha: idLinha
                            },
                            success: function(data) {
                                if (data.status == 200) {
                                    tabelaHistoricoLinha.clear().draw();
                                    tabelaHistoricoLinha.rows.add(data.results).draw();
                                } else {
                                    alert(data.results.mensagem);
                                }
                            },
                            error: function(data) {
                                alert('Erro ao listar histórico da linha, tente novamente');
                            }
                        })
                    } else {
                        alert(data.dados.mensagem)
                        btn.html('<i class="fa fa-exchange"></i>');
                        btn.attr('disabled', false);
                    }
                },
                error: function(data) {
                    alert('Erro ao alterar status do histórico da linha, tente novamente');
                    btn.html('<i class="fa fa-exchange"></i>');
                    btn.attr('disabled', false);
                }
            });
        } else {
            return false;
        }
    }

    function addHistoricoLinha(botao, id) {
        $('#modalAddHistoricoLinha').modal('show');
        $('#idLinhaAddHistorico').val(id);

    }

    function modalResetarChip(botao, linha, idOperadora) {
        $('#modalResetarChip').modal('show');
        $('#idOperadoraResetarChip').val(idOperadora);
        $('#linhaResetarChip').val(linha);

    }

    function resetarChip(botao) {
        btn = $(botao);
        btn.html('<i class="fa fa-spinner fa-spin"></i>');
        btn.attr('disabled', true);

        var tim = 41;
        // var claro = 21;
        var vivo = 15;

        var idOperadora = $('#idOperadoraResetarChip').val();
        var linha = $('#linhaResetarChip').val();

        //OPERADORA TIM
        if (idOperadora == 1) {
            idOperadora = tim;
        } else if (idOperadora == 2) { //OPERADORA VIVO
            idOperadora = vivo;
        }

        $.ajax({
            url: `${URL_PAINEL_OMNILINK}/resetarChip`,
            type: 'POST',
            data: {
                idOperadora: idOperadora,
                linha: linha
            },
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    // var mensagem = JSON.parse(data.dados).mensagem;
                    alert('Linha resetada com sucesso!');
                    $('#modalResetarChip').modal('hide');
                } else {
                    alert('Erro ao resetar linha, tente novamente');
                }
            },
            error: function(data) {
                alert('Erro ao resetar linha, tente novamente');
                btn.html('Resetar');
                btn.attr('disabled', false);
            },
            complete: function() {
                btn.html('Resetar');
                btn.attr('disabled', false);
            }
        });
    }

    $('#btnSalvarAddHistorico').click(function() {
        btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin"></i>');
        btn.attr('disabled', true);
        dataInicio = $('#dataInicio').val();
        dataInicioInserir = transformardataparainserir(dataInicio);
        acao = $('#acaoLinha').val();
        idLinha = $('#idLinhaAddHistorico').val();

        $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/cadastrarHistoricoLinha') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idLinha: idLinha,
                dataInicio: dataInicioInserir,
                acao: acao,
            },
            success: function(data) {
                if (data.status == 201) {
                    alert(data.dados.mensagem);
                    $('#modalAddHistoricoLinha').modal('hide');
                    $('#dataInicio').val('').trigger('change');
                    $('#acao').val('');
                    $('#idLinhaAddHistorico').val('');
                } else if (data.status == 404) {
                    alert(data?.dados?.mensagem ? data.dados.mensagem : 'Não foi possível cadastrar o histórico da linha. Tente Novamente.')
                } else if (data.status == 400) {
                    alert(data?.dados?.mensagem ? data.dados.mensagem : 'Não foi possível cadastrar o histórico da linha. Tente Novamente.')
                } else {
                    alert('Erro ao cadastrar linha. Tente novamente');
                }
            },
            error: function(data) {
                alert('Erro ao cadastrar histórico da linha, tente novamente');
                btn.html('Salvar')
                btn.attr('disabled', false);
            },
            complete: function() {
                btn.html('Salvar')
                btn.attr('disabled', false);
            }
        })
    })

    function editHistorico(botao, id, idLinha, dataInicioAcao, dataFimAcao, acao, event) {
        event.preventDefault();
        if (dataFimAcao == 'null') {
            dataInicio = dataInicioAcao.split(' ')[0];
            dataInicioEdit = dataInicio.split('/').reverse().join('-');

            if (acao == "Cadastro") {
                $('#acaoLinhaEdit').val(0);
            } else if (acao == "Suspensao") {
                $('#acaoLinhaEdit').val(1);
            } else if (acao == "Cancelamento") {
                $('#acaoLinhaEdit').val(2);
            } else if (acao == "Vinculo Chip") {
                $('#acaoLinhaEdit').val(3);
            } else if (acao == "Vinculo Equipamento") {
                $('#acaoLinhaEdit').val(4);
            } else {
                $('#acaoLinhaEdit').val(5);
            }

            $('#dataInicioEdit').val(dataInicioEdit);
            $('#idHistoricoEdit').val(id);
            $('#idLinhaEditHistorico').val(idLinha);
            $('#modalEditarHistoricoLinha').modal('show');
        } else {
            alert('Não é possível editar histórico de linha finalizado');
        }
    }

    $('#btnSalvarEditHistorico').click(function() {
        btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin"></i> Editando');
        btn.attr('disabled', true);
        dataInicio = $('#dataInicioEdit').val();
        dataInicioInserir = transformardataparainserir(dataInicio);
        acao = $('#acaoLinhaEdit').val();

        $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/editarHistoricoLinha') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idLinhaHistorico: $('#idHistoricoEdit').val(),
                dataInicio: dataInicioInserir,
                acao: acao,
            },
            success: function(data) {
                if (data.status == 200) {
                    alert(data.dados.mensagem);
                    btn.html('Salvar')
                    btn.attr('disabled', false);
                    $('#modalEditarHistoricoLinha').modal('hide');
                    $.ajax({
                        url: '<?= site_url('GestaoDeChips/linhas/listarHistoricoLinha') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            idLinha: $('#idLinhaEditHistorico').val()
                        },
                        success: function(data) {
                            if (data.status == 200) {
                                tabelaHistoricoLinha.clear().draw();
                                tabelaHistoricoLinha.rows.add(data.results).draw();
                            } else {
                                alert(data.results.mensagem);
                            }
                        },
                        error: function(data) {
                            alert('Erro ao listar histórico da linha, tente novamente');
                        }
                    })
                } else {
                    alert(data.dados.mensagem);
                    btn.html('Salvar')
                    btn.attr('disabled', false);
                }
            },
            error: function(data) {
                alert('Erro ao editar histórico da linha, tente novamente');
                btn.html('Salvar')
                btn.attr('disabled', false);
            }
        })
    })

    function transformardataparainserir(localData) {
        var divisor = "-";
        var partesData = localData.split(divisor);
        var novaData = partesData[2] + "/" + partesData[1] + "/" + partesData[0];
        return novaData;
    }

    $('#pesqFiltro').on('change', function() {
        var filtro = $('#pesqFiltro').val();
        if (filtro == "linha") {
            $('#labelLinha').show();
            $('#labelCcid').hide();
            $('#pesqFiltroInputLinha').css('display', 'block');
            $('#pesqFiltroInputCcid').css('display', 'none');
        } else {
            $('#labelLinha').hide();
            $('#labelCcid').show();
            $('#pesqFiltroInputLinha').css('display', 'none');
            $('#pesqFiltroInputCcid').css('display', 'block');
        }
    })

    $(document).ready(async function() {
        $('#pesqOperadora').attr('disabled', true);
        let operadoras = await $.ajax({
            url: '<?= site_url('GestaoDeChips/linhas/listarOperadoras') ?>',
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                return data;
            }
        })
        $('#pesqOperadora').select2({
            data: operadoras,
            placeholder: "Selecione a operadora",
            allowClear: true,
            language: "pt-BR",

        });
        $('#pesqOperadora').on('select2:select', function(e) {
            var data = e.params.data;
        });
        $('#pesqOperadora').find('option').get(0).remove();
        $('#pesqOperadora').prepend('<option value="0" selected="selected" disabled>Selecione a operadora</option>');
        $('#pesqOperadora').attr('disabled', false);
    });
</script>

<!-- Traduções -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>