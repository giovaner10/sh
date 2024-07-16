<style>
    th.dt-center, td.dt-center { 
        text-align: center !important; 
    }
</style>

<h3><?=lang("key_account")?></h3>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('processos_atendimento')?> >
	<?=lang('key_account')?>
</div>

<hr>

<button class="btn btn-md btn-primary" id='buttonNovoKeyAccount' onclick="formularioNovoKeyAccount()"><?=lang("adicionar_key_account")?></button>

<div class="row">
    <div class="col-md-12">
        <div id="empresas" class="tab-pane fade in active" style="margin-top: 20px">
            <table class="table-responsive table-bordered table" id="tabelaKeyAccount">
                <thead>
                    <tr class="tableheader">
                        <th>ID</th>
                        <th><?=lang('titulo_key_account')?></th>
                        <th><?=lang("acoes")?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>   
        </div>
    </div>
</div>

<div id="modalKeyAccount"></div>

<script type="text/javascript">
    let canEdit = "<?= $this->auth->is_allowed_block('edi_key_account_processos_atendimento') ? "true" : "false"; ?>";
    let canDel = "<?= $this->auth->is_allowed_block('del_key_account_processos_atendimento') ? "true" : "false"; ?>";
    let dtKeyAccountsButtons = [];
    let dtKeyAccountsColumnDefs = [];
    let releasesEdicaoExclusaoHtml = '';
    let key_accountsEdicaoExclusaoHtml = '';
    let identificadorModalKeyAccount = 'KeyAccount';

    // Edição e Exclusão
    if (canEdit == "true") {
        key_accountsEdicaoExclusaoHtml = key_accountsEdicaoExclusaoHtml + `\
        <a onclick="formularioEditarKeyAccount(__id__)" data-toggle="tooltip"
            id="buttonEditarKeyAccount___id__" class="btn btn-sm btn-primary" title="<?=lang('editar')?>">
            <i class="fa fa-edit"></i>
        </a>
        `;
    }

    if (canDel == "true") {
        key_accountsEdicaoExclusaoHtml = key_accountsEdicaoExclusaoHtml + `\
        <a onclick="modalExcluirKeyAccount(__id__)" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('remover')?>">
            <i class="fa fa-remove"></i>
        </a>
        `;
    }
    

    dtKeyAccountsButtons.push(
        {
            filename: filenameGenerator("Key Account"),
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
            filename: filenameGenerator("Key Account"),
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
                titulo = `KeyAccount`;
                // Personaliza a página do PDF
                widths = ['20%', '80%'];
                pdfTemplateIsolated(doc, titulo, 'A4', widths, [], '', 16, 14)
            }
        },
        {
            filename: filenameGenerator("Key Account"),
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
            filename: filenameGenerator("Key Account"),
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
                titulo = `KeyAccount`;
                // Personaliza a página Impressale
                printTemplateOmni(win, titulo);
            }
        }
    );

    dtKeyAccountsColumnDefs.push(
        {
            render: function (data, type, row) 
            {
                return `\
                    <a href="<?=base_url('uploads/processos_atendimento_key_account')?>/${data.arquivo}" target="_blank" data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('visualizar')?>">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="<?=base_url('uploads/processos_atendimento_key_account')?>/${data.arquivo}" download data-toggle="tooltip" class="btn btn-sm btn-primary" title="<?=lang('baixar')?>">
                        <i class="fa fa-download"></i>
                    </a>
                    ${key_accountsEdicaoExclusaoHtml.replaceAll('__id__', data.id)}
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


    var tabelaKeyAccount = $("#tabelaKeyAccount").DataTable({
        language: {
           loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
           loadingRecords: '<i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b>',
           searchPlaceholder:  'Pesquisar',
           emptyTable:         "Nenhuma key account a ser listada",
           info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
           infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
           zeroRecords:        "Nenhum resultado encontrado.",
           paginate: {
               first:          "Primeira",
               last:           "Última",
               next:           "Próxima",
               previous:       "Anterior"
           },
           infoFiltered: "(filtrado de _MAX_ registros no total)"
        },
        order: [[ 0, "desc" ]],
        autoWidth: false,
        lengthMenu: [20],
        ajax: {
            url:  "<?=site_url('ProcessosAtendimento/KeyAccount/getKeyAccounts')?>",
            beforeSend: function() {
                showLoadingMessage();
            },
            success: function(response){
                tabelaKeyAccount.clear().draw();
                tabelaKeyAccount.rows.add(response.data).draw();
            },
            error: function(){
                alert('Erro ao buscar os dados. Tente novamente.')
                tabelaKeyAccount.clear().draw();
            },
        },
        dom: 'Bfrtip',
        buttons: dtKeyAccountsButtons,
        columnDefs: dtKeyAccountsColumnDefs,
        drawCallback: function( settings )
        {
            $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
        }
    });

    function showLoadingMessage() {
        $('#tabelaKeyAccount > tbody').html(
            '<tr class="odd">' +
            '<td valign="top" colspan="10" class="dataTables_empty" style="align-items: center;"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i>   <b>Processando...</b></td>' +
            '</tr>'
        );
    }

    function reloadTable() {
        showLoadingMessage();
        tabelaKeyAccount.ajax.reload();
    }

    function formularioNovoKeyAccount()
    {
        // Carregando
        $('#buttonNovoKeyAccount')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        // Modal
        $("#modalKeyAccount").load(
            "<?=site_url('ProcessosAtendimento/KeyAccount/formularioKeyAccount')?>",
            function()
            {
                $('#buttonNovoKeyAccount')
                    .html('<?=lang('adicionar_key_account')?>')
                    .attr('disabled', false);
            });
    }

    function formularioEditarKeyAccount(key_accountId)
    {
        // Carregando
        $('#buttonEditarKeyAccount_'+key_accountId)
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
        
        // Modal
        $("#modalKeyAccount").load(
            "<?=site_url('ProcessosAtendimento/KeyAccount/formularioKeyAccount')?>/"+key_accountId,
            function()
            {
                // Carregado
                $('#buttonEditarKeyAccount_'+key_accountId)
                    .html('<i class="fa fa-edit"></i>')
                    .attr('disabled', false);
            }
        );
    }

    function modalExcluirKeyAccount(key_accountId)
    {
        abrirModalConfirmarExclusaoBootstrap(
            identificadorModalKeyAccount,
            key_accountId,
            '<?=lang("confirmacao_exclusao_key_account")?>' // Texto modal
        );
    }

    function excluirKeyAccount()
    {
        let key_accountId = getModalConfirmarExclusaoBootstrapIdRegistro(identificadorModalKeyAccount);

        // Carregando
        $('#btnExcluirKeyAccount')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta
        $.ajax({
            url: '<?=site_url("ProcessosAtendimento/KeyAccount/excluirKeyAccount")?>/'+key_accountId,
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

                    fecharModalConfirmarExclusaoBootstrap(identificadorModalKeyAccount);

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
                $('#btnExcluirKeyAccount')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

</script>