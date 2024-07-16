
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/1.7.1/jquery.js" ></script>
<!-- VERSÃO QUE FUNCIONA AS PERMISSÕES CORRETAMENTE -->

<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/select2.css">

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

<link href="<?=base_url()?>assets/plugins/datepicker/css/datepicker.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url()?>assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>

<style>
    .dt-buttons {
        display: none !important;
    }
    .select2 {
        margin-bottom: .8% !important;
    }
    th.dt-center, td.dt-center {
        text-align: center;
    }
</style>

<h3><?php echo $titulo; ?></h3>
<hr>

<div>
    <div>
        <button onclick="$('.buttons-excel').trigger('click');" class="btn btn-primary"><i class='fa fa-file-excel-o'></i> Excel</button>
        <button onclick="$('.buttons-pdf').trigger('click');" class="btn btn-primary"><i class='fa fa-file-pdf-o'></i> PDF</button>
        <button onclick="$('.buttons-csv').trigger('click');" class="btn btn-primary"><i class='fa fa-file-code-o'></i> CSV</button>
        <button onclick="$('.buttons-print').trigger('click');" class="btn btn-primary"><i class="fa fa-print"></i> IMPRIMIR</button>
    </div>

    <div>
        <div class="well well-small" style="height: 30px;">
            <form id="formGerarResult" action="<?=site_url('relatorios/relatorio_tipo_servico')?>" method="get" accept-charset="utf-8">
                <span class="input-group-addon" >
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                </span>
                <input type="text" name="di" required placeholder="Data Início" autocomplete="off" id="dp1" value="<?php if($this->input->get('di'))echo $this->input->get('di'); ?>" />

                <span class="input-group-addon" style="margin-left: 10px;">
                    <i class="fa fa-calendar" style="font-size: 22px;"></i>
                </span>
                <input type="text" name="df" required placeholder="Data Fim" autocomplete="off" id="dp2" value="<?php if($this->input->get('df'))echo $this->input->get('df'); ?>" />

                <span class="input-group-addon" style="margin-left: 10px;">
                    <i class="fa fa-institution" style="font-size: 22px;"></i>
                </span>
                <select name="id_cliente" id="clientes" required>
                    <option value="">Selecione o cliente</option>
                </select>

                <span class="input-group-addon" style="float: right;">
                    <button type="submit" class="btn btn-success gerar_rel">
                        Gerar
                    </button>
                </span>
            </form>
        </div>
    </div>
    <div class="col-md-12">
        <p style="font-size:15px;">
            <strong>Mensalidades: </strong>R$
                <span id="somaMensalidade">0,00</span>
            <strong>Instalações: </strong>R$
                <span id="somaInstalacao">0,00</span>
            <strong>Manutenções: </strong>R$
                <span id="somaManutencao">0,00</span>
            <strong>Desativações: </strong>R$
            <span id="somaDesativacao">0,00</span>
        </p>
    </div>

    <table id="tblExport" class="datatable display responsive table-bordered table table-hover">
        <thead>
            <tr>
                <th>Cód. Fatura</th>
        		<th>Cliente</th>
                <th>Tipo de Serviço</th>
                <th>Valor</th>
                <th>Valor Pago</th>
                <th>Venc. Fatura</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#clientes').select2({
        ajax: {
            url: '<?= site_url('relatorios/listAjaxSelectClient') ?>',
            dataType: 'json'
        }
    });

    var table = $('#tblExport').DataTable( {
        dom: 'Bfrtip',
        responsive: true,
        paging: false,
		bLengthChange: false,
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
                    return 'Mensalidades: R$'+$('#somaMensalidade').text()+' | Instalação: R$'+$('#somaInstalacao').text()+' | Manutenção: R$'+$('#somaManutencao').text()+' | Desativação: R$'+$('#somaDesativacao').text()
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
                    return 'Mensalidades: R$'+$('#somaMensalidade').text()+' | Instalação: R$'+$('#somaInstalacao').text()+' | Manutenção: R$'+$('#somaManutencao').text()+' | Desativação: R$'+$('#somaDesativacao').text()
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
                    return 'Mensalidades: R$'+$('#somaMensalidade').text()+' | Instalação: R$'+$('#somaInstalacao').text()+' | Manutenção: R$'+$('#somaManutencao').text()+' | Desativação: R$'+$('#somaDesativacao').text()
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
                    return 'Mensalidades: R$'+$('#somaMensalidade').text()+' | Instalação: R$'+$('#somaInstalacao').text()+' | Manutenção: R$'+$('#somaManutencao').text()+' | Desativação: R$'+$('#somaDesativacao').text()
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
        // $('.gerar_rel').attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');
        $('.gerar_rel').html('<i class="fa fa-spinner fa-spin"></i> Gerando...');
        $.getJSON('<?=  site_url('relatorios/calcula_relatorio_tipo_servico') ?>', data_get, function(data) {
            if (data.status == 'OK') {
                // Atualiza Tabela
                table.clear();
                table.rows.add(data.table);
                table.draw();

                //Atualiza total de veiculos
                $('#somaMensalidade').text(data.somatorioMensalidade);
                $('#somaInstalacao').text(data.somatorioInstalacao);
                $('#somaManutencao').text(data.$somatorioManutencao);
                $('#somaDesativacao').text(data.somatorioDesativacao);
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

$('#dp1').datepicker({
    format: 'dd/mm/yyyy'
});

$('#dp2').datepicker({
    format: 'dd/mm/yyyy'
});

</script>
<script src="https://dits.cloud/js/select2.js"></script>
