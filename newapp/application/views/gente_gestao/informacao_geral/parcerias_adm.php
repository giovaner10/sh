<div id="modal_parcerias" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 95%;">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=$modalTitulo?></h4>
            </div>

            <div class="modal-body">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_adm_parcerias" data-toggle="tab"><?=lang('parcerias')?></a></li>
                    <li><a href="#tab_categorias_adm" data-toggle="tab"><?=lang('categorias')?></a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_adm_parcerias">
                        <!-- ADM Parcerias -->
                        <table id="dt_parcerias" class="table table-striped table-bordered">
                            <thead>
                                <tr class="tableheader">
                                    <th><?=lang("capa")?></th>
                                    <th><?=lang("categoria")?></th>
                                    <th><?=lang("descricao")?></th>
                                    <th><?=lang("link")?></th>
                                    <th style="min-width: 70px;"><?=lang("acoes")?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <!-- Categorias -->
                    <div class="tab-pane" id="tab_categorias_adm">
                        <?php $this->load->view('gente_gestao/informacao_geral/parcerias_categorias');?>
                    </div>
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
            </div>

        </div>
    </div>
</div>

<div id="divModalParceria"></div>

<div id="divModalParceriaCategoria"></div>

<script>

    identificadorModalParceria = 'Parceria';

    $(document).ready(function()
    {
        $("#modal_parcerias").modal({backdrop: 'static' });
    });

    // Trabalha com o overflow para 2 modais
    $('#modal_parcerias').on('hidden.bs.modal', function(e){
        $("body").css('overflow-y', 'auto');
    });
    $('#modal_parcerias').on('show.bs.modal', function(e){
        $("body").css('overflow-y', 'hidden');
    });

    var dt_parcerias = $("#dt_parcerias").DataTable({
        order: [],
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/listarParcerias')?>"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("nova_parceria")?>',
                attr:  {
                    id: 'buttonNovaParceria'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovaParceria();
                }
            }
        ],
        columnDefs: [
            {
                render: function (data, type, row) // Visualizar Parceria
                {
                    return `\
                        <img class="img-parcerias-gerenciar" src='<?=base_url("uploads/gente_gestao/desenv_organizagional/parcerias/");?>/${data}'>
                    `;
                },
                className: 'text-center',
                targets: 0
            },
            {
                render: function (data, type, row) // Ações
                {
                    return `\
                        <a onclick="formularioEditarParceria(${data})" data-toggle="tooltip"
                            id="buttonEditarParceria_${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a onclick="modalExcluirParceria(${data})" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                            <i class="fa fa-remove"></i>
                        </a>
                    `;
                },
                className: 'text-center',
                targets: 4
            }
        ],
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function formularioNovaParceria()
    {
        // Carregando
        $('#buttonNovaParceria')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalParceria").load(
            "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/formularioParceria')?>",
            function()
            {
                // Carregado
                $('#buttonNovaParceria')
                    .html('<?=lang('nova_parceria')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarParceria(parceriaId)
    {
        // Carregando
        $('#buttonEditarParceria_'+parceriaId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalParceria").load(
            "<?=site_url('GentesGestoes/GentesGestoesInfoGerais/formularioParceria')?>/"+parceriaId,
            function()
            {
                // Carregado
                $('#buttonEditarParceria_'+parceriaId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirParceria(parceriaId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalParceria,
            parceriaId,
            '<?=lang("confirmacao_exclusao_parceria")?>' // Texto modal
        );
    }

    function excluirParceria()
    {
        let parceriaId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalParceria);
        
        // Carregando
        $('#btnExcluirParceria')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta Parceria
        $.ajax({
            url: '<?=site_url("GentesGestoes/GentesGestoesInfoGerais/excluirParceria")?>/'+parceriaId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalParceria);

                    // Recarrega a tabela
                    dt_parcerias.ajax.reload();

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
                $('#btnExcluirParceria')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

    function atualizarListagemParcerias()
    {
        $("#tab_parcerias").load("<?=site_url('GentesGestoes/GentesGestoesInfoGerais/atualizarListagemParcerias')?>");
    }

</script>

<style>
    .img-parcerias-gerenciar {
        width: 100px;
        height: 80px;
    }
</style>