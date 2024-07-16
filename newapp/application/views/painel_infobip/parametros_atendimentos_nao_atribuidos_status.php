<div class="m-t-20">
    <table id="dataAtendimentosNaoAtribuidosStatus" class="table table-striped table-bordered">
        <thead>
            <tr class="tableheader">
                <th><?=lang("status")?></th>
                <th><?=lang("tempo")?></th>
                <th><?=lang("editar")?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    
    var dataAtendimentosNaoAtribuidosStatus = $("#dataAtendimentosNaoAtribuidosStatus").DataTable({
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        paging: false,
        searching: false,
        ordering:  false,
        ajax: {
            url:  "<?=site_url('PaineisInfobip/parametrosAtendimentosNaoAtruibidosStatus')?>"
        },
        dom: 'Bfrtip',
        buttons: [],
        columnDefs: [
            {
                render: function (data, type, row) // Ícone status
                {
                    return `\
                        <span class="icone-aten-nao-atrib-status background-color-${data}"></span>
                    `;
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
                        <a onclick="formularioEditarAtendimentoNaoAtribuidoStatus(${data})" data-toggle="tooltip"
                            id="buttonEditarAtendimentoNaoAtribuidoStatus_${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
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

    function formularioEditarAtendimentoNaoAtribuidoStatus(statusId)
    {
        // Carregando
        $('#buttonEditarAtendimentoNaoAtribuidoStatus_'+statusId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divAtendimentoNaoAtribuidoStatus").load(
            "<?=site_url('PaineisInfobip/formularioAtendimentoNaoAtribuidoStatus')?>/"+statusId,
            function()
            {
                // Carregado
                $('#buttonEditarAtendimentoNaoAtribuidoStatus_'+statusId)
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