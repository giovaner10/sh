<table id="tabelaFilasGrupos" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <th><?=lang("codigo")?></th>
            <th><?=lang("nome")?></th>
            <th><?=lang("acoes")?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>

    var tabelaFilasGrupos = $("#tabelaFilasGrupos").DataTable({
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('PaineisInfobip/parametrosFilasGrupos')?>"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'btn btn-lg btn-primary',
                text: '<i class="fa fa-plus m-r-10" aria-hidden="true"></i><?=lang("novo_grupo")?>',
                attr:  {
                    id: 'buttonNovaFilaGrupo'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovaFilaGrupo();
                }
            }
        ],
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
                render: function (data, type, row) // Açôes
                {
                    return `\
                        <a onclick="formularioEditarFilaGrupo(${data})" data-toggle="tooltip"
                            id="buttonEditarFilaGrupo_${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a onclick="modalExcluirFilaGrupo(${data})" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                            <i class="fa fa-remove"></i>
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

    function formularioNovaFilaGrupo()
    {
        // Carregando
        $('#buttonNovaFilaGrupo')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#divFilaGrupo").load(
            "<?=site_url('PaineisInfobip/formularioFilasGrupos')?>",
            function()
            {
                // Carregado
                $('#buttonNovaFilaGrupo')
                    .html('<i class="fa fa-plus m-r-10" aria-hidden="true"></i><?=lang('novo_grupo')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarFilaGrupo(filaGrupoId)
    {
        // Carregando
        $('#buttonEditarFilaGrupo_'+filaGrupoId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divFilaGrupo").load(
            "<?=site_url('PaineisInfobip/formularioFilasGrupos')?>/"+filaGrupoId,
            function()
            {
                // Carregado
                $('#buttonEditarFilaGrupo_'+filaGrupoId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirFilaGrupo(filaGrupoId)
    {
        $("#modalConfirmarFilaGrupoId").val(filaGrupoId);
        $('#modalFilaGrupoConfirmarExcluir').modal();
    }

    function excluirFilaGrupo()
    {
        let filaGrupoId = $("#modalConfirmarFilaGrupoId").val();

        // Carregando
        $('#buttonExcluirFilaGrupo')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta grupo
        $.ajax({
            url: '<?=site_url("PaineisInfobip/excluirFilaGrupo")?>/'+filaGrupoId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    // Fecha modal
                    $("#modalFilaGrupoConfirmarExcluir").modal('hide');
                    // Recarrega a tabela
                    tabelaFilasGrupos.ajax.reload();
                }
                else
                {
                    // Mensagem de retorno
                    toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                // Mensagem de retorno
                toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
            },
            complete: function ()
            {
                // Carregado
                $('#buttonExcluirFilaGrupo')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>