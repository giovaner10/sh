<style>
    .panel-heading {
        text-align: center;
    }

    .panel-body {
        height: 300px;
    }
</style>

<div class="panel panel-primary">
    <div class="panel-heading">SOLICITAÇÕES DE EQUIPAMENTOS</div>
    <div class="panel-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>End. Destino</th>
                    <th>Solicitante</th>
                    <th>Tipo</th>
                    <th>Suporte</th>
                    <th>Data Solicit.</th>
                    <th>Cod. Rastreio</th>
                    <th>Status</th>
                    <th>Administrar</th>
                </tr>
            </thead>
            <tbody>
            <!-- INICIO DO BODY DA TABELA -->
            <?php if(!empty($solicitacoes)) { ?>
                <?php foreach ($solicitacoes as $solic) { ?>
                <tr>
                    <td><?= $solic->id ?></td>
                    <td><?= $solic->cliente ?></td>
                    <td><?= $solic->end_destino ?></td>
                    <td><?= $solic->solicitante ?></td>
                    <td><?= $solic->tipo == 1? 'Instalação' : 'Manutenção'; ?></td>
                    <td><?= $solic->suporte_responsavel ?></td>
                    <td><?= $solic->data_solicitacao ?></td>
                    <td><?= $solic->cod_rastreio ?></td>
                    <td>
                        <?php switch ($solic->status) {
                            case 0:
                                echo "Cadastrada";
                                break;
                            case 1:
                                echo "Pronta p/ Despacho";
                                break;
                            case 2:
                                echo "Despachado";
                                break;
                            case 3:
                                echo "Entregue";
                                break;
                        } ?>
                    </td>
                    <td>
                        <button class="btn btn-info">x</button> |
                        <button class="btn btn-primary">x</button> |
                        <button class="btn btn-success">x</button> |
                        <button class="btn btn-danger">x</button>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                Nenhum registro encontrado.
            <?php } ?>
            <!-- FIM DO BODY DA TABELA -->
            </tbody>
        </table>
    </div>
</div>