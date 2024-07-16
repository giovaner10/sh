<h3><?=lang('propostas')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('propostas')?>
</div>

<hr>

<table id="tabelaPropostas" class="table table-striped table-bordered">
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

<div id="divModalProposta"></div>

<script type="text/javascript">

    let dtPropostasBtns = [];
    let dtPropostasColumnDefs = [];
    let propostaEdicaoExclusaoHtml = '';
    let identificadorModalProposta = 'Proposta';

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_propostacomercial')) :?>

        // Nova proposta
        dtPropostasBtns.push(
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("nova_proposta")?>',
                attr:  {
                    id: 'btnNovaProposta'
                },
                action: function ( e, dt, node, config )
                {
                    formularioProposta();
                }
            }
        );

        // Edição e Exclusão
        propostaEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarProposta(__id__)" data-toggle="tooltip"
                id="btnEditarProposta___id__" class="btn btn-sm btn-primary" title="${lang.editar}">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalExcluirProposta(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="${lang.remover}">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    dtPropostasColumnDefs.push(
        {
            render: function (data, type, row) // Baixar Formulario
            {
                return `\
                    <a href="<?=base_url('uploads/propostas_comerciais')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${propostaEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2
        }
    );
    
    // DataTable
    var tabelaPropostas = $("#tabelaPropostas").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ComerciaisTelevendas/Propostas/get')?>"
        },
        dom: 'Bfrtip',
        buttons: dtPropostasBtns,
        columnDefs: dtPropostasColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioProposta()
    {
        // Carregando
        $('#btnNovaProposta')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#divModalProposta").load(
            "<?=site_url('ComerciaisTelevendas/Propostas/formulario')?>",
            function()
            {
                // Carregado
                $('#btnNovaProposta')
                    .html('<?=lang('nova_proposta')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarProposta(propostaId)
    {
        // Carregando
        $('#btnEditarProposta_'+propostaId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalProposta").load(
            "<?=site_url('ComerciaisTelevendas/Propostas/formulario')?>/"+propostaId,
            function()
            {
                // Carregado
                $('#btnEditarProposta_'+propostaId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirProposta(propostaId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalProposta,
            propostaId,
            lang.confirmacao_exclusao_proposta
        );
    }

    function excluirProposta()
    {
        let propostaId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalProposta);

        // Carregando
        $('#btnExcluirProposta')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta apresentação
        $.ajax({
            url: '<?=site_url("ComerciaisTelevendas/Propostas/excluir")?>/'+propostaId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalProposta);
                    
                    tabelaPropostas.ajax.reload();
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
                $('#btnExcluirProposta')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>