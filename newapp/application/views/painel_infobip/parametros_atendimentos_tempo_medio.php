<div class="m-t-20">
    <table id="tabelaAtendimentosTempoMedio" class="table table-striped table-bordered">
        <thead>
            <tr class="tableheader">
                <th><?=lang("tipo_canal")?></th>
                <th><?=lang("tempo_medio")?></th>
                <th><?=lang("editar")?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>

    var tabelaAtendimentosTempoMedio = $("#tabelaAtendimentosTempoMedio").DataTable({
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        paging: false,
        searching: false,
        ajax: {
            url:  "<?=site_url('PaineisInfobip/parametrosAtendimentosTempoMedio')?>"
        },
        dom: 'Bfrtip',
        buttons: [],
        columnDefs: [
            {
                render: function (data, type, row)
                {
                    return data;
                },
                className: 'text-center',
                targets: 0
            },
            {
                render: function (data, type, row)
                {
                    return data;
                },
                className: 'text-center',
                targets: 1
            },
            {
                render: function (data, type, row) // Editar
                {
                    return `\
                        <a onclick="formularioAtendimentosTempoMedio(${data})" data-toggle="tooltip"
                            id="buttonEditarAtendimentosTempoMedio${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                    `;
                },
                className: 'text-center',
                targets: 2
            }
        ],
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioAtendimentosTempoMedio(id)
    {
        // Carregando
        $('#buttonEditarAtendimentosTempoMedio'+id)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divAtendimentosTempoMedio").load(
            "<?=site_url('PaineisInfobip/formularioAtendimentosTempoMedio')?>/"+id,
            function()
            {
                // Carregado
                $('#buttonEditarAtendimentosTempoMedio'+id)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

</script>

<style>
    .icone-aten-nao-atrib-status {
        border-radius: 50%;
        width: 15px;
        height: 15px;
        position: absolute;
    }
</style>