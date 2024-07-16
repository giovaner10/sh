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
        max-width: 100px;
        word-wrap: break-word;
        text-align: center;
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

    .top.length-selector {
        margin-top: 20px;
    }

    .dt-button {
        margin-top: 5px;
        margin-bottom: 5px;
    }
</style>

<h3><?= lang("fornecedores") ?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?= site_url('Homes') ?>">Home</a> >
    <?= lang('logistica') ?> >
    <?= lang('fornecedores') ?>
</div>

<div id="modalEditarFornecedores" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditarSetor">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Editar Fornecedor</h3>
                </div>
                <div id="bodyModalEditarFornecedores" class="modal-body scrollModal">
                    <div class="col-md-6 form-group" hidden>
                        <label class="control">Id Empresa</label>
                        <input type="text" class="form-control" id="idEmpresaEditar" disabled>
                    </div>
                    <div class="col-md-6 form-group" hidden>
                        <label class="control-label">Id Fornecedor</label>
                        <input type="text" class="form-control" id="idFornecedorEditar" disabled>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Nome</label>
                        <input type="text" class="form-control" id="nomeFornecedorEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">CPF/CNPJ</label>
                        <input type="text" class="form-control" id="cpfCnpjFornecedorEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">CEP</label>
                        <input type="text" class="form-control" id="cepFornecedorEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Rua</label>
                        <input type="text" class="form-control" id="ruaFornecedorEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Bairro</label>
                        <input type="text" class="form-control" id="bairroFornecedorEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control">Complemento</label>
                        <input type="text" class="form-control" id="complementoFornecedorEditar" placeholder="Digite o Número/Complemento">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Cidade</label>
                        <input type="text" class="form-control" id="cidadeFornecedorEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Estado</label>
                        <select type="text" class="form-control input-sm" id="ufFornecedorEditar">
                            <option selected disabled value="0">Selecione</option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MT">MT</option>
                            <option value="MS">MS</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                        <a class="btn btn-primary" id="btnSalvarEditFornecedores">Salvar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalCadFornecedor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadSetor">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?= lang("novo_fornecedor") ?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nomeFornecedor" placeholder="Nome do Fornecedor">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">CPF/CNPJ</label>
                                            <input type="text" class="form-control" id="cpfCnpjFornecedor" placeholder="CPF/CNPJ do Fornecedor">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">CEP</label>
                                            <input type="text" class="form-control" id="cepFornecedor" placeholder="Digite o cep">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Rua</label>
                                            <input type="text" class="form-control" id="ruaFornecedor" placeholder="Digite a rua">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Bairro</label>
                                            <input type="text" class="form-control" id="bairroFornecedor" placeholder="Digite o bairro">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control">Complemento</label>
                                            <input type="text" class="form-control" id="complementoFornecedor" placeholder="Digite o Número/Complemento">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Cidade</label>
                                            <input type="text" class="form-control" id="cidadeFornecedor" placeholder="Digite a cidade">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Estado</label>
                                            <select type="text" class="form-control input-sm" id="ufFornecedor">
                                                <option selected disabled value="0">Selecione</option>
                                                <option value="AC">AC</option>
                                                <option value="AL">AL</option>
                                                <option value="AP">AP</option>
                                                <option value="AM">AM</option>
                                                <option value="BA">BA</option>
                                                <option value="CE">CE</option>
                                                <option value="DF">DF</option>
                                                <option value="ES">ES</option>
                                                <option value="GO">GO</option>
                                                <option value="MA">MA</option>
                                                <option value="MT">MT</option>
                                                <option value="MS">MS</option>
                                                <option value="MG">MG</option>
                                                <option value="PA">PA</option>
                                                <option value="PB">PB</option>
                                                <option value="PR">PR</option>
                                                <option value="PE">PE</option>
                                                <option value="PI">PI</option>
                                                <option value="RJ">RJ</option>
                                                <option value="RN">RN</option>
                                                <option value="RS">RS</option>
                                                <option value="RO">RO</option>
                                                <option value="RR">RR</option>
                                                <option value="SC">SC</option>
                                                <option value="SP">SP</option>
                                                <option value="SE">SE</option>
                                                <option value="TO">TO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group" style="margin-right: 1px;">
                                            <label class="control-label">Empresa</label>
                                            <select class="form-control input-sm" id="idEmpresaFornecedor"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarCadastroFornecedor">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid my-1">
    <div class="alert alert-info">Pesquise os fornecedores pelo nome da empresa, nome do fornecedor ou registro (CPF/CNPJ):
        <br>
        <br>
        <form id="formPesquisa">
            <div class="row">
                <div class="col-md-2" style="margin-bottom: 5px;">
                    <select class="form-control input-sm" id="searchType" name="searchType">
                        <option value="empresa">Empresa</option>
                        <option value="fornecedor">Fornecedor</option>
                        <option value="registro">Registro (CPF/CNPJ)</option>
                    </select>
                </div>
                <div class="col-md-4" style="margin-bottom: 5px;">
                    <select class="form-control input-sm" id="pesqnome" name="value" style="width: 100%"></select>
                </div>
                <div class="col-md-4 button-container">
                    <div class="button-aligner">
                        <button class="btn btn-primary" id="BtnPesquisar" type="button"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-primary" id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="container-fluid" id="tabelaGeral">
                <br>
                <a id="abrirModalInserir" class="btn btn-primary"><?= lang('novo_fornecedor') ?></a>
                <table class="table table-striped table-bordered table-hover responsive" id="tabelaTransportadores" style="width: 100%">
                    <thead>
                        <th>Empresa</th>
                        <th>Fornecedor</th>
                        <th>CPF/CNPJ</th>
                        <th>Cidade</th>
                        <th>Cep</th>
                        <th>Rua</th>
                        <th>Bairro</th>
                        <th>Estado</th>
                        <th>Complemento</th>
                        <th>Data de criação</th>
                        <th>Data de atualização</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<script>
    $(document).ready(function() {
        var tabelaTransportadores = $('#tabelaTransportadores').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            order: [10, 'desc'],
            dom: '<"top length-selector"l><"top button-section"B>frtip',
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder: 'Pesquisar',
                emptyTable: "Nenhum fornecedor a ser listado",
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
            deferRender: true,
            lengthChange: false,
            lengthChange: false,
            ajax: {
                url: '<?= site_url('GestaoDeChips/fornecedores/listarAllFornecedores') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 200) {
                        tabelaTransportadores.clear().draw();
                        tabelaTransportadores.rows.add(data.dados).draw();
                        $('#tabelaGeral').show();
                        $('#alert').hide();
                    } else {
                        alert('Erro ao carregar fornecedores.');
                        tabelaTransportadores.clear().draw();
                    }
                },
                error: function() {
                    alert('Erro ao carregar fornecedores.');
                    tabelaTransportadores.clear().draw();
                }
            },
            columns: [{
                    data: 'nomeEmpresa'
                },
                {
                    data: 'nomeFornecedor'
                },
                {
                    data: 'cpfCnpjFornecedor',
                    render: function(data) {
                        if (data.length === 11) {
                            return data.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                        } else if (data.length === 14) {
                            return data.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: 'cidade'
                },
                {
                    data: 'cep'
                },
                {
                    data: 'rua'
                },
                {
                    data: 'bairro'
                },
                {
                    data: 'estado'
                },
                {
                    data: 'complemento'
                },
                {
                    data: 'dataCadastro',
                    render: function(data) {
                        return new Date(data).toLocaleString();
                    }
                },
                {
                    data: 'dataAtualizacao',
                    render: function(data) {
                        return new Date(data).toLocaleString();
                    }
                },
                {
                    data: 'status'
                },
                {
                    data: {
                        'id': 'id',
                        'status': 'status',
                        'nomeFornecedor': 'nomeFornecedor',
                        'cpfCnpjFornecedor': 'cpfCnpjFornecedor',
                        'cidade': 'cidade',
                        'cep': 'cep',
                        'rua': 'rua',
                        'bairro': 'bairro',
                        'estado': 'estado',
                        'complemento': 'complemento',
                        'idEmpresa': 'idEmpresa',
                    },
                    //orderable: false,
                    render: function(data) {
                        return `
                        <button 
                            class="btn fa fa-pencil-square-o"
                            title="Editar Fornecedor"
                            style="width: 38px; margin: 4px auto; background-color: #04acf4; color: white;"
                            id="btnEditarSetor"
                            onClick="javascript:editFornecedor(this,'${data['id']}','${data['status']}','${data['nomeFornecedor']}',
                                                                        '${data['cpfCnpjFornecedor']}','${data['cidade']}','${data['cep']}',
                                                                        '${data['rua']}','${data['bairro']}','${data['estado']}','${data['complemento']}', '${data['idEmpresa']}'
                                                                )">
                        </button>
                        <button
                            class="btn fa fa-exchange"
                            title="Alterar Status"
                            style="width: 38px; margin: 4px auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                            id="btnAlterarStatusFornecedor"
                            onClick="javascript:alterarStatusFornecedor(this,'${data['id']}', '${data['status']}')">
                        </button>
                        `;
                    }
                }
            ],
            buttons: [{
                    extend: 'excelHtml5',
                    filename: 'Relatório de Fornecedores',
                    title: 'Relatório de Fornecedores',

                    exportOptions: {
                        columns: ':visible:not(:last-child):not(:nth-last-child(2))'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> Exportar para Excel'
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'Relatório de Fornecedores',
                    exportOptions: {
                        columns: ':visible:not(:last-child):not(:nth-last-child(2))'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> Exportar para PDF',
                    customize: function(document) {
                        var titulo = "Relatório de Fornecedores";
                        pdfTemplateIsolatedFornecedor(document, titulo)
                    }
                },
                {
                    extend: 'csvHtml5',
                    filename: 'Relatório de Fornecedores',
                    exportOptions: {
                        columns: ':visible:not(:last-child):not(:nth-last-child(2))'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> Exportar para CSV'
                },
                {
                    extend: 'print',
                    filename: 'Relatório de Fornecedores',
                    exportOptions: {
                        columns: ':visible:not(:last-child):not(:nth-last-child(2))'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> Imprimir',
                    customize: function(window) {
                        var titulo = "Relatório de Fornecedores";
                        printTemplateImpressaoFornecedores(window, titulo);
                    }
                }
            ]
        });

        $('#cpfCnpjFornecedor').inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99'],
            keepStatic: true
        });

        $('#cpfCnpjFornecedorEditar').inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99'],
            keepStatic: true
        });

        $('#BtnLimpar').click(function() {
            var table = $('#tabelaTransportadores').DataTable();
            table.clear().draw();
            $('#pesqnome').val(null).trigger('change');

        });

        $('#idEmpresaFornecedor').select2({
            ajax: {
                url: '<?= site_url('GestaoDeChips/fornecedores/listarEmpresas') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.dados
                    };
                }
            },
            width: '100%',
            placeholder: 'Selecione uma empresa',
            allowClear: true,
            language: "pt-BR",
        });

        $('#idEmpresaFornecedor').on('change', function() {
            var valor = $(this).val();
            $('#idEmpresaFornecedor').val(valor);

        });

        function setPesqNomeSelect2(searchType) {
            var url;
            var placeholder;

            if (searchType === 'empresa') {
                url = '<?= site_url('setores/buscarEmpresas') ?>';
                placeholder = "Digite o nome da empresa";
            } else if (searchType === 'fornecedor') {
                url = '<?= site_url('setores/buscarFornecedores') ?>';
                placeholder = "Digite o nome do fornecedor";
            } else {
                url = '<?= site_url('setores/buscarRegistros') ?>';
                placeholder = "Digite o número de registro (CPF/CNPJ)";
            }

            $('#pesqnome').select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 1000,
                    type: 'GET',
                    data: function(params) {
                        var term = params.term || '';
                        return {
                            q: term
                        };
                    },
                },
                placeholder: placeholder,
                allowClear: true,
                minimumInputLength: 0,
                language: "pt-BR",
            })
        }

        setPesqNomeSelect2($('#searchType').val());

        $('#searchType').change(function() {

            $('#pesqnome').val(null).trigger('change').select2('destroy');

            setPesqNomeSelect2($(this).val());
        });
    });

    $(document).ready(function() {
        var tabelaTransportadores = $('#tabelaTransportadores').DataTable();
        var valor = "";

        $('#pesqnome').on('select2:selecting', function(e) {
            valor = e.params.args.data.text;
        });

        $('#BtnPesquisar').click(function(e) {
            // Carregando
            $('#BtnPesquisar')
                .attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');

            e.preventDefault();

            var searchType = $('#searchType').val();
            var nome = $('#pesqnome').val();

            var dataToSend = {
                tipo: searchType
            };

            if (searchType === "empresa") {
                dataToSend.idEmpresa = nome;
            } else if (searchType === "registro") {
                dataToSend.registro = valor;
            } else if (searchType === "fornecedor") {
                dataToSend.fornecedor = valor;
            }

            $.ajax({
                url: '<?= site_url('GestaoDeChips/fornecedores/buscarFornecedores') ?>',
                type: 'POST',
                data: dataToSend,
                dataType: 'json',
                success: function(data) {
                    if (data.status === 200) {
                        tabelaTransportadores.clear().draw();
                        tabelaTransportadores.rows.add(data.dados).draw();
                        $('#tabelaGeral').show();
                        $('#alert').hide();
                        $('#BtnPesquisar')
                            .attr('disabled', false)
                            .html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
                    } else {
                        alert('Não existem fornecedores para essa empresa')
                        $('#BtnPesquisar')
                            .attr('disabled', false)
                            .html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
                        tabelaTransportadores.clear().draw();

                    }
                }
            })
        });
    })

    function editFornecedor(botao, id, status, nomeFornecedor, CpfCnpjFornecedor, cidade, cep, rua, bairro, estado, complemento, idEmpresa) {
        $('#modalEditarFornecedores').modal('show');
        $('#idFornecedorEditar').val(id);
        $('#idEmpresaEditar').val(idEmpresa);
        $('#statusFornecedorEditar').val(status);
        $('#nomeFornecedorEditar').val(nomeFornecedor);
        $('#cpfCnpjFornecedorEditar').val(CpfCnpjFornecedor);
        $('#cidadeFornecedorEditar').val(cidade);
        $('#cepFornecedorEditar').val(cep);
        $('#ruaFornecedorEditar').val(rua);
        $('#bairroFornecedorEditar').val(bairro);
        $('#ufFornecedorEditar').val(estado);
        $('#complementoFornecedorEditar').val(complemento);
    }

    $('#btnSalvarEditFornecedores').click(event => {
        $('#btnSalvarEditFornecedores')
            .html('<i class="fa fa-spin fa-spinner"></i> <?= lang('carregando') ?>')
            .attr('disabled', true);
        var idFornecedor = $('#idFornecedorEditar').val();
        var idEmpresa = $('#idEmpresaEditar').val();
        var nome = $('#nomeFornecedorEditar').val();
        var cpfCnpj = $('#cpfCnpjFornecedorEditar').val();
        var cep = $('#cepFornecedorEditar').val();
        var rua = $('#ruaFornecedorEditar').val();
        var bairro = $('#bairroFornecedorEditar').val();
        var cidade = $('#cidadeFornecedorEditar').val();
        var complemento = $('#complementoFornecedorEditar').val();
        var uf = $('#ufFornecedorEditar').val();
        var tabelaTransportadores = $('#tabelaTransportadores').DataTable();
        $.ajax({
            url: '<?= site_url('GestaoDeChips/fornecedores/editarFornecedores') ?>',
            type: 'POST',
            data: {
                idFornecedor: idFornecedor,
                nome: nome,
                idEmpresa: idEmpresa,
                registro: cpfCnpj,
                cep: cep,
                rua: rua,
                bairro: bairro,
                cidade: cidade,
                complemento: complemento,
                uf: uf,
            },
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    alert('Fornecedor editado com sucesso!');
                    $('#modalEditarFornecedores').modal('hide');
                    $('#nomeFornecedorEditar').val('');
                    $('#idFornecedorEditar').val('');
                    $('#idEmpresaEditar').val('');
                    $('#cpfCnpjFornecedorEditar').val('');
                    $('#cepFornecedorEditar').val('');
                    $('#ruaFornecedorEditar').val('');
                    $('#bairroFornecedorEditar').val('');
                    $('#cidadeFornecedorEditar').val('');
                    $('#complementoFornecedorEditar').val('');
                    $('#ufFornecedorEditar').val('');


                    var nome = $('#pesqnome').val();
                    $.ajax({
                        url: '<?= site_url('GestaoDeChips/fornecedores/listarAllFornecedores') ?>',
                        type: 'POST',
                        dataType: 'json',
                        success: function(data) {
                            if (data.status === 200) {
                                tabelaTransportadores.clear().draw();
                                tabelaTransportadores.rows.add(data.dados).draw();
                                $('#btnSalvarEditFornecedores')
                                    .html('Salvar')
                                    .attr('disabled', false);
                            } else {
                                alert('Não existem fornecedores para essa empresa')
                                tabelaTransportadores.clear().draw();
                                $('#btnSalvarEditFornecedores')
                                    .html('Salvar')
                                    .attr('disabled', false);
                            }
                        }
                    })
                } else {
                    $('#btnSalvarEditFornecedores')
                        .html('Salvar')
                        .attr('disabled', false);

                    switch (data.status) {
                        case 400:
                            alert("Falha ao editar fornecedor. Verifique os dados e tente novamente!");
                            break;
                        case 404:
                            alert(data.dados.mensagem);
                            break;
                        default:
                            alert("Falha ao editar fornecedor. Tente novamente mais tarde ou contate nosso administrador.");
                            break;
                    }
                }
            },
            error: function(data) {
                $('#btnSalvarEditFornecedores')
                    .html('Salvar')
                    .attr('disabled', false);
                alert("Ocorreu um erro ao editar os dados. Por favor, tente mais tarde ou contate nosso administrador.");
            }
        })
    })

    $('#abrirModalInserir').click(event => {
        $('#modalCadFornecedor').modal('show')
        $('#idEmpresaFornecedor').val('');
        $('#nomeFornecedor').val('');
        $('#cpfCnpjFornecedor').val('');
        $('#cepFornecedor').val('');
        $('#ruaFornecedor').val('');
        $('#bairroFornecedor').val('');
        $('#cidadeFornecedor').val('');
        $('#ufFornecedor').val(0);
        $('#complementoFornecedor').val('');
    })

    $('#btnSalvarCadastroFornecedor').click(event => {
        $('#btnSalvarCadastroFornecedor')
            .html('<i class="fa fa-spin fa-spinner"></i> <?= lang('carregando') ?>')
            .attr('disabled', true);
        var nomeFornecedor = $('#nomeFornecedor').val();
        var cpfCnpj = $('#cpfCnpjFornecedor').val();
        var cep = $('#cepFornecedor').val();
        var rua = $('#ruaFornecedor').val();
        var bairro = $('#bairroFornecedor').val();
        var cidade = $('#cidadeFornecedor').val();
        var uf = $('#ufFornecedor').val();
        var complemento = $('#complementoFornecedor').val();
        var idEmpresa = $('#idEmpresaFornecedor').val();
        var tabelaTransportadores = $('#tabelaTransportadores').DataTable();
        $.ajax({
            url: '<?= site_url('GestaoDeChips/fornecedores/inserirFornecedor') ?>',
            type: 'POST',
            data: {
                nome: nomeFornecedor,
                cpfCnpj: cpfCnpj,
                cep: cep,
                rua: rua,
                bairro: bairro,
                cidade: cidade,
                uf: uf,
                complemento: complemento,
                idEmpresa: idEmpresa
            },
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    alert(data.dados.mensagem)
                    $('#modalCadFornecedor').modal('hide')
                    $('#nomeFornecedor').val('');
                    $('#cpfCnpjFornecedor').val('');
                    $('#cepFornecedor').val('');
                    $('#ruaFornecedor').val('');
                    $('#bairroFornecedor').val('');
                    $('#cidadeFornecedor').val('');
                    $('#ufFornecedor').val('');
                    $('#complementoFornecedor').val('');
                    $('#idEmpresaFornecedor').val('');
                    $.ajax({
                        url: '<?= site_url('GestaoDeChips/fornecedores/listarAllFornecedores') ?>',
                        type: 'POST',
                        dataType: 'json',
                        success: function(data) {
                            if (data.status === 200) {
                                tabelaTransportadores.clear().draw();
                                tabelaTransportadores.rows.add(data.dados).draw();
                                $('#btnSalvarEditFornecedores')
                                    .html('Salvar')
                                    .attr('disabled', false);
                            } else {
                                alert('Não existem fornecedores para essa empresa')
                                tabelaTransportadores.clear().draw();
                                $('#btnSalvarEditFornecedores')
                                    .html('Salvar')
                                    .attr('disabled', false);
                            }
                        }
                    })
                } else if (data.status === 400 && data.dados.mensagem != null) {
                    $('#btnSalvarCadastroFornecedor')
                        .html('Salvar')
                        .attr('disabled', false);
                    alert(data.dados.mensagem);
                } else {
                    $('#btnSalvarCadastroFornecedor')
                        .html('Salvar')
                        .attr('disabled', false);

                }
            }
        })
    })


    $(document).ready(function() {
        // Máscaras
        $('#cepTransportadorEditar, #cepTransportador, #cepFornecedorEditar, #cepFornecedor').mask('00000-000');
        $('#cpfCnpjTransportadorEditar, #cpfCnpjTransportador').inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99'],
            keepStatic: true
        });

        // Busca informações de CEP para Edição de Transportador
        $("#cepTransportadorEditar").blur(function() {
            buscarCEP(this.value, "#ruaTransportadorEditar", "#bairroTransportadorEditar", "#cidadeTransportadorEditar", "#ufTransportadorEditar");
        });

        // Busca informações de CEP para Cadastro de Transportador
        $("#cepTransportador").blur(function() {
            buscarCEP(this.value, "#ruaTransportador", "#bairroTransportador", "#cidadeTransportador", "#ufTransportador");
        });

        // Busca informações de CEP para Edição de Fornecedor
        $("#cepFornecedorEditar").blur(function() {
            buscarCEP(this.value, "#ruaFornecedorEditar", "#bairroFornecedorEditar", "#cidadeFornecedorEditar", "#ufFornecedorEditar");
        });

        // Busca informações de CEP para Cadastro de Fornecedor
        $("#cepFornecedor").blur(function() {
            buscarCEP(this.value, "#ruaFornecedor", "#bairroFornecedor", "#cidadeFornecedor", "#ufFornecedor");
        });
    });

    function buscarCEP(cep, ruaSelector, bairroSelector, cidadeSelector, ufSelector) {
        cep = cep.replace(/\D/g, "");

        if (cep.length !== 8) {
            alert('CEP inválido!');
            return;
        }

        var url = "https://viacep.com.br/ws/" + cep + "/json";
        $.getJSON(url)
            .done(function(dadosRetorno) {
                if (!dadosRetorno.erro) {
                    $(ruaSelector).val(dadosRetorno.logradouro);
                    $(bairroSelector).val(dadosRetorno.bairro);
                    $(cidadeSelector).val(dadosRetorno.localidade);
                    $(ufSelector).val(dadosRetorno.uf);
                } else {
                    alert('CEP não encontrado.');
                }
            })
            .fail(function() {
                alert('Erro ao buscar CEP.');
            });
    }


    function alterarStatusFornecedor(botao, id, status) {
        var tabelaTransportadores = $('#tabelaTransportadores').DataTable();
        if (confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')) {
            var idEmpresa = $('#pesqnome').val();
            if (status == 'Ativo') {
                status = 0
            } else {
                status = 1
            }
            $.ajax({
                url: '<?= site_url('GestaoDeChips/fornecedores/alterarStatusFornecedor') ?>',
                type: 'POST',
                data: {
                    idFornecedor: id,
                    status: status
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        $.ajax({
                            url: '<?= site_url('GestaoDeChips/fornecedores/listarAllFornecedores') ?>',
                            type: 'POST',
                            dataType: 'json',
                            success: function(data) {
                                if (data.status === 200) {
                                    tabelaTransportadores.clear().draw();
                                    tabelaTransportadores.rows.add(data.dados).draw();
                                } else {
                                    alert('Não existem fornecedores para essa empresa')
                                }
                            }
                        })
                    } else {
                        alert(data.dados.mensagem);
                    }
                }
            })
        } else {
            return false;
        }
    }
</script>

Traduções
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>