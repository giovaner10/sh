<style>
    th.dt-center, td.dt-center { 
        text-align: center !important; 
    }
</style>

<h3><?=lang("reclame_aqui")?></h3>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('processos_atendimento')?> >
	<?=lang('reclame_aqui')?>
</div>

<hr>

<button class="btn btn-md btn-primary" id='buttonNovoReclameAqui' onclick="formularioNovoReclameAqui()"><?=lang("adicionar_reclame_aqui")?></button>

<div class="row">
    <div class="col-md-12">
        <div id="empresas" class="tab-pane fade in active" style="margin-top: 20px">
            <table class="table-responsive table-bordered table" id="tabelaReclameAqui">
                <thead>
                    <tr class="tableheader">
                        <th>ID</th>
                        <th><?=lang('titulo_reclame_aqui')?></th>
                        <th><?=lang("acoes")?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>   
        </div>
    </div>
</div>

<div id="modalReclameAqui"></div>

<script type="text/javascript">
    let canEdit = "<?= $this->auth->is_allowed_block('edi_reclame_aqui_processos_atendimento') ? "true" : "false"; ?>";
    let canDel = "<?= $this->auth->is_allowed_block('del_reclame_aqui_processos_atendimento') ? "true" : "false"; ?>";
    let dtReclameAquisButtons = [];
    let dtReclameAquisColumnDefs = [];
    let releasesEdicaoExclusaoHtml = '';
    let reclame_aquisEdicaoExclusaoHtml = '';
    let identificadorModalReclameAqui = 'ReclameAqui';

    // Edição e Exclusão
    if (canEdit == "true") {
        reclame_aquisEdicaoExclusaoHtml = reclame_aquisEdicaoExclusaoHtml + `\
        <a onclick="formularioEditarReclameAqui(__id__)" data-toggle="tooltip"
            id="buttonEditarReclameAqui___id__" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
            <i class="fa fa-edit"></i>
        </a>
        `;
    }

    if (canDel == "true") {
        reclame_aquisEdicaoExclusaoHtml = reclame_aquisEdicaoExclusaoHtml + `\
        <a onclick="modalExcluirReclameAqui(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
            <i class="fa fa-remove"></i>
        </a>
        `;
    }
    

    dtReclameAquisButtons.push(
        {
            filename: filenameGenerator("Reclame Aqui"),
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
            filename: filenameGenerator("Reclame Aqui"),
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
                titulo = `Reclame Aqui`;
                // Personaliza a página do PDF
                widths = ['20%', '80%'];
                pdfTemplateIsolated(doc, titulo, 'A4', widths, [], '', 16, 14)
            }
        },
        {
            filename: filenameGenerator("Reclame Aqui"),
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
            filename: filenameGenerator("Reclame Aqui"),
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
                titulo = `Reclame Aqui`;
                // Personaliza a página Impressale
                printTemplateOmni(win, titulo);
            }
        }
    );

    dtReclameAquisColumnDefs.push(
        {
            render: function (data, type, row) 
            {
                return `\
                    <a href="<?=base_url('uploads/processos_atendimento_reclame_aqui')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/processos_atendimento_reclame_aqui')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${reclame_aquisEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
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


    var tabelaReclameAqui = $("#tabelaReclameAqui").DataTable({
        language: {
           loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
           loadingRecords: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b>',
           searchPlaceholder:  'Pesquisar',
           emptyTable:         "Nenhum documento a ser listado",
           info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
           infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
           zeroRecords:        "Nenhum resultado encontrado.",
           paginate: {
               first:          "Primeira",
               last:           "Última",
               next:           "Próxima",
               previous:       "Anterior"
           },
           infoFiltered:   "(filtrados de _MAX_ registros)",
        },
        order: [[ 0, "desc" ]],
        autoWidth: false,
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ProcessosAtendimento/ReclameAqui/getReclameAquis')?>",
            beforeSend: function() {
                showLoadingMessage();
            },
            success: function(response){
                tabelaReclameAqui.clear().draw();
                tabelaReclameAqui.rows.add(response.data).draw();
            },
            error: function(){
                alert('Erro ao buscar os dados. Tente novamente.')
                tabelaReclameAqui.clear().draw();
            },
        },
        dom: 'Bfrtip',
        buttons: dtReclameAquisButtons,
        columnDefs: dtReclameAquisColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
        }
    });

    function showLoadingMessage() {
        $('#tabelaReclameAqui > tbody').html(
            '<tr class="odd">' +
            '<td valign="top" colspan="10" class="dataTables_empty" style="align-items: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
            '</tr>'
        );
    }

    function reloadTable() {
        showLoadingMessage();
        tabelaReclameAqui.ajax.reload();
    }

    function formularioNovoReclameAqui()
    {
        // Carregando
        $('#buttonNovoReclameAqui')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#modalReclameAqui").load(
            "<?=site_url('ProcessosAtendimento/ReclameAqui/formularioReclameAqui')?>",
            function()
            {
                $('#buttonNovoReclameAqui')
                    .html('<?=lang('adicionar_reclame_aqui')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarReclameAqui(reclame_aquiId)
    {
        // Carregando
        $('#buttonEditarReclameAqui_'+reclame_aquiId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalReclameAqui").load(
            "<?=site_url('ProcessosAtendimento/ReclameAqui/formularioReclameAqui')?>/"+reclame_aquiId,
            function()
            {
                // Carregado
                $('#buttonEditarReclameAqui_'+reclame_aquiId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirReclameAqui(reclame_aquiId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalReclameAqui,
            reclame_aquiId,
            '<?=lang("confirmacao_exclusao_reclame_aqui")?>' // Texto modal
        );
    }

    function excluirReclameAqui()
    {
        let reclame_aquiId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalReclameAqui);

        // Carregando
        $('#btnExcluirReclameAqui')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta
        $.ajax({
            url: '<?=site_url("ProcessosAtendimento/ReclameAqui/excluirReclameAqui")?>/'+reclame_aquiId,
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

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalReclameAqui);

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
                $('#btnExcluirReclameAqui')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>