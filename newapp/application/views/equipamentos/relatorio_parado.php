<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">
<link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet">
<link href="<?php echo base_url('media') ?>/css/eqp_parado.css" rel="stylesheet">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<h3>Relatório Equipamentos Desatualizados</h3>
<div id="alert-dt" class="alert alert-danger display">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Atenção!</strong>
    A data inicial não pode ser maior que a data final!
</div>
<div class="well">
    <div class="dDate1">
        <label>Data Início</label>
        <input type="date" id="dtIni" name="dtIni" class="inputData" required>
    </div>
    <div class="dDate2">
        <label>Data Fim</label>
        <input type="date" id="dtFim" name="dtFim" class="inputData" required>
    </div>
    <button id="btn-gerar" class="btn btn-primary"> Gerar Relatório <i class="fa fa-spin fa-spinner display fa-fw"></i> </button>
</div>
<div id="exportar_tabela">
    <div class="span6 painel-btns display">
        <button class="btn btn-info pull-left" onclick="imprimir();" type="button" style="margin-right: 5px;">Imprimir</button>
        <form class="pull-left" style="margin-right: 5px;"  name="FormPostExcel" method="post" action="https://gestor.showtecnologia.com/show/GR/exp_csv.php"  id="FormPostExcel">
            <a class="btn btn-info" href="javascript:Excel('FormPostExcel');"> Gerar Excel </a>
            <input type="hidden" id="conteudo" name="conteudo" />
        </form>
    </div>
    <table id="table-eqp" class="table table-hover table-bordered table-striped display">
        <thead>
        <th class="span1">Id</th>
        <th class="span3">Serial</th>
        <th class="span3">Data</th>
        <th class="span4">CCID</th>
        <th class="span4">Número</th>
        <th class="span4">Cliente</th>

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
    $('#btn-gerar').click(function () {
        var dtIni = $('#dtIni').val();
        var dtFim = $('#dtFim').val();
        var datInicio = new Date(dtIni.substring(6,10),
            dtIni.substring(3,5),
            dtIni.substring(0,2));
        datInicio.setMonth(datInicio.getMonth() - 1);
        var datFim = new Date(dtFim.substring(6,10),
            dtFim.substring(3,5),
            dtFim.substring(0,2));
        datFim.setMonth(datFim.getMonth() - 1);

        if (datInicio > datFim) {
            $('#alert-dt').removeClass('display');
        } else {
            $('#alert-dt').addClass('display');
            $(this).prop('disabled', true).text('Aguarde...');
            $('#table-eqp').removeClass('display');
            $.getJSON('get_eqp_parado', {dtIni: dtIni, dtFim: dtFim}, function (data) {
                $('#table-eqp').removeClass('display');
                $('#tbody').html('');
                var dynatable = $('#table-eqp').dynatable({
                    features: {
                        paginate: false,
                        sort: false,
                        pushState: false,
                        search: true,
                        recordCount: true,
                        perPageSelect: false
                    },
                    dataset: {
                        records: data
                    }
                }).data("dynatable");
                dynatable.settings.dataset.originalRecords =  data;
                dynatable.process();
            }).success( function () {
                $('#btn-gerar').prop('disabled', false).text('Gerar Relatório');
                $('.painel-btns').removeClass('display');
            })
        }
    });

    function Excel(form){
        $("#conteudo").val($('#exportar_tabela').html());
        $("#" + form).submit();
    }

</script>