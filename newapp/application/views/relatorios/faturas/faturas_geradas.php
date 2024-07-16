<style>
    .close{
        margin-right: 10px!important;
        margin-top: 10px!important;
    }
    tr.group,
    tr.group:hover { background-color: #ddd !important; }
    th.dt-center, td.dt-center { text-align: center; }
    .nada{ display: none; }
    .select2-container{ width: 90% !important; }
</style>
<div class="row">
    <div class="col-md-12">
        <h3><?=lang('relatorio'). ' - ' .lang('faturas_geradas')?></h3>
        <div class="well well-small col-md-12">
            <form style="align-items:center" id="formGerarResult" action="<?=site_url('relatorios/ajax_load_faturas_geradas')?>" method="get" accept-charset="utf-8">
                <div class="col-md-12">
                    <div class="col-md-2">
                        <i class="fa fa-calendar-check-o" style="font-size: 25px;"></i>
                        <select name="gerar_por" id="gerar_por" style="width:85%; height:25px;" required>
                            <option value="" selected disabled><?=lang('gerar_pela')?>:</option>
                            <option value="data_emissao"><?=lang('data_de_emissao')?></option>
                            <option value="data_vencimento"><?=lang('data_de_vencimento')?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:88%" type="date" name="di" required placeholder="<?=lang('data_inicial')?>" autocomplete="off" id="dp1" value="" />
                    </div>
                    <div class="col-md-2">
                        <i class="fa fa-calendar" style="font-size: 22px;"></i>
                        <input style="width:88%" type="date" name="df" required placeholder="<?=lang('data_final')?>" autocomplete="off" id="dp2" value="" />
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
                    <div class="col-md-2">
                        <i class="fa fa-file" style="font-size: 22px;"></i>
                        <select name="situacao" id="situacao" style="width:90%; height:25px;" required>
                            <option value="" selected disabled><?=lang('select_situacao')?></option>
                            <option value="todos"><?=lang('todos')?></option>
                            <option value="0"><?=lang('enviadas')?></option>
                            <option value="2"><?=lang('nao_enviadas')?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <i class="fa fa-user" style="font-size: 22px;"></i>
                        <select name="id_cliente" id="clientes">
                            <option value=""><?=lang('selecione_cliente')?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12" style="text-align:right; margin-top:15px;">
                    <div>
                        <button type="reset" class="btn btn-secundary" id="btn_limpar_busca"><?=lang('limpar')?></button>
                        <button type="submit" class="btn btn-primary gerar_rel" > <?=lang('gerar') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12">
        <div class="alert_acao row" style="display:none">
            <button type="button" class="close" onclick="fecharMensagem('alert_acao')">&times;</button>
            <span id="mensagem"></span>
        </div>
    </div>
    <div class="col-md-12">
        <h4><span id="total_faturas"></span><span id="total" style="margin-left:3em"></span></h4>
        <div>
            <table id="tableFaturasGeradas" class="display table responsive table-striped table-bordered" style="width:100%">
          <thead>
                <th>Id</th>
                <th><?=lang('cliente')?></th>
                <th><?=lang('prestadora')?></th>
                <th><?=lang('orgao')?></th>
                <th><?=lang('data_emissao')?></th>
                <th><?=lang('data_vencimento')?></th>
                <th><?=lang('valor')?></th>
                <th><?=lang('status')?></th>
          </thead>
          <tbody>
          </tbody>
        </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    let valor_total = 0;
    let data_ini = 'dd/mm/aaaa';
    let data_fim = 'dd/mm/aaaa';

    //ordenar pela coluna data de emissao
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-uk-pre": function ( a ) {
            if (a == null || a == "") {
                return 0;
            }
            var ukDatea = a.split('/');
            return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
        },

        "date-uk-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-uk-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );

    $(document).ready(function() {
        $('#clientes').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            }
        });

        var tableFaturasGeradas = $('#tableFaturasGeradas').DataTable( {
            dom: 'Bfrtip',
            paging: true,
            responsive: true,
            columnDefs: [
                {
                    className: "dt-center",
                    targets: "_all"
                },
                {
                    type: 'date-uk',
                    targets: [4,5]
                }
            ],
            orderable: false,
            order: [0, 'desc'],
            columns:[
                {data: 'id'},
                {data: 'cliente'},
                {data: 'prestadora'},
                {data: 'orgao'},
                {
                    data: 'data_emissao',
                    render: function(data, type, row, meta){
                      if (data) {
                        var split = data.split('-');
                        var data_nova = split[2] + '/' + split[1] + '/' + split[0];
                        return data_nova;
                      } else {
                        return 'NULL';
                      }
                    }
                },
                {
                    data: 'data_vencimento',
                    render: function(data, type, row, meta){
                      if (data) {
                        var split = data.split('-');
                        var data_nova = split[2] + '/' + split[1] + '/' + split[0];
                        return data_nova;
                      } else {
                        return 'NULL';
                      }
                    }
                },
                {
                    data: 'valor',
                    render: $.fn.dataTable.render.number( '.', ',', 2, 'R$' )
                },
                {data: 'status'}
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    messageTop: function () {
                        return $('#total').text()+' | '+$('#total_faturas').text()+' | <?=ucfirst(lang('periodo'))?>: '+data_ini+' à '+data_fim
                    },
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL'
                },
                {
                    extend: 'pdfHtml5',
                    messageTop: function () {
                        return $('#total').text()+' | '+$('#total_faturas').text()+' | <?=ucfirst(lang('periodo'))?>: '+data_ini+' à '+data_fim
                    },
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    messageTop: function () {
                        return $('#total').text()+' | '+$('#total_faturas').text()+' | <?=ucfirst(lang('periodo'))?>: '+data_ini+' à '+data_fim
                    },
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    extend: 'print',
                    messageTop: function () {
                        return $('#total').text()+' | '+$('#total_faturas').text()+' | <?=ucfirst(lang('periodo'))?>: '+data_ini+' à '+data_fim
                    },
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> <?=strtoupper(lang('imprimir'))?>'

                }
            ],
            language: langDatatable
        });


        $('form#formGerarResult').submit(function () {
            var data_get = $(this).serialize();
            $('.gerar_rel').attr('disabled', false).html('<i class="fa fa-spinner fa-spin"></i> <?=lang('gerando')?>');
            $.getJSON('<?=  site_url('relatorios/ajax_load_faturas_geradas') ?>', data_get, function(data) {
                if (data.success) {
                    var tabela = data.tabela;
                    $('.alert_acao').css('display', 'none');

                    // Atualiza Tabela
                    tableFaturasGeradas.clear();
                    tableFaturasGeradas.rows.add(tabela);
                    tableFaturasGeradas.draw();

                    //Atualiza qtd de veiculos e valor total
                    $('#total_faturas').text("<?=lang('total_faturas')?>: "+data.total_faturas);
                    $('#total').text("<?=lang('valor_total')?>: R$ "+data.valor_total);

                } else {
                    tableFaturasGeradas.clear();
                    tableFaturasGeradas.draw();

                    //Atualiza qtd de veiculos e valor total
                    $('#total_faturas').text("<?=lang('total_faturas')?>: 0");
                    $('#total').text("<?=lang('valor_total')?>: R$ 0,00");
                    $('.alert_acao').css('display', 'block');
                    $("#mensagem").html('<div class="alert alert-danger"><p><b>'+data.msg+'</p></div>');
                }

                // Ativa e troca html button "GERAR"
                $('.gerar_rel').removeAttr('disabled').html("<?=lang('gerar')?>");
            });

            return false;
        });

    });


    $(document).on('click', '#btn_limpar_busca', function(e){
        e.preventDefault();
        $('#formGerarResult')[0].reset()
        $('#clientes').val(null).trigger('change');
    });

    //SETA AS DATAS PARA O RELATORIO EXPOT
    $(document).on('change', '#dp1', function() {
        var split = $('#dp1').val().split('-');
        data_ini = split[2] + '/' + split[1] + '/' + split[0];
    });
    $(document).on('change', '#dp2', function() {
        var splitFim = $('#dp2').val().split('-');
        data_fim = splitFim[2] + '/' + splitFim[1] + '/' + splitFim[0];
    });

</script>
