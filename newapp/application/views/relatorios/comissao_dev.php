<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js" ></script>

<h3>Relatório de Comissionamento - ShowRoutes</h3>
<hr>

<!-- MODAL -->
<div id="myModal" class="modal fade" style="width: 80% !important; margin-left: -40% !important;" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detalhamento de pontos</h4>
            </div>
            <div class="modal-body">
                <div id="resultado">

                </div>
            </div>
        </div>

    </div>
</div>

<!----------->

<div class="well">
    <form method="post" accept-charset="utf-8">
        <span class="input-group-addon" >
            <i class="fa fa-calendar" style="font-size: 22px;"></i>
            Data Inicial
        </span>
        <input type="date" name="data_ini" value="<?= $this->input->post('data_ini') ? $this->input->post('data_ini') : date('Y-m-d') ?>" required />
        
        <span class="input-group-addon" style="margin-left: 5px;">
            <i class="fa fa-calendar" style="font-size: 22px;"></i>
            Data Final
        </span>
        <input type="date" name="data_fim" value="<?= $this->input->post('data_ini') ? $this->input->post('data_ini') : date('Y-m-d') ?>" required />

        <span class="input-group-addon" style="float: right;">
            <button id="gerar_relatorio" class="btn">
                <i class="icon-list-alt"></i> Gerar
            </button>
        </span>
    </form>
</div>

<?php if ($this->input->post()): ?>
<div class="tabela table-responsive" style="">
    <table id="tabela_comissao" class="table table-striped table-hover">
        <thead>
            <th class="span3">Cliente</th>
            <th class="span2">Pontos</th>
            <th class="span2">Valor p/ Ponto</th>
            <th class="span2">Subtotal</th>
            <th class="span2">Total Comissão</th>
            <th class="span2">Detalhar</th>
        </thead>
        <tbody>
        <?php if ($relatorio): $total = 0;?>
            <?php foreach ($relatorio as $r): $total += (($r->soma * 1) / 100) * 10; ?>
            <tr>
                <td><?= $r->nome ?></td>
                <td><?= $r->soma ?></td>
                <td>R$ 1,00</td>
                <td><?= 'R$ '.number_format(($r->soma * 1), 2, ',', '.') ?></td>
                <td><?= 'R$ '.number_format((($r->soma * 1) / 100) * 10, 2, ',', '.') ?></td>
                <td><a class="btn btn-primary" onclick='detalhar(<?= $r->id_cliente.', "'.$this->input->post('data_ini').'", "'.$this->input->post('data_fim').'"' ?>)'><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
        <?php if ($total): ?>
        <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th>Total:</th>
            <th><?= 'R$ '.number_format($total, 2, ',', '.') ?></th>
            <th></th>
        </tfoot>
        <?php endif; ?>
    </table>
</div>


<script>
    function detalhar(id_cliente, data_ini, data_fim) {
        $.post("<?= site_url('relatorios/detalha_points_ajax') ?>", {
            id_cliente : id_cliente, inicio : data_ini, fim : data_fim
        }, function(retorno){
            $("#resultado").html(retorno);
            $('#myModal').modal('show');
        });
    }

    $('#tabela_comissao').DataTable({
        "language": {
            "decimal":        "",
            "emptyTable":     "Nenhum registro encontrado",
            "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "0 Registros",
            "infoFiltered":   "(filtered from _MAX_ total entries)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Qntd: _MENU_",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar:",
            "zeroRecords":    "Nenhum registro encontrado",
            "paginate": {
                "first":      "Anterior",
                "last":       "Avançar",
                "next":       "Avançar",
                "previous":   "Início"
            }
        },
        "paging": false,
        "ordering": false
    });
</script>
<?php endif; ?>