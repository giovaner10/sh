<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

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

<style>
    .dt-buttons {
        display: none !important;
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
        <div class="col-md-12" style="display: contents;">
            <div class="well well-small"style=" margin-top: 60px;">
                <form id="formGerarResult" action="<?=site_url('relatorios/calcular_rescisao_contratos_privados')?>" method="get" accept-charset="utf-8" class="form-inline">
                <div class="form-group mr-2">
                    <span class="input-group-addon" style="display: contents;">
                        <i class="fa fa-paper-plane" style="font-size: 22px;"></i>
                    </span>
                    <input type="number" style="margin-left: 10px; height: 35px;" name="retirada" required placeholder="Retirada" autocomplete="true" id="retirada" value="<?php if($this->input->get('retirada'))echo $this->input->get('retirada'); ?>" />
                </div>

                    <span>
                        <button type="submit" class="btn btn-primary gerar_rel" style="display: flex; position: relative; float: left; margin-left: 230px; margin-top: -34px;">
                            Gerar
                        </button>
                    </span>
                </form>
            </div>
        </div>
    </div>

    <table id="table" class="datatable display responsive table-bordered table table-hover">
        <thead>
            <th>Contrato</th>
            <th>Quantidade</th>
            <th>Valor P/ Veículo</th>
            <th>Total Mensal</th>
            <th>Vigência</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Mês à vencer</th>
            <th>Total à Vencer</th>
            <th>30%</th>
            <th>Retirada</th>
            <th>SubTotal</th>
            <th>Total Geral</th>
            <th>status</th>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

<script type="text/javascript">

$(document).ready(function() {

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
                }
            },
            {
                extend: 'pdfHtml5',
                messageTop: function () {
                    return 'EMPRESA: SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA  |  CNPJ: 09.338.999/0001-58  |  E-MAIL: www.showtecnologia.com  |  FONE: 4020-2472';
                },
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csvHtml5',
                messageTop: function () {
                    return 'EMPRESA: SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA  |  CNPJ: 09.338.999/0001-58  |  E-MAIL: www.showtecnologia.com  |  FONE: 4020-2472';
                },
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                messageTop: function () {
                    return 'EMPRESA: SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA  |  CNPJ: 09.338.999/0001-58  |  E-MAIL: www.showtecnologia.com  |  FONE: 4020-2472';
                },
                exportOptions: {
                    columns: ':visible'
                }

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
        $.getJSON('<?=  site_url("relatorios/calcular_rescisao_contratos_privados/".$id_contrato) ?>', data_get, function(data) {
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
