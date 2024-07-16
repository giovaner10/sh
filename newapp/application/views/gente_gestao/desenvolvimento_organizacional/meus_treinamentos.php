<table id="dt_treinamentos" class="table table-striped table-bordered">
    <thead>
        <tr class="tableheader">
            <?php if ($this->auth->is_allowed_block('cad_atividades')) : ?>
                <th><?=lang("funcionario")?></th>
            <?php endif; ?>
            <th><?=lang("descricao")?></th>
            <th><?=lang("tipo")?></th>
            <th><?=lang("inicio")?></th>
            <th><?=lang("termino")?></th>
            <th><?=lang("carga_hr")?></th>
            <th><?=lang("status")?></th>
            <th style="min-width: 70px;"><?=lang("acoes")?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="modalTreinamento"></div>

<!-- Modal de confirmacao de exclusao -->
<div id="modalTreinamentoConfirmarExcluir" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    
    <input type="hidden" id="modalConfirmarTreinamentoId">
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
                <button type="button" onclick="excluirTreinamento()" id="buttonExcluirTreinamento" class="btn btn-warning"><?=lang("excluir")?></button>
                <button type="button" class="btn" data-dismiss="modal"><?=lang("cancelar")?></button>
            </div>

        </div>
    </div>
</div>

<script>

    var dt_treinamentos = $("#dt_treinamentos").DataTable({
        order: [],
        language: lang.datatable,
        autoWidth: false,
        processing: true,
        bLengthChange : false, //thought this line could hide the LengthMenu
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/listarTreinamentos')?>"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                className: 'btn btn-lg btn-primary',
                text: '<?=lang("novo_treinamento")?>',
                attr:  {
                    id: 'buttonNovoTreinamento'
                },
                action: function ( e, dt, node, config )
                {
                    formularioNovoTreinamento();
                }
            }
        ],
        columnDefs: [
            {
                render: function (data, type, row) // Carga Horária
                {
                    return data + ' hora(s)'
                },
                targets: $("#dt_treinamentos tr th").length == 5 ? 4 : 5
            },
            {
                render: function (data, type, row) // Ações
                {
                    return `\
                        <a onclick="formularioEditarTreinamento(${data})" data-toggle="tooltip"
                            id="buttonEditarTreinamento_${data}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a onclick="modalExcluirTreinamento(${data})" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                            <i class="fa fa-remove"></i>
                        </a>
                    `;
                },
                className: 'text-center',
                targets: $("#dt_treinamentos tr th").length == 7 ? 6 : 7
            }
        ],
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip();

            <?php if ($this->auth->is_allowed_block('cad_atividades')) : ?>
                if (!$("#filtro_funcionario").length)
                {
                    $("#dt_treinamentos_wrapper .dt-buttons").append(`
                        <select name="filtro_funcionario" id="filtro_funcionario" class="form-control"
                            style="margin-left: 10px">
                            <option value=""><?=lang("selecione_um_funcionario_filtrar_dados")?></option>
                        </select>
                    `);

                    carregaSelectFiltroFuncionarios();
                }
            <?php endif; ?>
        }
    });

    // Recarrega tabela com o filtro
    $("#filtro_funcionario").on("change", function()
    {
        let url = "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/listarTreinamentos')?>"
            + "/" + $("#filtro_funcionario").val();
            
        dt_treinamentos.ajax.url(url).load();
    });

    // Carrega select de funcionarios
    function carregaSelectFiltroFuncionarios()
    {
        $.get("<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/listarFuncionarios')?>", function (retorno)
        {
            if (retorno.status == 1)
            {
                retorno.dados.forEach(function(funcionario)
                {
                    $("#filtro_funcionario").append(`
                        <option value="${funcionario.id}">${funcionario.nome}</option>
                    `);
                });
            }
        }, "JSON");
    }

    function formularioNovoTreinamento()
    {
        // Carregando
        $('#buttonNovoTreinamento')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);
        
        // Modal
        $("#modalTreinamento").load(
            "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/formularioTreinamento')?>",
            function()
            {
                // Carregado
                $('#buttonNovoTreinamento')
                    .html('<?=lang('novo_treinamento')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarTreinamento(treinamentoId)
    {
        // Carregando
        $('#buttonEditarTreinamento_'+treinamentoId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalTreinamento").load(
            "<?=site_url('GentesGestoes/DesenvolvimentosOrganizacionais/formularioTreinamento')?>/"+treinamentoId,
            function()
            {
                // Carregado
                $('#buttonEditarTreinamento_'+treinamentoId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirTreinamento(treinamentoId)
    {
        $("#modalConfirmarTreinamentoId").val(treinamentoId);
        $('#modalTreinamentoConfirmarExcluir').modal();
    }

    function excluirTreinamento()
    {
        let treinamentoId = $("#modalConfirmarTreinamentoId").val();
        
        // Carregando
        $('#buttonExcluirTreinamento')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta Treinamento
        $.ajax({
            url: '<?=site_url("GentesGestoes/DesenvolvimentosOrganizacionais/excluirTreinamento")?>/'+treinamentoId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    // Fecha modal
                    $("#modalTreinamentoConfirmarExcluir").modal('hide');
                    // Recarrega a tabela
                    dt_treinamentos.ajax.reload();
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
                $('#buttonExcluirTreinamento')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>