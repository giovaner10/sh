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

    .bord {
        border-left: 3px solid #03A9F4;
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
        max-width: 150px;
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

    .button-container {
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .button-aligner {
        display: flex;
        gap: 10px;
        margin-top: 22px;
    }

    .tabs {
        width: 100%;
        display: inline-block;
    }

    .tab-links:after {
        display: block;
        clear: both;
        content: '';
    }

    .tab-links li {
        margin: 0px 5px;
        list-style: none;
        display: inline-block;
    }

    .tab-links {
        text-align: center;
        padding-bottom: 20px;
    }

    .tab-links a {
        padding: 9px 15px;
        display: inline-block;
        border-radius: 3px 3px 0px 0px;
        background: #7FB5DA;
        font-size: 16px;
        font-weight: 600;
        color: #4c4c4c;
        transition: all linear 0.15s;
        text-decoration: none;
    }

    .tab-links a:hover {
        background: #a7cce5;
        text-decoration: none;
    }

    .tab-content {
        border-radius: 3px;
        box-shadow: -1px 1px 1px rgba(0, 0, 0, 0.15);
        background: #fff;
        margin-top: 30px;
        padding: 15px;
    }

    .tab {
        display: none;
    }

    .tab.active {
        display: block;
    }

    .top.length-selector {
    margin-top: 20px;
    }
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 25px;
    }

    /* Hide default HTML checkbox */
    .switch input {display:none;}

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(34px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
    
</style>

<h3><?= lang('vendedores') ?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?= site_url('Homes') ?>">Home</a> >
    <?= lang('departamentos') ?> >
    <a href="<?= site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais') ?>"><?= lang('comercial_e_televendas') ?></a> >
    <?= lang('comissionamento_vendas') ?>
</div>

<hr>

<div class="alert alert-info col-md-12" style="margin-bottom: 50px;">
    <p class="col-md-12">Informe o nome, empresa, regional ou cargo para buscar as informações do Vendedor:</p>
    <br>
    <span class="help help-block"></span>

    <form action="" id="formBusca">
        <div class="form-group">
            <div class="col-md-3">
                <label for="searchTypeData">Buscar por:</label>
                <select id="tipoData" name="tipoData" class="form-control">
                    <option value="nome">Nome</option>
                    <option value="empresa">Empresa</option>
                    <option value="regional">Regional</option>
                    <option value="cargo">Cargo</option>
                </select>
            </div>

            <div class="col-md-4 input-container" id="nomeContainer">
                <label for="nomeInput">Nome:</label>
                <input type="hidden" id="nomeInputHidden" name="nomeId">
                <select class="form-control input-sm" id="nomeInput" name="name" type="text" style="width: 100%"></select>
            </div>

            <div class="col-md-4 input-container" id="empresaContainer" style="display:none;">
                <label for="empresaInput">Empresa:</label>
                <select class="form-control input-sm" id="empresaInput" name="empresa" type="text" style="width: 100%"></select>
            </div>

            <div class="col-md-4 input-container" id="regionalContainer" style="display:none;">
                <label for="regionalInput">Regional:</label>
                <select class="form-control input-sm" id="regionalInput" name="regional" type="text" style="width: 100%"></select>
            </div>

            <div class="col-md-4 input-container" id="cargoContainer" style="display:none;">
                <label for="cargoInput">Cargo:</label>
                <select class="form-control input-sm" id="cargoInput" name="cargo" type="text" style="width: 100%"></select>
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

<div id="vendedores" class="tab-pane fade in active" style="margin-top: 20px">
    <div class="container-fluid" id="tabelaGeral">
        <a id="abrirModalInserirVendedor" class="btn btn-primary">Novo Vendedor</a>
        <table class="table table-bordered table-striped table-hover responsive nowrap" id="tabelaVendedores">
            <thead>
                <tr class="tableheader">
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Empresa</th>
                    <th>Regional</th>
                    <th>Cargo</th>
                    <th>Salário</th>
                    <th>Data de Atualização</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Cadastro/Edição de Vendedor -->
<div id="modalCadVendedor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadVendedor" id="formCadastroVendedor">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="nomeHeaderModal"></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="col-md-12">
                        <div id="div_identificacao">
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Matrícula</label>
                                    <input type="text" class="form-control input-sm" name="matriculaVendedor" id="matriculaVendedor" placeholder="Digite a matrícula do vendedor" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Nome</label>
                                    <input type="text" class="form-control input-sm" name="nomeVendedor" id="nomeVendedor" placeholder="Digite o nome do vendedor" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control input-sm" name="emailVendedor" id="emailVendedor" placeholder="Digite o email do vendedor" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Data de Admissão</label>
                                    <input type="date" class="form-control input-sm" name="dataAdmissaoVendedor" id="dataAdmissaoVendedor" placeholder="Digite a data de admissão do vendedor" required>
                                </div>
                            </div>
                            <div class="row">
                                <div id="divSelectEmpresaCad" class="col-md-6 form-group bord">
                                    <label class="control-label">Empresa: </label>
                                    <select class="form-control input-sm" id="selectEmpresaCad" name="selectEmpresaCad" type="text" required></select>
                                </div>
                                <div id="divSelectEmpresaEdit" class="col-md-6 form-group bord" hidden>
                                    <label class="control-label">Empresa: </label>
                                    <select class="form-control input-sm" id="selectEmpresaEdit" name="selectEmpresaEdit" type="text"></select>
                                </div>
                                <div id="divSelectRegionalCad" class="col-md-6 form-group bord">
                                    <label class="control-label">Regional: </label>
                                    <select class="form-control input-sm" id="selectRegionalCad" name="selectRegionalCad" type="text" required>
                                        <option value="" disabled selected>Selecione a regional</option>
                                    </select>
                                </div>
                                <div id="divSelectRegionalEdit" class="col-md-6 form-group bord" hidden>
                                    <label class="control-label">Regional: </label>
                                    <select class="form-control input-sm" id="selectRegionalEdit" name="selectRegionalEdit" type="text"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div id="divSelectCargolCad" class="col-md-6 form-group bord">
                                    <label class="control-label">Cargo: </label>
                                    <select class="form-control input-sm" id="selectCargoCad" name="selectCargoCad" type="text" required>
                                        <option value="" disabled selected>Selecione o cargo</option>
                                    </select>
                                </div>
                                <div id="divSelectCargolEdit" class="col-md-6 form-group bord" hidden>
                                    <label class="control-label">Cargo: </label>
                                    <select class="form-control input-sm" id="selectCargoEdit" name="selectCargoEdit" type="text"></select>
                                </div>
                                <div id="divChefiaCad" class="col-md-6 form-group bord">
                                    <label class="control-label">Chefia Imediata</label>
                                    <select class="form-control input-sm" id="chefiaVendedor" required>
                                    </select>
                                </div>
                                <div id="divChefiaEdit" class="col-md-6 form-group bord" hidden>
                                    <label class="control-label">Chefia Imediata</label>
                                    <select class="form-control input-sm" id="chefiaVendedorEdit">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Código - Centro de Resultado</label>
                                    <input type="text" class="form-control input-sm" name="codigoCentroResultadoVendedor" id="codigoCentroResultadoVendedor" placeholder="Digite o código do centro de resultado" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Nome - Centro de Resultado</label>
                                    <input type="text" class="form-control input-sm" name="nomeCentroResultadoVendedor" id="nomeCentroResultadoVendedor" placeholder="Digite o nome do centro de resultado" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Salário (R$)</label>
                                    <input type="text" class="form-control input-sm" name="salarioVendedor" id="salarioVendedor" onkeyup="formatarMoeda(this.id)" placeholder="Digite o salário do vendedor" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Garantia de Comissão</label>
                                        <label class="switch" style="margin-left: 35px;">
                                          <input type="checkbox" id="checkGarantiaComissao">
                                          <div class="slider round"></div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 form-group" id="divValorGarantia" style="padding: initial;">
                                        <label class="control-label">Valor Garantia (R$)</label>
                                        <input type="text" class="form-control input-sm" name="valorGarantia" id="valorGarantia" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor da garantia">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord" id="divStatus" hidden>
                                    <label class="control-label">Status</label>
                                    <select class="form-control input-sm" id="statusVendedor">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-id='' type="submit" id="btnSalvarCadastroVendedor" style="margin-right: 15px;">Salvar</button>
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
<!-- Traduções -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

<script>
    $(document).ready(function() {
        var btnPesquisar = $('#BtnPesquisar');
        var tabelaVendedores = $('#tabelaVendedores').DataTable({
            responsive: true,
                ordering: true,
                paging: true,
                searching: true,
                info: true,
                order: [5, 'desc'],
                dom: '<"top length-selector"l><"top button-section"B>frtip',
                language: {
                    loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                    searchPlaceholder: 'Pesquisar',
                    emptyTable: "Nenhum vendedor a ser listado",
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
                ajax: {
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendedores') ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 200) {
                            tabelaVendedores.clear().draw();
                            tabelaVendedores.rows.add(data.results).draw();
                        } else {
                            tabelaVendedores.clear().draw();
                        }
                    },
                    error: function() {
                        alert('Erro ao buscar vendedores. Tente novamente');
                        tabelaVendedores.clear().draw();
                    }
                },
                columns: [{
                        data: 'id',
                        visible: false
                    },
                    {
                        data: 'nome'
                    },
                    {
                        data: 'nomeEmpresa'
                    },
                    {
                        data: 'nomeRegional'
                    },
                    {
                        data: 'nomeCargo'
                    },
                    {
                        data: 'salario',
                        render: function(data) {
                            if (data === null) {
                                return 'Não consta';
                            } else {
                                return 'R$ ' + data;
                            }
                        }
                    },
                    {
                        data: 'dataUpdate',
                        render: function(data) {
                            var date = new Date(data);
                            date.setDate(date.getDate());
                            return date.toLocaleDateString('pt-BR');
                        }
                    },
                    {
                        data: 'status',
                        visible: false
                    },
                    {
                        data: {
                            'nome': 'nome',
                            'idEmpresa': 'idEmpresa',
                            'idRegional': 'idRegional',
                            'idCargo': 'idCargo',
                            'dataCadastro': 'dataCadastro',
                            'dataUpdate': 'dataUpdate',
                            'status': 'status'
                        },
                        orderable: false,
                        render: function(data) {
                            return `
                            <button 
                                class="btn btn-primary"
                                title="Editar Vendedor"
                                id="btnEditarVendedor"
                                onClick="javascript:editVendedor(this,'${data['id']}','${data['nome']}','${data['idEmpresa']}','${data['idRegional']}', '${data['idCargo']}', '${data['status']}', '${data['matricula']}', '${data['salario']}', '${data['email']}', '${data['garantia']}', '${data['codigoCentroResultado']}', '${data['nomeCentroResultado']}', '${data['dataAdmissao']}', '${data['chefiaImediata']}', '${data['valorGarantia']}')">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            `;
                        }
                    }

                ],
                buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'Relatório de Vendedores',
                    title: 'Relatório de Vendedores',
                    exportOptions: { columns: ':visible:not(:last-child)' },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> Exportar para Excel'
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'Relatório de Vendedores',
                    exportOptions: { columns: ':visible:not(:last-child)' },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> Exportar para PDF',
                    customize: function(document) {
                        var titulo = filenameGenerator("Relatório de Vendedores");
                        pdfTemplateIsolated(document, titulo)
                    }
                },
                {
                    extend: 'csvHtml5',
                    filename: 'Relatório de Vendedores',
                    exportOptions: { columns: ':visible:not(:last-child)' },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> Exportar para CSV'
                },
                {
                    extend: 'print',
                    filename: 'Relatório de Vendedores',
                    exportOptions: { 
                        columns: ':visible:not(:last-child):not(:nth-child(6))' 
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> Imprimir',
                    customize: function(window) {
                        var titulo = filenameGenerator("Relatório de Vendedores");
                        printTemplateImpressao(window, titulo);
                    }
                }
            ]
            });

            btnPesquisar.on('click', function() {
            var tipoData = $('#tipoData').val();
           
            var dados = {};

            switch (tipoData) {
                case 'nome':
                    dados.nome = $('#nomeInputHidden').val();
                    break;
                case 'empresa':
                    dados.empresa = $('#empresaInput').val();
                    break;
                case 'regional':
                    dados.regional = $('#regionalInput').val();
                    break;
                case 'cargo':
                    dados.cargo = $('#cargoInput').val();
                    break;
            }

            $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');

            if (validarDados(tipoData)) {
                $.ajax({
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendedoresByNomeOrEmpresaOrCargoOrRegional') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: dados,
                    success: function(data) {
                        if (data.status === 200) {
                            tabelaVendedores.clear().draw();
                            tabelaVendedores.rows.add(data.results).draw();
                            resetButton();
                        } else {
                            tabelaVendedores.clear().draw();
                            alert('Erro ao pesquisar vendedores. Tente novamente');
                            resetButton();
                        }
                    },
                    error: function() {
                        tabelaVendedores.clear().draw();
                        alert('Erro ao pesquisar vendedores. Tente novamente');
                        resetButton();
                    }
                });
            } else {
                resetButton();
            }
        });

        $('#BtnLimpar').click(function() {
            var table = $('#tabelaVendedores').DataTable();
            table.clear().draw();
            $('#formBusca')[0].reset();
            $('#nomeInput').val(null).trigger('change');
            $('.input-container').hide();
            $('#nomeContainer').show();
        
        });

        $('.tabs .tab-links a').on('click', function(e) {
            var currentAttrValue = $(this).attr('href');
            $('.tabs ' + currentAttrValue).show().siblings().hide();
            $(this).parent('li').addClass('active').siblings().removeClass('active');
            e.preventDefault();
        });

        $('#tipoData').change(function() {
            $('.input-container').hide();

            switch ($(this).val()) {
                case 'nome':
                    $('#nomeContainer').show();
                    break;
                case 'empresa':
                    $('#empresaContainer').show();
                    break;
                case 'regional':
                    $('#regionalContainer').show();
                    break;
                case 'cargo':
                    $('#cargoContainer').show();
                    break;
            }
        });


        $('#nomeInput').select2({
            ajax: {
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarVendedoresSelect2Nome') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
            },
            placeholder: "Selecione o nome",
            allowClear: true,
            minimumInputLength: 0,
            language: "pt-BR",
            templateSelection: function(item) {
                if (item.id && item.text) {
                    $('#nomeInputHidden').val(item.text);
                }
                return item.text; 
            }
        });

        $('#regionalInput').select2({
            ajax: {
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarRegionaisSelect2') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
                
            },
            placeholder: "Selecione o regional",
            allowClear: true,
            minimumInputLength: 0,
            language: "pt-BR",
        });

        $('#cargoInput').select2({
            ajax: {
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCargosSelect2') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
            },
            placeholder: "Selecione o cargo",
            allowClear: true,
            minimumInputLength: 0,
            language: "pt-BR",
        });



        $('#empresaInput').select2({
            ajax: {
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
            },
            width: '100%',
            placeholder: 'Selecione uma empresa',
            allowClear: true,
            language: "pt-BR",
        });

        function validarDados(tipoData) {
            var valido = true;
            var alertMsg = "";
            switch (tipoData) {
                case 'nome':
                    if (!$('#nomeInput').val()) {
                        alertMsg = "Insira um nome válido.";
                        valido = false;
                    }
                    break;
                case 'empresa':
                    if (!$('#empresaInput').val()) {
                        alertMsg = "Insira uma empresa válida.";
                        valido = false;
                    }
                    break;
                case 'regional':
                    if (!$('#regionalInput').val()) {
                        alertMsg = "Insira um regional válido.";
                        valido = false;
                    }
                    break;
                case 'cargo':
                    if (!$('#cargoInput').val()) {
                        alertMsg = "Insira um cargo válido.";
                        valido = false;
                    }
                    break;
            }

            if (!valido) {
                alert(alertMsg);
            }

            return valido;
        }

        function resetButton() {
            btnPesquisar.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
        }
    });

    $('#abrirModalInserirVendedor').click(async function() {
        btn = $(this);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $('#nomeHeaderModal').html('<?= lang("novo_vendedor") ?>');

        let regionaisCad = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarRegionaisSelect2') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar regionais, tente novamente');
                btn.attr('disabled', false).html('Novo Vendedor');
            }
        });

        $('#selectRegionalCad').select2({
            data: regionaisCad.results,
            placeholder: "Selecione a regional",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });

        $('#selectRegionalCad').on('select2:select', function(e) {
            var data = e.params.data;
        });

        let cargosCad = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCargosSelect2') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar cargos, tente novamente');
                btn.attr('disabled', false).html('Novo Vendedor');
            }
        });

        $('#selectCargoCad').select2({
            data: cargosCad.results,
            placeholder: "Selecione o cargo",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });

        $('#selectCargoCad').on('select2:select', function(e) {
            var data = e.params.data;
        });

        $('#selectCargoCad').val(null).trigger('change');
        $('#selectRegionalCad').val(null).trigger('change');
        btn.attr('disabled', false).html('Novo Vendedor');
        $('#checkGarantiaComissao').prop('checked', false).trigger('change');
        $('#modalCadVendedor').modal('show');

    });

    $('#selectEmpresaCad').select2({
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            type: 'GET',
            dataType: 'json',
            delay: 1000,
            data: function(params) {
                return {
                    q: params.term,
                };
            },
        },
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR",
    });

    async function editVendedor(botao, id, nome, idEmpresa, idRegional, idCargo, status, matricula, salario, email, garantia, codigoCentroResultado, nomeCentroResultado, dataAdmissao, chefiaImediata, valorGarantia) {
        btn = $(botao);
        $('#nomeHeaderModal').html('<?= lang("editar_vendedor") ?>');
        if (status == 'Ativo') {
            status = 1;
        } else {
            status = 0;

        }
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        let empresasEdit = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar empresas, tente novamente');
                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
            }
        });

        $('#selectEmpresaEdit').select2({
            data: empresasEdit.results,
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
            width: '100%'
        });

        $('#selectEmpresaEdit').on('select2:select', function(e) {
            var data = e.params.data;
        });

        let regionaisEdit = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarRegionaisSelect2') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar regionais, tente novamente');
                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
            }
        });

        $('#selectRegionalEdit').select2({
            data: regionaisEdit.results,
            placeholder: "Selecione a regional",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });

        $('#selectRegionalEdit').on('select2:select', function(e) {
            var data = e.params.data;
        });

        let cargosEdit = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCargosSelect2') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar cargos, tente novamente');
                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
            }
        });

        $('#selectCargoEdit').select2({
            data: cargosEdit.results,
            placeholder: "Selecione o cargo",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });

        $('#selectCargoEdit').on('select2:select', function(e) {
            var data = e.params.data;
        });

        let chefiaEdit = await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarUsuarios') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar chefia, tente novamente');
                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
            }
        });

        $('#chefiaVendedorEdit').select2({
            data: chefiaEdit.results,
            placeholder: "Selecione a chefia",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });

        $('#chefiaVendedorEdit').on('select2:select', function(e) {
            var data = e.params.data;
        });

        $('#divStatus').attr('hidden', false)
        $('#statusVendedor').attr('required', true);
        $('#divSelectEmpresaCad').attr('hidden', true)
        $('#selectEmpresaCad').attr('required', false);
        $('#divSelectEmpresaEdit').attr('hidden', false)
        $('#selectEmpresaEdit').attr('required', true);
        $('#divSelectRegionalCad').attr('hidden', true)
        $('#selectRegionalCad').attr('required', false);
        $('#divSelectRegionalEdit').attr('hidden', false)
        $('#selectRegionalEdit').attr('required', true);
        $('#divSelectCargolCad').attr('hidden', true)
        $('#selectCargoCad').attr('required', false);
        $('#divSelectCargolEdit').attr('hidden', false)
        $('#selectCargoEdit').attr('required', true);
        $('#btnSalvarCadastroVendedor').data('id', id)
        $('#nomeVendedor').val(nome);
        $('#selectEmpresaEdit').val(idEmpresa).trigger('change');
        $('#selectRegionalEdit').val(idRegional).trigger('change');
        $('#selectCargoEdit').val(idCargo).trigger('change');
        $('#statusVendedor').val(status).trigger('change');
        $('#divChefiaCad').attr('hidden', true);
        $('#chefiaVendedor').attr('required', false);
        $('#divChefiaEdit').attr('hidden', false);
        $('#chefiaVendedorEdit').attr('required', true);
        $('#matriculaVendedor').val(matricula == 'null' || matricula == null ? '' : matricula);
        $('#emailVendedor').val(email == 'null' || email == null ? '' : email);
        $('#dataAdmissaoVendedor').val(dataAdmissao == 'null' || dataAdmissao == null ? '' : dataAdmissao.split('/').reverse().join('-')) 
        if (chefiaImediata == null || chefiaImediata == 'null'){
            $('#chefiaVendedorEdit').val(null).trigger('change');
        }else{
            $('#chefiaVendedorEdit option').each(function() {
                if ($(this).text() == chefiaImediata) {
                    $('#chefiaVendedorEdit').val($(this).val()).trigger('change');
                }
            });
        }

        $('#codigoCentroResultadoVendedor').val(codigoCentroResultado == 'null' || codigoCentroResultado == null ? '' : codigoCentroResultado);
        $('#nomeCentroResultadoVendedor').val(nomeCentroResultado == 'null' || nomeCentroResultado == null ? '' : nomeCentroResultado);
        $('#checkGarantiaComissao').prop('checked', garantia == 1 ? true : false).trigger('change');
        $('#valorGarantia').val(valorGarantia == 'null' || valorGarantia == null ? '' : valorGarantia.includes('.') && !valorGarantia.includes(',') ? valorGarantia.replace('.', ',') : valorGarantia);
        $('#salarioVendedor').val(salario == 'null' || salario == null ? '' : salario.includes('.') && !salario.includes(',') ? salario.replace('.', ',') : salario);

        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        $('#modalCadVendedor').modal('show');
    }

    $('#modalCadVendedor').on('hidden.bs.modal', function() {
        $('#btnSalvarCadastroVendedor').attr('data-id', '');
        $('#nomeVendedor').val('');
        $('#selectEmpresaCad').val('').trigger('change');
        $('#selectRegionalCad').val('').trigger('change');
        $('#selectCargoCad').val('').trigger('change');
        $('#divStatus').attr('hidden', true)
        $('#statusVendedor').val('').trigger('change');
        $('#statusVendedor').attr('required', false);
        $('#divSelectEmpresaCad').attr('hidden', false)
        $('#selectEmpresaCad').attr('required', true);
        $('#divSelectEmpresaEdit').attr('hidden', true)
        $('#selectEmpresaEdit').attr('required', false);
        $('#divSelectRegionalCad').attr('hidden', false)
        $('#selectRegionalCad').attr('required', true);
        $('#divSelectRegionalEdit').attr('hidden', true)
        $('#selectRegionalEdit').attr('required', false);
        $('#divSelectCargolCad').attr('hidden', false)
        $('#selectCargoCad').attr('required', true);
        $('#divSelectCargolEdit').attr('hidden', true)
        $('#selectCargoEdit').attr('required', false);
        $('#divChefiaCad').attr('hidden', false);
        $('#chefiaVendedor').attr('required', true);
        $('#divChefiaEdit').attr('hidden', true);
        $('#chefiaVendedorEdit').attr('required', false);
        $('#matriculaVendedor').val('');
        $('#valorGarantia').val('');
        $('#emailVendedor').val('');
        $('#checkGarantiaComissao').prop('checked', false).trigger('change');
        $('#codigoCentroResultadoVendedor').val('');
        $('#nomeCentroResultadoVendedor').val('');
        $('#dataAdmissaoVendedor').val('');
        $('#chefiaVendedor').val('').trigger('change');
        $('#chefiaVendedorEdit').val(null).trigger('change');
        $('#salarioVendedor').val('');
    });

    $('#formCadastroVendedor').submit(function(e) {
        e.preventDefault();
        var id = $('#btnSalvarCadastroVendedor').data('id')
        var nome = $('#nomeVendedor').val();
        var idEmpresa = $('#selectEmpresaCad').val();
        var idRegional = $('#selectRegionalCad').val();
        var idCargo = $('#selectCargoCad').val();
        var status = $('#statusVendedor').val();
        var idEmpresaEdit = $('#selectEmpresaEdit').val();
        var idRegionalEdit = $('#selectRegionalEdit').val();
        var idCargoEdit = $('#selectCargoEdit').val();
        var tabelaVendedores = $('#tabelaVendedores').DataTable();
        var matricula = $('#matriculaVendedor').val();
        var valorGarantia = ($('#valorGarantia').val());
        var email = $('#emailVendedor').val();
        var garantia = $('#checkGarantiaComissao').is(':checked');
        var codigoCentroResultado = $('#codigoCentroResultadoVendedor').val();
        var nomeCentroResultado = $('#nomeCentroResultadoVendedor').val();
        var dataAdmissao = ($('#dataAdmissaoVendedor').val()).split('-').reverse().join('/');
        var chefia = $('#chefiaVendedor option:selected').text();
        var chefiaEditar = $('#chefiaVendedorEdit option:selected').text();
        var salario = $('#salarioVendedor').val();

        if ($('#nomeHeaderModal').html() == '<?= lang("novo_vendedor") ?>') {
            $('#btnSalvarCadastroVendedor').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarVendedor') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    nome: nome,
                    idEmpresa: idEmpresa,
                    idRegional: idRegional,
                    idCargo: idCargo,
                    matricula: matricula,
                    valorGarantia: valorGarantia ? formatValorInserir(valorGarantia) : null,
                    email: email,
                    garantia: garantia == true ? 1 : 0,
                    codigoCentroResultado: codigoCentroResultado,
                    nomeCentroResultado: nomeCentroResultado,
                    dataAdmissao: dataAdmissao,
                    chefiaImediata: chefia,
                    salario: salario ? formatValorInserir(salario) : null,
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        tabelaVendedores.ajax.reload();
                        $('#modalCadVendedor').modal('hide');
                    }else if (data.status === 404 && data.dados.mensagem) {
                        alert(data.dados.mensagem)
                    }else if(data.status === 400 && data.dados.mensagem){
                        alert(data.dados.mensagem)
                    }else{
                        alert('Não foi possível realizar o cadastro. Verifique os campos e tente novamente!')
                    }
                },
                error: function() {
                    alert('Erro ao cadastrar vendedor. Tente novamente');
                    $('#btnSalvarCadastroVendedor').attr('disabled', false).html('Salvar');
                },
                complete: function() {
                    $('#btnSalvarCadastroVendedor').attr('disabled', false).html('Salvar');
                }
            });
        } else {
            $('#btnSalvarCadastroVendedor').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Editando...');
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarVendedor') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    nome: nome,
                    idEmpresa: idEmpresaEdit,
                    idRegional: idRegionalEdit,
                    idCargo: idCargoEdit,
                    status: status,
                    matricula: matricula,
                    valorGarantia: valorGarantia ? formatValorInserir(valorGarantia) : null,
                    email: email,
                    garantia: garantia == true ? 1 : 0,
                    codigoCentroResultado: codigoCentroResultado,
                    nomeCentroResultado: nomeCentroResultado,
                    dataAdmissao: dataAdmissao,
                    chefiaImediata: chefiaEditar,
                    salario: salario ? formatValorInserir(salario) : null,
                },
                success: function(data) {
                    if (data.status === 200) {
                        alert(data.dados.mensagem)
                        tabelaVendedores.ajax.reload();
                        $('#modalCadVendedor').modal('hide');

                    }else if (data.status === 404 && data.dados.mensagem) {
                        alert(data.dados.mensagem)
                    }else if(data.status === 400 && data.dados.mensagem){
                        alert(data.dados.mensagem)
                    }else{
                        alert('Não foi possível realizar a edição. Verifique os campos e tente novamente!')
                    }
                },
                error: function() {
                    alert('Erro ao editar vendedor. Tente novamente');
                    $('#btnSalvarCadastroVendedor').attr('disabled', false).html('Salvar');
                },
                complete: function() {
                    $('#btnSalvarCadastroVendedor').attr('disabled', false).html('Salvar');
                }
            });
        }
    });

    function formatarMoeda(campo) {
        var elemento = document.getElementById(campo);
        var valor = elemento.value;

        valor = valor.toString().replace(/\D/g, '');
        valor = (parseFloat(valor) / 100).toFixed(2).toString();
        valor = valor.replace('.', ',');

        if (valor.length > 6) {
            valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        }

        elemento.value = valor;
        if (valor == 'NaN') elemento.value = '';
    }

    function formatValorInserir(value) {
		value = value.replaceAll('.', '');
		value = value.replace(',', '.');

		return value;
		
	}

    $('#chefiaVendedor').select2({
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarUsuariosPorNome') ?>',
            type: 'GET',
            dataType: 'json',
            delay: 1000,
            data: function(params) {
                return {
                    q: params.term,
                };
            },
        },
        width: '100%',
        placeholder: 'Selecione uma chefia',
        allowClear: true,
        language: "pt-BR",
        minimumInputLength: 3
    });

    $('#checkGarantiaComissao').on('change', function() {
        if ($(this).is(':checked')) {
            $('#divValorGarantia').attr('hidden', false);
            $('#valorGarantia').attr('required', true);
        } else {
            $('#divValorGarantia').attr('hidden', true);
            $('#valorGarantia').val(null);
            $('#valorGarantia').attr('required', false);
        }
    });
</script>