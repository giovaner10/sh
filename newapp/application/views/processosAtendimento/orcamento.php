<style>
    th.dt-center, td.dt-center { 
        text-align: center !important; 
    }
</style>

<h3><?=lang("orcamento")?></h3>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('processos_atendimento')?> >
	<?=lang('orcamento')?>
</div>

<hr>

<button class="btn btn-md btn-primary" id='buttonNovoOrcamento' onclick="formularioNovoOrcamento()"><?=lang("adicionar_orcamento")?></button>

<div class="row">
    <div class="col-md-12">
        <div id="empresas" class="tab-pane fade in active" style="margin-top: 20px">
            <table class="table-responsive table-bordered table" id="tabelaOrcamento">
                <thead>
                    <tr class="tableheader">
                        <th>ID</th>
                        <th><?=lang('titulo_orcamento')?></th>
                        <th><?=lang("acoes")?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>   
        </div>
    </div>
</div>

<div id="modalOrcamento"></div>

<script type="text/javascript">
    let canEdit = "<?= $this->auth->is_allowed_block('edi_orcamento_processos_atendimento') ? "true" : "false"; ?>";
    let canDel = "<?= $this->auth->is_allowed_block('del_orcamento_processos_atendimento') ? "true" : "false"; ?>";
    let dtOrcamentosButtons = [];
    let dtOrcamentosColumnDefs = [];
    let releasesEdicaoExclusaoHtml = '';
    let orcamentosEdicaoExclusaoHtml = '';
    let identificadorModalOrcamento = 'Orcamento';

    // Edição e Exclusão
    if (canEdit == "true") {
        orcamentosEdicaoExclusaoHtml = orcamentosEdicaoExclusaoHtml + `\
        <a onclick="formularioEditarOrcamento(__id__)" data-toggle="tooltip"
            id="buttonEditarOrcamento___id__" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
            <i class="fa fa-edit"></i>
        </a>
        `;
    }

    if (canDel == "true") {
        orcamentosEdicaoExclusaoHtml = orcamentosEdicaoExclusaoHtml + `\
        <a onclick="modalExcluirOrcamento(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
            <i class="fa fa-remove"></i>
        </a>
        `;
    }
    

    dtOrcamentosButtons.push(
        {
            filename: filenameGenerator("Orçamento"),
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
            filename: filenameGenerator("Orçamento"),
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
                titulo = `Orçamento`;
                // Personaliza a página do PDF
                widths = ['20%', '80%'];
                pdfTemplateIsolated(doc, titulo, 'A4', widths, 16, 14)
            }
        },
        {
            filename: filenameGenerator("Orçamento"),
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
            filename: filenameGenerator("Orçamento"),
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
                titulo = `Orçamento`;
                // Personaliza a página Impressale
                printTemplateOmni(win, titulo);
            }
        }
    );

    dtOrcamentosColumnDefs.push(
        {
            render: function (data, type, row) 
            {
                return `\
                    <a href="<?=base_url('uploads/processos_atendimento_orcamento')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/processos_atendimento_orcamento')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${orcamentosEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
                `;
            },
            className: 'text-center',
            targets: 2,
            orderable: false,
            width: '30%'
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
    );


    var tabelaOrcamento = $("#tabelaOrcamento").DataTable({
        language: {
           loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
           loadingRecords: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b>',
           searchPlaceholder:  'Pesquisar',
           emptyTable:         "Nenhum orçamento a ser listado",
           info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
           infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
           zeroRecords:        "Nenhum resultado encontrado.",
           paginate: {
               first:          "Primeira",
               last:           "Última",
               next:           "Próxima",
               previous:       "Anterior"
           },
        },
        order: [[ 0, "desc" ]],
        autoWidth: false,
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ProcessosAtendimento/Orcamento/getOrcamentos')?>",
            beforeSend: function() {
                showLoadingMessage();
            },
            success: function(response){
                tabelaOrcamento.clear().draw();
                tabelaOrcamento.rows.add(response.data).draw();
            },
            error: function(){
                alert('Erro ao buscar os dados. Tente novamente.')
                tabelaOrcamento.clear().draw();
            },
        },
        dom: 'Bfrtip',
        buttons: dtOrcamentosButtons,
        columnDefs: dtOrcamentosColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
        }
    });

    function showLoadingMessage() {
        $('#tabelaOrcamento > tbody').html(
            '<tr class="odd">' +
            '<td valign="top" colspan="10" class="dataTables_empty" style="align-items: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
            '</tr>'
        );
    }

    function reloadTable() {
        showLoadingMessage();
        tabelaOrcamento.ajax.reload();
    }

    function formularioNovoOrcamento()
    {
        // Carregando
        $('#buttonNovoOrcamento')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#modalOrcamento").load(
            "<?=site_url('ProcessosAtendimento/Orcamento/formularioOrcamento')?>",
            function()
            {
                $('#buttonNovoOrcamento')
                    .html('<?=lang('adicionar_orcamento')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarOrcamento(orcamentoId)
    {
        // Carregando
        $('#buttonEditarOrcamento_'+orcamentoId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalOrcamento").load(
            "<?=site_url('ProcessosAtendimento/Orcamento/formularioOrcamento')?>/"+orcamentoId,
            function()
            {
                // Carregado
                $('#buttonEditarOrcamento_'+orcamentoId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirOrcamento(orcamentoId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalOrcamento,
            orcamentoId,
            '<?=lang("confirmacao_exclusao_orcamento")?>' // Texto modal
        );
    }

    function excluirOrcamento()
    {
        let orcamentoId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalOrcamento);

        // Carregando
        $('#btnExcluirOrcamento')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta
        $.ajax({
            url: '<?=site_url("ProcessosAtendimento/Orcamento/excluirOrcamento")?>/'+orcamentoId,
            type: "POST",
            dataType: "JSON",
            beforeSend: function() {
                $('#tabelaSolicitacoes > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty" style="align-items: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalOrcamento);

                    // Recarrega a tabela
                    reloadTable();
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
                $('#btnExcluirOrcamento')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>