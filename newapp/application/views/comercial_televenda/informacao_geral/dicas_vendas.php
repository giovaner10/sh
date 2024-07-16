<table id="tabelaDicasVendas" class="table table-striped table-bordered">
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

<div id="divModalDicaVenda"></div>

<script type="text/javascript">

    let dtDicasVendasBotoes = [];
    let dtDicasVendasColumnDefs = [];
    let dicasEdicaoExclusaoHtml = '';
    let identificadorModalDicaVenda = 'DicaVenda';

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_dicasdevendas')) :?>

        // Nova dica de venda
        dtDicasVendasBotoes.push(
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("nova_dica")?>',
                attr:  {
                    id: 'btnNovaDica'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovaDica();
                }
            }
        );

        // Edição e Exclusão
        dicasEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarDicaVenda(__id__)" data-toggle="tooltip"
                id="btnEditarDicaVenda___id__" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalExcluirDicaVenda(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    // Visualização, dowload, edição e exclusão
    dtDicasVendasColumnDefs.push(
        {
            render: function (data, type, row)
            {
                return `\
                    <a href="<?=base_url('uploads/dicas_vendas')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/dicas_vendas')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${dicasEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2
        }
    );
    
    // DataTable
    var tabelaDicasVendas = $("#tabelaDicasVendas").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/getDicasVendas')?>"
        },
        dom: 'Bfrtip',
        buttons: dtDicasVendasBotoes,
        columnDefs: dtDicasVendasColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioNovaDica()
    {
        // Carregando
        $('#btnNovaDica')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#divModalDicaVenda").load(
            "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/formularioDicaVenda')?>",
            function()
            {
                // Carregado
                $('#btnNovaDica')
                    .html('<?=lang('nova_dica')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarDicaVenda(dicaVendaId)
    {
        // Carregando
        $('#btnEditarDicaVenda_'+dicaVendaId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalDicaVenda").load(
            "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/formularioDicaVenda')?>/"+dicaVendaId,
            function()
            {
                // Carregado
                $('#btnEditarDicaVenda_'+dicaVendaId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirDicaVenda(dicaVendaId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalDicaVenda,
            dicaVendaId,
            '<?=lang("confirmacao_exclusao_dica_venda")?>' // Texto modal
        );
    }

    function excluirDicaVenda()
    {
        let dicaVendaId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalDicaVenda);

        // Carregando
        $('#btnExcluirDicaVenda')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta dica de venda
        $.ajax({
            url: '<?=site_url("ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/excluirDicaVenda")?>/'+dicaVendaId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalDicaVenda);

                    tabelaDicasVendas.ajax.reload();
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
                $('#btnExcluirDicaVenda')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>