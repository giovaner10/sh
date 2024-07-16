<h3><?=lang('folhetos')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('folhetos')?>
</div>

<hr>

<table id="tabelaFolhetos" class="table table-striped table-bordered">
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

<div id="divModalFolheto"></div>

<script type="text/javascript">

    let dtFolhetosBtns = [];
    let dtFolhetosColumnDefs = [];
    let folhetoEdicaoExclusaoHtml = '';
    let identificadorModalFolheto = 'Folheto';

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_folheto')) :?>

        // Novo Folheto
        dtFolhetosBtns.push(
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("novo_folheto")?>',
                attr:  {
                    id: 'btnNovoFolheto'
                },
                action: function ( e, dt, node, config )
                {
                    formularioFolheto();
                }
            }
        );

        // Edição e Exclusão
        folhetoEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarFolheto(__id__)" data-toggle="tooltip"
                id="btnEditarFolheto___id__" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalExcluirFolheto(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    dtFolhetosColumnDefs.push(
        {
            render: function (data, type, row) // Visualizar Folheto
            {
                return `\
                    <a href="<?=base_url('uploads/folhetos')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="${lang.visualizar}">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/folhetos')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="${lang.baixar}">
                        <i class="fa fa-download"></i>
                    </a>
                    ${folhetoEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2
        }
    );
    
    // DataTable
    var tabelaFolhetos = $("#tabelaFolhetos").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ComerciaisTelevendas/Folhetos/get')?>"
        },
        dom: 'Bfrtip',
        buttons: dtFolhetosBtns,
        columnDefs: dtFolhetosColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioFolheto()
    {
        // Carregando
        $('#btnNovoFolheto')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#divModalFolheto").load(
            "<?=site_url('ComerciaisTelevendas/Folhetos/formulario')?>",
            function()
            {
                // Carregado
                $('#btnNovoFolheto')
                    .html('<?=lang('novo_folheto')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarFolheto(folhetoId)
    {
        // Carregando
        $('#btnEditarFolheto_'+folhetoId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalFolheto").load(
            "<?=site_url('ComerciaisTelevendas/Folhetos/formulario')?>/"+folhetoId,
            function()
            {
                // Carregado
                $('#btnEditarFolheto_'+folhetoId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirFolheto(folhetoId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalFolheto,
            folhetoId,
            lang.confirmacao_exclusao_folheto
        );
    }

    function excluirFolheto()
    {
        let folhetoId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalFolheto);

        // Carregando
        $('#btnExcluirFolheto')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta folheto
        $.ajax({
            url: '<?=site_url("ComerciaisTelevendas/Folhetos/excluir")?>/'+folhetoId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalFolheto);
                    
                    tabelaFolhetos.ajax.reload();
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
                $('#btnExcluirFolheto')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>