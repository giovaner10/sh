<table id="dt_comunicados" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <th><?=lang("codigo")?></th>
            <th><?=lang("titulo_comunicado")?></th>
            <th style="min-width: 70px;"><?=lang("acoes")?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="modalComunicado"></div>

<script type="text/javascript">

    let dtComunicadosButtons = [];
    let dtComunicadosColumnDefs = [];
    let comunicadosEdicaoExclusaoHtml = '';
    let identificadorModalComunicado = 'Comunicado';

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_comunicado')) :?>

        // Novo Comunicado
        dtComunicadosButtons.push(
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("novo_comunicado")?>',
                attr:  {
                    id: 'buttonNovoComunicado'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovoComunicado();
                }
            }
        );

        // Edição e Exclusão
        comunicadosEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarComunicado(__id__)" data-toggle="tooltip"
                id="buttonEditarComunicado___id__" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalExcluirComunicado(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    dtComunicadosColumnDefs.push(
        {
            render: function (data, type, row) // Visualizar Comunicado
            {
                return `\
                    <a href="<?=base_url('uploads/comunicados')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/comunicados')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${comunicadosEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2
        }
    );
    
    // DataTable
    var dt_comunicados = $("#dt_comunicados").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/getComunicados')?>"
        },
        dom: 'Bfrtip',
        buttons: dtComunicadosButtons,
        columnDefs: dtComunicadosColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioNovoComunicado()
    {
        // Carregando
        $('#buttonNovoComunicado')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#modalComunicado").load(
            "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/formularioComunicado')?>",
            function()
            {
                // Carregado
                $('#buttonNovoComunicado')
                    .html('<?=lang('novo_comunicado')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarComunicado(comunicadoId)
    {
        // Carregando
        $('#buttonEditarComunicado_'+comunicadoId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalComunicado").load(
            "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/formularioComunicado')?>/"+comunicadoId,
            function()
            {
                // Carregado
                $('#buttonEditarComunicado_'+comunicadoId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirComunicado(comunicadoId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalComunicado,
            comunicadoId,
            '<?=lang("confirmacao_exclusao_comunicado")?>' // Texto modal
        );
    }

    function excluirComunicado()
    {
        let comunicadoId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalComunicado);

        // Carregando
        $('#btnExcluirComunicado')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta comunicado
        $.ajax({
            url: '<?=site_url("GentesGestoes/GentesGestoesInfoGerais/excluirComunicado")?>/'+comunicadoId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalComunicado);

                    // Recarrega a tabela
                    dt_comunicados.ajax.reload();
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
                $('#btnExcluirComunicado')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>