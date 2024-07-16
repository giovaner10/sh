<style type="text/css">
    .select2-container--default .select2-selection--single {
        border: 1px solid #333!important;
        border-radius: 0px!important;
        height: 30px!important;
        text-align: Left!important;
    }
    .formatInput {
        width: 40% !important;
        height: 30px!important;
    }
    .btn:not(.btn-link):not(.btn-circle) i {
        font-size: 16px!important;
    }

</style>

<div class="container-fluid">
    <h3><?=lang('monitoramento_panico')?></h3>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#aba_panico"><?=lang('ativos')?></a></li>
        <li><a data-toggle="tab" href="#aba_historico"><?=lang('historico')?></a></li>
    </ul>

    <div class="tab-content">
        <div id="aba_panico" class="tab-pane in active ">
            <div style="margin-top: 15px">
                <div class="col-md-6" style="padding-left: 0px;">
                    <div id="total_veiculos_panico"></div>
                </div>
                <div class="col-md-6" style="text-align:right; padding-right:0px;">
                    <select id="filtroPanico" class="formatInput">
                        <option value="todos" selected disabled><?=lang('selec_filtro')?></option>
                        <option value="id_evento">Id</option>
                        <option value="placa"><?=lang('placa')?></option>
                        <option value="time_gps"><?=lang('data')?></option>
                        <option value="serial"><?=lang('serial')?></option>
                        <option value="id_cliente"><?=lang('cliente')?></option>
                    </select>
                    <span id="span_searchTablePanico">
                        <input type="text" id="searchTablePanico" class="formatInput" autocomplete="off" disabled>
                    </span>
                    <span id="span_cliente" style="display:none;">
                        <select name="cliente" id="cliente" class="formatInput">
                            <option value=""><?=lang('selecione_cliente')?></option>
                        </select>
                    </span>

                    <button type="button" id="btnSearchPanico" class="btn btn-primary" disabled><i class="fa fa-search"></i></button>
                    <button type="button" id="btnResetsearchPanico" class="btn btn-default" style=""><?=lang('limpar')?></button>
                </div>
            </div>

            <table id="tablePanico" class="table table-bordered table-hover responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th><?=lang('placa')?></th>
                        <th><?=lang('data')?></th>
                        <th><?=lang('serial')?></th>
                        <th><?=lang('latitude')?></th>
                        <th><?=lang('longitude')?></th>
                        <th><?=lang('velocidade')?></th>
                        <th><?=lang('endereco')?></th>
                        <th><?=lang('cliente')?></th>
                        <th><?=lang('responsavel')?></th>
                        <th><?=lang('administrar')?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="aba_historico" class="tab-pane fade">
            <div class="well well-small col-md-12">
                <form style="align-items:center" id="formGerarResult">
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:85%; height:30px!important" type="date" name="di" class="data" min="1991-01-01" max="2999-12-28" placeholder="<?=lang('data_inicial')?>" required autocomplete="off" id="dp1" value="" />
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:85%; height:30px!important" type="date" name="df" class="data" min="1991-01-01" max="2999-12-28" placeholder="<?=lang('data_final')?>" required autocomplete="off" id="dp2" value="" />
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-user" style="font-size: 22px;"></i>
                        <select name="cliente_historico" id="cliente_historico" required>
                            <option value=""><?=lang('selecione_cliente')?></option>
                        </select>
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary gerar_rel" > <?=lang('gerar') ?> </button>
                    </div>
                </form>
            </div>

            <h4 id="total_alertas"></h4>
            <table id="tableHistorico" class="table table-bordered table-hover responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th><?=lang('placa')?></th>
                        <th><?=lang('data')?></th>
                        <th><?=lang('serial')?></th>
                        <th><?=lang('endereco')?></th>
                        <th><?=lang('velocidade')?></th>
                        <th><?=lang('cliente')?></th>
                        <th><?=lang('removido_por')?></th>
                        <th><?=lang('tratado_em')?></th>
                        <th><?=lang('observacao')?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tratamento de Evento -->
<div id="modalTratamento" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="tituloModalTratamento"></h3>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <form id="formTratamento">
                        <div class="col-md-12">
                            <label for="observacao_tratamento"><?=lang('observacao')?>: </label>
                            <textarea name="observacao" id="observacao_tratamento" rows="2" style="width:100%"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btnFormTratamento"><?=lang('finalizar_tratamento')?></button>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tablePanico = false;
    var tableHistorico = false;
    var page = 0;

    $(document).ready(function() {
        $('#cliente_historico').select2({
            ajax: {
                url: '<?= site_url('clientes/listAjaxSelectClient') ?>',
                dataType: 'json'
            },
            width: '85%'
        });

        $('#cliente').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            },
            width: '40%'
        });

        //CARREGA OS DADOS DA TABELA DE PANICO
        loadTablePanico();

        //INSTANCIA A TABELA DE HISTORICO
        tableHistorico = $('#tableHistorico').DataTable( {
            dom: 'Bfrtip',
            responsive: true,
            destroy: true,
            order: [ 0, 'asc' ],
            columns: [
                { data: 'id' },
                { data: 'placa' },
                { data: 'data' },
                { data: 'serial' },
                { data: 'endereco' },
                { data: 'velocidade' },
                { data: 'cliente' },
                { data: 'removido_por' },
                { data: 'tratado_em' },
                { data: 'observacao' }
            ],
            otherOptions: {},
            initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
            },
            buttons: [
                {
                    filename: filenameGenerator(lang.historico_eventos_panico),
                    extend: 'pdfHtml5',
                    orientation: customPageExport('tableHistorico','orientation'),
                    pageSize: customPageExport('tableHistorico','pageSize'),
                    className: 'btn btn-outline-info',
                    text: 'PDF',
                    messageTop: function () {
                        var di = $('#dp1').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#dp2').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#total_alertas').text()+' | '+lang.cliente+': '+$('#cliente_historico option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    },
                    customize: function ( win )
                    {
                        // Personaliza a página para PDF
                        let titulo = lang.historico_eventos_panico;
                        pdfTemplate(win, titulo);
                    }
                },
                {     
                    title: lang.historico_eventos_panico,               
                    extend: 'excelHtml5',                    
                    orientation: customPageExport('tableHistorico','orientation'),
                    pageSize: customPageExport('tableHistorico','pageSize'),
                    className: 'btn btn-outline-info',
                    text: 'EXCEL',
                    messageTop: function () {
                        var di = $('#dp1').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#dp2').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#total_alertas').text()+' | '+lang.cliente+': '+$('#cliente_historico option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    }                  
                },                
                {
                    title: lang.historico_eventos_panico,
                    extend: 'csvHtml5',                    
                    orientation: customPageExport('tableHistorico','orientation'),
                    pageSize: customPageExport('tableHistorico','pageSize'),
                    className: 'btn btn-outline-info',
                    text: 'CSV',
                    messageTop: function () {
                        var di = $('#dp1').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#dp2').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#total_alertas').text()+' | '+lang.cliente+': '+$('#cliente_historico option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    }
                },
                {
                    filename: filenameGenerator(lang.historico_eventos_panico),
                    extend: 'print',
                    orientation: customPageExport('tableHistorico','orientation'),
                    pageSize: customPageExport('tableHistorico','pageSize'),
                    className: 'btn btn-outline-info',
                    text: lang.imprimir.toUpperCase(),
                    messageTop: function () {
                        var di = $('#dp1').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#dp2').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#total_alertas').text()+' | '+lang.cliente+': '+$('#cliente_historico option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    },
                    customize: function ( win )
                    {
                        let titulo = lang.historico_eventos_panico;
                        // Personaliza a página Impressa
                        printTemplate(win, titulo);
                    }

                }
            ],            
            language: lang.datatable
        });

    });

    //CARREGA A TABELA COM OS DADOS FILTRADOS
	$('#formGerarResult').submit(function(e){
		e.preventDefault();

		var data_form = $(this).serialize();

		$.ajax({
			type: 'POST',
			url: "<?=site_url('monitor/gerarRelatorioPanico')?>",
			data: data_form,
            dataType: 'json',
			beforeSend: function () {
				$('.gerar_rel').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?=lang("gerando")?>');
			},
			success: function (retorno) {

				if (retorno.success) {
                    // Atualiza Tabela
                    tableHistorico.clear();
                    tableHistorico.rows.add(retorno.table);
                    tableHistorico.draw();

                    //Atualiza qtd total de eventos
                    $('#total_alertas').html("<b><?=lang('total_alertas')?>:</b> "+retorno.table.length);

				} else {
                    //Atualiza qtd total de eventos
                    $('#total_alertas').html('');

                    //Limpa a tabela
                    tableHistorico.clear().draw();

                    alert(retorno.msg);
				}
			},
			error: function(){
                //Atualiza qtd total de eventos
                $('#total_alertas').html('');

                //Limpa a tabela
                tableHistorico.clear().draw();

                alert(lang.operacao_nao_finalizada);
			},
			complete: function(){
				$('.gerar_rel').removeAttr('disabled').html('<?=lang("gerar")?>');
			}
		});

	});

    function loadTablePanico() {
        tablePanico = $('#tablePanico').DataTable({destroy: true}).clear();

        let filtro = $('#filtroPanico').val();
        let searchPanico = filtro == 'id_cliente' ? $('#cliente option:selected').val() : $('#searchTablePanico').val();

        tablePanico = $('#tablePanico').DataTable({
            lengthChange: false,
            serverSide: true,
            responsive: true,
            processing: false,
            searching: false,
            destroy: true,
            order: [ 0, 'desc' ],         
            ajax: {
                url: "<?= site_url('monitor/ajaxLoadEventosPanico') ?>",
                type: 'POST',
                dataType: 'json',
                data:{
                    filtroPanico: $('#filtroPanico').val(),
                    searchPanico: searchPanico.trim()
                },
                beforeSend: function () {
                    // criamos o loading
                    $('#tablePanico > tbody').html(
                        `<tr class="odd">
                            <td valign="top" colspan="12" class="dataTables_empty">${lang.carregando}</td>
                        </tr>`
                    );
                },
                complete: function(callback){
            		$('#total_veiculos_panico').html('<span class="label label-info" style="font-size: 100%;">'+lang.total_alertas+' <span class="badge badge-alert">'+tablePanico.page.info().recordsTotal+'</span></span>');
                    $('#btnSearchPanico').html('<i class="fa fa-search"></i>');
            	}
            },            
            otherOptions: {},
            initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
            },
            language: lang.datatable,
            columns: [
                { data: 'id' },
                { data: 'placa' },
                { data: 'data' },
                { data: 'serial' },
                { data: 'latitude' },
                { data: 'longitude' },
                { data: 'velocidade' },
                { 
                    data: 'endereco',
                    orderable: false
                },
                { 
                    data: 'cliente',
                    orderable: false
                },
                { 
                    data: 'responsavel',
                    orderable: false
                },
                {
                   data: "admin",
                   orderable: false
                }
            ]
        });
    }

    //GERENCIA A LIBERACAO DO CAMPO DE PESQUISA E DO BOTAO DE PESQUIA DA TABELA DEBITOS
    $(document).on('change', '#filtroPanico', function(e) {
        e.preventDefault();
        $('#searchTablePanico').attr('disabled', false);
        $('#btnSearchPanico').attr('disabled', false);

        let fil = $('#filtroPanico').val();
        if (fil == 'time_gps') {
            $('#span_searchTablePanico').css('display', 'inline');
            $('#searchTablePanico').attr('type', 'date');
            $('#span_cliente').css('display', 'none');

        }else if (fil == 'id_evento') {
            $('#span_searchTablePanico').css('display', 'inline');
            $('#searchTablePanico').attr('type', 'number');
            $('#span_cliente').css('display', 'none');

        }else if (fil == 'id_cliente') {
            $('#span_searchTablePanico').css('display', 'none');
            $('#span_cliente').css('display', 'inline');

        }else {
            $('#span_searchTablePanico').css('display', 'inline');
            $('#searchTablePanico').attr('type', 'text');
            $('#span_cliente').css('display', 'none');
        }
    });

    //EVENTO PARA PESQUISA NA TABELA DE DEBITOS
    $(document).on('click', '#btnSearchPanico', function(e) {
        e.preventDefault();

        var botao = $(this);
        botao.html('<i class="fa fa-spinner fa-spin"></i>');
        //CHAMA A FUNCAO QUE CARREGA OS DADOS DA TABELA FILTRANDO PELO SEARCH
        loadTablePanico();
    });

    //EVENTO PARA PESQUISA NA TABELA DE PANICO
    $(document).on('click', '#btnResetsearchPanico', function(e) {
        e.preventDefault();
        //DESABILITA O CAMPO E BOTAO DE PEQUISA
        $('#searchTablePanico').attr('disabled', true);
        $('#searchTablePanico').val('');
        $('#btnSearchPanico').attr('disabled', true);
        $('#cliente').val(null).trigger('change');

        //RESETA A SELECAO DO FILTRO DE PESQUSIA
        $('#filtroPanico option[value=todos]').prop('selected', true);

        //CHAMA A FUNCAO QUE CARREGA OS DADOS DA TABELA
        loadTablePanico();
    });

    //ABRE MODAL PARA TRATAR UM EVENTO
    $(document).on('click', '.btnTratar', function(e){
        e.preventDefault();
        var botao = $(this);
        var id = botao.attr('data-id');
        var serial = botao.attr('data-serial');

        $('#formTratamento')[0].reset();

        $.ajax({
            type: 'GET',
            url: '<?= site_url('monitor/get_evento') ?>/'+id,
            dataType: 'json',
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ');
            },
            success: function (callback) {
                if (callback.success) {
                    var call = callback.retorno;
                    if ( call.tem_responsavel ) {
                        alert(lang.evento_possui_responsavel + call.nome_responsavel );
                    
                    } else {
                        //PEGA PAGINA CORRENTE DA TABELA
                        page = tablePanico.page();

                        $('#tituloModalTratamento').html("<?=lang('tratando_evento')?>: #"+id);
                        $('#btnFormTratamento').attr('data-id', id).attr('data-serial', serial);
                        $('#modalTratamento').modal('show');
                    }
                    
                } else {
                    alert(callback.msg);
                }
                
            },
            error: function () {
                alert(lang.operacao_nao_finalizada);
            },
            complete: function () {
                botao.attr('disabled', false).html('<i class="fa fa-edit"></i> ');
            }
        });

        

    });

    
    //TRATA UM EVENTO
    $('#formTratamento').submit(function(e){
        e.preventDefault();

        var botao = $('#btnFormTratamento');
        var id = botao.attr('data-id');
        var serial = botao.attr('data-serial');
        var data_form = $(this).serialize()+'&id='+id+'&serial='+serial;

        $.ajax({
            type: 'POST',
            url: '<?= site_url('monitor/tratar_evento') ?>',
            data: data_form,
            dataType: 'json',
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.finalizando_tratamento);
            },
            success: function (callback) {
                if (callback.success) {
                    var count = parseInt($('span.badge-alert').html());
                    if (count > 0) $('span.badge-alert').html(count - 1);

                    //REMOVE E VAI PARA A PAGINA ONDE REMOVEL O EVENTO
                    tablePanico.row( botao.parents('tr') ).remove().draw();
                    tablePanico.page(page).draw(false);

                    $('#modalTratamento').modal('hide');
                }
                alert(callback.msg);
            },
            error: function () {
                alert(lang.operacao_nao_finalizada);
            },
            complete: function () {
                botao.attr('disabled', false).html(lang.finalizar_tratamento);
            }
        });

    });

    setInterval( function () {
        loadTablePanico(); // user paging is not reset on reload
    }, 120000 );
    

</script>
