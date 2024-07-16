<div id = "grupos_usuario_content">
    <span style="font-size: 15px;"> Lista de grupos em que o usuário está vinculado </span>
    <table id = "grupos_usuario_table"  style="margin-bottom: 20px;" >
        <thead>
            <tr>
                <th> Nome do grupo </th>
                <th> Status </th>
                <th> Mais informações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        var table = $('#grupos_usuario_table').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            data: <?= json_encode($grupos) ?>,
            language: langDatatable,
            "columns": [
                {"data": "nome"},
                {"data": "status", "render": function (data) {
                        if (data == 1) {
                            return '<span class="label label-success">Ativo</span>';
                        } else {
                            return '<span class="label label-danger">Inativo</span>';
                        }
                    }
                },
                {"data": "id_group", "render": function (data) {
                        return '<a data-url="<?php echo site_url('usuarios_gestor/getVeiculosGrupo')?>/' + data + '" data-toggle="modal" data-target="#view_veiculos_grupo"  onclick="render(this)" data-modal="#body_veiculosGrupo" class="btn btn-primary btn-mini" title="Lista de veículos do grupo" > <img src="<?php echo base_url('assets/css/icon/src/caminhao.svg')?>"  style="width: 18px;height: 18px;"> </a>'
                    }
                }
            ],
        });
    });
    // função para previnir que o modal anterior seja fechado ao clicar no botão fechar
    $('#btnFecharMVeicGrupos').click(function () {
        $('#view_veiculos_grupo').modal('hide');
    });
    
</script>