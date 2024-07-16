<script type="text/javascript" language="javascript" src="<?php echo base_url('media/datatable/dataTables.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<div class="container">
    <h3>Monitoramento - Contratos</h3>

    <table class="table table-striped" id="example">
        <thead>
            <tr>
                <th>Contrato</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Inicio Contrato</th>
                <th>Fim Contrato</th>
                <th>Vigência (Meses)</th>
                <th>Cronograma (Dias)</th>
                <th>Visualizar</th>
                <th>Remover</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    var groupCol = 1;

    $('#group-column').on('change',function(){
        if(this.value == "") {
            return;
        }
        groupCol = this.value;
        var table = $('#example').DataTable();
        table.order([ groupCol, "desc" ]);
        table.draw(groupCol);
    });

    var table = $('#example').DataTable( {
        serverSide: true,
        ajax: "<?= site_url('monitor/ajaxListMonitorContracts') ?>",
        aLengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        ordering: false,
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
            "sSearch": "Pesquisar:",
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
        "drawCallback": function ( settings ) {
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last = null;
          var groupadmin = []; 
          api.column(groupCol, {page:'current'} ).data().each( function ( raNum, i ) {
              if ( last !== raNum ) {
                  //raNum.replace("hidden","").replace("<span class=''>","<span class=''> ")
                  $(rows).eq( i ).before(
                      '<tr class="group table-secondary" id="'+i+'"><td colspan="11" style="background-color:#c0c0c0">'+raNum+'</td></tr>'
                  );
                  groupadmin.push(i);  
                  last = raNum;
              }
          });
          for( var k=0; k < groupadmin.length; k++){
                $("#"+groupadmin[k]).nextUntil("#"+groupadmin[k+1]).addClass(' group_'+groupadmin[k]); 
                    $("#"+groupadmin[k]).click(function() {
                        var gid = $(this).attr("id");
                        $(".group_"+gid).slideToggle(300);
                });
            } 
        },
        rowGroup: {
            dataSrc: 1,
            startRender: function (rows, group) {
                let collapsed = !!itemCollapsedGroups[group];
                rows.nodes().each(function (r) {
                    r.style.display = collapsed ? 'none' : '';
                });
                return $('<tr/>').append('<td colspan="8">' + group + ' (' + rows.count() + ')</td>').attr('data-name', group).toggleClass('collapsed', collapsed);
            }
        }
    });
</script>