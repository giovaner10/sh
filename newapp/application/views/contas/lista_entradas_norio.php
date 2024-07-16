<table class="table">
    <thead>
    <th>Data</th>
    <th>Valor</th>
    <th>Descrição</th>
    <th>Excluir</th>
    </thead>
    <tbody>
    <?php if (count($entradas)):?>
        <?php foreach ($entradas as $entrada): ?>
            <tr>
                <td><?=data_for_humans($entrada->data)?></td>
                <td><?=number_format($entrada->valor,2, ',', '.')?></td>
                <td><?=$entrada->descricao?></td>
                <td><a href="<?=site_url('contas/remove_entrada_norio')?>" data-identrada="<?=$entrada->id_entrada?>" class="btn btn-mini btn-danger del-entrada"><i class="fa fa-remove"></a></td>
            </tr>
        <?php endforeach ?>
    <?php endif; ?>
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        $(".del-entrada").click(function(ev){
            ev.preventDefault();
            var url = $(this).attr("href");
            var id = $(this).attr("data-identrada");
            var tr = $(this).parents("tr");
            $.post(url, {id_entrada: id}, function(cb){
                if (cb.success){
                    $(tr).remove();
                }
            }, 'json');

        });
    })
</script>