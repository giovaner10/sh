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
            <form style="align-items:center" id="formGerarResult" action="<?=site_url('relatorios/faturas_processadas')?>" method="get" accept-charset="utf-8">
                <div class="col-md-2">
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:87%" type="text" name="di" class="data" required autocomplete="off" id="dp1" placeholder="<?=lang('select_data_inicial')?>" value="" />
                </div>
                <div class="col-md-2">
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                    <input style="width:87%" type="text" name="df" class="data" required autocomplete="off" id="dp2" placeholder="<?=lang('select_data_final')?>" value="" />
                </div>
                               

                
                <div style="text-align:right; float: left;">
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
        <div class="col-md-12">
            <table id="tableFaturasProcessadas" class="datatable display responsive table-bordered table table-hover" style="width:100%">
                <thead>
                    <th>Id</th>
                    <th>Arquivo</th>
                    <th>Número Retorno</th>
                    <th>Data Pagamento</th>
                    <th>Data Execução</th>
                    <th><?=lang('status');?></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tableFaturasProcessadas=false;

    $(document).ready(function() {
        
        tableFaturasProcessadas = $('#tableFaturasProcessadas').DataTable( {
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
            ],
            columns: [
                { data: 'id_retorno' },
                { data: 'arquivo_retorno' },
                { data: 'fatnumero_retorno' },
                { data: 'datapagto_retorno' },
                { data: 'dataexec_retorno' },
                { data: 'statusexec_retorno' }
                
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    messageTop: function () {
                        return ' Período da Execução: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableFaturasProcessadas','orientation'),
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
                        return ' Período da Execução: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableFaturasProcessadas','orientation'),
                    pageSize: 'A4',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    messageTop: function () {
                        return ' Período da Execução: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tableFaturasProcessadas','orientation'),
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
			url: "<?=site_url('relatorios/ajaxListaFaturasProcessadas')?>",
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
                    tableFaturasProcessadas.clear();
                    tableFaturasProcessadas.rows.add(retorno.tabela);
                    tableFaturasProcessadas.draw();                    

				} 
			},
			error: function(){
                
                //Limpa a tabela
                tableFaturasProcessadas.clear().draw();

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
