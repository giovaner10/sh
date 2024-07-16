<div id = "veiculos_grupo_content">
    <span style="font-size: 15px;"> Lista de veículos pertencentes ao grupo</span>
    <table id = "veiculos_grupo_table">
        <thead>
            <tr>
                <th> Placa </th>
                <th> Status do espelhamento </th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        var table = $('#veiculos_grupo_table').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            data: <?= json_encode($veiculos) ?>,
            language: langDatatable,
            "columns": [
                {"data": "placa"},
                {"data": "espelhamento", "render": function (data, type, row) {
                        if (data == 0) {
                            return "Contrato";
                        }
                        else if (data == 1) {
                            return "Veículo espelhado de uma central";
                        }
                        else if (data == 2) {
                            return "Veículo espelhado pelo gestor";
                        }
                        else {
                            return "Espelhamento rejeitado"
                        }
                    }
                },
                
            ],
        });
    });
    
</script>
