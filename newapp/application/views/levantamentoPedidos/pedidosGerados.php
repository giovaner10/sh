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

    th.dt-center,
    td.dt-center {
        text-align: center !important;
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

    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
</style>

<div class="alert alert-info col-md-12" <div class="col-md-12">
    <p class="col-md-12">Informe o número do pedido, AF ou data para filtrar as informações dos pedidos.</p>
    <br>
    <span class="help help-block"></span>

    <form action="" id="formBusca">
        <div class="form-group">

            <div class="col-md-3">
                <label for="searchTypeData">Buscar por:</label>
                <select id="tipoData" name="tipoData" class="form-control">
                    <option value="dateRange">Intervalo de dias</option>
                    <option value="numero_pedido">Número do Pedido</option>
                    <option value="af">AF</option>
                </select>
            </div>

            <div class="col-md-2 input-container" id="dateContainer1">
                <label for="dataInicial">Data Inicial:</label>
                <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value="" />
            </div>

            <div class="col-md-2 input-container" id="dateContainer2">
                <label for="dataFinal">Data Final:</label>
                <input type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value="" />
            </div>

            <div class="col-md-2 input-container" id="numero_pedidoContainer" style="display:none;">
                <label for="numero_pedidoInput">Número do Pedido:</label>
                <input type="text" id="numero_pedidoInput" class="form-control">
            </div>

            <div class="col-md-2 input-container" id="afContainer" style="display:none;">
                <label for="afInput">AF:</label>
                <input type="text" id="afInput" class="form-control">
            </div>

            <div class="col-md-4 button-container">
                <div class="button-aligner">
                    <button class="btn btn-primary" id="BtnPesquisar" type="button" style="margin-top: 20px;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                    <button class="btn btn-primary" id="BtnLimpar" type="button" style="margin-top: 20px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container-fluid my-1">

    <div class="col-sm-12" style="padding: 0;">

        <div id="estornos" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">

                <table class="table-responsive table-bordered table-striped table-hover table" id="tabelaPedidosGerados">
                    <thead>
                        <tr class="tableheader">
                            <th>Filial</th>
                            <th>Número</th>
                            <th>Tipo</th>
                            <th>AF</th>
                            <th type="date">Data de Emissão</th>
                            <th type="date">Data de Criação</th>
                            <th type="date">Data de Atualização</th>
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

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<!-- Masks -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
<script>
$(document).ready(function() {
    $.fn.dataTable.moment('DD/MM/YYYY');
    const dataAtual = new Date();

    var tabelaPedidosGerados = $('#tabelaPedidosGerados').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        autoWidth: false,
        order: [6, 'desc'],
        language: {
            loadingRecords: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhum registro encontrado.",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords: "Nenhum registro encontrado.",
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
            url: '<?= site_url('levantamentoPedidos/listaUltimos100PedidosGerados') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    tabelaPedidosGerados.clear().draw();
                    tabelaPedidosGerados.rows.add(data.results).draw();
                } else {
                    alert('Erro ao buscar pedidos gerados.');
                    tabelaPedidosGerados.clear().draw();
                }
            },
            error: function() {
                alert('Erro ao buscar pedidos gerados. Tente novamente!');
                tabelaPedidosGerados.clear().draw();
            }
        },
        columnDefs: [{
                className: "dt-center",
                targets: "_all"
            },
            {
                type: "date-br",
                targets: [4, 5, 6]
            }
        ],
        columns: [{
                data: 'filial',
                width: '10%'
            },
            {
                data: 'numero',
                width: '14%'
            },
            {
                data: 'tipo',
                width: '10%'
            },
            {
                data: 'af',
                width: '12%'
            },
            {
                data: 'dataEmissao',
                width: '18%',
                render: function(data) {
                    return new Date(data).toLocaleDateString('pt-BR');
                }
            },
            {
                data: 'dataCreated',
                width: '18%',
                render: function(data) {
                    return new Date(data).toLocaleDateString('pt-BR');
                }
            },
            {
                data: 'dataUpdated',
                width: '18%',
                render: function(data) {
                    return new Date(data).toLocaleDateString('pt-BR');
                }
            },
            {
                data: {
                    'filial': 'filial',
                    'numero': 'numero',
                    'tipo': 'tipo',
                    'af': 'af',
                    'dataEmissao': 'dataEmissao'
                },
                orderable: false,
                visible: false,
                render: function(data) {
                    return `
                    <button 
                        class="btn btn-primary"
                        title="Editar Levantamento"
                        data-id="${data.id}"
                        style="width: auto; margin: 0 auto;text-align: center;"
                        onclick="javascript:abrirEditLevantamento(this, '${data['filial']}', '${data['numero']}', '${data['tipo']}', '${data['af']}', '${data['dataEmissao']}')"
                        id="btnEditarLevantamento"
                        >
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    `;
                }
            }
        ],
        dom: 'Blfrtip',
        buttons: [{
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function(doc, tes) {
                    titulo = `Levantamento de Pedidos`;
                    // Personaliza a página do PDF
                    pdfTemplateIsolated(doc, titulo)
                }
            },
            {
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                filename: filenameGenerator("Levantamento de Pedidos"),
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function(win) {
                    titulo = `Levantamento de Pedidos`;
                    // Personaliza a página Impressale
                    printTemplateOmni(win, titulo);
                }
            }
        ]
    });

    async function abrirEditLevantamento(botao, filial, numero, tipo, af, dataEmissao) {}

    $('#BtnPesquisar').on('click', function() {
        showLoadingPesquisarButton();
        var tipoData = $('#tipoData').val();
        var searchOptions = {
            dataInicial: null,
            dataFinal: null,
            af: null,
            numero_pedido: null
        };

        if (tipoData === 'dateRange') {
            const dataInicial = new Date($('#dataInicial').val());

            if (!$('#dataInicial').val() || !$('#dataFinal').val()) {
                alert("Insira um intervalo de datas válido.");
                resetPesquisarButton();
                return;
            } else if (dataInicial > dataAtual) {
                alert("A data inicial não pode ser maior que a data atual.");
                resetPesquisarButton();
                return;
            } else if ($('#dataInicial').val() > $('#dataFinal').val()) {
                alert("Data inicial não pode ser maior que a data final.");
                resetPesquisarButton();
                return;
            }
        } else if (tipoData === 'af' && !$('#afInput').val()) {
            alert("Insira uma AF válida.");
            resetPesquisarButton();
            return;
        } else if (tipoData === 'numero_pedido' && !$('#numero_pedidoInput').val()) {
            alert("Insira um número de pedido válido.");
            resetPesquisarButton();
            return;
        }

        switch (tipoData) {
            case 'dateRange':
                searchOptions.dataInicial = $('#dataInicial').val();
                searchOptions.dataFinal = $('#dataFinal').val();
                break;
            case 'af':
                searchOptions.af = $('#afInput').val();
                break;
            case 'numero_pedido':
                searchOptions.numero_pedido = $('#numero_pedidoInput').val();
                break;
        }

        $.ajax({
            url: '<?= site_url('levantamentoPedidos/listarPedidosGeradosByNumPedidoOrAFOrDate') ?>',
            type: 'POST',
            data: {
                "dataInicial": searchOptions.dataInicial,
                "dataFinal": searchOptions.dataFinal,
                "af": searchOptions.af,
                "numero_pedido": searchOptions.numero_pedido
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 200) {
                    tabelaPedidosGerados.clear().draw();
                    tabelaPedidosGerados.rows.add(data.results).draw();
                    resetPesquisarButton();
                } else {
                    alert('Erro ao buscar pedidos gerados.');
                    tabelaPedidosGerados.clear().draw();
                    resetPesquisarButton();
                }
            },
            error: function() {
                alert('Erro ao buscar pedidos gerados. Tente novamente!');
                tabelaPedidosGerados.clear().draw();
                resetPesquisarButton();
            }
        }, );
    });

    $('.nav-tabs a').on('click', function(e) {
        var currentAttrValue = $(this).attr('href');
        $(currentAttrValue).addClass('active').siblings().removeClass('active');
        $(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();
    });

    $('#tipoData').change(function() {
        $('.input-container').hide();

        switch ($(this).val()) {
            case 'dateRange':
                $('#dateContainer1, #dateContainer2').show();
                break;
            case 'af':
                $('#afContainer').show();
                break;
            case 'numero_pedido':
                $('#numero_pedidoContainer').show();
                break;
            default:
                $('#dateContainer1, #dateContainer2').show();
                break;
        }
    });

    $('#BtnLimpar').on('click', function() {
        $('#dataInicial').val('');
        $('#dataFinal').val('');
        $('#afInput').val('');
        $('#numero_pedidoInput').val('');
        $('#tipoData').val('dateRange');
        $('.input-container').hide();
        $('#dateContainer1, #dateContainer2').show();

        $.ajax({
            url: '<?= site_url('levantamentoPedidos/listaUltimos100PedidosGerados') ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#tabelaPedidosGerados tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data) {
                if (data.status === 200) {
                    tabelaPedidosGerados.clear().draw();
                    tabelaPedidosGerados.rows.add(data.results).draw();
                } else {
                    alert('Erro ao buscar pedidos gerados.');
                    tabelaPedidosGerados.clear().draw();
                }
            },
            error: function() {
                alert('Erro ao buscar pedidos gerados. Tente novamente!');
                tabelaPedidosGerados.clear().draw();
            }
        });

    })

    function showLoadingPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando...').attr('disabled', true);
    }

    function resetPesquisarButton() {
        $('#BtnPesquisar').html('<i class="fa fa-search"></i> Pesquisar').attr('disabled', false);
    }
});
</script>