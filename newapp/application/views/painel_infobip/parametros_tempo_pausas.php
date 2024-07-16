<div class="m-t-20">
    <table id="dataTempoPausas" class="table table-striped table-bordered">
        <thead>
            <tr class="tableheader">
                <th><?=lang("pausa")?></th>
                <th><?=lang("tempo")?></th>
                <th><?=lang("editar")?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>

    var dataTempoPausas = $("#dataTempoPausas").DataTable({
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        paging: false,
        searching: false,
        ajax: {
            url:  "<?=site_url('PaineisInfobip/parametrosTempoPausas')?>"
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
                        <a onclick="formularioEditarTempoPausa(${data})" data-toggle="tooltip"
                            id="buttonEditarTempoPausa${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
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

    function formularioEditarTempoPausa(pausaId)
    {
        // Carregando
        $('#buttonEditarTempoPausa'+pausaId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divTempoPausa").load(
            "<?=site_url('PaineisInfobip/formularioTempoPausa')?>/"+pausaId,
            function()
            {
                // Carregado
                $('#buttonEditarTempoPausa'+pausaId)
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