<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>

<style type="text/css">
    #preloader {
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div id="preloader"><div class="loader"></div></div>

<?php
    if (isset($_GET['fun'])) {
        if ($_GET['fun'] == 's')
            $rel = $relatorio['NOT'];
        elseif ($_GET['fun'] == 'c')
            $rel = $relatorio['VINC'];
        elseif ($_GET['fun'] == 'u')
            $rel = $relatorio['CLIENTE'];
    } else {
        $aux = array_merge(array_merge($relatorio['NOT'], $relatorio['VINC']), array_merge($relatorio['CLIENTE'], $relatorio['BLOCK']));
        $rel = array_merge($aux, $relatorio['CAN']);
    }
?>

<h3>Detalhamento de Conta</h3>
<hr class="featurette-divider">
<div class="row-fluid">
    <div class="span6" style="padding: 5% 0% 0% 10%">
        <?php if ($block_button == TRUE && $block_button > 0): ?>
            <a class="btn btn-danger" disabled="disabled" style="text-align: center; margin-right: 10px;"><i class="fa fa-ban" style="font-size:36px"></i><br/>Solicitar Cancelamento</a>
        <?php else: ?>
            <a class="btn btn-danger sol_can" style="text-align: center; margin-right: 10px;"><i class="fa fa-ban" style="font-size:36px"></i><br/>Solicitar Cancelamento</a>
        <?php endif; ?>
        <a class="btn btn-info" href="<?= site_url('linhas/lista_solicitacoes') ?>"><i class="fa fa-reorder" style="font-size:36px"></i><br/>Listar Solicitações</a>
    </div>
    <div class="span6">
        <div id="piechart" style="width: 400px; height: 200px;"></div>
    </div>
</div>
<div class="container-fluid">
    <div>
        <span class="label label-info">Sem Vinculo: <?= count($relatorio['NOT']) ?></span>
        <span class="label label-primary">Com Vinculo (Sem Cliente): <?= count($relatorio['VINC']) ?></span>
        <span class="label label-success">Em Uso: <?= count($relatorio['CLIENTE']) ?></span>
        <span class="label label-default">Bloqueado: <?= count($relatorio['BLOCK']) ?></span>
        <span class="label label-danger">Cancelado: <?= count($relatorio['CAN']) ?></span>
        <span class="label label-warning">Total de Linhas: <?= count($relatorio['CLIENTE']) + count($relatorio['VINC']) + count($relatorio['NOT']) + count($relatorio['BLOCK']) + count($relatorio['CAN']) ?></span>
    </div>
    <div class="row-fluid well">
        <a class="btn btn-info" href="<?= site_url('linhas/gerar_relContas/'.$id_ref.'/s') ?>">Listar Sem Vinculo</a>
        <a class="btn btn-primary" href="<?= site_url('linhas/gerar_relContas/'.$id_ref.'/c') ?>">Listar Com Vinculo (Sem Cliente)</a>
        <a class="btn btn-success" href="<?= site_url('linhas/gerar_relContas/'.$id_ref.'/u') ?>">Listar Em Uso</a>
        <a class="btn btn-warning" href="<?= site_url('linhas/gerar_relContas/'.$id_ref) ?>">Listar Todas</a>
    </div>

    <div class="container">
        <table class="table table-striped" id="example">
            <thead>
                <tr>
                    <th>CCID</th>
                    <th>LINHA</th>
                    <th>DATA ATIVAÇÃO</th>
                    <th>SERVIÇO</th>
                    <th>PLANO</th>
                    <th>REFERENCIA</th>
                    <th>DATA IMPORTAÇÃO</th>
                    <th>CONTA</th>
                    <th>SERIAL</th>
                    <th>ULTIMA TRANSMISSÃO</th>
                    <th>CLIENTE</th>
                    <th>STATUS DA LINHA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rel as $r):?>
                <tr>
                    <td>"<?= $r->ccid ?>"</td>
                    <td><?= $r->ddd.$r->linha ?></td>
                    <td><?= date('d/m/Y', strtotime($r->data_ativacao)) ?></td>
                    <td><?= $r->servico ?></td>
                    <td><?= $r->plano ?></td>
                    <td><?= $r->referencia ?></td>
                    <td><?= date('d/m/Y H:i:s', strtotime($r->data_insert)) ?></td>
                    <td><?= $r->conta ?></td>
                    <td><?= $r->serial ?></td>
                    <td><?= $r->data_rastreador ? date('d/m/Y H:i:s', strtotime($r->data_rastreador)) : '' ?></td>
                    <td><?= $r->nome ?></td>
                    <td>
                        <?= strtoupper($r->status_linha) ?>
                        <?php
                        if ($r->data_block) {
                            if (date('m', strtotime($r->data_block)) >= substr($id_ref, 0, 2))
                                echo "<i title='Bloqueio Solicitado em: ".date('d/m/Y H:i:s', strtotime($r->data_block))."' class='fa fa-exclamation-triangle' style='font-size:30px;color:red'></i>";
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js" type="text/javascript"></script>
<script>
    $('.sol_can').click(function () {
        $('.sol_can').attr('disabled', true);
        $.post("<?= site_url('linhas/solicitaCancelamento').'/'.$id_ref ?>",
            function(){
                $('.sol_can').removeClass('sol_can');
                alert('Solicitação realizada com sucesso.');
            }
        ).fail( function () {
            $('.sol_can').removeAttr('disabled');
            alert('Não foi possível realizar a solicitação no momento, tente novamente mais tarde!');
        });
    });

    $(document).ready(function() {
        $('#example').DataTable({
            "language": {
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
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf'
            ],
            extend: 'excelHtml5'
        });
    });

    $(window).load(function() {
        $('#preloader').fadeOut(1500);//1500 é a duração do efeito (1.5 seg)
    });
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Em Uso',     <?= count($relatorio['CLIENTE']) ?>],
            ['Com Vinculo',      <?= count($relatorio['VINC']) ?>],
            ['Sem Vinculo',  <?= count($relatorio['NOT']) ?>],
            ['Bloqueado',  <?= count($relatorio['BLOCK']) ?>],
            ['Cancelado',  <?= count($relatorio['CAN']) ?>]
        ]);

        var options = {
            title: 'Percentual'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>
