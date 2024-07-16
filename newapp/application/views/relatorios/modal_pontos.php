<h3><?= $relatorio[0]->nome ?></h3>
<div>
    <table id="tabela_comissao" class="table table-striped table-hover">
        <thead>
        <th class="span3">Pontos</th>
        <th class="span2">Data da Compra</th>
        <th class="span2">Valor p/ Ponto</th>
        <th class="span2">Subotal Venda</th>
        <th class="span2">Total Comissão</th>
        </thead>
        <tbody>
        <?php if ($relatorio): $total = 0;?>
            <?php foreach ($relatorio as $r): $total += (($r->pontos * 1) / 100) * 10; ?>
                <tr>
                    <td><?= $r->pontos ?></td>
                    <td><?= date('d/m/Y H:i:s', strtotime($r->data)) ?></td>
                    <td>R$ 1,00</td>
                    <td><?= 'R$ '.number_format(($r->pontos * 1), 2, ',', '.') ?></td>
                    <td><?= 'R$ '.number_format((($r->pontos * 1) / 100) * 10, 2, ',', '.') ?></td>
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
            </tfoot>
        <?php endif; ?>
    </table>
</div>


<script>
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
        "ordering": false,
        "searching": false
    });
</script>