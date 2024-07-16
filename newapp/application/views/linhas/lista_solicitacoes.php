<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<style>
    .modal-body {
        max-height: 400px;
        overflow: auto;
    }
</style>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3>Detalhes do Cancelamento</h3>
            </div>
            <div id="print" class="conteudo modal-body">
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-info" onclick="print();" value="Imprimir">
            </div>
        </div>

    </div>
</div>

<h3>SOLICITAÇÕES DE CANCELAMENTOS</h3>
<hr class="featurette-divider">
<br />
<div class="container">
    <table class="table table-striped" id="example">
        <thead>
            <tr>
                <th>OPERADORA</th>
                <th>QTD DE LINHAS</th>
                <th>DATA SOLICITAÇÃO</th>
                <th>USUARIO</th>
                <th>REFERÊNCIA</th>
                <th>VISUALIZAR</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solicitacoes as $s):?>
            <tr>
                <td><?= $s->operadora ?></td>
                <td><?= $s->quantidade ?></td>
                <td><?= date('d/m/Y', strtotime($s->data_insert)) ?></td>
                <td><?= $s->email_user ?></td>
                <td><?= $s->referencia ?></td>
                <td><a style="cursor: pointer;" onclick="listachips(<?= $s->id ?>)">Ver Detalhes <i id="spin<?= $s->id ?>" class="fa fa-gear fa-spin" style="display: none; color: #0394ec; font-size:24px"></i></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
    function print(){
        var conteudo = document.getElementById('print').innerHTML;
        tela_impressao = window.open('about:blank');
        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    }

    function listachips(id) {
        $('#spin'+id).removeAttr('style');
        $('#spin'+id).attr('style', 'display: block; color: #0394ec; font-size:24px');
        $.ajax({
            url: "<?= site_url('linhas/getSolicitacao') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {id: id},
            success: function(data) {
                if (data.status == 'OK') {
                    var chips = '',
                        referencia = '',
                        operadora = '',
                        user = '',
                        data_insert = '';
                    $.each(data.detalhes, function (index, value) {
                        if (index == 0) {
                            referencia = value.referencia;
                            operadora = value.operadora;
                            user = value.email_user;
                            data_insert = value.data_insert;
                        }

                        chips += '<div class="form-group span5">' +
                            '<input type="text" value="'+value.ccid+'" class="form-control span12" disabled>' +
                            '</div>' +
                            '<div class="form-group span5">' +
                            '<input type="text" value="'+value.linha+'" class="form-control span12" disabled>' +
                            '</div>';

                    });

                    $('.modal-body').html(
                        '<div class="row-fluid">' +
                        '                    <div class="form-group span4">' +
                        '                        <label>Referência:</label>' +
                        '                        <input type="text" value="'+referencia+'" class="form-control span12" disabled>' +
                        '                    </div>' +
                        '' +
                        '                    <div class="form-group span4">' +
                        '                        <label>Operadora:</label>' +
                        '                        <input type="text" value="'+operadora+'" class="form-control span12" disabled>' +
                        '                    </div>' +
                        '' +
                        '                    <div class="form-group span4">' +
                        '                        <label>Data da Solicitação:</label>' +
                        '                        <input type="text" value="'+data_insert+'" class="form-control span12" disabled>' +
                        '                    </div>' +
                        '                </div>' +
                        '                <div class="row-fluid">' +
                        '                    <div class="form-group span12">' +
                        '                        <label>Usuário:</label>' +
                        '                        <input type="text" value="'+user+'" class="form-control span8" disabled>' +
                        '                    </div>' +
                        '                </div>' +
                        '                <div class="row-fluid" style="text-align: center;">' +
                        '                    <h3>Chips</h3>' +
                        chips +
                        '                </div>'
                    );
                    $('#myModal').modal('show');
                    $('#spin'+id).removeAttr('style');
                    $('#spin'+id).attr('style', 'display: none; color: #0394ec; font-size:24px');
                } else {
                    alert('Não foi identificado nenhum detalhe para o cancelamento selecionado.');
                }
            }
        });
    }

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
</script>