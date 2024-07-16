<h3><?=lang('guias')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('guias')?>
</div>

<hr>

<table id="tabelaGuias" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <th><?=lang("codigo")?></th>
            <th><?=lang("descricao")?></th>
            <th style="min-width: 70px;"><?=lang("acoes")?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="divModalGuia"></div>

<script type="text/javascript">

    let dtGuiasBtns = [];
    let dtGuiasColumnDefs = [];
    let guiaEdicaoExclusaoHtml = '';
    let identificadorModalGuia = 'Guia';

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_guia')) :?>

        // Nova Guia
        dtGuiasBtns.push(
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("nova_guia")?>',
                attr:  {
                    id: 'btnNovoGuia'
                },
                action: function ( e, dt, node, config )
                {
                    formularioGuia();
                }
            }
        );

        // Edição e Exclusão
        guiaEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarGuia(__id__)" data-toggle="tooltip"
                id="btnEditarGuia___id__" class="btn btn-sm btn-primary" title="${lang.editar}">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalInativarGuia(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="${lang.remover}">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    dtGuiasColumnDefs.push(
        {
            render: function (data, type, row) // Visualizar Guia
            {
                return `\
                    <a href="<?=base_url('uploads/guias')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/guias')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${guiaEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2
        }
    );
    
    // DataTable
    var tabelaGuias = $("#tabelaGuias").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ComerciaisTelevendas/Guias/get')?>"
        },
        dom: 'Bfrtip',
        buttons: dtGuiasBtns,
        columnDefs: dtGuiasColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioGuia()
    {
        // Carregando
        $('#btnNovoGuia')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#divModalGuia").load(
            "<?=site_url('ComerciaisTelevendas/Guias/formulario')?>",
            function()
            {
                // Carregado
                $('#btnNovoGuia')
                    .html('<?=lang('nova_guia')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarGuia(guiaId)
    {
        // Carregando
        $('#btnEditarGuia_'+guiaId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalGuia").load(
            "<?=site_url('ComerciaisTelevendas/Guias/formulario')?>/"+guiaId,
            function()
            {
                // Carregado
                $('#btnEditarGuia_'+guiaId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalInativarGuia(guiaId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalGuia,
            guiaId,
            lang.confirmacao_exclusao_guia
        );
    }

    function excluirGuia()
    {
        let guiaId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalGuia);

        // Carregando
        $('#btnExcluirGuia')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta guia
        $.ajax({
            url: '<?=site_url("ComerciaisTelevendas/Guias/excluir")?>/'+guiaId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalGuia);

                    tabelaGuias.ajax.reload();
                }
                else
                {
                    toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
            },
            complete: function ()
            {
                // Carregado
                $('#btnExcluirGuia')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>