<div id="modalDocumentosPendentes" class="modal fade" tabindex="-1" role="dialog"
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
            
                <table id="dtDocumentosPendentes" class="table table-striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th><?=lang("funcionario")?></th>
                            <th><?=lang("documentos")?></th>
                            <th><?=lang("status")?></th>
                            <th><?=lang("visualizar_documentos")?></th>
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

<div id="divModalDocumentosPendentes"></div>
<div id="divModalVisualizarArquivos"></div>

<!-- Modal de confirmacao de exclusao -->
<div id="modalDocumentoPendenteConfirmarExcluir" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    
    <input type="hidden" id="modalConfirmarDocumentoPendenteId">
    <div class="modal-dialog" role="document" style="width: 30%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=lang("modal_confirmacao_titulo")?></h4>
            </div>

            <div class="modal-body">
                <?=lang("confirmacao_exclusao_solicitacao")?>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="documentoPendenteExcluir()" id="buttonDocumentoPendenteExcluir" class="btn btn-warning"><?=lang("excluir")?></button>
                <button type="button" class="btn" data-dismiss="modal"><?=lang("cancelar")?></button>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function()
    {
        $("#modalDocumentosPendentes").modal({backdrop: 'static' });
    });

    // Trabalha com o overflow para 2 modais
    $('#modalDocumentosPendentes').on('hidden.bs.modal', function(e){
        $("body").css('overflow-y', 'auto');
    });
    $('#modalDocumentosPendentes').on('show.bs.modal', function(e){
        $("body").css('overflow-y', 'hidden');
    });

    var dtDocumentosPendentes = $("#dtDocumentosPendentes").DataTable({
        order: [[0, "asc"]],
        language: lang.datatable,
        autoWidth: false,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesListar')?>"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("nova_solicitacao")?>',
                attr:  {
                    id: 'buttonNovosDocumentosPendentes'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovosDocumentosPendentes();
                }
            }
        ],
        columnDefs: [
            {
                render: function (data, type, row) // Visualizar Arquivos
                {
                    return `\
                        <a onclick="visualizarArquivos(${data})" id="visualizarArquivos_${data}" target="_blank" data-toggle="tooltip"
                            class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                            <i class="fa fa-eye"></i>
                        </a>
                    `;
                },
                className: 'text-center',
                targets: 3
            },
            {
                render: function (data, type, row) // Ações
                {
                    return `\
                        <a onclick="formularioEditarDocumentosPendentes(${data})" data-toggle="tooltip"
                            id="buttonEditarDocumentosPendentes_${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a onclick="modalExcluirDocumentosPendentes(${data})" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
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

    function formularioNovosDocumentosPendentes()
    {
        // Carregando
        $('#buttonNovosDocumentosPendentes')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalDocumentosPendentes").load(
            "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesFormulario')?>",
            function()
            {
                // Carregado
                $('#buttonNovosDocumentosPendentes')
                    .html('<?=lang('nova_solicitacao')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarDocumentosPendentes(documentoPendenteId)
    {
        // Carregando
        $('#buttonEditarDocumentosPendentes_'+documentoPendenteId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalDocumentosPendentes").load(
            "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesFormulario')?>/"+documentoPendenteId,
            function()
            {
                // Carregado
                $('#buttonEditarDocumentosPendentes_'+documentoPendenteId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirDocumentosPendentes(documentoPendenteId)
    {
        $("#modalConfirmarDocumentoPendenteId").val(documentoPendenteId);
        $('#modalDocumentoPendenteConfirmarExcluir').modal();
    }

    function documentoPendenteExcluir()
    {
        let documentoPendenteId = $("#modalConfirmarDocumentoPendenteId").val();
        
        // Carregando
        $('#buttonDocumentoPendenteExcluir')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta documento pendente
        $.ajax({
            url: '<?=site_url("GentesGestoes/AdministracoesPessoais/documentosPendentesExcluir")?>/'+documentoPendenteId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    // Fecha modal
                    $("#modalDocumentoPendenteConfirmarExcluir").modal('hide');
                    // Recarrega a tabela
                    dtDocumentosPendentes.ajax.reload();
                    // Recarrega a listagem da tela principal
                    documentosPendentesAtualizarListagem();
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
                $('#buttonDocumentoPendenteExcluir')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

    function visualizarArquivos(documentosId)
    {
        // Carregando
        $('#visualizarArquivos_'+documentosId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#divModalVisualizarArquivos").load(
            "<?=site_url('GentesGestoes/AdministracoesPessoais/documentosPendentesVisualizarArquivos')?>/"+documentosId,
            function()
            {
                // Carregado
                $('#visualizarArquivos_'+documentosId)
                    .html('<i class="fa fa-eye"></i>')
                    .attr('disabled', false);
            }
        );
    }
</script>