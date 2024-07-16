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

    .sorting_disabled {
        width: auto !important;
    }


    #tabelaRelatorioSms th:nth-child(1),
    #tabelaRelatorioSms td:nth-child(1) {
        width: 30%;
    }

    #tabelaRelatorioSms th:nth-child(2),
    #tabelaRelatorioSms td:nth-child(2) {
        width: 15%;
    }

    #tabelaRelatorioSms th:nth-child(3),
    #tabelaRelatorioSms td:nth-child(3) {
        width: 45%;
    }

    #tabelaRelatorioSms th:nth-child(4),
    #tabelaRelatorioSms td:nth-child(4) {
        width: 10%;
    }

    .dt-button {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .header-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    .div-caminho-menus-pais {
        margin-top: 10px;
    }
</style>

<div class="header-container">
    <h3><?= lang("relatorio_manutencao") ?></h3>

    <div class="div-caminho-menus-pais" style="margin-bottom: 5px;">
        <a href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('auditoria') ?> >
        <?= lang('relatorio_agendamento') ?> >
        <?= lang('relatorio_manutencao') ?>
    </div>
</div>

<div class="alert alert-info col-md-12">
    <div class="col-md-12">
        <p class="col-md-12">Informe os dados para realizar a pesquisa</p>
        <span class="help help-block"></span>
        <form action="" id="formBusca">
            <div class="form-group col-md-4">
                <label for="selectPesqTecnico">Técnico:</label>
                <select name="nomeTecnico" id="selectPesqTecnico" class="form-control input-sm" style="width: 100%">
                </select>
            </div>
            <div class="form-group">
                <div class="col-md-2">
                    <label for="dataInicial">Data Inicial:</label>
                    <input type="date" name="dataInicial" class="form-control input-sm" placeholder="Data Início" autocomplete="off" id="dataInicial" value="" />
                </div>
                <div class="col-md-2">
                    <label for="dataFinal">Data Final:</label>
                    <input type="date" name="dataFinal" class="form-control input-sm" placeholder="Data Fim" autocomplete="off" id="dataFinal" value="" />
                </div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" id="BtnPesquisar" type="button" style="margin-top: 20px"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                <button class="btn btn-primary" id="BtnLimparPesquisar" type="button" style="margin-top: 20px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
            </div>
        </form>
    </div>
</div>

<ul class="nav nav-tabs">
    <li id="abaTabela" class="nav-item">
        <a id="tab-relatorioTabela" data-toggle="tab" href="" class="nav-link active">Relatório de Manutenção em Tabela</a>
    </li>
    <li id="abaGrafico" class="nav-item">
        <a id="tab-relatorioGrafico" data-toggle="tab" href="" class="nav-link">Relatório de Manutenção em Gráfico</a>
    </li>
</ul>

<div id="divTabelaRelatorio" class="container-fluid my-1" hidden>
    <div class="col-sm-12">
        <div id="relatorioSms" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                <table class="table-responsive table-bordered table table-hover table-striped" id="tabelaRelatorioSms">
                    <thead>
                        <tr class="tableheader">
                            <th>Técnico</th>
                            <th>Ação</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="divRelatorioGrafico" class="container-fluid my-1" hidden>
    <h2 style="grid-column: span 2; margin-bottom: 30px; font-size: 15px; text-align: center;">Relatório de Manutenção</h2>
    <div class="col-sm-12">
        <div id="relatorioSmsGrafico" class="tab-pane fade in active">
            <canvas id="graficoTotal" width="600" height="400" display: block;"></canvas>
        </div>
        <div id="relatorioSmsGraficoLinha" class="tab-pane fade in active">
            <canvas id="graficoLinha" width="600" height="400" display: block;"></canvas>
        </div>
        <div id="loadingMessage" class="loadingMessage" style="display: none; position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);">
            <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
        </div>
        <div id="emptyMessage" style="display: none; position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);">
            <b>Nenhuma informação a ser exibida.</b>
        </div>
    </div>
</div>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Biblioteca charts -->
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community/dist/ag-charts-community.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctxBarManu = document.getElementById('graficoTotal');
    const ctxLineManu = document.getElementById('graficoLinha');
    const dataAtual = new Date();

    var tabelaRelatorioSms = $('#tabelaRelatorioSms').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder: 'Pesquisar',
            emptyTable: "Nenhum resultado encontrado",
            info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords: "Nenhum resultado encontrado",
            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próxima",
                previous: "Anterior"
            },
        },
        ajax: {
            url: '<?= site_url('Auditoria/Agendamento/listarUltimosCemRelatorioTecnicosManutencao') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    tabelaRelatorioSms.clear().draw();
                    tabelaRelatorioSms.rows.add(data.results).draw();
                } else {
                    alert(data?.results?.mensagem ? data.results.mensagem : 'Erro ao buscar os dados. Tente novamente.')
                    tabelaRelatorioSms.clear().draw();
                }
            },
            error: function() {
                alert('Erro ao buscar os dados. Tente novamente.')
                tabelaRelatorioSms.clear().draw();
            },
        },
        deferRender: true,
        lengthChange: false,
        fixedcolumns: true,
        columns: [{
                data: 'nomeTecnico'
            },
            {
                data: 'acao',
                render: function(data) {
                    let acao = ""
                    switch (data) {
                        case 'ATTEMPT':
                            acao = '<span class="label label-danger" style="font-size: 11px; background-color: rgba(75, 192, 192, 1);">Tentativa</span>';
                            break;
                        case 'CLOSE':
                            acao = '<span class="label label-success" style="font-size: 11px; background-color: rgba(54, 162, 235, 1);">Fechar</span>';
                            break;
                        case 'OPEN':
                            acao = '<span class="label label-info" style="font-size: 11px; background-color: rgba(255, 206, 86, 1);">Abrir</span>';
                            break;
                        case 'SUCCESS':
                            acao = '<span class="label label-warning" style="font-size: 11px; background-color: rgba(255, 99, 132, 1);">Sucesso</span>';
                            break;
                        case 'ACCEPT':
                            acao = '<span class="label label-primary" style="font-size: 11px; background-color: rgba(153, 102, 255, 1);">Aceitar</span>';
                            break;
                        case 'CANCEL':
                            acao = '<span class="label label-danger" style="font-size: 11px; background-color: rgba(255, 159, 64, 1);">Cancelar</span>';
                            break;
                        default:
                            acao = '<span class="label label-default" style="font-size: 11px; background-color: rgba(128, 128, 128, 1);">Não informado</span>';
                            break;
                    }
                    return acao;
                }
            },
            {
                data: 'descricao'
            },
            {
                data: 'quantidade'
            },
        ],
        columnDefs: [{
            targets: [0, 1, 2, 3],
            className: 'text-center'
        }],
        dom: 'Bfrtip',
        buttons: [{
                filename: "Relatório de Manutenção",
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function(doc, tes) {
                    date = new Date();
                    date = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
                    titulo = `Relatório de Manutenção - ${date}`;
                    pdfTemplateIsolated(doc, titulo)
                }
            },
            {
                filename: "Relatório de Manutenção",
                extend: 'excelHtml5',
                title: "Relatório de Manutenção",
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                filename: "Relatório de Manutenção",
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                filename: "Relatório de Manutenção",
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function(win) {

                    titulo = `Relatório de Manutenção - Técnicos`;
                    printTemplateImpressao(win, titulo);
                }
            }
        ],
    })

    $('#BtnPesquisar').click(function() {
        let dataInicial = new Date($('#dataInicial').val());
        let dataFinal = new Date($('#dataFinal').val());
        let nomeTecnico = $('#selectPesqTecnico').val();

        // Verifica se a data inicial é maior que a data atual
        if (dataInicial > dataAtual) {
            alert('A data inicial não pode ser maior que a data de hoje.');
            return false;
        }
        // Verifica se a data final é maior que a data inicial
        else if (dataFinal && (dataFinal < dataInicial)) {
            alert('A data final não pode ser menor que a data inicial.');
            return false;
        }
        // Verifica se todos os campos estão preenchidos
        else if (!$('#dataInicial').val() || !$('#dataFinal').val() || !$('#selectPesqTecnico').val()) {
            alert('Preencha todos os campos para realizar a pesquisa.');
            return false;
        }
        // Verifica se ambas as datas foram informadas corretamente
        else if (($('#dataInicial').val() && !$('#dataFinal').val()) || (!$('#dataInicial').val() && $('#dataFinal').val())) {
            alert('Informe as datas corretamente para realizar a pesquisa.');
            return false;
        } else {
            let form = $('#formBusca').serialize();
            var botao = $(this);
            botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');

            $.ajax({
                url: '<?= site_url('Auditoria/Agendamento/listarRelatorioManutencaoSmsPorNomeTecnicoEData') ?>',
                type: 'POST',
                data: form,
                dataType: 'json',
                success: function(data) {
                    if (data.status === 200) {
                        if ($('#abaGrafico').hasClass('active')) {
                            $('#emptyMessage').hide();
                            atualizaGraficoTotal(data.contagemAcoes, true);
                            tabelaRelatorioSms.clear().draw();
                            tabelaRelatorioSms.rows.add(data.results).draw();
                        } else {
                            tabelaRelatorioSms.clear().draw();
                            tabelaRelatorioSms.rows.add(data.results).draw();
                        }
                    } else if (data.status === 400) {
                        alert('Nenhum resultado encontrado!');
                        if ($('#abaGrafico').hasClass('active')) {
                            stopDashboard();
                            $('#emptyMessage').show();
                        } else {
                            tabelaRelatorioSms.clear().draw();
                        }
                    } else if (data.status === 404) {
                        alert(data?.results?.mensagem ? data.results.mensagem : 'Ocorreu um erro ao buscar os dados. Tente novamente.')
                        if ($('#abaGrafico').hasClass('active')) {
                            stopDashboard();
                            $('#emptyMessage').show();
                        } else {
                            tabelaRelatorioSms.clear().draw();
                        }

                    } else {
                        alert('Ocorreu um erro ao buscar os dados. Tente novamente.')
                        if ($('#abaGrafico').hasClass('active')) {
                            stopDashboard();
                            $('#emptyMessage').show();
                        } else {
                            tabelaRelatorioSms.clear().draw();
                        }
                    }
                },
                error: function() {
                    alert('Ocorreu um erro ao buscar os dados. Tente novamente.')
                    botao.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
                    if ($('#abaGrafico').hasClass('active')) {
                        stopDashboard();
                        $('#emptyMessage').show();
                    } else {
                        tabelaRelatorioSms.clear().draw();
                    }
                },
                complete: function() {
                    botao.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true"></i> Pesquisar');
                }
            });
        }
    });

    $('#BtnLimparPesquisar').click(function() {
        $('#dataInicial').val('');
        $('#dataFinal').val('');
        $('#selectPesqTecnico').val('').trigger('change');
        // $('#tab-relatorioTabela').click();
        stopDashboard();
        $('#emptyMessage').show();
        tabelaRelatorioSms.clear().draw();
        carregarTabela();
    });

    function stopDashboard() {
        if (myChartManu) {
            myChartManu.destroy();
        }
        if (chartLineManu) {
            chartLineManu.destroy();
        }
    }

    $(document).ready(async function() {
        $('#selectPesqTecnico').empty();
        $('#selectPesqTecnico').append('<option value="0" disabled selected>Buscando técnicos...</option>');
        let tecnicos = await $.ajax({
            url: '<?= site_url('Auditoria/Agendamento/listarTecnicosManutencao') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            error: function() {
                alert('Erro ao buscar técnicos, tente novamente');
            }
        });

        $('#selectPesqTecnico').select2({
            data: tecnicos.dados,
            placeholder: "Selecione o técnico",
            allowClear: true,
            language: "pt-BR",
            width: '100%'
        });

        $('#selectPesqTecnico').on('select2:select', function(e) {
            var data = e.params.data;
        });

        $('#selectPesqTecnico').find('option').get(0).remove();
        $('#selectPesqTecnico').append('<option value="" disabled selected>Selecione o técnico</option>');

        $('#loadingMessage').show();
        $.ajax({
            url: '<?= site_url('Auditoria/Agendamento/listarUltimosCemRelatorioTecnicosManutencao') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    gerarGraficoTotal(data.contagemAcoes, true);
                    $('#emptyMessage').hide();
                    $('#loadingMessage').hide();
                } else {
                    alert(data?.results?.mensagem ? data.results.mensagem : 'Erro ao buscar os dados. Tente novamente.')
                    stopDashboard();
                    $('#emptyMessage').show();
                    $('#loadingMessage').hide();
                }
            },
            error: function() {
                alert('Erro ao buscar os dados. Tente novamente.')
                stopDashboard();
                $('#emptyMessage').show();
                $('#loadingMessage').hide();
            },
        })
    });


    $('#tab-relatorioTabela').click(function() {
        $('#divTabelaRelatorio').show();
        $('#divRelatorioGrafico').hide();
    });

    $('#tab-relatorioGrafico').click(function() {
        $('#divTabelaRelatorio').hide();
        $('#divRelatorioGrafico').show();
    });

    $(document).ready(function() {
        $('#tab-relatorioTabela').click();
    });

    function gerarGraficoTotal(dados) {
        const traducoes = {
            "ATTEMPT": "Tentativa",
            "CLOSE": "Fechar",
            "OPEN": "Abrir",
            "SUCCESS": "Sucesso",
            "ACCEPT": "Aceitar",
            "CANCEL": "Cancelar",
            "NULL": "Não informado",
        };

        const dadosObjetos = Object.keys(dados).map(acao => ({
            label: traducoes[acao],
            data: dados[acao],
            backgroundColor: getCor(acao),
            borderColor: getCor(acao),
            borderWidth: 1,
        }));

        if (dadosObjetos.length === 0) {
            $('#emptyMessage').show();
        } else {
            const labels = dadosObjetos.map(obj => obj.label);

            myChartManu = new Chart(ctxBarManu, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ações',
                        data: dadosObjetos.map(obj => obj.data),
                        backgroundColor: dadosObjetos.map(obj => obj.backgroundColor),
                        borderColor: dadosObjetos.map(obj => obj.borderColor),
                        borderWidth: 1,
                    }],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantidade',
                            },
                            ticks: {
                                stepSize: 1,
                            },
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Descrição',
                                padding: {
                                    top: 20,
                                },
                            },
                        },
                    },
                },
            });

            chartLineManu = new Chart(ctxLineManu, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ações',
                        data: dadosObjetos.map(obj => obj.data),
                        backgroundColor: dadosObjetos.map(obj => obj.backgroundColor),
                        borderColor: dadosObjetos.map(obj => obj.borderColor),
                        borderWidth: 1,
                    }],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantidade',
                            },
                            ticks: {
                                stepSize: 1,
                            },
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Descrição',
                                padding: {
                                    top: 20,
                                },
                            },
                        },
                    },
                },
            });
        }
    }

    function atualizaGraficoTotal(dados) {
        stopDashboard();
        const traducoes = {
            "ATTEMPT": "Tentativa",
            "CLOSE": "Fechar",
            "OPEN": "Abrir",
            "SUCCESS": "Sucesso",
            "ACCEPT": "Aceitar",
            "CANCEL": "Cancelar",
            "NULL": "Não informado",
        };

        const dadosObjetos = Object.keys(dados).map(acao => ({
            label: traducoes[acao],
            data: dados[acao],
            backgroundColor: getCor(acao),
            borderColor: getCor(acao),
            borderWidth: 1,
        }));

        if (dadosObjetos.length === 0) {
            $('#emptyMessage').show();
        } else {
            $('#emptyMessage').hide();
            const labels = dadosObjetos.map(obj => obj.label);

            myChartManu = new Chart(ctxBarManu, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ações',
                        data: dadosObjetos.map(obj => obj.data),
                        backgroundColor: dadosObjetos.map(obj => obj.backgroundColor),
                        borderColor: dadosObjetos.map(obj => obj.borderColor),
                        borderWidth: 1,
                    }],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantidade',
                            },
                            ticks: {
                                stepSize: 1,
                            },
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Descrição',
                                padding: {
                                    top: 20,
                                },
                            },
                        },
                    },
                },
            });

            chartLineManu = new Chart(ctxLineManu, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ações',
                        data: dadosObjetos.map(obj => obj.data),
                        backgroundColor: dadosObjetos.map(obj => obj.backgroundColor),
                        borderColor: dadosObjetos.map(obj => obj.borderColor),
                        borderWidth: 1,
                    }],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantidade',
                            },
                            ticks: {
                                stepSize: 1,
                            },
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Descrição',
                                padding: {
                                    top: 20,
                                },
                            },
                        },
                    },
                },
            });
        }
    }

    function getCor(acao) {
        switch (acao) {
            case 'ATTEMPT':
                return 'rgba(75, 192, 192, 1)';
            case 'CLOSE':
                return 'rgba(54, 162, 235, 1)';
            case 'OPEN':
                return 'rgba(255, 206, 86, 1)';
            case 'SUCCESS':
                return 'rgba(255, 99, 132, 1)';
            case 'ACCEPT':
                return 'rgba(153, 102, 255, 1)';
            case 'CANCEL':
                return 'rgba(255, 159, 64, 1)';
            default:
                return 'rgba(128, 128, 128, 1)';
        }
    }

    function carregarTabela() {
        $.ajax({
            url: '<?= site_url('Auditoria/Agendamento/listarUltimosCemRelatorioTecnicosManutencao') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status === 200) {
                    tabelaRelatorioSms.clear().draw();
                    tabelaRelatorioSms.rows.add(data.results).draw();
                } else {
                    alert(data?.results?.mensagem ? data.results.mensagem : 'Erro ao buscar os dados. Tente novamente.')
                    tabelaRelatorioSms.clear().draw();
                }
            },
            error: function() {
                alert('Erro ao buscar os dados. Tente novamente.')
                tabelaRelatorioSms.clear().draw();
            }
        });
    }
</script>

<!-- Traduções -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>