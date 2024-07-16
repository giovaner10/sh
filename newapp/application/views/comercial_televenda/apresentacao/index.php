<h3><?=lang('apresentacoes')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('apresentacoes')?>
</div>

<hr>

<table id="tabelaApresentacoes" class="table table-striped table-bordered">
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

<div id="divModalApresentacao"></div>

<script type="text/javascript">

    let dtApresentacaoBotoes = [];
    let dtApresentacaoColumnDefs = [];
    let apresentacaoEdicaoExclusaoHtml = '';
    let identificadorModalApresentacoes = 'Apresentacao';

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_apresentacao')) :?>

        // Nova apresentacao
        dtApresentacaoBotoes.push(
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("nova_apresentacao")?>',
                attr:  {
                    id: 'btnNovaApresentacao'
                },
                action: function ( e, dt, node, config )
                {
                    formularioApresentacao();
                }
            }
        );

        // Edição e Exclusão
        apresentacaoEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarApresentacao(__id__)" data-toggle="tooltip"
                id="btnEditarApresentacao___id__" class="btn btn-sm btn-primary" title="${lang.editar}">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalExcluirApresentacao(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="${lang.remover}">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    dtApresentacaoColumnDefs.push(
        {
            render: function (data, type, row) // Visualizar apresentacao
            {
                return `\
                    <a href="<?=base_url('uploads/apresentacoes')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="${lang.visualizar}">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/apresentacoes')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="${lang.baixar}">
                        <i class="fa fa-download"></i>
                    </a>
                    ${apresentacaoEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2
        }
    );
    
    // DataTable
    var tabelaApresentacoes = $("#tabelaApresentacoes").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ComerciaisTelevendas/Apresentacoes/get')?>"
        },
        dom: 'Bfrtip',
        buttons: dtApresentacaoBotoes,
        columnDefs: dtApresentacaoColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioApresentacao()
    {
        // Carregando
        $('#btnNovaApresentacao')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#divModalApresentacao").load(
            "<?=site_url('ComerciaisTelevendas/Apresentacoes/formulario')?>",
            function()
            {
                // Carregado
                $('#btnNovaApresentacao')
                    .html('<?=lang('nova_apresentacao')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarApresentacao(apresentacaoId)
    {
        // Carregando
        $('#btnEditarApresentacao_'+apresentacaoId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalApresentacao").load(
            "<?=site_url('ComerciaisTelevendas/Apresentacoes/formulario')?>/"+apresentacaoId,
            function()
            {
                // Carregado
                $('#btnEditarApresentacao_'+apresentacaoId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirApresentacao(apresentacaoId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalApresentacoes,
            apresentacaoId,
            '<?=lang("confirmacao_exclusao_apresentacao")?>'
        );
    }

    function excluirApresentacao()
    {
        let apresentacaoId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalApresentacoes);

        // Carregando
        $('#btnExcluirApresentacao')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta apresentação
        $.ajax({
            url: '<?=site_url("ComerciaisTelevendas/Apresentacoes/excluir")?>/'+apresentacaoId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalApresentacoes);

                    tabelaApresentacoes.ajax.reload();
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
                $('#btnExcluirApresentacao')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>