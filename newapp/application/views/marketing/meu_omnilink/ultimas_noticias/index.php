<table id="dt_noticias" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <th><?=lang("codigo")?></th>
            <th><?=lang("titulo")?></th>
            <th><?=lang("descricao")?></th>
            <th><?=lang("data_cadastro")?></th>
            <th style="min-width: 70px;"><?=lang("acoes")?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="modalNoticia"></div>

<script type="text/javascript">

    let dtNoticiasButtons = [];
    let dtNoticiasColumnDefs = [];
    let noticiasEdicaoExclusaoHtml = '';
    let identificadorModalNoticia = 'Noticia';

    // Nova Noticia
    dtNoticiasButtons.push(
        {
            className: 'btn btn-lg btn-primary',
            text: '<?=lang("nova_noticia")?>',
            attr:  {
                id: 'buttonNovaNoticia'
            },
            action: function ( e, dt, node, config )
            {
                formularioNovaNoticia();
            }
        }
    );

    // Btns da coluna ação
    dtNoticiasColumnDefs.push(
        {
            render: function (data, type, row)
            {
                return `
                    <a onclick="formularioEditarNoticia(${data})" data-toggle="tooltip"
                        id="buttonEditarNoticia_${data}" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-search"></i>
                    </a>
                    <a onclick="modalExcluirNoticia(${data})" data-toggle="tooltip" class="btn btn-sm btn-danger" title="<?=lang('remover')?>">
                        <i class="fa fa-remove"></i>
                    </a>
                `;
            },
            className: 'text-center',
            targets: 4
        }
    );
    
    // DataTable
    var dt_noticias = $("#dt_noticias").DataTable({
        order: [],
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url: "<?=site_url('Marketings/MeuOmnilink/listarNoticias')?>"
        },
        dom: 'Bfrtip',
        buttons: dtNoticiasButtons,
        columnDefs: dtNoticiasColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioNovaNoticia()
    {
        // Carregando
        $('#buttonNovaNoticia')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#modalNoticia").load(
            "<?=site_url('Marketings/MeuOmnilink/formularioNoticia')?>",
            function()
            {
                // Carregado
                $('#buttonNovaNoticia')
                    .html('<?=lang('nova_noticia')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarNoticia(id)
    {
        // Carregando
        $('#buttonEditarNoticia_'+id)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalNoticia").load(
            "<?=site_url('Marketings/MeuOmnilink/formularioNoticia')?>/"+id,
            function()
            {
                // Carregado
                $('#buttonEditarNoticia_'+id)
                    .html('<i class="fa fa-search"></i>')
                    .attr('disabled', false);
            });
    }

    function modalExcluirNoticia(id)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalNoticia,
            id,
            '<?=lang("tem_certeza_que_deseja_excluir_a_noticia")?>' // Texto modal
        )
    }

    function excluirNoticia()
    {
        let id = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalNoticia);

        // Carregando
        $('#btnExcluirNoticia')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta noticia
        $.ajax({
            url: '<?=site_url("Marketings/MeuOmnilink/excluirNoticia")?>/'+id,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalNoticia);
                    
                    // Recarrega a tabela
                    dt_noticias.ajax.reload();
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
                $('#btnExcluirNoticia')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>