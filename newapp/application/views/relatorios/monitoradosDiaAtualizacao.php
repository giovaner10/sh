<style>
    .close{
        margin-right: 10px!important;
        margin-top: 10px!important;
    }
    .select2-container{ width: 90% !important; }
    .progress-bar{
        position: absolute;
        height: 100%;
        background-color: #66b3ff;
        width: 0%;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <h3><?=$titulo?></h3>
        <div class="alert_faturamento alert col-md-12" style="display:none;">
            <button type="button" class="close" onclick="fecharMensagem('alert_faturamento')">&times;</button>
            <span id="msgFaturamento"></span>
        </div>
        <div class="well well-small col-md-12">
            <form style="align-items:center" id="formGerarResult" action="<?=site_url('relatorios/monitoradosDiaAtualizacao')?>" method="get" accept-charset="utf-8">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <i class="fa fa-handshake-o" style="font-size: 22px;"></i>
                        <select name="tipo_faturamento" id="tipo_faturamento" style="width:85%; height:25px;" required>
                            <option value="" disabled selected><?=lang('selecione_faturamento')?></option>
                            <option value="diasRastreados"><?=lang('dias_de_atividade')?></option>
                            <option value="dataAtivacao"><?=lang('data_ativacao')?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:89%" type="text" name="di" class="data" required placeholder="<?=lang('data_inicial')?>" autocomplete="off" id="dp1" value="" />
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:89%" type="text" name="df" class="data" required placeholder="<?=lang('data_final')?>" autocomplete="off" id="dp2" value="" />
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-user" style="font-size: 22px;"></i>
                        <select name="id_cliente" id="clientes" required>
                            <option value=""><?=lang('selecione_cliente')?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                    <div class="col-md-3">
                        <i class="fa fa-wpforms" style="font-size: 22px;"></i>
                        <select name="formato_relatorio" id="formato_relatorio" style="width:85%; height:25px;" required disabled>
                            <option value="" selected disabled><?=lang('selecione_formato_relatorio')?></option>
                            <option value="completo"><?=lang('completo')?></option>
                            <option value="resumido"><?=lang('resumido')?></option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary gerar_rel" >
                            <?=lang('gerar') ?>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="alert_acao alert col-md-12" style="display:none">
        <button type="button" class="close" onclick="fecharMensagem('alert_acao')">&times;</button>
        <span id="mensagem"></span>
    </div>    

    <div class="col-md-12">
        <div class="progress col-md-12" style="display: none">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="progresso"></span></div>
        </div>
        <h4><span id="total_monitorados"></span>  <span id="valor_total"></span></h4>
        <div class="col-md-12">
            <div id="CriaTab"> </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var table=false;

    $(document).ready(function() {
        $('#clientes').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            }
        });
    });

    //CARREGA A TABELA COM OS DADOS FILTRADOS
	$('#formGerarResult').submit(function(e){
		e.preventDefault();

		var data_form = $(this).serialize();
        var retorno;

        var botao = $('.gerar_rel');            
        //cria as colunas do intervalo de datas na head da tabela
        var thCreat = '';

        //cria a tabela dinamicamente            
        if ($('#formato_relatorio').val() == 'completo') {
            let dataIni = new Date(($('#dp1').val().split('/').reverse().join('-'))+' 00:00:00');                
            let dataFim = new Date(($('#dp2').val().split('/').reverse().join('-'))+' 23:59:59');

            while(dataIni <= dataFim) {
                let data = dataIni;
                data = data.toLocaleString("pt-BR", {timeZone: "America/Recife"}).split(' ')[0];
                thCreat +=
                    '<th>'+
                        data.substr(0, 5)+
                    '</th>';
                dataIni.setDate(dataIni.getDate() + 1);
            }
        }

        var th =
            `<table id="tb_detentos" class="datatable display responsive dt-responsive table-bordered table table-hover" style="width:100%">
                <thead id="novaTh">
                    <th></th>
                    <th>${lang.monitorado}</th>
                    <th>${lang.contrato}</th>
                    <th>${lang.tornozeleira}</th>
                    <th>${lang.cinta}</th>
                    <th>${lang.powerbank}</th>
                    <th>${lang.carregador}</th>
                    <th>${lang.unidade}</th>
                    <th>${lang.unidade_custodia}</th>
                    <th>${lang.data_ativacao}</th>
                    <th>${lang.data_inativacao}</th>
                    <th>${lang.quant_dias}</th>
                    <th>${lang.valor}</th>
                    ${thCreat}
                </thead>
                <tbody></tbody>
            </table>`;
        $('#CriaTab').html(th);

        table = $('#tb_detentos').DataTable( {
            dom: 'Bfrtip',
            destroy: true,
            order: [ 1, 'desc' ],
            responsive: {
                details: {
                    type: 'column',
                    target: 0
                }
            },
            columnDefs: [
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
                    targets: [9, 10]
                },
                {
                render: $.fn.dataTable.render.number( '.', ',', 2),
                targets: [12]
            }
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    messageTop: function () {
                        return $('#total_monitorados').text()+' | '+$('#valor_total').text()+' | Cliente: '+$('#clientes option:selected').text()+' | Período: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tb_detentos','orientation'),
                    pageSize: customPageExport('tb_detentos','pageSize'),
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL',
                    exportOptions: {
                        format: {
                            body: function (data, row, column, node) {
                                var columnsNumbers = [12];
                                if (columnsNumbers.includes(column)){
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
                        return $('#total_monitorados').text()+' | '+$('#valor_total').text()+' | Cliente: '+$('#clientes option:selected').text()+' | Período: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tb_detentos','orientation'),
                    pageSize: customPageExport('tb_detentos','pageSize'),
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    customize: function(doc) {
                        doc.defaultStyle.alignment = 'center';
                        doc.styles.tableHeader.alignment = 'center';
                    }
                },
                {
                    extend: 'csvHtml5',
                    messageTop: function () {
                        return $('#total_monitorados').text()+' | '+$('#valor_total').text()+' | Cliente: '+$('#clientes option:selected').text()+' | Período: '+$('#dp1').val()+' a '+$('#dp2').val()
                    },
                    orientation: customPageExport('tb_detentos','orientation'),
                    pageSize: customPageExport('tb_detentos','pageSize'),
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                }
            ],
            language: langDatatable
        });

        //limpa os dados         
        $('#total_monitorados').html('');
        $('#valor_total').html('');
        table.clear();
        $('.progress').css('display', 'none');

        var valor_total = 0.0, qtd_total = 0;

        //CONFIGURA A MENSAGEM DE ALERT BOTAO
        $(".alert_acao").css('display','none');
        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?=lang("gerando")?>');

        function gerarRelatorio(parte = 1, tries = 3) {
            data_form = data_form+'&parte='+parte;
            
            $.ajax({
                type: 'POST',
                url: "<?=site_url('relatorios/listaMonitoradosDiaAtualizacao')?>",
                data: data_form,
                datatype: 'json',
                success: function (retorno) {
                    callback = JSON.parse(retorno);
                    if (callback.success) {
                        if (callback.parte == 1) table.clear();
                        if (Array.isArray(callback.tabela)) {

                            // Atualiza valores totais
                            valor_total += parseFloat(callback.valor_total);
                            qtd_total += parseFloat(callback.qtd_total);
                           
                            //preenche a tabela
                            table.rows.add(callback.tabela);
                            table.draw();
                            table.responsive.recalc();
                        }

                        //se o tipo faturamento for diasRastreados mostra o progressBar
                        if ($('#tipo_faturamento').val() == 'diasRastreados') {
                            try {
                                let width = Math.round(100*callback.parte/callback.partes);
                                $('.progress').css('display', 'grid');
                                $('.progress-bar').css('width', `${width}%`);
                                $('#progresso').html(`<?=lang('progresso')?>: ${width}%`);
                            } catch (err) {
                                console.log(`Progresso: ${callback.parte} de ${callback.partes}`)
                            }
                        }

                        if (callback.parte < callback.partes) {
                            setTimeout(function(){ 
                                gerarRelatorio(parte + 1);
                            }, 500);
                        }
                        else {
                            botao.attr('disabled', false).html('<?=lang("gerar")?>');
                             //Atualiza os totais
                            $('#total_monitorados').html("<b><?=lang('total_monitorados')?>:</b> "+qtd_total).css('margin-right', '20px');
                            $('#valor_total').html("<b><?=lang('valor_total')?>:</b> R$ "+valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
                        }

                    } else if (!callback.success && tries > 0) {
                        gerarRelatorio(parte, tries - 1);

                    } else {
                        if (parte > 1) {
                            $("#mensagem").html('<div class="alert alert-danger"><p><b>'+lang.nao_foi_possivel_concluir_geracao_relatorio+'</b></p></div>');
                            $(".alert_acao").css('display','block');
                        }else {
                            $("#mensagem").html('<div class="alert alert-danger"><p><b>'+lang.nao_foi_possivel_gerar_relatorio+'</b></p></div>');
                            $(".alert_acao").css('display','block');
                        }
                        table.clear().draw();
                        $('.progress').css('display', 'none');
                        $('.progress-bar').css('width', '0%');
                        $('#progresso').html('');
                        
                        if (parte == 1) $('.progress').css('display', 'none');
                        botao.attr('disabled', false).html('<?=lang("gerar")?>');
                    }
                },
                error: function(err) {
                    if (parte == 1) $('.progress').css('display', 'none');
                    botao.attr('disabled', false).html('<?=lang("gerar")?>');

                    $("#mensagem").html('<div class="alert alert-danger"><p><b>'+lang.nao_foi_possivel_concluir_geracao_relatorio+'</b></p></div>');
                    $(".alert_acao").css('display','block');                        
                }
            });
        }

        gerarRelatorio();
		
    });

    //DESABILITA A SELECAO DE FORMATO DE LAOYOUT DO RELATORIO PARA FATURAMENTO POR ATIVACAO
     $(document).on('change', '#tipo_faturamento', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if (valor == 'diasRastreados') {
            $('#formato_relatorio').attr('disabled', false).attr('required', true);
            $("#msgFaturamento").html('<div class="alert alert-info" style="margin-bottom:0px"><p><b>'+lang.msg_rel_tipo_faturamento1+'</b></p></div>');
            $(".alert_faturamento").css('display','block').css('margin-bottom','0px');

        }else{
            $('#formato_relatorio option:first').prop('selected',true);
            $('#formato_relatorio').attr('disabled', true).attr('required', false);
            $("#msgFaturamento").html('<div class="alert alert-info" style="margin-bottom:0px"><p><b>'+lang.msg_rel_tipo_faturamento2+'</b></p></div>');
            $(".alert_faturamento").css('display','block').css('margin-bottom','0px');
        }
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

    /* TRADUCAO DO PLUGIN DATAPICKET */
    jQuery(function($){
        $.datepicker.regional['pt-BR'] = {
            closeText: 'Fechar',
            prevText: '&#x3c;Anterior',
            nextText: 'Pr&oacute;ximo&#x3e;',
            currentText: 'Hoje',
            monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
            dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };

        $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
    });

</script>
