<table id="dt_banners" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <th><?=lang("codigo")?></th>
            <th><?=lang("titulo")?></th>
            <th><?=lang("ordem_de_exibicao")?></th>
            <th><?=lang("exibir_na_tela_inicial")?></th>
            <th style="min-width: 70px;"><?=lang("acoes")?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="modalBanner"></div>

<script type="text/javascript">

    let dtBannersButtons = [];
    let dtBannersColumnDefs = [];
    let bannersEdicaoExclusaoHtml = '';
    let identificadorModalBanner = 'Banner';

    // Novo Banner
    dtBannersButtons.push(
        {
            className: 'btn btn-lg btn-primary',
            text: '<?=lang("novo_banner")?>',
            attr:  {
                id: 'buttonNovoBanner'
            },
            action: function ( e, dt, node, config )
            {
                formularioNovoBanner();
            }
        }
    );

    // Btns da coluna ação
    dtBannersColumnDefs.push(
        {
            render: function (data, type, row)
            {
                return `
                    <span class="label label-${data === 'sim' ? 'success' : 'default'}">${lang[data]}</span>
                `;
            },
            className: 'text-center',
            targets: 3
        },
        {
            render: function (data, type, row)
            {
                return `
                    <a onclick="formularioEditarBanner(${data})" data-toggle="tooltip"
                        id="buttonEditarBanner_${data}" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-search"></i>
                    </a>
                    <a onclick="modalExcluirBanner(${data})" data-toggle="tooltip" class="btn btn-sm btn-danger" title="<?=lang('remover')?>">
                        <i class="fa fa-remove"></i>
                    </a>
                `;
            },
            className: 'text-center',
            targets: 4
        }
    );
    
    // DataTable
    var dt_banners = $("#dt_banners").DataTable({
        order: [],
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url: "<?=site_url('Marketings/MeuOmnilink/listarBanners')?>"
        },
        dom: 'Bfrtip',
        buttons: dtBannersButtons,
        columnDefs: dtBannersColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioNovoBanner()
    {
        // Carregando
        $('#buttonNovoBanner')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#modalBanner").load(
            "<?=site_url('Marketings/MeuOmnilink/formularioBanner')?>",
            function()
            {
                // Carregado
                $('#buttonNovoBanner')
                    .html('<?=lang('novo_banner')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarBanner(id)
    {
        // Carregando
        $('#buttonEditarBanner_'+id)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalBanner").load(
            "<?=site_url('Marketings/MeuOmnilink/formularioBanner')?>/"+id,
            function()
            {
                // Carregado
                $('#buttonEditarBanner_'+id)
                    .html('<i class="fa fa-search"></i>')
                    .attr('disabled', false);
            });
    }

    function modalExcluirBanner(id)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalBanner,
            id,
            '<?=lang("tem_certeza_que_deseja_excluir_o_banner")?>' // Texto modal
        )
    }

    function excluirBanner()
    {
        let id = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalBanner);

        // Carregando
        $('#btnExcluirBanner')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta banner
        $.ajax({
            url: '<?=site_url("Marketings/MeuOmnilink/excluirBanner")?>/'+id,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalBanner);
                    
                    // Recarrega a tabela
                    dt_banners.ajax.reload();
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
                $('#btnExcluirBanner')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>