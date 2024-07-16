<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">
<!-- <link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet"> -->
<script type="text/javascript" src="<?= base_url('assets/js/modules/jquery.battatech.excelexport.js') ?>"></script>
<link href="<?php echo base_url('media') ?>/css/eqp_parado.css" rel="stylesheet">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
<style>th, td{text-align: center} @media print {.well{ display: none }} </style>
<style>
    th,
    td {
        text-align: center
    }

    @media print {
        .well {
            display: none
        }
    }
    .close {
        margin-right: 10px !important;
        margin-top: 10px !important;
    }

    .select2-container {
        width: 90% !important;
    }

    .inputData {
        width: 155px;
        background: white !important;
        cursor: pointer !important;
    }

    .display {
        display: none;
    }

    .painel-btns {
        margin-left: 0;
    }
</style>
<div class="container">
<div class="row">
<h3>Relatório Faturas em Atraso</h3>
<div id="alert_cli"></div>
<div class="well">
    <div class="dDate2">
        <label>Cliente</label>
        <select class="js-example-basic-single span6" id="cliente" name="cliente">
        </select>
    </div>
    <div class="dDate1">
        <label>Data Início</label>
        <input type="text" id="dtIni" name="dtIni" class="inputData datepickerX" placeholder="__/__/__" required readonly>
    </div>
    <div class="dDate2">
        <label>Data Fim</label>
        <input type="text" id="dtFim" name="dtFim" class="inputData datepickerX" placeholder="__/__/__" required readonly>
    </div>
    <button id="btn-gerar" class="btn btn-primary"> Gerar Relatório <i class="fa fa-spin fa-spinner display fa-fw"></i> </button>
     </div>
  </div>
</div>
<div id="alertahahaha"></div>
<div id="exportar_tabela">
    <div class="span6 painel-btns display">
        <button class="btn btn-primary pull-left" onclick="imprimir();" type="button" style="margin-right: 5px;">Imprimir</button>
        <button class="btn btn-primary" id="btnExport">Gerar Excel</button>
    </div>
    <table id="table-eqp" class="table table-hover table-bordered table-striped display">
        <thead>
        <th class="span1 center">#</th>
        <th class="span3">Cliente</th>
        <th class="span3">Faturas em atraso</th>
        <th class="span4">Valor Médio</th>
        <th class="span4">Valor Total do Débito</th>
        <th class="span3">Situação</th>
        </thead>
        <tbody id="tbody" align="center">
        <td></td>
        <td></td>
        <td></td>
        <td style="width: 15%"><i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i></td>
        <td></td>
        </tbody>
    </table>
</div>
<script>
    $(function() {
        $( ".datepickerX" ).datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
            dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            maxDate: '0d',
            showAnim: 'fadeIn'
//            beforeShowDay: $.datepicker.noWeekends
        });
    });

    $('.js-example-basic-single').select2({
        placeholder: "Selecione o(s) cliente(s)",
        allowClear: true,
        ajax: {
            url: '<?= site_url('clientes/ajaxListSelect') ?>',
            dataType: 'json'
        }
    });

    $('#btn-gerar').click(function () {
        $('#tbody').html('<td></td><td></td><td></td><td style="width: 15%"><i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i></td><td></td>');
        var dtIni = $('#dtIni').val();
        var dtFim = $('#dtFim').val();
        var client = $('#cliente option:selected').val();
        var datInicio = new Date(dtIni.substring(6,10),
            dtIni.substring(3,5),
            dtIni.substring(0,2));
        datInicio.setMonth(datInicio.getMonth() - 1);
        var datFim = new Date(dtFim.substring(6,10),
            dtFim.substring(3,5),
            dtFim.substring(0,2));
        datFim.setMonth(datFim.getMonth() - 1);
        if (client == ""){
            var alert_cli = [
                '<div id="alert-cliente" class="alert alert-danger">'+
                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                '<strong>Atenção!</strong> Digite o nome do cliente! </div>'
            ].join();
            $('#alert_cli').html(alert_cli);
        } else if (dtIni == "" || dtFim == ""){
            alert_cli = [
                '<div id="alert-cliente" class="alert alert-danger">'+
                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                '<strong>Atenção!</strong> Defina duas datas para continuar! </div>'
            ].join();
            $('#alert_cli').html(alert_cli);
        } else if (datInicio > datFim) {
            alert_cli = [
                '<div id="alert-cliente" class="alert alert-danger">' +
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                '<strong>Atenção!</strong> A data inicial não pode ser maior que a data final! </div>'
            ].join();
            $('#alert_cli').html(alert_cli);
        } else {
            $('#alert-dt').addClass('display');
            $(this).prop('disabled', true).text('Gerando...');
            $('#table-eqp').removeClass('display');
            $.getJSON('get_list_fatura', {dtIni: dtIni, dtFim: dtFim, cliente: client}, function (data) {
                $('#tbody').html('');
                var dynatable = $('#table-eqp').dynatable({
                    features: {
                        paginate: false,
                        sort: false,
                        pushState: false,
                        search: false,
                        recordCount: true,
                        perPageSelect: false
                    },
                    dataset: {
                        records: data
                    }
                }).data("dynatable");
                dynatable.settings.dataset.originalRecords =  data;
                dynatable.process();
            }).success( function (callback) {
                if (callback.dados == false) {
                    $('#exportar_tabela').addClass('display');
                    $('#btn-gerar').prop('disabled', false).text('Gerar Relatório');
                    var alert_false = [
                        '<div id="alert-cliente" class="alert alert-info">'+
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                        '<strong><b><h3><i class="fa fa-smile-o fa-2x"></i></b></strong>  O cliente não possui faturas em atraso no período informado! </h3></div>'
                    ].join();
                    $('#alertahahaha').html(alert_false);
                } else {
                    $('#alertahahaha').html("");
                    $('#exportar_tabela').removeClass('display');
                    $('#btn-gerar').prop('disabled', false).text('Gerar Relatório');
                    $('.painel-btns').removeClass('display');
                }
            })
        }
    });

    $("#btnExport").click(function() {
        $("#table-eqp").battatech_excelexport({
            containerid: "table-eqp",
            datatype: 'table'
        });
    });

</script>