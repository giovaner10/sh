<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<!-- bibliotecas usadas apenas nessa pagina -->
<script type="text/javascript" language="javascript"
        src="<?php echo base_url('media/datatable/dataTables.min.js'); ?>">
</script>

<script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js">
</script>

<script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
</script>

<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js">
</script>
<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js">
</script>
<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js">
</script>
<script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
</script>

<!-- datapicket -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<style>
    .dt-buttons {
        display: none !important;
    }
    .select2 {
        margin-bottom: .8% !important;
    }
    .close{
        margin-right: 10px!important;
        margin-top: 10px!important;
    }
    tr.group,
    tr.group:hover {
        background-color: #ddd !important;
    }
    th.dt-center, td.dt-center {
        text-align: center;
    }
</style>

<div class="container">
    <div class="row">
        <h3><?php echo $titulo; ?></h3>
        <div class="col-md-12">
            <button onclick="$('.buttons-excel').trigger('click');" class="btn btn-primary"><i class='fa fa-file-excel-o'></i> Excel</button>
            <button onclick="$('.buttons-pdf').trigger('click');" class="btn btn-primary"><i class='fa fa-file-pdf-o'></i> PDF</button>
            <button onclick="$('.buttons-csv').trigger('click');" class="btn btn-primary"><i class='fa fa-file-code-o'></i> CSV</button>
            <button onclick="$('.buttons-print').trigger('click');" class="btn btn-primary"><i class="fa fa-print"></i> IMPRIMIR</button>
        </div>
        <div class="col-md-12">
        <div class="col-md-12" style="display: contents;">
            <div class="well well-small" style=" margin-top: 7px;">
                <form id="formGerarResult" action="<?=site_url('relatorios/listar_rescisao_contratos_privados')?>" method="get" accept-charset="utf-8" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="clientes" class="mr-2"><i class="fa fa-address-book-o" style="font-size: 25px;"></i></label>
                        <select name="id_cliente" id="clientes" class="form-control" style="width: 200px;" required>
                            <option value="">Selecione o cliente</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="float: right; position: absolute; margin-left: -856px; margin-top: -1px;">Gerar</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <table id="table" class="datatable display responsive table-bordered table table-hover">
            <thead id="novaTh" style="text-align:center;" >
                <th>Contrato</th>
                <th>Quantidade</th>
                <th>Valor P/ Veículo</th>
                <th>Total Mensal</th>
                <th>Vigência</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Mês à vencer</th>
                <th>Total à Vencer</th>
                <th>status</th>
                <th></th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function() {
    $('#clientes').select2({
        ajax: {
            url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
            dataType: 'json',
            dataSrc: function(data){
                return data;
            }

        }
    });

    var table = $('#table').DataTable( {
        dom: 'Bfrtip',
        responsive: true,
        paging: false,
        "columnDefs": [
            {
                "className": "dt-center",
                "targets": "_all"
            }
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                messageTop: function () {
                    return 'EMPRESA: SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA  |  CNPJ: 09.338.999/0001-58  |  E-MAIL: www.showtecnologia.com  |  FONE: 4020-2472';
                },
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            {
                extend: 'pdfHtml5',
                messageTop: function () {
                    return 'EMPRESA: SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA  |  CNPJ: 09.338.999/0001-58  |  E-MAIL: www.showtecnologia.com  |  FONE: 4020-2472';
                },
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            {
                extend: 'csvHtml5',
                messageTop: function () {
                    return 'EMPRESA: SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA  |  CNPJ: 09.338.999/0001-58  |  E-MAIL: www.showtecnologia.com  |  FONE: 4020-2472';
                },
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            {
                extend: 'print',
                messageTop: function () {
                    return 'EMPRESA: SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA  |  CNPJ: 09.338.999/0001-58  |  E-MAIL: www.showtecnologia.com  |  FONE: 4020-2472';
                },
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL'

            }
        ],
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });

    $('form#formGerarResult').submit(function () {
        var data_get = $(this).serialize();
        $('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');
        $.getJSON('<?=  site_url("relatorios/listar_rescisao_contratos_privados") ?>', data_get, function(data) {
            if (data.status == 'OK') {
                // Atualiza Tabela
                table.clear();
                table.rows.add(data.tabela);
                table.draw();

            } else {
                table.clear();
                table.draw();
            }

            // Ativa e troca html button "GERAR"
            $('.gerar_rel').removeAttr('disabled').html('Gerar');
        });

        return false;
    });

});

</script>
<script src="https://dits.cloud/js/select2.js"></script>
<!-- <script src="<?=base_url()?>assets/js/select2.js"/> -->  <!--impossibilitando abertura do menu global -->
