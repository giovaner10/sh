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
            <form style="align-items:center" id="formGerarResult" action="<?=site_url('relatorios/faturas_atrasadas')?>" method="get" accept-charset="utf-8">
                <div class="col-md-2">
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:87%" type="text" name="di" class="data" required autocomplete="off" id="dp1" placeholder="<?=lang('select_data_inicial')?>" value="" />
                </div>
                <div class="col-md-2">
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:87%" type="text" name="df" class="data" required autocomplete="off" id="dp2" placeholder="<?=lang('select_data_final')?>" value="" />
                </div>
                <div class="col-md-2">
                    <i class="fa fa-home" style="font-size: 25px;"></i>
                    <select name="empresa" id="empresa" style="width:85%; height:25px;" required>
                        <option value="" selected disabled><?=lang('select_empresa')?></option>
                        <option value="todas"><?=lang('todas')?></option>
                        <option value="EMBARCADORES"><?=lang('embarcadores')?></option>
                        <option value="NORIO"><?=lang('norio')?></option>
                        <option value="OMNILINK"><?=lang('omnilink')?></option>
                        <option value="SIMM2M"><?=lang('simm2m')?></option>
                        <option value="TRACKER"><?=lang('show_tecnologia')?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <i class="fa fa-home" style="font-size: 25px;"></i>
                    <select name="orgao" id="orgao" style="width:85%; height:25px;" required>
                        <option value="" selected disabled><?=lang('select_orgao')?></option>
                        <option value="todos"><?=lang('todos')?></option>
                        <option value="publico"><?=lang('publico')?></option>
                        <option value="privado"><?=lang('privado')?></option>
                    </select>
                </div>
                <div class="col-md-2" style="width:20% !important">
                    <i class="fa fa-user" style="font-size: 22px;"></i>
                    <select name="id_cliente" id="clientes">
                        <option value=""><?=lang('selecione_cliente')?></option>
                    </select>
                </div>
                <div style="text-align:right; margin-top:15px;">
                    <button type="reset" class="btn btn-secundary" id="btn_limpar_busca"><?=lang('limpar')?></button>
                    <button type="submit" class="btn btn-primary gerar_rel" > <?=lang('gerar') ?> </button>
                </div>
            </form>
        </div>
    </div>
    <div class="alert_acao alert col-md-12" style="display:none">
        <button type="button" class="close" onclick="fecharMensagem('alert_acao')">&times;</button>
        <span id="mensagem"></span>
    </div>
    <div class="col-md-12">
        <h4><span id="total_faturas"></span><span id="valor_total"></span></h4>
        <div class="col-md-12">
            <table id="tableFaturasAtrasadas" class="datatable display responsive table-bordered table table-hover" style="width:100%">
                <thead>
                    <th>Id</th>
                    <th><?=lang('cliente');?></th>
                    <th><?=lang('data_emissao');?></th>
                    <th><?=lang('data_vencimento');?></th>
                    <th><?=lang('valor_total');?></th>
                    <th><?=lang('status');?></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tableFaturasAtrasadas=false;

    $(document).ready(function() {
        $('#clientes').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            }
        });

        tableFaturasAtrasadas = $('#tableFaturasAtrasadas').DataTable( {
            dom: 'Bfrtip',
            responsive: true,
            order: [ 0, 'desc' ],
            "columnDefs": [
                {
                    className: "dt-center",
                    targets: "_all",
                },
                {
                    type: 'date-uk',
                    targets: [2, 3]
                },
                {
                   render: $.fn.dataTable.render.number( '.', ',', 2),
                   targets: [4]
                }
            ],
            columns: [
                { data: 'id' },
                { data: 'cliente' },
                { data: 'data_emissao' },
                { data: 'data_vencimento' },
                { data: 'valor_total' },
                { data: 'status' }
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    messageTop: function () {
                        return $('#total_faturas').text()+' | '+$('#valor_total').text()+' | Período: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableFaturasAtrasadas','orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL',
                    exportOptions: {
                        format: {
                            body: function (data, row, column, node) {
                                var columnsDates = [4];
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
                        return $('#total_faturas').text()+' | '+$('#valor_total').text()+' | Período: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableFaturasAtrasadas','orientation'),
                    pageSize: 'A3',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    messageTop: function () {
                        return $('#total_faturas').text()+' | '+$('#valor_total').text()+' | Período: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableFaturasAtrasadas','orientation'),
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
			url: "<?=site_url('relatorios/ajaxListaFaturasAtrasadas')?>",
			data: data_form,
            datatype: 'json',
			beforeSend: function () {
                $(".alert_acao").css('display','none');
				$('.gerar_rel').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?=lang("gerando")?>');
			},
			success: function (retornoJson) {
				retorno = JSON.parse(retornoJson);

				if (retorno.success) {
                    // Atualiza Tabela
                    tableFaturasAtrasadas.clear();
                    tableFaturasAtrasadas.rows.add(retorno.tabela);
                    tableFaturasAtrasadas.draw();

                    //Atualiza qtd e valor total de equipamentos/veiculos
                    $('#total_faturas').html("<b><?=lang('total_faturas')?>:</b> "+retorno.tabela.length).css('margin-right', '20px');
                    $('#valor_total').html("<b><?=lang('valor_total')?>:</b> "+retorno.valor_total);

				} else {
                    //Atualiza qtd e valor total de equipamentos/veiculos
                    $('#total_faturas').html('');
                    $('#valor_total').html('');

                    //Limpa a tabela
                    tableFaturasAtrasadas.clear().draw();

                    $("#mensagem").html('<div class="alert alert-danger"><p><b>'+retorno.msg+'</b></p></div>');
    				$(".alert_acao").css('display','block');
				}
			},
			error: function(){
                //Atualiza qtd e valor total de equipamentos/veiculos
                $('#total_faturas').html('');
                $('#valor_total').html('');

                //Limpa a tabela
                tableFaturasAtrasadas.clear().draw();

                $("#mensagem").html('<div class="alert alert-danger"><p><b>'+lang.operacao_nao_finalizada+'</b></p></div>');
				$(".alert_acao").css('display','block');
			},
			complete: function(){
				$('.gerar_rel').removeAttr('disabled').html('<?=lang("gerar")?>');
			}
		});

	});

    $(document).on('click', '#btn_limpar_busca', function(e){
        e.preventDefault();
        $('#formGerarResult')[0].reset()
        $('#clientes').val(null).trigger('change');
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
