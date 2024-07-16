<table id="tabelaRegulamentosCampanhaVendas" class="table table-striped table-bordered">
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

<div id="divModalRegulamentoCampanhaVenda"></div>

<script type="text/javascript">

    let dtRegulamentosCampanhaVendasBotoes = [];
    let dtRegulamentosCampanhaVendasColumnDefs = [];
    let regulamentosEdicaoExclusaoHtml = '';
    let identificadorModalRegulamento = 'RegulamentoCampanhaVenda';

    // Caso tenha permissão de administrador
    <?php if ($this->auth->is_allowed_block('cad_regulamentosdecampanhadevendas')) :?>

        // Novo regulamento
        dtRegulamentosCampanhaVendasBotoes.push(
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("novo_regulamento")?>',
                attr:  {
                    id: 'btnNovoRegulamentoCampanhaVenda'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovoRegulamentoCampanhaVenda();
                }
            }
        );

        // Edição e Exclusão
        regulamentosEdicaoExclusaoHtml = `\
            <a onclick="formularioEditarRegulamentoCampanhaVenda(__id__)" data-toggle="tooltip"
                id="btnEditarRegulamentoCampanhaVenda___id__" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                <i class="fa fa-edit"></i>
            </a>
            <a onclick="modalExcluirRegulamentoCampanhaVenda(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                <i class="fa fa-remove"></i>
            </a>
        `;

    <?php endif; ?>

    // Visualizar, dowload, edição e exclusão
    dtRegulamentosCampanhaVendasColumnDefs.push(
        {
            render: function (data, type, row)
            {
                return `\
                    <a href="<?=base_url('uploads/regulamentos_campanha_vendas')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/regulamentos_campanha_vendas')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${regulamentosEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2
        }
    );
    
    // DataTable
    var tabelaRegulamentosCampanhaVendas = $("#tabelaRegulamentosCampanhaVendas").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/getRegulamentosCampanhaVendas')?>"
        },
        dom: 'Bfrtip',
        buttons: dtRegulamentosCampanhaVendasBotoes,
        columnDefs: dtRegulamentosCampanhaVendasColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioNovoRegulamentoCampanhaVenda()
    {
        // Carregando
        $('#btnNovoRegulamentoCampanhaVenda')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#divModalRegulamentoCampanhaVenda").load(
            "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/formularioRegulamentoCampanhaVenda')?>",
            function()
            {
                // Carregado
                $('#btnNovoRegulamentoCampanhaVenda')
                    .html('<?=lang('novo_regulamento')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarRegulamentoCampanhaVenda(id)
    {
        // Carregando
        $('#btnEditarRegulamentoCampanhaVenda_'+id)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalRegulamentoCampanhaVenda").load(
            "<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/formularioRegulamentoCampanhaVenda')?>/"+id,
            function()
            {
                // Carregado
                $('#btnEditarRegulamentoCampanhaVenda_'+id)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirRegulamentoCampanhaVenda(id)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalRegulamento,
            id,
            '<?=lang("confirmacao_exclusao_regulamento_campanha_venda")?>' // Texto modal
        );
    }

    function excluirRegulamentoCampanhaVenda()
    {
        let id = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalRegulamento);

        // Carregando
        $('#btnExcluirRegulamentoCampanhaVenda')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta regulamento
        $.ajax({
            url: '<?=site_url("ComerciaisTelevendas/ComerciaisTelevendasInfoGerais/excluirRegulamentoCampanhaVenda")?>/'+id,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalRegulamento);

                    tabelaRegulamentosCampanhaVendas.ajax.reload();
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
                $('#btnExcluirRegulamentoCampanhaVenda')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>