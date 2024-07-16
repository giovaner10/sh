<?php header('Access-Control-Allow-Origin: *'); ?>
<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<?php if ($this->session->flashdata('sucesso')) { ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('sucesso'); ?>
    </div>
<?php } elseif ($this->session->flashdata('erro')) { ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('erro'); ?>
    </div>
<?php } ?>
<a href="<?= site_url('faturas') ?>" class="btn btn-primary"><i class="fa fa-mail-reply"></i> Voltar</a>
<h3>Listagem de RPS Geradas</h3>
<div class="well container-fluid span4">
    <form action="<?= site_url('nfes/leitura') ?>" method="POST" enctype="multipart/form-data">
        <label>Importar Retorno:</label>
        <input type="file" name="arq" />
        <button type="submit" class="btn btn-success">Processar Arquivo</button>
    </form>
    <a href="<?= base_url('media/demo.csv') ?>" download="gabarito_nfe.csv">Download Gabarito</a>: <p>INFORMAÇÃO! O arquivo deve ser salvo em formato CSV.</p>
</div>


<div class="container">
    <table id="example" class="table table-hover">
        <thead>
            <th class="span1">Id</th>
            <th class="span4">Arquivo</th>
            <th class="span2">Data</th>
            <th class="span4">Usuário</th>
            <th class="span2">Empresa</th>
            <th class="span1">Download</th>
        </thead>
        <tbody>
        <?php if ($rps): ?>
            <?php foreach ($rps as $rp): ?>
            <tr>
                <td><?= $rp->id ?></td>
                <td><?= $rp->nome_arquivo ?></td>
                <td><?= date('d/m/Y H:i:s', strtotime($rp->data)) ?></td>
                <td><?= $rp->user ?></td>
                <td>
                    <?php if ($rp->empresa == '1'): ?>
                    Show Tecnologia
                    <?php else: ?>
                    Norio Momoi
                    <?php endif; ?>
                </td>
                <td style="text-align: center;"><?= "<a href='".base_url('media/nfe/geradas').'/'.$rp->nome_arquivo."' download='".$rp->nome_arquivo."'><i class='fa fa-download' style='font-size:24px'></i></a>" ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
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
            }
        });
    } );
</script>