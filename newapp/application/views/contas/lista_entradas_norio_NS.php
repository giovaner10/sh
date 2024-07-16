
<div class="modal-content">
    <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Entradas do Mês</h3>
    </div>
    <form>
        <div class="modal-body" id="modal-body-view-entrada">
            <table class="table text-center">
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
                                <td><a href="<?=site_url('contas/remove_entrada_norio')?>" data-identrada="<?=$entrada->id_entrada?>" class="btn btn-mini btn-primary del-entrada"><i class="fa fa-remove"></a></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
        </div>
    </form>
</div>

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