<style>
    .close{
        margin-right: 10px!important;
        margin-top: 10px!important;
    }
    .select2-container{ width: 90% !important; }
</style>
<div class="container">
    <div class="row">
        <h3><?=lang('relatorio').' - '.lang('quantitativo_contratos_veiculos')?></h3>
        <div class="well well-small col-md-12">
            <form style="align-items:center" id="formGerarResult" action="<?=site_url('relatorios/veiculosDiaAtualizacao')?>" method="get" accept-charset="utf-8">
                <div class="col-md-3">
                    <label style="width: 100%;" class="control-label"><?=lang('data_inicial')?>: </label>
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:90%" class="data control-label" type="text" name="di" required placeholder="" autocomplete="off" id="dp1" value="" />
                </div>
                <div class="col-md-3">
                    <label style="width: 100%;" class="control-label"><?=lang('data_final')?>: </label>
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:90%" class="data control-label" type="text" name="df" required placeholder="" autocomplete="off" id="dp2" value="" />
                </div>
                <div class="col-md-4">
                    <label style="width: 100%;" class="control-label"><?=lang('prestadora')?>: </label>
                    <input type="checkbox" value="TRACKER" name="prestadora[]" checked> <?=lang('show_tecnologia')?>
                    <input type="checkbox" value="NORIO" name="prestadora[]"> <?=lang('norio')?>
                    <input type="checkbox" value="OMNILINK" name="prestadora[]"> <?=lang('omnilink')?>
                    <input type="checkbox" value="EMBARCADORES" name="prestadora[]"> <?=lang('embarcadores')?>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary gerar_rel" >
                        <?=lang('gerar') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="alert_acao row" style="display:none">
        <button type="button" class="close" >&times;</button>
        <span id="mensagem"></span>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h5><span id="total_quantitativo"></span></h5>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <table id="tableqtdContratos" class="table table-hover table-bordered responsive display" style="width:100%;">
                        <thead>
                            <th>#</th>
                            <th><?=lang('mes_ano')?></th>
                            <th><?=lang('quantidade_contratos')?></th>
                            <th><?=lang('quantidade_veiculos')?></th>
                            <th><?=lang('veiculos_ativos')?></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"  style="background-color:#337ab7 !important; color:#ffffff !important; "><?=lang('grafico_quantitativo')?></div>
                    <table style="width:100%;">
                        <tbody>
                            <tr>
                                <td><div id="chartContratos" class="panel-body col-md-6"></div></td>
                                <td><div id="chartVeiculos" class="panel-body col-md-6"></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tableqtdContratos;

    $(document).ready(function() {
        //gerencia os dias que o usuario pode escolher no calendario

        $('.data').datepicker({
            dateFormat: 'dd/mm/yy',
            showOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            highlightWeek: true,
            maxDate: 0,
        });

        //DECLARACAO DA DATATABLE
        tableqtdContratos = $('#tableqtdContratos').DataTable( {
            dom: 'Bfrtip',
            responsive: {
                details: {
                    type: 'column',
                    target: -1
                }
            },
            columns: [
                {data: 'indice'},
                {data: 'data'},
                {data: 'qtdContratos'},
                {data: 'qtdVeiculos'},
                {data: 'veiculosAtivos'}
            ],
            "columnDefs": [
                {
                    className: "dt-center",
                    targets: "_all",
                }
            ],
            order: [ 0, 'asc' ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    messageTop: function () {
                        return $('#total_quantitativo').text()+' | Período: '+$('#dp1').val()+' à '+$('#dp2').val()
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL'
                },
                {
                    extend: 'pdfHtml5',
                    messageTop: function () {
                        return $('#total_quantitativo').text()+' | Período: '+$('#dp1').val()+' à '+$('#dp2').val()
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    messageTop: function () {
                        return $('#total_quantitativo').text()+' | Período: '+$('#dp1').val()+' à '+$('#dp2').val()
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    extend: 'print',
                    messageTop: function () {
                        return $('#total_quantitativo').text()+' | Período: '+$('#dp1').val()+' à '+$('#dp2').val()
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> <?=strtoupper(lang('imprimir'))?>'

                }
            ],
            language: langDatatable
        });

    });

    //CARREGA A TABELA COM OS DADOS FILTRADOS
	$('#formGerarResult').submit(function(e){
		e.preventDefault();
        var callbak;

		var data_form = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: "<?=site_url('relatorios/loadQuantitativoContratos')?>",
			data: data_form,
            datatype: 'json',
			beforeSend: function () {
                $(".alert_acao").css('display','none');
				$('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> <?=lang("gerando")?>');
			},
			success: function (retorno) {
				callbak = JSON.parse(retorno);
				if (callbak.success) {
                    var dados = callbak.table;

                    //ATUALIZA O GRAFICO
                    var valoresGraficoContratos = [];
                    var valoresGraficoVeiculos = [];
                    valoresGraficoContratos.push([lang.data, lang.total_contratos]);
                    valoresGraficoVeiculos.push([lang.data, lang.total_veiculos]);

                    dados.forEach(function(value){
                        //GUARDA OS DADOS EM UM ARRAY QUE SERÁ USADO PARA MOSTRAR NO GRAFICO
                        valoresGraficoContratos.push([value.data, parseInt(value.qtdContratos)]);
                        valoresGraficoVeiculos.push([value.data, parseInt(value.qtdVeiculos)]);
                    });

                    google.charts.load("current", {packages:["corechart"]});
                    google.charts.setOnLoadCallback(drawChartContratos);
                    google.charts.setOnLoadCallback(drawChartVeiculos);

                    //CONFIGURA E MOSTRAR O GRAFICO DE CONTRATOS
                    function drawChartContratos() {
                        var dataContratos = google.visualization.arrayToDataTable( valoresGraficoContratos );
                        var options = {
                            width: 410,
                            height: 300,
                            is3D: true,
                            title: lang.quantitativo_contratos
                        };
                        var chartContratos = new google.visualization.PieChart(document.getElementById('chartContratos'));
                        chartContratos.draw(dataContratos, options);
                    }
                    //CONFIGURA E MOSTRAR O GRAFICO DE VEICULOS
                    function drawChartVeiculos() {
                        var dataVeiculos = google.visualization.arrayToDataTable( valoresGraficoVeiculos );
                        var options = {
                            width: 410,
                            height: 300,
                            is3D: true,
                            title: lang.quantitativo_veiculos
                        };
                        var chartVeiculos = new google.visualization.PieChart(document.getElementById('chartVeiculos'));
                        chartVeiculos.draw(dataVeiculos, options);
                    }

                    //ATUALIZA A TABELA
                    tableqtdContratos.clear();
                    tableqtdContratos.rows.add(callbak.table);
                    tableqtdContratos.draw();

                    //Atualiza total de veiculos
                    $('#total_quantitativo').html("<b><?=lang('total_contratos')?>:</b> "+callbak.total_contratos+" | "+"<b><?=lang('total_veiculos')?>:</b> "+callbak.total_veiculos);

				} else {
                    tableqtdContratos.clear().draw();

                    //Atualiza total de veiculos
                    $('#total_quantitativo').css('display', 'none');
    				$(".alert_acao").css('display','block');
                    callbak = JSON.parse(retorno);
    				$("#mensagem").html('<div class="alert alert-danger"><p><b>'+callbak.msg+'</b></p></div>');
				}
			},
			complete: function(){
				$('.gerar_rel').removeAttr('disabled').html('<?=lang("gerar")?>');
			}
		});

	});

    //esconde a mensagem caso apertem o botao de fechar
    $(document).on('click', '.close', function() {
        $(".alert_acao").css('display','none');
    });

    //LIMITA AS ESTRADAS DOS INPUTS DE DATAS PARA NO MAXIMO/MINIMO 1 ANO A FRENTE/ATRAS
    $(document).on('click', '.data', function() {
        minMaxDataImput('.data', new Date(), 0, 0, 1);
    });

</script>
