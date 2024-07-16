<style>
    table {
        width: 100%;
        /* table-layout:fixed; */
    }

    td {
        word-wrap: break-word;
    }

    .modeloIsca {
        width: 100%;
        word-wrap: break-word;
    }

    .dt-buttons {
        margin-bottom: 20px;
    }

    .my-1 {
        margin: 1em 0 1em 0 !important;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }
</style>
<div class="row justify-content-center">
    <div class="col-sm-12">
        <h2><?= $titulo ?></h2>
    </div>
</div>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <button id="btnDesvincular" class="btn btn-primary">Desvincular Selecionadas</button>
        <button id="btnExibirModalAlterarDataExpiracao" class="btn btn-primary">Alterar Data de Expiração Selecionadas</button>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-sm-12">
        <table id="IscasVinculadasTable" class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 25px;">
                        <input class="selectAll" id="selectAll" names="selectAll" type="checkbox">
                    </th>
                    <th>ID</th>
                    <th>Serial</th>
                    <th>Descrição</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Data Cadastro</th>
                    <th>Data Expiração</th>
                    <th>Status</th>
                    <th>Cliente</th>
                    <th>Contrato</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal Trocar Cliente-->
<div class="modal fade" id="modalMigrarIsca" tabindex="-1" role="dialog" aria-labelledby="migarEquipamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="migrarEquipamentoModalLabel">Migrar Equipamento</h4>
            </div>
            <div class="modal-body">
                <!-- Dados Isca -->
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Isca</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Serial</label>
                        <h5 id="serialIsca"></h5>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Descrição</label>
                        <h5 id="descricaoIsca"></h5>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Data de Cadastro</label>
                        <h5 id="dataCadastroIsca"></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Modelo</label>
                        <h5 id="modeloIsca"></h5>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Marca</label>
                        <h5 id="marcaIsca"></h5>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Status</label>
                        <h5 id="statusIsca"></h5>
                    </div>
                </div>
                <!-- Dados Cliente -->
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Cliente Atual</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Nome do Cliente</label>
                        <h5 id="nomeCliente"></h5>
                    </div>
                    <div class="col-sm-4">
                        <label>CNPJ</label>
                        <h5 id="cnpjCliente"></h5>
                    </div>
                    <div class="col-sm-4">
                        <label for="">Data do Contrato</label>
                        <h5 id="dataContrato"></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4>Migrar Isca Para</h4>
                    </div>
                </div>

                <div class="row">
                    <form">
                        <div class="col-sm-3">
                            <select class="form-control" name="tipoBuscaCliente" id="tipoBuscaCliente">
                                <option value="cnpj" selected>CNPJ</option>
                                <option value="cpf">CPF</option>
                                <option value="id_contrato">Contrato</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <input type="text" class="form-control" id="cnpjBuscarNovoCliente" placeholder="Buscar Cliente por CNPJ">
                                <input type="text" class="form-control" id="cpfBuscarNovoCliente" placeholder="Buscar Cliente por CPF" style="display: none;">
                                <input type="text" class="form-control numeric" id="idContratoBuscarNovoCliente" placeholder="Buscar Cliente por Contrato" style="display: none;">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <a id="btnBuscarClientePorCNPJ" class="btn btn-primary" title="Buscar Cliente"><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                        </form>
                </div>

                <!-- Dados novo Cliente -->
                <div id="dadosNovoCliente" style="display: none;">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="">Nome do Cliente</label>
                            <h5 id="nomeNovoCliente"></h5>
                        </div>
                        <div class="col-sm-6">
                            <label id="labelTipoBusca" for="">CNPJ</label>
                            <h5 id="cnpjNovoCliente"></h5>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Endereço</label>
                            <h5 id="enderecoNovoCliente"></h5>
                        </div>
                    </div>

                    <form>
                        <input type="hidden" id="id_isca" name="id_isca">
                        <input type="hidden" id="idNovoCliente" name="idNovoCliente">
                        <input type="hidden" id="idContratoNovoCliente" name="idContratoNovoCliente">

                    </form>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="MigrarIsca">Migrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal adicionado dinamicamente para alterar data de expiracao das iscas -->
<div id="modalDataExpiracao"></div>


<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

<!-- Estilo do botão status -->
<link rel="stylesheet" type="text/css" href="<?= base_url("media/css/toggle-button.css") ?>">
<!-- helper iscas -->
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>

<!-- CLASS DATA EXPIRACAO ISCA -->
<script type="text/javascript" src="<?= base_url("media/js/helpers/DataExpiracaoIsca.js") ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#cnpjBuscarNovoCliente").mask("99.999.999/9999-99");
        $("#cpfBuscarNovoCliente").mask("999.999.999-99");

        $('.buttons-csv').addClass('btn btn-info');
        $('.buttons-excel').addClass('btn btn-info');
        $('.buttons-pdf').addClass('btn btn-info');
        $('.buttons-print').addClass('btn btn-info');
    });

    $('.numeric').on('input', function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $(document).ready(function() {
        var mask = function(val) {
            val = val.split(":");
            return (parseInt(val[0]) > 19) ? "HZ:M0:M0" : "H0:M0:M0";
        }

        pattern = {
            onKeyPress: function(val, e, field, options) {
                field.mask(mask.apply({}, arguments), options);
            },
            translation: {
                'H': {
                    pattern: /[0-2]/,
                    optional: false
                },
                'Z': {
                    pattern: /[0-3]/,
                    optional: false
                },
                'M': {
                    pattern: /[0-5]/,
                    optional: false
                }
            },
        };
        $('.time').mask(mask, pattern);
    });

    var checksIscas = [];

    // Iscas Vinculadas ()
    const dtIscasVinculadas = $('#IscasVinculadasTable').DataTable({
        paging: true,
        serverSide: true,
        ajax: {
            url: '<?= site_url("iscas/isca/getIscasVinculadas") ?>',
            type: 'POST',
            dataType: 'json',
            data: function(d) {
                d.filtro = $('#coluna_search').val();
                d.search = $('#search_tabela').val();
            }
        },
        sort: true,
        searching: true,
        order: [
            [1, 'asc']
        ],
        columnDefs: [{
            "targets": [11],
            "orderable": false
        }, {
            "targets": [0],
            "orderable": false
        }],
        aLengthMenu: [
            [10, 25, 50, 75, 100],
            [10, 25, 50, 75, 100]
        ],
        dom: 'Blrtip',
        columns: [{
                data: 'id',
                render: function(data, type, row, meta) {
                    let checked = checksIscas.indexOf(data) > -1 ? "checked" : "";
                    return '<input type="checkbox" value="' + data + '" class="checkIscas" ' + checked + ' name="checkIscas[]">';
                }
            },
            {
                data: 'id'
            },
            {
                data: 'serial'
            },
            {
                data: 'descricao'
            },
            {
                data: 'modelo'
            },
            {
                data: 'marca'
            },
            {
                data: 'data_cadastro',
                render: function(data, type, row, meta) {
                    return formatDateTime(data);
                }
            },
            {
                data: 'data_expiracao',
                render: function(data, type, row, meta) {
                    return formatDateTime(data);
                }
            },
            {
                data: {
                    id: 'id',
                    status: 'status'
                },
                render: function(data, type, row, meta) {
                    return returnStatus(data.status, data.id);
                }
            },
            {
                data: 'nome'
            },
            {
                data: 'id_contrato'
            },
            {
                data: {
                    id_cliente: 'id_cliente',
                    id: 'id'
                },
                render: function(data, type, row, meta) {
                    return returnBtnsAcao(data.id_cliente, data.id)
                }
            },
        ],
        buttons: [{
                title: 'Iscas Vinculadas',
                messageTop: function() {
                    date = new Date();
                    return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    modified: {
                        page: 'all',
                    },
                },
                action: function(e, dt, node, config) {
                    let self = this;
                    let start = dt.page.info().start;
                    let length = dt.page.len();

                    dt.one('preXhr', function(e, settings, data) {
                        data.start = 0;
                        data.length = dt.page.info().recordsTotal;

                        dt.one('preDraw', function(e, settings) {
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, node, config);

                            dt.one('preXhr', function(e, settings, data) {
                                data.start = start;
                                data.length = length;
                            });

                            setTimeout(dt.ajax.reload, 0);

                            return false;
                        });
                    });

                    dt.ajax.reload();
                },
            },
            {
                title: 'Iscas Vinculadas',
                messageTop: function() {
                    date = new Date();
                    return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'csv',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    modified: {
                        page: 'all'
                    },
                },
                action: function(e, dt, node, config) {
                    let self = this;
                    let start = dt.page.info().start;
                    let length = dt.page.len();

                    dt.one('preXhr', function(e, settings, data) {
                        data.start = 0;
                        data.length = dt.page.info().recordsTotal;

                        dt.one('preDraw', function(e, settings) {
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, node, config);

                            dt.one('preXhr', function(e, settings, data) {
                                data.start = start;
                                data.length = length;
                            });

                            setTimeout(dt.ajax.reload, 0);

                            return false;
                        });
                    });

                    dt.ajax.reload();
                },
            },
            {
                title: 'Iscas Vinculadas',
                messageTop: function() {
                    date = new Date();
                    return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'pdfHtml5',
                className: 'dt-button buttons-pdf buttons-html5 btn btn-outline-primary',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    modified: {
                        page: 'all',
                    },
                },
                action: function(e, dt, node, config) {
                    let self = this;
                    let start = dt.page.info().start;
                    let length = dt.page.len();

                    dt.one('preXhr', function(e, settings, data) {
                        data.start = 0;
                        data.length = dt.page.info().recordsTotal;

                        dt.one('preDraw', function(e, settings) {
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, node, config);

                            dt.one('preXhr', function(e, settings, data) {
                                data.start = start;
                                data.length = length;
                            });

                            setTimeout(dt.ajax.reload, 0);

                            return false;
                        });
                    });

                    dt.ajax.reload();
                },
            },
            {
                title: 'Iscas Vinculadas',
                messageTop: function() {
                    date = new Date();
                    return `Data de Exportação: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'Imprimir',
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    modified: {
                        page: 'all',
                    },
                },
                action: function(e, dt, node, config) {
                    let self = this;
                    let start = dt.page.info().start;
                    let length = dt.page.len();

                    dt.one('preXhr', function(e, settings, data) {
                        data.start = 0;
                        data.length = dt.page.info().recordsTotal;

                        dt.one('preDraw', function(e, settings) {
                            $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, node, config);

                            dt.one('preXhr', function(e, settings, data) {
                                data.start = start;
                                data.length = length;
                            });

                            setTimeout(dt.ajax.reload, 0);

                            return false;
                        });
                    });

                    dt.ajax.reload();
                },
            },
        ],
        language: lang.datatable,
        rowId: 'id'
    });

    var search_iscas = document.createRange().createContextualFragment(`
        <div style="float:right;">
            <label style="margin-left: 20px">
                <span id="span_busca_estoque">
                    <select id="coluna_search">
                        <option value="cad_iscas.id">ID</option> 
                        <option value="cad_iscas.serial" selected>Serial</option>
                        <option value="cad_iscas.descricao">Descrição</option>
                        <option value="cad_iscas.modelo">Modelo</option>
                        <option value="cad_iscas.marca">Marca</option>
                        <option value="cad_iscas.data_cadastro">Data de Cadastro</option>
                        <option value="cad_iscas.data_expiracao">Data de Expiração</option>
                        <option value="cad_iscas.status">Status</option>
                        <option value="cad_clientes.nome">Cliente</option>
                        <option value="cad_iscas.id_contrato">Contrato</option>
                    </select>
                </span>
            </label>
            <label style="margin-left: 20px">
                Pesquisar: <input type="search" id="search_tabela" name="search_tabela" placeholder="">
            </label>
            <button style="padding: 2px 4px !important; text-align: center !important;" class="btn btn-primary" id="btnBuscarEstoque" title="Pesquisar"><i style="font-size: 15px !important;" class="fa fa-search" aria-hidden="true"></i></button>
            <button style="padding: 2px 4px !important; text-align: center !important;" class="btn btn-danger" id="btnResetEstoque" title="Limpar Pesquisa"><i style="font-size: 15px !important;" class="fa fa-trash" aria-hidden="true"></i></button>
        </div>`);

    $('#IscasVinculadasTable_length').append(search_iscas);
    $('#IscasVinculadasTable_length').css('left', '60%');
    $('#IscasVinculadasTable_length').css('width', '100%');

    //GERENCIA A LIBERACAO DO CAMPO DE PESQUISA E DO BOTAO DE PESQUIA DA TABELA
    $(document).on('change', '#coluna_search', function(e) {
        e.preventDefault();

        filtro = $('#coluna_search').val();
        $('#search_tabela').val('');

        if (filtro == 'cad_iscas.data_cadastro' || filtro == 'cad_iscas.data_expiracao') $('#search_tabela').attr('type', 'date');
        else if (filtro == 'serial' || filtro == 'descricao' || filtro == 'modelo' || filtro == 'marca') $('#search_tabela').attr('type', 'text');
        if (filtro == 'cad_iscas.status') {
            $('#search_tabela').replaceWith('<select id="search_tabela"><option value="ativa">Ativa</option><option value="inativa">Inativa</option></select>');
        } else {
            $('#search_tabela').replaceWith('<input type="search" id="search_tabela" name="search_tabela" placeholder="">');
        }
    });

    //EVENTO PARA PESQUISA NA TABELA
    $(document).on('click', '#btnBuscarEstoque', function(e) {
        e.preventDefault();
        var botao = $(this);
        botao.html('<i class="fa fa-spinner fa-spin"></i>');
        dtIscasVinculadas.ajax.reload();
        botao.html('<i class="fa fa-search"></i>');
    });

    //EVENTO PARA LIMPAR TABELA
    $(document).on('click', '#btnResetEstoque', function(e) {
        e.preventDefault();
        var botao = $(this);

        $('#search_tabela').val('');

        botao.html('<i class="fa fa-spinner fa-spin"></i>');
        dtIscasVinculadas.ajax.reload();
        botao.html('<i class="fa fa-trash"></i>');
    });

    // Cria objeto DataExpiracaoIsca
    const dataExpiracaoIsca = new DataExpiracaoIsca(dtIscasVinculadas, 7, 'checkIscas', 'btnExibirModalAlterarDataExpiracao', checksIscas);
    // Carrega html do modal de data de expiração
    dataExpiracaoIsca.carregarModalDataExpiracao('modalDataExpiracao');

    // Exibe modal
    dataExpiracaoIsca.getButtonTriggerModal().click(function() {
        dataExpiracaoIsca.exibirModal();
    });

    // Altera data de expiração
    dataExpiracaoIsca.getForm().submit(event => {
        event.preventDefault();

        dataExpiracaoIsca.submit('<?= site_url("iscas/isca/ajaxAlterarDataExpiracao") ?>');

    });

    $(document).on('click', '.checkIscas', function() {

        let checked = $(this).prop('checked');
        let value = $(this).val()

        const index = checksIscas.indexOf(value);

        if (checked && index == -1) {
            checksIscas.push(value);
        } else {
            checksIscas.splice(index, 1);
        }
    });

    // Ao clicar no checkbox selecionar todos
    $("#selectAll").click(function() {
        let allPages = dtIscasVinculadas.cells().nodes();
        if ($("#selectAll").is(':checked')) {
            $(allPages).find('.checkIscas[type="checkbox"]').prop('checked', true);
        } else {
            $(allPages).find('.checkIscas[type="checkbox"]').prop('checked', false).attr('disabled', false);
        }

        $(".checkIscas").each(function() {
            let checked = $(this).prop('checked');
            let value = $(this).val()

            const index = checksIscas.indexOf(value);

            if (checked && index == -1) {
                checksIscas.push(value);
            } else if (!checked && index > -1) {
                checksIscas.splice(index, 1);
            }
        });
    });

    $("#tipoBuscaCliente").change(function() {
        let tipo = $(this).val();
        // console.log(tipo)
        if (tipo == 'cpf') {
            $("#cnpjBuscarNovoCliente").css('display', 'none');
            $("#cnpjBuscarNovoCliente").val("");
            $("#idContratoBuscarNovoCliente").css('display', 'none');
            $("#idContratoBuscarNovoCliente").val("");
            $("#cpfBuscarNovoCliente").css('display', 'block');
        } else if (tipo == 'cnpj') {
            $("#cnpjBuscarNovoCliente").css('display', 'block');
            $("#cpfBuscarNovoCliente").css('display', 'none');
            $("#cpfBuscarNovoCliente").val("");
            $("#idContratoBuscarNovoCliente").css('display', 'none');
            $("#idContratoBuscarNovoCliente").val("");
        } else {
            $("#cnpjBuscarNovoCliente").css('display', 'none');
            $("#cnpjBuscarNovoCliente").val("");
            $("#cpfBuscarNovoCliente").css('display', 'none');
            $("#cpfBuscarNovoCliente").val("");
            $("#idContratoBuscarNovoCliente").css('display', 'block');
        }
    });

    /**
    Busca dados do cliente de acordo com o parametro informado (cpf ou cnpj) */
    $("#btnBuscarClientePorCNPJ").click(function() {
        button = $(this);
        let cnpj = $("#cnpjBuscarNovoCliente").val();
        let cpf = $("#cpfBuscarNovoCliente").val();
        let id_contrato = $("#idContratoBuscarNovoCliente").val();
        let tipoBusca = $("#tipoBuscaCliente").val();
        let id_isca = $("#id_isca").val();

        let data = {
            cnpj: cnpj,
            cpf: cpf,
            id_contrato: id_contrato,
            tipoBusca: tipoBusca,
            id_isca: id_isca,
        }

        if (tipoBusca == "cnpj" && cnpj == "") {
            alert("Por favor, informe o CNPJ do cliente.");
            return false;
        } else if (tipoBusca == "cpf" && cpf == "") {
            alert("Por favor, informe o CPF do cliente.");
            return false;
        } else if (tipoBusca == "id_contrato" && id_contrato == "") {
            alert("Por favor, informe o ID do contrato do cliente.");
            return false;
        } else {
            // Limpa os dados exibidos ao buscar um novo cliente para fazer a vinculação
            limparDadosNovoCliente();
            button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: '<?= site_url("iscas/isca/getDadosContratoClientePorCpfOuCnpj") ?>',
                type: 'POST',
                data: data,
                success: function(callback) {
                    data = JSON.parse(callback);
                    if (data.status == false) {
                        alert(data.msg)
                    } else {
                        $("#nomeNovoCliente").html(data[0].nome);
                        if (tipoBusca == "cnpj") {
                            $("#labelTipoBusca").html("CNPJ");
                            $("#cnpjNovoCliente").html(data[0].cnpj);
                        } else if (tipoBusca == "cpf") {
                            $("#labelTipoBusca").html("CPF");
                            $("#cnpjNovoCliente").html(data[0].cpf);
                        } else {
                            $("#labelTipoBusca").html("Contrato");
                            $("#cnpjNovoCliente").html(data[0].id_contrato);
                        }
                        $("#enderecoNovoCliente").html(`${data[0].endereco}, ${data[0].numero} - ${data[0].bairro}, ${data[0].cidade}.`)
                        $("#idNovoCliente").val(data[0].id);
                        $("#idContratoNovoCliente").val(data[0].id_contrato);

                        $('#dadosNovoCliente').css('display', 'block');
                    }
                    button.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true">');
                },
                error: function() {
                    alert("Não foi possível buscar o cliente. Por favor, tente novamente.");
                    button.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true">');
                }
            });
        }
    });
    $("#MigrarIsca").click(function() {

        data = {
            id_isca: [$("#id_isca").val()],
            id_cliente: $("#idNovoCliente").val(),
            id_contrato: $("#idContratoNovoCliente").val()
        }
        if (data.id_cliente == "" || data.id_cliente == undefined || data.id_cliente == null) {
            alert("Informe o Cliente para concluir a transferência.");
            return false;
        } else {
            let button = $(this);
            button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Migrando');
            $.ajax({
                url: '<?= site_url("iscas/isca/migrarIscaCliente") ?>',
                type: 'POST',
                data: data,
                success: function(callback) {
                    data = JSON.parse(callback);
                    alert(data.msg);
                    button.attr('disabled', false).html('Migrar');
                    // Lista iscas cadastradas vinculadas a um cliente
                    dtIscasVinculadas.ajax.reload();
                    $("#modalMigrarIsca").modal('hide');
                },
                error: function() {
                    alert("Não foi possível buscar o cliente. Por favor, tente novamente.");

                    button.attr('disabled', false).html('Migrar');
                    $("#modalMigrarIsca").modal('hide');
                }
            });
        }
    });

    function returnBtnsAcao(id_cliente, id_isca) {

        let btn = '';

        if (id_cliente == 0 || id_cliente == null || id_cliente == undefined) {
            btn = '';
        } else {
            btn += `
                    <div>
                        <a type="button" title="Migrar Equipamento" id="isca${id_isca}" onClick="migrarIscaCliente(${id_cliente},${id_isca})">
                            <i class="fa fa-exchange" aria-hidden="true"></i>
                        </a>
                        <a type="button" style="margin-left: 5px; color:red;" title="Desvincular Equipamento" id="desvincularIsca${id_isca}" onClick="desvincularIsca($(this), ${id_isca})">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                        </a>
                    </div>
                    
                    `;

        }

        return btn;
    }


    // });

    function ativarDesativarIsca(statusAtual, id_isca) {
        let confirma = false;
        let colunaStatus = $("#statusIsca" + id_isca).parent();
        colunaStatus.html('<i class="fa fa-spinner fa-spin"></i>');
        if (statusAtual === 0) {
            confirma = confirm("Você tem certeza que deseja ativar a isca?");
        } else {
            confirma = confirm("Você tem certeza que deseja desativar a isca?");
        }

        if (confirma) {
            $.ajax({
                url: '<?= site_url("iscas/isca/ajaxAtivarDesativarIsca") ?>',
                type: 'POST',
                data: {
                    id_isca: id_isca
                },
                success: function(callback) {
                    data = JSON.parse(callback);
                    alert(data.msg);
                    dtIscasVinculadas.ajax.reload();

                },
                error: function() {
                    alert("Não foi possível buscar o cliente. Por favor, tente novamente.");
                    dtIscasVinculadas.ajax.reload();

                }
            });
            return;
        } else {
            if (statusAtual === 0) {
                colunaStatus.html(returnStatus(0, id_isca));
                return;
            } else {
                colunaStatus.html(returnStatus(1, id_isca));
                return;
            }
        }
        return;
    }

    function returnIdContratoCliente(id_contrato) {
        if (id_contrato == 0)
            return '';
        else
            return id_contrato;
    }

    function migrarIscaCliente(id_cliente, id_isca) {
        // Limpa os dados exibidos ao buscar um novo cliente para fazer a vinculação
        limparDadosNovoCliente();

        let button = $("#isca" + id_isca);
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: '<?= site_url("iscas/isca/getDadosIscaVinculada") ?>',
            type: 'POST',
            data: {
                id_isca: id_isca,
                id_cliente: id_cliente
            },
            success: function(callback) {
                let data = JSON.parse(callback);
                exibirDadosModalMigrarIscaCliente(data);
                button.attr('disabled', false).html('<i class="fa fa-exchange" aria-hidden="true"></i>');


            },
            error: function() {
                button.attr('disabled', false).html('<i class="fa fa-exchange" aria-hidden="true"></i>');
                alert('Não foi possível exibir os dados refetentes à isca');
            }
        });
    }

    function desvincularIsca(button, id_isca) {
        // let button = //$("#desvincularIsca"+id_isca);
        buttonText = button.html();
        button.attr('disabled', true).html(
            '<i class="fa fa-spinner fa-spin"></i> ' +
            (buttonText == 'Desvincular selecionados' ? 'Desvinculando' : ''));

        dados = {
            id_isca: id_isca.toString().split(',')
        }

        if (!id_isca) {
            alert('Selecione pelo menos uma isca.');
            button.attr('disabled', false).html(buttonText);
            return false;
        }

        confirma = confirm("Você tem certeza que deseja desvincular a(s) isca(s) do cliente?");
        if (confirma) {

            $.ajax({
                url: '<?= site_url("iscas/isca/desvincularIsca") ?>',
                type: 'POST',
                data: dados,
                success: function(callback) {
                    let data = JSON.parse(callback);
                    alert(
                        data.msg +
                        ((data.falhas.length > 0) ?
                            '\nAs seguintes iscas não foram desvinculadas: ' + data.falhas :
                            ''
                        )
                    );
                    button.attr('disabled', false).html(buttonText);

                    // Lista iscas cadastradas vinculadas a um cliente
                    dtIscasVinculadas.ajax.reload();
                },
                error: function() {
                    button.attr('disabled', false).html(buttonText);
                    alert('Não foi possível desvincular à isca');
                }
            });
        } else {
            button.attr('disabled', false).html(buttonText);
        }
    }

    function limparDadosNovoCliente() {
        // $("#id_isca").val("");
        $("#cnpjBuscarNovoCliente").val("");
        $("#cpfBuscarNovoCliente").val("");
        $("#IdContratoBuscarNovoCliente").val("");
        $("#nomeNovoCliente").html("");
        $("#cnpjNovoCliente").html("");
        $("#enderecoNovoCliente").html("")
        // $("#idNovoCliente").val("");
        // $("#idContratoNovoCliente").val("");
        // $("#id_isca").val(""),
        $("#idNovoCliente").val(""),
            $("#idContratoNovoCliente").val("")

        $('#dadosNovoCliente').css('display', 'none');
    }

    function exibirDadosModalMigrarIscaCliente(dados) {
        // console.log(dados)
        $("#id_isca").val(dados.id);
        $("#serialIsca").html(dados.serial);
        $("#descricaoIsca").html(formatarString(dados.descricao));
        $("#modeloIsca").html(formatarString(dados.modelo));
        $("#marcaIsca").html(dados.marca);
        $("#dataCadastroIsca").html(formatDateTime(dados.data_cadastro));
        $("#statusIsca").html(returnStatusAtivoInativo(dados.status));
        $("#nomeCliente").html(dados.nome);
        $("#cnpjCliente").html(dados.cnpj);
        $("#dataContrato").html(formatDate(dados.data_contrato));

        $("#tipoBuscaCliente").val("cnpj");
        $("#cnpjBuscarNovoCliente").css('display', 'block');
        $("#cpfBuscarNovoCliente").css('display', 'none');
        $("#cpfBuscarNovoCliente").val("");
        $("#idContratoBuscarNovoCliente").css('display', 'none');
        $("#idContratoBuscarNovoCliente").val("");

        $("#modalMigrarIsca").modal('show');
    }
    /**
     * Caso a string ultrapasse 20 caracteres, formata a string para não ultrapassar o limite da coluna
     * @param String
     */
    function formatarString(string) {
        if (string.length > 20) {
            return `<div style="cursor:pointer" title="${string}">${string.slice(0,20)}...</div>`;
        } else {
            return string;
        }
    }

    $('#btnDesvincular').on('click', () => {
        selecionados = checksIscas.join();

        desvincularIsca($('#btnDesvincular'), selecionados);
    });
</script>