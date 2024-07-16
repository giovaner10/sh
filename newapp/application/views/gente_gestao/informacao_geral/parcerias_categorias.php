<!-- ADM Parcerias Categorias -->
<table id="dt_parcerias_categorias" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <th><?=lang("ordem")?></th>
            <th><?=lang("nome")?></th>
            <th style="min-width: 70px;"><?=lang("acoes")?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>

    identificadorModalParceriaCategoria = 'ParceriaCategoria';

    var dt_parcerias_categorias = $("#dt_parcerias_categorias").DataTable({
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/listarParceriasCategorias')?>"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("nova_categoria")?>',
                attr:  {
                    id: 'buttonNovaParceriaCategoria'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovaParceriaCategoria();
                }
            }
        ],
        columnDefs: [
            {
                render: function (data, type, row) // Ações
                {
                    return `\
                        <a onclick="formularioEditarParceriaCategoria(${data})" data-toggle="tooltip"
                            id="buttonEditarParceriaCategoria_${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a onclick="modalExcluirParceriaCategoria(${data})" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
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

    function formularioNovaParceriaCategoria()
    {
        // Carregando
        $('#buttonNovaParceriaCategoria')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalParceriaCategoria").load(
            "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/formularioParceriaCategoria')?>",
            function()
            {
                // Carregado
                $('#buttonNovaParceriaCategoria')
                    .html('<?=lang('nova_categoria')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarParceriaCategoria(parceriaCategoriaId)
    {
        // Carregando
        $('#buttonEditarParceriaCategoria_'+parceriaCategoriaId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalParceriaCategoria").load(
            "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/formularioParceriaCategoria')?>/"+parceriaCategoriaId,
            function()
            {
                // Carregado
                $('#buttonEditarParceriaCategoria_'+parceriaCategoriaId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirParceriaCategoria(parceriaCategoriaId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalParceriaCategoria,
            parceriaCategoriaId,
            '<?=lang("confirmacao_exclusao_parceria")?>' // Texto modal
        );
    }

    function excluirParceriaCategoria()
    {
        let parceriaCategoriaId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalParceriaCategoria);
        
        // Carregando
        $('#btnExcluirParceriaCategoria')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta Parceria
        $.ajax({
            url: '<?=site_url("GentesGestoes/GentesGestoesInfoGerais/excluirParceriaCategoria")?>/'+parceriaCategoriaId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalParceriaCategoria);

                    // Recarrega a tabela
                    dt_parcerias_categorias.ajax.reload();

                    // Recarrega a listagem da tela principal
                    atualizarListagemParcerias();
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
                $('#btnExcluirParceriaCategoria')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>