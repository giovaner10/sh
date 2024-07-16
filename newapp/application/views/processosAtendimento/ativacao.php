<h3><?=lang("pa_ativacao")?></h3>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('processos_atendimento')?> >
	<?=lang('pa_ativacao')?>
</div>

<hr>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-primary" id='addProcesso'>Adicionar Ativação</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="empresas" class="tab-pane fade in active" style="margin-top: 20px">
            <table class="table-responsive table-bordered table" id="tabelaAtivacao">
                <thead>
                    <tr class="tableheader">
                        <th>ID</th>
                        <th>Título da Ativação</th>
                        <th><?=lang("acoes")?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>   
        </div>
	</div>
</div>

<div id="modal_processo" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form id="form_processo" enctype="multipart/form-data">
                <input type="hidden" id="processoId" name="processoId" value="<?=isset($processo->id) ? $processo->id : ''?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel">Adicionar Ativação</h4>
                </div>
                <div class="modal-body">
                
                    <div class="form-group">
                        <label class="control-label" for="processo">Título da Ativação</label>
                        <input type="text" id="processo" name="processo" class="form-control" required
                            value="">
                    </div>
                    <div class="form-group">
                        <label class="<?=isset($processo->id) ? '' : 'control-label' ?>" for="arquivo"><?=lang("arquivo")?> (PDF)</label>
                        <input type="file" id="arquivo" name="arquivo" class="form-control"
                            accept="application/pdf" <?=isset($processo->id) ? '' : 'required'?>>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="buttonSalvarProcesso"><?=lang("salvar")?></button>
                    <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function()
    {
        $("#addProcesso").on("click", function (evento){
            $("#modal_processo").modal('show');
            $("#modalLabel").text('Adicionar Ativação');
            $('#arquivo').prop('required', true)
            $('#processoId').val('');
            $('#processo').val('');
        });

        // Salva Comunicado
        $("#form_processo").on("submit", function (evento)
        {
            evento.preventDefault();
            // Carregando
            $('#buttonSalvarProcesso')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
                .attr('disabled', true);
            let formData = new FormData($("#form_processo")[0]);
            let url;
            if (formData.get("processoId"))
                url = "<?=site_url('ProcessosAtendimento/Ativacao/editarProcesso')?>/"
                    + formData.get("processoId");
            else
                url = "<?=site_url('ProcessosAtendimento/Ativacao/adicionarProcesso')?>";
            $.ajax({
                url: url,
                data: formData,
                type: "POST",
                dataType: "JSON",
                success: function (retorno)
                {
                    if (retorno.status == 1)
                    {
                        // Mensagem de retorno
                        toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');
                        // Fecha modal
                        $("#modal_processo").modal('hide');
                        $('#arquivo').val("");
                        // Recarrega a tabela
                        tabelaAtivacao.ajax.reload();
                    }
                    else
                    {
                        // Mensagem de retorno
                        toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                    }
                },
                error: function (xhr, textStatus, errorThrown)
                {
                    // Mensagem de erro
                    toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
                },
                complete: function ()
                {
                    // Carregado
                    $('#buttonSalvarProcesso')
                        .html('<?=lang('salvar')?>')
                        .attr('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>


<script type="text/javascript">
    
    let identificadorModal = 'Ativacao'; 
    let canEdit = "<?= $this->auth->is_allowed_block('edi_agendamento_processos_atendimento') ? "true" : "false"; ?>";
    let canDel = "<?= $this->auth->is_allowed_block('del_agendamento_processos_atendimento') ? "true" : "false"; ?>";

    var tabelaAtivacao = $("#tabelaAtivacao").DataTable({
        language: lang.datatable,
        order: [[ 0, "desc" ]],
        autoWidth: false,
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ProcessosAtendimento/Ativacao/listaProcessos')?>"
        },
        dom: 'Bfrtip',
        columnDefs: [
            {
                render: function (data, type, row) // Visualizar Release
                {
                    let acao = `\
                        <a href="<?=base_url('uploads/processo_notes')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="<?=base_url('uploads/processo_notes')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                            <i class="fa fa-download"></i>
                        </a>
                    `; 
                    
                    if (canEdit == "true") {
                        acao += `\
                        <a onclick="formularioEditarAtivacao('${data.id}','${data.processo}')" data-toggle="tooltip"
                            id="buttonEditarAtivacao_${data.id}" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        `;
                    }

                    if (canDel == "true") {
                        acao += `\
                        <a onclick="modalExcluirAtivacao('${data.id}')" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
                            <i class="fa fa-remove"></i>
                        </a>
                        `;
                    }

                    return acao;
                },
                className: 'text-center',
                targets: 2,
                width: "30%"
            },
            {
                tagerts: 0,
                width: '10%'
            },
            {
                tagerts: 1,
                width: '60%'
            },
            {
                className: "dt-center", 
                targets: "_all"
            }
        ],
        buttons: [
            {
                filename: filenameGenerator("Ativação"),
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL'
            },
            {
                filename: filenameGenerator("Ativação"),
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function ( doc , tes)
                {                
                    titulo = `Ativação`;
                    // Personaliza a página do PDF
                    widths = ['30%', '70%'];
                    pdfTemplateIsolated(doc, titulo, 'A4', widths, [], '', 16, 14)
                }
            },
            {
                filename: filenameGenerator("Ativação"),
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV'
            },
            {
                filename: filenameGenerator("Ativação"),
                extend: 'print',
                exportOptions: {
                    columns: [0, 1]
                },
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> ' + '<?= lang('imprimir') ?>'.toUpperCase(),
                customize: function ( win )
                {
                    titulo = `Ativação`;
                    // Personaliza a página Impressale
                    printTemplateOmni(win, titulo);
                }
            }
        ]
    });

    
    function formularioEditarAtivacao(ativacaoId, processo)
    {        
        $("#modal_processo").modal('show');
        $('#processoId').val(ativacaoId);
        $('#processo').val(processo);
        $("#modalLabel").text('Editar Ativação');
        $('#arquivo').prop('required', false)
    }
    
    function modalExcluirAtivacao(ativacaoId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModal,
            ativacaoId,
            '<?=lang("confirmacao_exclusao_ativacao")?>' // Texto modal
        );
    }

    function excluirAtivacao()
    {
        let ativacaoId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModal);

        // Carregando
        $('#btnExcluirAtivacao')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta release
        $.ajax({
            url: '<?=site_url("ProcessosAtendimento/Ativacao/removerProcesso")?>?ativacaoId='+ativacaoId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModal);

                    // Recarrega a tabela
                    tabelaAtivacao.ajax.reload();
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
                $('#btnExcluirRelease')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }
</script>