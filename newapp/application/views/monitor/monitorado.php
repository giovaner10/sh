<!-- CSS DATATABLES -->
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">

<!-- JS DATATABLES -->
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<!-- EXPORT BUTTONS -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>

<style type="text/css">
    .dt-buttons {
        display: none  !important;
    }
    table {
        width: 100% !important;
    }
    .none {
        display: none !important;
    }
    .label-danger-alert {
        background-color: red !important;
    }
    span.badge-alert {
        background-color: #ffffff !important;
        color: red !important;
    }
</style>

<div class="container-fluid">
    <table class="nowrap table-striped table-bordered" style="width:100%" id="example">
        <thead>
            <tr>
                <th>Monitorado</th>
                <th>Data GPS</th>
                <th>Data Comunicação</th>
                <th>Serial</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Velocidade</th>
                <th>Cinta</th>
                <th>Case</th>
                <th>Carregador</th>
                <th>Bateria</th>
                <th>GPS</th>
                <th>GPRS</th>
                <th>Altitude</th>
                <th>Endereço</th>
                <th>Versão</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    var last_tracker = '2020-01-01 00:00:00';
    var monitorados = [];
    var table;

    

    // setInterval( function () {
    //     table.ajax.reload( null, true ); // user paging is not reset on reload
    // }, 60000 );

    $(document).ready(function() {
        $.getJSON('getMonitoradosAtivos', function(data) {
            monitorados = data;
        }).always(call => {
            table = $('#example').DataTable({
                sScrollY: $(window).height() * 55 / 100,
                columns: [
                    { data: 'ID_OBJECT_TRACKER' },
                    { data: 'DATA' },
                    { data: 'DATASYS' },
                    { data: 'ID' },
                    { data: 'X' },
                    { data: 'Y' },
                    { data: 'VEL' },
                    { data: 'IN3' },
                    { data: 'IN8' },
                    { data: 'IN2' },
                    { data: 'VOLTAGE' },
                    { data: 'GPS' },
                    { data: 'GPRS' },
                    { data: 'IN6' },
                    { data: 'ENDERECO' },
                    { data: 'FWVER' },
                ],
                initComplete: function () {
                    $('#example_wrapper').prepend('<div class="pull-left" style="margin-top: 10px;"><span class="label label-danger label-danger-alert">Monitorados: <span class="badge badge-alert">'+table.rows().count()+'</span></span></div>');
                },
                drawCallback: function ( settings ) {
                    if (table) {
                        $('span.badge-alert').html(table.rows().count());
                    }
                },
                paging: false,
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
                },

                ajax: {
                    "url": "<?= site_url('monitor/load_monitorados') ?>/" + last_tracker,
                    "type": "GET",
                    "dataSrc": function (callback) {
                        $.each(callback, function(e, a) {
                            if (new Date(a.DATASYS) > new Date(last_tracker))
                                last_tracker = a.DATASYS;

                            let chave = $.inArray(a.ID_OBJECT_TRACKER.replace('DT:', ''), monitorados.map( el => el.id ));
                            a.ID_OBJECT_TRACKER = chave != -1 ? monitorados[chave].nome : a.ID_OBJECT_TRACKER;
                            
                        });
                            

                        setTimeout(function(){ table.ajax.reload(); }, 60000);
                        return callback;
                    }
                }
            });
        });
    });
</script>