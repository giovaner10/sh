<style>
    .close{
        margin-right: 10px!important;
        margin-top: 10px!important;
    }
    tr.group,
    tr.group:hover { background-color: #ddd !important; }
    th.dt-center, td.dt-center { text-align: center; }
    .nada{ display: none; }
    /* Select2*/
    .select2-container{
        width: 90% !important;
     }
</style>
<div class="container">
    <div class="row">
        <h3><?=lang('relatorio'). ' - ' .lang('geracao_adesao')?></h3>
        <div class="well well-small col-md-12">
            <form style="align-items:center" id="formGerarResult" action="<?=site_url('relatorios/loadStatusAdesoesInstalacoes')?>" method="get" accept-charset="utf-8">
                <div class="col-md-3">
                    <i class="fa fa-user" style="font-size: 22px;"></i>
                    <select name="id_cliente" id="clientes" required>
                        <option value=""><?=lang('selecione_cliente')?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <i class="fa fa-file" style="font-size: 22px;"></i>
                    <select name="id_contrato" id="contratos" disabled style="width: 90%; font-size:12px; padding:6px 12px; background-color:#fff; border: 1px solid #aaa; border-radius: 4px;" required >
                        <option value="" disabled selected><?=lang('selecione_contrato')?></option>
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
    <div class="alert_acao row" style="display:none">
        <button type="button" class="close">&times;</button>
        <span id="mensagem"></span>
    </div>

    <div class="row">
        <h4><span id="total_veiculos"></span><span id="total" style="margin-left:3em"></span></h4>
        <div class="col-md-12">
            <table id="tableAdesaoInstalacao" class="display table responsive table-striped table-bordered">
          <thead>
                <th>#</th>
                <th class="span1"><?=lang('contrato')?></th>
                <th class="span1"><?=lang('status_adesao')?></th>
          </thead>
          <tbody>
          </tbody>
        </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    jQuery.browser = {};
    (function () {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();

    $(document).ready(function() {
        $('#clientes').select2({
            ajax: {
                url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            }
        });

        var tableAdesaoInstalacao = $('#tableAdesaoInstalacao').DataTable( {
            dom: 'Bfrtip',
            paging: true,
            responsive: true,
            "columnDefs": [
                {
                    "className": "dt-center",
                    "orderable": false,
                    "targets": "_all"
                },
            ],
            orderable: false,
            order: [0, 'asc'],
            columns:[
                {data: 'indice'},
                {data: 'idContrato'},
                {data: 'status_adesao'}
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL'
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    extend: 'print',
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
            var data_form = $(this).serialize();
            $('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> <?=lang('gerando')?>');
            $.getJSON('<?=  site_url('relatorios/loadStatusAdesoesInstalacoes') ?>', data_form, function(retorno) {
                if (retorno.success) {
                    $('.alert_acao').css('display', 'none');

                    // Atualiza Tabela
                    tableAdesaoInstalacao.clear();
                    tableAdesaoInstalacao.rows.add(retorno.table);
                    tableAdesaoInstalacao.draw();

                } else {
                    // Limpa a Tabela e mostra mensagem
                    tableAdesaoInstalacao.clear();
                    tableAdesaoInstalacao.draw();
                    $('.alert_acao').css('display', 'block');
                    $("#mensagem").html('<div class="alert alert-danger"><p><b>'+retorno.msg+'</p></div>');
                }

                // Ativa e troca html button "GERAR"
                $('.gerar_rel').removeAttr('disabled').html("<?=lang('gerar')?>");
            });

            return false;
        });

    });

    //CARREGA O SELECT DE CONTRATOS
    $(document).on('change', '#clientes',function(e) {
        e.preventDefault();
        $('#contratos').attr('disabled', true);  //SEMPRE QUE TENTAR MUDAR DE CLIETE, O CAMPO DE CONTRATOS FICA SESABILITADO

        let id_cliente = $('#clientes option:selected').text().split(' - ')[0];
        var lista='<option value="todos" selected>'+lang.todos+'</option>';

        $.ajax({
            url: "<?=site_url('contratos/listaAjaxContratosCliente')?>",
            type: 'POST',
            data: {id_cliente: id_cliente },
            dataType: 'json',
            beforeSend: function () {
                $('.alert_acao').css('display', 'none');
                $("#mensagem").html('');
            },
            success: function(retorno){
                if(retorno.success){
                    var contratos = retorno.contratos;
                    $.each(contratos, function (i, d) {
                        lista += '<option value="'+d.id+'">'+d.id+'</option>';
                    });
                    //HABILITA O SELECT DE CONTRATOS
                    setTimeout(
                        function(){
                            $('#contratos').removeAttr('disabled');
                        },
                        1000
                    );
                }else {
                    lista = '<option value="" disabled selected><?=lang('selecione_contrato')?></option>';
                    $('#contratos').attr('disabled', true);
                    $('.alert_acao').css('display', 'block');
                    $("#mensagem").html('<div class="alert alert-danger"><p><b>'+retorno.msg+'</b></p></div>');
                }
            },
            complete: function(retorno){
                //LIMPA OS OPTIONS DO SELECT
                $("#contratos option[value]").remove();
                //PREENCHE COM OPTIONS NOVAS
                $('#contratos').append(lista);
            }
        });
    });


    //esconde a mensagem caso apertem o botao de fechar
    $(document).on('click', '.close', function() {
        $(".alert_acao").css('display','none');
    });



</script>
