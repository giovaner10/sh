<style>
    .close{
        margin-right: 10px!important;
        margin-top: 10px!important;
    }
    .select2-container{ width: 90% !important; }
</style>
<div class="row">
    <div class="col-md-12">
        <h3><?=$titulo?></h3>
        <div class="well well-small col-md-12">
            <form style="align-items:center" id="formGerarResult" action="<?=site_url('relatorios/monitoradosDiaAtualizacao')?>" method="get" accept-charset="utf-8">
                <div class="col-md-3">
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:90%" type="text" value="" name="di" class="data" required autocomplete="off" id="dp1" value="" />
                </div>
                <div class="col-md-3">
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:90%" type="text" value="" name="df" class="data" required autocomplete="off" id="dp2" value="" />
                </div>
                <div class="col-md-3">
                    <i class="fa fa-user" style="font-size: 22px;"></i>
                    <select name="id_cliente" id="clientes" required>
                        <option value=""><?=lang('selecione_cliente')?></option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary gerar_rel" >
                        <?=lang('gerar') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="alert_acao alert col-md-12" style="display:none">
        <button type="button" class="close" onclick="fecharMensagem('alert_acao')">&times;</button>
        <span id="mensagem"></span>
    </div>
    <div class="col-md-12">
        <h4><span id="total_veiculos"></span><span id="valor_total"></span></h4>
        <div class="col-md-12">
            <table id="tableEquipamentos" class="datatable display responsive dt-responsive table-bordered table table-hover" style="width:100%">
                <thead>
                    <th></th>
                    <th>Id</th>
                    <th><?=lang('placa');?></th>
                    <th><?=lang('serial');?>/IMEI</th>
                    <th><?=lang('prefixo');?></th>
                    <th><?=lang('modelo_veiculo');?></th>
                    <th><?=lang('modelo_rastreador');?></th>
                    <th><?=lang('fabricante');?></th>
                    <th><?=lang('tipo_frota');?></th>
                    <th><?=lang('o_s');?></th>
                    <th><?=lang('lote');?></th>
                    <th><?=lang('centro_custo');?></th>
                    <th><?=lang('data_desinstalacao');?></th>
                    <th><?=lang('dias_utilizacao');?></th>
                    <th><?=lang('valor_mensal');?></th>
                    <th><?=lang('valor_diario');?></th>
                    <th><?=lang('valor_instalacao');?></th>
                    <th><?=lang('valor_faturado');?></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tableEquipamentos = false;

    $(document).ready(function() {
        $('#clientes').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            }
        });

        tableEquipamentos = $('#tableEquipamentos').DataTable( {
            dom: 'Bfrtip',
            order: [ 1, 'asc' ],
            responsive: {
                details: {
                    type: 'column',
                    target: 0
                }
            },
            "columnDefs": [
                {
                    className: 'dtr-control',
                    orderable: false,
                    targets:   0
                },
                {
                    className: "dt-center",
                    targets: "_all",
                },
                {
                    type: 'date-uk',
                    targets: [12]
                },
                {
                   render: $.fn.dataTable.render.number( '.', ',', 2),
                   targets: [14,15,16,17]
                }
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    messageTop: function () {
                        return $('#total_veiculos').text()+' | '+$('#valor_total').text()+' | Cliente: '+$('#clientes option:selected').text()+' | PerÃ­odo: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableEquipamentos','orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL',
                    exportOptions: {
                        format: {
                            body: function (data, row, column, node) {
                                var columnsDates = [14, 15, 16, 17];
                            	if (columnsDates.includes(column)){
                                    if (data)
                                        return data.replace( '.', ';').replace(',','.').replace(';',',');  //deixa no formato 1,254.21
                                }
                                return data;
                            }
                        }
	                }
                },
                {
                    extend: 'pdfHtml5',
                    messageTop: function () {
                        return $('#total_veiculos').text()+' | '+$('#valor_total').text()+' | Cliente: '+$('#clientes option:selected').text()+' | PerÃ­odo: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableEquipamentos','orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    messageTop: function () {
                        return $('#total_veiculos').text()+' | '+$('#valor_total').text()+' | Cliente: '+$('#clientes option:selected').text()+' | PerÃ­odo: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableEquipamentos','orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                }
            ],
            language: langDatatable
        });

    });

    //CARREGA A TABELA COM OS DADOS FILTRADOS
	$('#formGerarResult').submit(function(e){
		e.preventDefault();

		var data_form = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: "<?=site_url('relatorios/listaDadosEquipamentosAtividades')?>",
			data: data_form,
            datatype: 'json',
			beforeSend: function () {
                $(".alert_acao").css('display','none');
				$('.gerar_rel').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?=lang("gerando")?>');
			},
			success: function (retornoJson) {
				retorno = JSON.parse(retornoJson);

				if (retorno.status) {
                    // Atualiza Tabela
                    tableEquipamentos.clear();
                    tableEquipamentos.rows.add(retorno.tabela);
                    tableEquipamentos.draw();

                    //Atualiza qtd e valor total de equipamentos/veiculos
                    $('#total_veiculos').html("<b><?=lang('total_veiculos')?>:</b> "+retorno.tabela.length).css('margin-right', '20px');
                    $('#valor_total').html("<b><?=lang('valor_total')?>:</b> "+retorno.valor_total);

				} else {
                    //Atualiza qtd e valor total de equipamentos/veiculos
                    $('#total_veiculos').html('');
                    $('#valor_total').html('');

                    //Limpa a tabela
                    tableEquipamentos.clear().draw();

                    $("#mensagem").html('<div class="alert alert-danger"><p><b>'+retorno.msg+'</b></p></div>');
    				$(".alert_acao").css('display','block');
				}
			},
			error: function(){
                //Atualiza qtd e valor total de equipamentos/veiculos
                $('#total_veiculos').html('');
                $('#valor_total').html('');

                //Limpa a tabela
                tableEquipamentos.clear().draw();

                $("#mensagem").html('<div class="alert alert-danger"><p><b>'+lang.operacao_nao_finalizada+'</b></p></div>');
				$(".alert_acao").css('display','block');
			},
			complete: function(){
				$('.gerar_rel').removeAttr('disabled').html('<?=lang("gerar")?>');
			}
		});

	});

    //gerencia os dias que o usuario pode escolher no calendario
    $( function() {
        var dateFormat = 'dd/mm/yy',
        from = $( "#dp1" )
        .datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            maxDate:new Date()
        })
        .on( "change", function() {
            // from.datepicker( "option", "maxDate", getDate( this ) );
            to.datepicker( "option", "minDate", getDate( this ) );
            var hoje = new Date();
            var dateAtual = new Date(hoje.getTime());
            var dataImput01 = getDate( this );
            var dataFinal = new Date(dataImput01.getTime() + (30 * 24 * 60 * 60 * 1000));
            if (dataFinal > dateAtual) {
                dataFinal = dateAtual;
            }
            to.datepicker( "option", "maxDate", dataFinal.getDate() + "/" + (dataFinal.getMonth() + 1) + "/" + dataFinal.getFullYear() );
        }),
        to = $( "#dp2" ).datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            maxDate:new Date()
        })
        .on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
        });

        function getDate( element ) {
            var date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            }
            return date;
        }
    });

</script>