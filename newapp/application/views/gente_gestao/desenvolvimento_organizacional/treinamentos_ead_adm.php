<div id="modal_treinamentos_ead" class="modal fade" tabindex="-1" role="dialog"
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
            
            <table id="dt_treinamentos_ead" class="table table-striped table-bordered">
                <thead>
                    <tr class="tableheader">
                        <th><?=lang("capa")?></th>
                        <th><?=lang("tipo")?></th>
                        <th><?=lang("descricao")?></th>
                        <th><?=lang("link")?></th>
                        <th style="min-width: 70px;"><?=lang("acoes")?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
            </div>

        </div>
    </div>
</div>

<div id="modalTreinamentoEad"></div>

<!-- Modal de confirmacao de exclusao -->
<div id="modalTreinamentoEadConfirmarExcluir" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    
    <input type="hidden" id="modalConfirmarTreinamentoEadId">
    <div class="modal-dialog" role="document" style="width: 30%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=lang("modal_confirmacao_titulo")?></h4>
            </div>

            <div class="modal-body">
                <?=lang("confirmacao_exclusao_treinamento")?>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="excluirTreinamentoEad()" id="buttonExcluirTreinamentoEad" class="btn btn-warning"><?=lang("excluir")?></button>
                <button type="button" class="btn" data-dismiss="modal"><?=lang("cancelar")?></button>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modal_treinamentos_ead").modal({backdrop: 'static' });
    });

    // Trabalha com o overflow para 2 modais
    $('#modal_treinamentos_ead').on('hidden.bs.modal', function(e){
        $("body").css('overflow-y', 'auto');
    });
    $('#modal_treinamentos_ead').on('show.bs.modal', function(e){
        $("body").css('overflow-y', 'hidden');
    });

    var dt_treinamentos_ead = $("#dt_treinamentos_ead").DataTable({
        order: [[4, "desc"]],
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/listarTreinamentosEad')?>"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("novo_treinamento_ead")?>',
                attr:  {
                    id: 'buttonNovoTreinamentoEad'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovoTreinamentoEad();
                }
            }
        ],
        columnDefs: [
            {
                render: function (data, type, row) // Visualizar Treinamento EAD
                {
                    return `\
                        <img class="img-treinamentos-gerenciar" src='<?=base_url("uploads/gente_gestao/desenv_organizagional/treinamentos/");?>/${data}'>
                    `;
                },
                className: 'text-center',
                targets: 0
            },
            {
                render: function (data, type, row) // Ações
                {
                    return `\
                        <a onclick="formularioEditarTreinamentoEad(${data})" data-toggle="tooltip"
                            id="buttonEditarTreinamentoEad_${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a onclick="modalExcluirTreinamentoEad(${data})" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
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

    function formularioNovoTreinamentoEad()
    {
        // Carregando
        $('#buttonNovoTreinamentoEad')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#modalTreinamentoEad").load(
            "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/formularioTreinamentoEad')?>",
            function()
            {
                // Carregado
                $('#buttonNovoTreinamentoEad')
                    .html('<?=lang('novo_treinamento_ead')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarTreinamentoEad(treinamentoEadId)
    {
        // Carregando
        $('#buttonEditarTreinamentoEad_'+treinamentoEadId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalTreinamentoEad").load(
            "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/formularioTreinamentoEad')?>/"+treinamentoEadId,
            function()
            {
                // Carregado
                $('#buttonEditarTreinamentoEad_'+treinamentoEadId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirTreinamentoEad(treinamentoEadId)
    {
        $("#modalConfirmarTreinamentoEadId").val(treinamentoEadId);
        $('#modalTreinamentoEadConfirmarExcluir').modal();
    }

    function excluirTreinamentoEad()
    {
        let treinamentoEadId = $("#modalConfirmarTreinamentoEadId").val();
        
        // Carregando
        $('#buttonExcluirTreinamentoEad')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta Treinamento EAD
        $.ajax({
            url: '<?=site_url("GentesGestoes/DesenvolvimentosOrganizacionais/excluirTreinamentoEad")?>/'+treinamentoEadId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    // Fecha modal
                    $("#modalTreinamentoEadConfirmarExcluir").modal('hide');
                    // Recarrega a tabela
                    dt_treinamentos_ead.ajax.reload();
                    // Recarrega a listagem da tela principal
                    atualizarListagemTreinamentosEad();
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
                $('#buttonExcluirTreinamentoEad')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

    function atualizarListagemTreinamentosEad()
    {
        $("#tab_treinamentos_ead").load("<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/atualizarListagemTreinamentosEad')?>");
    }

</script>

<style>
    .img-treinamentos-gerenciar {
        width: 100px;
        height: 80px;
    }
</style>