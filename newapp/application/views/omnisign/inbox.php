<style>
    .destinatario-label {
        display: inline-flex;
        align-items: center;
        margin: 2px;
        background-color: #007bff;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }

    .destinatario-label .acao {
        margin-left: 5px;
        cursor: pointer;
        color: #ffdddd;
    }

    .destinatario-label .reenviar {
        color: #FFFF00; /* Amarelo para diferenciar o reenvio */
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/vendasDeSoftware', 'style.css') ?>">

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?=lang("assinatura_eletronica")?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none"><?= lang('inicio') ?></a>
    </h4>
</div>

<div class="row container-fluid">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link' href="<?= site_url('omnisigns/index') ?>">Meus Documentos</a>
                </li>
                <li>
                    <a class='menu-interno-link selected' href="<?= site_url('omnisigns/inbox') ?>">Caixa de Entrada</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-9 card-conteudo">
        <div class="row" style="margin-bottom:10px">
            <select id="select-quantidade-por-pagina-produtos" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
            <input class="form-control inputBusca" type="text" id="search-input-show-produtos" placeholder="Pesquisar" style="margin-top: 10px; float: right;">
        </div>
        <div class="row">
            <div id="myGrid" class="ag-theme-alpine my-grid" style="height: 460px; width:100%;"></div>
        </div>
    </div>
</div>

<script>
    var idFileOpen;

    // Colunas da tabela AG-Grid
    var columnDefs = [
        { headerName: "Nome do Documento", field: "nome" },
        { headerName: "Categoria", field: "categoria" },
        { headerName: "Data Cadastro", field: "data_cadastro" },
        {
            headerName: "Status",
            valueGetter: function(params) {
                switch (params.data.status) {
                    case '1':
                        return 'Assinado Parcialmente';
                    case '2':
                        return 'Assinado';
                    case '3':
                        return 'Rejeitado';
                    case '4':
                        return 'Cancelado';
                    default:
                        return 'Aguardando assinaturas';
                }
            }
        },
        {
            headerName: "Assinados",
            valueGetter: function(params) {
                var signed = params.data.signed ? params.data.signed : "0";
                var signature = params.data.signature ? params.data.signature : "0";
                return `${signed} / ${signature}`;
            }
        },
        {
            headerName: "Ações",
            pinned: 'right',
            cellRenderer: function(params) {
                return `
                    <a title="Documento" class="btn btn-sm btn-primary" target="_blank" href="${'../../'+params.data.arquivo}"><i class="fa fa-file-text-o"></i></a>
                    <button class="btn btn-sm btn-success btn-aceitar" data-id="${params.data.id}"><i class="fa fa-check"></i></button>
                    <button class="btn btn-sm btn-danger btn-rejeitar" data-id="${params.data.id}"><i class="fa fa-close"></i></button>
                `;
            }
        }
    ];

    // Configuração da AG-Grid
    var gridOptions = {
        columnDefs: columnDefs,
        getContextMenuItems: params => null,
        getRowNodeId: data => data.id,
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 100,
            minHeight: 100,
            filter: true,
            resizable: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                    },
                },
            ],
        },
        pagination: true,
        paginationPageSize: 10,
        onGridReady: function(event) {
            $.getJSON('getMyInbox', function(data) {
                event.api.setRowData(data);
            });
        }
    };

    // Espera o DOM carregar para inicializar a AG-Grid
    document.addEventListener('DOMContentLoaded', function () {
        var gridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(gridDiv, gridOptions);
    });

    /** Função aceita documento */
    $(document).on('click', 'button.btn-aceitar', function() {
        var button = $(this);
        var idFile = button.attr('data-id');

        // Verificação de segurança pos click
        if (button.attr('disabled') == 'disabled') return false;

        // Desabilita o botão e inicia efeito
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('../omnisignsSignature/aceitarDocumentoInbox', { idFile }, response => {
            if (response.status == true)
                removeRowTable(idFile);
            else
                button.removeAttr('disabled').html('<i class="fa fa-check"></i>');    

            alert(response.message)
        }, 'JSON').catch(e => {
            button.removeAttr('disabled').html('<i class="fa fa-check"></i>');
            alert('Não foi possível processar sua solicitação.');
        });
    });

    /** Função cancela documento */
    $(document).on('click', 'button.btn-rejeitar', function() {
        var button = $(this);
        var idFile = button.attr('data-id');

        // Verificação de segurança pos click
        if (button.attr('disabled') == 'disabled') return false;

        // Desabilita o botão e inicia efeito
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('../omnisignsSignature/rejeitaDocumentoInbox', { idFile }, response => {
            if (response.status == true)
                removeRowTable(idFile);
            else
                button.removeAttr('disabled').html('<i class="fa fa-close"></i>');    

            alert(response.message)
        }, 'JSON').catch(e => {
            button.removeAttr('disabled').html('<i class="fa fa-close"></i>');
            alert('Não foi possível processar sua solicitação.');
        });
    });

    /** Função remove linha da Ag-Grid */
    function removeRowTable(idRow)
    {
        // Encontra linha na Ag-Grid
        var linhaParaRemover = gridOptions.api.getRowNode(idRow);

        // Se a linha existir, remove
        if (linhaParaRemover) {
            gridOptions.api.updateRowData({ remove: [linhaParaRemover.data] });
        }
    }

    function validarEmail(email) {
        var regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        return regex.test(email);
    }
</script>