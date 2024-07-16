<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.dataTables.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/dataTables.material.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.material.min.css');?>">
<button class="btn btn-primary" id="add_row" style="margin-bottom: 10px;"><i class="fa fa-plus-circle"></i> Adiconar</button>
<br>
<table id="example" class="table table-bordered display" style="width:100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Descrição</th>
            <th>Cod. Permissão</th>
            <th>Categoria</th>
            <th>Status</th>
        </tr>
    </thead>
    <tfoot>
    </tfoot>
</table>
<script type="text/javascript">
    $(document).ready( function () {
        $('#add_row').on('click', function() {
            alert('a');
            table.row.add( [
                table.rows().count() + 1,
                '<input class="form-control" name="descricao" />',
                '<input class="form-control" name="cod_permissao" />',
                '<select class="form-control" name="categoria">'+
                    '<option value="RELATORIO">Relatórios</option>'+
                    '<option value="CADASTRO">Cadastros</option>'+
                    '<option value="CONFIGURACAO">Configurações</option>'+
                    '<option value="ATENDIMENTO">Atendimento</option>'+
                '</select>',
                '<button class="btn btn-success btn-mini add_row">Salvar</button>'
            ]).draw();
        });

        $(document).on( 'click', '.add_row', function () {
            // Elemento do click
            var element = $(this);

            // Desabilita button e inicia spinner
            element.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            let dados = [];
            // Grava dados dos inputs
            element.closest('tr').find('td').children().each(function (i, a) {
                if ($(a).val())
                    dados[ $(a).attr('name') ] = $(a).val();
            });

            $.ajax({
                url: "<?= site_url('cadastros/add_permissao') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    descricao: dados['descricao'],
                    cod_permissao: dados['cod_permissao'],
                    categoria: dados['categoria']
                },
                success: callback => {
                    if (callback.status == true) {
                        // Busco o indice da linha que o objeto pertence
                        let indice_linha = element.closest('tr').find('td').eq(0).html();
                        
                        // Remove Linha (Inputs)
                        table
                            .row( element.parents('tr') )
                            .remove()
                            .draw(false, null);
                        
                        // Adiciona Linha (Dados fixos)
                        table.row.add([
                            indice_linha,
                            dados['descricao'],
                            dados['cod_permissao'],
                            dados['categoria'],
                            '<span class="label label-success">Ativo</span>'
                        ]).draw(false, null);
                    } else {
                        element.attr('disabled', false).html('Salvar');
                        alert('Verifique os campos e tente novamente.');
                    }
                }
            })
            
        } );

        var table = $('#example').DataTable( {
            ajax: "<?= site_url('cadastros/ajax_permissoes') ?>",
            order: [0, 'desc'],
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
        } );
    } );
</script>